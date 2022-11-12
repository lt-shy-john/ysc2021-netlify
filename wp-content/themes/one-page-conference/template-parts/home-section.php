<?php

get_template_part( 'template-parts/home-sections/banner', '' );
if ( !class_exists( 'WepPlugin' ) ) {
    $sections = get_theme_mod( 'one_page_conference_sort_homepage', array( 'about-us', 'counter' ) );
}

if ( class_exists( 'WepPlugin' ) ) {
    $sections = get_theme_mod( 'one_page_conference_sort_homepage', array(
        'about-us',
        'counter',
        'testimonials',
        'sponsors',
        'speakers',
        'schedules',
        'venue',
        'organizers'
    ) );
    
    if ( class_exists( 'Wp_Event_Tickets' ) ) {
        $sections = get_theme_mod( 'one_page_conference_sort_homepage', array(
            'about-us',
            'counter',
            'testimonials',
            'sponsors',
            'speakers',
            'schedules',
            'venue',
            'organizers'
        ) );
    } else {
    }

}

if ( !empty($sections) && is_array( $sections ) ) {
    foreach ( $sections as $section ) {
        get_template_part( 'template-parts/home-sections/' . $section, $section );
    }
}
add_action( 'admin_init', 'check_some_other_plugin' );