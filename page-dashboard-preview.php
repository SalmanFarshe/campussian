<?php
/**
 * Template Name: Dashboard Preview
 *
 * A static front-end mockup of a student/parent portal dashboard. Purely a
 * visual preview (no real data or authentication) to demonstrate the future
 * portal UI: sidebar navigation, summary stat cards, a results table and a
 * fees panel.
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

// Demo dashboard data.
$summary = array(
	array( 'label' => __( 'Attendance', 'campussian' ),  'value' => '96%',   'icon' => 'M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.4-1.4z' ),
	array( 'label' => __( 'Avg. Grade', 'campussian' ),  'value' => 'A',     'icon' => 'M12 3L1 9l11 6 9-4.9V17h2V9L12 3z' ),
	array( 'label' => __( 'Due Fees', 'campussian' ),    'value' => '৳0',    'icon' => 'M12 1a11 11 0 100 22 11 11 0 000-22zm1 17h-2v-2h2zm0-4h-2V6h2z' ),
	array( 'label' => __( 'Rank', 'campussian' ),        'value' => '#4',    'icon' => 'M12 2l3 7h7l-5.5 4L18 20l-6-4-6 4 1.5-7L2 9h7z' ),
);

$results = array(
	array( 'subject' => __( 'Mathematics', 'campussian' ), 'marks' => '92', 'grade' => 'A+' ),
	array( 'subject' => __( 'English', 'campussian' ),     'marks' => '85', 'grade' => 'A'  ),
	array( 'subject' => __( 'Science', 'campussian' ),     'marks' => '88', 'grade' => 'A'  ),
	array( 'subject' => __( 'ICT', 'campussian' ),         'marks' => '95', 'grade' => 'A+' ),
);

$nav_items = array(
	array( 'label' => __( 'Overview', 'campussian' ),   'active' => true ),
	array( 'label' => __( 'Results', 'campussian' ),    'active' => false ),
	array( 'label' => __( 'Attendance', 'campussian' ), 'active' => false ),
	array( 'label' => __( 'Fees', 'campussian' ),       'active' => false ),
	array( 'label' => __( 'Routine', 'campussian' ),    'active' => false ),
	array( 'label' => __( 'Profile', 'campussian' ),    'active' => false ),
);
?>

<main id="primary" class="cmpsian-main cmpsian-dashboard">

	<header class="cmpsian-page-hero" data-aos="fade-up">
		<div class="container">
			<h1 class="cmpsian-page-hero__title"><?php the_title(); ?></h1>
			<?php cmpsian_breadcrumb(); ?>
		</div>
	</header>

	<div class="container">

		<div class="cmpsian-dashboard__notice" data-aos="fade-up">
			<?php esc_html_e( 'Preview only — this is a visual mockup of the upcoming student portal. No login or live data is connected.', 'campussian' ); ?>
		</div>

		<div class="cmpsian-portal" data-aos="fade-up">

			<!-- Sidebar -->
			<aside class="cmpsian-portal__sidebar">
				<div class="cmpsian-portal__user">
					<span class="cmpsian-portal__avatar" aria-hidden="true">
						<svg viewBox="0 0 24 24" width="40" height="40"><path fill="currentColor" d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4 0-9 2-9 6v2h18v-2c0-4-5-6-9-6z"/></svg>
					</span>
					<div>
						<strong class="cmpsian-portal__name"><?php esc_html_e( 'Ayaan Rahman', 'campussian' ); ?></strong>
						<span class="cmpsian-portal__role"><?php esc_html_e( 'Grade 8 · Roll 12', 'campussian' ); ?></span>
					</div>
				</div>
				<nav class="cmpsian-portal__nav" aria-label="<?php esc_attr_e( 'Portal navigation', 'campussian' ); ?>">
					<?php foreach ( $nav_items as $item ) : ?>
						<span class="cmpsian-portal__navlink<?php echo $item['active'] ? ' is-active' : ''; ?>">
							<?php echo esc_html( $item['label'] ); ?>
						</span>
					<?php endforeach; ?>
				</nav>
			</aside>

			<!-- Main panel -->
			<section class="cmpsian-portal__content">

				<!-- Summary cards -->
				<div class="cmpsian-portal__cards">
					<?php foreach ( $summary as $i => $card ) : ?>
						<div class="cmpsian-portal-card" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 60 ); ?>">
							<span class="cmpsian-portal-card__icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="<?php echo esc_attr( $card['icon'] ); ?>"/></svg>
							</span>
							<span class="cmpsian-portal-card__value"><?php echo esc_html( $card['value'] ); ?></span>
							<span class="cmpsian-portal-card__label"><?php echo esc_html( $card['label'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="row gy-4">
					<!-- Results table -->
					<div class="col-lg-7" data-aos="fade-up">
						<div class="cmpsian-portal-panel">
							<h3 class="cmpsian-portal-panel__title"><?php esc_html_e( 'Recent Results', 'campussian' ); ?></h3>
							<div class="table-responsive">
								<table class="table cmpsian-portal-table">
									<thead>
										<tr>
											<th scope="col"><?php esc_html_e( 'Subject', 'campussian' ); ?></th>
											<th scope="col"><?php esc_html_e( 'Marks', 'campussian' ); ?></th>
											<th scope="col"><?php esc_html_e( 'Grade', 'campussian' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $results as $row ) : ?>
											<tr>
												<th scope="row"><?php echo esc_html( $row['subject'] ); ?></th>
												<td><?php echo esc_html( $row['marks'] ); ?></td>
												<td><span class="cmpsian-grade-pill"><?php echo esc_html( $row['grade'] ); ?></span></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<!-- Fees panel -->
					<div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
						<div class="cmpsian-portal-panel">
							<h3 class="cmpsian-portal-panel__title"><?php esc_html_e( 'Fees Status', 'campussian' ); ?></h3>
							<div class="cmpsian-fees-status">
								<div class="cmpsian-fees-status__row">
									<span><?php esc_html_e( 'August 2026', 'campussian' ); ?></span>
									<span class="cmpsian-badge cmpsian-badge--green"><?php esc_html_e( 'Paid', 'campussian' ); ?></span>
								</div>
								<div class="cmpsian-fees-status__row">
									<span><?php esc_html_e( 'September 2026', 'campussian' ); ?></span>
									<span class="cmpsian-badge cmpsian-badge--orange"><?php esc_html_e( 'Due', 'campussian' ); ?></span>
								</div>
								<div class="cmpsian-fees-status__row">
									<span><?php esc_html_e( 'Exam Fee', 'campussian' ); ?></span>
									<span class="cmpsian-badge cmpsian-badge--green"><?php esc_html_e( 'Paid', 'campussian' ); ?></span>
								</div>
							</div>
							<button type="button" class="cmpsian-btn cmpsian-btn--teal cmpsian-btn--sm cmpsian-btn--block" disabled>
								<?php esc_html_e( 'Pay Now (demo)', 'campussian' ); ?>
							</button>
						</div>
					</div>
				</div>

			</section>
		</div>

	</div>
</main>

<?php
get_footer();
