<?php
/**
 * Album block-editor UI behavior.
 *
 * @package Mysterious_Face
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
