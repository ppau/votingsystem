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
			$mail->setFrom('votes@pirateparty.org.au','PPAU Voting System');
			$mail->setSubject('PPAU Vote Key');
			$mail->setBodyText('http://ppauvote.sdunster.com/vote/view/id/'.$this->_getParam('id').'/#'.$privateKey->asString(16));
			$mail->send();

			echo 'sent email to '.$p->firstname.' '.$p->surname.' - '.$p->email.'<hr />';	
		}
		
		$this->_helper->viewRenderer->setNoRender(true);	
	}

	public function questionAction()
	{
		$questionid = $this->_getParam('id');
		
		if($questionid == null || $questionid < 1)
		{
			throw new Exception('must pass ID');
		}
		
		$db = new App_Model_DbTable_Questions();
		if(!($question = $db->fetchQuestion($questionid)))
		{
			throw new Exception('invalid ID');
		}

		if($question->key_private != NULL && $question->key_private != '')
		{
			throw new Exception('Question already has private key - remove from DB before running this script');
		}

		// generate a key
		$group = new StandardCurve('P256');
		$privateKey = $group->n_field->randomMemberNOZero();
		$publicKey = $group->G->intMultiply($privateKey->asString());
		$scaled = $publicKey->scale();

		$question->key_public_x = $scaled->x->asString(16);
		$question->key_public_y = $scaled->y->asString(16);
		$question->key_private = $privateKey->asString(16);
		$question->save();
		
		echo 'success!';

		$this->_helper->viewRenderer->setNoRender(true);
	}
}

