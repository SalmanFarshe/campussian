<?php
/**
 * Template Name: Full Width
 *
 * A one-column, full-width page template with no sidebar, for landing pages
 * and wide content (grids, banners, embeds).
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

<main id="primary" class="cmpsian-main cmpsian-main--full">

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<header class="cmpsian-page-hero" data-aos="fade-up">
			<div class="container">
				<h1 class="cmpsian-page-hero__title"><?php the_title(); ?></h1>
				<?php cmpsian_breadcrumb(); ?>
			</div>
		</header>

		<div class="cmpsian-container-fluid">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'cmpsian-page cmpsian-page--full' ); ?>>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="cmpsian-page__media" data-aos="fade-up">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<div class="cmpsian-page__content" data-aos="fade-up">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="cmpsian-page-links">' . esc_html__( 'Pages:', 'campussian' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>

			</article>

			<?php
			// Comments, if open.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>

		<?php
	endwhile;
	?>

</main>

<?php
get_footer();