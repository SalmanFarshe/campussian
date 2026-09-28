/**
 * Campussian theme scripts.
 *
 * Handles:
 *  - Light/Dark mode toggle (persisted in localStorage).
 *  - AOS (Animate On Scroll) initialisation.
 *  - Animated statistics counters.
 *  - Notice-board dependency-free lightbox for the gallery.
 *  - Back-to-top button.
 *
 * No jQuery dependency — vanilla ES5-safe JavaScript.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */
( function () {
	'use strict';

	var settings = window.CampussianData || {};

	/* ------------------------------------------------------------------ *
	 * 1. Light / Dark mode toggle
	 * ------------------------------------------------------------------ */
	function initThemeToggle() {
		var root   = document.documentElement;
		var toggle = document.getElementById( 'cmpsianThemeToggle' );

		if ( ! toggle ) {
			return;
		}

		toggle.addEventListener( 'click', function () {
			var current = root.getAttribute( 'data-theme' ) === 'dark' ? 'dark' : 'light';
			var next    = current === 'dark' ? 'light' : 'dark';

			root.setAttribute( 'data-theme', next );
			try {
				window.localStorage.setItem( 'cmpsian-theme', next );
			} catch ( e ) {}

			toggle.setAttribute( 'aria-pressed', next === 'dark' ? 'true' : 'false' );
		} );
	}

	/* ------------------------------------------------------------------ *
	 * 2. AOS init
	 * ------------------------------------------------------------------ */
	function initAOS() {
		if ( typeof window.AOS === 'undefined' ) {
			return;
		}

		var prefersReduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		window.AOS.init( {
			duration: settings.aosDuration || 700,
			offset: settings.aosOffset || 120,
			easing: 'ease-out-cubic',
			once: true,
			disable: prefersReduced
		} );
	}

	/* ------------------------------------------------------------------ *
	 * 7. Preloader
	 * ------------------------------------------------------------------ */
	function initPreloader() {
		var preloader = document.getElementById( 'cmpsianPreloader' );

		if ( ! preloader ) {
			return;
		}

		function hide() {
			preloader.classList.add( 'is-hidden' );
		}

		if ( document.readyState === 'complete' ) {
			hide();
		} else {
			window.addEventListener( 'load', hide );
			// Safety fallback: never block the page for more than 3s.
			setTimeout( hide, 3000 );
		}
	}

	/* ------------------------------------------------------------------ *
	 * 3. Animated counters
	 * ------------------------------------------------------------------ */
	function animateCounter( el ) {
		var target   = parseInt( el.getAttribute( 'data-target' ), 10 ) || 0;
		var duration = 1800;
		var start    = null;

		function step( timestamp ) {
			if ( ! start ) {
				start = timestamp;
			}
			var progress = Math.min( ( timestamp - start ) / duration, 1 );
			// easeOutQuad.
			var eased = 1 - ( 1 - progress ) * ( 1 - progress );
			el.textContent = Math.floor( eased * target ).toLocaleString();

			if ( progress < 1 ) {
				window.requestAnimationFrame( step );
			} else {
				el.textContent = target.toLocaleString();
			}
		}

		window.requestAnimationFrame( step );
	}

	function initCounters() {
		var counters = document.querySelectorAll( '[data-cmpsian-counter]' );

		if ( ! counters.length ) {
			return;
		}

		if ( ! ( 'IntersectionObserver' in window ) ) {
			// Fallback: just set final values.
			counters.forEach( function ( el ) {
				el.textContent = ( parseInt( el.getAttribute( 'data-target' ), 10 ) || 0 ).toLocaleString();
			} );
			return;
		}

		var observer = new IntersectionObserver( function ( entries, obs ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					animateCounter( entry.target );
					obs.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.35 } );

		counters.forEach( function ( el ) {
			observer.observe( el );
		} );
	}

	/* ------------------------------------------------------------------ *
	 * 4. Gallery lightbox (dependency-free, with prev/next controls)
	 * ------------------------------------------------------------------ */
	function initLightbox() {
		var galleries = document.querySelectorAll( '[data-cmpsian-lightbox]' );

		if ( ! galleries.length ) {
			return;
		}

		// Build a single reusable overlay with prev/next controls.
		var overlay = document.createElement( 'div' );
		overlay.className = 'cmpsian-lightbox';
		overlay.setAttribute( 'aria-hidden', 'true' );
		overlay.innerHTML =
			'<button type="button" class="cmpsian-lightbox__close" aria-label="Close">&times;</button>' +
			'<button type="button" class="cmpsian-lightbox__nav cmpsian-lightbox__nav--prev" aria-label="Previous">&#10094;</button>' +
			'<button type="button" class="cmpsian-lightbox__nav cmpsian-lightbox__nav--next" aria-label="Next">&#10095;</button>' +
			'<figure class="cmpsian-lightbox__figure">' +
			'<img class="cmpsian-lightbox__img" src="" alt="" />' +
			'<figcaption class="cmpsian-lightbox__caption"></figcaption>' +
			'</figure>';
		document.body.appendChild( overlay );

		var imgEl     = overlay.querySelector( '.cmpsian-lightbox__img' );
		var captionEl = overlay.querySelector( '.cmpsian-lightbox__caption' );
		var closeEl   = overlay.querySelector( '.cmpsian-lightbox__close' );
		var prevEl    = overlay.querySelector( '.cmpsian-lightbox__nav--prev' );
		var nextEl    = overlay.querySelector( '.cmpsian-lightbox__nav--next' );

		var currentIndex = 0;
		var items        = [];

		function collectItems() {
			items = [];
			galleries.forEach( function ( gallery ) {
				var links = gallery.querySelectorAll( '.cmpsian-masonry__link' );
				links.forEach( function ( link ) {
					items.push( {
						src: link.getAttribute( 'href' ),
						caption: link.getAttribute( 'data-caption' ) || ''
					} );
				} );
			} );
		}

		function open( index ) {
			if ( index < 0 ) {
				index = items.length - 1;
			} else if ( index >= items.length ) {
				index = 0;
			}
			currentIndex = index;
			var item = items[ currentIndex ];
			imgEl.setAttribute( 'src', item.src );
			imgEl.setAttribute( 'alt', item.caption );
			captionEl.textContent = item.caption;
			overlay.classList.add( 'is-open' );
			overlay.setAttribute( 'aria-hidden', 'false' );
			document.body.classList.add( 'cmpsian-no-scroll' );
		}

		function close() {
			overlay.classList.remove( 'is-open' );
			overlay.setAttribute( 'aria-hidden', 'true' );
			document.body.classList.remove( 'cmpsian-no-scroll' );
			imgEl.setAttribute( 'src', '' );
		}

		collectItems();

		galleries.forEach( function ( gallery ) {
			gallery.addEventListener( 'click', function ( e ) {
				var link = e.target.closest( '.cmpsian-masonry__link' );
				if ( ! link ) {
					return;
				}
				e.preventDefault();
				// Find the clicked link's index in the collected items.
				var index = 0;
				for ( var i = 0; i < items.length; i++ ) {
					if ( items[ i ].src === link.getAttribute( 'href' ) ) {
						index = i;
						break;
					}
				}
				open( index );
			} );
		} );

		prevEl.addEventListener( 'click', function () {
			open( currentIndex - 1 );
		} );
		nextEl.addEventListener( 'click', function () {
			open( currentIndex + 1 );
		} );
		closeEl.addEventListener( 'click', close );
		overlay.addEventListener( 'click', function ( e ) {
			if ( e.target === overlay ) {
				close();
			}
		} );
		document.addEventListener( 'keyup', function ( e ) {
			if ( ! overlay.classList.contains( 'is-open' ) ) {
				return;
			}
			if ( 'Escape' === e.key ) {
				close();
			} else if ( 'ArrowLeft' === e.key ) {
				open( currentIndex - 1 );
			} else if ( 'ArrowRight' === e.key ) {
				open( currentIndex + 1 );
			}
		} );
	}

	/* ------------------------------------------------------------------ *
	 * 5. Back-to-top button
	 * ------------------------------------------------------------------ */
	function initBackToTop() {
		var btn = document.getElementById( 'cmpsianBackToTop' );

		if ( ! btn ) {
			return;
		}

		function toggleVisibility() {
			if ( window.pageYOffset > 500 ) {
				btn.classList.add( 'is-visible' );
			} else {
				btn.classList.remove( 'is-visible' );
			}
		}

		window.addEventListener( 'scroll', toggleVisibility, { passive: true } );
		btn.addEventListener( 'click', function () {
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );

		toggleVisibility();
	}

	/* ------------------------------------------------------------------ *
	 * Boot
	 * ------------------------------------------------------------------ */
	function boot() {
		initPreloader();
		initThemeToggle();
		initAOS();
		initCounters();
		initLightbox();
		initBackToTop();
		initDemoForms();
	}

	/**
	 * Prevent submission of the layout-only demo contact form.
	 *
	 * Keeps the glossary/demo markup inert without inline event handlers.
	 */
	function initDemoForms() {
		document.querySelectorAll( '[data-cmpsian-demo-form]' ).forEach( function ( form ) {
			form.addEventListener( 'submit', function ( event ) {
				event.preventDefault();
			} );
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}

}() );
