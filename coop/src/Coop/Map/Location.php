<?php

namespace Coop\Map;

class Location {
	const S = 0;
	const N = 1;
	const E = 2;
	const W = 3;
	const SE = 4;
	const SW = 5;
	const NE = 6;
	const NW = 7;


	public $x, $y;

	public function __construct($x, $y) {
		$this->set($x, $y);
	}

	/**
	 * @param Location $target
	 *
	 * @return int
	 */
	public function distance(Location $target) {
		return max(abs($target->x - $this->x), abs($target->y - $this->y));
	}

	/**
	 * @param int $x
	 * @param int $y
	 */
	public function set($x, $y) {
		$this->x = abs($x);
		$this->y = abs($y);
	}

	/**
	 * @param Location $target
	 *
	 * @return bool
	 */
	public function equal(Location $target) {
		return ($this->x == $target->x) && ($this->y == $target->y);
	}

	/**
	 * @param $distance
	 *
	 * @return array
	 */
	public function neighborhood($distance) {
		$S = array(0, -1);
		$N = array(0, 1);
		$W = array(1, 0);
		$E = array(-1, 0);
		$SE = array(-1, -1);
		$SW = array(1, -1);
		$NE = array(-1, 1);
		$NW = array(1, 1);
		return array(
			new Location($this->x + $S[0] * $distance, $this->y + $S[1] * $distance),
			new Location($this->x + $N[0] * $distance, $this->y + $N[1] * $distance),
			new Location($this->x + $E[0] * $distance, $this->y + $E[1] * $distance),
			new Location($this->x + $W[0] * $distance, $this->y + $W[1] * $distance),
			new Location($this->x + $SE[0] * $distance, $this->y + $SE[1] * $distance),
			new Location($this->x + $SW[0] * $distance, $this->y + $SW[1] * $distance),
			new Location($this->x + $NE[0] * $distance, $this->y + $NE[1] * $distance),
			new Location($this->x + $NW[0] * $distance, $this->y + $NW[1] * $distance),
		);
	}
}
