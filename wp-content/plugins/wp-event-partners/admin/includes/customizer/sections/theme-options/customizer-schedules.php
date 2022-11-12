<?php
/**
 * Pages Settings
 *
 * @package One Page Conference
 */
add_action( 'customize_register', 'one_page_conference_customize_schedules_section' );

function one_page_conference_customize_schedules_section( $wp_customize ) {

    $wp_customize->add_section( 'one_page_conference_schedule_sections', array(
        'title'          => esc_html__( 'Schedule Section', 'wp-event-partners' ),
        'panel'          => 'one_page_conference_theme_options_panel',
    ) );

    $wp_customize->add_setting( 'schedule_display_option', array(
        'sanitize_callback'     =>  'wep_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_Page_Conference_Toggle_Control( $wp_customize, 'schedule_display_option', array(
        'label' => esc_html__( 'Hide / Show','wp-event-partners' ),
        'section' => 'one_page_conference_schedule_sections',
        'settings' => 'schedule_display_option',
        'type'=> 'toggle',
    ) ) );

    $wp_customize->add_setting( 'heading_for_schedule', array(
        'sanitize_callback'     =>  'sanitize_text_field',
        'default'               =>  ''
    ) );

    $wp_customize->add_control( 'heading_for_schedule', array(
        'label' => esc_html__( 'Enter Heading for Schedule', 'wp-event-partners' ),
        'section' => 'one_page_conference_schedule_sections',
        'settings' => 'heading_for_schedule',
        'type'=> 'text',
    ) );

    $wp_customize->selective_refresh->add_partial( 'heading_for_schedule', array(
        'selector' => '#schedule .main-title .title', // You can also select a css class
    ) );

    $wp_customize->add_setting( 'content_for_schedule', array(
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( 'content_for_schedule', array(
        'label' => esc_html__( 'Enter content for Schedule Section', 'wp-event-partners' ),
        'section' => 'one_page_conference_schedule_sections',
        'settings' => 'content_for_schedule',
        'type'=> 'textarea',
    ) );
    $wp_customize->selective_refresh->add_partial( 'schedule_display_option', array(
        'selector' => '#schedule .main-title', // You can also select a css class
    ) );
}