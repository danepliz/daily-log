<?php

use hotel\models\HotelCategory;
use hotel\models\Hotel;

class Ajax_Controller extends Xhr{

    /**
     * @param string $type [A = add, E = edit]
     * @param string $format [html/json]
     * @param null $personID [personID]
     */
    public function getPersonForm($personID = NULL ){

        $response = array();
        $data = array();

        if( $personID and $personID !== "" ){

            $person = $this->doctrine->em->find('hotel\models\HotelContactPerson', $personID);

            if( $person ){
                $data['person'] = $person;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Contact Person Not Found';
            }
        }

        $this->load->theme('common/xhrtemplates/hotel_contact_person_form', $data);
    }

    public function savePerson($hotelId, $personId = NULL){
        $post = $_POST;

        $person = NULL;
        $response = array();
        $isEditing = FALSE;


        if( !is_null($personId) and $personId !== "" and $personId !== '0' ){
            $person = $this->doctrine->em->find('hotel\models\HotelContactPerson', $personId);
            $isEditing = TRUE;
        }else{
            $person = new \hotel\models\HotelContactPerson();
        }

        $person->setName(trim($post['name']));
        $person->setAddress(trim($post['address']));
        $person->setDesignation(trim($post['designation']));
        $person->setSkype(trim($post['skype']));

        $phones = array($post['phone1']);
        $emails = array($post['email1']);

        $person->setHotel($this->doctrine->em->find('hotel\models\Hotel', $hotelId));

        if( isset($post['phone2']) and trim($post['phone2']) !== "" ) $phones[] = $post['phone2'];
        if( isset($post['email2']) and trim($post['email2']) !== "" ) $emails[] = $post['email2'];

        $person->setPhones($phones);
        $person->setEmails($emails);

        $this->doctrine->em->persist($person);

        try{
            $this->doctrine->em->flush();

//                $pEmails = $person->getEmails();
//                $pEmailsArr = [];
//                foreach($pEmails as $pe){
//                    $pEmailsArr[] = '<a href="mailto:'.$pe.'">'.$pe.'</a>';
//                }
//
//                $trOpen = '<tr id="cp-data-'.$person->id().'">';
//                $tdData = '<td>'.$person->getName().'</td>';
//                $tdData .= '<td>'.$person->getDesignation().'</td>';
//                $tdData .= '<td>'.$person->getAddress().'</td>';
//                $tdData .= '<td>'.$person->getSkype().'</td>';
//                $tdData .= '<td>'.implode("<br /> ", $person->getPhones()).'</td>';
//                $tdData .= '<td>'.implode("<br />", $pEmailsArr).'</td>';
//                $tdData .= '<td>';
//                $tdData .= (user_access('edit hotel contact persons'))? action_button('edit', '#', array('class'=>"edit-contact-person",'data-person-id' => $person->id(), 'data-toggle' => 'modal', 'data-target' => '#contactPersonsForm', 'data-form-type'=>'E')) : '';
////                $tdData .= (user_access('delete hotel contact persons'))? action_button('delete', '#', array('class'=>"delete-contact-person")) : '';
//                $tdData .= '</td>';
//                $trClose = '</tr>';

            $response['status'] = 'success';
            $response['message'] = ($isEditing) ? 'Contact Updated Successfully.' : 'Contact Added Successfully.' ;
//                $response['data'] = array(
//                    'row_id' => 'cp-data-'.$person->id(),
//                    'table_data' => ($isEditing)? $tdData : $trOpen.$tdData.$trClose
//                );

        }catch(\Exception $e){
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
            $response['data'] = '';
        }
//        }

        echo json_encode($response);
    }

    public function getOutletsForm($outletID = NULL ){

        $response = array();
        $data = array();

        if( $outletID and $outletID !== "" ){

            $outlet = $this->doctrine->em->find('hotel\models\HotelOutlet', $outletID);

            if( $outlet ){
                $data['outlet'] = $outlet;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Outlet Not Found';
            }
        }

        $this->load->theme('common/xhrtemplates/hotel_outlet_form', $data);
    }

    public function saveOutlet($hotelId, $outletId = NULL){

        $post = $_POST;
        $outlet = NULL;
        $response = array();
        $isEditing = FALSE;
        if( !is_null($outletId) and $outletId !== "" and $outletId !== '0' ){
            $outlet = $this->doctrine->em->find('hotel\models\HotelOutlet', $outletId);
            $isEditing = TRUE;
        }else{
            $outlet = new \hotel\models\HotelOutlet();
        }

        $outlet->setName(trim($post['name']));
        $outlet->setDescription(trim($post['description']));
        $outlet->setHotel($this->doctrine->em->find('hotel\models\Hotel', $hotelId));
        $this->doctrine->em->persist($outlet);

        try{
            $this->doctrine->em->flush();
            $response['status'] = 'success';
            $response['message'] = ($isEditing) ? 'Outlet Updated Successfully.' : 'Outlet Added Successfully.' ;

        }catch(\Exception $e){
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
            $response['data'] = '';
        }
        echo json_encode($response);
    }

    public function deleteOutlet(){
        $outlet_Id = $this->input->post('id');

        $outlet = $this->doctrine->em->find('hotel\models\HotelOutlet', $outlet_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if( $outlet ){
            $outlet->markAsInactive();
            $this->doctrine->em->persist($outlet);
            try {
                $this->doctrine->em->flush();
                log_message('info', 'Hotel Outlet' . $outlet->getName() . ' marked as Inactive');
                $response['status'] = 'success';
                $response['message'] = 'The Hotel Outlet "'.$outlet->getName().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Hotel Outlet. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'Outlet Not Found.';
        }

        echo json_encode($response);
    }

    public function getServiceForms($serviceID = NULL ){

        $response = array();
        $data = array();

        if( $serviceID and $serviceID !== "" ){

            $service = $this->doctrine->em->find('hotel\models\HotelServices', $serviceID);

            if( $service ){
                $data['service'] = $service;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Service Not Found';
            }
        }

        $this->load->theme('common/xhrtemplates/hotel_service_form', $data);
    }


//    public function saveService(){
//        $post = $_POST;
//
//        $service = NULL;
//        $response = array();
//        $response['status'] = 'error';
//        $response['message'] = 'Unable to edit service';
//
//        if( isset($post['id']) and $post['id'] !== '' and user_access('administer hotel') ){
//
//
//            $isDuplicate = FALSE;
//
//            $serviceRepo = $this->doctrine->em->getRepository('hotel\models\HotelServices');
//            if(strtolower($post['name']) != strtolower($post['name_old']))
//            {
//                $oldService = $serviceRepo->findBy(array('name'=>$post['name']));
//                $isDuplicate = count($oldService)? TRUE : FALSE;
//            }
//
//            if( ! $isDuplicate ){
//                $serviceID = $post['id'];
//                $service = $serviceRepo->find($serviceID);
//
//                if( $service ){
//                    $service->setName($post['name']);
//                    $service->setDescription($post['description']);
//
//                    $this->doctrine->em->persist($service);
//
//                    try{
//                        $this->doctrine->em->flush();
//                        $this->message->set('Hotel Service Updated Successfully.', 'success', TRUE, 'feedback');
//                        $response['status'] = 'success';
//                        $response['message'] = 'Hotel Service Updated Successfully.';
//
//                    }catch (\Exception $e){
//                        $response['message'] = 'Unable to update hotel service. '.$e->getMessage();
//                    }
//                }
//            }else{
//                $response['status'] = 'error';
//                $response['message'] = 'Service with provided service name already exists.';
//            }
//
//        }
//
//        echo json_encode($response);
//    }

    public function saveService($hotelId, $serviceId = NULL){

        $post = $_POST;
        $outlet = NULL;
        $response = array();
        $isEditing = FALSE;
        if( !is_null($serviceId) and $serviceId !== "" and $serviceId !== '0' ){
            $service = $this->doctrine->em->find('hotel\models\HotelServices', $serviceId);
            $isEditing = TRUE;
        }else{
            $service = new \hotel\models\HotelServices();
        }

        $service->setName(trim($post['name']));
        $service->setDescription(trim($post['description']));
        $service->setHotel($this->doctrine->em->find('hotel\models\Hotel', $hotelId));
        $this->doctrine->em->persist($service);

        try{
            $this->doctrine->em->flush();
            $response['status'] = 'success';
            $response['message'] = ($isEditing) ? 'service Updated Successfully.' : 'Service Added Successfully.' ;

        }catch(\Exception $e){
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
            $response['data'] = '';
        }
        echo json_encode($response);
    }

    public function deleteService(){
        $service_Id = $this->input->post('id');

        $service = $this->doctrine->em->find('hotel\models\HotelServices', $service_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if( $service ){
            $service->markAsInactive();
            $this->doctrine->em->persist($service);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'Hotel Service' . $service->getName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The Hotel service "'.$service->getName().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Hotel Service. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'Hotel Service Not Found.';
        }

        echo json_encode($response);
    }


    public function getDateRangeForm($seasonsID, $hotelID){
        $response = array();
        $data = array();

        $seasons = $this->doctrine->em->find('hotel\models\HotelSeason', $seasonsID);
        if ($seasons) {
            $data['season'] = $seasons;
            $data['hotelID'] = $hotelID;
            $response['status'] = 'success';
            $response['message'] = 'Success';
            $response['html'] = $this->load->theme('common/xhrtemplates/date_range_form', $data, true);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Season Not Found';
        }
        echo json_encode($response);
    }

    public function saveDateRanges(){
        $post = $_POST;

        $seasonID = $post['season'];
        $hotel = $post['hotel'];
        $dateRanges = $post['date_range'];

        $season = $this->doctrine->em->find('hotel\models\HotelSeason', $seasonID);

        if( count( $dateRanges ) ){

            $rangeRepo = $this->doctrine->em->getRepository('hotel\models\HotelSeasonDateRange');

            foreach($dateRanges as $dr){
                $rangeId = $dr['id'];
                $fromDay = $dr['fromDay'];
                $fromMonth = $dr['fromMonth'];
                $toDay = $dr['toDay'];
                $toMonth = $dr['toMonth'];

                $startDate = new DateTime('0000-'.$fromMonth.'-'.$fromDay);
                $endDate = new DateTime('0000-'.$toMonth.'-'.$toDay);

                $dateRange = ( $rangeId != '' )? $rangeRepo->find($rangeId) : NULL;

                $dateRange = ( !is_null($dateRange) )? $dateRange : new \hotel\models\HotelSeasonDateRange();
                $dateRange->setFromDate($startDate);
                $dateRange->setSeason($season);
                $dateRange->setToDate($endDate);
                $this->doctrine->em->persist($dateRange);

                $season->addDateRange($dateRange);

            }

            $this->doctrine->em->persist($season);

            try{
                $this->doctrine->em->flush();
                $this->message->set('Date Ranges Updated successfully', 'success', true, 'feedback');
                $response['status'] = 'success';
                $response['message'] = 'Date Ranges Updated successfully';
            }catch (\Exception $e){
                $response['status'] = 'error';
                $response['message'] = 'Unable to update date ranges. '.$e->getMessage();
            }
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong.';
        }

        echo json_encode($response);
    }


    public function deleteContactPerson(){
        $contact_person_Id = $this->input->post('id');

        $contact_person = $this->doctrine->em->find('hotel\models\HotelContactPerson', $contact_person_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if( $contact_person ){
            $contact_person->markAsDeleted();
            $this->doctrine->em->persist($contact_person);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'Hotel Contact Person' . $contact_person->getName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The Hotel Contact Person "'.$contact_person->getName().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Hotel Contact Person. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'Contact Person Not Found.';
        }

        echo json_encode($response);
    }


    public function deleteSeason()
    {
        if(!user_access('administer hotel'))	redirect('dashboard');


        $season_Id = $this->input->post('id');
//        show_pre($season_Id);die();
        $season = $this->doctrine->em->find('hotel\models\HotelSeason', $season_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if ($season) {
            $season->markAsDeleted();
            $this->doctrine->em->persist($season);
//            $this->doctrine->em->remove($season);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'Season ' . $season->getName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The season "' . $season->getName() . '" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Season. ' . $e->getMessage();
            }
        } else {
            $response['message'] = 'Service Not Found.';
        }

        echo json_encode($response);
    }

    public function addRoomCategory(){
        $post = $_POST;

        $name = strtoupper(trim($post['name']));
        $description = strtoupper(trim($post['description']));

        $roomCategory = new \hotel\models\HotelRoomCategory();
        $roomCategory->setName($name);
        $roomCategory->setDescription($description);

        $this->doctrine->em->persist($roomCategory);

        $response = array();

        try{
            $this->doctrine->em->flush();

            $response['status'] = 'success';
            $response['message'] = 'Room Category Added Successfully.';
            $response['data'] = array(
                [
                    'name' => $name,
                    'description' => $description,
                    'id' => $roomCategory->id(),
                    'dataTarget' => '#roomcategoryForm',
                    'dataRoomDesc' => 'data-room-category-id',
                    'dataBB' => 'custom_delete_category'
                ]
            );
        }catch(\Exception $e){
            $response['status'] = 'error';
            $response['message'] = 'Unable to add room category. '.$e->getMessage();
            $response['data'] = array();
        }

        echo json_encode($response);

    }

    public function addRoomType(){
        $post = $_POST;

        $name = strtoupper(trim($post['name']));
        $quantity = trim($post['quantity']);
        $description = strtoupper(trim($post['description']));

        if( $name == "" or $quantity == "" or !is_numeric($quantity) ){
            $response['status'] = 'error';
            $response['message'] = 'Please Make sure you have completed form with proper value.';
            $response['data'] = '';
        }else{
            $roomType = new \hotel\models\HotelRoomType();
            $roomType->setName($name);
            $roomType->setDescription($description);
            $roomType->setQuantity($quantity);

            $this->doctrine->em->persist($roomType);

            $response = array();

            try{
                $this->doctrine->em->flush();

                $response['status'] = 'success';
                $response['message'] = 'Room Category Added Successfully.';
                $response['data'] = array(
                    [
                        'name' => $name,
                        'quantity' => $quantity,
                        'description' => $description,
                        'id' => $roomType->id(),
                        'dataTarget' => '#roomtypeForm',
                        'dataRoomDesc' => 'data-room-type-id',
                        'dataBB' => 'custom_delete_type'
                    ]
                );
            }catch(\Exception $e){
                $response['status'] = 'error';
                $response['message'] = 'Unable to add room category. '.$e->getMessage();
                $response['data'] = array();
            }
        }

        echo json_encode($response);

    }

    public function addRoomPlan(){
        $post = $_POST;

        $name = strtoupper(trim($post['name']));
        $description = strtoupper(trim($post['description']));

        $roomPlan = new \hotel\models\HotelRoomPlan();
        $roomPlan->setName($name);
        $roomPlan->setDescription($description);

        $this->doctrine->em->persist($roomPlan);

        $response = array();

        try{
            $this->doctrine->em->flush();

            $response['status'] = 'success';
            $response['message'] = 'Room Plan Added Successfully.';
            $response['data'] = array(
                [
                    'name' => $name,
                    'description' => $description,
                    'id' => $roomPlan->id(),
                    'dataTarget' => '#roomplanForm',
                    'dataRoomDesc' => 'data-room-plan-id',
                    'dataBB' => 'custom_delete_plan'
                ]
            );
        }catch(\Exception $e){
            $response['status'] = 'error';
            $response['message'] = 'Unable to add room plan. '.$e->getMessage();
            $response['data'] = array();
        }

        echo json_encode($response);

    }

    public function getCategoryForm($categoryID = NULL ){

        $response = array();
        $data = array();

        if( $categoryID and $categoryID !== "" ){

            $category = $this->doctrine->em->find('hotel\models\HotelCategory', $categoryID);

            if( $category ){
                $data['category'] = $category;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Category Not Found';
            }
        }

        $this->load->theme('hotel/category/edit_category_template', $data);
    }

    public function saveCategory(){
        $post = $_POST;

        $category = NULL;
        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'Unable to edit category';

        if( isset($post['id']) and $post['id'] !== '' and user_access('administer hotel') ){
            $categoryID = $post['id'];
            $category = $this->doctrine->em->find('hotel\models\HotelCategory', $categoryID);
            if( $category ){
                $category->setName($post['name']);
                $category->setDescription($post['description']);

                $this->doctrine->em->persist($category);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Hotel Category Updated Successfully.', 'success', TRUE, 'feedback');
                    $response['status'] = 'success';
                    $response['message'] = 'Hotel Category Updated Successfully.';

                }catch (\Exception $e){
                    $response['message'] = 'Unable to update hotel category. '.$e->getMessage();
                }
            }
        }

        echo json_encode($response);
    }

//    public function getServiceForm($serviceID = NULL ){
//
//        $response = array();
//        $data = array();
//
//        if( $serviceID and $serviceID !== "" ){
//
//            $service = $this->doctrine->em->find('hotel\models\HotelServices', $serviceID);
//
//            if( $service ){
//                $data['service'] = $service;
//            }else{
//                $response['status'] = 'failure';
//                $response['message'] = 'Service Not Found';
//            }
//        }
//
//        $this->load->theme('hotel/service/edit_service_template', $data);
//    }
    public function getSeasonEditForm($seasonID = NULL ){

        $response = array();
        $data = array();

        if( $seasonID and $seasonID !== "" ){

            $season = $this->doctrine->em->find('hotel\models\HotelSeason', $seasonID);

            if( $season ){
                $data['season'] = $season;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Service Not Found';
            }
        }

        $this->load->theme('hotel/templates/edit_season_template', $data);
    }


    public function getGradeForm($gradeID = NULL ){

        $response = array();
        $data = array();

        if( $gradeID and $gradeID !== "" ){

            $grade = $this->doctrine->em->find('hotel\models\HotelGrade', $gradeID);

            if( $grade ){
                $data['grade'] = $grade;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Hotel grade Not Found';
            }
        }

        $this->load->theme('hotel/grade/edit_grade_template', $data);
    }

    public function saveGrade(){
        $post = $_POST;

        $grade = NULL;
        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'Unable to edit hotel grade';

        if( isset($post['id']) and $post['id'] !== '' and user_access('administer hotel') ){
            $gradeID = $post['id'];
            $grade = $this->doctrine->em->find('hotel\models\HotelGrade', $gradeID);
            if( $grade ){
                $grade->setName($post['name']);
                $grade->setDescription($post['description']);

                $this->doctrine->em->persist($grade);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Hotel Grade Updated Successfully.', 'success', TRUE, 'feedback');
                    $response['status'] = 'success';
                    $response['message'] = 'Hotel Grade Updated Successfully.';

                }catch (\Exception $e){
                    $response['message'] = 'Unable to update hotel grade. '.$e->getMessage();
                }
            }
        }

        echo json_encode($response);
    }

    public function getHotelsSelectByCategory($category, $selected = ''){
        $hotelRepository = $this->doctrine->em->getRepository('hotel\models\Hotel');
        $filter['status'] = Hotel::HOTEL_STATUS_ACTIVE;
        if( $category == HotelCategory::HOTEL_CATEGORY_RESTAURANT ){
            $filter['category'] = 1;
        }
        $hotels = $hotelRepository->listHotels(NULL, NULL, $filter);

        $options = '<option value="">-- CHOOSE PROPERTY --</option>';

        if( count($hotels) ){
            foreach($hotels as $hotel){
                $selectedVal = ( $selected == $hotel->id() )? 'selected="selected"' : '';
                $options .= '<option value="'.$hotel->id().'" '.$selectedVal.'> '.$hotel->getName().' </option>';
            }
        }
        $response['status'] = 'success';
        $response['options'] = $options;

        echo json_encode($response);

    }

    public function getOutletsByHotel($hotelID){
        $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelID);

        $outletOptions = '<option value="">-- OUTLET --</option>';
        $serviceOptions = '<option value="">-- SERVICE --</option>';

        if( $hotel ){
            $outlets = $hotel->getOutlets();
            if( count($outlets) ){
                foreach($outlets as $outlet){
                    if( ! $outlet->isActive() ) continue;
                    $outletOptions .= '<option value="'.$outlet->id().'"> '.$outlet->getName().' </option>';
                }
            }

            $services = $hotel->getServices();
            if( count($services) ){
                foreach($services as $service){
                    if( ! $service->isActive() ) continue;
                    $serviceOptions .= '<option value="'.$service->id().'"> '.$service->getName().' </option>';
                }
            }
        }

        $response['status'] = 'success';
        $response['options']['outlets'] = $outletOptions;
        $response['options']['services'] = $serviceOptions;

        echo json_encode($response);
    }

    public function getMarketsByHotelRate(){
        $filters = [];
        if( isset($_GET['type']) ){ $filters['type'] = $_GET['type']; }
        if( isset($_GET['strategy']) ){ $filters['strategy'] = $_GET['strategy']; }
        if( isset($_GET['hotel']) ){ $filters['hotel'] = $_GET['hotel']; }

        $rateRepo = $this->doctrine->em->getRepository('hotel\models\Rate');
        $markets = $rateRepo->getMarketByHotelRates($filters);

        $options = '<option value="">-- SELECT MARKET --</option>';

        if( count($markets) ){
            foreach($markets as $m){
                $options .= '<option value="'.$m['marketID'].'">'.$m['market'].'( '.$m['currency'].' ) </option>';
            }
        }

        echo json_encode([
            'status' => 'success',
            'options' => $options
        ]);

    }
}