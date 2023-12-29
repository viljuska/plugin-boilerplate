<?php
/**
 * @package Boiler
 * @author  Stefan Jocić
 */

namespace Boiler;

/**
 * Helper class stores some methods to help us in developing.
 */
final class Helpers {
	public const ASSET_URL   = PLUGIN_BOILERPLATE_PLUGIN_URL . 'assets/';
	public const ASSET_PATH  = PLUGIN_BOILERPLATE_PLUGIN_DIR_PATH . 'assets/';
	public const PUBLIC_URL  = PLUGIN_BOILERPLATE_PLUGIN_URL . 'dist/';
	public const PUBLIC_PATH = PLUGIN_BOILERPLATE_PLUGIN_DIR_PATH . 'dist/';

	/**
	 * Prevent developers to instantiate this class.
	 */
	private function __construct() { }

	/**
	 * Loads or gets template file contents
	 * Modification of the WordPress function get_template_part, so it can work in plugin
	 *
	 * @param string  $slug           Path to the template file without extension.
	 * @param ?string $name           Template name if any.
	 * @param array   $args           Array of arguments for template part.
	 * @param bool    $return         Should the function load file directly or return its contents through buffer.
	 * @param string  $main_directory Main directory where templates are stored if you want to override them.
	 *
	 * @return false|string
	 */
	public static function get_template_part( string $slug, ?string $name = null, array $args = [], bool $return = false, string $main_directory = 'plugin-boilerplate' ) {
		/**
		 * Fires before the specified template part file is loaded.
		 *
		 * The dynamic portion of the hook name, `$slug`, refers to the slug name
		 * for the generic template part.
		 *
		 * @param string      $slug The slug name for the generic template.
		 * @param string|null $name The name of the specialized template.
		 * @param array       $args Additional arguments passed to the template.
		 */
		do_action( "plugin_boilerplate_get_template_part_{$slug}", $slug, $name, $args );

		$templates = [];
		$name      = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "{$slug}-{$name}.php";
		}

		$templates[] = "{$slug}.php";

		/**
		 * Fires before an attempt is made to locate and load a template part.
		 *
		 * @param string   $slug      The slug name for the generic template.
		 * @param string   $name      The name of the specialized template.
		 * @param string[] $templates Array of template files to search for, in order.
		 * @param array    $args      Additional arguments passed to the template.
		 */
		do_action( 'get_template_part', $slug, $name, $templates, $args );

		if ( ! $return && ! self::locate_template( $templates, true, false, $args ) ) {
			trigger_error( sprintf( __( 'Required template not found: %s', 'plugin-boilerplate' ), $slug ) );

			return false;
		}

		ob_start();
		self::locate_template( $templates, true, false, $args, $main_directory );

		return ob_get_clean();
	}

	/**
	 * Locate template part. Search firstly in theme root for file overrides, if there isn't any, then
	 * look in templates directory, and if there isn't either, then load from the plugin folder
	 *
	 * @param array  $template_names Array of possible template names/paths.
	 * @param bool   $load           Should the method load the template or return its path.
	 * @param bool   $require_once   Should method use require once or just require.
	 * @param array  $args           Array of arguments for the template.
	 * @param string $main_directory Main directory where templates are stored if you want to override them.
	 *
	 * @return mixed|string
	 */
	public static function locate_template( array $template_names, bool $load = false, bool $require_once = true, array $args = [], string $main_directory = 'plugin-boilerplate' ) {
		$located = '';

		foreach ( $template_names as $template_name ) {
			if ( ! $template_name ) {
				continue;
			}

			// Allow for third party to modify inclusion of the templates
			$custom_template_path = apply_filters( 'plugin_boilerplate_locate_template_path', '', $template_name, $template_names, $args );

			if ( $custom_template_path && file_exists( $custom_template_path ) ) {
				$located = $custom_template_path;
				break;
			}

			// Check in theme folder.
			if ( file_exists( get_stylesheet_directory() . "/$main_directory/$template_name" ) ) {
				$located = get_stylesheet_directory() . "/$main_directory/$template_name";
				break;
			}

			// Then check in templates folder - parent theme.
			if ( file_exists( get_template_directory() . "/$main_directory/$template_name" ) ) {
				$located = get_template_directory() . "/$main_directory/$template_name";
				break;
			}

			// Then check in plugin templates folder.
			if ( file_exists( PLUGIN_BOILERPLATE_PLUGIN_DIR_PATH . "templates/{$template_name}" ) ) {
				$located = PLUGIN_BOILERPLATE_PLUGIN_DIR_PATH . "templates/{$template_name}";
				break;
			}
		}

		if ( $load && '' !== $located ) {
			self::load_template( $located, $require_once, $args );
		}

		return $located;
	}

	/**
	 * Handles loading of the template file. Extracted in method for future extensions.
	 * This method loads template file directly and extracts all arguments into separate variables.
	 *
	 * @param string $_template_file absolute location.
	 * @param bool   $require_once   if the template should be loaded only once.
	 * @param array  $args           will be extracted into separate variable for easier manipulation.
	 *
	 * @return void
	 */
	public static function load_template( string $_template_file, bool $require_once = true, array $args = [] ): void {
		// Extract arguments to separate variable
		extract( $args, EXTR_SKIP );

		if ( $require_once ) {
			require_once $_template_file;
		} else {
			require $_template_file;
		}
	}

	/**
	 * Get path to the asset either from dist or from src folder
	 *
	 * @param string $asset      Asset path from resources or public folder
	 * @param false  $isResource If it should check in public or resource folder
	 * @param bool   $getPath    If it should return file system path instead of url
	 *
	 * @return string
	 */
	public static function asset( string $asset = '', bool $isResource = false, bool $getPath = false ): string {
		if ( $getPath ) {
			return ( $isResource ? self::ASSET_PATH : self::PUBLIC_PATH ) . $asset;
		}

		return ( $isResource ? self::ASSET_URL : self::PUBLIC_URL ) . $asset;
	}

	public static function asset_version( $asset_path ) {
		if ( file_exists( $asset_path ) ) {
			return filemtime( $asset_path );
		}

		return uniqid( '', true );
	}
}
