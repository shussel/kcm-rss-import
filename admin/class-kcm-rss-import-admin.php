<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://none
 * @since      1.0.0
 *
 * @package    Kcm_Rss_Import
 * @subpackage Kcm_Rss_Import/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kcm_Rss_Import
 * @subpackage Kcm_Rss_Import/admin
 * @author     Shane Hussel <shussel@gmail.com>
 */
class Kcm_Rss_Import_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'kcm_rss_import';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kcm_Rss_Import_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kcm_Rss_Import_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/kcm-rss-import-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kcm_Rss_Import_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kcm_Rss_Import_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kcm-rss-import-admin.js', array( 'jquery' ), $this->version, false );

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
	 * Register settings for plugin
	 *
	 * @since  1.0.0
	 */
	public function init() {

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
	 * Render the member id input for this plugin
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
