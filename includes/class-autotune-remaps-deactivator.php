<?php

/**
 * Fired during plugin deactivation
 *
 * @link       wisener.me
 * @since      1.0.0
 *
 * @package    Autotune_Remaps
 * @subpackage Autotune_Remaps/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Autotune_Remaps
 * @subpackage Autotune_Remaps/includes
 * @author     Toby Wisener <toby@wisener.me>
 */
class Autotune_Remaps_Deactivator {

	/**
	 * A function ran when the plugin is deactivated
	 *
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if(!Autotune_Remaps_Public::$drop_db_deactivate) {
			return;
		}

		global $wpdb;

		$table_name = $wpdb->prefix . "remaps";

		// drop the table from the database.
		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

	}

}
