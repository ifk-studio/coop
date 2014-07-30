<?php

namespace Coop\Views;

use Coop\Objects\Objects;
use Coop\Interfaces\FormaterInterface;
use Coop\Utils\WaveGenerator;

/**
 * Class WaveFormater
 * @package Coop\Views
 *
 * @property Array       $Routes
 * @property CodeIgniter $CI
 *
 */
class WaveFormater extends Objects implements FormaterInterface {
	protected $Routes;
	protected $CI;

	public function __construct($CI, Array $routes) {
		$this->Routes = $routes;
		$this->CI = $CI;
	}

	/**
	 *
	 * {
	 * title: "troll",
	 * level: 4,
	 * route: "route1",
	 * time_to_start: 0,
	 * count: 5,
	 * delay: 2000
	 * },
	 *
	 * @param Objects $objects
	 *
	 * @return array
	 */
	public function format(Objects $objects, $to = null) {
		$result = array();
		if ($objects instanceof WaveGenerator) {
			$wave = $objects->getLimitedWave();

			$this->CI->load->model('Creatures_model', 'CreatureModel', TRUE);
			$creatures = $this->CI->CreatureModel->getCreature(array_keys($wave));
			$result = array(
				'time_to_start' => 0,
				'monsters' => array()
			);
			foreach ($wave as $mobId => $value) {

				$result['monsters'][] = array(
					"title" => preg_replace('/[1-90]/', '', $creatures[$mobId]['cr_altname']),
					"level" => $creatures[$mobId]['cr_level'],
					"route" => $this->Routes[array_rand($this->Routes)],
					"time_to_start" => 0,
					"count" => $value['mob_count'],
					"delay" => $this->getDelay($creatures[$mobId]['cr_title'])
				);
			}
		}
		return $result;
	}

	/**
	 * @param string $title
	 *
	 * @return int
	 */
	public function getDelay($title) {
		return in_array($title, array('tank_boss', 'heli_boss')) ? 10000 : mt_rand(3, 6) * 1000;
	}
}
