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
 * Plugin URI:        https://github.com/shussel/kcm-rss-import
 * Description:       Automatically import your Personalized Posts&trade; from Keeping Current Matters.
 * Version:           1.0.0
 * Author:            Shane Hussel
 * Author URI:        https://github.com/shussel
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
 */
function activate_kcm_rss_import() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kcm-rss-import-activator.php';
	Kcm_Rss_Import_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_kcm_rss_import() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kcm-rss-import-deactivator.php';
	Kcm_Rss_Import_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kcm_rss_import' );
register_deactivation_hook( __FILE__, 'deactivate_kcm_rss_import' );

/**
 * Main plugin class
 */
class Kcm_Rss_Import {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name = 'kcm-rss-import';

	/**
	 * Options name for this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $options_name    The string used to uniquely identify plugin options array.
	 */
	protected $options_name = 'kcm_rss_import';

	/**
	 * Array of options
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$options 	Options array for this plugin
	 */
	private $options;

	/**
	 * Admin object
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Admin object for this plugin
	 */
	private $admin;

	/**
	 * Static property to hold our singleton instance
	 *
	 */
	static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return void
	 */
	private function __construct() {		

		// load admin functionality
		if ( is_admin() ) {

			include_once 'admin/kcm-rss-import-admin.php';
			$this->admin = new Kcm_Rss_Import_Admin( $this->plugin_name, $this->options_name );

		}		
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * returns it.
	 *
	 * @return Kcm_Rss_Import
	 */
	public static function getInstance() {
		if ( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}	
}

// Instantiate class
$Kcm_Rss_Import = Kcm_Rss_Import::getInstance();
