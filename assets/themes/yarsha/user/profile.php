<form method="POST" id="user_profile" action="<?php echo site_url('user/profile') ?>">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">

                <?php
                    echo inputWrapper('fullName', 'Full Name', $user->getFullName(), 'class="form-control required" readonly="readonly" ');
                    echo inputWrapper('mobile', 'Mobile', $user->getMobile(), ' class="form-control required" ');
                    echo inputWrapper('phone', 'Phone', $user->getPhone(), ' class="form-control" ');
                    echo inputWrapper('email', 'Email', $user->getEmail(), ' class="form-control required" readonly="readonly" ');
                    echo inputWrapper('address', 'Address', $user->getAddress(), ' class="form-control required" ');
                ?>

                <div class="form-group-sm">
                    <input type="submit" value="UPDATE" class="btn btn-primary" />
                    <a href="<?php echo site_url()?>" class="btn btn-danger">CANCEL</a>
                </div>


            </div>
        </div>
    </div>
</div>
</form>