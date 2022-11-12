<?php
/**
 * Register Schedule Settings
 *
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


//register setting
add_action('admin_init','event_register_data_init');
function event_register_data_init()
{
    register_setting( 'event_timezone', 'timezone_setting' );
    register_setting( 'event_scheduleManagement', 'session_date' );
    register_setting( 'event_scheduleManagement', 'session_time_start' );
    register_setting( 'event_scheduleManagement', 'session_time_end' );
    register_setting( 'event_scheduleManagement', 'selectSession' );
    register_setting( 'event_scheduleManagement', 'selectRoom' );

    register_setting( 'event_information', 'event_title' );
    register_setting( 'event_information', 'event_info' );
    register_setting( 'event_information', 'event_date_start' );
    register_setting( 'event_information', 'event_date_end' );
    register_setting( 'event_information', 'eventbanner_date_format' );
    register_setting( 'event_information', 'event_time_start' );
    register_setting( 'event_information', 'event_time_end' );
    register_setting( 'event_information', 'eventbanner_time_format' );
    register_setting( 'event_information', 'event_venue' );
    register_setting( 'event_information', 'event_location' );
    register_setting( 'event_information', 'event_venue_image' );
    register_setting( 'event_information', 'event_venue_description' );
    register_setting( 'event_information', 'event_google_map' );
    register_setting( 'event_information', 'event_call_btn_name' );
    register_setting( 'event_information', 'event_call_for_paper' );
    register_setting( 'event_information', 'event_registration_btn_name' );
    register_setting( 'event_information', 'event_registration' );

    register_setting( 'event_information', 'map_lng' );
    register_setting( 'event_information', 'map_lat' );
}
