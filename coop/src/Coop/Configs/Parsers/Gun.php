<?php

namespace Coop\Configs\Parsers;

use Coop\Interfaces\ParseConfigInterface;

class Gun implements ParseConfigInterface {
	/**
       "grenade": {
           "type": "gun",
           "width": 204,
           "height":128,
           "details": {
               "price": 50,
               "level": 1,
               "damage": 150,
               "delay": 1,
               "range": 3
            },
           "upgrades": {
                       "1":{"id": 1,"type": "damage","damage": 10,"upgradeStep": 0 ,"price": 270,"priceStep": 75},
                       "2":{"id": 2,"type": "range","range": 1,"upgradeStep": 0,"price": 200,"priceStep": 100}
                    },
           "levels": {
               "2": {
                   "price": 1320,
                   "details": {
                       "price": 50,
                       "level": 2,
                       "damage": 200,
                       "delay": 1,
                       "range": 5
                    }
                },
               "3": {
                   "price": 4000,
                   "details": {
                       "price": 50,
                       "level": 3,
                       "damage": 300,
                       "delay": 1,
                       "range": 7
                    }
                }
            }
        },
	*/
	public static function parse(Array $info){
		return array(
			'type' => $info['type'],
			'upgrades' => $info['upgrades'],
			'levels' => array_merge(array(
				'1' => array(
					'price' => 0,
					'details' => $info['details']
				)), $info['levels'])
		);
	}
}
