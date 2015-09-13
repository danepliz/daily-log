<?php
$strategy = \hotel\models\Hotel::$paymentStrategies[$hotel->getPaymentStrategy()];
$percent = $hotel->getPaymentStrategyPercent();
$hotelID = $hotel->id();
$serviceRatesPreFilledForm = [];

?>

<form method="post" class="validate form-group-sm myForm">
    <input type="hidden" name="adaptorClass" value="Yarsha\HotelRates\ServiceRate" />
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><?php echo 'Payment Strategy : '.$strategy.'( '.$percent.'% )' ?></h3></div>

        <div class="panel-body">
            <div class="col-md-12 templateWrapper"">

            <table class="table table-responsive">
                <tbody id="serviceRates">
                <tr>
                    <th>Market</th>
                    <th>Outlet</th>
                    <th>Service</th>
                    <th style="width:25rem">Amount</th>
                    <th>Expiry Date</th>
                    <th>&nbsp;</th>
                </tr>

                <tr id="serviceRates_template">
                    <input type="hidden" name="serviceRates[#index#][id]" value="" id="serviceRates_#index#_id" />
                    <td><?php getSelectMarketElement('serviceRates[#index#][market]',NULL,'class="form-control" id="serviceRates_#index#_market" onchange="showCurrency(this)"') ?></td>
                    <td><?php getSelectOutletsByHotel($hotelID, 'serviceRates[#index#][outlet]',NULL,'class="form-control"  id="serviceRates_#index#_outlet"') ?></td>
                    <td><?php getSelectServicesByHotel($hotelID, 'serviceRates[#index#][service]',NULL,'class="form-control" id="serviceRates_#index#_service"') ?></td>
                    <td class="input-group">
                        <span class="input-group-addon" id="serviceRates_#index#_market_currency"><?php echo $defaultCurrency ?></span>
                        <input type="text" name="serviceRates[#index#][amount]" value="0.00" class="form-control" id="serviceRates_#index#_amount"  onkeyup="calculateTotal(this)" />
                        <span class="input-group-addon" id="serviceRates_#index#_amount_billing">0.00</span>
                    </td>
                    <td><input type="text" name="serviceRates[#index#][expiryDate]" class="form-control serviceRatesExpDate" id="serviceRates_#index#_serviceRatesExpDate" placeholder="Expiry Date" /></td>
                    <td><a id="serviceRates_remove_current"><i class="fa fa-trash"></i></a></td>
                </tr>

                <?php
                if( count($hotel_service_rates) ){
                    $count = 0;
                    foreach( $hotel_service_rates as $r ){
                        $id = 'serviceRates_old_'.$count;
                        $serviceRatesPreFilledForm[] = $id;
                        $totalAmount = $r['amount'] + ( ($percent/100) * $r['amount'] );
                        $market = ( $r['market'] != '' )? $this->doctrine->em->find('market\models\Market', $r['market']) : NULL;
                        $currency = ( $market and $market->getCurrency() )? $market->getCurrency()->getIso3() : $defaultCurrency;
                        ?>
                        <tr id="<?php echo $id ?>">

                            <input type="hidden" name="serviceRates[#index#][id]" value="<?php echo $r['rateID'] ?>" id="serviceRates_#index#_id" />
                            <td><?php getSelectMarketElement('serviceRates[#index#][market]',$r['market'],'class="form-control" id="serviceRates_#index#_market" onchange="showCurrency(this)"') ?></td>
                            <td><?php getSelectOutletsByHotel($hotelID, 'serviceRates[#index#][outlet]',$r['outlet'],'class="form-control"  id="serviceRates_#index#_outlet"') ?></td>
                            <td><?php getSelectServicesByHotel($hotelID, 'serviceRates[#index#][service]',$r['service'],'class="form-control" id="serviceRates_#index#_service"') ?></td>
                            <td class="input-group">
                                <span class="input-group-addon" id="serviceRates_#index#_market_currency"><?php echo $currency ?></span>
                                <input type="text" name="serviceRates[#index#][amount]" value="<?php echo $r['amount'] ?>" class="form-control" id="serviceRates_#index#_amount"  onkeyup="calculateTotal(this)" />
                                <span class="input-group-addon" id="serviceRates_#index#_amount_billing"><?php echo $totalAmount ?></span>
                            </td>
                            <td><input type="text" name="serviceRates[#index#][expiryDate]" value="<?php echo $r['expDate'] ?>" class="form-control serviceRatesExpDate" id="serviceRates_#index#_serviceRatesExpDate" placeholder="Expiry Date" /></td>
                            <td><a id="serviceRates_remove_current"><i class="fa fa-trash"></i></a></td>
                        </tr>
                    <?php
                    }
                }
                ?>

                <tr id="serviceRates_noforms_template">
                    <td colspan="6">No Rates Added</td>
                </tr>

                <tr id="serviceRates_controls">
                    <td id="serviceRates_add" style="padding-top:1rem"><a class="btn btn-sm btn-flat bg-olive">ADD ANOTHER RATE</a></td>
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

        var serviceRatesSheepItForm = $("#serviceRates").sheepIt({
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
                $(newForm).find('.serviceRatesExpDate').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            },
            pregeneratedForms: <?php echo  json_encode($serviceRatesPreFilledForm); ?>

        });

        $('.serviceRatesExpDate').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

    });

</script>
