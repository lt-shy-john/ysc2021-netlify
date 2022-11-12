<?php

/**
 * One Page Conference functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package One_Page_Conference
 */




if ( !function_exists( 'one_page_conference_setup' ) ) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function one_page_conference_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on One Page Conference, use a find and replace
         * to change 'one-page-conference' to the name of your theme in all the template files.
         */
        load_theme_textdomain( 'one-page-conference' );
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );
        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );
        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );
        // This theme uses wp_nav_menu() in one location.
        register_nav_menus( array(
            'main-menu' => esc_html__( 'Main Menu', 'one-page-conference' ),
        ) );
        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        ) );
        // Set up the WordPress core custom background feature.
        add_theme_support( 'custom-background', apply_filters( 'one_page_conference_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );
        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );
        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support( 'custom-logo', array(
            'height'      => 120,
            'width'       => 400,
            'flex-width'  => true,
            'flex-height' => true,
            'class'       => 'navbar-band',
        ) );
        add_editor_style();
    }

}
add_action( 'after_setup_theme', 'one_page_conference_setup' );
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function one_page_conference_content_width()
{
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters( 'one_page_conference_content_width', 640 );
}

add_action( 'after_setup_theme', 'one_page_conference_content_width', 0 );
/**
 * Enqueue scripts and styles.
 */
function one_page_conference_scripts()
{
    $font_family = get_theme_mod( 'font_family', 'Montserrat' );
    $heading_font_family = get_theme_mod( 'heading_font_family', 'Poppins' );
    $site_identity_font_family = esc_attr( get_theme_mod( 'site_identity_font_family', 'Montserrat' ) );
    wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css' );
    wp_enqueue_style( 'fontawesome-css', get_template_directory_uri() . '/css/fontawesome.css' );
    wp_enqueue_style( 'one-page-conference-googlefonts', 'https://fonts.googleapis.com/css?family=' . esc_attr( $font_family ) . ':200,300,400,500,600,700,800,900|' . esc_attr( $heading_font_family ) . ':200,300,400,500,600,700,800,900|' . esc_attr( $site_identity_font_family ) . ':200,300,400,500,600,700,800,900|' );
    wp_enqueue_style(
        'owl-carousel',
        get_template_directory_uri() . '/css/owl.carousel.min.css',
        array(),
        '2.3.4'
    );
    wp_enqueue_style( 'one-page-conference-style', get_stylesheet_uri() );
    wp_enqueue_script(
        'one-page-conference-navigation',
        get_template_directory_uri() . '/js/navigation.js',
        array(),
        "",
        true
    );
    wp_enqueue_script(
        'one-page-conference-skip-link-focus-fix',
        get_template_directory_uri() . '/js/skip-link-focus-fix.js',
        array(),
        "",
        true
    );
    wp_enqueue_script(
        'owl.carousel',
        get_template_directory_uri() . '/js/owl.carousel.js',
        array( 'jquery' ),
        '2.3.4',
        true
    );
    wp_enqueue_script(
        'one-page-conference-script',
        get_template_directory_uri() . '/js/script.js',
        array( 'jquery' ),
        '',
        true
    );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    $home_url = home_url();
    $home = array(
        'homePage' => $home_url,
    );
    wp_localize_script( 'one-page-conference-script', 'home_url', $home );
    if ( is_front_page() ) {
        wp_register_script(
            'event-frontend-script',
            get_template_directory_uri() . '/js/event-frontend.js',
            array( 'jquery' ),
            '20151215',
            true
        );
    }
    $event_date = get_theme_mod( 'start_date', '2021-02-20' );
    $event_time = get_theme_mod( 'start_time', '10:00 AM' );
    $var = array(
        'date' => $event_date,
        'time' => $event_time,
    );
    wp_localize_script( 'event-frontend-script', 'event_var', $var );
    wp_enqueue_script( 'event-frontend-script' );
}

add_action( 'wp_enqueue_scripts', 'one_page_conference_scripts' );
/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/custom-controls/custom-control.php';
/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';
/**
 * Customizer changes css
 */
require get_template_directory() . '/inc/dynamic-css.php';
// Register Custom Navigation Walker
require get_template_directory() . '/inc/wp_bootstrap_navwalker.php';
/**
 * Call Widget page
 **/
require get_template_directory() . '/inc/widgets/widgets.php';
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';
/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
/**
 * Recommended Plugins
 */
require get_template_directory() . '/inc/TGMPA/class-tgm-plugin-activation.php';
/**
 * For Admin Page
 */

if ( is_admin() ) {
    require get_template_directory() . '/inc/getting-started/getting-started.php';
    require get_template_directory() . '/inc/about/about.php';
}

require get_template_directory() . '/inc/upgrade-to-pro/class-customize.php';
/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
    require get_template_directory() . '/inc/jetpack.php';
}
function one_page_conference_social_media()
{
    $social_media = array(
        'Facebook'  => esc_html__( 'Facebook', 'one-page-conference' ),
        'Twitter'   => esc_html__( 'Twitter', 'one-page-conference' ),
        'Pinterest' => esc_html__( 'Pinterest', 'one-page-conference' ),
        'GPlus'     => esc_html__( 'Google Plus', 'one-page-conference' ),
        'Instagram' => esc_html__( 'Instagram', 'one-page-conference' ),
        'Youtube'   => esc_html__( 'Youtube', 'one-page-conference' ),
    );
    return $social_media;
}

function one_page_conference_load_more_scripts()
{
    $args = array(
        'post_type' => 'post',
        'paged'     => ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 ),
    );
    $wp_query = new WP_Query( $args );
    wp_register_script( 'one_page_conference_loadmore', get_template_directory_uri() . '/js/loadmore.js', array( 'jquery' ) );
    wp_localize_script( 'one_page_conference_loadmore', 'one_page_conference_loadmore_params', array(
        'ajaxurl'      => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
        'current_page' => ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 ),
        'max_page'     => $wp_query->max_num_pages,
    ) );
    wp_reset_postdata();
    wp_enqueue_script( 'one_page_conference_loadmore' );
}

add_action( 'wp_enqueue_scripts', 'one_page_conference_load_more_scripts' );
function one_page_conference_load_more_ajax()
{
    if ( isset( $_POST['page'] ) ) {
        $args['paged'] = absint( $_POST['page'] + 1 );
    }
    $args['post_status'] = esc_html( 'publish' );
    $wp_query = new WP_Query( $args );
    if ( $wp_query->have_posts() ) {
        while ( $wp_query->have_posts() ) {
            $wp_query->the_post();
            get_template_part( 'template-parts/content' );
        }
    }
    die;
    // here we exit the script and even no wp_reset_query() required!
}

add_action( 'wp_ajax_one_page_conference_loadmore', 'one_page_conference_load_more_ajax' );
add_action( 'wp_ajax_nopriv_one_page_conference_loadmore', 'one_page_conference_load_more_ajax' );
function one_page_conference_excerpt( $limit )
{
    $excerpt = explode( ' ', get_the_excerpt(), $limit );
    if ( count( $excerpt ) >= $limit ) {
        array_pop( $excerpt );
    }
    $excerpt = implode( " ", $excerpt ) . '...';
    $excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );
    return $excerpt;
}

function one_page_conference_skip_link_focus_fix()
{
    // The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
    ?>
    <script>
    /(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
    </script>
    <?php 
}

add_action( 'wp_print_footer_scripts', 'one_page_conference_skip_link_focus_fix' );
add_action( 'tgmpa_register', 'one_page_conference_register_required_plugins', 15 );
function one_page_conference_register_required_plugins()
{
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        array(
        'name'     => esc_html__( 'WPEventPartners', 'one-page-conference' ),
        'slug'     => 'wp-event-partners',
        'required' => false,
    ),
        array(
        'name'     => esc_html__( 'Advanced Import', 'one-page-conference' ),
        'slug'     => 'advanced-import',
        'required' => false,
    ),
        array(
        'name'     => esc_html__( 'WPEventPartners Demo Import', 'one-page-conference' ),
        'slug'     => 'wep-demo-import',
        'required' => false,
    ),
        array(
        'name'     => esc_html__( 'Event Registration and Tickets', 'one-page-conference' ),
        'slug'     => 'wp-event-tickets',
        'required' => false,
    ),
        array(
        'name'     => esc_html__( 'Woocommerce', 'one-page-conference' ),
        'slug'     => 'woocommerce',
        'required' => false,
    )
    );
    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'one-page-conference',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'themes.php',
        'capability'   => 'edit_theme_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => true,
        'message'      => '',
    );
    tgmpa( $plugins, $config );
}

function one_page_conference_check_video_source( $url )
{
    
    if ( strpos( $url, 'youtube' ) > 0 ) {
        return 'youtube';
    } elseif ( strpos( $url, 'vimeo' ) > 0 ) {
        return 'vimeo';
    } else {
        return 'unknown';
    }

}

function one_page_conference_get_youtube_embedded_url( $url )
{
    $shortUrlRegex = '/youtu.be\\/([a-zA-Z0-9_]+)\\??/i';
    $longUrlRegex = '/youtube.com\\/((?:embed)|(?:watch))((?:\\?v\\=)|(?:\\/))(\\w+)/i';
    if ( preg_match( $longUrlRegex, $url, $matches ) ) {
        $youtube_id = $matches[count( $matches ) - 1];
    }
    if ( preg_match( $shortUrlRegex, $url, $matches ) ) {
        $youtube_id = $matches[count( $matches ) - 1];
    }
    return 'https://www.youtube.com/embed/' . $youtube_id;
}

function one_page_conference_get_vimeo_embedded_url( $url = '' )
{
    $regs = array();
    $id = '';
    if ( preg_match( '%^https?:\\/\\/(?:www\\.|player\\.)?vimeo.com\\/(?:channels\\/(?:\\w+\\/)?|groups\\/([^\\/]*)\\/videos\\/|album\\/(\\d+)\\/video\\/|video\\/|)(\\d+)(?:$|\\/|\\?)(?:[?]?.*)$%im', $url, $regs ) ) {
        $id = $regs[3];
    }
    return 'https://player.vimeo.com/video/' . $id;
}
