<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {    	        
    }

    public function indexAction()
    {	    
    	$users = new Application_Model_UserMapper();
    	
    	$dbAdapter = $users->getDbTable()->getAdapter();
    	$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
    	
    	$authAdapter->setTableName('users')
    	->setIdentityColumn('mail')
    	->setCredentialColumn('password');    	
    	
    	
		$form = new Application_Form_Login();		
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {
				$email = $form->getValue('username');
				$pw = $form->getValue('pw');
				
				$authAdapter->setIdentity($email)
				->setCredential($pw);
				 
				$auth = Zend_Auth::getInstance();
								
				if($auth->authenticate($authAdapter)->isValid()) {					
					$this->_helper->redirector('overview', 'content', null, array('page' => 0));
				} else {					
					$this->view->error = "Wrong Email and password combination.";
				}
			}
		}		
    } 

    public function passwordAction() {
    	$error='';
    	$form = new Application_Form_Password();
    	$this->view->form = $form;
    	
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    			
    		if ($form->isValid($formData)) {
	    		$email = $form->getValue('email');
	    		
	    		$users = new Application_Model_UserMapper();
	    		
	    		if(($id = $users->emailExists($email)) != null) {
	    			$npw = $this->generatePass();
	    			
	    			$user = $users->getUser($id);
	    			$user->setPw($npw);
	    			$users->editUser($user);	    			
	    				    			
	    			Zend_Mail::setDefaultFrom('noreply@marlon.be', 'No reply');
	    			$transport = new Zend_Mail_Transport_Smtp('uit.telenet.be');
	    			
	    			$mail = new Zend_Mail();
	    			$mail->addTo($email);
	    			
	    			$mail->setSubject('New password');
	    			$mail->setBodyText('password: ' .  $npw);
	    			$mail->send($transport);
	    			
	    			$this->_helper->redirector('passwordsend');
	    		} else {	    			
	    			$this->view->error = 'The email was not found in the database';
	    		}
    		}
    	}
    }
    
    public function passwordsendAction() {
    	//Load the page.
    }
    
    
    private function generatePass() {
    	$length = 8;
    	
    	$pw= '';
    	$int = range('0','9');
    	$alph = range('a','z');
    	$calph = range('A','Z');    		
    	$chrs = explode(',',implode($int,',') . "," . implode($alph,',') . "," . implode($calph,','));
    		
    	for($a=0; $a<$length; $a++){
    		$pw.= $chrs[rand(0,count($chrs)-1)];
    	} 
    		
    	return $pw;    	
    	
    }
    
}