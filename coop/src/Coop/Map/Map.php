<?php

namespace Coop\Map;

use Coop\Objects\Objects;
use Coop\Map\PathFinder;
use Coop\Map\Location;
use Coop\Objects\StaticObject;

/**
 * Class Map
 * @package Coop\Map
 *
 * @property int $width
 * @property int $height
 * @property int $unpassability
 * @property array $map
 * $property \SplObjectStorage $staticObjects
 *
 */
class Map extends Objects {
	protected $width, $height;
	protected $unpassability;
	protected $map = array();
	protected $staticObjects;

	public function __construct($width = 100, $height = 100) {
		$this->width = (int) $width;
		$this->height = (int) $height;
		$this->unpassability = max($width, $height) + 1;
		$this->staticObjects = new\SplObjectStorage();
	}

	/**
	 * @return int
	 */
	public function getWidth(){
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getHeight(){
		return $this->height;
	}

	/**
	 * @return int
	 */
	public function unpassabilityValue(){
		return $this->unpassability;
	}

	/**
	 * initialization map
	 */
	public function blank(){
		for($x = -1; $x <= $this->width; $x++) {
			for($y = -1; $y <= $this->height; $y++) {
				if($x < 0 || ($x == $this->width) || $y < 0 || ($y == $this->height)) {
					$this->map[$x][$y] = $this->unpassability;
				} else {
					$this->map[$x][$y] = PathFinder::PASSABILITY;
				}
			}
		}
	}

	/**
	 * @param Location     $base
	 * @param StaticObject $object
	 */
	public function placeOnMap(Location $base, StaticObject $object){
		$objectInfo = array('location' => $base);
		$this->staticObjects->attach($object, $objectInfo);
		$area = $object->getArea($base);
		foreach ($area as $location) {
			$this->map[$location->x][$location->y] = $this->unpassability;
		}
	}
}
