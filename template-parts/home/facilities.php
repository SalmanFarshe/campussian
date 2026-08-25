<?php
/**
 * Homepage Facilities Section — Science Lab, Library, IT Lab Grid.
 *
 * Pulls facilities from the cmpsian_facility custom post type.
 * Section visibility controlled via Customizer: cmpsian_sections_enable_facilities.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$enable = cmpsian_get_option( 'cmpsian_sections_enable_facilities' );
if ( ! $enable ) {
	return;
}

$facilities = new WP_Query(
	array(
		'post_type'      => 'cmpsian_facility',
		'posts_per_page' => 6,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

if ( ! $facilities->have_posts() ) {
	return;
}
?>
<section class="cmpsian-facilities" id="facilities">
	<div class="container">
		<div class="section-header">
			<h2 class="cmpsian-section-title">
				<?php echo esc_html( cmpsian_get_option( 'cmpsian_facilities_title' ) ); ?>
			</h2>
		</div>

		<div class="cmpsian-facilities__grid">
			<?php
			while ( $facilities->have_posts() ) {
				$facilities->the_post();
				$image_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
				?>
				<div class="cmpsian-facility__card" data-aos="fade-up">
					<?php if ( $image_url ) : ?>
						<div class="cmpsian-facility__image"
							 style="background-image: url(<?php echo esc_url( $image_url ); ?>);">
							<div class="cmpsian-facility__overlay"></div>
						</div>
					<?php endif; ?>
					<div class="cmpsian-facility__content">
						<h3 class="cmpsian-facility__title">
							<?php the_title(); ?>
						</h3>
						<p class="cmpsian-facility__description">
							<?php echo esc_html( get_the_excerpt() ); ?>
						</p>
					</div>
				</div>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>