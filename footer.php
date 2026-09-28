<?php
/**
 * The footer for the theme.
 *
 * Closes #content, prints the footer widget columns, contact block and the
 * copyright bar, then closes the document.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone    = cmpsian_get_option( 'cmpsian_phone' );
$email    = cmpsian_get_option( 'cmpsian_email' );
$address  = cmpsian_get_option( 'cmpsian_address' );
$about    = cmpsian_get_option( 'cmpsian_footer_about' );
$has_cols = is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' )
	|| is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' );
?>
	</div><!-- #content -->

	<footer id="colophon" class="cmpsian-footer" data-aos="fade-up">
		<div class="container">

			<div class="row cmpsian-footer__top gy-4">

				<div class="col-lg-4 col-md-6">
					<div class="cmpsian-footer__brand">
						<?php cmpsian_site_logo(); ?>
					</div>
					<?php if ( $about ) : ?>
						<p class="cmpsian-footer__about"><?php echo esc_html( $about ); ?></p>
					<?php endif; ?>
					<?php cmpsian_social_links( 'cmpsian-socials--footer' ); ?>
				</div>

				<?php if ( $has_cols ) : ?>
					<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
						<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
							<div class="col-lg-2 col-md-6 cmpsian-footer__col">
								<?php dynamic_sidebar( 'footer-' . $i ); ?>
							</div>
						<?php endif; ?>
					<?php endfor; ?>
				<?php else : ?>
					<div class="col-lg-3 col-md-6 cmpsian-footer__col">
						<h4 class="cmpsian-footer-title"><?php esc_html_e( 'Quick Links', 'campussian' ); ?></h4>
						<?php
						if ( has_nav_menu( 'footer' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'footer',
									'container'      => false,
									'menu_class'     => 'cmpsian-footer__menu',
									'depth'          => 1,
								)
							);
						} else {
							wp_list_pages(
								array(
									'title_li' => '',
									'depth'    => 1,
								)
							);
						}
						?>
					</div>
				<?php endif; ?>

				<div class="col-lg-3 col-md-6 cmpsian-footer__col">
					<h4 class="cmpsian-footer-title"><?php esc_html_e( 'Get in Touch', 'campussian' ); ?></h4>
					<ul class="cmpsian-footer__contact">
						<?php if ( $address ) : ?>
							<li><?php echo esc_html( $address ); ?></li>
						<?php endif; ?>
						<?php if ( $phone ) : ?>
							<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
						<?php endif; ?>
						<?php if ( $email ) : ?>
							<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>

			</div>

			<div class="cmpsian-footer__bottom">
				<p class="cmpsian-footer__copyright"><?php echo wp_kses_post( cmpsian_get_copyright() ); ?></p>
				<p class="cmpsian-footer__credit">
					<?php
					printf(
						/* translators: %s: site name. */
						esc_html__( 'Powered by %s', 'campussian' ),
						esc_html( get_bloginfo( 'name' ) )
					);
					?>
				</p>
			</div>

		</div>
	</footer>

</div><!-- #page -->

<button type="button" class="cmpsian-back-to-top" id="cmpsianBackToTop" aria-label="<?php esc_attr_e( 'Back to top', 'campussian' ); ?>">
	<svg viewBox="0 0 24 24" width="20" height="20"><path fill="currentColor" d="M12 8l-6 6 1.4 1.4L12 10.8l4.6 4.6L18 14z"/></svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
