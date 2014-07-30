<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Views\WaveFormater;
use Coop\Db;
use Coop\Enemies\WaveGenerator;
use Coop\Map\Routes;

/**
 * Class WaveFormaterTest
 *
 * @property WaveFormater $WaveFormater
 * @property WaveGenerator $WaveGenerator
 *
 */
class WaveFormaterTest extends PHPUnit_Framework_TestCase
{
	protected $WaveFormater;
	protected $data;
	protected $baseHp, $coefficient, $waveId;

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

		$this->WaveFormater = new WaveFormater(new Db(null), new Routes(array(1,2,3,4,5,6)));
	}

	public function testToJSON(){
		$result = $this->WaveFormater->toJSON($this->WaveGenerator);
		print_r($result);
	}
}