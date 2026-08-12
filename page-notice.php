<?php
/**
 * Template Name: Notices Board
 *
 * A date-filterable notice board. Displays notice cards with a year/month
 * filter bar and a Bootstrap modal preview that supports both inline PDF and
 * text content. Notices are pulled from the "cmpsian_notice" custom post type.
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

// Read filters from the query string (sanitised).
$filter_year  = isset( $_GET['ny'] ) ? absint( $_GET['ny'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter.
$filter_month = isset( $_GET['nm'] ) ? absint( $_GET['nm'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter.
$paged        = max( 1, get_query_var( 'paged' ) ? get_query_var( 'paged' ) : ( get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 ) );

// Build the query.
$args = array(
	'post_type'      => 'cmpsian_notice',
	'posts_per_page' => 9,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

$date_query = array();
if ( $filter_year ) {
	$date_query['year'] = $filter_year;
}
if ( $filter_month ) {
	$date_query['month'] = $filter_month;
}
if ( ! empty( $date_query ) ) {
	$args['date_query'] = array( $date_query );
}

$notices = new WP_Query( $args );

// Gather available years for the filter (from all notices).
$year_list = array();
$all_years = new WP_Query(
	array(
		'post_type'      => 'cmpsian_notice',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	)
);
if ( $all_years->have_posts() ) {
	foreach ( $all_years->posts as $pid ) {
		$year_list[ get_the_date( 'Y', $pid ) ] = true;
	}
}
wp_reset_postdata();
$year_list = array_keys( $year_list );
rsort( $year_list );

$months = array(
	1  => __( 'January', 'campussian' ),
	2  => __( 'February', 'campussian' ),
	3  => __( 'March', 'campussian' ),
	4  => __( 'April', 'campussian' ),
	5  => __( 'May', 'campussian' ),
	6  => __( 'June', 'campussian' ),
	7  => __( 'July', 'campussian' ),
	8  => __( 'August', 'campussian' ),
	9  => __( 'September', 'campussian' ),
	10 => __( 'October', 'campussian' ),
	11 => __( 'November', 'campussian' ),
	12 => __( 'December', 'campussian' ),
);

$page_url = get_permalink();
?>

<main id="primary" class="cmpsian-main cmpsian-notice-board">

	<header class="cmpsian-page-hero" data-aos="fade-up">
		<div class="container">
			<h1 class="cmpsian-page-hero__title"><?php the_title(); ?></h1>
			<?php cmpsian_breadcrumb(); ?>
		</div>
	</header>

	<div class="container">

		<?php // Optional intro content from the page editor. ?>
		<?php
		while ( have_posts() ) :
			the_post();
			if ( trim( get_the_content() ) ) :
				?>
				<div class="cmpsian-notice-board__intro" data-aos="fade-up"><?php the_content(); ?></div>
				<?php
			endif;
		endwhile;
		?>

		<!-- Date filter bar -->
		<form class="cmpsian-filter-bar" method="get" action="<?php echo esc_url( $page_url ); ?>" data-aos="fade-up">
			<div class="cmpsian-filter-bar__group">
				<label for="cmpsian-ny"><?php esc_html_e( 'Year', 'campussian' ); ?></label>
				<select id="cmpsian-ny" name="ny" class="cmpsian-select">
					<option value="0"><?php esc_html_e( 'All Years', 'campussian' ); ?></option>
					<?php foreach ( $year_list as $y ) : ?>
						<option value="<?php echo esc_attr( $y ); ?>" <?php selected( $filter_year, $y ); ?>><?php echo esc_html( $y ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="cmpsian-filter-bar__group">
				<label for="cmpsian-nm"><?php esc_html_e( 'Month', 'campussian' ); ?></label>
				<select id="cmpsian-nm" name="nm" class="cmpsian-select">
					<option value="0"><?php esc_html_e( 'All Months', 'campussian' ); ?></option>
					<?php foreach ( $months as $num => $name ) : ?>
						<option value="<?php echo esc_attr( $num ); ?>" <?php selected( $filter_month, $num ); ?>><?php echo esc_html( $name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<button type="submit" class="cmpsian-btn cmpsian-btn--teal cmpsian-btn--sm"><?php esc_html_e( 'Filter', 'campussian' ); ?></button>
			<?php if ( $filter_year || $filter_month ) : ?>
				<a class="cmpsian-filter-bar__reset" href="<?php echo esc_url( $page_url ); ?>"><?php esc_html_e( 'Reset', 'campussian' ); ?></a>
			<?php endif; ?>
		</form>

		<!-- Notice grid -->
		<?php if ( $notices->have_posts() ) : ?>
			<div class="row gy-4 cmpsian-notice-grid">
				<?php
				while ( $notices->have_posts() ) :
					$notices->the_post();
					$pdf        = get_post_meta( get_the_ID(), '_cmpsian_notice_pdf', true );
					$modal_id   = 'cmpsianNotice' . get_the_ID();
					$excerpt    = wp_strip_all_tags( get_the_excerpt() );
					?>
					<div class="col-md-6 col-lg-4" data-aos="fade-up">
						<article class="cmpsian-notice-card">
							<div class="cmpsian-notice-card__top">
								<span class="cmpsian-notice-card__date">
									<span class="cmpsian-notice-card__day"><?php echo esc_html( get_the_date( 'd' ) ); ?></span>
									<span class="cmpsian-notice-card__mon"><?php echo esc_html( get_the_date( 'M Y' ) ); ?></span>
								</span>
								<?php if ( $pdf ) : ?>
									<span class="cmpsian-notice-card__pdf" aria-label="<?php esc_attr_e( 'Has PDF attachment', 'campussian' ); ?>">PDF</span>
								<?php endif; ?>
							</div>

							<h3 class="cmpsian-notice-card__title"><?php the_title(); ?></h3>

							<?php if ( $excerpt ) : ?>
								<p class="cmpsian-notice-card__excerpt"><?php echo esc_html( wp_trim_words( $excerpt, 18 ) ); ?></p>
							<?php endif; ?>

							<div class="cmpsian-notice-card__actions">
								<button type="button" class="cmpsian-btn cmpsian-btn--outline cmpsian-btn--sm"
									data-bs-toggle="modal" data-bs-target="#<?php echo esc_attr( $modal_id ); ?>">
									<?php esc_html_e( 'Preview', 'campussian' ); ?>
								</button>
								<a class="cmpsian-notice-card__full" href="<?php the_permalink(); ?>">
									<?php esc_html_e( 'Full page', 'campussian' ); ?> &rarr;
								</a>
							</div>
						</article>

						<!-- Preview modal -->
						<div class="modal fade cmpsian-notice-modal" id="<?php echo esc_attr( $modal_id ); ?>" tabindex="-1"
							aria-labelledby="<?php echo esc_attr( $modal_id ); ?>Label" aria-hidden="true">
							<div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="<?php echo esc_attr( $modal_id ); ?>Label"><?php the_title(); ?></h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'campussian' ); ?>"></button>
									</div>
									<div class="modal-body">
										<p class="cmpsian-notice-modal__date">
											<?php echo esc_html( cmpsian_notice_date() ); ?>
										</p>

										<div class="cmpsian-notice-modal__text">
											<?php the_content(); ?>
										</div>

										<?php if ( $pdf ) : ?>
											<div class="cmpsian-notice-modal__pdf">
												<object data="<?php echo esc_url( $pdf ); ?>" type="application/pdf" width="100%" height="500">
													<p>
														<?php esc_html_e( 'Unable to display the PDF inline.', 'campussian' ); ?>
														<a href="<?php echo esc_url( $pdf ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Open PDF', 'campussian' ); ?></a>
													</p>
												</object>
											</div>
										<?php endif; ?>
									</div>
									<div class="modal-footer">
										<?php if ( $pdf ) : ?>
											<a class="cmpsian-btn cmpsian-btn--teal cmpsian-btn--sm" href="<?php echo esc_url( $pdf ); ?>" target="_blank" rel="noopener noreferrer">
												<?php esc_html_e( 'Download PDF', 'campussian' ); ?>
											</a>
										<?php endif; ?>
										<a class="cmpsian-btn cmpsian-btn--outline cmpsian-btn--sm" href="<?php the_permalink(); ?>">
											<?php esc_html_e( 'Open full page', 'campussian' ); ?>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				endwhile;
				?>
			</div>

			<!-- Pagination -->
			<div class="cmpsian-pagination" data-aos="fade-up">
				<?php
				echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- paginate_links returns safe markup.
					array(
						'base'      => trailingslashit( $page_url ) . '%_%',
						'format'    => 'page/%#%/',
						'current'   => $paged,
						'total'     => $notices->max_num_pages,
						'add_args'  => array_filter(
							array(
								'ny' => $filter_year ? $filter_year : false,
								'nm' => $filter_month ? $filter_month : false,
							)
						),
						'prev_text' => esc_html__( '&larr; Prev', 'campussian' ),
						'next_text' => esc_html__( 'Next &rarr;', 'campussian' ),
					)
				);
				?>
			</div>

		<?php else : ?>
			<div class="cmpsian-empty" data-aos="fade-up">
				<p><?php esc_html_e( 'No notices match your filter. Try a different year or month.', 'campussian' ); ?></p>
			</div>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

	</div>
</main>

<?php
get_footer();
