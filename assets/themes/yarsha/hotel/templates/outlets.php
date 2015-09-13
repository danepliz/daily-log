<div class="col-md-12">
    <table class="table table-striped data-table">
        <tbody>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Action</th>

        </tr>

        <?php

        $count = 1; foreach($outlets as $os){ $osID = $os->id(); ?>
            <tr id="cp-data-<?php echo $osID ?>">
                <td><?php echo $os->getName() ?></td>
                <td><?php echo $os->getDescription() ?></td>

                <td>
                    <?php
                    if(user_access('edit hotel contact persons')){ //contactPersonsForm //hotel/contactPerson/edit/'.$cp->id()
                        echo action_button('edit', '#', array('title' => 'Edit' .$os->getName(),'class'=>"edit-outlet",'data-outlet-id' => $osID, 'data-toggle' => 'modal', 'data-target' => '#addoutlets', 'data-form-type'=>'E'));
                    }
                    echo action_button('delete', '#', array('data-bd' => 'custom_delete', 'title' => 'Delete' .$os->getName(), 'data-id' => $os->id()));
                    ?>
                </td>
            </tr>
            <?php $count++; } ?>
        </tbody>
    </table>

    <a href="#" class="btn btn-primary btn-margin" data-toggle="modal" data-target="#addoutlets">ADD OUTLETS</a>
 </div>
<!-- Add outlets form -->
<div class="modal fade" id="addoutlets" tabindex="-1" role="dialog" aria-labelledby="addoutletsFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addoutletsLabel">Outlets | <?php echo ucwords($hotel->getName()) ?></h4>
            </div>

            <form role="form" class="validate" id="formOutlet" data-person="0" data-hotel="<?php echo $hotel->id() ?>">
                <div class="alert alert-danger alert-dismissable personError hidden"></div>
                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE OUTLET" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end outlets form -->
<script type="text/javascript">
    $(document).ready(function(){
        $('th').click(function(){
            console.log('clicked');
        });

        /* CONTACT PERSON FORM RENDERING */
        $('#addoutlets').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                form_type = button.attr('data-form-type'),

                remoteUrl = Yarsha.config.base_url+'hotel/ajax/getOutletsForm';
            modal.find('form').attr('data-outlet', 0);
            if( form_type == 'E' ){
                var _outlet_id = button.attr('data-outlet-id');
                modal.find('form').attr('data-outlet', _outlet_id);
                remoteUrl = remoteUrl + '/' + _outlet_id;
            }
            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function(res){
                    modal.find('.modal-body').html(res);
                }
            });
        });
        /* outlet FORM SUBMISSION */
        $('#formOutlet').submit(function(e){
            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }
            var postData = _form.serialize(),
                outlet_data = _form.attr('data-outlet'),
                hotelId = _form.attr('data-hotel'),
                baseURL = Yarsha.config.base_url + 'hotel/ajax/saveOutlet/'+hotelId,
                isEditing = ( outlet_data && outlet_data !== '0' && outlet_data !== "" )? true : false,
                remoteURL = isEditing ? baseURL + '/' + outlet_data : baseURL;
            $('body').mask('Processing Request ...');
            $.ajax({
                type: 'POST',
                url: remoteURL,
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status == 'success' ){
                        window.location = Yarsha.config.base_url+'hotel/detail/<?php echo $hotel->slug() ?>?t=outlets';
                    }else{
                        $('.personError').html(data.message).removeClass('hidden');
                    }
                    $('body').unmask();
                }
            });

            return false;
        });

        $("body").on('click', "a[data-bd='custom_delete']", function (e) {
            var $me = $(this);
            bootbox.confirm('Are you sure you want to delete?', function (result) {
                if (result == true) {
                    removeData($me.attr("data-id"));
                }
            });
            return false;
        });

        function removeData(id) {
            $.ajax({
                type: "POST",
                url: Yarsha.config.base_url + 'hotel/ajax/deleteOutlet',
                data: {id: id},
                success: function (res) {
                    var data = $.parseJSON(res);
                    if( data.status == 'success' ) {
                        window.location = Yarsha.config.base_url + 'hotel/detail/<?php echo $hotel->slug() ?>?t=outlets';
                    }
//                        alert(res)
                }
            });
        }
    });
</script>