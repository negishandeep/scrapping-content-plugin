<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://https://creatotech.com/
 * @since      1.0.0
 *
 * @package    Creatotech_Dynamic_News
 * @subpackage Creatotech_Dynamic_News/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Creatotech_Dynamic_News
 * @subpackage Creatotech_Dynamic_News/includes
 * @author     Creatotech <support@creatotech>
 */
class Creatotech_Dynamic_News_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

        // wp_clear_scheduled_hook('my_daily_event');

	}

}
