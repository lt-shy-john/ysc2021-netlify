<?php
if( ! function_exists( 'One_Page_Conference_register_custom_controls' ) ) :
/**
 * Register Custom Controls
*/
function One_Page_Conference_register_custom_controls( $wp_customize ) {
    
    // Load our custom control.
    include_once dirname(__FILE__) .'/radiobtn/class-radio-buttonset-control.php';
    include_once dirname(__FILE__) .'/radioimg/class-radio-image-control.php';
    include_once dirname(__FILE__) .'/select/class-select-control.php';
    include_once dirname(__FILE__) .'/slider/class-slider-control.php';
    include_once dirname(__FILE__) .'/toggle/class-toggle-control.php';
    include_once dirname(__FILE__) .'/repeater/class-repeater-setting.php';
    include_once dirname(__FILE__) .'/repeater/class-control-repeater.php';
    include_once dirname(__FILE__) .'/sortable/class-sortable-control.php';
    include_once dirname(__FILE__) .'/dropdown-taxonomies/class-dropdown-taxonomies-control.php';
    include_once dirname(__FILE__) .'/posttype-taxonomies/class-post-type-taxonomies-control.php';
    include_once dirname(__FILE__) .'/multicheck/class-multi-check-control.php';


    include("notes.php");
//    require_once get_template_directory() . '/inc/custom-controls/notes.php';
            
    // Register the control type.
    $wp_customize->register_control_type( 'One_Page_Conference_Radio_Buttonset_Control' );
    $wp_customize->register_control_type( 'One_Page_Conference_Radio_Image_Control' );
    $wp_customize->register_control_type( 'One_Page_Conference_Select_Control' );
    $wp_customize->register_control_type( 'One_Page_Conference_Slider_Control' );
    $wp_customize->register_control_type( 'One_Page_Conference_Toggle_Control' );    
    $wp_customize->register_control_type( 'One_Page_Conference_Control_Sortable' );
    $wp_customize->register_control_type( 'One_Page_Conference_Multi_Check_Control' );
}

add_action( 'customize_register', 'One_Page_Conference_register_custom_controls' );

endif;