<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initPage()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		
		$view->headTitle()->prepend('PPAU Voting System');
		$view->headTitle()->setSeparator(' - ');
		
		$view->doctype('HTML5');
		$view->headMeta()->setCharset('UTF-8');
		
		return $view;
	}
}

