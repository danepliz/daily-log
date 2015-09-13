<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
            <form class="validate" action="" method="post">
                <div class="form-group-sm col-md-4">
                    <label for="oldpwd">Old Password</label>
                    <input type="password" name="oldpwd" class="required form-control" />
                </div>
                <div class="clear"></div>
                <div class="form-group-sm col-md-4">
                    <label for="newpwd">New Password</label>
                    <input type="password" name="newpwd" class="required form-control" />
                </div>
                <div class="clear"></div>
                <div class="form-group-sm col-md-4">
                    <label for="conpwd">Confirm Password</label>
                    <input type="password" name="conpwd" class="required form-control" />
                </div>
                <div class="clear"></div>

                <div class="form-group-sm">
                    <input type="submit" value="Change" class="btn btn-primary" />
                    <a href="<?php echo site_url('dashboard')?>" class="btn btn-danger"><?php echo lang('cancel');?></a>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>