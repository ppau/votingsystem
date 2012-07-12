#!/usr/bin/php
<?php

require_once dirname(__FILE__).'/init.php';
require_once 'Crypto/group.php';
require_once 'Crypto/standardCurves.php';

function usage()
{
	echo 'Usage: ./make-poll-keys.php <pollid>'."\n";
}

if($argc != 2 || !is_numeric($argv[1]))
{
	usage();
	die();
}

$pollid = $argv[1];

$db = new App_Model_DbTable_Polls();
if(!($poll = $db->find($pollid)->getRow(0)))
{
	die("invalid ID\n");
}

if($poll->key_private != NULL && $poll->key_private != '')
{
	die("Poll already has private key - remove from DB before running this script\n");
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

echo "success!\n";


