<?php
use models\Transaction;
use models\Agent;
$ci = CI::$APP;

$forexBatchArray = array();
$forexBatchArray[""] = "Base Rate";
	
if($forexBatches and count($forexBatches) > 0)
{
	foreach($forexBatches as $fb)
	{
		$forexBatchArray[$fb->id()] = $fb->getName();
	}
}
$offset = array('plus' => 'Increase By', 'minus' => 'Decrease By');

$countryID = isset($filters['country'])? $filters['country'] : '';

$paymentMethod = 0;
?>

<div id="calculation_block">
<span id="amount_error" class="error"></span>
<form id="wdgt_calculator_form" name="wdgt_calculator_form" method="post">
	<table>
			<thead>
				<tr>
					<td>Country</td>
					<td>
						<?php 
							$opt = '<select name="destination_country" id="wdgtcalc_destination_country" class="required" >';
							$opt .= '<option value=""> -- Select Country -- </option>';
							foreach($countries as $c)
							{
								$sel = ($countryID == $c->id())? 'selected="selected"' : '';
								$opt .= '<option value="'.$c->id().'" '.$sel.'> '.$c->getName().' </option>';
							}					
							$opt .= '</select>';
							echo $opt;
						?>
					</td>
				</tr>
				<tr>
					<td>Exchange Rate</td>
					<td><?php echo form_dropdown('forex_batch', $forexBatchArray, NULL, 'id="wdgtcalc_forex_batch"'); ?></td>
				</tr>
				
				<tr>
					<td>Payment Type</td>
					<td>
					<?php 
					$paymentMethods = array(
							Transaction::TYPE_ID_PAYMENT => 'Pick Up',
							Transaction::TYPE_BANK_PAYMENT => 'Bank Payment',
							Transaction::TYPE_HOME_DELIVERY => 'Home Delivery',
							//Transaction::TYPE_WALLET_PAYMENT => 'Wallet Payment',
					);
					
					echo form_dropdown('payment_type', $paymentMethods, $paymentMethod, 'class="required" id="payment_type"');
					?>
					</td>
				</tr>
				
				<tr>
					<td>Payout Currency</td>
					<td>
					<select name="payout_currency" id="payout_currency" class="required">
						<option value="">-- Select Payout Currency --</option>
					</select>
					</td>
				</tr>
				
				<tr>
					<td>Amount</td>
					<td><input type="text" name="sending_amount" id="wdgtcalc_sending_amount" class="required text ui-widget-content" value=""></td>
				</tr>
				<tr>
					<td>Service Charge</td> 
					<td><input type="text" name="commission" id="wdgtcalc_commission" readonly="readonly" class="text ui-widget-content" /></td>
				</tr>
				<tr>
					<td>Total Amount</td> 
					<td><input type="text" name="total_amount" id="wdgtcalc_total_amount" readonly="readonly" class="text ui-widget-content"/></td>
				</tr>
				<tr>
					<td>Receiving Amount by Beneficiary</td>
					<td>
						<input type="text" name="receiving_amount" id="wdgtcalc_receiving_amount" readonly="readonly" class="text ui-widget-content"/>
						<br /><span id="showExchangeRate"></span>
					</td>
				</tr>
				<tr>
					<td>Amount in Words</td>
					<td><textarea rows="4" name="amount_in_words" id="wdgtcalc_amount_in_words" class="text ui-widget-content" readonly="readonly" ></textarea></td>
				</tr>
			</thead>
	</table>
</form>
</div>

<script type="text/javascript">

$("#wdgtcalc_destination_country").change(function() {
	  var countryId = $(this).val();
	  $.ajax({
			type : 'GET',
			url : Transborder.config.base_url+'country/ajax/getPayoutCurrencies/'+countryId,
			success : function(data){ $('#payout_currency').html(data); }
	  });
	  $('#amount_error').html("");
});
						
$(function(){
	$('#wdgtcalc_sending_amount').bind('blur', function(){
		
		var valid = $('#wdgt_calculator_form').validate({
				errorElement: 'span',
			}).form();
		
		if(valid)
			getTotalAmount($(this).val());
	});
	$('#amount_error').html("");
});

$(function(){
	$('#wdgtcalc_sending_amount').keypress(function (e) {
		if (e.which == 13) {
			var valid = $('#wdgt_calculator_form').validate({
				errorElement: 'span',
			}).form();
		
			if(valid)
				return getTotalAmount($(this).val());
		} else return;
	});
	$('#amount_error').html("");
});

function getTotalAmount(val){

	var sourceAmount = (val) ? val : '0';
	
	if(sourceAmount != 0){

		var payout_limit = $('#payout_limit').val();

		//var datatosend = 0+"/"+sourceAmount+"/"+targetCountry+"/"+forex_batch;
		var datatosend = $('#wdgt_calculator_form').serialize();
		$('#calculation_block').mask("Calculating...");
		
		$.ajax({
			type : 'POST',
			url : Transborder.config.base_url+'remittance/ajax/getTotalAmountStd',
			data: datatosend,
			success : function(res){
					res = $.parseJSON(res)
					$('#calculation_block').unmask();
					
					if(res.status == 'failure'){
						return calculationFailureAction(res.description);
					}
					if(res.status == 'success'){
						
						if(payout_limit != 0 && parseInt(res.data.targetAmount) > payout_limit){ return calculationFailureAction('Sorry, Selected Agent Location cannot pay more than '+payout_limit); }
						
						$('#wdgtcalc_sending_amount').val(res.data.sourceAmount);
						send_amt = $('#wdgtcalc_sending_amount').val();
						$('#wdgtcalc_commission').val(res.data.serviceCharge);
						$('#wdgtcalc_total_amount').val(res.data.totalAmount);
						tot_amt = $('#wdgtcalc_total_amount').val();
						$('#wdgtcalc_receiving_amount').val(res.data.targetAmount);	
						$('#wdgtcalc_amount_in_words').val(res.data.amount_in_words);
						$('#showExchangeRate').html(' @ '+res.data.sdExchangeRate);
						$('#amount_error').html("");
					};
				}
		});
	};
}

function checkMask(){
if( $('.loadmask').is(':visible') ) { alert('calculating'); return false; }
else return true;
}	

function calculationFailureAction(error)
{
	$('#wdgtcalc_source_amount').focus();
	$('#amount_error').html(error);
	$('#wdgtcalc_source_amount, #wdgtcalc_sending_amount, #wdgtcalc_total_amount, #wdgtcalc_receiving_amount, #wdgtcalc_commission, #wdgtcalc_amount_in_words').val("");
	$('#showExchangeRate').html("");
	return false;
}

</script>
