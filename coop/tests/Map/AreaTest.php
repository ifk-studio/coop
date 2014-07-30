<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Map\Area;
use Coop\Map\Location;

/**
 * Class AreaTest
 *
 * @property Area $Area
 *
 */
class AreaTest extends PHPUnit_Framework_TestCase
{
	public function testCreate(){
		$radius = rand(0, 10);
		$area = new Area($radius);

		$this->assertEquals($radius, $area->getRadius());
	}

	public function testSetRadius(){
		$area = new Area();

		$this->assertEquals(0, $area->getRadius());

		$radius = rand(0, 10);
		$area->setRadius($radius);
		$this->assertEquals($radius, $area->getRadius());
	}

	public function testGetArea(){
		$radius = rand(0, 10);
		$area = new Area($radius);

		$location = new Location(rand(1, 5), rand(1, 5));

		$expect = array();

		for($distance = 0; $distance <= $radius; $distance++) {
			array_merge($expect, $location->neighborhood($distance));
		}

		$result = $area->getArea($location);
		$this->assertEquals($expect, $result);
	}
}

