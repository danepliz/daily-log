<?php
use models\Agent;
$ci = CI::$APP;

$totalRemits = $txnCounts['remit']['total_count'];
$remittedAmount = $txnCounts['remit']['total_amount'];
$serviceCharge = $txnCounts['remit']['service_charge'];
$remittedAmount = $remittedAmount + $serviceCharge;

$totalPayouts = $txnCounts['payout']['total_count'];
$paidAmount = $txnCounts['payout']['total_amount'];

$creditLimit =  $agent->getCreditLimit();


// if payment done in ledger posting
if(isset($agent_payment) and $agent_payment != ''){
	$remittedAmount = $remittedAmount - $agent_payment['total_payment'];
// 	$creditLimit = $creditLimit + $agent_payment['total_payment'];
	$creditLimit = $creditLimit ;
}

/*
$serviceCharge = $txnCounts['remit']['service_charge'];
$remittedAmount = $remittedAmount + $serviceCharge; 					// total remit = remitted amount + service charge
$totalPayouts = $txnCounts['payout']['total_count'];
$paidAmount = $txnCounts['payout']['total_amount'];

$availableBalance = ($creditLimit + $paidAmount) - $remittedAmount; 	// available balance = credit limit + total payout - total remit  
$netCreditLimit = $creditLimit - $remittedAmount; 						// net credit limit = credit limit - total remit
$netTransactionTotal = $remittedAmount - $paidAmount;

*/

$netCreditLimit = $creditLimit - $remittedAmount;
$netTransactionTotal = $remittedAmount - $paidAmount;
$availableBalance = $netCreditLimit - ($paidAmount-(!is_null($credit_payment)?$credit_payment['credit_payment']:0));

?>

<div>
<table style="font-size:12px;">
	<thead>
		<tr>
			<td><strong>Total Remit (<?php echo $totalRemits ?>):</strong></td>
			<td><?php echo ($remittedAmount == "" or $remittedAmount == 0)? ' - ' : $remittedAmount ?></td>
		</tr>
		<tr>
			<td><strong>Total Payout (<?php echo $totalPayouts ?>):</strong></td>
			<td><?php echo ($paidAmount == "" or $paidAmount == 0)? ' - ' : $paidAmount ?></td>
		</tr>
		<tr>
			<td><strong>Net Transaction Total :</strong></td>
			<td><?php echo $netTransactionTotal;?></td>
		</tr>
		<tr>
			<td><strong>Credit Limit :</strong></td>
			<td><?php echo $creditLimit;?></td>
		</tr>
		<tr>
			<td><strong>Net Credit Limit :</strong></td>
			<td><?php echo $netCreditLimit;?></td>
		</tr>
		<tr>
			<td><strong>Available Balance :</strong></td>
			<td><?php echo $availableBalance;?></td>
		</tr>
	</thead>
</table>
</div>
