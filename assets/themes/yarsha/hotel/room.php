<div class="row">

    <!-- categories -->
    <div class="col-md-4">
        <div class="panel panel-default" id="room-categories">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Room Categories</h3>
            </div>

            <div class="panel-body">
                <table class="table table-striped" id="tbl-room-category">
                    <tbody>
                    <tr>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    <?php if( count($categories) > 0 ){ foreach($categories as $category){ ?>
                    <tr>
                        <td><?php echo $category->getName() ?></td>
                        <td><?php echo $category->getDescription() ?></td>
                        <td><?php
                            echo action_button('edit', '#', array('title' => 'Edit ' .$category->getName(), 'data-toggle' => 'modal', 'data-target' =>'#roomcategoryForm', 'data-room-category-id' => $category->id()));
                            echo action_button('delete', '#', array('data-bb' => 'custom_delete_category', 'title' => 'Delete ' .$category->getName(), 'data-id' => $category->id()));
                            ?>
                        </td>
                    </tr>
                    <?php }} ?>
                    </tbody>
                </table>
            </div>

            <div class="panel-footer">
                <button class="btn btn-primary room-button" data-toggle="modal" data-target="#roomForm" data-room="category">ADD ROOM CATEGORY</button>
            </div>

        </div>
    </div>
    <!-- categories end -->

    <!-- room category form -->
    <div class="modal fade" id="roomcategoryForm" tabindex="-1" role="dialog" aria-labelledby="roomcategoryFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="roomtypeFormLabel">Room Category | Edit</h4>
                </div>

                <form role="form" class="validate" id="formCategory">

                    <div class="col-md-12 alert alert-danger" id="market-alert" style="display:none"></div>

                    <div class="modal-body">

                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="SAVE ROOM CATEGORY  " />
                        <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- end room category edit form -->

    <!-- types -->
    <div class="col-md-4">
        <div class="panel panel-default" id="room-categories">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Room Types</h3>
            </div>

            <div class="panel-body">
                <table class="table table-striped" id="tbl-room-type">
                    <tbody>
                    <tr>
                        <th>Types</th>
                        <th>No of Pax</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    <?php if( count($types) > 0 ){ foreach($types as $type){ ?>
                        <tr>
                            <td><?php echo $type->getName() ?></td>
                            <td><?php echo $type->getQuantity() ?></td>
                            <td><?php echo $type->getDescription() ?></td>
                            <td><?php
                                echo action_button('edit', '#', array('title' => 'Edit ' .$type->getName(), 'data-toggle' => 'modal', 'data-target' =>'#roomtypeForm', 'data-room-type-id' => $type->id()));
                                echo action_button('delete', '#', array('data-bb' => 'custom_delete_type', 'title' => 'Delete ' .$type->getName(), 'data-id' => $type->id()));
                                ?>
                            </td>
                        </tr>
                    <?php }} ?>
                    </tbody>
                </table>
            </div>

            <div class="panel-footer">
                <button class="btn btn-primary room-button" data-toggle="modal" data-target="#roomForm" data-room="type">ADD ROOM TYPE</button>
            </div>

        </div>
    </div>
    <!-- types end -->

    <!-- room type edit form -->
    <div class="modal fade" id="roomtypeForm" tabindex="-1" role="dialog" aria-labelledby="roomtypeFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="roomtypeFormLabel">Room Type | Edit</h4>
                </div>

                <form role="form" class="validate" id="formRoom">

                    <div class="col-md-12 alert alert-danger" id="market-alert" style="display:none"></div>

                    <div class="modal-body">

                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="SAVE ROOM TYPE" />
                        <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- end room type edit form -->

    <!-- plans -->
    <div class="col-md-4">
        <div class="panel panel-default" id="room-categories">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Room Plans</h3>
            </div>

            <div class="panel-body">
                <table class="table table-striped" id="tbl-room-plan">
                    <tbody>
                    <tr>
                        <th>Plans</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    <?php if( count($plans) > 0 ){ foreach($plans as $plan){ ?>
                        <tr>
                            <td><?php echo $plan->getName() ?></td>
                            <td><?php echo $plan->getDescription() ?></td>
                            <td><?php
                                echo action_button('edit', '#', array('title' => 'Edit ' .$plan->getName(), 'data-toggle' => 'modal', 'data-target' =>'#roomplanForm', 'data-room-plan-id' => $plan->id()));
                                echo action_button('delete', '#', array('data-bb' => 'custom_delete_plan', 'title' => 'Delete ' .$plan->getName(), 'data-id' => $plan->id()));
                                ?>
                            </td>
                        </tr>
                    <?php }} ?>
                    </tbody>
                </table>
            </div>

            <div class="panel-footer">
                <button class="btn btn-primary room-button" data-toggle="modal" data-target="#roomForm" data-room="plan">ADD ROOM PLAN</button>
            </div>

        </div>
    </div>
    <!-- plans end -->

    <!-- room plan edit form -->
    <div class="modal fade" id="roomplanForm" tabindex="-1" role="dialog" aria-labelledby="roomplanFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="roomplanFormLabel">Room Plan | Edit</h4>
                </div>

                <form role="form" class="validate" id="formPlan">

                    <div class="col-md-12 alert alert-danger" id="market-alert" style="display:none"></div>

                    <div class="modal-body">

                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="SAVE ROOM PLAN" />
                        <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- end room plan edit form -->

</div>


<!-- room types form -->
<div class="modal fade" id="roomForm" tabindex="-1" role="dialog" aria-labelledby="roomFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="roomFormLabel"></h4>
            </div>

            <form role="form" class="validate" id="roomTypesForm" data-type-room="">
                <div class="modal-body">
                    <div class="alert alert-danger"></div>

                    <div class="form-group-sm">
                        <label for="name">Name</label>
                        <input type="text" class="form-control required" name="name" />
                    </div>

                    <div class="form-group-sm" id="quantity">
                        <label for="quantity">No of Pax</label>
                        <input type="text" class="form-control required greaterThan[0]" name="quantity" />
                    </div>

                    <div class="form-group-sm">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" ></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary submit-btn" value="SAVE PERSON" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end room types form -->


<script type="text/javascript">
    function checkForm(form) {
        if(form.quantity.value == "0") {
            alert("Error: No Of Pax cannot be zero!");
            form.quantity.focus();
            return false;
        }
    }

    var roomModalID = '';

    $(document).ready(function(){

        $('#roomForm').on('show.bs.modal', function(e){
            var _modal = $(this),
                _button = $(e.relatedTarget),
                _action_type = _button.attr('data-room'),
                _t = getValues(_action_type);

            _modal.find('.modal-title').html(_t.modal_title);
            _modal.find('.submit-btn').val(_t.modal_btn_desc);

            _modal.find('.alert').html('').hide();

            if( _action_type == 'type' ){
                _modal.find('#quantity input').addClass('required');
                _modal.find('#quantity').show();
            }else{
                _modal.find('#quantity input').removeClass('required');
                _modal.find('#quantity').hide();
            }

            _modal.find('form#roomTypesForm').attr('data-type-room', _action_type);
            _modal.find('form#roomTypesForm').find('input[type="text"], textarea').val('');
        });

        $('form#roomTypesForm').submit(function(e){
            e.preventDefault();

            var _form = $(this);

            if( ! _form.valid() ) return false;

            var _action_type = $(this).attr('data-type-room'),
                _t = getValues(_action_type),
                remote_url = _t.remote_url;
            $('.modal-dialog').mask('Updating Room Type ...');

            $.ajax({
                url: remote_url,
                type: 'POST',
                data: _form.serialize(),
                success: function(result){

                    var res = $.parseJSON(result),
                        _row = '';

                    if( res.status && res.status == "success" ){
                        _row = createDataRow(res.data);
                        $('#tbl-room-'+ _action_type + ' tbody').append(_row);
                        $('#roomForm').modal('hide');
                    }else{
                        var errorMessage = ( res.message && res.message !== '' )? res.message : 'Something Went Wrong. Please Try Again Later';
                        _form.find('.alert').html(errorMessage).show();
                    }

                    $('.modal-dialog').unmask();
                }
            });


        });

        $('#roomtypeForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                room_typeID = button.data('room-type-id');
            remoteUrl = Yarsha.config.base_url+'hotel/room/getRoomType/'+room_typeID;

            modal.find('.alert').hide();

            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function(res){
                    modal.find('.modal-body').html(res);
                    modal.find('form').addClass('validate');
                }
            });

        });

        $('#formRoom').submit(function(e){

            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }

            var postData = _form.serialize();

            $('.modal-dialog').mask('Updating RoomType ...');

            $.ajax({
                type: 'POST',
                url: Yarsha.config.base_url + 'hotel/room/saveRoomType',
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status == 'success' ){
                        window.location = '<?php echo site_url('hotel/room') ?>';
                    }else{
                        $('#market-alert').html(data.message).show();
                    }
                    $('.modal-dialog').unmask();
                }
            });

            return false;
        });

        $('#roomcategoryForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                room_categoryID = button.data('room-category-id');
            remoteUrl = Yarsha.config.base_url+'hotel/room/getRoomCategory/'+room_categoryID;

            modal.find('.alert').hide();

            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function(res){
                    modal.find('.modal-body').html(res);
                    modal.find('form').addClass('validate');
                }
            });

        });

        $('#formCategory').submit(function(e){

            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }

            var postData = _form.serialize();

            $('.modal-dialog').mask('Updating RoomCategory ...');

            $.ajax({
                type: 'POST',
                url: Yarsha.config.base_url + 'hotel/room/saveRoomCategory',
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    console.log(data);
                    if( data.status == 'success' ){
                        window.location = '<?php echo site_url('hotel/room') ?>';
                    }else{
                        $('#market-alert').html(data.message).show();
                    }
                    $('.modal-dialog').unmask();
                }
            });

            return false;
        });

        $('#roomplanForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                room_planID = button.data('room-plan-id');
            remoteUrl = Yarsha.config.base_url+'hotel/room/getRoomPlan/'+room_planID;

            modal.find('.alert').hide();

            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function(res){
                    modal.find('.modal-body').html(res);
                    modal.find('form').addClass('validate');
                }
            });

        });

        $('#formPlan').submit(function(e){

            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }

            var postData = _form.serialize();

            $('.modal-dialog').mask('Updating RoomPlan ...');

            $.ajax({
                type: 'POST',
                url: Yarsha.config.base_url + 'hotel/room/saveRoomPlan',
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    console.log(data);
                    if( data.status == 'success' ){
                        window.location = '<?php echo site_url('hotel/room') ?>';
                    }else{
                        $('#market-alert').html(data.message).show();
                    }
                    $('.modal-dialog').unmask();
                }
            });

            return false;
        });

        $("body").on('click', "a[data-bb='custom_delete_category']",function(e){
            var $me = $(this);
            bootbox.confirm('Are you sure you want to delete?',function(result){
                if(result==true){
                    removeData($me.attr("data-id"));
                }
            });
            function removeData(id) {
                $.ajax({
                    type: "POST",
                    url:  Yarsha.config.base_url + 'hotel/room/deleteRoomCategory',
                    data: {id: id},
                    success: function(res){
                        var data = $.parseJSON(res);
                        if(data.status && data.status == 'success'){
                            window.location = '<?php echo site_url('hotel/room') ?>'
                        }else{
                            $("#alertarea").html('<div class="alert alert-success" role="alert">'+data.message+'</div>');
                        }
                        return true;
                    }
                });
            }
            return false;
        });

        $("body").on('click', "a[data-bb='custom_delete_type']",function(e){
            var $me = $(this);
            bootbox.confirm('Are you sure you want to delete?',function(result){
                if(result==true){
                    removeData($me.attr("data-id"));
                }
            });
            function removeData(id) {
                $.ajax({
                    type: "POST",
                    url:  Yarsha.config.base_url + 'hotel/room/deleteRoomType',
                    data: {id: id},
                    success: function(res){
                        var data = $.parseJSON(res);
                        if(data.status && data.status == 'success'){
                            window.location = '<?php echo site_url('hotel/room') ?>'
                        }else{
                            $("#alertarea").html('<div class="alert alert-success" role="alert">'+data.message+'</div>');
                        }
                        return true;
                    }
                });
            }
            return false;
        });

        $("body").on('click', "a[data-bb='custom_delete_plan']",function(e){
            var $me = $(this);
            bootbox.confirm('Are you sure you want to delete?',function(result){
                if(result==true){
                    removeData($me.attr("data-id"));
                }
            });
            function removeData(id) {
                $.ajax({
                    type: "POST",
                    url:  Yarsha.config.base_url + 'hotel/room/deleteRoomPlan',
                    data: {id: id},
                    success: function(res){
                        var data = $.parseJSON(res);
                        if(data.status && data.status == 'success'){
                            window.location = '<?php echo site_url('hotel/room') ?>'
                        }else{
                            $("#alertarea").html('<div class="alert alert-success" role="alert">'+data.message+'</div>');
                        }
                        return true;
                    }
                });
            }
            return false;
        });

    });

    function getValues($type){
        var _res = [],
            remote_url = Yarsha.config.base_url+'hotel/ajax/';

        if( $type == 'category' ){
            _res['modal_title'] = 'Room Category';
            _res['modal_btn_desc'] = 'SAVE ROOM CATEGORY';
            _res['remote_url'] = remote_url + 'addRoomCategory';
        }else if( $type == 'plan' ){
            _res['modal_title'] = 'Room Plan';
            _res['modal_btn_desc'] = 'SAVE ROOM PLAN';
            _res['remote_url'] = remote_url + 'addRoomPlan';
        }else if( $type == 'type' ){
            _res['modal_title'] = 'Room Type';
            _res['modal_btn_desc'] = 'SAVE ROOM TYPE';
            _res['remote_url'] = remote_url + 'addRoomType';
        }else{
            return false;
        }

        return _res;
    }

    function createDataRow(data){
        var out = '';
        if( data.length > 0 ){
            out = '<tr>';
            for(var i=0; i < data.length; i++){
                out = out + '<td>'+ data[i].name + '</td>';
                if( data[i].quantity ){
                    out = out + '<td>'+ data[i].quantity + '</td>';
                }
                out = out + '<td>'+ data[i].description + '</td>';
                out = out + '<td>';
                out = out + '<a href="#" title="Edit '+data[i].name+'" data-toggle="modal" data-target="'+data[i].dataTarget+'" '+data[i].dataRoomDesc+'="'+data[i].id+'" class="action-icon "><i class="fa fa-pencil-square"></i></a>';
                out = out + '<a href="#" data-bb="'+data[i].dataBB+'" title="Delete '+data[i].name+'" data-id="'+data[i].id+'" class="action-icon "><i class="fa fa-minus-square"></i></a>';
                out = out + '</td>'
            }
            out = out + '</tr>';
        }

        return out;
    }




</script>