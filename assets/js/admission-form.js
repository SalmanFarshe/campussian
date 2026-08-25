/**
 * Campussian admission form handler.
 *
 * Submits the front-end admission form via WP admin-ajax so the application is
 * stored as a pending "cmpsian_application" post in the database.
 *
 * Vanilla ES5-safe JavaScript, no jQuery dependency (matches campussian.js).
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */
( function () {
	'use strict';

	var form     = document.getElementById( 'cmpsian-admission-form' );
	var config   = window.CampussianAdmission || {};
	var feedback = document.getElementById( 'cmpsian-admission-feedback' );

	if ( ! form ) {
		return;
	}

	/**
	 * Show a Bootstrap-style alert inside the form feedback area.
	 *
	 * @param {string}  message  The message to display.
	 * @param {boolean} isError  True for danger, false for success.
	 */
	function showMessage( message, isError ) {
		if ( ! feedback ) {
			return;
		}
		feedback.innerHTML = '';
		var alert = document.createElement( 'div' );
		alert.className = isError ? 'alert alert-danger' : 'alert alert-success';
		alert.setAttribute( 'role', 'alert' );
		alert.textContent = message;
		feedback.appendChild( alert );
	}

	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();

		// Let the browser run native HTML5 validation (required fields).
		if ( ! form.checkValidity() ) {
			form.reportValidity();
			return;
		}

		var btn      = form.querySelector( 'button[type="submit"]' );
		var original = btn ? btn.textContent : '';

		if ( btn ) {
			btn.disabled   = true;
			btn.textContent = 'Submitting…';
		}

		// Serialise the whole form (includes the "nonce" hidden field).
		var data = new URLSearchParams( new FormData( form ) );
		data.set( 'action', 'cmpsian_submit_admission' );

		fetch( config.ajaxUrl || '', {
			method: 'POST',
			body: data
		} )
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( result ) {
				var msg = result && result.data && result.data.message
					? result.data.message
					: ( result && result.success
						? 'Application submitted successfully!'
						: 'Something went wrong. Please try again.' );

				showMessage( msg, ! result || ! result.success );

				if ( result && result.success ) {
					form.reset();
				}
			} )
			.catch( function () {
				showMessage( 'Could not submit your application. Please try again.', true );
			} )
			.finally( function () {
				if ( btn ) {
					btn.disabled   = false;
					btn.textContent = original;
				}
			} );
	} );
}() );
