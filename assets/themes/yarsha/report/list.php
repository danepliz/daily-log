<style>
.toggle{cursor: pointer}
</style>

<div class="row">

    <?php if( user_access('add report') ){ ?>
    <div class="col-md-12">
        <a href="<?php echo site_url('report/editor')?>" class="btn btn-primary btn-margin" >Add Report</a>
    </div>
    <?php } ?>

    <div class="col-md-12">

        <?php if( count($reports) > 0 ){ ?>

        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Report List</h3></div>
           <div class="table-responsive">
            <table class="table ">
                <tbody>
                <tr>
                    <th class="serial" width="3%">#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th width="10%">Actions</th>
                </tr>
                <?php
                $count = isset($offset)?$offset+1:1;

                foreach($reports as $c):
                    if(count($subreports[$c['id']]) > 0):
                        ?>
                        <tr class="toggle">
                            <td><?php echo $count++; ;?></td>
                            <td><?php echo $c['name'];?></td>
                            <td colspan="2"></td>
                        </tr>
                        <?php
                        foreach($subreports[$c['id']] as $subrep):?>
                            <tr class="hidethis" style="display:none;">
                                <td></td>
                                <td><?php echo $subrep->getName()?></td>
                                <td> <?php echo $subrep->getDescr()?></td>
                                <td>
                                    <?php
                                    if (report_access($subrep->id())):
                                        echo action_button('view', 'report/result/'.$subrep->getSlug(),array('title'	=>	'Execute Report Query'));
                                        echo action_button('edit', 'report/change/'.$subrep->getSlug(),array('title'	=>	'Edit Report Query'));
                                        echo action_button('delete', 'report/delete/'.$subrep->getSlug(),array('title'	=>	'Delete Report'));
                                    endif;
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; endforeach;?>

                <?php if(count($otherreports) > 0): ?>
                    <tr class="toggle">
                        <td><?php echo $count;?></td>
                        <td>Other Reports</td>
                        <td colspan="2"></td>
                    </tr>
                    <?php 	foreach($otherreports as $other ):?>
                        <tr  class="hidethis" style="display:none;">
                            <td></td>
                            <td><?php echo $other->getName()?></td>
                            <td> <?php echo $other->getDescr()?></td>
                            <td>
                                <?php
                                if (report_access($other->id())):
                                    echo action_button('view', 'report/result/'.$other->getSlug(),array('title'	=>	'Execute Report Query'));
                                    echo action_button('edit', 'report/change/'.$other->getSlug(),array('title'	=>	'Edit Report Query'));
                                    echo action_button('delete', 'report/delete/'.$other->getSlug(),array('title'	=>	'Delete Report'));
                                endif;	?>
                            </td>
                        </tr>
                    <?php endforeach; endif;?>
                </tbody>

            </table>
        </div>
</div>

        <?php }else{ echo no_results_found('No Reports Added.'); } ?>

    </div>

</div>


<script>
$(document).ready(function(){
	$('.fa-minus-square').bind('click',function(){
		return confirm('Are you sure to delete this report?');
	});

$('tr.toggle ').bind('click',function(ev){
var that = $(this);
$(that).nextAll('tr.hidethis').each(function(){
$(this).slideToggle();
if($(this).next().hasClass('toggle'))
return false;
});
});
});

</script>