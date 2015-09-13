<?php
use file\models\TourFileActivity;
use hotel\models\Hotel;
use hotel\models\HotelPackage;

$arrivalDate =  $departureDate = '';
$hotelID = $mode_type = $dep_mod_type = NULL;
$details = $activityDetailsString =  $estimatedArrivalTime =  $estimatedDepartureTime =  $confirmationNumber= $numberOfNights = $arrivalDepartureNote = '';
//$market = $file->getMarket()? $file->getMarket()->id() : '';
$market = '';
$activityDetailsTemplates = array();
$activityDetails = array();
$activityID = $arrivalNote = $departureNote = $currency = $bookingType = '';
$numberOfBedsChoosen = 0;
$numberOfPax = $file->getNumberOfPax();
$extraBeds = \Options::get('config_extrabeds', 4);
$allowedBeds = $numberOfPax + $extraBeds;

//echo $allowedBeds.' beds allowed';

$showMargins = user_access('view update margins')? '' : 'hide';

if( isset($activity) and !is_null($activity)){
    $tourFile = $activity->getTourFile();
    $arrivalDate = ($activity->getArrivalDate())? $activity->getArrivalDate()->format('Y-m-d') : '';
    $departureDate = ($activity->getDepartureDate())? $activity->getDepartureDate()->format('Y-m-d') : '';
    $hotel = $activity->getHotel();
    $hotelID = ($activity->getHotel())? $activity->getHotel()->id() : NULL;
    $details = $activity->getDescription();
    $activityID = $activity->id();
    $currency = ($activity->getCurrency())? $activity->getCurrency()->id() : '';
    $market = ($activity->getMarket())? $activity->getMarket()->id() : '';

//    $activityDetails = $activity->getDetails();
    $activityDetails = $activity_details;

    $estimatedArrivalTime = $activity->getArrivalTime()? $activity->getArrivalTime()->format('H:i') : '';
    $estimatedDepartureTime = $activity->getDepartureTime()? $activity->getDepartureTime()->format('H:i') : '';
    $confirmationNumber= $activity->getConfirmationNumber();
    $numberOfNights = $activity->getNumberOfNights();
    $arrivalDepartureNote = $activity->getArrivalDepartureNote();
    $arrivalNote = $activity->getArrivalNote();
    $departureNote = $activity->getDepartureNote();
    $mode_type = $activity->getArrivalMode();
    $dep_mod_type = $activity->getDepartureMode();
    $bookingType = $activity->getBookingType();

    $hotelPackages = $hotel->getPackages();


    $pckArr['main'] = [];
    $pckArr['extra'] = [];

    if( count($hotelPackages)  ){
        foreach($hotelPackages as $hp){
            if( $hp->getType() == \hotel\models\HotelPackage::PACKAGE_TYPE_MAIN ){
                $pckArr['main'][$hp->id()] = $hp->getName();
            }else{
                $pckArr['extra'][$hp->id()] = $hp->getName();
            }
        }
    }

}

$arrivalModes = TourFileActivity::$arrival_modes;

?>

<div class="row"><div class="col-md-12"><div class="panel panel-default"><div class="panel-body">
<form method="post" action="" class="validate" role="form" id="hotelActivityForm">
<div id ="alertarea"></div>

    <div class="col-md-3">
        <label>Arrival Mode</label>
        <?php echo form_dropdown('mode_type', $arrivalModes, $mode_type, 'id="mode_type" class="form-control"'); ?>
    </div>
<?php
echo inputWrapper('arrival_date', 'Arrival Date', $arrivalDate, 'class="form-control datepicker required" placeholder="arrival date" id="arrivalDate" onchange="clearAmount(null)"',  'col-md-3');
echo inputWrapper('estimatedArrivalTime','Estimated Time Of Arrival', $estimatedArrivalTime, 'placeholder="arrival time" class="form-control timeOnly" id="estimatedArrivalTime"', 'col-md-3');
echo inputWrapper('arrivalNote', 'Arrival Flight Details(if available)', $arrivalNote, 'placeholder="arrival flight details" class="form-control" id="arrivalNote"', 'col-md-3');
echo clearDiv();?>


<div class="col-md-3">
    <label>Departure Mode</label>
    <?php echo form_dropdown('departure_mode', $arrivalModes, $dep_mod_type, 'id="departure_mode" class="form-control"'); ?>
</div>

<?php
echo inputWrapper('departure_date', 'Departure Date', $departureDate, 'class="form-control datepicker required" placeholder="departure date" id="departureDate"  onchange="clearAmount(null)"', 'col-md-3');
echo inputWrapper('estimatedDepartureTime','Estimated Time Of Departure', $estimatedDepartureTime, 'placeholder="departure time" class="form-control timeOnly" id="estimatedDepartureTime"', 'col-md-3');
echo inputWrapper('departureNote', 'Departure Flight Details (if available)', $departureNote, 'placeholder="departure flight details" class="form-control" id="departureNote"', 'col-md-3');
echo clearDiv();
?>
<div class="form-group-sm col-md-3">
    <label for="hotel">Hotel</label>
    <?php getHotelSelectionElementForXo('hotel', $hotelID, 'class="form-control required" id="hotel"', 'id="hotel') ?>
</div>

<div class="form-group-sm col-md-3">
    <label for="bookingType">Booking Type</label>
    <?php echo form_dropdown('bookingType', ['' => '-- BOOKING TYPE --'], NULL, 'class="form-control required" id="bookingType"'); ?>
</div>

<div class="form-group-sm col-md-3">
    <label for="market">Market</label>
    <?php getMarketSelectionElementForXo('market', $market, 'class="form-control required" id="market"') ?>
</div>


<?php
echo inputWrapper('confirmationNumber','Hotel Confirmation Number', $confirmationNumber, 'placeholder="if available" class="form-control" id="confirmationNumber"', 'col-md-3');
echo clearDiv();
?>

<div class="form-group-sm col-md-12">

    <div class="col-md-12" id="activity">

        <div class="row">
            <div class="col-md-1 no-margin room_basis"><label>Rooms</label></div>
            <div class="col-md-1 no-margin package_basis"><label>Qty</label></div>
            <div class="col-md-2 no-margin room_basis"><label>Category</label></div>
            <div class="col-md-1 no-margin room_basis"><label>Type</label></div>
            <div class="col-md-1 no-margin room_basis"><label>Alt Name</label></div>
            <div class="col-md-1 no-margin room_basis"><label>Plan</label></div>
            <div class="col-md-1 no-margin room_basis"><label>Extra Bed</label></div>
            <div class="col-md-6 no-margin package_basis"><label>Package</label></div>
            <div class="col-md-5 no-margin">
                <div class="col-md-1 no-margin">&nbsp;</div>
                <div class="col-md-4 no-margin"><label>Payable Amount</label></div>
                <div class="col-md-2 no-margin <?php echo $showMargins ?>"><label>Margin</label></div>
                <div class="col-md-3 no-margin <?php echo $showMargins ?>" ><label>Billing Amount</label></div>
            </div>
        </div>

        <div class="clear"></div>

        <div class="hotelActivityWrapper" id="activity_template" style="position:relative; float:left;  width:100%">
            <div class="col-md-1 no-margin"><input type="text" class="form-control required number_only quantity" id="quantity_MAIN_INDEX" onkeypress="return clearAmount(this, 'MAIN_INDEX', '')" name="detail[MAIN_INDEX][quantity]" /></div>
            <div class="col-md-2 no-margin room_basis"><?php echo form_dropdown('detail[MAIN_INDEX][category]', array('' => '-- CATEGORY --'), NULL, 'class="form-control category" id="category_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX)"') ?></div>
            <div class="col-md-1 no-margin room_basis"><?php echo form_dropdown('detail[MAIN_INDEX][type]', array('' => '-- TYPE --'), NULL, 'class="form-control type" id="type_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX)"') ?></div>
            <div class="col-md-1 no-margin room_basis">
                <input type="text" name="detail[MAIN_INDEX][roomTypeNickName]" class="form-control nickName" id="nickName_MAIN_INDEX" />
            </div>
            <div class="col-md-1 no-margin room_basis"><?php echo form_dropdown('detail[MAIN_INDEX][plan]', array('' => '-- PLAN --'), NULL, 'class="form-control plan" id="plan_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX )"') ?></div>
            <div class="col-md-1 no-margin room_basis"><input type="text" class="form-control number_only extraBed" id="extraBed_MAIN_INDEX" onkeypress="return clearAmount(this, 'MAIN_INDEX', '')" name="detail[MAIN_INDEX][extraBed]" /></div>
            <div class="col-md-6 no-margin package_basis"><?php echo form_dropdown('detail[MAIN_INDEX][package]', array('' => '-- PACKAGE --'), NULL, 'class="form-control packageMain" id="package_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX)"') ?></div>
            <div class="col-md-5 no-margin marginWrapper">

                <div class="col-md-1 no-margin"><span id="calculate_MAIN_INDEX" onclick="return getRate(this, 'MAIN_INDEX', '')"><i title="calculate" class="fa fa-calculator"></i></span></div>
                <div class="col-md-3 no-margin"><input type="text" disabled name="detail[MAIN_INDEX][payableAmountH]" id="payableAmount_MAIN_INDEX" class="form-control payableAmount" /></div>
                <div class="col-md-3 no-margin <?php echo $showMargins ?>">
                    <div class="input-group">
                        <input type="text" name="detail[MAIN_INDEX][margin]" class="form-control percent pmargin marginValue"  id="margin_MAIN_INDEX"  value="" placeholder="margin" onkeyup="calculateBillingRate(this)"/>
                        <span class="input-group-addon">%</span>
                    </div>
                </div>
                <div class="col-md-3 no-margin <?php echo $showMargins ?>"><input type="text" onkeyup="calculateMargin(this)" name="detail[MAIN_INDEX][billingAmountH]" id="billingAmount_MAIN_INDEX" class="form-control billingAmount" /></div>

                <input type="hidden" name="detail[MAIN_INDEX][activityDetailID]" class="activityDetailID" id="activityDetailID_MAIN_INDEX" value="">
                <input type="hidden" name="detail[MAIN_INDEX][actualRate]" id="actualRate_MAIN_INDEX" value="" class="actualRate">
                <input type="hidden" name="detail[MAIN_INDEX][actualRateDetail]" id="actualRateDetail_MAIN_INDEX" value="" class="actualRateDetail">
                <input type="hidden" name="detail[MAIN_INDEX][payableAmount]" id="payableAmount_h_MAIN_INDEX" class="payableAmountHidden payableAmount" />
                <input type="hidden" name="detail[MAIN_INDEX][billingAmount]" id="billingAmount_h_MAIN_INDEX" class="billingAmountHidden billingAmount" />
                <input type="hidden" name="detail[MAIN_INDEX][paymentStrategy]" id="paymentStrategy_MAIN_INDEX" class="paymentStrategyHidden" />
                <input type="hidden" name="detail[MAIN_INDEX][paymentStrategyPercent]" id="paymentStrategyPercent_MAIN_INDEX" class="paymentStrategyPercentHidden" />

            </div>

            <div class="package_basis" id="activity_MAIN_INDEX_extra">
                <div class="" id="activity_MAIN_INDEX_extra_template" style="position:relative; float:left; width:100%">
                    <div class="col-md-1 no-margin"><input type="text" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][quantity]" class="form-control quantityExtra" id="quantity_MAIN_INDEX_EXTRA_INDEX" onchange="return clearAmount(this, 'MAIN_INDEX', 'EXTRA_INDEX')" /></div>
                    <div class="col-md-6 no-margin"><?php echo form_dropdown('detail[MAIN_INDEX][extra][EXTRA_INDEX][package]', [''=>'-- EXTRA --'],NULL, 'class="form-control packageExtra" id="package_MAIN_INDEX_EXTRA_INDEX" onchange="return clearAmount(this, MAIN_INDEX, EXTRA_INDEX)"')?></div>
                    <div class="col-md-5 no-margin marginWrapper">
                        <div class="col-md-1 no-margin"><span id="calculate_MAIN_INDEX_EXTRA_INDEX" onclick="return getRate(this, 'MAIN_INDEX', 'EXTRA_INDEX')"><i title="calculate" class="fa fa-calculator calculateExtra"></i></span></div>
                        <div class="col-md-3 no-margin"><input type="text" disabled name="detail[MAIN_INDEX][extra][EXTRA_INDEX][payableAmountH]" id="payableAmount_MAIN_INDEX_EXTRA_INDEX" class="form-control payableAmount" /></div>
                        <div class="col-md-3 no-margin <?php echo $showMargins ?>">
                            <div class="input-group">
                                <input type="text" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][margin]" class="form-control percent pmargin marginValue"  id="margin_MAIN_INDEX_EXTRA_INDEX"  value="" placeholder="margin" onkeyup="calculateBillingRate(this)"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-3 no-margin <?php echo $showMargins ?>"><input type="text" onkeyup="calculateMargin(this)" name="detail[MAIN_INDEX][billingAmountH]" id="billingAmount_MAIN_INDEX_EXTRA_INDEX" class="form-control billingAmount" /></div>

                        <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][activityDetailID]" class="activityDetailID" id="activityDetailID_MAIN_INDEX_EXTRA_INDEX" value="">
                        <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][actualRate]" id="actualRate_MAIN_INDEX_EXTRA_INDEX" value="" class="actualRate">
                        <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][actualRateDetail]" id="actualRateDetail_MAIN_INDEX_EXTRA_INDEX" value="" class="actualRateDetail">
                        <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][payableAmount]" id="payableAmount_h_MAIN_INDEX_EXTRA_INDEX" class="payableAmountHidden  payableAmount" />
                        <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][billingAmount]" id="billingAmount_h_MAIN_INDEX_EXTRA_INDEX" class="billingAmountHidden billingAmount" />
                        <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][paymentStrategy]" id="paymentStrategy_MAIN_INDEX_EXTRA_INDEX" class="paymentStrategyHidden" />
                        <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][paymentStrategyPercent]" id="paymentStrategyPercent_MAIN_INDEX_EXTRA_INDEX" class="paymentStrategyPercentHidden" />
                    </div>
                    <a style="position: absolute;  right:4rem" id="activity_MAIN_INDEX_extra_remove_current"><i class="fa fa-trash"></i></a>

                </div>
                <div class="col-md-11 col-md-push-1 no-margin" id="activity_MAIN_INDEX_extra_noforms_template"><i>Click on Add Extra If Any</i></div>
                <div class="col-md-11 col-md-push-1 no-margin activity_MAIN_INDEX_extra_controls">
                    <a class="btn btn-flat btn-sm bg-olive" id="activity_MAIN_INDEX_extra_add">Add Extra</a>
                </div>
            </div>

            <div class="clear"></div>

            <div class="extra-wrapper">
                <div class="activityNote">
                    <div class="col-md-6"><?php echo inputWrapper('detail[MAIN_INDEX][activityNote]', '', '', 'class="form-control" id="activityNote_MAIN_INDEX" placeholder="Activity Note"'); ?></div>
                    <div class="col-md-3"><?php echo checkBoxWrapper('detail[MAIN_INDEX][complimentary]', 'Mark As Complimentary', 1, 'class="simple complimentary" id="complimentary_MAIN_INDEX" ', FALSE, ''); ?></div>
                    <div class="col-md-3"><?php echo checkBoxWrapper('detail[MAIN_INDEX][applySpecialRate]', 'Apply Special Rate', 1, 'class="simple applySpecialRate" onclick="return showHideSpecialRate(this)" id="applySpecialRate_MAIN_INDEX" ', FALSE, ''); ?></div>

                </div>

                <div class="clear"></div>

                <div class="row specialAmountWrapper hidden" id="applySpecialRate_MAIN_INDEX_desc">
                    <div class="col-md-6"><input type="text" class="form-control" name="detail[MAIN_INDEX][specialRateReason]" id="applySpecialRate_MAIN_INDEX_reason" placeholder="Reason for special rate" value=""/></div>
                    <div class="col-md-6 marginWrapper">
                        <div class="col-md-1 no-margin">&nbsp;</div>
                        <div class="col-md-3 no-margin"><?php echo form_input('detail[MAIN_INDEX][specialPaymentAmount]', '', 'id="specialPayableAmount_MAIN_INDEX" class="form-control payableAmount"  onkeyup="calculateSpecialBillingRate(this)"' ); ?></div>
                        <div class="col-md-3 no-margin <?php echo $showMargins ?>">
                            <div class="input-group">
                                <?php echo form_input('detail[MAIN_INDEX][specialMargin]', '', 'class="form-control percent marginValue" id="specialMargin_MAIN_INDEX" placeholder="margin" onkeyup="calculateBillingRate(this)"' ); ?>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-3 no-margin <?php echo $showMargins ?>"><?php echo form_input('detail[MAIN_INDEX][specialBillingAmount]', '', 'id="specialBillingAmount_MAIN_INDEX" class="form-control billingAmount" onkeyup="calculateMargin(this)"'); ?></div>
                    </div>
                </div>
            </div>

            <a style="position: absolute; top:0; right:1rem"  title="remove" class="remove_rate" id="activity_remove_current" data-formIndex="MAIN_INDEX"  onclick="return deleteActivity('0', 'activity_templateMAIN_INDEX')" ><i class="fa fa-times text-red"></i></a>

            <div class="clear"></div>

        </div>

        <?php
        if( count($activityDetails) >0 ){
            foreach($activityDetails as $ad){

                $activityTempId = 'hotelActivityWrapper_'.$ad->id();
                $activityDetailsTemplates[] = $activityTempId;
                $activityDetailsID = $ad->id();

                $roomType = NULL;
                $roomQuantity = $ad->getNumberOfRooms();

                if( $ad->getRoomType() ){
                    $roomTypeObj = $ad->getRoomType();
                    $roomType = $roomTypeObj->id();
                    $beds = $roomTypeObj->getQuantity() ?: 0;
                    $totalBeds = $beds * $roomQuantity;

                    $numberOfBedsChoosen += $totalBeds;
                }

                $roomCategory = ( $ad->getRoomCategory() ) ? $ad->getRoomCategory()->id() : NULL;
                $roomType = ( $ad->getRoomType() ) ? $ad->getRoomType()->id() : NULL;
                $roomPlan = ( $ad->getRoomPlan() ) ? $ad->getRoomPlan()->id() : NULL;
                $extraBed = ( $ad->getExtraBed() ) ? $ad->getExtraBed() : 0;
                $package = ( $ad->getPackage() ) ? $ad->getPackage()->id() : NULL;

                $appliedRate = ($ad->getHotelRate()) ? $ad->getHotelRate()->id() : '';
                $appliedDetailRate = ( $ad->getHotelRateDetail() )? $ad->getHotelRateDetail()->id() : '';
                $actualAmount = $ad->getTotalAmount();
                $isComplimentary = ( $ad->isComplimentary() )? 'checked="checked"' : '';

                $isSpecialRateAppliedCheck = '';
                $isSpecialRateAppliedClass = 'hidden';
                $specialAmount = '';
                $specialAmountReason = '';
                $specialPayableAmount = '';
                $specialBillingAmount = '';
                $specialMargin = '';

                $payableAmount = $ad->getPayableAmount();
                $billingAmount = $ad->getBillingAmount();
                $paymentStrategy = $ad->getPaymentStrategyType();
                $paymentStrategyPercent = $ad->getPaymentStrategyPercent();
                $margin = $ad->getMargin();

                $activityNote = $ad->getNote();
                $nickName = $ad->getNickNameForRoomType();

                if( $ad->isSpecialRateApplied() ){
                    $isSpecialRateAppliedCheck = 'checked="checked"';
                    $isSpecialRateAppliedClass = '';
                    $specialAmount = $ad->getSpecialRate();
                    $specialAmountReason = $ad->getReasonForSpecialRate();
                    $specialMargin = $margin;
                    $specialPayableAmount = $payableAmount;
                    $specialBillingAmount = $billingAmount;
                }

                $packageExtraDetails = ( $bookingType == Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS )? $ad->getChildren() : [];


                ?>
                <div class="hotelActivityWrapper" id="<?php echo $activityTempId ?>"  style="position:relative; float:left; width:100%">
                    <div class="col-md-1 no-margin"><input type="text" class="form-control required number_only quantity" id="quantity_MAIN_INDEX" onkeypress="return clearAmount(this, 'MAIN_INDEX', '')" value="<?php echo $roomQuantity ?>" name="detail[MAIN_INDEX][quantity]" /></div>
                    <div class="col-md-2 no-margin room_basis"><?php getSelectRoomCategoriesByHotel($hotelID, 'detail[MAIN_INDEX][category]', $roomCategory, 'class="form-control category" id="category_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX)"' ) ?></div>
                    <div class="col-md-1 no-margin room_basis"><?php getSelectRoomTypesByHotel($hotelID, 'detail[MAIN_INDEX][type]', $roomType, 'class="form-control type" id="type_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX)"') ?></div>
                    <div class="col-md-1 no-margin room_basis">
                        <input type="text" name="detail[MAIN_INDEX][roomTypeNickName]" class="form-control nickName" id="nickName_MAIN_INDEX" value="<?php echo $nickName ?>"/>
                    </div>
                    <div class="col-md-1 no-margin room_basis"><?php getSelectRoomPlansByHotel($hotelID, 'detail[MAIN_INDEX][plan]', $roomPlan, 'class="form-control plan" id="plan_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX )"') ?></div>
                    <div class="col-md-1 no-margin room_basis"><input type="text" class="form-control number_only extraBed" value="<?php echo $extraBed ?>" id="extraBed_MAIN_INDEX" onkeypress="return clearAmount(this, 'MAIN_INDEX', '')" name="detail[MAIN_INDEX][extraBed]" /></div>
                    <div class="col-md-6 no-margin package_basis"><?php getSelectPackagesElementByHotel($hotelID, 'detail[MAIN_INDEX][package]', HotelPackage::PACKAGE_TYPE_MAIN, $package, ' data-selected="'.$package.'" class="form-control packageMain" id="package_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX)"') ?></div>
                    <div class="col-md-5 no-margin marginWrapper">

                        <div class="col-md-1 no-margin"><span id="calculate_MAIN_INDEX" onclick="return getRate(this, 'MAIN_INDEX', '')"><i title="calculate" class="fa fa-calculator"></i></span></div>
                        <div class="col-md-3 no-margin"><input type="text" disabled name="detail[MAIN_INDEX][payableAmountH]" id="payableAmount_MAIN_INDEX" class="form-control payableAmount"  value="<?php echo $payableAmount ?>" /></div>
                        <div class="col-md-3 no-margin <?php echo $showMargins ?>">
                            <div class="input-group">
                                <input type="text" name="detail[MAIN_INDEX][margin]" class="form-control percent pmargin marginValue"  id="margin_MAIN_INDEX"  value="<?php echo $margin ?>" placeholder="margin" onkeyup="calculateBillingRate(this)"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-3 no-margin <?php echo $showMargins ?>"><input type="text" onkeyup="calculateMargin(this)" name="detail[MAIN_INDEX][billingAmountH]" id="billingAmount_MAIN_INDEX" class="form-control billingAmount" value="<?php echo $billingAmount ?>"  /></div>

                        <input type="hidden" name="detail[MAIN_INDEX][activityDetailID]" class="activityDetailID" id="activityDetailID_MAIN_INDEX" value="<?php echo $activityDetailsID ?>">
                        <input type="hidden" name="detail[MAIN_INDEX][actualRate]" id="actualRate_MAIN_INDEX" value="<?php echo $appliedRate ?>" class="actualRate">
                        <input type="hidden" name="detail[MAIN_INDEX][actualRateDetail]" id="actualRateDetail_MAIN_INDEX" value="<?php echo $appliedDetailRate ?>" class="actualRateDetail">
                        <input type="hidden" name="detail[MAIN_INDEX][payableAmount]" id="payableAmount_h_MAIN_INDEX" class="payableAmountHidden  payableAmount"  value="<?php echo $payableAmount ?>" />
                        <input type="hidden" name="detail[MAIN_INDEX][billingAmount]" id="billingAmount_h_MAIN_INDEX" class="billingAmountHidden billingAmount" value="<?php echo $billingAmount ?>"  />
                        <input type="hidden" name="detail[MAIN_INDEX][paymentStrategy]" id="paymentStrategy_MAIN_INDEX" class="paymentStrategyHidden"  value="<?php echo $paymentStrategy ?>" />
                        <input type="hidden" name="detail[MAIN_INDEX][paymentStrategyPercent]" id="paymentStrategyPercent_MAIN_INDEX" class="paymentStrategyPercentHidden" value="<?php echo $paymentStrategyPercent ?>" />

                    </div>


                    <div class="package_basis" id="activity_MAIN_INDEX_extra">
                        <div class="" id="activity_MAIN_INDEX_extra_template" style="position:relative; float:left; width:100%">
                            <div class="col-md-1 no-margin"><input type="text" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][quantity]" class="form-control quantityExtra" id="quantity_MAIN_INDEX_EXTRA_INDEX" onchange="return clearAmount(this, 'MAIN_INDEX', 'EXTRA_INDEX')" /></div>
                            <div class="col-md-6 no-margin"><?php echo form_dropdown('detail[MAIN_INDEX][extra][EXTRA_INDEX][package]', [''=>'-- EXTRA --'],NULL, 'class="form-control packageExtra" id="package_MAIN_INDEX_EXTRA_INDEX" onchange="return clearAmount(this, MAIN_INDEX, EXTRA_INDEX)"')?></div>
                            <div class="col-md-5 no-margin marginWrapper">
                                <div class="col-md-1 no-margin"><span id="calculate_MAIN_INDEX_EXTRA_INDEX" onclick="return getRate(this, 'MAIN_INDEX', 'EXTRA_INDEX')"><i title="calculate" class="fa fa-calculator calculateExtra"></i></span></div>
                                <div class="col-md-3 no-margin"><input type="text" disabled name="detail[MAIN_INDEX][extra][EXTRA_INDEX][payableAmountH]" id="payableAmount_MAIN_INDEX_EXTRA_INDEX" class="form-control payableAmount" /></div>
                                <div class="col-md-3 no-margin <?php echo $showMargins ?>">
                                    <div class="input-group">
                                        <input type="text" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][margin]" class="form-control percent pmargin marginValue"  id="margin_MAIN_INDEX_EXTRA_INDEX"  value="" placeholder="margin" onkeyup="calculateBillingRate(this)"/>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="col-md-3 no-margin <?php echo $showMargins ?>"><input type="text" onkeyup="calculateMargin(this)" name="detail[MAIN_INDEX][billingAmountH]" id="billingAmount_MAIN_INDEX_EXTRA_INDEX" class="form-control billingAmount" /></div>

                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][activityDetailID]" class="activityDetailID" id="activityDetailID_MAIN_INDEX_EXTRA_INDEX" value="">
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][actualRate]" id="actualRate_MAIN_INDEX_EXTRA_INDEX" value="" class="actualRate">
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][actualRateDetail]" id="actualRateDetail_MAIN_INDEX_EXTRA_INDEX" value="" class="actualRateDetail">
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][payableAmount]" id="payableAmount_h_MAIN_INDEX_EXTRA_INDEX" class="payableAmountHidden  payableAmount" />
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][billingAmount]" id="billingAmount_h_MAIN_INDEX_EXTRA_INDEX" class="billingAmountHidden billingAmount" />
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][paymentStrategy]" id="paymentStrategy_MAIN_INDEX_EXTRA_INDEX" class="paymentStrategyHidden" />
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][paymentStrategyPercent]" id="paymentStrategyPercent_MAIN_INDEX_EXTRA_INDEX" class="paymentStrategyPercentHidden" />
                            </div>
                            <a style="position: absolute;  right:4rem" id="activity_MAIN_INDEX_extra_remove_current"><i class="fa fa-trash"></i></a>

                        </div>


                        <?php
                        if( count($packageExtraDetails) ){
                            $eDCount = 0;
                            foreach($packageExtraDetails as $extraDetail){
                                $eDID = 'activity_MAIN_INDEX_extra_'.$eDCount;
                                //                                $eDID = 'extraDetailsOld_'.$extraDetail->id().'_'.$eDCount;
                                $extraPackage = $extraDetail->getPackage() ? $extraDetail->getPackage()->id() : NULL;
//                                show_pre($extraPackage, 'extra package');
                                $extraPayableAmount = $extraDetail->getPayableAmount();
                                $extraMargin = $extraDetail->getMargin();
                                $extraBillingAmount = $extraDetail->getBillingAmount();
                                $extraActivityID = $extraDetail->id();

                                $extraAppliedRate = ($extraDetail->getHotelRate()) ? $extraDetail->getHotelRate()->id() : '';
                                $extraAppliedDetailRate = ( $extraDetail->getHotelRateDetail() )? $extraDetail->getHotelRateDetail()->id() : '';
                                $extraPaymentStrategy = $extraDetail->getPaymentStrategyType();
                                $extraPaymentPercent = $extraDetail->getPaymentStrategyPercent();
                                $eQuantity = $extraDetail->getNumberOfRooms();




                        ?>
                        <div class="" id="activity_MAIN_INDEX_extra_template" style="position:relative; float:left; width:100%">
                            <div class="col-md-1 no-margin"><input type="text" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][quantity]" class="form-control quantityExtra" id="quantity_MAIN_INDEX_EXTRA_INDEX" onchange="return clearAmount(this, 'MAIN_INDEX', 'EXTRA_INDEX')" value="<?php echo $eQuantity ?>" /></div>
                            <div class="col-md-6 no-margin"><?php getSelectPackagesElementByHotel($hotelID, 'detail[MAIN_INDEX][extra][EXTRA_INDEX][package]', HotelPackage::PACKAGE_TYPE_EXTRA, $extraPackage, ' data-selected="'.$extraPackage.'" class="form-control packageExtra" id="package_MAIN_INDEX_EXTRA_INDEX" onchange="return clearAmount(this, MAIN_INDEX, EXTRA_INDEX)"') ?></div>
                            <div class="col-md-5 no-margin marginWrapper">
                                <div class="col-md-1 no-margin"><span id="calculate_MAIN_INDEX_EXTRA_INDEX" onclick="return getRate(this, 'MAIN_INDEX', 'EXTRA_INDEX')"><i title="calculate" class="fa fa-calculator calculateExtra"></i></span></div>
                                <div class="col-md-3 no-margin"><input type="text" disabled name="detail[MAIN_INDEX][extra][EXTRA_INDEX][payableAmountH]" id="payableAmount_MAIN_INDEX_EXTRA_INDEX" value="<?php echo $extraPayableAmount ?>" class="form-control payableAmount" /></div>
                                <div class="col-md-3 no-margin <?php echo $showMargins ?>">
                                    <div class="input-group">
                                        <input type="text" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][margin]" class="form-control percent pmargin marginValue"  id="margin_MAIN_INDEX_EXTRA_INDEX"  value="<?php echo $extraMargin ?>" placeholder="margin" onkeyup="calculateBillingRate(this)"/>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="col-md-3 no-margin <?php echo $showMargins ?>"><input type="text" name="detail[MAIN_INDEX][billingAmountH]" value="<?php echo $extraBillingAmount ?>" id="billingAmount_MAIN_INDEX_EXTRA_INDEX" class="form-control billingAmount" onkeyup="calculateMargin(this)" /></div>

                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][activityDetailID]" class="activityDetailID" id="activityDetailID_MAIN_INDEX_EXTRA_INDEX" value="<?php echo $extraActivityID ?>">
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][actualRate]" id="actualRate_MAIN_INDEX_EXTRA_INDEX" value="<?php echo $extraAppliedRate ?>" class="actualRate">
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][actualRateDetail]" id="actualRateDetail_MAIN_INDEX_EXTRA_INDEX" value="<?php echo $extraAppliedDetailRate ?>" class="actualRateDetail">
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][payableAmount]" id="payableAmount_h_MAIN_INDEX_EXTRA_INDEX" value="<?php echo $extraPayableAmount ?>" class="payableAmountHidden payableAmount" />
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][billingAmount]" id="billingAmount_h_MAIN_INDEX_EXTRA_INDEX" value="<?php echo $extraBillingAmount ?>" class="billingAmountHidden billingAmount" />
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][paymentStrategy]" id="paymentStrategy_MAIN_INDEX_EXTRA_INDEX" value="<?php echo $extraPaymentStrategy ?>" class="paymentStrategyHidden" />
                                <input type="hidden" name="detail[MAIN_INDEX][extra][EXTRA_INDEX][paymentStrategyPercent]" id="paymentStrategyPercent_MAIN_INDEX_EXTRA_INDEX" value="<?php echo $extraPaymentPercent ?>" class="paymentStrategyPercentHidden" />
                            </div>
                            <a style="position: absolute;  right:4rem" id="activity_MAIN_INDEX_extra_remove_current"><i class="fa fa-trash"></i></a>

                        </div>
                        <?php $eDCount++; } } ?>


                        <div class="col-md-11 col-md-push-1 no-margin" id="activity_MAIN_INDEX_extra_noforms_template"><i>Click on Add Extra If Any</i></div>
                        <div class="col-md-11 col-md-push-1 no-margin activity_MAIN_INDEX_extra_controls">
                            <a class="btn btn-flat btn-sm bg-olive" id="activity_MAIN_INDEX_extra_add">Add Extra</a>
                        </div>
                    </div>



                    <div class="clear"></div>

                    <div class="extra-wrapper">
                        <div class="activityNote">
                            <div class="col-md-6"><?php echo inputWrapper('detail[MAIN_INDEX][activityNote]', '', $activityNote, 'class="form-control" id="activityNote_MAIN_INDEX" placeholder="Activity Note"'); ?></div>
                            <div class="col-md-3"><?php echo checkBoxWrapper('detail[MAIN_INDEX][complimentary]', 'Mark As Complimentary', 1, 'class="simple complimentary" id="complimentary_MAIN_INDEX" ', $isComplimentary, ''); ?></div>
                            <div class="col-md-3"><?php echo checkBoxWrapper('detail[MAIN_INDEX][applySpecialRate]', 'Apply Special Rate', 1, 'class="simple applySpecialRate" onclick="return showHideSpecialRate(this)" id="applySpecialRate_MAIN_INDEX" ', $isSpecialRateAppliedCheck, ''); ?></div>
                        </div>

                        <div class="clear"></div>

                        <div class="row specialAmountWrapper <?php echo $isSpecialRateAppliedClass ?>" id="applySpecialRate_MAIN_INDEX_desc">
                            <div class="col-md-6"><input type="text" class="form-control" name="detail[MAIN_INDEX][specialRateReason]" id="applySpecialRate_MAIN_INDEX_reason" placeholder="Reason for special rate" value="<?php echo $specialAmountReason ?>"/></div>
                            <div class="col-md-6 marginWrapper">
                                <div class="col-md-1 no-margin">&nbsp;</div>
                                <div class="col-md-3 no-margin"><?php echo form_input('detail[MAIN_INDEX][specialPaymentAmount]', $specialPayableAmount, 'id="specialPayableAmount_MAIN_INDEX" class="form-control payableAmount"  onkeyup="calculateSpecialBillingRate(this)"' ); ?></div>
                                <div class="col-md-3 no-margin <?php echo $showMargins ?>">
                                    <div class="input-group">
                                        <?php echo form_input('detail[MAIN_INDEX][specialMargin]', $specialMargin, 'class="form-control percent marginValue" id="specialMargin_MAIN_INDEX" placeholder="margin" onkeyup="calculateBillingRate(this)"' ); ?>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="col-md-3 no-margin <?php echo $showMargins ?>"><?php echo form_input('detail[MAIN_INDEX][specialBillingAmount]', $specialBillingAmount, 'id="specialBillingAmount_MAIN_INDEX" class="form-control billingAmount" onkeyup="calculateMargin(this)"'); ?></div>
                            </div>
                        </div>
                    </div>

                    <a style="position: absolute; top:0; right:1rem"  title="remove" class="remove_rate" id="activity_remove_current" data-formIndex="MAIN_INDEX"  onclick="return deleteActivity('<?php echo $activityID?>', 'activity_templateMAIN_INDEX')" ><i class="fa fa-times text-red"></i></a>

                    <div class="clear"></div>


                </div>
            <?php }
        }
        ?>

        <div class=col-md-12" id="activity_noforms_template">
            No Descriptions found.
        </div>
        <div class="col-md-12" id="activity_controls">
            <a href="#" id="activity_add" class="btn btn-sm btn-primary">Add Another Room</a>
        </div>

    </div>

    <input type="hidden" name="numberOfPax" id="numberOfPax" value="<?php echo $numberOfPax?>" />
    <input type="hidden" name="allowedBeds" id="allowedBeds" value="<?php echo $allowedBeds?>" />
    <input type="hidden" name="market_id" id="market_id" value="<?php echo $market ?>" />
    <input type="hidden" name="activity_id" id="activity_id" value="<?php echo $activityID ?>" />
    <input type="hidden" name="activity_type" id="activity_type" value="HOTEL" />

</div>



<?php

echo clearDiv();
// echo textAreaWrapper('noteOfArrivalAndDeparture', 'Note Of Arrival and Departure', $arrivalDepartureNote, 'class="form-control"', 'col-md-6');
echo textAreaWrapper('description', 'Details', $details, 'class="form-control"', 'col-md-12');
echo clearDiv();

?>

    <div class="col-md-12">
        <input type="submit" value="SAVE ACTIVITY" class="btn btn-primary">
        <a href="<?php echo site_url('file/detail/'.$file->id()) ?>" class="btn btn-danger">CANCEL</a>
    </div>

</form>

</div></div></div></div>

<?php loadJS(array('jquery.sheepit.min', 'jquery.inputmask', 'jquery.sheepItPlugin')) ?>
<script type="text/javascript">
var sheepItXO = {};
$(document).ready(function(){
    $('#hotel').select2();

        var hotel = $('#hotel'),
            activity = $('#activity'),
            bookingType = $('#bookingType');

        $('#arrivalDate, #departureDate, #market').bind('change', function(){
            clearAmount(null, '');
        });


        $('#arrivalDate').datepicker({
            dateFormat: 'yy-mm-dd',
//            minDate: 'dateToday',
            changeMonth: true,
            changeYear: true,
            onClose: function(selectedDate) {
                var nextDate = new Date(selectedDate);
                nextDate.addDays(1);
                $("#departureDate").datepicker("option", "minDate", nextDate);
            }
        });

        $('#departureDate').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

        $('.timeOnly').inputmask('99:99');

        hotel.bind('change', function(){
            clearAmount(null, '', '');
            getHotelRoomOptions(activity);
            if( hotel.val() != '' ){
                getHotelBookingTypes(hotel.val());
                getHotelPackages(hotel.val());
            }else{
                $('#bookingType').html('<option value="">-- BOOKING TYPE --</option>');
            }
        });

        if( hotel.val() != '' ){
            getHotelBookingTypes(hotel.val());
            getHotelPackages(hotel.val());

        }

        bookingType.bind('change', function(){
            generateActivityDetailWrapper(bookingType.val());
            getMarketOptions();
        });


        sheepItXO = activity.sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 0,
            minFormsCount: 1,
            iniFormsCount: 1,
            indexFormat: 'MAIN_INDEX',
            afterClone: function(source, clone) {
                getHotelRoomOptions(clone);
                getHotelPackages($('#hotel').val(), clone);
                var _clone = $(clone);
                if( bookingType.val() == '<?php echo Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS ?>' ){
                    _clone.find('.room_basis').find('input.required, select.required').addClass('isRequired').removeClass('required');
                    _clone.find('.package_basis').find('input.isRequired, select.isRequired').addClass('required').removeClass('isRequired');
                    _clone.find('.room_basis').addClass('hidden');
                    _clone.find('.package_basis').removeClass('hidden');
                }else{
                    _clone.find('.package_basis').find('input.required, select.required').addClass('isRequired').removeClass('required');
                    _clone.find('.room_basis').find('input.isRequired, select.isRequired').addClass('required').removeClass('isRequired');
                    _clone.find('div.package_basis').addClass('hidden');
                    _clone.find('.room_basis').removeClass('hidden');
                }
            },
            nestedForms: [
                {
                    id: 'activity_MAIN_INDEX_extra',
                    options: {
                        indexFormat: 'EXTRA_INDEX',
                        maxFormsCount: 0,
                        minFormsCount: 1,
                        iniFormsCount: 1,
                        afterClone: function(source, clone){
                            getHotelPackages($('#hotel').val(), clone);
                        }
                    }
                }
            ],
            pregeneratedForms: <?php echo json_encode($activityDetailsTemplates) ?>

        });

        generateActivityDetailWrapper(bookingType.val());


        $('#hotelActivityForm').submit(function(){

            var form = $(this),
                error = false,
                specialRateError = false,
                rateError = false;

            if( form.valid() !== true ) {
                return false;
            }

            var allocatedBedQuantity = 0;
            var allowedBeds = $('#allowedBeds').val();


            $.each($('.type'), function(i, v){
                var elem = $(v);
                var id = elem.attr('id').split('_'),
                    index = id[1],
                    qty = $('#quantity_'+index).val();

                var bedQty = $('option:selected', elem).attr('data-qty');
                var totalBeds = parseInt(bedQty) * parseInt(qty);
                var extraBeds = $.isNumeric($('#extraBed_'+index).val())? $('#extraBed_'+index).val() : 0;
                allocatedBedQuantity += parseInt(totalBeds);
                allocatedBedQuantity += parseInt(extraBeds);
            });


            if( allocatedBedQuantity > allowedBeds ){
                Yarsha.notify('warn', 'Number of Rooms allocated exceeds the pax size.');
                return false;
            }


            $.each($('.actualRate'), function(ind, val){

                var _obj = $(val);

                if( _obj.val() == '' ){

                    var id = _obj.attr('id').split('_'),
                        index = id[1],
                        rateObj = $('#applySpecialRate_'+index+'_rate'),
                        compObj = $('#complimentary_'+index);

                    if( rateObj.val() == '' && compObj.is(':checked') == false){
                        error = true;
                        rateError = true;
                    }
                }
            });

            $.each($('.applySpecialRate'), function(i, v){

                if( $(v).is(':checked') ){
                    var id = $(v).attr('id'),
                        specialRateObj = $('#'+id+'_rate');

                    if( $(specialRateObj).val() == '' ){
                        specialRateError = true;
                        error = true;
                    }
                }
            });

            if( error === true){
                if( rateError === true ){
                    Yarsha.notify('error', 'Please be sure rate is applied for every detail.');
                }

                if( specialRateError === true ){
                    Yarsha.notify('error', 'Be sure that special rate is defined, if special rate is to be applied.');
                }

                return false;
            }else{
                return true;
            }

        });

    });

function getMarketOptions(){
    var type = $('#bookingType').val(),
        hotel = $('#hotel').val(),
        market = $('#market');

    market.html('<option value="">-- SELECT MARKET --</option>');

    if( hotel !== undefined && hotel != '' && type !== undefined && type != '' ){
        $.ajax({
            type: 'get',
            url: Yarsha.config.base_url+'hotel/ajax/getMarketsByHotelRate',
            data: {'type':type,'hotel':hotel},
            success: function(res){
                var data = $.parseJSON(res);
                if( data.status && data.status == 'success' ){
                    market.html(data.options)
                    market.val('<?php echo $market ?>');
                }
            }
        });
    }
}

    function generateActivityDetailWrapper(val){
        if( val == '<?php echo Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS ?>' ){
            $('.room_basis').find('input.required, select.required').addClass('isRequired').removeClass('required');//.val('');
            $('.room_basis').addClass('hidden');
            $('.package_basis').removeClass('hidden');
            $('.package_basis').find('input.isRequired, select.isRequired').addClass('required').removeClass('isRequired');
        }else{
            $('.package_basis').find('input.required, select.required').addClass('isRequired').removeClass('required');//.val('');
            $('.package_basis').addClass('hidden');
            $('.room_basis').removeClass('hidden');
            $('.room_basis').find('input.isRequired, select.isRequired').addClass('required').removeClass('isRequired');
        }
    }

    function clearAmount(obj, index, extraIndex){

        index = ( typeof extraIndex === "undefined" || extraIndex === "" )? index : index + '_' + extraIndex;

        if( obj !== null ){

            var actualRate = $('#actualRate_'+index);
                activityDetailID = $('#activityDetailID_'+index);
                payableAmount = $('#payableAmount_'+index);
                billingAmount = $('#billingAmount_'+index);
                payableAmountHidden = $('#payableAmount_h_'+index);
                billingAmountHidden = $('#billingAmount_h_'+index);
                paymentStrategyHidden = $('#paymentStrategy_'+index);
                paymentStrategyPercentHidden = $('#paymentStrategyPercent_'+index);
                margin = $('#margin_'+index);
                actualRateDetail = $('#actualRateDetail_'+index);

        }else{
            var actualRate = $('.actualRate'),
                activityDetailID = $('.activityDetailID'),
                payableAmount = $('.payableAmount'),
                billingAmount = $('.billingAmount'),
                payableAmountHidden = $('.payableAmountHidden'),
                billingAmountHidden = $('.billingAmountHidden'),
                paymentStrategyHidden = $('.paymentStrategyHidden'),
                paymentStrategyPercentHidden = $('.paymentStrategyPercentHidden'),
                margin = $('.pmargin'),
                actualRateDetail = $('.actualRateDetail');
        }

        actualRate.val('');
        payableAmount.val('');
        billingAmount.val('');
        payableAmountHidden.val('');
        billingAmountHidden.val('');
        paymentStrategyHidden.val('');
        paymentStrategyPercentHidden.val('');
        actualRateDetail.val();
        margin.val('');
    }

    function getHotelRoomOptions(obj){

        var hotel_id = $('#hotel').val(),
            _obj = $(obj);

        if( hotel_id === 'undefined' || hotel_id === "" ){
            _obj.find('.category').html('<option value="">-- CATEGORY --</option>');
            _obj.find('.type').html('<option value="">-- TYPE --</option>');
            _obj.find('.plan').html('<option value="">-- PLAN --</option>');
            return;
        }

        $.ajax({
            url: Yarsha.config.base_url+'file/ajax/getHotelRoomOptions/'+hotel_id,
            type: 'GET',
            success: function(res){
                var data = $.parseJSON(res);
                if( data.status && data.status == "success" ){
                    _obj.find('.category').html(data.categories);
                    _obj.find('.type').html(data.types);
                    _obj.find('.plan').html(data.plans);
                }
            }
        });
    }

    function getHotelExtraPackages(hotel_id,obj){
        if(hotel_id!=""){
            $.ajax({
                url: Yarsha.config.base_url+'file/ajax/getHotelPackages/'+hotel_id,
                type: 'GET',
                success: function(res){
                    var data = $.parseJSON(res);

                    $.each($(obj), function(i,v){
                        console.log($(v));
                        $(v).html(data.extra);
                        console.log($(v));
                    });
//                    console.log($(obj));

                }
            });
        }else{
            $(obj).html('<option value="">-- EXTRA --</option>');
        }
    }

    function getHotelPackages(hotel_id, obj){
        if(hotel_id!=""){
            $.ajax({
                url: Yarsha.config.base_url+'file/ajax/getHotelPackages/'+hotel_id,
                type: 'GET',
                success: function(res){
                    var data = $.parseJSON(res);

                    if( obj == undefined || obj == '' ){
                        $('.packageMain').html(data.main);
                        $('.packageExtra').html(data.extra);

                        $.each($('.packageMain'), function(i,v){
                            var selected = $(v).attr('data-selected');
                            $(v).val(selected);
                        });

                        $.each($('.packageExtra'), function(i,v){
                            var selected = $(v).attr('data-selected');
                            $(v).val(selected);
                        });
                    }else{
                        $(obj).find('.packageMain').html(data.main);
                        $(obj).find('.packageExtra').html(data.extra);
                    }

                }
            });
        }
    }

    function getHotelBookingTypes(hotel_id){
        $.ajax({
            url: Yarsha.config.base_url+'file/ajax/getHotelBookingTypes/'+hotel_id,
            type: 'GET',
            success: function(res){
                var data = $.parseJSON(res);
                if( data.status && data.status == "success" ){
                    $('#bookingType').html(data.bookingType);
                    <?php if($bookingType != ''){?>$('#bookingType').val('<?php echo $bookingType ?>');<?php } ?>
                    $('#bookingType').trigger('change');

                }
            }
        });
    }


    function getRate(obj, index, extraIndex){

        var self = $(obj);

        var hotel = $('#hotel').val(),
//            market = $('#market_id').val(),
            market = $('#market').val(),
            bookingType = $('#bookingType').val(),
            arrivalDate = $('#arrivalDate').val(),
            departureDate = $('#departureDate').val(),
            pax = $('#numberOfPax').val();


        var objIndex = ( extraIndex == '' || extraIndex == undefined )? index : index+'_'+extraIndex;

        var category = $('#category_'+objIndex).val(),
            type = $('#type_'+objIndex).val(),
            plan = $('#plan_'+objIndex).val(),
            margin = $('#margin_'+objIndex).val(),
            pkg = $('#package_'+objIndex).val(),
            extraBed = $('#extraBed_'+objIndex).val(),
            quantity = $('#quantity_'+objIndex).val();

        var payableAmount = $('#payableAmount_'+objIndex),
            billingAmount = $('#billingAmount_'+objIndex),
            activityDetailID = $('#activityDetailID_'+objIndex),
            actualRate = $('#actualRate_'+objIndex),
            actualRateDetail = $('#actualRateDetail_'+objIndex),
            payableAmount_h = $('#payableAmount_h_'+objIndex),
            billingAmount_h = $('#billingAmount_h_'+objIndex),
            paymentStrategy = $('#paymentStrategy_'+objIndex),
            paymentStrategyPercent = $('#paymentStrategyPercent_'+objIndex);

        var error = false,
            msg = '';

        if( hotel == undefined || hotel == "" || bookingType == undefined || bookingType == "" ){
            msg = msg + 'Select Hotel and Booking type.';
            error = true;
        }

        if( arrivalDate == undefined || arrivalDate == '' || departureDate == undefined || departureDate == '' ){
            msg = msg + '\n Select Arrival and Departure date.';
            error = true;
        }

        console.log('market :: '+market);

        if( market === undefined || market == '' ){
            msg = msg + '\n Select market before rate calculation.';
            error = true;
        }

        if( error == true ){
            Yarsha.notify('error', msg);
            return false;
        }

        var dataToSend = {
            'market' : market, 'arrivalDate':arrivalDate, 'departureDate':departureDate, 'hotel':hotel, 'bookingType': bookingType,
            'quantity':quantity, 'category':category, 'type':type, 'plan':plan, 'package':pkg, 'margin':margin,
            'pax': pax,'extraBed':extraBed
        };

        $.ajax({
            type: 'POST',
            url: Yarsha.config.base_url+'file/ajax/calculateHotelRate',
            data: dataToSend,
            success: function(res){
                var response = $.parseJSON(res);
                var data = response.message;

                if( response.status && response.status == "success" ){

                    if( data.isExpired && data.isExpired == true ){
                        Yarsha.notify('error', 'Rate for the given parameter is already expired.');
                    }else{

                        if( data.isExpiring && data.isExpiring == true) {
                            Yarsha.notify('info', 'Rate is expiring on '+ data.expiryDate);
                        }

                        if( parseFloat(data.payableAmount) <= 0) {
                            Yarsha.notify('info', 'Rate may not be available.');
                        }

                        actualRate.val(data.actualRate);
                        payableAmount.val(data.payableAmount);
                        billingAmount.val(data.billingAmount);
                        payableAmount_h.val(data.payableAmount);
                        billingAmount_h.val(data.billingAmount);
                        paymentStrategy.val(data.paymentStrategy);
                        paymentStrategyPercent.val(data.paymentStrategyPercent);
                        actualRateDetail.val(data.rateDetailsID);
                    }
                }else{
                    payableAmount.val('');
                    billingAmount.val('');
                    payableAmount_h.val('');
                    billingAmount_h.val('');
                    actualRate.val('');
                    paymentStrategy.val('');
                    paymentStrategyPercent.val('');
                    actualRateDetail.val('');
                    Yarsha.notify('error', response.message);
                }

                return;
            }
        });
    }

    function calculateMargin(obj){
        var self = $(obj);
        var splitted = self.attr('id').split('_');
        var index = splitted[splitted.length - 1];

        var margin = self.closest('.marginWrapper').find('.marginValue'),
            payableAmount = self.closest('.marginWrapper').find('.payableAmount').val(),
            billingAmount = self.val();

        $('#billingAmount_h_'+index).val(billingAmount);


        if($.isNumeric(billingAmount) && $.isNumeric(payableAmount)){
            var calculatedMargin = ( (parseFloat(billingAmount) - parseFloat(payableAmount)) / parseFloat(payableAmount) ) * 100;
            calculatedMargin = Math.round(calculatedMargin * 100) / 100;
            margin.val(calculatedMargin);
        }else{
            margin.val('0.00');
        }


    }

    function calculateBillingRate(obj){

        var self = $(obj);
        var splitted = self.attr('id').split('_');
        var index = splitted[splitted.length - 1];

        var margin = self.val(),
            payableAmount = self.closest('.marginWrapper').find('.payableAmount').val(),
            billingAmount = self.closest('.marginWrapper').find('.billingAmount'),
            billingAmountHidden = $('#billingAmount_h_'+index);

        if($.isNumeric(margin) && $.isNumeric(payableAmount)){
            var calculatedAmount =  parseFloat(payableAmount) + ( parseFloat(margin) / 100  ) * parseFloat(payableAmount);
            calculatedAmount = Math.round(calculatedAmount * 100) / 100;
            billingAmount.val(calculatedAmount);
            billingAmountHidden.val(calculatedAmount);
        }else{
            billingAmount.val('0.00');
            billingAmountHidden.val('0.00');
        }


    }

    function calculateSpecialBillingRate(obj){

        var self = $(obj);

        var payableAmount = self.val(),
            margin = self.closest('.marginWrapper').find('.marginValue').val(),
            margin = $.isNumeric(margin) ? margin : 0,
            billingAmount = self.closest('.marginWrapper').find('.billingAmount');

        if($.isNumeric(margin) && $.isNumeric(payableAmount)){
            var calculatedAmount =  parseFloat(payableAmount) + ( parseFloat(margin) / 100  ) * parseFloat(payableAmount);
            calculatedAmount = Math.round(calculatedAmount * 100) / 100;
            billingAmount.val(calculatedAmount);
        }else{
            billingAmount.val('0.00');
        }


    }

function roundMe(n, sig) {
    if (n === 0) return 0;
    var mult = Math.pow(10, sig - Math.floor(Math.log(n < 0 ? -n: n) / Math.LN10) - 1);
    return Math.round(n * mult) / mult;
}

    function showHideSpecialRate(obj){
        var obj = $(obj),
            isChecked = obj.is(':checked'),
            objID = obj.attr('id'),
            rateObj = $('#' + objID + '_rate'),
            reasonObj = $('#' + objID + '_reason'),
            objDesc = $('#' + objID + '_desc');

        if( isChecked == true ){
            rateObj.addClass('required');
            reasonObj.addClass('required');
            objDesc.removeClass('hidden');
        }else{

            rateObj.val('').removeClass('required');
            reasonObj.val('').removeClass('required');
            objDesc.addClass('hidden');
        }
    }

    function calculateSpecialRate(obj){
        var obj = $(obj),
            id = obj.attr('id').split('_'),
            index = id[1],
            marginOBJ = $('#specialMargin_'+index),
            payableAmountOBJ = $('#specialPayableAmount_'+index),
            billingAmountOBJ = $('#specialBillingAmount_'+index);
        var amount = parseFloat(payableAmountOBJ.val());
        var margin = ( marginOBJ.val() == '' || marginOBJ.val() === "undefined" )? 0 : parseFloat(marginOBJ.val());
        if( amount > 0){
            var calAmount = ( amount + ( margin/100 ) * amount );
            billingAmountOBJ.val(calAmount);
        }else{
            return false;
        }
    }

    function deleteActivity(activityID, template){

        if(confirm('Are you sure you want to delete?')){

            if( activityID == '' || activityID == 0 ){

                return true;
            }else{
                return removeData(activityID);
            }
        }else{
            return false;
        }


        function removeData(id) {
            $.ajax({
                type: "POST",
                url:  Yarsha.config.base_url + 'file/activity/deleteHotelActivity',
                data: {id: id},
                success: function(res){
                    var data = $.parseJSON(res);
                    if(data.status && data.status == 'success'){
                        $('#'+template).remove();
                        $("#alertarea").html('<div class="alert alert-success" role="alert"><strong>Deleted!!!</strong> Delete success..</div>');
                        jQuery(document).ready(function () {
                            //hide a div after 4 seconds
                            setTimeout( "jQuery('#alertarea').hide();",4000 );
                        });
                    }else{
                        return false;
                    }
                    return true;
                }
            });
        }
        return false;
    }
</script>