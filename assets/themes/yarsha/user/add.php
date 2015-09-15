<?php
use user\models\User;
use user\models\Group;
use Doctrine\Common\Util\Debug;

$userGroup = $currentUser->getGroup()->id();
?>

<form class="validate" action="" method="post" name="addUser" id="userAddForm">
<div class="row">
    <?php
        // Basic Information
        echo panelWrapperOpen('col-md-6', 'Basic Information');
        $elem = accessible_group_list('group_id', $this->input->post('group_id'), 'class="required form-control" id="group_id"');
        echo selectElementWrapper($elem, 'User Group', 'group_id');
        echo inputWrapper('full_name', 'Full Name', set_value('full_name'), 'class="form-control required"');
        echo inputWrapper('email', 'Email', set_value('email'), 'class="form-control required email"');
        echo inputWrapper('password', 'Password', set_value('password'), 'class="form-control required" id="pass"','', TRUE);
        echo inputWrapper('confirmpassword', 'Confirm Password', set_value('password'), 'class="form-control required" id="confirm_password"','', TRUE);
        echo panelWrapperClose();

        // Contact Information
        echo panelWrapperOpen('col-md-6', 'Contact Information');
        echo textAreaWrapper('address', 'Address', $this->input->post('address'), 'class="form-control required" style="min-height: 10rem"');
        echo inputWrapper('mobile', 'Mobile', set_value('mobile'), 'class="form-control number_only required"');
        echo inputWrapper('phone', 'Phone', set_value('phone'), 'class="form-control number_only"');
        echo panelWrapperClose();
    ?>

    <div class="clear"></div>

    <div class="col-md-12">

        <input type="submit" value="SAVE USER" onclick="return submitForm()" class="btn btn-primary">
        <input type="reset" value="CLEAR DATA" class="btn btn-primary">
        <a href="<?php echo site_url('user') ?>" class="btn btn-danger" >CANCEL</a>
    </div>
</div>
</form>

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