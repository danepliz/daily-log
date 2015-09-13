<?php echo form_open('','name = "addreport" class="validate"');?>

<div class="grid_12">
	
	<h2><?php echo lang('Edit_a_Report_Query');?></h2>

		<fieldset>
		<legend><?php echo lang('Report_Generator_Query');?> </legend>
			<div class="section">
        			<form class="validate" action="" method="post">
        				<br />
        				<strong><?php echo lang('SQL_Query');?> </strong>
        				<p class="element">
        					<textarea style="width:98%; height:200px" name="sqlquery" class="required" id="sqlquery"><?php echo ($newoutput or $editmode) ? set_value('sqlquery') : $report->getSqlQuery()?></textarea>
        				</p>
        				
        	</div>
			</fieldset>
		
</div>

<div class="grid_12 generate" style="display:none">
	<input type="submit" value="Generate Report for changed Query" name="gen-report" id="report-gen"/>
	<input type="button" value="Undo Query Change" name="revert-change" id="revert-change"/>
</div>

<?php if (isset($output) and !$editmode):
	$labels = array_keys($output[0]);
?>
<div class="grid_12 output">
<h2><?php echo lang('Preview_Report');?></h2>
<fieldset>
	<legend><?php echo lang('Generated_Report');?> </legend>
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
<div class="grid_12 output">
<h2><?php echo lang('Manage_Report');?></h2>
	<div class="grid_6">
		<fieldset>
		<legend><?php echo lang('Assign_Permission_to_Groups');?>  &nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkall" /></legend>
				<div class="section">
							
							<p class="element">
								<?php 
								foreach ($groups as $g) {
									$checked = ($g['group_id']==1) ? 'checked="checked"': '';
									if ($g['group_id']!=1) {
										$checked = (in_array($g['group_id'],$report->getUserGroups())) ? 'checked="checked"': '';
									}
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
								<label> <?php echo lang('Name');?></label>
								<input type="text" name="name" value="<?php echo $report->getName();// (isset($newoutput)) ? set_value('name') :?>" class="required" id="reportname"/>
							</p>
							
							<p class="element">
								<label><?php echo lang('Description');?> </label>
								<textarea cols=40 rows=6 name="description" class="required"><?php echo $report->getDescr();//(isset($newoutput)) ? set_value('description') :?></textarea>
							</p>
				</div>
			</fieldset>
	</div>
	
	
	
</div>

<div class="grid_12 output">
	<input type="button" value="Cancel" id="go_back" class="cancelaction"/>
	<input type="submit" value="Save Report" name="save-report" id="report-submit"/>
</div>
<?php endif?>

<?php if (isset($validbutempty) and $validbutempty):?>
<div class="grid_12 output" >
<h2><?php echo lang('Preview_Report');?></h2>
<fieldset>
	<legend><?php echo lang('Empty_Results');?>  </legend>
	<p class="element">
		<?php echo lang('no_matching');?>
	</p>
</fieldset>
</div>
<div class="grid_12">

<div class="grid_12 output">
<h2><?php echo lang('Manage_Report');?></h2>
	<div class="grid_6">
		<fieldset>
		<legend><?php echo lang('Assign_Permission_to_Groups');?>  &nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkall" /></legend>
				<div class="section">
							<p class="element">
							
								<?php 
								foreach ($groups as $g) {
									$checked = ($g['group_id']==1) ? 'checked="checked"': '';
									if ($g['group_id']!=1) {
										$checked = (in_array($g['group_id'],$report->getUserGroups())) ? 'checked="checked"': '';
									}
									echo '&nbsp;&nbsp;&nbsp; <input type="checkbox" value="'.$g['group_id'].'" name="usrgrp[]" '.$checked.' class="usr-grp"/> &nbsp;&nbsp;'.$g['name'].'<br/>';
								}
								?>
							</p>
				</div>
			</fieldset>
	</div>
	
	<div class="grid_6">
		<fieldset>
		<legend><?php echo lang('Report_Credentials');?></legend>
				<div class="section">
							<p class="element">
								<label><?php echo lang('Name');?> </label>
								<input type="text" name="name" value="<?php echo $report->getName();// (isset($newoutput)) ? set_value('name') :?>" class="required" id="reportname"/>
							</p>
							
							<p class="element">
								<label><?php echo lang('Description');?> </label>
								<textarea cols=40 rows=6 name="description" class="required"><?php echo $report->getDescr();//(isset($newoutput)) ? set_value('description') :?></textarea>
							</p>
				</div>
			</fieldset>
	</div>
	
	
	
</div>

<div class="grid_12 output">
	<input type="button" value="Cancel" id="go_back" class="cancelaction"/>
	<input type="submit" value="Save Report" name="save-report" id="report-submit"/>
</div>
<?php endif;?>

<?php echo form_close();?>

<script>
$(function(){
	$('#report-submit').bind('click',function(){
		$('#reportname').addClass('required');
		if ($('.usr-grp:checked').length < 1) {
			alert('Check atleast ONE permitted group.');
			return false;
		}
	});

	$('#show-report').find('tr').each(function(i,e) {
		(i < 1) ? $(this).prepend('<th width="2%">SN</th>') 
				: $(this).prepend('<td align="center">' + i + '</td>');
		
	});
	
	if ($('.usr-grp').length == $('.usr-grp:checked').length) {
		$('#checkall').attr('checked',true);
		$('#checkall').attr('title','Deselect all');
	}
	
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
	});
	
	$('#sqlquery').live('keyup',function(){
		
			$('.output').hide(); 
			$('.generate').show();
		
	});
	
	$('#revert-change').click(function(){
		window.location = '<?php echo current_url()?>'
	});
});
</script>


