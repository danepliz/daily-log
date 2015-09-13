<div class="row">
    <?php
    if(user_access('administer hotel')) {?>
        <div class="col-xs-12 margin">
            <a href="javascript:void(0)" class="btn btn-primary" id="add-hotel-service-btn" >Add Hotel Service</a>
        </div>
    <?php } ?>

    <div class="col-xs-12 margin" id="add-service-form-wrapper" style="display: none">
        <div class="panel panel-default">
            <div class="panel-body">
                <form role="form" method="post" action="<?php echo site_url('hotel/service/add') ?>" class="validate">

                    <div class="form-group-sm">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control required" placeholder="service name" />
                    </div>

                    <div class="form-group-sm">
                        <label for="description">Price</label>
                        <input type="text" name="price" class="form-control" placeholder="add price"></textarea>
                    </div>

                    <div class="form-group-sm">
                        <input type="submit" value="SAVE" class="btn btn-primary" />
                        <input type="reset" value="CLEAR" class="btn btn-primary" />
                        <input type="button" value="CANCEL" class="btn btn-danger" id="cancel-add-hotel-grade"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xs-12 margin" id="hotel-service-list">
        <div class="panel panel-default">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Hotel Service List</h3>
            </div>
            <?php if( count($hotelServices) > 0 ){ ?>
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>

                    <?php
                    $count = 1;
                    foreach($hotelServices as $service){
                        $editAction = '';
                        if( user_access('administer hotel') ){
                            $editAction = action_button('edit', '#', array('data-toggle' => 'modal', 'title' => 'Edit' .$service->getServiceName(), 'data-target' =>'#gradeForm'));
                            $deleteAction = action_button('delete', '#', array('title' => 'Delete' .$service->getServiceName(), 'data-bb' => 'custom_delete'));
                        }
                        $out = '<tr>';
                        $out .= '<td>'.$count.'</td>';
                        $out .= '<td>'.$service->getServiceName().'</td>';
                        $out .= '<td>'.$service->getPrice().'</td>';
                        $out .= '<td>'.$editAction.$deleteAction.'</td>';
                        $out .= '</tr>';

                        echo $out;
                        $count++;
                    }
                    ?>
                    </tbody>
                </table>
            <?php }else{ no_results_found('No Hotel Services Found.'); } ?>
        </div>
    </div>
</div>

<!--<!-- hotel grade edit form -->
<!--<div class="modal fade" id="gradeForm" tabindex="-1" role="dialog" aria-labelledby="gradeFormLabel" aria-hidden="true">-->
<!--    <div class="modal-dialog">-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header">-->
<!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
<!--                <h4 class="modal-title" id="marketFormLabel">Hotel Grade | Edit</h4>-->
<!--            </div>-->
<!---->
<!--            <form role="form" class="validate" id="formGrade">-->
<!---->
<!--                <div class="col-md-12 alert alert-danger" id="market-alert" style="display:none"></div>-->
<!---->
<!--                <div class="modal-body">-->
<!---->
<!--                </div>-->
<!---->
<!--                <div class="modal-footer">-->
<!--                    <input type="submit" class="btn btn-primary" value="SAVE GRADE " />-->
<!--                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>-->
<!--                </div>-->
<!---->
<!--            </form>-->
<!---->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--<!-- end market edit form -->


<script type="text/javascript">

    $(document).ready(function(){

        $('#add-hotel-service-btn').click(function(){
            $('#add-hotel-service-btn, #hotel-service-list').hide();
            $('#add-service-form-wrapper').show();
        });

        $('#cancel-add-hotel-grade').click(function(){
            $('#add-hotel-service-btn, #hotel-service-list').show();
            $('#add-service-form-wrapper').hide();
        });

    });


    $('#gradeForm').on('show.bs.modal', function (e) {
        var modal = $(this),
            button = $(e.relatedTarget),
            gradeID = button.data('grade-id');
        remoteUrl = Yarsha.config.base_url+'hotel/ajax/getGradeForm/'+gradeID;

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

    $('#formGrade').submit(function(e){

        e.preventDefault();
        var _form = $(this);
        if( ! _form.valid() ){ return false; }

        var postData = _form.serialize();
        // console.log(postData);

        $('.modal-dialog').mask('Updating Grade ...');

        $.ajax({
            type: 'POST',
            url: Yarsha.config.base_url + 'hotel/ajax/saveGrade',
            data: postData,
            success: function(res){
                var data = $.parseJSON(res);
                console.log(data);
                if( data.status == 'success' ){
                    window.location = '<?php echo site_url('hotel/grade') ?>';
                }else{
                    $('#market-alert').html(data.message).show();
                }
                $('.modal-dialog').unmask();
            }
        });

        return false;

    });

//    $("body").on('click', "a[data-bb='custom_delete']",function(e){
//        var $me = $(this);
//        bootbox.confirm('Are you sure you want to delete?',function(result){
//            if(result==true){
//                removeData($me.attr("data-id"));
//            }
//        });
//        function removeData(id) {
//            $.ajax({
//                type: "POST",
//                url:  Yarsha.config.base_url + 'hotel/grade/deleteHotel',
//                data: {id: id},
//                success: function(res){
//                    var data = $.parseJSON(res);
//                    if(data.status && data.status == 'success'){
//                        window.location = '<?php //echo site_url('hotel/grade') ?>//'
//                    }else{
//                        $("#alertarea").html('<div class="alert alert-success" role="alert">'+data.message+'</div>');
//                    }
//                    return true;
//                }
//            });
//        }
//        return false;
//    });

</script>