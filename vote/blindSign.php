<?php header('content-type: application/json; charset=utf-8');
require_once ('/var/www/src/group.php');
require_once ('/var/www/src/standardCurves.php');
require_once('/var/www/src/ECBlind.php');
include_once ('database.php');

function bsigExists($Rcap){
	return bsigExistsDB($Rcap);
}
function RExists($Rcap){
	$v = bigRExistsDB($Rcap);
	return $v;
}
function getbsig($Rcap, $group){
	return getbsigDB($Rcap, $group);
}
function storeBsig($scap,$hcap, $Rcap){
	return storeBsigDB($scap, $hcap, $Rcap);
}
function getPrivRElection($Rcap, $group){
	return getPrivRElectionDB($Rcap, $group);
}
function getPrivKey($election, $group){
	return getPrivKeyDB($election, $group);
}

$req = $_GET['req'];
$reqdat = json_decode(base64_decode($_GET['req']) , $assoc = true );
$group = new StandardCurve('P256');
$Rcap = new elipticCurveValue($group, $reqdat['Rcap']["x"], $reqdat['Rcap']["y"] , 16);
if(RExists($Rcap)){
	if(bsigExists($Rcap)){
		list($scap, $hcap) = getbsig($Rcap, $group);
	} else {
		$hcap = new primeFieldValue($group->n_field, $reqdat['hcap'], 16);
		list($k, $election) = getPrivRElection($Rcap, $group);
		$d = getPrivKey($election, $group);
		$scap = ecBlindSign($Rcap, $d, $hcap, $k, $group);
		storeBsig($scap, $hcap, $Rcap);
	}
	$json = json_encode(array('scap' => $scap->asString(16), 'hcap'=> $hcap->asString(16)));
} else {
	$json = json_encode(false);
}
echo isset($_GET['callback'])
    ? "{$_GET['callback']}($json)"
    : $json;
