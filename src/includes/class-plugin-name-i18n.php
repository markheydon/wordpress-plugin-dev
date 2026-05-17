<?php
/**
 * Internationalization support.
 *
 * @package Plugin_Name
 */

/**
 * Loads plugin text domain.
 */
class Plugin_Name_I18n {

	/**
	 * Load textdomain for translation.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'plugin-name', false, dirname( plugin_basename( PLUGIN_NAME_PATH . 'plugin-name.php' ) ) . '/languages/' );
	}
}
