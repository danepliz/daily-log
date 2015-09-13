<?php
namespace parameter\models;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class TourActivityParameterRepository extends EntityRepository{
	
	public function listParameters( $offset = NULL, $per_page = NULL, $filters = array() ){
		$qb = $this->_em->createQueryBuilder();

		$qb->select(array('p.id', 'p.name', 'p.travelXO as travel_xo', 'p.transportXO as transport_xo', 'p.hotelXO as hotel_xo',
            'p.entranceXO as entrance_xo', 'p.otherXO as other_xo',))
			->from('parameter\models\TourActivityParameter','p')
			;

		if(!is_null($offset))
			$qb->setFirstResult($offset);

		if(!is_null($per_page))
			$qb->setMaxResults($per_page);

		$qb->orderBy('p.name', 'asc');

		$query = $qb->getQuery();

		$pagination = new Paginator($query, $fetchJoin = true);
		return $pagination;
	}
	
}