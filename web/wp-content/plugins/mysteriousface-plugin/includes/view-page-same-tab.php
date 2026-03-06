<?php
/**
 * View Page Same Tab feature.
 *
 * Adds an additional "View Page" button to the block editor header
 * that opens in the same tab instead of a new tab.
 *
 * @package Mysteriousface_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue the editor script that adds the same-tab view button.
 */
function mf_plugin_enqueue_view_same_tab_script() {
	$asset_path = MF_PLUGIN_DIR . 'js/view-page-same-tab.js';
	$version    = file_exists( $asset_path ) ? (string) filemtime( $asset_path ) : MF_PLUGIN_VERSION;

	wp_enqueue_script(
		'mf-view-page-same-tab',
		MF_PLUGIN_URL . 'js/view-page-same-tab.js',
		array( 'wp-dom-ready' ),
		$version,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'mf_plugin_enqueue_view_same_tab_script' );
