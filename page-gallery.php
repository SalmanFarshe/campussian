<?php
/**
 * Template Name: Gallery
 *
 * Campus-life image grid with a lightweight, dependency-free lightbox (handled
 * in campussian.js). Images are read from the page's attached media; if none
 * are attached, styled placeholder tiles are shown.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Gather all images attached to this page.
$attachments = get_attached_media( 'image', get_the_ID() );
?>

<main id="primary" class="cmpsian-main cmpsian-gallery">

	<header class="cmpsian-page-hero" data-aos="fade-up">
		<div class="container">
			<h1 class="cmpsian-page-hero__title"><?php the_title(); ?></h1>
			<?php cmpsian_breadcrumb(); ?>
		</div>
	</header>

	<div class="container">

		<?php
		while ( have_posts() ) :
			the_post();
			if ( trim( get_the_content() ) ) :
				?>
				<div class="cmpsian-gallery__intro" data-aos="fade-up"><?php the_content(); ?></div>
				<?php
			endif;
		endwhile;
		?>

		<?php if ( ! empty( $attachments ) ) : ?>
			<div class="cmpsian-masonry" data-cmpsian-lightbox>
				<?php
				$i = 0;
				foreach ( $attachments as $att ) :
					$full  = wp_get_attachment_image_url( $att->ID, 'full' );
					$thumb = wp_get_attachment_image( $att->ID, 'cmpsian-card', false, array( 'loading' => 'lazy' ) );
					$alt   = get_post_meta( $att->ID, '_wp_attachment_image_alt', true );
					?>
					<figure class="cmpsian-masonry__item" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( ( $i % 4 ) * 60 ); ?>">
						<a href="<?php echo esc_url( $full ); ?>" class="cmpsian-masonry__link"
							data-caption="<?php echo esc_attr( $alt ? $alt : get_the_title( $att->ID ) ); ?>">
							<?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image returns safe markup. ?>
							<span class="cmpsian-masonry__zoom" aria-hidden="true">
								<svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M15.5 14h-.8l-.3-.3a6.5 6.5 0 10-.7.7l.3.3v.8l5 5 1.5-1.5-5-5zm-6 0A4.5 4.5 0 1114 9.5 4.5 4.5 0 019.5 14zm.5-7v2H8v2H6V9H4V7h2V5h2v2z"/></svg>
							</span>
						</a>
					</figure>
					<?php
					$i++;
				endforeach;
				?>
			</div>
		<?php else : ?>
			<div class="cmpsian-masonry">
				<?php for ( $i = 0; $i < 9; $i++ ) : ?>
					<figure class="cmpsian-masonry__item cmpsian-masonry__item--placeholder" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( ( $i % 4 ) * 60 ); ?>">
						<span aria-hidden="true">
							<svg viewBox="0 0 24 24" width="40" height="40"><path fill="currentColor" d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2zM8.5 13.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5z"/></svg>
						</span>
					</figure>
				<?php endfor; ?>
			</div>
			<p class="cmpsian-gallery__hint text-center">
				<?php esc_html_e( 'Tip: attach images to this page in the Media panel to populate the gallery.', 'campussian' ); ?>
			</p>
		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
