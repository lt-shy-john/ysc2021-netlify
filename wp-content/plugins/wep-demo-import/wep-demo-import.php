<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              as
 * @since             1.0.0
 * @package           Wep_Demo_Import
 *
 * @wordpress-plugin
 * Plugin Name:       Wep Demo Import
 * Plugin URI:        
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.4
 * Author:            WPEventPartners 
 * Author URI:        https://profiles.wordpress.org/wpeventpartners/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wep-demo-import
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WEP_DEMO_IMPORT_VERSION', '1.0.0' );



/*Define Constants for this plugin*/
define( 'WEP_DEMO_IMPORT_PLUGIN_NAME', 'acme-demo-setup' );
define( 'WEP_DEMO_IMPORT_PATH', plugin_dir_path( __FILE__ ) );
define( 'WEP_DEMO_IMPORT_URL', plugin_dir_url( __FILE__ ) );
define( 'WEP_DEMO_IMPORT_TEMPLATE_URL', WEP_DEMO_IMPORT_URL.'includes/demo-data/' );
define( 'WEP_DEMO_IMPORT_SCRIPT_PREFIX', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wep-demo-import-activator.php
 */
function activate_wep_demo_import() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wep-demo-import-activator.php';
	Wep_Demo_Import_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wep-demo-import-deactivator.php
 */
function deactivate_wep_demo_import() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wep-demo-import-deactivator.php';
	Wep_Demo_Import_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wep_demo_import' );
register_deactivation_hook( __FILE__, 'deactivate_wep_demo_import' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wep-demo-import.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

if( !function_exists( 'run_wep_demo_import')){

	function run_wep_demo_import() {
  
	    return Wep_Demo_Import::instance();
	}
	run_wep_demo_import()->run();
  }