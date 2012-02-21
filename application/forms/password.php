<?php
class Application_Form_Password extends Zend_Form
{
	public function init() 
	{
		$this->setName('forgotPassword');
		
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email: ')
				 ->setRequired(true)	
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim')
				 ->addValidator('EmailAddress');
		 
		 $submit = new Zend_Form_Element_Submit('Send');		 
		 
		 $this->addElements(array($email,$submit));
	}
}
?>