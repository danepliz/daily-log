<?php 
	
	$from_date = ( isset($filters['from_date']) )? $filters['from_date'] : date('Y-m-d');
	$to_date = ( isset($filters['to_date']) )? $filters['to_date'] : date('Y-m-d');
// 	$country = ( isset($filters['country']) )? $filters['country'] : '';
// 	$state = ( isset($filters['state']) )? $filters['state'] : '';
	$diff = strtotime($to_date) - strtotime($from_date);
	
?>
<style>
.dollar{ display: block; float:left }
.test{ border: 1px solid #000; width: 100%; }
.test tr td, .test tr th{border-bottom:1px solid black;}
.rb{border-right:1px solid black; }
tr.lastrow td{
	border-bottom: 1px solid #FFF;
}
 
</style>
<div class="grid_12">
	<h2 style="width:100%; text-align:center;"><?php echo 'Daily Transmission Liability Report';?></h2>
	
	<table style="margin:10px auto">
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
	
	<div class="section" id="gen-report-wrap">
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
