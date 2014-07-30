<?php

namespace Coop\Configs\Parsers;

use Coop\Interfaces\ParseConfigInterface;

class Defender implements ParseConfigInterface {

	public static function parse(Array $info){
		return array(
			'type' => $info['type'],
			'details' => $info['details'],
			'levels' => array_merge(array(
				'1' => array(
					'price' => 0,
					'gun' => $info['details']
				)), $info['levels'])
		);
	}
}