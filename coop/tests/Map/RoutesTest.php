<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Map\Routes;

/**
 * Class RoutesTest
 *
 * @property Routes $Routes
 *
 */
class RoutesTest extends PHPUnit_Framework_TestCase
{
	protected $Routes;

	protected function setUp(){
		$this->Routes = new Routes(array(1, 2, 3));
	}

	public function testGet(){
		$routes = $this->Routes->getRoutes();

		foreach ($routes as $route) {
			$this->assertEquals($route * 10, $this->Routes->get());
		}
		$this->assertEquals($routes[0] * 10, $this->Routes->get());
	}
}
