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
$song_ids = mf_get_album_songs();
$has_player = (
	strlen(trim((string) $bandcamp_embed_code)) > 10
	|| strlen(trim((string) $bandcamp_album_id)) > 5
);
$classes = 'layout layout--album';
if (!$has_player) :
	$classes .= ' no-player ';
else :
	$classes .= ' has-player ';
endif;
$allowed_bandcamp_embed = array(
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
	'a' => array(
		'href'   => true,
		'target' => true,
		'rel'    => true,
	),
);
?>

	<div class="<?php echo esc_attr( trim( $classes ) ); ?>">

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
						<?php echo wp_kses( $bandcamp_embed_code, $allowed_bandcamp_embed ); ?>
					</article>
				<?php elseif (strlen($bandcamp_album_id) > 5): ?>
					<article class="bandcamp-embed large">
						<iframe style="border: 0; width: 350px; height: 786px;"
								src="https://bandcamp.com/EmbeddedPlayer/album=<?php echo esc_attr($bandcamp_album_id); ?>/size=large/bgcol=ffffff/linkcol=0687f5/transparent=true/"
								seamless></iframe>
					</article>
					<article class="bandcamp-embed small">
						<iframe style="border: 0; width: 100%; height: 120px;"
								src="https://bandcamp.com/EmbeddedPlayer/album=<?php echo esc_attr($bandcamp_album_id); ?>/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=true/artwork=small/transparent=true/"
								seamless=""></iframe>
					</article>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<aside>
			<?php if ($song_ids): ?>
				<nav role="navigation" aria-labelledby="songs-menu">
					<h2 class="visually-hidden" id="songs-menu">Songs menu</h2>
					<ul>
						<?php
						foreach ($song_ids as $song_id):
							$permalink = get_permalink($song_id);
							$title = get_the_title($song_id);
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
