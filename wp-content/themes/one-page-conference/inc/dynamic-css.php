<?php
function one_page_conference_dynamic_css() {

        wp_enqueue_style(
            'one-page-conference-dynamic-css', get_template_directory_uri() . '/css/dynamic.css'
        );

        $header_bg_color = esc_attr( get_theme_mod( 'header_bg_color', '#ffffff' ) );
        $primary_color = esc_attr( get_theme_mod( 'primary_color', '#8c52ff' ) );
        $secondary_color = esc_attr( get_theme_mod( 'secondary_color', '#ffc014' ) );
        $text_color = get_theme_mod( 'text_color', '#757575' );
        $accent_color = get_theme_mod( 'accent_color', '#5278AD' );
        $light_color = get_theme_mod( 'light_color', '#ffffff ' );
        $dark_color = get_theme_mod( 'dark_color', '#111111 ' );
        $grey_color = get_theme_mod( 'grey_color', '#606060 ' );

        $header_height = absint( get_theme_mod( 'header_height', 15 ) );

        $banner_overlay_color = esc_attr( get_theme_mod( 'banner_overlay_color', '#8c52ff' ) );
        $banner_overlay_color_opacity = get_theme_mod( 'banner_overlay_color_opacity', 0.8 );

        $font_family = esc_attr( get_theme_mod( 'font_family', 'Roboto' ) );
        $font_size = esc_attr( get_theme_mod( 'font_size', '16px' ) );

        $logo_font_size = absint( get_theme_mod( 'logo_size', 30 ) );
        $logo_size = absint( $logo_font_size * 6 );
        $site_color = esc_attr( get_theme_mod( 'site_color', '#4169e1' ) );
        $site_identity_font_family = esc_attr( get_theme_mod( 'site_identity_font_family', 'Montserrat' ) );

        $heading_font_family = esc_attr( get_theme_mod( 'heading_font_family', 'Poppins' ) );

        $event_title_font_size = get_theme_mod( 'event_title_font_size', 40 );

        $default_size = array(
                '1' =>  32,
                '2' =>  28,
                '3' =>  24,
                '4' =>  21,
                '5' =>  15,
                '6' =>  12,
        );

        for( $i = 1; $i <= 6 ; $i++ ) {
            $heading[$i] = absint( get_theme_mod( 'one_page_conference_heading_' . $i . '_size', absint( $default_size[$i] ) ) );
        }

        



        $dynamic_css = "


                :root {
                        --header-background: $header_bg_color;
                        --primary-color: $primary_color;
                        --secondary-color: $secondary_color;
                        --text-color: $text_color;
                        --accent-color: $accent_color;
                        --light-color: $light_color;
                        --dark-color: $dark_color;
                        --grey-color: $grey_color;
                        // --font-family: $heading_font_family;
                }


                html,:root{ font: normal $font_size"." $font_family;}
                body{line-height: 1.5em; }


                header .custom-logo-link img{ width: {$logo_size}"."px; }

                .header{padding: {$header_height}" . "px 0;}

                /*Site Title*/
                header .site-title a{ font-size: $logo_font_size"."px; font-family: $site_identity_font_family; color: $site_color; }

                /* Banner Colors */
                section[class*=banner-layout-] .item:before { background: {$banner_overlay_color}; opacity: {$banner_overlay_color_opacity};}
                

                #banner .caption .title{
                        font-size: $event_title_font_size"."px;
                        font-family: $heading_font_family;
                }




                /* media */
                        @media (min-width: 320px) and (max-width: 1199px){
        
                        }

                        
                        @media (min-width: 320px) and (max-width: 767px){
                                [class*=schedule-layout-] .tab-content .nav-pills .nav-link.active{
                                        background: $primary_color;
                                }
                                [class*=schedule-layout-] .tab-content .tabContent-toggler{
                                        background: $secondary_color;
                                }
                        }                        

                /* end media */

               
        ";
        wp_add_inline_style( 'one-page-conference-dynamic-css', $dynamic_css );
}
add_action( 'wp_enqueue_scripts', 'one_page_conference_dynamic_css' ,'99');