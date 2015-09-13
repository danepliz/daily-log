<?php
$hasAccess = user_access('manage hotel rates');
$hotelID = $hotel->id();
$pre_filled_forms = [];
$preFilledServiceRateIds = [];
$strategy = \hotel\models\Hotel::$paymentStrategies[$hotel->getPaymentStrategy()];
$percent = $hotel->getPaymentStrategyPercent();
$payableCurrencies = $hotel->getPayableCurrencies();


$hotelRateFormOpen = ( user_access('manage hotel rates') ) ? '<form role="form" method="POST" action="" class="validate">' : '';
$hotelServiceRateFormOpen = ( user_access('manage hotel service rates') ) ? '<form role="form" method="POST" action="" class="validate">' : '';
$formClose = '</form>';
?>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="javascript:void(0);" class="tabbed" rel="hotelRates" data-toggle="tab">Hotel Rates</a></li>
            <li role="presentation"><a href="javascript:void(0);" class="tabbed" rel="hotelService" data-toggle="tab">Hotel Services</a></li>
        </ul>
        <div class="tab-content">

            <div class="row">
                <?php echo $hotelRateFormOpen ?>
                <div class="tab-pane" id="hotelRates">
                    <div class="col-md-12">
                        <div class="panel panel-default">

                            <div class="col-md-12 margin bg-gray"><?php echo 'Payment Strategy : '.$strategy.'( '.$percent.'% )' ?></div>

                            <table class="table table-responsive">
                                <tbody id="rates">
                                <tr>
                                    <th>Market</th>
                                    <th>Room Category</th>
                                    <th>Room Type</th>
                                    <th>Room Plan</th>
                                    <th style="width:20rem">Payable Rate</th>
                                    <!--                                    <th>Payable Rate</th>-->
                                    <th>Expiry Date</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <tr id="rates_template" class="form-group-sm">
                                    <td><?php getSelectMarketElement('rates[#index#][market]', NULL, 'class="form-control market"') ?></td>
                                    <td><?php getSelectRoomCategoriesByHotel($hotelID, 'rates[#index#][category]', NULL, 'class="form-control" id', FALSE) ?></td>
                                    <td><?php getSelectRoomTypesByHotel($hotelID, 'rates[#index#][type]', NULL, 'class="form-control"', FALSE) ?></td>
                                    <td><?php getSelectRoomPlansByHotel($hotelID, 'rates[#index#][plan]', NULL, 'class="form-control required"', FALSE) ?></td>
                                    <td>
                                        <?php
                                        foreach($payableCurrencies as $pc){
                                            echo '<div class="input-group"><span class="input-group-addon">'.$pc->getIso3().'</span>';
                                            echo form_input('rates[#index#][payableRate]['.$pc->id().']', '0.00', 'class="form-control percent" onblur="calculateAmount(this)"');
                                            echo '<span class="input-group-addon" id="prates#index#payableRate'.$pc->id().'">00.000</span>';
                                            echo '</div>';
                                        }
                                        ?>
                                        <!--                                        <input type="text" name="rates[#index#][payableRate]" value="0.00" class="form-control money" />-->
                                    </td>
                                    <td><input type="text" name="rates[#index#][expiryDate]" class="form-control exDate" /></td>
                                    <td><a id="rates_remove_current" title="remove" class="remove_rate" data-rate-id=""><i class="fa fa-times text-red"></i></a></td>
                                </tr>

                                <?php
                                if( $rates and count($rates) > 0 ){
                                    $count = 0;
                                    $pre_filled_forms = array();
                                    foreach($rates as $r){
                                        $id = 'pre_filled_form_'.$count;
                                        $pre_filled_forms[] = $id;
                                        $marketID = $r->getMarket()? $r->getMarket()->id() : NULL;
                                        $categoryID = $r->getRoomCategory()? $r->getRoomCategory()->id() : NULL;
                                        $typeID = $r->getRoomType()? $r->getRoomType()->id() : NULL;
                                        $planID = $r->getRoomPlan()? $r->getRoomPlan()->id() : NULL;
                                        $expiryDate = $r->getExpiryDate() ? $r->getExpiryDate()->format('Y-m-d') : '';
                                        $rateDetails = $r->getRateDetails();
                                        $p_rate = [];
                                        if( count($rateDetails) ){
                                            foreach($rateDetails as $rd){
                                                $p_rate[$rd->getCurrency()->id()] = $rd->getPayableRate();
                                            }
                                        }
                                        ?>
                                        <tr id="<?php echo $id ?>" class="form-group-sm">
                                            <td><?php getSelectMarketElement('rates[#index#][market]', $marketID, 'class="form-control"') ?></td>
                                            <td><?php getSelectRoomCategoriesByHotel($hotelID, 'rates[#index#][category]', $categoryID, 'class="form-control"', FALSE) ?></td>
                                            <td><?php getSelectRoomTypesByHotel($hotelID, 'rates[#index#][type]', $typeID, 'class="form-control"', FALSE) ?></td>
                                            <td><?php getSelectRoomPlansByHotel($hotelID, 'rates[#index#][plan]', $planID, 'class="form-control required"', FALSE) ?></td>
                                            <td>
                                                <?php
                                                foreach($payableCurrencies as $pc){
                                                    $currencyID = $pc->id();
                                                    $payableRate = ( isset($p_rate[$currencyID]) )? $p_rate[$currencyID] : '0.00';
                                                    echo '<div class="input-group"><span class="input-group-addon">'.$pc->getIso3().'</span>';
                                                    echo form_input('rates[#index#][payableRate]['.$currencyID.']', $payableRate, 'class="form-control percent" onblur="calculateAmount(this)"');
                                                    $prate = calculatePayableAmount($payableRate, $percent);
                                                    echo '<span class="input-group-addon" id="prates#index#payableRate'.$currencyID.'">'.$prate.'</span>';
                                                    echo '</div>';
                                                }
                                                ?>
                                            </td>
                                            <td><input type="text" name="rates[#index#][expiryDate]" value="<?php echo $expiryDate ?>" class="form-control exDate" /></td>
                                            <td><a id="rates_remove_current" title="remove" class="remove_rate" data-rate-id="<?php echo $r->id() ?>"><i class="fa fa-times text-red"></i></a></td>
                                        </tr>
                                    <?php
                                    }
                                }
                                ?>

                                <tr id="rates_noforms_template">
                                    <td colspan="8">No rates available</td>
                                </tr>

                                <tr id="rates_controls">
                                    <td colspan="8">
                                        <a href="#" id="rates_add" class="btn btn-sm btn-primary">Add Another Rate</a>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if(user_access('manage hotel rates')) { ?>
                        <div class="col-md-12">
                            <br/>
                            <input type="submit" value="SAVE HOTEL RATES" class="btn btn-primary btn-margin" name="rate_hotel">
                            <a href="<?php echo site_url('hotel') ?>" class="btn btn-danger btn-margin" >CANCEL</a>
                        </div>
                    <?php } ?>
                </div>
                <?php echo $formClose ?>


                <!-- Hotel Service Rates -->
                <form class="validate" action="" method="post">
                    <div class="tab-pane" id="hotelService" style="display: none;">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <?php ?>
                                <table class="table table-responsive">
                                    <tbody id="services">
                                    <tr>
                                        <th style="width:30rem">Service Type</th>
                                        <th style="width:20rem">Payable Rate</th>
                                        <th>Expiry DATE</th>
                                    </tr>

                                    <tr id="services_template" class="form-group-sm">
                                        <td><?php getSelectServiceElement('services[#index#][service]', NULL, 'class="form-control service"') ?></td>
                                        <td>
                                            <?php
                                            foreach($payableCurrencies as $pc){
                                                echo '<div class="input-group"><span class="input-group-addon">'.$pc->getIso3().'</span>';
                                                echo form_input('services[#index#][payableRate]['.$pc->id().']', '0.00', 'class="form-control percent" onblur="calculateAmount(this)"');
                                                echo '<span class="input-group-addon" id="prates#index#payableRate'.$pc->id().'">00.000</span>';
                                                echo '</div>';
                                            }
                                            ?>
                                        </td>

                                        <td style="width:30rem"><input type="text" name="services[#index#][expiryDate]" class="form-control sexDate" /></td>
                                        <td style="width:5rem"><a id="services_remove_current" title="remove" class="remove_service" data-rate-id=""><i class="fa fa-times text-red"></i></a></td>

                                    </tr>

                                    <?php

                                    if( count($hotelServiceRates) ){
                                        $sCount = 0;
                                        foreach( $hotelServiceRates as $hsr ){
                                            $tempId = 'pre_filled_form_service'.$sCount;
                                            $preFilledServiceRateIds[] = $tempId;
                                            $serviceId = $hsr->getService() ? $hsr->getService()->id() : NULL;
                                            $serviceRateDetails = $hsr->getRateDetails();
                                            $serviceRateDetailsList = [];
                                            if( count($serviceRateDetails) ){
                                                foreach($serviceRateDetails as $srd){
                                                    $serviceRateDetailsList[$srd->getCurrency()->id()] = $srd->getPayableRate();
                                                }
                                            }
                                            $rExpDate = $hsr->getExpiryDate()->format('Y-m-d');
                                            $sCount++;
                                            ?>
                                            <tr id="<?php echo $tempId ?>" class="form-group-sm">
                                                <td><?php getSelectServiceElement('services[#index#][service]', $serviceId, 'class="form-control"') ?></td>
                                                <td>
                                                    <?php
                                                    foreach($payableCurrencies as $pc){
                                                        $cAmt = ( array_key_exists( $pc->id(), $serviceRateDetailsList ) )? $serviceRateDetailsList[$pc->id()] : '0.00';
                                                        $pAmt = $cAmt + ( ( $percent / 100 ) * $cAmt ) ;
                                                        echo '<div class="input-group"><span class="input-group-addon">'.$pc->getIso3().'</span>';
                                                        echo form_input('services[#index#][payableRate]['.$pc->id().']', $cAmt, 'class="form-control percent" onblur="calculateAmount(this)"');
                                                        echo '<span class="input-group-addon" id="prates#index#payableRate'.$pc->id().'">'.$pAmt.'</span>';
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                </td>

                                                <td style="width:30rem"><input type="text" name="services[#index#][expiryDate]" class="form-control sexDate" value="<?php echo $rExpDate ?>" /></td>
                                                <td style="width:5rem"><a id="services_remove_current" title="remove" class="remove_service" data-rate-id=""><i class="fa fa-times text-red"></i></a></td>

                                            </tr>
                                        <?php } } ?>

                                    <tr id="services_noforms_template">
                                        <td colspan="8">No rates available</td>
                                    </tr>
                                    <tr id="services_controls">
                                        <td colspan="8">
                                            <a href="#" id="services_add" class="btn btn-sm btn-primary">Add Another Service</a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <?php ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <br/>
                            <input type="submit" value="SAVE HOTEL SERVICES RATES" class="btn btn-primary btn-margin" name="service_hotel"/>
                            <a href="<?php echo site_url('hotel') ?>" class="btn btn-danger btn-margin" >CANCEL</a>

                        </div>

                    </div>

                </form>

                <!-- Hotel Service Rates End -->
                <?php //$this->load->theme('hotel/rates/service', $data); ?>

            </div>


        </div>
    </div>
</div>
</div>

<?php loadJS(array('jquery.sheepit.min')); ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#market, #room_category, #country').select2();

        var sheepItForm = $('#rates').sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 0,
            minFormsCount: 1,
            iniFormsCount: 1,
            afterAdd: function(source, newForm) {
                $('.exDate').datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            },
            pregeneratedForms: <?php echo json_encode($pre_filled_forms); ?>
        });

        var sheepItForm = $('#services').sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 0,
            minFormsCount: 1,
            iniFormsCount: 1,
            afterAdd: function(source, newForm) {
                $('.sexDate').datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            },
            pregeneratedForms: <?php echo json_encode($preFilledServiceRateIds); ?>

        });

        $('.service, .market').select2();

        $('.exDate, .sexDate').datepicker({
            dateFormat: 'yy-mm-dd'
        });


        $('.remove_rate').click(function(){

            var rate_id = $(this).attr('data-rate-id');

            if( rate_id !== "" ){
                alert('You can only update this data.');
                return false;
            }

        });

        $('.remove_service').click(function(){

            var rate_id = $(this).attr('data-rate-id');

            if( rate_id !== "" ){
                alert('You can only update this data.');
                return false;
            }

        });

        $('a.tabbed').click(function(){
            $('ul.nav-tabs li').removeClass('active');
            $(this).parent('li').addClass('active');
            var chk=$(this).attr('rel');
            $('div.tab-pane').hide();
            $('div#'+chk).show();
        })
    });

    function calculateAmount(obj){
        var _obj = $(obj),
            name = _obj.attr('name'),
            descObjId = 'p'+ name.replace(/\[/g,'').replace(/\]/g,''),
            descObj = $('#'+descObjId),
            percent = parseFloat('<?php echo $percent ?>'),
            amount = parseFloat(_obj.val());

        var pamt = ( amount  + ( percent / 100 ) * amount );
        descObj.html(pamt.toFixed(3));
    }

</script>