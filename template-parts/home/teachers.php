<?php
/**
 * Homepage Teachers Section — Faculty cards with photo, name, designation, qualification and social links.
 *
 * Pulls teachers from the cmpsian_teacher custom post type.
 * Section visibility controlled via Customizer: cmpsian_sections_enable_teachers.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$enable = cmpsian_get_option( 'cmpsian_sections_enable_teachers' );
if ( ! $enable ) {
	return;
}

$teachers = new WP_Query(
	array(
		'post_type'      => 'cmpsian_teacher',
		'posts_per_page' => 6,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

$archive_url = get_post_type_archive_link( 'cmpsian_teacher' );
?>
<section class="cmpsian-teachers" id="teachers">
	<div class="container">
		<div class="section-header">
			<h2 class="cmpsian-section-title">
				<?php echo esc_html( cmpsian_get_option( 'cmpsian_teachers_title' ) ); ?>
			</h2>
		</div>

		<?php if ( $teachers->have_posts() ) : ?>
			<div class="cmpsian-teachers__grid">
				<?php
				while ( $teachers->have_posts() ) {
					$teachers->the_post();
					$photo         = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
					$designation   = get_post_meta( get_the_ID(), '_cmpsian_teacher_designation', true );
					$qualification = get_post_meta( get_the_ID(), '_cmpsian_teacher_qualification', true );
					$facebook      = get_post_meta( get_the_ID(), '_cmpsian_teacher_facebook', true );
					$linkedin      = get_post_meta( get_the_ID(), '_cmpsian_teacher_linkedin', true );
					$twitter       = get_post_meta( get_the_ID(), '_cmpsian_teacher_twitter', true );
					?>
					<a class="cmpsian-teacher__card cmpsian-teacher__card--link" href="<?php the_permalink(); ?>" data-aos="fade-up">
						<div class="cmpsian-teacher__photo">
							<?php if ( $photo ) : ?>
								<img src="<?php echo esc_url( $photo ); ?>" alt="<?php the_title_attribute(); ?>">
							<?php else : ?>
								<div class="cmpsian-placeholder">
									<i class="fas fa-user-graduate"></i>
								</div>
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
					<?php
				}
				wp_reset_postdata();
				?>
			</div>

			<?php if ( $archive_url ) : ?>
				<div class="cmpsian-teachers__footer">
					<a class="cmpsian-btn cmpsian-btn--outline" href="<?php echo esc_url( $archive_url ); ?>">
						<?php esc_html_e( 'View All Teachers', 'campussian' ); ?>
					</a>
				</div>
			<?php endif; ?>

		<?php else : ?>
			<div class="cmpsian-empty">
				<p><?php esc_html_e( 'No teachers found yet.', 'campussian' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>