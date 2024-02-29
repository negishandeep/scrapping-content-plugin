<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://creatotech.com/
 * @since      1.0.0
 *
 * @package    Creatotech_Dynamic_News
 * @subpackage Creatotech_Dynamic_News/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Creatotech_Dynamic_News
 * @subpackage Creatotech_Dynamic_News/includes
 * @author     Creatotech <support@creatotech>
 */
class Creatotech_Dynamic_News_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'creatonews';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			title varchar(255) NOT NULL,
			new_img varchar(255) NOT NULL,
			short_text longtext NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY (title)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		/*if ( ! wp_next_scheduled( 'my_daily_event' ) ) {
			wp_schedule_event( time(), 'everyminute', 'my_daily_event' );
		}*/

	}
}