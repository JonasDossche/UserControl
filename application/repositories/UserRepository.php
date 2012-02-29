<?php
namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Entities\User;

class UserRepository extends EntityRepository
{
	public function searchUsers($value) {
		$qb = $this->createQueryBuilder('u');
				
		if(!empty($value)) {
			$qb->where('u.name LIKE :value')
			   ->orWhere('u.firstName LIKE :value')
			   ->orWhere('u.mail LIKE :value')
			   ->setParameter('value', '%'.$value.'%');
		}
		
		return $qb->getQuery()->getResult();
	}
	
	public function authenticate($mail, $pw) {
		$qb = $this->createQueryBuilder('u');
		
		$qb->where('u.mail = :mail')
		   ->andWhere('u.pw = :pw')
		   ->setParameter('mail', $mail)
		   ->setParameter('pw', md5($pw));
		
		return $qb->getQuery()->getSingleResult();	
	}
}