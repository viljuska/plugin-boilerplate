<?php
/**
 * @package Boiler
 *
 * This plugin uses PSR-4 and OOP logic. This class is main controller class that initializes all other functionality in
 *  plugin.
 * @author  Stefan Jocić
 */

namespace Boiler;

use Boiler\Traits\Singleton;

/**
 * Class Init - Main class to init all other classes and plugin functionality
 */
final class Init {
	/**
	 * @var Singleton[]
	 */
	private static array $services = [];

	/**
	 * Store all the classes inside an array
	 *
	 * @return void
	 */
	public static function getServices(): void {
		self::$services = [
			Actions::class,
			Filters::class,
			Ajax::class,
			Enqueue::class,
		];
	}

	/**
	 * Loop through the classes and initialize them
	 *
	 * @return void
	 */
	public static function register_services(): void {
		self::getServices();

		if ( ! empty( self::$services ) ) {

			foreach ( self::$services as $class ) {
				$class::get_instance();
			}
		}
	}
}
