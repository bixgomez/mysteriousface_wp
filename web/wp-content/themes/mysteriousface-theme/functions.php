<?php
/**
 * Mysterious Face functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mysterious_Face
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'mysteriousface_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function mysteriousface_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Mysterious Face, use a find and replace
		 * to change 'mysteriousface-theme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'mysteriousface-theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'mysteriousface_theme_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Load the main theme stylesheet inside the block editor canvas.
		add_theme_support( 'editor-styles' );
		add_editor_style( 'style.css' );
	}
endif;
add_action( 'after_setup_theme', 'mysteriousface_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mysteriousface_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mysteriousface_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'mysteriousface_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mysteriousface_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Home Page Slider', 'mysteriousface-theme' ),
			'id'            => 'home-page-slider',
			'description'   => esc_html__( 'Add widgets here.', 'mysteriousface-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'mysteriousface_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mysteriousface_theme_scripts() {
	wp_enqueue_style( 'mysteriousface-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'mysteriousface-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'mysteriousface-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'hc-offcanvas-nav', get_template_directory_uri() .'/js/hc-offcanvas-nav.js', array('jquery'), null, true );
	wp_enqueue_script( 'hc-offcanvas-nav--config', get_template_directory_uri() .'/js/hc-offcanvas-nav--config.js', array('jquery'), null, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mysteriousface_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Mysterious Face additions.
 */
require get_template_directory() . '/inc/mysteriousface-theme.php';

/**
 * Custom meta boxes for Songs and Albums
 */
require get_template_directory() . '/inc/meta-boxes/meta-box--song.php';
require get_template_directory() . '/inc/meta-boxes/meta-box--album.php';

/**
 * Meta helper functions
 */
require get_template_directory() . '/inc/helpers/meta-helpers.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * deactivate new block editor
 */
function phi_theme_support() {
  remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'phi_theme_support' );

/**
 * Disable block editor for songs and albums post types.
 * Falls back to the classic editor interface.
 */
function mysteriousface_theme_disable_block_editor( $use_block_editor, $post_type ) {
	$disabled_post_types = array( 'song', 'album' );

	if ( in_array( $post_type, $disabled_post_types ) ) {
		return false;
	}

	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'mysteriousface_theme_disable_block_editor', 10, 2 );

/**
 * Keep custom PHP single templates for legacy Song/Album rendering.
 *
 * Block templates are now enabled for this theme, but these post types still
 * rely on custom PHP output (personnel, bandcamp, song relationships).
 *
 * @param string $template Resolved template path.
 * @return string
 */
function mysteriousface_theme_legacy_templates( $template ) {
	if ( is_singular( 'song' ) ) {
		$legacy_template = get_theme_file_path( '/single-song.php' );
		if ( is_file( $legacy_template ) ) {
			return $legacy_template;
		}
	}

	if ( is_singular( 'album' ) ) {
		$legacy_template = get_theme_file_path( '/single-album.php' );
		if ( is_file( $legacy_template ) ) {
			return $legacy_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'mysteriousface_theme_legacy_templates', 99 );
