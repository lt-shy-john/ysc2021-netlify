<div class="wep-counter-wrapper home-section">
	<div class="container">
		<div class="wep-counter-inside">
		<div class="row">



			<div class="col-lg-6 offset-lg-3 col-sm-12">
			<?php
                $heading_for_counter = get_theme_mod('heading_for_counter');
                if($heading_for_counter){
            ?>
				<h2><?php echo $heading_for_counter; ?></h2>
			<?php } ?>
			<?php
                $content_for_counter = get_theme_mod('content_for_counter');
                if($content_for_counter){
            ?>
				<p><?php echo $content_for_counter; ?></p>
			<?php } ?>

			
			
			</div>


			
			<div class="col-lg-12 wep-counter-wrapper-holder">
				<div class="wep-counter">
					<?php
					$counters = get_theme_mod( 'counter_display_option', '' );
					if ( ! empty( $counters ) && is_array( $counters ) ) :
						foreach ( $counters as $value ) {
							$count_num = $value['counter_number'];
							$count_text = $value['counter_text'];
							?>
							<div class="wep-counter-number-text">
								<div class="wep-counter-number"><?php echo $count_num; ?></div>
								<div class="wep-counter-text"><?php echo $count_text; ?></div>
							</div>
						<?php } endif; ?>
					</div>

					<?php
                $counter_button_label = get_theme_mod('counter_button_label');
				$counter_link = get_theme_mod('counter_link');
                if($counter_button_label && $counter_link){
            ?>
			
				<a href="<?php echo $counter_link; ?>" class="btn btn-secondary"><?php echo $                $counter_button_label = get_theme_mod('counter_button_label');
; ?></a>
			<?php } ?>


				</div>





</div>
			</div>
		</div>
	</div>
