<?php

class App_Model_DbTable_Keys extends Zend_Db_Table_Abstract
{

	protected $_name = 'keys';

	public function participantAllowed($participant)
	{
		$result = $this->fetchRow($this->select()
			->where('PubX = ?',$participant->x->asString(16))
			->where('PubY = ?',$participant->y->asString(16)));

		return $result != null;
	}

	public function addPrivateKey($questionid, $publicKey, $privateKey)
	{
		$scaled = $publicKey->scale();
	
		$newKey = $this->createRow();
		$newKey->PubX = $scaled->x->asString(16);
		$newKey->PubY = $scaled->y->asString(16);
		$newKey->priv = $privateKey->asString(16);
		$newKey->questionid = $qestionid;
		
		$newKey->save();
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
	}
}

