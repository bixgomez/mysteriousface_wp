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

<div class="wrapper">
	<div class="wrapper-inner">
		<div class="container-site">

			<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'mysteriousface-theme' ); ?></a>

			<?php if ( function_exists( 'block_template_part' ) ) : ?>
				<?php block_template_part( 'header' ); ?>
			<?php endif; ?>

			<?php if ( is_singular() && has_post_thumbnail() ) : ?>
				<section class="featured-image">
					<?php the_post_thumbnail( 'full' ); ?>
				</section>
			<?php endif; ?>

			<main id="primary" class="site-main">
