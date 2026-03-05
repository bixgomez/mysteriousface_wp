<?php
/**
 * Dynamic album block rendering and registration.
 *
 * @package Mysterious_Face
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		get_template_directory_uri() . '/js/blocks-music.js',
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
