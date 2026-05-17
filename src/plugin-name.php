<?php
/**
 * Plugin Name:       WordPress Plugin Dev Template
 * Plugin URI:        https://developer.wordpress.org/plugins/
 * Description:       Starter plugin entry point for template-based development.
 * Version:           1.0.0
 * Author:            Your Name or Company
 * Author URI:        https://example.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 *
 * @package Plugin_Name
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );
define( 'PLUGIN_NAME_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_NAME_URL', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'PLUGIN_NAME_ENABLE_CRON' ) ) {
	define( 'PLUGIN_NAME_ENABLE_CRON', true );
}

if ( ! defined( 'PLUGIN_NAME_ENABLE_WP_CLI' ) ) {
	define( 'PLUGIN_NAME_ENABLE_WP_CLI', true );
}

require_once PLUGIN_NAME_PATH . 'includes/class-plugin-name-activator.php';
require_once PLUGIN_NAME_PATH . 'includes/class-plugin-name-deactivator.php';
require_once PLUGIN_NAME_PATH . 'includes/class-plugin-name.php';

register_activation_hook( __FILE__, array( 'Plugin_Name_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Plugin_Name_Deactivator', 'deactivate' ) );

/**
 * Start plugin execution.
 *
 * @return void
 */
function run_plugin_name() {
	$plugin = new Plugin_Name();
	$plugin->run();
}

run_plugin_name();
