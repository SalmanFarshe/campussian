<?php
/**
 * Customizer defaults and option getter.
 *
 * Centralises every default value used by the theme Customizer so templates and
 * the Customizer registration file share a single source of truth. Retrieve any
 * value with cmpsian_get_option( 'key' ).
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'cmpsian_default_options' ) ) {
	/**
	 * Return the associative array of Customizer defaults.
	 *
	 * @since 1.0.0
	 * @return array<string,mixed> Keyed default values.
	 */
	function cmpsian_default_options() {

		$defaults = array(

			/* ---- School Branding ------------------------------------- */
			'cmpsian_phone'          => '+880 1XXX-XXXXXX',
			'cmpsian_email'          => 'info@campussian.edu',
			'cmpsian_address'        => 'Dhaka, Bangladesh',
			'cmpsian_facebook'       => '#',
			'cmpsian_youtube'        => '#',
			'cmpsian_linkedin'       => '#',
			'cmpsian_twitter'        => '',

			/* ---- Hero Section ---------------------------------------- */
			'cmpsian_hero_title'     => 'Shaping Brighter Futures at Campussian',
			'cmpsian_hero_subtitle'  => 'A nurturing, technology-driven learning environment where every student is empowered to excel academically and grow as a responsible citizen.',
			'cmpsian_hero_image'     => '',
			'cmpsian_hero_btn1_text' => 'Apply Now',
			'cmpsian_hero_btn1_url'  => '#admission',
			'cmpsian_hero_btn2_text' => 'Explore',
			'cmpsian_hero_btn2_url'  => '#overview',

			/* ---- Marquee / Ticker ------------------------------------ */
			'cmpsian_marquee_enable' => true,
			'cmpsian_marquee_label'  => 'Latest',
			'cmpsian_marquee_text'   => 'Admissions for the 2026 academic session are now open. Visit the Admission page for details.',
			'cmpsian_marquee_source' => 'notices', // 'notices' or 'manual'.
			'cmpsian_marquee_speed'  => 25,

			/* ---- Principal ------------------------------------------- */
			'cmpsian_principal_photo'  => '',
			'cmpsian_principal_name'   => 'Dr. Rahima Chowdhury',
			'cmpsian_principal_desig'  => 'Principal, Campussian School & College',
			'cmpsian_principal_speech' => 'Welcome to Campussian. For over two decades we have been committed to academic excellence and holistic development. Our dedicated teachers, modern facilities and student-centred approach ensure every learner discovers their full potential in a safe and inspiring environment.',

			/* ---- Overview -------------------------------------------- */
			'cmpsian_overview_title' => 'Why Choose Campussian',
			'cmpsian_overview_text'  => 'From experienced educators to a future-ready curriculum, discover what makes our campus a place students are proud to call their second home.',

			/* ---- Statistics Counter ---------------------------------- */
			'cmpsian_stat1_number' => 2500,
			'cmpsian_stat1_label'  => 'Students',
			'cmpsian_stat2_number' => 120,
			'cmpsian_stat2_label'  => 'Teachers',
			'cmpsian_stat3_number' => 98,
			'cmpsian_stat3_label'  => 'Pass Rate (%)',
			'cmpsian_stat4_number' => 30,
			'cmpsian_stat4_label'  => 'Years of Excellence',

			/* ---- Admission CTA --------------------------------------- */
			'cmpsian_cta_heading' => 'Admissions Open for 2026',
			'cmpsian_cta_text'    => 'Give your child the gift of a world-class education. Limited seats available across all grades.',
			'cmpsian_cta_btn'     => 'Start Application',
			'cmpsian_cta_url'     => '#',

			/* ---- Preloader -------------------------------------------- */
			'cmpsian_preloader_enable' => true,

			/* ---- Footer ---------------------------------------------- */
			'cmpsian_footer_about'     => 'Campussian is a modern educational institution dedicated to academic excellence, character building and preparing students for a rapidly changing world.',
			'cmpsian_copyright'        => '',

			/* ---- Section Visibility Toggles ---- */
			'cmpsian_sections_enable_hero'             => true,
			'cmpsian_sections_enable_principal'        => true,
			'cmpsian_sections_enable_news'             => true,
			'cmpsian_sections_enable_notices_events'   => true,
			'cmpsian_sections_enable_stats_counter'    => true,
			'cmpsian_sections_enable_admission_cta'    => true,
			'cmpsian_sections_enable_gallery_preview'  => true,
			'cmpsian_sections_enable_facilities'       => true,
			'cmpsian_sections_enable_teachers'         => true,
			'cmpsian_sections_enable_alumni'           => true,

			/* ---- News Defaults ---- */
			'cmpsian_news_title'                       => 'Latest News',

			/* ---- Facilities Defaults ---- */
			'cmpsian_facilities_title'                 => 'Our Facilities',

			/* ---- Teachers Defaults ---- */
			'cmpsian_teachers_title'                   => 'Our Faculty',

			/* ---- Alumni Defaults ---- */
			'cmpsian_alumni_title'                     => 'Alumni Success',

		);

		/**
		 * Filter the Customizer default values.
		 *
		 * @since 1.0.0
		 * @param array $defaults Default option values.
		 */
		return apply_filters( 'cmpsian_default_options', $defaults );
	}
}

if ( ! function_exists( 'cmpsian_get_option' ) ) {
	/**
	 * Retrieve a theme_mod value, falling back to the registered default.
	 *
	 * @since 1.0.0
	 * @param string $key The option key (theme_mod name).
	 * @return mixed The stored or default value; empty string if unknown.
	 */
	function cmpsian_get_option( $key ) {
		$defaults = cmpsian_default_options();
		$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		return get_theme_mod( $key, $default );
	}
}