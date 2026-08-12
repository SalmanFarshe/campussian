<?php
/**
 * Notice marquee / ticker bar.
 *
 * Displays either the latest notices or manual Customizer text scrolling
 * horizontally. Uses a CSS animation (no external plugin) with a duration
 * driven by the Customizer speed setting.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if the ticker is disabled.
if ( ! cmpsian_get_option( 'cmpsian_marquee_enable' ) ) {
	return;
}

$label  = cmpsian_get_option( 'cmpsian_marquee_label' );
$source = cmpsian_get_option( 'cmpsian_marquee_source' );
$speed  = absint( cmpsian_get_option( 'cmpsian_marquee_speed' ) );
$speed  = $speed ? $speed : 25;

// Build the list of items.
$items = array();

if ( 'notices' === $source && post_type_exists( 'cmpsian_notice' ) ) {
	$q = cmpsian_get_posts( 'cmpsian_notice', 6, array( 'orderby' => 'date', 'order' => 'DESC' ) );
	if ( $q->have_posts() ) {
		while ( $q->have_posts() ) {
			$q->the_post();
			$items[] = array(
				'text' => get_the_title(),
				'url'  => get_permalink(),
			);
		}
		wp_reset_postdata();
	}
}

// Fall back to manual text if no notices were found or manual is selected.
if ( empty( $items ) ) {
	$manual = cmpsian_get_option( 'cmpsian_marquee_text' );
	if ( $manual ) {
		$items[] = array(
			'text' => $manual,
			'url'  => '',
		);
	}
}

// Nothing to show.
if ( empty( $items ) ) {
	return;
}
?>
<div class="cmpsian-marquee" data-aos="fade-up">
	<div class="container">
		<div class="cmpsian-marquee__inner">

			<?php if ( $label ) : ?>
				<span class="cmpsian-marquee__label"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>

			<div class="cmpsian-marquee__viewport">
				<div class="cmpsian-marquee__track" style="animation-duration: <?php echo esc_attr( $speed ); ?>s;">
					<?php foreach ( $items as $item ) : ?>
						<span class="cmpsian-marquee__item">
							<?php if ( $item['url'] ) : ?>
								<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $item['text'] ); ?>
							<?php endif; ?>
						</span>
						<span class="cmpsian-marquee__sep" aria-hidden="true">◆</span>
					<?php endforeach; ?>

					<?php // Duplicate the run for a seamless loop. ?>
					<?php foreach ( $items as $item ) : ?>
						<span class="cmpsian-marquee__item" aria-hidden="true">
							<?php if ( $item['url'] ) : ?>
								<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $item['text'] ); ?>
							<?php endif; ?>
						</span>
						<span class="cmpsian-marquee__sep" aria-hidden="true">◆</span>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>
</div>
