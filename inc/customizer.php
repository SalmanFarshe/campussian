<?php
/**
 * Theme Customizer.
 *
 * Registers the "Campussian Options" panel and every branding, hero, marquee,
 * principal, statistics, admission and footer control. All controls sanitise
 * input on save; templates read the values through cmpsian_get_option().
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ========================================================================== *
 * Sanitisation helpers
 * ========================================================================== */

/**
 * Sanitise a checkbox value to a strict boolean.
 *
 * @since 1.0.0
 * @param mixed $checked The raw value.
 * @return bool
 */
function cmpsian_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === (bool) $checked ) ? true : false );
}

/**
 * Sanitise the marquee source select against a whitelist.
 *
 * @since 1.0.0
 * @param string $value Raw value.
 * @return string
 */
function cmpsian_sanitize_marquee_source( $value ) {
	$choices = array( 'notices', 'manual' );
	return in_array( $value, $choices, true ) ? $value : 'notices';
}

/**
 * Sanitise the sections order input.
 *
 * Whitelists each entry and keeps the user's chosen ordering of the valid ones.
 * The whitelist is derived from the shared default order so it cannot drift
 * from the section IDs used by the templates.
 *
 * @since 1.0.0
 * @param string $value Raw value.
 * @return string
 */
function cmpsian_sections_order_sanitize( $value ) {
	$defaults = cmpsian_default_options();
	$allowed  = explode( ',', $defaults['cmpsian_sections_order'] );
	$parts    = array_map( 'trim', explode( ',', $value ) );
	$seen     = array();
	$sanitized = array();
	foreach ( $parts as $part ) {
		if ( in_array( $part, $allowed, true ) && ! in_array( $part, $seen, true ) ) {
			$sanitized[] = $part;
			$seen[]      = $part;
		}
	}
	return implode( ',', $sanitized );
}

/* ========================================================================== *
 * Registration
 * ========================================================================== */

/**
 * Register Customizer panel, sections, settings and controls.
 *
 * Hooked to 'customize_register'.
 *
 * @since 1.0.0
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function cmpsian_customize_register( $wp_customize ) {

	$defaults = cmpsian_default_options();

	// Live-preview the site title & tagline.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	/* ---------------------------------------------------------------- *
	 * Master panel
	 * ---------------------------------------------------------------- */
	$wp_customize->add_panel(
		'cmpsian_panel',
		array(
			'title'       => esc_html__( 'Campussian Options', 'campussian' ),
			'description' => esc_html__( 'Theme-wide settings for the Campussian school theme by WhyCodeBD.', 'campussian' ),
			'priority'    => 10,
		)
	);

	/* ================================================================ *
	 * SECTION 1 — School Branding
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_branding',
		array(
			'title' => esc_html__( 'School Branding', 'campussian' ),
			'panel' => 'cmpsian_panel',
		)
	);

	// Logo upload.
	$wp_customize->add_setting(
		'cmpsian_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'cmpsian_logo',
			array(
				'label'       => esc_html__( 'School Logo', 'campussian' ),
				'description' => esc_html__( 'Upload your school logo. Falls back to the site title if empty.', 'campussian' ),
				'section'     => 'cmpsian_branding',
			)
		)
	);

	// Text/email/url branding fields.
	$branding_fields = array(
		'cmpsian_phone'    => array( 'label' => __( 'Phone Number', 'campussian' ),  'type' => 'text',  'sanitize' => 'sanitize_text_field' ),
		'cmpsian_email'    => array( 'label' => __( 'Email Address', 'campussian' ), 'type' => 'email', 'sanitize' => 'sanitize_email' ),
		'cmpsian_address'  => array( 'label' => __( 'Address', 'campussian' ),       'type' => 'text',  'sanitize' => 'sanitize_text_field' ),
		'cmpsian_facebook' => array( 'label' => __( 'Facebook URL', 'campussian' ),  'type' => 'url',   'sanitize' => 'esc_url_raw' ),
		'cmpsian_youtube'  => array( 'label' => __( 'YouTube URL', 'campussian' ),   'type' => 'url',   'sanitize' => 'esc_url_raw' ),
		'cmpsian_linkedin' => array( 'label' => __( 'LinkedIn URL', 'campussian' ),  'type' => 'url',   'sanitize' => 'esc_url_raw' ),
		'cmpsian_twitter'  => array( 'label' => __( 'Twitter / X URL', 'campussian' ), 'type' => 'url', 'sanitize' => 'esc_url_raw' ),
	);

	foreach ( $branding_fields as $id => $args ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => isset( $defaults[ $id ] ) ? $defaults[ $id ] : '',
				'sanitize_callback' => $args['sanitize'],
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $args['label'],
				'section' => 'cmpsian_branding',
				'type'    => $args['type'],
			)
		);
	}

	/* ================================================================ *
	 * SECTION 2 — Hero Banner
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_hero',
		array(
			'title' => esc_html__( 'Hero Banner', 'campussian' ),
			'panel' => 'cmpsian_panel',
		)
	);

	// Hero background image.
	$wp_customize->add_setting(
		'cmpsian_hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'cmpsian_hero_image',
			array(
				'label'   => esc_html__( 'Hero Background Image', 'campussian' ),
				'section' => 'cmpsian_hero',
			)
		)
	);

	$hero_fields = array(
		'cmpsian_hero_title'     => array( 'label' => __( 'Hero Title', 'campussian' ),       'type' => 'text',     'sanitize' => 'sanitize_text_field' ),
		'cmpsian_hero_subtitle'  => array( 'label' => __( 'Hero Subtitle', 'campussian' ),    'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'cmpsian_hero_btn1_text' => array( 'label' => __( 'Primary Button Text', 'campussian' ), 'type' => 'text',  'sanitize' => 'sanitize_text_field' ),
		'cmpsian_hero_btn1_url'  => array( 'label' => __( 'Primary Button URL', 'campussian' ),  'type' => 'text',  'sanitize' => 'sanitize_text_field' ),
		'cmpsian_hero_btn2_text' => array( 'label' => __( 'Secondary Button Text', 'campussian' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'cmpsian_hero_btn2_url'  => array( 'label' => __( 'Secondary Button URL', 'campussian' ),  'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
	);

	foreach ( $hero_fields as $id => $args ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => isset( $defaults[ $id ] ) ? $defaults[ $id ] : '',
				'sanitize_callback' => $args['sanitize'],
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $args['label'],
				'section' => 'cmpsian_hero',
				'type'    => $args['type'],
			)
		);
	}

	/* ================================================================ *
	 * SECTION 3 — Marquee / Notice Ticker
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_marquee',
		array(
			'title' => esc_html__( 'Notice Marquee', 'campussian' ),
			'panel' => 'cmpsian_panel',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_marquee_enable',
		array(
			'default'           => $defaults['cmpsian_marquee_enable'],
			'sanitize_callback' => 'cmpsian_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'cmpsian_marquee_enable',
		array(
			'label'   => esc_html__( 'Enable the marquee ticker', 'campussian' ),
			'section' => 'cmpsian_marquee',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_marquee_source',
		array(
			'default'           => $defaults['cmpsian_marquee_source'],
			'sanitize_callback' => 'cmpsian_sanitize_marquee_source',
		)
	);
	$wp_customize->add_control(
		'cmpsian_marquee_source',
		array(
			'label'   => esc_html__( 'Ticker Content Source', 'campussian' ),
			'section' => 'cmpsian_marquee',
			'type'    => 'select',
			'choices' => array(
				'notices' => esc_html__( 'Latest Notices (automatic)', 'campussian' ),
				'manual'  => esc_html__( 'Manual Text (below)', 'campussian' ),
			),
		)
	);

	$wp_customize->add_setting(
		'cmpsian_marquee_label',
		array(
			'default'           => $defaults['cmpsian_marquee_label'],
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'cmpsian_marquee_label',
		array(
			'label'   => esc_html__( 'Ticker Label', 'campussian' ),
			'section' => 'cmpsian_marquee',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_marquee_text',
		array(
			'default'           => $defaults['cmpsian_marquee_text'],
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'cmpsian_marquee_text',
		array(
			'label'       => esc_html__( 'Manual Ticker Text', 'campussian' ),
			'description' => esc_html__( 'Used when the source is set to "Manual Text".', 'campussian' ),
			'section'     => 'cmpsian_marquee',
			'type'        => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_marquee_speed',
		array(
			'default'           => $defaults['cmpsian_marquee_speed'],
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'cmpsian_marquee_speed',
		array(
			'label'       => esc_html__( 'Scroll Duration (seconds)', 'campussian' ),
			'section'     => 'cmpsian_marquee',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 5,
				'max'  => 120,
				'step' => 1,
			),
		)
	);

	/* ================================================================ *
	 * SECTION 4 — Principal & Overview
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_principal',
		array(
			'title' => esc_html__( 'Principal & Overview', 'campussian' ),
			'panel' => 'cmpsian_panel',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_principal_photo',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'cmpsian_principal_photo',
			array(
				'label'   => esc_html__( "Principal's Photo", 'campussian' ),
				'section' => 'cmpsian_principal',
			)
		)
	);

	$principal_fields = array(
		'cmpsian_principal_name'   => array( 'label' => __( "Principal's Name", 'campussian' ),  'type' => 'text',     'sanitize' => 'sanitize_text_field' ),
		'cmpsian_principal_desig'  => array( 'label' => __( 'Designation', 'campussian' ),        'type' => 'text',     'sanitize' => 'sanitize_text_field' ),
		'cmpsian_principal_speech' => array( 'label' => __( "Principal's Message", 'campussian' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'cmpsian_overview_title'   => array( 'label' => __( 'Overview Heading', 'campussian' ),    'type' => 'text',     'sanitize' => 'sanitize_text_field' ),
		'cmpsian_overview_text'    => array( 'label' => __( 'Overview Text', 'campussian' ),       'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
	);

	foreach ( $principal_fields as $id => $args ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => isset( $defaults[ $id ] ) ? $defaults[ $id ] : '',
				'sanitize_callback' => $args['sanitize'],
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $args['label'],
				'section' => 'cmpsian_principal',
				'type'    => $args['type'],
			)
		);
	}

	/* ================================================================ *
	 * SECTION 5 — Statistics Counter
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_stats',
		array(
			'title'       => esc_html__( 'Statistics Counter', 'campussian' ),
			'description' => esc_html__( 'Animated numbers on the homepage.', 'campussian' ),
			'panel'       => 'cmpsian_panel',
		)
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		$num_id   = 'cmpsian_stat' . $i . '_number';
		$label_id = 'cmpsian_stat' . $i . '_label';

		$wp_customize->add_setting(
			$num_id,
			array(
				'default'           => isset( $defaults[ $num_id ] ) ? $defaults[ $num_id ] : 0,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			$num_id,
			array(
				/* translators: %d: statistic slot number. */
				'label'   => sprintf( esc_html__( 'Stat %d — Number', 'campussian' ), $i ),
				'section' => 'cmpsian_stats',
				'type'    => 'number',
			)
		);

		$wp_customize->add_setting(
			$label_id,
			array(
				'default'           => isset( $defaults[ $label_id ] ) ? $defaults[ $label_id ] : '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$label_id,
			array(
				/* translators: %d: statistic slot number. */
				'label'   => sprintf( esc_html__( 'Stat %d — Label', 'campussian' ), $i ),
				'section' => 'cmpsian_stats',
				'type'    => 'text',
			)
		);
	}

	/* ================================================================ *
	 * SECTION 6 — Admission CTA
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_cta',
		array(
			'title' => esc_html__( 'Admission CTA', 'campussian' ),
			'panel' => 'cmpsian_panel',
		)
	);

	$cta_fields = array(
		'cmpsian_cta_heading' => array( 'label' => __( 'CTA Heading', 'campussian' ), 'type' => 'text',     'sanitize' => 'sanitize_text_field' ),
		'cmpsian_cta_text'    => array( 'label' => __( 'CTA Text', 'campussian' ),    'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'cmpsian_cta_btn'     => array( 'label' => __( 'Button Label', 'campussian' ), 'type' => 'text',    'sanitize' => 'sanitize_text_field' ),
		'cmpsian_cta_url'     => array( 'label' => __( 'Button URL', 'campussian' ),  'type' => 'text',     'sanitize' => 'sanitize_text_field' ),
	);

	foreach ( $cta_fields as $id => $args ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => isset( $defaults[ $id ] ) ? $defaults[ $id ] : '',
				'sanitize_callback' => $args['sanitize'],
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $args['label'],
				'section' => 'cmpsian_cta',
				'type'    => $args['type'],
			)
		);
	}

	/* ================================================================ *
	 * SECTION 7 — Footer
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_footer',
		array(
			'title' => esc_html__( 'Footer', 'campussian' ),
			'panel' => 'cmpsian_panel',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_footer_about',
		array(
			'default'           => $defaults['cmpsian_footer_about'],
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'cmpsian_footer_about',
		array(
			'label'   => esc_html__( 'Footer About Text', 'campussian' ),
			'section' => 'cmpsian_footer',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_copyright',
		array(
			'default'           => $defaults['cmpsian_copyright'],
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'cmpsian_copyright',
		array(
			'label'       => esc_html__( 'Copyright Text', 'campussian' ),
			'description' => esc_html__( 'Leave blank to use the default. Use {year} for the current year.', 'campussian' ),
			'section'     => 'cmpsian_footer',
			'type'        => 'text',
		)
	);

	/* ================================================================ *
	 * SECTION 10 — Preloader
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_preloader',
		array(
			'title'       => esc_html__( 'Preloader', 'campussian' ),
			'description' => esc_html__( 'Control the page loading animation shown before the site is ready.', 'campussian' ),
			'panel'       => 'cmpsian_panel',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_preloader_enable',
		array(
			'default'           => $defaults['cmpsian_preloader_enable'],
			'sanitize_callback' => 'cmpsian_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'cmpsian_preloader_enable',
		array(
			'label'       => esc_html__( 'Enable Preloader', 'campussian' ),
			'description' => esc_html__( 'Show the branded loading spinner while the page loads.', 'campussian' ),
			'section'     => 'cmpsian_preloader',
			'type'        => 'checkbox',
		)
	);

	/* ================================================================ *
	 * SECTION 11 — Homepage Sections
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_homepage_sections',
		array(
			'title' => esc_html__( 'Homepage Sections', 'campussian' ),
			'panel' => 'cmpsian_panel',
		)
	);

	// Section visibility toggles.
	$section_toggles = array(
		'cmpsian_sections_enable_hero'             => esc_html__( 'Hero Section', 'campussian' ),
		'cmpsian_sections_enable_principal'        => esc_html__( 'Principal & Overview', 'campussian' ),
		'cmpsian_sections_enable_news'             => esc_html__( 'News Section', 'campussian' ),
		'cmpsian_sections_enable_notices_events'   => esc_html__( 'Notices & Events', 'campussian' ),
		'cmpsian_sections_enable_stats_counter'    => esc_html__( 'Statistics Counter', 'campussian' ),
		'cmpsian_sections_enable_admission_cta'    => esc_html__( 'Admission CTA', 'campussian' ),
		'cmpsian_sections_enable_gallery_preview'  => esc_html__( 'Gallery Preview', 'campussian' ),
		'cmpsian_sections_enable_facilities'       => esc_html__( 'Facilities', 'campussian' ),
		'cmpsian_sections_enable_teachers'         => esc_html__( 'Teachers', 'campussian' ),
		'cmpsian_sections_enable_alumni'           => esc_html__( 'Alumni', 'campussian' ),
	);

	foreach ( $section_toggles as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => true,
				'sanitize_callback' => 'cmpsian_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'cmpsian_homepage_sections',
				'type'    => 'checkbox',
			)
		);
	}

	// Section ordering control.
	$wp_customize->add_setting(
		'cmpsian_sections_order',
		array(
			'default'           => $defaults['cmpsian_sections_order'],
			'sanitize_callback' => 'cmpsian_sections_order_sanitize',
		)
	);
	$wp_customize->add_control(
		'cmpsian_sections_order',
		array(
			'label'       => esc_html__( 'Homepage Section Order', 'campussian' ),
			'section'     => 'cmpsian_homepage_sections',
			'type'        => 'text',
			'description' => esc_html__( 'Comma-separated list of section IDs to control display order. Example: hero,principal,facilities,teachers,alumni,gallery_preview', 'campussian' ),
		)
	);

	/* ================================================================ *
	 * SECTION 12 — Facilities (content managed via CPT)
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_facilities',
		array(
			'title'       => esc_html__( 'Facilities', 'campussian' ),
			'description' => esc_html__( 'Add facilities under the "Facilities" menu in the admin. This only controls the section heading.', 'campussian' ),
			'panel'       => 'cmpsian_panel',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_facilities_title',
		array(
			'default'           => isset( $defaults['cmpsian_facilities_title'] ) ? $defaults['cmpsian_facilities_title'] : 'Our Facilities',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'cmpsian_facilities_title',
		array(
			'label'   => esc_html__( 'Section Heading', 'campussian' ),
			'section' => 'cmpsian_facilities',
			'type'    => 'text',
		)
	);

	/* ================================================================ *
	 * SECTION 13 — Teachers (content managed via CPT)
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_teachers',
		array(
			'title'       => esc_html__( 'Teachers', 'campussian' ),
			'description' => esc_html__( 'Add teachers under the "Teachers" menu in the admin. This only controls the section heading.', 'campussian' ),
			'panel'       => 'cmpsian_panel',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_teachers_title',
		array(
			'default'           => isset( $defaults['cmpsian_teachers_title'] ) ? $defaults['cmpsian_teachers_title'] : 'Our Faculty',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'cmpsian_teachers_title',
		array(
			'label'   => esc_html__( 'Section Heading', 'campussian' ),
			'section' => 'cmpsian_teachers',
			'type'    => 'text',
		)
	);

	/* ================================================================ *
	 * SECTION 14 — Alumni (content managed via CPT)
	 * ================================================================ */
	$wp_customize->add_section(
		'cmpsian_alumni',
		array(
			'title'       => esc_html__( 'Alumni', 'campussian' ),
			'description' => esc_html__( 'Add alumni under the "Alumni" menu in the admin. This only controls the section heading.', 'campussian' ),
			'panel'       => 'cmpsian_panel',
		)
	);

	$wp_customize->add_setting(
		'cmpsian_alumni_title',
		array(
			'default'           => isset( $defaults['cmpsian_alumni_title'] ) ? $defaults['cmpsian_alumni_title'] : 'Alumni Success',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'cmpsian_alumni_title',
		array(
			'label'   => esc_html__( 'Section Heading', 'campussian' ),
			'section' => 'cmpsian_alumni',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'cmpsian_customize_register' );

/**
 * Enqueue the Customizer live-preview helper script.
 *
 * Hooked to 'customize_preview_init'.
 *
 * @since 1.0.0
 * @return void
 */
function cmpsian_customize_preview_js() {
	wp_enqueue_script(
		'cmpsian-customizer-preview',
		WCBD_CAMPUSSIAN_URI . 'assets/js/customizer-preview.js',
		array( 'customize-preview', 'jquery' ),
		WCBD_CAMPUSSIAN_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'cmpsian_customize_preview_js' );