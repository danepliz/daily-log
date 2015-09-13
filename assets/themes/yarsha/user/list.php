
<?php
use user\models\Group;
use user\models\User;

$currentUser = Current_User::user();
$groupId = $currentUser->getGroup()->id();
$currentUserCountry = $currentUser->getCountry();

$fullName = isset($filters['fullName'])? $filters['fullName'] : '';
$phone = isset($filters['phone'])? $filters['phone'] : '';
$status = isset($filters['status'])? $filters['status'] : '';
//$address = isset($filters['address'])? $filters['address'] : '';
//$mobile = isset($filters['mobile'])? $filters['mobile'] : '';
//$phone = isset($filters['phone'])? $filters['phone'] : '';


$CI =& get_instance();
$CI->load->library('session');
	
$switch = TRUE;
	
if (is_numeric($main_user = $CI->session->userdata('main_user'))) {

	$main_user = $this->doctrine->em->find('user\models\User', $main_user);
	if ($main_user) $switch = FALSE;
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><?php echo $page_title ?></h3></div>
            <div class="panel-body">
                <?php if(isset($users) && count($users)>0){ ?>
                    <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th class="serial" width="3%">#</th>
                            <th>FullName</th>
                            <th>Email</th>
                            <th>Group Name</th>
                            <th>Address</th>
                            <th>Country</th>
                            <th width="12%" class="actions">Actions</th>
                        </tr>
                        <?php
                        $count = isset($offset)?$offset+1:1;
                        foreach($users as $u):

                            $isUserActive = ( $u['status'] === User::USER_STATUS_ACTIVE );

                            if($u['status'] != "1")
                            {
                                $bg = ' style="background-color: #FF5544;"';
                                $inactiveUser = TRUE;
                            }
                            else
                            {
                                $bg = '';
                                $inactiveUser = FALSE;
                            }

                            if( $inactiveUser == FALSE and $u['group_id'] == Group::SUPER_ADMIN ){ $bg = ' style="background-color: #D8EFCC;"'; }
                            $canActOn = \Current_User::canActOn($u['user_id']);

                            $isSuperAdmin = ( $u['group_id'] === Group::SUPER_ADMIN );
                            $address = [ ];
                            if( $u['city'] !== '' ){ $address[] = $u['city'];  }
                            if( $u['address'] !== '' ){ $address[] = $u['address'];  }

                            ?>
                            <tr>
                                <td><?php echo $count++;  ?></td>
                                <td><?php echo $u['fullname'];?></td>
                                <td><?php echo $u['email'];?></td>
                                <td><?php echo $u['groups'];?></td>
                                <td><?php echo implode(', ', $address )?></td>
                                <td><?php echo $u['country'];?></td>
                                <td class="actions">
                                    <?php
                                        if( ! $isSuperAdmin ){
                                            if (user_access('reset password') and $u['status'] == User::USER_STATUS_ACTIVE) echo action_button('wrench','user/resetpwd/'.$u['user_id'],array('title'	=>	'Reset Password'));
                                            if( user_access('administer user') ){
                                                if($isUserActive){
                                                    echo action_button('edit','user/edit/'.$u['user_id'],array('title'	=>	'Edit '.$u['fullname']));

                                                    if( $u['user_id'] !== $currentUser->id() ){
                                                        echo action_button('delete', '#', array('title' => 'Delete' .$u['fullname'],'data-bb' => 'custom_delete', 'data-id' => $u['user_id']));
                                                        //echo action_button('delete','user/delete/'.$u['user_id'],array('title'	=>	'Delete '.$u['fullname'], 'class'=>'delete-user'));
                                                        //echo action_button('block','user/block/'.$u['user_id'],array('title'  =>  'Block '.$u['fullname'], 'class'=>'block-user'));
                                                        echo action_button('block','#', array('title'  =>  'Block '.$u['fullname'], 'data-bb' => 'custom_block', 'data-id' => $u['user_id']));
                                                    }
                                                }
                                                else {
                                                    //echo action_button('unblock','user/unblock/'.$u['user_id'],array('title'  =>  'Unblock '.$u['fullname'], 'class'=>'unblock-user'));
                                                    echo action_button('unblock','#', array('title'  =>  'Unblock '.$u['fullname'],'data-bb' => 'custom_unblock', 'data-id' => $u['user_id']));
                                                }
                                            }
                                            if ($switch and user_access('allow user switching') and !$inactiveUser) {
                                                echo action_button('switch','auth/switchuser/'.$u['user_id'],array('title'  =>  'Run Application as '.$u['fullname']));
                                            }
                                        }else{ echo action_button('help', '#', array('title' => 'Superadmin')); }
                                    ?>
                                </td>
                            </tr>
                        <?php  endforeach;?>
                        </tbody>
                    </table>
                    </div>
                <?php }else{ no_results_found('Users Not Found.'); } ?>
            </div>

            <div class="panel-footer"><?php echo isset($pagination) ? $pagination : '' ?></div>
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