<?php
class Application_Form_Search extends ZendX_JQuery_Form
{
	private $source;
	
	public function init() 
	{
		$this->setName('searchForm');
		
		$elem = new ZendX_JQuery_Form_Element_AutoComplete('search');
		$elem->setAttrib('style', 'width:300px');
				
		if($this->source == null) {
			$this->source == array();
		}
		
		$elem->setJQueryParams(array('source' => $this->source));
		
		$search = new Zend_Form_Element_Text('search');
		$search->setRequired(true)	
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim');
		 
		 $submit = new Zend_Form_Element_Submit('Search');		 
				
		 $this->addElements(array($elem,$submit));
	}
	
	public function setSource($source) {
		$this->source = $source;
	}
}
?>