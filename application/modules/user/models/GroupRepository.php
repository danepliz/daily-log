<?php
namespace user\models;
 

use Doctrine\ORM\Query\AST\WhereClause;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use content\models\Content,
	F1soft\DoctrineExtensions\Paginate\Paginate,
	Doctrine\ORM\Query;
 
class GroupRepository extends EntityRepository{
	
	public function getGroupList(){
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array('g.id AS group_id', 'g.name', 'g.description', 'g.mtoOnly'))
			->from('user\models\Group', 'g')
            ->where('g.id != 1')
			->groupBy('g.id')
			->orderBy('g.name', 'ASC');
			;
		
		return $qb->getQuery()->getResult();
		
	}
	
	public function getUserCount() {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('g.id AS group_id', 'COUNT(u.id) AS numusers')
			->from('user\models\Group', 'g')
			->leftJoin('g.users', 'u')
            ->where('u.status = :status')->setParameter('status', '1')
			->groupBy('g.id')
			;

//        print_r($qb->getQuery()->getSQL()); die;
		
//		$counts = $qb->getQuery()->getResult();
		
		return  $qb->getQuery()->getResult();
	}
	
	public function getAllPermissions(){
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array('p.id AS perm_id', 'p.name', 'p.description'))
			->from('user\models\Permission', 'p');
		
		return $qb->getQuery()->getResult();
	
	}
}