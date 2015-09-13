<?php
namespace market\models;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class MarketRepository extends EntityRepository{
	
	public function listMarkets( $offset = NULL, $perpage = NULL, $filters = array() ){
//		$qb = $this->_em->createQueryBuilder();
//
//		$qb->select(array('a.id as agent_id','a.slug','a.name','a.branch_code as agent_code','c.name as country','a.phone','a.email','a.address',
//					'p.name as place', 'ct.name as city','st.name as state','a.fax', 'a.status'))
//			->from('agent\models\Agent','a')
//            ->join('a.place','p')
//            ->join('p.city','ct')
//			->join('ct.state','st')
//			->join('st.country','c')
//			->where('a.parentAgent IS NULL')
//			->andWhere("a.deleted = 0")
//
//			;
//
//		if (array_key_exists('inCountry', $filters) && $filters['inCountry'] !== '')
//			$qb->andWhere("c.id IN (". $filters['inCountry'] .")");
//
//		if (array_key_exists('inAgent', $filters) && $filters['inAgent'] != '')
//			$qb->andWhere("a.id IN (". $filters['inAgent'] .")");
//
//		if(array_key_exists('active', $filters) && $filters['active'] != '')
//			$qb->andWhere('a.active = '.$filters['active']);
//
//		if(array_key_exists('country', $filters) && $filters['country'] != '')
//			$qb->andWhere('c.id = '.$filters['country']);
//
//		if(array_key_exists('group', $filters) && $filters['group'] != '')
//			$qb->andWhere('a.groups = '.$filters['group']);
//
//		if(array_key_exists('city', $filters) and $filters['city'] != '')
//			$qb->andWhere('ct.id = :ctID')->setParameter('ctID', $filters['city']);
//
//		if(!is_null($offset))
//			$qb->setFirstResult($offset);
//
//		if(!is_null($perpage))
//			$qb->setMaxResults($perpage);
//
//		$qb->orderBy('a.name', 'asc');
//		$query = $qb->getQuery();
//
//		$paginator = new Paginator($query, $fetchJoin = true);
//		return $paginator;

        return array();
	}
	
}