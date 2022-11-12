<?php
/**
 * Banner Settings
 */

add_action( 'customize_register', 'one_page_conference_customize_register_banner_panel' );

function one_page_conference_customize_register_banner_panel( $wp_customize ) {
    $wp_customize->add_panel( 'one_page_conference_banner_panel', array(
        'priority'    => 10,
        'title'       => esc_html__( 'Event Information / Banner', 'one-page-conference' ),
    ) );
}