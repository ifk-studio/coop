<?php

namespace Coop\Map;

use Coop\Interfaces\AreaInterface;
use Coop\Map\Location;

/**
 * Class Area
 * @package Coop\Map
 *
 * @property int $radius
 *
 */
class Area implements AreaInterface{
	protected $radius;

	public function __construct($radius = 0){
		$this->setRadius($radius);
	}

	/**
	 * @param $radius
	 */
	public function setRadius($radius){
		$this->radius = abs($radius);
	}

	/**
	 * @param Location $base
	 *
	 * @return array
	 */
	public function getArea(Location $base){
		$area = array();
		for($distance = 0; $distance <= $this->radius; $distance++) {
			array_merge($area, $base->neighborhood($distance));
		}
		return $area;
	}

	/**
	 * @return int
	 */
	public function getRadius(){
		return $this->radius;
	}
}

