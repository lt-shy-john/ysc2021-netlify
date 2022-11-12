<?php

/**
 * Fonts Settings
 */


add_action( 'customize_register', 'one_page_conference_customize_register_fonts_section' );
function one_page_conference_customize_register_fonts_section( $wp_customize ) {

    $wp_customize->add_section( 'one_page_conference_fonts_section', array(
        'title'          => esc_html__( 'Fonts', 'one-page-conference' ),
        'panel'          => 'one_page_conference_general_panel',
        'priority'       => 2,
    ) );
}

add_action( 'customize_register', 'one_page_conference_customize_font_family' );
function one_page_conference_customize_font_family( $wp_customize ) {

    $wp_customize->add_setting( 'font_family', array(
        'transport' => 'postMessage',
        'default'     => 'Montserrat',
        'sanitize_callback' => 'one_page_conference_sanitize_google_fonts',
    ) );

    $wp_customize->add_control( 'font_family', array(
        'settings'    => 'font_family',
        'label'       =>  esc_html__( 'Choose Font Family For Your Site', 'one-page-conference' ),
        'section'     => 'one_page_conference_fonts_section',
        'type'        => 'select',
        'choices'     => one_page_conference_google_fonts(),
        'priority'    => 100
    ) );
}

add_action( 'customize_register', 'one_page_conference_customize_font_size' );
function one_page_conference_customize_font_size( $wp_customize ) {
    $wp_customize->add_setting( 'font_size', array(
        'transport' => 'postMessage',
        'default'     => '14px',
        'sanitize_callback' => 'one_page_conference_sanitize_select',
    ) );

    $wp_customize->add_control( 'font_size', array(
        'settings'    => 'font_size',
        'label'       =>  esc_html__( 'Choose Font Size', 'one-page-conference' ),
        'section'     => 'one_page_conference_fonts_section',
        'type'        => 'select',
        'default'     => '13px',
        'choices'     =>  array(
            '13px' => '13px',
            '14px' => '14px',
            '15px' => '15px',
            '16px' => '16px',
            '17px' => '17px',
            '18px' => '18px',
        ),
        'priority'    =>  101
    ) );
}

add_action( 'customize_register', 'one_page_conference_heading_options' );
function one_page_conference_heading_options( $wp_customize ) {

    $wp_customize->add_setting( 'heading_options_text', array(
        'default' => '',
        'type' => 'customtext',
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( new One_Page_Conference_Custom_Text( $wp_customize, 'heading_options_text', array(
        'label' => esc_html__( 'Heading Options :', 'one-page-conference' ),
        'section' => 'one_page_conference_fonts_section',
        'settings' => 'heading_options_text',
        'priority'    => 103
    ) ) );
}

add_action( 'customize_register', 'one_page_conference_heading_font_family' );
function one_page_conference_heading_font_family( $wp_customize ) {

    $wp_customize->add_setting( 'heading_font_family', array(
        'transport' => 'postMessage',
        'sanitize_callback' => 'one_page_conference_sanitize_google_fonts',
        'default'     => 'Poppins',
    ) );

    $wp_customize->add_control( 'heading_font_family', array(
        'settings'    => 'heading_font_family',
        'label'       =>  esc_html__( 'Font Family', 'one-page-conference' ),
        'section'     => 'one_page_conference_fonts_section',
        'type'        => 'select',
        'choices'     => one_page_conference_google_fonts(),
        'priority'    => 103
    ) );

}