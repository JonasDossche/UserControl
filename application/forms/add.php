<?php
class Application_Form_Add extends Zend_Form
{
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
		
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email: ')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('EmailAddress');
		
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
		 
		 
		 $this->addElements(array($name,$firstName,$email,$pw,$pwConfirm,$submit));
	}
}
?>