<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Mysterious_Face
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function mysteriousface_theme_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'mysteriousface_theme_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function mysteriousface_theme_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'mysteriousface_theme_pingback_header' );

/**
 * Override Navigation block responsive breakpoint from 600px to 781px.
 */
function mysteriousface_navigation_breakpoint_css() {
	$css = '
		/* Show hamburger menu up to 780px (override core 600px) */
		@media (min-width: 600px) and (max-width: 780px) {
			.wp-block-navigation__responsive-container-open {
				display: flex !important;
			}
			.wp-block-navigation__responsive-container:not(.hidden-by-default):not(.is-menu-open) {
				display: none !important;
			}
		}
	';
	wp_add_inline_style( 'wp-block-navigation', $css );
}
add_action( 'wp_enqueue_scripts', 'mysteriousface_navigation_breakpoint_css' );
