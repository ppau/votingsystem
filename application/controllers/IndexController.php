<?php

class IndexController extends Zend_Controller_Action
{

	public function init()
	{
		
	}

	public function indexAction()
	{
		$table = new App_Model_DbTable_Participants();
		
		$results = $table->fetchAll();
		var_dump($results);
	}


}

