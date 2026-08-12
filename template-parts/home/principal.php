<?php
/**
 * Principal's message & overview section.
 *
 * Two-column layout: the principal's portrait and speech on one side, a short
 * "why choose us" overview with feature highlights on the other.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$photo   = cmpsian_get_option( 'cmpsian_principal_photo' );
$name    = cmpsian_get_option( 'cmpsian_principal_name' );
$desig   = cmpsian_get_option( 'cmpsian_principal_desig' );
$speech  = cmpsian_get_option( 'cmpsian_principal_speech' );
$ov_head = cmpsian_get_option( 'cmpsian_overview_title' );
$ov_text = cmpsian_get_option( 'cmpsian_overview_text' );

// Static feature highlights for the overview column.
$features = array(
	array(
		'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M12 3L1 9l11 6 9-4.9V17h2V9L12 3zM5 13.2V17c0 1.7 3.1 3 7 3s7-1.3 7-3v-3.8l-7 3.8-7-3.6z"/></svg>',
		'title' => __( 'Academic Excellence', 'campussian' ),
		'text'  => __( 'A future-ready curriculum delivered by experienced, caring educators.', 'campussian' ),
	),
	array(
		'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M16 11c1.7 0 3-1.3 3-3s-1.3-3-3-3-3 1.3-3 3 1.3 3 3 3zm-8 0c1.7 0 3-1.3 3-3S9.7 5 8 5 5 6.3 5 8s1.3 3 3 3zm0 2c-2.3 0-7 1.2-7 3.5V19h14v-2.5C15 14.2 10.3 13 8 13zm8 0c-.3 0-.6 0-1 .1 1.2.9 2 2 2 2.9V19h6v-2.5c0-2.3-4.7-3.5-7-3.5z"/></svg>',
		'title' => __( 'Supportive Community', 'campussian' ),
		'text'  => __( 'A safe, inclusive campus where every learner feels they belong.', 'campussian' ),
	),
	array(
		'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-9 14l-4-4 1.4-1.4L10 14.2l6.6-6.6L18 9l-8 8z"/></svg>',
		'title' => __( 'Modern Facilities', 'campussian' ),
		'text'  => __( 'Smart classrooms, science labs and a vibrant library for hands-on learning.', 'campussian' ),
	),
);
?>
<section class="cmpsian-principal" id="overview">
	<div class="container">
		<div class="row align-items-center gy-4">

			<div class="col-lg-5" data-aos="fade-up">
				<div class="cmpsian-principal__card">
					<div class="cmpsian-principal__photo">
						<?php if ( $photo ) : ?>
							<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy" />
						<?php else : ?>
							<span class="cmpsian-principal__placeholder" aria-hidden="true">
								<svg viewBox="0 0 24 24" width="64" height="64"><path fill="currentColor" d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4 0-9 2-9 6v2h18v-2c0-4-5-6-9-6z"/></svg>
							</span>
						<?php endif; ?>
					</div>
					<div class="cmpsian-principal__meta">
						<?php if ( $name ) : ?>
							<h4 class="cmpsian-principal__name"><?php echo esc_html( $name ); ?></h4>
						<?php endif; ?>
						<?php if ( $desig ) : ?>
							<span class="cmpsian-principal__desig"><?php echo esc_html( $desig ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
				<span class="cmpsian-section__eyebrow"><?php esc_html_e( "Principal's Message", 'campussian' ); ?></span>
				<?php if ( $ov_head ) : ?>
					<h2 class="cmpsian-section__title"><?php echo esc_html( $ov_head ); ?></h2>
				<?php endif; ?>

				<?php if ( $speech ) : ?>
					<blockquote class="cmpsian-principal__speech">
						<?php echo wp_kses_post( wpautop( $speech ) ); ?>
					</blockquote>
				<?php endif; ?>

				<?php if ( $ov_text ) : ?>
					<p class="cmpsian-principal__overview"><?php echo esc_html( $ov_text ); ?></p>
				<?php endif; ?>

				<div class="cmpsian-feature-grid">
					<?php foreach ( $features as $i => $feature ) : ?>
						<div class="cmpsian-feature" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( 150 + ( $i * 80 ) ); ?>">
							<span class="cmpsian-feature__icon" aria-hidden="true"><?php echo $feature['icon']; // phpcs:ignore -- internal SVG. ?></span>
							<div>
								<h5 class="cmpsian-feature__title"><?php echo esc_html( $feature['title'] ); ?></h5>
								<p class="cmpsian-feature__text"><?php echo esc_html( $feature['text'] ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>
</section>
