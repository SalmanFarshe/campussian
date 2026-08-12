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
			'labels'       => $notice_labels,
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-megaphone',
			'menu_position' => 20,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'      => array( 'slug' => 'notices' ),
			'show_in_rest' => true,
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
			'rewrite'       => array( 'slug' => 'events' ),
			'show_in_rest'  => true,
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
}
add_action( 'save_post', 'cmpsian_save_meta' );
