<?php
	include dirname( __FILE__ ) . '/database.php';
	require_once dirname( __FILE__ ) . '/src/group.php';
	require_once dirname( __FILE__ ) . '/src/standardCurves.php';
	
	$group = new StandardCurve( 'P256' );
	
	//if( isset($_GET['election']){
	$election = $_GET['election'];
	$privateKey = $group->n_field->randomMemberNOZero();
	$publicKey  = $group->G->intMultiply( $privateKey->asString() );
	$scaled = $publicKey->scale();
	
	add_priv_key( $election, $publicKey, $privateKey );
	echo "Added New Election :".$election."<br>";
	echo "Public Key :".$scaled->asString()."<br>";
	echo "Private Key :".$privateKey->asString()."<br>";
	/*
	?>
	<form name="addElection" action="addElection.php" method="get">
		Election ID: <input type="text" name="election" />
	</form>
	
	</html>
