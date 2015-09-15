<?php
    use user\models\Group;
?>
<div class="row">

    <div class="col-xs-12 btn-margin">
        <a href="javascript:void(0)" class="btn btn-primary" id="add-user-group-btn" >Add New User Group</a>
    </div>

    <div class="col-xs-12" id="add-user-group-form-wrapper" style="display: none">
        <div class="panel panel-default">
            <div class="panel-body">
                <form role="form" method="post" action="<?php echo site_url('user/group/add') ?>" class="validate" >

                    <div class="form-group-sm">
                        <label for="name">Group</label>
                        <input type="text" name="name" class="form-control required" placeholder="group name" />
                    </div>

                    <div class="form-group-sm">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control" placeholder="add description"></textarea>
                    </div>

                    <div class="form-group-sm">
                        <input type="submit" value="SAVE" class="btn btn-primary" />
                        <input type="reset" value="CLEAR" class="btn btn-primary" />
                        <input type="button" value="CANCEL" class="btn btn-danger" id="cancel-add-user-group"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xs-12" id="user-group-list">
        <div class="panel panel-default">

            <?php if( count($groups) > 0 ){ ?>
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>User Counts</th>
                        <th>Actions</th>
                    </tr>

                    <?php
                    $count = 1;
                    $fixedGroups = array(Group::SUPER_ADMIN);
                    foreach($groups as $g){
                        $actions = '';

                        $actions .= action_button('permissions', 'user/group/permissions/' . $g['group_id'], array('title' => 'Edit Permissions'));
                        $actions .= action_button('copy','#',array('title'	=>	'Copy to create a new Group','class'	=>	'clone-group', 'id'	=>	'gid-'.$g['group_id']));

                        if( !in_array($g['group_id'], $fixedGroups) ) {
                            $actions .= action_button('edit','user/group/edit/'.$g['group_id'],array('title'	=>	'Edit Group'));
                        }

                        $userCount = isset($numusers[$g['group_id']]) ? $numusers[$g['group_id']] : 0;
                        $out = '<tr>';
                        $out .= '<td>'.$count.'</td>';
                        $out .= '<td>'.$g['name'].'</td>';
                        $out .= '<td>'.$g['description'].'</td>';
                        $out .= '<td>'. $userCount . '</td>';
                        $out .= '<td>'. $actions . '</td>';
                        $out .= '</tr>';

                        echo $out;
                        $count++;
                    }
                    ?>
                    </tbody>
                </table>
            <?php }else{ echo alertBox('No User Groups Found.','warning'); } ?>
        </div>
    </div>


</div>


<script type="text/javascript">

    $(document).ready(function(){
        $('#add-user-group-btn').click(function(){
            $('#add-user-group-btn, #user-group-list').hide();
            $('#add-user-group-form-wrapper').show();
        });

        $('#cancel-add-user-group').click(function(){
            $('#add-user-group-btn, #user-group-list').show();
            $('#add-user-group-form-wrapper').hide();
        });

        $('.clone-group').click(function(e) {
            e.preventDefault();
            var _id = $(this).attr('id').split('-'),
                gid = _id[1];

            $('body').mask("Please wait while we copy the group.");

            $.ajax({
                type: 'GET',
                url: '<?php echo base_url().'user/group/copygroup/'?>' + gid,
                data: null,
                success: function (res) {
                    res = $.parseJSON(res);
                    $('body').unmask();
                    if (res.response == 'success') {
                        window.location = '<?php echo base_url().'user/group/edit/'?>' + res.group_id;
                    } else {
                        alert('An error occurred while copying the group. Please try again.');
                    }
                },
                failure: function () {

                }

            });

        });

        <?php if( isset($has_error) and $has_error == TRUE ){ ?> $('#add-user-group-btn').trigger('click') <?php } ?>
    });
</script>