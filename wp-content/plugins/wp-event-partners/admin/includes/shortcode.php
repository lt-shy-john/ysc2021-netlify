<?php
/**
 * Shortcode
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//HOOKS
add_action('init','wep_event_management_shortcode_init');

    /**********************************/
    /*			FUNCTIONS FOR SHORTCODE
    ***********************************/
function wep_event_management_shortcode_init()
{
    add_shortcode('wep_schedule', 'wep_event_management_shortcode');
    add_shortcode('wep_about_us', 'wep_about_us_shortcode');
    add_shortcode('wep_speaker', 'wep_speaker_shortcode');
    add_shortcode('wep_sponsor', 'wep_sponsor_shortcode');
    add_shortcode('wep_testimonial', 'wep_testimonial_shortcode');
    add_shortcode('wep_venue', 'wep_venue_shortcode');
}

function wep_about_us_shortcode($atts){
    ob_start();
    $atts = shortcode_atts(array(
        'layout' => '1'
    ), $atts);
    $aboutLayout = $atts['layout'];

    set_query_var( 'aboutShortcodeLayout', $aboutLayout );

    if ($aboutLayout == 1){

        include WEP_EVENT_DIR .'frontend/layout/about-us/about-us-layout-1.php';

        } else {

        include WEP_EVENT_DIR .'frontend/layout/about-us/about-us-extra-layout.php';

    }
    return ob_get_clean();
}

function wep_speaker_shortcode($atts){
    ob_start();
    $atts = shortcode_atts(array(
        'layout' => '1'
    ), $atts);
    $speakerLayout = $atts['layout'];

    set_query_var( 'speakerShortcodeLayout', $speakerLayout );

    if ($speakerLayout > 0 AND $speakerLayout < 5){
        include WEP_EVENT_DIR .'frontend/layout/speaker/speaker-layout-'.$speakerLayout.'.php';
    }else {
        include WEP_EVENT_DIR .'frontend/layout/speaker/speaker-layout-1.php';
    }

    return ob_get_clean();
}

function wep_sponsor_shortcode($atts){
    ob_start();
    $atts = shortcode_atts(array(
        'layout' => '1'
    ), $atts);
    $sponsorLayout = $atts['layout'];

    set_query_var( 'sponsorShortcodeLayout', $sponsorLayout );

    if ($sponsorLayout > 0 AND $sponsorLayout < 3 ){
        include WEP_EVENT_DIR .'frontend/layout/sponsor/sponsor-layout-'.$sponsorLayout.'.php';
    } else {
        include WEP_EVENT_DIR .'frontend/layout/sponsor/sponsor-layout-1.php';

    }
    return ob_get_clean();
}

function wep_testimonial_shortcode($atts){
    ob_start();
    $atts = shortcode_atts(array(
        'layout' => '1'
    ), $atts);
    $testimonialLayout = $atts['layout'];

    set_query_var( 'testimonialShortcodeLayout', $testimonialLayout );

    if ($testimonialLayout == 1){

        include WEP_EVENT_DIR .'frontend/layout/testimonial/testimonial-layout-1.php';

    } else {

        include WEP_EVENT_DIR .'frontend/layout/testimonial/testimonial-extra-layout.php';

    }

    return ob_get_clean();
}

function wep_venue_shortcode($atts){
    ob_start();
    $atts = shortcode_atts(array(
        'layout' => '1'
    ), $atts);
    $venueLayout = $atts['layout'];

    set_query_var( 'venueShortcodeLayout', $venueLayout );

    if ($venueLayout == 1){

        include WEP_EVENT_DIR .'frontend/layout/venue/venue-layout-1.php';

    } else {

        include WEP_EVENT_DIR .'frontend/layout/venue/venue-extra-layout.php';

    }

    return ob_get_clean();
}

function wep_event_management_shortcode($atts){
    ob_start();
    
    $atts = shortcode_atts(array(
        'layout' => '1'
    ), $atts);
    $scheduleShortcodeLayout = $atts['layout'];

    set_query_var( 'scheduleShortcodeLayout', $scheduleShortcodeLayout );

    if ($scheduleShortcodeLayout == 1){

        include WEP_EVENT_DIR .'frontend/layout/schedule/schedule-layout-1.php';

    } else {

        include WEP_EVENT_DIR .'frontend/layout/schedule/schedule-extra-layout.php';

    }
    
   
    return ob_get_clean();
    
}