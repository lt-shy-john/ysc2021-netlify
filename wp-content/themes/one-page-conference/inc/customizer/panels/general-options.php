<?php
/**
 * General Settings
 */

add_action( 'customize_register', 'one_page_conference_customize_register_general_panel' );

function one_page_conference_customize_register_general_panel( $wp_customize ) {
	$wp_customize->add_panel( 'one_page_conference_general_panel', array(
	    'priority'    => 10,
	    'title'       => esc_html__( 'General Options', 'one-page-conference' ),
	    'description' => esc_html__( 'General Options', 'one-page-conference' ),
	) );

}