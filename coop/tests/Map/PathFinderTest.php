<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Map\PathFinder;
use Coop\Map\Location;

/**
 * Class PathFinderTest
 *
 * @property PathFinder $PathFinder
 *
 */
class PathFinderTest extends PHPUnit_Framework_TestCase {
	protected $PathFinder;

	protected function setUp() {
		$this->PathFinder = new PathFinder();
	}

	public function testExistsInCache() {
		$this->PathFinder->init();
		$result = $this->PathFinder->getNeighborhoodCashe();
		$this->assertTrue(empty($result));

		$this->PathFinder->push(new Location(1, 2), 1);
		$this->PathFinder->push(new Location(5, 4), 2);

		$this->assertTrue($this->PathFinder->existsInCache(new Location(1, 2)));
		$this->assertTrue($this->PathFinder->existsInCache(new Location(5, 4)));

		$this->assertFalse($this->PathFinder->existsInCache(new Location(rand(6, 10), rand(5, 100))));
	}

	public function testPush() {
		$this->PathFinder->init();
		$result = $this->PathFinder->getNeighborhoodCashe();
		$this->assertTrue(empty($result));

		$this->PathFinder->push(new Location(1, 2), 1);
		$this->PathFinder->push(new Location(5, 4), 2);

		$result = $this->PathFinder->getNeighborhoodCashe();
		$this->assertFalse(empty($result));

		$this->assertTrue($result[0]['location']->equal(new Location(1, 2)));
		$this->assertEquals($result[0]['distance'], 1);

		$this->assertTrue($result[1]['location']->equal(new Location(5, 4)));
		$this->assertEquals($result[1]['distance'], 2);
		$this->assertEquals(2, count($result));

		$this->PathFinder->push(new Location(5, 4), 4);
		$this->assertEquals(2, count($result));
	}

	public function testPop() {
		$this->PathFinder->init();
		$result = $this->PathFinder->getNeighborhoodCashe();
		$this->assertTrue(empty($result));

		$this->PathFinder->push(new Location(1, 2), 1);
		$this->PathFinder->push(new Location(5, 4), 2);
		$this->assertEquals(count($this->PathFinder->getNeighborhoodCashe()), 2);

		$result = $this->PathFinder->pop();
		$this->assertTrue($result['location']->equal(new Location(1, 2)));
		$this->assertEquals($result['distance'], 1);
		$this->assertEquals(count($this->PathFinder->getNeighborhoodCashe()), 1);

		$result = $this->PathFinder->pop();
		$this->assertTrue($result['location']->equal(new Location(5, 4)));
		$this->assertEquals($result['distance'], 2);

		$result = $this->PathFinder->getNeighborhoodCashe();
		$this->assertTrue(empty($result));
	}

	/**
	 * tested only distance between $start and $end == 1 or 0
	 * another situation tested in testWaves and testShortestPath
	 *
	 */
	public function testNextLocation() {
		$start = new Location(2, 3);
		$result = $this->PathFinder->nextLocation($start, $start);
		$this->assertTrue($start->equal($result));

		$ends = $start->neighborhood(1);
		$result = $this->PathFinder->nextLocation($start, $ends[0]);
		$this->assertEquals(1, $start->distance($result));
	}

	public function testPushNeighborhood() {
		$start = new Location(2, 3);
		$expected = $start->neighborhood(1);
		$distance = 1;
		$neighborhoods = $this->PathFinder->pushNeighborhoods($start, $distance);
		foreach ($neighborhoods as $id => $neighborhood) {
			$this->assertTrue($expected[$id]->equal($neighborhood['location']));
			$this->assertEquals($distance, $neighborhood['distance']);
		}
	}

	public function testWaves() {
		$map = array(
			array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
			array(0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
			array(0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0),
			array(0, 1, 0, 1, 1, 1, 1, 1, 0, 0, 1, 0),
			array(0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
			array(0, 1, 1, 1, 1, 1, 1, 0, 1, 0, 1, 0),
			array(0, 1, 1, 0, 1, 1, 0, 0, 1, 0, 1, 0),
			array(0, 1, 1, 1, 1, 1, 0, 1, 1, 0, 1, 0),
			array(0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0),
			array(0, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 0),
			array(0, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 0),
			array(0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
			array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
		);
		$start = new Location(1, 2);
		$end = new Location (5, 3);
		$wave = $this->PathFinder->waves($start, $end, null);
		$expected = Array(
			1 => Array(
				2 => 0,
				1 => 1,
				3 => 1,
				0 => 2,
				4 => 2,
				5 => 3,
				6 => 4,
			),
			0 => Array(
				2 => 1,
				1 => 1,
				3 => 1,
				0 => 2,
				4 => 2,
				5 => 3,
				6 => 4,
			),
			2 => Array(
				2 => 1,
				1 => 1,
				3 => 1,
				0 => 2,
				4 => 2,
				5 => 3,
				6 => 4,
			),
			3 => Array(
				2 => 2,
				1 => 2,
				3 => 2,
				0 => 2,
				4 => 2,
				5 => 3,
				6 => 4,
			),
			4 => Array(
				2 => 3,
				1 => 3,
				3 => 3,
				0 => 3,
				4 => 3,
				5 => 3,
				6 => 4,
			),
			5 => Array(
				2 => 4,
				1 => 4,
				3 => 4,
			)
		);

		$this->assertEquals($expected, $wave);

		$wave = $this->PathFinder->waves($start, $end, $map);
		$expected = Array(
			1 => Array(
				2 => 0,
				1 => 1,
				3 => 1,
				0 => 4,
				4 => 2,
				5 => 3,
				6 => 4,
			),
			0 => Array(
				2 => 4,
				1 => 4,
				3 => 4,
				0 => 4,
				4 => 4,
				5 => 4,
				6 => 4,
			),
			2 => Array(
				2 => 1,
				1 => 1,
				3 => 1,
				0 => 4,
				4 => 2,
				5 => 3,
				6 => 4,
			),
			3 => Array(
				2 => 4,
				1 => 2,
				3 => 2,
				0 => 4,
				4 => 2,
				5 => 3,
				6 => 4,
			),
			4 => Array(
				2 => 3,
				1 => 3,
				3 => 3,
				0 => 4,
				4 => 3,
				5 => 3,
				6 => 4,
			),
			5 => Array(
				2 => 4,
				1 => 4,
				3 => 4,
			),
		);
		$this->assertEquals($expected, $wave);
	}

	public function testShortestPath(){
		$waves = Array(
			1 => Array(
				2 => 0,
				1 => 1,
				3 => 1,
				0 => 4,
				4 => 2,
				5 => 3,
				6 => 4,
			),
			0 => Array(
				2 => 4,
				1 => 4,
				3 => 4,
				0 => 4,
				4 => 4,
				5 => 4,
				6 => 4,
			),
			2 => Array(
				2 => 1,
				1 => 1,
				3 => 1,
				0 => 4,
				4 => 2,
				5 => 3,
				6 => 4,
			),
			3 => Array(
				2 => 4,
				1 => 2,
				3 => 2,
				0 => 4,
				4 => 2,
				5 => 3,
				6 => 4,
			),
			4 => Array(
				2 => 3,
				1 => 3,
				3 => 3,
				0 => 4,
				4 => 3,
				5 => 3,
				6 => 4,
			),
			5 => Array(
				2 => 4,
				1 => 4,
				3 => 4,
			),
		);
		$this->PathFinder->setWaves($waves);
		$start = new Location(1, 2);
		$end = new Location (5, 3);
		$path = $this->PathFinder->shortestPath($start, $end);
		$expected = Array(
			0 => new Location (5, 3),
			1 => new Location (4, 3),
			2 => new Location (3, 3),
			3 => new Location (2, 3),
		);

		$this->assertEquals($expected, $path);
	}
}
