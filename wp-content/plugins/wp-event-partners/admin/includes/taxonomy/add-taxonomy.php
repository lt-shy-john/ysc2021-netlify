<?php

add_action( 'init', 'wep_create_session_type' );

function wep_create_session_type() {
    register_taxonomy(
        'session_type',
        'session',
        array(
            'label' => __( 'Session type' ),
            'rewrite' => array( 'slug' => 'session_type' ),
            'hierarchical' => true,
            'type'          => 'image',
        )
    );
}