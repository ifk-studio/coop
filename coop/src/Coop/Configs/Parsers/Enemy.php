<?php

namespace Coop\Configs\Parsers;

use Coop\Interfaces\ParseConfigInterface;

class Enemy implements ParseConfigInterface {

	public static function parse(Array $info) {
		$result = array(
			'type' => $info['type'],
			'details' => $info['details'],
			'isBig' => isset($info['isBig']) ? $info['isBig'] : false,
			'on_top' => isset($info['on_top']) ? $info['on_top'] : false,
			'levels' => array(
				'1' => $info['details']
			)
		);
		if(!empty($info['levels'])) {
			foreach ($info['levels'] as $id => $levelInfo) {
				$result['levels'][$id] = $levelInfo['details'];
			}
		}
		return $result;
	}
}
