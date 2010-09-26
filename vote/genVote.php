<?php
include_once('Smarty/libs/Smarty.class.php');
include_once('Swift-4.0.6/lib/swift_required.php');
include_once('database.php');
require_once('/var/www/src/group.php');
require_once('/var/www/src/standardCurves.php');
require_once('/var/www/src/ECDSA.php');
require_once('/var/www/src/ECBlind.php');
require_once('/var/www/src/finitefield.php');


include_once ('database.php');

function getPubKey($election, $group){
	return getPubKeyDB($election, $group);
}
//simulate email generation
$group = new StandardCurve('P256');
$privateKey = $group->n_field->randomMemberNOZero();
$publicKey  = $group->G->intMultiply($privateKey->asString());
$smarty = new Smarty;
$smarty->assign('name',  'Alexis'. ' ' . 'Shaw');
$smarty->assign('privateKey', $privateKey->asString(16));
addPubkey($publicKey);

//gen Blind Req
$election = 'startrek';
$req = base64_encode(json_encode(array('election'=>$election, 'stamp'=> time())));
echo json_encode(array('election'=>$election, 'stamp'=> time()));
list($r, $s) = ecDSASign($req, $privateKey, $group, 'sha256');
$sig = base64_encode(json_encode(array('r'=>$r->asString(16), 's'=> $s->asString(16))));
$pk = base64_encode($publicKey->asJSON(16));
$Rcap1 = json_decode(file_get_contents('http://localhost/blindReq.php?req='.$req.'&sig='.$sig.'&pk='.$pk), $assoc = true );

//gen blindsignature
$M2 = "This is a Message It will be a vote";
$M = base64_encode($M2);
$Rcap = new elipticCurveValue($group, $Rcap1['x'], $Rcap1['y'] , 16);
$hash = 'sha256';
list($hcap, $beta, $R) = ecBlindness($M, $Rcap, $group, $hash);

$BlindSign = base64_encode(json_encode(array('hcap'=>$hcap->asString(16), 'Rcap'=> $Rcap->asJSONArray())));
$BS1 = file_get_contents('http://localhost/blindSign.php?req='.$BlindSign);
$BS = json_decode(file_get_contents('http://localhost/blindSign.php?req='.$BlindSign),$assoc = true );

//submit vote
$hcapOut =  new primeFieldValue($group->n_field, $BS['hcap'], 16);
if ($hcapOut->equals($hcap)){
	$scap = new primeFieldValue($group->n_field, $BS['scap'], 16);
	$s = ecUnblindness($scap, $R, $Rcap, $M, $beta, $group, $hash);
	//echo $s->asString(16);
	$bsig = base64_encode(json_encode(array('R'=>$R->asJSONArray(), 's'=> $s->asString(16))));
//	echo $election."\n";
	echo file_get_contents('http://localhost/addVote.php?vote='.$M.'&election='.$election.'&bsig='.$bsig);
} else {
	echo 'error';
}
