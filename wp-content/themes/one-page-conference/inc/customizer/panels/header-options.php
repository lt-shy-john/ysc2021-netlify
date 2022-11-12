<?php
/**
 * Header Settings
 */

add_action( 'customize_register', 'one_page_conference_customize_register_header_panel' );

function one_page_conference_customize_register_header_panel( $wp_customize ) {
	$wp_customize->add_panel( 'one_page_conference_header_panel', array(
	    'priority'    => 10,
	    'title'       => esc_html__( 'Header Options', 'one-page-conference' ),
	    'description' => esc_html__( 'Header Options', 'one-page-conference' ),
	) );
}