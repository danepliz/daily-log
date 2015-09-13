<div class="row" xmlns="http://www.w3.org/1999/html">
    <div class="col-md-12">
        <?php
        if(user_access('administer location')) {?>
            <a href="#" class="btn btn-primary btn-margin" data-toggle="modal" data-target="#countryForm" data-state-id="0">ADD NEW COUNTRY</a>
        <?php } ?>
    </div>

    <div class="col-md-12" style="display: none">
        <?php
        if(user_access('administer location')) {?>
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#countryForm" data-country-id="0">ADD NEW COUNTRY</a>
        <?php } ?>
    </div>


    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Search Country</h3> </div>
            <div class="panel-body">
                <form action="" method="get" role="form" name="c_filter" id="countryFilter">
                    <div class="form-group-sm col-md-3">
                        <input type="text" placeholder="Country Name" name="name" class="form-control" value="<?php echo (isset($post['name']))? $post['name'] : ''; ?>" id="name">
                    </div>

                    <div class="form-group-sm col-md-3">
                        <input type="submit" name="submit" value="SEARCH" class="btn btn-primary">
                        <input type="reset" name="reset" value="RESET" class="btn btn-danger">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading bg-gray"> <h3 class="panel-title">Country List</h3> </div>

            <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Nationality</th>
                        <th>ISO2</th>
                        <th>ISO3</th>
                        <th>Dialing Code</th>
                        <th>Actions</th>
                    </tr>

                    <?php
                    if(count($countries) > 0 ){
                        $count = $offset +1 ;
                        foreach($countries as $country){
                            $cid = $country['country_id'];
                    ?>
                        <tr>
                            <td><?php echo $count ?></td>
                            <td><?php echo $country['name'] ?></td>
                            <td><?php echo $country['nationality'] ?></td>
                            <td><?php echo $country['code2'] ?></td>
                            <td><?php echo $country['code3'] ?></td>
                            <td><?php echo $country['dialing_code'] ?></td>
                            <td>
                                <?php  if(user_access('administer location')) {
//                                    echo action_button('list', 'location/state/' . $cid, array('title' => 'List States of ' . $country['name']));
                                    echo action_button('edit', '#', array('title' => 'Edit Country', 'data-toggle' => 'modal', 'data-target' => '#countryForm', 'data-country-id' => $cid));
                                }
                                ?>
                            </td>
                        </tr>
                    <?php $count++; }} ?>
                </tbody>
            </table>
            </div>
</div>
            <div class="panel-footer">
                <?php if( isset($pagination) ){  echo $pagination; }?>
            </div>

        </div>

    </div>

</div>

<!-- Country form -->
<div class="modal fade" id="countryForm" tabindex="-1" role="dialog" aria-labelledby="countryFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="countryFormLabel">Country</h4>
            </div>

            <form role="form" class="validate" id="formCountry">
                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE COUNTRY" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end country form -->


<script type="text/javascript">
    $(document).ready(function(){

        $('#countryForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                country = button.attr('data-country-id'),
                remoteUrl = Yarsha.config.base_url+'location/ajax/getCountryForm';

            if( country !== "0" && country && "undefined" && country !== null ){
                remoteUrl = remoteUrl + '/' + country;
                $('#formCountry').attr('data-country', country);
            }

            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function(res){
                    modal.find('.modal-body').html(res);
                }
            });

        });

        $('#formCountry').submit(function(e){
            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }

            var postData = _form.serialize(),
                _country = _form.attr('data-country'),
                baseURL = Yarsha.config.base_url + 'location/ajax/saveCountry',
                isEditing = ( _country !== undefined && _country !== '0' && _country !== "" )? true : false,
                remoteURL = isEditing ? baseURL + '/' + _country : baseURL;

            $.ajax({
                type: 'POST',
                url: remoteURL,
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    console.log(data);
                    if( data.status == 'success' ){
                        window.location = '<?php echo base_url().'location' ?>';
                    }else{
                        console.log(data.message);
                    }

                    $('#contactForm').modal('hide');
                }
            });

            return false;

        });

    });
</script>