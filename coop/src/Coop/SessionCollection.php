<?php

namespace Coop;

use Coop;
use Ratchet\ConnectionInterface;

class SessionCollection{
	protected static $instance;
	protected $sessions;
	protected $CI;

	protected function __construct() {
		$this->sessions = array();
		$this->CI =& get_instance();
		$config = Coop\Configs\MainConfig::getInstance();
	}

	protected function __clone() {
	}

	public static function getInstance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @param ConnectionInterface $master
	 *
	 * @return bool|int
	 */
	public function add($master) {
		$session = new Session($this->CI, $master);
		if (isset($this->sessions[$session->id])) {
			return false;
		}
		$this->sessions[$session->id] = $session;
		return $session->id;
	}

	/**
	 * @param $sessionId
	 *
	 * @return null|Session
	 */
	public function get($sessionId) {
		return isset($this->sessions[$sessionId]) ? $this->sessions[$sessionId] : null;
	}

	/**
	 * @param string $userName
	 * @param string $password
	 *
	 * @return null|array
	 */
	public function auth($userName, $password) {
		$this->CI->load->model('User_model', 'UserModel', TRUE);
		return $this->CI->UserModel->auth($userName, $password);
	}

	/**
	 * @return array
	 */
	public function listSession() {
		$sessions = array();
		if(!empty($this->sessions)) {
			foreach ($this->sessions as $session) {
				if(!$session->isFull()) {
					$sessions[] = array(
						'id' => $session->id,
						'master' => $session->getFirst()
					);
				}
			}
		}
		return $sessions;
	}

	/**
	 * @param int $sessionId
	 */
	public function delete($sessionId){
		unset($this->sessions[$sessionId]);
	}
}
