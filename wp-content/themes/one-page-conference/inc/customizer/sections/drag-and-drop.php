<?php

/***
 * Drag & Drop Sections
 */
add_action( 'customize_register', 'one_page_conference_drag_and_drop_sections' );
function one_page_conference_drag_and_drop_sections( $wp_customize )
{
    $wp_customize->add_section( 'one_page_conference_sort_homepage_sections', array(
        'title'    => esc_html__( 'Drag & Drop', 'one-page-conference' ),
        'priority' => 12,
    ) );
    $choices = array(
        'about-us' => esc_html__( 'About Us', 'one-page-conference' ),
        'counter'  => esc_html__( 'Counter', 'one-page-conference' ),
    );
    $default = array( 'about-us', 'counter' );
    
    if ( class_exists( 'WepPlugin' ) ) {
        $choices['sponsors'] = esc_html__( 'Sponsors', 'one-page-conference' );
        $choices['speakers'] = esc_html__( 'Speakers', 'one-page-conference' );
        $choices['schedules'] = esc_html__( 'Schedules', 'one-page-conference' );
        $choices['venue'] = esc_html__( 'Venue', 'one-page-conference' );
        $choices['testimonials'] = esc_html__( 'Testimonials', 'one-page-conference' );
        $choices['organizers'] = esc_html__( 'Organizers', 'one-page-conference' );
        array_push(
            $default,
            'sponsors',
            'speakers',
            'schedules',
            'venue',
            'testimonials',
            'organizers'
        );
    }
    
    
    if ( class_exists( 'Wp_Event_Tickets' ) ) {
        $choices['pricing'] = esc_html__( 'Pricing', 'one-page-conference' );
        array_push( $default, 'pricing' );
    }
    
    $wp_customize->add_setting( 'one_page_conference_sort_homepage', array(
        'sanitize_callback' => 'one_page_conference_sanitize_array',
        'default'           => $default,
    ) );
    $wp_customize->add_control( new One_Page_Conference_Control_Sortable( $wp_customize, 'one_page_conference_sort_homepage', array(
        'label'    => esc_html__( 'Drag and Drop Sections to rearrange.', 'one-page-conference' ),
        'section'  => 'one_page_conference_sort_homepage_sections',
        'settings' => 'one_page_conference_sort_homepage',
        'type'     => 'sortable',
        'choices'  => $choices,
    ) ) );
}
