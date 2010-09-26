<?php
require_once('simpletest/autorun.php');
require_once('/Users/admin/Sites/src/finitefield.php');
class TestOfSHA256 extends UnitTestCase {
	function testSHA256(){
		$var = "";
		$this->assertTrue(hash('sha256', $var)==='e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');
		$var = "Test vector from febooti.com";
		$this->assertTrue(hash('sha256', $var)==='077b18fe29036ada4890bdec192186e10678597a67880290521df70df4bac9ab');
	}
}