<?php

class App_Model_DbTable_ParticipantKeys extends Zend_Db_Table_Abstract
{

	protected $_name = 'participant_keys';

	public function keyExists($x, $y)
	{
		if(!is_string($x) || !is_string($y))
		{
			throw new Exception("Must pass strings to keyExists()");
		}
		
		$result = $this->fetchRow($this->select()
			->where('key_public_x = ?',$x)
			->where('key_public_y = ?',$y));

		return $result != null;
	}

/*
	public function bsigExists($key)
	{
		$result = $this->fetchRow($this->select()
			->where('pubKeyX = ?',$key->x->asString(16))
			->where('pubKeyY = ?',$key->y->asString(16)));
		
		return ($result != null) && !empty($result['bsig']);
	}
*/
}

