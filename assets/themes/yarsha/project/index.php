
<div class="row">

    <?php
    $buttons[] = [ 'type' => 'add', 'link' => site_url('project/add'), 'others' => 'id="add-user-btn"','permissions' => ['add project'] ];
    echo actionWrapper($buttons);
    ?>

    <div class="col-md-12">
        <div class="panel panel-default">
            <?php if(isset($projects) && count($projects)>0){ ?>
                <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th class="serial" width="3%">#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th width="12%" class="actions">Actions</th>
                    </tr>
                    <?php
                    $count = isset($offset) ? $offset+1 :1;
                    foreach($projects as $p):
                        echo "<tr>";
                        echo "<td>{$count}</td>";
                        echo "<td>{$p->getName()}</td>";
                        echo "<td>{$p->getDescription()}</td>";
                        echo "<td>".\project\models\Project::getStatusString($p->getStatus())."</td>";
                        echo "<td>";
                        if( user_access('view project') )
                            echo action_button('view', 'project/detail/'.$p->id(), '');
                        if( user_access('edit project') )
                            echo action_button('edit', 'project/add/'.$p->id(), '');
                        if( user_access('delete project') )
                            echo action_button('delete', 'project/delete/'.$p->id(), '');
                        echo "</td>";
                        echo "</tr>";
                        $count++;
                    endforeach;?>
                    </tbody>
                </table>
                </div>
            <?php }else{ echo alertBox('No Projects Found.','warning'); } ?>

            <?php echo isset($pagination) ? '<div class="panel-footer">'.$pagination.'</div>' : '' ?>
        </div>
    </div>
</div>

