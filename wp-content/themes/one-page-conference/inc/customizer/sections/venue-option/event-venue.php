<?php
/**
 * Pages Settings
 *
 * @package One Page Conference
 */
add_action( 'customize_register', 'one_page_conference_customize_venues_section' );

function one_page_conference_customize_venues_section( $wp_customize )
{
    $wp_customize->add_section('one_page_conference_venue_sections', array(
        'title' => esc_html__('Venue Section', 'one-page-conference'),
        'description' => esc_html__('Venue Section :', 'one-page-conference'),
        'priority' => 11
    ));

    $wp_customize->add_setting('venue_display_option', array(
        'sanitize_callback' => 'one_page_conference_sanitize_checkbox',
        'default' => true
    ));

    $wp_customize->add_control(new One_Page_Conference_Toggle_Control($wp_customize, 'venue_display_option', array(
        'label' => esc_html__('Hide / Show', 'one-page-conference'),
        'section' => 'one_page_conference_venue_sections',
        'settings' => 'venue_display_option',
        'type' => 'toggle',
    )));

    $wp_customize->add_setting('heading_for_venue', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));

    $wp_customize->add_control('heading_for_venue', array(
        'label' => esc_html__('Enter Heading for Venue', 'one-page-conference'),
        'section' => 'one_page_conference_venue_sections',
        'settings' => 'heading_for_venue',
        'type' => 'text',
    ));
    $wp_customize->selective_refresh->add_partial('venue_display_option', array(
        'selector' => '.venue-layout-main .container', // You can also select a css class
    ));
    $wp_customize->add_setting('venue_name', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('venue_name', array(
        'label' => esc_html__('Event Venue Name', 'one-page-conference'),
        'section' => 'one_page_conference_venue_sections',
        'settings' => 'venue_name',
        'type' => 'text',
    ));
    $wp_customize->add_setting('venue_location', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default'     => '',
    ));

    $wp_customize->add_control('venue_location', array(
        'label' => esc_html__('Enter Venue Location', 'one-page-conference'),
        'section' => 'one_page_conference_venue_sections',
        'settings' => 'venue_location',
        'type' => 'text',
    ));
    $wp_customize->add_setting('venue_description', array(
        'sanitize_callback' => 'sanitize_textarea_field',
        'default'     => '',
    ));

    $wp_customize->add_control('venue_description', array(
        'label' => esc_html__('Enter Venue Description', 'one-page-conference'),
        'section' => 'one_page_conference_venue_sections',
        'settings' => 'venue_description',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('venue_location_map', array(
        'sanitize_callback' => 'one_page_conference_sanitize_ifram',
        'default'     => '',
    ));

    $wp_customize->add_control('venue_location_map', array(
        'label' => esc_html__('Enter iframe for location', 'one-page-conference'),
        'section' => 'one_page_conference_venue_sections',
        'settings' => 'venue_location_map',
        'type' => 'textarea',
    ));
    $wp_customize->add_setting( 'event_venue_image', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_image',
        'default'               =>  ''
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'event_venue_image', array(
        'label' => esc_html__( 'Upload a Venue Image', 'one-page-conference' ),
        'section'   => 'one_page_conference_venue_sections',
        'settings'  => 'event_venue_image'
    ) )); 
      

}