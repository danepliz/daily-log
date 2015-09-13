<div class="row">
    <?php
    if(user_access('administer branch')) {?>
    <div class="col-xs-12 btn-margin">
        <a href="javascript:void(0)" class="btn btn-primary" id="add-branch-btn" >Add New Branch</a>
    </div>
    <?php } ?>

    <div class="col-xs-12" id="add-branch-form-wrapper" style="display: none">
        <div class="panel panel-default">
            <div class="panel-body">
                <form role="form" method="post" action="<?php echo site_url('branch/add') ?>" class="validate" >

                    <div class="form-group-sm">
                        <label for="name">Branch</label>
                        <input type="text" name="name" class="form-control required" placeholder="group name" />
                    </div>

                    <div class="form-group-sm">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control" placeholder="add description"></textarea>
                    </div>

                    <div class="form-group-sm">
                        <input type="submit" value="SAVE" class="btn btn-primary" />
                        <input type="reset" value="CLEAR" class="btn btn-primary" />
                        <input type="button" value="CANCEL" class="btn btn-danger" id="cancel-add-branch"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xs-12" id="branch-list">
        <div class="panel panel-default">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Branch List</h3>
            </div>

            <?php if( count($branches) > 0 ){ ?>
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>

                    <?php
                    $count = 1;
                    foreach($branches as $b){

                        $editAction = (  ! user_access('administer branch') )
                                        ? ''
                                        : action_button('edit', '#', array('data-toggle' => 'modal', 'title' =>'Edit' .$b->getName(), 'data-target' =>'#branchForm', 'data-branch-id' => $b->id())) ;

                        $out = '<tr>';
                        $out .= '<td>'.$count.'</td>';
                        $out .= '<td>'.$b->getName().'</td>';
                        $out .= '<td>'.getStatusActionWrapper($b->id(), $b->isActive(), 'branch/ajax/toggleBranchStatus').'</td>';
                        $out .= '<td>'.$b->getDescription().'</td>';
                        $out .= '<td>'.$editAction.'</td>';
                        $out .= '</tr>';

                        echo $out;
                        $count++;
                    }
                    ?>
                    </tbody>
                </table>
            <?php }else{ no_results_found('No Branches Found.'); } ?>
        </div>
    </div>


</div>

<!-- branch edit form -->
<div class="modal fade" id="branchForm" tabindex="-1" role="dialog" aria-labelledby="branchFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="branchFormLabel">Branch | Edit</h4>
            </div>

            <form role="form" class="validate" id="formBranch">

                <div class="col-md-12 alert alert-danger" id="branch-alert" style="display:none"></div>

                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE BRANCH" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end branch edit form -->

<script type="text/javascript">

    $(document).ready(function(){
        $('#add-branch-btn').click(function(){
            $('#add-branch-btn, #branch-list').hide();
            $('#add-branch-form-wrapper').show();
        });

        $('#cancel-add-branch').click(function(){
            $('#add-branch-btn, #branch-list').show();
            $('#add-branch-form-wrapper').hide();
        });

        $('#branchForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                branchID = button.data('branch-id');
                remoteUrl = Yarsha.config.base_url+'branch/ajax/getBranchForm/'+branchID;

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

        $('#formBranch').submit(function(e){

            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }

            var postData = _form.serialize();

            $.ajax({
                type: 'POST',
                url: Yarsha.config.base_url + 'branch/ajax/saveBranch',
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    console.log(data);
                    if( data.status == 'success' ){
                        window.location = '<?php echo site_url('branch') ?>';
                    }else{
                        $('#branch-alert').html(data.message).show();
                    }
                }
            });

            return false;

        });

        <?php if( isset($has_error) and $has_error == TRUE ){ ?> $('#add-branch-btn').trigger('click') <?php } ?>

    });
</script>