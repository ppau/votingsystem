<?php
require_once('simpletest/autorun.php');
require_once('/Users/admin/Sites/src/finitefield.php');
class TestOfFiniteField extends UnitTestCase {
	function testPrimeFieldAdd(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 5);
		$B = new primeFieldValue($field, 7);
		$C = $A->add($B);
		$this->assertTrue($C->asString = "1");
		$C = $field->add($A, $B);
		$this->assertTrue($C->asString = "1");
	}
	function testPrimeFieldNegate(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 5);
		$C = $A->negate();
		$this->assertTrue($C->asString = "6");
		$C = $field->negate($A);
		$this->assertTrue($C->asString = "6");
	}
	function testPrimeFieldSubtract(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 5);
		$B = new primeFieldValue($field, 7);
		$C = $A->subtract($B);
		$this->assertTrue($C->asString = "9");
		$C = $field->subtract($A, $B);
		$this->assertTrue($C->asString = "9");
	}
	function testPrimeFieldDouble(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 5);
		$C = $A->double();
		$this->assertTrue($C->asString = "10");
		$C = $field->double($A);
		$this->assertTrue($C->asString = "10");
	}
	
	function testPrimeFieldMultiply(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 5);
		$B = new primeFieldValue($field, 7);
		$C = $A->multiply($B);
		$this->assertTrue($C->asString = "2");
		$C = $field->multiply($A, $B);
		$this->assertTrue($C->asString = "2");
	}
	function testPrimeFieldInvert(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 5);
		$C = $A->invert();
		$this->assertTrue($C->asString = "9");
		$C = $field->invert($A);
		$this->assertTrue($C->asString = "9");
	}
	function testPrimeFieldDivide(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 5);
		$B = new primeFieldValue($field, 7);
		$C = $A->divide($B);
		$this->assertTrue($C->asString = "8");
		$C = $field->divide($A, $B);
		$this->assertTrue($C->asString = "8");
	}
	function testPrimeFieldSquare(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 5);
		$C = $A->square();
		$this->assertTrue($C->asString = "3");
		$C = $field->square($A);
		$this->assertTrue($C->asString = "3");
	}
	
	function testPrimeFieldEquals(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 5);
		$B = new primeFieldValue($field, 5);
		$this->assertTrue($A->equals($B));
		$B = new primeFieldValue($field, 6);
		$this->assertFalse($A->equals($B));
	}
	function testPrimeFieldIsZero(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 0);
		$this->assertTrue($A->isZero());
		$B = new primeFieldValue($field, 1);
		$this->assertFalse($B->isZero());
	}
	function testPrimeFieldIsOne(){
		$field = new primeField(11);
		$A = new primeFieldValue($field, 0);
		$this->assertFalse($A->isOne());
		$B = new primeFieldValue($field, 1);
		$this->assertTrue($B->isOne());
	}
}