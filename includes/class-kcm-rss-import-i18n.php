<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://none
 * @since      1.0.0
 *
 * @package    Kcm_Rss_Import
 * @subpackage Kcm_Rss_Import/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Kcm_Rss_Import
 * @subpackage Kcm_Rss_Import/includes
 * @author     Shane Hussel <shussel@gmail.com>
 */
class Kcm_Rss_Import_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'kcm-rss-import',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
