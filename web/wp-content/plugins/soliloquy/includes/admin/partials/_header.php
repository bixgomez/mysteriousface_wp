<?php
/**
 * Header Partial.
 *
 * @package Soliloquy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$base = Soliloquy::get_instance(); ?>

<div id="soliloquy-header">

	<div id="soliloquy-logo">
		<a href="https://soliloquywp.com/?utm_source=proplugin&utm_medium=logo&utm_campaign=proplugin" aria-label="<?php esc_html_e( 'Soliloquy home page', 'soliloquy' ); ?>" target="_blank" rel="noopener noreferrer">
			<img src="<?php echo plugins_url( 'assets/images/logo-color.png', $base->file ); ?>" alt="<?php esc_html_e( 'Soliloquy', 'soliloquy' ); ?>">
		</a>
	</div>

</div>
