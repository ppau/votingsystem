<?php
	include_once('Smarty/libs/Smarty.class.php');
	include_once('Swift-4.0.6/lib/swift_required.php');
	include('database.php');
	require_once('/var/www/src/group.php');
	require_once('/var/www/src/standardCurves.php');
	require_once('/var/www/src/ECDSA.php');
	require_once('/var/www/ECBlind.php');
	
	
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
	$Rcap1 = json_decode(file_get_contents('http://localhost/~admin/blindReq.php?req='.$req.'&sig='.$sig.'&pk='.$pk), $assoc = true );
	
	$M = "This is a Message It will be a vote";
	$Rcap = new elipticCurveValue($group, $Rcap1['x'], $Rcap1['y'] , 16);
	$hash = 'sha256';
	list($hcap, $beta, $R) = ecBlindness($M, $Rcap, $group, $hash);
	
	$BlindSign = base64_encode(json_encode(array('hcap'=>$hcap->asString(16), 'Rcap'=> $Rcap->asJSONArray())));
	$BS1 = file_get_contents('http://localhost/~admin/blindSign.php?req='.$BlindSign);
	echo $BS1;
	echo "\n";
	echo file_get_contents('http://localhost/~admin/blindSign.php?req='.$BlindSign);
