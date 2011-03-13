<?php

class App_Model_DbTable_Polls extends Zend_Db_Table_Abstract
{

	protected $_name = 'polls';

	function fetchValidPoll($id)
	{
		return $this->fetchRow($this->select()->where('id = ?',$id)->where('active = 1')->limit(1));
	}
}

