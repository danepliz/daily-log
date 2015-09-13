<?php
namespace agent\models;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class AgentRepository extends EntityRepository{

	public function listPrincipalAgents( $offset = NULL, $perpage = NULL, $filters = array() ){
		$qb = $this->_em->createQueryBuilder();

		$qb->select(array('a.id as agent_id','a.slug','a.name','c.name as country','a.phone1',
			'a.phone2', 'a.email1', 'a.website1', 'a.website2', 'a.email2', 'a.address',
			'a.city as city', 'a.fax', 'a.pobox as po_box', 'a.status'))
			->from('agent\models\Agent','a')
            ->leftJoin('a.permittedUsers', 'pu')
			->leftJoin('a.country','c')
		;

		if (array_key_exists('name', $filters) && $filters['name'] !== '')
			$qb->andWhere("a.name LIKE '%".$filters['name']."%'");

		if (array_key_exists('email', $filters) && $filters['email'] !== ''){
			$qb->andWhere(
				$qb->expr()->orX("a.email1 LIKE '%".$filters['email']."%'", "a.email2 LIKE '%".$filters['email']."%'")
			);
		}

		if (array_key_exists('status', $filters) && $filters['status'] !== '') {
			$qb->andWhere("a.status = " . $filters['status']);
		}
		else{
			$qb->andWhere("a.status = 1");
		}
		if(array_key_exists('country', $filters) && $filters['country'] != '')
			$qb->andWhere('c.id = '.$filters['country']);

		if(array_key_exists('city', $filters) and $filters['city'] != '')
			$qb->andWhere("a.city LIKE '%".$filters['city']."%'");

        if( ! user_access('view all agents') ){
            $currentUser = \Current_User::user();
            $currentUserId = $currentUser->id();

            $qb->andWhere(
                $qb->expr()->orX('IDENTITY(a.createdBy) = :createdBy', 'pu.id = :createdBy')
            )->setParameter('createdBy', $currentUserId);
        }
        $qb->groupBy('a.id');

		if(!is_null($offset))
			$qb->setFirstResult($offset);

		if(!is_null($perpage))
			$qb->setMaxResults($perpage);

		$qb->orderBy('a.name', 'asc');

		$query = $qb->getQuery();

//        show_pre($query->getSQL());
//        show_pre($qb->getParameters()); die;

		$paginator = new Paginator($query, $fetchJoin = true);
		return $paginator;
	}

	public function getUserAgents( $offset = NULL, $perpage = NULL, $filters = array(),$currentUser ){
		$qb = $this->_em->createQueryBuilder();

		$qb->select(array('a.id as agent_id','a.slug','a.name','c.name as country','a.phone1',
			'a.phone2', 'a.email1', 'a.website1', 'a.email2', 'a.address',
			'a.city as city', 'a.fax', 'a.pobox as po_box', 'a.status'))
			->from('agent\models\Agent','a')
			->join('a.country','c')
			->andWhere("a.createdBy=". $currentUser->id())
		;
		if (array_key_exists('name', $filters) && $filters['name'] !== '')
			$qb->andWhere("a.name LIKE '%".$filters['name']."%'");

		if (array_key_exists('email', $filters) && $filters['email'] !== ''){
			$qb->andWhere(
				$qb->expr()->orX("a.email1 LIKE '%".$filters['email']."%'", "a.email2 LIKE '%".$filters['email']."%'")
			);
		}

		if (array_key_exists('status', $filters) && $filters['status'] !== '') {
			$qb->andWhere("a.status = " . $filters['status']);
		}
		if (array_key_exists('user', $filters) && $filters['user'] !== '') {
			$qb->andWhere("a.createdBy_id = " . $filters['user']);
		}
		else{
			$qb->andWhere("a.status = 1");
		}

//		if(array_key_exists('active', $filters) && $filters['active'] != '')
//			$qb->andWhere('a.active = '.$filters['active']);

		if(array_key_exists('country', $filters) && $filters['country'] != '')
			$qb->andWhere('c.id = '.$filters['country']);

		if(array_key_exists('city', $filters) and $filters['city'] != '')
			$qb->andWhere("a.city LIKE '%".$filters['city']."%'");

		if(!is_null($offset))
			$qb->setFirstResult($offset);

		if(!is_null($perpage))
			$qb->setMaxResults($perpage);

		$qb->orderBy('a.name', 'asc');

		$query = $qb->getQuery();

		$paginator = new Paginator($query, $fetchJoin = true);
		return $paginator;
	}


	public function listContactPerson($offset = NULL, $perpage = NULL, $filters = array())
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('y')
			->from('agent\models\AgentContactPerson', 'y')
			->leftJoin('y.agent', 'a')
			->Where("y.deleted=0");

		if( array_key_exists('agent', $filters) and $filters['agent'] !==''  ){
			$qb->andWhere('a.id = :agentId')->setParameter('agentId', $filters['agent']);
		}

		if (!is_null($offset))
			$qb->setFirstResult($offset);

		if (!is_null($perpage))
			$qb->setMaxResults($perpage);

		$query = $qb->getQuery();

		$paginator = new Paginator($query, $fetchJoin = true);
		return $paginator;
	}

	public function getPrincipalAgents($pagent = NULL)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('a')->from('agent\models\Agent', 'a')
			->where('a.deleted = FALSE');

		if(!is_null($pagent))
		{
			$qb->andWhere('a.parentAgent = '.$pagent->id());
		}
		else
		{
			$qb->andWhere('a.parentAgent IS NULL');
		}

		return $qb->getQuery()->getResult();

	}

	public function listSubAgents($offset = NULL,$perpage = NULL,$filters = array()){

		$qb = $this->_em->createQueryBuilder();
		$qb->select(array('a.id as agent_id','a.slug','a.name', 'a.branch_code as agent_code', 'c.name as country','a.phone','a.email','a.address','a.created','a.owner',
			'p.name as place', 'ct.name as city','st.name as state','a.fax','pa.name as pa_name','pa.id as pa_id','pa.slug as pa_slug','a.status', 'pa.address as pa_address',
			'pa.status as active_parent'))
			->from('agent\models\Agent','a')
			->join('a.place','p')
			->join('p.city','ct')
			->join('ct.state','st')
			->join('a.parentAgent','pa')
			->join('st.country','c')
			->where('a.parentAgent IS NOT NULL')
			->andWhere("a.deleted = FALSE");

		if(count($filters) > 0){

			if(isset($filters['branch_code']) and $filters['branch_code'] != "")
			{
				$qb->andWhere('a.branch_code = :branchCode')->setParameter('branchCode', $filters['branch_code']);
			}

			if(isset($filters['agent_short_code']) and $filters['agent_short_code'] != "")
			{
				$qb->andWhere('a.shortCode = :shortCode')->setParameter('shortCode', $filters['agent_short_code']);
			}

			if(isset($filters['name']) and $filters['name'] != "")
			{
				$qb->andWhere('a.name LIKE :name')->setParameter('name', '%'.$filters['name'].'%');
			}

			if(isset($filters['phone']) and $filters['phone'] != "")
			{
				$qb->andWhere('a.phone LIKE :phone')->setParameter('phone', $filters['phone'].'%');
			}

			if(isset($filters['status']) and $filters['status'] != "")
			{
				$qb->andWhere('a.status = :status')->setParameter('status', $filters['status']);
			}

			if(isset($filters['country']) and $filters['country'] != "")
			{
				$qb->andWhere('c.id = :cid')->setParameter('cid', $filters['country']);
			}

			if(isset($filters['state']) and $filters['state'] != "")
			{
				$qb->andWhere('st.id = :stid')->setParameter('stid', $filters['state']);
			}

			if(isset($filters['city']) and $filters['city'] != "")
			{
				$qb->andWhere('ct.id = :ctid')->setParameter('ctid', $filters['city']);
			}

			if(isset($filters['pagent']) and $filters['pagent'] != "")
			{
				$qb->andWhere('a.parentAgent = :pid')->setParameter('pid', $filters['pagent']);
			}

			if(isset($filters['group']) and $filters['group'] != "")
			{
				$qb->andWhere('a.groups = :gid')->setParameter('gid', $filters['group']);
			}
		}

		if(!is_null($offset))
			$qb->setFirstResult($offset);

		if(!is_null($perpage))
			$qb->setMaxResults($perpage);
		$qb->orderBy('a.name', 'ASC');
// 				$qb->orderBy('c.name', 'ASC')
// 					->addOrderBy('a.name', 'ASC')
// 					->addOrderBy('a.branch_code','ASC')
// 					->addOrderBy('a.address', 'ASC')
// 					->addOrderBy('st.name', 'ASC')
// 					->addOrderBy('ct.name', 'ASC');

		//show_pre($qb->getQuery()->getSQL());

		$paginator = new Paginator($qb->getQuery(), $fetchJoin = true);
		return $paginator;
	}


	public function getAgentsByCountry($country_id, $parentAgent_id, $filters = array()){

		//show_pre($filters); die;

		$qb = $this->_em->createQueryBuilder();

		$parentAgent = ($parentAgent_id == 0) ? 'a.parentAgent IS NULL' : 'a.parentAgent='.$parentAgent_id;

		$qb->select('a')->from('models\Agent','a')
			->join('a.city','c')
			->join('c.state','s')
			->join('s.country','con')
			->andWhere($parentAgent)
			->andWhere('a.deleted = :d_status')
			->setParameter('d_status', FALSE)
			->andWhere('con.id= :country_id')
			->setParameter('country_id',$country_id)
			->orderBy('a.name', 'asc');

		//if(array_key_exists('status', $filters) && $filters['status'] != '')
		//$qb->andWhere('a.status = '.$filters['status']);

		if(isset($filters['status']) and $filters['status'] != "")
		{
			$qb->andWhere('a.status = :st')->setParameter('st', $filters['status']);
		}

		//show_pre($qb->getQuery()->getResult()); die;
		return $qb->getQuery()->getResult();
	}

	public function principalAgentsArray() {

		$qb = $this->_em->createQueryBuilder();

		$qb->select(array('a.id as agent_id','a.slug','a.name','c.name as country'))
			->from('agent\models\Agent','a')
			->join('a.place','p')
			->join('p.city','ct')
			->join('ct.state','st')
			->join('st.country','c')
			->where('a.parentAgent IS NULL')
			->andWhere("a.deleted = FALSE")
			->andWhere("a.status != FALSE")
			->orderBy('a.name', 'asc');

		return $qb->getQuery()->getResult();
	}

	public function getAgentsByState($state_id){

		$qb = $this->_em->createQueryBuilder();

		$qb->select('agent')->from('models\Agent', 'agent')
			->join('agent.city', 'city')
			->join('city.state', 'state')
			->where('state.id = '.$state_id)
			->andWhere("agent.deleted = FALSE")
			->andWhere("agent.status != FALSE")
			->andWhere('agent.parentAgent IS NOT NULL')
			->orderBy('agent.name','asc');

// 		return $qb->getQuery()->getSQL();

		return $qb->getQuery()->getResult();

	}

	public function getPrincipalAgent($id)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('a.id as agent_id, a.name as agent_name, a.address,
				p.name as place, ct.id as city_id, ct.name as city_name, st.id as state_id, st.name as state_name, con.id as country_id, con.name as country_name, con.dialing_code as dialing_code,
				a.operation_hours, IDENTITY(a.parentAgent) as parent_agent_id')
			->from('agent\models\Agent','a')
			->join('a.place', 'p')
			->join('p.city', 'ct')
			->join('ct.state', 'st')
			->join('st.country', 'con')
			->where('a.id = :id')
			->andWhere('a.deleted = FALSE')
			->setParameter('id', $id);
		$agent = $qb->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);


		if($agent)
		{
			$qst = $this->_em->createQueryBuilder();

			$qst->select('s')->from('location\models\State', 's')
				->join('s.country', 'con')
				->where('con.id = '.$agent['country_id']);

			$agent['states'] = $qst->getQuery()->getArrayResult();

			return $agent;
		}

		return FALSE;

	}

	public function listAgentByUser($userId){

		$qb = $this->_em->createQueryBuilder();

		$qb->select('a')
			->from('agent\models\Agent', 'a')
			->where('a.deleted = 0 ')
			->andWhere('IDENTITY(a.createdBy) = :userId')->setParameter('userId', $userId)
		;

		return $qb->getQuery()->getResult();
	}

    public function getDuplicateAgentContactPersons($agentId, $emails){
        $conditions = [];

        $query = 'SELECT a.name from agent\models\AgentContactPerson a LEFT JOIN a.agent aa WHERE a.deleted = 0 AND aa.id = '.$agentId;

        foreach( $emails as $email ){
            if($email != ''){
                $conditions[] = "a.email1 = '".$email."' ";
                $conditions[] = "a.email2 = '".$email."' ";
            }
        }

        $query .= ' AND (' .implode(' OR ', $conditions). ')';
        $qb = $this->_em->createQuery($query);
        $result = $qb->getResult();

        if( count($result) ){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function checkDuplicateAgent($filters){

        $conditions = [];

        $query = 'SELECT a.name, u.fullname, u.phone, u.email from agent\models\Agent a LEFT JOIN a.createdBy u WHERE a.deleted = 0 ';

        foreach( $filters as $email ){
            if($email != ''){
                $conditions[] = "a.email1 = '".$email."' ";
                $conditions[] = "a.email2 = '".$email."' ";
            }
        }

//        if(isset($filters['emails']) and count($filters['emails']) > 0){
//            $emails = $filters['emails'];
//            foreach($emails as $email){
//
//            }
//            $hasCond = TRUE;
//        }
//
//        if(isset($filters['websites'])and count($filters['websites']) > 0){
//            $websites = $filters['websites'];
//            foreach($websites as $website){
//
//                $website = str_replace('https://', '', $website);
//                $website = str_replace('http://', '', $website);
//                $website = str_replace('www.', '', $website);
//
//                $replaceStrWeb1 = "REPLACE( REPLACE( REPLACE(a.website1,'https://',''), 'http://', '' ), 'www.','' )";
//                $replaceStrWeb2 = "REPLACE( REPLACE( REPLACE(a.website2,'https://',''), 'http://', '' ), 'www.','' )";
//
//
//                if( $website != '' ){
//                    $conditions[] = $replaceStrWeb1." = '".$website."'";
//                    $conditions[] = $replaceStrWeb2." = '".$website."'";
//                }
//            }
//            $hasCond = TRUE;
//        }

        $query .= ' AND (' .implode(' OR ', $conditions). ')';

        $qb = $this->_em->createQuery($query);

//        echo $query; die;

        $result = $qb->getResult();

        if( count($result) ){
            $message = $result[0]['fullname']. '( '.$result[0]['email'].' / '.$result[0]['phone'].' )' ;
            return $message;
        }else{
            return FALSE;
        }

    }
}