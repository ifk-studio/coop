<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Map\Map;

/**
 * Class MapTest
 *
 * @property Map $Map
 *
 */
class MapTest extends PHPUnit_Framework_TestCase
{
	protected $Map;

	protected function setUp(){
		$this->Map = new Map(100, 100);
	}

	public function testCreate()
	{
		$this->assertEquals(100, $this->Map->getWidth());
		$this->assertEquals(100, $this->Map->getHeight());
	}
}
