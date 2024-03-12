<?php
/**
 * Metabox class.
 *
 * @since 2.2.2
 *
 * @package Soliloquy_Featured_Content_Common
 * @author  Tim Carr
 */
class Soliloquy_Featured_Content_Common {

	/**
	 * Holds the class object.
	 *
	 * @since 2.2.2
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 2.2.2
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.2.2
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

		// Get base instance
		$this->base = Soliloquy_Featured_Content::get_instance();

		// Actions and filters
		add_action( 'wp_loaded', array( $this, 'register_publish_hooks' ) );
		add_action( 'save_post', array( $this, 'flush_global_caches' ), 999 );
		add_action( 'pre_post_update', array( $this, 'flush_global_caches' ), 999 );
		add_action( 'soliloquy_flush_caches', array( $this, 'flush_caches' ), 10, 2 );

	}

	/**
	 * Registers publish and publish future actions for each public Post Type,
	 * so FC caches can be flushed
	 *
	 * @since 2.3.0
	 */
	public function register_publish_hooks() {

		// Get public Post Types
		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		// Register publish hooks for each Post Type
		foreach ( $post_types as $post_type => $data ) {
			add_action( 'publish_' . $post_type, array( $this, 'flush_global_caches' ) );
			add_action( 'publish_future_' . $post_type, array( $this, 'flush_global_caches' ) );
		}

	}

	/**
	 * Callback for post types to exclude from the dropdown select box.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of post types to exclude.
	 */
	function get_post_types() {

		$post_types = apply_filters( 'soliloquy_fc_excluded_post_types', array( 'attachment', 'soliloquy', 'envira' ) );
		return (array) $post_types;

	}

	/**
	 * Callback for taxonomies to exclude from the dropdown select box.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of taxonomies to exclude.
	 */
	function get_taxonomies() {

		$taxonomies = apply_filters( 'soliloquy_fc_excluded_taxonomies', array( 'nav_menu' ) );
		return (array) $taxonomies;

	}

	/**
	 * Callback for taxonomy relation options.
	 *
	 * @since 2.2.7
	 *
	 * @return array Array of taxonomies to exclude.
	 */
	function get_taxonomy_relations() {

		$relations = array(
			'AND' => esc_attr__( 'Posts must have ALL of the above taxonomy terms (AND)', 'soliloquy-featured-content' ),
			'IN'  => esc_attr__( 'Posts must have ANY of the above taxonomy terms (IN)', 'soliloquy-featured-content' ),
		);

		// Allow relations to be filtered
		$relations = apply_filters( 'soliloquy_fc_taxonomy_relations', $relations );

		return (array) $relations;

	}

	/**
	 * Returns the available sticky post options for the query
	 *
	 * @since 2.3.4
	 *
	 * @return array Sticky Post Options
	 */
	function get_sticky() {

		$sticky = array(
			array(
				'name'  => esc_attr__( 'Exclude Sticky Posts', 'soliloquy-featured-content' ),
				'value' => 0,
			),
			array(
				'name'  => esc_attr__( 'Include Sticky Posts', 'soliloquy-featured-content' ),
				'value' => 1,
			),
			array(
				'name'  => esc_attr__( 'Only Include Sticky Posts (ignores all other settings)', 'soliloquy-featured-content' ),
				'value' => 2,
			),
		);

		return apply_filters( 'soliloquy_fc_sticky', $sticky );

	}

	/**
	 * Returns the available orderby options for the query.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of orderby data.
	 */
	function get_orderby() {

		$orderby = array(
			array(
				'name'  => esc_attr__( 'Date', 'soliloquy-featured-content' ),
				'value' => 'date',
			),
			array(
				'name'  => esc_attr__( 'ID', 'soliloquy-featured-content' ),
				'value' => 'ID',
			),
			array(
				'name'  => esc_attr__( 'Author', 'soliloquy-featured-content' ),
				'value' => 'author',
			),
			array(
				'name'  => esc_attr__( 'Title', 'soliloquy-featured-content' ),
				'value' => 'title',
			),
			array(
				'name'  => esc_attr__( 'Menu Order', 'soliloquy-featured-content' ),
				'value' => 'menu_order',
			),
			array(
				'name'  => esc_attr__( 'Random', 'soliloquy-featured-content' ),
				'value' => 'rand',
			),
			array(
				'name'  => esc_attr__( 'Comment Count', 'soliloquy-featured-content' ),
				'value' => 'comment_count',
			),
			array(
				'name'  => esc_attr__( 'Post Name', 'soliloquy-featured-content' ),
				'value' => 'name',
			),
			array(
				'name'  => esc_attr__( 'Modified Date', 'soliloquy-featured-content' ),
				'value' => 'modified',
			),
			array(
				'name'  => esc_attr__( 'Meta Value', 'soliloquy-featured-content' ),
				'value' => 'meta_value',
			),
			array(
				'name'  => esc_attr__( 'Meta Value (Numeric)', 'soliloquy-featured-content' ),
				'value' => 'meta_value_num',
			),
		);

		return apply_filters( 'soliloquy_fc_orderby', $orderby );

	}

	/**
	 * Returns the available order options for the query.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of order data.
	 */
	function get_order() {

		$order = array(
			array(
				'name'  => esc_attr__( 'Descending Order', 'soliloquy-featured-content' ),
				'value' => 'DESC',
			),
			array(
				'name'  => esc_attr__( 'Ascending Order', 'soliloquy-featured-content' ),
				'value' => 'ASC',
			),
		);

		return apply_filters( 'soliloquy_fc_order', $order );

	}

	/**
	 * Returns the available post status options for the query.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of post status data.
	 */
	function get_statuses() {

		$statuses = get_post_stati( array( 'internal' => false ), 'objects' );
		return apply_filters( 'soliloquy_fc_statuses', $statuses );

	}

	/**
	 * Returns the available content type options for the query output.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of content type data.
	 */
	function get_content_types() {

		$types = array(
			array(
				'name'  => esc_attr__( 'No Content', 'soliloquy-featured-content' ),
				'value' => 'none',
			),
			array(
				'name'  => esc_attr__( 'Post Content', 'soliloquy-featured-content' ),
				'value' => 'post_content',
			),
			array(
				'name'  => esc_attr__( 'Post Excerpt', 'soliloquy-featured-content' ),
				'value' => 'post_excerpt',
			),
		);

		return apply_filters( 'soliloquy_fc_content_types', $types );

	}

	/**
	 * Flushes the Featured Content data caches globally on save/update of any post.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The current post ID.
	 */
	function flush_global_caches( $post_id ) {

		if ( !class_exists('Soliloquy') ) {
			return;
		}

		// Get all Featured Content Sliders
		$sliders = Soliloquy::get_instance()->get_sliders( false, true ); // false = don't skip empty (i.e. FC) sliders
																		  // true = ignore cache/transient
		if ( is_array( $sliders ) ) {
			foreach ( $sliders as $slider ) {
				// Skip non-FC sliders
				if ( isset( $slider['config'] ) && $slider['config']['type'] !== 'fc' ) {
					continue;
				}

				// Check slider ID exists
				// Does not exist on slider creation
				if ( ! isset( $slider['id'] ) ) {
					continue;
				}

				// Delete the ID cache.
				delete_transient( '_sol_cache_' . $slider['id'] );
				delete_transient( '_sol_fc_' . $slider['id'] );

				// Delete the slug cache.
				$slug = get_post_meta( $slider['id'], '_sol_slider_data', true );
				if ( ! empty( $slug['config']['slug'] ) ) {
					delete_transient( '_sol_cache_' . $slug['config']['slug'] );
					delete_transient( '_sol_fc_' . $slug['config']['slug'] );
				}
			}
		}

	}

	/**
	 * Flushes the Featured Content data caches on save.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $post_id The current post ID.
	 * @param string $slug The current slider slug.
	 */
	function flush_caches( $post_id, $slug ) {

		delete_transient( '_sol_fc_' . $post_id );
		delete_transient( '_sol_fc_' . $slug );

	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object The Soliloquy_Featured_Content_Common object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Soliloquy_Featured_Content_Common ) ) {
			self::$instance = new Soliloquy_Featured_Content_Common();
		}

		return self::$instance;

	}

}

// Load the metabox class.
$soliloquy_featured_content_common = Soliloquy_Featured_Content_Common::get_instance();