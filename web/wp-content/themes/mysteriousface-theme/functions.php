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

		// Load frontend styles and fonts inside editor iframes (block + classic WYSIWYG).
		add_theme_support( 'editor-styles' );
		add_editor_style(
			array(
				'style.css',
				mysteriousface_theme_fonts_url(),
			)
		);
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
 * Get the Google Fonts stylesheet URL used by the theme.
 *
 * @return string
 */
function mysteriousface_theme_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Comfortaa:wght@300;500;700&display=swap';
}

/**
 * Enqueue scripts and styles.
 */
function mysteriousface_theme_scripts() {
	wp_enqueue_style( 'mysteriousface-theme-fonts', mysteriousface_theme_fonts_url(), array(), null );
	wp_enqueue_style( 'mysteriousface-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'mysteriousface-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'mysteriousface-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

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
 * Disable block editor for song posts while that template remains legacy PHP.
 */
function mysteriousface_theme_disable_block_editor( $use_block_editor, $post_type ) {
	if ( 'song' === $post_type ) {
		return false;
	}

	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'mysteriousface_theme_disable_block_editor', 10, 2 );

/**
 * Keep Gutenberg meta boxes usable on Album edit screens.
 *
 * The block editor remembers the meta-box panel height/state per user.
 * If it is collapsed, custom Album meta boxes can look like a tiny footer.
 * Force a practical default open state/height for Album editing UI.
 */
function mysteriousface_theme_album_editor_meta_boxes_defaults() {
	$screen = get_current_screen();
	if ( ! $screen || 'album' !== $screen->post_type || ! method_exists( $screen, 'is_block_editor' ) || ! $screen->is_block_editor() ) {
		return;
	}

	$script = <<<'JS'
( function() {
	if ( ! window.wp || ! wp.domReady || ! wp.data ) {
		return;
	}

	wp.domReady( function() {
		var retries = 0;
		var maxRetries = 20;

		function applyMetaBoxDefaults() {
			var select = wp.data.select( 'core/preferences' );
			var dispatch = wp.data.dispatch( 'core/preferences' );

			if ( ! select || ! dispatch || 'function' !== typeof select.get || 'function' !== typeof dispatch.set ) {
				if ( retries < maxRetries ) {
					retries++;
					window.setTimeout( applyMetaBoxDefaults, 100 );
				}
				return;
			}

			var scope = 'core/edit-post';
			var isOpen = select.get( scope, 'metaBoxesMainIsOpen' );
			var openHeight = Number( select.get( scope, 'metaBoxesMainOpenHeight' ) );

			if ( true !== isOpen ) {
				dispatch.set( scope, 'metaBoxesMainIsOpen', true );
			}

			if ( ! Number.isFinite( openHeight ) || openHeight < 220 ) {
				dispatch.set( scope, 'metaBoxesMainOpenHeight', 320 );
			}
		}

		applyMetaBoxDefaults();
	} );
}() );
JS;

	wp_add_inline_script( 'wp-edit-post', $script, 'after' );
}
add_action( 'enqueue_block_editor_assets', 'mysteriousface_theme_album_editor_meta_boxes_defaults' );

/**
 * Resolve the post ID for dynamic block rendering contexts.
 *
 * @param WP_Block|null $block Current block instance.
 * @return int
 */
function mysteriousface_theme_get_context_post_id( $block = null ) {
	if ( is_object( $block ) && isset( $block->context ) && is_array( $block->context ) && ! empty( $block->context['postId'] ) ) {
		return (int) $block->context['postId'];
	}

	$post_id = get_the_ID();
	if ( $post_id ) {
		return (int) $post_id;
	}

	global $post;
	if ( $post instanceof WP_Post ) {
		return (int) $post->ID;
	}

	return 0;
}

/**
 * Determine whether an album has an available Bandcamp player.
 *
 * @param int $post_id Album post ID.
 * @return bool
 */
function mysteriousface_theme_album_has_player( $post_id ) {
	$bandcamp_embed_code = (string) get_post_meta( $post_id, 'bandcamp_embed_code', true );
	$bandcamp_album_id   = trim( (string) get_post_meta( $post_id, 'bandcamp_album_id', true ) );

	return (
		strlen( trim( $bandcamp_embed_code ) ) > 10
		|| strlen( $bandcamp_album_id ) > 5
	);
}

/**
 * Allowed HTML tags for stored Bandcamp embed code.
 *
 * @return array<string, array<string, bool>>
 */
function mysteriousface_theme_allowed_bandcamp_embed_html() {
	return array(
		'iframe' => array(
			'style'       => true,
			'src'         => true,
			'seamless'    => true,
			'width'       => true,
			'height'      => true,
			'title'       => true,
			'allow'       => true,
			'loading'     => true,
			'frameborder' => true,
		),
		'a'      => array(
			'href'   => true,
			'target' => true,
			'rel'    => true,
		),
	);
}

/**
 * Render callback for the Album shell block.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Block inner content.
 * @param WP_Block|null        $block      Current block instance.
 * @return string
 */
function mysteriousface_theme_render_album_shell_block( $attributes, $content, $block = null ) {
	$post_id = mysteriousface_theme_get_context_post_id( $block );
	$classes = array( 'layout', 'layout--album' );

	if ( $post_id && mysteriousface_theme_album_has_player( $post_id ) ) {
		$classes[] = 'has-player';
	} else {
		$classes[] = 'no-player';
	}

	return sprintf(
		'<div class="%1$s">%2$s</div>',
		esc_attr( implode( ' ', $classes ) ),
		$content
	);
}

/**
 * Render callback for the Album Bandcamp player block.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Block inner content.
 * @param WP_Block|null        $block      Current block instance.
 * @return string
 */
function mysteriousface_theme_render_album_player_block( $attributes, $content, $block = null ) {
	$post_id = mysteriousface_theme_get_context_post_id( $block );
	if ( ! $post_id ) {
		return '';
	}

	$bandcamp_embed_code = (string) get_post_meta( $post_id, 'bandcamp_embed_code', true );
	$bandcamp_album_id   = trim( (string) get_post_meta( $post_id, 'bandcamp_album_id', true ) );

	if ( ! mysteriousface_theme_album_has_player( $post_id ) ) {
		return '';
	}

	ob_start();
	?>
	<section class="player">
		<?php if ( strlen( trim( $bandcamp_embed_code ) ) > 10 ) : ?>
			<article class="bandcamp-embed">
				<?php echo wp_kses( $bandcamp_embed_code, mysteriousface_theme_allowed_bandcamp_embed_html() ); ?>
			</article>
		<?php elseif ( strlen( $bandcamp_album_id ) > 5 ) : ?>
			<article class="bandcamp-embed large">
				<iframe style="border: 0; width: 350px; height: 786px;"
					src="<?php echo esc_url( 'https://bandcamp.com/EmbeddedPlayer/album=' . rawurlencode( $bandcamp_album_id ) . '/size=large/bgcol=ffffff/linkcol=0687f5/transparent=true/' ); ?>"
					seamless></iframe>
			</article>
			<article class="bandcamp-embed small">
				<iframe style="border: 0; width: 100%; height: 120px;"
					src="<?php echo esc_url( 'https://bandcamp.com/EmbeddedPlayer/album=' . rawurlencode( $bandcamp_album_id ) . '/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=true/artwork=small/transparent=true/' ); ?>"
					seamless></iframe>
			</article>
		<?php endif; ?>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Render callback for the Album songs list block.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Block inner content.
 * @param WP_Block|null        $block      Current block instance.
 * @return string
 */
function mysteriousface_theme_render_album_songs_block( $attributes, $content, $block = null ) {
	$post_id = mysteriousface_theme_get_context_post_id( $block );
	if ( ! $post_id ) {
		return '';
	}

	$song_ids = array_map( 'intval', mf_get_album_songs( $post_id ) );
	$song_ids = array_values( array_filter( $song_ids ) );

	if ( empty( $song_ids ) ) {
		return '';
	}

	$menu_id = 'songs-menu-' . $post_id;

	ob_start();
	?>
	<aside>
		<nav role="navigation" aria-labelledby="<?php echo esc_attr( $menu_id ); ?>">
			<h2 class="visually-hidden" id="<?php echo esc_attr( $menu_id ); ?>"><?php esc_html_e( 'Songs menu', 'mysteriousface-theme' ); ?></h2>
			<ul>
				<?php foreach ( $song_ids as $song_id ) : ?>
					<?php
					$permalink = get_permalink( $song_id );
					$title     = get_the_title( $song_id );
					if ( ! $permalink ) {
						continue;
					}
					?>
					<li><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>
	</aside>
	<?php
	return (string) ob_get_clean();
}

/**
 * Register custom dynamic blocks for Album block-template rendering.
 */
function mysteriousface_theme_register_music_blocks() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	wp_register_script(
		'mysteriousface-theme-music-blocks',
		get_template_directory_uri() . '/js/blocks--music.js',
		array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-server-side-render' ),
		_S_VERSION,
		true
	);

	$block_settings = array(
		'api_version'   => 3,
		'editor_script' => 'mysteriousface-theme-music-blocks',
		'supports'      => array(
			'html'     => false,
			'inserter' => false,
			'reusable' => false,
		),
		'uses_context'  => array( 'postId', 'postType' ),
	);

	register_block_type(
		'mysteriousface/album-shell',
		array_merge(
			$block_settings,
			array(
				'render_callback' => 'mysteriousface_theme_render_album_shell_block',
			)
		)
	);

	register_block_type(
		'mysteriousface/album-player',
		array_merge(
			$block_settings,
			array(
				'render_callback' => 'mysteriousface_theme_render_album_player_block',
			)
		)
	);

	register_block_type(
		'mysteriousface/album-songs',
		array_merge(
			$block_settings,
			array(
				'render_callback' => 'mysteriousface_theme_render_album_songs_block',
			)
		)
	);
}
add_action( 'init', 'mysteriousface_theme_register_music_blocks' );

/**
 * Keep custom PHP single template for legacy Song rendering.
 *
 * Block templates are now enabled for this theme, but these post types still
 * rely on custom PHP output.
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

	return $template;
}
add_filter( 'template_include', 'mysteriousface_theme_legacy_templates', 99 );
