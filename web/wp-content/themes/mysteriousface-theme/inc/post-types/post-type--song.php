<?php

add_action( 'init', 'mysteriousface_theme_register_song_post_type' );

function mysteriousface_theme_register_song_post_type() {
	register_post_type(
		'song',
		array(
			'labels'        => array(
				'name'          => esc_html__( 'Songs', 'mysteriousface-theme' ),
				'singular_name' => esc_html__( 'Song', 'mysteriousface-theme' ),
				'all_items'     => esc_html__( 'All Songs', 'mysteriousface-theme' ),
				'add_new'       => esc_html__( 'Add New Song', 'mysteriousface-theme' ),
				'add_new_item'  => esc_html__( 'Add New Song', 'mysteriousface-theme' ),
				'edit_item'     => esc_html__( 'Edit Song', 'mysteriousface-theme' ),
			),
			'description'   => esc_html__( 'Allowing you to create individual songs.', 'mysteriousface-theme' ),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_position' => 20,
			'menu_icon'     => 'dashicons-tickets-alt',
			'rewrite'       => array(
				'slug' => 'songs',
			),
			'supports'      => array( 'title', 'editor', 'custom-fields' ),
		)
	);
}
