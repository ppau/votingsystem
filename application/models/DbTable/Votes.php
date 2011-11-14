<?php

class App_Model_DbTable_Votes extends Zend_Db_Table_Abstract
{

    protected $_name = 'votes';


	public function fetchVotesForPoll($pollid)
	{
		return $this->fetchAll($this->select()->where('pollid = ?',(int)$pollid));
	}
}

