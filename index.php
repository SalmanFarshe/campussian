<?php
/**
 * The main template file (blog / fallback).
 *
 * The most generic template. Renders the blog index and acts as the ultimate
 * fallback for any query WordPress cannot match to a more specific template.
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
	<div class="container">
		<div class="row gy-4">

			<div class="col-lg-8">

				<?php if ( is_home() && ! is_front_page() ) : ?>
					<header class="cmpsian-page-head" data-aos="fade-up">
						<h1 class="cmpsian-page-head__title"><?php single_post_title(); ?></h1>
					</header>
				<?php endif; ?>

				<?php if ( have_posts() ) : ?>

					<div class="cmpsian-post-grid">
						<?php
						while ( have_posts() ) :
							the_post();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cmpsian-post-card' ); ?> data-aos="fade-up">
								<?php if ( has_post_thumbnail() ) : ?>
									<a class="cmpsian-post-card__media" href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'cmpsian-card' ); ?>
									</a>
								<?php endif; ?>
								<div class="cmpsian-post-card__body">
									<div class="cmpsian-post-card__meta">
										<span><?php echo esc_html( get_the_date() ); ?></span>
									</div>
									<h2 class="cmpsian-post-card__title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h2>
									<div class="cmpsian-post-card__excerpt">
										<?php the_excerpt(); ?>
									</div>
									<a class="cmpsian-readmore" href="<?php the_permalink(); ?>">
										<?php esc_html_e( 'Read more', 'campussian' ); ?> &rarr;
									</a>
								</div>
							</article>
							<?php
						endwhile;
						?>
					</div>

					<div class="cmpsian-pagination">
						<?php
						the_posts_pagination(
							array(
								'mid_size'  => 1,
								'prev_text' => esc_html__( '&larr; Previous', 'campussian' ),
								'next_text' => esc_html__( 'Next &rarr;', 'campussian' ),
							)
						);
						?>
					</div>

				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>

			</div>

			<aside class="col-lg-4 cmpsian-sidebar" data-aos="fade-up" data-aos-delay="100">
				<?php get_sidebar(); ?>
			</aside>

		</div>
	</div>
</main>

<?php
get_footer();
