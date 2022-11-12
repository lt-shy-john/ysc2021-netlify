<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       as
 * @since      1.0.0
 *
 * @package    Wep_Demo_Import
 * @subpackage Wep_Demo_Import/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wep_Demo_Import
 * @subpackage Wep_Demo_Import/includes
 * @author     s <sjunee5@gmail.com>
 */
class Wep_Demo_Import {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wep_Demo_Import_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Full Name of plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_full_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_full_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;


	/**
	 * Main Instance
	 *
	 * Insures that only one instance of Wep_Demo_Import exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return object
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new Wep_Demo_Import;
		}

		// Always return the instance
		return $instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		if ( defined( 'WEP_DEMO_IMPORT_VERSION' ) ) {
			$this->version = WEP_DEMO_IMPORT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wep-demo-import';
		$this->plugin_full_name = esc_html__('Wep Demo Import','wep-demo-import');

		$this->load_dependencies();
		$this->set_locale();
		$pluginList = wep_plugin_check_activated();

		if ( wep_demo_import_get_current_theme_author() == 'WPEventPartners' && $pluginList == '1') {
			$this->define_hooks();
			$this->load_hooks();
		}
		else {
			add_action( 'admin_notices', array( $this, 'wep_demo_import_missing_notice' ) );
		}

		$this->define_hooks();
		$this->load_hooks();
		

	}

	/**
	 * Since the plugin is created specially for One page Conference Themes
	 * Show notice if One page Conference Themes theme is not installed/activated
	 *
	 * @since    1.0.0
	 */
	public function wep_demo_import_missing_notice() {

		if ( wep_demo_import_get_current_theme_author() != 'WPEventPartners' ){

		$search_url = in_array( 'one-page-conference', array_keys( wp_get_themes()), true ) ? admin_url( 'theme-install.php?search=one-page-conference' ) : admin_url( 'theme-install.php?search=WPEventPartners' );

		echo '<div class="error notice is-dismissible"><p><strong>' . $this->plugin_full_name . '</strong> &#8211; ' . sprintf( esc_html__( 'This plugin requires %s Theme to be activated to work.', 'wep-demo-import' ), '<a href="'.esc_url( $search_url ).'">' . esc_html__('One Page Conference','wep-demo-import'). '</a>' ) . '</p></div>';
		}
		$pluginList = wep_plugin_check_activated();

		if($pluginList != '1'){

			$fileexists = wep_plugin_file_exists();
			
			if( $fileexists == '1' ){
				$wep_plugin = 'wp-event-partners/wpeventpartners.php'; 
				$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $wep_plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $wep_plugin );
                $message = '<p>' . __( 'Wep Demo Import is not working because you need to activate the WPeventpartners plugin.' ) . '</p>';
                $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate WPEventPartners Now' ) ) . '</p>';

                echo '<div class="error"><p>' . $message . '</p></div>';
			}
			else{
				$search_url = admin_url( 'plugin-install.php?s=wpeventpartner&tab=search&type=term' );
	
				echo '<div class="error notice is-dismissible"><p><strong>' . $this->plugin_full_name . '</strong> &#8211; ' . sprintf( esc_html__( 'This plugin requires %s plugin to be activated to work.', 'wep-demo-import' ), '<a href="'.esc_url( $search_url ).'">' . esc_html__('WP Event Partners','wep-demo-import'). '</a>' ) . '</p></div>';
				
			}
			}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wep_Demo_Import_Loader. Orchestrates the hooks of the plugin.
	 * - Wep_Demo_Import_i18n. Defines internationalization functionality.
	 * - Wep_Demo_Import_Admin. Defines all hooks for the admin area.
	 * - Wep_Demo_Import_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wep-demo-import-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wep-demo-import-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
        require_once WEP_DEMO_IMPORT_PATH . 'admin/functions.php';
		require_once WEP_DEMO_IMPORT_PATH . 'admin/hooks.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wep-demo-import-public.php';

		$this->loader = new Wep_Demo_Import_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wep_Demo_Import_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wep_Demo_Import_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_hooks() {

		$plugin_admin = wep_demo_import_hooks();

		$this->loader->add_action( 'admin_init', $plugin_admin, 'redirect' );
        $this->loader->add_action( 'advanced_import_demo_lists', $plugin_admin, 'add_demo_lists',999 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'import_menu' );
        $this->loader->add_action( 'wp_ajax_wep_demo_import_getting_started', $plugin_admin, 'install_advanced_import' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wep_Demo_Import_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function load_hooks() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wep_Demo_Import_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
