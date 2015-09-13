<?php /*?>
<style type="text/css" media="print">
@page
{
size: landscape;
margin: 2cm;
}
</style>
<?php */?>
<?php echo form_open('','name = "addagent" class="validate"');?>

<?php if (isset($output)):
	$labels = array_keys($output[0]);
?>
<div class="grid_12 noprint">
<h2><?php echo $report->getName()?></h2>
<?php /* ?>
<input type="button" value="Print Report" name="" class="printbtn" />
<input type="button" value="Export Report as XLS" name="export-report" class="export-report" />
<?php */ ?>
</div>
<div class="grid_12" id="printable">
	
	<div class="section">
		<?php echo $report->getDescr()?>
	</p>
		<table id="show-report" border=0 cellspacing=1 cellpadding=1 class="compact">
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
	<?php
		if(isset($pagination)) { ?>
		<div class="pagination noprint">
			<div class="" style="float:left;">
				<?php // echo $offset + 1 . '-' . $offset + $perpage . 'of' . $total?>
			</div>
			<div class="nxt">
			  <?php	echo $pagination ?>
			</div>
			<div class="clear"></div>
		  </div>
	<?php } ?>
</div>
<div class="clear"></div>
	 


<div class="clear"></div>
<div class="grid_12 noprint">
	
	<input type="button" value="Print Report" name="" class="printbtn" />
    <input type="button" value="Export Report as XLS" name="export-report" class="export-report" />

</div>
<?php else:?>
<div class="grid_12">
<h2><?php echo lang('Preview_Report');?></h2>
<fieldset>
	<legend><?php echo lang('Empty_Results');?>  </legend>
	<p class="element">
		<?php echo lang('no_matching');?>
	</p>
</fieldset>
</div>
<?php endif?>
<?php echo form_close();?>
<script>
$(function(){

	$('#show-report').find('tr').each(function(i,e) {
		(i < 1) ? $(this).prepend('<th width="2%">SN</th>') 
				: $(this).prepend('<td align="center">' + (i+<?php echo (isset($pagination)) ? CI::$APP->uri->segment(4,0) : 0 ?>) + '</td>');
		
	});

	
	$('.usr-grp').bind('click',function(){
		$('#report-submit').show();
	});
	
	$('.export-report').click(function(){
		window.location = '<?php echo site_url('report/exportxls/'.$report->getSlug())?>';
	});
	
	$('.printbtn').click(function(){
		$('#printable').removeAttr('style');
		window.print();
		//$('#printable').attr('style','overflow-x:auto');
		});
});
</script>

