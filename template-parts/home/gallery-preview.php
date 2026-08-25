<?php
/**
 * Gallery preview.
 *
 * A compact preview strip of campus-life images. Pulls images from the
 * `cmpsian_gallery` custom post type (admin-managed) if images exist;
 * otherwise renders styled placeholder tiles so the section never looks
 * empty on a fresh install.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Pull up to 6 latest gallery images from the CPT.
$gallery_query = new WP_Query(
	array(
		'post_type'      => 'cmpsian_gallery',
		'posts_per_page' => 6,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);
?>
<section class="cmpsian-gallery-preview" id="gallery-preview">
	<div class="container">

		<div class="cmpsian-section__head text-center" data-aos="fade-up">
			<span class="cmpsian-section__eyebrow"><?php esc_html_e( 'Campus Life', 'campussian' ); ?></span>
			<h2 class="cmpsian-section__title"><?php esc_html_e( 'Moments from Our Campus', 'campussian' ); ?></h2>
		</div>

		<div class="cmpsian-gallery-grid" data-aos="fade-up">
	<?php if ( $gallery_query->have_posts() ) : ?>
		<?php $idx = 0; ?>
		<?php while ( $gallery_query->have_posts() ) : $gallery_query->the_post(); ?>
			<figure class="cmpsian-gallery-grid__item" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( ( $idx++ % 4 ) * 60 ); ?>">
						<img src="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>"
							alt="<?php esc_attr_e( 'Campus life photo', 'campussian' ); ?>"
							loading="lazy" />
					</figure>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<?php for ( $i = 0; $i < 6; $i++ ) : ?>
					<figure class="cmpsian-gallery-grid__item cmpsian-gallery-grid__item--placeholder" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 60 ); ?>">
						<span aria-hidden="true">
							<svg viewBox="0 0 24 24" width="40" height="40"><path fill="currentColor" d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2zM8.5 13.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5z"/></svg>
						</span>
					</figure>
				<?php endfor; ?>
			<?php endif; ?>
		</div>

		<?php if ( $gallery_query->have_posts() ) : ?>
			<div class="text-center" data-aos="fade-up">
<a class="cmpsian-btn cmpsian-btn--outline" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">
					<?php esc_html_e( 'View Full Gallery', 'campussian' ); ?>
				</a>
			</div>
		<?php endif; ?>

	</div>
</section>