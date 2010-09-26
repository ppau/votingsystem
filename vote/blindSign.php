<?php
	header( 'content-type: application/json; charset=utf-8' );
	
	require_once dirname( __FILE__ ) . '/src/group.php';
	require_once dirname( __FILE__ ) . '/src/standardCurves.php';
	require_once dirname( __FILE__ ) . '/src/ECBlind.php';
	include_once dirname( __FILE__ ) . '/database.php';
	
	$req = $_GET['req'];
	$reqdat = json_decode( base64_decode( $_GET['req'] ), $assoc = true );
	$group = new StandardCurve( 'P256' );
	$Rcap = new elipticCurveValue( $group, $reqdat['Rcap']['x'], $reqdat['Rcap']['y'] , 16);
	
	if( r_pub_key_exists( $Rcap ) !== false )
	{
		if( bsig_exist( $Rcap ) )
		{
			list( $scap, $hcap ) = r_get_bsig( $Rcap, $group );
		}
		else
		{
			$hcap = new primeFieldValue( $group->n_field, $reqdat['hcap'], 16 );
			list($k, $election) = r_get_priv_election( $Rcap, $group );
			$d = get_priv_key( $election, $group );
			$scap = ecBlindSign( $Rcap, $d, $hcap, $k, $group );
			r_store_bsig( $scap, $hcap, $Rcap );
		}
		
		$json = json_encode( array( 'scap' => $scap->asString( 16 ), 'hcap'=> $hcap->asString( 16 ) ) );
	}
	else
	{
		$json = json_encode( false );
	}
	
	echo isset( $_GET['callback'] ) ? "{$_GET['callback']}($json)" : $json;
