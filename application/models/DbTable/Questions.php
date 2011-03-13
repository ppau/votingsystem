<?php

class App_Model_DbTable_Questions extends Zend_Db_Table_Abstract
{

	protected $_name = 'questions';

	function fetchQuestionsForPoll($id)
	{
		return $this->fetchAll($this->select()->where('pollid',$id)->order('rank ASC'));
	}
}

