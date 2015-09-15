<?php

$name = $description = NULL;
$prefilledForms = [];
$meta = [];
if( $project ){
    $name = $project->getName();
    $description = $project->getDescription();
    $meta = $project->getMeta();
}

echo loadJS(['jquery.sheepit.min']);

echo form_open('', 'method="post" class="form-horizontal form-label-left validate" role="form"');
echo panelWrapperOpen('col-md-12','Information', TRUE);
echo inputWrapper('name', 'Project Name', $name, 'class="form-control required"');
echo textAreaWrapper('description', 'Description', $description, 'class="form-control"');
echo panelWrapperClose();


echo panelWrapperOpen('col-md-12','Project Meta', TRUE);

?>






<div classs="col-md-12 form-group-sm">

    <table class="table sheepit-table">
        <tbody id="meta">
            <tr>
                <th>Key</th>
                <th>Value</th>
                <th>Access To All</th>
                <th>&nbsp;</th>
            </tr>

            <tr id="meta_template">
                <td><input type="text" name="meta[#index#][key]" class="form-control" /></td>
                <td><input type="text" name="meta[#index#][value]" class="form-control" /></td>
                <td><input type="checkbox" name="meta[#index#][allow]" class="form-control simple"/></td>
                <td><a id="meta_remove_current"><i class="fa fa-trash"></i></a></td>
            </tr>

            <?php
                if( count($meta) ){
                    foreach($meta as $m){
                        $tempId = 'old_meta_'.$m->id();
                        $prefilledForms[] = $tempId;
                        $checked = ($m->showToAll())? 'checked="checked"' : '';
            ?>
                <tr id="<?php echo $tempId ?>">
                    <td><input type="text" name="meta[#index#][key]" class="form-control" value="<?php echo $m->getMetaKey() ?>" /></td>
                    <td><input type="text" name="meta[#index#][value]" class="form-control" value="<?php echo $m->getMetaValue() ?>"  /></td>
                    <td><input type="checkbox" name="meta[#index#][allow]" class="form-control simple" <?php echo $checked ?> /></td>
                    <td><a id="meta_remove_current"><i class="fa fa-trash"></i></a></td>
                </tr>

            <?php
                    }
                }
            ?>

            <tr id="meta_noforms_template">
                <td colspan="4">No forms</td>
            </tr>

            <tr id="meta_controls">
                <td colspan="4" ><a class="btn btn-default" id="meta_add"><i class="fa fa-plus-square"></i> add new meta</a></td>
            </tr>

        </tbody>
    </table>

</div>






<?php

echo panelWrapperClose();
echo panelWrapperOpen(); ?>
<div class="col-md-12">
    <input type="submit" value="SUBMIT"  class="btn btn-default" />
</div>

<?
echo panelWrapperClose();

echo form_close();


?>



<script type="text/javascript">
    $(document).ready(function(){
        var meta = $("#meta").sheepIt({
            separator: "",
            allowRemoveLast: false,
            allowRemoveCurrent: true,
            allowRemoveAll: false,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 20,
            minFormsCount: 1,
            iniFormsCount: 1,
            pregeneratedForms: <?php echo json_encode($prefilledForms) ?>
        });
    });
</script>