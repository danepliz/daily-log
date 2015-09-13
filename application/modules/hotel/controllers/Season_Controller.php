<?php

use hotel\models\HotelSeasonDateRange;
use hotel\models\HotelSeason;



class Season_Controller extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb->append_crumb('Hotel', site_url('hotel'));

    }


    public function addSeason()
    {

        if (!user_access('administer hotel')) {
            redirect('dashboard');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Name', 'required|xss_clean|trim');
            $post = $this->input->post();
            $hotelID = $post['hotel'];
            $hotel = $this->doctrine->em->find('hotel\models\Hotel', $hotelID);
            if ($this->form_validation->run($this) === TRUE) {
                $seasonName = strtoupper(trim($post['name']));
                $newSeason = new HotelSeason();
                $newSeason->setName($seasonName);
                $newSeason->setHotel($hotel);
                $this->doctrine->em->persist($newSeason);
                try {
                    $this->doctrine->em->flush();
                    $this->message->set('Season "' . $seasonName . '" added successfully. ', 'success', TRUE, 'feedback');

                } catch (\Exception $e) {
                    $this->message->set($e->getMessage(), 'error', TRUE, 'feedback');
                }
            } else {
                $validationError = validation_errors('<p>', '</p>');
                $this->message->set($validationError, 'error', TRUE, 'feedback');
            }
        }
        redirect('hotel/detail/' . $hotel->slug() . '?t=hotelSeasons');

    }






}


