<?php
namespace location\models;
 

use models\User\Group;

use Doctrine\ORM\Query\AST\WhereClause;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use F1soft\DoctrineExtensions\Paginate\Paginate;
use	Doctrine\ORM\Query;
 
class CountryRepository extends EntityRepository{
	
	public function getCountryList($offset = NULL, $perPage = NULL, $filters= array()){
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array('c.id AS country_id','c.name', 'c.nationality', 'c.iso_2 as code2','c.iso_3 AS code3', 'c.dialing_code as dialing_code'))
			->from('location\models\Country','c')
            ->where('1=1');

        if( array_key_exists('name', $filters) and $filters['name'] !== ""){
            $qb->andWhere(" c.name LIKE '%" . $filters['name'] . "%' ");
        }

		$qb->orderBy('c.name', 'asc');

		if(!is_null($offset))
			$qb->setFirstResult($offset);
		
		if(!is_null($perPage))
			$qb->setMaxResults($perPage);

		$pagination = new Paginator($qb->getQuery(), $fetchJoin = true);
		return $pagination;
	}


}