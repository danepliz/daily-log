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


}