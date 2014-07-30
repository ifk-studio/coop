<?php

namespace Coop\Utils;

use Coop\Configs\MainConfig;

class EnemyFabric {

	/**
	 * @param array $enemyParams
	 *
	 * @return array|null
	 */
	public static function fabric($enemyParams){
		if(strpos($enemyParams['title'], 'big') !== false) {
			$title = explode('_', $enemyParams['title']);
			$enemyParams['title'] = $title[0];
		}
		$MainConfig = MainConfig::getInstance();
		$enemyInfo = $MainConfig->getEnemy($enemyParams);
		if(empty($enemyInfo)) {
			return null;
		}
		$result = array(
			'id' => mt_rand(1, time()),
			'type' => $enemyParams['title'],
			'level' => $enemyParams['level'],
			'health' => $enemyInfo[Inflector::camelize($enemyParams['title'])]['levels'][$enemyParams['level']]['full_life'] * 2
		);

		return $result;
	}
}