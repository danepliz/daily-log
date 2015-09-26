<div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">

        <?php
        $buttons[] = [ 'type' => 'add', 'link' => site_url('project/add'), 'others' => 'id="add-user-btn"','permissions' => ['add project'] ];
//        echo actionWrapper($buttons);
        ?>

        <div class="page-header-wrap">
            <h2>Project List</h2>
            <?php if(user_access('add project')) {  ?>
            <a href="<?php echo site_url('project/add') ?>"><i class="fa fa-plus"></i> Add New</a>
            <?php } ?>
        </div>

        <div class="col-md-12">

<!--                <div class="project-brief-box">-->
<!--                    <a href="#" class="project-brief-title">Project Name</a><span class="label label-success">active</span>-->
<!--                    <p class="project-brief-description">This project is based on web based application</p>-->
<!--                </div>-->

                <?php if(isset($projects) && count($projects)>0){
                            $count = isset($offset) ? $offset+1 :1;
                            foreach($projects as $p):

                                $viewLink = ( user_access_or(['view project', 'edit project', 'delete project']) )? site_url('project/detail/'.$p->slug()) : '#';

                                echo '<div class="project-brief-box">';
                                echo '<a href="'.$viewLink.'" class="project-brief-title">'.$p->getName().'</a>';
                                echo \project\models\Project::getStatusString($p->getStatus());
                                echo '<p class="project-brief-description">'.$p->getDescription().'</p>';
                                echo '</div>';
                                $count++;
                            endforeach;
                }else{ echo alertBox('No Projects Found.','warning'); } ?>

                <?php echo isset($pagination) ? '<div class="panel-footer">'.$pagination.'</div>' : '' ?>
        </div>
</div>


<div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
    <div class="row">
        <h2 class="blog-title">Recent Activities</h2>
        <div class="blog-post">
            <p class="blog-post-meta">December 14, 2013 by <a href="#">Chris</a></p>
            <p class="blog-post-detail">New feature on MIS</p>

        </div>
    </div>
</div>





