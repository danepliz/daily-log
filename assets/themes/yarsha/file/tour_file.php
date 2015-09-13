    <form role="form" action="" class="validate" method="post">
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Tour File Register</h3></div>

            <div class="panel-body">
                <div class="form-group-sm col-md-2">
                    <label for="file">File #</label>
                    <input type="text" name="file" class="form-control required" />
                </div>

                <div class="form-group-sm col-md-5">
                    <label for="agent">Agent</label>
                    <?php getAgentSelectionElementForXO('agent', NULL, 'class="form-control" id="agent"') ?>
                </div>

                <div class="form-group-sm col-md-5">
                    <label for="agent">Contact Person</label>
                    <?php echo form_dropdown('agentContactPerson', array(''=> ' -- SELECT CONTACT PERSON --'), NULL, 'class="form-control" id="agentContactPerson"');  ?>
                </div>

                <div class="clear"></div>

                <div class="form-group-sm col-md-4">
                    <label for="client">Client</label>
                    <input type="text" class="form-control required" name="client">
                </div>

                <div class="form-group-sm col-md-4">
                    <label for="nationality">Nationality</label>
                    <?php getCountrySelectionElementForXo('nationality', NULL, 'class="form-control required" id="nationality"') ?>
                </div>

                <div class="form-group-sm col-md-4">
                    <label for="market">Market</label>
                    <?php getMarketSelectionElementForXo('market', NULL, 'class="form-control required" id="market"') ?>
                </div>

                <div class="clear"></div>

                <div class="form-group-sm col-md-2">
                    <label for="pax">No. Of Pax</label>
                    <input type="text" class="form-control required" name="pax">
                </div>

                <div class="form-group-sm col-md-2">
                    <label for="child">Child</label>
                    <input type="text" class="form-control" name="child" value="0">
                </div>

                <div class="form-group-sm col-md-2">
                    <label for="infants">Infants</label>
                    <input type="text" class="form-control" name="infants" value="0">
                </div>

                <div class="form-group-sm col-md-6">
                    <label for="tourOfficer">Tour Officer</label>
                    <?php getTourOfficersSelectElement('tourOfficer', NULL, 'class="form-control required" id="tourOfficer"') ?>
                </div>



                <div class="clear"></div>

                <div class="col-md-12">
                    <div class="form-group-sm">
                        <label for="instructions">Instructions</label>
                        <textarea name="instructions" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12 btn-margin">
        <input type="submit" value="SAVE" class="btn btn-primary">
        <input type="reset" value="CLEAR" class="btn btn-primary">
        <a href="<?php echo site_url('file') ?>" class="btn btn-danger" >CANCEL</a>
    </div>

</div>
</form>

<?php loadJS(array('select2.min')) ?>
<script type="text/javascript">
    $(document).ready(function(){

        $('#agent, #market, #nationality, #agentContactPerson, #tourOfficer').select2();

        $('#agent').bind('change', function(){

            var obj = $(this),
                agentID = obj.val(),
                contactObj = $('#agentContactPerson'),
                contactObjSel = $('#select2-agentContactPerson-container'),
                option = '<option value="">-- SELECT CONTACT PERSON --</option>';

            contactObjSel.html('-- SELECT CONTACT PERSON --');

            if( agentID == 'undefined' || agentID == "" ){

                contactObj.html(option);
                return false;
            }

            $.ajax({
                type: 'GET',
                url: Yarsha.config.base_url + 'agent/ajax/getContactPersonByAgent/'+ agentID,
                success : function(res){
                    var data = $.parseJSON(res);

                    if(data.length > 0){
                        for(var i= 0; i < data.length; i++){
                            option = option + '<option value="'+data[i].id+'">'+data[i].name+'</option>';
                        }
                    }
                    contactObj.html(option);

                }
            });

        });


    });
</script>