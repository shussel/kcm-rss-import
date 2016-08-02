<?php

/**
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
		
		// setup import hook
		add_action( 'kcm_import_rss',  array( $this, 'import_rss' ) );

		// Add custom cron interval
		add_filter( 'cron_schedules', array( $this,'add_custom_cron_intervals'), 10, 1 );

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
	
	public function import_rss() {

		// load options
		$this->options = get_option( $this->options_name );

		$latest = $this->options['latest'];

		// set url
		$feed_url = "http://www.simplifyingthemarket.com/en/feed/?a=" . $this->options['member_id'];

		$curl = curl_init();
		curl_setopt_array($curl, Array(
			CURLOPT_URL            => $feed_url,
			CURLOPT_USERAGENT      => 'kcm-rss-import',
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_ENCODING       => 'UTF-8'
		));
		$data = curl_exec($curl);
		curl_close($curl);
		$xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

		foreach ($xml->channel->item as $item) {

			// quit if post is before last update
			if ( strtotime($item->pubDate) <= $latest) break;

			$post['post_date'] = date("Y-m-d H:i:s", strtotime($item->pubDate));
			$post['post_title'] = $item->title;
			$post['post_excerpt'] = $item->description;
			$post['post_content'] = $item->children('http://purl.org/rss/1.0/modules/content/')->encoded;
			$post['post_status'] = 'publish';

			$post_id = wp_insert_post( $post );

			if ($post_id && $this->options['category']) {
				wp_set_post_categories( $post_id, $this->options['category'] );
			}
		}

		// save new latest entry time
		if (strtotime($xml->channel->item[0]->pubDate)) {

			$this->options['latest'] = strtotime($xml->channel->item[0]->pubDate);
			update_option( $this->options_name, $this->options );

		}
	}

	public function add_custom_cron_intervals( $schedules ) {
		// $schedules stores all recurrence schedules within WordPress
		$schedules['two_minutes'] = array(
			'interval'	=> 120,	// Number of seconds, 120 in 2 minutes
			'display'	=> 'Once Every 2 Minutes'
		);

		// Return our newly added schedule to be merged into the others
		return (array)$schedules; 
	}
}

// Instantiate class
$Kcm_Rss_Import = Kcm_Rss_Import::getInstance();
