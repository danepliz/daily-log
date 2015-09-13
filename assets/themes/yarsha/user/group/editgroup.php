<form class="validate" action="" method="post">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group-sm">
                    <label>Group Name</label>
                    <input type="text" name="name" class="form-control required" value="<?php echo $group->getName();?>" />
                </div>

                <div class="form-group-sm">
                    <label>Description</label>
                    <textarea cols=40 rows=4 name="description" class="form-control required"><?php echo $group->getDescription();?></textarea>
                </div>

                <div class="form-group-sm">
                    <input type="submit" value="SAVE" class="btn btn-primary"/>
                    <a href="<?php echo site_url('user/group')?>" class="btn btn-danger">CANCEL</a>
                </div>
            </div>
        </div>
    </div>
</div>
</form>