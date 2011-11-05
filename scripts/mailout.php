#!/usr/bin/php
<?php

// must have trailing slash
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

$db = new App_Model_DbTable_Polls();
$polls = $db->find($argv[1]);

if(count($polls) == 0)
{
	die("Specified poll doesn't exist\n");
}

$poll = $polls->getRow(0);

if($poll->active == 0)
{
	die("Poll isn't active (active = 0 in DB)\n");
}

if($poll->key_public_x == '' || $poll->key_public_y == '' | $poll->key_private == '')
{
	die("Must generate keys for this poll first\n");
}

if(!file_exists(dirname(__FILE__).'/templates/mailout-'.$poll->id.'.phtml'))
{
	die("./templates/mailout-".$poll->id.".phtml doesn't exist\n");
}

$db = new App_Model_DbTable_Participants();	
$participants = $db->fetchNeedKeys();

$db = new App_Model_DbTable_ParticipantKeys();

$group = new StandardCurve( 'P256' );

$count = count($participants);
echo "About to mail " . count($participants) . " key(s) - are you sure? (ctrl-c to bail) ";
fgets(STDIN);
echo "\n";

$number = 0;

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

	$view = new Zend_View();
	$view->setScriptPath(dirname(__FILE__).'/templates');
	$view->firstname = $p->firstname;
	$view->surname = $p->surname;
	$view->email = $p->email;
	$view->link = $URL.'poll/'.$poll->id.'/#'.str_pad($privateKey->asString(16),64,"0",STR_PAD_LEFT);
	
	$mail = new Zend_Mail();
	$mail->addTo($p->email,$p->firstname.' '.$p->surname);
	$mail->setSubject('PPAU Vote - '.$poll->name);
	$mail->setBodyText($view->render('mailout-'.$poll->id.'.phtml'));
	$mail->send();
	
	// save to the DB only after we've successfully dispatched the email
	$newKey->save();
	$p->save();

	$number++;

	echo "(".str_pad($number,strlen($count),' ',STR_PAD_LEFT). "/".$count.') ';
	echo "Sent email to ".$p->firstname.' '.$p->surname.' - '.$p->email."\n";	
}

