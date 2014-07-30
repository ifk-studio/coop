<?php

namespace Coop;

use Coop\Objects\Objects;

use Coop\Game;
use Coop\Objects\Ups;

/**
 * Class Session
 * @package Coop
 *
 * @property Game              $Game
 * @property \SplObjectStorage $participants
 * @property \SplQueue         $msgByTick
 * @property int               $id
 * @property CI_Model          $CI
 * @property Ups               $Ups
 *
 */
class Session extends Objects {
	const MAX_PARTICIPANTS = 2;

	protected $Game;
	protected $participants;
	protected $msgByTick;
	public $id;
	protected $CI;
	protected $Ups;

	public function __construct($CI, $participants) {
		$this->participants = new \SplObjectStorage();
		if (is_array($participants)) {
			foreach ($participants as $participant) {
				$this->participants->attach($participant);
			}
		} else {
			$this->participants->attach($participants);
		}
		$this->msgByTick = new \SplQueue();
		$this->CI = $CI;
		$this->id = time(); // довольно уникальный идентификатор для прототипа
		$this->Ups = new Ups();
	}

	/**
	 * @return bool
	 */
	public function isFull() {
		return ($this->participants->count() >= self::MAX_PARTICIPANTS);
	}

	/**
	 * @param $participant
	 */
	public function attach($participant) {
		if (!$this->participants->contains($participant)) {
			$this->participants->attach($participant);
		}
	}

	/**
	 * @param $participant
	 */
	public function detach($participant) {
		$this->participants->detach($participant);
	}

	/**
	 * @return \SplObjectStorage
	 */
	public function getParticipants() {
		return $this->participants;
	}

	/**
	 * @return object
	 */
	public function getFirst() {
		$this->participants->rewind();
		return $this->participants->current();
	}

	/**
	 * @param $you
	 *
	 * @return null
	 */
	public function partner($you) {
		foreach ($this->participants as $participant) {
			if ($participant != $you) {
				return $participant;
			}
		}
		return null;
	}

	/**
	 * @param bool  $from
	 * @param array $action
	 *
	 * @return mixed
	 */
	public function action($from, Array $action) {
		array_shift($action); //action
		array_shift($action); //user_id
		return $this->Game->action($from, $action);
	}

	/**
	 *
	 */
	public function endGame() {
		unset($this->Game);
		$this->Game = null;
	}

	/**
	 * @return bool
	 */
	public function gameStarted() {
		return ($this->Game !== null) && $this->upsFull();
	}

	/**
	 * @return bool
	 */
	public function startGame() {
		if (!$this->isFull()) {
			return false;
		}
		$this->Game = new Game($this->CI);
		$this->Game->makeEnemy();
		return $this->gameStarted();
	}

	/**
	 * @return null|array
	 */
	public function getMap() {
		return ($this->gameStarted()) ? $this->Game->getMap() : null;
	}

	/**
	 * @return null|array
	 */
	public function getWave() {
		return ($this->gameStarted()) ? $this->Game->getWave() : null;
	}

	/**
	 * @return null|array
	 */
	public function getPath() {
		return ($this->gameStarted()) ? $this->Game->getPath() : null;
	}

	/**
	 * @return Ups
	 */
	public function getUps() {
		return $this->Ups;
	}

	/**
	 * @return bool
	 */
	public function upsFull() {
		return $this->Ups->count() == self::MAX_PARTICIPANTS;
	}

	/**
	 * @return bool
	 */
	public function waveEnded() {
		return $this->Game->checkEndWave();
	}

	/**
	 * @param $from
	 *
	 * @return bool
	 */
	public function ready($from) {
		return $this->Game->ready($from->resourceId);
	}

	/**
	 * @return bool
	 */
	public function gameReady() {
		return $this->Game->gameReady();
	}

	/**
	 * @param $mobId
	 *
	 * @return bool
	 */
	public function isDeadMob($mobId){
		return $this->Game->isDeadMob($mobId);
	}
}