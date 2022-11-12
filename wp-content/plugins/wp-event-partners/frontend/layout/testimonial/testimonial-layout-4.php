<section class="testimonials-layout-4" id="testimonials">
		<div class="testimonials-holder owl-carousel owl-theme">
			<?php
			$args =[
				'post_per_page' => '',
				'post_type'     => 'testimonial',
				'post_status'   => 'publish',
				'order'         => 'DESC'
			];
			query_posts($args);
			$counter = 0;
			if (have_posts()) {
			while( have_posts()) : the_post();
				$custom = get_post_custom();
				?>
				<div class="item">
					<div class="img-title">
						<?php if (has_post_thumbnail()){ ?>
							<div class="img-holder">
								<img src="<?php echo the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
							</div>
						<?php } ?>
						<div class="testimonial-info">
							<h6><?php the_title(); ?></h6>
							<em class="position"><?php echo $custom['position'][0]; ?><?php if ($custom['position'][0] AND $custom['company'][0]){ echo ',';}?> <?php echo $custom['company'][0]; ?></em>
                            <ul class="social-media">
                                <?php if ($custom['facebook_link'][0]){ ?>
                                    <li><a href="<?php echo $custom['facebook_link'][0]; ?>"><span class="fa fa-facebook" aria-hidden="true"></span></a></li>
                                <?php } ?>
                                <?php if ($custom['instagram_link'][0]){ ?>
                                    <li><a href="<?php echo $custom['instagram_link'][0]; ?>"><span class="fa fa-instagram" aria-hidden="true"></span></a></li>
                                <?php } ?>
                                <?php if ($custom['twitter_link'][0]){ ?>
                                    <li><a href="<?php echo $custom['twitter_link'][0]; ?>"><span class="fa fa-twitter" aria-hidden="true"></span></a></li>
                                <?php } ?>
                                <?php if ($custom['linkedIn_link'][0]){ ?>
                                    <li><a href="<?php echo $custom['linkedIn_link'][0]; ?>"><span class="fa fa-linkedin" aria-hidden="true"></span></a></li>
                                <?php } ?>
                            </ul>
						</div>
					</div>
					<!-- <span class="fas fa-quote-left"></span> -->
					<?php the_content();?>
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

