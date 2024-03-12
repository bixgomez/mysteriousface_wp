<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mysterious_Face
 */

get_header('home');

$classes = 'layout layout--home';
?>

    <div class="<?php print $classes ?>">

        <section class="heading">
            <h1 class="node-title"><?php the_title(); ?></h1>
        </section>

        <section class="body">
          <?php the_content(); ?>
        </section>

    </div>

<?php
get_footer('home');
