<?php
/**
 * Runs on plugin deactivation.
 *
 * @package Plugin_Name
 */

/**
 * Deactivation handler.
 */
class Plugin_Name_Deactivator {

	/**
	 * Deactivate the plugin.
	 *
	 * @return void
	 */
	public static function deactivate() {
		$timestamp = wp_next_scheduled( 'plugin_name_cron_event' );
		if ( false !== $timestamp ) {
			wp_unschedule_event( $timestamp, 'plugin_name_cron_event' );
		}
	}
}
