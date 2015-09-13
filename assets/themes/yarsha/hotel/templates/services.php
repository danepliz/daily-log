<div class="col-md-12">
    <table class="table table-striped data-table">
        <tbody>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Action</th>

        </tr>

        <?php

        $count = 1; foreach($services as $s){ $sID = $s->id(); ?>
            <tr id="cp-data-<?php echo $sID ?>">
                <td><?php echo $s->getName() ?></td>
                <td><?php echo $s->getDescription() ?></td>

                <td>
                    <?php
                    if(user_access('edit hotel service')){ //contactPersonsForm //hotel/contactPerson/edit/'.$cp->id()
                        echo action_button('edit', '#', array('title' => 'Edit' .$s->getName(),'class'=>"edit-service",'data-service-id' => $sID, 'data-toggle' => 'modal', 'data-target' => '#addServices', 'data-form-type'=>'E'));
                    }
                    echo action_button('delete', '#', array('data-be' => 'custom_delete', 'title' => 'Delete' .$s->getName(), 'data-id' => $s->id()));
                    ?>
                </td>
            </tr>
            <?php $count++; } ?>
        </tbody>
    </table>

    <a href="#" class="btn btn-primary btn-margin" data-toggle="modal" data-target="#addServices">ADD SERVICE</a>
</div>
<!-- Add outlets form -->
<div class="modal fade" id="addServices" tabindex="-1" role="dialog" aria-labelledby="addServicesFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addServicesLabel"> Service | <?php echo ucwords($hotel->getName()) ?></h4>
            </div>

            <form role="form" class="validate" id="formService" data-person="0" data-hotel="<?php echo $hotel->id() ?>">
                <div class="alert alert-danger alert-dismissable personError hidden"></div>
                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE SERVICE" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('th').click(function(){
            console.log('clicked');
        });

        /* CONTACT PERSON FORM RENDERING */
        $('#addServices').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                form_type = button.attr('data-form-type'),

                remoteUrl = Yarsha.config.base_url+'hotel/ajax/getServiceForms';
            modal.find('form').attr('data-service', 0);
            if( form_type == 'E' ){
                var _service_id = button.attr('data-service-id');
                modal.find('form').attr('data-service', _service_id);
                remoteUrl = remoteUrl + '/' + _service_id;
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
        $('#formService').submit(function(e){
            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }
            var postData = _form.serialize(),
                service_data = _form.attr('data-service'),
                hotelId = _form.attr('data-hotel'),
                baseURL = Yarsha.config.base_url + 'hotel/ajax/saveService/'+hotelId,
                isEditing = ( service_data && service_data !== '0' && service_data !== "" )? true : false,
                remoteURL = isEditing ? baseURL + '/' + service_data : baseURL;
            $('body').mask('Processing Request ...');
            $.ajax({
                type: 'POST',
                url: remoteURL,
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status == 'success' ){
                        window.location = Yarsha.config.base_url+'hotel/detail/<?php echo $hotel->slug() ?>?t=HotelServices';
                    }else{
                        $('.personError').html(data.message).removeClass('hidden');
                    }
                    $('body').unmask();
                }
            });

            return false;
        });

        $("body").on('click', "a[data-be='custom_delete']", function (e) {
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
                url: Yarsha.config.base_url + 'hotel/ajax/deleteService',
                data: {id: id},
                success: function (res) {
                    var data = $.parseJSON(res);
                    if( data.status == 'success' ) {
                        window.location = Yarsha.config.base_url + 'hotel/detail/<?php echo $hotel->slug() ?>?t=HotelServices';
                    }
//                        alert(res)
                }
            });
        }
    });
</script>