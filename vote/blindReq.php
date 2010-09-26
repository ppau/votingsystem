<?php header('content-type: application/json; charset=utf-8');
require_once ('/var/www/src/group.php');
require_once ('/var/www/src/standardCurves.php');
require_once('/var/www/src/ECDSA.php');
include_once ('database.php');

function reqValid($req, $sig, $voter, $group){
	$r = new primeFieldValue($group->n_field,$sig['r'], 16);
	$s = new primeFieldValue($group->n_field,$sig['s'], 16);
	$a = ecDSAVerify($r, $s, $req, $voter, $group, 'sha256');
	$b = voterAllowedDB($voter);
	return $a && $b;
}
function rExists($voter, $election){
	return rExistsDB($voter, $election);
}
function getPubR($voter, $election, $group){
	return getPubRDB($voter, $election, $group);
}
function storeR($privateKey, $publicKey, $voter, $election){
	return storeRDB($privateKey, $publicKey,$voter,  $election);
}

$req = $_GET['req'];
$temp = json_decode(base64_decode($_GET['req']) , $assoc = true );
$election = $temp['election'];
$sig = json_decode(base64_decode($_GET['sig']) , $assoc = true );
$vstring = json_decode(base64_decode($_GET['pk']) , $assoc = true );
$group = new StandardCurve('P256');
$voter = new elipticCurveValue($group, $vstring["x"], $vstring["y"] , 16);

if(reqValid($req, $sig, $voter, $group)){
	if(rExists($voter, $election)){
		$publicKey = getPubR($voter, $election, $group);
	} else {
		$privateKey = $group->n_field->randomMemberNOZero();
		$publicKey  = $group->G->intMultiply($privateKey->asString());
		storeR($privateKey, $publicKey,$voter, $election);
	}
	$json = $publicKey->asJson();
} else {
	$json = json_encode(false);
}
echo isset($_GET['callback'])
    ? "{$_GET['callback']}($json)"
    : $json;
