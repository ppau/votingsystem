<?php

require_once 'Crypto/group.php';
require_once 'Crypto/standardCurves.php';

class AdminController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		// action body
	}

	public function mailAction()
	{
		if($this->_getParam('id') == '')
		{
			throw new Exception('No ID param passed to this script');
		}	
	
		$db = new App_Model_DbTable_Participants();	
		$participants = $db->fetchAll();

		$db = new App_Model_DbTable_ParticipantKeys();
	
		$group = new StandardCurve( 'P256' );
	
		foreach( $participants as $p )
		{
			$privateKey = $group->n_field->randomMemberNOZero();
			$publicKey  = $group->G->intMultiply( $privateKey->asString() );
		
			$scaled = $publicKey->scale();
			$newKey = $db->createRow();
			$newKey->key_public_x = $scaled->x->asString(16);
			$newKey->key_public_y = $scaled->y->asString(16);

			// TODO: add some extra metadata to the row that will determine
			// what polls/questions this key can be used to vote in

			$newKey->save();
			
			$mail = new Zend_Mail();
			$mail->addTo($p->email,$p->firstname.' '.$p->surname);
			//$mail->setFrom('votes@pirateparty.org.au','PPAU Voting System');
			$mail->setSubject('PPAU Vote Key');
			$mail->setBodyText('http://vote.pirateparty.org.au/poll/'.$this->_getParam('id').'/#'.$privateKey->asString(16));
			$mail->send();

			echo 'sent email to '.$p->firstname.' '.$p->surname.' - '.$p->email.'<hr />';	
		}
		
		$this->_helper->viewRenderer->setNoRender(true);	
	}

	public function pollAction()
	{
		$pollid = $this->_getParam('id');
		
		if($pollid == null || $pollid < 1)
		{
			throw new Exception('must pass ID');
		}
		
		$db = new App_Model_DbTable_Polls();
		if(!($poll = $db->find($pollid)->getRow(0)))
		{
			throw new Exception('invalid ID');
		}

		if($poll->key_private != NULL && $poll->key_private != '')
		{
			throw new Exception('Poll already has private key - remove from DB before running this script');
		}

		// generate a key
		$group = new StandardCurve('P256');
		$privateKey = $group->n_field->randomMemberNOZero();
		$publicKey = $group->G->intMultiply($privateKey->asString());
		$scaled = $publicKey->scale();

		$poll->key_public_x = $scaled->x->asString(16);
		$poll->key_public_y = $scaled->y->asString(16);
		$poll->key_private = $privateKey->asString(16);
		$poll->save();
		
		echo 'success!';

		$this->_helper->viewRenderer->setNoRender(true);
	}
}

