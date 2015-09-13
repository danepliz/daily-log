<?php

use branch\models\Branch;

class Branch_Controller extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        $this->breadcrumb->append_crumb('Branch', site_url('branch'));
    }


    public function index(){
        
        if( !user_access('administer branch')) redirect('dashboard');

        $branchRepository = $this->doctrine->em->getRepository('branch\models\Branch');
        $branches = $branchRepository->findAll();

        $this->templatedata['page_title'] = 'Branch Lists';
        $this->templatedata['maincontent'] = 'branch/list';
        $this->templatedata['branches'] = $branches;
        $this->load->theme('master', $this->templatedata);
    }

    public function add(){

        if( !user_access('administer branch')) redirect('dashboard');

        if( $this->input->post() ){

            $post = $this->input->post();

            $this->form_validation->set_rules('name', 'Hotel Name', 'required|trim|xss_clean');

            if( $this->form_validation->run($this) === TRUE){

                $branch = new Branch();
                $branch->setName(trim($post['name']));
                $branch->setDescription(trim($post['description']));
                $branch->markAsActive();

                $this->doctrine->em->persist($branch);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Branch "'.$branch->getName().'" added successfully.', 'success', TRUE, 'feedback');
                }catch (\Exception $e){
                    $this->message->set('Unable to add Branch. "'.$e->getMessage().'"', 'success', TRUE, 'feedback');
                    $this->templatedata['has_error'] = TRUE;
                }
            }else{
                $this->templatedata['has_error'] = TRUE;
            }
        }
        redirect('branch');
    }
}