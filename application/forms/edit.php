<?php
use Entities\Group;
use Entitie\User;

class Application_Form_Edit extends Zend_Form
{
	private $groups;
	private $user;
	
	public function init() 
	{
		$this->setName('Edit');
		
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Name: ')
			 ->setRequired(true)	
			 ->addFilter('StripTags')
			 ->addFilter('StringTrim');

		$firstName = new Zend_Form_Element_Text('firstName');
		$firstName->setLabel('First name: ')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim');		
		
		$email = new Zend_Form_Element_Text('mail');
		$email->setLabel('Email: ')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('EmailAddress');
		
		$groups = new Zend_Form_Element_Text('groups');
		$groups->setLabel('Groups: ')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim');		
		
		$groups = new Zend_Form_Element_MultiCheckbox('groups');
		$groups->setLabel('Groups:')
		->setRequired(true);
		
		$checked = array();
		
		foreach ($this->groups as $group) {
			if($this->user->hasGroup($group)) {
				$checked[] = $group->getId();
			}
			
			$groups->addMultiOption($group->getId(), $group->getName());
		}
		
		$groups->setValue($checked);
		
		 $pw = new Zend_Form_Element_Password('pw');
		 $pw->setLabel('Password: ')			
			->addFilter('StripTags')
		 	->addFilter('StringTrim')
		 	->addValidator(new Zend_Validate_Identical('pwConfirm'));
		 
		 $pwConfirm = new Zend_Form_Element_Password('pwConfirm');
		 $pwConfirm->setLabel('Confirm password: ')			 
			 ->addFilter('StripTags')
			 ->addFilter('StringTrim')
			 ->addValidator(new Zend_Validate_Identical('pw'));
		 
		 $submit = new Zend_Form_Element_Submit('Edit');
		 
		 
		 $this->addElements(array($name,$firstName,$email,$groups,$pw,$pwConfirm,$submit));
	}
	
	public function setGroups($groups) {
		$this->groups = $groups;
	}
	
	public function setUser($user) {
		$this->user = $user;
	}
}
?>