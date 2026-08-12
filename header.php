<?php
/**
 * The header for the theme.
 *
 * Opens the document, prints the utility bar, main navigation and the notice
 * marquee, then opens the #content wrapper.
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
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="cmpsian-skip-link screen-reader-text" href="#content">
	<?php esc_html_e( 'Skip to content', 'campussian' ); ?>
</a>

<div id="page" class="cmpsian-site">

	<header id="masthead" class="cmpsian-header" data-aos="fade-down" data-aos-once="true">
		<?php
		// Top utility bar (contact, socials, dark-mode toggle).
		get_template_part( 'template-parts/header/utility-bar' );

		// Main header & navigation.
		get_template_part( 'template-parts/header/nav' );
		?>
	</header>

	<?php
	// Notice marquee ticker (shown site-wide; self-hides when disabled/empty).
	get_template_part( 'template-parts/home/marquee' );
	?>

	<div id="content" class="cmpsian-site-content">
