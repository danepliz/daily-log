<?php

$arrivalDate = '';
$departureDate = '';
$hotelID = NULL;
$details = '';
$activityDetailsString = '';
$estimatedArrivalTime = '';
$estimatedDepartureTime = '';
$confirmationNumber= '';
$numberOfNights = '';
$arrivalDepartureNote = '';
$market = $file->getMarket()? $file->getMarket()->id() : '';
$activityDetailsTemplates = array();
$activityDetails = array();
$activityID = '';

if( isset($activity) and !is_null($activity)){
    $tourFile = $activity->getTourFile();
    $arrivalDate = ($activity->getArrivalDate())? $activity->getArrivalDate()->format('Y-m-d') : '';
    $departureDate = ($activity->getDepartureDate())? $activity->getDepartureDate()->format('Y-m-d') : '';
    $hotel = $activity->getHotel();
    $hotelID = ($activity->getHotel())? $activity->getHotel()->id() : NULL;
    $details = $activity->getDescription();
    $activityID = $activity->id();

    $activityDetails = $activity->getDetails();

    $estimatedArrivalTime = $activity->getArrivalTime()? $activity->getArrivalTime()->format('H:s') : '';
    $estimatedDepartureTime = $activity->getDepartureTime()? $activity->getDepartureTime()->format('H:s') : '';
    $confirmationNumber= $activity->getConfirmationNumber();
    $numberOfNights = $activity->getNumberOfNights();
    $arrivalDepartureNote = $activity->getArrivalDepartureNote();

}


echo inputWrapper('arrival_date', 'Arrival Date', $arrivalDate, 'class="form-control datepicker required" placeholder="arrival date" id="arrival_date"',  'col-md-3');
echo inputWrapper('estimatedArrivalTime','Estimated Time Of Arrival', $estimatedArrivalTime, 'placeholder="arrival time" class="form-control timeOnly" id="estimatedArrivalTime"', 'col-md-3');

echo inputWrapper('departure_date', 'Departure Date', $departureDate, 'class="form-control datepicker required" placeholder="departure date" id="departure_date"', 'col-md-3');
echo inputWrapper('estimatedDepartureTime','Estimated Time Of Departure', $estimatedDepartureTime, 'placeholder="departure time" class="form-control timeOnly" id="estimatedDepartureTime"', 'col-md-3');
echo clearDiv();
?>
<div class="form-group-sm col-md-9">
    <label for="hotel">Hotel</label>
    <?php getHotelSelectionElementForXo('hotel', $hotelID, 'class="form-control required" id="hotel"') ?>
</div>

<?php
echo inputWrapper('confirmationNumber','Confirmation Number', $confirmationNumber, 'placeholder="if available" class="form-control" id="confirmationNumber"', 'col-md-3');
echo clearDiv();
?>

<div class="form-group-sm col-md-12">

    <div class="col-md-12" id="activity">

        <div class="row">
            <div class="col-md-1"><label>Qty</label></div>
            <div class="col-md-2"><label>Category</label></div>
            <div class="col-md-2"><label>Type</label></div>
            <div class="col-md-2"><label>Plan</label></div>
            <div class="col-md-2"><label>Total</label></div>
            <div class="col-md-1"><label>Mark As Complimentary</label></div>
            <div class="col-md-1"><label>Apply Special Rate</label></div>
            <div class="col-md-1"><label>&nbsp</label></div>
        </div>

        <div class="clear"></div>

        <div class="row hotelActivityWrapper" id="activity_template">
            <div class="col-md-1"><input type="text" class="form-control number_only" id="quantity_#index#" name="detail[#index#][quantity]" /></div>
            <div class="col-md-2"><?php echo form_dropdown('detail[#index#][category]', array('' => '-- CATEGORY --'), NULL, 'class="form-control category" id="category_#index#"') ?></div>
            <div class="col-md-2"><?php echo form_dropdown('detail[#index#][type]', array('' => '-- TYPE --'), NULL, 'class="form-control type" id="type_#index#"') ?></div>
            <div class="col-md-2"><?php echo form_dropdown('detail[#index#][plan]', array('' => '-- PLAN --'), NULL, 'class="form-control plan" id="plan_#index#"') ?></div>
            <div class="col-md-2">
                <span id="calculate_#index#" onclick="return getRate(this)"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                <span>&nbsp; &nbsp;</span>
                <input type="hidden" name="detail[#index#][activityDetailID]" id="activity_detail_id_#index#" value="">
                <input type="hidden" name="detail[#index#][actualRate]" id="actualRate_#index#" value="">
                <input type="hidden" class="form-control number_only" name="detail[#index#][actualAmount]" readonly="readonly" id="actualAmount_#index#"/>
                <span  id="showActualAmount_#index#"></span>
            </div>
            <div class="col-md-1">
                <input type="checkbox" class="simple complimentary" name="detail[#index#][complimentary]" id="complimentary_#index#" />
            </div>
            <div class="col-md-1"><input type="checkbox" class="simple applySpecialRate" onclick="return showHideSpecialRate(this)" name="detail[#index#][applySpecialRate]" id="applySpecialRate_#index#" /></div>
            <div class="col-md-1"><a id="activity_remove_current" title="remove" class="remove_rate"><i class="fa fa-times text-red"></i></a></div>

            <div class="clear"></div>

            <div class="row specialAmountWrapper hidden" id="applySpecialRate_#index#_desc">
                <div class="col-md-4 text-right"><label>Special Rate</label></div>
                <div class="col-md-3"><input type="text" class="form-control number_only" name="detail[#index#][specialRate]" id="applySpecialRate_#index#_rate" placeholder="price"/></div>
                <div class="col-md-5"><input type="text" class="form-control" name="detail[#index#][specialRateReason]" id="applySpecialRate_#index#_reason" placeholder="reason"/></div>
            </div>
        </div>

        <?php
        if( count($activityDetails) >0 ){
            foreach($activityDetails as $ad){

                $activityTempId = 'hotelActivityWrapper_'.$ad->id();
                $activityDetailsTemplates[] = $activityTempId;
                $activityDetailsID = $ad->id();

                $roomCategory = ( $ad->getRoomCategory() ) ? $ad->getRoomCategory()->id() : NULL;
                $roomType = ( $ad->getRoomType() ) ? $ad->getRoomType()->id() : NULL;
                $roomPlan = ( $ad->getRoomPlan() ) ? $ad->getRoomPlan()->id() : NULL;

                $appliedRate = ($ad->getHotelRate()) ? $ad->getHotelRate()->id() : '';
                $actualAmount = $ad->getTotalAmount();
                $isComplimentary = ( $ad->isComplimentary() )? 'checked="checked"' : '';

                $isSpecialRateAppliedCheck = '';
                $isSpecialRateAppliedClass = 'hidden';
                $specialAmount = '';
                $specialAmountReason = '';

                if( $ad->isSpecialRateApplied() ){
                    $isSpecialRateAppliedCheck = 'checked="checked"';
                    $isSpecialRateAppliedClass = '';
                    $specialAmount = $ad->getSpecialRate();
                    $specialAmountReason = $ad->getReasonForSpecialRate();
                }

                $billingRate = ( $ad->getHotelRate() ) ? number_format($ad->getHotelRate()->getBillingRate(), 2, '.', '') : '00.00';
                $actualTotalAmount = $ad->getTotalAmount() ? number_format($ad->getTotalAmount(), 2, '.', '') : '00.00';


        ?>
            <div class="row hotelActivityWrapper" id="<?php echo $activityTempId ?>">
                <div class="col-md-1"><input type="text" class="form-control number_only" id="quantity_#index#" name="detail[#index#][quantity]" value="<?php echo $ad->getNumberOfRooms() ?>" /></div>
                <div class="col-md-2"><?php getSelectRoomCategoriesByHotel($hotelID, 'detail[#index#][category]', $roomCategory, 'class="form-control category" id="category_#index#"') ?></div>
                <div class="col-md-2"><?php getSelectRoomTypesByHotel($hotelID, 'detail[#index#][type]', $roomType, 'class="form-control type" id="type_#index#"') ?></div>
                <div class="col-md-2"><?php getSelectRoomPlansByHotel($hotelID, 'detail[#index#][plan]', $roomPlan, 'class="form-control plan" id="plan_#index#"') ?></div>
                <div class="col-md-2">
                    <span id="calculate_#index#" onclick="return getRate(this)"><i class="glyphicon glyphicon-circle-arrow-right"></i></span>
                    <span>&nbsp; &nbsp;</span>
                    <input type="hidden" name="detail[#index#][activityDetailID]" id="activity_detail_id_#index#" value="<?php echo $activityDetailsID ?>">
                    <input type="hidden" name="detail[#index#][actualRate]" id="actualRate_#index#" value="<?php echo $appliedRate ?>">
                    <input type="hidden" class="form-control number_only" name="detail[#index#][actualAmount]" readonly="readonly" id="actualAmount_#index#" value="<?php echo $actualAmount ?>"/>
                    <span  id="showActualAmount_#index#"><?php echo $actualTotalAmount.'@'.$billingRate ?></span>
                </div>
                <div class="col-md-1">
                    <input type="checkbox" class="simple complimentary" name="detail[#index#][complimentary]" id="complimentary_#index#" <?php echo $isComplimentary ?> />
                </div>
                <div class="col-md-1"><input type="checkbox" class="simple applySpecialRate" onclick="return showHideSpecialRate(this)" name="detail[#index#][applySpecialRate]" id="applySpecialRate_#index#" <?php echo $isSpecialRateAppliedCheck ?> /></div>
                <div class="col-md-1"><a id="activity_remove_current" title="remove" class="remove_rate"><i class="fa fa-times text-red"></i></a></div>

                <div class="clear"></div>

                <div class="row specialAmountWrapper <?php echo $isSpecialRateAppliedClass ?>" id="applySpecialRate_#index#_desc">
                    <div class="col-md-4 text-right"><label>Special Rate</label></div>
                    <div class="col-md-3"><input type="text" class="form-control number_only" name="detail[#index#][specialRate]" id="applySpecialRate_#index#_rate" placeholder="price" value="<?php echo $specialAmount ?>" /></div>
                    <div class="col-md-5"><input type="text" class="form-control" name="detail[#index#][specialRateReason]" id="applySpecialRate_#index#_reason" placeholder="reason" value="<?php echo $specialAmountReason ?>"/></div>
                </div>
            </div>
        <?php }
        }
//            echo $activityDetailsString;
        ?>

        <div class=col-md-12" id="activity_noforms_template">
            No Descriptions found.
        </div>
        <div class="col-md-12" id="activity_controls">
            <a href="#" id="activity_add" class="btn btn-sm btn-primary">Add Another Room</a>
        </div>

    </div>

    <input type="hidden" name="market_id" id="market_id" value="<?php echo $market ?>" />
    <input type="hidden" name="activity_id" id="activity_id" value="<?php echo $activityID ?>" />
    <input type="hidden" name="activity_type" id="activity_type" value="HOTEL" />

</div>

<?php

echo clearDiv();
echo textAreaWrapper('noteOfArrivalAndDeparture', 'Note Of Arrival and Departure', $arrivalDepartureNote, 'class="form-control"', 'col-md-6');
echo textAreaWrapper('description', 'Details', $details, 'class="form-control"', 'col-md-6');
echo clearDiv();

?>

<?php loadJS(array('jquery.sheepit.min', 'jquery.inputmask')) ?>
<script type="text/javascript">
    $(document).ready(function(){

        var hotel = $('#hotel'),
            activity = $('#activity');


        $('#arrival_date').datepicker({
            dateFormat: 'yy-mm-dd',
            onClose: function(selectedDate) {
                $("#departure_date").datepicker("option", "minDate", selectedDate);
            }
        });

        $('#departure_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });

//        $('.timeOnly').inputmask('99:99', { "placeholder" : "00:00"});
        $('.timeOnly').inputmask('99:99');


        hotel.bind('change', function(){
            getHotelRoomOptions(activity);
        });


        var sheepItXO = activity.sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 0,
            minFormsCount: 1,
            iniFormsCount: 1,
            afterClone: function(source, clone) {
                getHotelRoomOptions(clone);
            },
            pregeneratedForms: <?php echo json_encode($activityDetailsTemplates) ?>
        });

    });

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


    function getRate(obj){

        var obj = $(obj),
            id = obj.attr('id').split('_'),
            index = id[1],
            quantity = $('#quantity_'+index).val(),
            category = $('#category_'+index).val(),
            type = $('#type_'+index).val(),
            plan = $('#plan_'+index).val(),
            hotel = $('#hotel').val(),
            market = $('#market_id').val(),
            arrivalDate = $('#arrival_date').val(),
            departureDate = $('#departure_date').val(),
            actualRate = $('#actualRate_'+index),
            actualAmount = $('#actualAmount_'+index),
            showAmount = $('#showActualAmount_'+index);


        showAmount.html('');

        if( hotel == undefined || hotel == "" ){
            Yarsha.notify('warn', 'Please select the hotel first');
            return false;
        }

        if( arrivalDate == undefined || arrivalDate == '' || departureDate == undefined || departureDate == '' ){
            Yarsha.notify('warn', 'Please give us arrival and departure date, so that we can calculate rates for you');
            return false;
        }

        var dataToSend = {  'hotel':hotel, 'category':category, 'type':type, 'plan':plan, 'market':market,
                            'arrivalDate':arrivalDate, 'departureDate':departureDate, 'quantity':quantity
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
                    }else if( data.isExpiring && data.isExpiring == true){
                        Yarsha.notify('info', 'Rate is expiring on '+ data.expiryDate);
                        actualRate.val(data.actualRate);
                        actualAmount.val(data.actualBillingRate);
                        showAmount.html(data.actualBillingRate+'@'+data.billingRate);
                    }else{
                        actualRate.val(data.actualRate);
                        actualAmount.val(data.actualBillingRate);
                        showAmount.html(data.actualBillingRate+'@'+data.billingRate);
                    }
                }else{
                    //'Something wents wrong. Please try again. '+
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
            objDesc.removeClass('hidden');
        }else{
            objDesc.addClass('hidden');
        }
    }

</script>
