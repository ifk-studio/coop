<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Configs\MainConfig;

/**
 * Class AreaTest
 *
 * @property Area $Area
 *
 */
class MainConfigTest extends PHPUnit_Framework_TestCase
{
	public function testGetRoutesByMap(){
		$config = MainConfig::getInstance();
		$this->assertTrue(is_array($config->getRoutesByMap('map2')));
		print_r($config->getRoutesByMap('map2'));
		$result = $config->getRoutesByMap('map2000');
		$this->assertTrue(empty($result));
	}

}
