<?php
/**
 * Archive: All Alumni.
 *
 * Displays a grid of all alumni profiles with search + graduation year filter.
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

$search_query = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
$year_filter  = isset( $_GET['year'] ) ? sanitize_text_field( wp_unslash( $_GET['year'] ) ) : '';

$args = array(
	'post_type'      => 'cmpsian_alumni',
	'posts_per_page' => 12,
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
	'paged'          => max( 1, get_query_var( 'paged' ) ),
);

if ( $search_query ) {
	$args['s'] = $search_query;
}

if ( $year_filter ) {
	$args['meta_query'] = array(
		array(
			'key'     => '_cmpsian_alumni_graduation_year',
			'value'   => $year_filter,
			'compare' => '=',
		),
	);
}

$alumni = new WP_Query( $args );

// Collect unique graduation years for the filter dropdown.
$alumni_ids = get_posts(
	array(
		'post_type'      => 'cmpsian_alumni',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	)
);
$year_list = array();
foreach ( $alumni_ids as $id ) {
	$y = get_post_meta( $id, '_cmpsian_alumni_graduation_year', true );
	if ( $y && ! in_array( $y, $year_list, true ) ) {
		$year_list[] = $y;
	}
}
rsort( $year_list );
?>

<main id="primary" class="cmpsian-main cmpsian-alumni-archive">

	<section class="cmpsian-page-hero">
		<div class="container">
			<h1 class="cmpsian-page-hero__title"><?php esc_html_e( 'Our Alumni', 'campussian' ); ?></h1>
			<nav class="cmpsian-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'campussian' ); ?></a>
				<span class="cmpsian-breadcrumb__sep">/</span>
				<span class="cmpsian-breadcrumb__current"><?php esc_html_e( 'Alumni', 'campussian' ); ?></span>
			</nav>
		</div>
	</section>

	<div class="container">
		<!-- Filter / Search bar -->
		<form class="cmpsian-filter-bar" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_alumni' ) ); ?>">
			<div class="cmpsian-filter-bar__group">
				<label for="cmpsian-alumni-search"><?php esc_html_e( 'Search Alumni', 'campussian' ); ?></label>
				<input type="search" id="cmpsian-alumni-search" name="q" class="cmpsian-select"
					value="<?php echo esc_attr( $search_query ); ?>"
					placeholder="<?php esc_attr_e( 'Name or keyword…', 'campussian' ); ?>">
			</div>

			<div class="cmpsian-filter-bar__group">
				<label for="cmpsian-alumni-year"><?php esc_html_e( 'Filter by Year', 'campussian' ); ?></label>
				<select id="cmpsian-alumni-year" name="year" class="cmpsian-select">
					<option value=""><?php esc_html_e( 'All Years', 'campussian' ); ?></option>
					<?php foreach ( $year_list as $y ) : ?>
						<option value="<?php echo esc_attr( $y ); ?>" <?php selected( $year_filter, $y ); ?>>
							<?php echo esc_html( $y ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<button type="submit" class="cmpsian-btn cmpsian-btn--orange cmpsian-btn--sm"><?php esc_html_e( 'Filter', 'campussian' ); ?></button>
			<a class="cmpsian-filter-bar__reset" href="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_alumni' ) ); ?>"><?php esc_html_e( 'Reset', 'campussian' ); ?></a>
		</form>

		<?php if ( $alumni->have_posts() ) : ?>
			<div class="cmpsian-alumni__grid">
				<?php
				while ( $alumni->have_posts() ) :
					$alumni->the_post();
					$photo       = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
					$year        = get_post_meta( get_the_ID(), '_cmpsian_alumni_graduation_year', true );
					$institution = get_post_meta( get_the_ID(), '_cmpsian_alumni_current_institution', true );
					$testimonial = get_the_excerpt();
					?>
					<a class="cmpsian-alumni__card cmpsian-alumni__card--link" href="<?php the_permalink(); ?>" data-aos="fade-up">
						<div class="cmpsian-alumni__photo">
							<?php if ( $photo ) : ?>
								<img src="<?php echo esc_url( $photo ); ?>" alt="<?php the_title_attribute(); ?>">
							<?php else : ?>
								<div class="cmpsian-placeholder"><i class="fas fa-user-graduate"></i></div>
							<?php endif; ?>
							<?php if ( $year ) : ?>
								<span class="cmpsian-alumni__year"><?php echo esc_html( $year ); ?></span>
							<?php endif; ?>
						</div>
						<div class="cmpsian-alumni__details">
							<h3 class="cmpsian-alumni__name"><?php the_title(); ?></h3>
							<?php if ( $institution ) : ?>
								<p class="cmpsian-alumni__institution"><?php echo esc_html( $institution ); ?></p>
							<?php endif; ?>
							<?php if ( $testimonial ) : ?>
								<blockquote class="cmpsian-alumni__testimonial"><?php echo esc_html( wp_trim_words( $testimonial, 20, '…' ) ); ?></blockquote>
							<?php endif; ?>
							<span class="cmpsian-teacher__view-profile"><?php esc_html_e( 'View Profile →', 'campussian' ); ?></span>
						</div>
					</a>
				<?php endwhile; ?>
			</div>

			<?php
			// Pagination.
			the_posts_pagination(
				array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
				)
			);
			?>

		<?php else : ?>
			<div class="cmpsian-empty">
				<p><?php esc_html_e( 'No alumni found. Try adjusting your search or filter.', 'campussian' ); ?></p>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>

</main>

<?php
get_footer();