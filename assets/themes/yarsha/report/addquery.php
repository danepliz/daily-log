<?php echo form_open('','name = "addreport" class="validate"');?>

<div class="grid_12">
	
	<h2><?php echo lang('Add_a_Report_Query');?></h2>

		<fieldset>
		<legend><?php echo lang('Report_Generator_Query');?></legend>
			<div class="section">
        			<form class="validate" action="" method="post">
        				<br />
        				<strong><?php echo lang('SQL_Query');?></strong>
        				<p class="element">
        					<textarea style="width:98%; height:200px" name="sqlquery" class="required"><?php echo set_value('sqlquery')?></textarea>
        				</p>
        				
        	</div>
			</fieldset>
		
</div>

<div class="grid_12">
	<input type="submit" value="Generate Report" name="gen-report" id="report-gen"/>
</div>

<?php if (isset($output)):
	$labels = array_keys($output[0]);
?>
<div class="grid_12">
<h2><?php echo lang('Preview_Report');?></h2>
<fieldset>
	<legend><?php echo lang('Generated_Report');?></legend>
	<div class="section">
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
</fieldset>
</div>
<div class="grid_12">
<h2><?php echo lang('Save_Report');?></h2>
	<div class="grid_6">
		<fieldset>
		<legend><?php echo lang('Assign_Permission_to_Groups');?>  &nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkall" /></legend>
				<div class="section">
							<p class="element">
								<?php 
								foreach ($groups as $g) {
									$checked = ($g['group_id']==1 or $g['group_id'] == Current_User::user()->getGroup()->id()) ? 'checked="checked"': '';
									echo '&nbsp;&nbsp;&nbsp; <input type="checkbox" value="'.$g['group_id'].'" name="usrgrp[]" '.$checked.' class="usr-grp"/> &nbsp;&nbsp;'.$g['name'].'<br/>';
								}
								?>
							</p>
				</div>
			</fieldset>
	</div>
	
	<div class="grid_6">
		<fieldset>
		<legend><?php echo lang('Report_Credentials');?> </legend>
				<div class="section">
							<p class="element">
								<label><?php echo lang('Name');?> </label>
								<input type="text" name="name" value="<?php echo set_value('name')?>" class="required" id="reportname"/>
							</p>
							
							<p class="element">
								<label><?php echo lang('Description');?> </label>
								<textarea cols=40 rows=6 name="description" class="required" id="reportdescr"><?php echo set_value('description')?></textarea>
							</p>
				</div>
			</fieldset>
	</div>
	
	
	
</div>
<div class="grid_12">
	<input type="submit" value="Save Report" name="save-report" id="report-submit"/>
</div>
<?php endif?>

<?php if (isset($validbutempty) and $validbutempty):?>
<div class="grid_12">
<h2><?php echo lang('Preview_Report');?></h2>
<fieldset>
	<legend><?php echo lang('Empty_Results');?>  </legend>
	<p class="element">
		<?php echo lang('no_matching');?>
	</p>
</fieldset>
</div>
<div class="grid_12">

<h2><?php echo lang('Save_Report');?></h2>
	<div class="grid_6">
		<fieldset>
		<legend><?php echo lang('Assign_Permission_to_Groups');?>  &nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkall" /></legend>
				<div class="section">
							<p class="element">
								<?php 
								foreach ($groups as $g) {
									$checked = ($g['group_id']==1 or $g['group_id'] == Current_User::user()->getGroup()->id()) ? 'checked="checked"': '';
									echo '&nbsp;&nbsp;&nbsp; <input type="checkbox" value="'.$g['group_id'].'" name="usrgrp[]" '.$checked.' class="usr-grp"/> &nbsp;&nbsp;'.$g['name'].'<br/>';
								}
								?>
							</p>
				</div>
			</fieldset>
	</div>
	
	<div class="grid_6">
		<fieldset>
		<legend><?php echo lang('Report_Credentials');?> </legend>
				<div class="section">
							<p class="element">
								<label><?php echo lang('Name');?> </label>
								<input type="text" name="name" value="<?php echo set_value('name')?>" class="required" id="reportname"/>
							</p>
							
							<p class="element">
								<label><?php echo lang('Description');?> </label>
								<textarea cols=40 rows=6 name="description" class="required" id="reportdescr"><?php echo set_value('description')?></textarea>
							</p>
				</div>
			</fieldset>
	</div>
	
	
	
</div>
<div class="grid_12">
	<input type="submit" value="Save Report" name="save-report" id="report-submit"/>
</div>
<?php endif;?>

<?php echo form_close();?>

<script>
$(function(){
	$('#report-submit').bind('click',function(){
		$('#reportname').addClass('required');
		$('#reportdescr').addClass('required');
		if ($('.usr-grp:checked').length < 1) {
			alert('Check atleast ONE permitted group.');
			return false;
		}
	});

	$('#show-report').find('tr').each(function(i,e) {
		(i < 1) ? $(this).prepend('<th width="2%">SN</th>') 
				: $(this).prepend('<td align="center">' + i + '</td>');
		
	});
	
	$('#checkall').attr('title','Select all');
	
	$('#checkall').click(function() {
		$('.usr-grp').attr('checked', $(this).attr('checked'));
		var title = ($(this).attr('checked')) ? 'Deselect all' : 'Select all';
		$(this).attr('title', title);
	});

	$('.usr-grp').click(function() {
		if (!($(this).attr('checked'))) {
			$('#checkall').attr('checked', false);
			$('#checkall').attr('title','Select all');
		}
	});
	
	
	$('#report-gen').bind('click',function(){
		$('#reportname').removeClass('required');
		$('#reportdescr').removeClass('required');
	});
});
</script>

