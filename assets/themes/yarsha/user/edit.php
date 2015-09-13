<?php
    $userGroupId = $userGroup = $currentUser->getGroup()->id();
?>

<form class="validate" action="" method="post" name="addUser">
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Edit User</h3> </div>

            <div class="panel-body">

                <div class="form-group-sm col-md-12">
                    <label for="group_id">User Group</label>

                    <?php if($user->getGroup()->id() == $userGroupId){?>
                        <input type="text" name="" value="<?php echo $user->getGroup()->getName();?>" class="form-control required" disabled="disabled"/>
                        <input type="hidden" name="group_id" value="<?php echo $user->getGroup()->id();?>"/>
                    <?php }else{ accessible_group_list('group_id', $user->getGroup()->id(), 'class="form-control required" id="group_id"') ; } ?>
                </div>

                <div class="form-group-sm col-md-12">
                    <label for="fullName">Full Name</label>
                    <input type="text" name="fullName" value="<?php echo $user->getFullName();?>" class="required form-control" />
                </div>

                <div class="form-group-sm col-md-4">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" class="number_only form-control" value="<?php echo $user->getPhone();?>" />
                </div>

                <div class="form-group-sm col-md-4">
                    <label for="mobile">Mobile</label>
                    <input type="text" name="mobile" class="form-control number_only"  value="<?php echo $user->getMobile();?>" />
                </div>

                <div class="form-group-sm col-md-4">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="required form-control" disabled="disabled" value="<?php echo $user->getEmail();?>" />
                </div>

                <div class="form-group-sm col-md-12">
                    <label for="address">Address</label>
                    <textarea name="address"class="form-control required"><?php echo $user->getAddress();?></textarea>
                </div>


                <div class="form-group-sm col-md-12">
                    <input id="confirm-submit" type="submit" value="UPDATE" class="btn btn-primary" />
                    <a href="<?php echo site_url('user')?>" class="btn btn-danger">CANCEL</a>
                </div>


            </div>
        </div>


    </div>
</div>

</form>
<script>
    $(document).ready(function(){
        $('#group_id').select2();
    });
</script>