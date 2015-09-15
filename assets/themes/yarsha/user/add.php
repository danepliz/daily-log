<?php
use user\models\User;
use user\models\Group;
use Doctrine\Common\Util\Debug;

$userGroup = $currentUser->getGroup()->id();
?>

<form class="validate" action="" method="post" name="addUser" id="userAddForm">
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Basic Infromation</h3> </div>
            <div class="panel-body">
                <div class="form-group-sm">
                    <label for="group_id">User Group</label>
                    <?php accessible_group_list('group_id', $this->input->post('group_id'), 'class="required form-control" id="group_id"') ?>
                </div>

                <div class="form-group-sm">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" value="<?php echo set_value('full_name')?>" class="form-control required" />
                </div>

                <div class="form-group-sm">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" class="form-control required email" value="<?php echo set_value('email')?>" />
                </div>
                <div class="form-group-sm">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="pass" autocomplete="off" class="required form-control" />
                </div>
                <div class="form-group-sm">
                    <label for="confirmpassword">Confirm Password</label>
                    <input type="password" name="confirmpassword" id="confirm_password" autocomplete="off" class="required form-control" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Contact Information</h3> </div>
            <div class="panel-body">

                <div class="form-group-sm">
                    <label for="address">Address</label>
                    <textarea name="address" class="form-control required" style="min-height: 10rem"><?php echo $this->input->post('address') ?></textarea>
                </div>
                <div class="form-group-sm">
                    <label for="mobile">Mobile</label>
                    <input type="text" name="mobile" class="form-control  number_only" value="<?php echo $this->input->post('mobile') ?>" />
                </div>
                <div class="form-group-sm">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" class="form-control number_only" value="<?php echo $this->input->post('phone') ?>" />
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <div class="col-md-12">

        <input type="submit" value="SAVE USER" onclick="return submitForm()" class="btn btn-primary">
        <input type="reset" value="CLEAR DATA" class="btn btn-primary">
        <a href="<?php echo site_url('user') ?>" class="btn btn-danger" >CANCEL</a>
    </div>
</div>
</form>


<script type="text/javascript">
    $(document).ready(function(){
        $('#country, #state, #city, #group_id, #branch').select2();
    });

    $(document).ready(function(){

        $('#userAddForm').attr('autocomplete', 'off');

        $('#country').bind('change',function(){
            getStatesByCountry($('#country').val());
        });

        $('#state').bind('change',function(){
            getCitiesByState($('#state').val());
        });

        $('#country').trigger('change');


        function getStatesByCountry(country_id){

            if( country_id == 'undefined' || country_id == '' ){ return }

            $.ajax({
                type: 'GET',
                url: Yarsha.config.base_url + 'location/ajax/getStates/'+country_id,
                success: function(res){
                    $('#state').html(res);
                }
            });
        }

        function getCitiesByState(state_id){

            if( state_id == 'undefined' || state_id == '' ){ return }

            $.ajax({
                type: 'GET',
                url: Yarsha.config.base_url + 'location/ajax/getCities/'+state_id,
                success: function(res){
                    $('#city').html(res);
                }
            });
        }
    });

</script>
<script type="text/javascript">
    $('#pass, #confirm_password').on('keyup', function () {
        if ($('#pass').val() == $('#confirm_password').val() && $('#pass').val()!="" && $('#confirm_password').val()!="") {
            $('#message').html('Passwords Match').css('color', 'green');
            $('#tick ').slideDown();
            $('#cross ').slideUp();
        } else {
            $('#tick ').slideUp();
            $('#cross').slideDown();
            $('#message').html('Please enter the same Password as above').css('color', '#900');
        }
        if($('#pass').val())=="")
        {
            $('#message').html('').css('color','#900');
        }

    });

</script>