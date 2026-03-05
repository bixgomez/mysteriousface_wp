<?php
/**
 * Template-part edit guardrails.
 *
 * @package Mysterious_Face
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the current REST request is a direct template-part editor context.
 *
 * @param WP_REST_Request $request REST request instance.
 * @return bool
 */
function mysteriousface_theme_is_template_part_editor_context( WP_REST_Request $request ) {
	$referer = (string) $request->get_header( 'referer' );

	if ( '' === $referer && isset( $_SERVER['HTTP_REFERER'] ) ) {
		$referer = wp_unslash( (string) $_SERVER['HTTP_REFERER'] );
	}

	if ( '' === $referer ) {
		return false;
	}

	$parts = wp_parse_url( $referer );
	if ( empty( $parts ) ) {
		return false;
	}

	$query_vars = array();
	if ( ! empty( $parts['query'] ) ) {
		parse_str( $parts['query'], $query_vars );
	}

	$post_type = isset( $query_vars['postType'] ) ? (string) $query_vars['postType'] : '';
	$path      = isset( $query_vars['path'] ) ? rawurldecode( (string) $query_vars['path'] ) : '';

	/*
	 * Strict allowlist:
	 * - must be template-part post type, and
	 * - must be inside Site Editor Patterns routes.
	 *
	 * This prevents "Edit original" from regular template editing contexts
	 * from mutating shared header/footer parts.
	 */
	if ( 'wp_template_part' !== $post_type ) {
		return false;
	}

	if ( '' === $path ) {
		return false;
	}

	return false !== strpos( $path, '/patterns' );
}

/**
 * Resolve target template-part slug for a REST save request.
 *
 * @param stdClass|WP_Error $prepared_post Prepared post object from REST controller.
 * @param WP_REST_Request   $request       REST request instance.
 * @return string
 */
function mysteriousface_theme_get_target_template_part_slug( $prepared_post, WP_REST_Request $request ) {
	if ( $prepared_post instanceof stdClass && ! empty( $prepared_post->post_name ) ) {
		return sanitize_title( (string) $prepared_post->post_name );
	}

	$slug = (string) $request->get_param( 'slug' );
	if ( '' !== $slug ) {
		return sanitize_title( $slug );
	}

	$id = (int) $request->get_param( 'id' );
	if ( ! $id ) {
		$url_params = $request->get_url_params();
		if ( isset( $url_params['id'] ) ) {
			$id = (int) $url_params['id'];
		}
	}

	if ( $id ) {
		$post = get_post( $id );
		if ( $post instanceof WP_Post && 'wp_template_part' === $post->post_type ) {
			return sanitize_title( $post->post_name );
		}
	}

	return '';
}

/**
 * Protect shared header/footer template parts from accidental in-context edits.
 *
 * Allows edits only from the Template Parts editor context.
 *
 * @param stdClass|WP_Error $prepared_post Prepared post object from REST controller.
 * @param WP_REST_Request   $request       REST request instance.
 * @return stdClass|WP_Error
 */
function mysteriousface_theme_protect_header_footer_template_parts( $prepared_post, WP_REST_Request $request ) {
	if ( is_wp_error( $prepared_post ) ) {
		return $prepared_post;
	}

	$slug = mysteriousface_theme_get_target_template_part_slug( $prepared_post, $request );
	if ( ! in_array( $slug, array( 'header', 'footer' ), true ) ) {
		return $prepared_post;
	}

	if ( mysteriousface_theme_is_template_part_editor_context( $request ) ) {
		return $prepared_post;
	}

	return new WP_Error(
		'mysteriousface_template_part_locked',
		__( 'Header and Footer can only be edited from Appearance -> Editor -> Patterns -> Template Parts.', 'mysteriousface-theme' ),
		array( 'status' => 403 )
	);
}
add_filter( 'rest_pre_insert_wp_template_part', 'mysteriousface_theme_protect_header_footer_template_parts', 10, 2 );
