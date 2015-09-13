<?php
namespace agent\models;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;

class AgentDocumentRepository extends EntityRepository{
	
	public function getAgentDocuments($offset = NULL, $perpage = NULL, $filters = array())
	{
        return 'agent documents';
		
	}	
	
}