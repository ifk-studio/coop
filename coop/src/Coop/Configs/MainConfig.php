<?php

namespace Coop\Configs;

use Coop\Utils\Inflector;

/**
 * Class MainConfig
 * @package Coop\Configs
 *
 * @property RoutesConfig        $Routes
 * @property ObjectsConfig       $Objects
 * @property LevelsConfig        $Levels
 * @property CollectionsConfig   $Collections
 *
 */
class MainConfig {
	protected static $instance;
	protected static $Routes;
	protected static $Objects;
	protected static $Levels;
	protected static $Collections;

	protected function __construct() {
		foreach (array('Routes', 'Levels', 'Objects') as $config) {
			self::$$config = self::initConfig($config);
		}
	}

	protected function __clone() {
	}

	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @return int
	 */
	public static function getClientVersion() {
		return 1;
	}

	/**
	 * @return int
	 */
	public static function getConfigVersion() {
		return 2;
	}

	/**
	 * @return string
	 */
	public static function getConfigPath() {
		$configPath = 'config/coop/';

		return (strstr(APPPATH, 'api') ? '' : FCPATH) . APPPATH . $configPath;
	}

	/**
	 * @param string $type
	 *
	 * @return bool|array
	 */
	protected function initConfig($type) {
		$className = 'Coop\\Configs\\' . Inflector::camelize($type) . 'Config';
		$fileName = $this->getConfigPath() . Inflector::underscore($type) . '.json';
		if (!class_exists($className) || !file_exists($fileName)) {
			return false;
		}
		return new $className(json_decode(file_get_contents($fileName), true));
	}

	/**
	 * @return array
	 */
	public function getEnemy() {
		return self::$Objects->getEnemy();
	}

	/**
	 * @return array
	 */
	public function getFortress() {
		return self::$Objects->getFortress();
	}

	/**
	 * @param string $map
	 *
	 * @return array
	 */
	public function getRoutesByMap($map) {
		return self::$Routes->getRoutesByMap($map);
	}
}