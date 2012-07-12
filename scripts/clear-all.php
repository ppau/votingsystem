#!/usr/bin/php
<?php

require_once dirname(__FILE__).'/init.php';

echo "About to clear ALL VOTE DATA FROM THE SYSTEM. This include VOTE, VOTE STATE and ALL KEYS (ctrl-c to bail) ";
fgets(STDIN);
echo "\n";

$db = Zend_Db_Table::getDefaultAdapter();

$db->beginTransaction();

try {
	$db->query("TRUNCATE TABLE voting_state");
	$db->query("TRUNCATE TABLE participant_keys");
	$db->query("TRUNCATE TABLE votes");
	
	$db->commit();
	echo "done\n";
}
catch(Exception $e)
{
	$db->rollBack();
	throw $e;
}
	

