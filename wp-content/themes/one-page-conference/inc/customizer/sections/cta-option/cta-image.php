<?php
/**
 * CTA With Image Settings
 *
 * @package One Page Conference
 */
add_action( 'customize_register', 'one_page_conference_customize_cta_with_image_section' );

function one_page_conference_customize_cta_with_image_section( $wp_customize ) {

    $wp_customize->add_section('one_page_conference_cta_with_image_sections', array(
        'title' => esc_html__('CTA with Image', 'one-page-conference'),
        'priority' => 12,
        'panel' => 'one_page_conference_cta_panel'
    ));

    $wp_customize->add_setting('cta_with_image_display_option', array(
        'sanitize_callback' => 'one_page_conference_sanitize_checkbox',
        'default' => true
    ));

    $wp_customize->add_control(new One_Page_Conference_Toggle_Control($wp_customize, 'cta_with_image_display_option', array(
        'label' => esc_html__('Hide / Show', 'one-page-conference'),
        'section' => 'one_page_conference_cta_with_image_sections',
        'settings' => 'cta_with_image_display_option',
        'type' => 'toggle',
    )));

    $wp_customize->selective_refresh->add_partial('cta_with_image_display_option', array(
        'selector' => '.cta-image-1 .container', // You can also select a css class
    ));

    $wp_customize->add_setting('cta_with_image_title', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));

    $wp_customize->add_control('cta_with_image_title', array(
        'label' => esc_html__('CTA Title', 'one-page-conference'),
        'section' => 'one_page_conference_cta_with_image_sections',
        'settings' => 'cta_with_image_title',
        'type' => 'text',
    ));

    $wp_customize->add_setting( 'cta_with_image_image', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_image',
    ) );


    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'cta_with_image_image', array(
        'label'      => esc_html__( 'CTA Image', 'one-page-conference' ),
        'section'    => 'one_page_conference_cta_with_image_sections',
        'settings'   => 'cta_with_image_image',
    ) ) );


    $wp_customize->add_setting('cta_with_image_text', array(
        'sanitize_callback' => 'wp_kses_post',
        'default' => ''
    ));

    $wp_customize->add_control('cta_with_image_text', array(
        'label' => esc_html__('CTA Text', 'one-page-conference'),
        'section' => 'one_page_conference_cta_with_image_sections',
        'settings' => 'cta_with_image_text',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('cta_with_image_button_label', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));

    $wp_customize->add_control('cta_with_image_button_label', array(
        'label' => esc_html__('CTA Button Label', 'one-page-conference'),
        'section' => 'one_page_conference_cta_with_image_sections',
        'settings' => 'cta_with_image_button_label',
        'type' => 'text',
    ));

    $wp_customize->add_setting('cta_with_image_link', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));

    $wp_customize->add_control('cta_with_image_link', array(
        'label' => esc_html__('CTA Link', 'one-page-conference'),
        'section' => 'one_page_conference_cta_with_image_sections',
        'settings' => 'cta_with_image_link',
        'type' => 'text',
    ));

    

}

