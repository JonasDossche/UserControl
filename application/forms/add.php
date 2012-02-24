<?php
use Validators\RecordNotExistsValidator;

class Application_Form_Add extends Zend_Form
{
	private $groups;
	private $em;
		
	public function init() 
	{		
		$this->setName('Add');
		
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
		
		
		$er = $this->em->getRepository('Entities\User');		
		$validator = new RecordNotExistsValidator($er, 'mail');
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email: ')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('EmailAddress')
			->addValidator($validator);
		
		$groups = new Zend_Form_Element_MultiCheckbox('groups');
		$groups->setLabel('Groups:')
		       ->setRequired(true);
		
		foreach ($this->groups as $group) {
			$groups->addMultiOption($group->getId(), $group->getName());
		}
			
				
		 $pw = new Zend_Form_Element_Password('pw');
		 $pw->setLabel('Password: ')
			->setRequired(true)	
			->addFilter('StripTags')
		 	->addFilter('StringTrim');
		 
		 $pwConfirm = new Zend_Form_Element_Password('pwConfirm');
		 $pwConfirm->setLabel('Confirm password: ')
			 ->setRequired(true)
			 ->addFilter('StripTags')
			 ->addFilter('StringTrim')
			 ->addValidator(new Zend_Validate_Identical('pw'));
		 
		 $submit = new Zend_Form_Element_Submit('Add');
		 
		 
		 $this->addElements(array($name,$firstName,$email,$groups,$pw,$pwConfirm,$submit));
	}
	
	public function setGroups($groups)
	{
		$this->groups = $groups;
	}
	
	public function setEntityManager($em) {
		$this->em = $em;
	}
}
?>