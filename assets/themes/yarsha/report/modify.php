<?php echo form_open('','name = "addreport" class="validate"');?>

<div class="row">
    <?php
    $query = ($editmode) ? $this->input->post('sqlquery') : $report->getSqlQuery();
    echo textAreaWrapper('sqlquery', 'SQL Query', $query, 'style="height:200px" class="required form-control" rows="20" id="sqlquery"', 'col-md-12'); ?>
    <div class="col-md-12 generate" style="display: none;">
        <input type="submit" value="Generate Report" class="btn btn-primary" name="gen-report" id="report-gen"/>
        <input type="button" value="Undo Change"  name="revert-change" class="btn btn-danger" id="revert-change"/>
    </div>
</div>


<div class="clear" style="height:5rem; width:100%"></div>


<?php if(isset($query_result)){ ?>
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Report Preview</h3></div>
            <?php echo $query_result;?>
        </div>

    </div>
</div>
<?php } ?>


<?php /* if (isset($query_result)):?>
<div class="grid_12 output">
<h2><?php echo lang('Preview_Report');?></h2>
	<div class="section" id="gen-report-wrap">
		<?php echo $query_result;?>
	</div>
</div>
<?php endif; */ ?>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><input type="checkbox" id="checkall" class="simple" />&nbsp;&nbsp;&nbsp;Assign Permissions</h3></div>
            <div class="panel-body">
                <?php
                foreach ($groups as $g) {
                    $checked = ($g['group_id']==1) ? 'checked="checked"': '';
                    if ($g['group_id']!=1) {
                        $checked = (in_array($g['group_id'], $report->getUserGroups())) ? 'checked="checked"': '';
                    }
//                    $checked = ($g['group_id']==1 or $g['group_id'] == Current_User::user()->getGroup()->id()) ? 'checked="checked"': '';
                    echo '<div class="list-row" style="float:left; width:48%;">
											<span style="float: left; width:8%;"><input type="checkbox" class="simple usr-grp" value="'.$g['group_id'].'" name="usrgrp[]" '.$checked.' /></span>
											<span style="float: left;  width:90%;" >' .$g['name']. '</span>
										</div>';
                }
                ?>
            </div>
        </div>
    </div><!-- assign permission -->

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Report Detail</h3></div>
            <div class="panel-body">

                <?php
                $name = ($editmode) ? $this->input->post('name') : $report->getName();
                $desc = ($editmode) ? $this->input->post('description') : $report->getDescr();
                echo inputWrapper('name', 'Name', $name, 'class="form-control required" id="reportname"', '');
                echo textAreaWrapper('description', 'Description', $desc, 'class="form-control required" id="reportdescr"', '');

                $placeholder = '';

                $hasGroup = ! is_null($report->getGroup());

                if( count($reportgroups) > 0 ){ ?>
                    <div class="form-group-sm">
                        <label>Menu Group</label>
                        <select name="reportgroup_id" id="reportgroup_id" class="form-control">
                            <?php
                            if (! $hasGroup) echo '<option value=""> -- Select -- </option>';
                            foreach ($reportgroups as $rg) {
                                $sel = ($hasGroup and $rg['id'] == $report->getGroup()->id()) ? 'selected="selected"' : '';
                                echo "<option value='{$rg['id']}' {$sel} > {$rg['name']} </option>";
                            }
                            ?>
                        </select> <span>OR Add New Menu</span>
                    </div>
                    <?php
                    $placeholder = 'Override Existing Menu Group';
                }
                $class = (count($reportgroups) == 0 or ! $hasGroup) ? 'required toggle-reqd' : '';
                echo inputWrapper('reportgroup', 'Report Group', set_value('reportgroup'), 'class="form-control '.$class.'" id="reportgroup" autocomplete="off" placeholder="'.$placeholder.'"', 'toggle-error');
                ?>

            </div>
        </div>
    </div><!-- Report Credentials -->


    <div class="clear"></div>

    <div class="col-md-12">
        <input type="submit" value="Save Report" name="save-report" class="btn btn-primary" id="report-submit"/>
    </div>
</div>






<?php echo form_close();?>
<script type="text/javascript">
$(function() {								
	$('input.datepicker').datepicker({
		dateFormat:'yy-mm-dd'
	});

	$('.report-cred').find('label').css('width', '22%');

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
		$('#reportname, #reportdescr, .toggle-reqd').removeClass('required');
	});

	$('#report-submit').bind('click',function(){
		$('#reportname, #reportdescr').addClass('required');
		if ($('#reportgroup_id').val() == '') $('.toggle-reqd').addClass('required');
		if ($('.usr-grp:checked').length < 1) {
			alert('Check atleast ONE permitted group.');
			return false;
		}
	});

	$('#sqlquery').bind('keyup',function() {
		$('.output').hide(); 
		$('.generate').show();
	});

	$('input.datepicker').change(function() {
		$('.generate').show();
	});

	$('#revert-change').click(function(){
		window.location = '<?php echo current_url()?>'
	});

	$('#gen-report-wrap').find('table.genreport').each(function(i,e) {
		rows = $(this).find('tr').not('.aggregate');
		rows.each(function(j,e) {
			(j < 1) ? $(this).prepend('<th width="1%">SN</th>') 
				: $(this).prepend('<td align="center">' + j + '</td>');
		})
		$(this).find('tr.aggregate').prepend('<td>&nbsp;</td>');
	});

	$('.filter-area').find('input, textarea, select').bind('keyup, change',function(){
		$('.generate').show();
	}); 

	$('.export-report').click(function() {
		$('form[name="dumpxls"]').submit();
	});

	$('.printbtn').bind('click', function() {
		window.print();
	});

	$('#reportgroup_id').change(function(){
		if ($(this).val() != '') {
			 $('.toggle-reqd').removeClass('required').val('');
			 $('.toggle-error').find('span.error, em.required').hide();
			  
		} else {
			$('.toggle-reqd').addClass('required');
			$('.toggle-error').find('em.required').show();
		}
	});
});

</script>