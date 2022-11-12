<?php
/**
 * Pages Settings
 *
 * @package One Page Conference
 */
add_action( 'customize_register', 'one_page_conference_customize_testimonials_section' );

function one_page_conference_customize_testimonials_section( $wp_customize )
{

    $wp_customize->add_section('one_page_conference_testimonial_sections', array(
        'title' => esc_html__('Testimonial Section', 'wp-event-partners'),
        'description' => esc_html__('Testimonial Section :', 'wp-event-partners'),
        'panel' => 'one_page_conference_theme_options_panel',
    ));

    $wp_customize->add_setting('testimonial_display_option', array(
        'sanitize_callback' => 'wep_sanitize_checkbox',
        'default' => true
    ));

    $wp_customize->add_control(new One_Page_Conference_Toggle_Control($wp_customize, 'testimonial_display_option', array(
        'label' => esc_html__('Hide / Show', 'wp-event-partners'),
        'section' => 'one_page_conference_testimonial_sections',
        'settings' => 'testimonial_display_option',
        'type' => 'toggle',
    )));

    $wp_customize->add_setting('heading_for_testimonial', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));

    $wp_customize->add_control('heading_for_testimonial', array(
        'label' => esc_html__('Enter Heading for Testimonial', 'wp-event-partners'),
        'section' => 'one_page_conference_testimonial_sections',
        'settings' => 'heading_for_testimonial',
        'type' => 'text',
    ));
    $wp_customize->selective_refresh->add_partial('testimonial_display_option', array(
        'selector' => '.testimonials-wrapper .container', // You can also select a css class
    ));

    $wp_customize->add_setting('testimonail_layouts', array(
        'sanitize_callback' => 'wep_sanitize_choices',
        'default' => '1',
    ));

    $wp_customize->add_control(new One_Page_Conference_Radio_Image_Control($wp_customize, 'testimonail_layouts', array(
        'label' => esc_html__('Select Layout', 'wp-event-partners'),
        'section' => 'one_page_conference_testimonial_sections',
        'settings' => 'testimonail_layouts',
        'type' => 'radio-image',
        'choices' => array(
            '1' => WEP_EVENT_URL . '/admin/assets/images/testimonials/layout-1.jpg',
            '2' => WEP_EVENT_URL . '/admin/assets/images/testimonials/layout-2.jpg',
            '3' => WEP_EVENT_URL . '/admin/assets/images/testimonials/layout-3.jpg',
            '4' => WEP_EVENT_URL . '/admin/assets/images/testimonials/layout-4.jpg',
        ),
    )));

}