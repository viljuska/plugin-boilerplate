<?php
/**
 * Main interface for the plugin
 *
 * @package MainInterface
 * @author  Stefan Jocić
 */

namespace Boiler\Abstracts;

abstract class MainInterface {
	private function __construct() {
		$this->addActions();
		$this->addFilters();
		$this->removeActions();
		$this->removeFilters();
	}

	private function addActions(): void { }

	private function addFilters(): void { }

	private function removeActions(): void { }

	private function removeFilters(): void { }
}
