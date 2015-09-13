<?php


class Service_Controller extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb->append_crumb('Hotel', site_url('hotel'));
    }

    public function index()
    {

        if (!user_access('administer hotel')) {
            redirect('dashboard');
        }

        $hotelServiceRepository = $this->doctrine->em->getRepository('hotel\models\HotelServices');
        $services = $hotelServiceRepository->findBy(
            array('status' => 1),
            array('id' => 'ASC')
        );
        $this->breadcrumb->append_crumb('Service', site_url('hotel/service'));
        $this->templatedata['page_title'] = 'Hotel Service';
        $this->templatedata['services'] = $services;
        $this->templatedata['maincontent'] = 'hotel/service/list';
        $this->load->theme('master', $this->templatedata);
    }



    public function add()
    {

        if(!user_access('administer hotel')){ redirect('dashboard'); }

        if( $this->input->post() ){
            $this->form_validation->set_rules('name', 'Name', 'required|xss_clean|trim|callback_checkDuplicateService');
            $post = $this->input->post();
            if( $this->form_validation->run($this) === TRUE ){
                $serviceName = strtoupper(trim($post['name']));
                $serviceDescription = strtoupper(trim($post['description']));
                $hotelService = new \hotel\models\HotelServices();
                $hotelService->setName($serviceName);
                $hotelService->setDescription($serviceDescription);
                $this->doctrine->em->persist($hotelService);
                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Hotel Service "'.$serviceName.'" added successfully. ', 'success', TRUE, 'feedback');

                }catch (\Exception $e){
                    $this->message->set($e->getMessage(), 'error', TRUE, 'feedback');
                }
            }
            else{
                $validationError = validation_errors('<p>','</p>');
                $this->message->set($validationError, 'error', TRUE, 'feedback');
            }
        }
        redirect('hotel/service');
    }

    public function checkDuplicateService($str){
        $hotelServiceRepository = $this->doctrine->em->getRepository('hotel\models\HotelServices');
        $service = $hotelServiceRepository->findOneBy(array('name' => $str));

        if( $service ){
            $this->form_validation->set_message('checkDuplicateService', 'Hotel Service "'.$str.'" already exists.');
            return FALSE;
        }

        return TRUE;
    }

    public function deleteService(){

        if(!user_access('administer hotel')){ redirect('dashboard'); }

        $service_Id = $this->input->post('id');

        $service = $this->doctrine->em->find('hotel\models\HotelServices', $service_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if( $service ){
            $service->markAsDeleted();
            $this->doctrine->em->persist($service);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'HotelCategory ' . $service->getName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The Hotel Service"'.$service->getName().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Hotel Service. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'Service Not Found.';
        }

        echo json_encode($response);
    }

//    public function is_duplicate($val){
//        $post = $this->input->post();
//        $emails = [];
//        if(isset($post['email1_old']) and $post['email1_old'] != $post['email1']){
//            $emails[] = $post['email1'];
//        }
//
//        if(isset($post['email2_old']) and $post['email2_old'] != $post['email2']){
//            $emails[] = $post['email2'];
//        }
//
//        if( count($emails) ){
//            $agentRepo = $this->doctrine->em->getRepository('agent\models\Agent');
//            $response = $agentRepo->checkDuplicateAgent($emails);
//
//            if( $response === FALSE ){
//                return TRUE;
//            }else{
//                $this->form_validation->set_message('is_duplicate', 'Agent is already created with provided emails. Please contact '.$response);
//                return FALSE;
//            }
//        }else{
//            return TRUE;
//        }
//
//
//
//    }


}
