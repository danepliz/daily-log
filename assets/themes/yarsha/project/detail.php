<?php
    $projectMeta = $project->getMeta();
    $metaArray = [];
    foreach($projectMeta as $meta){
        if( $meta->showToAll() or user_access('list project meta') ){
            $metaArray[$meta->getMetaKey()] = $meta->getMetaValue();
        }
    }

    $hasMeta = count($metaArray);
?>

<div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">

    <div class="page-header-wrap">
        <h2><?php echo ucwords($project->getName()) ?></h2>
        <?php if(user_access('delete project')) {  ?>
        <a href="<?php echo site_url('project/delete/'.$project->slug()) ?>" title="Delete Project"><i class="fa fa-trash"></i> Delete</a>
        <?php } ?>
        <?php if(user_access('edit project')) {  ?>
        <a href="<?php echo site_url('project/add/'.$project->slug()) ?>" title="Edit Project"><i class="fa fa-pencil"></i> Edit</a>
        <?php } ?>
    </div>

    <div class="col-md-12">
            <blockquote>
                <p><?php echo $project->getDescription() ?></p>
                <footer>
                    last updated at :
                    <cite>
                        <?php
                            echo ($project->getUpdated()) ? $project->getUpdated()->format('F j, Y') : $project->getCreated()->format('f j, Y');
                            echo ' &nbsp '.$project->getStatusAsString();
                        ?>
                    </cite>
                </footer>
                    <?php if($hasMeta){ ?>
                        <div class="clearfix"></div>
                        <!--                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#projectMetaDialog">Other Details</button>-->
                        <a href="#"  data-toggle="modal" data-target="#projectMetaDialog">MORE DETAIL</a>

                        <div class="modal fade" tabindex="-1" role="dialog" id="projectMetaDialog" aria-labelledby="mySmallModalLabel">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <!--                            <div class="modal-header">-->
                                    <!--                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                                    <!--                                <h2>Other Details</h2>-->
                                    <!--                            </div>-->
                                    <div class="modal-body">
                                        <table class="table">
                                            <?php foreach($metaArray as $k => $v) {
                                                echo '<tr><td>'.$k.'</td><td>'.$v.'</td></tr>';
                                            } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php }?>


            </blockquote>


    </div>

    <hr class="separator" />

    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <ul class="messages">
            <li>
                <img src="images/img.jpg" class="avatar" alt="Avatar">
                <div class="message_date">
                    <h3 class="date text-info">24</h3>
                    <p class="month">May</p>
                </div>
                <div class="message_wrapper">
                    <h4 class="heading">Desmond Davison</h4>
                    <blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
                    <br>
                    <p class="url">
                        <span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
                        <a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
                    </p>
                </div>
            </li>
            <li>
                <img src="images/img.jpg" class="avatar" alt="Avatar">
                <div class="message_date">
                    <h3 class="date text-error">21</h3>
                    <p class="month">May</p>
                </div>
                <div class="message_wrapper">
                    <h4 class="heading">Brian Michaels</h4>
                    <blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
                    <br>
                    <p class="url">
                        <span class="fs1" aria-hidden="true" data-icon=""></span>
                        <a href="#" data-original-title="">Download</a>
                    </p>
                </div>
            </li>
            <li>
                <img src="images/img.jpg" class="avatar" alt="Avatar">
                <div class="message_date">
                    <h3 class="date text-info">24</h3>
                    <p class="month">May</p>
                </div>
                <div class="message_wrapper">
                    <h4 class="heading">Desmond Davison</h4>
                    <blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
                    <br>
                    <p class="url">
                        <span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
                        <a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
                    </p>
                </div>
            </li>
        </ul>
    </div>

    <?php if( count($project->getMeta())){  ?>
        <?php foreach($project->getMeta() as $meta){ ?>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <label><?php echo $meta->getMetaKey() ?></label><br>
                <span><?php echo $meta->getMetaValue() ?></span>
            </div>
        <?php } ?>
    <?php } ?>
</div>


<div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
<!--    <div class="row">-->

        <h2 class="blog-title">Team Members</h2>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2">
                    <img class="img-responsive" width="100" style="width:100%; float:left;" src="<?php echo image_url('profile-pic.jpg') ?>" alt=""/>
                </div>
                <div class="col-md-2">
                    <img class="img-responsive" width="100" style="width:100%; float:left;" src="<?php echo image_url('profile-pic.jpg') ?>" alt=""/>
                </div>
                <div class="col-md-2">
                    <img class="img-responsive" width="100" style="width:100%; float:left;" src="<?php echo image_url('profile-pic.jpg') ?>" alt=""/>
                </div>
                <div class="col-md-2">
                    <img class="img-responsive" width="100" style="width:100%; float:left;" src="<?php echo image_url('profile-pic.jpg') ?>" alt=""/>
                </div>
                <div class="col-md-2">
                    <img class="img-responsive" width="100" style="width:100%; float:left;" src="<?php echo image_url('profile-pic.jpg') ?>" alt=""/>
                </div>
            </div>
        </div>

        <div class="clearfix box-separator"></div>

        <h2 class="blog-title">Task List</h2>
        <div class="blog-post">
            <p class="blog-post-meta">December 14, 2013 by <a href="#">Chris</a></p>
            <p class="blog-post-detail">New feature on MIS</p>

        </div>
<!--    </div>-->
</div>







