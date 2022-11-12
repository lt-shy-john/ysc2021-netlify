<?php
    $layout = get_theme_mod( 'one_page_conference_banner_layouts', 'one' );

    $countdown_show_hide = get_theme_mod( 'hide_show_banner_countdown_timer', true );
    $date_show_hide = get_theme_mod( 'hide_show_banner_date', true );
    $venu_show_hide = get_theme_mod( 'hide_show_banner_venue', true );

    set_query_var( 'countdown_show_hide', $countdown_show_hide );
    set_query_var( 'date_show_hide', $date_show_hide );
    set_query_var( 'venu_show_hide', $venu_show_hide );

    if( $layout == 'one' ) {
        $class = "1 left";
        set_query_var( 'class', $class );
        get_template_part( 'layouts/banner/banner-layout', 'one' );
    }
    if( $layout == 'two' ) {
        $class = "1 center";
        set_query_var( 'class', $class );
        get_template_part( 'layouts/banner/banner-layout', 'one' );
    }
    if( $layout == 'three' ) {
        $class = "2 left";
        set_query_var( 'class', $class );
        get_template_part( 'layouts/banner/banner-layout', 'two' );
    }
    if( $layout == 'four' ) {
        $class = "2 right";
        set_query_var( 'class', $class );
        get_template_part( 'layouts/banner/banner-layout', 'two' );
    }
?>