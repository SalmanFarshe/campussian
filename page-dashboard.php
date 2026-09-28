<?php
/**
 * Template Name: Dashboard (Portal)
 *
 * Front-end portal dashboard. Requires the (optional, recommended)
 * "Campussian Core" plugin for role-aware data; without it a friendly
 * locked message is shown instead of an error.
 *
 * @package Campussian
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Guard 1 - Campussian Core must be active.
 */
$core_active = function_exists( 'cmpsian_core_active' ) && cmpsian_core_active();

get_header();

if ( ! $core_active ) :
	?>
	<main id="primary" class="cmpsian-main cmpsian-dashboard-locked">
		<header class="cmpsian-page-hero" data-aos="fade-up">
			<div class="container">
				<h1 class="cmpsian-page-hero__title"><?php esc_html_e( 'Dashboard', 'campussian' ); ?></h1>
				<?php cmpsian_breadcrumb(); ?>
			</div>
		</header>
		<div class="container">
			<div class="cmpsian-dashboard-locked__card" data-aos="fade-up" role="alert">
				<svg viewBox="0 0 24 24" width="44" height="44" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2zm0-4h-2V7h2z"/></svg>
				<h2><?php esc_html_e( 'Portal unavailable', 'campussian' ); ?></h2>
				<p><?php esc_html_e( 'The dashboard requires the Campussian Core plugin to be installed and activated. Please contact the site administrator.', 'campussian' ); ?></p>
				<p><code>wp-content/plugins/campussian-core/</code></p>
				<a class="cmpsian-btn cmpsian-btn--teal" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php esc_html_e( 'Back to home', 'campussian' ); ?>
				</a>
			</div>
		</div>
	</main>
	<?php
	get_footer();
	return;
endif;

/**
 * Guard 2 - must be logged in.
 */
if ( ! is_user_logged_in() ) {
	wp_safe_redirect( cmpsian_get_login_url() );
	exit;
}

$current_user = wp_get_current_user();
$user_role    = function_exists( 'cmpsian_core_get_user_role' ) ? cmpsian_core_get_user_role( $current_user ) : '';
$role_label   = cmpsian_role_label( $user_role );

// Active portal tab (Overview / Edit Profile / Password) via ?tab=.
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only display flag.
$tab        = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'overview';
$pw_status  = isset( $_GET['pw'] ) ? sanitize_key( wp_unslash( $_GET['pw'] ) ) : '';
// phpcs:enable

if ( ! in_array( $tab, array( 'overview', 'profile', 'password' ), true ) ) {
	$tab = 'overview';
}

// Whether this user can reach the WP admin backend.
$can_wpadmin = in_array( $user_role, array( 'system_admin', 'school_admin', 'principal', 'teacher' ), true );

/**
 * Resolve the linked student record for this user (or false).
 *
 * Looks for: user meta _cmpsian_student_id, then a cmpsian_student post with
 * _cmpsian_student_user_id == user ID, then email match.
 *
 * @param WP_User $user User.
 * @return WP_Post|false
 */
function cmpsian_dash_get_student( $user ) {
	$linked_id = (int) get_user_meta( $user->ID, '_cmpsian_student_id', true );
	if ( $linked_id ) {
		$post = get_post( $linked_id );
		if ( $post && 'cmpsian_student' === $post->post_type ) {
			return $post;
		}
	}

	$found = get_posts(
		array(
			'post_type'      => 'cmpsian_student',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'   => '_cmpsian_student_user_id',
					'value' => $user->ID,
				),
				array(
					'key'   => '_cmpsian_student_email',
					'value' => $user->user_email,
				),
			),
		)
	);

	return $found ? get_post( $found[0] ) : false;
}

/**
 * Resolve the linked teacher record for this user (or false).
 *
 * @param WP_User $user User.
 * @return WP_Post|false
 */
function cmpsian_dash_get_teacher( $user ) {
	$linked_id = (int) get_user_meta( $user->ID, '_cmpsian_teacher_id', true );
	if ( $linked_id ) {
		$post = get_post( $linked_id );
		if ( $post && 'cmpsian_teacher' === $post->post_type ) {
			return $post;
		}
	}

	$found = get_posts(
		array(
			'post_type'      => 'cmpsian_teacher',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'title'          => $user->display_name,
		)
	);

	return $found ? get_post( $found[0] ) : false;
}

/**
 * Students linked to a guardian account (or empty array).
 *
 * @param WP_User $user Guardian user.
 * @return WP_Post[]
 */
function cmpsian_dash_get_guardian_students( $user ) {
	$ids = array_filter( array_map( 'absint', (array) get_user_meta( $user->ID, '_cmpsian_guardian_student_ids', true ) ) );

	if ( $ids ) {
		return get_posts(
			array(
				'post_type'        => 'cmpsian_student',
				'post_status'      => 'publish',
				'posts_per_page'   => 20,
				'post__in'         => $ids,
				'orderby'          => 'post__in',
			)
		);
	}

	// Fallback: students whose guardian email matches.
	return get_posts(
		array(
			'post_type'      => 'cmpsian_student',
			'post_status'    => 'publish',
			'posts_per_page' => 20,
			'meta_key'       => '_cmpsian_student_guardian_email',
			'meta_value'     => $user->user_email,
		)
	);
}

/* ----------------------------------------------------------------------
 * Render the portal
 * ------------------------------------------------------------------- */
?>
<main id="primary" class="cmpsian-main cmpsian-dashboard">

	<header class="cmpsian-page-hero" data-aos="fade-up">
		<div class="container">
			<h1 class="cmpsian-page-hero__title">
				<?php
				printf(
					/* translators: %s: role label. */
					esc_html__( '%s Portal', 'campussian' ),
					esc_html( $role_label ? $role_label : __( 'Member', 'campussian' ) )
				);
				?>
			</h1>
			<?php cmpsian_breadcrumb(); ?>
		</div>
	</header>

	<div class="container">
		<div class="cmpsian-portal" data-aos="fade-up">

			<!-- Sidebar -->
			<aside class="cmpsian-portal__sidebar">
				<div class="cmpsian-portal__user">
					<span class="cmpsian-portal__avatar" aria-hidden="true">
						<?php echo get_avatar( $current_user->ID, 80 ); ?>
					</span>
					<div class="cmpsian-portal__userinfo">
						<strong class="cmpsian-portal__name"><?php echo esc_html( $current_user->display_name ); ?></strong>
						<span class="cmpsian-portal__role"><?php echo esc_html( $role_label ); ?></span>
						<small class="cmpsian-portal__email"><?php echo esc_html( $current_user->user_email ); ?></small>
					</div>
				</div>

				<nav class="cmpsian-portal__nav" aria-label="<?php esc_attr_e( 'Portal navigation', 'campussian' ); ?>">
					<a class="cmpsian-portal__navlink<?php echo 'overview' === $tab ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( cmpsian_get_dashboard_url() ); ?>">
						<span class="cmpsian-portal__navic" aria-hidden="true">&#9632;</span>
						<?php esc_html_e( 'Overview', 'campussian' ); ?>
					</a>
					<a class="cmpsian-portal__navlink<?php echo 'profile' === $tab ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( add_query_arg( 'tab', 'profile', cmpsian_get_dashboard_url() ) ); ?>">
						<span class="cmpsian-portal__navic" aria-hidden="true">&#9786;</span>
						<?php esc_html_e( 'My Profile', 'campussian' ); ?>
					</a>
					<a class="cmpsian-portal__navlink<?php echo 'password' === $tab ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( add_query_arg( 'tab', 'password', cmpsian_get_dashboard_url() ) ); ?>">
						<span class="cmpsian-portal__navic" aria-hidden="true">&#128274;</span>
						<?php esc_html_e( 'Change Password', 'campussian' ); ?>
					</a>
				</nav>

				<?php if ( $can_wpadmin ) : ?>
					<div class="cmpsian-portal__navgroup"><?php esc_html_e( 'Admin Shortcuts', 'campussian' ); ?></div>
					<nav class="cmpsian-portal__nav">
						<a class="cmpsian-portal__navlink" href="<?php echo esc_url( admin_url( 'index.php' ) ); ?>">
							<span class="cmpsian-portal__navic" aria-hidden="true">&#9881;</span>
							<?php esc_html_e( 'WP Dashboard', 'campussian' ); ?>
						</a>
						<a class="cmpsian-portal__navlink" href="<?php echo esc_url( admin_url( 'edit.php?post_type=cmpsian_application' ) ); ?>">
							<span class="cmpsian-portal__navic" aria-hidden="true">&#9998;</span>
							<?php esc_html_e( 'Applications', 'campussian' ); ?>
						</a>
						<a class="cmpsian-portal__navlink" href="<?php echo esc_url( admin_url( 'edit.php?post_type=cmpsian_student' ) ); ?>">
							<span class="cmpsian-portal__navic" aria-hidden="true">&#9786;</span>
							<?php esc_html_e( 'Students', 'campussian' ); ?>
						</a>
						<a class="cmpsian-portal__navlink" href="<?php echo esc_url( admin_url( 'edit.php?post_type=cmpsian_notice' ) ); ?>">
							<span class="cmpsian-portal__navic" aria-hidden="true">&#128226;</span>
							<?php esc_html_e( 'Notices', 'campussian' ); ?>
						</a>
					</nav>
				<?php endif; ?>

				<a class="cmpsian-btn cmpsian-btn--orange cmpsian-btn--sm cmpsian-btn--block cmpsian-portal__logout"
					href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">
					<?php esc_html_e( 'Log out', 'campussian' ); ?>
				</a>
			</aside>

			<!-- Content -->
			<section class="cmpsian-portal__content">
			<?php if ( 'overview' === $tab ) : ?>
			<?php
			$student_post = cmpsian_dash_get_student( $current_user );
			$teacher_post = cmpsian_dash_get_teacher( $current_user );

			if ( 'student' === $user_role ) :
				if ( ! $student_post ) :
					?>
					<div class="cmpsian-portal-panel">
						<h3 class="cmpsian-portal-panel__title"><?php esc_html_e( 'No student record linked yet', 'campussian' ); ?></h3>
						<p><?php esc_html_e( 'Your account is not linked to a student record yet. Once your admission is approved, your profile appears here automatically.', 'campussian' ); ?></p>
						<a class="cmpsian-btn cmpsian-btn--teal cmpsian-btn--sm" href="<?php echo esc_url( home_url( '/admission/' ) ); ?>"><?php esc_html_e( 'Go to Admission', 'campussian' ); ?></a>
					</div>
					<?php
				else :
					$s_meta = array(
						'dob'      => get_post_meta( $student_post->ID, '_cmpsian_student_dob', true ),
						'grade'    => get_post_meta( $student_post->ID, '_cmpsian_student_grade', true ),
						'guardian' => get_post_meta( $student_post->ID, '_cmpsian_student_guardian', true ),
						'phone'    => get_post_meta( $student_post->ID, '_cmpsian_student_phone', true ),
						'email'    => get_post_meta( $student_post->ID, '_cmpsian_student_email', true ),
						'address'  => get_post_meta( $student_post->ID, '_cmpsian_student_address', true ),
						'roll'     => get_post_meta( $student_post->ID, '_cmpsian_student_roll', true ),
					);
					?>
					<div class="cmpsian-portal-panel">
						<h3 class="cmpsian-portal-panel__title"><?php esc_html_e( 'My Profile', 'campussian' ); ?></h3>
						<div class="table-responsive">
							<table class="table cmpsian-portal-table">
								<tbody>
									<tr><th scope="row"><?php esc_html_e( 'Name', 'campussian' ); ?></th><td><?php echo esc_html( $student_post->post_title ); ?></td></tr>
									<tr><th scope="row"><?php esc_html_e( 'Grade', 'campussian' ); ?></th><td><?php echo esc_html( $s_meta['grade'] ); ?></td></tr>
									<tr><th scope="row"><?php esc_html_e( 'Roll', 'campussian' ); ?></th><td><?php echo esc_html( $s_meta['roll'] ); ?></td></tr>
									<tr><th scope="row"><?php esc_html_e( 'Date of Birth', 'campussian' ); ?></th><td><?php echo esc_html( $s_meta['dob'] ); ?></td></tr>
									<tr><th scope="row"><?php esc_html_e( 'Guardian', 'campussian' ); ?></th><td><?php echo esc_html( $s_meta['guardian'] ); ?></td></tr>
									<tr><th scope="row"><?php esc_html_e( 'Phone', 'campussian' ); ?></th><td><?php echo esc_html( $s_meta['phone'] ); ?></td></tr>
									<tr><th scope="row"><?php esc_html_e( 'Email', 'campussian' ); ?></th><td><?php echo esc_html( $s_meta['email'] ); ?></td></tr>
									<tr><th scope="row"><?php esc_html_e( 'Address', 'campussian' ); ?></th><td><?php echo esc_html( $s_meta['address'] ); ?></td></tr>
								</tbody>
							</table>
						</div>
					</div>
					<?php
				endif;

			elseif ( 'teacher' === $user_role ) :
				if ( ! $teacher_post ) :
					?>
					<div class="cmpsian-portal-panel">
						<h3 class="cmpsian-portal-panel__title"><?php esc_html_e( 'Teacher portal', 'campussian' ); ?></h3>
						<p><?php esc_html_e( 'Welcome! No teacher profile is linked to this account yet.', 'campussian' ); ?></p>
					</div>
					<?php
				else :
					?>
					<div class="cmpsian-portal-panel">
						<h3 class="cmpsian-portal-panel__title"><?php esc_html_e( 'My Teacher Profile', 'campussian' ); ?></h3>
						<p><strong><?php echo esc_html( $teacher_post->post_title ); ?></strong></p>
						<div><?php echo wp_kses_post( wpautop( $teacher_post->post_content ) ); ?></div>
					</div>
					<?php
				endif;

			elseif ( 'guardian' === $user_role ) :
				$children = cmpsian_dash_get_guardian_students( $current_user );
				?>
				<div class="cmpsian-portal-panel">
					<h3 class="cmpsian-portal-panel__title"><?php esc_html_e( 'My Students', 'campussian' ); ?></h3>
					<?php if ( ! $children ) : ?>
						<p><?php esc_html_e( 'No students are linked to this guardian account yet.', 'campussian' ); ?></p>
					<?php else : ?>
						<ul class="cmpsian-guardian-list">
							<?php foreach ( $children as $child ) : ?>
								<li>
									<strong><?php echo esc_html( $child->post_title ); ?></strong>
									&mdash; <?php echo esc_html( get_post_meta( $child->ID, '_cmpsian_student_grade', true ) ); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<?php

			else :
				// Admin-style overview for system_admin / school_admin / principal.
				$students_count    = (int) wp_count_posts( 'cmpsian_student' )->publish;
				$applications_pend = count(
					get_posts(
						array(
							'post_type'      => 'cmpsian_application',
							'post_status'    => 'publish',
							'posts_per_page' => -1,
							'fields'         => 'ids',
							'meta_key'       => '_cmpsian_app_status',
							'meta_value'     => 'pending',
						)
					)
				);
				$notices_count = (int) wp_count_posts( 'cmpsian_notice' )->publish;
				?>
				<div class="cmpsian-portal__cards">
					<div class="cmpsian-portal-card"><span class="cmpsian-portal-card__value"><?php echo esc_html( number_format_i18n( $students_count ) ); ?></span><span class="cmpsian-portal-card__label"><?php esc_html_e( 'Students', 'campussian' ); ?></span></div>
					<div class="cmpsian-portal-card"><span class="cmpsian-portal-card__value"><?php echo esc_html( number_format_i18n( $applications_pend ) ); ?></span><span class="cmpsian-portal-card__label"><?php esc_html_e( 'Pending Applications', 'campussian' ); ?></span></div>
					<div class="cmpsian-portal-card"><span class="cmpsian-portal-card__value"><?php echo esc_html( number_format_i18n( $notices_count ) ); ?></span><span class="cmpsian-portal-card__label"><?php esc_html_e( 'Notices', 'campussian' ); ?></span></div>
				</div>
				<div class="cmpsian-portal-panel">
					<h3 class="cmpsian-portal-panel__title"><?php esc_html_e( 'Quick Actions', 'campussian' ); ?></h3>
					<p>
						<a class="cmpsian-btn cmpsian-btn--teal cmpsian-btn--sm" href="<?php echo esc_url( admin_url( 'edit.php?post_type=cmpsian_application' ) ); ?>"><?php esc_html_e( 'Review Applications', 'campussian' ); ?></a>
						<a class="cmpsian-btn cmpsian-btn--orange cmpsian-btn--sm" href="<?php echo esc_url( admin_url( 'edit.php?post_type=cmpsian_student' ) ); ?>"><?php esc_html_e( 'Manage Students', 'campussian' ); ?></a>
					</p>
				</div>
				<?php endif; ?>
			<?php endif; // overview tab. ?>

			<?php
			/* ---------------------------- My Profile tab --------------------------- */
			if ( 'profile' === $tab ) :
				?>
				<div class="cmpsian-portal-panel">
					<h3 class="cmpsian-portal-panel__title"><?php esc_html_e( 'My Account', 'campussian' ); ?></h3>
					<div class="table-responsive">
						<table class="table cmpsian-portal-table">
							<tbody>
								<tr><th scope="row"><?php esc_html_e( 'Name', 'campussian' ); ?></th><td><?php echo esc_html( $current_user->display_name ); ?></td></tr>
								<tr><th scope="row"><?php esc_html_e( 'Username', 'campussian' ); ?></th><td><?php echo esc_html( $current_user->user_login ); ?></td></tr>
								<tr><th scope="row"><?php esc_html_e( 'Email', 'campussian' ); ?></th><td><?php echo esc_html( $current_user->user_email ); ?></td></tr>
								<tr><th scope="row"><?php esc_html_e( 'Role', 'campussian' ); ?></th><td><?php echo esc_html( $role_label ); ?></td></tr>
								<tr><th scope="row"><?php esc_html_e( 'Registered', 'campussian' ); ?></th><td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $current_user->user_registered ) ) ); ?></td></tr>
							</tbody>
						</table>
					</div>
					<?php if ( $can_wpadmin ) : ?>
						<p class="cmpsian-portal-actions">
							<a class="cmpsian-btn cmpsian-btn--teal cmpsian-btn--sm" href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php esc_html_e( 'Edit in WP Profile', 'campussian' ); ?></a>
						</p>
					<?php endif; ?>
				</div>
				<?php
			endif;

			/* ------------------------- Change Password tab ------------------------- */
			if ( 'password' === $tab ) :
				$pw_notice = '';
				if ( 'success' === $pw_status ) {
					$pw_notice = array( 'ok', __( 'Password updated successfully.', 'campussian' ) );
				} elseif ( 'wrong' === $pw_status ) {
					$pw_notice = array( 'err', __( 'Your current password is incorrect.', 'campussian' ) );
				} elseif ( 'short' === $pw_status ) {
					$pw_notice = array( 'err', __( 'New password must be at least 6 characters.', 'campussian' ) );
				} elseif ( 'mismatch' === $pw_status ) {
					$pw_notice = array( 'err', __( 'The new passwords did not match.', 'campussian' ) );
				} elseif ( 'nonce' === $pw_status ) {
					$pw_notice = array( 'err', __( 'Your session expired. Please try again.', 'campussian' ) );
				}
				?>
				<div class="cmpsian-portal-panel cmpsian-portal-panel--narrow">
					<h3 class="cmpsian-portal-panel__title"><?php esc_html_e( 'Change Password', 'campussian' ); ?></h3>

					<?php if ( $pw_notice ) : ?>
						<div class="cmpsian-alert cmpsian-alert--<?php echo esc_attr( $pw_notice[0] ); ?>">
							<?php echo esc_html( $pw_notice[1] ); ?>
						</div>
					<?php endif; ?>

					<form method="post" action="<?php echo esc_url( cmpsian_get_dashboard_url() ); ?>">
						<?php wp_nonce_field( 'cmpsian_change_password', 'cmpsian_password_nonce' ); ?>
						<div class="mb-3">
							<label class="form-label" for="cmpsian-current-pass"><?php esc_html_e( 'Current Password', 'campussian' ); ?></label>
							<input type="password" class="form-control" id="cmpsian-current-pass" name="current_password" required />
						</div>
						<div class="mb-3">
							<label class="form-label" for="cmpsian-new-pass"><?php esc_html_e( 'New Password', 'campussian' ); ?></label>
							<input type="password" class="form-control" id="cmpsian-new-pass" name="new_password" minlength="6" required />
						</div>
						<div class="mb-3">
							<label class="form-label" for="cmpsian-confirm-pass"><?php esc_html_e( 'Confirm New Password', 'campussian' ); ?></label>
							<input type="password" class="form-control" id="cmpsian-confirm-pass" name="confirm_password" minlength="6" required />
						</div>
						<button type="submit" name="cmpsian_password_submit" value="1" class="cmpsian-btn cmpsian-btn--teal">
							<?php esc_html_e( 'Update Password', 'campussian' ); ?>
						</button>
					</form>
				</div>
				<?php
			endif;
			?>
			</section>

		</div>
	</div>
</main>

<style>
	.cmpsian-portal__avatar img { border-radius: 50%; display: block; }
	.cmpsian-dashboard-locked__card {
		max-width: 560px; margin: 0 auto 64px; text-align: center;
		background: var(--cmpsian-surface, #fff);
		border: 1px solid var(--cmpsian-border, #e4ebe9);
		border-radius: var(--cmpsian-radius, 16px);
		box-shadow: var(--cmpsian-shadow, 0 10px 30px rgba(4,58,52,.08));
		padding: 48px 32px; color: var(--cmpsian-text, #1f2a28);
	}
	.cmpsian-dashboard-locked__card svg { color: var(--cmpsian-orange, #ED8B31); margin-bottom: 12px; }
	.cmpsian-dashboard-locked__card code { font-size: .8rem; color: var(--cmpsian-text-muted, #5d6b68); }
	.cmpsian-guardian-list { padding-left: 0; list-style: none; }
</style>

<?php
get_footer();