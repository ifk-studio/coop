<?php

namespace Coop\Configs\Parsers;

use Coop\Interfaces\ParseConfigInterface;

class Fortress implements ParseConfigInterface {

	public static function parse(Array $info) {
		return array(
			'type' => $info['type'],
			'can_move' => $info['can_move'],
			'first_state' => $info['first_state'],
			'details' => $info['details'],
		);
	}
}
