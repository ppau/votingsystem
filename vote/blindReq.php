<?php
	header( 'content-type: application/json; charset=utf-8' );
	
	require_once dirname( __FILE__ ) . '/src/group.php';
	require_once dirname( __FILE__ ) . '/src/standardCurves.php';
	require_once dirname( __FILE__ ) . '/src/ECDSA.php';
	include_once dirname( __FILE__ ) . '/database.php';
	
	function check_if_request_valid( $req, $sig, $voter, $group )
	{
		$r = new primeFieldValue( $group->n_field,$sig['r'], 16 );
		$s = new primeFieldValue( $group->n_field,$sig['s'], 16 );
		$a = ecDSAVerify( $r, $s, $req, $voter, $group, 'sha256' );
		$b = check_voter_allowed( $voter );
		return $a && $b;
	}
	
	$req = $_GET['req'];
	
	$temp = json_decode( base64_decode( $_GET['req'] ), $assoc = true );
	$election = $temp['election'];
	
	$sig = json_decode( base64_decode( $_GET['sig'] ), $assoc = true );
	$vstring = json_decode( base64_decode( $_GET['pk'] ), $assoc = true );
	
	$group = new StandardCurve( 'P256' );
	$voter = new elipticCurveValue( $group, $vstring['x'], $vstring['y'] , 16 );
	
	if( check_if_request_valid( $req, $sig, $voter, $group ) !== false )
	{
		if( r_voter_exists( $voter, $election ) )
		{
			$publicKey = r_get_pub( $voter, $election, $group );
		}
		else
		{
			$privateKey = $group->n_field->randomMemberNOZero();
			$publicKey  = $group->G->intMultiply( $privateKey->asString() );
			r_store( $privateKey, $publicKey,$voter, $election );
		}
		
		$json = $publicKey->asJson();
	}
	else
	{
		$json = json_encode( false );
	}
	
	echo isset( $_GET['callback'] ) ? "{$_GET['callback']}($json)" : $json;
