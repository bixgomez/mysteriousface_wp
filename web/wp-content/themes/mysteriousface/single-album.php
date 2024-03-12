<?php
/**
 * The template for displaying all single album posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Mysterious_Face
 */
get_header();

$bandcamp_embed_code = get_post_meta(get_the_ID(), 'bandcamp_embed_code', true);
$bandcamp_album_id = get_post_meta(get_the_ID(), 'bandcamp_album_id', true);
$songs = get_field('songs');
$song_ids = get_field('song_ids');
$has_player = 1;
$classes = 'layout layout--album';
if ((strlen($bandcamp_embed_code) + strlen($bandcamp_album_id)) < 15) :
    $has_player = 0;
    $classes .= ' no-player ';
else :
    $classes .= ' has-player ';
endif;
?>

    <div class="<?php print $classes ?>">

        <section class="heading">
            <h1 class="node-title"><?php single_post_title(); ?></h1>
        </section>

        <section class="body">
            <?php the_content(); ?>
        </section>

        <?php if ($has_player) : ?>
            <section class="player">
                <?php if (strlen($bandcamp_embed_code) > 10): ?>
                    <article class="bandcamp-embed">
                        <?php echo $bandcamp_embed_code; ?>
                    </article>
                <?php elseif (strlen($bandcamp_album_id) > 5): ?>
                    <article class="bandcamp-embed large">
                        <iframe style="border: 0; width: 350px; height: 786px;"
                                src="https://bandcamp.com/EmbeddedPlayer/album=<?php echo $bandcamp_album_id ?>/size=large/bgcol=ffffff/linkcol=0687f5/transparent=true/"
                                seamless></iframe>
                    </article>
                    <article class="bandcamp-embed small">
                        <iframe style="border: 0; width: 100%; height: 120px;"
                                src="https://bandcamp.com/EmbeddedPlayer/album=<?php echo $bandcamp_album_id ?>/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=true/artwork=small/transparent=true/"
                                seamless=""></iframe>
                    </article>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <aside>
            <?php if ($song_ids): ?>
                <nav role="navigation" aria-labelledby="songs-menu">
                    <h2 class="visually-hidden" id="songs-menu">Songs menu</h2>
                    <!-- https://www.advancedcustomfields.com/resources/relationship/ -->
                    <ul>
                        <?php
                        foreach ($song_ids as $song_id):
                            $permalink = get_permalink($song_id);
                            $title = get_the_title($song_id);
                            $custom_field = get_field('field_name', $song_id);
                            echo '<li><a href="' . esc_url($permalink) . '">' . esc_html($title) . '</a></li>';
                        endforeach;
                        ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </aside>
    </div>

<?php
get_footer();
