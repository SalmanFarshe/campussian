/**
 * Campussian Customizer live preview.
 *
 * Binds a handful of settings to postMessage transport so changes render in the
 * preview frame without a full refresh.
 *
 * @package Campussian
 * @since   1.0.0
 */
( function ( $ ) {
	'use strict';

	if ( 'undefined' === typeof wp || ! wp.customize ) {
		return;
	}

	// Site title.
	wp.customize( 'blogname', function ( value ) {
		value.bind( function ( to ) {
			$( '.cmpsian-logo--text' ).text( to );
		} );
	} );

	// Hero title.
	wp.customize( 'cmpsian_hero_title', function ( value ) {
		value.bind( function ( to ) {
			$( '.cmpsian-hero__title' ).text( to );
		} );
	} );

	// Hero subtitle.
	wp.customize( 'cmpsian_hero_subtitle', function ( value ) {
		value.bind( function ( to ) {
			$( '.cmpsian-hero__subtitle' ).text( to );
		} );
	} );

	// CTA heading.
	wp.customize( 'cmpsian_cta_heading', function ( value ) {
		value.bind( function ( to ) {
			$( '.cmpsian-cta__heading' ).text( to );
		} );
	} );

}( jQuery ) );
