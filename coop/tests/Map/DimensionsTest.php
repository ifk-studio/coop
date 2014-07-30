<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Map\Dimensions;
use Coop\Map\Location;

/**
 * Class DimensionsTest
 *
 * @property Dimensions $Dimensions
 *
 */
class DimensionsTest extends PHPUnit_Framework_TestCase
{
	public function testCreate(){
		$dimensions = new Dimensions();
		$this->assertEquals(Dimensions::MIN_HEIGHT, $dimensions->getHeight());
		$this->assertEquals(Dimensions::MIN_WIDTH, $dimensions->getWidth());

		$height = round(1, 100);
		$width = round(1, 100);
		$dimensions = new Dimensions($width, $height);
		$this->assertEquals($height, $dimensions->getHeight());
		$this->assertEquals($width, $dimensions->getWidth());
	}

	public function testSet(){
		$dimensions = new Dimensions();
		$this->assertEquals(Dimensions::MIN_HEIGHT, $dimensions->getHeight());
		$this->assertEquals(Dimensions::MIN_WIDTH, $dimensions->getWidth());

		$height = round(1, 100);
		$width = round(1, 100);
		$dimensions->set($width, $height);
		$this->assertEquals($height, $dimensions->getHeight());
		$this->assertEquals($width, $dimensions->getWidth());
	}

	public function testGetArea(){
		$height = round(1, 100);
		$width = round(1, 100);
		$dimensions = new Dimensions($width, $height);
		$expected = function(Location $base, $width, $height) {
			$area = array();
			for($x = 0; $x < $width; $x ++) {
				for($y = 0; $y < $height; $y ++) {
					$area[] = new Location($base->x + $x, $base->y + $y);
				}
			}
			return $area;
		};

		$place = new Location(rand(0, 10), rand(0, 10));
		$result = $dimensions->getArea($place);
		$expect = $expected($place, $width, $height);
		foreach ($result as $id => $location) {
			$this->assertTrue($location->equal($expect[$id]));
		}
	}

}

