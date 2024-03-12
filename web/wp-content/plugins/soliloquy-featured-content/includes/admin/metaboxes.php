<?php
/**
 * Metabox class.
 *
 * @since 2.2.2
 *
 * @package Soliloquy_Featured_Content_Metaboxes
 * @author  Tim Carr
 */
class Soliloquy_Featured_Content_Metaboxes {

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
		add_action( 'soliloquy_metabox_styles', array( $this, 'styles' ) );
		add_action( 'soliloquy_metabox_scripts', array( $this, 'scripts' ) );
		add_filter( 'soliloquy_defaults', array( $this, 'defaults' ), 10, 2 );
		add_filter( 'soliloquy_slider_types', array( $this, 'types' ) );
		add_action( 'soliloquy_display_fc', array( $this, 'settings_screen' ) );
		add_filter( 'soliloquy_save_settings', array( $this, 'save' ), 10, 2 );

	}

	/**
	 * Registers and enqueues featured content styles.
	 *
	 * @since 1.0.0
	 */
	public function styles() {

		// Register featured content styles.
		wp_register_style( $this->base->plugin_slug . '-style', plugins_url( 'assets/css/admin.css', $this->base->file ), array( $this->base->plugin_slug . '-chosen' ), $this->base->version );

		// Enqueue featured content styles.
		wp_enqueue_style( $this->base->plugin_slug . '-style' );

	}

	/**
	 * Registers and enqueues featured content scripts.
	 *
	 * @since 1.0.0
	 */
	public function scripts() {

		// Register featured content scripts.
		wp_register_script( $this->base->plugin_slug . '-script', plugins_url( 'assets/js/fc.js', $this->base->file ), array( 'jquery', $this->base->plugin_slug . '-chosen' ), $this->base->plugin_slug, true );

		// Enqueue featured content scripts.
		wp_enqueue_script( $this->base->plugin_slug . '-script' );

		// Localize script with nonces.
		wp_localize_script(
			$this->base->plugin_slug . '-script',
			'soliloquy_fc_metabox',
			array(
				'refresh_nonce' => wp_create_nonce( 'soliloquy-fc-refresh' ),
				'term_nonce'    => wp_create_nonce( 'soliloquy-fc-term-refresh' ),
			)
		);

	}

	/**
	 * Applies a default to the addon setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults  Array of default config values.
	 * @param int   $post_id     The current post ID.
	 * @return array $defaults Amended array of default config values.
	 */
	function defaults( $defaults, $post_id ) {

		$defaults['fc_post_types']           = array( 'post' );
		$defaults['fc_terms']                = array();
		$defaults['fc_terms_relation']       = 'IN';
		$defaults['fc_query']                = 'include';
		$defaults['fc_inc_ex']               = array();
		$defaults['fc_sticky']               = 0;
		$defaults['fc_orderby']              = 'date';
		$defaults['fc_order']                = 'DESC';
		$defaults['fc_number']               = 5;
		$defaults['fc_offset']               = 0;
		$defaults['fc_status']               = 'publish';
		$defaults['fc_post_url']             = 1;
		$defaults['fc_post_title']           = 1;
		$defaults['fc_post_title_link']      = 1;
		$defaults['fc_content_type']         = 'post_excerpt';
		$defaults['fc_content_length']       = 40;
		$defaults['fc_content_ellipses']     = 1;
		$defaults['fc_content_html']         = 1;
		$defaults['fc_read_more']            = 1;
		$defaults['fc_read_more_text']       = esc_attr__( 'Continue Reading...', 'soliloquy-featured-content' );
		$defaults['fc_fallback']             = '';
		$defaults['fc_disable_post_classes'] = 0;
		$defaults['fc_no_cache']             = 0;
		// $defaults['fc_ignore_current'] = 0;
		return $defaults;

	}

	/**
	 * Adds the "Featured Content" slider type to the list of available options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $types  Types of sliders to select.
	 * @return array $types Amended types of sliders to select.
	 */
	function types( $types ) {

		$types['fc'] = esc_attr__( 'Featured Content', 'soliloquy-featured-content' );
		return $types;

	}

	/**
	 * Callback for displaying the UI for setting fc options.
	 *
	 * @since 1.0.0
	 *
	 * @param object $post The current post object.
	 */
	function settings_screen( $post ) {

		// Load the settings for the addon.
		$instance = Soliloquy_Metaboxes::get_instance();
		$common   = Soliloquy_Featured_Content_Common::get_instance();
		?>
		<div id="soliloquy-fc">
		<div class="soliloquy-config-header">
			<h2 class="soliloquy-intro"><?php esc_html_e( 'The settings below adjust the Featured Content settings for the slider.', 'soliloquy-featured-content' ); ?></h2>
			<p class="soliloquy-help"><?php esc_html_e( 'Need some help?', 'soliloquy' ); ?><a href="http://soliloquywp.com/docs/featured-content-addon/" target="_blank"><?php esc_html_e( ' Watch a video on how to setup your gallery configuration', 'soliloquy-featured-content' ); ?></a></p>
		</div>

			<h3><?php esc_html_e( 'Query Settings', 'soliloquy-featured-content' ); ?></h3>
			<table class="form-table">

				<tbody>

					<tr id="soliloquy-config-fc-post-type-box">
						<th scope="row">
							<label for="soliloquy-config-fc-post-type"><?php esc_html_e( 'Select Your Post Type(s)', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<div class="soliloquy-select">
							<select id="soliloquy-config-fc-post-type" class="soliloquy-chosen" name="_soliloquy[fc_post_types][]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select post type(s) to query (defaults to post)...', 'soliloquy-featured-content' ); ?>" data-soliloquy-chosen-options='{ "width": "100%" }'>
							<?php
								$post_types = get_post_types( array( 'public' => true ) );
							foreach ( (array) $post_types as $post_type ) {
								if ( in_array( $post_type, $common->get_post_types() ) ) {
									continue;
								}

								$object = get_post_type_object( $post_type );
								echo '<option value="' . esc_attr( $post_type ) . '"' . selected( $post_type, in_array( $post_type, (array) $instance->get_config( 'fc_post_types', $instance->get_config_default( 'fc_post_types' ) ) ) ? $post_type : '', false ) . '>' . esc_html( $object->labels->singular_name ) . '</option>';
							}
							?>
							</select>
							</div>
							<p class="description"><?php esc_html_e( 'Determines the post types to query.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-terms-box">
						<th scope="row">
							<label for="soliloquy-config-fc-terms"><?php esc_html_e( 'Select Your Taxonomy Term(s)', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<div class="soliloquy-select">
							<select id="soliloquy-config-fc-terms" class="soliloquy-chosen" name="_soliloquy[fc_terms][]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select taxonomy terms(s) to query (defaults to none)...', 'soliloquy-featured-content' ); ?>" data-soliloquy-chosen-options='{ "width": "100%" }'>
							<?php
								$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
							foreach ( (array) $taxonomies as $taxonomy ) {
								if ( in_array( $taxonomy, $common->get_taxonomies() ) ) {
									continue;
								}

								$terms = get_terms( $taxonomy->name );
								echo '<optgroup label="' . esc_attr( $taxonomy->labels->name ) . '">';
								foreach ( $terms as $term ) {
									echo '<option value="' . esc_attr( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug ) . '"' . selected( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, in_array( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, (array) $instance->get_config( 'fc_terms', $instance->get_config_default( 'fc_terms' ) ) ) ? strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug : '', false ) . '>' . esc_html( ucwords( $term->name ) ) . '</option>';
								}
									echo '</optgroup>';
							}
							?>
							</select>
							</div>
							<p class="description"><?php esc_html_e( 'Determines the taxonomy terms that should be queried based on post type selection.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-terms-relation-box">
						<th scope="row">
							<label for="soliloquy-config-fc-terms-relation"><?php esc_html_e( 'Taxonomy Term(s) Relation', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<div class="soliloquy-select">
							<select id="soliloquy-config-fc-terms-relation" name="_soliloquy[fc_terms_relation]" class="soliloquy-chosen" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "100%" }'>
								<?php
								$relations = $common->get_taxonomy_relations();
								foreach ( (array) $relations as $relation => $label ) {
									$selected = selected( $instance->get_config( 'fc_terms_relation', $instance->get_config_default( 'fc_terms_relation' ) ), $relation, false );
									echo '<option value="' . $relation . '"' . $selected . '>' . $label . '</option>';
								}
								?>
							</select>
							</div>
							<p class="description"><?php esc_html_e( 'Determines whether all or any taxonomy terms must be present in the above Posts.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-inc-ex-box">
						<th scope="row">
							<label for="soliloquy-config-fc-inc-ex-box"><?php esc_html_e( 'Include or Exclude?', 'soliloquy-featured-content' ); ?></label>

						</th>
						<td>
							<label for="soliloquy-config-fc-inc-ex">
							<div class="soliloquy-select">
								<select id="soliloquy-config-fc-query" class="soliloquy-chosen" name="_soliloquy[fc_query]" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "150px" }'>
									<option value="include" <?php selected( 'include', $instance->get_config( 'fc_query', $instance->get_config_default( 'fc_query' ) ) ); ?>><?php esc_html_e( 'Include', 'soliloquy-featured-content' ); ?></option>
									<option value="exclude" <?php selected( 'exclude', $instance->get_config( 'fc_query', $instance->get_config_default( 'fc_query' ) ) ); ?>><?php esc_html_e( 'Exclude', 'soliloquy-featured-content' ); ?></option>
								</select>
							</div>
								<?php esc_html_e( ' ONLY the items below.', 'soliloquy-featured-content' ); ?>
							</label>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-inc-ex-items-box">
						<th scope="row">
							<label for="soliloquy-config-fc-inc-ex-items-box"><?php esc_html_e( 'Select Items?', 'soliloquy-featured-content' ); ?></label>

						</th>
						<td>
							<div class="soliloquy-select">
							<select id="soliloquy-config-fc-inc-ex-items" class="soliloquy-chosen" name="_soliloquy[fc_inc_ex][]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Make your selection (defaults to none)...', 'soliloquy-featured-content' ); ?>" data-soliloquy-chosen-options='{ "width": "100%" }'>
							<?php
								$post_types = get_post_types( array( 'public' => true ) );
							foreach ( (array) $post_types as $post_type ) {
								if ( in_array( $post_type, $common->get_post_types() ) ) {
									continue;
								}

								$object = get_post_type_object( $post_type );
								$posts  = get_posts(
									array(
										'post_type'      => $post_type,
										'posts_per_page' => apply_filters( 'soliloquy_fc_max_queried_posts', 500 ),
										'no_found_rows'  => true,
										'cache_results'  => false,
									)
								);
								echo '<optgroup label="' . esc_attr( $object->labels->name ) . '">';
								foreach ( (array) $posts as $item ) {
									echo '<option value="' . absint( $item->ID ) . '"' . selected( $item->ID, in_array( $item->ID, (array) $instance->get_config( 'fc_inc_ex', $instance->get_config_default( 'fc_inc_ex' ) ) ) ? $item->ID : '', false ) . '>' . esc_html( ucwords( $item->post_title ) ) . '</option>';
								}
									echo '</optgroup>';
							}
							?>
							</select>
							</div>
							<p class="description"><?php esc_html_e( 'Will include or exclude ONLY the selected post(s).', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-sticky-box">
						<th scope="row">
							<label for="soliloquy-config-fc-sticky"><?php esc_html_e( 'Include Sticky Posts', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<div class="soliloquy-select">
							<select id="soliloquy-config-fc-sticky" class="soliloquy-chosen" name="_soliloquy[fc_sticky]" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "100%" }'>
								<?php
								foreach ( (array) $common->get_sticky() as $array => $data ) {
									echo '<option value="' . esc_attr( $data['value'] ) . '"' . selected( $data['value'], $instance->get_config( 'fc_sticky', $instance->get_config_default( 'fc_sticky' ) ), false ) . '>' . esc_html( $data['name'] ) . '</option>';
								}
								?>
							</select>
							</div>
							<p class="description">
								<?php esc_html_e( 'Controls if and how Sticky Posts are displayed within the slider.', 'soliloquy-featured-content' ); ?><br />
								<?php
								_e(
									'<strong>Exclude Sticky Posts:</strong> all Sticky Posts that meet the query criteria set above and below will be excluded.
',
									'soliloquy-featured-content'
								);
								?>
								<br />
								<?php _e( '<strong>Include Sticky Posts:</strong> Sticky Posts are treated as ordinary Posts, and will only appear if they meet the other query criteria set above and below.', 'soliloquy-featured-content' ); ?><br />
								<?php
								_e(
									'<strong>Only Include Sticky Posts:</strong> all Sticky Posts that meet the query criteria set above and below will be shown. Ordinary (non-Sticky) Posts will not be displayed.
',
									'soliloquy-featured-content'
								);
								?>
								<br />

							</p>

						</td>
					</tr>

					<tr id="soliloquy-config-fc-orderby-box">
						<th scope="row">
							<label for="soliloquy-config-fc-orderby"><?php esc_html_e( 'Sort Posts By', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<div class="soliloquy-select">
							<select id="soliloquy-config-fc-orderby" class="soliloquy-chosen" name="_soliloquy[fc_orderby]" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "100%" }'>
								<?php
								foreach ( (array) $common->get_orderby() as $array => $data ) {
									echo '<option value="' . esc_attr( $data['value'] ) . '"' . selected( $data['value'], $instance->get_config( 'fc_orderby', $instance->get_config_default( 'fc_orderby' ) ), false ) . '>' . esc_html( $data['name'] ) . '</option>';
								}
								?>
							</select>
							</div>
							<p class="description"><?php esc_html_e( 'Determines how the posts are sorted in the slider.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-meta-key-box">
						<th scope="row">
							<label for="soliloquy-config-fc-meta-key"><?php esc_html_e( 'Meta Key', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-meta-key" type="text" name="_soliloquy[fc_meta_key]" value="<?php echo ( $instance->get_config( 'fc_meta_key', $instance->get_config_default( 'fc_meta_key' ) ) ); ?>" />
							<p class="description"><?php esc_html_e( 'The meta key to use when ordering Posts. Used when Sort Posts By = Meta Value', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-order-box">
						<th scope="row">
							<label for="soliloquy-config-fc-order"><?php esc_html_e( 'Order Posts By', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<div class="soliloquy-select">
							<select id="soliloquy-config-fc-order" class="soliloquy-chosen" name="_soliloquy[fc_order]" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "100%" }'>
							<?php
							foreach ( (array) $common->get_order() as $array => $data ) {
								echo '<option value="' . esc_attr( $data['value'] ) . '"' . selected( $data['value'], $instance->get_config( 'fc_order', $instance->get_config_default( 'fc_order' ) ), false ) . '>' . esc_html( $data['name'] ) . '</option>';
							}
							?>
							</select>
							</div>
							<p class="description"><?php esc_html_e( 'Determines how the posts are ordered in the slider.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-number-box">
						<th scope="row">
							<label for="soliloquy-config-fc-number"><?php esc_html_e( 'Number of Slides', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-number" type="number" name="_soliloquy[fc_number]" value="<?php echo $instance->get_config( 'fc_number', $instance->get_config_default( 'fc_number' ) ); ?>" />
							<p class="description"><?php esc_html_e( 'The number of slides in your Featured Content slider.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-offset-box">
						<th scope="row">
							<label for="soliloquy-config-fc-offset"><?php esc_html_e( 'Posts Offset', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-offset" type="number" name="_soliloquy[fc_offset]" value="<?php echo absint( $instance->get_config( 'fc_offset', $instance->get_config_default( 'fc_offset' ) ) ); ?>" />
							<p class="description"><?php esc_html_e( 'The number of posts to offset in the query.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-status-box">
						<th scope="row">
							<label for="soliloquy-config-fc-status"><?php esc_html_e( 'Post Status', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
						   <div class="soliloquy-select">

								<select id="soliloquy-config-fc-status" class="soliloquy-chosen" name="_soliloquy[fc_status]" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "100%" }'>
								<?php
								foreach ( (array) $common->get_statuses() as $status ) {
									echo '<option value="' . esc_attr( $status->name ) . '"' . selected( $status->name, $instance->get_config( 'fc_status', $instance->get_config_default( 'fc_status' ) ), false ) . '>' . esc_html( $status->label ) . '</option>';
								}
								?>
								</select>

							</div>
							<p class="description"><?php esc_html_e( 'Determines the post status to use for the query.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-no-cache-box">

						<th scope="row">
							<label for="soliloquy-config-fc-no-cache"><?php esc_html_e( 'Disable Caching?', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-no-cache" type="checkbox" name="_soliloquy[fc_no_cache]" value="1" <?php checked( $instance->get_config( 'fc_no_cache', $instance->get_config_default( 'fc_no_cache' ) ), 1 ); ?> />                        <span class="description"><?php esc_html_e( 'Disables caching of query.', 'soliloquy-featured-content' ); ?></span>

						</td>
					</tr>

					<?php do_action( 'soliloquy_fc_box', $post ); ?>

				</tbody>

			</table>

			<h3><?php esc_html_e( 'Content Settings', 'soliloquy-featured-content' ); ?></h3>

			<table class="form-table">

				<tbody>

					<tr id="soliloquy-config-fc-post-url-box">
						<th scope="row">
							<label for="soliloquy-config-fc-post-url"><?php esc_html_e( 'Link Image to Post URL?', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-post-url" type="checkbox" name="_soliloquy[fc_post_url]" value="<?php echo $instance->get_config( 'fc_post_url', $instance->get_config_default( 'fc_post_url' ) ); ?>" <?php checked( $instance->get_config( 'fc_post_url', $instance->get_config_default( 'fc_post_url' ) ), 1 ); ?> />
							<span class="description"><?php esc_html_e( 'Links the image to the post URL.', 'soliloquy-featured-content' ); ?></span>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-post-title-box">
						<th scope="row">
							<label for="soliloquy-config-fc-post-title"><?php esc_html_e( 'Display Post Title?', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-post-title" type="checkbox" name="_soliloquy[fc_post_title]" value="<?php echo $instance->get_config( 'fc_post_title', $instance->get_config_default( 'fc_post_title' ) ); ?>" <?php checked( $instance->get_config( 'fc_post_title', $instance->get_config_default( 'fc_post_title' ) ), 1 ); ?> />
							<span class="description"><?php esc_html_e( 'Displays the post title over the image.', 'soliloquy-featured-content' ); ?></span>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-post-title-link-box">
						<th scope="row">
							<label for="soliloquy-config-fc-post-title-link"><?php esc_html_e( 'Link Post Title to Post URL?', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-post-title-link" type="checkbox" name="_soliloquy[fc_post_title_link]" value="<?php echo $instance->get_config( 'fc_post_title_link', $instance->get_config_default( 'fc_post_title_link' ) ); ?>" <?php checked( $instance->get_config( 'fc_post_title_link', $instance->get_config_default( 'fc_post_title_link' ) ), 1 ); ?> />
							<span class="description"><?php esc_html_e( 'Links the post title to the post URL.', 'soliloquy' ); ?></span>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-content-type-box">
						<th scope="row">
							<label for="soliloquy-config-fc-content-type"><?php esc_html_e( 'Post Content to Display', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<div class="soliloquy-select">
							<select id="soliloquy-config-fc-content-type" class="soliloquy-chosen" name="_soliloquy[fc_content_type]" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "100%" }'>
							<?php
							foreach ( (array) $common->get_content_types() as $array => $data ) {
								echo '<option value="' . esc_attr( $data['value'] ) . '"' . selected( $data['value'], $instance->get_config( 'fc_content_type', $instance->get_config_default( 'fc_content_type' ) ), false ) . '>' . esc_html( $data['name'] ) . '</option>';
							}
							?>
							</select>
							</div>
							<p class="description"><?php esc_html_e( 'Determines the type of content to retrieve and output in the caption.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-content-length-box">
						<th scope="row">
							<label for="soliloquy-config-fc-content-length"><?php esc_html_e( 'Number of Words in Content', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-content-length" type="number" name="_soliloquy[fc_content_length]" value="<?php echo $instance->get_config( 'fc_content_length', $instance->get_config_default( 'fc_content_length' ) ); ?>" />
							<p class="description"><?php esc_html_e( 'Sets the number of words for trimming the post content.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-content-ellipses-box">
						<th scope="row">
							<label for="soliloquy-config-fc-content-ellipses"><?php esc_html_e( 'Append Ellipses to Post Content?', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-content-ellipses" type="checkbox" name="_soliloquy[fc_content_ellipses]" value="<?php echo $instance->get_config( 'fc_content_ellipses', $instance->get_config_default( 'fc_content_ellipses' ) ); ?>" <?php checked( $instance->get_config( 'fc_content_ellipses', $instance->get_config_default( 'fc_content_ellipses' ) ), 1 ); ?> />
							<span class="description"><?php esc_html_e( 'Places an ellipses at the end of the post content.', 'soliloquy-featured-content' ); ?></span>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-content-html">
						<th scope="row">
							<label for="soliloquy-config-fc-read-more"><?php esc_html_e( 'Output Content as HTML?', 'soliloquy' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-content-html" type="checkbox" name="_soliloquy[fc_content_html]" value="<?php echo $instance->get_config( 'fc_read_more', $instance->get_config_default( 'fc_content_html' ) ); ?>" <?php checked( $instance->get_config( 'fc_content_html', $instance->get_config_default( 'fc_content_html' ) ), 1 ); ?> />
							<span class="description"><?php esc_html_e( 'If enabled, retrieves the Post Content as HTML, to allow for formatting to be included in the caption. Uncheck this option if you just want to get the Post Content as plain text.', 'soliloquy' ); ?></span>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-read-more-box">
						<th scope="row">
							<label for="soliloquy-config-fc-read-more"><?php esc_html_e( 'Display Read More Link?', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-read-more" type="checkbox" name="_soliloquy[fc_read_more]" value="<?php echo $instance->get_config( 'fc_read_more', $instance->get_config_default( 'fc_read_more' ) ); ?>" <?php checked( $instance->get_config( 'fc_read_more', $instance->get_config_default( 'fc_read_more' ) ), 1 ); ?> />
							<span class="description"><?php esc_html_e( 'Displays a "read more" link after the post content.', 'soliloquy-featured-content' ); ?></span>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-read-more-text-box">
						<th scope="row">
							<label for="soliloquy-config-fc-read-more-text"><?php esc_html_e( 'Read More Text', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-read-more-text" type="text" name="_soliloquy[fc_read_more_text]" value="<?php echo $instance->get_config( 'fc_read_more_text', $instance->get_config_default( 'fc_read_more_text' ) ); ?>" />
							<span class="description"><?php esc_html_e( 'Sets the read more link text.', 'soliloquy-featured-content' ); ?></span>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-fallback-box">
						<th scope="row">
							<label for="soliloquy-config-fc-fallback"><?php esc_html_e( 'Fallback Image URL', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-fallback" type="text" name="_soliloquy[fc_fallback]" value="<?php echo $instance->get_config( 'fc_fallback', $instance->get_config_default( 'fc_fallback' ) ); ?>" />
							<a href="#" class="button button-soliloquy-secondary soliloquy-insert-image"><?php esc_html_e( 'Insert Image', 'soliloquy-featured-content' ); ?></a>
							<p class="description"><?php esc_html_e( 'This image URL is used if no image URL can be found for a post.', 'soliloquy-featured-content' ); ?></p>
						</td>
					</tr>

					<tr id="soliloquy-config-fc-disable-post-classes-box">
						<th scope="row">
							<label for="soliloquy-config-fc-disable-post-classes"><?php esc_html_e( 'Disable get_post_classes()?', 'soliloquy-featured-content' ); ?></label>
						</th>
						<td>
							<input id="soliloquy-config-fc-disable-post-classes" type="checkbox" name="_soliloquy[fc_disable_post_classes]" value="1" <?php checked( $instance->get_config( 'fc_disable_post_classes', $instance->get_config_default( 'fc_disable_post_classes' ) ), 1 ); ?> />
							<span class="description"><?php esc_html_e( 'If checked, disables appending CSS classes generated by get_post_classes() for each Post\'s slide. Use this if your Posts have a large number of taxonomy terms, taxonomies etc, for better performance.', 'soliloquy-featured-content' ); ?></span>
						</td>
					</tr>

					<?php do_action( 'soliloquy_fc_content_box', $post ); ?>

				</tbody>
			</table>
		</div>
		<?php

	}

	/**
	 * Saves the addon settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings  Array of settings to be saved.
	 * @param int   $post_id     The current post ID.
	 * @return array $settings Amended array of settings to be saved.
	 */
	public function save( $settings, $post_id ) {

		if ( ! isset( $_POST['soliloquy'] ) || ! wp_verify_nonce( sanitize_key( $_POST['soliloquy'] ), 'soliloquy' ) ) {
			return;
		}

		// If not saving a featured content slider, do nothing.
		if ( ! isset( $_POST['_soliloquy']['type_fc'] ) ) {
			return $settings;
		}

		// Save the settings.
		$settings['config']['fc_post_types']           = isset( $_POST['_soliloquy']['fc_post_types'] ) ? stripslashes_deep( $_POST['_soliloquy']['fc_post_types'] ) : array();
		$settings['config']['fc_terms']                = isset( $_POST['_soliloquy']['fc_terms'] ) ? stripslashes_deep( $_POST['_soliloquy']['fc_terms'] ) : array();
		$settings['config']['fc_terms_relation']       = esc_attr( $_POST['_soliloquy']['fc_terms_relation'] );
		$settings['config']['fc_query']                = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['fc_query'] );
		$settings['config']['fc_inc_ex']               = isset( $_POST['_soliloquy']['fc_inc_ex'] ) ? stripslashes_deep( $_POST['_soliloquy']['fc_inc_ex'] ) : array();
		$settings['config']['fc_sticky']               = absint( $_POST['_soliloquy']['fc_sticky'] );
		$settings['config']['fc_orderby']              = esc_attr( $_POST['_soliloquy']['fc_orderby'] );
		$settings['config']['fc_meta_key']             = esc_attr( $_POST['_soliloquy']['fc_meta_key'] );
		$settings['config']['fc_order']                = esc_attr( $_POST['_soliloquy']['fc_order'] );
		$settings['config']['fc_number']               = absint( $_POST['_soliloquy']['fc_number'] );
		$settings['config']['fc_offset']               = absint( $_POST['_soliloquy']['fc_offset'] );
		$settings['config']['fc_status']               = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['fc_status'] );
		$settings['config']['fc_post_url']             = isset( $_POST['_soliloquy']['fc_post_url'] ) ? 1 : 0;
		$settings['config']['fc_post_title']           = isset( $_POST['_soliloquy']['fc_post_title'] ) ? 1 : 0;
		$settings['config']['fc_post_title_link']      = isset( $_POST['_soliloquy']['fc_post_title_link'] ) ? 1 : 0;
		$settings['config']['fc_content_type']         = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['fc_content_type'] );
		$settings['config']['fc_content_length']       = absint( $_POST['_soliloquy']['fc_content_length'] );
		$settings['config']['fc_content_ellipses']     = isset( $_POST['_soliloquy']['fc_content_ellipses'] ) ? 1 : 0;
		$settings['config']['fc_content_html']         = isset( $_POST['_soliloquy']['fc_content_html'] ) ? 1 : 0;
		$settings['config']['fc_read_more']            = isset( $_POST['_soliloquy']['fc_read_more'] ) ? 1 : 0;
		$settings['config']['fc_read_more_text']       = trim( strip_tags( $_POST['_soliloquy']['fc_read_more_text'] ) );
		$settings['config']['fc_fallback']             = esc_url( $_POST['_soliloquy']['fc_fallback'] );
		$settings['config']['fc_disable_post_classes'] = isset( $_POST['_soliloquy']['fc_disable_post_classes'] ) ? 1 : 0;
		$settings['config']['fc_no_cache']             = isset( $_POST['_soliloquy']['fc_no_cache'] ) ? 1 : 0;
		// $settings['config']['fc_ignore_current'] = isset( $_POST['_soliloquy']['fc_ignore_current'] ) ? 1 : 0;
		// Run filter
		$settings = apply_filters( 'soliloquy_fc_save', $settings, $post_id );

		return $settings;

	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return object The Soliloquy_Featured_Content_Metaboxes object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Soliloquy_Featured_Content_Metaboxes ) ) {
			self::$instance = new Soliloquy_Featured_Content_Metaboxes();
		}

		return self::$instance;

	}

}

// Load the metabox class.
$soliloquy_featured_content_metaboxes = Soliloquy_Featured_Content_Metaboxes::get_instance();
