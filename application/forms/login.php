<?php
class Application_Form_Login extends Zend_Form
{
	public function init() 
	{
		$this->setName('login');
		
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel('Email: ')
				 ->setRequired(true)	
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim');
				 
		
		 $pw = new Zend_Form_Element_Password('pw');
		 $pw->setLabel('Password: ')
			->setRequired(true)	
			->addFilter('StripTags')
		 	->addFilter('StringTrim');
		 
		 $submit = new Zend_Form_Element_Submit('login');
		 
		 
		 $this->addElements(array($username,$pw,$submit));
	}
}
?>