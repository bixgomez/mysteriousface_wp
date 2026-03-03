<?php
/**
 * Album Custom Post Type Meta Boxes
 *
 * Replaces ACF functionality with native WordPress meta boxes
 *
 * @package Mysterious_Face
 */

// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Register Album Meta Boxes
 */
add_action('add_meta_boxes', 'mf_register_album_meta_boxes');

function mf_register_album_meta_boxes() {
	// Remove default Custom Fields meta box
	remove_meta_box('postcustom', 'album', 'normal');
	// Album Songs
	add_meta_box(
		'mf_album_songs',
		'Album Songs',
		'mf_album_songs_callback',
		'album',
		'normal',
		'high'
	);

	// Bandcamp Settings
	add_meta_box(
		'mf_album_bandcamp',
		'Bandcamp Settings',
		'mf_album_bandcamp_callback',
		'album',
		'normal',
		'default'
	);
}

/**
 * Album Songs Meta Box Callback
 */
function mf_album_songs_callback($post) {
	wp_nonce_field('mf_album_songs_nonce', 'mf_album_songs_nonce_field');

	$selected_songs = get_post_meta($post->ID, 'song_ids', true);
	if (!is_array($selected_songs)) {
		$selected_songs = array();
	}

	// Get all published songs
	$songs = get_posts(array(
		'post_type' => 'song',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC',
		'post_status' => array('publish', 'draft')
	));

	// Show selected songs first in their saved order, then remaining songs.
	$songs_by_id = array();
	foreach ($songs as $song) {
		$songs_by_id[(int) $song->ID] = $song;
	}

	$ordered_songs = array();
	foreach ($selected_songs as $song_id) {
		$song_id = (int) $song_id;
		if (isset($songs_by_id[$song_id])) {
			$ordered_songs[] = $songs_by_id[$song_id];
			unset($songs_by_id[$song_id]);
		}
	}

	foreach ($songs_by_id as $song) {
		$ordered_songs[] = $song;
	}

	?>
	<div class="mf-song-checklist">
		<?php if (!empty($ordered_songs)) : ?>
			<p><strong>Select songs to include in this album, then drag to set order:</strong></p>
			<ul class="mf-song-checklist-list">
			<?php foreach ($ordered_songs as $song) : ?>
				<?php
				$checked = in_array($song->ID, $selected_songs) ? 'checked' : '';
				$status = $song->post_status === 'draft' ? ' (Draft)' : '';
				?>
				<li class="mf-song-checklist-item">
					<span class="mf-song-drag-handle" aria-hidden="true">≡</span>
					<label>
						<input type="checkbox" name="mf_song_ids[]" value="<?php echo esc_attr($song->ID); ?>" <?php echo $checked; ?> />
						<?php echo esc_html($song->post_title . $status); ?>
					</label>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p><em>No songs found. <a href="<?php echo admin_url('post-new.php?post_type=song'); ?>">Create a song</a> first.</em></p>
		<?php endif; ?>
	</div>
	<p><small>Checked songs appear on the album page in this list order. Drag rows using the handle (≡) to reorder.</small></p>
	<style>
		.mf-song-checklist-list {
			margin: 0;
		}
		.mf-song-checklist-item {
			align-items: center;
			background: #fff;
			border: 1px solid #ddd;
			display: flex;
			gap: 10px;
			margin-bottom: 6px;
			padding: 8px 10px;
		}
		.mf-song-checklist-item label {
			flex: 1;
		}
		.mf-song-drag-handle {
			color: #666;
			cursor: move;
			font-weight: 700;
			line-height: 1;
			user-select: none;
		}
		.mf-song-sort-placeholder {
			border: 1px dashed #999;
			height: 38px;
			margin-bottom: 6px;
		}
	</style>
	<?php
}

/**
 * Bandcamp Settings Meta Box Callback
 */
function mf_album_bandcamp_callback($post) {
	wp_nonce_field('mf_album_bandcamp_nonce', 'mf_album_bandcamp_nonce_field');

	$bandcamp_embed_code = get_post_meta($post->ID, 'bandcamp_embed_code', true);
	$bandcamp_album_id = get_post_meta($post->ID, 'bandcamp_album_id', true);
	?>
	<p>
		<label for="mf_bandcamp_embed_code"><strong>Bandcamp Embed Code:</strong></label><br>
		<textarea id="mf_bandcamp_embed_code" name="mf_bandcamp_embed_code" rows="4" cols="60"><?php echo esc_textarea($bandcamp_embed_code); ?></textarea>
		<br><small>Full Bandcamp album player embed code</small>
	</p>
	<p>
		<label for="mf_bandcamp_album_id"><strong>Bandcamp Album ID (optional):</strong></label><br>
		<input type="text" id="mf_bandcamp_album_id" name="mf_bandcamp_album_id" value="<?php echo esc_attr($bandcamp_album_id); ?>" size="30" />
		<br><small>Bandcamp album identifier (only used when not using full embed code)</small>
	</p>
	<?php
}

/**
 * Save Album Meta Data
 */
add_action('save_post_album', 'mf_save_album_meta', 10, 2);

function mf_save_album_meta($post_id, $post) {
	// Check if this is an autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Check user permissions
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	// Save Album Songs
	if (isset($_POST['mf_album_songs_nonce_field']) && wp_verify_nonce($_POST['mf_album_songs_nonce_field'], 'mf_album_songs_nonce')) {
		if (isset($_POST['mf_song_ids']) && is_array($_POST['mf_song_ids'])) {
			$song_ids = array_map('intval', $_POST['mf_song_ids']);
			update_post_meta($post_id, 'song_ids', $song_ids);
		} else {
			delete_post_meta($post_id, 'song_ids');
		}
	}

	// Save Bandcamp Settings
	if (isset($_POST['mf_album_bandcamp_nonce_field']) && wp_verify_nonce($_POST['mf_album_bandcamp_nonce_field'], 'mf_album_bandcamp_nonce')) {
		if (isset($_POST['mf_bandcamp_embed_code'])) {
			update_post_meta($post_id, 'bandcamp_embed_code', wp_kses_post($_POST['mf_bandcamp_embed_code']));
		}
		if (isset($_POST['mf_bandcamp_album_id'])) {
			update_post_meta($post_id, 'bandcamp_album_id', sanitize_text_field($_POST['mf_bandcamp_album_id']));
		}
	}
}
