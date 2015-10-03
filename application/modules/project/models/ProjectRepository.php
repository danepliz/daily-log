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

        $sql = "SELECT u.id as user_id, u.email as email, u.fullname as name FROM user\models\User u WHERE u.status = 1 ";

        $sql .= " AND ( u.fullname LIKE '%$query%' OR u.email LIKE '%$query%' ) ";

//        if($project){
//            $sql .= " AND u.id NOT IN ( SELECT m.user_id FROM ys_project_members m WHERE m.project_id = 1) ";
//        }

        $dql = $this->_em->createQuery($sql);


        return $dql->getScalarResult();


    }


}