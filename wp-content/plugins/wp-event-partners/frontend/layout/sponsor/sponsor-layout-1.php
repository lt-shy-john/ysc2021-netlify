<section class="sponsors-layout-1" id="sponsor">
       
        <?php
		$hasposts = get_posts( array( 'post_type' => 'sponsor', 'numberposts' => -1 ) );

		$default = array();

	    foreach ($hasposts as $carousel) {
	        array_push( $default, $carousel->post_name );
	    }

		if( !empty ( $hasposts ) ) {
			$choose_sponsors = get_theme_mod( 'choose_sponsors', $default );
			if($choose_sponsors){
				foreach ($choose_sponsors as $choose_sponsor) {

					$sponsorposts = get_page_by_path($choose_sponsor, '', 'sponsor');
					if ($sponsorposts) {
						$sponserTitle = $sponsorposts->post_title;
						$custom = get_post_custom($sponsorposts->ID);
						if(isset($custom['txt_image_url'])){
							$photo_url_serializeD = $custom['txt_image_url'][0];
							
							if(is_serialized($photo_url_serializeD)){
								$image = unserialize($photo_url_serializeD);
								$imageName = unserialize($custom["image_name"][0]);
								$imageLink = unserialize($custom["image_link"][0]);
							} else {
								$image = $custom['txt_image_url'];
								$imageName = $custom["image_name"][0];
								$imageLink = $custom["image_link"][0];
							}
					
						}
				
						$imageLink = unserialize($custom["image_link"][0]);
						if ( $image[0] ) {
							?>

							<span class="sponsor-subtitle"><?php echo $sponserTitle; ?></span>

							<div class="row justify-content-center">
								<?php for ($i = 0; $i < count(array_filter($image)); $i++) { ?>
									<div class="col-lg-3 col-sm-4 col-6">
										<div class="img-holder">
											<?php if( is_array( $imageLink ) && $imageLink[$i] ) { ?>
												<a target="_blank" href="<?php echo $imageLink[$i]; ?>">
													<?php if( $image[$i] ) { ?>
														<img src="<?php echo esc_url($image[$i]); ?>" alt="<?php the_title(); ?>">
													<?php } ?>
												</a>
											<?php } else { 
												if( $image[$i] ) { ?>
													<img src="<?php echo esc_url($image[$i]); ?>" alt="<?php the_title(); ?>">
												<?php }
											} ?>

										</div>
									</div>
								<?php } ?>
					</div>

						<?php }
					} else {
						echo "No data selected";
					}
				}
			} else {
				echo "No data selected";
			}
		}else {
			echo "No data found";
		}
		?>
</section>
