<?php
/**
 * Legacy template routing.
 *
 * @package Mysterious_Face
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Keep custom PHP single template for legacy Song rendering.
 *
 * Block templates are now enabled for this theme, but this post type still
 * relies on custom PHP output.
 *
 * @param string $template Resolved template path.
 * @return string
 */
function mysteriousface_theme_legacy_templates( $template ) {
	if ( is_singular( 'song' ) ) {
		$legacy_template = get_theme_file_path( '/single-song.php' );
		if ( is_file( $legacy_template ) ) {
			return $legacy_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'mysteriousface_theme_legacy_templates', 99 );
