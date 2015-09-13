<?php
namespace hotel\models;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class HotelRepository extends EntityRepository{
	
	public function listHotels( $offset = NULL, $per_page = NULL, $filters = array() )
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('hotel')
            ->from('hotel\models\Hotel', 'hotel')
            ->join('hotel.country', 'country')
            ->join('hotel.category', 'category')
            ->join('hotel.grade', 'grade')
            ->where('1=1');

        if (array_key_exists('name', $filters) && $filters['name'] !== ''){
            $qb->andWhere($qb->expr()->like('hotel.name', $qb->expr()->literal("%".$filters['name']."%")));
        }

        if( array_key_exists('country', $filters) && $filters['country'] !== ''  )
            $qb->andWhere("country.id = ". $filters['country']);

        if( array_key_exists('city', $filters) && $filters['city'] !== ''  )
            $qb->andWhere("hotel.city LIKE  '%". $filters['city']."%'");

        if( array_key_exists('status', $filters) && $filters['status'] !== ''  )
            $qb->andWhere("hotel.status = '". $filters['status']."'");

        if( array_key_exists('email', $filters) && $filters['email'] !== ''  )
            $qb->andWhere("hotel.emails LIKE '%". $filters['email']."%'");

        if( array_key_exists('phone', $filters) && $filters['phone'] !== ''  )
            $qb->andWhere("hotel.phones LIKE '%". $filters['phone']."%'");

        if( array_key_exists('service', $filters) && $filters['service'] !== ''  )
            $qb->andWhere("service.id = ". $filters['service']);

        if( array_key_exists('grade', $filters) && $filters['grade'] !== ''  )
            $qb->andWhere("grade.id = ". $filters['grade']);

        if( array_key_exists('category', $filters) && $filters['category'] !== ''  )
            $qb->andWhere("category.id = ". $filters['category']);

		if(!is_null($offset))
			$qb->setFirstResult($offset);

		if(!is_null($per_page))
			$qb->setMaxResults($per_page);

		$qb->orderBy('hotel.name', 'asc');
		$query = $qb->getQuery();

		$pagination = new Paginator($query, $fetchJoin = true);
		return $pagination;

//        return array();
	}


    public function listContactPerson($offset = NULL, $perpage = NULL, $filters = array())
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('x')
            ->from('hotel\models\HotelContactPerson', 'x')
            ->leftJoin('x.hotel', 'h')
            ->Where("x.deleted=0");

        if( array_key_exists('hotel', $filters) and $filters['hotel'] !==''  ){
            $qb->andWhere('h.id = :hotelID')->setParameter('hotelID', $filters['hotel']);
        }


        if (!is_null($offset))
            $qb->setFirstResult($offset);

        if (!is_null($perpage))
            $qb->setMaxResults($perpage);

        $query = $qb->getQuery();

        $paginator = new Paginator($query, $fetchJoin = true);
        return $paginator;
    }

    public function listHotelServices($offset = NULL, $perPage = NULL, $filters = array()){
        $qb = $this->_em->createQueryBuilder();


        $qb->select('h.id as hotelId','h.name as hotelName','service.price','service.service_name')
            ->from('hotel\models\HotelServices','service')
            ->leftJoin('service.hotel', 'h')
            ->where('1=1')
            ->andWhere("h.status='ACTIVE'");

        $hasFilter = FALSE;

        if(array_key_exists('hotel', $filters) && $filters['hotel'] != ''){
            $qb->andWhere('h.id = '.$filters['hotel']);
            $hasFilter = TRUE;
        }

        if( !$hasFilter ){

            if(!is_null($offset))
                $qb->setFirstResult($offset);

            if(!is_null($perPage))
                $qb->setMaxResults($perPage);
        }


        $qb->orderBy('h.name', 'asc');
        $query = $qb->getQuery();

        $pagination = new Paginator($query, $fetchJoin = true);
        return $pagination;
    }





	
}