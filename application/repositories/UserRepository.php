<?php
namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Entities\User;

class UserRepository extends EntityRepository
{
	public function searchUsers($value) {
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('u')
		   ->from('Entities\User', 'u');
		
		if(!empty($value)) {
			$qb->where('u.name LIKE :value')
			   ->orWhere('u.firstName LIKE :value')
			   ->orWhere('u.mail LIKE :value')
			   ->setParameter('value', '%'.$value.'%');
		}
		
		return $qb->getQuery()->getResult();
	}
	
	public function authenticate($mail, $pw) {
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('u')
		   ->from('Entities\User', 'u')
		   ->where('u.mail = :mail')
		   ->andWhere('u.pw = :pw')
		   ->setParameter('mail', $mail)
		   ->setParameter('pw', $pw);
		
		$res =  $qb->getQuery()->getResult();
		
		if($res) {
			return $res[0];
		} else {
			return null;
		}
	}
}