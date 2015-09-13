<?php

namespace Reservation\REST\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * 
 */
class Arg extends Annotation{
	
	/** @var string */
	public $name;
	
	/** @var string */
	public $rules;
	
}