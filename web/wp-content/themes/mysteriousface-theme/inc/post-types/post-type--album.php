<?php

add_action( 'init', 'post_type__album' );

function post_type__album() {
	register_post_type( 'album', array(
		'labels' => array(
			'name' => 'Albums',
			'singular_name' => 'Album',
			'all_items'     => __( 'All Albums', 'text_domain' ),
			'add_new'       => __( 'Add New Album', 'text_domain' ),
			'add_new_item'  => __( 'Add New Album', 'text_domain' ),
			'edit_item'     => __( 'Edit Album', 'text_domain' ),
		),
		'description' => 'Allowing you to group songs into an album.',
		'public' => true,
		'show_in_rest' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-tickets-alt',
    'rewrite' => array('slug' => 'albums'),
		'supports' => array( 'title', 'editor', 'custom-fields' )
	));
}
