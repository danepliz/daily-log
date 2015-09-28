<div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
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
echo textAreaWrapper('description', 'Description', $description, 'class="form-control" rows=""');
echo panelWrapperClose();


echo panelWrapperOpen('col-md-12','Project Meta', TRUE);

?>


<div classs="col-md-12 col-sm-12 col-lg-8 col-xs-12 form-group-sm table-responsive">

<!--    <div class="col-md-12">-->
<!--        <div class="col-md-1">-->
<!--            <input type="checkbox" />-->
<!--        </div>-->
<!--        <div class="input-group">-->
<!--            <span class="input-group-addon"><input type="text" class="form-control" /></span>-->
<!--            <span class="input-group-addon"><input type="text" class="form-control" /></span>-->
<!--        </div>-->
<!--    </div>-->


    <table class="table sheepit-table project_meta_sheepit_table">
        <tbody id="meta">
            <tr id="meta_template">
                <td class="td-small">
                    <label class="chk-label">
                        <input type="checkbox" name="meta[#index#][allow]" class="sform-control simple" title="access to all"/>
                    </label>
                </td>
                <td><input type="text" name="meta[#index#][key]" class="form-control" placeholder="key" /></td>
                <td class="td-small">:</td>
                <td><input type="text" name="meta[#index#][value]" class="form-control" placeholder="value" /></td>
                <td><a id="meta_remove_current"><i class="fa fa-trash"></i></a></td>
            </tr>

            <?php
                if( count($meta) ){
                    foreach($meta as $m){
                        $metaId = $m->id();
                        $tempId = 'old_meta_'.$metaId;
                        $prefilledForms[] = $tempId;
                        $checked = ($m->showToAll())? 'checked="checked"' : '';
            ?>
            <tr id="<?php echo $tempId ?>">
                <td class="td-small">
                    <label class="chk-label">
                        <input type="checkbox" name="meta[#index#][allow]" class="sform-control simple" title="access to all" <?php echo $checked ?> />
                    </label>
                </td>
                <td><input type="text" name="meta[#index#][key]" class="form-control" placeholder="key" value="<?php echo $m->getMetaKey() ?>" /></td>
                <td class="td-small">:</td>
                <td><input type="text" name="meta[#index#][value]" class="form-control" placeholder="value" value="<?php echo $m->getMetaValue() ?>" /></td>
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
                <td colspan="4" ><a id="meta_add"><i class="fa fa-plus-square"></i> add another</a></td>
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

    <?php
        echo panelWrapperClose();
        echo form_close();
    ?>

</div><!-- left side -->


<div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
    <?php

        echo panelWrapperOpen('col-md-12', 'Attachments', TRUE);

        echo form_open('', 'class="dropzone dz-clickable dz-started form-horizontal form-label-left validate" role="form"');
        echo '<div class="dz-default dz-message"><span>Drop files here to upload</span></div>';
        echo form_close();

    ?>



    <ul class="list-unstyled project_files">
        <li><a href=""><i class="fa fa-file-word-o"></i> Functional-requirements.docx</a>
        </li>
        <li><a href=""><i class="fa fa-file-pdf-o"></i> UAT.pdf</a>
        </li>
        <li><a href=""><i class="fa fa-mail-forward"></i> Email-from-flatbal.mln</a>
        </li>
        <li><a href=""><i class="fa fa-picture-o"></i> Logo.png</a>
        </li>
        <li><a href=""><i class="fa fa-file-word-o"></i> Contract-10_12_2014.docx</a>
        </li>
    </ul>

    <?php
        echo panelWrapperClose();
    ?>
</div>



<?php echo loadJS(['dropzone/dropzone']); ?>


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