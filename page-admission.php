<?php
/**
 * Template Name: Admission
 *
 * Admission landing page: criteria list, a responsive fee-breakdown table and a
 * front-end dummy application form (UI only, no processing). All content is
 * static demo markup that can be replaced by the page editor content above it.
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

// Demo admission criteria.
$criteria = array(
	__( 'Completed application form with recent passport-size photograph.', 'campussian' ),
	__( 'Photocopy of the previous school report card / transcript.', 'campussian' ),
	__( 'Birth certificate or National ID of the student.', 'campussian' ),
	__( 'Two copies of parent/guardian photographs.', 'campussian' ),
	__( 'Admission test and short interview (grade dependent).', 'campussian' ),
);

// Demo fee breakdown.
$fees = array(
	array( 'grade' => __( 'Play – KG', 'campussian' ),      'admission' => '5,000',  'monthly' => '2,500', 'annual' => '3,000' ),
	array( 'grade' => __( 'Grade 1 – 5', 'campussian' ),    'admission' => '7,000',  'monthly' => '3,200', 'annual' => '3,500' ),
	array( 'grade' => __( 'Grade 6 – 8', 'campussian' ),    'admission' => '9,000',  'monthly' => '4,000', 'annual' => '4,000' ),
	array( 'grade' => __( 'Grade 9 – 10', 'campussian' ),   'admission' => '12,000', 'monthly' => '5,000', 'annual' => '5,000' ),
);
?>

<main id="primary" class="cmpsian-main cmpsian-admission">

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
				<div class="cmpsian-admission__intro" data-aos="fade-up"><?php the_content(); ?></div>
				<?php
			endif;
		endwhile;
		?>

		<div class="row gy-4">
			<!-- Criteria -->
			<div class="col-lg-6" data-aos="fade-up">
				<div class="cmpsian-card-block">
					<h2 class="cmpsian-section__title"><?php esc_html_e( 'Admission Criteria', 'campussian' ); ?></h2>
					<ul class="cmpsian-criteria">
						<?php foreach ( $criteria as $item ) : ?>
							<li class="cmpsian-criteria__item">
								<span class="cmpsian-criteria__check" aria-hidden="true">
									<svg viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.4-1.4z"/></svg>
								</span>
								<?php echo esc_html( $item ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>

			<!-- Fee breakdown -->
			<div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
				<div class="cmpsian-card-block">
					<h2 class="cmpsian-section__title"><?php esc_html_e( 'Fee Breakdown', 'campussian' ); ?></h2>
					<div class="table-responsive">
						<table class="table cmpsian-fee-table">
							<thead>
								<tr>
									<th scope="col"><?php esc_html_e( 'Grade', 'campussian' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Admission', 'campussian' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Monthly', 'campussian' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Annual', 'campussian' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $fees as $row ) : ?>
									<tr>
										<th scope="row"><?php echo esc_html( $row['grade'] ); ?></th>
										<td>৳<?php echo esc_html( $row['admission'] ); ?></td>
										<td>৳<?php echo esc_html( $row['monthly'] ); ?></td>
										<td>৳<?php echo esc_html( $row['annual'] ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<p class="cmpsian-fee-table__note"><?php esc_html_e( '* Figures shown are indicative demo values. Contact the office for the current fee structure.', 'campussian' ); ?></p>
				</div>
			</div>
		</div>

		<!-- Dummy application form (UI only) -->
		<div class="cmpsian-card-block cmpsian-admission-form" data-aos="fade-up">
			<h2 class="cmpsian-section__title"><?php esc_html_e( 'Online Application (Demo)', 'campussian' ); ?></h2>
			<p class="cmpsian-admission-form__note"><?php esc_html_e( 'This is a front-end demo form for layout preview only — submissions are not processed.', 'campussian' ); ?></p>

			<form class="row g-3 cmpsian-form" onsubmit="return false;" novalidate>
				<div class="col-md-6">
					<label class="form-label" for="cmpsian-af-name"><?php esc_html_e( "Student's Full Name", 'campussian' ); ?></label>
					<input type="text" class="form-control" id="cmpsian-af-name" placeholder="<?php esc_attr_e( 'e.g. Ayaan Rahman', 'campussian' ); ?>" />
				</div>
				<div class="col-md-6">
					<label class="form-label" for="cmpsian-af-dob"><?php esc_html_e( 'Date of Birth', 'campussian' ); ?></label>
					<input type="date" class="form-control" id="cmpsian-af-dob" />
				</div>
				<div class="col-md-6">
					<label class="form-label" for="cmpsian-af-grade"><?php esc_html_e( 'Grade Applying For', 'campussian' ); ?></label>
					<select class="form-select" id="cmpsian-af-grade">
						<option value=""><?php esc_html_e( 'Select a grade', 'campussian' ); ?></option>
						<?php foreach ( $fees as $row ) : ?>
							<option><?php echo esc_html( $row['grade'] ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-6">
					<label class="form-label" for="cmpsian-af-guardian"><?php esc_html_e( "Guardian's Name", 'campussian' ); ?></label>
					<input type="text" class="form-control" id="cmpsian-af-guardian" />
				</div>
				<div class="col-md-6">
					<label class="form-label" for="cmpsian-af-phone"><?php esc_html_e( 'Phone', 'campussian' ); ?></label>
					<input type="tel" class="form-control" id="cmpsian-af-phone" />
				</div>
				<div class="col-md-6">
					<label class="form-label" for="cmpsian-af-email"><?php esc_html_e( 'Email', 'campussian' ); ?></label>
					<input type="email" class="form-control" id="cmpsian-af-email" />
				</div>
				<div class="col-12">
					<label class="form-label" for="cmpsian-af-message"><?php esc_html_e( 'Message (optional)', 'campussian' ); ?></label>
					<textarea class="form-control" id="cmpsian-af-message" rows="3"></textarea>
				</div>
				<div class="col-12">
					<button type="submit" class="cmpsian-btn cmpsian-btn--orange"><?php esc_html_e( 'Submit Application', 'campussian' ); ?></button>
				</div>
			</form>
		</div>

	</div>
</main>

<?php
get_footer();
