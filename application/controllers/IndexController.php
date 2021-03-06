<?php

class IndexController extends Zend_Controller_Action
{
	private $em;
	private $userRep;
	
    public function init()
    {        	
    	$this->em = $this->getInvokeArg('bootstrap')->getResource('doctrine');  
    	$this->userRep = $this->em->getRepository('Entities\User');  	 	
    }

    public function indexAction()
    {	    	
		$form = new Application_Form_Login();		
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {
				$email = $form->getValue('username');
				$pw = $form->getValue('pw');				
				
				try {
					//login succesfull
					$user = $this->userRep->Authenticate($email, $pw);
					$result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,$user->getId(),array());
					Zend_Auth::getInstance()->getStorage()->write($user);
					$this->_helper->redirector('overview', 'user', null, array('page' => 1));
				} catch (Exception $e) {
					//login failed
					$result =  new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,null,array());
					$this->view->error = "Wrong Email and password combination.";
				}
											
			}
		}		
    } 

    public function passwordAction() {
    	$form = new Application_Form_Password(array('repository' => $this->userRep));
    	$this->view->form = $form;
    	
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    			
    		if ($form->isValid($formData)) {
    			$email = $form->getValue('email');	    			    		
    			$npw = $this->generatePass();
    			
    			$user = $this->userRep->findOneByMail($email);    
    			$user->setPw($npw);	
    			$this->em->flush();			    			
    				    			
    			Zend_Mail::setDefaultFrom('noreply@marlon.be', 'No reply');
    			$transport = new Zend_Mail_Transport_Smtp('uit.telenet.be');
    			
    			$mail = new Zend_Mail();
    			$mail->addTo($email);
    			
    			$mail->setSubject('New password');
    			$mail->setBodyText('password: ' .  $npw);
    			$mail->send($transport);
    			
    			$this->_helper->redirector('passwordsend');
	    		
    		} 
    	}
    }
    
    public function logoutAction()
    {
    	Zend_Auth::getInstance()->clearIdentity();
    	$this->_helper->redirector('index', 'index');
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