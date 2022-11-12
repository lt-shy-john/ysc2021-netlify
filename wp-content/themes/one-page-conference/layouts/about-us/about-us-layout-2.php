<?php

    $aboutHeading = get_theme_mod('heading_for_about_us');

    $aboutPage = get_page_by_path(get_theme_mod('about_us_pages'));

    if(!empty($aboutPage)):
        global $post;
        $post = get_post($aboutPage);
        setup_postdata( $post );
    ?>

    <section class="home-section about-layout-2" id="about">
        <div class="container">
            <div class="about-holder">
                <div class="row align-items-center">
                    <?php if(has_post_thumbnail()): ?>
                    <div class="col-lg-6">
                        <div class="img-holder">
                            <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-lg-6">
                        <div class="about-content">
                            <div class="main-title">
                                <?php if ( $aboutHeading ) { ?>
                                    <h2 class="title title-1"><?php echo esc_html( $aboutHeading ); ?></h2>
                                <?php } ?>
                            </div>
                            <p><?php the_excerpt(); ?></p>
                            <a class="btn btn-secondary" href="<?php the_permalink( $aboutPage );?>"><?php esc_html_e( 'Read More', 'one-page-conference' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
    wp_reset_postdata();
    endif;
?>
