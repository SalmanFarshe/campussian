<?php
/**
 * Archive: All Teachers.
 *
 * Displays a grid of all teacher profiles with search + designation filter.
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
$designation  = isset( $_GET['designation'] ) ? sanitize_text_field( wp_unslash( $_GET['designation'] ) ) : '';

$args = array(
	'post_type'      => 'cmpsian_teacher',
	'posts_per_page' => 12,
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
	'paged'          => max( 1, get_query_var( 'paged' ) ),
);

if ( $search_query ) {
	$args['s'] = $search_query;
}

if ( $designation ) {
	$args['meta_query'] = array(
		array(
			'key'     => '_cmpsian_teacher_designation',
			'value'   => $designation,
			'compare' => 'LIKE',
		),
	);
}

$teachers = new WP_Query( $args );

// Collect unique designations for the filter dropdown.
$designations = get_posts(
	array(
		'post_type'      => 'cmpsian_teacher',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	)
);
$designation_list = array();
foreach ( $designations as $id ) {
	$d = get_post_meta( $id, '_cmpsian_teacher_designation', true );
	if ( $d && ! in_array( $d, $designation_list, true ) ) {
		$designation_list[] = $d;
	}
}
?>

<main id="primary" class="cmpsian-main cmpsian-teachers-archive">

	<section class="cmpsian-page-hero">
		<div class="container">
			<h1 class="cmpsian-page-hero__title"><?php esc_html_e( 'Our Teachers', 'campussian' ); ?></h1>
			<nav class="cmpsian-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'campussian' ); ?></a>
				<span class="cmpsian-breadcrumb__sep">/</span>
				<span class="cmpsian-breadcrumb__current"><?php esc_html_e( 'Teachers', 'campussian' ); ?></span>
			</nav>
		</div>
	</section>

	<div class="container">
		<!-- Filter / Search bar -->
		<form class="cmpsian-filter-bar" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_teacher' ) ); ?>">
			<div class="cmpsian-filter-bar__group">
				<label for="cmpsian-teacher-search"><?php esc_html_e( 'Search Teachers', 'campussian' ); ?></label>
				<input type="search" id="cmpsian-teacher-search" name="q" class="cmpsian-select"
					value="<?php echo esc_attr( $search_query ); ?>"
					placeholder="<?php esc_attr_e( 'Name or keyword…', 'campussian' ); ?>">
			</div>

			<div class="cmpsian-filter-bar__group">
				<label for="cmpsian-teacher-designation"><?php esc_html_e( 'Filter by Designation', 'campussian' ); ?></label>
				<select id="cmpsian-teacher-designation" name="designation" class="cmpsian-select">
					<option value=""><?php esc_html_e( 'All Designations', 'campussian' ); ?></option>
					<?php foreach ( $designation_list as $d ) : ?>
						<option value="<?php echo esc_attr( $d ); ?>" <?php selected( $designation, $d ); ?>>
							<?php echo esc_html( $d ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<button type="submit" class="cmpsian-btn cmpsian-btn--orange cmpsian-btn--sm"><?php esc_html_e( 'Filter', 'campussian' ); ?></button>
			<a class="cmpsian-filter-bar__reset" href="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_teacher' ) ); ?>"><?php esc_html_e( 'Reset', 'campussian' ); ?></a>
		</form>

		<?php if ( $teachers->have_posts() ) : ?>
			<div class="cmpsian-teachers__grid">
				<?php
				while ( $teachers->have_posts() ) :
					$teachers->the_post();
					$photo         = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
					$designation   = get_post_meta( get_the_ID(), '_cmpsian_teacher_designation', true );
					$qualification = get_post_meta( get_the_ID(), '_cmpsian_teacher_qualification', true );
					?>
					<a class="cmpsian-teacher__card cmpsian-teacher__card--link" href="<?php the_permalink(); ?>" data-aos="fade-up">
						<div class="cmpsian-teacher__photo">
							<?php if ( $photo ) : ?>
								<img src="<?php echo esc_url( $photo ); ?>" alt="<?php the_title_attribute(); ?>">
							<?php else : ?>
								<div class="cmpsian-placeholder"><i class="fas fa-user-graduate"></i></div>
							<?php endif; ?>
						</div>
						<div class="cmpsian-teacher__details">
							<h3 class="cmpsian-teacher__name"><?php the_title(); ?></h3>
							<?php if ( $designation ) : ?>
								<p class="cmpsian-teacher__designation"><?php echo esc_html( $designation ); ?></p>
							<?php endif; ?>
							<?php if ( $qualification ) : ?>
								<p class="cmpsian-teacher__qualification"><?php echo esc_html( $qualification ); ?></p>
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
				<p><?php esc_html_e( 'No teachers found. Try adjusting your search or filter.', 'campussian' ); ?></p>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>

</main>

<?php
get_footer();