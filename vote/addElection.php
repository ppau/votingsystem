<?php
include('database.php');
require_once('/var/www/src/group.php');
require_once('/var/www/src/standardCurves.php');
$group = new StandardCurve('P256');
//if( isset($_GET['election']){
$election = $_GET['election'];
$privateKey = $group->n_field->randomMemberNOZero();
$publicKey  = $group->G->intMultiply($privateKey->asString());
$scaled = $publicKey->scale();

addPrivKeyDB($election, $publicKey, $privateKey);
echo "Added New Election :".$election."<br>";
echo "Public Key :".$scaled->asString()."<br>";
echo "Private Key :".$privateKey->asString()."<br>";
/*
?>
<form name="addElection" action="addElection.php" method="get">
	Election ID: <input type="text" name="election" />
</form>

</html>
