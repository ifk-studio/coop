<?php
require_once dirname(__DIR__) . '/InitTests.php';

use Coop\Utils;

/**
 * Class InflectorTest
 *
 */
class InflectorTest extends PHPUnit_Framework_TestCase
{
	public function testHumanize(){
		$this->assertEquals('Lower Case And Underscored', Utils\Inflector::humanize('lower_case_and_Underscored'));
		$this->assertEquals('CamelCase And Underscored', Utils\Inflector::humanize('CamelCase_and_Underscored'));
		$this->assertEquals('Lower Case  And Underscored', Utils\Inflector::humanize('lower_case__and_underscored'));
		$this->assertEquals('Oneword', Utils\Inflector::humanize('oneword'));
	}

	public function testCamelize(){
		$this->assertEquals('LowerCaseAndUnderscored', Utils\Inflector::camelize('lower_case_and_Underscored'));
		$this->assertEquals('CamelCaseAndUnderscored', Utils\Inflector::camelize('CamelCase_and_Underscored'));
		$this->assertEquals('LowerCaseAndUnderscored', Utils\Inflector::camelize('lower_case__and_underscored'));
	}

	public function testUnderscore(){
		$this->assertEquals('lower_case_and_underscored', Utils\Inflector::underscore('LowerCaseAndUnderscored'));
		$this->assertEquals('camel_case__and_underscored', Utils\Inflector::underscore('CamelCase_AndUnderscored'));
	}
}