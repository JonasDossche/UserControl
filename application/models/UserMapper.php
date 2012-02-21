<?php
class Application_Model_UserMapper {

	private $_dbTable;

	private function setDbTable($dbTable)
	{
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}

		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}

		$this->_dbTable = $dbTable;
		return $this;
	}

	public function getDbTable()
	{
		if ($this->_dbTable === null) {
			$this->setDbTable('Application_Model_DbTable_Users');
		}
		
		return $this->_dbTable;
	}

	public function getUser($id) {
		$row = $this->getDbTable()->fetchRow('id = ' . (int) $id);
		
		if(!$row) {
			throw new Exception("User not found");
		}
		
		$user = New Application_Model_User($row->id,$row->name,$row->firstName,$row->password,$row->mail);	
		
		return $user;
		 
	}
	
	public function getAllUsers() {		
		$result = $this->getDbTable()->fetchAll();
		$users = array();
		
		foreach($result as $row) {
			$user = New Application_Model_User($row->id,$row->name,$row->firstName,$row->password,$row->mail);				
			$users[] = $user;
		}
		
		return $users;
	}
	
	public function getUsers($start, $limit) {
		$result = $this->getDbTable()->fetchAll(
			$this->getDbTable()->select()			
			->order('name ASC')
			->limit($limit,$start)
		);
						
		
		$users = array();
		foreach($result as $row) {
			$user = New Application_Model_User($row->id,$row->name,$row->firstName,$row->password,$row->mail);
			$users[] = $user;
		}
		
		return $users;
						
	}
	
	public function searchUsers($value, $start = 0, $limit = 0) {
		$query = $this->getDbTable()->select();
		if ( !empty($value) )
			$query->where('mail LIKE ?', '%'.$value.'%')
				->orWhere('name LIKE ?', '%'.$value.'%')
				->orWhere('firstName LIKE ?', '%'.$value.'%');
			$query->order('name ASC')
				->limit($limit,$start);
			
		$result = $this->getDbTable()->fetchAll($query);
		
		$users = array();
		foreach($result as $row) {
			$user = New Application_Model_User($row->id,$row->name,$row->firstName,$row->password,$row->mail);
			$users[] = $user;
		}
		
		return $users;
	}
	
	public function addUser(Application_Model_User $user) {
		$data = array(
			'name'   => $user->getName(),
			'firstName' => $user->getFirstName(),
			'mail' => $user->getEmail(),
			'password' => md5($user->getPw())
		);
		
		$this->getDbTable()->insert($data);
	}
	
	public function editUser(Application_Model_User $user) {
		
		if (($id = $user->getId()) === null) {
			throw new Exception('Invalid user data');				
		}
		
		$data = array(
			'name'   => $user->getName(),
			'firstName' => $user->getFirstName(),
			'mail' => $user->getEmail()			
		);
		
		if(($pw = $user->getPw()) !== null and $pw != '') {
			$data += array('password' => md5($pw));
		}
		
		$this->getDbTable()->update($data, array('id = ?' => (int) $id));		
	}
	
	public function deleteUser($id) {
		$this->getDbTable()->delete('id =' . (int) $id);
	}
	
	public function emailExists($email) {
		$row = $this->getDbTable()->fetchRow($this->getDbTable()->select()
				->where('mail = ?', $email));
		
		if(!$row) {
			return null;
		} else {
			return $row->id;
		}
	}
	
}