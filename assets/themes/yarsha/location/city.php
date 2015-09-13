<div class="row">

    <div class="col-md-12 btn-margin">
        <?php
        if(user_access('administer location')) {?>
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#cityForm" data-city-id="0">ADD NEW CITY</a>
        <?php } ?>
        <a href="<?php echo site_url('location/state/'.$state->getCountry()->id()); ?>" class="btn btn-primary">BACK</a>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Cities | <?php echo $state->getName()?></h3>
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                    <?php if( count($cities) > 0 ){ foreach($cities as $c) { ?>
                        <tr>
                        <td><?php echo ++$offset; ?></td>
                        <td><?php echo $c['name']; ?></td>
                        <td>
                        <?php  if (user_access('administer location')) {
                            echo action_button('edit', '#', array('title' => 'Edit State', 'data-toggle' => 'modal', 'data-target' => '#cityForm', 'data-city-id' => $c['city_id']));
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
<div class="modal fade" id="cityForm" tabindex="-1" role="dialog" aria-labelledby="cityFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="cityFormLabel">City</h4>
            </div>

            <form role="form" class="validate" id="formCity">
                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE CITY" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end state form -->


<script type="text/javascript">
    $(document).ready(function(){

        var state = '<?php echo $state->id() ?>';

        $('#cityForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                city = button.attr('data-city-id'),
                remoteUrl = Yarsha.config.base_url+'location/ajax/getCityForm';

            if( city !== "0" && city && "undefined" && city !== null ){
                remoteUrl = remoteUrl + '/' + city;
                $('#formCity').attr('data-city', city);
            }

            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function(res){
                    modal.find('.modal-body').html(res);
                }
            });

        });

        $('#formCity').submit(function(e){
            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }

            var postData = _form.serialize(),
                city = _form.attr('data-city'),
                baseURL = Yarsha.config.base_url + 'location/ajax/saveCity/'+state,
                isEditing = ( city !== undefined && city !== '0' && city !== "" )? true : false,
                remoteURL = isEditing ? baseURL + '/' + city : baseURL;

            $.ajax({
                type: 'POST',
                url: remoteURL,
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status == 'success' ){
                        window.location = '<?php echo base_url().'location/city/'.$state->id() ?>';
                    }else{
                        console.log(data.message);
                    }
                    $('#cityForm').modal('hide');
                }
            });
            return false;
        });

    });
</script>