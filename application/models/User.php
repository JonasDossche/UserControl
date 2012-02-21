<?php
class Application_Model_User {
	private $_id;
	private $_name;
	private $_firstName;
	private $_pw;
	private $_email;
	
	public function __construct($id, $name, $firstName, $pw, $email)
	{
		$this->_id = $id;
		$this->_name = $name;
		$this->_firstName = $firstName;
		$this->_pw = $pw;
		$this->_email = $email;
	}
	
	public function getId() {
		return $this->_id;
	}
	
	public function setId($id) {
		$this->_id = $id;		
	}
	
	public function getName() {
		return $this->_name;
	}
	
	public function setName($name) {
		$this->_name = $name;
	}
	
	public function getFirstName() {
		return $this->_firstName;
	}
	
	public function setFirstName($firstName) {
		$this->_firstName = $firstName;
	}
	
	public function getPw() {
		return $this->_pw;	
	}
	
	public function setPw($pw) {
		$this->_pw = $pw;
	}
	
	public function getEmail() {
		return $this->_email;
	}
	
	public function setEmail($email) {
		$this->_email = $email;
	}
} 
?>