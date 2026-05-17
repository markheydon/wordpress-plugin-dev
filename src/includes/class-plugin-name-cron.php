<?php
/**
 * Cron hook support.
 *
 * @package Plugin_Name
 */

/**
 * Cron event callbacks.
 */
class Plugin_Name_Cron {

	/**
	 * Handle scheduled event.
	 *
	 * @return void
	 */
	public function run_hourly_task() {
		do_action( 'plugin_name_hourly_task' );
	}
}
