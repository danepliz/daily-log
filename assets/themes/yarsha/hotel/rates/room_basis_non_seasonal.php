<?php
$strategy = \hotel\models\Hotel::$paymentStrategies[$hotel->getPaymentStrategy()];
$percent = $hotel->getPaymentStrategyPercent();
$hotelID = $hotel->id();
$roomBasisNonSeasonalPreFilledForm = [];

?>

<form method="post" class="validate form-group-sm myForm">
    <input type="hidden" name="adaptorClass" value="Yarsha\HotelRates\RoomBasis\NonSeasonal" />
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><?php echo 'Payment Strategy : '.$strategy.'( '.$percent.'% )' ?></h3></div>

        <div class="panel-body">
            <div class="col-md-12 templateWrapper"">

                <table class="table table-responsive">
                    <tbody id="rBNS">
                    <tr>
                        <th>Market</th>
                        <th style="width:10rem">Room Category</th>
                        <th style="width:10rem">Room Type</th>
                        <th style="width:6rem">Room Plan</th>
                        <th>Amount</th>
                        <th>Charge/Extra Bed</th>
                        <th style="width:10rem">Expiry Date</th>
                        <th>&nbsp;</th>
                    </tr>

                    <tr id="rBNS_template">
                        <input type="hidden" name="rBNS[#index#][id]" value="" id="rBNS_#index#_id" />
                        <td><?php getSelectMarketElement('rBNS[#index#][market]',NULL,'class="form-control" id="rBNS_#index#_market" onchange="showCurrency(this)"') ?></td>
                        <td><?php getSelectRoomCategoriesByHotel($hotelID, 'rBNS[#index#][category]',NULL,'class="form-control"  id="rBNS_#index#_category"') ?></td>
                        <td><?php getSelectRoomTypesByHotel($hotelID, 'rBNS[#index#][type]',NULL,'class="form-control" id="rBNS_#index#_type"') ?></td>
                        <td><?php getSelectRoomPlansByHotel($hotelID, 'rBNS[#index#][plan]',NULL,'class="form-control" id="rBNS_#index#_plan"') ?></td>
                        <td>
                            <div class="input-group col-md-12">
                                <span class="input-group-addon" id="rBNS_#index#_market_currency"><?php echo $defaultCurrency ?></span>
                                <input type="text" name="rBNS[#index#][amount]" value="0.000" class="form-control" id="rBNS_#index#_amount"  onkeyup="calculateTotal(this)" />
                                <span class="input-group-addon" id="rBNS_#index#_amount_billing">0.000</span>
                            </div>
                        </td>
                        <td>
                            <div class="input-group col-md-12">
                                <input type="text" name="rBNS[#index#][extraBed]" value="0.000" class="form-control" id="rBNS_#index#_extraBed" onkeyup="calculateTotal(this)" />
                                <span class="input-group-addon" id="rBNS_#index#_extraBed_billing">0.000</span>
                            </div>
                        </td>
                        <td><input type="text" name="rBNS[#index#][expiryDate]" class="form-control rBNSExpDate" id="rBNS_#index#_rBNSExpDate" placeholder="Expiry Date" /></td>
                        <td><a id="rBNS_remove_current"><i class="fa fa-trash"></i></a></td>
                    </tr>

                    <?php
                        if( count($room_basis_non_seasonal_rates) ){
                            $count = 0;
                            foreach( $room_basis_non_seasonal_rates as $r ){
                                $id = 'rBNS_old_'.$count;
                                $roomBasisNonSeasonalPreFilledForm[] = $id;
                                $totalAmount = $r['amount'] + ( ($percent/100) * $r['amount'] );
                                $extraBillingAmount = $r['extraBed'] + ( ($percent/100) * $r['extraBed'] );

                                $market = ( $r['market'] != '' )? $this->doctrine->em->find('market\models\Market', $r['market']) : NULL;
                                $currency = ( $market and $market->getCurrency() )? $market->getCurrency()->getIso3() : $defaultCurrency;

                    ?>
                    <tr id="<?php echo $id ?>">
                        <input type="hidden" name="rBNS[#index#][id]" value="<?php echo $r['rateID'] ?>" id="rBNS_#index#_id" />
                        <td><?php getSelectMarketElement('rBNS[#index#][market]',$r['market'],'class="form-control" id="rBNS_#index#_market"  onchange="showCurrency(this)"') ?></td>
                        <td><?php getSelectRoomCategoriesByHotel($hotelID, 'rBNS[#index#][category]',$r['category'],'class="form-control"  id="rBNS_#index#_category"') ?></td>
                        <td><?php getSelectRoomTypesByHotel($hotelID, 'rBNS[#index#][type]',$r['type'],'class="form-control" id="rBNS_#index#_type"') ?></td>
                        <td><?php getSelectRoomPlansByHotel($hotelID, 'rBNS[#index#][plan]',$r['plan'],'class="form-control" id="rBNS_#index#__plan"') ?></td>
                        <td>
                            <div class="input-group col-md-12">
                                <span class="input-group-addon" id="rBNS_#index#_market_currency"><?php echo $currency ?></span>
                                <input type="text" name="rBNS[#index#][amount]" value="<?php echo $r['amount'] ?>" class="form-control" id="rBNS_#index#_amount"  onkeyup="calculateTotal(this)" />
                                <span class="input-group-addon" id="rBNS_#index#_amount_billing"><?php echo $totalAmount ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="input-group col-md-12">
                                <input type="text" name="rBNS[#index#][extraBed]" value="<?php echo $r['extraBed'] ?>" class="form-control" id="rBNS_#index#_extraBed" onkeyup="calculateTotal(this)" />
                                <span class="input-group-addon" id="rBNS_#index#_extraBed_billing"><?php echo $extraBillingAmount ?></span>
                            </div>
                        </td>
                        <td><input type="text" name="rBNS[#index#][expiryDate]" value="<?php echo $r['expDate'] ?>" class="form-control rBNSExpDate" id="rBNS_#index#_rBNSExpDate" placeholder="Expiry Date" /></td>
                        <td><a id="rBNS_remove_current"><i class="fa fa-trash"></i></a></td>
                    </tr>
                    <?php
                            }
                        }
                    ?>

                    <tr id="rBNS_noforms_template">
                        <td colspan="6">No Rates Added</td>
                    </tr>

                    <tr id="rBNS_controls">
                        <td id="rBNS_add" style="padding-top:1rem"><a class="btn btn-sm btn-flat bg-olive">ADD ANOTHER RATE</a></td>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    </tbody>
                </table>

            </div>

        </div>


        <div class="panel-footer">
            <input type="submit" class="btn btn-primary" value="UPDATE HOTEL RATES" />
            <a href="<?php echo site_url('hotel/rate/show/'.$hotel->slug()) ?>" class="btn btn-danger">CANCEL</a>        </div>
    </div>
</form>


<?php echo loadJS(['jquery.sheepit.min.js']); ?>

<script type="text/javascript">

    $(document).ready(function() {

        var rBNSSheepItForm = $("#rBNS").sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
            allowAddN: true,
            indexFormat: '#index#',

            // Limits
            maxFormsCount: 0,
            minFormsCount: 1,
            iniFormsCount: 1,
            afterAdd: function(source, newForm) {
                $(newForm).find('.rBNSExpDate').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            },
            pregeneratedForms: <?php echo  json_encode($roomBasisNonSeasonalPreFilledForm); ?>

        });

        $('.rBNSExpDate').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

    });


</script>
