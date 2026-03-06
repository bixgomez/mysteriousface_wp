/**
 * View Page Same Tab
 *
 * Adds a "View Page (same tab)" button to the block editor header.
 * Uses the WordPress visibility/eye icon.
 *
 * @package Mysteriousface_Plugin
 */

( function() {
	'use strict';

	// WordPress visibility (eye) icon SVG.
	var eyeIconSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"></path></svg>';

	/**
	 * Find the existing "View Post" button and add our same-tab version.
	 */
	function addSameTabButton() {
		// Find the header settings area.
		var headerSettings = document.querySelector( '.editor-header__settings' );
		if ( ! headerSettings ) {
			return false;
		}

		// Check if we already added our button.
		if ( headerSettings.querySelector( '.mf-view-same-tab' ) ) {
			return true;
		}

		// Find the existing "View Post" link (first link with target="_blank" in header settings).
		var viewPostLink = headerSettings.querySelector( 'a[target="_blank"][aria-label="View Post"], a[target="_blank"][aria-label="View Page"]' );
		if ( ! viewPostLink ) {
			return false;
		}

		// Create our same-tab button.
		var sameTabLink = document.createElement( 'a' );
		sameTabLink.href = viewPostLink.href;
		sameTabLink.className = 'components-button is-compact has-icon mf-view-same-tab';
		sameTabLink.setAttribute( 'aria-label', 'Open Post' );
		sameTabLink.setAttribute( 'title', 'Open Post' );
		sameTabLink.innerHTML = eyeIconSVG;

		// Update the existing button to indicate it opens in new window.
		viewPostLink.setAttribute( 'aria-label', 'Open Post In New Window' );
		viewPostLink.setAttribute( 'title', 'Open Post In New Window' );

		// Insert before the existing view post link.
		viewPostLink.parentNode.insertBefore( sameTabLink, viewPostLink );

		return true;
	}

	/**
	 * Fix snackbar "View Post" links to open in same tab.
	 */
	function fixSnackbarLinks() {
		var snackbarLinks = document.querySelectorAll( '.components-snackbar a[target="_blank"]' );
		snackbarLinks.forEach( function( link ) {
			link.removeAttribute( 'target' );
			link.removeAttribute( 'rel' );
		} );
	}

	/**
	 * Initialize with retry logic for editor load timing.
	 */
	function init() {
		var attempts = 0;
		var maxAttempts = 50; // 5 seconds max.

		var interval = setInterval( function() {
			attempts++;

			if ( addSameTabButton() || attempts >= maxAttempts ) {
				clearInterval( interval );
			}
		}, 100 );
	}

	// Wait for DOM ready.
	if ( window.wp && window.wp.domReady ) {
		window.wp.domReady( init );
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}

	// Also watch for navigation changes (switching between posts in editor).
	var observer = new MutationObserver( function( mutations ) {
		// Debounce.
		clearTimeout( window.mfViewSameTabDebounce );
		window.mfViewSameTabDebounce = setTimeout( function() {
			addSameTabButton();
			fixSnackbarLinks();
		}, 200 );
	} );

	// Start observing once DOM is ready.
	document.addEventListener( 'DOMContentLoaded', function() {
		var target = document.body;
		if ( target ) {
			observer.observe( target, {
				childList: true,
				subtree: true
			} );
		}
	} );
} )();
