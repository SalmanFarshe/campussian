/**
 * Campussian admin admission workflow.
 *
 * Handles, on the admin side:
 *  1. Approve / Reject buttons in the Applications list → AJAX to
 *     cmpsian_approve_application / cmpsian_reject_application.
 *  2. Quick "Add Student" button + modal on the Students list → AJAX to
 *     cmpsian_admin_add_student.
 *
 * Vanilla JS (no jQuery dependency required by modern browsers).
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */
( function () {
	'use strict';

	var conf = window.CampussianAdmin || {};

	/**
	 * Fire a POST request to admin-ajax.php with URLSearchParams.
	 *
	 * @param {string} action The AJAX action name.
	 * @param {Object} data   Additional key/value payload.
	 * @param {string} nonce  Localized nonce key (property name of conf).
	 * @return {Promise}
	 */
	function post( action, data, nonce ) {
		var body = new URLSearchParams();
		body.set( 'action', action );

		if ( conf[ nonce ] ) {
			body.set( 'nonce', conf[ nonce ] );
		}

		Object.keys( data ).forEach( function ( key ) {
			body.set( key, data[ key ] );
		} );

		return fetch( conf.ajaxUrl || '', {
			method: 'POST',
			credentials: 'same-origin',
			body: body
		} ).then( function ( response ) {
			return response.json();
		} );
	}

	/**
	 * Wire up Approve / Reject buttons in the Applications list.
	 */
	function bindApproveReject() {
		var buttons = document.querySelectorAll( '.cmpsian-approve, .cmpsian-reject' );

		Array.prototype.forEach.call( buttons, function ( btn ) {
			btn.addEventListener( 'click', function () {
				var id     = btn.getAttribute( 'data-id' );
				var approve = btn.classList.contains( 'cmpsian-approve' );
				var action = approve ? 'cmpsian_approve_application' : 'cmpsian_reject_application';
				var message = approve
					? 'Approve this application and create a student record?'
					: 'Reject this application?';

				if ( ! window.confirm( message ) ) {
					return;
				}

				btn.disabled = true;

				post( action, { app_id: id }, 'approveNonce' )
					.then( function ( res ) {
						var msg = res && res.data && res.data.message
							? res.data.message
							: ( res && res.success ? 'Done.' : 'Request failed.' );

						window.alert( msg );

						if ( res && res.success ) {
							window.location.reload();
						} else {
							btn.disabled = false;
						}
					} )
					.catch( function () {
						window.alert( 'Request failed. Please try again.' );
						btn.disabled = false;
					} );
			} );
		} );
	}

	/**
	 * Wire up the quick "Add Student" modal on the Students list screen.
	 */
	function bindQuickAddStudent() {
		var openBtn = document.getElementById( 'cmpsian-quick-add-student' );
		var modal   = document.getElementById( 'cmpsian-add-student-modal' );
		var overlay = document.getElementById( 'cmpsian-add-student-overlay' );
		var form    = document.getElementById( 'cmpsian-add-student-form' );
		var closeBtn = document.querySelector( '.cmpsian-modal__close' );

		if ( ! openBtn || ! modal || ! overlay || ! form ) {
			return;
		}

		function open() {
			overlay.classList.add( 'is-open' );
			document.body.classList.add( 'cmpsian-modal-open' );
		}

		function close() {
			overlay.classList.remove( 'is-open' );
			document.body.classList.remove( 'cmpsian-modal-open' );
		}

		openBtn.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			open();
		} );

		if ( closeBtn ) {
			closeBtn.addEventListener( 'click', close );
		}

		overlay.addEventListener( 'click', function ( e ) {
			if ( e.target === overlay ) {
				close();
			}
		} );

		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();

			var btn = form.querySelector( 'button[type="submit"]' );
			if ( btn ) {
				btn.disabled = true;
			}

			var payload = {
				name:     document.getElementById( 'cmpsian-new-name' ).value,
				dob:      document.getElementById( 'cmpsian-new-dob' ).value,
				grade:    document.getElementById( 'cmpsian-new-grade' ).value,
				guardian: document.getElementById( 'cmpsian-new-guardian' ).value,
				phone:    document.getElementById( 'cmpsian-new-phone' ).value,
				email:    document.getElementById( 'cmpsian-new-email' ).value,
				address:  document.getElementById( 'cmpsian-new-address' ).value,
				roll:     document.getElementById( 'cmpsian-new-roll' ).value
			};

			post( 'cmpsian_admin_add_student', payload, 'addStudentNonce' )
				.then( function ( res ) {
					var msg = res && res.data && res.data.message
						? res.data.message
						: ( res && res.success ? 'Student added.' : 'Request failed.' );

					window.alert( msg );

					if ( res && res.success ) {
						close();
						window.location.reload();
					} else if ( btn ) {
						btn.disabled = false;
					}
				} )
				.catch( function () {
					window.alert( 'Request failed. Please try again.' );
					if ( btn ) {
						btn.disabled = false;
					}
				} );
		} );
	}

	function boot() {
		bindApproveReject();
		bindQuickAddStudent();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
}() );
