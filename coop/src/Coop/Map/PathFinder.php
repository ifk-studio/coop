<?php

namespace Coop\Map;

/**
 * Class PathFinder
 * @package Coop\Map
 *
 * @property array $wave
 * @property array $neighborhoodCashe
 *
 */
class PathFinder {
	const PASSABILITY = 1;
	protected $wave = array();
	protected $neighborhoodCashe = array();

	/**
	 * @param Location $start
	 * @param Location $end
	 * @param array    $mapLocationPassability
	 *
	 * @return array
	 */
	public function path(Location $start, Location $end, $mapLocationPassability = null) {
		$this->waves($start, $end, $mapLocationPassability);
		return $this->shortestPath($start, $end);
	}

	/**
	 * @param Location $start
	 * @param Location $end
	 * @param array    $mapLocationPassability
	 *
	 * @return Location
	 */
	public function nextLocation(Location $start, Location $end, $mapLocationPassability = null) {
		if (($start->equal($end)) || ($start->distance($end) == 1)) {
			return $end;
		}
		$path = $this->path($start, $end, $mapLocationPassability);
		return array_pop($path);
	}

	/**
	 * @param Location $location
	 * @param int      $distance
	 *
	 * @return array
	 */
	public function pushNeighborhoods(Location $location, $distance) {
		$neighborhoods = $location->neighborhood(1);
		foreach ($neighborhoods as $neighborhood) {
			if (empty($this->wave[$neighborhood->x][$neighborhood->y])) {
				$this->push($neighborhood, $distance);
			}
		}
		return $this->neighborhoodCashe;
	}

	/**
	 * @param Location $location
	 *
	 * @return bool
	 */
	public function existsInCache(Location $location){
		foreach ($this->neighborhoodCashe as $neighborhood) {
			if($location->equal($neighborhood['location'])) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param Location $location
	 * @param int      $distance
	 */
	public function push(Location $location, $distance) {
		if(!$this->existsInCache($location)) {
			array_push($this->neighborhoodCashe, array('location' => $location, 'distance' => $distance));
		}
	}

	/**
	 * @return array|null
	 */
	public function pop() {
		return array_shift($this->neighborhoodCashe);
	}

	/**
	 *
	 */
	public function init() {
		unset ($this->wave);
		$this->wave = array();
		unset ($this->neighborhoodCashe);
		$this->neighborhoodCashe = array();
	}

	/**
	 * @param Location $start
	 * @param Location $end
	 * @param array    $mapLocationPassability
	 *
	 * @return array
	 */
	public function waves(Location $start, Location $end, $mapLocationPassability = null) {
		$this->init();
		$maxDistance = $start->distance($end);
		$this->push($start, 0);
		while (($current = $this->pop()) !== null) {
			$distance = $current['distance'];
			/** @var Location $location */
			$location = $current['location'];
			if (!isset($this->wave[$location->x][$location->y]) ) {
				if ($mapLocationPassability === null) {
					$this->wave[$location->x][$location->y] = $distance;
				} else {
					if (isset($mapLocationPassability[$location->x][$location->y]) && ($mapLocationPassability[$location->x][$location->y] == self::PASSABILITY)) {
						$this->wave[$location->x][$location->y] = $distance;
					} else {
						$this->wave[$location->x][$location->y] = $maxDistance;
					}
				}
			}
			if ($location->equal($end)) {
				break;
			}
			$this->pushNeighborhoods($location, $distance + 1);
		}
		return $this->wave;
	}

	/**
	 * @param Location $start
	 * @param Location $end
	 *
	 * @return array
	 */
	public function shortestPath(Location $start, Location $end) {
		$path = array();
		$current = $end;
		do {
			$path[] = $current;
			$neighborhoods = $current->neighborhood(1);
			foreach ($neighborhoods as $neighborhood) {
				if (isset($this->wave[$neighborhood->x][$neighborhood->y]) && ($this->wave[$neighborhood->x][$neighborhood->y] === ($this->wave[$current->x][$current->y] - 1))) {
					$current = $neighborhood;
				}
			}
		} while(!$current->equal($start));
		return $path;
	}

	/**
	 * @return array
	 */
	public function getWaves() {
		return $this->wave;
	}

	/**
	 * @return array
	 */
	public function getNeighborhoodCashe() {
		return $this->neighborhoodCashe;
	}

	/**
	 * @param $waves
	 */
	public function setWaves($waves){
		$this->wave = $waves;
	}
}