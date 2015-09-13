<?php


class Room_Controller extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        if( !user_access('manage hotel rooms') ) redirect('dashboard');

        $this->breadcrumb->append_crumb('Hotel', site_url('hotel'));
    }

    public function index(){

        $hotelRoomCategoryRepository = $this->doctrine->em->getRepository('hotel\models\HotelRoomCategory');
        $hotelRoomTypeRepository = $this->doctrine->em->getRepository('hotel\models\HotelRoomType');
        $hotelRoomPlanRepository = $this->doctrine->em->getRepository('hotel\models\HotelRoomPlan');

        $categories = $hotelRoomCategoryRepository->findBy(
            array('status' =>1),
            array('id' =>'ASC')
        );
        $types = $hotelRoomTypeRepository->findBy(
            array('status' =>1),
            array('id' =>'ASC')
        );
        $plans = $hotelRoomPlanRepository->findBy(
            array('status' =>1),
            array('id' =>'ASC')
        );

        $this->breadcrumb->append_crumb('Rooms', site_url('hotel/room'));
        $this->templatedata['page_title'] = 'Hotel Rooms';
        $this->templatedata['categories'] = $categories;
        $this->templatedata['types'] = $types;
        $this->templatedata['plans'] = $plans;
        $this->templatedata['maincontent'] = 'hotel/room';
        $this->load->theme('master', $this->templatedata);
    }

    public function getRoomType($room_typeID = NULL ){

        $response = array();
        $data = array();

        if( $room_typeID and $room_typeID !== "" ){

            $room_type = $this->doctrine->em->find('hotel\models\HotelRoomType', $room_typeID);

            if( $room_type ){
                $data['room_type'] = $room_type;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'RoomType Not Found';
            }
        }

        $this->load->theme('hotel/roomtype/edit_room_type_template', $data);
    }

    public function saveRoomType(){
        $post = $_POST;

        $room_type = NULL;
        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'Unable to edit room type';

        if( isset($post['id']) and $post['id'] !== '' and user_access('administer hotel') ){
            $room_typeID = $post['id'];
            $room_type = $this->doctrine->em->find('hotel\models\HotelRoomType', $room_typeID);
            if( $room_type ){
                $room_type->setName($post['type']);
                $room_type->setQuantity($post['quantity']);
                $room_type->setDescription($post['description']);

                $this->doctrine->em->persist($room_type);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Hotel RoomType Updated Successfully.', 'success', TRUE, 'feedback');
                    $response['status'] = 'success';
                    $response['message'] = 'Hotel RoomType Updated Successfully.';

                }catch (\Exception $e){
                    $response['message'] = 'Unable to update hotel RoomType. '.$e->getMessage();
                }
            }
        }

        echo json_encode($response);
    }

    public function getRoomCategory($room_categoryID = NULL ){

        $response = array();
        $data = array();

        if( $room_categoryID and $room_categoryID !== "" ){

            $room_category = $this->doctrine->em->find('hotel\models\HotelRoomCategory', $room_categoryID);

            if( $room_category ){
                $data['room_category'] = $room_category;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'RoomType Not Found';
            }
        }

        $this->load->theme('hotel/roomtype/edit_room_category_template', $data);
    }

    public function getRoomPlan($room_planID = NULL ){

        $response = array();
        $data = array();

        if( $room_planID and $room_planID !== "" ){

            $room_planID = $this->doctrine->em->find('hotel\models\HotelRoomPlan', $room_planID);

            if( $room_planID ){
                $data['room_plan'] = $room_planID;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'RoomType Not Found';
            }
        }

        $this->load->theme('hotel/roomtype/edit_room_plan_template', $data);
    }

    public function saveRoomCategory(){
        $post = $_POST;

        $room_type = NULL;
        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'Unable to edit room category';

        if( isset($post['id']) and $post['id'] !== '' and user_access('administer hotel') ){
            $room_categoryID = $post['id'];
            $room_category = $this->doctrine->em->find('hotel\models\HotelRoomCategory', $room_categoryID);
            if( $room_category ){
                $room_category->setName($post['category']);
                $room_category->setDescription($post['description']);

                $this->doctrine->em->persist($room_category);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Hotel RoomCategory Updated Successfully.', 'success', TRUE, 'feedback');
                    $response['status'] = 'success';
                    $response['message'] = 'Hotel RoomCategory Updated Successfully.';

                }catch (\Exception $e){
                    $response['message'] = 'Unable to update hotel RoomCategory. '.$e->getMessage();
                }
            }
        }

        echo json_encode($response);
    }

    public function saveRoomPlan(){
        $post = $_POST;

        $room_type = NULL;
        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'Unable to edit room plan';

        if( isset($post['id']) and $post['id'] !== '' and user_access('administer hotel') ){
            $room_planID = $post['id'];
            $room_plan = $this->doctrine->em->find('hotel\models\HotelRoomPlan', $room_planID);
            if( $room_plan ){
                $room_plan->setName($post['plan']);
                $room_plan->setDescription($post['description']);

                $this->doctrine->em->persist($room_plan);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Hotel RoomPlan Updated Successfully.', 'success', TRUE, 'feedback');
                    $response['status'] = 'success';
                    $response['message'] = 'Hotel RoomPlan Updated Successfully.';

                }catch (\Exception $e){
                    $response['message'] = 'Unable to update hotel RoomPlan. '.$e->getMessage();
                }
            }
        }

        echo json_encode($response);
    }

    public function deleteRoomCategory(){
        $room_category_Id = $this->input->post('id');

        $room_category = $this->doctrine->em->find('hotel\models\HotelRoomCategory', $room_category_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if( $room_category ){
            $room_category->markAsDeleted();
            $this->doctrine->em->persist($room_category);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'Hotel RoomCategory ' . $room_category->getName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The Hotel RoomCategory "'.$room_category->getName().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Hotel RoomCategory. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'RoomCategory Not Found.';
        }

        echo json_encode($response);
    }

    public function deleteRoomType(){
        $room_type_Id = $this->input->post('id');

        $room_type = $this->doctrine->em->find('hotel\models\HotelRoomType', $room_type_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if( $room_type ){
            $room_type->markAsDeleted();
            $this->doctrine->em->persist($room_type);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'Hotel RoomType ' . $room_type->getName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The Hotel RoomType "'.$room_type->getName().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Hotel RoomType. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'RoomType Not Found.';
        }

        echo json_encode($response);
    }

    public function deleteRoomPlan(){
        $room_plan_Id = $this->input->post('id');

        $room_plan = $this->doctrine->em->find('hotel\models\HotelRoomPlan', $room_plan_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if( $room_plan ){
            $room_plan->markAsDeleted();
            $this->doctrine->em->persist($room_plan);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'Hotel RoomPlan ' . $room_plan->getName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The Hotel RoomPlan "'.$room_plan->getName().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Hotel RoomPlan. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'RoomPlan Not Found.';
        }

        echo json_encode($response);
    }

}