<?php
include_once('Smarty/libs/Smarty.class.php');
include_once('Swift-4.0.6/lib/swift_required.php');
include('database.php');
require_once('/var/www/src/group.php');
require_once('/var/www/src/standardCurves.php');
require_once('/var/www/src/ECDSA.php');

$group = new StandardCurve('P256');

	$privateKey = $group->n_field->randomMemberNOZero();
	$publicKey  = $group->G->intMultiply($privateKey->asString());
	
	$smarty = new Smarty;
	$smarty->assign('name',  'Alexis'. ' ' . 'Shaw');
	$smarty->assign('privateKey', $privateKey->asString(16));

addPubkey($publicKey);
$election = 'startrek';


$req = base64_encode(json_encode(array('election'=>$election, 'stamp'=> time())));

list($r, $s) = ecDSASign($req, $privateKey, $group, 'sha256');

$sig = base64_encode(json_encode(array('r'=>$r->asString(16), 's'=> $s->asString(16))));

$pk = base64_encode($publicKey->asJSON(16));
echo '<a href = "http://178.63.144.167/blindReq.php?req='.$req.'&sig='.$sig.'&pk='.$pk.'">Link</a>';
