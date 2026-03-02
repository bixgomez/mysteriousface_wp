<?php
/**
 * Menu and Menu Item Custom Fields
 *
 * Replaces ACF functionality for navigation menu customizations
 *
 * @package Mysterious_Face
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom fields to menu items
 */
add_action('wp_nav_menu_item_custom_fields', 'mf_menu_item_custom_fields', 10, 4);

function mf_menu_item_custom_fields($item_id, $item, $depth, $args) {
    $menu_item_class = get_post_meta($item_id, '_menu_item_class', true);
    $menu_item_image = get_post_meta($item_id, '_menu_item_image', true);
    ?>
    <p class="field-menu-item-class description description-wide">
        <label for="edit-menu-item-class-<?php echo $item_id; ?>">
            Menu Item Class<br>
            <input type="text"
                   id="edit-menu-item-class-<?php echo $item_id; ?>"
                   class="widefat edit-menu-item-class"
                   name="menu_item_class[<?php echo $item_id; ?>]"
                   value="<?php echo esc_attr($menu_item_class); ?>" />
            <span class="description">Custom CSS class for this menu item</span>
        </label>
    </p>
    <p class="field-menu-item-image description description-wide">
        <label for="edit-menu-item-image-<?php echo $item_id; ?>">
            Menu Item Image URL<br>
            <input type="text"
                   id="edit-menu-item-image-<?php echo $item_id; ?>"
                   class="widefat edit-menu-item-image"
                   name="menu_item_image[<?php echo $item_id; ?>]"
                   value="<?php echo esc_url($menu_item_image); ?>" />
            <span class="description">Image URL for icon menus or image-based navigation</span>
        </label>
    </p>
    <?php
}

/**
 * Save menu item custom fields
 */
add_action('wp_update_nav_menu_item', 'mf_save_menu_item_custom_fields', 10, 2);

function mf_save_menu_item_custom_fields($menu_id, $menu_item_db_id) {
    // Check user permissions
    if (!current_user_can('edit_theme_options')) {
        return;
    }

    // Save menu item class
    if (isset($_POST['menu_item_class'][$menu_item_db_id])) {
        $class = sanitize_text_field($_POST['menu_item_class'][$menu_item_db_id]);
        if (!empty($class)) {
            update_post_meta($menu_item_db_id, '_menu_item_class', $class);
        } else {
            delete_post_meta($menu_item_db_id, '_menu_item_class');
        }
    }

    // Save menu item image
    if (isset($_POST['menu_item_image'][$menu_item_db_id])) {
        $image = esc_url_raw($_POST['menu_item_image'][$menu_item_db_id]);
        if (!empty($image)) {
            update_post_meta($menu_item_db_id, '_menu_item_image', $image);
        } else {
            delete_post_meta($menu_item_db_id, '_menu_item_image');
        }
    }
}
