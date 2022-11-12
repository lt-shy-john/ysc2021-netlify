<section class="home-section organisers-profile" id="organisers-profile">
		
		<div class="organiser-holder">
        <?php
            $args =[

                'posts_per_page' => '-1',
                'post_type'     => 'wep_organizer',
                'post_status'   => 'publish',
                'order'         => 'DESC'
            ];
            query_posts($args);
            $counter = 0;
            if (have_posts()) {
                while (have_posts()) : the_post();
                    $custom = get_post_custom();
                    ?>
			<div class="organiser-block">
				<div class="img-holder">
                <?php
                if (has_post_thumbnail()) {
                    $org_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');
                ?>
					<img src="<?php echo $org_image[0]; ?>" alt="<?php the_title(); ?>">
                <?php } ?>
				</div>
				<h5 class="organiser-title"><?php the_title(); ?></h5>

                <div class="social-icons">
                    <ul class="list-inline">
                        <?php if( $custom['facebook_link'][0] ) { ?>
                            <li class="facebook"><a href="<?php echo esc_url( $custom['facebook_link'][0] ); ?>" target="_blank"><i class="icon-facebook"></i></a></li>
                        <?php } ?>
                        <?php if( $custom['twitter_link'][0] ) { ?>
                            <li class="twitter"><a href="<?php echo esc_url( $custom['twitter_link'][0] ); ?>" target="_blank"><i class="icon-twitter"></i></a></li>
                        <?php } ?>
                        <?php if( $custom['instagram_link'][0] ) { ?>
                            <li class="instagram"><a href="<?php echo esc_url( $custom['instagram_link'][0] ); ?>" target="_blank"><i class="icon-instagram"></i></a></li>
                        <?php } ?>
                        <?php if( $custom['linkedIn_link'][0] ) { ?>
                            <li class="linkedin"><a href="<?php echo esc_url( $custom['linkedIn_link'][0] ); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                        <?php } ?>
                    </ul>
                </div>

				<?php the_content(); ?>
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
</section>