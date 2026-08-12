<?php
/**
 * Latest notices & upcoming events grid.
 *
 * Two-column band: a compact list of the newest notices on the left, and a
 * grid of the nearest upcoming events on the right. Falls back to friendly
 * placeholder copy when no content exists yet.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$notice_page = get_post_type_archive_link( 'cmpsian_notice' );
$event_page  = get_post_type_archive_link( 'cmpsian_event' );
?>
<section class="cmpsian-updates" id="updates">
	<div class="container">

		<div class="cmpsian-section__head text-center" data-aos="fade-up">
			<span class="cmpsian-section__eyebrow"><?php esc_html_e( 'Stay Informed', 'campussian' ); ?></span>
			<h2 class="cmpsian-section__title"><?php esc_html_e( 'Notices &amp; Upcoming Events', 'campussian' ); ?></h2>
		</div>

		<div class="row gy-4">

			<!-- Latest notices -->
			<div class="col-lg-6" data-aos="fade-up">
				<div class="cmpsian-panel">
					<div class="cmpsian-panel__head">
						<h3 class="cmpsian-panel__title">
							<span class="cmpsian-panel__dot cmpsian-panel__dot--teal"></span>
							<?php esc_html_e( 'Latest Notices', 'campussian' ); ?>
						</h3>
						<?php if ( $notice_page ) : ?>
							<a class="cmpsian-panel__link" href="<?php echo esc_url( $notice_page ); ?>"><?php esc_html_e( 'View all', 'campussian' ); ?></a>
						<?php endif; ?>
					</div>

					<ul class="cmpsian-notice-list">
						<?php
						$notices = cmpsian_get_posts( 'cmpsian_notice', 5, array( 'orderby' => 'date', 'order' => 'DESC' ) );
						if ( $notices->have_posts() ) :
							while ( $notices->have_posts() ) :
								$notices->the_post();
								?>
								<li class="cmpsian-notice-list__item">
									<span class="cmpsian-notice-list__date">
										<span class="cmpsian-notice-list__day"><?php echo esc_html( get_the_date( 'd' ) ); ?></span>
										<span class="cmpsian-notice-list__mon"><?php echo esc_html( get_the_date( 'M' ) ); ?></span>
									</span>
									<a class="cmpsian-notice-list__title" href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</li>
								<?php
							endwhile;
							wp_reset_postdata();
						else :
							?>
							<li class="cmpsian-notice-list__empty">
								<?php esc_html_e( 'No notices published yet. Add some under Notices in the WordPress admin.', 'campussian' ); ?>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>

			<!-- Upcoming events -->
			<div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
				<div class="cmpsian-panel">
					<div class="cmpsian-panel__head">
						<h3 class="cmpsian-panel__title">
							<span class="cmpsian-panel__dot cmpsian-panel__dot--orange"></span>
							<?php esc_html_e( 'Upcoming Events', 'campussian' ); ?>
						</h3>
						<?php if ( $event_page ) : ?>
							<a class="cmpsian-panel__link" href="<?php echo esc_url( $event_page ); ?>"><?php esc_html_e( 'View all', 'campussian' ); ?></a>
						<?php endif; ?>
					</div>

					<div class="cmpsian-event-list">
						<?php
						$today  = current_time( 'Y-m-d' );
						$events = cmpsian_get_posts(
							'cmpsian_event',
							4,
							array(
								'meta_key'     => '_cmpsian_event_date',
								'orderby'      => 'meta_value',
								'order'        => 'ASC',
								'meta_query'   => array(
									array(
										'key'     => '_cmpsian_event_date',
										'value'   => $today,
										'compare' => '>=',
										'type'    => 'DATE',
									),
								),
							)
						);
						if ( $events->have_posts() ) :
							while ( $events->have_posts() ) :
								$events->the_post();
								?>
								<a class="cmpsian-event-item" href="<?php the_permalink(); ?>">
									<span class="cmpsian-event-item__date"><?php echo esc_html( cmpsian_event_datetime() ); ?></span>
									<span class="cmpsian-event-item__title"><?php the_title(); ?></span>
									<?php
									$venue = get_post_meta( get_the_ID(), '_cmpsian_event_venue', true );
									if ( $venue ) :
										?>
										<span class="cmpsian-event-item__venue"><?php echo esc_html( $venue ); ?></span>
									<?php endif; ?>
								</a>
								<?php
							endwhile;
							wp_reset_postdata();
						else :
							?>
							<p class="cmpsian-event-list__empty">
								<?php esc_html_e( 'No upcoming events scheduled. Add some under Events in the WordPress admin.', 'campussian' ); ?>
							</p>
						<?php endif; ?>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
