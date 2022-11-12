<?php
    if( get_theme_mod( 'venue_display_option', true ) ) :

        if (class_exists('WepPlugin')) {

            $selectaboutLayout = get_theme_mod('one_page_conference_venue_layout','1');

            if (class_exists('WepVenueAddonPlugin')) {
                if ($selectaboutLayout == '1') {
                    do_action('WEP_venue_add_layouts');
                } else {
                    do_action('WEP_venue_addon_customize_layouts');
                }
            } else {
                do_action('WEP_venue_add_layouts');
            }


        }

    endif;