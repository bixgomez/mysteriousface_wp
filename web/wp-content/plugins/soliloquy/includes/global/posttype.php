<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Soliloquy_Posttype' ) ) {

	/**
	 * Posttype class.
	 *
	 * @since 1.0.0
	 *
	 * @package Soliloquy
	 * @author SoliloquyWP Team <support@soliloquywp.com>
	 */
	class Soliloquy_Posttype {

		/**
		 * Holds the class object.
		 *
		 * @since 1.0.0
		 *
		 * @var object
		 */
		public static $instance;

		/**
		 * Path to the file.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $file = __FILE__;

		/**
		 * Holds the base class object.
		 *
		 * @since 1.0.0
		 *
		 * @var object
		 */
		public $base;

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Load the base class object.
			$this->base = Soliloquy::get_instance();

			// Build the labels for the post type.
			$labels = array(
				'name'               => esc_attr__( 'Soliloquy Sliders', 'soliloquy' ),
				'singular_name'      => esc_attr__( 'Soliloquy', 'soliloquy' ),
				'add_new'            => esc_attr__( 'Add New', 'soliloquy' ),
				'add_new_item'       => esc_attr__( 'Add New Soliloquy Slider', 'soliloquy' ),
				'edit_item'          => esc_attr__( 'Edit Soliloquy Slider', 'soliloquy' ),
				'new_item'           => esc_attr__( 'New Soliloquy Slider', 'soliloquy' ),
				'view_item'          => esc_attr__( 'View Soliloquy Slider', 'soliloquy' ),
				'search_items'       => esc_attr__( 'Search Soliloquy Sliders', 'soliloquy' ),
				'not_found'          => esc_attr__( 'No Soliloquy sliders found.', 'soliloquy' ),
				'not_found_in_trash' => esc_attr__( 'No Soliloquy sliders found in trash.', 'soliloquy' ),
				'parent_item_colon'  => '',
				'menu_name'          => esc_attr__( 'Soliloquy', 'soliloquy' ),
			);
			$labels = apply_filters( 'soliloquy_post_type_labels', $labels );

			// Build out the post type arguments.
			$args = apply_filters(
				'soliloquy_post_type_args',
				[
					'labels'              => $labels,
					'public'              => false,
					'exclude_from_search' => true,
					'publicly_queryable'  => true,
					'show_ui'             => true,
					'show_in_admin_bar'   => false,
					'rewrite'             => false,
					'query_var'           => false,
					'show_in_rest'        => true,
					'rest_base'           => 'soliloquy',
					'capability_type'     => 'post',
					'capabilities'        => array(
						'read_post' => 'read', // Allow any logged-in user to read (filtered by map_meta_cap).
					),
					'menu_position'       => apply_filters( 'soliloquy_post_type_menu_position', 248 ),
					'menu_icon'           => plugins_url( 'assets/css/images/menu-icon@2x.png', $this->base->file ),
					'supports'            => [ 'title', 'author' ],
				]
			);

			// Register the post type with WordPress.
			register_post_type( 'soliloquy', $args );
			add_filter( 'rest_prepare_soliloquy', array( $this, 'prepare_meta' ), 10, 3 );

			// Map meta capabilities for proper ownership checks.
			add_filter( 'map_meta_cap', array( $this, 'map_meta_cap' ), 10, 4 );
		}

		/**
		 * Helper Method to add Soliloquy Meta data to the Rest API
		 *
		 * @param [type] $data Rest Data.
		 * @param [type] $post Post Object.
		 * @param [type] $context Context.
		 * @return void
		 */
		public function prepare_meta( $data, $post, $context ) {

			$slider_data = get_post_meta( $post->ID, '_sol_slider_data', true );

			if ( $slider_data ) {
				$data->data['slider_data'] = $slider_data;
			}

			return $data;
		}

		/**
		 * Map meta capabilities for Soliloquy post type.
		 *
		 * @since 2.5.0
		 * @param array  $caps    Required capabilities.
		 * @param string $cap     Capability being checked.
		 * @param int    $user_id User ID.
		 * @param array  $args    Additional arguments (post ID).
		 * @return array Modified capabilities.
		 */
		public function map_meta_cap( $caps, $cap, $user_id, $args ) {
			if ( ! in_array( $cap, array( 'edit_post', 'delete_post', 'read_post' ), true ) ) {
				return $caps;
			}

			$post_id = isset( $args[0] ) ? (int) $args[0] : 0;
			if ( ! $post_id ) {
				return $caps;
			}

			$post = get_post( $post_id );
			if ( ! $post || 'soliloquy' !== $post->post_type ) {
				return $caps;
			}

			$user = get_userdata( $user_id );
			if ( ! $user ) {
				return $caps;
			}

			$post_author = (int) $post->post_author;

			// Block authors from accessing sliders they don't own.
			if ( in_array( 'author', $user->roles, true ) && $user_id !== $post_author ) {
				return array( 'do_not_allow' );
			}

			if ( 'edit_post' === $cap ) {
				$caps = ( $user_id === $post_author ) ? array( 'edit_posts' ) : array( 'edit_others_posts' );
			}

			if ( 'delete_post' === $cap ) {
				$caps = ( $user_id === $post_author ) ? array( 'delete_posts' ) : array( 'delete_others_posts' );
			}

			if ( 'read_post' === $cap ) {
				$caps = ( 'private' === $post->post_status ) ? array( 'read_private_posts' ) : array( 'read' );
			}

			return $caps;
		}

		/**
		 * Returns the singleton instance of the class.
		 *
		 * @since 1.0.0
		 *
		 * @return object The Soliloquy_Posttype object.
		 */
		public static function get_instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Soliloquy_Posttype ) ) {
				self::$instance = new Soliloquy_Posttype();
			}

			return self::$instance;
		}
	}

}

// Load the posttype class.
$soliloquy_posttype = Soliloquy_Posttype::get_instance();
