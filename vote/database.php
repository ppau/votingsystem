<?php
include_once('adodb5/adodb.inc.php');
require_once('/var/www/src/group.php');
require_once('/var/www/src/standardCurves.php');

function addPrivKeyDB($election, $publicKey, $privateKey){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'keys';
$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$scaled = $publicKey->scale();	
	$record["PubX"] = $scaled->x->asString(16);
	$record["PubY"] = $scaled->y->asString(16);
	$record["priv"] = $privateKey->asString(16);
	$record["election"] = $election;
$query = $db->Execute("INSERT INTO `keys` (PubX, PubY, priv, election) VALUES ('".$record["PubX"]."','".$record["PubY"]."','".$record["priv"]."','".$record["election"]."');");
	return $query;
}
function existsDB($election){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'keys';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$result = $db->GetRow('SELECT * FROM  `'.$table."` WHERE election = '".$election."'");
return !empty($result);
}
function getnames(){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	return $db->GetAll('SELECT * FROM  `registrations`');
}
function addPubkey($publicKey){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'pubKey';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$scaled = $publicKey->scale();
	$record["pubKeyX"] = $scaled->x->asString(16);
	$record["pubKeyY"] = $scaled->y->asString(16);
	return $db->AutoExecute($table,$record,'INSERT');
}

function voterAllowedDB($voter){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'pubKey';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$result = $db->GetAll('SELECT * FROM  `'.$table.'` WHERE pubKeyX = \''.$voter->x->asString(16).
													 '\' AND pubKeyY = \''.$voter->y->asString(16).'\';');
	return $result != false;
}
function rExistsDB($voter, $election){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'R';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$result = $db->GetAll('SELECT * FROM  `'.$table.'` WHERE election = \''.$election.
													 '\' AND voterx = \''.$voter->x->asString(16).
													 '\' AND votery = \''.$voter->y->asString(16).'\';');
	return $result != false;
}

function BigRExistsDB($Rcap){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'R';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$result = $db->GetAll('SELECT * FROM  `'.$table.'` WHERE pubRX = \''.$Rcap->x->asString(16).
													 '\' AND pubRY = \''.$Rcap->y->asString(16).'\';');
	return $result != false;
}
function bsigExistsDB($Rcap){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'pubKey';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$result = $db->GetAll('SELECT * FROM  `'.$table.'` WHERE pubRX = \''.$Rcap->x->asString(16).
													 '\' AND pubRY = \''.$Rcap->y->asString(16).'\';');
	return ($result != false) && ($result[0]['bsig']!='');
}
function getPubRDB($voter, $election, $group){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'R';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$result = $db->GetAll('SELECT * FROM  `'.$table.'` WHERE election = \''.$election.
													 '\' AND voterx = \''.$voter->x->asString(16).
													 '\' AND votery = \''.$voter->y->asString(16).'\';');
	return new elipticCurveValue($group, $result[0]["pubRX"], $result[0]["pubRY"] , 16);
}

function getbsigDB($Rcap){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'R';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$result = $db->GetAll('SELECT * FROM  `'.$table.'` WHERE pubRX = \''.$Rcap->x->asString(16).
	                                                 '\' AND pubRY = \''.$Rcap->y->asString(16).'\';');
	return array(new primeFieldValue($group->n_field, $result[0]['scap'], 16),
	             new primeFieldValue($group->n_field, $result[0]['hcap'], 16)) ;
}
function storeBsigDB($scap, $hcap, $Rcap){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'R';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$record["scap"] = $scap->asString(16);
	$record["hcap"] = $hcap->asString(16);
	return $db->AutoExecute($table,$record,'UPDATE', 'pubRX = \''.$Rcap->x->asString(16).
	                                          '\' AND pubRY = \''.$Rcap->y->asString(16).'\'');
}
function getPrivRElectionDB($Rcap, $group){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'R';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$result = $db->GetAll('SELECT * FROM  `'.$table.'` WHERE pubRX = \''.$Rcap->x->asString(16).
	                                                 '\' AND pubRY = \''.$Rcap->y->asString(16).'\';');
	return array(new primeFieldValue($group->n_field, $result[0]['privR'], 16), $result[0]['election']);
}
function storeRDB($privateKey, $publicKey, $voter, $election){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'R';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$scaled = $publicKey->scale();
	$record["pubRX"] = $scaled->x->asString(16);
	$record["pubRY"] = $scaled->y->asString(16);
	$record["privR"] = $privateKey->asString(16);
	$record["election"] = $election;
	$record["voterx"] = $voter->x->asString(16);
	$record["votery"] = $voter->y->asString(16);
	return $db->AutoExecute($table,$record,'INSERT');
}
function addvoteDB($vote, $bsig, $election){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'Votes';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$record["Rx"] = $bsig['R']['x'];
	$record["Ry"] = $bsig['R']['x'];
	$record["s"] = $bsig['s'];
	$record["election"] = $election;
	$record["vote"] = $vote;
	return $db->AutoExecute($table,$record,'INSERT');
}
function getPrivKeyDB($election, $group){
	$dbdriver = 'mysql';
	$server = '127.0.0.1';
	$user = 'voting';
	$password = 'hvfdq2';
	$database = 'ppau_database';
	$table = 'keys';

	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug =false;
	$db->Connect($server, $user, $password, $database);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	$result = $db->GetAll('SELECT * FROM  `'.$table."` WHERE election = '".$election."'");
	return new primeFieldValue($group->n_field, $result[0]['priv'], 16);
}
function getPubKeyDB($election, $group){
		$dbdriver = 'mysql';
		$server = '127.0.0.1';
		$user = 'voting';
		$password = 'hvfdq2';
		$database = 'ppau_database';
		$table = 'keys';

		$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
		$db->debug =false;
		$db->Connect($server, $user, $password, $database);
		$db->SetFetchMode(ADODB_FETCH_ASSOC);
		$result = $db->GetAll('SELECT * FROM  `'.$table."` WHERE election = '".$election."'");
		return new elipticCurveValue($group, $result[0]["PubX"], $result[0]["PubY"] , 16);
	}
