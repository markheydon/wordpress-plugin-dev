<?php
/**
 * WP-CLI command support.
 *
 * @package Plugin_Name
 */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Template WP-CLI commands.
	 */
	class Plugin_Name_Admin_CLI {

		/**
		 * Run a basic health command.
		 *
		 * ## EXAMPLES
		 *
		 *     wp plugin-name health-check
		 *
		 * @when after_wp_load
		 *
		 * @return void
		 */
		public function health_check() {
			WP_CLI::success( 'Plugin template command is available.' );
		}
	}

	WP_CLI::add_command( 'plugin-name', 'Plugin_Name_Admin_CLI' );
}
