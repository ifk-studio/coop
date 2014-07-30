<?php

namespace Coop\Configs;

use Coop\Objects\Objects;
use Coop\Utils\Inflector;

class ObjectsConfig extends Objects {
	protected $Objects;

	public function __construct($objects) {
		if (!empty($objects)) {
			$this->Objects = $this->process($objects);
		}
		return $this;
	}

	/**
	 * @param array $objects
	 *
	 * @return array
	 */
	private function process($objects) {
		$results = array();
		if (!empty($objects)) {
			foreach ($objects as $id => $object) {
				if(empty($object['type'])) {
					continue;
				}
				$className = "Coop\\Configs\\Parsers\\".Inflector::camelize($object['type']);
				if(class_exists($className)) {
					$results[$object['type']][Inflector::camelize($id)] = $className::parse($object);
				}
			}
		}
		return $results;
	}

	/**
	 * @return array
	 */
	public function getEnemy(){
		return $this->Objects['enemy'];
	}

	/**
	 * @return array
	 */
	public function getFortress(){
		return $this->Objects['fortress'];
	}
}