<?php
namespace Entities;

use Entities\Group;
/**
 * @Entity(repositoryClass="Repositories\UserRepository")
 * @Table(name="users")
 **/
class User {
	
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
	
	/** @Column(type="string") 
	 * @var string 
	 **/
	private $firstName;
	
	/** @Column(type="string")
	 * @var string 
	 **/
	private $pw;
	
	/** @Column(type="string") 
	 * @var string 
	 **/
	private $mail;

	/**
	 * @ManyToMany(targetEntity="Group", inversedBy="users")
	 * @JoinTable(name="users_groups")
	 */
	private $groups;
	
	public function __construct() {
		$this->groups = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getFirstName() {
		return $this->firstName;
	}

	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	public function getPw() {
		return $this->pw;
	}

	public function setPw($pw) {
		$this->pw = $pw;
	}

	public function getEmail() {
		return $this->mail;
	}

	public function setEmail($email) {
		$this->mail = $email;
	}
			
	public function addGroup(Group $group) {
		if(!$this->groups->contains($group)) {
			$this->groups->add($group);
		}
	}
	
	public function removeAllGroups() {
		foreach($this->groups as $group) {
			$this->groups->removeElement($group);
		}
	}
	
	public function getGroups() {
		return $this->groups;
	}
}
?>