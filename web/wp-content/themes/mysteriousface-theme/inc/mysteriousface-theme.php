<?php

// Custom post types.
include('post-types/post-type--song.php');
include('post-types/post-type--album.php');

/**
 * If more than one page exists, return TRUE.
 */
function is_paginated() {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) {
		return true;
	} else {
		return false;
	}
}

/**
 * If last post in query, return TRUE.
 */
function is_last_post($wp_query) {
	$post_current = $wp_query->current_post + 1;
	$post_count = $wp_query->post_count;
	if ( $post_current == $post_count ) {
		return true;
	} else {
		return false;
	}
}
