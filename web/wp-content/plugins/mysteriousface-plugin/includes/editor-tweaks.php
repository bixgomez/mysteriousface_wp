<?php
/**
 * Editor CSS Tweaks.
 *
 * Enqueues CSS adjustments for the block editor UI.
 *
 * @package Mysteriousface_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue editor CSS tweaks.
 */
function mf_plugin_enqueue_editor_tweaks_css() {
	$asset_path = MF_PLUGIN_DIR . 'css/editor-tweaks.css';
	$version    = file_exists( $asset_path ) ? (string) filemtime( $asset_path ) : MF_PLUGIN_VERSION;

	wp_enqueue_style(
		'mf-editor-tweaks',
		MF_PLUGIN_URL . 'css/editor-tweaks.css',
		array(),
		$version
	);
}
add_action( 'enqueue_block_editor_assets', 'mf_plugin_enqueue_editor_tweaks_css' );
