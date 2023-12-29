<?php
/**
 * @package AvokadoWoo
 * @author  Stefan Jocić
 */

namespace Boiler;

class WPI18N {
	/**
	 * Load plugin text domain
	 *
	 * @return void
	 */
	public static function load_text_domain(): void {
		load_plugin_textdomain( 'plugin-boilerplate', false, PLUGIN_BOILERPLATE_PLUGIN_DIR_PATH . '/languages' );
	}
}
