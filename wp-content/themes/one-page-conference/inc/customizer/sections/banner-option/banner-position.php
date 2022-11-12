<?php
/**
 * Header Layout Settings
 */

add_action( 'customize_register', 'one_page_conference_theme_banner_position_section' );

function one_page_conference_theme_banner_position_section( $wp_customize ) {

    if( class_exists( 'WepPlugin' ) ) :
        $wp_customize->add_section( 'one_page_conference_theme_banner_option_section', array(
            'title'          => esc_html__( 'Banner Position', 'one-page-conference' ),
            'description'    => esc_html__( 'Banner Position', 'one-page-conference' ),
            'panel'          => 'one_page_conference_banner_panel',
            'priority'       => 2,
            'capability'     => 'edit_theme_options',
        ) );

        $wp_customize->add_setting( 'banner_position', array(
            'capability'  => 'edit_theme_options',
            'sanitize_callback' => 'one_page_conference_sanitize_choices',
            'default'     => 'center',
        ) );

        $wp_customize->add_control( new One_Page_Conference_Radio_Buttonset_Control( $wp_customize, 'banner_position', array(
            'label' => esc_html__( 'Banner Position :', 'one-page-conference' ),
            'section' => 'one_page_conference_theme_banner_option_section',
            'settings' => 'banner_position',
            'type'=> 'radio-buttonset',
            'choices'     => array(
                'left' => esc_html__( 'Left', 'one-page-conference' ),
                'center'    =>  esc_html__( 'Center', 'one-page-conference' ),
                'right' => esc_html__( 'Right', 'one-page-conference' ),
            ),
        ) ) );

    endif;
}