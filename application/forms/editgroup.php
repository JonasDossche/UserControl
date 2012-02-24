<?php
class Application_Form_Editgroup extends Zend_Form
{
	private $users;
	
	public function init() 
	{
		$this->setName('editgroup');
		
		$hidden = new Zend_Form_Element_Hidden('hidden');
		$hidden->setValue('test');
		
		
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Name: ')
			 ->setRequired(true)	
			 ->addFilter('StripTags')
			 ->addFilter('StringTrim');
		
		$users = null;
		
		if($this->users) {
			$users = new Zend_Form_Element_Select('users');
			$users->setLabel('Users: ')
				  ->setRequired(true)
			      ->addMultioption('0', 'select a user');
			 
			foreach ($this->users as $user) {
				$users->addMultiOption($user->getId(), $user->getEmail());
			}
		}
		
		 $submit = new Zend_Form_Element_Submit('Edit');		 
		 
		 $this->addElements(array($name,$users,$hidden,$submit));
	}
	
	public function setUsers($users)
	{
		$this->users = $users;
	}
}
?>