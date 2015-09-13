<?php 
	$from_date = ( isset($filters['from_date']) )? $filters['from_date'] : date('Y-m-d');
	$to_date = ( isset($filters['to_date']) )? $filters['to_date'] : date('Y-m-d');
	$country = ( isset($filters['country']) )? $filters['country'] : '';
	$state = ( isset($filters['state']) )? $filters['state'] : '';
	$diff = strtotime($to_date) - strtotime($from_date);
?>

<style>
.dollar{ display: block; float:left }
.test{ border: 1px solid #000; }
.test tr td, .test tr th{border-bottom:1px solid black;}
.rb{border-right:1px solid black; }
tr.lastrow td{
	border-bottom: 1px solid #FFF;
}
#state{
	height: 200px;
	width: 156px;
}
 
</style>
<div class="grid_12">
	<h2>Daily Transmission Liability Report</h2>
	
		<div class="grid_12 noprint inner-place-holder" style="float: right; text-align: right;">
			
			<img class="export-report export-report-xls action-image" src="<?php echo base_url() ?>assets/images/excel_16x16.png" alt="Print" title="Export Report as XLS" style="" /></a>
			<img class="export export-report-pdf action-image" src="<?php echo base_url(); ?>/assets/images/pdf_16x16.png" alt="Print" title="Export Report as PDF" style="">
			<img class="printbtn action-image" src="<?php echo base_url() ?>assets/images/print_16x16.png" alt="Print" title="Print Report" style="" />
		</div>
	<div class="clear"></div>
	<div class="print_table grid_12" style="display:none;">
				<table style="margin:10px auto;" >
					<tr>
						<td><strong>Country : <?php echo $country_name ?></strong></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><strong>State : <?php echo implode(', ', $state_name); ?></strong></td>
					</tr>
					<tr>
						<td><strong>From Date : <?php echo $from_date ?></strong></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><strong>To Date : <?php echo $to_date ?></strong></td>
					</tr>
				</table>
	</div>
	
	<div class="grid_2">
		
	<form class="validate" action="" method="post" name="" id="filter_form">
		<div class="section noprint">
		<table style="border-right:2px solid #CCC; padding-right: 15px;" cellspacing=0 cellpadding=0 class="compact" id="filter-txn">
			<tr><th colspan="4">Filter Options</th></tr>
			<tr>
				<td><strong><?php echo "Country"; ?></strong></td>
			</tr>
			<tr>
				<td>
					<select id = "country" name = "country">
						<option value ="" >--Select Country--</option>
						<?php foreach($countries as $key=>$value){ 
							$selected = ($key == $country) ? 'selected="selected"' : '';  ?>
						<option value ="<?php echo $key;?>" <?php echo $selected ?>><?php echo $value?></option>
						<?php }?>
					</select>
				</td>
			</tr>
			<tr>
				<td><strong><?php echo "State"; ?></strong></td>
			</tr>
			<tr>
				<td>
					<select class="multiple" multiple id = "state" name ="state[]">
						<option value="">--Select State--</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><strong><?php echo "Date From";?></strong></td>
			</tr>
			<tr>
				<td>
					<input type="text" name="from_date" id="fromdate"  value="<?php echo $from_date; ?>" autocomplete="off" />
				</td>
			</tr>
			<tr>
				<td><strong><?php echo "Date To";?></strong></td>
			</tr>
			<tr>
				<td>
					<input type="text" name="to_date" id="todate" value="<?php echo $to_date; ?>" autocomplete="off" />
				</td>
			</tr>
			
			<tr>
				<td colspan="4">
					<input type="submit" id="valid8" value="Submit"  />
        			<input type="button" value="Clear" id="clearform" />
        			<div style="display: inline-block;"><span class="error" id="info" style="display:none;"><?php echo lang('please_provide');?></span></div>
				</td>
			</tr>
			
		</table>
		</div>
	</form>
	</div>
	
	
	<div class="section grid_9" id="gen-report-wrap">
		<table cellspacing=0 class="test">
			<tr>
				<th class='rb'>Date</th>
				<th class='rb'># Of Transaction</th>
				
				<th class='rb' colspan="2">Receipt In Dollars</th>
				<th class='rb'># Of Trans. Refunded</th>
				
				<th  class='rb' colspan="2">Refunds In Dollars</th>
				
				<th class='rb' colspan="2">Receipt By Beneficiary</th>
				
				<th  colspan="2">Outstanding Daily Balance</th>
			</tr>
			<?php 

					$daylen = 60*60*24;
					
					$selectedNumberOfDays = '';
					$diff = strtotime($to_date) - strtotime($from_date) ;
					$selectedNumberOfDays = $diff / $daylen;
							
					$totalNoOfTransactions = 0;
					$totalReceiptsInDollars = 0;
					$totalNoOfTransactionRefunded = 0;
					$totalRefundsInDollar = 0;
					$totalReceivedByBenificiary = 0;
					$totalOutstandingDailyBalance = 0;
					
					$final= array();
						
					for ($i =0 ; $i <= $selectedNumberOfDays; $i++){
						$nextDate = date('Y-m-d', strtotime($from_date. ' + '.$i.' days'));
						
						$final[$i]['date'] = $nextDate;
						
						$final[$i]['# of Transactions']= 0;
						$final[$i]['Receipts in Dollars'] = 0.00;
						$final[$i]['# of Trans. Refunded'] = 0;
						$final[$i]['Refunds in Dollar'] = 0.00;
						$final[$i]['Received By Beneficiary'] = 0.00;
						$final[$i]['Outstanding Daily Balance'] = 0.00;
						foreach ($reports as $k => $v){
							if($v['date'] == $nextDate){
								$final[$i] = $v;
							}
						}
						
					}
 					array_unshift($final, $reports[0]);
 					
 					$nextOutstandingBalance = ($final[0]['Outstanding Daily Balance'] != '-')? $final[0]['Outstanding Daily Balance'] : 0.00;
 					
					foreach ($final as  $k => $report){
						
						$nextOutstandingBalance = $nextOutstandingBalance + $report['Receipts in Dollars'] - $report['Refunds in Dollar'] - $report['Received By Beneficiary'];
						
						
							if(strpos($report['date'], '-')){
								$_date = date('d', strtotime($report['date']));
								$_date= preg_replace('/^0/', '', $_date);
								echo "<tr>";
								echo "<td class='rb' align='left'>".$_date."</td>";
								echo "<td class='rb' align='right'>".$report['# of Transactions']."</td>";
								echo "<td>$</td>";
								echo "<td class='rb' align='right'> ".number_format($report['Receipts in Dollars'], '2', '.', ',')."</td>";
								echo "<td class='rb' align='right'>".$report['# of Trans. Refunded']."</td>";
								echo "<td>$</td>";
								echo "<td class='rb' align='right'>".number_format($report['Refunds in Dollar'], '2', '.', ',')."</td>";
								echo "<td >$</td>";
								echo "<td class='rb' align='right'>".number_format($report['Received By Beneficiary'], '2', '.', ',')."</td>";
								echo "<td>$</td>";
								echo "<td align='right'>".number_format($nextOutstandingBalance,'2','.',',')."</td>";
								echo "</tr>";
							}
							else{
								$_date = $report['date'];
								$_date= preg_replace('/^0/', '', $_date);
								echo "<tr>";
								echo "<td align='left' colspan='9'>".$_date."</td>";
								echo "<td>$</td>";
								echo "<td align='right'>".number_format($nextOutstandingBalance,'2','.',',')."</td>";
								echo "</tr>";
							}
						$totalNoOfTransactions += $report['# of Transactions'];
						$totalReceiptsInDollars += $report['Receipts in Dollars'];
						$totalNoOfTransactionRefunded += $report['# of Trans. Refunded'];
						$totalRefundsInDollar += $report['Refunds in Dollar'];
						$totalReceivedByBenificiary += $report['Received By Beneficiary'];
						$totalOutstandingDailyBalance += $nextOutstandingBalance;
					}
			
				echo "<tr class='aggregate lastrow'>";
					echo "<td class='rb'><b>Total</b></td>";
					echo "<td class='rb' align='right'><b>".$totalNoOfTransactions."</b></td>";
					echo "<td>$</td>";
					echo "<td class='rb' align='right'><b>".number_format($totalReceiptsInDollars, '2', '.',',')."</b></td>";
	 				echo "<td class='rb' align='right'><b>".$totalNoOfTransactionRefunded."</b></td>";
	 				echo "<td>$</td>";
	 				echo "<td class='rb' align='right'><b>".number_format($totalRefundsInDollar, '2', '.',',')."</b></td>";
	 				echo "<td>$</td>";
	 				echo "<td class='rb' align='right'><b>".number_format($totalReceivedByBenificiary, '2', '.',',')."</b></td>";
	 				echo "<td>$</td>";
	 				echo "<td align='right'><b>".number_format($totalOutstandingDailyBalance, '2', '.',',')."</b></td>";
				echo "</tr>";
			?>
			
		</table>
		
		<h3 style="width:100%; text-align:center; font-weight:bold">
			<span>Average Daily Transmission Liability(ADTL) for this month : $ <?php echo (count($reports) > 0 ) ? number_format(round($totalOutstandingDailyBalance/++$i , 2),'2','.',',') :'0.00'; ?></span>
		</h3>
	</div>

</div>

<script>
$(function(){
	$('#country').bind('change',function(){
		var country_id = $(this).val();
		if(country_id!=''){
		$.ajax({
			type : 'GET',
			url : Transborder.config.base_url+'country/ajax/getStates/'+country_id+'/'+null+'/'+true,
			success : function(data){

				var res = $.parseJSON(data);
				var opt = "";
				$.each( res, function(i, v){
					var sel = ($.inArray(i, <?php echo json_encode($state) ?>) !== -1)? 'selected="selected"' : '';
					opt += '<option value="'+i+'" '+sel+'> '+v+' </option>';
				});
				
				$('#state').html(opt);
			}
		});
		
		}else{
			var opt='<option value="">-- Any State --</option>';
				$('#state').html(opt);
			}
		});

	$('#country').trigger('change');
	
	$("#fromdate").datepicker({dateFormat: 'yy-mm-dd'});
	$("#todate").datepicker({dateFormat: 'yy-mm-dd'});
	$(".printbtn").bind("click",function(){
		$(".print_table").css({display:"block"});

		$("#gen-report-wrap").removeClass("grid_9");
		$("#gen-report-wrap").addClass("grid_12");
			window.print();
		$(".print_table").css({display:"none"});
		
		$("#gen-report-wrap").addClass("grid_9");
		$("#gen-report-wrap").removeClass("grid_12");
		
	});
	$(".export-report-xls").bind("click",function(){
		$("#filter_form").attr("action","<?php echo base_url()."report/exportADTL" ?>")
		$("#filter_form").submit();
	});

	$(".export-report-pdf").bind("click",function(){
		$("#filter_form").attr("action","<?php echo base_url()."report/exportADTL/pdf" ?>")
		$("#filter_form").submit();
	});
});
</script>
