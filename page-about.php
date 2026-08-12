<?php
/**
 * Template Name: About
 *
 * About page: mission/vision cards, a short "our story" area (page content) and
 * a set of school highlights. Designed to work with or without editor content.
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

$pillars = array(
	array(
		'title' => __( 'Our Mission', 'campussian' ),
		'text'  => __( 'To deliver holistic, values-based education that empowers students to become confident, compassionate and capable lifelong learners.', 'campussian' ),
		'icon'  => 'M12 2L2 7l10 5 10-5-10-5zm0 13l-8-4v6l8 4 8-4v-6l-8 4z',
	),
	array(
		'title' => __( 'Our Vision', 'campussian' ),
		'text'  => __( 'To be a leading centre of academic excellence recognised for nurturing responsible global citizens and future leaders.', 'campussian' ),
		'icon'  => 'M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12a5 5 0 110-10 5 5 0 010 10z',
	),
	array(
		'title' => __( 'Our Values', 'campussian' ),
		'text'  => __( 'Integrity, respect, curiosity and perseverance guide everything we do — inside and beyond the classroom.', 'campussian' ),
		'icon'  => 'M12 21s-7-4.5-9.5-9A5.5 5.5 0 0112 5a5.5 5.5 0 019.5 7c-2.5 4.5-9.5 9-9.5 9z',
	),
);

$highlights = array(
	__( 'Experienced and caring faculty', 'campussian' ),
	__( 'Smart, technology-enabled classrooms', 'campussian' ),
	__( 'Well-equipped science &amp; computer labs', 'campussian' ),
	__( 'Sports, music, art &amp; debate clubs', 'campussian' ),
	__( 'Safe, secure and inclusive campus', 'campussian' ),
	__( 'Strong track record of board results', 'campussian' ),
);
?>

<main id="primary" class="cmpsian-main cmpsian-about">

	<header class="cmpsian-page-hero" data-aos="fade-up">
		<div class="container">
			<h1 class="cmpsian-page-hero__title"><?php the_title(); ?></h1>
			<?php cmpsian_breadcrumb(); ?>
		</div>
	</header>

	<div class="container">

		<!-- Mission / Vision / Values -->
		<div class="row gy-4 cmpsian-pillars">
			<?php foreach ( $pillars as $i => $pillar ) : ?>
				<div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 80 ); ?>">
					<div class="cmpsian-pillar">
						<span class="cmpsian-pillar__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" width="26" height="26"><path fill="currentColor" d="<?php echo esc_attr( $pillar['icon'] ); ?>"/></svg>
						</span>
						<h3 class="cmpsian-pillar__title"><?php echo esc_html( $pillar['title'] ); ?></h3>
						<p class="cmpsian-pillar__text"><?php echo esc_html( $pillar['text'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Our story (page content) -->
		<?php
		while ( have_posts() ) :
			the_post();
			if ( trim( get_the_content() ) ) :
				?>
				<div class="row align-items-center gy-4 cmpsian-about__story">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="col-lg-6" data-aos="fade-up">
							<div class="cmpsian-about__media"><?php the_post_thumbnail( 'large' ); ?></div>
						</div>
						<div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
							<div class="cmpsian-about__content"><?php the_content(); ?></div>
						</div>
					<?php else : ?>
						<div class="col-12" data-aos="fade-up">
							<div class="cmpsian-about__content"><?php the_content(); ?></div>
						</div>
					<?php endif; ?>
				</div>
				<?php
			endif;
		endwhile;
		?>

		<!-- Highlights -->
		<div class="cmpsian-card-block" data-aos="fade-up">
			<h2 class="cmpsian-section__title text-center"><?php esc_html_e( 'What Makes Us Different', 'campussian' ); ?></h2>
			<div class="row gy-3 cmpsian-highlights">
				<?php foreach ( $highlights as $i => $item ) : ?>
					<div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( ( $i % 3 ) * 60 ); ?>">
						<span class="cmpsian-highlight">
							<span class="cmpsian-highlight__check" aria-hidden="true">
								<svg viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.4-1.4z"/></svg>
							</span>
							<?php echo wp_kses_post( $item ); ?>
						</span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</main>

<?php
get_footer();
