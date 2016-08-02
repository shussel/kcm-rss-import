<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://github.com/shussel/kcm-rss-import
 * @since      1.0.0
 *
 * @package    Kcm_Rss_Import
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// delete plugin options
delete_option( 'kcm_rss_import' );
