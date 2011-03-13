<?php

class VoteController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		if($this->_getParam('id') == '')
		{
			throw new Exception("Invalid polli request - no ID specified");
		}
		
		if($this->_getParam('key') == '')
		{
			throw new Exception("Invalid poll request - no key specified");
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


}

