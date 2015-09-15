<form name="group_permissions_form" method="post" action="" id="permission_form">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">

                <table class="table">
                    <tbody>
                    <tr>
                        <th><input type="checkbox" id="select_all"  name="select_all" class="simple" title="select all" /></th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                    <?php foreach($all_permissions as $module=>$permissions):
                        echo '<tr><th colspan="3"><strong>'.ucwords($module).' Permissions</strong></th></tr>';
                        if (!is_array($permissions)) continue;
                        foreach ($permissions as $name=>$descr):
                            if (!isset($db_permissions[$name])) continue;
                            $chk = (in_array($db_permissions[$name], $group_permissions)) ? " checked":"";
                    ?>
                    <tr>
                        <td><input type="checkbox" value="<?php echo $db_permissions[$name]?>" name="permission[<?php echo $db_permissions[$name]?>]"<?php echo $chk;?> class="simple checkIt <?php if ($chk != '') echo 'default'?>" /></td>
                        <td><?php echo $name?></td>
                        <td><?php echo $descr?></td>
                    </tr>
                    <?php
                        endforeach;
                        echo '<tr><td colspan="3">&nbsp;</td></td>';
                    endforeach;
                    ?>
                    </tbody>
                </table>

        </div>
    </div>

    <div class="col-md-12 ">
              <input type="submit" class="btn btn-primary save-perms" value="Save Permissions" name="save_perms"/>
        <input type="reset" class="btn btn-primary small" value="Reset" />
        <a href="<?php echo site_url('user/group')?>" class="btn btn-danger"><?php echo lang('cancel');?></a>
    </div>
</div>
</form>


<script type="text/javascript">

$(document).ready(function(){

	if ($('.checkIt:checked').length == $('.checkIt').length) $('#select_all').attr('checked',true);

	$('#select_all').click(function(){
		$('.checkIt').attr('checked', $(this).attr('checked'));
		var title = ($(this).attr('checked')) ? 'deselect all' : 'select all';
		$(this).attr('title', title);
		
	});
	$('.checkIt').click(function(){ if(!$(this).attr('checked')) $('#select_all').attr('checked',false); });
	$('.save-perms').click(function() {
		if ($('.checkIt:checked').length < 1) {
			alert('Please set atleast one permission.');
			return false;
		}
	});
});

</script>