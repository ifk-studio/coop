<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Utils\WaveGenerator;

/**
 * Class WaveGeneratorTest
 *
 * @property WaveGenerator $WaveGenerator
 *
 */
class WaveGeneratorTest extends PHPUnit_Framework_TestCase {
	public $data;
	public $baseHp;
	public $coefficient;
	public $waveId;
	public $WaveGenerator;

	protected function setUp() {
		$this->data = array(
			array(
				'mobid' => 1,
				'mob_health' => 30,
				'wave_start' => 1,
				'incr_value' => 5,
				'incr_step' => 1,
				'limit' => 8,
				'wave_end' => 10,
				'decr_value' => 1,
				'decr_step' => 1,
			),
			array(
				'mobid' => 7,
				'mob_health' => 65,
				'wave_start' => 1,
				'incr_value' => 3,
				'incr_step' => 1,
				'limit' => 5,
				'wave_end' => 10,
				'decr_value' => 1,
				'decr_step' => 1,
			),
			array(
				'mobid' => 13,
				'mob_health' => 130,
				'wave_start' => 1,
				'incr_value' => 3,
				'incr_step' => 1,
				'limit' => 5,
				'wave_end' => 40,
				'decr_value' => 1,
				'decr_step' => 1,
			),
			array(
				'mobid' => 19,
				'mob_health' => 330,
				'wave_start' => 1,
				'incr_value' => 3,
				'incr_step' => 1,
				'limit' => 5,
				'wave_end' => 50,
				'decr_value' => 1,
				'decr_step' => 2,
			),
		);
		$this->baseHp = 5000;
		$this->coefficient = 0.5;
		$this->waveId = 4;
		$this->WaveGenerator = new WaveGenerator($this->waveId, $this->data, $this->baseHp, $this->coefficient);
	}


	public function testHealthCapCalculate() {
		$expect = function($baseHp, $coefficient, $waveNumber) {
			return $baseHp * (1.0 + $coefficient * $waveNumber);
		};

		$this->assertEquals($expect(100, 1, 1), $this->WaveGenerator->healthCapCalculate(100, 1, 1));
		$this->assertEquals($expect(500, 0.5, 10), $this->WaveGenerator->healthCapCalculate(500, 0.5, 10));
		$this->assertEquals($expect(1000, 0, 15), $this->WaveGenerator->healthCapCalculate(1000, 0, 15));
	}

	public function testCalculateMobCount(){
		$mobHp = 100;
		$mobMaxCount = 10;
		$getHealthCap = $this->WaveGenerator->getHealthCap();
		$expect = min((int)($getHealthCap / $mobHp), $mobMaxCount);;
		$result = $this->WaveGenerator->calculateMobCount($mobHp, $mobMaxCount);

		$this->assertEquals($expect, $result);
		$this->assertEquals($expect - $result * $mobHp, $this->WaveGenerator->getHealthCap());
	}

	public function testIncreaseMobsCount() {
		$waveStart = 2;
		$incrValue = 2;
		$incrStep = 2;
		$limit = 5;

		$data = (Object)array(
			'wave_start' => $waveStart,
			'incr_value' => $incrValue,
			'incr_step' => $incrStep,
			'limit' => $limit,
		);
		$mobsCount = 2;

		$expect = function($mobsCount, $data) {
			$waveIncrementDiff = $this->WaveGenerator->getWaveId() - $data->wave_start;
			$incrementValue = $data->incr_value;
			$incrementStep = $data->incr_step;

			if ($waveIncrementDiff != 0) {
				$mobsCount += (int)($incrementValue * ($waveIncrementDiff / $incrementStep));
			}
			return min($data->limit, $mobsCount);
		};

		$this->assertEquals($expect($mobsCount, $data), $this->WaveGenerator->increaseMobsCount($mobsCount, $data));
	}

	public function testDecreaseMobsCount() {
		$waveEnd = 2;
		$decrValue = 2;
		$decrStep = 2;

		$data = (Object)array(
			'wave_end' => $waveEnd,
			'decr_value' => $decrValue,
			'decr_step' => $decrStep,
		);
		$mobsCount = 2;

		$expect = function($mobsCount, $data) {
			$waveDecrementDiff = $this->WaveGenerator->getWaveId() - $data->wave_end;
			$decrementValue = $data->decr_value;
			$decrementStep = $data->decr_step;

			if ($this->WaveGenerator->getWaveId() >= $data->wave_end) {
				$mobsCount--;
				if ($waveDecrementDiff != 0) {
					$mobsCount -= (int)($decrementValue * ($waveDecrementDiff / $decrementStep));
				}
			}
			return max(0, $mobsCount);
		};

		$this->assertEquals($expect($mobsCount, $data), $this->WaveGenerator->decreaseMobsCount($mobsCount, $data));
	}
}