<?php
/**
 * Single News Post.
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

<main id="primary" class="cmpsian-main cmpsian-single">

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<section class="cmpsian-page-hero">
			<div class="container">
				<h1 class="cmpsian-page-hero__title"><?php the_title(); ?></h1>
				<nav class="cmpsian-breadcrumb" aria-label="Breadcrumb">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'campussian' ); ?></a>
					<span class="cmpsian-breadcrumb__sep">/</span>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_news' ) ); ?>"><?php esc_html_e( 'News', 'campussian' ); ?></a>
					<span class="cmpsian-breadcrumb__sep">/</span>
					<span class="cmpsian-breadcrumb__current"><?php the_title(); ?></span>
				</nav>
			</div>
		</section>

		<div class="container">
			<article <?php post_class( 'cmpsian-single' ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="cmpsian-single__media">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<div class="cmpsian-single__meta">
					<span class="cmpsian-badge cmpsian-badge--teal"><?php esc_html_e( 'News', 'campussian' ); ?></span>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<?php echo esc_html( get_the_date() ); ?>
					</time>
				</div>

				<div class="cmpsian-single__content">
					<?php the_content(); ?>
				</div>

				<a class="cmpsian-btn cmpsian-btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_news' ) ); ?>">
					&larr; <?php esc_html_e( 'Back to All News', 'campussian' ); ?>
				</a>
			</article>
		</div>

	<?php endwhile; ?>

</main>

<?php
get_footer();