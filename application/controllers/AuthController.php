<?php

class AuthController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		$auth = Zend_Auth::getInstance();
		
		if($auth->hasIdentity())
		{
			$this->_helper->redirector('index','index',null,array('user' => $auth->getIdentity()->identity));
		}
		else
		{
			$this->_helper->redirector('login','auth');
		}
	}

	public function loginAction()
	{
		$auth = Zend_Auth::getInstance();
		
		if($auth->hasIdentity())
		{
			$this->_helper->redirector('index','auth');
		}
		
		$form = new App_Form_Login();

		if($this->_request->isPost() && $form->isValid($_POST))
		{
			$values = $form->getValues();
	
			$config = Zend_Registry::get('config');
			
			$adapter = new Zend_Auth_Adapter_Ldap($config->ldapauth->toArray(), $values['username'], $values['password']);
			$result = $auth->authenticate($adapter);
		
			if($result->isValid())
			{
				$account = $adapter->getAccountObject();
				$account->identity = $result->getIdentity();
				
				$auth->getStorage()->write($account);

				$session = new Zend_Session_Namespace('App_AuthController');			
				if(isset($session->returnUrl) && strlen($session->returnUrl) > 0)
				{
					$url = $session->returnUrl;
					unset($session->returnUrl);
					$this->_helper->redirector->gotoUrl($url);
				}
				else
				{
					$this->_helper->redirector('index','auth');
				}
			}
			else
			{
				$msgs = $result->getMessages();
				
				if($result->getCode() == Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND)
				{
					$form->getElement('username')->addError($msgs[0]);
				}
				else
				{
					$form->getElement('password')->addError($msgs[0]);
				}
			}
		}

		$this->view->form = $form;
	}	
	
	protected function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		
		$this->_helper->redirector('index','auth');
	}
}

