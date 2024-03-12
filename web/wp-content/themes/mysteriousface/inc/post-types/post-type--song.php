<?php

add_action( 'init', 'post_type__song' );

function post_type__song() {
	register_post_type( 'song', array(
		'labels' => array(
			'name' => 'Songs',
			'singular_name' => 'Song',
			'all_items'     => __( 'All Songs', 'text_domain' ),
			'add_new'       => __( 'Add New Song', 'text_domain' ),
			'add_new_item'  => __( 'Add New Song', 'text_domain' ),
			'edit_item'     => __( 'Edit Song', 'text_domain' ),
		),
		'description' => 'Allowing you to create individual songs.',
		'public' => true,
		'show_in_rest' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-tickets-alt',
    'rewrite' => array('slug' => 'songs'),
		'supports' => array( 'title', 'editor', 'custom-fields' )
	));
}
