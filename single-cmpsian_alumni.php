<?php
/**
 * Single Alumni Profile.
 *
 * Displays a full alumni profile: photo, name, graduation year, institution,
 * testimonial (excerpt), and bio (content).
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

<main id="primary" class="cmpsian-main cmpsian-single-alumni">

	<?php
	while ( have_posts() ) :
		the_post();

		$photo       = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		$year        = get_post_meta( get_the_ID(), '_cmpsian_alumni_graduation_year', true );
		$institution = get_post_meta( get_the_ID(), '_cmpsian_alumni_current_institution', true );
		$testimonial = get_the_excerpt();
		?>

		<section class="cmpsian-page-hero">
			<div class="container">
				<h1 class="cmpsian-page-hero__title"><?php the_title(); ?></h1>
				<nav class="cmpsian-breadcrumb" aria-label="Breadcrumb">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'campussian' ); ?></a>
					<span class="cmpsian-breadcrumb__sep">/</span>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_alumni' ) ); ?>"><?php esc_html_e( 'Alumni', 'campussian' ); ?></a>
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
							<div class="cmpsian-placeholder"><i class="fas fa-user-graduate"></i></div>
						<?php endif; ?>
					</div>

					<div class="cmpsian-teacher-profile__details">
						<h2 class="cmpsian-teacher-profile__name"><?php the_title(); ?></h2>

						<?php if ( $year ) : ?>
							<p class="cmpsian-teacher-profile__designation">
								<?php echo esc_html( sprintf( __( 'Class of %s', 'campussian' ), $year ) ); ?>
							</p>
						<?php endif; ?>

						<?php if ( $institution ) : ?>
							<p class="cmpsian-teacher-profile__qualification">
								<?php echo esc_html( $institution ); ?>
							</p>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( $testimonial ) : ?>
					<div class="cmpsian-teacher-profile__bio">
						<h3><?php esc_html_e( 'Testimonial', 'campussian' ); ?></h3>
						<blockquote class="cmpsian-alumni__testimonial"><?php echo esc_html( $testimonial ); ?></blockquote>
					</div>
				<?php endif; ?>

				<div class="cmpsian-teacher-profile__bio">
					<h3><?php esc_html_e( 'About', 'campussian' ); ?></h3>
					<?php the_content(); ?>
				</div>

				<a class="cmpsian-btn cmpsian-btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'cmpsian_alumni' ) ); ?>">
					&larr; <?php esc_html_e( 'Back to All Alumni', 'campussian' ); ?>
				</a>
			</div>
		</div>

	<?php endwhile; ?>

</main>

<?php
get_footer();