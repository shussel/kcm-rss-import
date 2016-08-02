<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Kcm_Rss_Import
 * @subpackage Kcm_Rss_Import/includes
 * @author     Shane Hussel <shussel@gmail.com>
 */
class Kcm_Rss_Import_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// unschedule import if already scheduled
		if ($timestamp = wp_next_scheduled( 'kcm_import_rss' ) ) {	
			wp_unschedule_event( $timestamp, 'kcm_import_rss' );
		}
	}

}
