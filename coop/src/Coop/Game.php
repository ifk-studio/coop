<?php

namespace Coop;

use Coop\Configs\MainConfig;
use Coop\Utils\EnemyFabric;
use Coop\Map\Routes;
use Coop\Views;

class Game {
	const LIVE = 0;
	const DEAD = 255;
	const FROM = true;
	const TO = false;
	protected $Routes;
	protected $CI;
	protected $MainConfig;
	protected $EnemyArray;
	protected $currentWave;
	protected $Fortress;
	protected $wave;
	protected $ready;

	public function __construct($CI, $routes = array(1, 2, 3, 4, 5, 6)) {
		$this->Routes = new Routes($routes);
		$this->CI = $CI;
		$this->MainConfig = MainConfig::getInstance();
		$this->setStartWave();
		$this->Fortress = $this->initFortress();
	}

	/**
	 * @return array
	 */
	public function initFortress() {
		$this->initReady();
		$fortress = MainConfig::getInstance()->getFortress();
		$this->Fortress = array(
			'level' => $fortress['Fortress']['details']['level'],
			'health' => $fortress['Fortress']['details']['full_life'] * Session::MAX_PARTICIPANTS
		);
		return $this->Fortress;
	}

	/**
	 * @return array|null
	 */
	public function makeEnemy() {
		$this->wave = $this->waveGenerate($this->currentWave);
		if (empty($this->wave)) {
			$this->setStartWave();
			return null;
		}
		$this->EnemyArray = array();
		foreach ($this->wave['monsters'] as &$param) {
			for ($i = 0; $i < $param['count']; $i++) {
				$enemy = EnemyFabric::fabric($param);
				$this->EnemyArray[$enemy['id']] = $enemy;
				$param['mobIds'][] = $enemy['id'];
			}
		}
		return $this->EnemyArray;
	}

	/**
	 * @return array|null
	 */
	public function nextWave() {
		$this->currentWave++;
		$this->initReady();
		return $this->makeEnemy();
	}

	public function setStartWave() {
		$this->currentWave = 1;
	}

	/**
	 * @param $waveId
	 *
	 * @return array
	 */
	public function waveGenerate($waveId) {
		$this->CI->load->model('Generator_Params_model', 'GeneratorParamModel', TRUE);
		$res = $this->CI->GeneratorParamModel->getFirst();
		$baseHp = $res->base_hp;
		$coefficient = $res->koef;

		$this->CI->load->model('Waves_Params_model', 'WavesParamModel', TRUE);
		$wavesParams = $this->CI->WavesParamModel->getWaveParams($waveId);

		$maps = $this->getMap();
		$WaveFormater = new Views\WaveFormater($this->CI, MainConfig::getInstance()->getRoutesByMap($maps[0]));
		return $WaveFormater->format(new Utils\WaveGenerator($waveId, $wavesParams, $baseHp, $coefficient));
	}

	public function getMap() {
		return array('map2');
	}

	public function getWave() {
		return $this->wave;
	}

	public function getPath() {
		return array('path');
	}

	/**
	 * @param bool  $direction
	 * @param array $action
	 *
	 * @return array
	 */
	public function action($direction, $action) {
		return ($direction == self::FROM) ? $this->fortressDamaged($action[1]) : $this->mobDamaged($action[0], $action[1]);
	}

	/**
	 * @param int $damage
	 *
	 * @return array
	 */
	protected function fortressDamaged($damage) {
		$this->Fortress['health'] = max(0, $this->Fortress['health'] - abs($damage));
		return array(
			'status' => ($this->Fortress['health'] === 0) ? self::DEAD : self::LIVE
		);
	}

	/**
	 * @param int $mobId
	 * @param int $damage
	 *
	 * @return array
	 */
	protected function mobDamaged($mobId, $damage) {
		if(empty($this->EnemyArray[$mobId])) {
			$result = array(
				'status' => self::DEAD,
				'mob_id' => $mobId
			);
		} else {
			$this->EnemyArray[$mobId]['health'] = max(0, $this->EnemyArray[$mobId]['health'] - abs($damage));
			$result = array(
				'status' => (empty($this->EnemyArray[$mobId]) || $this->EnemyArray[$mobId]['health'] === 0) ? self::DEAD : self::LIVE,
				'mob_id' => $mobId
			);
		}
		if ($this->checkEndWave()) {
			$this->nextWave();
		}
		return $result;
	}

	/**
	 * @return bool
	 */
	public function checkEndWave() {
		foreach ($this->EnemyArray as $mob) {
			if ($mob['health'] > 0) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param $gamerId
	 *
	 * @return bool
	 */
	public function ready($gamerId) {
		if (in_array($gamerId, $this->ready)) {
			return false;
		}
		$this->ready[] = $gamerId;
		return true;
	}

	/**
	 * @return bool
	 */
	public function gameReady() {
		return (count($this->ready) == Session::MAX_PARTICIPANTS);
	}

	/**
	 *
	 */
	public function initReady() {
		$this->ready = array();
	}

	/**
	 * @param $mobId
	 *
	 * @return bool
	 */
	public function isDeadMob($mobId) {
		return empty($this->EnemyArray[$mobId]);
	}
}