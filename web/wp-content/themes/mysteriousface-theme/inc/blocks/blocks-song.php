<?php
/**
 * Dynamic song block rendering and registration.
 *
 * @package Mysterious_Face
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether a song has an available Bandcamp player.
 *
 * @param int $post_id Song post ID.
 * @return bool
 */
function mysteriousface_theme_song_has_player( $post_id ) {
	$bandcamp_embed_code = (string) get_post_meta( $post_id, 'bandcamp_embed_code', true );
	$bandcamp_album_id   = trim( (string) get_post_meta( $post_id, 'bandcamp_album_id', true ) );
	$bandcamp_track_id   = trim( (string) get_post_meta( $post_id, 'bandcamp_track_id', true ) );

	return (
		strlen( trim( $bandcamp_embed_code ) ) > 10
		|| ( strlen( $bandcamp_album_id ) > 5 && strlen( $bandcamp_track_id ) > 5 )
	);
}

/**
 * Render callback for the Song shell block.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Block inner content.
 * @param WP_Block|null        $block      Current block instance.
 * @return string
 */
function mysteriousface_theme_render_song_shell_block( $attributes, $content, $block = null ) {
	$post_id = mysteriousface_theme_get_context_post_id( $block );
	$classes = array( 'layout', 'layout--song' );

	if ( $post_id && mysteriousface_theme_song_has_player( $post_id ) ) {
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
 * Render callback for the Song author line block.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Block inner content.
 * @param WP_Block|null        $block      Current block instance.
 * @return string
 */
function mysteriousface_theme_render_song_authors_block( $attributes, $content, $block = null ) {
	$post_id = mysteriousface_theme_get_context_post_id( $block );
	if ( ! $post_id ) {
		return '';
	}

	$author = trim( (string) get_post_meta( $post_id, 'author', true ) );
	if ( '' === $author ) {
		return '';
	}

	return sprintf(
		'<p class="song-authors">%s</p>',
		esc_html( sprintf( __( 'By %s', 'mysteriousface-theme' ), $author ) )
	);
}

/**
 * Render callback for the Song personnel block.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Block inner content.
 * @param WP_Block|null        $block      Current block instance.
 * @return string
 */
function mysteriousface_theme_render_song_personnel_block( $attributes, $content, $block = null ) {
	$post_id = mysteriousface_theme_get_context_post_id( $block );
	if ( ! $post_id || ! mf_has_personnel( $post_id ) ) {
		return '';
	}

	$personnel = mf_get_personnel( $post_id );
	$rows      = array();

	foreach ( $personnel as $person ) {
		$name         = isset( $person['name'] ) ? trim( (string) $person['name'] ) : '';
		$contribution = isset( $person['contribution'] ) ? trim( (string) $person['contribution'] ) : '';

		if ( '' === $name && '' === $contribution ) {
			continue;
		}

		$line = '' !== $name ? $name : $contribution;
		if ( '' !== $name && '' !== $contribution ) {
			$line = $name . ': ' . $contribution;
		}

		$rows[] = $line;
	}

	if ( empty( $rows ) ) {
		return '';
	}

	ob_start();
	?>
	<div class="personnel">
		<ul>
			<?php foreach ( $rows as $line ) : ?>
				<li><?php echo esc_html( $line ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Render callback for the Song lyrics block.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Block inner content.
 * @param WP_Block|null        $block      Current block instance.
 * @return string
 */
function mysteriousface_theme_render_song_lyrics_block( $attributes, $content, $block = null ) {
	$post_id = mysteriousface_theme_get_context_post_id( $block );
	if ( ! $post_id ) {
		return '';
	}

	$lyrics = trim( (string) get_post_meta( $post_id, 'lyrics', true ) );
	if ( '' === $lyrics ) {
		return '';
	}

	return sprintf(
		'<section class="lyrics">%s</section>',
		wp_kses_post( wpautop( $lyrics ) )
	);
}

/**
 * Render callback for the Song Bandcamp player block.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Block inner content.
 * @param WP_Block|null        $block      Current block instance.
 * @return string
 */
function mysteriousface_theme_render_song_player_block( $attributes, $content, $block = null ) {
	$post_id = mysteriousface_theme_get_context_post_id( $block );
	if ( ! $post_id || ! mysteriousface_theme_song_has_player( $post_id ) ) {
		return '';
	}

	$bandcamp_embed_code = (string) get_post_meta( $post_id, 'bandcamp_embed_code', true );
	$bandcamp_album_id   = trim( (string) get_post_meta( $post_id, 'bandcamp_album_id', true ) );
	$bandcamp_track_id   = trim( (string) get_post_meta( $post_id, 'bandcamp_track_id', true ) );

	ob_start();
	?>
	<section class="player">
		<?php if ( strlen( trim( $bandcamp_embed_code ) ) > 10 ) : ?>
			<article class="bandcamp-embed">
				<?php
				if ( function_exists( 'mysteriousface_theme_allowed_bandcamp_embed_html' ) ) {
					echo wp_kses( $bandcamp_embed_code, mysteriousface_theme_allowed_bandcamp_embed_html() );
				} else {
					echo wp_kses_post( $bandcamp_embed_code );
				}
				?>
			</article>
		<?php elseif ( strlen( $bandcamp_album_id ) > 5 && strlen( $bandcamp_track_id ) > 5 ) : ?>
			<article class="bandcamp-embed large">
				<iframe style="border: 0; width: 350px; height: 470px;"
					src="<?php echo esc_url( 'https://bandcamp.com/EmbeddedPlayer/album=' . rawurlencode( $bandcamp_album_id ) . '/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/track=' . rawurlencode( $bandcamp_track_id ) . '/transparent=true/' ); ?>"
					seamless><a href="http://mysteriousface.bandcamp.com/album/mysterious-face"><?php esc_html_e( 'Mysterious Face by Mysterious Face', 'mysteriousface-theme' ); ?></a></iframe>
			</article>
			<article class="bandcamp-embed small">
				<iframe style="border: 0; width: 100%; height: 120px;"
					src="<?php echo esc_url( 'https://bandcamp.com/EmbeddedPlayer/album=' . rawurlencode( $bandcamp_album_id ) . '/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/artwork=small/track=' . rawurlencode( $bandcamp_track_id ) . '/transparent=true/' ); ?>"
					seamless><a href="http://mysteriousface.bandcamp.com/album/mysterious-face"><?php esc_html_e( 'Mysterious Face by Mysterious Face', 'mysteriousface-theme' ); ?></a></iframe>
			</article>
		<?php endif; ?>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Render callback for the Song related albums block.
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Block inner content.
 * @param WP_Block|null        $block      Current block instance.
 * @return string
 */
function mysteriousface_theme_render_song_related_albums_block( $attributes, $content, $block = null ) {
	$post_id = mysteriousface_theme_get_context_post_id( $block );
	if ( ! $post_id ) {
		return '';
	}

	$album_ids = get_posts(
		array(
			'post_type'      => 'album',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'fields'         => 'ids',
		)
	);

	$related_albums = array();
	foreach ( $album_ids as $album_id ) {
		$song_ids = array_values( array_filter( array_map( 'intval', mf_get_album_songs( $album_id ) ) ) );
		if ( in_array( (int) $post_id, $song_ids, true ) ) {
			$related_albums[ $album_id ] = $song_ids;
		}
	}

	if ( empty( $related_albums ) ) {
		return '';
	}

	ob_start();
	?>
	<aside>
		<div class="related-albums-block">
			<h4 class="related-album-header"><?php esc_html_e( 'Appears on', 'mysteriousface-theme' ); ?></h4>
			<ul class="related-albums">
				<?php foreach ( $related_albums as $album_id => $song_ids ) : ?>
					<?php
					$album_permalink = get_permalink( $album_id );
					if ( ! $album_permalink ) {
						continue;
					}
					?>
					<li class="<?php echo esc_attr( 'related-album related-album--' . (int) $album_id ); ?>">
						<h5 class="related-album-title">
							<a href="<?php echo esc_url( $album_permalink ); ?>"><?php echo esc_html( get_the_title( $album_id ) ); ?></a>
						</h5>
						<ul class="related-album-songs">
							<?php foreach ( $song_ids as $related_song_id ) : ?>
								<?php
								$song_classes = esc_attr( 'related-album-song related-album-song--' . (int) $related_song_id );
								$title        = get_the_title( $related_song_id );
								$permalink    = get_permalink( $related_song_id );
								?>
								<?php if ( (int) $related_song_id === (int) $post_id || ! $permalink ) : ?>
									<li class="<?php echo $song_classes; ?>"><?php echo esc_html( $title ); ?></li>
								<?php else : ?>
									<li class="<?php echo $song_classes; ?>"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</aside>
	<?php
	return (string) ob_get_clean();
}

/**
 * Register custom dynamic blocks for Song block-template rendering.
 */
function mysteriousface_theme_register_song_blocks() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	$music_blocks_asset_path = get_template_directory() . '/js/blocks-music.js';
	$music_blocks_version    = file_exists( $music_blocks_asset_path ) ? (string) filemtime( $music_blocks_asset_path ) : _S_VERSION;

	if ( ! wp_script_is( 'mysteriousface-theme-music-blocks', 'registered' ) ) {
		wp_register_script(
			'mysteriousface-theme-music-blocks',
			get_template_directory_uri() . '/js/blocks-music.js',
			array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-server-side-render' ),
			$music_blocks_version,
			true
		);
	}

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
		'mysteriousface/song-shell',
		array_merge(
			$block_settings,
			array(
				'render_callback' => 'mysteriousface_theme_render_song_shell_block',
			)
		)
	);

	register_block_type(
		'mysteriousface/song-authors',
		array_merge(
			$block_settings,
			array(
				'render_callback' => 'mysteriousface_theme_render_song_authors_block',
			)
		)
	);

	register_block_type(
		'mysteriousface/song-personnel',
		array_merge(
			$block_settings,
			array(
				'render_callback' => 'mysteriousface_theme_render_song_personnel_block',
			)
		)
	);

	register_block_type(
		'mysteriousface/song-lyrics',
		array_merge(
			$block_settings,
			array(
				'render_callback' => 'mysteriousface_theme_render_song_lyrics_block',
			)
		)
	);

	register_block_type(
		'mysteriousface/song-player',
		array_merge(
			$block_settings,
			array(
				'render_callback' => 'mysteriousface_theme_render_song_player_block',
			)
		)
	);

	register_block_type(
		'mysteriousface/song-related-albums',
		array_merge(
			$block_settings,
			array(
				'render_callback' => 'mysteriousface_theme_render_song_related_albums_block',
			)
		)
	);
}
add_action( 'init', 'mysteriousface_theme_register_song_blocks' );
