<?php

/**
 * Fired during plugin uninstall
 *
 * @link       https://https://creatotech.com/
 * @since      1.0.0
 *
 * @package    Creatotech_Dynamic_News
 * @subpackage Creatotech_Dynamic_News/includes
 */

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstall.
 *
 * @since      1.0.0
 * @package    Creatotech_Dynamic_News
 * @subpackage Creatotech_Dynamic_News/includes
 * @author     Creatotech <support@creatotech>
 */
class Creatotech_Dynamic_News_Uninstall {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {
		global $wpdb;
	    $table_name = $wpdb->prefix . 'creatonews';
	    $wpdb->query("DROP TABLE IF EXISTS $table_name");

	}

}