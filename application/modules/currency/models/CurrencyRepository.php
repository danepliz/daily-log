<?php
namespace currency\models;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class CurrencyRepository extends EntityRepository{

    public function listCurrencies( $offset = NULL, $perpage = NULL, $filters = array() ){
        $qb = $this->_em->createQueryBuilder();

        $qb->select(array('c.id as id','c.name', 'c.iso_3', 'c.description','c.symbol',
            'cu.id as createdBy_id', 'cu.fullname as createdBy_name',
            'uu.id as updatedBy_id', 'uu.fullname as updatedBy_name',
            'c.created', 'c.updated'
            ))
            ->from('currency\models\Currency','c')
            ->leftJoin('c.createdBy','cu')
            ->leftJoin('c.updatedBy', 'uu')
        ;

        if (array_key_exists('name', $filters) && $filters['name'] !== '')
            $qb->andWhere("c.name LIKE '%".$filters['name']."%'");

        if(!is_null($offset))
            $qb->setFirstResult($offset);

        if(!is_null($perpage))
            $qb->setMaxResults($perpage);

        $qb->orderBy('c.name', 'asc');

        $query = $qb->getQuery();

        $paginator = new Paginator($query, $fetchJoin = true);
        return $paginator;
    }

}