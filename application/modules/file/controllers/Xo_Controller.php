<?php

use file\models\TourFileActivity;

class Xo_Controller extends Admin_Controller
{
	public function __construct()
	{
		//$this->mainmenu = MAINMENU_DASHBOARD;
		parent::__construct();
        $this->load->helper('file/xo');
        $this->load->helper('agent/agent');
        $this->breadcrumb->append_crumb('Tour File', site_url('exchangeOrder'));
	}	
	
	public function index()
	{
        if(!user_access_or(['view exchange orders', 'view all exchange orders']))	redirect('dashboard');

        $URIParams = getFiltersFromURL();

        $offset = $URIParams['offset'];
        $xoRepository = $this->doctrine->em->getRepository('file\models\TourFileActivity');
        $xo = $xoRepository->listXoTest($offset, PER_PAGE_DATA_COUNT, $URIParams['filters']);
        $total = count($xo);

        if($total > PER_PAGE_DATA_COUNT)
        {
            $this->templatedata['pagination']= getPagination($total, 'agent/index?'.$URIParams['param'], 2, TRUE);
        }

        $this->templatedata['counter'] = $offset ? $offset:0;
        $this->templatedata['xo'] = & $xo;
        $this->templatedata['offset'] = $offset;
        $this->templatedata['filters'] = $URIParams['filters'];
        $this->templatedata['post'] = $URIParams['post'];
        $this->templatedata['page_title'] = 'Generated Exchange Order';
        $this->templatedata['maincontent'] = 'file/list';
        $this->load->theme('master',$this->templatedata);
	}

    public function hotel($fileID){

        if( $fileID == ""  or !user_access('add hotel activity')) redirect('dashboard');

        $file = $this->doctrine->em->find('file\models\TourFile', $fileID);

        $this->breadcrumb->append_crumb('Hotel Activity', site_url('file/activity/hotel'));
        $this->templatedata['page_title'] = 'Hotel Activity';
        $this->templatedata['file'] = $file;
        $this->templatedata['maincontent'] = 'file/activities/hotel_activity';
        $this->load->theme('master',$this->templatedata);
    }

    public function deleteXo(){
        $xo_Id = $this->input->post('id');

        $xo = $this->doctrine->em->find('file\models\TourFileActivity', $xo_Id);

        $response['status'] = 'error';
        $response['message'] = '';

        if( $xo ){
            $xo->markAsDeleted();
            $this->doctrine->em->persist($xo);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'Tour File Activity ' . $xo->getType() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The Tour File Activity "'.$xo->getType().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Tour File Activity. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'The Tour File Activity Not Found.';
        }

        echo json_encode($response);
    }

    public function revertActivityXo($activityId=''){

        if( $activityId == "" ) redirect('dashboard');

        $activity = $this->doctrine->em->find('file\models\TourFileActivity', $activityId);

        $response['status'] = 'error';
        $response['message'] = '';

        if( ! $activity ) redirect('dashboard');

        $fileID = $activity->getTourFile()->id();
        if( $activity->isXoGenerated() ){
            $reverted_times = $activity->getRevertedTimes();

            if($reverted_times == Options::get('config_revert_time_xo', ALLOWED_XO_REVERT_TIME_DEFAULT)){
                $newActivity = clone $activity;
                $newActivity->markAsXoNotGenerated();
                $newActivity->setXoNumber(NULL);
                $newActivity->setRevertedTimes(0);
                $newActivity->setStatus(TourFileActivity::ACTIVITY_STATUS_ACTIVE);


                if( count($activity->getDetails()) ){
                    foreach($activity->getDetails() as $d){

                        $newD = clone $d;
                        $newD->setTourActivity($newActivity);
                        $this->doctrine->em->persist($newD);
                        $newActivity->addDetail($newD);


                        if( count($d->getChildren()) ){
                            foreach($d->getChildren() as $c){
                                $newC = clone $c;
                                $newC->setParent($newD);
                                $this->doctrine->em->persist($newC);
                                $newD->addChildren($newC);
                            }
                        }
                    }
                }

                $this->doctrine->em->persist($newActivity);

                $activity->setStatus(TourFileActivity::ACTIVITY_STATUS_VOID);
            }else{
                $activity->markAsXoNotGenerated();
                $activity->increaseRevertedTimes();
            }

            $this->doctrine->em->persist($activity);

            try{
                $this->doctrine->em->flush();
                log_message('info', 'Successfully Reverted !!!');
                $response['status'] = 'success';
                $response['message'] = 'Successfully Reverted !!!';

                if($reverted_times == Options::get('config_revert_time_xo', ALLOWED_XO_REVERT_TIME_DEFAULT)){
                    $response['message'] = 'Exchange Order has been Voided, and new exchange order has been generated.';
                }else{
                    $response['message'] = 'Exchange Order has been successfully reverted.';
                }

                $this->message->set($response['message'], 'success', true, 'feedback');

            }catch (\Exception $e){
                $this->message->set($e->getMessage(), 'error', true, 'feedback');
                redirect('file/activity/detail/'.$activityId);
                $response['message'] = 'Unable to Revert. '.$e->getMessage();
            }
        }
        redirect('file/detail/'.$fileID);

    }
//    public function voidXo($activityId=''){
//
//        if( $activityId == "" ) redirect('dashboard');
//
//        $activity = $this->doctrine->em->find('file\models\TourFileActivity', $activityId);
//
//        $response['status'] = 'error';
//        $response['message'] = '';
//        if( ! $activity ) redirect('dashboard');
//
//        $fileID = $activity->getTourFile()->id();
//        if( $activity->isXoGenerated() ) {
//            $reverted_times = $activity->getRevertedTimes();
//
//
//        }
//
//
//    }

    public function mergedPreview(){
        if( $this->input->post() ){

            $post = $this->input->post();
            $fileNumber = $post['merged_file_number'];
            $xos = $post['merged_xo'];
            $data = [];
            $xoRepo = $this->doctrine->em->getRepository('file\models\TourFileActivity');

            foreach($xos as $xo) {

                $xoObj = $xoRepo->findOneBy(array('xoNumber' => $xo));

                $activityData = $this->yarsha->am->getActivityDetail($xoObj);
                $data[] = $activityData;
            }

            $tourFile = $this->doctrine->em->find('file\models\TourFile', $fileNumber);

            $logoLink = base_url().'assets/themes/yarsha/resources/images/brand.png';
            $stampLink = Options::get('config_stamp', '');
            $stampLocation = './'.substr(Options::get('config_stamp', ''), strlen(base_url()));
            $this->templatedata['logoSrc'] = $logoLink;
            $this->templatedata['stampSrc'] = $stampLink;
            $this->templatedata['data'] = $data;
            $this->templatedata['tourFile'] = $tourFile;
            $this->templatedata['merged_xos'] = $xos;
            $this->templatedata['tourFileNumber'] = $fileNumber;
            $this->templatedata['maincontent'] = 'file/template/hotel_mergedPreview';
            $this->load->theme('master', $this->templatedata);
        }else{
            redirect('exchangeOrder');
        }
    }

    public function mergedEmail(){


        if( $this->input->post() ){

            $post = $this->input->post();

            $activityID = $post['activityID'];


            if( $activityID == '' ) redirect('dashboard');

            $activity = $this->doctrine->em->find('file\models\TourFileActivity', $activityID);

            if( ! $activity ) redirect('dashboard');

            $emails = $this->input->post('emails');

            $emailCount = 0;
            $clientsEmail = array();

            if( isset($emails['agent']) and count($emails['agent']) > 0 ){
                $emailCount++;
            }

            if( isset($emails['hotel']) and count($emails['hotel']) > 0 ){
                $emailCount++;
            }

            if( isset($emails['account']) and count($emails['account']) > 0 ){
                $emailCount++;
            }

            if( isset($emails['client']) and count($emails['client']) > 0 ){
                foreach($emails['client'] as $cem){
                    if( $cem !== "" ){
                        $clientsEmail[] = $cem;
                        $emailCount++;
                    }
                }
            }

            $emails['client'] = $clientsEmail;

            if( $emailCount == 0){
                $this->message->set('Please select atleast one email before sending.', 'error', true, 'feedback');
                redirect('file/activity/detail/'.$activityID);
            }

            $mergedXos = $post['merged_xos'];
            $xoRepo = $this->doctrine->em->getRepository('file\models\TourFileActivity');
            $fileNumber = '000';
            $receivers = [];
            $activityType = '';
            $data = [];

            foreach($mergedXos as $xo) {
                $xoObj = $xoRepo->findOneBy(array('xoNumber' => $xo));
                $activityData = $this->yarsha->am->getActivityDetail($xoObj);
                $data[] = $activityData;
                $fileNumber = $activityData['fileNumber'];
                $receivers = $activityData['emailReceivers'];
                $activityType = $activityData['activityType'];
            }

            $xoPdf = './assets/uploads/to/'.$fileNumber.'_merged.pdf';

            $tourFile = $this->doctrine->em->find('file\models\TourFile', $fileNumber);

            $logoLink = base_url().'assets/themes/yarsha/resources/images/brand.png';
            $stampLink = Options::get('config_stamp', '');
            $stampLocation = './'.substr(Options::get('config_stamp', ''), strlen(base_url()));
            $this->templatedata['logoSrc'] = $logoLink;
            $this->templatedata['stampSrc'] = $stampLink;
            $this->templatedata['data'] = $data;
            $this->templatedata['tourFile'] = $tourFile;
            $this->templatedata['merged_xos'] = $mergedXos;
            $this->templatedata['tourFileNumber'] = $fileNumber;

            $emailDetails = array();


            foreach( $receivers as $rc ){

                if( isset($emails[$rc]) and count($emails[$rc]) > 0 ){
                    $this->templatedata['emailFor'] = $rc;
                    $emailBody = $this->load->theme('file/template/'.$activityType.'_mergedPreview', $this->templatedata, TRUE);
//                    \Doctrine\Common\Util\Debug::dump($emailBody);die;

                    $this->load->library('dompdf_gen');
                    $dompdf = new DOMPDF();
                    $this->templatedata['logoSrc'] = './assets/themes/yarsha/resources/images/brand.png';
                    $this->templatedata['stampSrc'] = $stampLocation;
                    $dompdf->load_html($this->load->theme('file/template/'.$activityType.'_mergedPreview', $this->templatedata, TRUE));
                    $dompdf->render();
                    $output = $dompdf->output();
                    file_put_contents($xoPdf, $output);
                    $this->templatedata['logoSrc'] = $logoLink;
                    $this->templatedata['stampSrc'] = $stampLink;


                    $emailDetails[] = array(
                        'for' => $rc,
                        'to' => implode(',', $emails[$rc]),
                        'subject' => ucfirst($rc).' Exchange Order',
                        'message' =>  $emailBody,
                    );
                }
            }

            if( isset($emails['client']) and count($emails['client']) > 0 ){
                $clientsEmail = array();
                foreach($emails['client'] as $cem){
                    if( $cem !== "" ){
                        $clientsEmail[] = $cem;
                    }
                }
            }

            $emailError = FALSE;
            $errorMessage = array();
            if( count($emailDetails) > 0 ){

                $this->load->library('email');

                foreach($emailDetails as $detail){
                    $this->email->to($detail['to']);
                    $this->email->from('noreply@yetibilling.com', "Yetibilling");
                    $this->email->subject($detail['subject']);
                    $this->email->message($detail['message']);
                    $this->email->attach($xoPdf);

                    if( ! $this->email->send() ){
                        log_message('error', $this->email->print_debugger());
                        $emailError = TRUE;
                        $errorMessage[] = $detail['for'];
                    }
                }
            }

            if( $emailError ){
                $this->message->set('Unable to send emails for '.implode(', ', $errorMessage), 'error', TRUE, 'feedback');
            }else{
                $this->message->set('Emails send successfully.', 'success', TRUE, 'feedback');
                unlink(FCPATH .'assets/uploads/to/'.$xo.'.pdf');
            }

            redirect('file/activity/detail/'.$activityID);
        }else{
            redirect('dashboard');
        }
    }

}
?>