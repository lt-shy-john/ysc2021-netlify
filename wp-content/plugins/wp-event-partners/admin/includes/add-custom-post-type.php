<?php
/**
 * Creastod by PhpStorm.
 * Date: 9/25/2019
 * Time: 1:03 PM
 */

// Speaker custom post type function
function wep_speaker_posttype() {

    register_post_type( 'speaker',
// CPT Options
        array(
            'labels' => array(
                'name' => __( 'Speakers' ),
                'singular_name' => __( 'speaker' )
            ),
            'public' => true,
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'speaker'),
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt')
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'wep_speaker_posttype' );


// Sponsor custom post type function
function wep_sponsor_posttype() {

    register_post_type( 'sponsor',
// CPT Options
        array(
            'labels' => array(
                'name' => __( 'Sponsors' ),
                'singular_name' => __( 'sponsor' )
            ),
            'public' => true,
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'sponsor'),
            'supports' => array( 'title', 'editor', 'thumbnail')
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'wep_sponsor_posttype' );

// session custom post type function
function wep_session_posttype() {

    register_post_type( 'session',
// CPT Options
        array(
            'labels' => array(
                'name' => __( 'Sessions' ),
                'singular_name' => __( 'session' )
            ),
            'public' => true,
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'session'),
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt')
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'wep_session_posttype' );


// room custom post type function
function wep_room_posttype() {

    register_post_type( 'room',
// CPT Options
        array(
            'labels' => array(
                'name' => __( 'Rooms' ),
                'singular_name' => __( 'room' )
            ),
            'public' => true,
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'room'),
            'supports' => array( 'title', 'editor', 'thumbnail', )
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'wep_room_posttype' );


// Testimonial custom post type function
function wep_testimonial_posttype() {

    register_post_type( 'testimonial',
// CPT Options
        array(
            'labels' => array(
                'name' => __( 'Testimonials' ),
                'singular_name' => __( 'testimonial' )
            ),
            'public' => true,
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'testimonial'),
            'supports' => array( 'title', 'editor', 'thumbnail')
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'wep_testimonial_posttype' );

// Organizer Profile custom post type function
function wep_organizer_posttype() {

    register_post_type( 'wep_organizer',
// CPT Options
        array(
            'labels' => array(
                'name' => __( 'Organizer Profile' ),
                'singular_name' => __( 'organizer' )
            ),
            'public' => true,
            'show_in_menu' => 'false',
            'rewrite' => array('slug' => 'organizer'),
            'supports' => array( 'title', 'editor', 'thumbnail')
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'wep_organizer_posttype' );


