<?php

namespace Coop\Configs;


use Coop\Objects\Objects;

class CollectionsConfig extends Objects {
	protected $Collections;

	public function __construct($collections) {
		if (!empty($collections)) {
			$this->Collections = $this->process($collections);
		}
		return $this;
	}

	private function process($collections) {
	}
}