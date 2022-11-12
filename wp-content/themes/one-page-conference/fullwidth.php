<?php
/**
 * Template Name: Fullwidth
 * Template Post Type: page
 * The template used for displaying fullwidth page content in fullwidth.php
 *
 * @package One_Page_Conference
 */
get_header();
?>
<div id="primary" class="inside-page full-width content-area">
            <div id="main" class="site-main">

                <?php
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'template-parts/content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>

            </div><!-- #main -->

    </div><!-- #primary -->

<?php get_footer();