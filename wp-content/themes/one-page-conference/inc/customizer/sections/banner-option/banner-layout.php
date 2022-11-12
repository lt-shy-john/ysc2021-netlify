<?php
/**
 * Header Layout Settings
 */

add_action( 'customize_register', 'one_page_conference_theme_banner_layout_section' );

function one_page_conference_theme_banner_layout_section( $wp_customize ) {

    if( class_exists( 'WepPlugin' ) ) :
        $wp_customize->add_section( 'one_page_conference_theme_banner_layout_section', array(
            'title'          => esc_html__( 'Banner Layout', 'one-page-conference' ),
            'description'    => esc_html__( 'Banner Layout', 'one-page-conference' ),
            'panel'          => 'one_page_conference_banner_panel',
            'priority'       => 2,
            'capability'     => 'edit_theme_options',
        ) );


        $wp_customize->add_setting( 'one_page_conference_banner_layouts', array(
            'sanitize_callback' => 'one_page_conference_sanitize_choices',
            'default'     => 'one',
        ) );

        $wp_customize->add_control( new One_page_conference_Radio_Image_Control( $wp_customize, 'one_page_conference_banner_layouts', array(
            'label' => esc_html__( 'Banner Layout','one-page-conference' ),
            'section' => 'one_page_conference_theme_banner_layout_section',
            'settings' => 'one_page_conference_banner_layouts',
            'type'=> 'radio-image',
            'choices'     => array(
                'one'   => get_template_directory_uri() . '/images/banner/banner-1.jpg',
                'two'   => get_template_directory_uri() . '/images/banner/banner-2.jpg',
                'three'   => get_template_directory_uri() . '/images/banner/banner-3.jpg',
                'four'   => get_template_directory_uri() . '/images/banner/banner-4.jpg',
            ),
        ) ) );


    endif;
}