<?php
use hotel\models\HotelPackage;

$strategy = \hotel\models\Hotel::$paymentStrategies[$hotel->getPaymentStrategy()];
$percent = $hotel->getPaymentStrategyPercent();
$hotelID = $hotel->id();
$packageBasisNonSeasonalMainPreFilledForm = [];
$packageBasisNonSeasonalExtraPreFilledForm = [];

$mainPackageRates = $package_basis_non_seasonal_rates['main'];
$extraPackageRates = $package_basis_non_seasonal_rates['extra'];

?>


<div class="col-md-12 margin">

<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title"><?php echo 'Payment Strategy : '.$strategy.'( '.$percent.'% )' ?></h3></div>
    <div class="panel-body">

    <ul class="nav nav-tabs spnav-tabs">
        <li role="presentation" class="active"> <a href="#sMainPackagesRates" class="sptabbed" rel="sMainPackagesRates" data-toggle="tab">Main Packages Rate</a>        </li>
        <li role="presentation"> <a href="#sExtraPackagesRates" class="sptabbed" rel="sExtraPackagesRates" data-toggle="tab">Extra Packages Rate</a> </li>
    </ul>

    <div class="tab-content">
    <div class="row">
    <div class="sptab-pane" id="sMainPackagesRates">
        <form method="post" class="validate form-group-sm myForm">
            <input type="hidden" name="adaptorClass" value="Yarsha\HotelRates\PackageBasis\NonSeasonal" />
            <div class="col-md-12 templateWrapper">

                <table class="table table-responsive">
                    <tbody id="pNSM">
                    <tr>
                        <th colspan="7">Main Package</th>
                    </tr>
                    <tr>
                        <th>Market</th>
                        <th>Package</th>
                        <th style="width:20rem">Amount</th>
                        <th style="width:20rem">Charge Per Additional Night</th>
                        <th style="width:20rem">Single Supplement Charge Per Night</th>
                        <th style="width:10rem">Expiry Date</th>
                        <th>&nbsp;</th>
                    </tr>

                    <tr id="pNSM_template">
                        <input type="hidden" name="pNSM[#index#][id]" value="" id="pNSM_#index#_id" />
                        <td><?php getSelectMarketElement('pNSM[#index#][market]',NULL,'class="form-control required" id="pNSM_#index#_market" onchange="showCurrency(this)"') ?></td>
                        <td><?php getSelectPackagesElementByHotel($hotelID, 'pNSM[#index#][package]', HotelPackage::PACKAGE_TYPE_MAIN ,NULL,'class="form-control required"  id="pNSM_#index#_package"') ?></td>
                        <td>
                            <div class="col-md-12 input-group">
                                <span class="input-group-addon" id="pNSM_#index#_market_currency"><?php echo $defaultCurrency ?></span>
                                <input type="text" name="pNSM[#index#][amount]" value="0.000" class="form-control required" id="pNSM_#index#_amount"  onkeyup="calculateTotal(this)" />
                                <span class="input-group-addon" id="pNSM_#index#_amount_billing">0.000</span>
                            </div>
                        </td>
                        <td>
                            <div class="col-md-12 input-group">
                                <input type="text" name="pNSM[#index#][additional]" value="0.000" class="form-control required" id="pNSM_#index#_additional" onkeyup="calculateTotal(this)" />
                                <span class="input-group-addon" id="pNSM_#index#_additional_billing">0.000</span>
                            </div>
                        </td>
                        <td>
                            <div class="col-md-12 input-group">
                                <input type="text" name="pNSM[#index#][supplement]" value="0.000" class="form-control required" id="pNSM_#index#_supplement" onkeyup="calculateTotal(this)" />
                                <span class="input-group-addon" id="pNSM_#index#_supplement_billing">0.000</span>
                            </div>
                        </td>
                        <td><input type="text" name="pNSM[#index#][expiryDate]" class="form-control required expDate" id="pNSM_#index#_expdate" placeholder="Expiry Date" /></td>
                        <td><a id="pNSM_remove_current"><i class="fa fa-trash"></i></a></td>
                    </tr>

                    <?php
                    if( count($mainPackageRates) ){
                        $count = 0;
                        $marketRepo = $this->doctrine->em->getRepository('market\models\Market');
                        foreach( $mainPackageRates as $mr ){
                            $id = 'pNSM_old_'.$count;
                            $packageBasisNonSeasonalMainPreFilledForm[] = $id;
                            $market = ($mr['market'] != '')? $marketRepo->find($mr['market']) : NULL;
                            $currency = ($market and $market->getCurrency()) ? $market->getCurrency()->getIso3() : $defaultCurrency;
                            $total = $mr['amount'] + ( ( $percent / 100 ) * $mr['amount'] );
                            $additionalBilling = $mr['additional'] + ( ( $percent / 100 ) * $mr['additional'] );
                            $supplementBilling = $mr['supplement'] + ( ( $percent / 100 ) * $mr['supplement'] );
                            ?>
                            <tr id="<?php echo $id ?>">
                                <input type="hidden" name="pNSM[#index#][id]" value="<?php echo $mr['rateID'] ?>" id="pNSM_#index#_id" />
                                <td><?php getSelectMarketElement('pNSM[#index#][market]',$mr['market'],'class="form-control required" id="pNSM_#index#_market" onchange="showCurrency(this)"') ?></td>
                                <td><?php getSelectPackagesElementByHotel($hotelID, 'pNSM[#index#][package]', HotelPackage::PACKAGE_TYPE_MAIN ,$mr['package'],'class="form-control required"  id="pNSM_#index#_package"') ?></td>
                                <td>
                                    <div class="col-md-12 input-group">
                                        <span class="input-group-addon" id="pNSM_#index#_market_currency"><?php echo $currency ?></span>
                                        <input type="text" name="pNSM[#index#][amount]" value="<?php echo $mr['amount'] ?>" class="form-control required" id="pNSM_#index#_amount"  onkeyup="calculateTotal(this)" />
                                        <span class="input-group-addon" id="pNSM_#index#_amount_billing"><?php echo $total ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-12 input-group">
                                        <input type="text" name="pNSM[#index#][additional]" value="<?php echo $mr['additional'] ?>" class="form-control required" id="pNSM_#index#_additional" onkeyup="calculateTotal(this)" />
                                        <span class="input-group-addon" id="pNSM_#index#_additional_billing"><?php echo $additionalBilling ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-12 input-group">
                                        <input type="text" name="pNSM[#index#][supplement]" value="<?php echo $mr['supplement'] ?>" class="form-control required" id="pNSM_#index#_supplement" onkeyup="calculateTotal(this)" />
                                        <span class="input-group-addon" id="pNSM_#index#_supplement_billing"><?php echo $supplementBilling ?></span>
                                    </div>
                                </td>

                                <td><input type="text" name="pNSM[#index#][expiryDate]" value="<?php echo $mr['expDate'] ?>" class="form-control expDate required" id="pNSM_#index#_expdate" placeholder="Expiry Date" /></td>
                                <td><a id="pNSM_remove_current"><i class="fa fa-trash"></i></a></td>
                            </tr>
                            <?php
                            $count++;
                        }
                    }
                    ?>

                    <tr id="pNSM_noforms_template">
                        <td colspan="6">No Rates Added</td>
                    </tr>

                    <tr id="pNSM_controls">
                        <td id="pNSM_add" style="padding-top:1rem"><a class="btn btn-sm btn-flat bg-olive">ADD ANOTHER RATE</a></td>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <div class="col-md-12">
                <input type="submit" class="btn btn-primary" value="UPDATE HOTEL RATES" />
                <a href="<?php echo site_url('hotel/rate/show/'.$hotel->slug()) ?>" class="btn btn-danger">CANCEL</a>
            </div>
        </form>

    </div>

    <div class="sptab-pane" id="sExtraPackagesRates">
        <form method="post" class="validate form-group-sm myForm">
            <input type="hidden" name="adaptorClass" value="Yarsha\HotelRates\PackageBasis\NonSeasonal" />
            <div class="col-md-12 templateWrapper">

                <table class="table table-responsive">
                    <tbody id="pNSE">
                    <tr>
                        <th colspan="7">Extra Package</th>
                    </tr>
                    <tr>
                        <th>Market</th>
                        <th>Package</th>
                        <th style="width:20rem">Amount</th>
                        <th style="width:20rem">Charge Per Additional Night</th>
                        <th style="width:10rem">Expiry Date</th>
                        <th>&nbsp;</th>
                    </tr>

                    <tr id="pNSE_template">
                        <input type="hidden" name="pNSE[#index#][id]" value="" id="pNSE_#index#_id" />
                        <td><?php getSelectMarketElement('pNSE[#index#][market]',NULL,'class="form-control required" id="pNSE_#index#_market" onchange="showCurrency(this)"') ?></td>
                        <td><?php getSelectPackagesElementByHotel($hotelID, 'pNSE[#index#][package]', HotelPackage::PACKAGE_TYPE_EXTRA ,NULL,'class="form-control required"  id="pNSE_#index#_package"') ?></td>
                        <td>
                            <div class="col-md-12 input-group">
                                <span class="input-group-addon" id="pNSE_#index#_market_currency"><?php echo $defaultCurrency ?></span>
                                <input type="text" name="pNSE[#index#][amount]" value="0.000" class="form-control required" id="pNSE_#index#_amount"  onkeyup="calculateTotal(this)" />
                            </div>
                        </td>
                        <td>
                            <input type="text" name="pNSE[#index#][additional]" value="0.000" class="form-control required" id="pNSE_#index#_additional" onkeyup="calculateTotal(this)"/>
                        </td>
                        <td><input type="text" name="pNSE[#index#][expiryDate]" class="form-control required extDate" id="pNSE_#index#_extdate" placeholder="Expiry Date" /></td>
                        <td><a id="pNSE_remove_current"><i class="fa fa-trash"></i></a></td>
                    </tr>

                    <?php
                    if( count($extraPackageRates) ){
                        $eCount = 0;
                        $marketRepo = $this->doctrine->em->getRepository('market\models\Market');
                        foreach( $extraPackageRates as $mr ){
                            $eid = 'pNSM_old_'.$eCount;
                            $packageBasisNonSeasonalExtraPreFilledForm[] = $eid;
                            $market = ($mr['market'] != '')? $marketRepo->find($mr['market']) : NULL;
                            $e_currency = ($market and $market->getCurrency()) ? $market->getCurrency()->getIso3() : $defaultCurrency;
                            $total = $mr['amount'] + ( ( $percent / 100 ) * $mr['amount'] );
                            $eAdditionalBilling = $mr['additional'] + ( ( $percent / 100 ) * $mr['additional'] );
                            ?>
                            <tr id="<?php echo $eid ?>">
                                <input type="hidden" name="pNSE[#index#][id]" value="<?php echo $mr['rateID'] ?>" id="pNSM_#index#_id" />
                                <td><?php getSelectMarketElement('pNSE[#index#][market]',$mr['market'],'class="form-control required" id="pNSE_#index#_market" onchange="showCurrency(this)"') ?></td>
                                <td><?php getSelectPackagesElementByHotel($hotelID, 'pNSE[#index#][package]', HotelPackage::PACKAGE_TYPE_EXTRA ,$mr['package'],'class="form-control required"  id="pNSE_#index#_package"') ?></td>
                                <td>
                                    <div class="col-md-12 input-group">
                                        <span class="input-group-addon" id="pNSE_#index#_market_currency"><?php echo $e_currency ?></span>
                                        <input type="text" name="pNSE[#index#][amount]" value="<?php echo $mr['amount'] ?>" class="form-control required" id="pNSE_#index#_amount"  onkeyup="calculateTotal(this)" />
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="pNSE[#index#][additional]" value="<?php echo $mr['additional'] ?>" class="form-control required" id="pNSE_#index#_additional" onkeyup="calculateTotal(this)"/>
                                </td>
                                <td><input type="text" name="pNSE[#index#][expiryDate]" value="<?php echo $mr['expDate'] ?>" class="form-control extDate required" id="pNSE_#index#_extdate" placeholder="Expiry Date" /></td>
                                <td><a id="pNSE_remove_current"><i class="fa fa-trash"></i></a></td>
                            </tr>
                            <?php
                            $eCount++;
                        }
                    }
                    ?>

                    <tr id="pNSE_noforms_template">
                        <td colspan="6">No Rates Added</td>
                    </tr>

                    <tr id="pNSE_controls">
                        <td id="pNSE_add" style="padding-top:1rem"><a class="btn btn-sm btn-flat bg-olive">ADD ANOTHER RATE</a></td>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <div class="col-md-12">
                <input type="submit" class="btn btn-primary" value="UPDATE HOTEL RATES" />
                <a href="<?php echo site_url('hotel/rate/show/'.$hotel->slug()) ?>" class="btn btn-danger">CANCEL</a>
            </div>
        </form>
    </div>

    </div>
    </div>


    </div>
</div>








</div>


<?php echo loadJS(['jquery.sheepit.min.js']); ?>

<script type="text/javascript">

    $(document).ready(function() {

        /* TABS SCRIPT */
        $('.sptab-pane').not('#'+$('ul.spnav-tabs li.active').children('a').attr('rel')).hide();
        $('a.sptabbed').click(function(){
            $('ul.spnav-tabs li').removeClass('active');
            $(this).parent('li').addClass('active');
            var chk=$(this).attr('rel');
            $('div.sptab-pane').hide();
            $('div#'+chk).show();
        });
        /* TABS SCRIPT ENDS */

        var pNSMSheepItForm = $("#pNSM").sheepIt({
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
                $(newForm).find('.expDate').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            },
            pregeneratedForms: <?php echo  json_encode($packageBasisNonSeasonalMainPreFilledForm); ?>

        });


        var pNSESheepItForm = $("#pNSE").sheepIt({
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
                $(newForm).find('.extDate').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            },
            pregeneratedForms: <?php echo  json_encode($packageBasisNonSeasonalExtraPreFilledForm); ?>

        });

        $('.extDate').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

        $('.expDate').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

    });

</script>
