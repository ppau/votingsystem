<?php

require_once 'Crypto/group.php';
require_once 'Crypto/standardCurves.php';
require_once 'Crypto/ECDSA.php';
require_once 'Crypto/ECBlind.php';

class VoteController extends Zend_Controller_Action
{

	public function init()
	{
		$contextSwitch = $this->_helper->contextSwitch;
		$contextSwitch->addActionContext('request','json')
		              ->addActionContext('sign','json')
		              ->addActionContext('process','json');
	}

	protected function getGroup()
	{
		return new StandardCurve('P256');
	}

	public function indexAction()
	{
		
	}

	public function viewAction()
	{
		if($this->_getParam('id') == '')
		{
			throw new Exception("Invalid poll request - no ID specified");
		}
		
		$pollId = $this->_getParam('id');
		$pollsDb = new App_Model_DbTable_Polls();
		$poll = $pollsDb->fetchValidPoll($pollId);	
		
		if($poll == NULL)
		{
			throw new Exception("Invalid poll ID",404);
		}
	
		$questionsDb = new App_Model_DbTable_Questions();
		$questions = $questionsDb->fetchQuestionsForPoll($pollId);

		$questionIds = array();

		foreach($questions as $question)
		{
			$questionIds[] = $question->id;
		}

		$this->view->poll = $poll;
		$this->view->pollid = $poll->id;
		$this->view->questions = $questions;	
		$this->view->questionIds = $questionIds;
	}

	public function requestAction()
	{
		$this->_helper->contextSwitch->initContext('json');
			
		try
		{
			$req_str = $this->_getParam('req');		
			$req = json_decode(base64_decode($req_str),true);
			$sig = json_decode(base64_decode($this->_getParam('sig')),true);
			$pk  = json_decode(base64_decode($this->_getParam('pk')),true);

			$group = $this->getGroup();
			$participant = new elipticCurveValue( $group, $pk['x'], $pk['y'] , 16 );

			// verify the request they sent us was signed by the key
			// they sent us
			$r = new primeFieldValue( $group->n_field,$sig['r'], 16 );
			$s = new primeFieldValue( $group->n_field,$sig['s'], 16 );
			if(!ecDSAVerify( $r, $s, $req_str, $participant, $group, 'sha256' ))
			{
				throw new Exception('Invalid signature');
			}
	
			// check that the public keys they sent us are in our database
			// ie: check that we created them
			$db = new App_Model_DbTable_ParticipantKeys();
			if(!$db->keyExists($participant->x->asString(16),$participant->y->asString(16)))
			{
				throw new Exception('Invalid key, it might\'ve been revoked');
			}
		
			// TODO: do some checking on the participant's public key to
			// see if they're allowed to vote on this particular poll	
			
			$db = new App_Model_DbTable_VotingState();
			
			// check if the voter already has an R for this question
			if($state = $db->fetchParticipant($participant->x->asString(16),$participant->y->asString(16), $req['id']))
			{
				// remind them of their current R
				$publicKey = new elipticCurveValue($group, $state->r_public_x, $state->r_public_y, 16);
			}
			else
			{
				// generate a new R for this question
				$privateKey = $group->n_field->randomMemberNOZero();
				$publicKey  = $group->G->intMultiply( $privateKey->asString() );
				$scaled = $publicKey->scale();
				
				// store the R for later
				$newKey = $db->createRow();
				$newKey->questionid = $req['id'];
				$newKey->key_public_x = $participant->x->asString(16);
				$newKey->key_public_y = $participant->y->asString(16);
				$newKey->r_public_x = $scaled->x->asString(16);
				$newKey->r_public_y = $scaled->y->asString(16);
				$newKey->r_private = $privateKey->asString(16);
				$newKey->save();
			}
	
			// return the R to the user	
			$this->view->key = $publicKey->asJSONArray();
			
			$this->view->status = 0;
		}
		catch(Exception $e)
		{
			$this->view->status = 1;
			$this->view->error = $e->getMessage();
		}
	}

	public function signAction()
	{
		$this->_helper->contextSwitch->initContext('json');

		try
		{
			$req = json_decode(base64_decode($this->_getParam('req')),true);
			$group = $this->getGroup();
			$rcap = new elipticCurveValue($group, $req['rcap']['x'], $req['rcap']['y'], 16);

			$db1 = new App_Model_DbTable_VotingState();
			$db2 = new App_Model_DbTable_Questions();
		
			// verify the data the sent us is legit (check it matches what
			// we sent them in step 1	
			if(!($state = $db1->fetchR($rcap->x->asString(16),$rcap->y->asString(16))))
			{
				throw new Exception("Invalid rcap");
			}
			
			// have we already generated a blind signature?
			if($state->scap != NULL && $state->hcap != NULL)
			{
				// use the existing blind signature (scap, hcap)
				$scap = new primeFieldValue($group->n_field, $state->scap, 16);
				$hcap = new primeFieldValue($group->n_field, $state->hcap, 16);
			}
			else
			{
				// else, generate a new blind signature (scap, hcap)
				$hcap = new primeFieldValue($group->n_field, $req['hcap'], 16);
				$k = new primeFieldValue($group->n_field, $state->r_private, 16);
				
				// fetch this question's private key
				if(!($question = $db2->fetchQuestion($state->questionid)))
				{
					throw new Exception("Unable to fetch question's private key");
				}
				$d = new primeFieldValue($group->n_field, $question->key_private, 16);

				// sign their key with the key for this question
				$scap = ecBlindSign($rcap, $d, $hcap, $k, $group);
				
				// store in the database for later
				$state->scap = $scap->asString(16);
				$state->hcap = $hcap->asString(16);
				$state->save();
			}

			$this->view->scap = $scap->asString(16);
			$this->view->hcap = $hcap->asString(16);
			$this->view->status = 0;
				
		}	
		catch(Exception $e)
		{
			$this->view->status = 1;
			$this->view->error = $e->getMessage();
		}
	}

	public function verifyVoteData($voteData, $blindSignature, $question, $group)
	{
		$s = new primeFieldValue($group->n_field, $blindSignature['s'], 16);
		$M = $voteData;
		$Q = new elipticCurveValue($group, $question->key_public_x, $question->key_public_y, 16);
		$R = new elipticCurveValue($group, $blindSignature['R']['x'], $blindSignature['R']['y'], 16);
		$hash = 'sha256';
		return ecBlindVerify($R, $s, $M, $Q, $group, $hash);
	}
	
	public function processAction()
	{
		$this->_helper->contextSwitch->initContext('json');
	
		try
		{
			$voteData = $this->_getParam('data');
			$pollid = $this->_getParam('id');
			$blindSignature = json_decode(base64_decode($this->_getParam('bsig')),true);
			$group = $this->getGroup();
	
			$table = new App_Model_DbTable_Questions();
			if(($question = $table->fetchQuestion($pollid)) == NULL)
			{
				throw new Exception('Question doesn\'t exist');
			}
	
			if(!$this->verifyVoteData($voteData, $blindSignature, $question, $group))
			{
				throw new Exception('The vote data was not signed correctly - vote cannot be stored');
			}

			$table = new App_Model_DbTable_Votes();
			$row = $table->createRow();
			$row->Rx = $blindSignature['R']['x'];
			$row->Ry = $blindSignature['R']['y'];
			$row->s = $blindSignature['s'];
			$row->questionid = $pollid;
			$row->data = base64_decode($voteData);

			$row->save();	
		
			$this->view->status = 0;
		}	
		catch(Exception $e)
		{
			$this->view->status = 1;
			$this->view->error = $e->getMessage();
		}
	}
}

