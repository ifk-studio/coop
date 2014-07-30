<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Map\Location;

/**
 * Class LocationTest
 *
 * @property Location $Location
 *
 */
class LocationTest extends PHPUnit_Framework_TestCase
{
	protected $Location;

	protected function setUp(){
		$this->Location = new Location(0, 0);
	}

	public function testSet()
	{
		$x = 1;
		$y = 10;
		$this->Location->set($x, $y);
		$this->assertEquals($x, $this->Location->x);
		$this->assertEquals($y, $this->Location->y);
	}

	public function testDistance(){
		$this->Location->set(0, 0);
		$target = new Location(0, 0);
		$this->assertEquals($this->Location->distance($target), 0);

		$target->set(1,1);
		$this->assertEquals($this->Location->distance($target), 1);

		$target->set(10,2);
		$this->assertEquals($this->Location->distance($target), 10);

		$this->Location->set(9,0);
		$this->assertEquals($this->Location->distance($target), 2);

		$target->set(4, 3);
		$this->assertEquals($this->Location->distance($target), 5);
	}

	public function testNeighborhood() {
		$this->Location->set(5,3);

		$neighborhoods = $this->Location->neighborhood(0);
		$this->assertTrue($neighborhoods[0]->equal($this->Location));
		$this->assertTrue($neighborhoods[1]->equal($this->Location));
		$this->assertTrue($neighborhoods[2]->equal($this->Location));
		$this->assertTrue($neighborhoods[3]->equal($this->Location));

		$this->assertTrue($neighborhoods[4]->equal($this->Location));
		$this->assertTrue($neighborhoods[5]->equal($this->Location));
		$this->assertTrue($neighborhoods[6]->equal($this->Location));
		$this->assertTrue($neighborhoods[7]->equal($this->Location));

		$neighborhoods = $this->Location->neighborhood(1);
		$this->assertTrue($neighborhoods[0]->equal(new Location(5,2)));
		$this->assertTrue($neighborhoods[1]->equal(new Location(5,4)));
		$this->assertTrue($neighborhoods[2]->equal(new Location(4,3)));
		$this->assertTrue($neighborhoods[3]->equal(new Location(6,3)));

		$this->assertTrue($neighborhoods[4]->equal(new Location(4,2)));
		$this->assertTrue($neighborhoods[5]->equal(new Location(6,2)));
		$this->assertTrue($neighborhoods[6]->equal(new Location(4,4)));
		$this->assertTrue($neighborhoods[7]->equal(new Location(6,4)));

		$neighborhoods = $this->Location->neighborhood(3);
		$this->assertTrue($neighborhoods[0]->equal(new Location(5,0)));
		$this->assertTrue($neighborhoods[1]->equal(new Location(5,6)));
		$this->assertTrue($neighborhoods[2]->equal(new Location(2,3)));
		$this->assertTrue($neighborhoods[3]->equal(new Location(8,3)));

		$this->assertTrue($neighborhoods[4]->equal(new Location(2,0)));
		$this->assertTrue($neighborhoods[5]->equal(new Location(8,0)));
		$this->assertTrue($neighborhoods[6]->equal(new Location(2,6)));
		$this->assertTrue($neighborhoods[7]->equal(new Location(8,6)));
	}

	public function testEqual(){
		$this->Location->set(10, 9);
		$this->assertFalse($this->Location->equal(new Location(3, 4)));
		$this->assertTrue($this->Location->equal(new Location(10, 9)));
	}
}
