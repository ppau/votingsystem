#!/usr/bin/php
<?php

$URL = 'http://vote.pirateparty.org.au/';

require_once './init.php';
require_once 'Crypto/group.php';
require_once 'Crypto/standardCurves.php';

function usage()
{
	echo 'Usage: ./mailout.php <pollid>'."\n";
}

if($argc != 2 || !is_numeric($argv[1]))
{
	usage();
	die();
}

$db = new App_Model_DbTable_Participants();	
$participants = $db->fetchNeedKeys();

$db = new App_Model_DbTable_ParticipantKeys();

$group = new StandardCurve( 'P256' );

echo "About to mail " . count($participants) . " key(s) - are you sure? (ctrl-c to bail) ";
fgets(STDIN);
echo "\n";

foreach( $participants as $p )
{
	$privateKey = $group->n_field->randomMemberNOZero();
	$publicKey  = $group->G->intMultiply( $privateKey->asString() );

	$scaled = $publicKey->scale();
	$newKey = $db->createRow();
	$newKey->key_public_x = $scaled->x->asString(16);
	$newKey->key_public_y = $scaled->y->asString(16);

	$p->mailed = time();

	// TODO: add some extra metadata to the row that will determine
	// what polls/questions this key can be used to vote in

	$newKey->save();
	$p->save();
	
	$mail = new Zend_Mail();
	$mail->addTo($p->email,$p->firstname.' '.$p->surname);
	$mail->setSubject('PPAU Vote Key');
	$mail->setBodyText($URL.'/poll/'.$argv[1].'/#'.$privateKey->asString(16));
	$mail->send();

	echo 'Sent email to '.$p->firstname.' '.$p->surname.' - '.$p->email."\n";	
}





/*

		$pollid = $this->_getParam('id');
		
		if($pollid == null || $pollid < 1)
		{
			throw new Exception('must pass ID');
		}
		
		$db = new App_Model_DbTable_Polls();
		if(!($poll = $db->find($pollid)->getRow(0)))
		{
			throw new Exception('invalid ID');
		}

		if($poll->key_private != NULL && $poll->key_private != '')
		{
			throw new Exception('Poll already has private key - remove from DB before running this script');
		}

		// generate a key
		$group = new StandardCurve('P256');
		$privateKey = $group->n_field->randomMemberNOZero();
		$publicKey = $group->G->intMultiply($privateKey->asString());
		$scaled = $publicKey->scale();

		$poll->key_public_x = $scaled->x->asString(16);
		$poll->key_public_y = $scaled->y->asString(16);
		$poll->key_private = $privateKey->asString(16);
		$poll->save();
		
		echo 'success!';

		$this->_helper->viewRenderer->setNoRender(true);
	}
}
*/

