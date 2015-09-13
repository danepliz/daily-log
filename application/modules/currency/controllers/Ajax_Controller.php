<?php


class Ajax_Controller extends Xhr{


    public function getCurrencyForm($currencyID = NULL ){

        $response = array();
        $data = array();

        if( $currencyID and $currencyID !== "" ){

            $currency = $this->doctrine->em->find('currency\models\Currency', $currencyID);

            if( $currency ){
                $data['currency'] = $currency;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Currency Not Found';
            }
        }

        $this->load->theme('currency/edit_currency_template', $data);
    }

    public function saveCurrency(){
        $post = $_POST;
        $currency = NULL;
        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'Unable to update currency.';
        if( isset($post['id']) and $post['id'] !== '' and user_access('update currency') ){
            $currencyID = $post['id'];
            $currency = $this->doctrine->em->find('currency\models\Currency', $currencyID);
            if( $currency ){

                $currentUser = Current_User::user();

                $currency->setName($post['name']);
                $currency->setDescription($post['description']);
                $currency->setIso3($post['iso_3']);
                $currency->setSymbol($post['symbol']);
                $currency->setUpdatedBy($currentUser);

                $this->doctrine->em->persist($currency);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Currency Updated Successfully.', 'success', TRUE, 'feedback');
                    $response['status'] = 'success';
                    $response['message'] = 'Currency Updated Successfully.';

                }catch (\Exception $e){
                    $response['message'] = 'Unable to update Currency. '.$e->getMessage();
                }
            }
        }

        echo json_encode($response);
    }



}