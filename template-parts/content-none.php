<?php
/**
 * Template part: no results found.
 *
 * Shown when a loop returns no posts (search, archive or index).
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="cmpsian-no-results" data-aos="fade-up">
	<h2 class="cmpsian-no-results__title"><?php esc_html_e( 'Nothing found', 'campussian' ); ?></h2>

	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'Sorry, no results matched your search. Try different keywords.', 'campussian' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'It seems we cannot find what you are looking for.', 'campussian' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</section>
