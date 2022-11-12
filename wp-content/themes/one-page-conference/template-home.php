<?php

// Template Name:Home
get_header();
?>

<?php 
get_template_part( 'template-parts/home', 'section' );
?>

<?php 

if ( get_theme_mod( 'hide_show_blog_list_on_home', true ) ) {
    ?>

<?php 
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => '3',
    );
    $query = new WP_Query( $args );
    ?>

<?php 
    
    if ( $query->have_posts() ) {
        ?>

    <section class="home-section blog-layout-1" id="blog">
        <div class="container">
            <div class="main-title">
                <h2 class="title title-1">
                    <?php 
        esc_html_e( "Blog", 'one-page-conference' );
        ?>
                </h2>
            </div>
   
            <div class="grid-view blog-list-block">

                 <?php 
        /* Start the Loop */
        ?>
                 <?php 
        while ( $query->have_posts() ) {
            $query->the_post();
            ?>
                    <?php 
            get_template_part( 'template-parts/content' );
            ?>
                 <?php 
        }
        ?>

            </div>
        </div>

    </section>

<?php 
    }
    
    ?>

<?php 
    wp_reset_postdata();
    ?>

<?php 
}

?>



<!-- static code -->
<?php 
?>
<!-- static code -->




<?php 
get_footer();