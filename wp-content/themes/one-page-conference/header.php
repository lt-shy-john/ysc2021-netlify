<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package One_Page_Conference
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'one-page-conference' ); ?></a>
	<div id="page" class="site">
        <?php
            $layout = get_theme_mod( 'one_page_conference_header_layouts', 'one' );
            if( $layout == 'one' ) {
                $class = "1";
            }
            if( $layout == 'two' ) {
                $class = "2";
            }
            if( $layout == 'three' ) {
                $class = "3";
            }
            if( $layout == 'four' ) {
                $class = "4";
            }
            set_query_var( 'class', $class );
            get_template_part( 'layouts/header/header-layout', 'one' );

        ?>



	<div id="content" class="site-content">

        <?php

            $classes = get_body_class();
            if (in_array('home',$classes)) {
                $banner_display_in_homepage = get_theme_mod('banner_display_in_homepage', true );
                if($banner_display_in_homepage == '1'){
                    get_template_part( 'template-parts/banner' );
                }   
            }
            else{
                $banner_display_in_otherpage = get_theme_mod('banner_display_in_otherpage', true );
                if($banner_display_in_otherpage == '1'){
                    get_template_part( 'template-parts/banner' );
                }
            }
