<?php
use user\models\TourFileActivity;

$exchange_order = isset($post['exchange_order']) ? $post['exchange_order'] : '';
$file_number = isset($post['file_number'])? $post['file_number'] : '';
$agent_name = isset($post['agent_name'])? $post['agent_name'] : NULL;
$created_from = isset($post['created_from'])? $post['created_from'] : NULL;
$created_to = isset($post['created_to'])? $post['created_to'] : NULL;
$created_by = isset($post['created_by'])? $post['created_by'] : NULL;
$client_name = isset($post['client_name'])? $post['client_name'] : NULL;
$xoType = isset($post['xo_type'])? $post['xo_type'] : NULL;
$issuedTo = isset($post['issued_to'])? $post['issued_to'] : '';
$allow_merge_preview = ( $file_number != '' and $issuedTo != '' );
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Search Exchange Order <span style="font-size: 12px; color: #f4543c;">(Note: To merge Voucher use Issued to and File Number search )</span></h3>  </div>
            <div class="panel-body">
                <form action="" method="get" role="form" name="a_filter">
                    <div class="form-group-sm col-md-2">
                        <label>X/O Type</label>
                        <?php getTourActivitySelectElement('xo_type', $xoType, 'class="form-control" id="xoType"'); ?>
                    </div>

                    <div class="form-group-sm col-md-3">
                        <label for="issued_to" >Issued To</label>
                        <select name="issued_to" class="form-control" id="issued_to">
                            <option value=""> -- Issued To --</option>
                        </select>
                    </div>

                    <div class="form-group-sm col-md-2">
                        <label>Exchange Order</label>
                        <input type="text" name="exchange_order" class="form-control" value="<?php echo $exchange_order ?>">
                    </div>

                    <div class="form-group-sm col-md-2">
                        <label>File Number</label>
                        <input type="text" name="file_number" class="form-control" value="<?php echo $file_number ?>">
                    </div>

                    <div class="form-group-sm col-md-3">
                        <label>Agent</label>
                        <?php getAgentSelectionElementForXO('agent_name', $agent_name, 'class="form-control" id="agent"') ?>
                    </div>

                    <div class="form-group-sm col-md-3">
                        <label>Client Name</label>
                        <input type="text" name="client_name" class="form-control" value="<?php echo $client_name ?>" placeholder="Client Name">
                    </div>

                    <div class="form-group-sm col-md-3">
                        <label>Created By</label>
                        <?php getUserSelectElement('created_by', $created_by, 'class="form-control" id="created_by"') ?>
                    </div>

                    <div class="form-group-sm col-md-2">
                        <label>Created From</label>
                        <input type="text" name="created_from" class="form-control datepicker" value="<?php echo $created_from ?>">
                    </div>

                    <div class="form-group-sm col-md-2">
                        <label>Created To</label>
                        <input type="text" name="created_to" class="form-control datepicker" value="<?php echo $created_to ?>">
                    </div>

                    <div class="form-group-sm col-md-2">
                        <label>&nbsp;</label>
                        <input type="submit" name="submit" value="SEARCH" class="btn btn-primary form-control">
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-md-12">
    <?php
    if ($allow_merge_preview) {
        echo '<form name="merged_preview_form" method="post" action="'.site_url('file/xo/mergedPreview').'" id="merged_preview_form">';
        echo '<input type="hidden" name="merged_file_number" value="' . $file_number . '" />';
        echo '<input type="hidden" name="merged_xo_type" value="' . $xoType . '" />';
    }
    ?>
    <div class="panel panel-default">
        <div class="panel-heading bg-gray">
            <h3 class="panel-title">Exchange Order List</h3>
        </div>
            <?php if(isset($xo) && count($xo)>0){ ?>
              <div class ="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th class="serial" width="3%">#</th>
                        <th>Exchange Order Number</th>
                        <th>File No</th>
                        <th>Agent Name</th>
                        <th>Client Name</th>
                        <th>Issued to</th>
                        <th>Created Date </th>
                        <th>Created By</th>
                        <th width="12%" class="actions">Actions</th>
                    </tr>
                    <?php
                    $count = 1;
                    foreach($xo as $x):

                        $issuedToName = '';

                        if( $x instanceof \file\models\TourFileActivityHotel ){
                            $issuedToName = ( $hotel = $x->getHotel() )? $hotel->getName() : '';
                        }

                        ?>
                        <tr <?php  ?>>
                            <td><?php echo ++$counter ?></td>
                            <td><?php echo $x->getXoNumber() ?></td>
                            <td><?php echo $x->getTourFile()->getFileNumber() ?></td>
                            <td><?php echo ( $x->getTourFile()->getAgent() )? $x->getTourFile()->getAgent()->getName() : 'DIRECT CLIENT' ?></td>
                            <td><?php echo $x->getTourFile()->getClient() ?></td>
                            <td><?php echo $issuedToName ?></td>
                            <td><?php echo $x->getXoCreatedDate()->format('Y-m-d');?></td>
                            <td><?php echo $x->getCreatedBy()->getFullname() ?></td>
                            <td>
                                <?php if( user_access('view tour file')) {
                                    echo action_button('view', 'file/activity/detail/' . $x->id(), array('title' => 'View Detail'));

                                    if( $allow_merge_preview ){
                                        $data = [
                                            'name' => 'merged_xo[]',
                                            'value' => $x->getXoNumber(),
                                            'class' => 'simple mergeCheck'
                                        ];
                                        echo form_checkbox($data);
                                    }
                                } ?>

                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            <?php }else{ no_results_found('No Xo to list.'); } ?>

        </div></div>
<!---->
<!--        <div class="panel-footer">-->
<!--            --><?php
//
//            echo (isset($pagination))? $pagination : ''; ?>
<!--        </div>-->


        </div>
        <?php

        if(isset($pagination)){ echo '<div class="panel-footer">'.$pagination.'</div>'; }

        if ($allow_merge_preview){
            echo '<input type="submit" value="Preview Merged X/O" class="btn btn-primary" />';
            echo '</form>';
        }
        ?>

    </div>



</div>
</div>


<script>
    $(document).ready(function(){
        $('#agent, #created_by').select2();
            $('form#merged_preview_form').submit(function(e){

                var totalMergeChecked = $(this).find('.mergeCheck:checked').length;

                if(totalMergeChecked > 1){
                    return true;
                }else{
                    alert('Please select atleast 2 xo.');
                    return false;
                }

            });

        $('#xoType').change(function(){
            var self = $(this),
                activityType = self.val(),
                issuedTo = $('#issued_to'),
                options = '<option value=""> -- Issued To --</option>';

            if( activityType == '' ){
                issuedTo.html(options);
            }else{
                $.ajax({
                    type: 'GET',
                    url: Yarsha.config.base_url + 'file/ajax/getIssuedToList/' + activityType,
                    success: function(res){
                        var data = $.parseJSON(res);
                        if( data.status && data.status == 'success' ){
                            options = data.options;
                        }
                        issuedTo.html(options).val("<?php echo $issuedTo ?>");
                    }
                });
            }
        });

        $('#xoType').trigger('change');

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
                url:  Yarsha.config.base_url + 'file/xo/deleteXo',
                data: {id: id},
                success: function(res){
                    var data = $.parseJSON(res);
                    if(data.status && data.status == 'success'){
                        window.location = '<?php echo site_url('file/xo') ?>'
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