<?php

/**
 * Fired during plugin activation
 *
 * @link       wisener.me
 * @since      1.0.0
 *
 * @package    Autotune_Remaps
 * @subpackage Autotune_Remaps/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Autotune_Remaps
 * @subpackage Autotune_Remaps/includes
 * @author     Toby Wisener <toby@wisener.me>
 */
class Autotune_Remaps_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		//return; // Do not run the activate hook

		global $wpdb;
		$table_name = $wpdb->prefix . "remaps";
		$my_products_db_version = '1.0.0';
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

		    $sql = "CREATE TABLE $table_name (
		    	`remap_id` INT NOT NULL AUTO_INCREMENT,
		    	`user_id` INT NOT NULL ,
		    	`status` INT NOT NULL DEFAULT '0' ,
		    	`manufacturer` VARCHAR(100) NOT NULL ,
		    	`model` VARCHAR(100) NOT NULL ,
		    	`year` YEAR NOT NULL ,
		    	`engine_size` VARCHAR(100) NOT NULL,
		    	`remap_file` TEXT NOT NULL ,
		    	`ecu_type` TEXT NOT NULL ,
		    	`price` DECIMAL(13,2) NULL ,
		    	`autotune_note` TEXT NULL ,
		    	`performance_tuning` BOOLEAN NOT NULL ,
		    	`lambda_o2_decat` BOOLEAN NOT NULL ,
		    	`dpf_removal` BOOLEAN NOT NULL ,
		    	`adblue_scr_nox` BOOLEAN NOT NULL ,
		    	`inlet_swirl_throttle` BOOLEAN NOT NULL ,
		    	`egr_removal` BOOLEAN NOT NULL ,
		    	`dtc` BOOLEAN NOT NULL ,
		    	`dtc_p_codes` TEXT NULL ,
		    	`other_notes` TEXT NULL ,
		    	`created_at` DATETIME NOT NULL ,
		    	`updated_at` DATETIME NULL ,
		    	PRIMARY KEY `remap_id`(`remap_id`),
		    	INDEX `user_id` (`user_id`)
		    )	$charset_collate;";

		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql );
		    add_option( 'my_db_version', $my_products_db_version );
		}

        //adding autotune note column
        $row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE table_name = {$table_name} AND column_name = 'autotune_note'");

        if (empty($row)) {
            $wpdb->query("ALTER TABLE {$table_name} ADD autotune_note VARCHAR(100) NOT NULL AFTER price");
        }

		/* Migration 18/02/2020
		Toby: Make some columns nullable to handle storing of charges in the same table

		ALTER TABLE `wp_remaps` ADD `type` TINYINT NOT NULL DEFAULT '0' AFTER `user_id`;

		ALTER TABLE `wp_remaps`
		CHANGE `manufacturer` `manufacturer` VARCHAR(100) NULL,
		CHANGE `model` `model` VARCHAR(100) NULL, CHANGE `year` `year` YEAR(4) NULL,
		CHANGE `engine_size` `engine_size` VARCHAR(100) NULL,
		CHANGE `remap_file` `remap_file` TEXT NULL,
		CHANGE `ecu_type` `ecu_type` TEXT NULL,
		CHANGE `performance_tuning` `performance_tuning` TINYINT(1) NULL,
		CHANGE `lambda_o2_decat` `lambda_o2_decat` TINYINT(1) NULL,
		CHANGE `dpf_removal` `dpf_removal` TINYINT(1) NULL,
		CHANGE `adblue_scr_nox` `adblue_scr_nox` TINYINT(1) NULL,
		CHANGE `inlet_swirl_throttle` `inlet_swirl_throttle` TINYINT(1) NULL,
		CHANGE `egr_removal` `egr_removal` TINYINT(1) NULL,
		CHANGE `dtc` `dtc` TINYINT(1) NULL;
		*/

	}

}
