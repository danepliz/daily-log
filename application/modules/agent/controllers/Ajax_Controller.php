<?php

use agent\models\Agent;
use user\models\Group;

class Ajax_Controller extends Xhr{
	
	public function __construct(){
		parent::__construct();
	}

    public function getPersonForm($personID = NULL ){

        $response = array();
        $data = array();

        if( $personID and $personID !== "" ){

            $person = $this->doctrine->em->find('agent\models\AgentContactPerson', $personID);

            if( $person ){
                $data['person'] = $person;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Contact Person Not Found';
            }
        }

        $this->load->theme('common/xhrtemplates/hotel_contact_person_form', $data);
    }

    public function deleteContactPerson(){
        $contact_person_Id = $this->input->post('id');

        $contact_person = $this->doctrine->em->find('agent\models\AgentContactPerson', $contact_person_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if( $contact_person ){
            $contact_person->markAsDeleted();
            $this->doctrine->em->persist($contact_person);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'Agent Contact Person' . $contact_person->getName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The Agent Contact Person "'.$contact_person->getName().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Agent Contact Person. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'Contact Person Not Found.';
        }

        echo json_encode($response);
    }


    public function savePerson($agentId, $personId = NULL){
        $post = $_POST;

        $person = NULL;
        $response = array();
        $isEditing = FALSE;

        $phones = array($post['phone1']);
        $emails = array($post['email1']);
        if( isset($post['phone2']) and trim($post['phone2']) !== "" ) $phones[] = $post['phone2'];
        if( isset($post['email2']) and trim($post['email2']) !== "" ) $emails[] = $post['email2'];
        $hasDuplicate = FALSE;
        $agent = $this->doctrine->em->find('agent\models\Agent', $agentId);
        $emList = [];
        $agentContactPersons = $agent->getContactPersons();
        if( count($agentContactPersons) ){
            foreach($agentContactPersons as $ap){
                $em = $ap->getEmails();
                if( count($em) ){
                    foreach($em as $e){
                        $emList[] = $e;
                    }
                }
            }
        }

//        if( count($emails) ){
//            if($post['email1_old'] != $post['email1'] and in_array( $post['email1'], $emList)){
//                $hasDuplicate = TRUE;
//            }
//
//            if($post['email2_old'] != $post['email2']  and in_array( $post['email2'], $emList)  ){
//                $hasDuplicate = TRUE;
//            }
//        }

        if( ! $hasDuplicate ){
            if( !is_null($personId) and $personId !== "" and $personId !== '0' ){
                $person = $this->doctrine->em->find('agent\models\AgentContactPerson', $personId);
                $isEditing = TRUE;
            }else{
                $person = new \agent\models\AgentContactPerson();
            }

            $person->setName(trim($post['name']));
            $person->setAddress(trim($post['address']));
            $person->setSkype(trim($post['skype']));
            $person->setDesignation(trim($post['designation']));

            $person->setAgent($agent);

            $person->setPhones($phones);
            $person->setEmails($emails);

            $this->doctrine->em->persist($person);

            try{
                $this->doctrine->em->flush();

                $emails = $person->getEmails();
                $emailsArr = [];
                foreach($emails as $e){
                    if( $e != '' ){
                        $emailsArr[] = '<a href="mailto:'.$e.'">'.$e.'</a>';
                    }
                }

                $trOpen = '<tr id="cp-data-'.$person->id().'">';
                $tdData = '<td>'.$person->getName().'</td>';
                $tdData .= '<td>'.$person->getDesignation().'</td>';
                $tdData .= '<td>'.$person->getAddress().'</td>';
                $tdData .= '<td>'.$person->getSkype().'</td>';
                $tdData .= '<td>'.implode("/ ", $person->getPhones()).'</td>';
                $tdData .= '<td>'.implode("<br /> ", $emailsArr).'</td>';
                $tdData .= '<td>';
                $tdData .= (user_access('administer agent contact person'))? action_button('edit', '#', array('class'=>"edit-contact-person",'data-person-id' => $person->id(), 'data-toggle' => 'modal', 'data-target' => '#contactPersonsForm', 'data-form-type'=>'E')) : '';
                $tdData .= (user_access('delete hotel contact persons'))? action_button('delete', '#', array('data-bb' => 'custom_delete', 'title' => 'Delete' .$person->getName(), 'data-id' => $person->id())) : '';
                $tdData .= '</td>';
                $trClose = '</tr>';

                $response['status'] = 'success';
                $response['message'] = ($isEditing) ? 'Contact Updated Successfully.' : 'Contact Added Successfully.' ;
                $response['data'] = array(
                    'row_id' => 'cp-data-'.$person->id(),
                    'table_data' => ($isEditing)? $tdData : $trOpen.$tdData.$trClose
                );

            }catch(\Exception $e){
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
                $response['data'] = '';
            }
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Contact Person with provided email already exists.';
            $response['data'] = '';
        }
        echo json_encode($response);
    }

    public function getContactPersonByAgent($agentID, $selectedID=''){
        $agent = $this->doctrine->em->find('agent\models\Agent', $agentID);

        $res = array();

        if($agent ){
            $agentContactPersons = $agent->getContactPersons();
			    if( count($agentContactPersons) > 0 ){
                foreach($agentContactPersons as $acp) {
						if (!$acp->isDeleted() or $selectedID == $acp->id()) {

						$res[] = array('id' => $acp->id(), 'name' => $acp->getName(), 'deleted' => $acp->isDeleted());
					}
				}
            }
        }

        echo json_encode($res);
    }



	
	
	public function getAgentsByCountry($country_id, $parentAgent_id){
		
		$pRepo = $this->doctrine->em->getRepository('models\Agent');
		$agents = $pRepo->getAgentsByCountry($country_id, $parentAgent_id);
		
		$res = array();
		
		foreach($agents as $a)
		{ 
			$res[] = array( 'id'	=> $a->id(), 'name' 	=> $a->getName() ); 
		}
		echo json_encode($res);
	}
	
	public function getAgentsByState($state_id){
		$arepo = $this->doctrine->em->getRepository('models\Agent');
		$agents  = $arepo->getAgentsByState($state_id);
		
		$res = array();
		
		foreach($agents as $a){
			
			if($a->getParentAgent()->isActive())
			{
				$res[] = array(
						'id' 	=> $a->id(),
						'name'	=> $a->getAddress().' - '.$a->getName()
				);
			}
		}
		
		echo json_encode($res);
		
	}
	

	
	
	public function block($agent_id)
	{
		if($this->input->post('note'))
		{
			$agent = $this->doctrine->em->find('models\Agent', $agent_id);
			$agent->setReason($this->input->post('note'));
			$agent->deactivate();
			$this->doctrine->em->persist($agent);
			try{
				$this->doctrine->em->flush();
				$this->message->set($agent->getName().' Blocked Successfully', 'success', TRUE, 'feedback');
				echo 'success';
			}catch(\Exception $e){
				$this->message->set('Unable to block '.$agent->getName(), 'error', TRUE, 'feedback');
				echo 'failure';
			}	
		}
	}
	
	public function delete($agent_id)
	{
		if($this->input->post('note'))
		{
			$note = $this->input->post('note');
			$agent = $this->doctrine->em->find('models\Agent', $agent_id);
			
			$agent->setReason($note);
			$agent->markAsDeleted();
			
			$subagents = $agent->getSubagents();
			if(!is_null($subagents))
			{
				foreach($subagents as $sa)
				{
					$sa->markAsDeleted();
					$sa->setReason($note);
					$this->doctrine->em->persist($sa);
				}
			}
			
			$this->doctrine->em->persist($agent);
			try{
				$this->doctrine->em->flush();
				$this->message->set($agent->getName().' Deleted Successfully', 'success', TRUE, 'feedback');
				echo 'success';
			}catch(\Exception $e){
				$this->message->set('Unable to delete '.$agent->getName(), 'error', TRUE, 'feedback');
				echo 'failure';
			}
		}
	}

	public function getBranchCode($countryID, $stateID)
	{
		$country = $this->doctrine->em->find('location\models\Country', $countryID);
		$countryISO2 = $country->getIso_2();
		
		$state = $this->doctrine->em->find('location\models\State', $stateID);
		$stateISO2 = $state->getShortName();
		
		$agentRepo = $this->doctrine->em->getRepository('agent\models\Agent');
		
		$response = array();
		
		$response['branchCode'] = $agentRepo->getBranchCode($countryISO2, $stateISO2);

		echo json_encode($response);
	}
	
	public function getSubAgentsByListOfCountry()
	{
		
		$countryIDString = $this->input->post('cid');
		
		$agentRepo = $this->doctrine->em->getRepository('models\Agent');
		
		$agents = $agentRepo->getAgentsByCountries($countryIDString);
		
		$options = array();
		
		if($agents)
		{
			foreach($agents as $a)
			{
				$options[$a->id()] = $a->getName();
			}	
		}
		
		echo json_encode($options);
	}
	

	public function getAgentDetails($id)
	{
		$agentRepo = $this->doctrine->em->getRepository('agent\models\Agent');
	
		$principalAgentDetails = $agentRepo->getPrincipalAgent($id);

		echo json_encode($principalAgentDetails);
	}
	
	
	public function getBankDetails($id)
	{
		$bankRepo = $this->doctrine->em->getRepository('models\Common\BankBranch');
		
		$bankDetails = $bankRepo->getBankBranchDetails($id);
		
		$response = array();
		
		if($bankDetails and count($bankDetails) > 0)
		{
			$bank = $bankDetails[0];
			$response['branch_code'] = $bank->getBranchCode();
			$response['address'] = $bank->getAddress();
			$response['payout_limit'] = $bank->getPayoutLimit();
		}
		
		echo json_encode($response); 
	}
	
	
	public function getSubAgentsByCountry($countryID)
	{
		$arepo = $this->doctrine->em->getRepository('models\Agent');
		
		$agents = $arepo->getSubAgentsByCountry($countryID);
		
		$options = array();
		
		if($agents)
		{
			foreach($agents as $a)
			{
				$options[$a->id()] = $a->getName();
			}
		}
		
		echo json_encode($options);
	}
	
	public function addComment($agentID)
	{
		$agent = $this->doctrine->em->find('models\Agent', $agentID);
		$response = array('satus' => 'failure');
		
		if($agent)
		{
			$commentMsg = $this->input->post('comment');
			$comment = new AgentComment();
			$comment->setAgent($agent);
			$comment->setComment($commentMsg);
			$comment->setStatus($agent->getStatus());
			$comment->setUser(\Current_User::user());
			$this->doctrine->em->persist($comment);
			$this->doctrine->em->flush();
			
			if($comment->id())
			{
				$response['status'] = 'success';
				$response['data'] = array(
									'comment' => $commentMsg,
									'created' => $comment->created()->format('F j, Y'),
									'username' => $comment->getUsername(),
									'agentStatus' => $agent->getStatusString()
								);
			}
		}
		echo json_encode($response);
	}

	
	public function authOverride()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		
		$response = array();
		
		$loginStatus = FALSE;
		
		if($username == "" or $password == "")
		{
			$loginStatus = FALSE;
		}
		else{
			$userRepo = $this->doctrine->em->getRepository('user\models\User');
			$user = $userRepo->findOneBy(array('username' => $username));
			
			if($user and $user->isActive() and $user->getPassword() == md5($password))
			{
				$agent = $user->getAgent();
				
				$userPermissionsList = array();
				
				$userPermissions = $user->getGroup()->getPermissions();
				
				if($userPermissions)
				{
					foreach($userPermissions as $up)
					{
						$userPermissionsList[$up->getName()] = TRUE;
					}
				}
				
				
				if(isset($userPermissionsList['approve fx override']) or $user->getGroup()->id() == Group::SUPER_ADMIN)
				{
					if($agent and $agent->getStatus() == Agent::STATUS_AGENT_ACTIVE)
					{
						$pagent = $agent->getParentAgent();
							
						if($pagent)
						{
							$loginStatus = ($pagent->getStatus() == Agent::STATUS_AGENT_ACTIVE)? TRUE : FALSE;
						}else  $loginStatus = TRUE;
					}
				}
			}
		}
		
		if($loginStatus)
		{
			$response['status'] = 'success';
			$response['message'] = 'Authentication Success';
			$response['approvedBy'] = $username; 
		}
		else
		{
			$response['status'] = 'failure';
			$response['message'] = 'Authentication Failed';
		}
		
		echo json_encode($response);
	}
	
	public function getGroups($refID, $type)
	{
		$groupRepo = $this->doctrine->em->getRepository('models\Agent');
		$groups = $groupRepo->getGroups($refID, $type);
		
		$options = array();
		
		if($groups and count($groups) > 0)
		{
			foreach($groups as $group)
			{
				$options[$group->id()] = $group->getName();
			}
		}
		
		echo json_encode($options);
		
	}
	
	public function searchSubAgent(){
		
		$filters = array();
		$filters['name'] = $this->input->post('name');
		
		if( $this->input->post('country') and is_numeric($this->input->post('country')) )
		{
			$filters['country'] = $this->input->post('country');
		}
		
		$arepo = $this->doctrine->em->getRepository('models\Agent');
		
		$subagents = $arepo->listSubAgents(NULL,NULL,$filters);
		$ret = array();
		
		foreach($subagents as $s){
			$ret[] = array('id' 	=>	$s['agent_id'],
					'label'	=>	$s['name'],
					'value'	=>	$s['name']);
		}
		
		$response = array();
		$response['data'] = array();
		$response['numcolumns'] = 3;
		$response['columns'] = array('name','address','country');
		
		if($subagents and count($subagents)>0)
		{
			foreach($subagents as $a)
			{
				$response['data'][] = array(
						'id'		=> 	$a['agent_id'],
						'name' 		=> 	$a['name'],
						'address' 	=> 	$a['address'],
						'country'	=>	$a['country']
				);
			}
				
		}
		
		echo json_encode($response);
	}
	
	public function getAgentsTransactionSummary($agentId)
	{
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
	
		$tRepo = $this->doctrine->em->getRepository('models\Agent');
		$transactions = $tRepo->getAgentsTransactionSummary($agentId,$fromdate,$todate);
	
		if(count($transactions) > 0)
		{
			$response['status'] = 'success';
				
			foreach ($transactions as $t){
				$response['total_transaction'] = $t['total_transaction'] ? $t['total_transaction'] : 0;
				$response['total_remitted'] = $t['total_remitted'] ? $t['total_remitted'] : 0.00;
				$response['fees'] = $t['fees'] ? $t['fees'] : 0.00;
				$response['total_surcharge'] = $t['total_surcharge'] ? $t['total_surcharge'] : 0.00;
			}
				
			echo json_encode($response);
		}
	
	}
	
	public function getAgentsCommissionSummary($agentId)
	{
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
	
		$tRepo = $this->doctrine->em->getRepository('models\Agent');
		$transactions = $tRepo->getAgentsCommissionSummary($agentId,$fromdate,$todate);
	
		if(count($transactions) > 0)
		{
			$response['status'] = 'success';
	
			foreach ($transactions as $t){
				$response['total_commission'] = $t['total_remitting_commission'] ? $t['total_remitting_commission'] : 0;
			}
	
			echo json_encode($response);
		}
	}
	
	public function authenticateCancelTransaction()
	{
		$username = $this->input->post('username');
	
		
		$sRepo = $this->doctrine->em->getRepository('models\Common\State');
		$states = $sRepo->getStates($country_id);
		$options = "";
		$options = '<option value=""> --- Select State --- </option>'; 
		foreach ($states as $s){
			$sel = ($selected !== NULL && $selected == $s->id()) ? "selected='selected'":"";
			$options .= '<option value="'.$s->id().'"'.$sel.'>'.$s->getName().'</option>';
		}
		echo $options;
		$password = $this->input->post('password');
		
		$response = array();
		
		$loginStatus = FALSE;
		
		if($username == "" or $password == "")
		{
			$loginStatus = FALSE;
		}
		else{
			$userRepo = $this->doctrine->em->getRepository('user\models\User');
			$user = $userRepo->findOneBy(array('username' => $username));
			
			if($user and $user->isActive() and $user->getPassword() == md5($password))
			{
				$agent = $user->getAgent();
				
				$userPermissionsList = array();
				
				$userPermissions = $user->getGroup()->getPermissions();
				
				if($userPermissions)
				{
					foreach($userPermissions as $up)
					{
						$userPermissionsList[$up->getName()] = TRUE;
					}
				}
				
				
				if(isset($userPermissionsList['cancel transaction after 30 mins']) or $user->getGroup()->id() == Group::SUPER_ADMIN)
				{
					if($agent and $agent->getStatus() == Agent::STATUS_AGENT_ACTIVE)
					{
						$pagent = $agent->getParentAgent();
							
						if($pagent)
						{
							$loginStatus = ($pagent->getStatus() == Agent::STATUS_AGENT_ACTIVE)? TRUE : FALSE;
						}else  $loginStatus = TRUE;
					}
				}
			}
		}
		
		if($loginStatus)
		{
			$response['status'] = 'success';
			$response['message'] = 'Authentication Success';
			$response['approvedBy'] = $username; 
		}
		else
		{
			$response['status'] = 'failure';
			$response['message'] = 'Authentication Failed';
		}
		
		echo json_encode($response);
	
		
	}
	
	public function getSubAgentsByCon($country_id){
		$optstate ="";
		$optagent ="";
		$arepo = $this->doctrine->em->getRepository('models\Agent');
		$agents = $arepo->getSubAgentsByCon($country_id);
		$optagent ='<option value="">-- SELECT AGENT --</option>';
		foreach($agents as $agent){
			$optagent.="<option value='".$agent['id']."'>".$agent['name']."</option>" ;
		}
		


		$sRepo = $this->doctrine->em->getRepository('models\Common\State');
		$states = $sRepo->getStates($country_id);
		
		$optstate = '<option value=""> --- SELECT STATE --- </option>';
		foreach ($states as $s){
			$optstate .= '<option value="'.$s->id().'">'.$s->getName().'</option>';
		}
		$options = array($optagent,$optstate);
		echo json_encode($options);
		
	}
	
	public function getSubAgentsByCountryForMultiselect($country_id)
	{
		$arepo = $this->doctrine->em->getRepository('models\Agent');
		$agents = $arepo->getSubAgentsByCon($country_id);
	
		$res = array();
	
		foreach($agents as $a)
		{
			$res[] = array( 'id'	=> $a['id'], 'name' 	=> $a['name'] );
		}
			
		echo json_encode($res);	
	}
	
	public function getSubAgentsBySt($state_id){

		$arepo = $this->doctrine->em->getRepository('models\Agent');
		$agents = $arepo->getSubAgentsBySt($state_id);
		$opt ='<option value="">-- SELECT AGENT --</option>';
		foreach($agents as $agent){
			$opt.="<option value='".$agent['id']."'>".$agent['name']."</option>" ;
		}
		echo $opt;	
	}
	
	public function getAgentPayoutLimit($agentId, $payableAmount)
	{
		$response = array();
		
		$status = 'failure';
		$message = 'insufficient payout amount';
		
		$payoutAgent = $this->doctrine->em->find('models\Agent', $agentId);
		$payoutLimit = $payoutAgent->getPayoutLimit();
		
		if($payableAmount < $payoutLimit || $payoutLimit == 0)
		{
			$status = 'success';
			$message = 'sufficient payout amount';
		}
		
		$response['status'] = $status;
		$response['message'] = $message;
		
		echo json_encode($response);
	}

	public function getProcessorByServiceType($service_type, $country_id)
	{
		$agentRepo = $this->doctrine->em->getRepository('models\Agent');
		$filters['country'] = $country_id;
		$processors = $agentRepo->listPrincipalAgents(NULL, NULL, $filters);
		
		$response = array();
		$processorArray = array();
		$currencyArray = array();
		
		if( $processors and count($processors) > 0 )
		{
			foreach($processors as $p)
			{				
				$services = unserialize($p['services']);
				
				if( is_array($services) and in_array($service_type, $services) )
				{
					$processorArray[$p['agent_id']] = $p['name'];
				}
		
				$praRepo = $this->doctrine->em->getRepository('models\PayoutRateAdjustment');
				$currentRates = $praRepo->findBy(array('processor' => $p['agent_id']));
				
				if($currentRates and count($currentRates) > 0){
					foreach ($currentRates as $cR){
						$currencyArray[$p['name']][] = $cR->getCurrency()->getIsoCode().' - '.$cR->getCurrency()->getName().' @ '.$cR->getPayoutAdjustRate().'<br />';
					}
				}
			}
		}
		
		$response['processors'] = $processorArray;
		$response['currencies'] = $currencyArray;
		
		echo json_encode($response);		
	}
	
	public function getProcessorsExRates() //($country_id, $from_currency, $forex_batch)
	{
		$agent_id = $this->input->post('agent_id');
		$sending_currency = $this->input->post('source_currency');
		$forex_group = ( $this->input->post('group') == "" )? NULL : $this->input->post('group');
		$service_type = $this->input->post('service_type');
		$destination_country_id = $this->input->post('destination_country');
		
		
		$agentRepo = $this->doctrine->em->getRepository('models\Agent');
		$sourceAgent = $agentRepo->find($agent_id);
		$filters['country'] = $destination_country_id;
		$processors = $agentRepo->listPrincipalAgents(NULL, NULL, $filters);
		
		$sourceCurrency = $this->doctrine->em->find('models\Common\Currency', $sending_currency);
		
		$sourceCountry = $sourceAgent->getCountry();
		
		$sourceCurrencyCode = $sourceCurrency->getIsoCode();
		
		$forexRepo = $this->doctrine->em->getRepository('models\Common\ExchangeRate');
		
		$curRepo = $this->doctrine->em->getRepository('models\Common\Currency');
		
		$processorArray = array();
		$response = array();
		
		if( $processors and count($processors) > 0 )
		{
			foreach($processors as $p)
			{	
				$services = unserialize($p['services']);
		
				if( is_array($services) and in_array($service_type, $services) ) { $processorArray[$p['agent_id']] = $p['name']; }
		
				$praRepo = $this->doctrine->em->getRepository('models\PayoutRateAdjustment');
						
				/* Exchange Rates for Selected Sending Currency */
				$rates = $forexRepo->getExchangeRatesBySendingCurrency($sourceCountry->getIso_3(), $sourceCurrencyCode, $forex_group);
					
				$exrates = array();
								
				if( $rates and count($rates) > 0 )
				{
					foreach($rates as $r)
					{
						$appendPayerRate = '';
						$payerRate = $praRepo->findOneBy(array('processor' => $p['agent_id'], 'currency' => $r['id']));
						if($payerRate and !(is_null($payerRate))){
							$appendPayerRate = $payerRate->getPayoutAdjustRate();
						}
						
						if($appendPayerRate != ''){
							$appendPayerRate = ' + '.$appendPayerRate;
						}
						
						$exrates[$r['iso_code']] = $r['exrate'].' '.$appendPayerRate;
						
						
					}
					$response[$p['name']] = $exrates;
					
				}
			}
		}
		echo json_encode($response);
	}
	
	public function getOfferedCurrenciesByProcessor($processor_id)
	{
		$processor = $this->doctrine->em->find('models\Agent', $processor_id );
		
		$response = array();
		
		$payoutCurrencies = array();
		
		if( $processor )
		{
			$offerredCurriencies = $processor->getPayoutCurrencies();
			
			if( !empty($offerredCurriencies) )
			{
				foreach($offerredCurriencies as $ofc)
				{
					$currency = $this->doctrine->em->find('models\Common\Currency', $ofc);
					
					if( $currency )
						$payoutCurrencies[] = array(
									'id' => $ofc,
									'name' => $currency->getIsoCode().' - '.$currency->getName(),
								);
				}
			}
		}
		
		$response['receiving_currencies'] = $payoutCurrencies;

		echo json_encode($response);
	}
	
	public function getSubagentsByCurrencyOffered( $parentAgent_id, $currency_id )
	{
		$parentAgent = $this->doctrine->em->find('models\Agent', $parentAgent_id );
		
		$subagents = $parentAgent->getSubAgents();
		
		$response = array();
		
		if( $subagents and count($subagents) > 0 )
		{
			foreach( $subagents as $sa )
			{
// 				$response[$sa->id()] = $sa->getName();
				$offeredCurrencies = $sa->getPayoutCurrencies();
				
				if( is_array($offeredCurrencies) and in_array($currency_id, $offeredCurrencies) )
				{
					$response[$sa->id()] = $sa->getBranchCode().' - '.$sa->getName().' - '.$sa->getAddress();
				}	
			}
		}
		
		echo json_encode($response);
		
	}
	
	public function checkCountryPayoutLimit($countryId, $payout_limit)
	{
		$response = array();
		
		$response['status'] = 'success';
		
		$country = $this->doctrine->em->find('models\Common\Country', $countryId);
		$agentsCountryLimit = $country->getCountryPayoutLimit();
		
		if(!is_null($country))
		
		if($payout_limit > $agentsCountryLimit)
		{
			$response['status'] = 'error'; 
			$response['message'] = 'Agents payout limit cannot be greater than agents country payout limit. It should be less than '.$agentsCountryLimit.'.';
		}
		
		echo json_encode($response);
		
	}
}