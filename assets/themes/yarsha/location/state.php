<div class="row">

    <div class="col-md-12 btn-margin">
        <?php
        if(user_access('administer location')) {?>
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#stateForm" data-state-id="0">ADD NEW STATE</a>
        <?php } ?>
        <a href="<?php echo site_url('location'); ?>" class="btn btn-primary">BACK</a>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">States | <?php echo $country->getName() ?></h3>
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Short Name</th>
                            <th>Actions</th>
                        </tr>
                        <?php if( count($states) > 0 ){ foreach($states as $s){ ?>
                            <tr>
                                <td><?php echo ++$offset;?></td>
                                <td><?php echo $s['name'];?></td>
                                <td><?php echo $s['shortName'];?></td>
                                <td>
                                    <?php  if(user_access('administer location')) {
                                        echo action_button('list', 'location/city/' . $s['state_id'], array('title' => 'List Cities of ' . $s['name']));
                                        echo action_button('edit', '#', array('title' => 'Edit State', 'data-toggle' => 'modal', 'data-target' => '#stateForm', 'data-state-id' => $s['state_id']));
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php }} ?>
                    </tbody>
                </table>
            </div></div>

            <div class="panel-footer">
                <?php echo (isset($pagination))? $pagination : ''; ?>
            </div>
        </div>
    </div>


</div>

<!-- State form -->
<div class="modal fade" id="stateForm" tabindex="-1" role="dialog" aria-labelledby="stateFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="stateFormLabel">State</h4>
            </div>

            <form role="form" class="validate" id="formState">
                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE STATE" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end state form -->


<script type="text/javascript">
    $(document).ready(function(){

        var country = '<?php echo $country->id() ?>';

        $('#stateForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                state = button.attr('data-state-id'),
                remoteUrl = Yarsha.config.base_url+'location/ajax/getStateForm';

            if( state !== "0" && state && "undefined" && state !== null ){
                remoteUrl = remoteUrl + '/' + state;
                $('#formState').attr('data-state', state);
            }

            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function(res){
                    modal.find('.modal-body').html(res);
                }
            });

        });

        $('#formState').submit(function(e){
            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }

            var postData = _form.serialize(),
                _state = _form.attr('data-state'),
                baseURL = Yarsha.config.base_url + 'location/ajax/saveState/'+country,
                isEditing = ( _state !== undefined && _state !== '0' && _state !== "" )? true : false,
                remoteURL = isEditing ? baseURL + '/' + _state : baseURL;

            $.ajax({
                type: 'POST',
                url: remoteURL,
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status == 'success' ){
                        window.location = '<?php echo base_url().'location/state/'.$country->id() ?>';
                    }else{
                        console.log(data.message);
                    }
                    $('#stateForm').modal('hide');
                }
            });
            return false;
        });

    });

</script>