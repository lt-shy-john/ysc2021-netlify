<?php
/**
 * Pages Settings
 *
 * @package One Page Conference
 */
add_action( 'customize_register', 'one_page_conference_customize_about_us_section' );

function one_page_conference_customize_about_us_section( $wp_customize )
{

    $wp_customize->add_section('one_page_conference_about_us_sections', array(
        'title' => esc_html__('About Us Section', 'one-page-conference'),
        'description' => esc_html__('About Us Section :', 'one-page-conference'),
        'priority' => 10
    ));

    $wp_customize->add_setting('about_us_display_option', array(
        'sanitize_callback' => 'one_page_conference_sanitize_checkbox',
        'default' => true
    ));

    $wp_customize->add_control(new One_Page_Conference_Toggle_Control($wp_customize, 'about_us_display_option', array(
        'label' => esc_html__('Hide / Show', 'one-page-conference'),
        'section' => 'one_page_conference_about_us_sections',
        'settings' => 'about_us_display_option',
        'type' => 'toggle',
    )));

    $wp_customize->add_setting('heading_for_about_us', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));

    $wp_customize->add_control('heading_for_about_us', array(
        'label' => esc_html__('Enter Heading for About Us', 'one-page-conference'),
        'section' => 'one_page_conference_about_us_sections',
        'settings' => 'heading_for_about_us',
        'type' => 'text',
    ));

    $wp_customize->selective_refresh->add_partial('about_us_display_option', array(
        'selector' => '#about .container', // You can also select a css class
    ));

    $wp_customize->add_setting('about_us_pages', array(
        'sanitize_callback' => 'one_page_conference_sanitize_select',
        'default' => ''
    ));
    $page_query = get_pages();
    $pages = array();
    $pages[''] = esc_attr__("-- Select --", 'one-page-conference');
    foreach ($page_query as $page) {
        $pages[$page->post_name] = $page->post_title;
    }

    $wp_customize->add_control(new One_Page_Conference_Select_Control($wp_customize, 'about_us_pages', array(
        'label' => esc_html__('Select Page For About Us Section :', 'one-page-conference'),
        'section' => 'one_page_conference_about_us_sections',
        'settings' => 'about_us_pages',
        'type' => 'select',
        'choices' => $pages,
    )));
    $wp_customize->add_setting( 'one_page_conference_about_us_layout', array(
        'sanitize_callback' => 'one_page_conference_sanitize_choices',
        'default'     => '1',
    ) );
    $wp_customize->add_control(new One_Page_Conference_Radio_Image_Control($wp_customize, 'one_page_conference_about_us_layout', array(
        'label' => esc_html__('Select Layout For About Us Section', 'one-page-conference'),
        'section' => 'one_page_conference_about_us_sections',
        'settings' => 'one_page_conference_about_us_layout',
        'type' => 'radio-image',
        'choices' => array(
            '1' => get_template_directory_uri() . '/images/about-layouts/about-1.png',
            '2' => get_template_directory_uri() . '/images/about-layouts/about-2.png',
            '3' => get_template_directory_uri() . '/images/about-layouts/about-3.png',
            '4' => get_template_directory_uri() . '/images/about-layouts/about-4.png',
        ),
    )));


}

