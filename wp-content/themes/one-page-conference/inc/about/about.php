<?php

/**
 * Added One Page Conference Page.
*/
/**
 * Add a new page under Appearance
 */
function one_page_conference_menu()
{
    add_theme_page(
        __( 'Get Started', 'one-page-conference' ),
        __( 'Get Started', 'one-page-conference' ),
        'edit_theme_options',
        'one-page-conference-get-started',
        'one_page_conference_page'
    );
}

add_action( 'admin_menu', 'one_page_conference_menu' );
/**
 * Enqueue styles for the help page.
 */
function one_page_conference_admin_scripts( $hook )
{
    if ( 'appearance_page_one-page-conference-get-started' !== $hook ) {
        return;
    }
    wp_enqueue_style(
        'one-page-conference-admin-style',
        get_template_directory_uri() . '/inc/about/about.css',
        array(),
        ''
    );
}

add_action( 'admin_enqueue_scripts', 'one_page_conference_admin_scripts' );
/**
 * Add the theme page
 */
function one_page_conference_page()
{
    ?>
	<div class="das-wrap">
		<div class="one-page-conference-panel">
			<div class="one-page-conference-logo">
				<img class="ts-logo" width="250" src="<?php 
    echo  esc_url( get_template_directory_uri() . '/inc/about/images/wpeventpartners.png' ) ;
    ?>" alt="Logo">
			</div>
			<?php 
    ?>
				<a href="https://wpeventpartners.com/one-page-conference-pro/" target="_blank" class="btn btn-success pull-right"><?php 
    esc_html_e( 'Upgrade to Pro $59.99', 'one-page-conference' );
    ?></a>
			<?php 
    ?>
			<p>
			<?php 
    esc_html_e( 'One page Conference is a free WordPress theme for events, conferences, conclaves, and meetups. This theme is based on the Event and Conference Management WordPress theme, WPEventPartners. If you are the organizer of the conference and looking to build a one-page conference website then this is the best theme for you.', 'one-page-conference' );
    ?></p>
			<a class="btn btn-primary" href="<?php 
    echo  esc_url( admin_url( '/customize.php?' ) ) ;
    ?>"><?php 
    esc_html_e( 'Theme Options - Click Here', 'one-page-conference' );
    ?></a>
		</div>

		<div class="one-page-conference-panel">
			<div class="one-page-conference-panel-content">
				<div class="theme-title">
					<h3><?php 
    esc_html_e( 'Once you install all recommended plugins, you can import demo template.', 'one-page-conference' );
    ?></h3>
				</div>
				<a class="btn btn-secondary" href="<?php 
    echo  esc_url( admin_url( '/themes.php?page=advanced-import' ) ) ;
    ?>"><?php 
    esc_html_e( 'View Demo Templates', 'one-page-conference' );
    ?></a>
			</div>
		</div>
		<div class="one-page-conference-panel">
			<div class="one-page-conference-panel-content">
				<div class="theme-title">
					<h4><?php 
    esc_html_e( 'If you like the theme, please leave a review or Contact us for technical support.', 'one-page-conference' );
    ?></h4>
				</div>
				<a href="https://wordpress.org/support/theme/one-page-conference/reviews/#new-post" target="_blank" class="btn btn-secondary"><?php 
    esc_html_e( 'Rate this theme', 'one-page-conference' );
    ?></a> <a href="https://wpeventpartners.com/support/" target="_blank" class="btn btn-secondary"><?php 
    esc_html_e( 'Contact Us', 'one-page-conference' );
    ?></a>
			</div>
		</div>
	</div>
	<?php 
}
