<?php
/**
 * Campussian functions and definitions.
 *
 * The main bootstrap file for the Campussian theme. It defines global
 * constants (using the WhyCodeBD "wcbd_" brand prefix) and loads the modular
 * include files that power theme setup, asset enqueuing, the Customizer,
 * custom post types and template helpers.
 *
 * @package    Campussian
 * @author     WhyCodeBD
 * @link       https://salmanfarshe.me
 * @since      1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ---------------------------------------------------------------------------
 * Global constants (brand prefix: WCBD_)
 * ---------------------------------------------------------------------------
 */
if ( ! defined( 'WCBD_CAMPUSSIAN_VERSION' ) ) {
	// Used for cache-busting styles and scripts. Bump on release.
	define( 'WCBD_CAMPUSSIAN_VERSION', '1.0.0' );
}

if ( ! defined( 'WCBD_CAMPUSSIAN_DIR' ) ) {
	// Absolute path to the theme directory, with trailing slash.
	define( 'WCBD_CAMPUSSIAN_DIR', trailingslashit( get_template_directory() ) );
}

if ( ! defined( 'WCBD_CAMPUSSIAN_URI' ) ) {
	// Absolute URL to the theme directory, with trailing slash.
	define( 'WCBD_CAMPUSSIAN_URI', trailingslashit( get_template_directory_uri() ) );
}

/**
 * ---------------------------------------------------------------------------
 * Load modular include files.
 * ---------------------------------------------------------------------------
 *
 * Each concern lives in its own file inside /inc for readability and to keep
 * functions.php lean. These are loaded with explicit require_once statements so
 * that both PHP and static analysers (IDEs) can resolve every helper function.
 */
require_once WCBD_CAMPUSSIAN_DIR . 'inc/setup.php';               // Theme supports, menus, image sizes.
require_once WCBD_CAMPUSSIAN_DIR . 'inc/enqueue.php';             // Register & enqueue styles and scripts.
require_once WCBD_CAMPUSSIAN_DIR . 'inc/customizer-defaults.php'; // Default values + cmpsian_get_option() helper.
require_once WCBD_CAMPUSSIAN_DIR . 'inc/customizer.php';          // Customizer panels, sections and controls.
require_once WCBD_CAMPUSSIAN_DIR . 'inc/cpt.php';                 // Notice & Event custom post types + meta.
require_once WCBD_CAMPUSSIAN_DIR . 'inc/template-tags.php';       // Reusable presentational helper functions.
require_once WCBD_CAMPUSSIAN_DIR . 'inc/template-functions.php';  // Body classes, filters and misc hooks.


