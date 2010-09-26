<?php
require_once('definitions.php');
require_once('finitefield.php');
require_once('group.php');
require_once('standardCurves.php');
function getX($P , $group){
	$P = $P->scale();
	return new primeFieldValue($group->n_field,$P->x->asString());
}
function ecBlindness($M, $Rcap, $group, $hash){
	$H = hash($hash, $M);
	$e = new primeFieldValue($group->n_field,$H, 16);
	$alpha = $group->n_field->randomMemberNOZero(); //a = rand Zn
	$beta  = $group->n_field->randomMemberNOZero(); //b = rand Zn
	//$alpha = new primeFieldValue($group->n_field, 'ff38ae17683837fb58fd80110eebee59f81fd378b134eb265bd148bbf35aef02', 16);
	//$beta  = new primeFieldValue($group->n_field, '87379bbb01986ac5b37629f6050d1eaaee756548cb718768e523c85f6504096d', 16);
	
	$R1 = $group->G->intMultiply($beta->asString());//R1 = bG
	
	$R2 = $Rcap->intMultiply($alpha->asString());	//R2 = aRcap
	$R = $R1->add($R2);								//R = aRcap + bG
		
	$r = getX($R, $group);							//r = Rx
	$rinv = $r->invert();							//rinv =Rx^-1
	
	$rcap = getX($Rcap, $group);					//rcap = Rcapx
	
	$hcap1 = $rinv->multiply($rcap);				//hcap1 = Rx^1*Rcapx
	$hcap2 = $alpha->multiply($e);					//hcap2 = ha
	$hcap  = $hcap1->multiply($hcap2);				//hcap = ahRcapxRx^-1
	return array($hcap, $beta, $R);
}
function ecBlindSign($Rcap, $d, $hcap, $k, $group){
	$rcap = getX($Rcap, $group);					//rcap = Rcapx
	$scap1 = $rcap->multiply($d);					//scap1= Rcapx*d
	$scap2 = $k->multiply($hcap);					//scap2= hcap*k
	$scap = $scap1->add($scap2);					//scap = hcap*k + Rcap*d
	return $scap;
}
function ecUnblindness($scap, $R, $Rcap, $M, $beta, $group, $hash){
	$H = hash($hash, $M);
	$e = new primeFieldValue($group->n_field,$H, 16);
	$s2 = $beta->multiply($e);						//s2 = h*Beta
	
	$rcap = getX($Rcap, $group);					//rcap = Rcapx
	$rcapinv = $rcap->invert();						//rcapinv = Rcapx^-1
	
	$r = getX($R, $group);							//r = Rx
	$rrcapinv = $r->multiply($rcapinv);				//rrcapinv =Rx*Rcapx^-1 
	$s1 = $scap->multiply($rrcapinv);				//s1=scap*Rx*Rcapx^-1
	$s = $s1->add($s2);								//s =scap*Rx*Rcapx^-1 + h*Beta
	return $s;
}
function ecBlindVerify($R, $s, $M, $Q, $group, $hash){
	$H = hash($hash, $M);
	$e = new primeFieldValue($group->n_field,$H, 16);
	
	$L1 = $R->intMultiply($e->asString());
		
	$r = getX($R, $group);
	$L2 = $Q->intMultiply($r->asString());	
	
	$L = $L1->add($L2);
	$R = $group->G->intMultiply($s->asString());
	$result = $R->equals($L);
	return $result;
}
/*
//definitions
$group = new StandardCurve('P256');
$M = "This is only a test message. It is 48 bytes long";
$hash= 'sha256';

//key generation
$d =  new primeFieldValue($group->n_field, '6eedf9e45d6e6a4e5dce2f7341d7aaabb55f4ae2b04c2a591410324b41eda2e4', 16);
$Q = $group->G->intMultiply($d->asString());
$k = new primeFieldValue($group->n_field, 'c083d8a342125e2f25c11628c3e0f85e7a5bc75445fa4c9cd8b6ceb4b486a842', 16);
$Rcap = $group->G->intMultiply($k->asString());

//d, k, Rcap signer, Q, group, hash public, Rcap, M user.
//blindness
list($hcap, $beta, $R) = ecBlindness($M, $Rcap, $group, $hash);
//send $hcap to signer
//scap,hcap, d, k, Rcap signer  Q public, Rcap, hcap, R, M user  
//blind signature
$scap = ecBlindSign($Rcap, $d, $hcap, $k, $group);
//send scap to user
//
//unblindness
$s    = ecUnblindness($scap, $R, $Rcap, $M, $beta, $group, $hash);
//make s, R public.
//verify

echo (ecBlindverify($R, $s, $M, $Q, $group, $hash))? 'true':'false';
*/