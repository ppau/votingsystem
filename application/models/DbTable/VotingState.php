<?php

class App_Model_DbTable_VotingState extends Zend_Db_Table_Abstract
{

	protected $_name = 'voting_state';

/*	public function fetchParticipant($x, $y, $questionid = 0)
	{
		if(!is_string($x) || !is_string($y))
		{
			throw new Exception("Must pass strings to getParticipant()");
		}

		if($questionid == 0)
		{
			$result = $this->fetchRow($this->select()
				->where('key_public_x = ?',$x)
				->where('key_public_y = ?',$y));	
		}
		else
		{
			$result = $this->fetchRow($this->select()
				->where('questionid = ?',$questionid)
				->where('key_public_x = ?',$x)
				->where('key_public_y = ?',$y));	
		}
		
		return $result;
	}
*/

	public function fetchParticipant($x, $y, $questionid)
	{
		if(!is_string($x) || !is_string($y))
		{
			throw new Exception("Must pass strings to fetchParticipant()");
		}

		$result = $this->fetchRow($this->select()
			->where('questionid = ?',$questionid)
			->where('key_public_x = ?',$x)
			->where('key_public_y = ?',$y));	
		
		return $result;
	}

	public function fetchR($x, $y)
	{
		if(!is_string($x) || !is_string($y))
		{
			throw new Exception("Must pass strings to fetchR()");
		}

		$result = $this->fetchRow($this->select()
			->where('r_public_x = ?',$x)
			->where('r_public_y = ?',$y));	
		
		return $result;
	}


/*	public function getPrivPoll($key, $group)
	{
		$result = $this->fetchRow($this->select()
			->where('pubRX = ?',$key->x->asString(16))
			->where('pubRY = ?',$key->y->asString(16)));
		
		if($result == null)
		{
			throw new Exception("Error getting private key: row not found");
		}
		
		return array(
			new primeFieldValue($group->n_field, $result->privR, 16),
			$result->questionid
		);
	}*/
}

