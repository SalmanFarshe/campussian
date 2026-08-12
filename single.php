<?php
/**
 * The template for displaying all single posts.
 *
 * Also handles single "Notice" and "Event" custom-post-type views, printing
 * their date/PDF or date/venue meta.
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
?>

<main id="primary" class="cmpsian-main">

	<?php
	while ( have_posts() ) :
		the_post();

		$post_type = get_post_type();
		?>

		<header class="cmpsian-page-hero" data-aos="fade-up">
			<div class="container">
				<h1 class="cmpsian-page-hero__title"><?php the_title(); ?></h1>
				<?php cmpsian_breadcrumb(); ?>
			</div>
		</header>

		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-9">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'cmpsian-single' ); ?>>

						<?php // Notice meta: date + optional PDF preview button. ?>
						<?php if ( 'cmpsian_notice' === $post_type ) : ?>
							<?php $pdf = get_post_meta( get_the_ID(), '_cmpsian_notice_pdf', true ); ?>
							<div class="cmpsian-single__meta" data-aos="fade-up">
								<span class="cmpsian-badge cmpsian-badge--teal">
									<?php echo esc_html( cmpsian_notice_date() ); ?>
								</span>
								<?php if ( $pdf ) : ?>
									<a class="cmpsian-btn cmpsian-btn--outline cmpsian-btn--sm" href="<?php echo esc_url( $pdf ); ?>" target="_blank" rel="noopener noreferrer">
										<?php esc_html_e( 'Download PDF', 'campussian' ); ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php // Event meta: date/time + venue. ?>
						<?php if ( 'cmpsian_event' === $post_type ) : ?>
							<?php $venue = get_post_meta( get_the_ID(), '_cmpsian_event_venue', true ); ?>
							<div class="cmpsian-single__meta" data-aos="fade-up">
								<span class="cmpsian-badge cmpsian-badge--orange">
									<?php echo esc_html( cmpsian_event_datetime() ); ?>
								</span>
								<?php if ( $venue ) : ?>
									<span class="cmpsian-single__venue"><?php echo esc_html( $venue ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="cmpsian-single__media" data-aos="fade-up">
								<?php the_post_thumbnail( 'large' ); ?>
							</div>
						<?php endif; ?>

						<div class="cmpsian-single__content" data-aos="fade-up">
							<?php the_content(); ?>
						</div>

						<?php // Inline PDF preview for notices with an attached file. ?>
						<?php if ( 'cmpsian_notice' === $post_type && ! empty( $pdf ) ) : ?>
							<div class="cmpsian-pdf-embed" data-aos="fade-up">
								<object data="<?php echo esc_url( $pdf ); ?>" type="application/pdf" width="100%" height="640">
									<p>
										<?php esc_html_e( 'Your browser cannot display the PDF.', 'campussian' ); ?>
										<a href="<?php echo esc_url( $pdf ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Open it in a new tab.', 'campussian' ); ?></a>
									</p>
								</object>
							</div>
						<?php endif; ?>

						<footer class="cmpsian-single__footer">
							<?php
							the_tags( '<div class="cmpsian-single__tags">', '', '</div>' );

							wp_link_pages(
								array(
									'before' => '<div class="cmpsian-page-links">' . esc_html__( 'Pages:', 'campussian' ),
									'after'  => '</div>',
								)
							);
							?>
						</footer>

					</article>

					<nav class="cmpsian-post-nav" data-aos="fade-up">
						<div class="cmpsian-post-nav__prev"><?php previous_post_link( '%link', '&larr; %title' ); ?></div>
						<div class="cmpsian-post-nav__next"><?php next_post_link( '%link', '%title &rarr;' ); ?></div>
					</nav>

					<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</div>
			</div>
		</div>

		<?php
	endwhile;
	?>

</main>

<?php
get_footer();
