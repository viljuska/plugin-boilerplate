<?php
/*
 * Plugin boilerplate
 *
 * @package PluginBoilerplate
 * @author Stefan Jocić
 *
 * Plugin Name:        Plugin Boilerplate
 * Plugin URI:         https://github.com/viljuska/plugin-boilerplate.git
 * Description:        WordPress plugin boilerplate.
 * Version:            1.0.0
 * Requires at least:  6
 * Requires PHP:       7.4
 * Author:             Stefan Jocić
 * License:            GPLv2 or later
 * Text Domain:        plugin-boilerplate
 * Domain Path:        /languages
*/


/**
 * Autoload files
 */
$autoloader = __DIR__ . '/vendor/autoload.php';

// If autoloader doesn't exist, then show notice and stop plugin from executing
if ( ! file_exists( $autoloader ) ) {
	add_action( 'admin_notices', static function () {
		?>
		<div class='notice notice-error is-dismissible'>
			<p><?php echo __( 'Autoloader not found. Plugin won\'t work.', 'plugin-boilerplate' ) ?></p>
			<p><?php echo sprintf( __( 'In %s you need to start this command:', 'plugin-boilerplate' ), __DIR__ ) ?>
				<code>composer i</code></p>
		</div>
		<?php
	} );

	return;
}
require_once $autoloader;

/**
 * Define global constants
 */
define( "PLUGIN_BOILERPLATE_PLUGIN_DIR_PATH", plugin_dir_path( __FILE__ ) );
define( "PLUGIN_BOILERPLATE_PLUGIN_REL_PATH", dirname( plugin_basename( __FILE__ ) ) );
define( "PLUGIN_BOILERPLATE_PLUGIN_URL", plugin_dir_url( __FILE__ ) );
const PLUGIN_BOILERPLATE_PLUGIN_TEXT_DOMAIN = 'plugin-boilerplate';

/**
 * Initiate plugin functionality
 */
if ( class_exists( Boiler\Init::class ) ) {
	add_action( 'plugins_loaded', [ Boiler\Init::class, 'register_services' ] );
}

/**
 * Initiate plugin text domain
 */
if ( class_exists( Boiler\WPI18N::class ) ) {
	add_action( 'plugins_loaded', [ Boiler\WPI18N::class, 'load_text_domain' ] );
}
