<?php

namespace Coop\Configs;

use Coop\Map\Location;
use Coop\Objects\Objects;

class RoutesConfig extends Objects {
	public $Routes;

	public function __construct($routes) {
		if (!empty($routes)) {
			$this->Routes = $this->process($routes);
		}
		return $this;
	}

	/**
	 * @param array $routes
	 *
	 * @return array
	 */
	private function process($routes) {
		$result = array();
		foreach ($routes as $id => $route) {
			$result[$id] = array(
				"title" => $route['title'],
				"baseRoute" => $route['baseRoute'],
				"maps" => empty($route['maps']) ? array() : $route['maps'],
				"points" => $this->processPoints($route['points'])
			);
		}
		return $result;
	}

	/**
	 * @param array $points
	 *
	 * @return array
	 */
	protected function processPoints($points) {
		$results = array();
		foreach ($points as $point) {
			$results[] = new Location($point['x'], $point['y']);
		}
		return $results;
	}

	/**
	 * @param string $map
	 *
	 * @return array
	 */
	public function getRoutesByMap($map){
		$routes = array();
		foreach ($this->Routes as $route) {
			if(in_array($map, $route['maps'])) {
				$routes[] = $route['title'];
			}
		}
		return $routes;
	}
}