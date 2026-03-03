<?php

add_action( 'init', 'mysteriousface_theme_register_album_post_type' );

function mysteriousface_theme_register_album_post_type() {
	register_post_type(
		'album',
		array(
			'labels'        => array(
				'name'          => esc_html__( 'Albums', 'mysteriousface-theme' ),
				'singular_name' => esc_html__( 'Album', 'mysteriousface-theme' ),
				'all_items'     => esc_html__( 'All Albums', 'mysteriousface-theme' ),
				'add_new'       => esc_html__( 'Add New Album', 'mysteriousface-theme' ),
				'add_new_item'  => esc_html__( 'Add New Album', 'mysteriousface-theme' ),
				'edit_item'     => esc_html__( 'Edit Album', 'mysteriousface-theme' ),
			),
			'description'   => esc_html__( 'Allowing you to group songs into an album.', 'mysteriousface-theme' ),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_position' => 20,
			'menu_icon'     => 'dashicons-tickets-alt',
			'rewrite'       => array(
				'slug' => 'albums',
			),
			'supports'      => array( 'title', 'editor', 'custom-fields' ),
		)
	);
}
