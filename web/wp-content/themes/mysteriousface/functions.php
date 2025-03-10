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

if ( ! function_exists( 'mysteriousface_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function mysteriousface_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Mysterious Face, use a find and replace
		 * to change 'mysteriousface' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'mysteriousface', get_template_directory() . '/languages' );

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

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
                'menu-1' => esc_html__( 'Primary', 'mysteriousface' ),
                'menu-2' => esc_html__( 'Social', 'mysteriousface' ),
			)
		);

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
				'mysteriousface_custom_background_args',
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
	}
endif;
add_action( 'after_setup_theme', 'mysteriousface_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mysteriousface_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mysteriousface_content_width', 640 );
}
add_action( 'after_setup_theme', 'mysteriousface_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mysteriousface_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Home Page Slider', 'mysteriousface' ),
			'id'            => 'home-page-slider',
			'description'   => esc_html__( 'Add widgets here.', 'mysteriousface' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'mysteriousface_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mysteriousface_scripts() {
	wp_enqueue_style( 'mysteriousface-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'mysteriousface-style', 'rtl', 'replace' );

	wp_enqueue_script( 'mysteriousface-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
    wp_enqueue_script( 'hc-offcanvas-nav', get_template_directory_uri() .'/js/hc-offcanvas-nav.js', array('jquery'), null, true );
    wp_enqueue_script( 'hc-offcanvas-nav--config', get_template_directory_uri() .'/js/hc-offcanvas-nav--config.js', array('jquery'), null, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mysteriousface_scripts' );

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
require get_template_directory() . '/inc/mysteriousface.php';

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
 * Add provisions for classes and images in menu items.
 */
class Social_Media_Walker extends Walker_Nav_Menu {
    
	// Start level (before the <ul> part)
	function start_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	// End level (after the </ul> part)
	function end_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	// Start element (before each <li> part)
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		// Get custom ACF fields
		$custom_class = get_field('menu_item_class', $item);
		$custom_image = get_field('menu_item_image', $item);

		// Initialize classes array
		$classes = empty($item->classes) ? array() : (array) $item->classes;

		// Check if custom class exists and add it
		if (!empty($custom_class)) {
				$classes[] = $custom_class;
		}

		// Combine classes into a string
		$class_names = !empty($classes) ? ' class="' . esc_attr(join(' ', $classes)) . '"' : '';

		// Output the list item
		$output .= $indent . '<li' . $class_names .'>';

		// Prepare attributes for the <a> tag
		$attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
		$attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target) .'"' : '';
		$attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) .'"' : '';
		$attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url) .'"' : '';

		// Start building the item output
		$item_output = $args->before;

		// Check if custom image exists and is valid
		if (!empty($custom_image) && is_array($custom_image) && isset($custom_image['url'])) {
			$item_output .= '<img src="' . esc_url($custom_image['url']) . '" alt="' . esc_attr($custom_image['alt'] ?? '') . '" class="menu-item-image">';
		} else {
			// Log if image is missing or invalid
			error_log('Image not found or invalid for menu item ID ' . $item->ID);
		}

		// Add the link element
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
		$item_output .= '</a>';

		// Add anything after the link
		$item_output .= $args->after;

		// Append the item output to the overall output
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	// End element (after each </li> part)
	function end_el(&$output, $item, $depth = 0, $args = array()) {
		$output .= "</li>\n";
	}
}
