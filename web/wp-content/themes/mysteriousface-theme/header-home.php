<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Mysterious_Face
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="wrapper">
    <div class="wrapper-inner">
        <nav class="container-site">

            <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'mysteriousface'); ?></a>

            <header class="site-header">
                <?php $blog_info = get_bloginfo('name'); ?>
                <div class="header-top">
                    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                    <a class="toggle" href="#" role="button"><span></span></a>
                </div>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-2',
                        'menu_id' => 'social-media-menu',
                    )
                );
                ?>
            </header>

            <nav id="site-navigation" class="main-navigation">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-1',
                        'menu_id' => 'primary-menu',
                    )
                );
                ?>
            </nav>

            <?php if (has_post_thumbnail($post->ID)): ?>
                <section class="featured-image">
                    <?php the_post_thumbnail('full') ?>
                </section>
            <?php endif; ?>

            <?php if (is_active_sidebar('home-page-slider')) : ?>
                <?php dynamic_sidebar('home-page-slider'); ?>
            <?php endif; ?>

            <main>