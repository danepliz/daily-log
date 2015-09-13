<?php	
	$name = str_replace(' ','_',$report->getDescr());
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$name.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
?>


<?php if (isset($output)):
	$labels = array_keys($output[0]);
?>
<div class="grid_12">
	<h2><?php echo $report->getDescr()?></h2>
	<div class="section">
		<table>
			<tr>
			<?php foreach ($labels as $l) if ($l!='id') echo '<th>'.strtoupper(str_replace('_id','',$l)).'</th>'?>
			</tr>
			
			<?php foreach ($output as $row) {

				echo '<tr>';
				foreach ($row as $k => $v) {
					
					if ($k!='id') echo '<td>'.$v.'</td>';
					
				}
				echo '</tr>';
				
			} ?>
				
				
		</table>
	</div>
</div>


<?php endif?>


