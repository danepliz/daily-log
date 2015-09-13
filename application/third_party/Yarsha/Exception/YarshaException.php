<?php

namespace Yarsha\Exception;


class YarshaException extends \Exception{

    const YARSHA_EXCEPTION = 70000;
    const ACTIVITY_NOT_FOUND_EXCEPTION = 70001;
    const ACTIVITY_X0_ALREADY_GENERATED = 70002;
    const ACTIVITY_IS_NOT_VALID = 70003;

    public $exceptionMessage = array(
        self::YARSHA_EXCEPTION => 'Yarsha Exception Found.',
        self::ACTIVITY_NOT_FOUND_EXCEPTION => 'Activity Not Found.',
        self::ACTIVITY_X0_ALREADY_GENERATED => 'XO Already Generated for this Activity.',
        self::ACTIVITY_IS_NOT_VALID => 'Activity is not valid.'
    );

    public function __construct($exceptionCode){

        if( ! isset($this->exceptionMessage[$exceptionCode]) ){
            $exceptionCode = 70000;
        }

        $message = $this->exceptionMessage[$exceptionCode];

        parent::__construct($message, $exceptionCode, NULL);

    }



}