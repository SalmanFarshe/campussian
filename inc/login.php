<?php
/**
 * Login system for the Campussian theme.
 *
 * Front-end login handling (used by page-login.php), portal URL helpers and
 * the dynamic Login / Dashboard button rendered in the header navigation.
 *
 * Requires the "campussian-core" plugin for role routing; degrades gracefully
 * when the plugin is missing.
 *
 * @package Campussian
 * @author  Salman Farshe
 * @since   1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ----------------------------------------------------------------------
 * URL helpers
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'cmpsian_get_login_url' ) ) {
	/**
	 * URL of the custom login page (falls back to wp-login.php).
	 *
	 * @since 1.1.0
	 * @return string
	 */
	function cmpsian_get_login_url() {
		if ( function_exists( 'cmpsian_core_get_login_url' ) ) {
			return cmpsian_core_get_login_url();
		}
		$page = get_page_by_path( 'login' );
		return $page ? get_permalink( $page ) : wp_login_url();
	}
}

if ( ! function_exists( 'cmpsian_get_dashboard_url' ) ) {
	/**
	 * URL of the portal dashboard page (falls back to home).
	 *
	 * @since 1.1.0
	 * @return string
	 */
	function cmpsian_get_dashboard_url() {
		if ( function_exists( 'cmpsian_core_get_dashboard_url' ) ) {
			return cmpsian_core_get_dashboard_url();
		}
		$page = get_page_by_path( 'dashboard' );
		return $page ? get_permalink( $page ) : home_url( '/' );
	}
}

if ( ! function_exists( 'cmpsian_role_label' ) ) {
	/**
	 * Human-readable label for a Campussian role slug.
	 *
	 * @since 1.1.0
	 * @param string $role Role slug.
	 * @return string
	 */
	function cmpsian_role_label( $role ) {
		$labels = array(
			'system_admin' => __( 'System Admin', 'campussian' ),
			'school_admin' => __( 'School Admin', 'campussian' ),
			'principal'    => __( 'Principal', 'campussian' ),
			'teacher'      => __( 'Teacher', 'campussian' ),
			'guardian'     => __( 'Guardian', 'campussian' ),
			'student'      => __( 'Student', 'campussian' ),
		);
		return isset( $labels[ $role ] ) ? $labels[ $role ] : '';
	}
}

/* ----------------------------------------------------------------------
 * Front-end login processing
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'cmpsian_process_front_login' ) ) {
	/**
	 * Handle the login form POST submitted from page-login.php.
	 *
	 * Uses wp_signon() then routes by role (Campussian Core filter applies on
	 * top of this via the login_redirect filter inside wp_signon flow).
	 *
	 * @since 1.1.0
	 * @return void
	 */
	function cmpsian_process_front_login() {
		if ( ! is_page_template( 'page-login.php' ) ) {
			return;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only display flag.
		$failed = isset( $_GET['login'] ) && 'failed' === sanitize_key( wp_unslash( $_GET['login'] ) );
		// phpcs:enable

		if ( empty( $_POST['cmpsian_login_submit'] ) ) {
			return; // Nothing submitted; template renders the form (+ error banner if flagged).
		}

		if ( ! isset( $_POST['cmpsian_login_nonce'] )
			|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmpsian_login_nonce'] ) ), 'cmpsian_front_login' ) ) {
			wp_safe_redirect( add_query_arg( 'login', 'failed', cmpsian_get_login_url() ) );
			exit;
		}

		$log      = isset( $_POST['log'] ) ? sanitize_user( wp_unslash( $_POST['log'] ) ) : '';
		$pwd      = isset( $_POST['pwd'] ) ? (string) wp_unslash( $_POST['pwd'] ) : '';
		$remember = ! empty( $_POST['rememberme'] );

		if ( '' === $log || '' === $pwd ) {
			wp_safe_redirect( add_query_arg( 'login', 'empty', cmpsian_get_login_url() ) );
			exit;
		}

		$credentials = array(
			'user_login'    => $log,
			'user_password' => $pwd,
			'remember'      => $remember,
		);

		$user = wp_signon( $credentials, is_ssl() );

		if ( is_wp_error( $user ) ) {
			wp_safe_redirect( add_query_arg( 'login', 'failed', cmpsian_get_login_url() ) );
			exit;
		}

		// Campussian Core decides admin vs dashboard; fallback to home.
		if ( function_exists( 'cmpsian_core_active' ) && cmpsian_core_active() ) {
			wp_safe_redirect( cmpsian_core_get_dashboard_url() );
		} else {
			wp_safe_redirect( home_url( '/' ) );
		}
		exit;
	}
	add_action( 'template_redirect', 'cmpsian_process_front_login', 1 );
}

/* ----------------------------------------------------------------------
 * Front-end change-password handler (used by the portal dashboard)
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'cmpsian_process_password_change' ) ) {
	/**
	 * Handle the "Change Password" form submitted from page-dashboard.php.
	 *
	 * Validates the current password, checks the new one, updates it and
	 * re-establishes the auth cookie so the user stays signed in.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	function cmpsian_process_password_change() {
		if ( ! is_user_logged_in() ) {
			return;
		}
		if ( ! is_page_template( 'page-dashboard.php' ) ) {
			return;
		}
		if ( empty( $_POST['cmpsian_password_submit'] ) ) {
			return;
		}

		if ( ! isset( $_POST['cmpsian_password_nonce'] )
			|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmpsian_password_nonce'] ) ), 'cmpsian_change_password' ) ) {
			wp_safe_redirect( add_query_arg( array( 'tab' => 'password', 'pw' => 'nonce' ), cmpsian_get_dashboard_url() ) );
			exit;
		}

		$current = isset( $_POST['current_password'] ) ? (string) wp_unslash( $_POST['current_password'] ) : '';
		$new     = isset( $_POST['new_password'] ) ? (string) wp_unslash( $_POST['new_password'] ) : '';
		$confirm = isset( $_POST['confirm_password'] ) ? (string) wp_unslash( $_POST['confirm_password'] ) : '';
		$user    = wp_get_current_user();
		$base    = add_query_arg( 'tab', 'password', cmpsian_get_dashboard_url() );

		if ( ! wp_check_password( $current, $user->user_pass, $user->ID ) ) {
			wp_safe_redirect( add_query_arg( 'pw', 'wrong', $base ) );
			exit;
		}
		if ( strlen( $new ) < 6 ) {
			wp_safe_redirect( add_query_arg( 'pw', 'short', $base ) );
			exit;
		}
		if ( $new !== $confirm ) {
			wp_safe_redirect( add_query_arg( 'pw', 'mismatch', $base ) );
			exit;
		}

		wp_set_password( $new, $user->ID );
		wp_set_auth_cookie( $user->ID, true, is_ssl() );
		wp_safe_redirect( add_query_arg( 'pw', 'success', $base ) );
		exit;
	}
	add_action( 'template_redirect', 'cmpsian_process_password_change', 2 );
}

/* ----------------------------------------------------------------------
 * Header account menu (Login / Dashboard / Logout)
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'cmpsian_account_menu' ) ) {
	/**
	 * Render the dynamic account item in the primary navigation.
	 *
	 * Logged out -> a plain "Login" nav link.
	 * Logged in  -> a Bootstrap dropdown menu link (user icon + chevron)
	 *               containing Dashboard, My Profile and Log out.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	function cmpsian_account_menu() {
		$user_icon = '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4 0-9 2-9 6v2h18v-2c0-4-5-6-9-6z"/></svg>';

		if ( ! is_user_logged_in() ) {
			printf(
				'<ul class="navbar-nav cmpsian-account-nav ms-lg-2"><li class="nav-item"><a class="cmpsian-account-login" href="%1$s">%3$s %2$s</a></li></ul>',
				esc_url( cmpsian_get_login_url() ),
				esc_html__( 'Login', 'campussian' ),
				$user_icon // phpcs:ignore WordPress.Security.EscapeOutput -- internal SVG.
			);
			return;
		}

		$current_user = wp_get_current_user();
		$role         = function_exists( 'cmpsian_core_get_user_role' ) ? cmpsian_core_get_user_role( $current_user ) : '';
		$label        = cmpsian_role_label( $role );
		$display      = $current_user->display_name;

		// Everyone lands on the portal Dashboard; staff also get a WP Admin link.
		$portal_only = in_array( $role, array( 'student', 'guardian' ), true );
		$dash_url    = cmpsian_get_dashboard_url();
		$can_wpadmin = ! $portal_only
			&& function_exists( 'cmpsian_core_active' )
			&& cmpsian_core_active();
		?>
		<ul class="navbar-nav cmpsian-account-nav ms-lg-auto">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle cmpsian-account-link" href="#"
					id="cmpsianAccountMenu" role="button"
					data-bs-toggle="dropdown" data-bs-display="static"
					aria-expanded="false">
					<?php echo $user_icon; // phpcs:ignore WordPress.Security.EscapeOutput -- internal SVG. ?>
					<span class="cmpsian-account-label"><?php echo esc_html( $display ); ?></span>
				</a>
				<ul class="dropdown-menu dropdown-menu-end cmpsian-account-dropdown" aria-labelledby="cmpsianAccountMenu">
					<li><span class="dropdown-item-text cmpsian-account-name"><?php echo esc_html( $display ); ?></span></li>
					<?php if ( $label ) : ?>
						<li><span class="dropdown-item-text cmpsian-account-role"><?php echo esc_html( $label ); ?></span></li>
					<?php endif; ?>
					<li><hr class="dropdown-divider" /></li>
					<li>
						<a class="dropdown-item" href="<?php echo esc_url( $dash_url ); ?>">
							<?php esc_html_e( 'Dashboard', 'campussian' ); ?>
						</a>
					</li>
					<?php if ( $can_wpadmin ) : ?>
						<li>
							<a class="dropdown-item" href="<?php echo esc_url( admin_url( 'index.php' ) ); ?>">
								<?php esc_html_e( 'WP Admin', 'campussian' ); ?>
							</a>
						</li>
					<?php endif; ?>
					<li>
						<a class="dropdown-item" href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>">
							<?php esc_html_e( 'My Profile', 'campussian' ); ?>
						</a>
					</li>
					<li><hr class="dropdown-divider" /></li>
					<li>
						<a class="dropdown-item cmpsian-account-logout" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">
							<?php esc_html_e( 'Log out', 'campussian' ); ?>
						</a>
					</li>
				</ul>
			</li>
		</ul>
		<?php
	}
}