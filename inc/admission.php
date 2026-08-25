<?php
/**
 * Admission system: AJAX form handler, admin approval workflow, student list.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle the admission form submission via AJAX.
 */
function cmpsian_handle_admission_submission() {
	check_ajax_referer( 'cmpsian_admission_nonce', 'nonce' );

	$student_name = isset( $_POST['student_name'] ) ? sanitize_text_field( wp_unslash( $_POST['student_name'] ) ) : '';
	$dob          = isset( $_POST['dob'] ) ? sanitize_text_field( wp_unslash( $_POST['dob'] ) ) : '';
	$grade        = isset( $_POST['grade'] ) ? sanitize_text_field( wp_unslash( $_POST['grade'] ) ) : '';
	$guardian     = isset( $_POST['guardian'] ) ? sanitize_text_field( wp_unslash( $_POST['guardian'] ) ) : '';
	$phone        = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$email        = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message      = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( ! $student_name || ! $grade || ! $phone ) {
		wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'campussian' ) ) );
	}

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'cmpsian_application',
			'post_title'  => $student_name,
			'post_status' => 'publish',
		)
	);

	if ( is_wp_error( $post_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Could not save application. Please try again.', 'campussian' ) ) );
	}

	update_post_meta( $post_id, '_cmpsian_app_student_name', $student_name );
	update_post_meta( $post_id, '_cmpsian_app_dob', $dob );
	update_post_meta( $post_id, '_cmpsian_app_grade', $grade );
	update_post_meta( $post_id, '_cmpsian_app_guardian', $guardian );
	update_post_meta( $post_id, '_cmpsian_app_phone', $phone );
	update_post_meta( $post_id, '_cmpsian_app_email', $email );
	update_post_meta( $post_id, '_cmpsian_app_message', $message );
	update_post_meta( $post_id, '_cmpsian_app_status', 'pending' );

	wp_send_json_success( array( 'message' => __( 'Application submitted successfully! We will contact you soon.', 'campussian' ) ) );
}
add_action( 'wp_ajax_cmpsian_submit_admission', 'cmpsian_handle_admission_submission' );
add_action( 'wp_ajax_nopriv_cmpsian_submit_admission', 'cmpsian_handle_admission_submission' );

/**
 * Approve an application → create a student.
 */
function cmpsian_approve_application() {
	check_ajax_referer( 'cmpsian_admin_approve', 'nonce' );

	$app_id = isset( $_POST['app_id'] ) ? absint( $_POST['app_id'] ) : 0;
	if ( ! $app_id || 'cmpsian_application' !== get_post_type( $app_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid application.', 'campussian' ) ) );
	}

	$student_name = get_post_meta( $app_id, '_cmpsian_app_student_name', true );
	$dob          = get_post_meta( $app_id, '_cmpsian_app_dob', true );
	$grade        = get_post_meta( $app_id, '_cmpsian_app_grade', true );
	$guardian     = get_post_meta( $app_id, '_cmpsian_app_guardian', true );
	$phone        = get_post_meta( $app_id, '_cmpsian_app_phone', true );
	$email        = get_post_meta( $app_id, '_cmpsian_app_email', true );

	$student_id = wp_insert_post(
		array(
			'post_type'    => 'cmpsian_student',
			'post_title'   => $student_name,
			'post_status'  => 'publish',
		)
	);

	if ( is_wp_error( $student_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Could not create student.', 'campussian' ) ) );
	}

	update_post_meta( $student_id, '_cmpsian_student_dob', $dob );
	update_post_meta( $student_id, '_cmpsian_student_grade', $grade );
	update_post_meta( $student_id, '_cmpsian_student_guardian', $guardian );
	update_post_meta( $student_id, '_cmpsian_student_phone', $phone );
	update_post_meta( $student_id, '_cmpsian_student_email', $email );
	update_post_meta( $student_id, '_cmpsian_student_roll', '' );

	// Mark application as approved.
	update_post_meta( $app_id, '_cmpsian_app_status', 'approved' );

	wp_send_json_success( array( 'message' => __( 'Application approved and student created.', 'campussian' ) ) );
}
add_action( 'wp_ajax_cmpsian_approve_application', 'cmpsian_approve_application' );

/**
 * Reject an application.
 */
function cmpsian_reject_application() {
	check_ajax_referer( 'cmpsian_admin_approve', 'nonce' );

	$app_id = isset( $_POST['app_id'] ) ? absint( $_POST['app_id'] ) : 0;
	if ( ! $app_id || 'cmpsian_application' !== get_post_type( $app_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid application.', 'campussian' ) ) );
	}

	update_post_meta( $app_id, '_cmpsian_app_status', 'rejected' );
	wp_send_json_success( array( 'message' => __( 'Application rejected.', 'campussian' ) ) );
}
add_action( 'wp_ajax_cmpsian_reject_application', 'cmpsian_reject_application' );

/**
 * Add a student manually from admin.
 */
function cmpsian_admin_add_student() {
	check_ajax_referer( 'cmpsian_admin_add_student', 'nonce' );

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$dob     = isset( $_POST['dob'] ) ? sanitize_text_field( wp_unslash( $_POST['dob'] ) ) : '';
	$grade   = isset( $_POST['grade'] ) ? sanitize_text_field( wp_unslash( $_POST['grade'] ) ) : '';
	$guardian = isset( $_POST['guardian'] ) ? sanitize_text_field( wp_unslash( $_POST['guardian'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$address = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';
	$roll    = isset( $_POST['roll'] ) ? sanitize_text_field( wp_unslash( $_POST['roll'] ) ) : '';

	if ( ! $name || ! $grade ) {
		wp_send_json_error( array( 'message' => __( 'Name and grade are required.', 'campussian' ) ) );
	}

	$student_id = wp_insert_post(
		array(
			'post_type'    => 'cmpsian_student',
			'post_title'   => $name,
			'post_status'  => 'publish',
		)
	);

	if ( is_wp_error( $student_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Could not add student.', 'campussian' ) ) );
	}

	update_post_meta( $student_id, '_cmpsian_student_dob', $dob );
	update_post_meta( $student_id, '_cmpsian_student_grade', $grade );
	update_post_meta( $student_id, '_cmpsian_student_guardian', $guardian );
	update_post_meta( $student_id, '_cmpsian_student_phone', $phone );
	update_post_meta( $student_id, '_cmpsian_student_email', $email );
	update_post_meta( $student_id, '_cmpsian_student_address', $address );
	update_post_meta( $student_id, '_cmpsian_student_roll', $roll );

	wp_send_json_success( array( 'message' => __( 'Student added successfully.', 'campussian' ) ) );
}
add_action( 'wp_ajax_cmpsian_admin_add_student', 'cmpsian_admin_add_student' );

/**
 * Enqueue admin scripts for the admission workflow.
 */
function cmpsian_admin_admission_assets( $hook ) {
	if ( 'edit.php' !== $hook && 'post.php' !== $hook ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array( 'cmpsian_application', 'cmpsian_student' ), true ) ) {
		return;
	}

	wp_enqueue_script(
		'cmpsian-admin-admission',
		WCBD_CAMPUSSIAN_URI . 'assets/js/admin-admission.js',
		array( 'jquery' ),
		WCBD_CAMPUSSIAN_VERSION,
		true
	);

	wp_localize_script(
		'cmpsian-admin-admission',
		'CampussianAdmin',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'approveNonce' => wp_create_nonce( 'cmpsian_admin_approve' ),
			'addStudentNonce' => wp_create_nonce( 'cmpsian_admin_add_student' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'cmpsian_admin_admission_assets' );

/**
 * Add custom columns to the application list.
 */
function cmpsian_application_columns( $columns ) {
	$columns['student_name'] = __( 'Student', 'campussian' );
	$columns['grade']        = __( 'Grade', 'campussian' );
	$columns['phone']        = __( 'Phone', 'campussian' );
	$columns['status']       = __( 'Status', 'campussian' );
	$columns['actions']      = __( 'Actions', 'campussian' );
	return $columns;
}
add_filter( 'manage_cmpsian_application_posts_columns', 'cmpsian_application_columns' );

function cmpsian_application_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'student_name':
			echo esc_html( get_post_meta( $post_id, '_cmpsian_app_student_name', true ) );
			break;
		case 'grade':
			echo esc_html( get_post_meta( $post_id, '_cmpsian_app_grade', true ) );
			break;
		case 'phone':
			echo esc_html( get_post_meta( $post_id, '_cmpsian_app_phone', true ) );
			break;
		case 'status':
			$status = get_post_meta( $post_id, '_cmpsian_app_status', true );
			$labels = array( 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected' );
			$color  = 'pending' === $status ? 'orange' : ( 'approved' === $status ? 'green' : 'red' );
			echo '<span class="cmpsian-status cmpsian-status--' . esc_attr( $color ) . '">' . esc_html( isset( $labels[ $status ] ) ? $labels[ $status ] : $status ) . '</span>';
			break;
		case 'actions':
			$status = get_post_meta( $post_id, '_cmpsian_app_status', true );
			if ( 'pending' === $status ) {
				echo '<button class="button button-primary cmpsian-approve" data-id="' . esc_attr( $post_id ) . '">' . esc_html__( 'Approve', 'campussian' ) . '</button> ';
				echo '<button class="button cmpsian-reject" data-id="' . esc_attr( $post_id ) . '">' . esc_html__( 'Reject', 'campussian' ) . '</button>';
			} else {
				echo '<span class="cmpsian-status-done">' . esc_html__( 'Done', 'campussian' ) . '</span>';
			}
			break;
	}
}
add_action( 'manage_cmpsian_application_posts_custom_column', 'cmpsian_application_column_content', 10, 2 );

/**
 * Add custom columns to the student list.
 */
function cmpsian_student_columns( $columns ) {
	$columns['grade']    = __( 'Grade', 'campussian' );
	$columns['roll']     = __( 'Roll', 'campussian' );
	$columns['guardian'] = __( 'Guardian', 'campussian' );
	$columns['phone']    = __( 'Phone', 'campussian' );
	return $columns;
}
add_filter( 'manage_cmpsian_student_posts_columns', 'cmpsian_student_columns' );

function cmpsian_student_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'grade':
			echo esc_html( get_post_meta( $post_id, '_cmpsian_student_grade', true ) );
			break;
		case 'roll':
			echo esc_html( get_post_meta( $post_id, '_cmpsian_student_roll', true ) );
			break;
		case 'guardian':
			echo esc_html( get_post_meta( $post_id, '_cmpsian_student_guardian', true ) );
			break;
		case 'phone':
			echo esc_html( get_post_meta( $post_id, '_cmpsian_student_phone', true ) );
			break;
	}
}
add_action( 'manage_cmpsian_student_posts_custom_column', 'cmpsian_student_column_content', 10, 2 );

/**
 * Add filter dropdowns to the student list.
 */
function cmpsian_student_filters() {
	global $typenow;
	if ( 'cmpsian_student' !== $typenow ) {
		return;
	}

	$grade = isset( $_GET['grade'] ) ? sanitize_text_field( wp_unslash( $_GET['grade'] ) ) : '';
	?>
	<select name="grade" id="cmpsian-grade-filter">
		<option value=""><?php esc_html_e( 'All Grades', 'campussian' ); ?></option>
		<?php
		$grades = array( 'Play – KG', 'Grade 1 – 5', 'Grade 6 – 8', 'Grade 9 – 10' );
		foreach ( $grades as $g ) :
			?>
			<option value="<?php echo esc_attr( $g ); ?>" <?php selected( $grade, $g ); ?>><?php echo esc_html( $g ); ?></option>
		<?php endforeach; ?>
	</select>
	<button type="button" class="button cmpsian-quick-add-student" id="cmpsian-quick-add-student">
		<?php esc_html_e( 'Add Student', 'campussian' ); ?>
	</button>
	<?php
}
add_action( 'restrict_manage_posts', 'cmpsian_student_filters' );

function cmpsian_student_filter_query( $query ) {
	global $pagenow, $typenow;
	if ( ! is_admin() || 'edit.php' !== $pagenow || 'cmpsian_student' !== $typenow || ! $query->is_main_query() ) {
		return;
	}

	$grade = isset( $_GET['grade'] ) ? sanitize_text_field( wp_unslash( $_GET['grade'] ) ) : '';
	if ( $grade ) {
		$query->set(
			'meta_query',
			array(
				array(
					'key'   => '_cmpsian_student_grade',
					'value' => $grade,
				),
			)
		);
	}
}
add_action( 'pre_get_posts', 'cmpsian_student_filter_query' );

/**
 * Enqueue the front-end admission form script on the Admission page template.
 *
 * Hooked to 'wp_enqueue_scripts'.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_enqueue_admission_form_assets() {
	if ( ! is_page_template( 'page-admission.php' ) ) {
		return;
	}

	wp_enqueue_script(
		'campussian-admission-form',
		WCBD_CAMPUSSIAN_URI . 'assets/js/admission-form.js',
		array(),
		WCBD_CAMPUSSIAN_VERSION,
		true
	);

	wp_localize_script(
		'campussian-admission-form',
		'CampussianAdmission',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'cmpsian_admission_nonce' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'cmpsian_enqueue_admission_form_assets' );

/**
 * Render the quick "Add Student" modal in the admin footer on the
 * Students list screen.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_render_add_student_modal() {
	$screen = get_current_screen();
	if ( ! $screen || 'cmpsian_student' !== $screen->post_type ) {
		return;
	}

	$grades = array( 'Play – KG', 'Grade 1 – 5', 'Grade 6 – 8', 'Grade 9 – 10' );
	?>
	<div id="cmpsian-add-student-overlay" class="cmpsian-modal-overlay">
		<div class="cmpsian-modal" id="cmpsian-add-student-modal" role="dialog" aria-modal="true" aria-labelledby="cmpsian-add-student-title">
			<div class="cmpsian-modal__head">
				<h2 id="cmpsian-add-student-title"><?php esc_html_e( 'Add New Student', 'campussian' ); ?></h2>
				<button type="button" class="cmpsian-modal__close" aria-label="<?php esc_attr_e( 'Close', 'campussian' ); ?>">&times;</button>
			</div>
			<form id="cmpsian-add-student-form">
				<table class="form-table">
					<tr>
						<th scope="row"><label for="cmpsian-new-name"><?php esc_html_e( 'Student Name', 'campussian' ); ?> *</label></th>
						<td><input type="text" id="cmpsian-new-name" class="widefat" required /></td>
					</tr>
					<tr>
						<th scope="row"><label for="cmpsian-new-grade"><?php esc_html_e( 'Grade', 'campussian' ); ?> *</label></th>
						<td>
							<select id="cmpsian-new-grade" class="widefat" required>
								<option value=""><?php esc_html_e( 'Select a grade', 'campussian' ); ?></option>
								<?php foreach ( $grades as $g ) : ?>
									<option value="<?php echo esc_attr( $g ); ?>"><?php echo esc_html( $g ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="cmpsian-new-roll"><?php esc_html_e( 'Roll Number', 'campussian' ); ?></label></th>
						<td><input type="text" id="cmpsian-new-roll" class="widefat" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="cmpsian-new-dob"><?php esc_html_e( 'Date of Birth', 'campussian' ); ?></label></th>
						<td><input type="date" id="cmpsian-new-dob" class="widefat" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="cmpsian-new-guardian"><?php esc_html_e( "Guardian's Name", 'campussian' ); ?></label></th>
						<td><input type="text" id="cmpsian-new-guardian" class="widefat" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="cmpsian-new-phone"><?php esc_html_e( 'Phone', 'campussian' ); ?></label></th>
						<td><input type="tel" id="cmpsian-new-phone" class="widefat" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="cmpsian-new-email"><?php esc_html_e( 'Email', 'campussian' ); ?></label></th>
						<td><input type="email" id="cmpsian-new-email" class="widefat" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="cmpsian-new-address"><?php esc_html_e( 'Address', 'campussian' ); ?></label></th>
						<td><textarea id="cmpsian-new-address" class="widefat" rows="2"></textarea></td>
					</tr>
				</table>
				<p class="submit">
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Student', 'campussian' ); ?></button>
					<button type="button" class="button cmpsian-modal__close"><?php esc_html_e( 'Cancel', 'campussian' ); ?></button>
				</p>
			</form>
		</div>
	</div>
	<style>
		.cmpsian-add-student-overlay, #cmpsian-add-student-overlay { display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(0, 0, 0, 0.6); overflow-y: auto; }
		#cmpsian-add-student-overlay.is-open { display: flex; align-items: flex-start; justify-content: center; padding: 40px 16px; }
		#cmpsian-add-student-overlay .cmpsian-modal { background: #fff; border-radius: 8px; max-width: 620px; width: 100%; padding: 24px; }
		#cmpsian-add-student-overlay .cmpsian-modal__head { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #dcdcde; margin-bottom: 16px; padding-bottom: 12px; }
		#cmpsian-add-student-overlay .cmpsian-modal__head h2 { margin: 0; }
		#cmpsian-add-student-overlay .cmpsian-modal__close { background: transparent; border: 0; font-size: 24px; line-height: 1; cursor: pointer; padding: 4px; }
		body.cmpsian-modal-open { overflow: hidden; }
	</style>
	<?php
}
add_action( 'admin_footer', 'cmpsian_render_add_student_modal' );