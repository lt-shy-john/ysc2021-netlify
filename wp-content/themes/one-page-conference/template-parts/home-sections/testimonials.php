<?php if( class_exists('WepPlugin') && get_theme_mod( 'testimonial_display_option', true ) ) { ?>
<section class="testimonials-wrapper home-section">
        <div class="container">
            <?php $testimonialHeading = get_theme_mod( 'heading_for_testimonial', '' ); ?>

	        <?php if($testimonialHeading) { ?>
	            <h2 class="title title-1"><?php echo esc_html($testimonialHeading); ?></h2>
	        <?php } ?>

        	<?php do_action('WEP_testimonial_add_layouts'); ?>
        </div>
</section>
<?php }