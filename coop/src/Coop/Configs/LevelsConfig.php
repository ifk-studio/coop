<?php

namespace Coop\Configs;

use Coop\Objects\Objects;

class LevelsConfig extends Objects {
	protected $Levels;

	public function __construct($levels) {
		if (!empty($levels)) {
			$this->Levels = $this->process($levels);
		}
		return $this;
	}

	private function process($levels) {
	}
}