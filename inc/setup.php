<?php
/**
 * Theme setup.
 *
 * Registers theme supports, navigation menus, image sizes and the content
 * width global for the Campussian theme.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'cmpsian_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Hooked to 'after_setup_theme'.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function cmpsian_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'campussian', WCBD_CAMPUSSIAN_DIR . 'languages' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Output valid HTML5 markup for core features.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
		);

		// Enable featured images (used across notices, events, gallery, blog).
		add_theme_support( 'post-thumbnails' );

		// Custom logo support (fallback when the Customizer logo field is empty).
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 80,
				'width'       => 240,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		// Feed links in <head>.
		add_theme_support( 'automatic-feed-links' );

		// Selective refresh for widgets in the Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Gutenberg / block editor niceties.
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'wp-block-styles' );

		// Nested (threaded) comments.
		add_theme_support( 'threaded-comments' );

		// Editor stylesheet so the block editor matches the front end.
		add_theme_support( 'editor-style' );
		add_editor_style(
			array(
				'assets/css/campussian-fonts.css',
				'assets/css/editor.css',
			)
		);

		// Register navigation menus.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'campussian' ),
				'footer'  => esc_html__( 'Footer Menu', 'campussian' ),
			)
		);

		// Custom image sizes for cards and hero art.
		add_image_size( 'cmpsian-card', 600, 400, true );      // Notice / event / gallery cards.
		add_image_size( 'cmpsian-hero', 1600, 800, true );     // Hero banner background.
		add_image_size( 'cmpsian-principal', 400, 400, true ); // Principal portrait.
	}
}
add_action( 'after_setup_theme', 'cmpsian_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Hooked to 'after_setup_theme' at a late priority.
 *
 * @global int $content_width
 * @since 1.0.0
 * @return void
 */
function cmpsian_content_width() {
	// Applies a filter so child themes can adjust the value.
	$GLOBALS['content_width'] = apply_filters( 'cmpsian_content_width', 1140 );
}
add_action( 'after_setup_theme', 'cmpsian_content_width', 0 );

/**
 * Register widget areas.
 *
 * Hooked to 'widgets_init'.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_widgets_init() {

	// Sidebar for blog/archive pages.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Primary Sidebar', 'campussian' ),
			'id'            => 'sidebar-primary',
			'description'   => esc_html__( 'Appears on blog and archive pages.', 'campussian' ),
			'before_widget' => '<section id="%1$s" class="widget cmpsian-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	// Footer columns (registered as four independent areas).
	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( esc_html__( 'Footer Column %d', 'campussian' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => esc_html__( 'Footer widget area.', 'campussian' ),
				'before_widget' => '<div id="%1$s" class="widget cmpsian-footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="cmpsian-footer-title">',
				'after_title'   => '</h4>',
			)
		);
	}
}
add_action( 'widgets_init', 'cmpsian_widgets_init' );
