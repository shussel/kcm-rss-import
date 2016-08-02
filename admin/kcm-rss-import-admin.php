<?php

/**
 * Main plugin class
 */
class Kcm_Rss_Import_Admin {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

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
	 * @var  	string 		$options 	Option array for this plugin
	 */
	private $options;


	/**
	 * This is our constructor
	 *
	 * @return void
	 */
	public function __construct( $plugin_name, $options_name ) {

		$this->plugin_name = $plugin_name;
		$this->options_name = $options_name;

		// load options
		$this->options = get_option( $this->options_name );

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );

		// Add settings link to plugin
		add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ), array( $this, 'add_action_links' ) );

	}

	/**
	 * Register settings for plugin
	 *
	 * @since  1.0.0
	 */
	public function init() {

		register_setting( $this->plugin_name, $this->options_name );
		
		// add sections
		add_settings_section(
			$this->options_name . '_account',
			__( 'Account Info', 'kcm-rss-import' ),
			array( $this, 'section_callback' ),
			$this->plugin_name
		);

		add_settings_section(
			$this->options_name . '_import',
			__( 'Import Settings', 'kcm-rss-import' ),
			array( $this, 'section_callback' ),
			$this->plugin_name
		);

		// Add Member ID setting
		add_settings_field(
			$this->options_name . '_member_id',
			__( 'KCM Member ID', 'kcm-rss-import' ),
			array( $this, $this->options_name . '_member_id_cb' ),
			$this->plugin_name,
			$this->options_name . '_account',
			array( 'label_for' => $this->options_name . '_member_id' )
		);	
		
		// Add Member ID setting
		add_settings_field(
			$this->options_name . '_category',
			__( 'Add Posts to Category', 'kcm-rss-import' ),
			array( $this, $this->options_name . '_category_cb' ),
			$this->plugin_name,
			$this->options_name . '_import',
			array( 'label_for' => $this->options_name . '_category' )
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
			case $this->options_name . '_account':
				echo 'Enter your 39 digit member id from your RSS link to activate the import.';
				break;
			case $this->options_name . '_import':
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
		echo '<input type="text" size="40" name="' . $this->options_name . '[member_id]' . '" id="' . $this->options_name . '_member_id' . '" value="' . $this->options['member_id'] . '"><p class="description"> Enter everything after"a=" in your RSS URL</p>';
	}

	/**
	 * Render the category selector for this plugin
	 *
	 * @since  1.0.0
	 */
	public function kcm_rss_import_category_cb() {

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
			'selected'           => $this->options['category'],
			'hierarchical'       => 0,
			'name'               => 'kcm_rss_import[category]',
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