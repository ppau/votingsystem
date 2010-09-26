<?php header('content-type: application/json; charset=utf-8');
include_once('Smarty/libs/Smarty.class.php');
include_once('Swift-4.0.6/lib/swift_required.php');
include_once('database.php');
require_once('/var/www/src/group.php');
require_once('/var/www/src/standardCurves.php');
require_once('/var/www/src/ECDSA.php');
require_once('/var/www/src/ECBlind.php');
require_once('/var/www/src/finitefield.php');

function BsigOK($vote, $bsig, $election, $group){
	$s = new primeFieldValue($group->n_field, $bsig['s'], 16);
	$M = $vote;
	$Q = getPubKey($election, $group);
	$R = new elipticCurveValue($group, $bsig['R']['x'], $bsig['R']["y"] , 16);
	$hash = 'sha256';
	$v = ecBlindVerify($R, $s, $M, $Q, $group, $hash);
	
	return $v;
}
function getPubKey($election, $group){
	return getPubKeyDB($election, $group);
}
function addvote($vote, $bsig, $election){
	addvoteDB($vote, $bsig, $election);
}
function exists($election){
	existsDB($election);
}
$vote = $_GET['vote'];
$election = $_GET['election'];
$bsig = json_decode(base64_decode($_GET['bsig']) , $assoc = true );
$group = new StandardCurve('P256');
if (existsDB($election)&&BsigOK($vote, $bsig, $election, $group)){
	addvote($vote, $bsig, $election);
	$json = json_encode(true);
} else {
	$json = json_encode(false);
}
echo isset($_GET['callback'])
    ? "{$_GET['callback']}($json)"
    : $json;
