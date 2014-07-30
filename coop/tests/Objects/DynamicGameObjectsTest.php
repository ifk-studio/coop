<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Objects\DynamicGameObjects;

class DynamicObject extends DynamicGameObjects {

	public function append($objectParams) {
		$this->elements[] = $objectParams;
	}
}

/**
 * Class DynamicObjectTest
 *
 * @property DynamicObject $DynamicObject
 *
 */
class DynamicObjectTest extends PHPUnit_Framework_TestCase {
	protected $DynamicObject;

	protected function setUp() {
		$this->DynamicObject = new DynamicObject();
	}

	public function testDeleteRearrange() {
		$this->DynamicObject->append('test1');
		$this->DynamicObject->append('test2');
		$this->DynamicObject->append('test3');
		$this->DynamicObject->append('test4');
		$this->DynamicObject->append('test5');

		$this->DynamicObject->next();
		$this->DynamicObject->delete(1);

		$this->assertEquals(array('test1', 'test3', 'test4', 'test5'), $this->DynamicObject->getAll());

		$this->DynamicObject->delete(0);
		$this->assertEquals(array('test3', 'test4', 'test5'), $this->DynamicObject->getAll());

		$this->DynamicObject->delete(2);
		$this->assertEquals(array('test3', 'test4'), $this->DynamicObject->getAll());

		$this->DynamicObject->delete(2);
		$this->assertEquals(array('test3', 'test4'), $this->DynamicObject->getAll());
	}

	public function testCurrent() {
		$this->DynamicObject->append('test1');
		$this->DynamicObject->append('test2');
		$this->DynamicObject->append('test3');

		$this->DynamicObject->rewind();
		$this->assertEquals('test1', $this->DynamicObject->current());

		$this->DynamicObject->next();
		$this->assertEquals('test2', $this->DynamicObject->current());

		$this->DynamicObject->next();
		$this->assertEquals('test3', $this->DynamicObject->current());
	}

	public function testKeyRewindNext() {
		$this->DynamicObject->rewind();
		$this->assertEquals(0, $this->DynamicObject->key());
		$this->DynamicObject->next();
		$this->assertEquals(1, $this->DynamicObject->key());
		$this->DynamicObject->next();
		$this->assertEquals(2, $this->DynamicObject->key());
		$this->DynamicObject->rewind();
		$this->assertEquals(0, $this->DynamicObject->key());
	}

	public function testValid() {
		$this->DynamicObject->append('test1');
		$this->DynamicObject->append('test2');
		$this->DynamicObject->append('test3');

		$this->DynamicObject->rewind();
		$this->assertTrue($this->DynamicObject->valid());

		$this->DynamicObject->next();
		$this->assertTrue($this->DynamicObject->valid());

		$this->DynamicObject->next();
		$this->assertTrue($this->DynamicObject->valid());

		$this->DynamicObject->next();
		$this->assertFalse($this->DynamicObject->valid());
	}
}
