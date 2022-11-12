<?php
function wep_demo_import_get_current_theme_author(){
    $current_theme = wp_get_theme();
    return $current_theme->get('Author');
}
function wep_plugin_check_activated(){
    $pluginList = get_option( 'active_plugins' );
    $wep_plugin = 'wp-event-partners/wpeventpartners.php'; 
    $checkPlugin = in_array( $wep_plugin , $pluginList );
    // print_r($checkPlugin);
    return $checkPlugin;
}
function wep_plugin_file_exists(){
    $wep_plugin = 'wp-event-partners/wpeventpartners.php'; 
    $pathpluginurl = WP_PLUGIN_DIR .'/'. $wep_plugin;
    $isinstalled = file_exists( $pathpluginurl );
    return $isinstalled;
}
function wep_demo_import_get_current_theme_slug(){
    $current_theme = wp_get_theme();
    return $current_theme->stylesheet;
}
function wep_demo_import_get_theme_screenshot(){
    $current_theme = wp_get_theme();
    return $current_theme->get_screenshot();
}
function wep_demo_import_get_theme_name(){
    $current_theme = wp_get_theme();
    return $current_theme->get('Name');
}

function wep_demo_import_get_templates_lists( $theme_slug ){
    switch ( $theme_slug ):    
        case "one-page-conference":
        case "one-page-conference-pro":
            $demo_templates_lists = array(

                'startup-day' =>array(
                    'title' => __( 'Startup Day', 'one-page-conference' ),/*Title*/
                    'is_pro' => false,  /*Premium*/
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),    /*Author Name*/
                    'keywords' => array( 'Startup Day'),  /*Search keyword*/
                    'categories' => array( 'one-page-conference' ), /*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/1/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/1/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/1/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/1/startup-day.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/startup-day/',
                    'plugins' => ''
                ),


                'design-camp' =>array(
                    'title' => __( 'Design Camp', 'one-page-conference' ),/*Title*/
                    'is_pro' => false,  /*Premium*/
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),/*Author Name*/
                    'keywords' => array( 'Design Camp' ),/*Search keyword*/
                    'categories' => array( 'one-page-conference' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/2/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/2/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/2/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/2/design-camp.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/design-camp/',
                    'plugins' => ''
                ),

                'tedx-talk' =>array(
                    'title' => __( 'TEDX Talk', 'one-page-conference' ),/*Title*/
                    'is_pro' => false,  /*Premium*/
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),/*Author Name*/
                    'keywords' => array( 'Tedx Talk' ),/*Search keyword*/
                    'categories' => array( 'one-page-conference' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/3/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/3/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/3/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/3/tedx-talk.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/tedx-talk/',
                    'plugins' => ''
                ),

                'code-camp' =>array(
                    'title' => __( 'Code Camp Pro', 'one-page-conference' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/one-page-conference-pro/',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),/*Author Name*/
                    'keywords' => array( 'Code Camp' ),/*Search keyword*/
                    'categories' => array( 'one-page-conference-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/4/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/4/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/4/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/4/code-camp.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/code-camp-pro/',
                    'plugins' => ''
                ),

                'economy-forum' =>array(
                    'title' => __( 'Economy Forum Pro', 'one-page-conference' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/one-page-conference-pro/',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),/*Author Name*/
                    'keywords' => array( 'Economy Forum' ),/*Search keyword*/
                    'categories' => array( 'one-page-conference-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/5/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/5/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/5/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/5/economy-forum.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/economy-forum-pro/',
                    'plugins' => ''
                ),

                'agility-conclave' =>array(
                    'title' => __( 'Agility Conclave Pro', 'one-page-conference' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/one-page-conference-pro/',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),/*Author Name*/
                    'keywords' => array( 'Agility Conclave' ),/*Search keyword*/
                    'categories' => array( 'one-page-conference-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/6/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/6/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/6/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/6/agility-conclave.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/agility-conclave/',
                    'plugins' => ''
                ),

                'scale-conference' =>array(
                    'title' => __( 'Scale Conference Pro', 'one-page-conference' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/one-page-conference-pro/',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),/*Author Name*/
                    'keywords' => array( 'Scale Conference' ),/*Search keyword*/
                    'categories' => array( 'one-page-conference-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/7/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/7/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/7/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/7/scale-conference.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/scale-conference-pro/',
                    'plugins' => ''
                ),

                

            );
        break;
        case "next-event":
            $demo_templates_lists = array(

                'next-event' =>array(
                    'title' => __( 'Next Event', 'one-page-conference' ),/*Title*/
                    'is_pro' => false,  /*Premium*/
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),    /*Author Name*/
                    'keywords' => array( 'Next Event'),  /*Search keyword*/
                    'categories' => array( 'next-event' ), /*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/next-event/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/next-event/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/next-event/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/next-event/screenshot.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/next-event/',
                    'plugins' => ''
                ),
                
                'code-camp' =>array(
                    'title' => __( 'Code Camp Pro', 'one-page-conference' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/one-page-conference-pro/',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),/*Author Name*/
                    'keywords' => array( 'Code Camp' ),/*Search keyword*/
                    'categories' => array( 'one-page-conference-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/4/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/4/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/4/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/4/code-camp.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/code-camp-pro/',
                    'plugins' => ''
                ),

                'economy-forum' =>array(
                    'title' => __( 'Economy Forum Pro', 'one-page-conference' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/one-page-conference-pro/',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),/*Author Name*/
                    'keywords' => array( 'Economy Forum' ),/*Search keyword*/
                    'categories' => array( 'one-page-conference-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/5/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/5/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/5/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/5/economy-forum.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/economy-forum-pro/',
                    'plugins' => ''
                ),

                'agility-conclave' =>array(
                    'title' => __( 'Agility Conclave Pro', 'one-page-conference' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/one-page-conference-pro/',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),/*Author Name*/
                    'keywords' => array( 'Agility Conclave' ),/*Search keyword*/
                    'categories' => array( 'one-page-conference-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/6/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/6/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/6/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/6/agility-conclave.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/agility-conclave/',
                    'plugins' => ''
                ),

                'scale-conference' =>array(
                    'title' => __( 'Scale Conference Pro', 'one-page-conference' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/one-page-conference-pro/',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'one-page-conference' ),/*Author Name*/
                    'keywords' => array( 'Scale Conference' ),/*Search keyword*/
                    'categories' => array( 'one-page-conference-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/7/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/7/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/7/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/one-page-conference/7/scale-conference.jpg',
                    'demo_url' => 'https://demo.wpeventpartners.com/scale-conference-pro/',
                    'plugins' => ''
                ),
                

            );
        break;

        case "corporate-event":
        case "corporate-event-pro":

            $demo_templates_lists = array(

                'corporate-event-free' =>array(
                    'title' => __( 'Corporate Event', 'corporate-event' ),/*Title*/
                    'is_pro' => false,  /*Premium*/
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'corporate-event' ),    /*Author Name*/
                    'keywords' => array( 'Corporate Event'),  /*Search keyword*/
                    'categories' => array( 'corporate-event' ), /*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/1/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/1/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/1/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/1/screenshot.jpg',
                    'demo_url' => 'https://wpeventpartners.com/preview/?product_id=CorporateEvents',
                    'plugins' => ''
                ),


                'corporate-event-pro-1' =>array(
                    'title' => __( 'Corporate Event Pro 1', 'corporate-event' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/layouts/corporate-events-pro',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'corporate-event' ),/*Author Name*/
                    'keywords' => array( 'Corporate Event' ),/*Search keyword*/
                    'categories' => array( 'corporate-event-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/2/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/2/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/2/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/2/screenshot.jpg',
                    'demo_url' => 'https://wpeventpartners.com/preview/?product_id=CorporateEventsPro-1',
                    'plugins' => ''
                ),
                'corporate-event-pro-2' =>array(
                    'title' => __( 'Corporate Event Pro 2', 'corporate-event' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/layouts/corporate-events-pro',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'corporate-event' ),/*Author Name*/
                    'keywords' => array( 'Corporate Event' ),/*Search keyword*/
                    'categories' => array( 'corporate-event-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/3/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/3/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/3/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/3/screenshot.jpg',
                    'demo_url' => 'https://wpeventpartners.com/preview/?product_id=CorporateEventsPro-2',
                    'plugins' => ''
                ),
                'corporate-event-pro-3' =>array(
                    'title' => __( 'Corporate Event Pro 3', 'corporate-event' ),/*Title*/
                    'is_pro' => true,  /*Premium*/
                    'pro_url' => 'https://wpeventpartners.com/layouts/corporate-events-pro',
                    'type' => 'normal',
                    'author' => __( 'WPEventPartners', 'corporate-event' ),/*Author Name*/
                    'keywords' => array( 'Corporate Event' ),/*Search keyword*/
                    'categories' => array( 'corporate-event-pro' ),/*Categories*/
                    'template_url' => array(
                        'content' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/4/content.json',
                        'options' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/4/options.json',
                        'widgets' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/4/widgets.json'
                    ),
                    'screenshot_url' => WEP_DEMO_IMPORT_TEMPLATE_URL.'/corporate-event/4/screenshot.jpg',
                    'demo_url' => 'https://wpeventpartners.com/preview/?product_id=CorporateEventsPro-3',
                    'plugins' => ''
                ),
            );

        break;        

        default:
            $demo_templates_lists = array();
    endswitch;

    return $demo_templates_lists;

}
