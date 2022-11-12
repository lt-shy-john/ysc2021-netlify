<?php
/**
 * Event Information Settings
 */

add_action( 'customize_register', 'one_page_conference_customize_register_event_information' );

function one_page_conference_customize_register_event_information( $wp_customize ) {
	$wp_customize->add_section( 'one_page_conference_event_information_sections', array(
	    'title'          => esc_html__( 'Event Information', 'one-page-conference' ),
	    'panel'          => 'one_page_conference_banner_panel',
	) );


    $wp_customize->add_setting( 'banner_display_in_homepage', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'banner_display_in_homepage', array(
        'label' => esc_html__( 'Enable Banner in Home','one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'banner_display_in_homepage',
        'type'=> 'toggle',
    ) ) );

    $wp_customize->add_setting( 'banner_display_in_otherpage', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'banner_display_in_otherpage', array(
        'label' => esc_html__( 'Enable Banner in other pages','one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'banner_display_in_otherpage',
        'type'=> 'toggle',
    ) ) );



    $wp_customize->add_setting( 'hide_show_banner_countdown_timer', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'hide_show_banner_countdown_timer', array(
        'label' => esc_html__( 'Enable Countdown Timer?','one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'hide_show_banner_countdown_timer',
        'type'=> 'toggle',
    ) ) );

    $wp_customize->selective_refresh->add_partial('hide_show_banner_countdown_timer', array(
        'selector' => '#banner .caption', // You can also select a css class
    ));

    $wp_customize->add_setting( 'hide_show_banner_start_date', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'hide_show_banner_start_date', array(
        'label' => esc_html__( 'Show Start Date?','one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'hide_show_banner_start_date',
        'type'=> 'toggle',
    ) ) );


    $wp_customize->add_setting( 'hide_show_banner_end_date', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'hide_show_banner_end_date', array(
        'label' => esc_html__( 'Show End Date?','one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'hide_show_banner_end_date',
        'type'=> 'toggle',
    ) ) );


    $wp_customize->add_setting( 'hide_show_banner_start_time', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'hide_show_banner_start_time', array(
        'label' => esc_html__( 'Show Start Time?','one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'hide_show_banner_start_time',
        'type'=> 'toggle',
    ) ) );


    $wp_customize->add_setting( 'hide_show_banner_end_time', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'hide_show_banner_end_time', array(
        'label' => esc_html__( 'Show End Time?','one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'hide_show_banner_end_time',
        'type'=> 'toggle',
    ) ) );


    $wp_customize->add_setting( 'hide_show_banner_venue', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'hide_show_banner_venue', array(
        'label' => esc_html__( 'Show Venue?','one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'hide_show_banner_venue',
        'type'=> 'toggle',
    ) ) );


    $wp_customize->add_setting( 'event_title_font_size', array(
        'default'           => 40,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new One_Page_Conference_Slider_Control( $wp_customize, 'event_title_font_size', array(
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'event_title_font_size',
        'label'   => esc_html__( 'Font Size', 'one-page-conference' ),
        'choices'     => array(
            'min'  => 20,
            'max'  => 80,
            'step' => 1,
        ),
    ) ) );


    $wp_customize->add_setting( 'banner_overlay_color', array(
        'default'     => '#5d1dce',            
        'sanitize_callback' => 'one_page_conference_sanitize_hex_color'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'banner_overlay_color', array(
        'label'      => esc_html__( 'Overlay Color', 'one-page-conference' ),
        'section'    => 'one_page_conference_event_information_sections',
        'settings'   => 'banner_overlay_color',
    ) ) );


    $wp_customize->add_setting( 'banner_overlay_color_opacity', array(
        'default'           => 0.8,
        'sanitize_callback' => 'one_page_conference_sanitize_number_range'
    ) );

    $wp_customize->add_control( new One_page_Conference_Slider_Control( $wp_customize, 'banner_overlay_color_opacity', array(
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'banner_overlay_color_opacity',
        'label'   => esc_html__( 'Overlay Color Opacity', 'one-page-conference' ),
        'choices'     => array(
            'min'   => 0,
            'max'   => 1,
            'step'  => 0.1,
        )
    ) ) );


	$wp_customize->add_setting( 'event_title', array(
        'transport'         => 'postMessage',
        'sanitize_callback'     =>  'sanitize_text_field',
        'default'               =>  'Startup Day 2021'
    ) );

    $wp_customize->add_control( 'event_title', array(
        'label' => esc_html__( 'Event Title', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'event_title',
        'type'=> 'text',
    ) );

    $wp_customize->add_setting( 'event_description', array(
        'transport'         => 'postMessage',
        'sanitize_callback'     =>  'wp_kses_post',
        'default'           => 'Startup day is a one day conference dedicated to exploring your latest project. It is a strong space for collaboration, pitch sessions and group projects that are designed to be our last serious say in getting something out to the world.'
    ) );

    $wp_customize->add_control( 'event_description', array(
        'label' => esc_html__( 'Event Description', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'event_description',
        'type'=> 'textarea',
    ) );


    $wp_customize->add_setting( 'start_date', array(
        'sanitize_callback'     =>  'one_page_conference_date_time_sanitization',
        'default' => '2021-02-20'
    ) );

    $wp_customize->add_control( 'start_date', array(
        'label' => esc_html__( 'Start Date', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'start_date',
        'type'=> 'date',
		'input_attrs' => array(
			'placeholder' => __( 'mm/dd/yyyy', 'one-page-conference' ),
		),
    ) );

    $wp_customize->add_setting( 'end_date', array(
        'sanitize_callback'     =>  'one_page_conference_date_time_sanitization',
        'default'               => '2021-02-22'
    ) );

    $wp_customize->add_control( 'end_date', array(
        'label' => esc_html__( 'End Date', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'end_date',
        'type'=> 'date',
		'input_attrs' => array(
			'placeholder' => __( 'mm/dd/yyyy', 'one-page-conference' ),
		),
    ) );

    $wp_customize->add_setting( 'start_time', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '10:00'
    ) );

    $wp_customize->add_control( 'start_time', array(
        'label' => esc_html__( 'Start Time', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'start_time',
        'type'=> 'time',
    ) );

    $wp_customize->add_setting( 'end_time', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '14:00'
    ) );


    $wp_customize->add_control( 'end_time', array(
        'label' => esc_html__( 'End Time', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'end_time',
        'type'=> 'time',
    ) );

    $wp_customize->add_setting( 'event_venue', array(
        'transport'         => 'postMessage',
        'sanitize_callback'     =>  'sanitize_text_field',
        'default'           => 'Lifelong Learning Institute'
    ) );

    $wp_customize->add_control( 'event_venue', array(
        'label' => esc_html__( 'Event Venue', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'event_venue',
        'type'=> 'text',
    ) );

    $wp_customize->add_setting( 'hide_show_cta_button_1', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'hide_show_cta_button_1', array(
        'label' => esc_html__( 'Enable CTA 1 Button','one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'hide_show_cta_button_1',
        'type'=> 'toggle',
    ) ) );

    $wp_customize->add_setting( 'cta_1_button_label', array(
        'transport'         => 'postMessage',
        'sanitize_callback'     =>  'sanitize_text_field',
        'default'           => 'Call of Paper'
    ) );

    $wp_customize->add_control( 'cta_1_button_label', array(
        'label' => esc_html__( 'CTA 1 Button Label', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'cta_1_button_label',
        'type'=> 'text',
        'active_callback' => function(){
            return get_theme_mod( 'hide_show_cta_button_1', true );
        },
    ) );

    $wp_customize->add_setting( 'cta_1_link', array(
        'sanitize_callback'     => 'esc_url_raw',
        'default'               => '#'
    ) );

    $wp_customize->add_control( 'cta_1_link', array(
        'label' => esc_html__( 'CTA 1 Link', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'cta_1_link',
        'type'=> 'url',
        'active_callback' => function(){
            return get_theme_mod( 'hide_show_cta_button_1', true );
        },
    ) );

    $wp_customize->add_setting( 'hide_show_cta_button_2', array(
        'sanitize_callback'     =>  'one_page_conference_sanitize_checkbox',
        'default'               =>  true
    ) );

    $wp_customize->add_control( new One_page_conference_Toggle_Control( $wp_customize, 'hide_show_cta_button_2', array(
        'label' => esc_html__( 'Enable CTA 2 Button','one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'hide_show_cta_button_2',
        'type'=> 'toggle',
    ) ) );

    $wp_customize->add_setting( 'cta_2_button_label', array(
        'transport'         => 'postMessage',
        'sanitize_callback'     =>  'sanitize_text_field',
        'default'           => 'Registration'
    ) );

    $wp_customize->add_control( 'cta_2_button_label', array(
        'label' => esc_html__( 'CTA 2 Button Label', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'cta_2_button_label',
        'type'=> 'text',
        'active_callback' => function(){
            return get_theme_mod( 'hide_show_cta_button_2', true );
        },
    ) );

    $wp_customize->add_setting( 'cta_2_link', array(
        'sanitize_callback'     =>  'esc_url_raw',
        'default'               => '#'
    ) );

    $wp_customize->add_control( 'cta_2_link', array(
        'label' => esc_html__( 'CTA 2 Link', 'one-page-conference' ),
        'section' => 'one_page_conference_event_information_sections',
        'settings' => 'cta_2_link',
        'type'=> 'url',
        'active_callback' => function(){
            return get_theme_mod( 'hide_show_cta_button_2', true );
        },
    ) );
    

}