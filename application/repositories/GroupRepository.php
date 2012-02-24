<?php
namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Entities\User;

class GroupRepository extends EntityRepository
{
	public function searchGroups($value) {
		$qb = $this->createQueryBuilder('g');	
	
		if(!empty($value)) {
			$qb->where('g.name LIKE :value')
			->setParameter('value', '%'.$value.'%');
		}
	
		return $qb->getQuery()->getResult();
	}
}