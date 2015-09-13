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
	
	<h2><?php echo lang('Daily_Summary_Report');?></h2>
	
	<form class="validate" action="" method="post" name="" id="filter_form">
		<div class="section noprint">
		<table border=0 cellspacing=0 cellpadding=0 class="compact filter-area" id="filter-txn">
			<tr><th colspan="3"><?php echo lang('Filter_Report');?></th></tr>
			<tr>
				<td><strong><?php echo lang('Agent');?></strong></td>
				<td><strong><?php echo lang('Transaction_Date_From');?></strong></td>
				<td><strong><?php echo lang('Transaction_Date_To');?></strong></td>
			</tr>
			<tr>
				<td>
					<select name="agent" id="agent">
					<?php if ($level == User::USERLEVEL_SA) {
						$agentID = \Current_user::getAgents()->agent_id; 
						echo "<option value='" . $agentID . "'>{$agents[$agentID]['name']}</option>";
					} else { ?>
					<option value="">--<?php echo lang('Select_Agent');?>--</option>
					<?php 
						foreach ($agents as $ag) {
							$sel = (isset($_POST['agent']) and $_POST['agent'] == $ag['agent_id']) ? ' selected="selected"' : '';
							if (! $ag['parentAgent_id'] ) echo "<option value='{$ag['agent_id']}' {$sel}>{$ag['name']}</option>"; 
						}
					}?>
					</select>	
				</td>
				<td>
					<input type="text" name="fromdate" id="fromdate" placeholder="Date: yyyy-mm-dd" value="<?php echo empty($_POST) ? date('Y-m-d') : $_POST['fromdate'] ?>" autocomplete="off" />
				</td>
				<td>
					<input type="text" name="todate" id="todate" placeholder="Date: yyyy-mm-dd" value="<?php echo empty($_POST) ? date('Y-m-d') : $_POST['todate'] ?>" autocomplete="off" />
				</td>
			</tr>
			
			<tr>
				<td colspan="3">
					<input type="submit" id="valid8" value="Submit"  />
        			<input type="button" value="Clear" id="clearform" />
        			<div style="display: inline-block;"><span class="error" id="info" style="display:none;"><?php echo lang('please_provide');?></span></div>
				</td>
			</tr>
			
		</table>
		</div>
	</form>
	
	
	<?php if (! empty($transactions)):?>
		<div class=" noprint">
			<a href="<?php echo current_url()?>?do=xls&agent=<?php echo isset($_POST['agent'])?$_POST['agent']:''?>&fromdate=<?php 
				echo isset($_POST['fromdate'])?$_POST['fromdate']:date('Y-m-d')?>&todate=<?php 
				echo isset($_POST['todate'])?$_POST['todate']:date('Y-m-d')?>">
				<img class="export-report action-image" src="<?php echo base_url() ?>assets/images/excel_16x16.png" alt="Print" title="Export Report as XLS" style="" /></a>
    		<img class="printbtn action-image" src="<?php echo base_url() ?>assets/images/print_16x16.png" alt="Print" title="Print Report" style="" />
		</div>
	<?php endif;?>
	<div class="clear"></div>
	<div class="section" id="gen-report-wrap">
	<?php if (empty($transactions)) {
		echo '<div class="grid_12 no-result-found">Sorry, No Records found</div>';
	} else {
	
	echo "<h4 class='info'> Transaction summary report for {$label}</h4>";
		
	foreach ($transactions as $pa => $trxns):

		$data = $total = array();
	
		foreach ($origins as $o) {
			foreach ($types as $ty) {
				foreach ($columns as $col) {
					$data[$o][$ty][$col] = 0;
					$total[$col] = 0;
				}
			}
		}
		
		foreach ($trxns as $a => $txns) {
			foreach ($txns as $t) {
				$origin = ($t['payout_country_id'] == $t['remitting_country_id']) ? $dom : $intl;
				$type = $t['remittance_type'];
				foreach ($columns as $col) {

					if ($t['payout_agent_id'] != $t['remitting_agent_id']) {
						if ($a == $t['payout_agent_id'] and in_array($col, array('remitting_amount', 'net_comission', 'remitting_commission', ))) continue;
						if ($a == $t['remitting_agent_id'] and in_array($col, array('target_amount', 'payout_commission', ))) continue;
					}
					
					$incr = ($col == 'count') ? 1 : $t[$col];
					
					$data[$origin][$type][$col] += $incr;
					$total[$col] += $incr;
				
				}
				 
			}
		}
		
	?>
	<div class="table">
	<h3 title="Daily Transaction Summary Report"><?php echo ($level == User::USERLEVEL_SA) ? $agents[$a]['name'] : $agents[$pa]['name'] ?> 
		<span> 
			<?php echo $total['count']?><?php echo lang('Transaction_s');?>  &nbsp;&mdash;&nbsp; <?php echo ($level == User::USERLEVEL_SA) ? $agents[$a]['currency'] : $agents[$pa]['currency'] ?>
		</span>
	</h3>
	<table border="0" width="100%" cellpadding="0" cellspacing="1" class="genreport">
	<tbody>
		<tr>
			<th class="ita" rowspan="3" colspan="2"> <?php echo ($level == User::USERLEVEL_SA) ? $agents[$a]['name'] : $agents[$pa]['name'] ?> </th>
			<th rowspan="3"><?php echo lang('Count');?></th>
			<th colspan="3"><?php echo lang('Remit');?></th>
			<th colspan="2"><?php echo lang('Payout');?></th>
			<th colspan="3"><?php echo lang('BSA_Form');?></th>
		</tr>
		
		<tr>
			<th colspan="2"><?php echo lang('Receivable');?></th>
			<th><?php echo lang('Payable');?> </th>
			<th colspan="2"><?php echo lang('Payable');?></th>
			<th rowspan="2"><?php echo lang('Receivable');?></th>
			<th rowspan="2"><?php echo lang('Payable');?></th>
			<th rowspan="2"><?php echo lang('Total');?></th>
		</tr>
		
		<tr>
			<th title="Remitting Amount" class=""><?php echo lang('RA');?></th>
			<th title="Total Commission"><?php echo lang('TC');?></th>
			<th title="Remitting Commission"><?php echo lang('RC_Net');?></th>
			<th title="Payout Amount" class=""><?php echo lang('PA');?></th>
			<th title="Payout Commission"><?php echo lang('PC_Net');?></th>
		</tr>
		
		<tr>
			<th rowspan="2" class="thin"><?php echo lang('International');?></th>
			<th class="thin"><?php echo lang('Bank_Payment');?></th>
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
			<th class="thin"><?php echo lang('ID_Payment');?></th>
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
			<th rowspan="2" class="thin"><?php echo lang('Domestic');?></th>
			<th class="thin"><?php echo lang('Bank_Payment');?></th>
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
			<th class="thin"><?php echo lang('ID_Payment');?></th>
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
			<th colspan="2" class="ita"><?php echo lang('Total');?></th>
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
	<?php endforeach; 
	} 
	if (! empty($transactions)):?>
		<div class=" noprint">
			<a href="<?php echo current_url()?>?do=xls&agent=<?php echo isset($_POST['agent'])?$_POST['agent']:''?>&fromdate=<?php 
				echo isset($_POST['fromdate'])?$_POST['fromdate']:date('Y-m-d')?>&todate=<?php 
				echo isset($_POST['todate'])?$_POST['todate']:date('Y-m-d')?>">
				<img class="export-report action-image" src="<?php echo base_url() ?>assets/images/excel_16x16.png" alt="Print" title="Export Report as XLS" style="" /></a>
    		<img class="printbtn action-image" src="<?php echo base_url() ?>assets/images/print_16x16.png" alt="Print" title="Print Report" style="" />
		</div>
	<?php endif;?>
	<div class="clear"></div>
	</div>	
</div>
<script>
$(function(){
	var to = $('#todate').datepicker({
		dateFormat : 'yy-mm-dd',
		maxDate:'+0D'
	});	

	var from = $('#fromdate').datepicker({
		dateFormat : 'yy-mm-dd',
		maxDate:'+0D',
		onSelect:function(dateTxt,inst){
			var newMinDate = from.datepicker('getDate');
			to.datepicker('option','minDate',newMinDate);	
		}
	});

	$('.printbtn').click(function() {
		window.print();
	});

	$('#clearform').bind('click', function(){
		$('form#filter_form').find('select').val('');
		$('#fromdate,#todate').attr("value",'');
	});
});
</script>