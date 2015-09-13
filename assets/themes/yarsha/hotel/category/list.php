<div class="row">
    <?php
    if(user_access('administer hotel')) {?>
    <div class="col-xs-12">
        <a href="javascript:void(0)" class="btn btn-primary btn-margin" id="add-hotel-category-btn" >Add New Hotel Category</a>
    </div>
    <?php } ?>

    <div class="col-xs-12 margin" id="add-category-form-wrapper" style="display: none">
        <div class="panel panel-default">
            <div class="panel-body">
                <form role="form" method="post" action="<?php echo site_url('hotel/category/add') ?>" class="validate" >

                    <div class="form-group-sm">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control required" placeholder="category name" />
                    </div>

                    <div class="form-group-sm">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control" placeholder="add description"></textarea>
                    </div>

                    <div class="form-group-sm">
                        <input type="submit" value="SAVE" class="btn btn-primary" />
                        <input type="reset" value="CLEAR" class="btn btn-primary" />
                        <input type="button" value="CANCEL" class="btn btn-danger" id="cancel-add-hotel-category"/>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <div id="alertarea"></div>

    <div class="col-xs-12" id="hotel-category-list">
        <div class="panel panel-default">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Hotel Category List</h3>
            </div>

            <?php if( count($categories) > 0 ){ ?>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>

                    <?php
                        $count = 1;
                        foreach($categories as $cat){
                            $editAction = '';
                            $deleteAction = '';
                            if( user_access('administer hotel') ){
                                $editAction = action_button('edit', '#', array('title' => 'Edit' .$cat->getName(), 'data-toggle' => 'modal', 'data-target' =>'#categoryForm', 'data-category-id' => $cat->id()));
                                $deleteAction = action_button('delete', '#', array('data-bb' => 'custom_delete', 'title' => 'Delete' .$cat->getName(), 'data-id' => $cat->id()));
                            }


                            $out = '<tr>';
                            $out .= '<td>'.$count.'</td>';
                            $out .= '<td>'.$cat->getName().'</td>';
                            $out .= '<td>'.$cat->getDescription().'</td>';
                            $out .= '<td>'.$editAction.$deleteAction.'</td>';
                            $out .= '</tr>';
                            echo $out;
                            $count++;
                        }
                    ?>
                </tbody>
            </table>
            <?php }else{ no_results_found('No Categories Found.'); } ?>
        </div>
    </div>


</div>

<!-- hotel category edit form -->
<div class="modal fade" id="categoryForm" tabindex="-1" role="dialog" aria-labelledby="categoryFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="marketFormLabel">Hotel Category | Edit</h4>
            </div>

            <form role="form" class="validate" id="formCategory">

                <div class="col-md-12 alert alert-danger" id="market-alert" style="display:none"></div>

                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE CATEGORY" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end hotel category edit form -->

<script type="text/javascript">

    $(document).ready(function(){

        $('#add-hotel-category-btn').click(function(){
            $('#add-hotel-category-btn, #hotel-category-list').hide();
            $('#add-category-form-wrapper').show();
        });

        $('#cancel-add-hotel-category').click(function(){
            $('#add-hotel-category-btn, #hotel-category-list').show();
            $('#add-category-form-wrapper').hide();
        });


    });

    $('#categoryForm').on('show.bs.modal', function (e) {
        var modal = $(this),
            button = $(e.relatedTarget),
            categoryID = button.data('category-id');
        remoteUrl = Yarsha.config.base_url+'hotel/ajax/getCategoryForm/'+categoryID;

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

        $('.modal-dialog').mask('Updating Category ...');

        $.ajax({
            type: 'POST',
            url: Yarsha.config.base_url + 'hotel/ajax/saveCategory',
            data: postData,
            success: function(res){
                var data = $.parseJSON(res);
                console.log(data);
                if( data.status == 'success' ){
                    window.location = '<?php echo site_url('hotel/category') ?>';
                }else{
                    $('#market-alert').html(data.message).show();
                }
                $('.modal-dialog').unmask();
            }
        });

        return false;
    });

    $("body").on('click', "a[data-bb='custom_delete']",function(e){
        var $me = $(this);
        bootbox.confirm('Are you sure you want to delete?',function(result){
            if(result==true){
                removeData($me.attr("data-id"));
            }
        });
        function removeData(id) {
            $.ajax({
                type: "POST",
                url:  Yarsha.config.base_url + 'hotel/category/deleteHotel',
                data: {id: id},
                success: function(res){
                    var data = $.parseJSON(res);
                    if(data.status && data.status == 'success'){
                        window.location = '<?php echo site_url('hotel/category') ?>'
                    }else{
                        $("#alertarea").html('<div class="alert alert-success" role="alert">'+data.message+'</div>');
                    }
                    return true;
                }
            });
        }
        return false;
    });

</script>
