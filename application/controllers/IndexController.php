<?php

class IndexController extends Zend_Controller_Action
{
	private $em;
	
    public function init()
    {        	
    	$this->em = $this->getInvokeArg('bootstrap')->getResource('doctrine');    	 	
    }

    public function indexAction()
    {	      	
    	$users = new Application_Model_UserMapper();    	
    	
		$form = new Application_Form_Login();		
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {
				$email = $form->getValue('username');
				$pw = $form->getValue('pw');
									
				$user = $this->em->getRepository('Entities\User')->Authenticate($email, md5($pw));
				
				$result = '';
								
				if($user) {
					 //login correct
				     $result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,$user->getId(),array()); 
				     Zend_Auth::getInstance()->getStorage()->write($user);
				     $this->_helper->redirector('overview', 'content', null, array('page' => 1));
        		}else {
        			//login failed
            		$result =  new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,null,array());
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
	    		
    			$npw = $this->generatePass();
    			
    			$user = $this->em->getRepository('Entities\User')->findOneByMail($email);    
    			$user->setPw(md5($npw));	
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