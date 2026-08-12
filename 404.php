<?php
/**
 * The template for displaying 404 (not found) pages.
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
		<section class="cmpsian-404 text-center" data-aos="fade-up">
			<span class="cmpsian-404__code">404</span>
			<h1 class="cmpsian-404__title"><?php esc_html_e( 'Page Not Found', 'campussian' ); ?></h1>
			<p class="cmpsian-404__text">
				<?php esc_html_e( 'The page you are looking for might have been removed, renamed or is temporarily unavailable.', 'campussian' ); ?>
			</p>

			<div class="cmpsian-404__search">
				<?php get_search_form(); ?>
			</div>

			<a class="cmpsian-btn cmpsian-btn--teal" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Back to Homepage', 'campussian' ); ?>
			</a>
		</section>
	</div>
</main>

<?php
get_footer();
