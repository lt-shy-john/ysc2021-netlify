<?php
/**
 * Pages Settings
 *
 * @package One Page Conference
 */
add_action( 'customize_register', 'one_page_conference_customize_counter_section' );

function one_page_conference_customize_counter_section( $wp_customize ) {

    $wp_customize->add_section( 'one_page_conference_counter_sections', array(
        'title'          => esc_html__( 'Counter Section', 'wp-event-partners' ),
        'panel'          => 'one_page_conference_theme_options_panel',
    ) );

    $wp_customize->add_setting( 'counter_show_hide_option', array(
        'sanitize_callback'     =>  'wep_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_Page_Conference_Toggle_Control( $wp_customize, 'counter_show_hide_option', array(
        'label' => esc_html__( 'Hide / Show','wp-event-partners' ),
        'section' => 'one_page_conference_counter_sections',
        'settings' => 'counter_show_hide_option',
        'type'=> 'toggle',
    ) ) );


    $wp_customize->add_setting( 'heading_for_counter', array(
        'sanitize_callback'     =>  'sanitize_text_field',
        'default'               =>  ''
    ) );

    $wp_customize->add_control( 'heading_for_counter', array(
        'label' => esc_html__( 'Enter Heading for Counter', 'wp-event-partners' ),
        'section' => 'one_page_conference_counter_sections',
        'settings' => 'heading_for_counter',
        'type'=> 'text',
    ) );

    $wp_customize->add_setting( 'content_for_counter', array(
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( 'content_for_counter', array(
        'label' => esc_html__( 'Enter content for Counter Section', 'wp-event-partners' ),
        'section' => 'one_page_conference_counter_sections',
        'settings' => 'content_for_counter',
        'type'=> 'textarea',
    ) );
    $wp_customize->add_setting('counter_button_label', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));

    $wp_customize->add_control('counter_button_label', array(
        'label' => esc_html__('Button Label', 'one-page-conference'),
        'section' => 'one_page_conference_counter_sections',
        'settings' => 'counter_button_label',
        'type' => 'text',
    ));

    $wp_customize->add_setting('counter_link', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));

    $wp_customize->add_control('counter_link', array(
        'label' => esc_html__('Button Link', 'one-page-conference'),
        'section' => 'one_page_conference_counter_sections',
        'settings' => 'counter_link',
        'type' => 'text',
    ));

    $wp_customize->add_setting( new One_Page_Conference_Repeater_Setting( $wp_customize, 'counter_display_option', array(
        'sanitize_callback' => array( 'One_Page_Conference_Repeater_Setting', 'sanitize_repeater_setting' ),
    ) ) );

    $wp_customize->add_control( new One_Page_Conference_Control_Repeater( $wp_customize, 'counter_display_option', array(
        'section' => 'one_page_conference_counter_sections',
        'settings'    => 'counter_display_option',
        'label'   => esc_html__( 'Counter', 'one-page-conference' ),
        'row_label' => array(
            'type'  => 'field',
            'value' => esc_html__( 'Counter', 'one-page-conference' ),
            'field' => 'counter_text',
        ), 
        'fields' => array(
            'counter_number' => array(
                'type'        => 'text',
                'label'       => esc_html__( 'Enter Number', 'one-page-conference' ),
            ),
            'counter_text' => array(
                'type'      => 'text',
                'label'     => esc_html__( 'Enter Text', 'one-page-conference' ),
            ),          
        ),
                               
    ) ) );
    
    $wp_customize->selective_refresh->add_partial( 'counter_show_hide_option', array(
        'selector' => '.wep-counter-wrapper .container', // You can also select a css class
    ) );
}