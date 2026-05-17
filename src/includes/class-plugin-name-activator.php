<?php
/**
 * Runs on plugin activation.
 *
 * @package Plugin_Name
 */

/**
 * Activation handler.
 */
class Plugin_Name_Activator {

	/**
	 * Activate the plugin.
	 *
	 * @return void
	 */
	public static function activate() {
		if ( defined( 'PLUGIN_NAME_ENABLE_CRON' ) && ! PLUGIN_NAME_ENABLE_CRON ) {
			return;
		}

		if ( ! wp_next_scheduled( 'plugin_name_cron_event' ) ) {
			wp_schedule_event( time(), 'hourly', 'plugin_name_cron_event' );
		}
	}
}
