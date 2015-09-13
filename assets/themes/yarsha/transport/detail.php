<?php
echo loadJS(['jquery.sheepit.min.js']);
//echo loadJS(['jquery.sheepItPlugin.js'])

use transport\models\Transport;

$templateArray = [
    'details' => [
        'label' => 'Details',
        'template' => 'transport/templates/details',
        'data' => ['test' => 'nice']
    ]
//    ,

//    'contactPersons' => [
//        'label' => 'Contact Persons',
//        'template' => 'transport/templates/contact_persons',
//        'data' => []
//    ],
//    'outlets' => [
//        'label' => 'Outlets',
//        'template' => 'outlets',
//        'data' => []
//    ],
//    'transportServices' => [
//        'label' => 'Transport Services',
//        'template' => 'transport/templates/services',
//        'data' => []
//    ],

];
?>

<?php
//if($transport->hasBookingTypeRoomBasis()==Hotel::HOTEL_BOOKING_TYPE_ROOM_BASIS) {
//    $templateArray['rooms'] = [
//        'label' => 'Rooms',
//        'template' => 'hotel/templates/rooms',
//        'data' => []
//    ];
//}

//if( $hotel->getRateVariationStrategy() == Hotel::HOTEL_RATE_VARIATION_STRATEGY_SEASONAL ){
//    $templateArray['hotelSeasons'] = [
//        'label' => 'Operating Seasons',
//        'template' => 'hotel/templates/hotel_seasons',
//        'data' => []
//    ];
//}

//if($hotel->hasBookingTypePackageBasis()==Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS) {
//    $templateArray['packages'] = [
//        'label' => 'Packages',
//        'template' => 'hotel/templates/packages',
//        'data' => []
//    ];
//}

//$templateArray['rates'] = [
//    'label' => 'Rates',
//    'type' => 'link',
//    'link' => 'hotel/rate/show/'.$hotel->slug(),
//    'template' => '',
//    'data' => []
//];



$currentTab = ( isset($_GET['t']) and array_key_exists($_GET['t'], $templateArray))? $_GET['t'] : 'details';

echo getTabsTemplate($templateArray, $currentTab);






