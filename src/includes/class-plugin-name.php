<?php
/**
 * Core plugin class.
 *
 * @package Plugin_Name
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once PLUGIN_NAME_PATH . 'includes/class-plugin-name-loader.php';
require_once PLUGIN_NAME_PATH . 'includes/class-plugin-name-i18n.php';

if ( ! defined( 'PLUGIN_NAME_ENABLE_CRON' ) || PLUGIN_NAME_ENABLE_CRON ) {
	require_once PLUGIN_NAME_PATH . 'includes/class-plugin-name-cron.php';
}

if ( ! defined( 'PLUGIN_NAME_ENABLE_WP_CLI' ) || PLUGIN_NAME_ENABLE_WP_CLI ) {
	require_once PLUGIN_NAME_PATH . 'admin/class-plugin-name-admin-cli.php';
}

/**
 * Main plugin coordinator.
 */
class Plugin_Name {

	/**
	 * Hook loader.
	 *
	 * @var Plugin_Name_Loader
	 */
	protected $loader;

	/**
	 * Boot plugin hooks.
	 */
	public function __construct() {
		$this->loader = new Plugin_Name_Loader();
		$this->set_locale();

		if ( $this->is_cron_enabled() ) {
			$this->define_cron_hooks();
		}
	}

	/**
	 * Register locale hooks.
	 *
	 * @return void
	 */
	private function set_locale() {
		$plugin_i18n = new Plugin_Name_I18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register cron hooks.
	 *
	 * @return void
	 */
	private function define_cron_hooks() {
		$plugin_cron = new Plugin_Name_Cron();
		$this->loader->add_action( 'plugin_name_cron_event', $plugin_cron, 'run_hourly_task' );
	}

	/**
	 * Check whether cron scaffolding is enabled.
	 *
	 * @return bool
	 */
	private function is_cron_enabled() {
		return ! defined( 'PLUGIN_NAME_ENABLE_CRON' ) || PLUGIN_NAME_ENABLE_CRON;
	}

	/**
	 * Run plugin.
	 *
	 * @return void
	 */
	public function run() {
		$this->loader->run();
	}
}
