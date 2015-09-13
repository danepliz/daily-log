<?php
namespace Yarsha\HotelRates;


interface HotelRateInterface{

    public function getTemplate();

    public function getRates();

    public function updateRates($post);


}