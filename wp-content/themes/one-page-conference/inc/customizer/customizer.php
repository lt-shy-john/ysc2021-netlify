<?php

/**
 * One Page Conference Theme Customizer
 */
$panels = array(
    'general-options',
    'header-options',
    'banner-option',
    'cta-options'
);
add_action( 'customize_register', 'one_page_conference_change_homepage_settings_options' );
function one_page_conference_change_homepage_settings_options( $wp_customize )
{
    $wp_customize->get_section( 'title_tagline' )->priority = 12;
    $wp_customize->get_section( 'static_front_page' )->priority = 13;
    $wp_customize->get_section( 'header_image' )->panel = 'one_page_conference_banner_panel';
    $wp_customize->get_section( 'header_image' )->title = 'Banner Image';
    require get_template_directory() . '/inc/google-fonts.php';
}

$general_sections = array(
    'colors',
    'fonts',
    'social-media',
    'blog-list',
    'copyright'
);
$header_sections = array( 'site-identity', 'theme-header' );
$banner_sections = array( 'banner-layout', 'event-information' );
$about_sections = array( 'about-us' );
$venue_sections = array( 'event-venue' );
$drag_drop = array( 'drag-and-drop' );
if ( !empty($panels) ) {
    foreach ( $panels as $panel ) {
        require get_template_directory() . '/inc/customizer/panels/' . $panel . '.php';
    }
}
if ( !empty($general_sections) ) {
    foreach ( $general_sections as $section ) {
        require get_template_directory() . '/inc/customizer/sections/general-options/' . $section . '.php';
    }
}
if ( !empty($header_sections) ) {
    foreach ( $header_sections as $section ) {
        require get_template_directory() . '/inc/customizer/sections/header-options/' . $section . '.php';
    }
}
if ( !empty($banner_sections) ) {
    foreach ( $banner_sections as $section ) {
        require get_template_directory() . '/inc/customizer/sections/banner-option/' . $section . '.php';
    }
}
if ( !empty($about_sections) ) {
    foreach ( $about_sections as $section ) {
        require get_template_directory() . '/inc/customizer/sections/about-option/' . $section . '.php';
    }
}
if ( !empty($venue_sections) ) {
    foreach ( $venue_sections as $section ) {
        require get_template_directory() . '/inc/customizer/sections/venue-option/' . $section . '.php';
    }
}
if ( !empty($drag_drop) ) {
    foreach ( $drag_drop as $section ) {
        require get_template_directory() . '/inc/customizer/sections/' . $section . '.php';
    }
}
/**
 * Enqueue the customizer javascript.
 */
function one_page_conference_customize_preview_js()
{
    wp_enqueue_script(
        'one-page-conference-customizer-preview',
        get_template_directory_uri() . '/js/customizer.js',
        array( 'jquery' ),
        '1.0.0',
        true
    );
}

add_action( 'customize_preview_init', 'one_page_conference_customize_preview_js' );
/**
 * Sanitization Functions
*/
require get_template_directory() . '/inc/customizer/sanitization-functions.php';