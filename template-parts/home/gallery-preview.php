<?php
/**
 * Gallery preview.
 *
 * A compact preview strip of campus-life images. Pulls attachments from a page
 * titled "Gallery" if one exists; otherwise renders styled placeholder tiles so
 * the section never looks empty on a fresh install.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gallery_page = get_page_by_path( 'gallery' );
$gallery_url  = $gallery_page ? get_permalink( $gallery_page ) : '';

// Attempt to gather up to 6 images from the gallery page.
$images = array();
if ( $gallery_page ) {
	$attachments = get_attached_media( 'image', $gallery_page->ID );
	$attachments = array_slice( $attachments, 0, 6 );
	foreach ( $attachments as $att ) {
		$images[] = wp_get_attachment_image_url( $att->ID, 'cmpsian-card' );
	}
}
?>
<section class="cmpsian-gallery-preview" id="gallery-preview">
	<div class="container">

		<div class="cmpsian-section__head text-center" data-aos="fade-up">
			<span class="cmpsian-section__eyebrow"><?php esc_html_e( 'Campus Life', 'campussian' ); ?></span>
			<h2 class="cmpsian-section__title"><?php esc_html_e( 'Moments from Our Campus', 'campussian' ); ?></h2>
		</div>

		<div class="cmpsian-gallery-grid" data-aos="fade-up">
			<?php if ( ! empty( $images ) ) : ?>
				<?php foreach ( $images as $i => $src ) : ?>
					<figure class="cmpsian-gallery-grid__item" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 60 ); ?>">
						<img src="<?php echo esc_url( $src ); ?>" alt="<?php esc_attr_e( 'Campus life photo', 'campussian' ); ?>" loading="lazy" />
					</figure>
				<?php endforeach; ?>
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

		<?php if ( $gallery_url ) : ?>
			<div class="text-center" data-aos="fade-up">
				<a class="cmpsian-btn cmpsian-btn--outline" href="<?php echo esc_url( $gallery_url ); ?>">
					<?php esc_html_e( 'View Full Gallery', 'campussian' ); ?>
				</a>
			</div>
		<?php endif; ?>

	</div>
</section>
