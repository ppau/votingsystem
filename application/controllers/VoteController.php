<?php

require_once 'Crypto/group.php';
require_once 'Crypto/standardCurves.php';
require_once 'Crypto/ECDSA.php';

class VoteController extends Zend_Controller_Action
{

	public function init()
	{
		$contextSwitch = $this->_helper->contextSwitch;
		$contextSwitch->addActionContext('request','json')
		              ->addActionContext('sign','json')
		              ->addActionContext('process','json');
	}

	public function indexAction()
	{
		
	}

	public function viewAction()
	{
		if($this->_getParam('id') == '')
		{
			throw new Exception("Invalid polli request - no ID specified");
		}
		
		$pollsDb = new App_Model_DbTable_Polls();
		$poll = $pollsDb->fetchValidPoll($this->_getParam('id'));	
		
		if($poll == NULL)
		{
			throw new Exception("Invalid poll ID",404);
		}
	
		$questionsDb = new App_Model_DbTable_Questions();
		$questions = $questionsDb->fetchQuestionsForPoll($this->_getParam('id'));

		$questionIds = array();

		foreach($questions as $question)
		{
			$questionIds[] = $question->id;
		}

		$this->view->poll = $poll;
		$this->view->questions = $questions;	
		$this->view->questionIds = $questionIds;
	}

	public function requestAction()
	{
		$this->_helper->contextSwitch->initContext('json');
		$this->view->test = 'lol';
	}

	public function signAction()
	{
		
	}
	
	public function processAction()
	{
		
	}
}

