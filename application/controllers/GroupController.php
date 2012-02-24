<?php
use Entities\User;
use Entities\Group;

class GroupController extends Zend_Controller_Action
{	
	private $groupRep;
	private $em;
	
	public function init()
	{
		$this->em = $this->getInvokeArg('bootstrap')->getResource('doctrine');
		$this->groupRep = $this->em->getRepository('Entities\Group');
		$this->_helper->layout->setLayout('contentlayout');	
	}
	
	public function overviewAction()
	{		
		$page = $this->getRequest()->getParam('page',0);
		$searchValue = $this->getRequest()->getParam('search', null);
						
		$form = new Application_Form_Search();
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
		
			if ($form->isValid($formData)) {											
				$this->_helper->redirector('overview','group',null, array('search'=>$form->getValue('search')));				
			}
			
		}		
		
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($this->em->getRepository('Entities\Group')->searchGroups($searchValue)));		
		
		$paginator->setItemCountPerPage(5);
		$paginator->setCurrentPageNumber($page);
		
		$this->view->paginator = $paginator;
		$this->view->form = $form;
	}
	
	public function deleteAction()
	{
		if ($this->getRequest()->isPost()) {
			$del = $this->getRequest()->getPost('del');
	
			if ($del == 'Yes') {
				$id = $this->getRequest()->getPost('id');
				$group = $this->em->getRepository('Entities\Group')->findOneById($id);
				$this->em->remove($group);
				$this->em->flush();
			}
	
			$this->_helper->redirector('overview', 'group', null, array('page' => 1));
		} else {
			$id = $this->_getParam('id', 0);
			$this->view->group = $this->em->getRepository('Entities\Group')->findOneById($id);
		}
	}
	
	public function addAction()
	{
		$form = new Application_Form_Addgroup();
		$this->view->form = $form;
	
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
	
			if ($form->isValid($formData)) {
				$name = $form->getValue('name');
	
				$group = new Group();
				$group->setName($name);
	
				$this->em->persist($group);
				$this->em->flush();
	
				$this->_helper->redirector('overview', null, null, array('page'=>1));
			}
		}
	}
	
	public function editAction()
	{			
		$id = $this->getRequest()->getParam('id');
			
		if(!$id) {
			$this->_helper->redirector('overview');
		}
		
		$usersForm = array();		
		$users = $this->em->getRepository('Entities\User')->findAll();
		$group = $this->groupRep->findOneById($id);
		$usersInGroup = $group->getUsers();
		
		foreach($users as $user) {
			if(!$usersInGroup->contains($user)) {
				$usersForm[] = $user;
			}
		}		
		
		$form = new Application_Form_Editgroup(array('users' => $usersForm));
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
	
			if ($form->isValid($formData)) {
				$group->setName($form->getValue('name'));	
				
				$group->removeAllUsers();
				
				$this->em->flush();			
				//var_dump($group->getUsers());
				//die();
				
				$userIds = $form->getValue('hidden');				
				$ids = ($userIds!="" || $userIds!= null) ? explode(",", $userIds) : array();				
								
				foreach ($ids as $uid) {					
					$user = $this->em->getRepository('Entities\User')->findOneById($uid);									
					$group->addUser($user);
				}
				
				$this->em->flush();
				$this->_helper->redirector('overview', null, null, array('page'=>1));
			}
		}
			
		$userIds = array();
		foreach ($usersInGroup as $user) {
			$userIds[] = $user->getId();
		}
		
		$data = array(
			'name'   => $group->getName(),
			'hidden' =>	implode(',', $userIds)			
		);
	
		$form->populate($data);
		$this->view->form = $form;
		$this->view->users = $usersInGroup;
	}
}