<?php
class ContentController extends Zend_Controller_Action
{
	public function init()
	{
		$this->_helper->layout->setLayout('contentlayout');
		
	}
	
	public function addAction()
	{
		$form = new Application_Form_Add();
		$this->view->form = $form;
	
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
	
			if ($form->isValid($formData)) {
				$name = $form->getValue('name');
				$firstName = $form->getValue('firstName');
				$email = $form->getValue('email');				
				$pw = $form->getValue('pw');
				
				$users = new Application_Model_UserMapper();
				
				if($users->emailExists($email)!= null) {
					$this->view->error = "This email is already registered";
					return;
				}
				
				$user = new Application_Model_User(null, $name, $firstName,$pw,$email);
	
				$users->addUser($user);
				$this->_helper->redirector('overview', null, null, array('page'=>0));
			}
		}
	}
	
	public function editAction()
	{
		$form = new Application_Form_Edit();
		 
		$id = $this->getRequest()->getParam('id');
		 
		if(!$id) {
			$this->_helper->redirector('overview');
		}
		 
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			 
			if ($form->isValid($formData)) {
				$name = $form->getValue('name');
				$firstName = $form->getValue('firstName');
				$email = $form->getValue('mail');
				 
				if($form->getValue('pw') === null) {
					$pw=null;
				} else {
					$pw=$form->getValue('pw');
				}
				 
				$users = new Application_Model_UserMapper();
				$user = new Application_Model_User($id, $name, $firstName, $pw, $email);
				 
				$users->editUser($user);
				 
				$this->_helper->redirector('overview', null, null, array('page'=>1));
			}
		}	
	
		$users = new Application_Model_UserMapper();
		$user = $users->getUser($id);
		 
		 
	
		$data = array(
				'name'   => $user->getName(),
				'firstName' => $user->getFirstName(),
				'mail' => $user->getEmail(),
				'password' => $user->getPw()
		);
	
		$form->populate($data);
	
		$this->view->form = $form;
	}
	
	public function deleteAction()
	{
		if ($this->getRequest()->isPost()) {
			$del = $this->getRequest()->getPost('del');
			
				if ($del == 'Yes') {
					$id = $this->getRequest()->getPost('id');
					$users = new Application_Model_UserMapper();
					$users->deleteUser($id);
				}
				
			$this->_helper->redirector('overview', 'content', null, array('page' => 1));
		} else {
			$id = $this->_getParam('id', 0);
			$users = new Application_Model_UserMapper();
			$this->view->user = $users->getUser($id);
		}
	}
	
	public function overviewAction()
	{		
		$page = $this->getRequest()->getParam('page',0);
		$searchValue =($this->getRequest()->getParam('search')!= null) ? $this->getRequest()->getParam('search') : '';
		$users = new Application_Model_UserMapper();
		
		$paginator = '';
		
		
		$form = new Application_Form_Search();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
		
			if ($form->isValid($formData)) {											
				$this->_helper->redirector('overview','content',null, array('search'=>$form->getValue('search')));				
			}
			
		}
		
		if ($searchValue != '' || $searchValue != null) {
			$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($users->searchUsers($searchValue)));
		} else {
			$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($users->getAllUsers()));
		}
		
		$paginator->setItemCountPerPage(5);
		$paginator->setCurrentPageNumber($page);
		
		$this->view->paginator = $paginator;
		$this->view->form = $form;
	}
	
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->redirector('index', 'index');		
	}
}