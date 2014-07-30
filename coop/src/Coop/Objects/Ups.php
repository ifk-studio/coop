<?php

namespace Coop\Objects;

class Ups extends Objects {
	protected $Ups;

	public function __construct() {
		$this->Ups = new \SplObjectStorage();
	}

	/**
	 *
	 */
	public function clear() {
		$this->Ups->removeAll($this->Ups);
	}

	/**
	 * @param $from
	 * @param $ups
	 *
	 * @return array
	 */
	public function intersect($from, $ups) {
		$usedUps = array();
		$this->Ups->rewind();
		for ($i = 0; $i < $this->Ups->count(); $i++) {
			$usedUps = array_merge($usedUps, $this->get($from));
			$this->Ups->next();
		}

		return empty($usedUps) ? array() : array_intersect($usedUps, $ups);
	}

	/**
	 * @param $from
	 * @param $ups
	 *
	 * @return bool
	 */
	public function check($from, $ups) {
		$intersect = $this->intersect($from, $ups);
		return empty($intersect);
	}

	/**
	 * @param $from
	 * @param $ups
	 */
	public function set($from, $ups) {
		if (!$this->Ups->contains($from)) {
			$this->Ups->attach($from);
		}
		$this->Ups->offsetSet($from, $ups);
	}

	/**
	 * @param $from
	 *
	 * @return mixed
	 */
	public function get($from) {
		return $this->Ups->offsetGet($from);
	}

	/**
	 * @return int
	 */
	public function count(){
		return $this->Ups->count();
	}
}