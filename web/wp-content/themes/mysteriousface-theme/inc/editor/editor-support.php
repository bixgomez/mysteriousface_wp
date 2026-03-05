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
 * Disable widgets block editor for this theme.
 */
function phi_theme_support() {
	remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'phi_theme_support' );

/**
 * Disable block editor for song posts while that template remains legacy PHP.
 *
 * @param bool   $use_block_editor Whether to use block editor.
 * @param string $post_type        Current post type.
 * @return bool
 */
function mysteriousface_theme_disable_block_editor( $use_block_editor, $post_type ) {
	if ( 'song' === $post_type ) {
		return false;
	}

	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'mysteriousface_theme_disable_block_editor', 10, 2 );
