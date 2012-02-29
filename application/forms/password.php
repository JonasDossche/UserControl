<?php
use Validators\RecordExistsValidator;
use Doctrine\ORM\EntityRepository;

class Application_Form_Password extends Zend_Form
{
	private $er;
	
	public function init() 
	{
		$this->setName('forgotPassword');
		
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email: ')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('EmailAddress');
		
		
			$validator = new RecordExistsValidator($this->er, 'mail');
			$email->addValidator($validator);
					
		 
		 $submit = new Zend_Form_Element_Submit('Send');		 
		 
		 $this->addElements(array($email,$submit));
	}
	
	public function setRepository(EntityRepository $repository) {
		$this->er = $repository;
	}
}
?>