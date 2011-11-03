#!/usr/bin/php
<?php

require_once './init.php';

$db = new App_Model_DbTable_Participants();	
$participants = $db->fetchNoKeys();

echo "About to clear mailed keys flag on " . count($participants) . " people - aree you sure? This WILL NOT remove the keys from the database - it will only allow these people to be issued more. Use this  (ctrl-c to bail) ";
fgets(STDIN);
echo "\n";

foreach( $participants as $p )
{
	$p->mailed = 0;
	$p->save();
	echo 'Cleared mailed keys flag on '.$p->firstname.' '.$p->surname.' - '.$p->email."\n";	
}

