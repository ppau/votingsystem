#!/usr/bin/php
<?php

require_once dirname(__FILE__).'/init.php';
#require_once 'Crypto/group.php';
#require_once 'Crypto/standardCurves.php';

function usage()
{
	echo 'Usage: ./dump.php <pollid>'."\n";
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

$db = new App_Model_DbTable_Votes();
$votes = $db->fetchVotesForPoll($poll->id);

$dump = array();

foreach($votes as $v)
{
	if(json_decode($v->data) === NULL)
	{
		fwrite(STDOUT, $v->id . ": invalid JSON - skipping\n");
		continue;
	}

	$dump[] = $v->data;
}

echo "[\n" . implode(",", $dump) . "]\n";

