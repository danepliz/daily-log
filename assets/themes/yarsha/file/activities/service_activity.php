<?php
use file\models\TourFileActivity;
use hotel\models\Hotel;
use hotel\models\HotelPackage;
use hotel\models\HotelCategory;

$arrivalDate =  $departureDate = '';
$hotelID = '';
$mode_type = $dep_mod_type = NULL;
$details = $activityDetailsString =  $estimatedArrivalTime =  $estimatedDepartureTime =  $confirmationNumber= $numberOfNights = $arrivalDepartureNote = '';
$market = '';//$file->getMarket()? $file->getMarket()->id() : '';
$activityDetailsTemplates = array();
$activityDetails = array();
$activityID = $arrivalNote = $departureNote = $currency = $bookingType = '';
$numberOfBedsChoosen = 0;
$numberOfPax = $file->getNumberOfPax();
$extraBeds = \Options::get('config_extrabeds', 4);
$allowedBeds = $numberOfPax + $extraBeds;

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
    $market = $activity->getMarket() ? $activity->getMarket()->id() : '';

//    $activityDetails = $activity->getDetails();
    $activityDetails = $activity_details;

    $estimatedArrivalTime = $activity->getArrivalTime()? $activity->getArrivalTime()->format('H:s') : '';
    $estimatedDepartureTime = $activity->getDepartureTime()? $activity->getDepartureTime()->format('H:s') : '';
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


<div class="form-group-sm col-md-3">
    <label for="serviceType">Service Activity For</label>
    <?php
        $serviceTypeOptions = [
            'HOTEL' => 'HOTEL',
            HotelCategory::HOTEL_CATEGORY_RESTAURANT => 'RESTAURANT'
        ];
        echo form_dropdown('serviceType', $serviceTypeOptions, NULL, 'class="form-control required" id="serviceType"');
    ?>
</div>

<div class="form-group-sm col-md-3">
    <label for="hotel">Provider</label>
    <?php echo form_dropdown('hotel', ['' => '-- CHOOSE PROPERTY --'], NULL, 'class="form-control required" id="hotel"'); ?>
</div>

<div class="form-group-sm col-md-3">
    <label for="market">Market</label>
<!--    --><?php //getMarketSelectionElementForXo('market', $market, 'class="form-control required" id="market"') ?>
    <?php echo form_dropdown('market', [''=>'-- SELECT MARKET --'], NULL, 'class="form-control required" id="market"') ?>
</div>

<?php
echo inputWrapper('confirmationNumber','Reservation Confirmation Number', $confirmationNumber, 'placeholder="if available" class="form-control" id="confirmationNumber"', 'col-md-3');
echo clearDiv();
?>

<div class="form-group-sm col-md-12">

    <div class="col-md-12" id="activity">

        <div class="row">
            <div class="col-md-2 no-margin "><label>Qty</label></div>
            <div class="col-md-2 no-margin "><label>Outlet</label></div>
            <div class="col-md-2 no-margin "><label>Service</label></div>
            <div class="col-md-6 no-margin">
                <div class="col-md-1 no-margin">&nbsp;</div>
                <div class="col-md-4 no-margin"><label>Payable Amount</label></div>
                <div class="col-md-2 no-margin <?php echo $showMargins ?>"><label>Margin</label></div>
                <div class="col-md-3 no-margin <?php echo $showMargins ?>" ><label>Billing Amount</label></div>
            </div>
        </div>

        <div class="clear"></div>

        <div class="hotelActivityWrapper" id="activity_template" style="position:relative; float:left; width:100%">
            <div class="col-md-2 no-margin"><input type="text" class="form-control required number_only quantity" id="quantity_MAIN_INDEX" onkeypress="return clearAmount(this, 'MAIN_INDEX', '')" name="detail[MAIN_INDEX][quantity]" /></div>
            <div class="col-md-2 no-margin "><?php echo form_dropdown('detail[MAIN_INDEX][outlet]', array('' => '-- OUTLET --'), NULL, 'class="form-control outlet" id="outlet_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX)"') ?></div>
            <div class="col-md-2 no-margin "><?php echo form_dropdown('detail[MAIN_INDEX][service]', array('' => '-- SERVICE --'), NULL, 'class="form-control service" id="service_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX)"') ?></div>
            <div class="col-md-6 no-margin marginWrapper">

                <div class="col-md-1 no-margin"><span id="calculate_MAIN_INDEX" onclick="return getRate(this, 'MAIN_INDEX', '')"><i title="calculate" class="fa fa-calculator"></i></span></div>
                <div class="col-md-3 no-margin"><input type="text" disabled name="detail[MAIN_INDEX][payableAmountH]" id="payableAmount_MAIN_INDEX" class="form-control payableAmount" /></div>
                <div class="col-md-3 no-margin <?php echo $showMargins ?>">
                    <div class="input-group">
                        <input type="text" name="detail[MAIN_INDEX][margin]" class="form-control percent pmargin marginValue"  id="margin_MAIN_INDEX"  value="" placeholder="margin" onkeyup="calculateBillingRate(this)"/>
                        <span class="input-group-addon">%</span>
                    </div>
                </div>
                <div class="col-md-3 no-margin <?php echo $showMargins ?>"><input type="text" name="detail[MAIN_INDEX][billingAmountH]" id="billingAmount_MAIN_INDEX" class="form-control billingAmount" onkeyup="calculateMargin(this)" /></div>

                <input type="hidden" name="detail[MAIN_INDEX][activityDetailID]" class="activityDetailID" id="activityDetailID_MAIN_INDEX" value="">
                <input type="hidden" name="detail[MAIN_INDEX][actualRate]" id="actualRate_MAIN_INDEX" value="" class="actualRate">
                <input type="hidden" name="detail[MAIN_INDEX][actualRateDetail]" id="actualRateDetail_MAIN_INDEX" value="" class="actualRateDetail">
                <input type="hidden" name="detail[MAIN_INDEX][payableAmount]" id="payableAmount_h_MAIN_INDEX" class="payableAmountHidden" />
                <input type="hidden" name="detail[MAIN_INDEX][billingAmount]" id="billingAmount_h_MAIN_INDEX" class="billingAmountHidden" />
                <input type="hidden" name="detail[MAIN_INDEX][paymentStrategy]" id="paymentStrategy_MAIN_INDEX" class="paymentStrategyHidden" />
                <input type="hidden" name="detail[MAIN_INDEX][paymentStrategyPercent]" id="paymentStrategyPercent_MAIN_INDEX" class="paymentStrategyPercentHidden" />

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
                        <div class="col-md-3 no-margin <?php echo $showMargins ?>"><?php echo form_input('detail[MAIN_INDEX][specialBillingAmount]', '', ' onkeyup="calculateMargin(this)" id="specialBillingAmount_MAIN_INDEX" class="form-control billingAmount"'); ?></div>
                    </div>
                </div>
            </div>

            <a style="position: absolute; top:0; right:1rem"  title="remove" class="remove_rate" id="activity_remove_current" data-formIndex="MAIN_INDEX"  onclick="return deleteActivity('0', 'activity_templateMAIN_INDEX')" ><i class="fa fa-times text-red"></i></a>

            <div class="clear"></div>

        </div>

        <?php
        if( count($activityDetails) >0 ) {
            foreach ($activityDetails as $ad) {

                $activityTempId = 'hotelActivityWrapper_' . $ad->id();
                $activityDetailsTemplates[] = $activityTempId;
                $activityDetailsID = $ad->id();

                $roomType = NULL;
                $roomQuantity = $ad->getNumberOfRooms();

                $outlet = ( $ad->getOutlet() ) ? $ad->getOutlet()->id() : '';
                $service = ( $ad->getService() ) ? $ad->getService()->id() : '';

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

                if( $ad->isSpecialRateApplied() ){
                    $isSpecialRateAppliedCheck = 'checked="checked"';
                    $isSpecialRateAppliedClass = '';
                    $specialAmount = $ad->getSpecialRate();
                    $specialAmountReason = $ad->getReasonForSpecialRate();
                    $specialMargin = $margin;
                    $specialPayableAmount = $payableAmount;
                    $specialBillingAmount = $billingAmount;

                    $payableAmount = '';
                    $billingAmount = '';
                    $margin = '';
                }

                ?>


                <div class="hotelActivityWrapper" id="<?php echo $activityTempId ?>"
                     style="position:relative; float:left; width:100%">
                    <div class="col-md-2 no-margin"><input type="text" value="<?php echo $roomQuantity ?>" class="form-control required number_only quantity" id="quantity_MAIN_INDEX" onkeypress="return clearAmount(this, 'MAIN_INDEX', '')" name="detail[MAIN_INDEX][quantity]"/></div>
                    <div class="col-md-2 no-margin "><?php echo form_dropdown('detail[MAIN_INDEX][outlet]', array('' => '-- OUTLET --'), NULL, 'data-outlet="'.$outlet.'"  class="form-control outlet" id="outlet_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX)"') ?></div>
                    <div class="col-md-2 no-margin "><?php echo form_dropdown('detail[MAIN_INDEX][service]', array('' => '-- SERVICE --'), NULL, 'data-service="'.$service.'"  class="form-control service" id="service_MAIN_INDEX" onchange="return clearAmount(this, MAIN_INDEX)"') ?></div>
                    <div class="col-md-6 no-margin marginWrapper">

                        <div class="col-md-1 no-margin"><span id="calculate_MAIN_INDEX" onclick="return getRate(this, 'MAIN_INDEX', '')"><i title="calculate" class="fa fa-calculator"></i></span></div>
                        <div class="col-md-3 no-margin"><input type="text" value="<?php echo $payableAmount ?>" disabled name="detail[MAIN_INDEX][payableAmountH]" id="payableAmount_MAIN_INDEX"  class="form-control payableAmount"/></div>
                        <div class="col-md-3 no-margin <?php echo $showMargins ?>">
                            <div class="input-group">
                                <input type="text" name="detail[MAIN_INDEX][margin]" class="form-control percent pmargin marginValue" id="margin_MAIN_INDEX" value="<?php echo $margin ?>" placeholder="margin" onkeyup="calculateBillingRate(this)"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-3 no-margin <?php echo $showMargins ?>"><input type="text" value="<?php echo $billingAmount ?>"  onkeyup="calculateMargin(this)"  name="detail[MAIN_INDEX][billingAmountH]" id="billingAmount_MAIN_INDEX" class="form-control billingAmount"/></div>

                        <input type="hidden" name="detail[MAIN_INDEX][activityDetailID]" class="activityDetailID" id="activityDetailID_MAIN_INDEX" value="<?php echo $activityDetailsID ?>">
                        <input type="hidden" name="detail[MAIN_INDEX][actualRate]" id="actualRate_MAIN_INDEX" value="<?php echo $appliedRate ?>" class="actualRate">
                        <input type="hidden" name="detail[MAIN_INDEX][actualRateDetail]" id="actualRateDetail_MAIN_INDEX" value="<?php echo $appliedDetailRate ?>" class="actualRateDetail">
                        <input type="hidden" name="detail[MAIN_INDEX][payableAmount]" id="payableAmount_h_MAIN_INDEX" class="payableAmountHidden" value="<?php echo $payableAmount ?>" />
                        <input type="hidden" name="detail[MAIN_INDEX][billingAmount]" id="billingAmount_h_MAIN_INDEX" class="billingAmountHidden" value="<?php echo $billingAmount ?>" />
                        <input type="hidden" name="detail[MAIN_INDEX][paymentStrategy]" id="paymentStrategy_MAIN_INDEX" class="paymentStrategyHidden" value="<?php echo $paymentStrategy ?>" />
                        <input type="hidden" name="detail[MAIN_INDEX][paymentStrategyPercent]" id="paymentStrategyPercent_MAIN_INDEX" class="paymentStrategyPercentHidden" value="<?php echo $paymentStrategyPercent ?>" />

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
                            <div class="col-md-6"><input type="text" class="form-control" name="detail[MAIN_INDEX][specialRateReason]" id="applySpecialRate_MAIN_INDEX_reason" placeholder="Reason for special rate" value="<?php echo $specialAmountReason ?>" /></div>
                            <div class="col-md-6 marginWrapper">
                                <div class="col-md-1 no-margin">&nbsp;</div>
                                <div class="col-md-3 no-margin"><?php echo form_input('detail[MAIN_INDEX][specialPaymentAmount]', $specialPayableAmount, 'id="specialPayableAmount_MAIN_INDEX" class="form-control payableAmount"  onkeyup="calculateSpecialBillingRate(this)"'); ?></div>
                                <div class="col-md-3 no-margin <?php echo $showMargins ?>">
                                    <div class="input-group">
                                        <?php echo form_input('detail[MAIN_INDEX][specialMargin]', $specialMargin, 'class="form-control percent marginValue" id="specialMargin_MAIN_INDEX" placeholder="margin" onkeyup="calculateBillingRate(this)"'); ?>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="col-md-3 no-margin <?php echo $showMargins ?>"><?php echo form_input('detail[MAIN_INDEX][specialBillingAmount]', $specialBillingAmount, ' onkeyup="calculateMargin(this)" id="specialBillingAmount_MAIN_INDEX" class="form-control billingAmount"'); ?></div>
                            </div>
                        </div>
                    </div>

                    <a style="position: absolute; top:0; right:1rem" title="remove" class="remove_rate" id="activity_remove_current" data-formIndex="MAIN_INDEX" onclick="return deleteActivity(<?php echo $activityID ?>, 'activity_templateMAIN_INDEX')"><i class="fa fa-times text-red"></i></a>

                    <div class="clear"></div>

                </div>
            <?php
            }
        }
        ?>

        <div class=col-md-12" id="activity_noforms_template">
            No Descriptions found.
        </div>
        <div class="col-md-12" id="activity_controls">
            <a href="#" id="activity_add" class="btn btn-sm btn-primary">Add Another Service</a>
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

    var serviceType = $('#serviceType'),
        activity = $('#activity'),
        hotel = $('#hotel');

    $('#arrivalDate, #departureDate, #market').bind('change', function(){
        clearAmount(null, '');
    });

    serviceType.change(function(){
        if( serviceType.val() != '' ){
            var remoteUrl = Yarsha.config.base_url+'hotel/ajax/getHotelsSelectByCategory/'+serviceType.val();
            var hotelID = '<?php echo $hotelID ?>';
            if( hotelID != '' ){
                remoteUrl = remoteUrl + '/' + hotelID;
            }
            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status && data.status == 'success' ){
                        hotel.html(data.options).val('<?php echo $hotelID ?>');
                        hotel.trigger('change');
                        getMarketOptions();
                    }else{
                        hotel.html('<option value="">-- CHOOSE PROPERTY --</option>');
                    }
                }
            });
        }
    });

    serviceType.trigger('change');

    hotel.change(function(){ getHotelOptions(); getMarketOptions() });

    hotel.trigger('change');

    $('#arrivalDate').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 'dateToday'
    });

    $('.timeOnly').inputmask('99:99');

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
            getHotelOptions(clone);
        },
        pregeneratedForms: <?php echo json_encode($activityDetailsTemplates) ?>

    });


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
            allocatedBedQuantity += parseInt(totalBeds);
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
    var type = '<?php echo \hotel\models\Rate::RATE_TYPE_SERVICE ?>',
        hotel = $('#hotel').val(),
        market = $('#market');

    market.html('<option value="">-- SELECT MARKET --</option>');

    if( hotel !== undefined && hotel != '' ){
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

    function getHotelOptions(obj){

        var hotel = $('#hotel');

        if( hotel.val() != '' ){
            $.ajax({
                type: 'GET',
                url: Yarsha.config.base_url+'hotel/ajax/getOutletsByHotel/'+hotel.val(),
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status && data.status == 'success' ){

                        if( obj == undefined || obj == '' ){
                            $.each($('.outlet'), function(i, v){
                                var _outlet = $(v),
                                    _outlet_val = _outlet.attr('data-outlet');
                                _outlet.html(data.options.outlets).val(_outlet_val);
                            });

                            $.each($('.service'), function(si, sv){
                                var _service = $(sv),
                                    _service_val = _service.attr('data-service');
                                _service.html(data.options.services).val(_service_val);
                            });
                        }else{
                            $(obj).find('.outlet').html(data.options.outlets);
                            $(obj).find('.service').html(data.options.services);
                        }


                    }
                }
            });
        }else{
            $('.outlet').html('<option value="">-- OUTLET --</option>');
            $('.service').html('<option value="">-- SERVICE --</option>');
        }

    }

    function getRate(obj, index, extraIndex){

        var self = $(obj);

        var hotel = $('#hotel').val(),
//            market = $('#market_id').val(),
            market = $('#market').val(),
            serviceType = $('#serviceType').val(),
            arrivalDate = $('#arrivalDate').val(),
            quantity = $('#quantity_'+index).val();


        var objIndex = ( extraIndex == '' || extraIndex == undefined )? index : index+'_'+extraIndex;

        var outlet = $('#outlet_'+objIndex).val(),
            service = $('#service_'+objIndex).val(),
            margin = $('#margin_'+objIndex).val();

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

        if( hotel == undefined || hotel == "" || serviceType == undefined || serviceType == "" ){
            msg = msg + 'Select Hotel and Booking type.';
            error = true;
        }

        if( arrivalDate == undefined || arrivalDate == ''  ){
            msg = msg + '\n Select Arrival and Departure date.';
            error = true;
        }

        if( service == undefined || service == "" ){
            msg = msg + '\n Select Service.';
            error = true;
        }

        if( market == undefined || market == "" ){
            msg = msg + '\n Select Market.';
            error = true;
        }

        if( quantity == undefined || quantity == "" || quantity == 0 ){
            msg = msg + '\n Select Number of Quantity.';
            error = true;
        }

        if( error == true ){
            Yarsha.notify('error', msg);
            return false;
        }

        var dataToSend = {
            'market' : market, 'arrivalDate':arrivalDate,  'hotel':hotel, 'serviceType': serviceType,
            'quantity':quantity, 'outlet':outlet, 'service':service, 'margin':margin
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


function calculateMargin(obj){
    var self = $(obj);
    var margin = self.closest('.marginWrapper').find('.marginValue'),
        payableAmount = self.closest('.marginWrapper').find('.payableAmount').val(),
        billingAmount = self.val();


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

    var margin = self.val(),
        payableAmount = self.closest('.marginWrapper').find('.payableAmount').val(),
        billingAmount = self.closest('.marginWrapper').find('.billingAmount');

    if($.isNumeric(margin) && $.isNumeric(payableAmount)){
        var calculatedAmount =  parseFloat(payableAmount) + ( parseFloat(margin) / 100  ) * parseFloat(payableAmount);
        calculatedAmount = Math.round(calculatedAmount * 100) / 100;
        billingAmount.val(calculatedAmount);
    }else{
        billingAmount.val('0.00');
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

</script>