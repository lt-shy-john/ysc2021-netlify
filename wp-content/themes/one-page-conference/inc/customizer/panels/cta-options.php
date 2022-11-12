<?php
/**
 * CTA Settings
 */

add_action( 'customize_register', 'one_page_conference_customize_register_cta_panel' );

function one_page_conference_customize_register_cta_panel( $wp_customize ) {
	$wp_customize->add_panel( 'one_page_conference_cta_panel', array(
	    'priority'    => 12,
	    'title'       => esc_html__( 'CTA Options', 'one-page-conference' ),
	) );
}