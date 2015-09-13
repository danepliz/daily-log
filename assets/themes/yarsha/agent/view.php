<?php

use agent\models\Agent;

$fName = isset($post['name']) ? $post['name'] : '';
$fEmail = isset($post['email'])? $post['email'] : '';
$fCountry = isset($post['country'])? $post['country'] : NULL;
$fStatus = isset($post['status'])? $post['status'] : NULL;
?>

<div class="row">
    <?php
    if(user_access('add agent')) {?>
        <div class="col-md-12 margin"><a href="<?php echo site_url('agent/add') ?>" class="btn btn-primary btn-margin">ADD NEW AGENT</a></div>
    <?php } ?>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Search Agent</h3> </div>
            <div class="panel-body">
                <form action="" method="get" role="form" name="a_filter">
                    <div class="form-group-sm col-md-3">
                        <input type="text" placeholder="Agent Name" name="name" class="form-control" value="<?php echo $fName ?>">
                    </div>

                    <div class="form-group-sm col-md-3">
                        <input type="text" placeholder="Agent Email" name="email" class="form-control" value="<?php echo $fEmail ?>">
                    </div>

                    <div class="form-group-sm col-md-3">
                        <?php getCountrySelectElement('country', $fCountry, 'class="form-control" id="country"') ?>
                    </div>

                    <div class="form-group-sm col-md-3">
                        <?php getAgentStatusOptions('status', $fStatus, 'class="form-control" id="status"') ?>
                    </div>

                    <div class="form-group-sm col-md-2">
                        <input type="submit" name="submit" value="SEARCH" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12">

        <div class="panel panel-default">

            <div class="panel-body">
                <?php if(isset($agents) && count($agents)>0){ ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th class="serial" width="3%">#</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Website</th>
                            <th>Status</th>
                            <th>Email</th>
                            <th width="12%" class="actions">Actions</th>
                        </tr>
                        <?php
                        $count = 1;
                        if($agents){
                        foreach($agents as $p):
                            $class = ($p['status'])? '' : 'class="red"';
                            $contacts = array($p['phone1'],$p['phone2']);
                            $emails = array($p['email1'],$p['email2']);
                            $address = [];
                            if( $p['city'] != '' ){ $address[] = $p['city']; }
                            if( $p['address'] != '' ){ $address[] = $p['address']; }
                            ?>
                            <tr <?php echo $class ?>>
                                <td  ><?php echo ++$counter;?></td>
                                <td><?php echo $p['name'];?></td>
                                <td><?php echo $p['country'];?></td>
                                <td><?php echo implode(',', $address); ?></td>
                                <td><?php echo  implode('<br />',$contacts) ?></td>
                                <td><a target="_blank" href="<?php echo $p['website1'];?>"  ><?php echo $p['website1'];?></a></td>
                                <td><?php echo Agent::$status_desc[$p['status']]?></td>
                                <td>
                                    <?php
                                    foreach($emails as $email){
                                        echo '<a href="mailto:'.$email.'">'.$email.'</a><br />';
                                    }
                                    ?>

                                </td>
                                <td class="actions"><?php
                                    if(user_access('view agent') && $p['status']==1) {
                                        echo action_button('view', 'agent/detail/' . $p['slug'], array('title' => 'view detail'));
                                    }
                                    if(user_access('edit agent') && $p['status']==1){
                                        echo action_button('edit', 'agent/edit/'.$p['slug'],array('title'	=>	'Edit '.$p['name']));
                                    }
                                    if(user_access('View all agent') && $p['status']==1){
                                        echo action_button('permittedUser', 'agent/edit/'.$p['slug'],array('title'	=>	'PermittedUser '.$p['name']));
                                    }
                                    if(user_access('delete agent') && $p['status']==1) {
                                        echo action_button('delete', '#', array('data-bb' => 'custom_delete', 'data-id' => $p['agent_id'], 'title' => 'Delete ' . $p['name']));
                                    }else{
                                        echo action_button('undelete', '#', array('data-bb' => 'custom_revert', 'data-id' => $p['agent_id'], 'title' => 'Undo Delete ' . $p['name']));
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php } }else{ no_results_found('No agents to list.'); } ?>
                </div>

                <div class="panel-footer">
                    <?php echo (isset($pagination))? $pagination : ''; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dialog-form" style="display: none" title="Reason">
    <form id="dialog-note-form">
        <textarea name="note" rows="8" id="dialog-note" style="width: 98%;"
                  class="required"></textarea>
    </form>
</div>

<script>
    $(function(){
        $('a#view_sa').bind('click',function(e){
            e.preventDefault();
            $(this).closest('form').submit();
        });

        $('#clear').bind('click', function(){
            $('form#filter_form').find('select').val('');
        });

        $('a#unblock_agent').bind('click',function(){
            return confirm('Are you sure to unblock this agent?');

        });

        $( "#dialog-form" ).dialog({
            autoOpen: false,
            width:500,
            resizable:false,
            modal: true,
            buttons: {
                "Confirm": function() {
                    var valid = $('#dialog-note-form').validate({errorElement:'span'}).form();
                    var act = $('#dialog-form').attr('action');
                    if(valid){
                        $('#dialog-form').mask('Deleting Payer ...');
                        var data = {note:$('#dialog-note').val()};
                        var agentId = $('#dialog-form').attr('agent');
                        $.post(Transborder.config.base_url+'agent/ajax/'+act+'/'+agentId,
                            data,function(res){
                                window.location = "<?php echo site_url('agent')?>";
                            });
                    }
                },
                Cancel: function() {
                    $('#dialog-form').unmask();
                    $('#dialog-note').html('');
                    $('#dialog-form').dialog( "close");
                }
            },
            close: function() {
                $('#dialog-form').unmask();
                $('#dialog-note').html('');
            }
        });

        $( ".block_agent" ).click(function(e) {
            e.preventDefault();
            $('#dialog-form').attr('agent',$(this).attr('data-agentid'));
            $('#dialog-form').attr('title','Add Blocking Reason');
            $('#dialog-form').attr('action','block');
            $('#dialog-form').dialog( "open" );
        });

        $( ".delete_agent" ).click(function(e) {
            e.preventDefault();
            $('#dialog-form').attr('agent',$(this).attr('data-agentid'));
            $('#dialog-form').attr('title','Add Deleting Reason');
            $('#dialog-form').attr('action','delete');
            $('#dialog-form').dialog( "open" );
        });


        $('#country').live('change', function(){
            var country = $(this).val();

            if(country == "")
            {
                $('#group').html('<option value=""> -- Select Group -- </option>');
                return false;
            }

            $.ajax({
                type	: 'GET',
                url		: Transborder.config.base_url+'agent/ajax/getGroups/'+country+'/<?php //echo AgentGroup::GROUP_TYPE_PA ?>',
                success	: function(data){
                    var res = $.parseJSON(data);
                    var htmlOpt = '<option value=""> -- Select Group -- </option>';
                    if(res)
                    {
                        $.each(res, function(i, v){
                            htmlOpt += '<option value="'+i+'"> '+v+' </option>';
                        });
                    }

                    $('#group').html(htmlOpt).val('<?php echo $selected ?>');
                }
            });

        });

        <?php if(is_null($group)){ ?>$('#country').trigger('change');<?php } ?>
    });
</script>
<script>
    $(document).ready(function(){
        $('#country, #status').select2();
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
                url:  Yarsha.config.base_url + 'agent/deleteAgent',
                data: {id: id},
                success: function(res){
                    var data = $.parseJSON(res);
                    if(data.status && data.status == 'success'){
                        window.location = '<?php echo site_url('agent') ?>'
                    }else{
                        Yarsha.notify('warn', data.message);
                    }
                    return true;
                }
            });
        }
        return false;
    });

    $("body").on('click', "a[data-bb='custom_revert']",function(e){
        var $me = $(this);
        bootbox.confirm('Are you sure you want to UnDelete ?',function(result){
            if(result==true){
                removeData($me.attr("data-id"));
            }
        });
        function removeData(id) {
            $.ajax({
                type: "POST",
                url:  Yarsha.config.base_url + 'agent/UnDeleteAgent',
                data: {id: id},
                success: function(res){
                    var data = $.parseJSON(res);
                    if(data.status && data.status == 'success'){
                        window.location = '<?php echo site_url('agent') ?>'
                    }else{
                        Yarsha.notify('warn', data.message);
                    }
                    return true;
                }
            });
        }
        return false;
    });
</script>