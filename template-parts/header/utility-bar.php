<?php
/**
 * Top utility bar.
 *
 * Contact info, social links and the Light/Dark mode toggle. Shown above the
 * main header on all pages.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone   = cmpsian_get_option( 'cmpsian_phone' );
$email   = cmpsian_get_option( 'cmpsian_email' );
$address = cmpsian_get_option( 'cmpsian_address' );
?>
<div class="cmpsian-utility-bar">
	<div class="container">
		<div class="cmpsian-utility-bar__inner">

			<div class="cmpsian-utility-bar__contact">
				<?php if ( $phone ) : ?>
					<a class="cmpsian-utility-item" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
						<span class="cmpsian-utility-item__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" width="14" height="14"><path fill="currentColor" d="M6.6 10.8a15.5 15.5 0 006.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 013 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.4 0 .7-.2 1l-2.3 2.2z"/></svg>
						</span>
						<span><?php echo esc_html( $phone ); ?></span>
					</a>
				<?php endif; ?>

				<?php if ( $email ) : ?>
					<a class="cmpsian-utility-item" href="mailto:<?php echo esc_attr( $email ); ?>">
						<span class="cmpsian-utility-item__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" width="14" height="14"><path fill="currentColor" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm8 7L4 6.5V6l8 5 8-5v.5L12 11z"/></svg>
						</span>
						<span><?php echo esc_html( $email ); ?></span>
					</a>
				<?php endif; ?>

				<?php if ( $address ) : ?>
					<span class="cmpsian-utility-item cmpsian-utility-item--address">
						<span class="cmpsian-utility-item__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" width="14" height="14"><path fill="currentColor" d="M12 2a7 7 0 00-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1112 6a2.5 2.5 0 010 5.5z"/></svg>
						</span>
						<span><?php echo esc_html( $address ); ?></span>
					</span>
				<?php endif; ?>
			</div>

			<div class="cmpsian-utility-bar__actions">
				<?php cmpsian_social_links( 'cmpsian-socials--bar' ); ?>
				<?php cmpsian_dark_mode_toggle(); ?>
			</div>

		</div>
	</div>
</div>
