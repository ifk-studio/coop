<?php

namespace Coop\Map;

use Coop\Interfaces\AreaInterface;
use Coop\Map\Location;

/**
 * Class Dimensions
 * @package Coop\Map
 *
 * @property int $width
 * @property int $height
 *
 */
class Dimensions implements AreaInterface{
	const MIN_WIDTH = 1;
	const MIN_HEIGHT = 1;
	protected $width;
	protected $height;

	public function __construct($width = Dimensions::MIN_WIDTH, $height = Dimensions::MIN_HEIGHT){
		$this->set($width, $height);
	}

	/**
	 * @param Location $base
	 *
	 * @return array
	 */
	public function getArea(Location $base){
		$area = array();
		for($x = 0; $x < $this->width; $x ++) {
			for($y = 0; $y < $this->height; $y ++) {
				$area[] = new Location($base->x + $x, $base->y + $y);
			}
		}
		return $area;
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
	 * @param $width
	 * @param $height
	 */
	public function set($width, $height){
		$this->height = abs($height);
		$this->width = abs($width);
	}
}