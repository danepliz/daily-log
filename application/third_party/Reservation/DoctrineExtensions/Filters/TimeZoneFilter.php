<?php

namespace Reservation\DoctrineExtensions\Filters;

use Doctrine\ORM\Query\AST\Functions\DateAddFunction;

use user\models\User,
	agent\models\Agent,
	user\models\Group;

use Doctrine\ORM\Mapping\ClassMetaData,
    Doctrine\ORM\Query\Filter\SQLFilter;

class TimeZoneFilter extends SQLFilter
{
	protected $entityManager;
	
	public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias){
		
		if($targetEntity->name !== 'models\Transaction')
			return '';
		
		$user = \Current_User::user();
		
		if( ! $user ) return '';

		$date=trim($this->getParameter('value'),"'");
	
		$value=getUTCTime(new \DateTime($date));
		
		$operator = trim($this->getParameter('operator'), "'");
		
		$field = $targetTableAlias.'.'.$this->getParameter('column');

		return  $field . $operator . "'".$value->format('Y-m-d H:i:s')."'";
				
		
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