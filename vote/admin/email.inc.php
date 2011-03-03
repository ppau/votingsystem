<?php
	include_once dirname( __FILE__ ) . '/../Smarty/libs/Smarty.class.php';
	include_once dirname( __FILE__ ) . '/../Swift-4.0.6/lib/swift_required.php';
	include_once dirname( __FILE__ ) . '/../database.php';
	//require_once dirname( __FILE__ ) . '/src/group.php';
	//require_once dirname( __FILE__ ) . '/src/standardCurves.php';
	
	//Create the Transport
	//$transport = Swift_MailTransport::newInstance();
	$transport = Swift_SmtpTransport::newInstance( 'localhost', 25, '' );
	
	//Create the Mailer using your created Transport
	$mailer = Swift_Mailer::newInstance( $transport );
	
	$IDs = get_registration_accounts();
	
	$group = new StandardCurve( 'P256' );
	
	foreach( $IDs as $ID )
	{
		// generate Key pair new primeFieldValue($group->n_field, '594a4870c24763126167fda80a49d464abb17ac19d53a3cd92c5e837710b24fd', 16)
		$privateKey = $group->n_field->randomMemberNOZero();
		$publicKey  = $group->G->intMultiply( $privateKey->asString() );
		
		$smarty = new Smarty;
		$smarty->assign( 'name',  $ID['firstname'] . ' ' . $ID['surname'] );
		$smarty->assign( 'privateKey', $privateKey->asString( 16 ) );
		
		$message = Swift_Message::newInstance( 'Here is your Vote' )
			->setFrom( array('votes@pirateparty.org.au' => 'PPAU voting system' ) )
			->setTo( array( $ID['email'] => $ID['firstname'] . ' ' . $ID['surname'] ) )
			->setBody( $smarty->fetch( 'email.tpl' ), 'text/html' );
			
		add_pub_key( $publicKey );
		
		echo '<p>Sending Email to ' . $ID['firstname'] . ' ' . $ID['surname'] . '&lt;' .$ID['email'] . '&gt; </p>' ."\n";
		
		//send the message
		$result = $mailer->send( $message );
	}
