<?php
/**
 * The template for displaying all single posts.
 *
 * @package One_Page_Conference
 */

get_header(); ?>

<div class="inside-page content-area">
    <div class="row">

        <div class="col-sm-8" id="main-content">
            <section class="page-section">
                <div class="detail-content">

                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'template-parts/content', 'single' ); ?>
                    <?php endwhile; // End of the loop. ?>
                    <?php comments_template(); ?>

                </div><!-- /.end of deatil-content -->
            </section> <!-- /.end of section -->
        </div>

        <div class="col-sm-4"><?php get_sidebar(); ?></div>

    </div>
</div>

<?php get_footer();