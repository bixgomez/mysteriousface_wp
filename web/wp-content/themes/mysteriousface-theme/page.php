<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mysterious_Face
 */

get_header();

$classes = 'layout layout--page';
?>

    <div class="<?php print $classes ?>">

        <section class="heading">
            <h1 class="node-title"><?php the_title(); ?></h1>
        </section>

        <section class="body">
          <?php the_content();

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
            comments_template();
            endif;
          ?>
        </section>

    </div>

<?php
get_footer();
