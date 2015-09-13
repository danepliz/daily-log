<form role="form" action="" class="validate" method="post">
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Tour File Register</h3></div>

            <div class="panel-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="file">File #</label>
                        <input type="text" name="file" class="form-control required" />
                    </div>
                </div>

                <div class="clear"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="agent">Agent</label>
                        <?php getAgentSelectionElementForXO('agent', NULL, 'class="form-control" id="agent"') ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="client">Client</label>
                        <input type="text" class="form-control required" name="client">
                    </div>
                </div>

                <div class="clear"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pax">No. Of Pax</label>
                        <input type="text" class="form-control required" name="pax">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="child">Child</label>
                        <input type="text" class="form-control" name="child" value="0">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="infants">Infants</label>
                        <input type="text" class="form-control" name="infants" value="0">
                    </div>
                </div>

                <div class="clear"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <?php getCountrySelectionElementForXo('nationality', NULL, 'class="form-control required" id="nationality"') ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="market">Market</label>
                        <?php getMarketSelectionElementForXo('market', NULL, 'class="form-control required" id="market"') ?>
                    </div>
                </div>

                <div class="clear"></div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="instructions">Instructions</label>
                        <textarea name="instructions" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <input type="submit" value="SAVE" class="btn btn-primary">
        <input type="reset" value="CLEAR" class="btn btn-primary">
        <a href="<?php echo site_url('exchangeOrder') ?>" class="btn btn-danger" >CANCEL</a>
    </div>

</div>
</form>

<?php loadJS(array('select2.min')) ?>
<script type="text/javascript">
    $(document).ready(function(){

        $('#agent, #market, #nationality').select2();


    });
</script>