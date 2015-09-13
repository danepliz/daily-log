<?php

use user\models\User;
use agent\models\Agent;

class Agent_Controller extends Admin_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('location/country', 'agent/agent', 'agent/status'));
		$this->breadcrumb->append_crumb('Agent', site_url('agent'));
	}

	public function index( )
	{
		if( !user_access('view agent'))	redirect('dashboard');

		$URIParams = getFiltersFromURL();
		$offset = $URIParams['offset'];
        $agentRepository = $this->doctrine->em->getRepository('agent\models\Agent');
        $agents = $agentRepository->listPrincipalAgents($offset, PER_PAGE_DATA_COUNT, $URIParams['filters']);
		$total = count($agents);

		if($total > PER_PAGE_DATA_COUNT)
		{
			$this->templatedata['pagination']= getPagination($total, 'agent/index?'.$URIParams['param'], 2, TRUE);

		}
		$this->templatedata['counter'] = $offset ? $offset:0;
		$this->templatedata['agents'] = & $agents;
		$this->templatedata['offset'] = $offset;
		$this->templatedata['filters'] = $URIParams['filters'];
		$this->templatedata['post'] = $URIParams['post'];
//		$this->templatedata['permitted_users'] = $pUsers;
		$this->templatedata['page_title'] = 'Agents';
		$this->templatedata['maincontent'] = 'agent/list';
		$this->load->theme('master',$this->templatedata);
	}

	public function addPermittedUsers(){

		if( $this->input->post() ){
			$post = $this->input->post();
			$agentID = $post['agent_id'];
			$permittedUsers = $post['permitted_users'];

			$agent = $this->doctrine->em->find('agent\models\Agent', $agentID);
			$agent->resetPermittedUsers();

			if( count($permittedUsers) ){
				$userRepo = $this->doctrine->em->getRepository('user\models\User');
				foreach($permittedUsers as $key => $val){
					$user = $userRepo->find($val);
					if($user){
						$agent->addPermittedUser($user);
					}
				}
			}

			$this->doctrine->em->persist($agent);

			try{
				$this->doctrine->em->flush();
				$this->message->set('Agent Permitted Users Updated successfully.', 'success', TRUE, 'feedback');
			}catch(\Exception $e){
				$this->message->set('Unable to update permitted users. '.$e->getMessage(), 'error', TRUE, 'feedback');
			}

			redirect('agent/detail/'.$agent->getSlug());

		}else{
			redirect('agent');
		}

	}


	public function listAll()
	{
		if(!user_access('view all agent'))	redirect('dashboard');

		$URIParams = getFiltersFromURL();
		$offset = $URIParams['offset'];
		$agentRepository = $this->doctrine->em->getRepository('agent\models\Agent');
		$agents = $agentRepository->listPrincipalAgents($offset, PER_PAGE_DATA_COUNT, $URIParams['filters']);
		$total = count($agents);
		if($total > PER_PAGE_DATA_COUNT)
		{
			$this->templatedata['pagination']= getPagination($total, 'agent/index?'.$URIParams['param'], 2, TRUE);

		}
		$this->templatedata['counter'] = $offset ? $offset:0;
		$this->templatedata['agents'] = & $agents;
		$this->templatedata['offset'] = $offset;
		$this->templatedata['filters'] = $URIParams['filters'];
		$this->templatedata['post'] = $URIParams['post'];
		$this->templatedata['page_title'] = 'Agents';
		$this->templatedata['maincontent'] = 'agent/view';
		$this->load->theme('master',$this->templatedata);
	}

	public function add()
	{
		$currentUser = Current_User::user();
		if(!user_access('add agent')){ redirect('dashboard'); }

		if($this->input->post()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Agent Name', 'trim|required|is_unique[ys_agents.name]');
			$this->form_validation->set_rules('country','Country','trim|required');
			$this->form_validation->set_rules('city','City','trim|required');
			$this->form_validation->set_rules('email1','Email 1','trim|required|valid_email');
//			$this->form_validation->set_rules('phone1','Phone 1','trim|required');
			$this->form_validation->set_rules('fax','Fax','trim');
			$this->form_validation->set_rules('website1','Website 1','trim');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');

			if ($this->form_validation->run($this)) {

				$agent = new Agent();
				$country = $this->doctrine->em->find('location\models\Country', $this->input->post('country'));
				$agent->setName(strip_tags($this->input->post('name')));
				$agent->setCountry($country);
				$agent->setCity(strip_tags($this->input->post('city')));
				$agent->setAddress(strip_tags($this->input->post('address')));
				$agent->setPhone1(strip_tags($this->input->post('phone1')));
				$agent->setPhone2(strip_tags($this->input->post('phone2')));
				$agent->setEmail1(strip_tags($this->input->post('email1')));
				$agent->setEmail2(strip_tags($this->input->post('email2')));
				$agent->setWebsite1(trim($this->input->post('website1')));
				$agent->setWebsite2(trim($this->input->post('website2')));
				$agent->setSkype(trim($this->input->post('skype')));
				$agent->setFax(strip_tags($this->input->post('fax')));
				$agent->setPOBox(strip_tags($this->input->post('po_box')));
				$agent->setDescription(strip_tags($this->input->post('description')));
				$agent->setCreatedBy(Current_User::user());
				$this->doctrine->em->persist($agent);
				$this->form_validation->set_message('email1', 'Agent with this email already added');


				try{
					$this->doctrine->em->flush();
					if($agent->id()) {
						$this->message->set("Agent added successfully.", 'success', TRUE, 'feedback');
						redirect('agent/detail/' . $agent->getSlug());
					}

				}catch (\Exception $e){
					$this->message->set("Could not add Agent.", 'error',TRUE,'feedback');
					redirect('agent/add');
				}

			}
		}

		$this->breadcrumb->append_crumb('Add Agent', site_url('#'));
		$this->templatedata['currentUser'] = &$currentUser;
		$this->templatedata['page_title'] = 'Add Agent';
		$this->templatedata['maincontent'] = 'agent/add';
		$this->load->theme('master',$this->templatedata);
	}

    public function is_duplicate($val){
        $post = $this->input->post();
        $emails = [];
        if(isset($post['email1_old']) and $post['email1_old'] != $post['email1']){
            $emails[] = $post['email1'];
        }

        if(isset($post['email2_old']) and $post['email2_old'] != $post['email2']){
            $emails[] = $post['email2'];
        }

        if( count($emails) ){
            $agentRepo = $this->doctrine->em->getRepository('agent\models\Agent');
            $response = $agentRepo->checkDuplicateAgent($emails);

            if( $response === FALSE ){
                return TRUE;
            }else{
                $this->form_validation->set_message('is_duplicate', 'Agent is already created with provided emails. Please contact '.$response);
                return FALSE;
            }
        }else{
            return TRUE;
        }
    }

	public function detail($slug = ""){
		if( $slug == "" || !user_access('view agent') ) redirect('dashboard');

		$agentRepo = $this->doctrine->em->getRepository('agent\models\Agent');
		$agent = $agentRepo->findOneBy(array('slug' => $slug));

		if( !$agent ) redirect('dashboard');
		$agentId = $agent->id();

        $permittedUsers = $agent->getPermittedUsers();
        $pUsers = [];
        if( count($permittedUsers) ){
            foreach($permittedUsers as $p){
                $pUsers[] = $p->id();
            }
        }

		$URIParams = getFiltersFromURL();

		$offset = $URIParams['offset'];

		$contactPersons = $agentRepo->listContactPerson(NULL, NULL, array('agent'=>$agentId ));

		$this->breadcrumb->append_crumb($agent->getName(), site_url('agent/detail/'.$slug));
		$this->templatedata['page_title'] = 'Agent | '.$agent->getName();
		$this->templatedata['contactPersons'] = $contactPersons;
		$this->templatedata['agent'] = $agent;
		$this->templatedata['maincontent'] = 'agent/detail';
        $this->templatedata['permitted_users'] = $pUsers;
		$this->load->theme('master', $this->templatedata);

	}

	public function edit($agentSlug='')
	{
		if(!user_access('edit agent') && $agentSlug == "") redirect('dashboard');

		$this->breadcrumb->append_crumb('Edit Agent', site_url('#'));

		$arepo = $this->doctrine->em->getRepository('agent\models\Agent');
		$agent = $arepo->findOneBy(array('slug'=>$agentSlug));

		if(!$agent) redirect('dashboard');

		if ($agent->isDeleted()) {
			$this->message->set("This Agent no longer exists.", 'error',TRUE,'feedback');
			redirect('agent');
		}

		if($this->input->post()){

			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Agent Name', 'trim|required|alpha_numeric_tb');
			$this->form_validation->set_rules('country','Country','trim|required');
			$this->form_validation->set_rules('city','City','trim|required');
			$this->form_validation->set_rules('email1','Email 1','trim|required|valid_email');
//			$this->form_validation->set_rules('phone1','Phone 1','trim|required');
			$this->form_validation->set_rules('fax','Fax','trim');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('status', 'Status', 'trim|required');

			if ($this->form_validation->run($this)) {

				$country = $this->doctrine->em->find('location\models\Country', $this->input->post('country'));

				$agent->setName(strip_tags($this->input->post('name')));
				$agent->setCountry($country);
				$agent->setCity(strip_tags($this->input->post('city')));
				$agent->setAddress(strip_tags($this->input->post('address')));
				$agent->setPhone1(strip_tags($this->input->post('phone1')));
				$agent->setPhone2(strip_tags($this->input->post('phone2')));
				$agent->setEmail1(strip_tags($this->input->post('email1')));
				$agent->setEmail2(strip_tags($this->input->post('email2')));
				$agent->setWebsite1(trim($this->input->post('website1')));
				$agent->setWebsite2(trim($this->input->post('website2')));
				$agent->setSkype(trim($this->input->post('skype')));
				$agent->setFax(strip_tags($this->input->post('fax')));
				$agent->setPOBox(strip_tags($this->input->post('po_box')));
				$agent->setDescription(strip_tags($this->input->post('description')));
				$agent->setStatus($this->input->post('status'));

				$this->doctrine->em->persist($agent);

				try {
					$this->doctrine->em->flush();
					$this->message->set("Agent edited successfully.", 'success',TRUE,'feedback');
					redirect('agent/detail/'.$agent->getSlug());
				} catch (\Exception $e) {
					$this->message->set("Could not edit Agent.".$e->getMessage(), 'error',TRUE,'feedback');
					redirect('agent/edit/'.$agent->getSlug());
				}
			}
		}

		$this->templatedata['agent'] = &$agent;
		$this->templatedata['page_title'] = 'Edit Agent | '.$agent->getName();
		$this->templatedata['maincontent'] = 'agent/edit';
		$this->load->theme('master',$this->templatedata);

	}

	public function _validPhone($phone){
		die($phone);
	}

	public function delete($pagentSlug='')
	{
		if (empty($pagentSlug)) redirect('agent');

		if(isset($this->templatedata['critical_alerts']) || !user_access('delete super agent')){
			redirect('dashboard');
		}

		$arepo = $this->doctrine->em->getRepository('agent\models\Agent');
		$uRepo = $this->doctrine->em->getRepository('user\models\User');
		$pagent = $arepo->findOneBy(array('slug'=>$pagentSlug));
		$pagent->markAsDeleted();

		$pUsers = $uRepo->findBy(array('agent'=>$pagent->id()));
		foreach ($pUsers as $pu) {
			$pu->delete();
			$this->doctrine->em->persist($pu);
		}

		$filters['parentAgentID'] = $pagent->id();
		$subagents = $arepo->listSubAgents(NULL,NULL,$filters);

		if (count($subagents) > 0) {

			foreach ($subagents as $sa)	{

				$sUsers = $uRepo->findBy(array('agent'=>$sa['agent_id']));
				foreach ($sUsers as $su) {
					$su->delete();
					$this->doctrine->em->persist($su);
				}

				$agent = $this->doctrine->em->find('models\Agent',$sa['agent_id']);
				$agent->markAsDeleted();
				$this->doctrine->em->persist($agent);
			}
		}

		$this->doctrine->em->persist($pagent);
		$this->doctrine->em->flush();

		$this->message->set("Principal Agent deleted successfully", 'success', TRUE, 'feedback');
		redirect('agent');
	}

	public function block($pagentSlug = '')
	{
		if($pagentSlug == '' || !user_access('block super agent')) redirect();

		$arepo = $this->doctrine->em->getRepository('models\Agent');
		$agent = $arepo->findOneBy(array('slug'=> $pagentSlug));
		$agent->deactivate();
		$this->doctrine->em->persist($agent);
		try{
			$this->doctrine->em->flush();
			if($agent->id()){
				$this->message->set('Agent Blocked Successfully', 'success', TRUE, 'feedback');
				redirect('agent');
			}
		}catch(\Exception $e){
			$this->message->set('Unable to block agent', 'error', TRUE, 'feedback');
			redirect('agent');
		}
	}

	public function unblock($pagentSlug = '')
	{
		if($pagentSlug == '' || !user_access('block super agent')) redirect();

		$arepo = $this->doctrine->em->getRepository('models\Agent');
		$agent = $arepo->findOneBy(array('slug'=>$pagentSlug));
		$agent->setReason('');
		$agent->activate();
		$this->doctrine->em->persist($agent);

		try{
			$this->doctrine->em->flush();
			if($agent->id()){
				$this->message->set('Agent unblocked successfully', 'success', TRUE, 'feedback');
				redirect('agent');
			}
		}catch(\Exception $e){
			$this->message->set('Unable to unblock agent', 'error', TRUE, 'feedback');
			redirect('agent');
		}

	}

	public function group($slug = "")
	{
		if(!user_access('manage agent group')) redirect('dashboard');

		$this->breadcrumb->append_crumb('Agent', site_url('agent'));

		$groupRepo = $this->doctrine->em->getRepository('agent\models\AgentGroup');
		$agentRepo = $this->doctrine->em->getRepository('agent\models\Agent');

		$param = "";

		if($slug == "")
		{
			$filters = array();

			$get = $this->input->get();

			$offset = (isset($get['per_page']) and $get['per_page'] != "")? $get['per_page'] : NULL;

			$processorGroups = $groupRepo->getGroupsList($offset, PER_PAGE_DATA_COUNT, $filters);

			$total = count($processorGroups);

			if($total > PER_PAGE_DATA_COUNT)
			{
				$this->load->library('pagination');

				$config['base_url'] = base_url().'agent/group?'.$param;
				$config['total_rows'] = $total;
				$config['per_page'] = PER_PAGE_DATA_COUNT;
				$config['uri_segment'] = 2;
				$config['prev_link'] = 'Previous';
				$config['next_link'] = 'Next';
				$config['page_query_string'] = TRUE;

				$this->pagination->initialize($config);
				$this->templatedata['pagination'] = $this->pagination->create_links();
			}

			$this->templatedata['offset'] = $offset;
			$this->templatedata['groups'] = $processorGroups;
			$this->templatedata['maincontent'] = 'agent/group';

		}
		else
		{
			$group = $groupRepo->findOneBy(array('slug' => $slug));

			if(!$group) redirect('dashboard');

			if($this->input->post())
			{
				$agents = $this->input->post('agents');

				if(count($agents) > 0)
				{
					foreach($agents as $a)
					{
						$agent = $this->doctrine->em->find('agent\models\Agent', $a);
						if($agent)
						{
							$agent->setGroup($group);

							$this->doctrine->em->persist($agent);
						}
					}

					try{
						$this->doctrine->em->flush();
						$this->message->set('Agents added successfully to '.$group->getName(), 'success', TRUE, 'feedback');
					}catch(\Exception $e){
						$this->message->set('Unable to add Agents on group', 'error', TRUE, 'feedback');
					}

					redirect('agent/group/'.$slug);
				}

			}

			$gId = $group->id();
			$agents = $agentRepo->findBy(array('groups' => $gId, 'deleted' => FALSE));

			$country = $group->getCountry();

			$conId = $country ? $country->id() : NULL;

//			$unGroupedAgents = $agentRepo->getAgentsNotAssignedToGroups(AgentGroup::GROUP_TYPE_PA, $conId, NULL);

			$this->templatedata['maincontent'] = 'agent/list_by_group';
//			$this->templatedata['group'] = $group;
//			$this->templatedata['agentsInGroup'] = $agents;
//			$this->templatedata['agentsNotInGroup'] = $unGroupedAgents;
		}

		$this->breadcrumb->append_crumb('Groups', site_url('agent/group'));
		$this->load->theme('master', $this->templatedata);
	}

	public function removeFromGroup($agentID = "", $slug = "")
	{
		if($agentID == "") redirect('dashboard');

		$agent = $this->doctrine->em->find('agent\models\Agent', $agentID);

		$agent->setGroup(NULL);

		$this->doctrine->em->persist($agent);

		$this->doctrine->em->flush();

		redirect('agent/group/'.$slug);
	}

	public function processorBulkUpload()
	{
		$this->breadcrumb->append_crumb('Configuration', site_url('config'));
		$this->breadcrumb->append_crumb('Upload Payers', '#');

		if($this->input->post())
		{
			if(isset($_FILES['processor_file']))
			{
				$config['upload_path'] = './assets/uploads/temp/';
				$config['allowed_types'] = 'xls';
				$config['max_size']	= '';
				$this->load->library('upload', $config);

				if(!$this->upload->do_upload('processor_file'))
				{
					$this->message->set($this->upload->display_errors(), 'error', TRUE, 'feedback');
					redirect('agent/processorBulkUpload');
				}else
				{
					$updata = $this->upload->data();
					$this->readProcessorExcel($updata['full_path']);

				}
			}
		}

		$this->templatedata['maincontent'] = 'agent/processor_upload';
		$this->load->theme('master', $this->templatedata);
	}



	public function excelSample() {
		$this->load->helper('download');
		$data = file_get_contents('./assets/samples/processors.xls');
		force_download('processors.xls', $data);
	}

	public function deleteAgent(){

		if(!user_access('delete agent'))	redirect('dashboard');

		$id = $this->input->post('id');
		$agent = $this->doctrine->em->find('agent\models\Agent', $id);
		$response['status'] = 'error';
		$response['message'] = '';
		if( $agent ){
			$agent->markAsDeleted();
			$this->doctrine->em->persist($agent);

			try {
				$this->doctrine->em->flush();
				log_message('info', 'Agent ' . $agent->getName() . ' marked as deleted');
				$response['status'] = 'success';
				$response['message'] = 'The Agent "'.$agent->getName().'" has been Deleted.';
				$this->message->set($response['message'], 'success', true, 'feedback');
			} catch (Exception $e) {
				$response['message'] = 'Unable to delete Agent. '.$e->getMessage();
			}
		}else{
			$response['message'] = 'Agent Not Found.';
		}

		echo json_encode($response);
	}

	public function unDeleteAgent()
	{
		$id = $this->input->post('id');

		$agent = $this->doctrine->em->find('agent\models\Agent', $id);
		$response['status'] = 'error';
		$response['message'] = '';

		if( $agent ){
			$agent->markAsUnDeleted();
			$this->doctrine->em->persist($agent);

			try {
				$this->doctrine->em->flush();
				log_message('info', 'Agent ' . $agent->getName() . ' marked as UnDeleted');
				$response['status'] = 'success';
				$response['message'] = 'The Agent "'.$agent->getName().'" has been UnDeleted.';
				$this->message->set($response['message'], 'success', true, 'feedback');
			} catch (Exception $e) {
				$response['message'] = 'Unable to UnDelete Agent. '.$e->getMessage();
			}
		}else{
			$response['message'] = 'Agent Not Found.';
		}

		echo json_encode($response);

	}
}