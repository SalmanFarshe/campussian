<?php
/**
 * Template Name: Contact
 *
 * Contact page: contact detail cards (from the Customizer), a dummy contact
 * form (UI only) and an embedded map. No form processing occurs.
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

$phone   = cmpsian_get_option( 'cmpsian_phone' );
$email   = cmpsian_get_option( 'cmpsian_email' );
$address = cmpsian_get_option( 'cmpsian_address' );

// Build a Google Maps embed URL from the address (no API key required).
$map_query = $address ? rawurlencode( $address ) : rawurlencode( 'Dhaka, Bangladesh' );
$map_src   = 'https://www.google.com/maps?q=' . $map_query . '&output=embed';
?>

<main id="primary" class="cmpsian-main cmpsian-contact">

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
				<div class="cmpsian-contact__intro" data-aos="fade-up"><?php the_content(); ?></div>
				<?php
			endif;
		endwhile;
		?>

		<!-- Contact detail cards -->
		<div class="row gy-4 cmpsian-contact-cards">
			<div class="col-md-4" data-aos="fade-up">
				<div class="cmpsian-contact-card">
					<span class="cmpsian-contact-card__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M12 2a7 7 0 00-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1112 6a2.5 2.5 0 010 5.5z"/></svg>
					</span>
					<h3 class="cmpsian-contact-card__title"><?php esc_html_e( 'Address', 'campussian' ); ?></h3>
					<p><?php echo esc_html( $address ? $address : __( 'Dhaka, Bangladesh', 'campussian' ) ); ?></p>
				</div>
			</div>
			<div class="col-md-4" data-aos="fade-up" data-aos-delay="80">
				<div class="cmpsian-contact-card">
					<span class="cmpsian-contact-card__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M6.6 10.8a15.5 15.5 0 006.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 013 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.4 0 .7-.2 1l-2.3 2.2z"/></svg>
					</span>
					<h3 class="cmpsian-contact-card__title"><?php esc_html_e( 'Phone', 'campussian' ); ?></h3>
					<?php if ( $phone ) : ?>
						<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-md-4" data-aos="fade-up" data-aos-delay="160">
				<div class="cmpsian-contact-card">
					<span class="cmpsian-contact-card__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" width="22" height="22"><path fill="currentColor" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm8 7L4 6.5V6l8 5 8-5v.5L12 11z"/></svg>
					</span>
					<h3 class="cmpsian-contact-card__title"><?php esc_html_e( 'Email', 'campussian' ); ?></h3>
					<?php if ( $email ) : ?>
						<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="row gy-4 cmpsian-contact__body">

			<!-- Dummy contact form -->
			<div class="col-lg-6" data-aos="fade-up">
				<div class="cmpsian-card-block">
					<h2 class="cmpsian-section__title"><?php esc_html_e( 'Send Us a Message', 'campussian' ); ?></h2>
					<p class="cmpsian-form__note"><?php esc_html_e( 'Demo form — for layout preview only. Messages are not sent.', 'campussian' ); ?></p>
					<form class="row g-3 cmpsian-form" onsubmit="return false;" novalidate>
						<div class="col-md-6">
							<label class="form-label" for="cmpsian-cf-name"><?php esc_html_e( 'Your Name', 'campussian' ); ?></label>
							<input type="text" class="form-control" id="cmpsian-cf-name" />
						</div>
						<div class="col-md-6">
							<label class="form-label" for="cmpsian-cf-email"><?php esc_html_e( 'Your Email', 'campussian' ); ?></label>
							<input type="email" class="form-control" id="cmpsian-cf-email" />
						</div>
						<div class="col-12">
							<label class="form-label" for="cmpsian-cf-subject"><?php esc_html_e( 'Subject', 'campussian' ); ?></label>
							<input type="text" class="form-control" id="cmpsian-cf-subject" />
						</div>
						<div class="col-12">
							<label class="form-label" for="cmpsian-cf-message"><?php esc_html_e( 'Message', 'campussian' ); ?></label>
							<textarea class="form-control" id="cmpsian-cf-message" rows="5"></textarea>
						</div>
						<div class="col-12">
							<button type="submit" class="cmpsian-btn cmpsian-btn--orange"><?php esc_html_e( 'Send Message', 'campussian' ); ?></button>
						</div>
					</form>
				</div>
			</div>

			<!-- Map -->
			<div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
				<div class="cmpsian-card-block cmpsian-map-block">
					<h2 class="cmpsian-section__title"><?php esc_html_e( 'Find Us', 'campussian' ); ?></h2>
					<div class="cmpsian-map">
						<iframe
							title="<?php esc_attr_e( 'School location map', 'campussian' ); ?>"
							src="<?php echo esc_url( $map_src ); ?>"
							width="100%" height="360" style="border:0;"
							allowfullscreen="" loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"></iframe>
					</div>
				</div>
			</div>

		</div>

	</div>
</main>

<?php
get_footer();
