<?php
/**
 * Colors Settings
 *
 * @package One_Page_Conference
 */


add_action( 'customize_register', 'one_page_conference_change_colors_panel' );

function one_page_conference_change_colors_panel( $wp_customize)  {
    $wp_customize->get_section( 'colors' )->priority = 1;
    $wp_customize->get_section( 'colors' )->panel = 'one_page_conference_general_panel';
}


add_action( 'customize_register', 'one_page_conference_customize_color_options' );

function one_page_conference_customize_color_options( $wp_customize ) {

    $wp_customize->add_setting( 'header_bg_color', array(
        'default'     => '#ffffff',
        'transport'   => 'postMessage',
        'sanitize_callback' => 'one_page_conference_sanitize_hex_color'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_bg_color', array(
        'label'      => esc_html__( 'Header Background Color', 'one-page-conference' ),
        'section'    => 'colors',
        'settings'   => 'header_bg_color',
        'priority'   => 1
    ) ) );
            
    $wp_customize->add_setting( 'primary_color', array(
        'default'     => '#8c52ff',
        'transport'   => 'postMessage',
        'sanitize_callback' => 'one_page_conference_sanitize_hex_color'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
        'label'      => esc_html__( 'Primary Color', 'one-page-conference' ),
        'section'    => 'colors',
        'settings'   => 'primary_color',
        'priority'   => 2
    ) ) );

    $wp_customize->add_setting( 'secondary_color', array(
        'default'     => '#ffc014',
        'transport'   => 'postMessage',
        'sanitize_callback' => 'one_page_conference_sanitize_hex_color'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color', array(
        'label'      => esc_html__( 'Secondary Color', 'one-page-conference' ),
        'section'    => 'colors',
        'settings'   => 'secondary_color',
        'priority'   => 2
    ) ) );

    $wp_customize->add_setting( 'text_color', array(
        'default'     => '#757575',
        'transport'   => 'postMessage',
        'sanitize_callback' => 'one_page_conference_sanitize_hex_color'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
        'label'      => esc_html__( 'Text Color', 'one-page-conference' ),
        'section'    => 'colors',
        'settings'   => 'text_color',
        'priority'   => 2
    ) ) );

    $wp_customize->add_setting( 'accent_color', array(
        'default'     => '#5278AD',
        'transport'   => 'postMessage',
        'sanitize_callback' => 'one_page_conference_sanitize_hex_color'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
        'label'      => esc_html__( 'Accent Color', 'one-page-conference' ),
        'section'    => 'colors',
        'settings'   => 'accent_color',
        'priority'   => 2
    ) ) );

    $wp_customize->add_setting( 'light_color', array(
        'default'     => '#ffffff',
        'transport'   => 'postMessage',
        'sanitize_callback' => 'one_page_conference_sanitize_hex_color'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'light_color', array(
        'label'      => esc_html__( 'Light Color', 'one-page-conference' ),
        'section'    => 'colors',
        'settings'   => 'light_color',
        'priority'   => 2
    ) ) );

    $wp_customize->add_setting( 'dark_color', array(
        'default'     => '#111111',
        'transport'   => 'postMessage',
        'sanitize_callback' => 'one_page_conference_sanitize_hex_color'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_color', array(
        'label'      => esc_html__( 'Dark Color', 'one-page-conference' ),
        'section'    => 'colors',
        'settings'   => 'dark_color',
        'priority'   => 2
    ) ) );

    $wp_customize->add_setting( 'grey_color', array(
        'default'     => '#999999',
        'transport'   => 'postMessage',
        'sanitize_callback' => 'one_page_conference_sanitize_hex_color'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'grey_color', array(
        'label'      => esc_html__( 'Grey Color', 'one-page-conference' ),
        'section'    => 'colors',
        'settings'   => 'grey_color',
        'priority'   => 2
    ) ) );
   

}