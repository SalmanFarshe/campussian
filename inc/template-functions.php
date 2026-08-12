<?php
/**
 * Miscellaneous template functions and filters.
 *
 * Body classes, seeding of demo content on first activation and other small
 * hooks that keep templates clean.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add helpful classes to the <body> element.
 *
 * @since 1.0.0
 * @param array $classes Existing body classes.
 * @return array
 */
function cmpsian_body_classes( $classes ) {
	// Flag the front page for section-specific styling.
	if ( is_front_page() ) {
		$classes[] = 'cmpsian-front';
	}

	// A generic hook so every page can be targeted.
	$classes[] = 'cmpsian-theme';

	return $classes;
}
add_filter( 'body_class', 'cmpsian_body_classes' );

/**
 * Provide a Bootstrap-friendly fallback menu when no primary menu is set.
 *
 * Renders the page list wrapped in a Bootstrap navbar list so the header never
 * looks broken on a fresh install.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_primary_menu_fallback() {
	echo '<ul id="primary-menu" class="navbar-nav ms-auto mb-2 mb-lg-0 cmpsian-menu">';
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
			'walker'   => new Walker_Page(),
		)
	);
	echo '</ul>';
}

/**
 * Trim the excerpt to a friendlier length for cards.
 *
 * @since 1.0.0
 * @param int $length Default word count.
 * @return int
 */
function cmpsian_excerpt_length( $length ) {
	return is_admin() ? $length : 22;
}
add_filter( 'excerpt_length', 'cmpsian_excerpt_length' );

/**
 * Replace the excerpt "[...]" with an ellipsis.
 *
 * @since 1.0.0
 * @param string $more Default more string.
 * @return string
 */
function cmpsian_excerpt_more( $more ) {
	return is_admin() ? $more : '&hellip;';
}
add_filter( 'excerpt_more', 'cmpsian_excerpt_more' );

/**
 * Format the footer copyright line, expanding the {year} token.
 *
 * @since 1.0.0
 * @return string Escaped, ready-to-print HTML.
 */
function cmpsian_get_copyright() {
	$custom = cmpsian_get_option( 'cmpsian_copyright' );

	if ( $custom ) {
		$custom = str_replace( '{year}', gmdate( 'Y' ), $custom );
		return wp_kses_post( $custom );
	}

	return sprintf(
		/* translators: 1: year, 2: site name. */
		esc_html__( '© %1$s %2$s. All rights reserved.', 'campussian' ),
		gmdate( 'Y' ),
		esc_html( get_bloginfo( 'name' ) )
	);
}
