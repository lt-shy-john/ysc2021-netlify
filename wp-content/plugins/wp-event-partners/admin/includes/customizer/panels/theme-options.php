<?php
/**
 * Homepage Settings
 */

add_action( 'customize_register', 'one_page_conference_customize_register_theme_options_panel' );

function one_page_conference_customize_register_theme_options_panel( $wp_customize ) {
	$wp_customize->add_panel( 'one_page_conference_theme_options_panel', array(
	    'priority'    => 7,
	    'title'       => esc_html__( 'Event Controller', 'wp-event-partners' ),
	) );
}