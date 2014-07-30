<?php

namespace Coop\Map;

use Coop\Objects\Objects;
use Coop\Map\PathFinder;

/**
 * Class Routes
 * @package Coop\Objects
 *
 * @property array      $routes
 * @property int        $current
 * @property array      $wave
 * @property PathFinder $PathFinder
 *
 */
class Routes extends Objects {
	protected $routes;
	protected $current;
	protected $wave;
	protected $PathFinder;

	/**
	 * @param array $routes
	 */
	public function __construct($routes) {
		$this->routes = $routes;
		shuffle($this->routes);
		$this->current = 0;
	}

	/**
	 * @return int
	 */
	public function get() {
		$return = (int)$this->routes[$this->current];
		$this->current = (($this->current + 1) == count($this->routes)) ? 0 : $this->current + 1;
		return $return;
	}

	/**
	 * @return array
	 */
	public function getRoutes() {
		return $this->routes;
	}

	/**
	 * @param Location $start
	 * @param Location $end
	 * @param array    $mapLocationPassability
	 *
	 * @return Location
	 */
	public function nextLocation(Location $start, Location $end, Array $mapLocationPassability = null) {
		return $this->getPathFinder()->nextLocation($start, $end, $mapLocationPassability);
	}

	/**
	 * @return PathFinder
	 */
	protected function getPathFinder() {
		if (null === $this->PathFinder) {
			$this->PathFinder = new PathFinder();
		}
		return $this->PathFinder;
	}
}