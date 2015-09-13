<div class="row">

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Report Preview</h3></div>
            <?php echo $query_result;?>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><input type="checkbox" id="checkall" class="simple" />&nbsp;&nbsp;&nbsp;Assign Permissions</h3></div>
            <div class="panel-body">
                <?php
                foreach ($groups as $g) {
                    $checked = ($g['group_id']==1 or $g['group_id'] == Current_User::user()->getGroup()->id()) ? 'checked="checked"': '';
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
                    echo inputWrapper('name', 'Name', set_value('name'), 'class="form-control required" id="reportname"', '');
                    echo textAreaWrapper('description', 'Description', set_value('description'), 'class="form-control required" id="reportdescr"', '');

                    $placeholder = '';

                    if( count($reportgroups) > 0 ){ ?>
                    <div class="form-group-sm">
                        <label>Menu Group</label>
                        <select name="reportgroup_id" id="reportgroup_id" class="form-control">
                            <option value="">-- Select --</option>
                            <?php foreach($reportgroup as $rg){
                                echo "<option value='{$rg['id']}'  > {$rg['name']} </option>";
                            }
                            ?>
                        </select> <span>OR Add New Menu</span>
                    </div>
                <?php
                        $placeholder = 'Override Existing Menu Group';
                    }
                    echo inputWrapper('reportgroup', 'Report Group', set_value('reportgroup'), 'class="form-control" id="reportgroup" autocomplete="off" placeholder="'.$placeholder.'"', '');
                ?>
            </div>
        </div>
    </div><!-- Report Credentials -->


    <div class="clear"></div>

    <div class="col-md-12">
        <input type="submit" value="Save Report" name="save-report" class="btn btn-primary" id="report-submit"/>
    </div>
</div>





<script type="text/javascript">
$(function() {								
	$('input.datepicker').datepicker({
		dateFormat:'yy-mm-dd'
	});

	$('.report-cred').find('label').css('width', '22%');

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

	$('input.datepicker').change(function() {
		$('#report-gen').val('Regenerate Report');
	});

	$('#gen-report-wrap').find('table.genreport').each(function(i,e) {
		rows = $(this).find('tr').not('.aggregate');
		rows.each(function(j,e) {
			(j < 1) ? $(this).prepend('<th width="1%">SN</th>') 
				: $(this).prepend('<td align="center">' + j + '</td>');
		})
		$(this).find('tr.aggregate').prepend('<td>&nbsp;</td>');
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