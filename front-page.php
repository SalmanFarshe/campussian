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

// 1. Get the section order (falls back to the default in cmpsian_default_options()).
$sections_order   = cmpsian_get_option( 'cmpsian_sections_order' );
$enabled_sections = array_map( 'trim', explode( ',', $sections_order ) );

// 2. Resolve each section's visibility toggle (default to enabled).
$section_status = array();
foreach ( $enabled_sections as $section ) {
	if ( $section === '' ) {
		continue;
	}
	$section_status[ $section ] = cmpsian_get_option( "cmpsian_sections_enable_{$section}" );
	if ( ! is_bool( $section_status[ $section ] ) ) {
		$section_status[ $section ] = true; // default to enabled when not set.
	}
}
?>
<main id="primary" class="cmpsian-main cmpsian-home">
  <?php foreach ( $enabled_sections as $section ) :
    $is_enabled = isset( $section_status[ $section ] ) ? (bool) $section_status[ $section ] : true;
  ?>
    <?php if ( ! $is_enabled ) continue; ?>
    
    <?php switch ( $section ) :
      case 'hero': 
        get_template_part( 'template-parts/home/hero' );
        break;
      case 'principal': 
        get_template_part( 'template-parts/home/principal' );
        break;
      case 'news': 
        get_template_part( 'template-parts/home/news' );
        break;
      case 'notices_events': 
        get_template_part( 'template-parts/home/notices-events' );
        break;
      case 'stats_counter': 
        get_template_part( 'template-parts/home/stats-counter' );
        break;
      case 'admission_cta': 
        get_template_part( 'template-parts/home/admission-cta' );
        break;
      case 'gallery_preview': 
        get_template_part( 'template-parts/home/gallery-preview' );
        break;
      case 'facilities': 
        get_template_part( 'template-parts/home/facilities' );
        break;
      case 'teachers': 
        get_template_part( 'template-parts/home/teachers' );
        break;
      case 'alumni': 
        get_template_part( 'template-parts/home/alumni' );
        break;
    endswitch;
  ?>
  <?php endforeach; ?>
</main>

<?php
get_footer();