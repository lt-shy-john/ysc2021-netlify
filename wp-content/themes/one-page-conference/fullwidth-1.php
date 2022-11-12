<?php
/**
 * Template Name: Content Width
 * Template Post Type: post
 * The template used for displaying fullwidth page content in fullwidth.php
 * @package One_Page_Conference
 */
get_header(); ?>
    <div class="inside-page content-width content-area">
            <div id="main-content">
                <section class="page-section">
                    <div class="detail-content">

                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php get_template_part( 'template-parts/content', 'single' ); ?>
                        <?php endwhile; // End of the loop. ?>
                        <?php comments_template(); ?>

                    </div><!-- /.end of deatil-content -->
                </section> <!-- /.end of section -->
            </div>
    </div>
<?php get_footer(); ?>