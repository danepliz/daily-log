<?php
use models\User;
use models\Transaction;

$origins = array(
		'intl' 	=> 1,
		'dom' 	=> 0,
	);

$types = array(
		'bank' 	=> Transaction::TYPE_BANK_PAYMENT,
		'id' 	=> Transaction::TYPE_ID_PAYMENT,
	);

extract($origins);
extract($types);
	
$columns = array( // DO NOT CHANGE THE KEY ORDER
			'count', 
			'remitting_amount',
			'net_comission',
			'remitting_commission',
			'target_amount',
			'payout_commission',
		);

$aggregates = array(
			'receivable' => array('remitting_amount', 'net_comission',),
			'payable' => array('remitting_commission', 'target_amount', 'payout_commission'),
		);
?>
<style>
table.genreport tr th, table.genreport tr td {
	text-align: center !important;
	padding: 6px 3px !important;
}
table.genreport tr th.thin {
	width: 100px !important;
}
.ita{
	font-style: italic;
}
img.action-image {
	float: right;
	margin-left: 10px;
	cursor: pointer; 
}
</style>
<div class="grid_12">
	
	<h2>Daily Summary Report</h2>
	<div class="clear"></div>
	<div class="section" id="gen-report-wrap">
	<?php if (empty($transactions)) {
		echo '<div class="grid_12 no-result-found">Sorry, No Records found</div>';
	} else {
		
	echo "<h4 class='info'> Transaction summary report for {$label}</h4>";
	
	foreach ($transactions as $pa => $trxns):
		
		foreach ($trxns as $a => $txns) {
		
			$data = $total = array();
			
			foreach ($origins as $o) {
				foreach ($types as $ty) {
					foreach ($columns as $col) {
						$data[$o][$ty][$col] = 0;
						$total[$col] = 0;
					}
				}
			}	
		
			foreach ($txns as $t) {
				$origin = ($t['payout_country_id'] == $t['remitting_country_id']) ? $dom : $intl;
				$type = $t['remittance_type'];
				foreach ($columns as $col) {
					
					if ($t['payout_agent_id'] != $t['remitting_agent_id']) {
						if ($a == $t['payout_agent_id'] and in_array($col, array('remitting_amount', 'net_comission', 'remitting_commission',))) continue;
						if ($a == $t['remitting_agent_id'] and in_array($col, array('target_amount', 'payout_commission', ))) continue;
					}
					
					$incr = ($col == 'count') ? 1 : $t[$col];
					
					$data[$origin][$type][$col] += $incr;
					$total[$col] += $incr;
				}
			}
		
	?>
	<div class="table">
	<h3 titl="Daily Transaction Summary Report"><?php echo $agents[$a]['name']?> 
		<span> 
			<?php echo $total['count']?> Transaction(s) &nbsp;&mdash;&nbsp; <?php echo $agents[$a]['currency'] ?>
		</span>
	</h3>
	<table border="0" width="100%" cellpadding="0" cellspacing="1" class="genreport">
	<tbody>
		<tr>
			<th rowspan="3" colspan="2" class="ita"> <?php echo $agents[$a]['name']?> </th>
			<th rowspan="3" class="">Count</th>
			<th colspan="3">Remit</th>
			<th colspan="2">Payout</th>
			<th colspan="3">Summary</th>
		</tr>
		
		<tr>
			<th colspan="2">Receivable</th>
			<th> Payable</th>
			<th colspan="2">Payable</th>
			<th rowspan="2">Receivable</th>
			<th rowspan="2">Payable</th>
			<th rowspan="2">Total</th>
		</tr>
		
		<tr>
			<th title="Remitting Amount" class="">RA</th>
			<th title="Total Commission">TC</th>
			<th title="Remitting Commission">RC (Net)</th>
			<th title="Payout Amount" class="">PA</th>
			<th title="Payout Commission">PC (Net)</th>
		</tr>
		
		<tr>
			<th rowspan="2" class="thin">International</th>
			<th class="thin">Bank Payment</th>
			<?php 
				$receivable = 0;
				$payable = 0;
				
				foreach ($data[$intl][$bank] as $echo) echo "<td>{$echo}</td>";
				foreach ($aggregates as $col => $array) {
					$aggregate[$col] = 0;
					foreach ($array as $sum) {
						$aggregate[$col] += $data[$intl][$bank][$sum];
					}
				}

				foreach ($aggregate as $key => $echo) {
					$$key += $echo;
					echo "<td>{$echo}</td>";
				}
				echo '<td>' . ($aggregate['receivable'] - $aggregate['payable']) . '</td>';
			?>	
		</tr>
		
		<tr>
			<th class="thin">ID Payment</th>
			<?php 
				foreach ($data[$intl][$id] as $echo) echo "<td>{$echo}</td>";
				foreach ($aggregates as $col => $array) {
					$aggregate[$col] = 0;
					foreach ($array as $sum) {
						$aggregate[$col] += $data[$intl][$id][$sum];
					}
				}
				foreach ($aggregate as $key => $echo) {
					$$key += $echo;
					echo "<td>{$echo}</td>";
				}
				echo '<td>' . ($aggregate['receivable'] - $aggregate['payable']) . '</td>';
			?>	
		</tr>
		
		<tr>
			<th rowspan="2" class="thin">Domestic</th>
			<th class="thin">Bank Payment</th>
			<?php 
				foreach ($data[$dom][$bank] as $echo) echo "<td>{$echo}</td>";
				foreach ($aggregates as $col => $array) {
					$aggregate[$col] = 0;
					foreach ($array as $sum) {
						$aggregate[$col] += $data[$dom][$bank][$sum];
					}
				}
				foreach ($aggregate as $key => $echo) {
					$$key += $echo;
					echo "<td>{$echo}</td>";
				}
				echo '<td>' . ($aggregate['receivable'] - $aggregate['payable']) . '</td>';
			?>	
		</tr>
		
		<tr>
			<th class="thin">ID Payment</th>
			<?php 
				foreach ($data[$dom][$id] as $echo) echo "<td>{$echo}</td>";
				foreach ($aggregates as $col => $array) {
					$aggregate[$col] = 0;
					foreach ($array as $sum) {
						$aggregate[$col] += $data[$dom][$id][$sum];
					}
				}
				foreach ($aggregate as $key => $echo) {
					$$key += $echo;
					echo "<td>{$echo}</td>";
				}
				echo '<td>' . ($aggregate['receivable'] - $aggregate['payable']) . '</td>';
			?>
				
		</tr>
		<tr>
			<th colspan="2" class="ita">Total</th>
			<?php 
				foreach ($total as $key => $echo) echo "<th>" . (($key == 'count') ? $echo : number_format($echo, 2)) . "</th>";
				echo "<th>" . number_format($receivable, 2) . "</th>";
				echo "<th>" . number_format($payable , 2). "</th>";
				$style = (($net = $receivable - $payable) < 0) ? 'style="background-color: #C44;" title="Payable"' : 'style="background-color: #4A4;" title="Receivable"';
				echo "<th {$style}>" . number_format($net, 2) . "</th>";
				 
			?>
		</tr>
		
	</tbody>
	</table>
	</div>
	<?php } endforeach; 
	}  
	?>
	</div>	
</div>
