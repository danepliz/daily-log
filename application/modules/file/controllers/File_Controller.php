<?php

use file\models\TourFile;

class File_Controller extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->load->helper(array('file/xo', 'agent/agent'));
        $this->breadcrumb->append_crumb('Tour File',site_url('file'));
	}	
	
	public function index(){
        if(!user_access('view tour file'))	redirect('dashboard');
        $URIParams = getFiltersFromURL();
        $offset = $URIParams['offset'];
        $tourFileRepository = $this->doctrine->em->getRepository('file\models\TourFile');
        $tourFiles = $tourFileRepository->listFiles($offset, PER_PAGE_DATA_COUNT, $URIParams['filters']);
        $total = count($tourFiles);

        if($total > PER_PAGE_DATA_COUNT)
        {
            $this->templatedata['pagination']= getPagination($total, 'file/index?'.$URIParams['param'], 2, TRUE);
        }
        $this->templatedata['page_title'] = 'Tour Files';
        $this->templatedata['tourFiles'] = $tourFiles;
        $this->templatedata['offset'] = $offset;
        $this->templatedata['filters'] = $URIParams['filters'];
		$this->templatedata['maincontent'] = 'file/index';
		$this->load->theme('master',$this->templatedata);
	}

    public function register(){
        if(!user_access('manage tour file'))	redirect('dashboard');

        if($this->input->post()){
            $this->form_validation->set_rules('file', 'File', 'required|trim');
            $this->form_validation->set_rules('client', 'Client', 'required|trim');
            $this->form_validation->set_rules('pax', 'Number of Pax', 'required|trim');
            $this->form_validation->set_rules('nationality', 'Nationality', 'required|trim');
            $this->form_validation->set_rules('market', 'Market', 'required|trim');
            $this->form_validation->set_rules('tourOfficer', 'Tour Officer', 'required|trim');

            if( $this->form_validation->run($this) === TRUE ){
                $post = $this->input->post();

                $market = $this->doctrine->em->find('market\models\Market', $post['market']);
                $nationality = $this->doctrine->em->find('location\models\Country', $post['nationality']);
                $agent = ($post['agent'] and $post['agent'] !== "")?  $this->doctrine->em->find('agent\models\Agent', $post['agent']) : NULL;
                $agentContactPerson = ($post['agentContactPerson'] and $post['agentContactPerson'] !== "")?  $this->doctrine->em->find('agent\models\AgentContactPerson', $post['agentContactPerson']) : NULL;
                $tourOfficer = ($post['tourOfficer'] and $post['tourOfficer'] !== "")?  $this->doctrine->em->find('user\models\User', $post['tourOfficer']) : NULL;
                $current_user = Current_User::user();

                $tourFile = new TourFile();
                $tourFile->setMarket($market);
                if( $agent ) $tourFile->setAgent($agent);
                if( $agentContactPerson ) $tourFile->setAgentContactPerson($agentContactPerson);
                if( $tourOfficer ) $tourFile->setTourOfficer($tourOfficer);
                $tourFile->setClient($post['client']);
                $tourFile->setCreatedBy($current_user);
                $tourFile->setInstructions($post['instructions']);
                $tourFile->setNumberOfPax($post['pax']);
                $tourFile->setNumberOfChildren($post['child']);
                $tourFile->setNumberOfInfants($post['infants']);
                $tourFile->setNationality($nationality);
                $tourFile->setFileNumber($post['file']);

                $this->doctrine->em->persist($tourFile);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('File registered successfully.', 'success', TRUE, 'feedback');
                    redirect('file/detail/'.$tourFile->id());
                }catch(\Exception $e){
                    $this->message->set('Unable to register file. '.$e->getMessage(), 'error', TRUE, 'feedback');
                    redirect('file/register');
                }

            }
        }

        $this->breadcrumb->append_crumb('Register', site_url('file/register'));
        $this->templatedata['page_title'] = 'Register Tour File';
        $this->templatedata['maincontent'] = 'file/tour_file';
        $this->load->theme('master',$this->templatedata);
    }

    public function detail($file_id = ""){

        if( $file_id == "" or ! user_access_or([ 'view tour file', 'view all tour files' ]) ) redirect('dashboard');

        $tourFile = $this->doctrine->em->find('file\models\TourFile',$file_id);

        if( is_null($tourFile) ){ redirect('dashboard'); }

        $permittedUsers = $tourFile->getPermittedUsers();
        $pUsers = [];
        if( count($permittedUsers) ){
            foreach($permittedUsers as $p){
                $pUsers[] = $p->id();
            }
        }

        $currentUser = Current_User::user();

        if(!user_access('view all tour files') and $currentUser->id() != $tourFile->getCreatedBy()->id() and ! in_array($currentUser->id(), $pUsers)  ) redirect('dashboard');


        if( $this->input->post() ){
            $this->form_validation->set_rules('file', 'File', 'required|trim');
            $this->form_validation->set_rules('client', 'Client', 'required|trim');
            $this->form_validation->set_rules('pax', 'Number of Pax', 'required|trim');
            $this->form_validation->set_rules('nationality', 'Nationality', 'required|trim');
            $this->form_validation->set_rules('market', 'Market', 'required|trim');
            $this->form_validation->set_rules('tourOfficer', 'Tour Officer', 'required|trim');

            if( $this->form_validation->run($this) === TRUE ){
                $post = $this->input->post();

                $market = $this->doctrine->em->find('market\models\Market', $post['market']);
                $nationality = $this->doctrine->em->find('location\models\Country', $post['nationality']);
                $agent = ($post['agent'] and $post['agent'] !== "")?  $this->doctrine->em->find('agent\models\Agent', $post['agent']) : NULL;
                $agentContactPerson = ($post['agentContactPerson'] and $post['agentContactPerson'] !== "")?  $this->doctrine->em->find('agent\models\AgentContactPerson', $post['agentContactPerson']) : NULL;
                $tourOfficer = ($post['tourOfficer'] and $post['tourOfficer'] !== "")?  $this->doctrine->em->find('user\models\User', $post['tourOfficer']) : NULL;
                $current_user = Current_User::user();

                $tourFile->setMarket($market);
                if( $agent ) $tourFile->setAgent($agent);
                if( $agentContactPerson ) $tourFile->setAgentContactPerson($agentContactPerson);
                if( $tourOfficer ) $tourFile->setTourOfficer($tourOfficer);
                $tourFile->setClient($post['client']);
                $tourFile->setUpdatedBy($current_user);
                $tourFile->setInstructions($post['instructions']);
                $tourFile->setNumberOfPax($post['pax']);
                $tourFile->setNumberOfChildren($post['child']);
                $tourFile->setNumberOfInfants($post['infants']);
                $tourFile->setNationality($nationality);
                $tourFile->setFileNumber($post['file']);

                $this->doctrine->em->persist($tourFile);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('File updated successfully.', 'success', TRUE, 'feedback');
                }catch(\Exception $e){
                    $this->message->set('Unable to update file. '.$e->getMessage(), 'error', TRUE, 'feedback');
                }

                redirect('file/detail/'.$tourFile->id());

            }

        }

        $this->breadcrumb->append_crumb('Tour File', site_url('file/detail/'.$file_id));
        $this->templatedata['page_title'] = 'Tour File';
        $this->templatedata['file'] = $tourFile;
        $this->templatedata['permitted_users'] = $pUsers;
        $this->templatedata['maincontent'] = 'file/file_detail';
        $this->load->theme('master', $this->templatedata);
    }


    public function addPermittedUsers(){

        if( $this->input->post() ){
            $post = $this->input->post();

            $fileID = $post['file_id'];
            $permittedUsers = $post['permitted_users'];

            $file = $this->doctrine->em->find('file\models\TourFile', $fileID);
            $file->resetPermittedUsers();

            if( count($permittedUsers) ){
                $userRepo = $this->doctrine->em->getRepository('user\models\User');
                foreach($permittedUsers as $key => $val){
                    $user = $userRepo->find($val);
                    if($user){
                        $file->addPermittedUser($user);
                    }
                }
            }

            $this->doctrine->em->persist($file);

            try{
                $this->doctrine->em->flush();
                $this->message->set('Tour File Permitted Users Updated successfully.', 'success', TRUE, 'feedback');
            }catch(\Exception $e){
                $this->message->set('Unable to update permitted users. '.$e->getMessage(), 'error', TRUE, 'feedback');
            }

            redirect('file/detail/'.$fileID);

        }else{
            redirect('file');
        }

    }
}
