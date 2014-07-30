<?php

namespace Coop\Objects;

abstract class DynamicGameObjects implements \Iterator {
	protected $elements = array();
	protected $position = 0;

	public  function __construct(){
		$this->rewind();
	}
	abstract public function append($objectParams);

	/**
	 * delete element by key and rearrange $this->elements
	 *
	 * @param $key
	 */
	public function delete($key){
		if(!empty($this->elements[$key])) {
			unset($this->elements[$key]);
			$this->rearrange();
		}
	}

	/**
	 * rearrange elements
	 */
	public function rearrange(){
		$elements = array();
		foreach($this->elements as $element) {
			$elements[] = $element;
		}
		$this->elements = $elements;
		$this->rewind();
	}

	/**
	 * @return mixed
	 */
	public function current() {
		return $this->elements[$this->position];
	}

	/**
	 * @return int
	 */
	public function key() {
		return $this->position;
	}

	public function next() {
		$this->position ++;
	}

	public function rewind() {
		$this->position = 0;
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return ($this->position < count($this->elements));
	}

	/**
	 * @return array
	 */
	public function getAll(){
		return $this->elements;
	}
}
