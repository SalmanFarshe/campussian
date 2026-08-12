<?php
/**
 * Animated statistics counter.
 *
 * Four animated numbers (Students, Teachers, Pass Rate, Years) that count up
 * when scrolled into view. Values come from the Customizer; the JS reads the
 * data-target attribute.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stats = array();
for ( $i = 1; $i <= 4; $i++ ) {
	$number = cmpsian_get_option( 'cmpsian_stat' . $i . '_number' );
	$label  = cmpsian_get_option( 'cmpsian_stat' . $i . '_label' );
	if ( '' !== $label ) {
		$stats[] = array(
			'number' => absint( $number ),
			'label'  => $label,
		);
	}
}

if ( empty( $stats ) ) {
	return;
}
?>
<section class="cmpsian-stats" data-aos="fade-up">
	<div class="container">
		<div class="row g-4 justify-content-center">
			<?php foreach ( $stats as $i => $stat ) : ?>
				<div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 80 ); ?>">
					<div class="cmpsian-stat">
						<span class="cmpsian-stat__number" data-cmpsian-counter data-target="<?php echo esc_attr( $stat['number'] ); ?>">0</span>
						<span class="cmpsian-stat__label"><?php echo esc_html( $stat['label'] ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
