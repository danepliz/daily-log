	<h2>Filtered Transaction list</h2>
	<div class="section">
		<table border=0 cellspacing=1 cellpadding=1>
			<tr>
				<th class="serial" width="3%">S.N.</th>
				<th>Tracking Code</th>
				<th>Remitting Agent</th>
				<th>Remitting User</th>
				<th>Total Amount</th>
				<th>Amount (LCY)</th>
				<th>Amount (USD)</th>
				<th>Receiving Amount</th>
				<th>Service Charge</th>
				<th>Remit Date</th>
				<th>Beneficiary Name</th>
				<th>Payout Agent</th>
				<?php /* ?><th>Aml Status</th> <?php */ ?>
				<th>Status</th>
			</tr>
			<?php
				$count = 1;
				foreach($transactions as $t):
				$created = $t['created'];
				$beneficiaryName = $t['beneficiary_fname'].' '.$t['beneficiary_mname'].' '.$t['beneficiary_lname'];
			?>
			
			<tr>
				<td><?php echo $count++;?></td>
				<td>&nbsp;<?php echo $t['tracking_number'];?></td>
				<td><?php echo $t['remitting_agent_name'];?></td>
				<td><?php echo $t['entered_by'];?></td>
				<td><?php echo $t['deposited_amount'];?></td>
				<td><?php echo $t['remitting_amount'];?></td>
				<td><?php echo $t['usd_amount'];?></td>
				<td><?php echo $t['target_amount'];?></td>
				<td><?php echo $t['service_charge'];?></td>
				<td><?php echo $created->format('F j, Y');?></td>
				<td><?php echo $beneficiaryName ?></td>
				<td><?php echo $t['payout_agent_name'];?></td>
				<?php /* ?><td><?php echo $aml_details ?></td> <?php */ ?>
				<td><?php echo $t['status'];?></td>
			</tr>
		<?php endforeach;?>
		</table>
	</div>