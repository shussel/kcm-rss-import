<?php

/**
 * Fired during plugin activation
 *
 * @link       http://none
 * @since      1.0.0
 *
 * @package    Kcm_Rss_Import
 * @subpackage Kcm_Rss_Import/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Kcm_Rss_Import
 * @subpackage Kcm_Rss_Import/includes
 * @author     Shane Hussel <shussel@gmail.com>
 */
class Kcm_Rss_Import_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// load options
		$options = get_option( 'kcm_rss_import');

		// schedule if member id exists
		if ($options['member_id']) {
			
			// schedule import if not already scheduled
			if (!wp_next_scheduled( 'kcm_import_rss' ) ) {	
				wp_schedule_event( time(), 'hourly', 'kcm_import_rss' );
			}
		}
	}

}
