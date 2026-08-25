<?php
/**
 * Single Teacher Profile.
 *
 * Displays a full teacher profile: photo, name, designation, qualification,
 * bio (content), and social links.
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
?>

<main id="primary" class="cmpsian-main cmpsian-single-teacher">

	<?php
	while ( have_posts() ) :
		the_post();

		$photo         = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		$designation   = get_post_meta( get_the_ID(), '_cmpsian_teacher_designation', true );
		$qualification = get_post_meta( get_the_ID(), '_cmpsian_teacher_qualification', true );
		$facebook      = get_post_meta( get_the_ID(), '_cmpsian_teacher_facebook', true );
		$linkedin      = get_post_meta( get_the_ID(), '_cmpsian_teacher_linkedin', true );
		$twitter       = get_post_meta( get_the_ID(), '_cmpsian_teacher_twitter', true );
		?>

		<section class="cmpsian-page-hero">
			<div class="container">
				<h1 class="cmpsian-page-hero__title"><?php the_title(); ?></h1>
				<nav class="cmpsian-breadcrumb" aria-label="Breadcrumb">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'campussian' ); ?></a>
					<span class="cmpsian-breadcrumb__sep">/</span>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_teacher' ) ); ?>"><?php esc_html_e( 'Teachers', 'campussian' ); ?></a>
					<span class="cmpsian-breadcrumb__sep">/</span>
					<span class="cmpsian-breadcrumb__current"><?php the_title(); ?></span>
				</nav>
			</div>
		</section>

		<div class="container">
			<div class="cmpsian-teacher-profile">
				<div class="cmpsian-teacher-profile__card">
					<div class="cmpsian-teacher-profile__photo">
						<?php if ( $photo ) : ?>
							<img src="<?php echo esc_url( $photo ); ?>" alt="<?php the_title_attribute(); ?>">
						<?php else : ?>
							<div class="cmpsian-placeholder">
								<i class="fas fa-user-graduate"></i>
							</div>
						<?php endif; ?>
					</div>

					<div class="cmpsian-teacher-profile__details">
						<h2 class="cmpsian-teacher-profile__name"><?php the_title(); ?></h2>

						<?php if ( $designation ) : ?>
							<p class="cmpsian-teacher-profile__designation"><?php echo esc_html( $designation ); ?></p>
						<?php endif; ?>

						<?php if ( $qualification ) : ?>
							<p class="cmpsian-teacher-profile__qualification"><?php echo esc_html( $qualification ); ?></p>
						<?php endif; ?>

						<div class="cmpsian-teacher-profile__social">
							<?php if ( $facebook ) : ?>
								<a class="cmpsian-social" href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener">f</a>
							<?php endif; ?>
							<?php if ( $linkedin ) : ?>
								<a class="cmpsian-social" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener">in</a>
							<?php endif; ?>
							<?php if ( $twitter ) : ?>
								<a class="cmpsian-social" href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener">x</a>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="cmpsian-teacher-profile__bio">
					<h3><?php esc_html_e( 'About', 'campussian' ); ?></h3>
					<?php the_content(); ?>
				</div>

				<a class="cmpsian-btn cmpsian-btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_teacher' ) ); ?>">
					&larr; <?php esc_html_e( 'Back to All Teachers', 'campussian' ); ?>
				</a>
			</div>
		</div>

	<?php endwhile; ?>

</main>

<?php
get_footer();