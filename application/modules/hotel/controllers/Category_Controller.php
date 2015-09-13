<?php


class Category_Controller extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb->append_crumb('Hotel', site_url('hotel'));
    }

    public function index(){

        if(!user_access('administer hotel')){ redirect('dashboard'); }

        $hotelCategoryRepository = $this->doctrine->em->getRepository('hotel\models\HotelCategory');
        $categories = $hotelCategoryRepository->findBy(
            array('status'=>1),
            array('id' => 'ASC')
        );

        $this->breadcrumb->append_crumb('Category', site_url('hotel/category'));
        $this->templatedata['page_title'] = 'Hotel Category';
        $this->templatedata['categories'] = $categories;
        $this->templatedata['maincontent'] = 'hotel/category/list';
        $this->load->theme('master', $this->templatedata);
    }


    public function add()
    {

        if(!user_access('administer hotel')){ redirect('dashboard'); }

        if( $this->input->post() ){

            $this->form_validation->set_rules('name', 'Name', 'required|xss_clean|trim|callback_checkDuplicateCategory');

            $post = $this->input->post();

            if( $this->form_validation->run($this) === TRUE ){
                $categoryName = strtoupper(trim($post['name']));
                $categoryDescription = strtoupper(trim($post['description']));

                $hotelCategory = new \hotel\models\HotelCategory();
                $hotelCategory->setName($categoryName);
                $hotelCategory->setDescription($categoryDescription);

                $this->doctrine->em->persist($hotelCategory);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Hotel Category "'.$categoryName.'" added successfully. ', 'success', TRUE, 'feedback');

                }catch (\Exception $e){
                    $this->message->set($e->getMessage(), 'error', TRUE, 'feedback');
                }
            }
            else{
                $validationError = validation_errors('<p>','</p>');
                $this->message->set($validationError, 'error', TRUE, 'feedback');
            }
        }

        redirect('hotel/category');
    }

    public function checkDuplicateCategory($str){
        $hotelCategoryRepository = $this->doctrine->em->getRepository('hotel\models\HotelCategory');
        $category = $hotelCategoryRepository->findOneBy(array('name' => $str));

        if( $category ){
            $this->form_validation->set_message('checkDuplicateCategory', 'Hotel Category "'.$str.'" already exists.');
            return FALSE;
        }

        return TRUE;
    }

    public function deleteHotel(){

        if(!user_access('administer hotel')){ redirect('dashboard'); }

        $category_Id = $this->input->post('id');

        $category = $this->doctrine->em->find('hotel\models\HotelCategory', $category_Id);
        $response['status'] = 'error';
        $response['message'] = '';

        if( $category ){
            $category->markAsDeleted();
            $this->doctrine->em->persist($category);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'HotelCategory ' . $category->getName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The Hotel Category "'.$category->getName().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Hotel Category. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'Category Not Found.';
        }

        echo json_encode($response);
    }

}