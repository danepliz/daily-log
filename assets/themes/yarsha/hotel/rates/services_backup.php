<?php
use hotel\models\HotelPackage;

$strategy = \hotel\models\Hotel::$paymentStrategies[$hotel->getPaymentStrategy()];
$percent = $hotel->getPaymentStrategyPercent();
$hotelID = $hotel->id();
$servicesRatePreFilledForm = [];

?>
<form method="post" class="validate form-group-sm myForm">
    <input type="hidden" name="adaptorClass" value="Yarsha\HotelRates\ServiceRate" />
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><?php echo 'Payment Strategy : '.$strategy.'( '.$percent.'% )' ?></h3></div>

        <div class="panel-body">
            <div class="col-md-12 templateWrapper">
                <div id="serviceRates">
                    <div class="col-md-12">
                        <div class="col-md-4 no-margin">Market</div>
                        <div class="col-md-4 no-margin">Outlet</div>
                        <div class="col-md-3 no-margin">ExpiryDate</div>
                        <div class="col-md-1">&nbsp;</div>
                    </div>
                    <div id="serviceRates_template" class="col-md-12">
                        <div class="col-md-4 no-margin">
                            <div class="input-group">
                                <?php echo form_dropdown('serviceRates[#index#][market]',[], NULL,'class="form-control required" id="serviceRates_#index#_market"') ?>
                                <span class="input-group-addon">USD</span>
                            </div>
                        </div>
                        <div class="col-md-4 no-margin">
                            <?php echo form_dropdown('serviceRates[#index#][outlet]',[], NULL,'class="form-control required" id="serviceRates_#index#_outlet"') ?>
                        </div>
                        <div class="col-md-3 no-margin"><input type="text" name="serviceRates[#index#][expiryDate]" class="form-control required expDate" id="serviceRates_#index#_expdate" placeholder="Expiry Date" /></div>
                        <div class="col-md-1"><a id="serviceRates_remove_current"><i class="fa fa-trash"></i></a></div>
                        <div class="clear"></div>
                        <table class="table table-responsive">
                            <tbody id="serviceRates_#index#_rates">
                            <tr>
                                <td>Service</td>
                                <td>Rate</td>
                            </tr>
                            <tr id="serviceRates_#index#_rates_template">
                                <td>
                                    <?php echo form_dropdown('serviceRates[#index#][rates][#rates_index#][service]',[], NULL,'class="form-control required" id="serviceRates_#index#_rates_#rates_index#_amount"') ?>
                                </td>
                                <td><input type="text" name="serviceRates[#index#][rates][#rates_index#][amount]" id="serviceRates_#index#_rates_#rates_index#_amount" class="form-control" > </td>
                            </tr>
                            <tr>
                                <td colspan="2" id="serviceRates_#index#_rates_noform_templates">Add New Service Rate</td>

                            </tr>
                            <tr id="serviceRates_#index#_rates_controls">
                                <td id="serviceRates_#index#_rates_add" colspan="2"><a class="btn btn-sm btn-flat bg-olive">ADD ANOTHER SERVICE</a></td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="col-md-12" id="serviceRates_noforms_template">No Rates</div>

                        <div class="col-md-12" id="serviceRates_controls">
                            <span id="serviceRates_add"><a class="btn btn-sm btn-flat bg-olive">ADD ANOTHER RATE</a></span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <input type="submit" class="btn btn-primary" value="UPDATE HOTEL RATES" />
            <a href="<?php echo site_url('hotel/rate/show/'.$hotel->slug()) ?>" class="btn btn-danger">CANCEL</a>
        </div>
    </div>
</form>


<?php echo loadJS(['jquery.sheepit.min.js', 'jquery.sheepItPlugin']); ?>

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
                $(newForm).find('.expDate').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            },
            nestedForms: [
                {
                    id: 'serviceRates_#index#_rates',
                    options: {
                        indexFormat: '#rates_index#',
                        maxFormsCount: 0,
                        minFormsCount: 1,
                        iniFormsCount: 1
                    }
                }
            ],
            pregeneratedForms: <?php echo  json_encode($servicesRatePreFilledForm); ?>

        });

        $('.expDate').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

    });

    function calculateTotal(obj, index){
        var percent = parseFloat('<?php echo $percent ?>'),
            self = $(obj),
            amount = parseFloat(self.val()),
            total = amount + ( ( percent / 100 ) * amount ),
            id = self.attr('id').split('_')[0];
        $('#'+id+'_'+index+'_totalAmount').html(total);

    }

    function showCurrency(obj, index){
        var self = $(obj),
            market = self.val(),
            id = self.attr('id').split('_')[0],
            currencyObj = $('#'+id+'_'+index+'_currency'),
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
