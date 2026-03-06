<?php
/**
 * Plugin Name: Mysterious Face Plugin
 * Description: Custom functionality for the Mysterious Face site.
 * Version: 1.0.0
 * Author: Richard Gilbert
 * Text Domain: mysteriousface-plugin
 *
 * @package Mysteriousface_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MF_PLUGIN_VERSION', '1.0.0' );
define( 'MF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load plugin features.
 */
require_once MF_PLUGIN_DIR . 'includes/view-page-same-tab.php';
require_once MF_PLUGIN_DIR . 'includes/editor-tweaks.php';
