<?php
/**
 * Template Name: Events
 *
 * Displays a tabbed grid of Upcoming and Past events, split by the event date
 * meta. Events come from the "cmpsian_event" custom post type.
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

$today = current_time( 'Y-m-d' );

// Upcoming: event date >= today, ascending.
$upcoming = new WP_Query(
	array(
		'post_type'      => 'cmpsian_event',
		'posts_per_page' => 12,
		'meta_key'       => '_cmpsian_event_date',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => '_cmpsian_event_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE',
			),
		),
	)
);

// Past: event date < today, descending.
$past = new WP_Query(
	array(
		'post_type'      => 'cmpsian_event',
		'posts_per_page' => 12,
		'meta_key'       => '_cmpsian_event_date',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
		'meta_query'     => array(
			array(
				'key'     => '_cmpsian_event_date',
				'value'   => $today,
				'compare' => '<',
				'type'    => 'DATE',
			),
		),
	)
);

/**
 * Render a grid of events from a query. Local helper for this template only.
 *
 * @param WP_Query $query The event query.
 * @param string   $empty Empty-state message.
 * @return void
 */
$cmpsian_render_events = static function ( $query, $empty ) {
	if ( ! $query->have_posts() ) {
		echo '<div class="cmpsian-empty" data-aos="fade-up"><p>' . esc_html( $empty ) . '</p></div>';
		return;
	}
	echo '<div class="row gy-4 cmpsian-event-grid">';
	while ( $query->have_posts() ) :
		$query->the_post();
		$venue = get_post_meta( get_the_ID(), '_cmpsian_event_venue', true );
		?>
		<div class="col-md-6 col-lg-4" data-aos="fade-up">
			<article class="cmpsian-event-card">
				<?php if ( has_post_thumbnail() ) : ?>
					<a class="cmpsian-event-card__media" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'cmpsian-card' ); ?>
					</a>
				<?php endif; ?>
				<div class="cmpsian-event-card__body">
					<span class="cmpsian-event-card__date"><?php echo esc_html( cmpsian_event_datetime() ); ?></span>
					<h3 class="cmpsian-event-card__title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h3>
					<?php if ( $venue ) : ?>
						<span class="cmpsian-event-card__venue">
							<svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 00-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1112 6a2.5 2.5 0 010 5.5z"/></svg>
							<?php echo esc_html( $venue ); ?>
						</span>
					<?php endif; ?>
					<?php if ( has_excerpt() ) : ?>
						<p class="cmpsian-event-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p>
					<?php endif; ?>
				</div>
			</article>
		</div>
		<?php
	endwhile;
	echo '</div>';
	wp_reset_postdata();
};
?>

<main id="primary" class="cmpsian-main cmpsian-events">

	<header class="cmpsian-page-hero" data-aos="fade-up">
		<div class="container">
			<h1 class="cmpsian-page-hero__title"><?php the_title(); ?></h1>
			<?php cmpsian_breadcrumb(); ?>
		</div>
	</header>

	<div class="container">

		<?php
		while ( have_posts() ) :
			the_post();
			if ( trim( get_the_content() ) ) :
				?>
				<div class="cmpsian-events__intro" data-aos="fade-up"><?php the_content(); ?></div>
				<?php
			endif;
		endwhile;
		?>

		<!-- Tabs -->
		<ul class="nav nav-pills cmpsian-tabs" id="cmpsianEventTabs" role="tablist" data-aos="fade-up">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="upcoming-tab" data-bs-toggle="pill" data-bs-target="#upcoming"
					type="button" role="tab" aria-controls="upcoming" aria-selected="true">
					<?php esc_html_e( 'Upcoming', 'campussian' ); ?>
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="past-tab" data-bs-toggle="pill" data-bs-target="#past"
					type="button" role="tab" aria-controls="past" aria-selected="false">
					<?php esc_html_e( 'Past', 'campussian' ); ?>
				</button>
			</li>
		</ul>

		<div class="tab-content cmpsian-tabs__content" id="cmpsianEventTabsContent">
			<div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab" tabindex="0">
				<?php $cmpsian_render_events( $upcoming, __( 'No upcoming events right now. Please check back soon.', 'campussian' ) ); ?>
			</div>
			<div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab" tabindex="0">
				<?php $cmpsian_render_events( $past, __( 'No past events to show yet.', 'campussian' ) ); ?>
			</div>
		</div>

	</div>
</main>

<?php
get_footer();
