<?php
/**
 * Singleton trait for allowing us to have only one instance of the extending class
 *
 * @package Boiler
 * @author  Stefan Jocić
 */

namespace Boiler\Traits;

trait Singleton {
	private static ?self $instance = null;

	/**
	 * Prevent developers from instantiating
	 */
	private function __construct() { }

	/**
	 * Get class instance if available or instantiate it.
	 *
	 * @return self instance of the class.
	 */
	public static function get_instance(): self {
		if ( self::$instance === null ) {
			self::$instance = new static();
		}

		return self::$instance;
	}
}
