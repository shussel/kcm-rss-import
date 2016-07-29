<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://none
 * @since             1.0.0
 * @package           Kcm_Rss_Import
 *
 * @wordpress-plugin
 * Plugin Name:       KCM RSS Import
 * Plugin URI:        http://later
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Shane Hussel
 * Author URI:        http://none
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kcm-rss-import
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kcm-rss-import-activator.php
 */
function activate_kcm_rss_import() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kcm-rss-import-activator.php';
	Kcm_Rss_Import_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kcm-rss-import-deactivator.php
 */
function deactivate_kcm_rss_import() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kcm-rss-import-deactivator.php';
	Kcm_Rss_Import_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kcm_rss_import' );
register_deactivation_hook( __FILE__, 'deactivate_kcm_rss_import' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kcm-rss-import.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kcm_rss_import() {

	$plugin = new Kcm_Rss_Import();
	$plugin->run();

}
run_kcm_rss_import();
