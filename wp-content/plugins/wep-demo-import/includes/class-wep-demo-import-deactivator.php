<?php

/**
 * Fired during plugin deactivation
 *
 * @link       as
 * @since      1.0.0
 *
 * @package    Wep_Demo_Import
 * @subpackage Wep_Demo_Import/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wep_Demo_Import
 * @subpackage Wep_Demo_Import/includes
 */
class Wep_Demo_Import_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		update_option( '__wep_demo_import_do_redirect', false );

	}

}
