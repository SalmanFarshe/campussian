<?php
/**
 * Archive: All News.
 *
 * Displays a grid of all news posts with search.
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

$search_query = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';

$args = array(
	'post_type'      => 'cmpsian_news',
	'posts_per_page' => 12,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'paged'          => max( 1, get_query_var( 'paged' ) ),
);

if ( $search_query ) {
	$args['s'] = $search_query;
}

$news = new WP_Query( $args );
?>

<main id="primary" class="cmpsian-main cmpsian-news-archive">

	<section class="cmpsian-page-hero">
		<div class="container">
			<h1 class="cmpsian-page-hero__title"><?php esc_html_e( 'News & Updates', 'campussian' ); ?></h1>
			<nav class="cmpsian-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'campussian' ); ?></a>
				<span class="cmpsian-breadcrumb__sep">/</span>
				<span class="cmpsian-breadcrumb__current"><?php esc_html_e( 'News', 'campussian' ); ?></span>
			</nav>
		</div>
	</section>

	<div class="container">
		<!-- Search bar -->
		<form class="cmpsian-filter-bar" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_news' ) ); ?>">
			<div class="cmpsian-filter-bar__group">
				<label for="cmpsian-news-search"><?php esc_html_e( 'Search News', 'campussian' ); ?></label>
				<input type="search" id="cmpsian-news-search" name="q" class="cmpsian-select"
					value="<?php echo esc_attr( $search_query ); ?>"
					placeholder="<?php esc_attr_e( 'Search news…', 'campussian' ); ?>">
			</div>

			<button type="submit" class="cmpsian-btn cmpsian-btn--orange cmpsian-btn--sm"><?php esc_html_e( 'Search', 'campussian' ); ?></button>
			<a class="cmpsian-filter-bar__reset" href="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_news' ) ); ?>"><?php esc_html_e( 'Reset', 'campussian' ); ?></a>
		</form>

		<?php if ( $news->have_posts() ) : ?>
			<div class="cmpsian-post-grid cmpsian-news__grid">
				<?php
				while ( $news->have_posts() ) :
					$news->the_post();
					?>
					<article <?php post_class( 'cmpsian-post-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="cmpsian-post-card__media" href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'medium_large' ); ?>
							</a>
						<?php endif; ?>
						<div class="cmpsian-post-card__body">
							<div class="cmpsian-post-card__meta">
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<?php echo esc_html( get_the_date() ); ?>
								</time>
							</div>
							<h3 class="cmpsian-post-card__title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<?php if ( has_excerpt() ) : ?>
								<p><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>
							<a class="cmpsian-readmore" href="<?php the_permalink(); ?>">
								<?php esc_html_e( 'Read More →', 'campussian' ); ?>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
				)
			);
			?>

		<?php else : ?>
			<div class="cmpsian-empty">
				<p><?php esc_html_e( 'No news found. Try adjusting your search.', 'campussian' ); ?></p>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>

</main>

<?php
get_footer();