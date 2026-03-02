<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Mysterious_Face
 */

get_header();

$classes = 'layout layout--single';
?>

    <div class="<?php print $classes ?>">

        <section id="section_content" class="section section-content">
            <div class="section-content--inner section--inner">
                <div id="zone-content-wrapper" class="zone-wrapper zone-content-wrapper clearfix">
                    <div id="zone-content" class="zone zone-content clearfix">
                        <div class="region region-content">

            <?php
            while ( have_posts() ) :
                the_post();

                get_template_part( 'template-parts/content', get_post_type() );

                the_post_navigation(
                    array(
                        'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'mysteriousface' ) . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'mysteriousface' ) . '</span> <span class="nav-title">%title</span>',
                    )
                );

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

<?php
get_footer();
