<?php
use Entities\User;
use Entities\Group;

class UserController extends Zend_Controller_Action
{
	private $userRep;
	private $em;
	
	public function init()
	{
		$this->em = $this->getInvokeArg('bootstrap')->getResource('doctrine');
		$this->userRep = $this->em->getRepository('Entities\User');
		$this->_helper->layout->setLayout('contentlayout');
		
	}
	
	public function addAction()
	{		
		$groups = $this->em->getRepository('Entities\Group')->findAll();
		$form = new Application_Form_Add(array('groups' => $groups, 'Entitymanager' => $this->em));
		$this->view->form = $form;
	
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
	
			if ($form->isValid($formData)) {
				$name = $form->getValue('name');
				$firstName = $form->getValue('firstName');
				$email = $form->getValue('email');				
				$pw = $form->getValue('pw');		
				$groups = $form->getValue('groups');
				
				$user = new User();
				$user->setFirstName($firstName);
				$user->setPw(md5($pw));
				$user->setName($name);
				$user->setEmail($email);
								
				foreach ($groups as $groupId) {
					$group = $this->em->getRepository('Entities\Group')->findOneById($groupId);
					$user->addGroup($group);
				}			
				
				$this->em->persist($user);
				$this->em->flush();
				
				$this->_helper->redirector('overview', null, null, array('page'=>0));
			}
		}
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
			
		if(!$id) {
			$this->_helper->redirector('overview');
		}
		
		$user = $this->userRep->findOneById($id);
		$groups = $this->em->getRepository('Entities\Group')->findAll();
		$form = new Application_Form_Edit(array('groups'=>$groups, 'user' =>$user));		
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			 
			if ($form->isValid($formData)) {
				$user->setName($form->getValue('name'));
				$user->setFirstName($form->getValue('firstName'));
				$user->setEmail($form->getValue('mail'));
				 
				if(!$form->getValue('pw') === null) {
					$user->setPw(md5($form->getValue('pw')));
				} 
				
				$user->removeAllGroups();
				$groups = $form->getValue('groups');
				
				foreach ($groups as $gid) {					
					$group = $this->em->getRepository('Entities\Group')->findOneById($gid);					
					$user->addGroup($group);
				}
												
				$this->em->flush();			 
				$this->_helper->redirector('overview', null, null, array('page'=>1));
			}
		}
				
		$data = array(
				'name'   => $user->getName(),
				'firstName' => $user->getFirstName(),
				'mail' => $user->getEmail()
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
					$user = $this->userRep->findOneById($id);
					$this->em->remove($user);
					$this->em->flush();
				}
				
			$this->_helper->redirector('overview', 'user', null, array('page' => 1));
		} else {
			$id = $this->_getParam('id', 0);			
			$this->view->user = $this->userRep->findOneById($id);
		}
	}
	
	public function overviewAction()
	{			
		$page = $this->getRequest()->getParam('page',0);
		$searchValue = $this->getRequest()->getParam('search', null);
						
		$form = new Application_Form_Search();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
		
			if ($form->isValid($formData)) {											
				$this->_helper->redirector('overview','user',null, array('search'=>$form->getValue('search')));				
			}
			
		}		
		
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($this->userRep->searchUsers($searchValue)));		
		
		$paginator->setItemCountPerPage(5);
		$paginator->setCurrentPageNumber($page);
		
		$this->view->paginator = $paginator;
		$this->view->form = $form;
	}
		
}