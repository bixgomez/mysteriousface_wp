<?php
/**
 * Meta Helper Functions
 *
 * Provides ACF-like API for easier template usage
 *
 * @package Mysterious_Face
 */

// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Get a meta field value (ACF-like wrapper)
 *
 * @param string $key The meta key
 * @param int|null $post_id The post ID (null for current post)
 * @return mixed The meta value
 */
function mf_get_field($key, $post_id = null) {
	if (!$post_id) {
		$post_id = get_the_ID();
	}
	return get_post_meta($post_id, $key, true);
}

/**
 * Get personnel array from JSON
 *
 * @param int|null $post_id The post ID (null for current post)
 * @return array Array of personnel with 'name' and 'contribution' keys
 */
function mf_get_personnel($post_id = null) {
	if (!$post_id) {
		$post_id = get_the_ID();
	}

	$personnel_json = get_post_meta($post_id, 'personnel', true);
	if (empty($personnel_json)) {
		return array();
	}

	$personnel = json_decode($personnel_json, true);
	return is_array($personnel) ? $personnel : array();
}

/**
 * Check if personnel exists
 *
 * @param int|null $post_id The post ID (null for current post)
 * @return bool True if personnel exists
 */
function mf_has_personnel($post_id = null) {
	$personnel = mf_get_personnel($post_id);
	return !empty($personnel);
}

/**
 * Get song IDs for an album
 *
 * @param int|null $post_id The post ID (null for current post)
 * @return array Array of song post IDs
 */
function mf_get_album_songs($post_id = null) {
	if (!$post_id) {
		$post_id = get_the_ID();
	}

	$song_ids = get_post_meta($post_id, 'song_ids', true);
	return is_array($song_ids) ? $song_ids : array();
}
