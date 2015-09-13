<?php
use agent\models\Agent;

use    currency\models\Currency;

use idmgmt\models\IdentificationDocument;

use    location\models\Country;
use    location\models\Place;
use    location\models\TimeZone;

use    user\models\User;
use    user\models\Group;
use    user\models\Permission;


class Tools_Controller extends MY_Controller
{
    var $schemas;

    public function __construct()
    {
        parent::__construct();
        $this->schemas = array(
        $this->doctrine->em->getClassMetadata('agent\models\Agent'),
        $this->doctrine->em->getClassMetadata('agent\models\AgentGroup')

        , $this->doctrine->em->getClassMetadata('user\models\User')
        , $this->doctrine->em->getClassMetadata('user\models\Group')
        , $this->doctrine->em->getClassMetadata('user\models\Permission')

        , $this->doctrine->em->getClassMetadata('idmgmt\models\IdentificationDocument')

        , $this->doctrine->em->getClassMetadata('location\models\Country')
        , $this->doctrine->em->getClassMetadata('location\models\Place')
        , $this->doctrine->em->getClassMetadata('location\models\TimeZone')

        , $this->doctrine->em->getClassMetadata('currency\models\Currency')
        );

    }

    public function index()
    {

    }

    public function load()
    {
        $this->drop();
        //$tool->updateSchema($classes);
        $this->doctrine->tool->createSchema($this->schemas);


        echo "Schemas created.";

        //create a group
        $group = new Group();
        $group->setName('Super Admin');
        $group->setActive(TRUE);
        $group->setDescription('The super admin. Recommended only one user in this group');

        //add a currency
        $currency = new Currency();
        $currency->setIsoCode('NPR');
        $currency->setName('Nepalese Rupee');

        //add a country
        $country = new Country();
        $country->setName('Nepal');
        $country->setIso_2('NP');
        $country->setIso_3('NPL');
        $country->setCurrency($currency);

        //add a new timezone
        $timezone = new TimeZone();
        $timezone->setName('Asia/Kathmandu');
        $timezone->setCode('Asia/Kathmandu');
        $timezone->setDstOffset(5);
        $timezone->setGmtOffset(5);

        //create an agent
        $agent = new Agent();
        $agent->setStatus(TRUE);
        $agent->setAddress('Kupandul');
        $agent->setDescription('Ktm Express super agent');
        $agent->setEmail('bhattabhakta@yarshastudio.com');
        $agent->setMobile('0123456987');
        $agent->setName('Ktm Express');
        $agent->setPhone('123546545654');

        //create a user
        $user = new User();
        $user->setFirstname('Bhaktaraz');
        $user->setLastname('Bhatta');
        $user->setAddress('Koteshwor');
        $user->setEmail('bhattabhakta@yarshastudio.com');
        $user->setMobile('9860440466');
        $user->setPassword(md5(123456));
        $user->setPhone('12587458');
        $user->setUsername('superadmin');
        $user->setGroup($group);
        $user->setAgent($agent);


        $this->doctrine->em->persist($group);
        $this->doctrine->em->persist($country);
        $this->doctrine->em->persist($timezone);
        $this->doctrine->em->persist($agent);
        $this->doctrine->em->persist($user);

        $this->doctrine->em->flush();

    }

    public function update()
    {

        $sql = $this->doctrine->tool->getUpdateSchemaSql($this->schemas, TRUE);
        $count = count($sql);

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                if (substr($sql[$i], 0, 10) == 'DROP TABLE')
                    unset($sql[$i]);

// 			    $sessionTbl = trim(strstr($sql[$i],'f1_sessions',TRUE));
// 			    $optionsTbl = trim(strstr($sql[$i],'f1_options',TRUE));
// 			    if(($sessionTbl || $optionsTbl) == 'DROP TABLE')
// 			    	unset($sql[$i]);
            }

        }

        foreach ($sql as $statment) {
            $this->doctrine->em->getConnection()->exec($statment);
        }

        $this->writePatch($sql);

        echo "Schemas updated.<br/><br/><br/>";

        return;
    }

    private function writePatch(array $sql)
    {
        if (count($sql) == 0)
            return TRUE;

        $filepath = './dbpatch/db-' . time() . '.patch';

        if (!$fp = @fopen($filepath, FOPEN_WRITE_CREATE)) {
            return FALSE;
        }

        $message = '';
        foreach ($sql as $s) {
            $message .= $s . ";" . PHP_EOL;
        }


        flock($fp, LOCK_EX);
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);

        @chmod($filepath, FILE_WRITE_MODE);
        return TRUE;

    }

    public function drop()
    {
        $this->doctrine->tool->dropSchema($this->schemas);
        echo "Schemas dropped.<br/><br/><br/>";

        return;
    }
}