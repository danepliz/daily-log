<?php

namespace file\models;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TourFileRepository extends EntityRepository{


    public function listFiles($offset = NULL, $per_page = NULL, $filters = array()){
        $qb = $this->_em->createQueryBuilder();

        $qb->select('f')
            ->from('file\models\TourFile', 'f')
            ->leftJoin('f.agent', 'a')
            ->leftJoin('f.nationality', 'n')
            ->leftJoin('f.market', 'm')
            ->leftJoin('f.permittedUsers', 'pu')
            ->where('1=1');

        if( array_key_exists('agent', $filters) and $filters['agent'] != '' ){
            $qb->andWhere('a.id = '.$filters['agent']);
        }

        if( array_key_exists('nationality', $filters) and $filters['nationality'] != '' ){
            $qb->andWhere('n.id = '.$filters['nationality']);
        }

        if( array_key_exists('market', $filters) and $filters['market'] != '' ){
            $qb->andWhere('m.id = '.$filters['market']);
        }

        if( array_key_exists('file', $filters) and $filters['file'] != '' ){
            $qb->andWhere('f.fileNumber = :fileNumber')->setParameter('fileNumber', $filters['file']);
        }

        if( array_key_exists('client', $filters) and $filters['client'] != '' ){
            $qb->andWhere('f.client LIKE  :client')->setParameter('client', '%'.$filters['client'].'%');
        }

        if( array_key_exists('created', $filters) and $filters['created'] != '' ){
            $createdFrom = new \DateTime($filters['created'].' 00:00:00');
            $createdTo = new \DateTime($filters['created'].' 23:59:59');
            $qb->andWhere('f.created > :createdFrom')->setParameter('createdFrom', $createdFrom);
            $qb->andWhere('f.created < :createdTo')->setParameter('createdTo', $createdTo);
        }

        if( ! user_access('view all tour files') ){
            $currentUser = \Current_User::user();
            $currentUserId = $currentUser->id();

            $qb->andWhere(
                $qb->expr()->orX('IDENTITY(f.createdBy) = :createdBy', 'pu.id = :createdBy')
            )->setParameter('createdBy', $currentUserId);
        }

        if(!is_null($offset))
            $qb->setFirstResult($offset);

        if(!is_null($per_page))
            $qb->setMaxResults($per_page);

        $query = $qb->getQuery();

        $pagination = new Paginator($query, $fetchJoin = true);
        return $pagination;

    }

    public function getActivityDetailsByActivityType($activityId, $activeOnly = TRUE){
        $qb = $this->_em->createQueryBuilder();

        $qb->select('ad')
            ->from('file\models\TourFileActivityDetail', 'ad')
            ->where('ad.tourActivity = :activityID')
            ->setParameter('activityID', $activityId);

        if( $activeOnly ){
            $qb->andWhere('ad.deleted = 0');
        }

        $query = $qb->getQuery();

        return $query->getResult();


    }

    public function getActivitiesByTourFile($id, $activeOnly = TRUE){
        $qb = $this->_em->createQueryBuilder();

        $qb->select('ad')
            ->from('file\models\TourFileActivityDetail', 'ad')
            ->leftJoin('ad.tourActivity', 'ta')
            ->where('ta.id = :activityId')
            ->setParameter('activityId', $id);

        if( $activeOnly ){
            $qb->andWhere('ad.deleted = 0');
        }

        $query = $qb->getQuery();

        return $query->getResult();


    }


}