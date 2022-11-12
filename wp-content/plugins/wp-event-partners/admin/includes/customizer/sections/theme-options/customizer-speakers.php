<?php
/**
 * Pages Settings
 *
 * @package One Page Conference
 */
add_action( 'customize_register', 'one_page_conference_customize_speakers_section' );

function one_page_conference_customize_speakers_section( $wp_customize )
{

    $wp_customize->add_section('one_page_conference_speaker_sections', array(
        'title' => esc_html__('Speaker Section', 'wp-event-partners'),
        'description' => esc_html__('Speaker Section :', 'wp-event-partners'),
        'panel' => 'one_page_conference_theme_options_panel',
    ));

    $wp_customize->add_setting('speaker_display_option', array(
        'sanitize_callback' => 'wep_sanitize_checkbox',
        'default' => true
    ));

    $wp_customize->add_control(new One_Page_Conference_Toggle_Control($wp_customize, 'speaker_display_option', array(
        'label' => esc_html__('Hide / Show', 'wp-event-partners'),
        'section' => 'one_page_conference_speaker_sections',
        'settings' => 'speaker_display_option',
        'type' => 'toggle',
    )));

    $wp_customize->add_setting('heading_for_speaker', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));

    $wp_customize->add_control('heading_for_speaker', array(
        'label' => esc_html__('Enter Heading for Speaker', 'wp-event-partners'),
        'section' => 'one_page_conference_speaker_sections',
        'settings' => 'heading_for_speaker',
        'type' => 'text',
    ));
    $wp_customize->selective_refresh->add_partial('speaker_display_option', array(
        'selector' => '.speakers-wrapper .container', // You can also select a css class
    ));
    $wp_customize->add_setting('one_page_conference_speaker_layouts', array(
        'sanitize_callback' => 'wep_sanitize_choices',
        'default' => '1',
    ));
     $wp_customize->add_control(new One_Page_Conference_Radio_Image_Control($wp_customize, 'one_page_conference_speaker_layouts', array(
        'label' => esc_html__('Select Layout For Speaker Section', 'wp-event-partners'),
        'section' => 'one_page_conference_speaker_sections',
        'settings' => 'one_page_conference_speaker_layouts',
        'type' => 'radio-image',
        'choices' => array(
            '1' => WEP_EVENT_URL . '/admin/assets/images/speakers/speaker-layout-1.jpg',
            '2' => WEP_EVENT_URL . '/admin/assets/images/speakers/speaker-layout-2.jpg',
            '3' => WEP_EVENT_URL . '/admin/assets/images/speakers/speaker-layout-3.jpg',
            '4' => WEP_EVENT_URL . '/admin/assets/images/speakers/speaker-layout-4.jpg',
        ),
    )));

}