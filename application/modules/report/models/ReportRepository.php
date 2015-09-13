<?php
namespace report\models;
 

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
 
class ReportRepository extends EntityRepository{
	
	public function getReportsList()
	{
		
		$qb=$this->_em->createQueryBuilder();
		$qb->select(array('r.id','r.name','r.slug'))
				->from('report\models\ReportGroup','r');
		return $qb->getQuery()->getResult();
		
		
	}
	
//	public function getReports($offset = NULL,$perpage = NULL){
	public function getReports($report_id=NULL){
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array('r.id','r.description','r.sqlquery','r.name','r.slug'))
			->from('report\models\Report','r');
		
	//	if(!is_null($offset))
			//$qb->setFirstResult($offset);
		
		//if(!is_null($perpage))
			//$qb->setMaxResults($perpage);
		
		//$paginator = new Paginator($qb->getQuery(), $fetchJoin = true);
		//return $paginator;
		
	return $qb->getQuery()->getResult();
	}
	
	public function getReportGroups($filters = array()) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select(array('rg.id','rg.name','rg.slug'))
			->from('report\models\ReportGroup','rg')
			->where("1 = 1")
			;

		if (count($filters) > 0) {
			foreach($filters as $k => $v)
				$qb->andWhere("rg.".$k."='".$v."'");
		}
			
		return $qb->getQuery()->getResult();
		
	}
	

}