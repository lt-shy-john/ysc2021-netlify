<?php
/**
 * Blog List Settings
 */


add_action( 'customize_register', 'one_page_conference_customize_blog_list_option' );

function one_page_conference_customize_blog_list_option( $wp_customize ) {

    $wp_customize->add_section( 'one_page_conference_blog_list_section', array(
        'title'          => esc_html__( 'Blog Options', 'one-page-conference' ),
        'panel'          => 'one_page_conference_general_panel',
    ) );


    $wp_customize->add_setting( 'hide_show_blog_list_on_home', array(
      'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
      'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'hide_show_blog_list_on_home', array(
      'label' => esc_html__( 'Enable Blog on Home?','one-page-conference' ),
      'section' => 'one_page_conference_blog_list_section',
      'settings' => 'hide_show_blog_list_on_home',
      'type'=> 'toggle',
    ) ) );

    $wp_customize->add_setting( 'blog_post_list_options_title_text', array(
        'sanitize_callback' =>  'wp_kses_post',        
    ) );

    $wp_customize->add_control( new One_Page_Conference_Custom_Text( $wp_customize, 'blog_post_list_options_title_text', array(
        'label' =>  esc_html__( 'Blog List Options :', 'one-page-conference' ),
        'section'   =>  'one_page_conference_blog_list_section',
        'Settings'  =>  'blog_post_list_options_title_text'
    ) ) );

    $wp_customize->add_setting( 'blog_post_layout', array(
        'capability'  => 'edit_theme_options',        
        'sanitize_callback' => 'one_page_conference_sanitize_choices',
        'default'     => 'sidebar-right',
    ) );

    $wp_customize->add_control( new One_Page_Conference_Radio_Buttonset_Control( $wp_customize, 'blog_post_layout', array(
        'label' => esc_html__( 'Layouts :', 'one-page-conference' ),
        'section' => 'one_page_conference_blog_list_section',
        'settings' => 'blog_post_layout',
        'type'=> 'radio-buttonset',
        'choices'     => array(
            'sidebar-left' => esc_html__( 'Sidebar at left', 'one-page-conference' ),
            'no-sidebar'    =>  esc_html__( 'No sidebar', 'one-page-conference' ),
            'sidebar-right' => esc_html__( 'Sidebar at right', 'one-page-conference' ),            
        ),
    ) ) );


    $wp_customize->add_setting( 'blog_post_view', array(
        'transport'  => 'postMessage',        
        'sanitize_callback' => 'one_page_conference_sanitize_choices',
        'default'     => 'grid-view',
    ) );

    $wp_customize->add_control( new One_Page_Conference_Radio_Buttonset_Control( $wp_customize, 'blog_post_view', array(
        'label' => esc_html__( 'Post View :', 'one-page-conference' ),
        'section' => 'one_page_conference_blog_list_section',
        'settings' => 'blog_post_view',
        'type'=> 'radio-buttonset',
        'choices'     => array(
            'grid-view' => esc_html__( 'Grid View', 'one-page-conference' ),
            'list-view' => esc_html__( 'List View', 'one-page-conference' ),
            'full-width-view' => esc_html__( 'Fullwidth View', 'one-page-conference' ),
        ),
    ) ) );


    $wp_customize->add_setting( 'pagination_type', array(
        'sanitize_callback' => 'one_page_conference_sanitize_choices',
        'default'     => 'ajax-loadmore',
    ) );

    $wp_customize->add_control( new One_Page_Conference_Radio_Buttonset_Control( $wp_customize, 'pagination_type', array(
        'label' => esc_html__( 'Pagination Type :', 'one-page-conference' ),
        'section' => 'one_page_conference_blog_list_section',
        'settings' => 'pagination_type',
        'type'=> 'radio-buttonset',
        'choices'     => array(
            'ajax-loadmore' => esc_html__( 'Ajax Loadmore', 'one-page-conference' ),
            'number-pagination'    =>  esc_html__( 'Number Pagination', 'one-page-conference' ),      
        ),
    ) ) );            
    


}