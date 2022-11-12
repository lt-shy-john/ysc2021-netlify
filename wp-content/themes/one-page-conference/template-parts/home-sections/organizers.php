<?php

    if( get_theme_mod( 'organizers_display_option', true ) && class_exists('WepPlugin') ) { ?>

    	<div class="organizer-wrapper spacer">
	    	<div class="container">
	    		<h2 class="title title-1"><?php echo esc_html( get_theme_mod( 'heading_for_organizers' ) ); ?></h2>
	        	<?php do_action('WEP_organizers_add_layouts'); ?>
	        </div>
	    </div>

<?php

    }