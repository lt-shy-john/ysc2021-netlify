<section class="speaker-layout-1" id="speakers">
        <div class="speaker-block">
        <div class="owl-carousel" id="speakers-1">
            
            <?php
            $args =[

                'posts_per_page' => '-1',
                'post_type'     => 'speaker',
                'post_status'   => 'publish',
                'order'         => 'DESC'
            ];
            query_posts($args);
            $counter = 0;
            if (have_posts()) {
                while (have_posts()) : the_post();
                    $custom = get_post_custom();
                    ?>
                    <div class="speaker-holder">
                        <?php
                        if (has_post_thumbnail()) {
                            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');
                            ?>
                            <div class="img-holder"><a href= "<?php the_permalink(); ?>"><img src="<?php echo esc_url($image[0]); ?>" alt="<?php the_title(); ?>"></a></div>
                        <?php } ?>
                        <div class="speaker-content">
                            <h4 class="speaker-title">
                                <a href= "<?php the_permalink(); ?>"><?php the_title(); ?> </a>
                            </h4>
                            <div class="speaker-desc"><?php echo esc_html($custom['position'][0]); ?><?php if (!isset($custom['position'][0]) || ($custom['company'][0])) ?><strong><?php echo esc_html($custom['company'][0]); ?></strong></div>
                        </div>
                    </div>
                    <?php

                    $counter++;
                endwhile;
            }else{
                esc_html_e('No data found');
            }
            wp_reset_query();
            flush();
            ?>
    </div>
    </div>
</section>
