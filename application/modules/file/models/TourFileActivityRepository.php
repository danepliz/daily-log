<?php
namespace file\models;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class TourFileActivityRepository extends EntityRepository
{

    public function listXo($offset = NULL, $perpage = NULL, $filters = array())
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select(array('x.id as tour_file_activity_id','x.type',  'tf.fileNumber',
            'tf.client', 'x.xoNumber', 'x.xoCreatedDate', 'cb.fullname', 'a.name as agent_name', 'h.name as hotel_name'))
            ->from('file\models\TourFileActivityHotel', 'x')
            ->leftJoin('x.tourFile', 'tf')
            ->leftJoin('x.hotel', 'h')
            ->leftJoin('tf.agent', 'a')
            ->leftJoin('x.createdBy', 'cb')
            ->where("x.isXoGenerated = 1");

        if( array_key_exists('exchange_order', $filters) and $filters['exchange_order'] != '' ){
            $qb->andWhere('x.xoNumber LIKE  :exchange_order')->setParameter('exchange_order', '%'.$filters['exchange_order'].'%');
        }
        if( array_key_exists('file_number', $filters) and $filters['file_number'] != '' ){
            $qb->andWhere('tf.fileNumber LIKE  :file_number')->setParameter('file_number', '%'.$filters['file_number'].'%');
        }
        if( array_key_exists('agent_name', $filters) and $filters['agent_name'] != '' ){
            $qb->andWhere('a.id = '.$filters['agent_name']);
        }

        if(array_key_exists('created_from', $filters) && $filters['created_from'] != ''){
            $qb->andWhere('x.xoCreatedDate >= :minDate')->setParameter('minDate', $filters['created_from']);
        }

        if(array_key_exists('created_to', $filters) && $filters['created_to'] != ''){
            $qb->andWhere('x.xoCreatedDate <= :maxDate')->setParameter('maxDate', $filters['created_to']);
        }
        if( array_key_exists('created_by', $filters) and $filters['created_by'] != '' ){
            $qb->andWhere('cb.id = '.$filters['created_by']);
        }
        if( array_key_exists('client_name', $filters) and $filters['client_name'] != '' ){
            $qb->andWhere("tf.client LIKE  '%".$filters["client_name"]."%'");
        }

        $qb->orderBy('x.xoCreatedDate');
        

        if (!is_null($offset))
            $qb->setFirstResult($offset);

        if (!is_null($perpage))
            $qb->setMaxResults($perpage);

        $query = $qb->getQuery();

        $paginator = new Paginator($query, $fetchJoin = true);
        return $paginator;
    }

    public function listXoTest($offset = NULL, $perpage = NULL, $filters = array())
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('x');

        if( array_key_exists('xo_type', $filters) and $filters['xo_type'] != '' ){
            $type = $filters['xo_type'];
            if( $type == TourFileActivity::FILE_ACTIVITY_TYPE_HOTEL ){
                $qb->from('file\models\TourFileActivityHotel', 'x')
                    ->leftJoin('x.hotel', 'i');
            }else{
                $qb->from('file\models\TourFileActivity', 'x');
            }
        }else{
            $qb->from('file\models\TourFileActivity', 'x');
        }

        $qb->leftJoin('x.tourFile', 'tf')
            ->leftJoin('tf.agent', 'a')
            ->leftJoin('x.createdBy', 'cb')
            ->leftJoin('tf.permittedUsers', 'pu')
            ->where("x.isXoGenerated = 1")
        ->andWhere('x.status = '. TourFileActivity::ACTIVITY_STATUS_ACTIVE);

        if( array_key_exists('issued_to', $filters) and $filters['issued_to'] != '' ){
            $qb->andWhere('i.id = :issuedToID')->setParameter('issuedToID', $filters['issued_to']);
        }

        if( array_key_exists('exchange_order', $filters) and $filters['exchange_order'] != '' ){
            $qb->andWhere('x.xoNumber LIKE  :exchange_order')->setParameter('exchange_order', '%'.$filters['exchange_order'].'%');
        }
        if( array_key_exists('file_number', $filters) and $filters['file_number'] != '' ){
            $qb->andWhere('tf.fileNumber LIKE  :file_number')->setParameter('file_number', '%'.$filters['file_number'].'%');
        }
        if( array_key_exists('agent_name', $filters) and $filters['agent_name'] != '' ){
            $qb->andWhere('a.id = '.$filters['agent_name']);
        }

        if(array_key_exists('created_from', $filters) && $filters['created_from'] != ''){
            $qb->andWhere('x.xoCreatedDate >= :minDate')->setParameter('minDate', $filters['created_from']);
        }

        if(array_key_exists('created_to', $filters) && $filters['created_to'] != ''){
            $qb->andWhere('x.xoCreatedDate <= :maxDate')->setParameter('maxDate', $filters['created_to']);
        }
        if( array_key_exists('created_by', $filters) and $filters['created_by'] != '' ){
            $qb->andWhere('cb.id = '.$filters['created_by']);
        }
        if( array_key_exists('client_name', $filters) and $filters['client_name'] != '' ){
            $qb->andWhere("tf.client LIKE  '%".$filters["client_name"]."%'");
        }

        if( ! user_access('view all exchange orders') ){
            $currentUser = \Current_User::user();
            $currentUserId = $currentUser->id();

            $qb->andWhere(
                $qb->expr()->orX('IDENTITY(tf.createdBy) = :createdBy', 'pu.id = :createdBy')
            )->setParameter('createdBy', $currentUserId);
        }

        $qb->orderBy('x.xoCreatedDate');


        if (!is_null($offset))
            $qb->setFirstResult($offset);

        if (!is_null($perpage))
            $qb->setMaxResults($perpage);

        $query = $qb->getQuery();

        $paginator = new Paginator($query, $fetchJoin = true);
        return $paginator;
    }

    public function getNextXoNumber(){

        $tourFilePrefix = \Options::get('config_tourfile', 'YEPL');

        //$dql = "SELECT MAX(a.xoNumber) as xo FROM file\models\TourFileActivity a";
        $dql = "SELECT MAX(a.xoNumber) as xo FROM file\models\TourFileActivity a WHERE SUBSTRING(a.xoNumber, 1, ".strlen($tourFilePrefix).") = '".$tourFilePrefix."'";

        $result = $this->_em->createQuery($dql)->getSingleScalarResult();

        if( $result ){
            list($prefix, $seq) = explode('-', $result);
            $cur = (integer) $seq;
            $next = str_pad($cur+1, 4, '0', STR_PAD_LEFT);
            return $prefix.'-'.$next;
        }else{
            return $tourFilePrefix.'-0000';
        }
    }

}