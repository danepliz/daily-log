<?php

class Ajax_Controller extends Xhr{
	
	
	public function __construct(){
		parent::__construct();
	}

    public function getCountryForm($countryID = NULL ){
        $response = array();
        $data = array();
        if( $countryID and $countryID !== "" ){
            $country = $this->doctrine->em->find('location\models\Country', $countryID);
            if( $country ){
                $data['country'] = $country;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Country Not Found';
            }
        }
        $this->load->theme('common/xhrtemplates/country_form', $data);
    }

    public function saveCountry($countryID = NULL){
        $post = $_POST;

        $country = NULL;
        $response = array();
        $isEditing = FALSE;

        if( !is_null($countryID) and $countryID !== "" and $countryID !== '0' ){
            $country = $this->doctrine->em->find('location\models\Country', $countryID);
            $isEditing = TRUE;
        }else{
            $country = new \location\models\Country();
        }

        if( is_null($country) ){
            $response['status'] = 'error';
            $response['message'] = 'Country missing.';
            $response['data'] = '';
        }else{
            $country->setName(ucwords($post['name']));
            $country->setNationality($post['nationality']);
            $country->setIso_2($post['iso_2']);
            $country->setIso_3($post['iso_3']);
            $country->setDialingCode($post['dialing_code']);

            $this->doctrine->em->persist($country);

            try{
                $this->doctrine->em->flush();
                $msg = ($isEditing)? 'Country Updated Successfully.' : 'Country Added Successfully.';
                $this->message->set($msg, 'success', TRUE, 'feedback');
                $response['status'] = 'success';
                $response['message'] = $msg;
                $response['data'] = '';

            }catch(\Exception $e){
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
                $response['data'] = '';
            }
        }

        echo json_encode($response);
    }
}