<?php
	header( 'content-type: application/json; charset=utf-8' );
	
	include_once dirname( __FILE__ ) . '/Smarty/libs/Smarty.class.php';
	include_once dirname( __FILE__ ) . '/Swift-4.0.6/lib/swift_required.php';
	include_once dirname( __FILE__ ) . '/database.php';
	require_once dirname( __FILE__ ) . '/src/group.php';
	require_once dirname( __FILE__ ) . '/src/standardCurves.php';
	require_once dirname( __FILE__ ) . '/src/ECDSA.php';
	require_once dirname( __FILE__ ) . '/src/ECBlind.php';
	require_once dirname( __FILE__ ) . '/src/finitefield.php';
	
	function check_bsig( $vote, $bsig, $election, $group )
	{
		$s = new primeFieldValue( $group->n_field, $bsig['s'], 16 );
		$M = $vote;
		$Q = get_pub_key( $election, $group );
		$R = new elipticCurveValue( $group, $bsig['R']['x'], $bsig['R']['y'] , 16 );
		$hash = 'sha256';
		$v = ecBlindVerify( $R, $s, $M, $Q, $group, $hash );
		
		return $v;
	}
	
	$vote = $_GET['vote'];
	$election = $_GET['election'];
	
	$bsig = json_decode( base64_decode( $_GET['bsig'] ) , $assoc = true );
	
	$group = new StandardCurve( 'P256' );
	
	if( does_election_exist( $election ) && check_bsig( $vote, $bsig, $election, $group ) )
	{
		add_vote( $vote, $bsig, $election );
		$json = json_encode( true );
	}
	else
	{
		$json = json_encode( false );
	}
	
	echo isset( $_GET['callback'] ) ? "{$_GET['callback']}($json)" : $json;
