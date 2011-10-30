<?php

class App_Model_DbTable_Questions extends Zend_Db_Table_Abstract
{

	protected $_name = 'questions';

	function fetchQuestion($id)
	{
		if($id == NULL)
			throw new Exception('Unable to fetch question - id not speciified or NULL');

		return $this->fetchRow($this->select()->where('id = ?',$id));
	}

	function fetchQuestionsForPoll($id)
	{
		return $this->fetchAll($this->select()->where('pollid = ?',$id)->order('rank ASC'));
	}
	
/*	public function participantAllowed($participant)
	{
		$result = $this->fetchRow($this->select()
			->where('key_public_x = ?',$participant->x->asString(16))
			->where('key_public_y = ?',$participant->y->asString(16)));

		return $result != null;
	}

	public function getPrivateKey($questionid, $group)
	{
		$result = $this->fetchRow($this->select()
			->where('questionid = ?',$questionid));

		if($result == null)
		{
			throw new Exception("Unable to fetch question private key: row not found");
		}

		return new primeFieldValue($group->n_field, $result->priv, 16);
	}*/
}

