<?php
/**
 * Homepage Alumni Section — Success Stories Wall.
 *
 * Pulls alumni from the cmpsian_alumni custom post type.
 * Section visibility controlled via Customizer: cmpsian_sections_enable_alumni.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$enable = cmpsian_get_option( 'cmpsian_sections_enable_alumni' );
if ( ! $enable ) {
	return;
}

$alumni = new WP_Query(
	array(
		'post_type'      => 'cmpsian_alumni',
		'posts_per_page' => 8,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

$archive_url = get_post_type_archive_link( 'cmpsian_alumni' );
?>
<section class="cmpsian-alumni" id="alumni">
	<div class="container">
		<div class="section-header">
			<h2 class="cmpsian-section-title">
				<?php echo esc_html( cmpsian_get_option( 'cmpsian_alumni_title' ) ); ?>
			</h2>
		</div>

		<?php if ( $alumni->have_posts() ) : ?>
			<div class="cmpsian-alumni__grid">
				<?php
				while ( $alumni->have_posts() ) {
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
					<?php
				}
				wp_reset_postdata();
				?>
			</div>

			<?php if ( $archive_url ) : ?>
				<div class="cmpsian-teachers__footer">
					<a class="cmpsian-btn cmpsian-btn--outline" href="<?php echo esc_url( $archive_url ); ?>">
						<?php esc_html_e( 'View All Alumni', 'campussian' ); ?>
					</a>
				</div>
			<?php endif; ?>

		<?php else : ?>
			<div class="cmpsian-empty">
				<p><?php esc_html_e( 'No alumni found yet.', 'campussian' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>