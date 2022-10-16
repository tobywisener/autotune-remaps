<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              wisener.me
 * @since             1.0.0
 * @package           Autotune_Remaps
 *
 * @wordpress-plugin
 * Plugin Name:       Autotune Remaps
 * Plugin URI:        wisener.me
 * Description:       A system for anaging and requesting remaps. Handles uploading, downloading of ECU files, status tracking, email notifications, online payments (via Paypal) and emailing completed remaps as attachments when finished.
 * Version:           1.1.0
 * Author:            Toby Wisener
 * Author URI:        wisener.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       autotune-remaps
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
define( 'AUTOTUNE_REMAPS_VERSION', '1.0.0' );

// Load the composer dependencies
require 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-autotune-remaps-activator.php
 */
function activate_autotune_remaps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-autotune-remaps-activator.php';
	Autotune_Remaps_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-autotune-remaps-deactivator.php
 */
function deactivate_autotune_remaps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-autotune-remaps-deactivator.php';
	Autotune_Remaps_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_autotune_remaps' );
register_deactivation_hook( __FILE__, 'deactivate_autotune_remaps' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-autotune-remaps.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_autotune_remaps() {

	$plugin = new Autotune_Remaps();
	$plugin->run();
	$plugin->cleanup();

}
run_autotune_remaps();
