<?php

use parameter\models\TourActivityParameter;

class Parameter_Controller extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb->append_crumb('Parameters', site_url('parameter'));
    }

    public function index(){

        $marketRepository = $this->doctrine->em->getRepository('market\models\Market');
        $markets = $marketRepository->findAll();

        $this->breadcrumb->append_crumb('Category', site_url('market'));
        $this->templatedata['page_title'] = 'Market Category';
        $this->templatedata['hotel_grades'] = $markets;
        $this->templatedata['maincontent'] = 'market/list';
        $this->load->theme('master', $this->templatedata);
    }

    public function tour(){

        if(!user_access('administer parameter'))	redirect('dashboard');

        $parameterRepo = $this->doctrine->em->getRepository('parameter\models\TourActivityParameter');
        $parameters = $parameterRepo->listParameters();

        if( $this->input->post() ){

            $this->form_validation->set_rules('name', 'Activity Name','required|trim|is_unique[ys_tour_activity_parameters.name]');

            $post = $this->input->post();

            if( $this->form_validation->run($this) == TRUE ){

                $parameter = new TourActivityParameter();
                $parameter->setName(strtoupper($post['name']));

                if( $post['travel_xo'] == 1 or $post['travel_xo'] == TRUE ){
                    $parameter->selectTravelXO();
                }

                if( $post['transport_xo'] == 1 or $post['transport_xo'] == TRUE ){
                    $parameter->selectTransportXO();
                }

                if( $post['hotel_xo'] == 1 or $post['hotel_xo'] == TRUE ){
                    $parameter->selectHotelXO();
                }

                if( $post['entrance_xo'] == 1 or $post['entrance_xo'] == TRUE ){
                    $parameter->selectEntranceXO();
                }

                if( $post['other_xo'] == 1 or $post['other_xo'] == TRUE ){
                    $parameter->selectOtherXO();
                }

                $this->doctrine->em->persist($parameter);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Tour Activity Parameter added successfully.', 'success', TRUE, 'feedback');
                    redirect('parameter/tour');
                }catch(\Exception $e){
                    $this->message->set('Unable to add parameter. '.$e->getMessage(), 'error', TRUE, 'feedback');
                    $this->templatedata['hasError'] = TRUE;
                    redirect('parameter/tour');
                }

            }else{
                $this->templatedata['hasError'] = TRUE;
            }
            $this->templatedata['post']= $post;
        }

        $this->breadcrumb->append_crumb('Tour Activity Parameters', site_url('parameter/tour'));
        $this->templatedata['page_title'] = 'Tour Activities Parameter';
        $this->templatedata['parameters'] = $parameters;
        $this->templatedata['maincontent'] = 'parameter/list';
        $this->load->theme('master', $this->templatedata);
    }
}