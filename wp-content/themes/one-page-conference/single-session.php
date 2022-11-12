<?php 
get_header();
global $post;
$id = get_the_ID();
while ( have_posts() ) : the_post();
	$custom = get_post_custom(get_the_ID());
	?>
	<div class="inside-page content-area">
		<div class="schedule-detail" id="main-content">
			<div class="main-schedule-detail">
				<div class="schedule-layout">
					<div class="schedule-holder">
						<div class="session-block">
							<div class="session-content-wrapper">
								<h1 class="title"><?php the_title(); ?></h1>
								<div class="session-speakers">
									<?php
									$speakers = unserialize($custom["speakers"][0]);
									if($speakers){
										sort( $speakers );
										foreach($speakers as $speaker){
											$speakerPost = get_page_by_path($speaker, '', 'speaker');
											$speakerCustom = get_post_custom($speakerPost->ID);
											$speakerTitle = $speakerPost->post_title;
											$speakerPosition = $speakerCustom['position'][0];
											$speakerCompany = $speakerCustom['company'][0];
											$speakerImage = wp_get_attachment_image_src(get_post_thumbnail_id($speakerPost->ID));
											?>
											<div class="speakers">
												<div class="img-holder">
													<img src="<?php echo esc_url($speakerImage[0]);?>">
												</div>
												<div class="text">
													<span class="name"> <strong><a href="<?php the_permalink($speakerPost->ID);?>"><?php echo $speakerTitle; ?></a></strong></span>
													<span class="prof-comp"><?php if(isset($speakerPosition)) echo esc_html($speakerPosition);?> <?php if (!isset($speakerPosition) || ($speakerCompany)) echo esc_html('/'); ?><?php if(isset($speakerCompany)) echo esc_html($speakerCompany);?></span>
												</div>
											</div>
										<?php } } ?>
										
									</div>
									<?php the_content(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<?php 
    endwhile; // End of the loop.
    get_footer();
    ?>