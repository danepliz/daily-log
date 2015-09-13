<?php

$fieldsArray = array('agent', 'market', 'nationality', 'file', 'client', 'created');

foreach($fieldsArray as $fa){
    $$fa = ( isset( $filters[$fa] ) )? $filters[$fa] : NULL;
}
?>

<div class="row">
<!--    --><?php
//    if(user_access('add activity')) {?>
<!--    <div class="col-md-12 margin">-->
<!--        <a href="--><?php //echo site_url('file/register') ?><!--" class="btn btn-primary btn-margin">Register Tour File</a>-->
<!--    </div>-->
<!--    --><?php //} ?>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Search Tour File</h3> </div>
            <div class="panel-body">
                <form action="" method="get" role="form" name="a_filter">
                    <div class="form-group-sm col-md-3 smalldevice">
                        <label>Agent</label>
                        <?php getAgentDropDown('agent', $agent, 'class="form-control" id ="agent"') ?>
                    </div>

                    <div class="form-group-sm col-md-3 smalldevice">
                        <label>Client Name</label>
                        <input type="text" name="client" value="<?php echo $client ?>" class="form-control" />
                    </div>

                    <div class="form-group-sm col-md-3 smalldevice">
                        <label>Market</label>
                        <?php getMarketSelectionElementForXo('market', $market, 'class="form-control" id ="market"') ?>
                    </div>

                    <div class="form-group-sm col-md-3 smalldevice">
                        <label>Nationality</label>
                        <?php getCountrySelectionElementForXo('nationality', $nationality, 'class="form-control" id="country"') ?>
                    </div>

                    <div class="form-group-sm col-md-3 smalldevice">
                        <label>File#</label>
                        <input type="text" name="file" class="form-control" value="<?php echo $file ?>" />
                    </div>

                    <div class="form-group-sm col-md-3 smalldevice">
                        <label>Created Date</label>
                        <input type="text" name="created" class="form-control datepicker" value="<?php echo $created ?>" />
                    </div>

                    <div class="form-group-sm inline btn-tour col-md-3">
                        <label>&nbsp;</label>
                        <input type="submit" name="submit" value="SEARCH" class="btn btn-primary ">
                    </div>
                    <?php
                    if(user_access('add activity')) {?>
                    <div class="form-group-sm col-md-3 inline btn-tour">
                        <label>&nbsp;</label>
                        <a href="<?php echo site_url('file/register') ?>" class="btn btn-primary ">Register Tour File</a>
                    </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php if( count($tourFiles) > 0  ){  ?>
<div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th>File#</th>
                            <th>Agent</th>
                            <th>Client</th>
                            <th>Market</th>
                            <th>Nationality</th>
                            <th>Pax</th>
                            <th>Child</th>
                            <th>Infants</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>

                        <?php $count=$offset ? $offset+1 : 1; foreach($tourFiles as $f){ ?>
                            <tr>
                                <td><?php echo $count ?></td>
                                <td><?php echo $f->getFileNumber() ?></td>
                                <td><?php echo ( $f->getAgent() )? $f->getAgent()->getName() : 'DIRECT CLIENT' ?></td>
                                <td><?php echo $f->getClient() ?></td>
                                <td><?php echo $f->getMarket()->getName() ?></td>
                                <td><?php echo $f->getNationality()->getNationality() ?></td>
                                <td><?php echo $f->getNumberOfPax() ?></td>
                                <td><?php echo $f->getNumberOfChildren() ?></td>
                                <td><?php echo $f->getNumberOfInfants() ?></td>
                                <td><?php echo $f->created() ? $f->created()->format('Y-m-d') : ''; ?></td>
                                <?php if(user_access('view tour file')){?>
                                <td><?php echo action_button('view', 'file/detail/'.$f->id(), array('title' => 'View Detail')) ?>
<!--                                  --><?php
//                                    echo '<button class="btn btn-danger margin" onclick="return voidFile('.$f->id().')">Void</a>';?>


                                </td>
                                <?php } ?>

                            </tr>
                            <?php $count++; }  ?>
                        </tbody>
                    </table>
                <?php }else{
                    no_results_found('No results found');
                } ?>
            </div>
            <div class="panel-footer">
                <?php echo (isset($pagination))? $pagination : ''; ?>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function(){
        $('#agent, #market, #country').select2();
    });

//    function voidFile(id) {
//
//        if (confirm('Are you sure to void file?')) {
//            $('body').mask('Processing ...');
//            $.ajax({
//                type: 'get',
//                url: Yarsha.config.base_url + 'file/ajax/voidFile/' + id,
//                success: function (res) {
//                    var data = $.parseJSON(res);
//                    if (data.status && data.status == 'success') {
//                        window.location = Yarsha.config.base_url + 'file/detail/<?php //echo $data['tourFileID'] ?>//';
//                        return true;
//                    } else {
//                        $('body').unmask();
//                        Yarsha.notify('warn', data.message);
//                        return false;
//                    }
//                }
//            });
//        } else {
//            return false;
//        }
//    }
</script>
