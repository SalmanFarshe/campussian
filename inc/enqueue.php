<?php
/**
 * Scripts and styles.
 *
 * Registers and enqueues all front-end assets for Campussian. Every third-party
 * library (Bootstrap 5, AOS) is bundled locally under /assets so the theme has
 * no external CDN or plugin dependency, per the WhyCodeBD standalone rule.
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
 * Enqueue front-end styles and scripts.
 *
 * Hooked to 'wp_enqueue_scripts'.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_enqueue_assets() {

	$ver = WCBD_CAMPUSSIAN_VERSION;
	$css = WCBD_CAMPUSSIAN_URI . 'assets/css/';
	$js  = WCBD_CAMPUSSIAN_URI . 'assets/js/';

	/* ------------------------------------------------------------------ *
	 * Styles
	 * ------------------------------------------------------------------ */

	// Bootstrap 5 (local bundle).
	wp_enqueue_style( 'bootstrap', $css . 'bootstrap.min.css', array(), '5.3.3' );

	// AOS - Animate On Scroll (local bundle).
	wp_enqueue_style( 'aos', $css . 'aos.css', array(), '2.3.4' );

	// Owl Carousel 2 - hero slideshow (local bundle, no CDN dependency).
	wp_enqueue_style( 'owl-carousel', $css . 'owl.carousel.min.css', array(), '2.3.4' );
	wp_enqueue_style( 'owl-carousel-theme', $css . 'owl.theme.default.min.css', array( 'owl-carousel' ), '2.3.4' );

	// Google Fonts - Poppins (headings) + Inter (body). Loaded remotely only as
	// a webfont; the theme still renders fully with the system-font fallback.
	wp_enqueue_style(
		'cmpsian-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap',
		array(),
		null
	);

	// Main theme stylesheet (depends on Bootstrap so our overrides win).
	wp_enqueue_style(
		'campussian-main',
		$css . 'campussian.css',
		array( 'bootstrap', 'aos', 'owl-carousel', 'owl-carousel-theme' ),
		$ver
	);

	// The root style.css (for the theme header + any editor overrides).
	wp_enqueue_style(
		'campussian-style',
		get_stylesheet_uri(),
		array( 'campussian-main' ),
		$ver
	);

	/* ------------------------------------------------------------------ *
	 * Scripts
	 * ------------------------------------------------------------------ */

	// Bootstrap 5 bundle (includes Popper) - loaded in the footer.
	wp_enqueue_script( 'bootstrap', $js . 'bootstrap.bundle.min.js', array(), '5.3.3', true );

	// AOS library - loaded in the footer.
	wp_enqueue_script( 'aos', $js . 'aos.js', array(), '2.3.4', true );

	// Owl Carousel 2 - hero slideshow (local bundle, no CDN dependency).
	wp_enqueue_script( 'owl-carousel', $js . 'owl.carousel.min.js', array( 'jquery' ), '2.3.4', true );

	// Theme script: dark-mode toggle, AOS init, stat counters, notice modal.
	wp_enqueue_script(
		'campussian-main',
		$js . 'campussian.js',
		array( 'bootstrap', 'aos', 'owl-carousel' ),
		$ver,
		true
	);

	// Expose a few values to JS in a namespaced object.
	wp_localize_script(
		'campussian-main',
		'CampussianData',
		array(
			'ajaxUrl'      => esc_url( admin_url( 'admin-ajax.php' ) ),
			'restNotices'  => esc_url_raw( rest_url( 'wp/v2/cmpsian_notice' ) ),
			'aosDuration'  => 700,
			'aosOffset'    => 120,
		)
	);

	// Threaded comment support on singular views.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'cmpsian_enqueue_assets' );

/**
 * Add a no-flash inline script to the <head>.
 *
 * Applies the persisted colour scheme (from localStorage) to the <html> element
 * before paint, preventing a flash of the wrong theme. Hooked very early on
 * 'wp_head'.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_no_flash_dark_mode() {
	?>
	<script id="cmpsian-no-flash">
		(function () {
			try {
				var stored = localStorage.getItem( 'cmpsian-theme' );
				var prefersDark = window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches;
				var theme = stored ? stored : ( prefersDark ? 'dark' : 'light' );
				document.documentElement.setAttribute( 'data-theme', theme );
			} catch ( e ) {}
		})();
	</script>
	<?php
}
add_action( 'wp_head', 'cmpsian_no_flash_dark_mode', 1 );
