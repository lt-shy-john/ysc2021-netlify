<?php

if( get_theme_mod( 'cta_with_image_display_option', true ) ) :
   
    $title = get_theme_mod( 'cta_with_image_title' );
    $image = get_theme_mod( 'cta_with_image_image' );
    $text = get_theme_mod( 'cta_with_image_text' );
    $button_label = get_theme_mod( 'cta_with_image_button_label' );
    $link = get_theme_mod( 'cta_with_image_link' );

?>


	<div class="cta-image-1">
		<div class="container">
			<div class="row align-items-center">
				
				<div class="col-sm-6">
					<h3 class="title title-1"><?php echo esc_html( $title ); ?></h3>
					<p><?php echo wp_kses_post( $text ); ?></p>
					<?php if($button_label && $link){ ?>
					<a class="btn btn-secondary" href="<?php echo esc_url( $link ); ?>" target="_blank"><?php echo esc_html( $button_label ); ?></a>
					<?php } ?>
				</div>
				<div class="col-sm-6">
					<?php if( $image ) { ?>
						<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>">
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

<?php endif;