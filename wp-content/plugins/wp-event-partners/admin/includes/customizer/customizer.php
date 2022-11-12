<?php
/**
 * Event Partner Customizer
 */

$panels   = array( 'theme-options');


$general_sections = array( 'footer' );
$header_sections = array(  'site-identity' ,'theme-header');
$theme_sections = array( 'counter', 'speakers', 'schedules', 'sponsors', 'testimonials', 'organizers' );
$ad_section = array( 'header-ad' );


if( ! empty( $panels ) ) {
	foreach( $panels as $panel ){
        include_once dirname(__FILE__) .'/panels/' . $panel . '.php';

	}
}

if( ! empty( $theme_sections ) ) {
    foreach( $theme_sections as $section ) {
        include_once dirname(__FILE__) .'/sections/theme-options/customizer-' . $section . '.php';
    }
}


/**
 * Enqueue the customizer stylesheet.
 */
function wep_customizer_stylesheet() {

	wp_register_style( 'one-page-coference-customizer-css', get_template_directory_uri() . '/css/customizer.css', NULL, '1.1.0', 'all' );
	wp_enqueue_style( 'one-page-coference-customizer-css' );

}
add_action( 'customize_controls_print_styles', 'wep_customizer_stylesheet' );



/**
 * Sanitization Functions
*/
include_once dirname(__FILE__) .'/sanitization-functions.php';