/* =========================================================================
   VASTRA VEDA — theme.js
   1. Header state
   2. Intro slider
   3. Overlays (search + slide-out menu)
   4. Pinned horizontal category row
   ====================================================================== */
( function () {
	'use strict';

	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	/* ------------------------------------------------------------ 1 */
	function initHeader() {
		var header = document.querySelector( '[data-vv-header]' );
		if ( ! header ) {
			return;
		}

		var hero = document.querySelector( '[data-vv-hero]' );
		var lastY = window.pageYOffset;
		var ticking = false;

		function apply() {
			var y = window.pageYOffset;
			var threshold = hero ? hero.offsetHeight - header.offsetHeight : 40;

			header.classList.toggle( 'is-solid', y > threshold );
			lastY = y;
			ticking = false;
		}

		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				window.requestAnimationFrame( apply );
				ticking = true;
			}
		}, { passive: true } );

		apply();
	}

	/* ------------------------------------------------------------ 2 */
	function initHero() {
		var hero = document.querySelector( '[data-vv-hero]' );
		if ( ! hero ) {
			return;
		}

		var slides = Array.prototype.slice.call( hero.querySelectorAll( '[data-vv-slide]' ) );
		var dots = Array.prototype.slice.call( hero.querySelectorAll( '[data-vv-dot]' ) );
		var skip = hero.querySelector( '[data-vv-skip]' );
		var index = 0;
		var timer = null;
		var delay = ( window.vvData && parseInt( window.vvData.sliderDelay, 10 ) ) || 0;

		function go( next ) {
			if ( ! slides.length ) {
				return;
			}
			index = ( next + slides.length ) % slides.length;

			slides.forEach( function ( slide, i ) {
				var active = i === index;
				slide.classList.toggle( 'is-active', active );
				if ( active ) {
					slide.removeAttribute( 'aria-hidden' );
				} else {
					slide.setAttribute( 'aria-hidden', 'true' );
				}
			} );

			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === index );
				dot.setAttribute( 'aria-selected', i === index ? 'true' : 'false' );
			} );
		}

		function start() {
			stop();
			if ( delay > 0 && slides.length > 1 && ! reduceMotion.matches ) {
				timer = window.setInterval( function () {
					go( index + 1 );
				}, delay );
			}
		}

		function stop() {
			if ( timer ) {
				window.clearInterval( timer );
				timer = null;
			}
		}

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				go( parseInt( dot.getAttribute( 'data-vv-dot' ), 10 ) );
				start();
			} );
		} );

		hero.addEventListener( 'mouseenter', stop );
		hero.addEventListener( 'mouseleave', start );
		document.addEventListener( 'visibilitychange', function () {
			if ( document.hidden ) {
				stop();
			} else {
				start();
			}
		} );

		if ( skip ) {
			skip.addEventListener( 'click', function () {
				var next = hero.nextElementSibling;
				if ( next ) {
					next.scrollIntoView( {
						behavior: reduceMotion.matches ? 'auto' : 'smooth',
						block: 'start'
					} );
				}
			} );
		}

		/* Keyboard support on the rail. */
		hero.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'ArrowRight' || e.key === 'ArrowDown' ) {
				go( index + 1 );
				start();
			}
			if ( e.key === 'ArrowLeft' || e.key === 'ArrowUp' ) {
				go( index - 1 );
				start();
			}
		} );

		go( 0 );
		start();
	}

	/* ------------------------------------------------------------ 3 */
	function initOverlays() {
		var open = null;

		function openPanel( panel, trigger ) {
			if ( ! panel ) {
				return;
			}
			closePanel();
			panel.hidden = false;
			/* Force a reflow so the transition runs. */
			void panel.offsetWidth;
			panel.classList.add( 'is-open' );
			document.body.classList.add( 'vv-lock' );
			if ( trigger ) {
				trigger.setAttribute( 'aria-expanded', 'true' );
			}
			open = { panel: panel, trigger: trigger };

			var focusable = panel.querySelector( 'input, a, button' );
			if ( focusable ) {
				window.setTimeout( function () { focusable.focus(); }, 120 );
			}
		}

		function closePanel() {
			if ( ! open ) {
				return;
			}
			var panel = open.panel;
			var trigger = open.trigger;
			panel.classList.remove( 'is-open' );
			document.body.classList.remove( 'vv-lock' );
			if ( trigger ) {
				trigger.setAttribute( 'aria-expanded', 'false' );
				trigger.focus();
			}
			window.setTimeout( function () { panel.hidden = true; }, 400 );
			open = null;
		}

		function bind( openSelector, closeSelector, panelId ) {
			var panel = document.getElementById( panelId );
			Array.prototype.forEach.call( document.querySelectorAll( openSelector ), function ( btn ) {
				btn.addEventListener( 'click', function () { openPanel( panel, btn ); } );
			} );
			Array.prototype.forEach.call( document.querySelectorAll( closeSelector ), function ( btn ) {
				btn.addEventListener( 'click', closePanel );
			} );
		}

		bind( '[data-vv-search-open]', '[data-vv-search-close]', 'vv-search' );
		bind( '[data-vv-menu-open]', '[data-vv-menu-close]', 'vv-offcanvas' );

		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) {
				closePanel();
			}
		} );
	}

	/* ------------------------------------------------------------ 4 */
	function initCategories() {
		var section = document.querySelector( '[data-vv-cats]' );
		if ( ! section ) {
			return;
		}

		var wantsPin = section.getAttribute( 'data-pinned' ) === '1';
		var pin = section.querySelector( '[data-vv-pin]' );
		var viewport = section.querySelector( '[data-vv-viewport]' );
		var track = section.querySelector( '[data-vv-track]' );
		if ( ! pin || ! viewport || ! track ) {
			return;
		}

		var wide = window.matchMedia( '(min-width: 1025px)' );
		var distance = 0;
		var locked = false;
		var ticking = false;

		function measure() {
			/* How far the row must travel to reveal its last card. */
			distance = Math.max( 0, track.scrollWidth - window.innerWidth );

			if ( distance < 40 ) {
				unlock();
				return;
			}
			pin.style.height = ( window.innerHeight + distance ) + 'px';
			render();
		}

		function render() {
			if ( ! locked || ! distance ) {
				return;
			}
			var top = pin.getBoundingClientRect().top;
			var progress = Math.min( 1, Math.max( 0, -top / distance ) );
			track.style.transform = 'translate3d(' + ( -progress * distance ).toFixed( 2 ) + 'px,0,0)';
		}

		function onScroll() {
			if ( ! ticking ) {
				window.requestAnimationFrame( function () {
					render();
					ticking = false;
				} );
				ticking = true;
			}
		}

		function lock() {
			if ( locked ) {
				return;
			}
			locked = true;
			section.classList.add( 'is-locked' );
			viewport.scrollLeft = 0;
			window.addEventListener( 'scroll', onScroll, { passive: true } );
			measure();
		}

		function unlock() {
			locked = false;
			section.classList.remove( 'is-locked' );
			pin.style.height = '';
			track.style.transform = '';
			window.removeEventListener( 'scroll', onScroll );
		}

		function evaluate() {
			if ( wantsPin && wide.matches && ! reduceMotion.matches ) {
				lock();
			} else {
				unlock();
			}
		}

		var resizeTimer;
		window.addEventListener( 'resize', function () {
			window.clearTimeout( resizeTimer );
			resizeTimer = window.setTimeout( function () {
				if ( locked ) {
					measure();
				}
				evaluate();
			}, 180 );
		} );

		if ( wide.addEventListener ) {
			wide.addEventListener( 'change', evaluate );
		}

		/* Re-measure once images have decoded. */
		window.addEventListener( 'load', function () {
			if ( locked ) {
				measure();
			}
		} );

		/* Drag-to-scroll for the non-pinned (touch / narrow) mode. */
		var down = false, startX = 0, startScroll = 0;
		viewport.addEventListener( 'pointerdown', function ( e ) {
			if ( locked || e.pointerType === 'touch' ) {
				return;
			}
			down = true;
			startX = e.clientX;
			startScroll = viewport.scrollLeft;
			viewport.setPointerCapture( e.pointerId );
		} );
		viewport.addEventListener( 'pointermove', function ( e ) {
			if ( ! down ) {
				return;
			}
			viewport.scrollLeft = startScroll - ( e.clientX - startX );
		} );
		[ 'pointerup', 'pointercancel' ].forEach( function ( ev ) {
			viewport.addEventListener( ev, function () { down = false; } );
		} );

		evaluate();
	}

	/* ------------------------------------------------------------ 5 */
	function initAuth() {
		var auth = document.querySelector( '[data-vv-auth]' );
		if ( ! auth ) {
			return;
		}

		/* Login / Register tabs */
		var tabs  = Array.prototype.slice.call( auth.querySelectorAll( '[data-vv-tab]' ) );
		var panes = Array.prototype.slice.call( auth.querySelectorAll( '[data-vv-pane]' ) );
		var strip = auth.querySelector( '.vv-auth__tabs' );

		function show( name ) {
			tabs.forEach( function ( t ) {
				var on = t.getAttribute( 'data-vv-tab' ) === name;
				t.classList.toggle( 'is-active', on );
				t.setAttribute( 'aria-selected', on ? 'true' : 'false' );
			} );
			panes.forEach( function ( p ) {
				p.classList.toggle( 'is-active', p.getAttribute( 'data-vv-pane' ) === name );
			} );
			if ( strip ) {
				strip.classList.toggle( 'is-register', 'register' === name );
			}
			try {
				var url = new URL( window.location.href );
				if ( 'register' === name ) {
					url.searchParams.set( 'action', 'register' );
				} else {
					url.searchParams.delete( 'action' );
				}
				window.history.replaceState( {}, '', url );
			} catch ( e ) { /* older browsers: no deep link, no harm */ }
		}

		tabs.forEach( function ( t ) {
			t.addEventListener( 'click', function () { show( t.getAttribute( 'data-vv-tab' ) ); } );
		} );

		if ( strip && strip.querySelector( '[data-vv-tab="register"].is-active' ) ) {
			strip.classList.add( 'is-register' );
		}

		/* If the server bounced back a registration error, open that tab */
		if ( document.querySelector( '.woocommerce-error' ) &&
			 /register/i.test( window.location.search ) ) {
			show( 'register' );
		}

		/* Show / hide password */
		Array.prototype.forEach.call( auth.querySelectorAll( '[data-vv-reveal]' ), function ( btn ) {
			btn.addEventListener( 'click', function () {
				var input = btn.parentNode.querySelector( 'input' );
				if ( ! input ) {
					return;
				}
				var hidden = 'password' === input.type;
				input.type = hidden ? 'text' : 'password';
				btn.setAttribute( 'aria-label', hidden ? 'Hide password' : 'Show password' );
				btn.classList.toggle( 'is-on', hidden );
			} );
		} );
	}

	/* ------------------------------------------------------------ 6 */
	function initWishlist() {
		var buttons = Array.prototype.slice.call( document.querySelectorAll( '[data-vv-wish]' ) );
		if ( ! buttons.length || ! window.vvData || ! window.vvData.ajaxUrl ) {
			return;
		}

		function renderCount( n ) {
			var one  = ( window.vvData.i18n && window.vvData.i18n.itemOne ) || '%s item';
			var many = ( window.vvData.i18n && window.vvData.i18n.itemMany ) || '%s items';
			var tpl  = 1 === n ? one : many;
			Array.prototype.forEach.call( document.querySelectorAll( '[data-vv-wish-count]' ), function ( el ) {
				el.textContent = tpl.replace( '%s', String( n ) );
			} );
		}

		function toggle( btn ) {
			var id = btn.getAttribute( 'data-vv-wish' );
			if ( ! id || btn.classList.contains( 'is-busy' ) ) {
				return;
			}
			btn.classList.add( 'is-busy' );

			var body = new URLSearchParams();
			body.append( 'action', 'vv_wishlist' );
			body.append( 'product_id', id );
			body.append( 'nonce', window.vvData.wishNonce || '' );

			window.fetch( window.vvData.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: body.toString()
			} )
			.then( function ( r ) { return r.json(); } )
			.then( function ( res ) {
				if ( ! res || ! res.success ) {
					return;
				}
				var on = !! res.data.added;

				/* Every button for this product, wherever it is on the page */
				Array.prototype.forEach.call(
					document.querySelectorAll( '[data-vv-wish="' + id + '"]' ),
					function ( b ) {
						b.classList.toggle( 'is-on', on );
						b.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
						var label = b.querySelector( '.vv-wish__label' );
						if ( label && window.vvData.i18n ) {
							label.textContent = on ? window.vvData.i18n.saved : window.vvData.i18n.save;
						}
					}
				);

				renderCount( res.data.count );

				/* On the wishlist page itself, drop the card out of the grid */
				var grid = document.querySelector( '[data-vv-wishgrid]' );
				if ( grid && ! on ) {
					var card = btn.closest( 'li.product' );
					if ( card ) {
						card.style.transition = 'opacity .3s, transform .3s';
						card.style.opacity = '0';
						card.style.transform = 'scale(.97)';
						window.setTimeout( function () {
							card.remove();
							if ( ! grid.querySelector( 'li.product' ) ) {
								window.location.reload();
							}
						}, 320 );
					}
				}
			} )
			.catch( function () { /* leave the button as it was */ } )
			.then( function () { btn.classList.remove( 'is-busy' ); } );
		}

		/* Delegated, so cards added later still work */
		document.addEventListener( 'click', function ( e ) {
			var btn = e.target.closest ? e.target.closest( '[data-vv-wish]' ) : null;
			if ( ! btn ) {
				return;
			}
			e.preventDefault();
			e.stopPropagation();
			toggle( btn );
		} );
	}

	/* ------------------------------------------------------------ boot */
	ready( function () {
		initHeader();
		initHero();
		initOverlays();
		initCategories();
		initAuth();
		initWishlist();
	} );

	/* Keep the floating pill in sync after AJAX add-to-cart. */
	if ( window.jQuery ) {
		window.jQuery( document.body ).on( 'added_to_cart removed_from_cart', function () {
			/* Fragments handle the count; this just gives feedback. */
			var pill = document.querySelector( '.vv-cart-pill' );
			if ( pill ) {
				pill.classList.add( 'is-bumped' );
				window.setTimeout( function () { pill.classList.remove( 'is-bumped' ); }, 600 );
			}
		} );
	}
} )();
