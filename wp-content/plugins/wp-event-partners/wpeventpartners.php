<?php
/*
Plugin Name: WP Event Partners - WordPress Plugin for Event and Conference Management
Description: Event, Meetup and Conference partner plugin to help create landing pages with schedule, speakers, sponsors, of your conference and events. 
Author: WPEventPartners
Author URI: https://wpeventpartners.com
Text Domain: wp-event-partners
Domain Path: /languages/
Version: 1.2.2
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// freemius

if ( ! function_exists( 'wep_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wep_fs() {
        global $wep_fs;

        if ( ! isset( $wep_fs ) ) {
            // Activate multisite network integration.
            if ( ! defined( 'WP_FS__PRODUCT_5293_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_5293_MULTISITE', true );
            }

            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $wep_fs = fs_dynamic_init( array(
                'id'                  => '5293',
                'slug'                => 'wp-event-partners',
                'type'                => 'plugin',
                'public_key'          => 'pk_5a3ad5072333de10b92a881ba9b15',
                'is_premium'          => false,
                'has_addons'          => true,
                'has_paid_plans'      => false,
            ) );
        }

        return $wep_fs;
    }

    // Init Freemius.
    wep_fs();
    // Signal that SDK was initiated.
    do_action( 'wep_fs_loaded' );
}
// freemius



define('WEP_EVENT_URL', plugins_url('', __FILE__));
define('WEP_EVENT_DIR', plugin_dir_path(__FILE__));

if ( !class_exists( 'WepPlugin' ) ) :
    class WepPlugin{

        function register(){
            add_action( 'admin_enqueue_scripts', array( $this, 'event_custom_wp_admin_script' ) );
            add_action('wp_enqueue_scripts', array( $this, 'event_management_scripts' ) );
            add_action('plugins_loaded', array( $this, 'load_file' ) );
            add_action('init', array( $this, 'my_custom_init' ) );
        }        

        //ADMIN SCRIPTS AND STYLE
        function event_custom_wp_admin_script() {

            wp_enqueue_script('event_wp_plugin_script', WEP_EVENT_URL . '/admin/assets/js/event_wp_plugin_script.js', array('jquery'), '', true);
            wp_enqueue_script('bootstrap', WEP_EVENT_URL . '/admin/assets/js/bootstrap.min.js', array('jquery'), '', true);
            wp_enqueue_script( 'jquery-ui-sortable' );

            wp_enqueue_style('fontawesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, false, 'screen');
            wp_enqueue_style('bootstrap', WEP_EVENT_URL . '/admin/assets/css/bootstrap.css', false, false, 'screen');
            wp_register_style('event-wp-plugin-style', WEP_EVENT_URL . '/admin/assets/css/event_wp_plugin_style.css', array(), '');
            wp_enqueue_style( 'event-wp-plugin-style' );

            wp_enqueue_style('bootstrap-timepickers', WEP_EVENT_URL . '/admin/assets/css/timepicki.css', false, false, 'screen');

            wp_enqueue_script('bootstrap-timepicker', WEP_EVENT_URL . '/admin/assets/js/timepicki.js',  array('jquery'), '', true);

            //for map
            if( is_admin() ) {
            $screen = get_current_screen();
            if ( in_array( $screen->id, array( 'toplevel_page_wp_event_partners') ) ) {

                wp_enqueue_script('google-map-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAOULlbJ1DsvO32m9czvRKDLag8l_J4IHg&libraries=places&callback=initMap', array('event-google-map'), '20151215', true);

                wp_enqueue_script("event-google-map", WEP_EVENT_URL . '/admin/assets/js/google-map.js', array('jquery'), '', true);
            }
            }
            if( is_admin() ) {

                $screen = get_current_screen();

                if( $screen->post_type === 'speaker' OR $screen->post_type === 'room' OR $screen->post_type === 'sponsor' OR $screen->post_type === 'session' OR $screen->post_type === 'wep_organizer' OR $screen->post_type === 'testimonial' ) {
                    wp_register_script( 'active-submenu-js', WEP_EVENT_URL . '/admin/assets/js/active-menu.js', array('jquery-core'), false, true );
                    wp_enqueue_script( 'active-submenu-js' );

                }
            }
        }

        //FRONTEND SCRIPTS AND STYLE
        function event_management_scripts()
        {

            wp_enqueue_script('owl-carousel', WEP_EVENT_URL . '/frontend/assets/js/owl.carousel.js', array('jquery'), '', true);
            wp_enqueue_script('js-main-script', WEP_EVENT_URL . '/frontend/assets/js/script.js', array('jquery'), '', true);
            wp_enqueue_script('bootstrap', WEP_EVENT_URL . '/admin/assets/js/bootstrap.min.js', array('jquery'), '', true);


            wp_enqueue_style('bootstrap', WEP_EVENT_URL . '/admin/assets/css/bootstrap.css', false, false, 'screen');
            wp_enqueue_style('owl-carousel', WEP_EVENT_URL . '/frontend/assets/css/owl.carousel.min.css', false, false, 'screen');
            wp_enqueue_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', '', true);
            wp_enqueue_style('schedule-layout-1', WEP_EVENT_URL . '/frontend/assets/css/schedule-layout-1.css', false, false, 'screen');
            wp_enqueue_style('speaker-layout-', WEP_EVENT_URL . '/frontend/assets/css/speaker-layout.css', false, false, 'screen');
            wp_enqueue_style('sponsor-layout-', WEP_EVENT_URL . '/frontend/assets/css/sponsor-layout.css', false, false, 'screen');
            wp_enqueue_style('testimonial-layout-', WEP_EVENT_URL . '/frontend/assets/css/testimonial-layout.css', false, false, 'screen');
            wp_enqueue_style('venue-layout-', WEP_EVENT_URL . '/frontend/assets/css/venue-layout.css', false, false, 'screen');
            wp_enqueue_script( 'live-now', WEP_EVENT_URL . '/frontend/assets/js/live-now.js', array( 'jquery' ), '20151215', true );
        }

        function load_file(){
    //include extra files
            include "admin/includes/add-custom-post-type.php";
            include "admin/includes/metabox/add-metabox.php";
            include "admin/includes/metabox/save-metabox.php";
            include "admin/includes/taxonomy/add-taxonomy.php";
            include "admin/includes/taxonomy/taxonomy-image.php";
            include "admin/includes/functions.php";
            include "admin/includes/setting/register-settings.php";
            include "admin/includes/setting/display-schedule-setting.php";
            include "admin/includes/setting/display-event-information-setting.php";
            include "frontend/event-hooks.php";
            include "admin/includes/custom-controls/custom-control.php";
            include "admin/includes/customizer/customizer.php";
            include "admin/includes/class-menu.php";
            include "admin/includes/shortcode.php";
        }

        function my_custom_init() {
            add_post_type_support( 'page', 'excerpt' );
        }

    }
    $wepPlugin = new WepPlugin();
    $wepPlugin->register();


function wep_activate(){
    require_once plugin_dir_path( __FILE__ ) . 'admin/includes/wpeventpartners-activate.php';
    WepPluginActivate::activate();
}

function wep_deactivate(){
    $dependent = 'wep-pricing-table/wep-pricing-table.php';
    if( is_plugin_active( $dependent ) ){
        add_action( 'update_option_active_plugins', 'wep_deactivate_dependent_plugins' );
    }
    

}

function wep_deactivate_dependent_plugins(){
 $dependent = 'wep-pricing-table/wep-pricing-table.php';
 deactivate_plugins( $dependent );
}

register_activation_hook( __FILE__, 'wep_activate' );
register_deactivation_hook( __FILE__, 'wep_deactivate' );

endif;
add_post_type_support( 'page', 'excerpt' );