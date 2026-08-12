<?php
/**
 * Custom search form.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$unique = wp_unique_id( 'cmpsian-search-' );
?>
<form role="search" method="get" class="cmpsian-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $unique ); ?>">
		<?php esc_html_e( 'Search for:', 'campussian' ); ?>
	</label>
	<input type="search" id="<?php echo esc_attr( $unique ); ?>" class="cmpsian-search__field"
		placeholder="<?php esc_attr_e( 'Search&hellip;', 'campussian' ); ?>"
		value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="cmpsian-search__submit">
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'campussian' ); ?></span>
		<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M15.5 14h-.8l-.3-.3a6.5 6.5 0 10-.7.7l.3.3v.8l5 5 1.5-1.5-5-5zm-6 0A4.5 4.5 0 1114 9.5 4.5 4.5 0 019.5 14z"/></svg>
	</button>
</form>
