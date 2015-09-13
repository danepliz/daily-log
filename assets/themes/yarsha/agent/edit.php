<?php
use agent\models\Agent;


$city = $agent->getCity();
$countryID = ( $agent->getCountry() )? $agent->getCountry()->id() : NULL;

?>
<form role="form" method="post" action="<?php echo site_url('agent/edit/'.$agent->getSlug()) ?>" class="validate" >
    <div class="row">

        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Basic Information</h3>
                </div>

                <div class="panel-body">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="required form-control" value="<?php echo $agent->getName() ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="phone1">Phone 1</label>
                        <input type="text" name="phone1" class="form-control number_only" value="<?php echo $agent->getPhone1() ?>" />
                    </div>
                    <div class="form-group">
                        <label for="phone2">Phone 2</label>
                        <input type="text" name="phone2" class="form-control number_only" value="<?php echo $agent->getPhone2() ?>" />
                    </div>
                    <div class="form-group">
                        <label for="email1">Email 1</label>
                        <input type="text" name="email1" class="form-control required email" value="<?php echo $agent->getEmail1() ?>" />
                        <input type="hidden" name="email1_old" value="<?php echo $agent->getEmail1() ?>" />
                    </div>
                    <div class="form-group">
                        <label for="email2">Email 2</label>
                        <input type="text" name="email2" class="form-control email" value="<?php echo $agent->getEmail2() ?>" />
                        <input type="hidden" name="email2_old" value="<?php echo $agent->getEmail2() ?>" />
                    </div>
                    <div class="form-group">
                        <label for="website1">Website 1</label>
                        <input type="text" name="website1" class="form-control website_url" value="<?php echo $agent->getWebsite1() ?>" />
                    </div>
                    <div class="form-group">
                        <label for="website2">Website 2</label>
                        <input type="text" name="website2" class="form-control website_url" value="<?php echo $agent->getWebsite2() ?>" />
                    </div>
                    <div class="form-group">
                        <label for="skype">Skype ID</label>
                        <input type="text" name="skype" class="form-control" value="<?php echo $agent->getSkype()?>" />
                    </div>
                    <div class="form-group">
                        <label for="fax">Fax</label>
                        <input type="text" name="fax" class="form-control number_only" value="<?php echo $agent->getFax() ?>" />
                    </div>
                    <div class="form-group">
                        <label for="po_box">PO BOX</label>
                        <input type="text" class="form-control" name="po_box" value="<?php echo $agent->getPOBox() ?>"/>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <?php echo form_dropdown('status', Agent::$status_desc, $agent->getStatus(), 'class="required form-control" id =    "status"') ?>
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

                        <div class="form-group-sm">
                            <label for="country">Country</label>
                            <?php getCountrySelectElement('country', $countryID, 'class="form-control required" id="country"') ?>
                        </div>

                        <div class="form-group-sm">
                            <label for="city">City</label>
                            <input type="text" name="city" value="<?php echo $city?>" class="form-control required" id="city" placeholder="city" />
                        </div>

                        <div class="form-group-sm">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" class="required form-control" style="min-height:16rem"><?php echo $agent->getAddress() ?></textarea>
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
                        <textarea name="description" class="form-control" placeholder="other details" style="min-height: 34rem;"><?php echo $agent->getDescription() ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="clear"></div>
        <div class="col-md-12">
            <input type="submit" value="SAVE AGENT" class="btn btn-primary">
            <input type="reset" value="CLEAR DATA" class="btn btn-primary">
            <a href="<?php echo site_url('agent/detail/'.$agent->getSlug()) ?>" class="btn btn-danger" >CANCEL</a>
        </div>

    </div>
</form>


<script type="text/javascript">
    $(document).ready(function(){
        $('#country, #status').select2();
    });

</script>



