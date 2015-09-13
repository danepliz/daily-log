<?php
use agent\models\Agent;
?>

<form name="addagent" class="validate" method="post" id="addagent_form" >
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Basic Information</h3></div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="required form-control" value="<?php echo set_value('name')?>"/>
                </div>
                <div class="form-group">
                    <label for="phone1">Phone 1</label>
                    <input type="text" name="phone1" class="form-control number_only" value="<?php echo set_value('phone1')?>" />
                </div>
                <div class="form-group">
                    <label for="phone2">Phone 2</label>
                    <input type="text" name="phone2" class="form-control number_only" value="<?php echo set_value('phone2')?>" />
                </div>
                <div class="form-group">
                    <label for="email1">Email 1</label>
                    <input type="text" name="email1" class="form-control required email" value="<?php echo set_value('email1')?>" />
                    <input type="hidden" name="email1_old" value="" />
                </div>
                <div class="form-group">
                    <label for="email2">Email 2</label>
                    <input type="text" name="email2" class="form-control email" value="<?php echo set_value('email2')?>" />
                    <input type="hidden" name="email2_old" value="" />
                </div>
                <div class="form-group">
                    <label for="website1">Website 1</label>
                    <input type="text" name="website1" class="form-control website website_url" value="<?php echo set_value('website1')?>" />
                </div>
                <div class="form-group">
                    <label for="website2">Website 2</label>
                    <input type="text" name="website2" class="form-control website website_url" value="<?php echo set_value('website2')?>" />
                </div>
                <div class="form-group">
                    <label for="">Skype ID</label>
                    <input type="text" name="skype" class="form-control" value="<?php echo set_value('skype')?>" />
                </div>
                <div class="form-group">
                    <label for="fax">Fax</label>
                    <input type="text" name="fax" class="form-control number_only" value="<?php echo set_value('fax')?>" />
                </div>
                <div class="form-group">
                    <label for="po_box">PO BOX</label>
                    <input type="text" class="form-control" name="po_box" value="<?php echo set_value('po_box') ?>"/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Location Infromation</h3>
            </div>
            <div class="panel-body">
                <div class="box-body">

    <div class="form-group">
            <label for="country">Country</label>
            <?php getCountrySelectElement('country', NULL, 'class="form-control required" id="country"') ?>
        </div>

        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" value="<?php echo set_value('city') ?>" class="form-control required" id="city" placeholder="city" />
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" value="<?php echo set_value('address')?>" class="required form-control"></textarea>
        </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Others</h3>
            </div>
            <div class="panel-body">
                <div class="form-group-sm">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" value="<?php echo set_value('description')?>" placeholder="other details" style="min-height: 30rem;" ></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <div class="col-md-12">
         <input type="submit" value="SAVE AGENT" class="btn btn-primary">
        <input type="reset" value="CLEAR DATA" class="btn btn-primary">
        <a href="<?php echo site_url('agent') ?>" class="btn btn-danger" >CANCEL</a>
    </div>

</div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $('#country').select2();

        Yarsha.validator = $('form.validate').validate({
            errorElement:'span'
        });


});


</script>