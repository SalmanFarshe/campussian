<?php
/**
 * Homepage hero banner.
 *
 * Headline, subtext and the two call-to-action buttons ("Apply Now" / "Explore").
 * Background image is optional and comes from the Customizer.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title    = cmpsian_get_option( 'cmpsian_hero_title' );
$subtitle = cmpsian_get_option( 'cmpsian_hero_subtitle' );
$image    = cmpsian_get_option( 'cmpsian_hero_image' );
$btn1     = cmpsian_get_option( 'cmpsian_hero_btn1_text' );
$btn1_url = cmpsian_get_option( 'cmpsian_hero_btn1_url' );
$btn2     = cmpsian_get_option( 'cmpsian_hero_btn2_text' );
$btn2_url = cmpsian_get_option( 'cmpsian_hero_btn2_url' );

$style = $image ? ' style="background-image:linear-gradient(rgba(4,58,52,.82),rgba(4,58,52,.72)),url(' . esc_url( $image ) . ');"' : '';
?>
<section class="cmpsian-hero<?php echo $image ? ' cmpsian-hero--image' : ''; ?>"<?php echo $style; // phpcs:ignore -- URL escaped above. ?>>
	<div class="container">
		<div class="cmpsian-hero__inner">

			<div class="cmpsian-hero__content" data-aos="fade-up">
				<span class="cmpsian-hero__eyebrow" data-aos="fade-up" data-aos-delay="50">
					<?php esc_html_e( 'Welcome to', 'campussian' ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
				</span>

				<?php if ( $title ) : ?>
					<h1 class="cmpsian-hero__title" data-aos="fade-up" data-aos-delay="100">
						<?php echo esc_html( $title ); ?>
					</h1>
				<?php endif; ?>

				<?php if ( $subtitle ) : ?>
					<p class="cmpsian-hero__subtitle" data-aos="fade-up" data-aos-delay="150">
						<?php echo esc_html( $subtitle ); ?>
					</p>
				<?php endif; ?>

				<div class="cmpsian-hero__actions" data-aos="fade-up" data-aos-delay="200">
					<?php if ( $btn1 ) : ?>
						<a class="cmpsian-btn cmpsian-btn--orange" href="<?php echo esc_url( $btn1_url ); ?>">
							<?php echo esc_html( $btn1 ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $btn2 ) : ?>
						<a class="cmpsian-btn cmpsian-btn--ghost" href="<?php echo esc_url( $btn2_url ); ?>">
							<?php echo esc_html( $btn2 ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>

	<div class="cmpsian-hero__wave" aria-hidden="true">
		<svg viewBox="0 0 1440 90" preserveAspectRatio="none"><path fill="currentColor" d="M0 60l60-8c60-8 180-24 300-24s240 16 360 24 240 8 360-4 240-36 300-48l60-12v100H0z"/></svg>
	</div>
</section>
