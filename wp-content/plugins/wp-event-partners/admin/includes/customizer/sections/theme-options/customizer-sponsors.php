<?php
/**
 * Pages Settings
 *
 * @package One Page Conference
 */
add_action( 'customize_register', 'one_page_conference_customize_sponsors_section' );

function one_page_conference_customize_sponsors_section( $wp_customize )
{

    $wp_customize->add_section('one_page_conference_sponsor_sections', array(
        'title' => esc_html__('Sponsor Section', 'wp-event-partners'),
        'panel' => 'one_page_conference_theme_options_panel',
    ));

    $wp_customize->add_setting('sponsor_display_option', array(
        'sanitize_callback' => 'wep_sanitize_checkbox',
        'default' => true
    ));

    $wp_customize->add_control(new One_Page_Conference_Toggle_Control($wp_customize, 'sponsor_display_option', array(
        'label' => esc_html__('Hide / Show', 'wp-event-partners'),
        'section' => 'one_page_conference_sponsor_sections',
        'settings' => 'sponsor_display_option',
        'type' => 'toggle',
    )));

    $wp_customize->add_setting('heading_for_sponsor', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));

    $wp_customize->add_control('heading_for_sponsor', array(
        'label' => esc_html__('Enter Heading for Sponsor', 'wp-event-partners'),
        'section' => 'one_page_conference_sponsor_sections',
        'settings' => 'heading_for_sponsor',
        'type' => 'text',
    ));
    $wp_customize->selective_refresh->add_partial('sponsor_display_option', array(
        'selector' => '.sponsor-wrapper .container', // You can also select a css class
    ));

    $args = [
        'post_type' => 'sponsor',
        'numberposts' => -1
    ];
    $carousel_query = get_posts($args);
    $carousels = array();
    $default = array();

    foreach ($carousel_query as $carousel) {
        $carousels[$carousel->post_name] = $carousel->post_title;
        array_push( $default, $carousel->post_name );
    }


    $wp_customize->add_setting('choose_sponsors', array(
        'sanitize_callback' => 'wep_sanitize_array',
        'default' => $default,
    ));

    
    $wp_customize->add_control(new One_Page_Conference_Multi_Check_Control($wp_customize, 'choose_sponsors', array(
        'label' => esc_html__('Choose sponsor', 'wp-event-partners'),
        'section' => 'one_page_conference_sponsor_sections',
        'settings' => 'choose_sponsors',
        'type' => 'multi-check',
        'choices' => $carousels,
    )));
    $wp_customize->add_setting('one_page_conference_sponsor_layouts', array(
        'sanitize_callback' => 'wep_sanitize_choices',
        'default' => '1',
    ));

    $wp_customize->add_control(new One_Page_Conference_Radio_Image_Control($wp_customize, 'one_page_conference_sponsor_layouts', array(
        'label' => esc_html__('Select Layout For Sponsor Section', 'wp-event-partners'),
        'section' => 'one_page_conference_sponsor_sections',
        'settings' => 'one_page_conference_sponsor_layouts',
        'type' => 'radio-image',
        'choices' => array(
            '1' => WEP_EVENT_URL . '/admin/assets/images/sponsors/sponsor-layout-1.jpg',
            '2' => WEP_EVENT_URL . '/admin/assets/images/sponsors/sponsor-layout-2.jpg'
        ),
    )));

}