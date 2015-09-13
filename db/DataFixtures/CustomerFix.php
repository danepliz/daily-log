<?php
namespace DataFixtures;

use models\CustomerIDocuments;

use models\Customer;

use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\FixtureInterface;

class CustomerFix implements FixtureInterface
{
	
	private $names = array(	'Subash Sharma', 'Rajesh Sharma', 'Bhakta Bhatta', 'Sita Aryal', 'David Harper');
	private $streets = array(	'320 S. Flamingo Rd.', 'Kalikasthan 5','Putalisadak 6','Lagankhel 12','Ghattekulo');
	private $mobiles = array( '9801079789','9801079888','9803215478','9841256987','9813698547');
	
	private $phones = array( '+977015895898','+977014587987','+19874585424','+916546546546','+916546546546');
	
	private $country = array( 'NPL','USA','CAN','IND','PHI');
	private $idno = array( '15051212', '9846513612', 'asdasd54541', '65436as51das23d','56a5s1das1d');
	
	public function load(ObjectManager $manager)
	{
		for($i = 0; $i < 10; $i++){
			$rand = rand(0, 4);
			
			$c = new Customer();
			$c->setName($this->names[rand(0, 4)]);
			$c->setStreet($this->streets[rand(0, 4)]);
			$country = $manager->find('models\Common\Country',rand(1,240));
			$c_mobile = $country->getDialingCode().$this->mobiles[rand(0, 4)];
			$c->setMobile($c_mobile);
			$c->setPhone($this->phones[rand(0, 4)]);
			
			$cID = new CustomerIDocuments();
			$cID->setCustomer($c);
			$cID->setDocType($manager->find('models\Common\IdentificationDocument',rand(1,4)));
			$cID->setDocNumber($this->idno[rand(0, 4)]);
			$cID->setIssuedCountry($country);
			
			
			$c1 = new Customer();
			$c1->setName($this->names[rand(0, 4)]);
			$c1->setStreet($this->streets[rand(0, 4)]);
			$country1 = $manager->find('models\Common\Country',rand(1,240));
			$c1_mobile = $country1->getDialingCode().$this->mobiles[rand(0, 4)];
			$c1->setMobile($c_mobile);
			$c1->setPhone($this->phones[rand(0, 4)]);
				
			$cID1 = new CustomerIDocuments();
			$cID1->setCustomer($c1);
			$cID1->setDocType($manager->find('models\Common\IdentificationDocument',rand(1,4)));
			$cID1->setDocNumber($this->idno[rand(0, 4)]);
			$cID1->setIssuedCountry($country1);
				
			$c->addBeneficiary($c1);
			$c1->addRemitter($c);
			
			$manager->persist($cID1);
			$manager->persist($c1);
			
			$manager->persist($cID);
			$manager->persist($c);
		}
		
		$manager->flush();
	}
}