<?php

namespace project\models;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProjectRepository extends EntityRepository
{

    public function listProjects($offset = NULL, $perPage = NULL, $filters = []){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('p')
            ->from('project\models\Project', 'p')
            ;

        if($offset){
            $qb->setFirstResult($offset);
        }

        if($perPage){
            $qb->setMaxResults($perPage);
        }

        $query = $qb->getQuery();
        $paginate = new Paginator($query, $fetchJoin = TRUE);

        return $paginate;

    }


    public function searchForMembers($query, $project = NULL){

        $qb = $this->_em->createQueryBuilder();


        $qb->select('u')
            ->from('user\models\User' , 'u')
            ->where('u.status = 1');

        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('u.fullname', $qb->expr()->literal('%'.$query.'%')),
                $qb->expr()->like('u.email', $qb->expr()->literal('%'.$query.'%'))
            )
        );


        if($project){

            $qb2 = $this->_em->createQueryBuilder();
            $qb->andWhere(
                $qb->expr()->notIn(
                    'u.id',
                    $qb2->select('m.id')
                        ->from('project\models\Project', 'p')
                        ->innerJoin('p.members', 'm')
                        ->where('p.id = '.$project)
                        ->getDQL()
                )
            );
        }
        return $qb->getQuery()->getResult();
    }


}