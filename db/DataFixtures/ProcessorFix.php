<?php
namespace DataFixtures;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\FixtureInterface;

class ProcessorFix implements FixtureInterface
{

	private $names = array(	'ABC INC', 'PHIL MONEY', 'AL ANSARI', 'WESTERN UNION', 'ANY PROCESSOR');
	private $streets = array(	'320 S. Flamingo Rd.', 'Kalikasthan 5','Putalisadak 6','Lagankhel 12','Ghattekulo');
	private $mobiles = array( '9801079789','9801079888','9803215478','9841256987','9813698547');
	
	private $phones = array( '+977015895898','+977014587987','+19874585424','+916546546546','+916546546546');
	
	private $countries = array(19,40,103,155,167,168,180);
	
	public function load(ObjectManager $manager){
		
		$agent = new Agent();
		
		/* Generate Branch Code */
		$country = $manager->find('models\Common\Country', $this->countries[rand(0, 6)]);
		$countryISO2 = $country->getIso_2();
		
		/** @var $stateRepo models\Common\CountryRepository */
		$stateRepo = $manager->getRepository('models\Common\State');
		
		//$state = $stateRepo->findOne
		
		$state = $this->doctrine->em->find('models\Common\State', $this->input->post('state'));
		$stateISO2 = substr($state->getName(),0,2);
		
		$branchCode = $agentRepo->getBranchCode($countryISO2, $stateISO2);
		
		
		$ledger = new Ledger();
		$ledger->setAccountNumber($branchCode);
		$ledger->setBalance("0.00");
		$ledger->setAccountHead(strip_tags($this->input->post('name')).' - Payable/Receivable Account');
		$this->doctrine->em->persist($ledger);
		
		$agent->setName(strip_tags($this->input->post('name')));
		$agent->setBranchCode($branchCode);
		$agent->setLegalName(strip_tags($this->input->post('legal_name')));
		$agent->setDBAName(strip_tags($this->input->post('dba_name')));
		$agent->setOwner(strip_tags($this->input->post('owner')));
		$agent->setManager(strip_tags($this->input->post('manager')));
		$agent->setAccount($ledger);


		$agent->setTimezone($this->doctrine->em->find('models\Common\TimeZone', $this->input->post('timezone')));
		$agent->setAddress(strip_tags($this->input->post('address')));
		$agent->setPhone(strip_tags($this->input->post('phone')));
		$agent->setFax(strip_tags($this->input->post('fax')));
		$agent->setMobile(strip_tags($this->input->post('mobile')));
		$agent->setEmail(strip_tags($this->input->post('email')));
		$agent->setOperationHours(strip_tags($this->input->post('hours_of_operation')));
		$agent->setStatus($this->input->post('status'));
		
		$this->doctrine->em->persist($agent);
		
		$this->doctrine->em->flush();
		
	}
}