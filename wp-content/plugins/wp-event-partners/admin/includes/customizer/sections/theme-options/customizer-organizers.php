<?php
/**
 * Pages Settings
 *
 * @package One Page Conference
 */
add_action( 'customize_register', 'one_page_conference_customize_organizers_section' );

function one_page_conference_customize_organizers_section( $wp_customize ) {

    $wp_customize->add_section( 'one_page_conference_organizers_sections', array(
        'title'          => esc_html__( 'Organizers Section', 'wp-event-partners' ),
        'panel'          => 'one_page_conference_theme_options_panel',
    ) );

    $wp_customize->add_setting( 'organizers_display_option', array(
        'sanitize_callback'     =>  'wep_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_Page_Conference_Toggle_Control( $wp_customize, 'organizers_display_option', array(
        'label' => esc_html__( 'Hide / Show','wp-event-partners' ),
        'section' => 'one_page_conference_organizers_sections',
        'settings' => 'organizers_display_option',
        'type'=> 'toggle',
    ) ) );
    
    

    $wp_customize->add_setting( 'heading_for_organizers', array(
        'sanitize_callback'     =>  'sanitize_text_field',
        'default'               =>  ''
    ) );

    $wp_customize->add_control( 'heading_for_organizers', array(
        'label' => esc_html__( 'Enter Heading for organizers', 'wp-event-partners' ),
        'section' => 'one_page_conference_organizers_sections',
        'settings' => 'heading_for_organizers',
        'type'=> 'text',
    ) );

    $wp_customize->selective_refresh->add_partial( 'organizers_display_option', array(
        'selector' => '.organizer-wrapper .container', // You can also select a css class
    ) );

}