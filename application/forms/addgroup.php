<?php
use Validators\RecordNotExistsValidator;
use Entities\Group;

class Application_Form_Addgroup extends Zend_Form
{		
	public function init() 
	{		
		$this->setName('Add');
		$er = Zend_Registry::get('EntityManager')->getRepository('Entities\Group');
		$validator = new RecordNotExistsValidator($er, 'name');
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Name: ')
			 ->setRequired(true)	
			 ->addFilter('StripTags')
			 ->addFilter('StringTrim')
			 ->addValidator($validator);	
		 
		 $submit = new Zend_Form_Element_Submit('Add');
		 
		 
		 $this->addElements(array($name,$submit));
	}
}
?>