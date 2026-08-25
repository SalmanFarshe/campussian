<?php
/**
 * Custom post types & taxonomies.
 *
 * Registers two lightweight, self-contained content types so the theme is fully
 * standalone: Notices (with a "notice date" and optional PDF) and Events (with
 * an event date). Both include supporting meta boxes.
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
 * Register the Notice and Event custom post types.
 *
 * Hooked to 'init'.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_register_cpts() {

	/* ---- Notices ------------------------------------------------------ */
	$notice_labels = array(
		'name'               => esc_html__( 'Notices', 'campussian' ),
		'singular_name'      => esc_html__( 'Notice', 'campussian' ),
		'add_new'            => esc_html__( 'Add New', 'campussian' ),
		'add_new_item'       => esc_html__( 'Add New Notice', 'campussian' ),
		'edit_item'          => esc_html__( 'Edit Notice', 'campussian' ),
		'new_item'           => esc_html__( 'New Notice', 'campussian' ),
		'view_item'          => esc_html__( 'View Notice', 'campussian' ),
		'search_items'       => esc_html__( 'Search Notices', 'campussian' ),
		'not_found'          => esc_html__( 'No notices found.', 'campussian' ),
		'not_found_in_trash' => esc_html__( 'No notices found in Trash.', 'campussian' ),
		'all_items'          => esc_html__( 'All Notices', 'campussian' ),
		'menu_name'          => esc_html__( 'Notices', 'campussian' ),
	);

	register_post_type(
		'cmpsian_notice',
		array(
			'labels'        => $notice_labels,
			'public'        => true,
			'has_archive'   => true,
			'menu_icon'     => 'dashicons-megaphone',
			'menu_position' => 20,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			// Slug "notice" (singular) so it does not collide with the
			// "Notices" page (/notices/) which uses page-notice.php.
			'rewrite'       => array( 'slug' => 'notice' ),
			'show_in_rest'  => true,
			// Custom caps so teachers can be granted Notice access in isolation.
			'capability_type' => 'cmpsian_notice',
			'capabilities'    => array(
				'edit_post'            => 'edit_cmpsian_notice',
				'read_post'            => 'read_cmpsian_notice',
				'delete_post'          => 'delete_cmpsian_notice',
				'edit_posts'           => 'edit_cmpsian_notices',
				'edit_others_posts'    => 'edit_others_cmpsian_notices',
				'publish_posts'        => 'publish_cmpsian_notices',
				'read_private_posts'   => 'read_private_cmpsian_notices',
				'create_posts'         => 'edit_cmpsian_notices',
				'delete_posts'         => 'delete_cmpsian_notices',
				'delete_private_posts' => 'delete_private_cmpsian_notices',
				'delete_published_posts' => 'delete_published_cmpsian_notices',
				'delete_others_posts'  => 'delete_others_cmpsian_notices',
				'edit_private_posts'   => 'edit_private_cmpsian_notices',
				'edit_published_posts' => 'edit_published_cmpsian_notices',
			),
			'map_meta_cap' => true,
		)
	);

	// Notice category taxonomy.
	register_taxonomy(
		'cmpsian_notice_cat',
		'cmpsian_notice',
		array(
			'labels'            => array(
				'name'          => esc_html__( 'Notice Categories', 'campussian' ),
				'singular_name' => esc_html__( 'Notice Category', 'campussian' ),
			),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'notice-category' ),
		)
	);

	/* ---- Events ------------------------------------------------------- */
	$event_labels = array(
		'name'               => esc_html__( 'Events', 'campussian' ),
		'singular_name'      => esc_html__( 'Event', 'campussian' ),
		'add_new'            => esc_html__( 'Add New', 'campussian' ),
		'add_new_item'       => esc_html__( 'Add New Event', 'campussian' ),
		'edit_item'          => esc_html__( 'Edit Event', 'campussian' ),
		'new_item'           => esc_html__( 'New Event', 'campussian' ),
		'view_item'          => esc_html__( 'View Event', 'campussian' ),
		'search_items'       => esc_html__( 'Search Events', 'campussian' ),
		'not_found'          => esc_html__( 'No events found.', 'campussian' ),
		'not_found_in_trash' => esc_html__( 'No events found in Trash.', 'campussian' ),
		'all_items'          => esc_html__( 'All Events', 'campussian' ),
		'menu_name'          => esc_html__( 'Events', 'campussian' ),
	);

	register_post_type(
		'cmpsian_event',
		array(
			'labels'        => $event_labels,
			'public'        => true,
			'has_archive'   => true,
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			// Slug "event" (singular) so it does not collide with the
			// "Events" page (/events/) which uses page-events.php.
			'rewrite'       => array( 'slug' => 'event' ),
			'show_in_rest'  => true,
		)
	);

	/* ---- Gallery ------------------------------------------------------ */
	$gallery_labels = array(
		'name'               => esc_html__( 'Gallery', 'campussian' ),
		'singular_name'      => esc_html__( 'Gallery Image', 'campussian' ),
		'add_new'            => esc_html__( 'Add New', 'campussian' ),
		'add_new_item'       => esc_html__( 'Add New Image', 'campussian' ),
		'edit_item'          => esc_html__( 'Edit Image', 'campussian' ),
		'new_item'           => esc_html__( 'New Image', 'campussian' ),
		'view_item'          => esc_html__( 'View Image', 'campussian' ),
		'search_items'       => esc_html__( 'Search Gallery', 'campussian' ),
		'not_found'          => esc_html__( 'No images found.', 'campussian' ),
		'not_found_in_trash' => esc_html__( 'No images found in Trash.', 'campussian' ),
		'all_items'          => esc_html__( 'All Images', 'campussian' ),
		'menu_name'          => esc_html__( 'Gallery', 'campussian' ),
	);

	register_post_type(
		'cmpsian_gallery',
		array(
			'labels'        => $gallery_labels,
			'public'        => true,
			'has_archive'   => false,
			'menu_icon'     => 'dashicons-format-gallery',
			'menu_position' => 22,
			'supports'      => array( 'title', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'gallery-image' ),
			'show_in_rest'  => true,
		)
	);

	/* ---- Facilities --------------------------------------------------- */
	$facility_labels = array(
		'name'               => esc_html__( 'Facilities', 'campussian' ),
		'singular_name'      => esc_html__( 'Facility', 'campussian' ),
		'add_new'            => esc_html__( 'Add New', 'campussian' ),
		'add_new_item'       => esc_html__( 'Add New Facility', 'campussian' ),
		'edit_item'          => esc_html__( 'Edit Facility', 'campussian' ),
		'new_item'           => esc_html__( 'New Facility', 'campussian' ),
		'view_item'          => esc_html__( 'View Facility', 'campussian' ),
		'search_items'       => esc_html__( 'Search Facilities', 'campussian' ),
		'not_found'          => esc_html__( 'No facilities found.', 'campussian' ),
		'not_found_in_trash' => esc_html__( 'No facilities found in Trash.', 'campussian' ),
		'all_items'          => esc_html__( 'All Facilities', 'campussian' ),
		'menu_name'          => esc_html__( 'Facilities', 'campussian' ),
	);

	register_post_type(
		'cmpsian_facility',
		array(
			'labels'        => $facility_labels,
			'public'        => true,
			'has_archive'   => false,
			'menu_icon'     => 'dashicons-building',
			'menu_position' => 23,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'       => array( 'slug' => 'facility' ),
			'show_in_rest'  => true,
		)
	);

	/* ---- Teachers ----------------------------------------------------- */
	$teacher_labels = array(
		'name'               => esc_html__( 'Teachers', 'campussian' ),
		'singular_name'      => esc_html__( 'Teacher', 'campussian' ),
		'add_new'            => esc_html__( 'Add New', 'campussian' ),
		'add_new_item'       => esc_html__( 'Add New Teacher', 'campussian' ),
		'edit_item'          => esc_html__( 'Edit Teacher', 'campussian' ),
		'new_item'           => esc_html__( 'New Teacher', 'campussian' ),
		'view_item'          => esc_html__( 'View Teacher', 'campussian' ),
		'search_items'       => esc_html__( 'Search Teachers', 'campussian' ),
		'not_found'          => esc_html__( 'No teachers found.', 'campussian' ),
		'not_found_in_trash' => esc_html__( 'No teachers found in Trash.', 'campussian' ),
		'all_items'          => esc_html__( 'All Teachers', 'campussian' ),
		'menu_name'          => esc_html__( 'Teachers', 'campussian' ),
	);

	register_post_type(
		'cmpsian_teacher',
		array(
			'labels'        => $teacher_labels,
			'public'        => true,
			'has_archive'   => true,
			'menu_icon'     => 'dashicons-businessperson',
			'menu_position' => 24,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'       => array( 'slug' => 'teachers', 'with_front' => false ),
			'show_in_rest'  => true,
		)
	);

	/* ---- Alumni ------------------------------------------------------- */
	$alumni_labels = array(
		'name'               => esc_html__( 'Alumni', 'campussian' ),
		'singular_name'      => esc_html__( 'Alumni', 'campussian' ),
		'add_new'            => esc_html__( 'Add New', 'campussian' ),
		'add_new_item'       => esc_html__( 'Add New Alumni', 'campussian' ),
		'edit_item'          => esc_html__( 'Edit Alumni', 'campussian' ),
		'new_item'           => esc_html__( 'New Alumni', 'campussian' ),
		'view_item'          => esc_html__( 'View Alumni', 'campussian' ),
		'search_items'       => esc_html__( 'Search Alumni', 'campussian' ),
		'not_found'          => esc_html__( 'No alumni found.', 'campussian' ),
		'not_found_in_trash' => esc_html__( 'No alumni found in Trash.', 'campussian' ),
		'all_items'          => esc_html__( 'All Alumni', 'campussian' ),
		'menu_name'          => esc_html__( 'Alumni', 'campussian' ),
	);

	register_post_type(
		'cmpsian_alumni',
		array(
			'labels'        => $alumni_labels,
			'public'        => true,
			'has_archive'   => true,
			'menu_icon'     => 'dashicons-groups',
			'menu_position' => 25,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'       => array( 'slug' => 'alumni', 'with_front' => false ),
			'show_in_rest'  => true,
		)
	);

	/* ---- News --------------------------------------------------------- */
	$news_labels = array(
		'name'               => esc_html__( 'News', 'campussian' ),
		'singular_name'      => esc_html__( 'News', 'campussian' ),
		'add_new'            => esc_html__( 'Add New', 'campussian' ),
		'add_new_item'       => esc_html__( 'Add New News', 'campussian' ),
		'edit_item'          => esc_html__( 'Edit News', 'campussian' ),
		'new_item'           => esc_html__( 'New News', 'campussian' ),
		'view_item'          => esc_html__( 'View News', 'campussian' ),
		'search_items'       => esc_html__( 'Search News', 'campussian' ),
		'not_found'          => esc_html__( 'No news found.', 'campussian' ),
		'not_found_in_trash' => esc_html__( 'No news found in Trash.', 'campussian' ),
		'all_items'          => esc_html__( 'All News', 'campussian' ),
		'menu_name'          => esc_html__( 'News', 'campussian' ),
	);

	register_post_type(
		'cmpsian_news',
		array(
			'labels'        => $news_labels,
			'public'        => true,
			'has_archive'   => true,
			'menu_icon'     => 'dashicons-welcome-write-blog',
			'menu_position' => 26,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'       => array( 'slug' => 'news', 'with_front' => false ),
			'show_in_rest'  => true,
		)
	);

	/* ---- Admission Applications -------------------------------------- */
	$application_labels = array(
		'name'               => esc_html__( 'Admission Applications', 'campussian' ),
		'singular_name'      => esc_html__( 'Application', 'campussian' ),
		'add_new'            => esc_html__( 'Add New', 'campussian' ),
		'add_new_item'       => esc_html__( 'Add New Application', 'campussian' ),
		'edit_item'          => esc_html__( 'Edit Application', 'campussian' ),
		'new_item'           => esc_html__( 'New Application', 'campussian' ),
		'view_item'          => esc_html__( 'View Application', 'campussian' ),
		'search_items'       => esc_html__( 'Search Applications', 'campussian' ),
		'not_found'          => esc_html__( 'No applications found.', 'campussian' ),
		'not_found_in_trash' => esc_html__( 'No applications found in Trash.', 'campussian' ),
		'all_items'          => esc_html__( 'All Applications', 'campussian' ),
		'menu_name'          => esc_html__( 'Applications', 'campussian' ),
	);

	register_post_type(
		'cmpsian_application',
		array(
			'labels'        => $application_labels,
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-clipboard',
			'menu_position' => 27,
			'supports'      => array( 'title' ),
			'show_in_rest'  => true,
			'has_archive'   => false,
			'exclude_from_search' => true,
			'capability_type' => 'post',
		)
	);

	/* ---- Students ----------------------------------------------------- */
	$student_labels = array(
		'name'               => esc_html__( 'Students', 'campussian' ),
		'singular_name'      => esc_html__( 'Student', 'campussian' ),
		'add_new'            => esc_html__( 'Add New', 'campussian' ),
		'add_new_item'       => esc_html__( 'Add New Student', 'campussian' ),
		'edit_item'          => esc_html__( 'Edit Student', 'campussian' ),
		'new_item'           => esc_html__( 'New Student', 'campussian' ),
		'view_item'          => esc_html__( 'View Student', 'campussian' ),
		'search_items'       => esc_html__( 'Search Students', 'campussian' ),
		'not_found'          => esc_html__( 'No students found.', 'campussian' ),
		'not_found_in_trash' => esc_html__( 'No students found in Trash.', 'campussian' ),
		'all_items'          => esc_html__( 'All Students', 'campussian' ),
		'menu_name'          => esc_html__( 'Students', 'campussian' ),
	);

	register_post_type(
		'cmpsian_student',
		array(
			'labels'          => $student_labels,
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-id-alt',
			'menu_position'   => 28,
			'supports'        => array( 'title' ),
			'show_in_rest'    => true,
			'has_archive'     => false,
			'exclude_from_search' => true,
			// Custom caps so teachers can be granted Student access in isolation.
			'capability_type' => 'cmpsian_student',
			'capabilities'    => array(
				'edit_post'            => 'edit_cmpsian_student',
				'read_post'            => 'read_cmpsian_student',
				'delete_post'          => 'delete_cmpsian_student',
				'edit_posts'           => 'edit_cmpsian_students',
				'edit_others_posts'    => 'edit_others_cmpsian_students',
				'publish_posts'        => 'publish_cmpsian_students',
				'read_private_posts'   => 'read_private_cmpsian_students',
				'create_posts'         => 'edit_cmpsian_students',
				'delete_posts'         => 'delete_cmpsian_students',
				'delete_private_posts' => 'delete_private_cmpsian_students',
				'delete_published_posts' => 'delete_published_cmpsian_students',
				'delete_others_posts'  => 'delete_others_cmpsian_students',
				'edit_private_posts'   => 'edit_private_cmpsian_students',
				'edit_published_posts' => 'edit_published_cmpsian_students',
			),
			'map_meta_cap' => true,
		)
	);

	/* ---- Settings ----------------------------------------------------- */
	$setting_labels = array(
		'name'          => esc_html__( 'Campussian Settings', 'campussian' ),
		'singular_name' => esc_html__( 'Settings', 'campussian' ),
		'edit_item'     => esc_html__( 'Edit Theme Settings', 'campussian' ),
		'add_new'       => esc_html__( 'Add Settings', 'campussian' ),
		'add_new_item'  => esc_html__( 'Add Theme Settings', 'campussian' ),
		'search_items'  => esc_html__( 'Search Settings', 'campussian' ),
		'not_found'     => esc_html__( 'No settings found.', 'campussian' ),
		'menu_name'     => esc_html__( 'Campussian Settings', 'campussian' ),
	);

	register_post_type(
		'cmpsian_setting',
		array(
			'labels'          => $setting_labels,
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-admin-generic',
			'menu_position'   => 59,
			'supports'        => array( 'title', 'editor' ),
			'show_in_rest'    => true,
			'has_archive'     => false,
			'exclude_from_search' => true,
			'capability_type' => 'post',
			'capabilities'    => array(
				'create_posts' => 'do_not_allow', // Single instance.
			),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'cmpsian_register_cpts' );

/**
 * Flush rewrite rules once on theme switch so the CPT permalinks work.
 *
 * Hooked to 'after_switch_theme'.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_rewrite_flush() {
	cmpsian_register_cpts();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'cmpsian_rewrite_flush' );

/* ========================================================================== *
 * Meta boxes
 * ========================================================================== */

/**
 * Register meta boxes for notice date/PDF and event date/venue.
 *
 * Hooked to 'add_meta_boxes'.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_add_meta_boxes() {
	add_meta_box(
		'cmpsian_notice_meta',
		esc_html__( 'Notice Details', 'campussian' ),
		'cmpsian_render_notice_meta',
		'cmpsian_notice',
		'side',
		'default'
	);

	add_meta_box(
		'cmpsian_event_meta',
		esc_html__( 'Event Details', 'campussian' ),
		'cmpsian_render_event_meta',
		'cmpsian_event',
		'side',
		'default'
	);

	add_meta_box(
		'cmpsian_teacher_meta',
		esc_html__( 'Teacher Details', 'campussian' ),
		'cmpsian_render_teacher_meta',
		'cmpsian_teacher',
		'side',
		'default'
	);

	add_meta_box(
		'cmpsian_alumni_meta',
		esc_html__( 'Alumni Details', 'campussian' ),
		'cmpsian_render_alumni_meta',
		'cmpsian_alumni',
		'side',
		'default'
	);

	add_meta_box(
		'cmpsian_application_meta',
		esc_html__( 'Application Details', 'campussian' ),
		'cmpsian_render_application_meta',
		'cmpsian_application',
		'normal',
		'high'
	);

	add_meta_box(
		'cmpsian_student_meta',
		esc_html__( 'Student Details', 'campussian' ),
		'cmpsian_render_student_meta',
		'cmpsian_student',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'cmpsian_add_meta_boxes' );

/**
 * Render the Notice meta box (date + PDF attachment URL).
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 * @return void
 */
function cmpsian_render_notice_meta( $post ) {
	wp_nonce_field( 'cmpsian_save_notice_meta', 'cmpsian_notice_meta_nonce' );

	$date = get_post_meta( $post->ID, '_cmpsian_notice_date', true );
	$pdf  = get_post_meta( $post->ID, '_cmpsian_notice_pdf', true );
	?>
	<p>
		<label for="cmpsian_notice_date" class="cmpsian-meta-label">
			<strong><?php esc_html_e( 'Notice Date', 'campussian' ); ?></strong>
		</label>
		<input type="date" id="cmpsian_notice_date" name="cmpsian_notice_date"
			value="<?php echo esc_attr( $date ); ?>" class="widefat" />
	</p>
	<p>
		<label for="cmpsian_notice_pdf" class="cmpsian-meta-label">
			<strong><?php esc_html_e( 'PDF / File URL', 'campussian' ); ?></strong>
		</label>
		<input type="url" id="cmpsian_notice_pdf" name="cmpsian_notice_pdf"
			value="<?php echo esc_attr( $pdf ); ?>" class="widefat"
			placeholder="https://…/notice.pdf" />
		<span class="description"><?php esc_html_e( 'Paste a PDF URL from the Media Library to enable the preview button.', 'campussian' ); ?></span>
	</p>
	<?php
}

/**
 * Render the Event meta box (date, time, venue).
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 * @return void
 */
function cmpsian_render_event_meta( $post ) {
	wp_nonce_field( 'cmpsian_save_event_meta', 'cmpsian_event_meta_nonce' );

	$date  = get_post_meta( $post->ID, '_cmpsian_event_date', true );
	$time  = get_post_meta( $post->ID, '_cmpsian_event_time', true );
	$venue = get_post_meta( $post->ID, '_cmpsian_event_venue', true );
	?>
	<p>
		<label for="cmpsian_event_date"><strong><?php esc_html_e( 'Event Date', 'campussian' ); ?></strong></label>
		<input type="date" id="cmpsian_event_date" name="cmpsian_event_date"
			value="<?php echo esc_attr( $date ); ?>" class="widefat" />
	</p>
	<p>
		<label for="cmpsian_event_time"><strong><?php esc_html_e( 'Event Time', 'campussian' ); ?></strong></label>
		<input type="time" id="cmpsian_event_time" name="cmpsian_event_time"
			value="<?php echo esc_attr( $time ); ?>" class="widefat" />
	</p>
	<p>
		<label for="cmpsian_event_venue"><strong><?php esc_html_e( 'Venue', 'campussian' ); ?></strong></label>
		<input type="text" id="cmpsian_event_venue" name="cmpsian_event_venue"
			value="<?php echo esc_attr( $venue ); ?>" class="widefat" />
	</p>
	<?php
}

/**
 * Render the Teacher meta box (designation, qualification, social links).
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 * @return void
 */
function cmpsian_render_teacher_meta( $post ) {
	wp_nonce_field( 'cmpsian_save_teacher_meta', 'cmpsian_teacher_meta_nonce' );

	$designation   = get_post_meta( $post->ID, '_cmpsian_teacher_designation', true );
	$qualification = get_post_meta( $post->ID, '_cmpsian_teacher_qualification', true );
	$facebook      = get_post_meta( $post->ID, '_cmpsian_teacher_facebook', true );
	$linkedin      = get_post_meta( $post->ID, '_cmpsian_teacher_linkedin', true );
	$twitter       = get_post_meta( $post->ID, '_cmpsian_teacher_twitter', true );
	?>
	<p>
		<label for="cmpsian_teacher_designation"><strong><?php esc_html_e( 'Designation', 'campussian' ); ?></strong></label>
		<input type="text" id="cmpsian_teacher_designation" name="cmpsian_teacher_designation"
			value="<?php echo esc_attr( $designation ); ?>" class="widefat" />
	</p>
	<p>
		<label for="cmpsian_teacher_qualification"><strong><?php esc_html_e( 'Qualification', 'campussian' ); ?></strong></label>
		<input type="text" id="cmpsian_teacher_qualification" name="cmpsian_teacher_qualification"
			value="<?php echo esc_attr( $qualification ); ?>" class="widefat" />
	</p>
	<p>
		<label for="cmpsian_teacher_facebook"><strong><?php esc_html_e( 'Facebook URL', 'campussian' ); ?></strong></label>
		<input type="url" id="cmpsian_teacher_facebook" name="cmpsian_teacher_facebook"
			value="<?php echo esc_attr( $facebook ); ?>" class="widefat" />
	</p>
	<p>
		<label for="cmpsian_teacher_linkedin"><strong><?php esc_html_e( 'LinkedIn URL', 'campussian' ); ?></strong></label>
		<input type="url" id="cmpsian_teacher_linkedin" name="cmpsian_teacher_linkedin"
			value="<?php echo esc_attr( $linkedin ); ?>" class="widefat" />
	</p>
	<p>
		<label for="cmpsian_teacher_twitter"><strong><?php esc_html_e( 'Twitter / X URL', 'campussian' ); ?></strong></label>
		<input type="url" id="cmpsian_teacher_twitter" name="cmpsian_teacher_twitter"
			value="<?php echo esc_attr( $twitter ); ?>" class="widefat" />
	</p>
	<?php
}

/**
 * Render the Alumni meta box (graduation year, current institution).
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 * @return void
 */
function cmpsian_render_alumni_meta( $post ) {
	wp_nonce_field( 'cmpsian_save_alumni_meta', 'cmpsian_alumni_meta_nonce' );

	$year        = get_post_meta( $post->ID, '_cmpsian_alumni_graduation_year', true );
	$institution = get_post_meta( $post->ID, '_cmpsian_alumni_current_institution', true );
	?>
	<p>
		<label for="cmpsian_alumni_graduation_year"><strong><?php esc_html_e( 'Graduation Year', 'campussian' ); ?></strong></label>
		<input type="text" id="cmpsian_alumni_graduation_year" name="cmpsian_alumni_graduation_year"
			value="<?php echo esc_attr( $year ); ?>" class="widefat" placeholder="e.g. 2015" />
	</p>
	<p>
		<label for="cmpsian_alumni_current_institution"><strong><?php esc_html_e( 'Current Institution / Company', 'campussian' ); ?></strong></label>
		<input type="text" id="cmpsian_alumni_current_institution" name="cmpsian_alumni_current_institution"
			value="<?php echo esc_attr( $institution ); ?>" class="widefat" />
	</p>
	<p class="description"><?php esc_html_e( 'Use the Excerpt field for the testimonial quote.', 'campussian' ); ?></p>
	<?php
}

/**
 * Render the Application meta box (student details).
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 * @return void
 */
function cmpsian_render_application_meta( $post ) {
	wp_nonce_field( 'cmpsian_save_application_meta', 'cmpsian_application_meta_nonce' );

	$fields = array(
		'_cmpsian_app_student_name'  => __( "Student's Full Name", 'campussian' ),
		'_cmpsian_app_dob'           => __( 'Date of Birth', 'campussian' ),
		'_cmpsian_app_grade'         => __( 'Grade Applying For', 'campussian' ),
		'_cmpsian_app_guardian'      => __( "Guardian's Name", 'campussian' ),
		'_cmpsian_app_phone'         => __( 'Phone', 'campussian' ),
		'_cmpsian_app_email'         => __( 'Email', 'campussian' ),
		'_cmpsian_app_message'       => __( 'Message', 'campussian' ),
		'_cmpsian_app_status'        => __( 'Status', 'campussian' ),
	);
	?>
	<table class="form-table">
		<?php foreach ( $fields as $key => $label ) : ?>
			<tr>
				<th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
				<td>
					<?php if ( '_cmpsian_app_status' === $key ) : ?>
						<select name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="widefat">
							<?php
							$status = get_post_meta( $post->ID, $key, true );
							$options = array( 'pending' => __( 'Pending', 'campussian' ), 'approved' => __( 'Approved', 'campussian' ), 'rejected' => __( 'Rejected', 'campussian' ) );
							foreach ( $options as $val => $lbl ) :
								?>
								<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $status, $val ); ?>><?php echo esc_html( $lbl ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php else : ?>
						<input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>"
							value="<?php echo esc_attr( get_post_meta( $post->ID, $key, true ) ); ?>" class="widefat" />
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php
}

/**
 * Render the Student meta box (student details).
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 * @return void
 */
function cmpsian_render_student_meta( $post ) {
	wp_nonce_field( 'cmpsian_save_student_meta', 'cmpsian_student_meta_nonce' );

	$fields = array(
		'_cmpsian_student_dob'       => __( 'Date of Birth', 'campussian' ),
		'_cmpsian_student_grade'     => __( 'Grade', 'campussian' ),
		'_cmpsian_student_guardian'  => __( "Guardian's Name", 'campussian' ),
		'_cmpsian_student_phone'     => __( 'Phone', 'campussian' ),
		'_cmpsian_student_email'     => __( 'Email', 'campussian' ),
		'_cmpsian_student_address'   => __( 'Address', 'campussian' ),
		'_cmpsian_student_roll'      => __( 'Roll Number', 'campussian' ),
	);
	?>
	<table class="form-table">
		<?php foreach ( $fields as $key => $label ) : ?>
			<tr>
				<th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
				<td>
					<input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>"
						value="<?php echo esc_attr( get_post_meta( $post->ID, $key, true ) ); ?>" class="widefat" />
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php
}

/**
 * Persist meta box values.
 *
 * Hooked to 'save_post'. Verifies nonces, autosave and capability before saving.
 *
 * @since 1.0.0
 * @param int $post_id The post being saved.
 * @return void
 */
function cmpsian_save_meta( $post_id ) {

	// Bail on autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Notice meta.
	if ( isset( $_POST['cmpsian_notice_meta_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmpsian_notice_meta_nonce'] ) ), 'cmpsian_save_notice_meta' )
		&& current_user_can( 'edit_post', $post_id )
	) {
		if ( isset( $_POST['cmpsian_notice_date'] ) ) {
			update_post_meta( $post_id, '_cmpsian_notice_date', sanitize_text_field( wp_unslash( $_POST['cmpsian_notice_date'] ) ) );
		}
		if ( isset( $_POST['cmpsian_notice_pdf'] ) ) {
			update_post_meta( $post_id, '_cmpsian_notice_pdf', esc_url_raw( wp_unslash( $_POST['cmpsian_notice_pdf'] ) ) );
		}
	}

	// Event meta.
	if ( isset( $_POST['cmpsian_event_meta_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmpsian_event_meta_nonce'] ) ), 'cmpsian_save_event_meta' )
		&& current_user_can( 'edit_post', $post_id )
	) {
		if ( isset( $_POST['cmpsian_event_date'] ) ) {
			update_post_meta( $post_id, '_cmpsian_event_date', sanitize_text_field( wp_unslash( $_POST['cmpsian_event_date'] ) ) );
		}
		if ( isset( $_POST['cmpsian_event_time'] ) ) {
			update_post_meta( $post_id, '_cmpsian_event_time', sanitize_text_field( wp_unslash( $_POST['cmpsian_event_time'] ) ) );
		}
		if ( isset( $_POST['cmpsian_event_venue'] ) ) {
			update_post_meta( $post_id, '_cmpsian_event_venue', sanitize_text_field( wp_unslash( $_POST['cmpsian_event_venue'] ) ) );
		}
	}

	// Teacher meta.
	if ( isset( $_POST['cmpsian_teacher_meta_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmpsian_teacher_meta_nonce'] ) ), 'cmpsian_save_teacher_meta' )
		&& current_user_can( 'edit_post', $post_id )
	) {
		if ( isset( $_POST['cmpsian_teacher_designation'] ) ) {
			update_post_meta( $post_id, '_cmpsian_teacher_designation', sanitize_text_field( wp_unslash( $_POST['cmpsian_teacher_designation'] ) ) );
		}
		if ( isset( $_POST['cmpsian_teacher_qualification'] ) ) {
			update_post_meta( $post_id, '_cmpsian_teacher_qualification', sanitize_text_field( wp_unslash( $_POST['cmpsian_teacher_qualification'] ) ) );
		}
		if ( isset( $_POST['cmpsian_teacher_facebook'] ) ) {
			update_post_meta( $post_id, '_cmpsian_teacher_facebook', esc_url_raw( wp_unslash( $_POST['cmpsian_teacher_facebook'] ) ) );
		}
		if ( isset( $_POST['cmpsian_teacher_linkedin'] ) ) {
			update_post_meta( $post_id, '_cmpsian_teacher_linkedin', esc_url_raw( wp_unslash( $_POST['cmpsian_teacher_linkedin'] ) ) );
		}
		if ( isset( $_POST['cmpsian_teacher_twitter'] ) ) {
			update_post_meta( $post_id, '_cmpsian_teacher_twitter', esc_url_raw( wp_unslash( $_POST['cmpsian_teacher_twitter'] ) ) );
		}
	}

	// Alumni meta.
	if ( isset( $_POST['cmpsian_alumni_meta_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmpsian_alumni_meta_nonce'] ) ), 'cmpsian_save_alumni_meta' )
		&& current_user_can( 'edit_post', $post_id )
	) {
		if ( isset( $_POST['cmpsian_alumni_graduation_year'] ) ) {
			update_post_meta( $post_id, '_cmpsian_alumni_graduation_year', sanitize_text_field( wp_unslash( $_POST['cmpsian_alumni_graduation_year'] ) ) );
		}
		if ( isset( $_POST['cmpsian_alumni_current_institution'] ) ) {
			update_post_meta( $post_id, '_cmpsian_alumni_current_institution', sanitize_text_field( wp_unslash( $_POST['cmpsian_alumni_current_institution'] ) ) );
		}
	}

	// Application meta.
	if ( isset( $_POST['cmpsian_application_meta_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmpsian_application_meta_nonce'] ) ), 'cmpsian_save_application_meta' )
		&& current_user_can( 'edit_post', $post_id )
	) {
		$app_fields = array(
			'_cmpsian_app_student_name', '_cmpsian_app_dob', '_cmpsian_app_grade',
			'_cmpsian_app_guardian', '_cmpsian_app_phone', '_cmpsian_app_email',
			'_cmpsian_app_message', '_cmpsian_app_status',
		);
		foreach ( $app_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
			}
		}
	}

	// Student meta.
	if ( isset( $_POST['cmpsian_student_meta_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cmpsian_student_meta_nonce'] ) ), 'cmpsian_save_student_meta' )
		&& current_user_can( 'edit_post', $post_id )
	) {
		$student_fields = array(
			'_cmpsian_student_dob', '_cmpsian_student_grade', '_cmpsian_student_guardian',
			'_cmpsian_student_phone', '_cmpsian_student_email', '_cmpsian_student_address',
			'_cmpsian_student_roll',
		);
		foreach ( $student_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
			}
		}
	}
}
add_action( 'save_post', 'cmpsian_save_meta' );
