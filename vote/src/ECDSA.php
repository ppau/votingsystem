<?php
require_once('definitions.php');
require_once('finitefield.php');
require_once('group.php');
require_once('standardCurves.php');
function ecDSASign($M, $d, $group, $hash){
	//$k = new primeFieldValue($group->n_field, '580ec00d856434334cef3f71ecaed4965b12ae37fa47055b1965c7b134ee45d0', 16); //test
	$k = $group->n_field->randomMemberNOZero();
	$kinv = $k->invert();
	$R = $group->G->intMultiply($k->asString());
	$R = $R->scale();
	$r = new primeFieldValue($group->n_field,$R->x->asString());
	$H = hash($hash, $M);
	$e = new primeFieldValue($group->n_field,$H, 16);
	$s2 = $r->multiply($d);
	$s3 = $s2->add($e);
	$s = $s3->multiply($kinv);
	return array($r, $s);
}
function ecDSAVerify($r, $s, $M, $Q, $group, $hash){
	$H = hash($hash, $M);
	$e = new primeFieldValue($group->n_field,$H, 16);
	$w = $s->invert();
	$u1 = $w->multiply($e);
	$u2 = $w->multiply($r);
	$R1 = $group->G->intMultiply($u1->asString());
	$R2 = $Q->intMultiply($u2->asString());
	$R = $R1->add($R2);
	$R = $R->scale();
	$v = new primeFieldValue($group->n_field,$R->x->asString());
	$result = $v->equals($r);
	return $result;
}
/*echo '<code><pre>';
$group = new StandardCurve('P256');
$M = "This is only a test message. It is 48 bytes long";
$d = new primeFieldValue($group->n_field, '70a12c2db16845ed56ff68cfc21a472b3f04d7d6851bf6349f2d7d5b3452b38a', 16);
$Q = $group->G->intMultiply($d->asString());
$hash= 'sha256';

//list($r, $s) = ecDSASign($M, $d, $group, $hash);
$x = json_decode('{"r":"d41b7728c2fcf091547a66be309f975694ec3e9170b1bab3161140c42d75f5a8","s":"db98dc8abf872de81ae43750f2d99bbabeefdd6f42da055e18678b4faa97ac6"}', $assoc = true );
$r = new primeFieldValue($group->n_field, $x['r'], 16); $s=new primeFieldValue($group->n_field, $x['s'], 16);
echo $M;
echo 'r  :'.$r->asString(16).'<br>';
echo 's  :'.$s->asString(16).'<br>';
echo'<p>';
$result = ecDSAVerify($r, $s, $M, $Q, $group, $hash);

echo 'ans:'.(($result)? 'true':'false').'<br>';*/

