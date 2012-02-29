<?php
namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Repositories\GroupRepository")
 * @Table(name="groups")
 **/
class Group {
	
	/** @Column(type="integer")
	 *  @Id
	 *  @GeneratedValue(strategy="AUTO")
	 *  @var int
	 **/
	private $id;
	
	/** @Column(type="string")
	 * @var string
	 **/
	private $name;
	
	/**
	 * @ManyToMany(targetEntity="User", mappedBy="groups")
	 */
	private $users;
	
	public function __construct() {
		$this->users = new ArrayCollection();
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getUsers() {
		return $this->users;
	}
	
	public function removeAllUsers() {
		foreach($this->users as $user) {
			$user->removeGroup($this);
		}
		//$this->users = new ArrayCollection();		
	}
	
	public function addUser(User $user) {
		if(!$user->hasGroup($this)) {
			$user->addGroup($this);
		}
	}
	
	public function removeUser(User $user) {
		$user->removeElement($this);		
	}

}