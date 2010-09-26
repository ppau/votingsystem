<?php
require_once('simpletest/autorun.php');
require_once('/Users/admin/Sites/src/finitefield.php');
class TestOfbase64 extends UnitTestCase {
	function testbase64(){
		$str = 'This is an encoded string';
		$this->assertTrue(base64_encode($str)==='VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==');
		$str = 'VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==';
		$this->assertTrue(base64_decode($str)==='This is an encoded string');
	}
}