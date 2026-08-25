<?php
/**
 * Template Name: Login
 *
 * Custom front-end login page for the Campussian portal. Styled with the
 * theme's design tokens + Bootstrap 5. Authentication is handled by
 * cmpsian_process_front_login() in inc/login.php (standard POST, no AJAX),
 * then Campussian Core routes the user by role.
 *
 * @package Campussian
 * @author  Salman Farshe
 * @since   1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Already signed in? Send them to their portal.
if ( is_user_logged_in() ) {
	wp_safe_redirect( cmpsian_get_dashboard_url() );
	exit;
}

get_header();

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display flag only.
$login_state = isset( $_GET['login'] ) ? sanitize_key( wp_unslash( $_GET['login'] ) ) : '';
// phpcs:enable

$error_message = '';
if ( 'failed' === $login_state ) {
	$error_message = __( 'Incorrect username or password. Please try again.', 'campussian' );
} elseif ( 'empty' === $login_state ) {
	$error_message = __( 'Please enter both your username and password.', 'campussian' );
}
?>

<main id="primary" class="cmpsian-main cmpsian-login">

	<header class="cmpsian-page-hero" data-aos="fade-up">
		<div class="container">
			<h1 class="cmpsian-page-hero__title"><?php esc_html_e( 'Portal Login', 'campussian' ); ?></h1>
			<?php cmpsian_breadcrumb(); ?>
		</div>
	</header>

	<div class="container">
		<div class="cmpsian-login__wrap" data-aos="fade-up">

			<div class="cmpsian-login__card">
				<div class="cmpsian-login__logo">
					<?php cmpsian_site_logo(); ?>
				</div>

				<h2 class="cmpsian-login__title"><?php esc_html_e( 'Sign in to your account', 'campussian' ); ?></h2>
				<p class="cmpsian-login__sub"><?php esc_html_e( 'Students, teachers, guardians and staff can sign in here.', 'campussian' ); ?></p>

				<?php if ( $error_message ) : ?>
					<div class="cmpsian-login__error" role="alert">
						<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2zm0-4h-2V7h2z"/></svg>
						<span><?php echo esc_html( $error_message ); ?></span>
					</div>
				<?php endif; ?>

				<form id="cmpsian-login-form" method="post"
					action="<?php echo esc_url( cmpsian_get_login_url() ); ?>"
					class="cmpsian-login__form" novalidate>

					<?php wp_nonce_field( 'cmpsian_front_login', 'cmpsian_login_nonce' ); ?>

					<div class="mb-3">
						<label class="form-label" for="cmpsian-login-user">
							<?php esc_html_e( 'Username or Email', 'campussian' ); ?>
						</label>
						<input type="text" class="form-control" id="cmpsian-login-user"
							name="log" autocomplete="username" required autofocus />
					</div>

					<div class="mb-3">
						<label class="form-label" for="cmpsian-login-pass">
							<?php esc_html_e( 'Password', 'campussian' ); ?>
						</label>
						<input type="password" class="form-control" id="cmpsian-login-pass"
							name="pwd" autocomplete="current-password" required />
					</div>

					<div class="form-check mb-3">
						<input class="form-check-input" type="checkbox" id="cmpsian-login-remember" name="rememberme" value="1" />
						<label class="form-check-label" for="cmpsian-login-remember">
							<?php esc_html_e( 'Remember me', 'campussian' ); ?>
						</label>
					</div>

					<button type="submit" name="cmpsian_login_submit" value="1"
						class="cmpsian-btn cmpsian-btn--teal cmpsian-btn--block">
						<?php esc_html_e( 'Login', 'campussian' ); ?>
					</button>
				</form>

				<p class="cmpsian-login__back">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						&larr; <?php esc_html_e( 'Back to home', 'campussian' ); ?>
					</a>
				</p>
			</div>

			<p class="cmpsian-login__note">
				<?php esc_html_e( 'Need an account? Contact the school office or apply through the admission form.', 'campussian' ); ?>
				<a href="<?php echo esc_url( home_url( '/admission/' ) ); ?>"><?php esc_html_e( 'Apply now', 'campussian' ); ?></a>
			</p>

		</div>
	</div>
</main>

<style>
	.cmpsian-login__wrap { max-width: 460px; margin: 0 auto 64px; }
	.cmpsian-login__card {
		background: var(--cmpsian-surface, #fff);
		border: 1px solid var(--cmpsian-border, #e4ebe9);
		border-radius: var(--cmpsian-radius, 16px);
		box-shadow: var(--cmpsian-shadow, 0 10px 30px rgba(4,58,52,.08));
		padding: 40px 36px;
		text-align: center;
	}
	.cmpsian-login__logo { margin-bottom: 18px; }
	.cmpsian-login__title { font-family: var(--cmpsian-font-head, inherit); font-size: 1.35rem; margin-bottom: 6px; }
	.cmpsian-login__sub { color: var(--cmpsian-text-muted, #5d6b68); font-size: .92rem; margin-bottom: 22px; }
	.cmpsian-login__card form { text-align: left; }
	.cmpsian-login__card .form-label { font-weight: 600; font-size: .88rem; }
	.cmpsian-login__error {
		display: flex; align-items: center; gap: 8px;
		background: #fdecea; color: #b3261e;
		border: 1px solid #f5c6c2; border-radius: var(--cmpsian-radius-sm, 10px);
		padding: 10px 14px; font-size: .88rem; margin-bottom: 18px; text-align: left;
	}
	.cmpsian-login__back { margin-top: 18px; font-size: .88rem; }
	.cmpsian-login__back a { color: var(--cmpsian-teal, #057B6D); text-decoration: none; }
	.cmpsian-login__note { text-align: center; color: var(--cmpsian-text-muted, #5d6b68); font-size: .85rem; }
	.cmpsian-login__note a { color: var(--cmpsian-green, #8DAD42); font-weight: 600; }
</style>

<?php
get_footer();
