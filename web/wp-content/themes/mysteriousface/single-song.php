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
$has_player = 1;
$classes = 'layout layout--song';
if ((strlen($bandcamp_embed_code) + strlen($bandcamp_album_id)) < 15) :
    $has_player = 0;
    $classes .= ' no-player ';
endif;
?>

    <div class="<?php print $classes ?>">

        <section class="heading">
            <h1 class="node-title"><?php single_post_title(); ?></h1>
            <?php if ($author) :
                echo 'By ' . $author;
            endif; ?>
            <?php if (have_rows('personnel')): ?>
                <div class="personnel">
                    <ul>
                        <?php
                        while (have_rows('personnel')) : the_row();
                            $name = get_sub_field('name');
                            $contribution = get_sub_field('contribution');
                            echo '<li>';
                            echo $name . ': ';
                            echo $contribution;
                            echo '</li>';
                        endwhile;
                        ?>
                    </ul>
                </div>
            <?php endif; ?>
        </section>

        <section class="body">
            <?php the_content(); ?>
        </section>

        <section class="lyrics">
            <?php echo wpautop($lyrics); ?>
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
                                src="https://bandcamp.com/EmbeddedPlayer/album=<?php echo $bandcamp_album_id ?>/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/track=<?php echo $bandcamp_track_id ?>/transparent=true/"
                                seamless=""><a href="http://mysteriousface.bandcamp.com/album/mysterious-face">Mysterious
                                Face by Mysterious Face</a>
                        </iframe>
                    </article>
                    <article class="bandcamp-embed small">
                        <iframe style="border: 0; width: 100%; height: 120px;"
                                src="https://bandcamp.com/EmbeddedPlayer/album=<?php echo $bandcamp_album_id ?>/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/artwork=small/track=<?php echo $bandcamp_track_id ?>/transparent=true/"
                                seamless=""><a href="http://mysteriousface.bandcamp.com/album/mysterious-face">Mysterious
                                Face by Mysterious Face</a></iframe>
                    </article>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <aside>
            <?php

            $this_postID = get_the_ID();
            // echo 'This post\'s ID: ' . $this_postID . '<br>';

            // args
            $args = array(
                'post_type'	=> 'album',
                'meta_query'	=> array(
                    'relation'		=> 'AND',
                    array(
                        'key'	  	=> 'song_ids',
                        'value'	  	=> '"' . $this_postID . '"',
                        'compare' 	=> 'LIKE',
                    ),
                ),
            );

            // query
            $the_query = new WP_Query( $args );

            if( $the_query->have_posts() ):
                echo '<div class="related-albums-block">';
                echo '<h4 class="related-album-header">Appears on</h4><ul class="related-albums">';
                while( $the_query->have_posts() ) : $the_query->the_post();
                    $album_id = get_the_ID();
                    $song_ids = get_field('song_ids', $album_id);
                    // print_r (get_field('song_ids'));
                    echo '<li class="related-album ' . 'related-album--' . $album_id . '"><h5 class="related-album-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h5>';
                    echo '<ul class="related-album-songs">';
                        foreach ($song_ids as $song_id):
                            $permalink = get_permalink($song_id);
                            $title = get_the_title($song_id);
                            if ( $song_id == $this_postID ) :
                                echo '<li class="related-album-song related-album-song--' . $song_id . '">' . esc_html($title) . '</li>';
                            else :
                                echo '<li class="related-album-song related-album-song--' . $song_id . '"><a href="' . esc_url($permalink) . '">' . esc_html($title) . '</a></li>';
                            endif;
                        endforeach;
                    echo '</ul></li>';
                endwhile;
                echo '</ul>';
                echo '</div>';
            endif;

            wp_reset_query();

            ?>
        </aside>
    </div>

<?php
get_footer();
