<?php


class Ajax_Controller extends Xhr{


    public function getMarketForm($marketID = NULL ){

        $response = array();
        $data = array();
        $this->load->helper('currency/currency');

        if( $marketID and $marketID !== "" ){

            $market = $this->doctrine->em->find('market\models\Market', $marketID);

            if( $market ){
                $data['market'] = $market;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Market Not Found';
            }
        }

        $this->load->theme('market/edit', $data);
    }

    public function toggleMarketStatus($marketID){

        $response = array();

        if($marketID and $marketID !== ""){
            $market = $this->doctrine->em->find('market\models\Market', $marketID);

            if( $market ){

                $statusDesc = ' Enabled ';

                if( $market->isActive() ){
                    $market->markAsInactive();
                    $statusDesc = ' Disabled ';
                }else{
                    $market->markAsActive();
                }

                $this->doctrine->em->persist($market);

                try{
                    $this->doctrine->em->flush();

                    $response['status'] = 'success';
                    $response['currentStatus'] = $market->isActive();
                    $response['message'] = $market->getName().$statusDesc.'successfully.';

                }catch (\Exception $e){
                    $response['status'] = 'error';
                    $response['message'] = 'Unable to change status. '.$e->getMessage();
                }
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Could not find the branch';
            }
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Could not find the branch';
        }
        echo json_encode($response);
    }

    public function saveMarket(){
        $post = $_POST;
        $branch = NULL;
        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'Unable to edit market';
        if( isset($post['id']) and $post['id'] !== '' and user_access('administer market') ){
            $marketID = $post['id'];
            $market = $this->doctrine->em->find('market\models\Market', $marketID);
            $currency = $this->doctrine->em->find('currency\models\Currency', $post['currency']);
            if( $market ){
                $market->setName($post['name']);
                $market->setDescription($post['description']);
                if( isset($post['status']) and $post['status'] == true ){
                    $market->markAsActive();
                }else{
                    $market->markAsInactive();
                }
                if($currency){
                    $market->setCurrency($currency);
                }
                $this->doctrine->em->persist($market);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Market Updated Successfully.', 'success', TRUE, 'feedback');
                    $response['status'] = 'success';
                    $response['message'] = 'Market Updated Successfully.';

                }catch (\Exception $e){
                    $response['message'] = 'Unable to update market. '.$e->getMessage();
                }
            }
        }
        echo json_encode($response);
    }

    public function getCurrencyByMarket($marketID = ''){

        $currency = NULL;
        $response['status'] = 'error';
        $response['currency'] = [];
        $response['message'] = '';

        if( $marketID != "" ){
            $market = $this->doctrine->em->find('market\models\Market', $marketID);
            $currency = ( $market )? $market->getCurrency() : NULL;

        }
        if(is_null($currency) ){
            $optionCurrency = \Options::get('config_market_currency', '');
            $currency = ( $optionCurrency == '' ) ? NULL : $this->doctrine->em->find('currency\models\Currency', $optionCurrency);
        }
        $response['status'] = 'success';
        $response['message'] = '';

        if( ! is_null($currency) ){
            $response['currency'] = [
                'id' => $currency->id(),
                'iso_3' => $currency->getIso3(),
                'name' => $currency->getName()
            ];
        }else{
            $response['currency'] = [
                'id' => 0,
                'iso_3' => 'USD',
                'name' => 'United States Dollar'
            ];

        }

        echo json_encode($response);
    }





}