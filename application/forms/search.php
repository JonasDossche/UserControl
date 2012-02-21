<?php
class Application_Form_Search extends Zend_Form
{
	public function init() 
	{
		$this->setName('search');
				
		$search = new Zend_Form_Element_Text('search');
		$search->setRequired(true)	
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim');
		 
		 $submit = new Zend_Form_Element_Submit('Search');		 
		 
		 $this->addElements(array($search,$submit));
	}
}
?>