<?php
use hotel\models\Hotel;

loadJS(array('jquery.sheepit.min'));

$city = $hotel->getCity();
$countryID = ( $hotel->getCountry() )? $hotel->getCountry()->id() : NULL;
$oldEmails = array();
$oldPhones = array();
$phones = $hotel->getPhones();
$emails = $hotel->getEmails();
//$booking ="";

?>
<form role="form" method="post" action="<?php echo site_url('hotel/edit/'.$hotel->slug()) ?>" class="validate">
<div class="row">

    <div class="col-md-6">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Basic Information</h3>
            </div>
            <div class="panel-body">
                <div class="form-group-sm">
                    <label for="name">Hotel Category</label>
                    <?php getHotelCategorySelectElement('hotel_category', $hotel->getCategory()->id(), 'class="required form-control" id="hotel-category-select"') ?>
                </div>

                <div class="form-group-sm">
                    <label for="name">Hotel Grade</label>
                    <?php getHotelGradeSelectElement('hotel_grade', $hotel->getGrade()->id(), 'class="required form-control" id="hotel-grade-select"') ?>
                </div>

                <div class="form-group-sm">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="required form-control" value="<?php echo $hotel->getName() ?>"/>
                </div>

                <div class="form-group-sm">
                    <label for="name">Hotel Status</label>
                    <?php getHotelStatusSelectElement('hotel_status', $hotel->getStatus(), 'class="required form-control" id="hotel-status-select"') ?>
                </div>
            </div>
        </div>

        <!-- contact information -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Contact Information</h3>
            </div>
            <div class="panel-body">
                <div class="form-group-sm">
                    <label for="fax">Fax</label>
                    <input type="text" class="form-control number_only" name="fax" value="<?php echo $hotel->getFax() ?>" />
                </div>

                <div class="form-group-sm">
                    <label for="website1">Website 1</label>
                    <input type="text" id="contact_website" class="form-control website_url" name="website1"  value="<?php echo $hotel->getWebsite1() ?>" />
                </div>

                <div class="form-group-sm">
                    <label for="website2">Website 2</label>
                    <input type="text" id="contact_website" class="form-control website_url" name="website2"  value="<?php echo $hotel->getWebsite2() ?>" />
                </div>

                <div class="form-group-sm">
                    <label>Contact Phone</label>
                    <div id="contactPhone" class="sheepit_template">
                        <div id="contactPhone_template" class="sheepit_template_input_wrapper">
                            <div class="col-md-11">
                                <input type="text" name="contact_phone[#index#]" class="  form-control required number_only ">
                            </div>
                            <div class="col-md-1">
                                <a id="contactPhone_remove_current" title="remove"><i class="icon fa fa-times"></i></a>
                            </div>
                        </div>

                        <?php
                        if(count($phones) > 0){
                            $phCount = 0;
                            foreach($phones as $ph){
                                $phID = 'contactPhone_template_'.$phCount;
                                $oldPhones[] = $phID;
                                ?>
                                <div id="<?php echo $phID ?>" class="sheepit_template_input_wrapper">
                                    <div class="col-md-11">
                                        <input type="text" name="contact_phone[#index#]" class="  form-control required number_only " value="<?php echo $ph ?>">
                                    </div>
                                    <div class="col-md-1">
                                        <a id="contactPhone_remove_current" title="remove"><i class="icon fa fa-times"></i></a>
                                    </div>
                                </div>
                            <?php
                                $phCount++;
                            }
                        }

                        ?>

                        <div id="contactPhone_noforms_template" class="col-md-12">No Contact Phone</div>
                        <div id="contactPhone_controls" class="col-md-12">
                            <div id="contactPhone_add">
                                <a class="btn btn-default"><i class="fa fa-plus-square"></i>&nbsp; Add Another Contact Phone</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group-sm">
                    <label>Contact Email</label>
                    <div id="contactEmail" class="sheepit_template">
                        <div id="contactEmail_template" class="sheepit_template_input_wrapper">
                            <div class="col-md-11">
                                <input type="text" name="contact_email[#index#]" class="  form-control required email " >
                            </div>
                            <div class="col-md-1">
                                <a id="contactEmail_remove_current" title="remove"><i class="icon fa fa-times"></i></a>
                            </div>
                        </div>

                        <?php
                        if(count($emails) > 0){
                            $emCount = 0;
                            foreach($emails as $em){
                                $emID = 'contactEmail_template_'.$emCount;
                                $oldEmails[] = $emID;
                                ?>
                                <div id="<?php echo $emID ?>" class="sheepit_template_input_wrapper">
                                    <div class="col-md-11">
                                        <input type="text" name="contact_email[#index#]" class="  form-control required email " value="<?php echo $em ?>">
                                    </div>
                                    <div class="col-md-1">
                                        <a id="contactEmail_remove_current" title="remove"><i class="icon fa fa-times"></i></a>
                                    </div>
                                </div>
                            <?php
                                $emCount++;
                            }
                        }
                        ?>

                        <div id="contactEmail_noforms_template" class="col-md-12">No Contact Email</div>
                        <div id="contactEmail_controls" class="col-md-12">
                            <div id="contactEmail_add">
                                <a class="btn btn-default"><i class="fa fa-plus-square"></i>&nbsp; Add Another Contact Email</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- end contact information -->
    </div>


    <div class="col-md-6">
        <!-- location -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Hotel Location</h3>
            </div>
            <div class="panel-body">
                <div class="box-body">

                    <div class="form-group-sm">
                        <label for="country">Country</label>
                        <?php getCountrySelectElement('country', $countryID, 'class="form-control required" id="country"') ?>
                    </div>

                    <div class="form-group-sm">
                        <label for="city">City</label>
                        <?php echo form_input('city', $city, 'class="form-control required" id="city" placeholder="city"'); ?>
                    </div>

                    <div class="form-group-sm">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="required form-control"><?php echo $hotel->getAddress() ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- ! location -->

        <!-- payment strategies -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Payment Strategy</h3>
            </div>
            <div class="panel-body">


                <div class="form-group-sm col-md-6 no-margin">
                    <label for="payment_strategy">Strategy Type</label>
                    <?php
                        $paymentStrategies = Hotel::$paymentStrategies;
                        $checkedVal = $hotel->getPaymentStrategy();
                        echo form_dropdown('payment_strategy', $paymentStrategies, $checkedVal, 'class="form-control required"');
                    ?>
                </div>

                <div class="form-group-sm col-md-6 no-margin">
                    <label for="percentage">Percentage<em class="required">*</em></label>
                    <div class="input-group">
                        <input type="text" name="strategy_percent" value="<?php echo $hotel->getPaymentStrategyPercent() ?>" class="required form-control percent" />
                        <span class="input-group-addon">%</span>
                    </div>
                </div>


            </div>
        </div>
        <!-- end payment strategies -->

<!--        booking basis-->

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Booking Type</h3>
            </div>
            <div class="panel-body">
                <?php
            $package = $hotel->hasBookingTypePackageBasis();
            $room = $hotel->hasBookingTypeRoomBasis();
                $package_checked = " ";
                $room_checked = " ";
                ($package==true)?$package_checked="checked":$package_checked=" ";
                ($room==true)?$room_checked="checked":$room_checked=" ";

                ?>

            <input type="checkbox" name="room_basis" value="Room Basis" <?php echo $room_checked ?>><label>&nbsp;&nbsp;Room Basis</label>
            &nbsp;&nbsp;&nbsp;
            <input type="checkbox" <?php echo $package_checked; ?> name="package_basis" value="Package Basis"><label>&nbsp;&nbsp;Package Basis</label><br/>

<!--                --><?php
//                $bookingType = Hotel::$bookingTypes;
//                $hotelBookingType = $hotel->getBookingType();
//
//                foreach($bookingType as $bk => $bv){
//                    $checked = ( $bk == $hotelBookingType )? 'checked="checked"' : '';
//                ?>
<!--                <div class="form-group-sm col-md-6 no-margin">-->
<!--                    <label>-->
<!--                        <input type="radio" name="booking"  class="simple" value="--><?php //echo $bk ?><!--" --><?php //echo $checked ?><!--  >-->
<!--                        &nbsp;--><?php //echo $bv ?>
<!--                    </label>-->
<!--                </div>-->
<!---->
<!---->
<!--                --><?php //} ?>

            </div>

        </div>
<!--        end booking basis-->
<!--        rate variation strategy-->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Rate Variation Strategy</h3>
            </div>
            <div class="panel-body">
                <?php
                $rateVariationStrategy = Hotel::$rateVariationStrategies;
                $hotelRateVariationType = $hotel->getRateVariationStrategy();

                foreach($rateVariationStrategy as $rk => $rv){
                    $checked = ( $rk == $hotelRateVariationType )? 'checked="checked"' : '';
                    ?>
                    <div class="form-group-sm col-md-6 no-margin">
                        <label>
                            <input type="radio" name="rate_variation"  class="simple" value="<?php echo $rk ?>" <?php echo $checked ?>  >
                            &nbsp;<?php echo $rv ?>
                        </label>
                    </div>


                <?php } ?>

            </div>

        </div>
<!--        end rate variation strategy-->


        <!-- others -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Others</h3>
            </div>
            <div class="panel-body">
                <div class="form-group-sm">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" placeholder="other details" style="min-height: 14rem;" ><?php echo $hotel->getOthers() ?></textarea>
                </div>
            </div>
        </div>
        <!-- end others -->
   </div>
    <div class="col-md-12">

        <input type="submit" value="SAVE HOTEL" class="btn btn-primary">
        <input type="reset" value="CLEAR DATA" class="btn btn-primary">
        <a href="<?php echo site_url('hotel') ?>" class="btn btn-danger" >CANCEL</a>
    </div>
</form>


<script type="text/javascript">
    $(document).ready(function(){
        $('#hotel-category-select, #hotel-grade-select, #hotel-status-select, #country').select2();
        $(function() {
            $('input[name=booking]').on('click init-post-format', function() {
                $('#packages').toggle($('#package').prop('checked'));
            }).trigger('init-post-format');
        });

        var contactPhone = $("#contactPhone").sheepIt({
            separator: "",
            allowRemoveLast: false,
            allowRemoveCurrent: true,
            allowRemoveAll: false,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 4,
            minFormsCount: 1,
            iniFormsCount: 1,
            pregeneratedForms: <?php echo json_encode($oldPhones) ?>
        });

        var contactEmail = $("#contactEmail").sheepIt({
            separator: "",
            allowRemoveLast: false,
            allowRemoveCurrent: true,
            allowRemoveAll: false,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 4,
            minFormsCount: 1,
            iniFormsCount: 1,
            pregeneratedForms: <?php echo json_encode($oldEmails) ?>
        });

        var sheepItForm = $('#sheepItForm').sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
//            allowAddN: true,
            maxFormsCount: 4,
            minFormsCount: 1,
            iniFormsCount: 1

        });


        var sheepItForm1 = $('#sheepItForm1').sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
//            allowAddN: true,
            maxFormsCount: 4,
            minFormsCount: 1,
            iniFormsCount: 1
        });



    });

</script>



