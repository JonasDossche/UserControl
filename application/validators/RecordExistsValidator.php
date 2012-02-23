<?php
namespace Validators;
use Zend_Validate_Abstract;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class RecordExistsValidator extends Zend_Validate_Abstract
{
	private $er;
	private $column;
	const RECORD_NOT_EXISTS = 'not exists';
	
	public function __construct(EntityRepository $er, $column) {
		$this->er = $er;
		$this->column = $column;	
	}
	
	protected $_messageTemplates = array(
		self::RECORD_NOT_EXISTS => "The record does not exist."
	);
	 
	public function isValid($value)
	{	
				
		if(call_user_func(array($this->er,'findOneBy' . $this->column), $value) == null) {
			$this->_error(self::RECORD_NOT_EXISTS);
			return false;	
		}
				
		return true;
	}
}