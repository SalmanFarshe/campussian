<?php
/**
 * The sidebar containing the primary widget area.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_active_sidebar( 'sidebar-primary' ) ) {
	return;
}
?>
<div class="cmpsian-widget-area">
	<?php dynamic_sidebar( 'sidebar-primary' ); ?>
</div>
