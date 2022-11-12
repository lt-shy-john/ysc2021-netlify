<?php
/**
 * Class Menu - admin menues
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'WEP_Admin_Menu' ) ) :
class WEP_Admin_Menu {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_main_menus' ),	10 );
        add_action( 'admin_menu', array( $this, 'register_speaker_submenu' ),	10 );
        add_action( 'admin_menu', array( $this, 'register_session_submenu' ),	10 );
        add_action( 'admin_menu', array( $this, 'register_room_submenu' ),	10 );
        add_action( 'admin_menu', array( $this, 'register_manage_schedule' ),	10 );
        add_action( 'admin_menu', array( $this, 'register_sponsor_submenu' ),	10 );
        add_action( 'admin_menu', array( $this, 'register_organizer_submenu' ),	10 );
        add_action( 'admin_menu', array( $this, 'register_testimonial_submenu' ),	10 );
    }

    public function register_main_menus() {

        add_menu_page( 'WPEventPartners', 'WPEventPartners', 'manage_options', 'wp_event_partners', 'event_information', '','7' );

        add_submenu_page('wp_event_partners',
            'Event Information',
            __( 'Event Information', 'wp-event-partners' ),
            'manage_options',
            'wp_event_partners');

 }

    public function register_speaker_submenu() {

        add_submenu_page(
            'wp_event_partners',
            'Speaker',
            'Speakers',
            'manage_options',
            'edit.php?post_type=speaker'
        );

    }

    public function register_session_submenu() {

        add_submenu_page(
            'wp_event_partners',
            'Session',
            'Sessions',
            'manage_options',
            'edit.php?post_type=session'
        );

    }

    public function register_room_submenu() {

        add_submenu_page(
            'wp_event_partners',
            'Rooms',
            'Rooms/Tracks',
            'manage_options',
            'edit.php?post_type=room'
        );

    }

    public function register_manage_schedule(){
        add_submenu_page(
            'wp_event_partners',
            'ManageSchedules',
            'Manage Schedules',
            'manage_options',
            'schedule_management',
            'schedule_management_options'
        );

        add_submenu_page('edit.php?post_type=project','View Source of Fund','View Source of Fund','manage_options','view-sof','viewSofDetail');
    }

    public function register_sponsor_submenu() {

        add_submenu_page(
            'wp_event_partners',
            'Sponsor',
            'Sponsors',
            'manage_options',
            'edit.php?post_type=sponsor'
        );

    }
    public function register_organizer_submenu() {

        add_submenu_page(
            'wp_event_partners',
            'Organizer',
            'Organizers',
            'manage_options',
            'edit.php?post_type=wep_organizer'
        );

    }

    public function register_testimonial_submenu() {

        add_submenu_page(
            'wp_event_partners',
            'Testimonial',
            'Testimonials',
            'manage_options',
            'edit.php?post_type=testimonial'
        );

    }

}

$WEP_wp_menu = new WEP_Admin_Menu;

endif;
