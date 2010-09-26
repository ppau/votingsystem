<?php
require_once('definitions.php');
require_once('finitefield.php');
Class elipticCurve implements group {
	//-----------------------------------------------------------------------------
    //----                   Curve Properties                                  ----
	//-----------------------------------------------------------------------------
	public $p = NULL;
	public $a = NULL;
	public $b = NULL;
	public $G = NULL;
	public $n = NULL;
	public $h = NULL;
	
	//-----------------------------------------------------------------------------
	//----                    Bottled Finite Fields                            ----
	//-----------------------------------------------------------------------------
	
	public $p_field = NULL;
	public $n_field = NULL;
	
	public function zero(){   //returns GroupValue such that A + this.zero = A
		return new elipticCurveValue($this, 0, 0);
	}
	public function equals($A, $B){
		if (!($A instanceof elipticCurveValue)){
			trigger_error("Not elipticCurveValue", E_USER_ERROR);
		}
		if (!($A->elipticCurve == $this)){
			trigger_error("not elements of the same FiniteField", E_USER_ERROR);
		}
		if (!($B instanceof elipticCurveValue)){
			trigger_error("Not elipticCurveValue", E_USER_ERROR);
		}
		if (!($B->elipticCurve == $this)){
			trigger_error("not elements of the same FiniteField", E_USER_ERROR);
		}
		return $A->equals($B);
	}
	public function generator(){
		return $this->G;
	}
	public function asJSONArray() {
		return array(
			'name'=>'custom',
			'curveDescriptor'=>array(
					'p'=>gmp_strval($this->p, 16),
					'a'=>$this->a->asString(16),
					'b'=>$this->b->asString(16),
					'Gx'=>$this->G->x->asString(16),
					'Gy'=>$this->G->y->asString(16),
					'n'=>gmp_strval($this->n, 16),
					'h'=>gmp_strval($this->h, 16)
				)
			);
	}
	public function asJSON() {
		return json_encode($this->asJSONArray);
	}
	public function randomMember(){
		$temp1 = gmp_sub($this->n, 1);
		$temp2 = randGMPVar(0, $temp1);
		return $this->intMultiply($temp2, $this->G);
	}
	public function randomMemberNOZero(){
		$temp1 = gmp_sub($this->n, 1);
		$temp2 = randGMPVar(1, $temp1);
		return $this->intMultiply($temp2, $this->G);
	}

	public function add($A , $B){
		return $A->add($B);
	}
	public function negate($A){
		return $A->negate();
	}
	public function subtract($A , $B){
		return $A->subtract($B);
	}
	public function double($A){
		return $A->double();
	}
	public function intMultiply($k , $A, $base=0){
		return groupMultiply ($k, $A, $base);
	}
	public function __construct($p , $a, $b, $Gx, $Gy, $n, $h , $base=0) {
		$this->p = newGMPVar($p, $base);
		$this->p_field = new primeField($p, $base);
		$this->a = new primeFieldValue($this->p_field, $a, $base);
		$this->b = new primeFieldValue($this->p_field, $b, $base);
		$this->G = new elipticCurveValue($this, $Gx, $Gy, $base);
		$this->n = newGMPVar($n, $base);
		$this->n_field = new primeField($n, $base);
		$this->h = newGMPVar($h, $base);
	}
}

Class elipticCurveValue implements groupValue{
	public $elipticCurve;
	public $x;
	public $y;
	public $z; 
	
	public function group(){
		return $this->elipticCurve;
	}
	public function copy(){
		$x_c = $this->x->copy();
		$y_c = $this->y->copy();
		$z_c = $this->z->copy();
		return new elipticCurveValue($this->elipticCurve, $x_c, $y_c, 0, $z_c);
	}
	public function asString($base = 10){
		$temp = $this->scale();
		return "x: " . $temp->x->asString($base) . " y: " . $temp->y->asString($base);
	}
	public function asArrayString($base = 10){
		$temp = $this->scale();
		return array('x'=>gmp_strval($temp->x->asBigInt(), $base), 'y'=>gmp_strval($temp->y->asBigInt(), $base));
	}
	public function asArrayBigInt(){
		$temp = $this->scale();
		$y = $temp->y->asArrayBigInt();
		$x = $temp->x->asArrayBigInt();
		return array(0 => $x[0], 1 => $y[0]);
	}
	
	public function asJSONArray(){
		$temp = $this->scale();
		return array(
			'CurveID'=>$temp->elipticCurve->asJSONArray(),
			'x'=>gmp_strval($temp->x->asBigInt(), 16),
			'y'=>gmp_strval($temp->y->asBigInt(), 16)
		);
	}
	public function asJSON() {
		return json_encode($this->asJSONArray());
	}
	public function isZero(){
		return $this->equals($this->elipticCurve->zero());
	}
	
	public function equals($A){
		$u1 = $this->x->multiply($A->z);
		$u2 = $A->x->multiply($this->z);
		$v1 = $this->y->multiply($A->z);
		$v2 = $A->y->multiply($this->z);
		return ($u1->equals($u2) && $v1->equals($v2));
	}
	
	public function add($A){
		if ($this->isZero()){
			return $A->copy();
		}
		if ($A->isZero()){
			return $this->copy();
		}
		$u1 = $this->y->multiply($A->z);
		$u2 =    $A->y->multiply($this->z);
		$v1 = $this->x->multiply($A->z);
		$v2 =    $A->x->multiply($this->z);
		if($v1->equals($v2)){
			if($u1->equals($u2)){
				return $this->double();
			} else {
				return $this->elipticCurve->zero()->copy();
			} 
		}
		$u    = $u1->subtract($u2);
		$u_2  =  $u->square();
		$v    = $v1->subtract($v2);
		$v_2  =  $v->square();
		$v_2v2= $v2->multiply($v_2);
		$v_3  =  $v->multiply($v_2);
		$w    =  $A->z->multiply($this->z);
		$a1   =  $w->multiply($u_2);
		$a2   = $v_2v2->double();
		$a3   = $a1->subtract($v_3);
		$a    = $a3->subtract($a2);
		$x    =  $a->multiply($v);
		$y1   =  $v_2v2->subtract($a);
		$y2   =  $u->multiply($y1);
		$y3   = $u2->multiply($v_3);
		$y    = $y2->subtract($y3);
		$z    = $v_3->multiply($w);
		return new elipticCurveValue($this->elipticCurve, $x, $y, 0, $z);
	}
	public function negate(){
		return new elipticCurveValue($this->elipticCurve, $this->x->copy(), $this->y->negate(), 0, $this->z->copy());
	}
	public function subtract($A){
		return $this->add($A->negate());
	}
	public function double(){
		if ($this->isZero()){
			return $this->elipticCurve->zero()->copy();
		}
		$_3 = new primeFieldValue($this->elipticCurve->p_field, 3);
		$_4 = new primeFieldValue($this->elipticCurve->p_field, 4);
		$_8 = new primeFieldValue($this->elipticCurve->p_field, 8);
		$w1 = $this->z->square();
		$w2 = $this->elipticCurve->a->multiply($w1);
		$w3 = $this->x->square();
		$w4 =   $w3->multiply($_3);
		$w  = $w2->add($w4);
		$s  = $this->y->multiply($this->z);
		$b1 = $this->y->multiply($this->x);
		$b  = $b1->multiply($s);
		$h1 = $w->square();
		$h2 = $b->multiply($_8);
		$h  = $h1->subtract($h2);
		$x1 = $h->multiply($s);
		$x  = $x1->double();
		$y1 = $b->multiply($_4);
		$y2 = $y1->subtract($h);
		$y3 = $y2->multiply($w);
		$y4 = $this->y->square();
		$y5 = $s->square();
		$y6 = $y4->multiply($y5);
		$y7 = $y6->multiply($_8);
		$y  = $y3->subtract($y7);
		$z1 = $s->square();
		$z2 = $s->multiply($z1);
		$z  = $z2->multiply($_8);
		return new elipticCurveValue($this->elipticCurve, $x, $y, 0, $z);
	}
	public function intMultiply($k, $base=0){
		return groupMultiply ($k, $this, $base);
	}
	
	public function scale(){
		$a = $this->z->invert();
		$b = $this->x->multiply($a);
		$c = $this->y->multiply($a);
		return new elipticCurveValue($this->elipticCurve, $b, $c, 0);
	}
	
	public function __construct($elipticCurve, $x, $y , $base=0, $z = 1) {
		if ($elipticCurve instanceof elipticCurve){
			$this->elipticCurve = $elipticCurve;
		} else{
			trigger_error("Not eliptic Curve", E_USER_ERROR);
		}
		$sx = ($x instanceof primeFieldValue)? $x->copy() : new primeFieldValue($this->elipticCurve->p_field, $x, $base);
		$sy = ($y instanceof primeFieldValue)? $y->copy() : new primeFieldValue($this->elipticCurve->p_field, $y, $base);
		$sz = ($z instanceof primeFieldValue)? $z->copy() : new primeFieldValue($this->elipticCurve->p_field, $z, $base);
		$this->x = $sx;
		$this->y = $sy;
		$this->z = $sz;
		/*echo ($z instanceof primeFieldValue) . '<br>';
		if (($z instanceof primeFieldValue)){
		}else{
			$y_2 = $sy->square();
			$x_2 = $sx->square();
			$x_3 = $x_2->multiply($sx);
			$ax  = $sx->multiply($this->elipticCurve->a);
			$RHS1 = $ax->add($this->elipticCurve->b);
			$RHS = $RHS1->add($x_3);
			if (!($RHS->equals($y_2))){
				trigger_error("point not on curve", E_USER_ERROR);
			}
		}*/
	}
}