<?php 
get_header();
global $post;
$id = get_the_ID();
$session = get_post($id);
$speakersSession = $session->post_name;
while ( have_posts() ) : the_post();
    $custom = get_post_custom(get_the_ID());
    ?>
    <div class="inside-page content-area speaker-detail-block">
        <div class="row">
            <div class="col-md-6" id="main-content">
                <section class="speaker-detail">
                    <div class="detail-content">
                        <div class="speaker-info">
                            <div class="img-holder">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <figure class="feature-image">
                                        <?php the_post_thumbnail( 'medium' ); ?>
                                    </figure>      
                                <?php endif; ?>    
                            </div>
                            <div class="info">
                                <h3 class="title"><?php the_title(); ?></h3>
                                <div class="position">
                                    <ul>
                                        <?php if($custom['position'][0]){ ?>
                                            <li><?php  echo $custom['position'][0]?></li>
                                        <?php }?>
                                        <?php if($custom['company'][0]){ ?>
                                            <li><?php  echo $custom['company'][0]?></li>
                                        <?php }?>
                                        <?php if($custom['website_link'][0]){ ?>
                                            <li><?php  echo $custom['website_link'][0]?></li>
                                        <?php }?>
                                    </ul>
                                </div>
                                <div class="speaker-social-links">
                                <ul>
                                    <?php if($custom['facebook_link'][0]){ ?>
                                        <li><a target="_blank" href="<?php echo $custom['facebook_link'][0]?>"><span class="fa fa-facebook" aria-hidden="true"></span></a></li>
                                    <?php }?>
                                    <?php if( $custom['instagram_link'][0] ){ ?>
                                        <li><a target="_blank" href="<?php echo $custom['instagram_link'][0]?>"><span class="fa fa-instagram" aria-hidden="true"></span></a></li>
                                    <?php }?>
                                    <?php if($custom['twitter_link'][0]){ ?>
                                        <li><a target="_blank" href="<?php echo $custom['twitter_link'][0]?>"><span class="fa fa-twitter" aria-hidden="true"></span></a></li>
                                    <?php }?>
                                    <?php if($custom['linkedIn_link'][0]){ ?>
                                        <li><a target="_blank" href="<?php echo $custom['linkedIn_link'][0]?>"><span class="fa fa-linkedin" aria-hidden="true"></span></a></li>
                                    <?php }?>
                                </ul>
                
                                </div>
                            </div>
                        </div>
                        
                        <?php the_content(); ?>
                    </div><!-- /.end of deatil-content -->
                </section> <!-- /.end of section -->
            </div>
            
            <div class="col-md-6">
                <div class="speaker-session">
                    <h1 class="title">Session</h1>
                    <?php
                    $args = array(
                        'posts_per_page' => '6',
                        'post_type'      => 'session',
                        'post_status'    => 'publish',
                        'order'          => 'DESC',
                        'meta_query'    => array(
                            array(
                                'key'       => 'speakers',
                                'value'     => $speakersSession,
                                'compare' => 'LIKE'
                            )
                        )
                    );

                    $the_query = new WP_Query( $args );

                    if ( $the_query->have_posts() ) {
                        echo '<ul>';
                        while ( $the_query->have_posts() ) : $the_query->the_post();
                            ?>    
                            <li>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <p><?php the_excerpt(); ?></p>
                            </li>

                            <?php 
                        endwhile;
                        echo '</ul>';
                    } else {
                    // no posts found
                    }
                    /* Restore original Post Data */
                    wp_reset_postdata();

                    ?>

                </div>
            </div>
        </div>
    </div>
    <?php 
    endwhile; // End of the loop.
    get_footer(); ?>
