
<div class="row">

    <?php
    $buttons[] = [ 'type' => 'add', 'link' => site_url('project/add'), 'others' => 'id="add-user-btn"' ];
    echo actionWrapper($buttons);
    ?>

    <div class="col-md-12">
        <div class="panel panel-default">
            <?php if(isset($projects) && count($projects)>0){ ?>
                <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th class="serial" width="3%">#</th>
                        <th>FullName</th>
                        <th>Email</th>
                        <th>Group Name</th>
                        <th>Address</th>
                        <th width="12%" class="actions">Actions</th>
                    </tr>
                    <?php
                    $count = isset($offset)?$offset+1:1;
                    foreach($projects as $p): ?>

                    <?php  endforeach;?>
                    </tbody>
                </table>
                </div>
            <?php }else{ echo alertBox('No Projects Found.','warning'); } ?>

            <?php echo isset($pagination) ? '<div class="panel-footer">'.$pagination.'</div>' : '' ?>
        </div>
    </div>
</div>



<script>
$(document).ready(function(){

	$('#clear').bind('click',function(){
		$('form#filter_form').find('input[type=text], select').val("").removeAttr('selected');
		<?php ///* ?>
		<?php if(count($countries) == 1){ ?> $('#country').html('<option value="<?php echo $countries[0]['id'] ?>"> <?php echo $countries[0]['name'] ?> </option>'); <?php }else{ ?> $('#country').val(""); <?php } ?>

	}); 

	function emptyVal(obj, msg)
	{
		obj.html('<option value=""> -- '+msg+' -- </option>');
	}
	
//	$('.delete-user').click(function(){
//		return confirm('Are you sure to delete this User?');
//	});
//
//	$('.block-user').click(function(){
//		return confirm('Are you sure to block this User?');
//	});
//
//	$('.unblock-user').click(function(){
//		return confirm('Are you sure to unblock this User?');
//	});


	
});

$("body").on('click', "a[data-bb='custom_delete']",function(e){
    var $me = $(this);
    bootbox.confirm('Are you sure you want to delete?',function(result){
        if(result==true){
            removeData($me.attr("data-id"));
        }
    });
    function removeData(id) {
        $.ajax({
            type: "POST",
            url:  Yarsha.config.base_url + 'user/delete',
            data: {id: id},
            success: function(res){
                var data = $.parseJSON(res);
                if(data.status && data.status == 'success'){
                    window.location = '<?php echo site_url('user') ?>'
                }else{
                    Yarsha.notify('warn', data.message);
                }
                return true;
            }
        });
    }
    return false;
});

$("body").on('click', "a[data-bb='custom_block']",function(e){
    var $me = $(this);
    bootbox.confirm('Are you sure you want to block this user?',function(result){
        if(result==true){
            removeData($me.attr("data-id"));
        }
    });
    function removeData(id) {
        $.ajax({
            type: "POST",
            url:  Yarsha.config.base_url + 'user/block',
            data: {id: id},
            success: function(res){
                var data = $.parseJSON(res);
                if(data.status && data.status == 'success'){
                    window.location = '<?php echo site_url('user') ?>'
                }else{
                    Yarsha.notify('warn', data.message);
                }
                return true;
            }
        });
    }
    return false;
});

$("body").on('click', "a[data-bb='custom_unblock']",function(e){
    var $me = $(this);
    bootbox.confirm('Are you sure you want to unblock this user?',function(result){
        if(result==true){
            removeData($me.attr("data-id"));
        }
    });
    function removeData(id) {
        $.ajax({
            type: "POST",
            url:  Yarsha.config.base_url + 'user/unblock',
            data: {id: id},
            success: function(res){
                var data = $.parseJSON(res);
                if(data.status && data.status == 'success'){
                    window.location = '<?php echo site_url('user') ?>'
                }else{
                    Yarsha.notify('warn', data.message);
                }
                return true;
            }
        });
    }
    return false;
});

</script>