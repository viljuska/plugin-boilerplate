<?php
/**
 * @package Boiler
 * @author  Stefan Jocić
 */

namespace Boiler;

use Boiler\Traits\Singleton;

class Enqueue {
	use Singleton;

	private function __construct() {
		$this->addActions();
	}

	protected function addActions(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScriptsStyles' ] );
	}

	public function enqueueScriptsStyles(): void {
		wp_enqueue_style(
			'boiler-styles',
			Helpers::asset( 'styles/main.css' ),
			[],
			Helpers::asset_version( Helpers::asset( 'styles/main.css', false, true ) )
		);

		wp_enqueue_script(
			'boiler-scripts',
			Helpers::asset( 'scripts/main.js' ),
			[],
			Helpers::asset_version( Helpers::asset( 'scripts/main.js', false, true ) ),
			true
		);
	}
}
