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
	protected $plugin_name = 'kcm_rss_import';

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'kcm_rss_import';

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
		
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );

		// Add settings link to plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
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

	/**
	 * Register settings for plugin
	 *
	 * @since  1.0.0
	 */
	public function admin_init() {

		register_setting( $this->plugin_name, $this->option_name . '_member_id' );
		register_setting( $this->plugin_name, $this->option_name . '_category' );
		
		// add sections
		add_settings_section(
			$this->option_name . '_account',
			__( 'Account Info', 'kcm-rss-import' ),
			array( $this, 'section_callback' ),
			$this->plugin_name
		);

		add_settings_section(
			$this->option_name . '_import',
			__( 'Import Settings', 'kcm-rss-import' ),
			array( $this, 'section_callback' ),
			$this->plugin_name
		);

		// Add Member ID setting
		add_settings_field(
			$this->option_name . '_member_id',
			__( 'KCM Member ID', 'kcm-rss-import' ),
			array( $this, $this->option_name . '_member_id_cb' ),
			$this->plugin_name,
			$this->option_name . '_account',
			array( 'label_for' => $this->option_name . '_member_id' )
		);	
		
		// Add Member ID setting
		add_settings_field(
			$this->option_name . '_category',
			__( 'Add Posts to Category', 'kcm-rss-import' ),
			array( $this, $this->option_name . '_category_cb' ),
			$this->plugin_name,
			$this->option_name . '_import',
			array( 'label_for' => $this->option_name . '_category' )
		);
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	 
	public function add_action_links( $links ) {
	   $settings_link = array(
		'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );
	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
	
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'KCM RSS Import Settings', 'kcm-rss-import' ),
			__( 'KCM RSS Import', 'kcm-rss-import' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/kcm-rss-import-admin-display.php';
	}

	/**
	 * Display section info
	 *
	 * @since  1.0.0
	 */
	public function section_callback( $arguments ) {
		switch( $arguments['id'] ){
			case $this->option_name . '_account':
				echo 'Enter your 39 digit member id from your RSS link to activate the import.';
				break;
			case $this->option_name . '_import':
				echo 'Select the category into which your posts are imported.';
				break;
		}
	}

		/**
	 * Render the member id input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function kcm_rss_import_member_id_cb() {
		$member_id = get_option( $this->option_name . '_member_id' );
		echo '<input type="text" size="40" name="' . $this->option_name . '_member_id' . '" id="' . $this->option_name . '_member_id' . '" value="' . $member_id . '"><span class="description"> Enter everything after"a=" in your RSS URL</span>';
	}

	/**
	 * Render the category selector for this plugin
	 *
	 * @since  1.0.0
	 */
	public function kcm_rss_import_category_cb() {

		$category = get_option( $this->option_name . '_category' );

		$args = array(
			'show_option_all'    => '',
			'show_option_none'   => '',
			'option_none_value'  => '-1',
			'orderby'            => 'ID',
			'order'              => 'ASC',
			'show_count'         => 0,
			'hide_empty'         => 0,
			'child_of'           => 0,
			'exclude'            => '',
			'include'            => '',
			'echo'               => 1,
			'selected'           => $category,
			'hierarchical'       => 0,
			'name'               => 'kcm_rss_import_category',
			'id'                 => '',
			'class'              => 'postform',
			'depth'              => 0,
			'tab_index'          => 0,
			'taxonomy'           => 'category',
			'hide_if_empty'      => false,
			'value_field'	     => 'term_id',
		);
		wp_dropdown_categories( $args );
	}
}

// Instantiate class
$Kcm_Rss_Import = Kcm_Rss_Import::getInstance();
