<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Mysterious_Face
 */
get_header();

$author = get_post_meta(get_the_ID(), 'author', true);
$lyrics = get_post_meta(get_the_ID(), 'lyrics', true);
$bandcamp_embed_code = get_post_meta(get_the_ID(), 'bandcamp_embed_code', true);
$bandcamp_track_id = get_post_meta(get_the_ID(), 'bandcamp_track_id', true);
$bandcamp_album_id = get_post_meta(get_the_ID(), 'bandcamp_album_id', true);
$has_player = (
    strlen(trim((string) $bandcamp_embed_code)) > 10
    || (
        strlen(trim((string) $bandcamp_album_id)) > 5
        && strlen(trim((string) $bandcamp_track_id)) > 5
    )
);
$classes = 'layout layout--song';
if (!$has_player) :
    $classes .= ' no-player ';
endif;
?>

    <div class="<?php echo esc_attr( trim( $classes ) ); ?>">

        <section class="heading">
            <h1 class="node-title"><?php single_post_title(); ?></h1>
            <?php if ($author) :
                echo 'By ' . esc_html($author);
            endif; ?>
            <?php if (mf_has_personnel()): ?>
                <div class="personnel">
                    <ul>
                        <?php
                        $personnel = mf_get_personnel();
                        foreach ($personnel as $person) :
                            echo '<li>';
                            echo esc_html($person['name']) . ': ';
                            echo esc_html($person['contribution']);
                            echo '</li>';
                        endforeach;
                        ?>
                    </ul>
                </div>
            <?php endif; ?>
        </section>

        <section class="body">
            <?php the_content(); ?>
        </section>

        <section class="lyrics">
            <?php echo wp_kses_post( wpautop($lyrics) ); ?>
        </section>

        <?php if ($has_player) : ?>
            <section class="player">
                <?php if (strlen($bandcamp_embed_code) > 10): ?>
                    <article class="bandcamp-embed">
                        <?php echo $bandcamp_embed_code; ?>
                    </article>
                <?php elseif (strlen($bandcamp_album_id) > 5): ?>
                    <article class="bandcamp-embed large">
                        <iframe style="border: 0; width: 350px; height: 470px;"
                                src="https://bandcamp.com/EmbeddedPlayer/album=<?php echo esc_attr($bandcamp_album_id); ?>/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/track=<?php echo esc_attr($bandcamp_track_id); ?>/transparent=true/"
                                seamless=""><a href="http://mysteriousface.bandcamp.com/album/mysterious-face">Mysterious
                                Face by Mysterious Face</a>
                        </iframe>
                    </article>
                    <article class="bandcamp-embed small">
                        <iframe style="border: 0; width: 100%; height: 120px;"
                                src="https://bandcamp.com/EmbeddedPlayer/album=<?php echo esc_attr($bandcamp_album_id); ?>/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/artwork=small/track=<?php echo esc_attr($bandcamp_track_id); ?>/transparent=true/"
                                seamless=""><a href="http://mysteriousface.bandcamp.com/album/mysterious-face">Mysterious
                                Face by Mysterious Face</a></iframe>
                    </article>
                <?php endif; ?>
            </section>
        <?php endif; ?>

	        <aside>
	            <?php

	            $this_postID = get_the_ID();

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
	                $song_ids = array_map( 'intval', mf_get_album_songs( $album_id ) );
	                if ( in_array( (int) $this_postID, $song_ids, true ) ) {
	                    $related_albums[ $album_id ] = $song_ids;
	                }
	            }

	            if ( ! empty( $related_albums ) ) :
	                echo '<div class="related-albums-block">';
	                echo '<h4 class="related-album-header">Appears on</h4><ul class="related-albums">';
	                foreach ( $related_albums as $album_id => $song_ids ) :
                    echo '<li class="related-album ' . esc_attr( 'related-album--' . (int) $album_id ) . '"><h5 class="related-album-title"><a href="' . esc_url( get_permalink( $album_id ) ) . '">' . esc_html( get_the_title( $album_id ) ) . '</a></h5>';
                    echo '<ul class="related-album-songs">';
                        foreach ( $song_ids as $song_id ) :
                            $permalink = get_permalink( $song_id );
                            $title = get_the_title( $song_id );
                            if ( (int) $song_id === (int) $this_postID ) :
                                echo '<li class="related-album-song ' . esc_attr( 'related-album-song--' . (int) $song_id ) . '">' . esc_html($title) . '</li>';
                            else :
                                echo '<li class="related-album-song ' . esc_attr( 'related-album-song--' . (int) $song_id ) . '"><a href="' . esc_url($permalink) . '">' . esc_html($title) . '</a></li>';
                            endif;
                        endforeach;
	                    echo '</ul></li>';
	                endforeach;
	                echo '</ul>';
	                echo '</div>';
	            endif;

	            ?>
	        </aside>
    </div>

<?php
get_footer();
