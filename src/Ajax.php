<?php
/**
 * @package Boiler
 * @author  Stefan Jocić
 */

namespace Boiler;

use Boiler\Traits\Singleton;

class Ajax {
	use Singleton;

	private array $public_actions;
	private array $private_actions;

	private function __construct() {
		$this->addActions();
	}

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return void
	 */
	public function addActions(): void {
		$this->public_actions  = [

		];
		$this->private_actions = [

		];

		$this->registerActions();
	}

	public function registerActions(): void {
		if ( ! empty( $this->public_actions ) ) {
			foreach ( $this->public_actions as $public_action => $callback ) {
				add_action( "wp_ajax_nopriv_$public_action", $callback );
			}
		}

		if ( ! empty( $this->private_actions ) ) {
			foreach ( $this->private_actions as $private_action => $callback ) {
				add_action( "wp_ajax_$private_action", $callback );
			}
		}
	}
}
