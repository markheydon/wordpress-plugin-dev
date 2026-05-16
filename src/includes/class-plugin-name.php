<?php
/**
 * The core plugin class file.
 *
 * Defines internationalization and cron hooks.
 *
 * @link       https://developer.wordpress.org/plugins/
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

require_once PLUGIN_NAME_PATH . 'includes/class-plugin-name-loader.php';
require_once PLUGIN_NAME_PATH . 'includes/class-plugin-name-i18n.php';
require_once PLUGIN_NAME_PATH . 'includes/class-plugin-name-cron.php';
require_once PLUGIN_NAME_PATH . 'admin/class-plugin-name-admin-cli.php';

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
		$this->define_cron_hooks();
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
	 * Run plugin.
	 *
	 * @return void
	 */
	public function run() {
		$this->loader->run();
	}
}
