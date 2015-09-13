<?php
use hotel\models\HotelPackage;

$strategy = \hotel\models\Hotel::$paymentStrategies[$hotel->getPaymentStrategy()];
$percent = $hotel->getPaymentStrategyPercent();
$hotelID = $hotel->id();
$packageBasisSeasonalMainForm = [];
$packageBasisSeasonalExtraForm = [];
$mainPackageRates = $package_basis_seasonal_rates['main'];
$extraPackageRates = $package_basis_seasonal_rates['extra'];


?>

<div class="col-md-12 margin"><h3 class="panel-title"><?php echo 'Payment Strategy : '.$strategy.'( '.$percent.'% )' ?></h3></div>

<div class="col-md-12 margin">
    <ul class="nav nav-tabs snav-tabs">
        <li role="presentation" class="active"> <a href="#mainPackagesRates" class="stabbed" rel="mainPackagesRates" data-toggle="tab">Main Packages Rate</a>        </li>
        <li role="presentation"> <a href="#extraPackagesRates" class="stabbed" rel="extraPackagesRates" data-toggle="tab">Extra Packages Rate</a> </li>
    </ul>

    <div class="tab-content">
        <div class="row">

            <!-- main pacakge -->
            <div class="stab-pane" id="mainPackagesRates">
                <form method="post" class="validate form-group-sm myForm">
                    <input type="hidden" name="adaptorClass" value="Yarsha\HotelRates\PackageBasis\Seasonal" />
                    <div class="panel panel-default ">

                        <div class="panel-body" id="pBSM">
                            <div class="col-md-12 templateWrapper" id="pBSM_template">
                                <table class="table table-responsive bg-none">
                                    <tr>
                                        <th>Market</th>
                                        <th>Package</th>
                                        <th>Expiry Date</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <td><?php getSelectMarketElement('pBSM[#index#][market]',NULL,'class="form-control" id="pBSM_#index#_market" onchange="showCurrency(this, #index#)"') ?></td>
                                        <td><?php getSelectPackagesElementByHotel($hotelID, 'pBSM[#index#][package]', HotelPackage::PACKAGE_TYPE_MAIN ,NULL,'class="form-control"  id="extraRates_#index#_package"') ?></td>
                                        <td><input type="text" name="pBSM[#index#][expiryDate]" class="form-control pBSExpDate" id="pBSM_#index#_pBSExpDate" placeholder="Expiry Date" /></td>
                                        <td><a id="pBSM_remove_current"><i class="fa fa-trash-o"></i></a></td>
                                    </tr>

                                    <tr>
                                        <td colspan="4">
                                            <table class="table table-responsive bg-none">
                                                <tr>
                                                    <td style="width:15rem">&nbsp;</td>
                                                    <th>Amount</th>
                                                    <th>Additional Charge Per Night</th>
                                                    <th>Single Supplement Per Night</th>
                                                </tr>
                                                <?php  foreach($seasonsArr as $sTitle){  $sID = $sTitle['id']; ?>
                                                    <tr>
                                                        <td><?php echo $sTitle['name']?></td>
                                                        <td>
                                                            <input type="text" name="pBSM[#index#][seasons][<?php echo $sID ?>][amount]" value="0.000" class="form-control" id="pBSM_#index#_seasons_<?php echo $sID ?>_amount"  onkeyup="calculateTotal(this)" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="pBSM[#index#][seasons][<?php echo $sID ?>][additional]" value="0.000" class="form-control" id="pBSM_#index#_seasons_<?php echo $sID ?>_additional"  onkeyup="calculateTotal(this)" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="pBSM[#index#][seasons][<?php echo $sID ?>][supplement]" value="0.000" class="form-control" id="pBSM_#index#_seasons_<?php echo $sID ?>_supplement"  onkeyup="calculateTotal(this)" />
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <?php if(count($mainPackageRates)) {
                                $count = 0;
                                foreach($mainPackageRates as $MPR ){
                                    $mID = 'oldMainPackageRate_'.$count;
                                    $packageBasisSeasonalMainForm[] = $mID;
                            ?>
                            <div class="col-md-12 templateWrapper" id="<?php echo $mID ?>">
                                <table class="table table-responsive bg-none">
                                    <tr>
                                        <th>Market</th>
                                        <th>Package</th>
                                        <th>Expiry Date</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <td><?php getSelectMarketElement('pBSM[#index#][market]',$MPR['market'],'class="form-control" id="pBSM_#index#_market" onchange="showCurrency(this, #index#)"') ?></td>
                                        <td><?php getSelectPackagesElementByHotel($hotelID, 'pBSM[#index#][package]', HotelPackage::PACKAGE_TYPE_MAIN ,$MPR['package'],'class="form-control"  id="extraRates_#index#_package"') ?></td>
                                        <td><input type="text" name="pBSM[#index#][expiryDate]" class="form-control pBSExpDate" value="<?php echo $MPR['expiryDate'] ?>" id="pBSM_#index#_pBSExpDate" placeholder="Expiry Date" /></td>
                                        <td><a id="pBSM_remove_current"><i class="fa fa-trash-o"></i></a></td>
                                    </tr>

                                    <tr>
                                        <td colspan="4">
                                            <table class="table table-responsive bg-none">
                                                <tr>
                                                    <td style="width:15rem">&nbsp;</td>
                                                    <th>Amount</th>
                                                    <th>Additional Charge Per Night</th>
                                                    <th>Single Supplement Per Night</th>
                                                </tr>
                                                <?php  foreach($seasonsArr as $sTitle){  $sID = $sTitle['id'];
                                                    $seasons = $MPR['seasons'];
                                                    if(  array_key_exists($sID, $seasons) ){
                                                        $amount = $seasons[$sID]['amount'];
                                                        $extraBed = $seasons[$sID]['extraBed'];
                                                        $additional = $seasons[$sID]['additional'];
                                                        $supplement = $seasons[$sID]['supplement'];

                                                        $amountBilling = number_format( $amount + ( $percent / 100 * $amount ), 3, '.', '' );
                                                        $extraBedBilling = number_format( $extraBed + ( $percent / 100 * $extraBed ), 3, '.', '' );
                                                        $additionalBilling = number_format( $additional + ( $percent / 100 * $additional ), 3, '.', '' );
                                                        $supplementBilling = number_format( $supplement + ( $percent / 100 * $supplement ), 3, '.', '' );
                                                    }else{
                                                        $amount = '0.00';
                                                        $extraBed = '0.00';
                                                        $additional = '0.00';
                                                        $supplement = '0.00';

                                                        $amountBilling = '0.00';
                                                        $extraBedBilling = '0.00';
                                                        $additionalBilling = '0.00';
                                                        $supplementBilling = '0.00';
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?php echo $sTitle['name']?></td>
                                                        <td>
                                                            <input type="text" name="pBSM[#index#][seasons][<?php echo $sID ?>][amount]" value="<?php echo $amount ?>" class="form-control" id="pBSM_#index#_seasons_<?php echo $sID ?>_amount"  onkeyup="calculateTotal(this)" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="pBSM[#index#][seasons][<?php echo $sID ?>][additional]" value="<?php echo $additional ?>" class="form-control" id="pBSM_#index#_seasons_<?php echo $sID ?>_additional"  onkeyup="calculateTotal(this)" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="pBSM[#index#][seasons][<?php echo $sID ?>][supplement]" value="<?php echo $supplement ?>" class="form-control" id="pBSM_#index#_seasons_<?php echo $sID ?>_supplement"  onkeyup="calculateTotal(this)" />
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php $count++; } } ?>

                            <div class="col-md-12" id="pBSM_noforms_template">No Rates Available</div>
                            <div class="col-md-12" id="pBSM_controls">
                                <span id="pBSM_add"><a class="btn btn-sm btn-flat bg-olive">ADD ANOTHER RATE</a></span>
                            </div>
                        </div>


                        <div class="panel-footer">
                            <input type="submit" class="btn btn-primary" value="UPDATE HOTEL RATES" />
                            <a href="<?php echo site_url('hotel/rate/show/'.$hotel->slug()) ?>" class="btn btn-danger">CANCEL</a>
                        </div>
                    </div>
                </form>
            </div>
            <!-- main package ends -->

            <!-- extra package -->
            <div class="stab-pane" id="extraPackagesRates">
                <form method="post" class="validate form-group-sm myForm" name="extraPackageForm" action="<?php echo site_url('hotel/rate/show/'.$hotel->slug()) ?>">
                    <input type="hidden" name="adaptorClass" value="Yarsha\HotelRates\PackageBasis\Seasonal" />
                    <div class="panel panel-default ">

                        <div class="panel-body" id="pBSE">
                            <div class="col-md-12 templateWrapper" id="pBSE_template">
                                <table class="table table-responsive bg-none">
                                    <tr>
                                        <th>Market</th>
                                        <th>Package</th>
                                        <th>Expiry Date</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <td><?php getSelectMarketElement('pBSE[#index#][market]',NULL,'class="form-control" id="pBSE_#index#_market" onchange="showCurrency(this, #index#)"') ?></td>
                                        <td><?php getSelectPackagesElementByHotel($hotelID, 'pBSE[#index#][package]', HotelPackage::PACKAGE_TYPE_EXTRA ,NULL,'class="form-control"  id="pBSE_#index#_package"') ?></td>
                                        <td><input type="text" name="pBSE[#index#][expiryDate]" class="form-control pBSExpDate" id="pBSE_#index#_pBSExpDate" placeholder="Expiry Date" /></td>
                                        <td><a id="pBSE_remove_current"><i class="fa fa-trash-o"></i></a></td>
                                    </tr>

                                    <tr>
                                        <td colspan="4">
                                            <table class="table table-responsive bg-none">
                                                <tr>
                                                    <td style="width:15rem">&nbsp;</td>
                                                    <th>Amount</th>
                                                    <th>Additional Charge Per Night</th>
                                                </tr>
                                                <?php  foreach($seasonsArr as $sTitle){  $seID = $sTitle['id']; ?>
                                                    <tr>
                                                        <td><?php echo $sTitle['name']?></td>
                                                        <td>
                                                            <input type="text" name="pBSE[#index#][seasons][<?php echo $seID ?>][amount]" value="0.000" class="form-control" id="pBSE_#index#_seasons_<?php echo $seID ?>_amount"  onkeyup="calculateTotal(this)" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="pBSE[#index#][seasons][<?php echo $seID ?>][additional]" value="0.000" class="form-control" id="pBSE_#index#_seasons_<?php echo $seID ?>_additional"  onkeyup="calculateTotal(this)" />
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <?php if(count($mainPackageRates)) {
                            $eCount = 0;
                            foreach($extraPackageRates as $EPR ){
                            $eID = 'oldExtraPackageRate_'.$eCount;
                            $packageBasisSeasonalExtraForm[] = $eID;
                            ?>
                                <div class="col-md-12 templateWrapper" id="<?php echo $eID ?>">
                                    <table class="table table-responsive bg-none">
                                        <tr>
                                            <th>Market</th>
                                            <th>Package</th>
                                            <th>Expiry Date</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        <tr>
                                            <td><?php getSelectMarketElement('pBSE[#index#][market]',$EPR['market'],'class="form-control" id="pBSE_#index#_market" onchange="showCurrency(this, #index#)"') ?></td>
                                            <td><?php getSelectPackagesElementByHotel($hotelID, 'pBSE[#index#][package]', HotelPackage::PACKAGE_TYPE_EXTRA ,$EPR['package'],'class="form-control"  id="pBSE_#index#_package"') ?></td>
                                            <td><input type="text" name="pBSE[#index#][expiryDate]" class="form-control pBSExpDate" value="<?php echo $EPR['expiryDate'] ?>" id="pBSE_#index#_pBSExpDate" placeholder="Expiry Date" /></td>
                                            <td><a id="pBSE_remove_current"><i class="fa fa-trash-o"></i></a></td>
                                        </tr>

                                        <tr>
                                            <td colspan="4">
                                                <table class="table table-responsive bg-none">
                                                    <tr>
                                                        <td style="width:15rem">&nbsp;</td>
                                                        <th>Amount</th>
                                                        <th>Additional Charge Per Night</th>
                                                    </tr>
                                                    <?php  foreach($seasonsArr as $sTitle){  $seID = $sTitle['id'];
                                                        $eSeasons = $EPR['seasons'];
                                                        if(  array_key_exists($seID, $seasons) ){
                                                            $eAmount = $eSeasons[$seID]['amount'];
                                                            $eExtraBed = $eSeasons[$seID]['extraBed'];
                                                            $eAdditional = $eSeasons[$seID]['additional'];

                                                            $e_amountBilling = number_format( $eAmount + ( $percent / 100 * $eAmount ), 3, '.', '' );
                                                            $e_extraBedBilling = number_format( $eExtraBed + ( $percent / 100 * $eExtraBed ), 3, '.', '' );
                                                            $e_additionalBilling = number_format( $eAdditional + ( $percent / 100 * $eAdditional ), 3, '.', '' );
                                                        }else{
                                                            $eAmount = '0.00';
                                                            $eExtraBed = '0.00';
                                                            $eAdditional = '0.00';

                                                            $e_amountBilling = '0.00';
                                                            $e_extraBedBilling = '0.00';
                                                            $e_additionalBilling = '0.00';
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $sTitle['name']?></td>
                                                            <td>
                                                                <input type="text" name="pBSE[#index#][seasons][<?php echo $seID ?>][amount]" value="<?php echo $eAmount ?>" class="form-control" id="pBSE_#index#_seasons_<?php echo $seID ?>_amount"  onkeyup="calculateTotal(this)" />
                                                            </td>
                                                            <td>
                                                                <input type="text" name="pBSE[#index#][seasons][<?php echo $seID ?>][additional]" value="<?php echo $eAdditional ?>" class="form-control" id="pBSE_#index#_seasons_<?php echo $seID ?>_additional"  onkeyup="calculateTotal(this)" />
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            <?php $eCount++; } } ?>

                            <div class="col-md-12" id="pBSE_noforms_template">No Rates Available</div>
                            <div class="col-md-12" id="pBSE_controls">
                                <span id="pBSE_add"><a class="btn btn-sm btn-flat bg-olive">ADD ANOTHER RATE</a></span>
                            </div>
                        </div>


                        <div class="panel-footer">
                            <input type="submit" class="btn btn-primary" value="UPDATE HOTEL RATES" />
                            <a href="<?php echo site_url('hotel/rate/show/'.$hotel->slug()) ?>" class="btn btn-danger">CANCEL</a>
                        </div>
                    </div>
                </form>
            </div>
            <!-- extra package ends -->

        </div>


    </div>

</div>





<?php echo loadJS(['jquery.sheepit.min.js']); ?>

<script type="text/javascript">

    $(document).ready(function() {

        /* TABS SCRIPT */
        $('.stab-pane').not('#'+$('ul.snav-tabs li.active').children('a').attr('rel')).hide();
        $('a.stabbed').click(function(){
            $('ul.snav-tabs li').removeClass('active');
            $(this).parent('li').addClass('active');
            var chk=$(this).attr('rel');
            $('div.stab-pane').hide();
            $('div#'+chk).show();
        });
        /* TABS SCRIPT ENDS */



        var pBSMSheepItForm = $("#pBSM").sheepIt({
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
                $(newForm).find('.pBSExpDate').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            },
            pregeneratedForms: <?php echo  json_encode($packageBasisSeasonalMainForm); ?>

        });

        var pBSMSheepItForm = $("#pBSE").sheepIt({
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
                $(newForm).find('.pBSExpDate').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            },
            pregeneratedForms: <?php echo  json_encode($packageBasisSeasonalExtraForm); ?>

        });

        $('.pBSExpDate').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });



    });

</script>
