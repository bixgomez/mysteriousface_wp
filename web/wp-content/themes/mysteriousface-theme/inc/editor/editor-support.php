<?php
/**
 * Theme editor support toggles.
 *
 * @package Mysterious_Face
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether the request targets a template or template-part REST route.
 *
 * @param string $route REST route path.
 * @return bool
 */
function mysteriousface_is_template_rest_route( $route ) {
	return 0 === strpos( $route, '/wp/v2/templates' ) || 0 === strpos( $route, '/wp/v2/template-parts' );
}

/**
 * Disable widgets block editor for this theme.
 */
function phi_theme_support() {
	remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'phi_theme_support' );

/**
 * Disable switching from content editing into template editing.
 *
 * @param array                  $settings Editor settings.
 * @param WP_Block_Editor_Context $context Editor context.
 * @return array
 */
function mysteriousface_disable_template_mode( $settings, $context ) {
	$post = isset( $context->post ) ? $context->post : null;

	if ( $post instanceof WP_Post && 'wp_template' !== $post->post_type && 'wp_template_part' !== $post->post_type ) {
		$settings['supportsTemplateMode'] = false;
	}

	return $settings;
}
add_filter( 'block_editor_settings_all', 'mysteriousface_disable_template_mode', 10, 2 );

/**
 * Hide the Site Editor entry point while the theme is active.
 */
function mysteriousface_hide_site_editor_menu() {
	remove_submenu_page( 'themes.php', 'site-editor.php' );
}
add_action( 'admin_menu', 'mysteriousface_hide_site_editor_menu', 999 );

/**
 * Block direct access to template editing screens.
 */
function mysteriousface_block_template_editor_screens() {
	global $pagenow;

	$post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : '';
	$post_id   = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0;

	if ( ! $post_type && $post_id ) {
		$post_type = get_post_type( $post_id );
	}

	if ( 'site-editor.php' === $pagenow || in_array( $post_type, array( 'wp_template', 'wp_template_part' ), true ) ) {
		wp_die(
			esc_html__( 'Template editing is disabled for this theme. Edit template files in the theme instead.', 'mysteriousface-theme' ),
			403
		);
	}
}
add_action( 'admin_init', 'mysteriousface_block_template_editor_screens' );

/**
 * Block template and template-part writes through the REST API.
 *
 * @param mixed           $result  Existing result.
 * @param WP_REST_Server  $server  Server instance.
 * @param WP_REST_Request $request Request instance.
 * @return mixed
 */
function mysteriousface_block_template_rest_writes( $result, $server, $request ) {
	$route     = $request->get_route();
	$method    = $request->get_method();
	$is_write  = in_array( $method, array( 'POST', 'PUT', 'PATCH', 'DELETE' ), true );

	if ( $is_write && mysteriousface_is_template_rest_route( $route ) ) {
		return new WP_Error(
			'mysteriousface_template_overrides_disabled',
			__( 'Template overrides are disabled for this theme. Edit the template files instead.', 'mysteriousface-theme' ),
			array( 'status' => 403 )
		);
	}

	return $result;
}
add_filter( 'rest_pre_dispatch', 'mysteriousface_block_template_rest_writes', 10, 3 );
