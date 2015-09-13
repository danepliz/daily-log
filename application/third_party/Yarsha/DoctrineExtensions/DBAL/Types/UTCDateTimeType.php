<?php

namespace F1soft\DoctrineExtensions\DBAL\Types;

use Doctrine\DBAL\Types\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;

use Doctrine\DBAL\Types\DateTimeType;

use Doctrine\DBAL\Types\ConversionException;

class UTCDateTimeType extends DateTimeType
{
    static private $utc = null;
    
    public function __construct()
    {
    	$this->overrideType(Type::DATETIME, 'Yarsha\DoctrineExtensions\DBAL\Types\UTCDateTimeType');
    }

	public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        
        self::$utc = (self::$utc) ? self::$utc : (new \DateTimeZone('UTC'));
        
        $value->setTimeZone(self::$utc);
        
        return $value->format($platform->getDateTimeFormatString());
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

//         $val = \DateTime::createFromFormat(
//             $platform->getDateTimeFormatString(),
//             $value,
//             (self::$utc) ? self::$utc : (self::$utc = new \DateTimeZone('UTC'))
//         );
//         if (!$val) {
//             throw ConversionException::conversionFailed($value, $this->getName());
//         }
//         return $val;

        $val = \DateTime::createFromFormat(
        		$platform->getDateTimeFormatString(),
        		$value
        );
        if (!$val) {
        	throw ConversionException::conversionFailed($value, $this->getName());
        }
        
        $user = \Current_User::user();        
        
        $gmtoffset = ($user)? $user->getAgent()->getTimezone()->getGmtOffset() : timezone_offset_get(date_default_timezone_get(), new \DateTime());
        
        $sign = ($gmtoffset < 0)? '-' : '+'; 
        
        $GMT_offset = $gmtoffset * 60 ;
        
        $offset_string = $sign.$GMT_offset.' minutes';
        
        $value = $val->modify($offset_string)->format('Y-m-d H:i:s');
        
//         return $value;
        
        return new \DateTime($value);
        
    }
}