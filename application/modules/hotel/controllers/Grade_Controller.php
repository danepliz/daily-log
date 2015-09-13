<?php


class Grade_Controller extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb->append_crumb('Hotel', site_url('hotel'));
    }

    public function index(){

        if(!user_access('administer hotel')){ redirect('dashboard'); }

        $hotelGradeRepository = $this->doctrine->em->getRepository('hotel\models\HotelGrade');
        $hotelGrades = $hotelGradeRepository->findBy(
            array('status'=>1),
            array('id' => 'ASC')
        );

        $this->breadcrumb->append_crumb('Category', site_url('hotel/grade'));
        $this->templatedata['page_title'] = 'Hotel Grade';
        $this->templatedata['hotel_grades'] = $hotelGrades;
        $this->templatedata['maincontent'] = 'hotel/grade/list';
        $this->load->theme('master', $this->templatedata);
    }

    public function add(){

        if(!user_access('administer hotel')){ redirect('dashboard'); }

        if( $this->input->post() ){

            $this->form_validation->set_rules('name', 'Name', 'required|xss_clean|trim|callback_checkDuplicateGrade');

            $post = $this->input->post();

            if( $this->form_validation->run($this) === TRUE ){
                $gradeName = strtoupper(trim($post['name']));
                $gradeDescription = strtoupper(trim($post['description']));

                $hotelGrade = new \hotel\models\HotelGrade();
                $hotelGrade->setName($gradeName);
                $hotelGrade->setDescription($gradeDescription);

                $this->doctrine->em->persist($hotelGrade);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Hotel Grade "'.$gradeName.'" added successfully. ', 'success', TRUE, 'feedback');

                }catch (\Exception $e){
                    $this->message->set($e->getMessage(), 'error', TRUE, 'feedback');
                }
            }
            else{
                $validationError = validation_errors('<p>','</p>');
                $this->message->set($validationError, 'error', TRUE, 'feedback');
            }
        }

        redirect('hotel/grade');
    }

    public function checkDuplicateGrade($str){
        $hotelGradeRepository = $this->doctrine->em->getRepository('hotel\models\HotelGrade');
        $grade = $hotelGradeRepository->findOneBy(array('name' => $str));

        if( $grade ){
            $this->form_validation->set_message('checkDuplicateGrade', 'Hotel Grade "'.$str.'" already exists.');
            return FALSE;
        }

        return TRUE;
    }

    public function deleteHotel(){

        if(!user_access('administer hotel')){ redirect('dashboard'); }

        $grade_Id = $this->input->post('id');

        $grade = $this->doctrine->em->find('hotel\models\HotelGrade', $grade_Id);

        $response['status'] = 'error';
        $response['message'] = '';

        if( $grade ){
            $grade->markAsDeleted();
            $this->doctrine->em->persist($grade);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'HotelGrade ' . $grade->getName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The Hotel Grade "'.$grade->getName().'" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                $response['message'] = 'Unable to delete Hotel Grade. '.$e->getMessage();
            }
        }else{
            $response['message'] = 'Grade Not Found.';
        }

        echo json_encode($response);
    }

}