<?php
/**
 * The header for our theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mysterious_Face
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" id="wp-skip-link" href="#primary"><?php esc_html_e( 'Skip to content', 'mysteriousface-theme' ); ?></a>

<div class="wp-site-blocks">
	<div class="wp-block-group wrapper is-layout-flow wp-block-group-is-layout-flow">
		<div class="wp-block-group wrapper-inner is-layout-flow wp-block-group-is-layout-flow">
			<div class="wp-block-group container-site is-layout-flow wp-block-group-is-layout-flow">
				<?php if ( function_exists( 'do_blocks' ) ) : ?>
					<?php echo do_blocks( '<!-- wp:template-part {"slug":"header","area":"header","tagName":"header"} /-->' ); ?>
				<?php elseif ( function_exists( 'block_template_part' ) ) : ?>
					<?php block_template_part( 'header' ); ?>
				<?php endif; ?>
