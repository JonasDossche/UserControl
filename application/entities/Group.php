<?php
namespace Entities;

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

}