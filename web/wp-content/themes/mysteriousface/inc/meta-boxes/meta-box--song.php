<?php
/**
 * Song Custom Post Type Meta Boxes
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
 * Register Song Meta Boxes
 */
add_action('add_meta_boxes', 'mf_register_song_meta_boxes');

function mf_register_song_meta_boxes() {
    // Remove default Custom Fields meta box
    remove_meta_box('postcustom', 'song', 'normal');
    // Song Details
    add_meta_box(
        'mf_song_details',
        'Song Details',
        'mf_song_details_callback',
        'song',
        'normal',
        'high'
    );

    // Personnel
    add_meta_box(
        'mf_song_personnel',
        'Personnel',
        'mf_song_personnel_callback',
        'song',
        'normal',
        'high'
    );

    // Other Credits
    add_meta_box(
        'mf_song_credits',
        'Other Credits',
        'mf_song_credits_callback',
        'song',
        'normal',
        'default'
    );

    // Bandcamp Settings
    add_meta_box(
        'mf_song_bandcamp',
        'Bandcamp Settings',
        'mf_song_bandcamp_callback',
        'song',
        'normal',
        'default'
    );

    // SoundCloud Settings
    add_meta_box(
        'mf_song_soundcloud',
        'SoundCloud Settings',
        'mf_song_soundcloud_callback',
        'song',
        'normal',
        'default'
    );

    // Lyrics
    add_meta_box(
        'mf_song_lyrics',
        'Lyrics',
        'mf_song_lyrics_callback',
        'song',
        'normal',
        'default'
    );
}

/**
 * Song Details Meta Box Callback
 */
function mf_song_details_callback($post) {
    wp_nonce_field('mf_song_details_nonce', 'mf_song_details_nonce_field');

    $author = get_post_meta($post->ID, 'author', true);
    ?>
    <p>
        <label for="mf_author"><strong>Author(s):</strong></label><br>
        <input type="text" id="mf_author" name="mf_author" value="<?php echo esc_attr($author); ?>" size="50" />
        <br><small>Songwriter/composer name(s)</small>
    </p>
    <?php
}

/**
 * Song Personnel Meta Box Callback
 */
function mf_song_personnel_callback($post) {
    wp_nonce_field('mf_song_personnel_nonce', 'mf_song_personnel_nonce_field');

    $personnel_json = get_post_meta($post->ID, 'personnel', true);
    $personnel = array();

    if (!empty($personnel_json)) {
        $personnel = json_decode($personnel_json, true);
        if (!is_array($personnel)) {
            $personnel = array();
        }
    }
    ?>
    <div class="mf-personnel-repeater">
        <?php if (!empty($personnel)) : ?>
            <?php foreach ($personnel as $person) : ?>
                <div class="mf-personnel-row">
                    <input type="text" name="personnel_name[]" placeholder="Name" value="<?php echo esc_attr($person['name']); ?>" style="width: 40%;" />
                    <input type="text" name="personnel_contribution[]" placeholder="Contribution (instrument/role)" value="<?php echo esc_attr($person['contribution']); ?>" style="width: 40%;" />
                    <button type="button" class="button mf-remove-personnel">Remove</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <p>
        <button type="button" class="button mf-add-personnel">Add Personnel</button>
    </p>
    <input type="hidden" id="mf_personnel_json" name="mf_personnel_json" value="<?php echo esc_attr($personnel_json); ?>" />
    <p><small>List all musicians and their roles on this track</small></p>

    <style>
        .mf-personnel-row {
            margin-bottom: 10px;
        }
        .mf-personnel-row input {
            margin-right: 10px;
        }
    </style>
    <?php
}

/**
 * Song Credits Meta Box Callback
 */
function mf_song_credits_callback($post) {
    wp_nonce_field('mf_song_credits_nonce', 'mf_song_credits_nonce_field');

    $credits = get_post_meta($post->ID, 'credits', true);
    ?>
    <p>
        <label for="mf_credits"><strong>Other Credits:</strong></label><br>
        <textarea id="mf_credits" name="mf_credits" rows="3" cols="60"><?php echo esc_textarea($credits); ?></textarea>
        <br><small>Additional production credits (producer, engineer, etc.)</small>
    </p>
    <?php
}

/**
 * Bandcamp Settings Meta Box Callback
 */
function mf_song_bandcamp_callback($post) {
    wp_nonce_field('mf_song_bandcamp_nonce', 'mf_song_bandcamp_nonce_field');

    $bandcamp_embed_code = get_post_meta($post->ID, 'bandcamp_embed_code', true);
    $bandcamp_album_id = get_post_meta($post->ID, 'bandcamp_album_id', true);
    $bandcamp_track_id = get_post_meta($post->ID, 'bandcamp_track_id', true);
    ?>
    <p>
        <label for="mf_bandcamp_embed_code"><strong>Bandcamp Embed Code:</strong></label><br>
        <textarea id="mf_bandcamp_embed_code" name="mf_bandcamp_embed_code" rows="4" cols="60"><?php echo esc_textarea($bandcamp_embed_code); ?></textarea>
        <br><small>Full Bandcamp player embed code for the track</small>
    </p>
    <p>
        <label for="mf_bandcamp_album_id"><strong>Bandcamp Album ID:</strong></label><br>
        <input type="text" id="mf_bandcamp_album_id" name="mf_bandcamp_album_id" value="<?php echo esc_attr($bandcamp_album_id); ?>" size="30" />
        <br><small>Bandcamp album identifier for API/embed usage</small>
    </p>
    <p>
        <label for="mf_bandcamp_track_id"><strong>Bandcamp Track ID:</strong></label><br>
        <input type="text" id="mf_bandcamp_track_id" name="mf_bandcamp_track_id" value="<?php echo esc_attr($bandcamp_track_id); ?>" size="30" />
        <br><small>Bandcamp track identifier for API/embed usage</small>
    </p>
    <?php
}

/**
 * SoundCloud Settings Meta Box Callback
 */
function mf_song_soundcloud_callback($post) {
    wp_nonce_field('mf_song_soundcloud_nonce', 'mf_song_soundcloud_nonce_field');

    $soundcloud_id = get_post_meta($post->ID, 'soundcloud_id', true);
    ?>
    <p>
        <label for="mf_soundcloud_id"><strong>SoundCloud ID:</strong></label><br>
        <input type="text" id="mf_soundcloud_id" name="mf_soundcloud_id" value="<?php echo esc_attr($soundcloud_id); ?>" size="30" />
        <br><small>SoundCloud track identifier for embed integration</small>
    </p>
    <?php
}

/**
 * Lyrics Meta Box Callback
 */
function mf_song_lyrics_callback($post) {
    wp_nonce_field('mf_song_lyrics_nonce', 'mf_song_lyrics_nonce_field');

    $lyrics = get_post_meta($post->ID, 'lyrics', true);

    wp_editor(
        $lyrics,
        'mf_lyrics',
        array(
            'textarea_name' => 'mf_lyrics',
            'textarea_rows' => 15,
            'media_buttons' => false,
            'teeny' => false,
            'tinymce' => array(
                'toolbar1' => 'bold,italic,underline,blockquote,bullist,numlist,link,unlink,undo,redo',
            ),
        )
    );
    ?>
    <p><small>Full song lyrics with rich text formatting</small></p>
    <?php
}

/**
 * Save Song Meta Data
 */
add_action('save_post_song', 'mf_save_song_meta', 10, 2);

function mf_save_song_meta($post_id, $post) {
    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save Song Details
    if (isset($_POST['mf_song_details_nonce_field']) && wp_verify_nonce($_POST['mf_song_details_nonce_field'], 'mf_song_details_nonce')) {
        if (isset($_POST['mf_author'])) {
            update_post_meta($post_id, 'author', sanitize_text_field($_POST['mf_author']));
        }
    }

    // Save Personnel
    if (isset($_POST['mf_song_personnel_nonce_field']) && wp_verify_nonce($_POST['mf_song_personnel_nonce_field'], 'mf_song_personnel_nonce')) {
        if (isset($_POST['personnel_name']) && isset($_POST['personnel_contribution'])) {
            $names = $_POST['personnel_name'];
            $contributions = $_POST['personnel_contribution'];
            $personnel = array();

            for ($i = 0; $i < count($names); $i++) {
                $name = sanitize_text_field($names[$i]);
                $contribution = sanitize_text_field($contributions[$i]);

                // Only add if both name and contribution exist
                if (!empty($name) || !empty($contribution)) {
                    $personnel[] = array(
                        'name' => $name,
                        'contribution' => $contribution
                    );
                }
            }

            if (!empty($personnel)) {
                update_post_meta($post_id, 'personnel', wp_json_encode($personnel));
            } else {
                delete_post_meta($post_id, 'personnel');
            }
        } else {
            delete_post_meta($post_id, 'personnel');
        }
    }

    // Save Credits
    if (isset($_POST['mf_song_credits_nonce_field']) && wp_verify_nonce($_POST['mf_song_credits_nonce_field'], 'mf_song_credits_nonce')) {
        if (isset($_POST['mf_credits'])) {
            update_post_meta($post_id, 'credits', sanitize_text_field($_POST['mf_credits']));
        }
    }

    // Save Bandcamp Settings
    if (isset($_POST['mf_song_bandcamp_nonce_field']) && wp_verify_nonce($_POST['mf_song_bandcamp_nonce_field'], 'mf_song_bandcamp_nonce')) {
        if (isset($_POST['mf_bandcamp_embed_code'])) {
            update_post_meta($post_id, 'bandcamp_embed_code', wp_kses_post($_POST['mf_bandcamp_embed_code']));
        }
        if (isset($_POST['mf_bandcamp_album_id'])) {
            update_post_meta($post_id, 'bandcamp_album_id', sanitize_text_field($_POST['mf_bandcamp_album_id']));
        }
        if (isset($_POST['mf_bandcamp_track_id'])) {
            update_post_meta($post_id, 'bandcamp_track_id', sanitize_text_field($_POST['mf_bandcamp_track_id']));
        }
    }

    // Save SoundCloud Settings
    if (isset($_POST['mf_song_soundcloud_nonce_field']) && wp_verify_nonce($_POST['mf_song_soundcloud_nonce_field'], 'mf_song_soundcloud_nonce')) {
        if (isset($_POST['mf_soundcloud_id'])) {
            update_post_meta($post_id, 'soundcloud_id', sanitize_text_field($_POST['mf_soundcloud_id']));
        }
    }

    // Save Lyrics
    if (isset($_POST['mf_song_lyrics_nonce_field']) && wp_verify_nonce($_POST['mf_song_lyrics_nonce_field'], 'mf_song_lyrics_nonce')) {
        if (isset($_POST['mf_lyrics'])) {
            update_post_meta($post_id, 'lyrics', wp_kses_post($_POST['mf_lyrics']));
        }
    }
}

/**
 * Enqueue Admin Assets for Song Meta Boxes
 */
add_action('admin_enqueue_scripts', 'mf_enqueue_song_admin_assets');

function mf_enqueue_song_admin_assets($hook) {
    // Only enqueue on post edit screens
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    global $post_type;
    if ('song' === $post_type) {
        wp_enqueue_script(
            'mf-admin-meta-boxes',
            get_template_directory_uri() . '/js/admin-meta-boxes.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
