<?php
/**
 * The front page template.
 *
 * Assembles the homepage from modular, independently-cached template parts.
 * Each part carries its own AOS "fade-up" animation for a snappy sequential
 * reveal on scroll. The utility bar, navigation and marquee live in header.php.
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

<main id="primary" class="cmpsian-main cmpsian-home">

	<?php
	// 1. Hero banner with CTA buttons.
	get_template_part( 'template-parts/home/hero' );

	// 2. Principal's message & overview.
	get_template_part( 'template-parts/home/principal' );

	// 3. Latest notices & upcoming events grid.
	get_template_part( 'template-parts/home/notices-events' );

	// 4. Animated statistics counter.
	get_template_part( 'template-parts/home/stats-counter' );

	// 5. Admission call-to-action banner.
	get_template_part( 'template-parts/home/admission-cta' );

	// 6. Gallery preview.
	get_template_part( 'template-parts/home/gallery-preview' );
	?>

</main>

<?php
get_footer();
