<?php
/**
 * Homepage News Section.
 *
 * Pulls latest news from the cmpsian_news custom post type.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$enable = cmpsian_get_option( 'cmpsian_sections_enable_news' );
if ( ! $enable ) {
	return;
}

$news = new WP_Query(
	array(
		'post_type'      => 'cmpsian_news',
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

$archive_url = get_post_type_archive_link( 'cmpsian_news' );
?>
<section class="cmpsian-news" id="news">
	<div class="container">
		<div class="section-header">
			<h2 class="cmpsian-section-title">
				<?php echo esc_html( cmpsian_get_option( 'cmpsian_news_title' ) ); ?>
			</h2>
		</div>

		<?php if ( $news->have_posts() ) : ?>
			<div class="cmpsian-post-grid cmpsian-news__grid">
				<?php
				while ( $news->have_posts() ) {
					$news->the_post();
					?>
					<article <?php post_class( 'cmpsian-post-card' ); ?> data-aos="fade-up">
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
					<?php
				}
				wp_reset_postdata();
				?>
			</div>

			<?php if ( $archive_url ) : ?>
				<div class="cmpsian-teachers__footer">
					<a class="cmpsian-btn cmpsian-btn--outline" href="<?php echo esc_url( $archive_url ); ?>">
						<?php esc_html_e( 'View All News', 'campussian' ); ?>
					</a>
				</div>
			<?php endif; ?>

		<?php else : ?>
			<div class="cmpsian-empty">
				<p><?php esc_html_e( 'No news found yet.', 'campussian' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>