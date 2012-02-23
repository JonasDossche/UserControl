<?php
use Validators\RecordExistsValidator;

class Application_Form_Password extends Zend_Form
{
	public function init() 
	{
		$this->setName('forgotPassword');
		
		$er = Zend_Registry::get('EntityManager')->getRepository('Entities\User');		
		$validator = new RecordExistsValidator($er, 'mail');
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email: ')
				 ->setRequired(true)	
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim')
				 ->addValidator('EmailAddress')
			     ->addValidator($validator);		
		 
		 $submit = new Zend_Form_Element_Submit('Send');		 
		 
		 $this->addElements(array($email,$submit));
	}
}
?>