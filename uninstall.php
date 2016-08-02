<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://none
 * @since      1.0.0
 *
 * @package    Kcm_Rss_Import
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// delete options
delete_option( 'kcm_rss_import' );
