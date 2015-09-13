<?php

namespace Reservation\DoctrineExtensions\Filters;

use user\models\User,
	agent\models\Agent,
	user\models\Group;

use Doctrine\ORM\Mapping\ClassMetaData,
    Doctrine\ORM\Query\Filter\SQLFilter;

class PAgentFilter extends SQLFilter
{
	protected $entityManager;
	
	public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias){
		
		if($targetEntity->name !== 'models\Agent')
			return '';
		
		$parent = $targetEntity->getAssociationMapping('parentAgent');
		$sourceToTargetKeyColumns = $parent['sourceToTargetKeyColumns'];
		
		$field = array_search('id', $sourceToTargetKeyColumns);
		
		if($user = \Current_User::user()){
			
			if($user->getGroup()->id() == Group::SUPER_ADMIN or $user->getLevel() == User::USERLEVEL_MTO)
				return '';
			
			$agent = \Current_User::getAgents();
			if (!$agent) return '';
			
			if ($user->getLevel() == User::USERLEVEL_PA)
				return $targetTableAlias . '.id = ' . $agent->agent_id . ' OR ' . $targetTableAlias.'.'.$field.' = '.$agent->agent_id;
			
			if ($user->getLevel() == User::USERLEVEL_SA)
				return $targetTableAlias . '.id = ' . $agent->agent_id . ' OR ' . $targetTableAlias.'.id = '.$agent->parentAgent_id;
			
			return 	$targetTableAlias. '.' .$field.' = '.$agent->parentAgent_id;
			
		} else return '';
		
	}
	
	protected function getEntityManager()
	{
		if ($this->entityManager === null) {
			$refl = new \ReflectionProperty('Doctrine\ORM\Query\Filter\SQLFilter', 'em');
			$refl->setAccessible(true);
			$this->entityManager = $refl->getValue($this);
		}
	
		return $this->entityManager;
	}
}