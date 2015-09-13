<?php

use user\models\User;
use location\models\Country;

class Configuration_Controller extends  MY_Controller{

    public function __construct(){
        parent::__construct();
    }

    public function configure(){

        $country = new Country();
        $country->setName('Nepal');
        $country->setDialingCode('977');
        $country->setIso_2('NP');
        $country->setIso_3('NPL');

        $this->doctrine->em->persist($country);

        $super_admin = new User();

        $super_admin->setAddress('Durbar Margh');
        $super_admin->setEmail('admin@yarshastudio.com');
        $super_admin->setFullName('Yeti Express');
        $super_admin->setPassword('123456');
        $super_admin->setMobile('9841979051');
        $super_admin->setPhone('1325646');
        $super_admin->setUserType(User::USER_ROLE_SUPER_ADMIN);
        $super_admin->setApiKey(md5(microtime()));
        $super_admin->setToken();

        $this->doctrine->em->persist($super_admin);

        try{
            $this->doctrine->em->flush();

            die('System Configured successfully.');

        }catch(\Exception $e){
            die('Failed to configure system. '.$e->getMessage());
        }


    }


}