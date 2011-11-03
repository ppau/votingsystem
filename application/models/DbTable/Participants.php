<?php

class App_Model_DbTable_Participants extends Zend_Db_Table_Abstract
{

	protected $_name = 'participants';

	public function fetchNeedKeys()
	{
		return $this->fetchAll($this->select()->where('mailed = 0'));	
	}

	public function fetchNoKeys()
	{
		return $this->fetchAll($this->select()->where('mailed != 0'));	
	}

}

