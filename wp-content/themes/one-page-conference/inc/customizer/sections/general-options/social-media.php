<?php
/**
 * Social Media Sections

 */
add_action( 'customize_register', 'one_page_conference_social_media_sections' );

function one_page_conference_social_media_sections( $wp_customize ) {

    $wp_customize->add_section( 'one_page_conference_social_media_sections', array(
        'title'          => esc_html__( 'Social Media', 'one-page-conference' ),
        'priority'       => 2,
        'panel'			 => 'one_page_conference_general_panel',
    ) );
    
    
    $wp_customize->add_setting( new One_Page_Conference_Repeater_Setting( $wp_customize, 'one_page_conference_social_media', array(
        'sanitize_callback' => array( 'One_Page_Conference_Repeater_Setting', 'sanitize_repeater_setting' ),
    ) ) );

    $wp_customize->add_control( new One_Page_Conference_Control_Repeater( $wp_customize, 'one_page_conference_social_media', array(
        'section' => 'one_page_conference_social_media_sections',
        'settings'    => 'one_page_conference_social_media',
        'label'   => esc_html__( 'Social Media', 'one-page-conference' ),
        'row_label' => array(
            'type'  => 'field',
            'value' => esc_html__( 'Social Media', 'one-page-conference' ),
            'field' => 'social_media_repeater_class',
        ), 
        'fields' => array(
            'social_media_repeater_class' => array(
                'type'        => 'select',
                'label'       => esc_html__( 'Select Social Media', 'one-page-conference' ),
                'default'     => 'facebook',
                'choices'   => one_page_conference_social_media()
            ),
            'social_media_link' => array(
                'type'      => 'url',
                'label'     => esc_html__( 'Social Media Links', 'one-page-conference' ),
            ),          
        ),
                               
    ) ) );

    $wp_customize->selective_refresh->add_partial( 'one_page_conference_social_media', array(
        'selector'        => '.social-icons',
        'render_callback' => '',
    ) );

}