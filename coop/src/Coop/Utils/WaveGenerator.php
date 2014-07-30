<?php

namespace Coop\Utils;

use Coop\Objects\Objects;

/**
 * Class WaveGenerator
 * @package Coop\Map
 *
 * @property array $mobs
 * @property int $waveId
 * @property float $healthCap
 * @property float $defaultHealthCap
 * @property float $limitedHealthCap
 *
 */
class WaveGenerator extends Objects {
	protected $mobs = array();
	public $waveId;
	protected $healthCap;
	protected $defaultHealthCap;
	protected $limitedHealthCap;

	/**
	 * @param int $waveId
	 * @param array $wavesParams
	 * @param float $baseHp
	 * @param float $coefficient
	 */
	public function __construct($waveId, $wavesParams, $baseHp, $coefficient) {
		$this->healthCap = $this->healthCapCalculate($waveId, $baseHp, $coefficient);
		$this->defaultHealthCap = $this->healthCap;
		$this->waveId = $waveId;

		foreach ($wavesParams as $waveParams) {
			$mobsCount = $this->calculateMobsCount($waveParams);

			if ($mobsCount != 0) {
				$this->mobs[] = array(
					'mobid' => $waveParams->mobid,
					'mob_health' => $waveParams->mob_health,
					'mob_count' => $mobsCount
				);

				$this->etalonHealthCap += $waveParams->mob_health * $mobsCount;
			}
		}

		$this->fsort($this->mobs, 'mob_health');
	}

	/**
	 * @return int
	 */
	public function getWaveId(){
		return $this->waveId;
	}


	/**
	 * @param int   $waveNumber
	 * @param float $baseHp
	 * @param float $coefficient
	 *
	 * @return float
	 */
	public function healthCapCalculate($waveNumber, $baseHp, $coefficient){
		return $baseHp * (1.0 + $coefficient * $waveNumber);
	}

	/**
	 * @return array
	 */
	public function getLimitedWave() {
		return $this->healthCapLimited();
	}

	/**
	 * @return array
	 */
	public function getRawWave() {
		return $this->mobs;
	}

	/**
	 * @return float
	 */
	public function getDefaultHealthCap() {
		return $this->defaultHealthCap;
	}


	/**
	 * @return float
	 */
	public function getHealthCap() {
		return $this->healthCap;
	}

	/**
	 * @return float
	 */
	public function getLimitedHealthCap() {
		return $this->limitedHealthCap;
	}

	/**
	 * @return array
	 */
	public function healthCapLimited() {
		$resultWave = array();
		foreach ($this->mobs as $mob) {
			$mobHp = $mob['mob_health'];
			if ($this->healthCap < $mobHp) {
				break;
			}

			$mobCount = $this->calculateMobCount($mobHp, $mob['mob_count']);
			$resultWave[$mob['mobid']]['mob_count'] = $mobCount;
			$this->limitedHealthCap += ($mobHp * $mobCount);
		}

		return $resultWave;
	}

	/**
	 * @param float $mobHp
	 * @param int   $mobMaxCount
	 *
	 * @return int
	 */
	public function calculateMobCount($mobHp, $mobMaxCount){
		if($this->healthCap <= 0) {
			return 0;
		}
		$mobCount = min((int)($this->healthCap / $mobHp), $mobMaxCount);
		$this->healthCap -= ($mobHp * $mobCount);
		return $mobCount;
	}

	/**
	 * @param object $waveParams
	 *
	 * @return int
	 */
	public function calculateMobsCount($waveParams) {
		$mobsCount = 1;

		$mobsCount = $this->increaseMobsCount($mobsCount, $waveParams);
		$mobsCount = $this->decreaseMobsCount($mobsCount, $waveParams);

		return $mobsCount;
	}

	/**
	 * @param int $mobsCount
	 * @param object $waveParams
	 *
	 * @return int
	 */
	public function increaseMobsCount($mobsCount, $waveParams) {
		$waveIncrementDiff = $this->getWaveId() - $waveParams->wave_start;
		$incrementValue = $waveParams->incr_value;
		$incrementStep = $waveParams->incr_step;

		if ($waveIncrementDiff != 0) {
			$mobsCount += (int)($incrementValue * ($waveIncrementDiff / $incrementStep));
		}
		return min($waveParams->limit, $mobsCount);
	}

	/**
	 * @param $mobsCount
	 * @param $waveParams
	 *
	 * @return int
	 */
	public function decreaseMobsCount($mobsCount, $waveParams) {
		$waveDecrementDiff = $this->getWaveId() - $waveParams->wave_end;
		$decrementValue = $waveParams->decr_value;
		$decrementStep = $waveParams->decr_step;

		if ($this->getWaveId() >= $waveParams->wave_end) {
			$mobsCount--;
			if ($waveDecrementDiff != 0) {
				$mobsCount -= (int)($decrementValue * ($waveDecrementDiff / $decrementStep));
			}
		}
		return max(0, $mobsCount);
	}

}
