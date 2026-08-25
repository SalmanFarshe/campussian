<?php
/**
 * Main navigation.
 *
 * The primary Bootstrap 5 navbar with the school logo and the "primary" menu.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="navbar navbar-expand-lg cmpsian-navbar" aria-label="<?php esc_attr_e( 'Primary navigation', 'campussian' ); ?>">
	<div class="container">

		<div class="cmpsian-navbar__brand">
			<?php cmpsian_site_logo(); ?>
		</div>

		<button class="navbar-toggler cmpsian-navbar__toggler" type="button"
			data-bs-toggle="collapse" data-bs-target="#cmpsianPrimaryNav"
			aria-controls="cmpsianPrimaryNav" aria-expanded="false"
			aria-label="<?php esc_attr_e( 'Toggle navigation', 'campussian' ); ?>">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse cmpsian-navbar__collapse" id="cmpsianPrimaryNav">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0 cmpsian-menu',
					'depth'          => 2,
					'fallback_cb'    => 'cmpsian_primary_menu_fallback',
				)
			);
			?>
			<div class="cmpsian-navbar__account ms-lg-3">
				<?php cmpsian_account_menu(); ?>
			</div>
		</div>
	</div>
</nav>
