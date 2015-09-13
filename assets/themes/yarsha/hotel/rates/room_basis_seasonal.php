<?php
$strategy = \hotel\models\Hotel::$paymentStrategies[$hotel->getPaymentStrategy()];
$percent = $hotel->getPaymentStrategyPercent();
$hotelID = $hotel->id();
$roomBasisSeasonalPreFilledForm = [];
?>

<form method="post" class="validate form-group-sm myForm">
    <input type="hidden" name="adaptorClass" value="Yarsha\HotelRates\RoomBasis\Seasonal" />
    <div class="panel panel-default ">
        <div class="panel-heading"><h3 class="panel-title"><?php echo 'Payment Strategy : '.$strategy.'( '.$percent.'% )' ?></h3></div>

        <div class="panel-body" id="rBS">
            <div class="col-md-12 templateWrapper" id="rBS_template">
                <table class="table table-responsive bg-none">
                    <tr>
                        <th>Market</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Plan</th>
                        <th>Expiry Date</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <td><?php getSelectMarketElement('rBS[#index#][market]',NULL,'class="form-control" id="rBS_#index#_market" onchange="showCurrency(this, #index#)"') ?></td>
                        <td><?php getSelectRoomCategoriesByHotel($hotelID, 'rBS[#index#][category]',NULL,'class="form-control"  id="rBS_#index#_category"') ?></td>
                        <td><?php getSelectRoomTypesByHotel($hotelID, 'rBS[#index#][type]',NULL,'class="form-control" id="rBS_#index#_type"') ?></td>
                        <td><?php getSelectRoomPlansByHotel($hotelID, 'rBS[#index#][plan]',NULL,'class="form-control" id="rBS_#index#__plan"') ?></td>
                        <td><input type="text" name="rBS[#index#][expiryDate]" class="form-control rBSExpDate" id="rBS_#index#_rBSExpDate" placeholder="Expiry Date" /></td>
                        <td><a id="rBS_remove_current"><i class="fa fa-trash-o"></i></a></td>
                    </tr>

                    <tr>
                        <td colspan="7">
                            <table class="table table-responsive bg-none">
                                <tr>
                                    <th style="width:20rem">&nbsp;</th>
                                    <th >Amount</th>
                                    <th >Extra Bed Charge</th>
                                </tr>
                                <?php  foreach($seasonsArr as $sTitle){  $sID = $sTitle['id']; ?>
                                    <tr>
                                        <td><?php echo $sTitle['name']?></td>
                                        <td>
                                            <div class="input-group col-md-12">
                                                <input type="text" name="rBS[#index#][seasons][<?php echo $sID ?>][amount]" value="0.000" class="form-control" id="rBS_#index#_seasons_<?php echo $sID ?>_amount"  onkeyup="calculateTotal(this)" />
                                                <span class="input-group-addon" id="rBS_#index#_seasons_<?php echo $sID ?>_amount_billing">0.000</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group col-md-12">
                                                <input type="text" name="rBS[#index#][seasons][<?php echo $sID ?>][extraBed]" value="0.000" class="form-control" id="rBS_#index#_seasons_<?php echo $sID ?>_extraBed" onkeyup="calculateTotal(this)" />
                                                <span class="input-group-addon" id="rBS_#index#_seasons_<?php echo $sID ?>_extraBed_billing">0.000</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <?php $count = 0;
            if(count($room_basis_seasonal_rates)){
                foreach($room_basis_seasonal_rates as $hr){
                    $id = 'oldRates_'.$hr['id'];
                    $roomBasisSeasonalPreFilledForm[] = $id;
                    ?>
                    <div class="col-md-12 templateWrapper" id="<?php echo $id ?>">
                        <table class="table table-responsive bg-none">
                            <tr>
                                <th>Market</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Plan</th>
                                <th>Expiry Date</th>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <td><?php getSelectMarketElement('rBS[#index#][market]',$hr['market'],'class="form-control" id="rBS_#index#_market" onchange="showCurrency(this, #index#)"') ?></td>
                                <td><?php getSelectRoomCategoriesByHotel($hotelID, 'rBS[#index#][category]',$hr['category'],'class="form-control"  id="rBS_#index#_category"') ?></td>
                                <td><?php getSelectRoomTypesByHotel($hotelID, 'rBS[#index#][type]',$hr['type'],'class="form-control" id="rBS_#index#_type"') ?></td>
                                <td><?php getSelectRoomPlansByHotel($hotelID, 'rBS[#index#][plan]',$hr['plan'],'class="form-control" id="rBS_#index#__plan"') ?></td>
                                <td><input type="text" name="rBS[#index#][expiryDate]" value="<?php echo $hr['expiryDate'] ?>" class="form-control rBSExpDate" id="rBS_#index#_rBSExpDate" placeholder="Expiry Date" /></td>
                                <td><a id="rBS_remove_current"><i class="fa fa-trash-o"></i></a></td>
                            </tr>

                            <tr>
                                <td colspan="7">
                                    <table class="table table-responsive bg-none">
                                        <tr>
                                            <th style="width:20rem">&nbsp;</th>
                                            <th >Amount</th>
                                            <th >Extra Bed Charge</th>
                                        </tr>
                                        <?php
                                        $amount = $extraBed = 0.00;
                                        foreach($seasonsArr as $sTitle){
                                            $sID = $sTitle['id'];
                                            $seasons = $hr['seasons'];
                                            if(  array_key_exists($sID, $seasons) ){
                                                $amount = $seasons[$sID]['amount'];
                                                $extraBed = $seasons[$sID]['extraBed'];

                                                $amountBilling = number_format(( $amount ) + ( ( $percent / 100 ) * $amount ), 3, '.', ',');
                                                $extraBilling = number_format(( $extraBed) + ( ( $percent / 100 ) * $extraBed ), 3, '.', ',');
                                            }else{
                                                $amount = '0.00';
                                                $extraBed = '0.00';

                                                $amountBilling = '0.00';
                                                $extraBilling = '0.00';
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $sTitle['name']?></td>
                                                <td>
                                                    <div class="input-group col-md-12">
                                                        <input type="text" name="rBS[#index#][seasons][<?php echo $sID ?>][amount]" value="<?php echo $amount ?>" class="form-control" id="rBS_#index#_seasons_<?php echo $sID ?>_amount"  onkeyup="calculateTotal(this)" />
                                                        <span class="input-group-addon" id="rBS_#index#_seasons_<?php echo $sID ?>_amount_billing"><?php echo $amountBilling ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group col-md-12">
                                                        <input type="text" name="rBS[#index#][seasons][<?php echo $sID ?>][extraBed]" value="<?php echo $extraBed ?>" class="form-control" id="rBS_#index#_seasons_<?php echo $sID ?>_extraBed" onkeyup="calculateTotal(this)" />
                                                        <span class="input-group-addon" id="rBS_#index#_seasons_<?php echo $sID ?>_extraBed_billing"><?php echo $extraBilling ?></span>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                    $count++;
                }
            }
            ?>

            <div class="col-md-12" id="rBS_noforms_template">No Rates Available</div>
            <div class="col-md-12" id="rBS_controls">
                <span id="rBS_add"><a class="btn btn-sm btn-flat bg-olive">ADD ANOTHER RATE</a></span>
            </div>
        </div>


        <div class="panel-footer">
            <input type="submit" class="btn btn-primary" value="UPDATE HOTEL RATES" />
            <a href="<?php echo site_url('hotel/rate/show/'.$hotel->slug()) ?>" class="btn btn-danger">CANCEL</a>      </div>
    </div>
</form>


<?php echo loadJS(['jquery.sheepit.min.js']); ?>

<script type="text/javascript">

    $(document).ready(function() {

        var rBSSheepItForm = $("#rBS").sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
            allowAddN: true,

            // Limits
            maxFormsCount: 0,
            minFormsCount: 1,
            iniFormsCount: 1,
            afterAdd: function(source, newForm) {
                $(newForm).find('.rBSExpDate').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            },
            pregeneratedForms: <?php echo  json_encode($roomBasisSeasonalPreFilledForm); ?>

        });

        $('.rBSExpDate').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

    });

    function showCurrency(obj, index){
        var self = $(obj),
            market = self.val(),
            currencyObj = $('#rBS_'+index+'_currency'),
            remoteUrl = Yarsha.config.base_url + 'market/ajax/getCurrencyByMarket';

        if(market != ""){
            remoteUrl = remoteUrl + '/' + market;
        }

        $.ajax({
            type: 'GET',
            url: remoteUrl,
            success: function(res){
                var data = $.parseJSON(res);
                console.log(data);
                if( data.status && data.status == 'success' ){
                    currencyObj.html(data.currency.iso_3);
                }else{
                    // to do if  any error status responded
                }
            }
        });



    }

</script>
