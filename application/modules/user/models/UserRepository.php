<?php
namespace user\models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use F1soft\DoctrineExtensions\Paginate\Paginate;
use	Doctrine\ORM\Query;
 
class UserRepository extends EntityRepository{

	public function getUserList($offset = NULL, $per_page = NULL,$filters = NULL){
		
		$qb = $this->_em->createQueryBuilder();
		$qb->select(array('u.id as user_id', 'u.fullname','u.address',
				'u.mobile','u.email','u.status','u.created','u.phone',
				'gr.id as group_id','gr.name as groups' ))
			->from('user\models\User','u')
			->leftJoin('u.group','gr')
			->where('1=1')
			->andWhere("u.status != 3");

		if(is_array($filters) and count($filters) > 0)
		{
			
			if(isset($filters['full_name']) and $filters['full_name'] != ''){
				$qb->andWhere('u.fullname LIKE :fn')->setParameter('fn', '%'.$filters['full_name'].'%');
			}
			
			if(isset($filters['phone']) and $filters['phone'] != ''){
				$qb->andWhere('u.phone LIKE :phone')->setParameter('phone', $filters['phone']."%");
			}
			
			if(isset($filters['status']) and $filters['status'] != ''){
				$qb->andWhere('u.active = '.$filters['status']);
			}

            if(isset($filters['group']) and $filters['group'] != ''){
                $qb->andWhere('gr.id = '.$filters['group']);
            }
		}
		
		if(!is_null($offset)) $qb->setFirstResult($offset);
		
		if(!is_null($per_page)) $qb->setMaxResults($per_page);

		$qb->orderBy('u.fullname    ','asc');

		
		$pagination = new Paginator($qb->getQuery(), $fetchJoin = true);
		return $pagination;
		
	}
	
	
	public function generateApiKey(){
	
		$generatedNumber = random_string('alnum',6);
		
		$qb = $this->_em->createQueryBuilder();
		$qb->select('u')
			->from('user\models\User','u')
			->where('u.api_key= :val')
			->setParameter('val', $generatedNumber);
			
		$count = sizeof($qb->getQuery()->getScalarResult());
		
		if($count>=1){
			$this->generateApiKey();
		}
		else{ return $generatedNumber; }
	}
	
	public function generatePasswordResetKey(){
	
		$generatedNumber = random_string('alnum',32);
	
		$qb = $this->_em->createQueryBuilder();
		$qb->select('u')
		->from('user\models\User','u')
		->where('u.resetToken= :val')
		->setParameter('val', $generatedNumber);
			
		$count = sizeof($qb->getQuery()->getScalarResult());
	
		if($count>=1){
			$this->generatePasswordResetKey();
		}
		else{ 
			return $generatedNumber;
		}
	}
	
	public function getAgentUsers($agentId)
	{
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('u')
		->from('user\models\User', 'u')
		->Join('u.agent','a')
		->join('u.city', 'city')
		->join('city.state', 'state')
		->join('state.country', 'con')
		->where('u.agent = '. $agentId)
		->andWhere('u.deleted = FALSE');
		
		return $qb->getQuery()->getResult();
	}
}