<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Yarsha | <?php echo isset($page_title)? $page_title : ''  ?> </title>

    <!-- Bootstrap core CSS -->
    <?php
        loadCSS(['bootstrap.min', 'fonts/css/font-awesome.min', 'animate.min', 'custom', 'icheck/flat/green.css' ]);

    ?>

    <script src="<?php echo base_url().'JS/core' ?>"></script>

    <?php loadJS(['jquery.min','bootstrap.min', 'jquery.validate', 'yarsha']); ?>

    <!--[if lt IE 9]>
    <script src="../assets/js/ie8-responsive-file-warning.js"></script>
    <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>


<body class="nav-md">

<div class="container body">


<div class="main_container">

<div class="col-md-3 left_col">
    <div class="left_col scroll-view">

        <div class="navbar nav_title" style="border: 0;">

            <a href="<?php echo base_url() ?>" class="site_title"><span>
<!--                    <img src="--><?php //echo base_url().'assets/themes/yarsha/resources/images/brand.png' ?><!--" />-->
                    YARSHA
                </span>
            </a>
        </div>
        <div class="clearfix"></div>

        <!-- menu prile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src="images/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $current_user->getFullName() ?></h2>
            </div>
        </div>
        <!-- /menu prile quick info -->

        <br />

        <!-- sidebar menu -->
        <?php echo getAdminMenu(); ?>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>

<!-- top navigation -->
<div class="top_nav">

    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="images/img.jpg" alt=""><?php echo $current_user->getFullName(); ?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                        <li><a href="<?php echo site_url('user/profile') ?>">  Profile</a>
                        </li>
<!--                        <li>-->
<!--                            <a href="javascript:;">-->
<!--                                <span class="badge bg-red pull-right">50%</span>-->
<!--                                <span>Settings</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="javascript:;">Help</a>-->
<!--                        </li>-->
                        <li><a href="<?php echo site_url('auth/logout') ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                        </li>
                    </ul>
                </li>

<!--                <li role="presentation" class="dropdown">-->
<!--                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">-->
<!--                        <i class="fa fa-envelope-o"></i>-->
<!--                        <span class="badge bg-green">6</span>-->
<!--                    </a>-->
<!--                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">-->
<!--                        <li>-->
<!--                            <a>-->
<!--                                            <span class="image">-->
<!--                                        <img src="images/img.jpg" alt="Profile Image" />-->
<!--                                    </span>-->
<!--                                            <span>-->
<!--                                        <span>John Smith</span>-->
<!--                                            <span class="time">3 mins ago</span>-->
<!--                                            </span>-->
<!--                                            <span class="message">-->
<!--                                        Film festivals used to be do-or-die moments for movie makers. They were where...-->
<!--                                    </span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a>-->
<!--                                            <span class="image">-->
<!--                                        <img src="images/img.jpg" alt="Profile Image" />-->
<!--                                    </span>-->
<!--                                            <span>-->
<!--                                        <span>John Smith</span>-->
<!--                                            <span class="time">3 mins ago</span>-->
<!--                                            </span>-->
<!--                                            <span class="message">-->
<!--                                        Film festivals used to be do-or-die moments for movie makers. They were where...-->
<!--                                    </span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a>-->
<!--                                            <span class="image">-->
<!--                                        <img src="images/img.jpg" alt="Profile Image" />-->
<!--                                    </span>-->
<!--                                            <span>-->
<!--                                        <span>John Smith</span>-->
<!--                                            <span class="time">3 mins ago</span>-->
<!--                                            </span>-->
<!--                                            <span class="message">-->
<!--                                        Film festivals used to be do-or-die moments for movie makers. They were where...-->
<!--                                    </span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a>-->
<!--                                            <span class="image">-->
<!--                                        <img src="images/img.jpg" alt="Profile Image" />-->
<!--                                    </span>-->
<!--                                            <span>-->
<!--                                        <span>John Smith</span>-->
<!--                                            <span class="time">3 mins ago</span>-->
<!--                                            </span>-->
<!--                                            <span class="message">-->
<!--                                        Film festivals used to be do-or-die moments for movie makers. They were where...-->
<!--                                    </span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <div class="text-center">-->
<!--                                <a>-->
<!--                                    <strong>See All Alerts</strong>-->
<!--                                    <i class="fa fa-angle-right"></i>-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->

            </ul>
        </nav>
    </div>

</div>
<!-- /top navigation -->

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">

            <div class="title_left">
                <h3><?php echo ( isset($page_title) ) ? $page_title : '&nbsp'; ?></h3>

            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <ol class="breadcrumb"> <?php echo $this->breadcrumb->output(); ?> </ol>
<!--                    <div class="input-group">-->
<!--                        <input type="text" class="form-control" placeholder="Search for...">-->
<!--                                    <span class="input-group-btn">-->
<!--                            <button class="btn btn-default" type="button">Go!</button>-->
<!--                        </span>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
        <div class="clearfix"></div>


        <?php
        if( isset($critical_alerts) || isset($feedback) || $validation_errors = validation_errors('<p>','</p>') ){ ?>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <?php

                if(isset($critical_alerts)){
                    foreach($critical_alerts as $type => $msg){
                        $output = '<div class="alert alert-warning alert-dismissable">';
                        $output .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
                        $output .= $msg;
                        $output .= '</div>';
                        echo $output;
                    }
                }

                if(isset($feedback)){
                    foreach($feedback as $type => $messages){
                        if(count($messages) > 0){
                            $class = ($type == 'error')? 'danger' : $type;
                            $output = '<div class="alert ff alert-'.$class.' alert-dismissable">';
                            $output .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';

                            foreach($messages as $msg)
                                $output .= '<p>'.$msg.'</p>';

                            $output .= '</div>';
                            echo $output;
                        }
                    }
                }

                ?>
                <?php
                if($validation_errors = validation_errors('<p>','</p>'))
                    echo '<div class="alert vv alert-danger alert-dismissable">'.$validation_errors.'</div>';
                else echo "&nbsp;";
                ?>

            </div>
        </div>
        <?php } ?>


        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if (isset($maincontent)) $this->load->theme($maincontent); ?>
<!--                <div class="x_panel" style="height:600px;">-->
<!--                    <div class="x_title">-->
<!--                        <h2>Page Title</h2>-->
<!---->
<!--                        <ul class="nav navbar-right panel_toolbox">-->
<!--                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>-->
<!--                            </li>-->
<!--                            <li class="dropdown">-->
<!--                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>-->
<!--                                <ul class="dropdown-menu" role="menu">-->
<!--                                    <li><a href="#">Settings 1</a>-->
<!--                                    </li>-->
<!--                                    <li><a href="#">Settings 2</a>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li><a class="close-link"><i class="fa fa-close"></i></a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                        <div class="clearfix"></div>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
    </div>

    <!-- footer content -->
    <footer>
        <div class="">
            <p class="pull-right">Yarsha studio employee work log ! <a href="http://yarshastudio.com/">Yarsha Studio</a>. |
                <span class="lead"> <i class="fa fa-paw"></i> Yarsha Studio!</span>
            </p>
        </div>
        <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->

</div>
<!-- /page content -->
</div>

</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>



<?php
loadJS([

    'chartjs/chart.min',
    'progressbar/bootstrap-progressbar.min',
    'nicescroll/jquery.nicescroll.min',
    'icheck/icheck.min',

    'custom',

//    'moris/raphael-min',
//    'moris/morris',
//    'moris/example'
]);


$notyUI = (
    (isset($site_maintenance) and is_array($site_maintenance))
    or
    (isset($user_switch) and is_array($user_switch))
) ? TRUE : FALSE;
?>

<script type="text/javascript">
    if ( ! window.console || typeof console === "undefined" ) {
        console = {};
        console.log = function(arg){};
    }

    $(function(){

//        $('.datepicker').datepicker({
//            dateFormat: 'yy-mm-dd',
//            changeMonth: true,
//            changeYear: true
//        });

        var parentMenu = $('.sidebar-menu').find('li.active').parent('ul').parent('li');
        parentMenu.addClass('active');
        parentMenu.find('ul').show();
        var height = window.innerHeight - 70;
        $('section.content').css({'min-height':height});

        <?php if (isset($site_maintenance) and is_array($site_maintenance)): ?>
        generateSiteNoty();
        <?php endif?>
        <?php if (isset($user_switch) and is_array($user_switch)): ?>
        generateUserNoty();
        <?php endif?>

        <?php if ($notyUI):?>
        //$('body').css('margin-top', parseInt($('body').css('margin-top'))+37);
        <?php endif?>
        Yarsha.validator = $('form.validate').validate({
            errorElement:'span'
        });

//        $(".multiselect").multiSelect();
//
//        $(".search-select").select2();

        $('.cancelaction').click(function(){
            window.history.back();
        });

        $('.backlink').click(function(){
            window.location = $(this).attr('link');
        });
    });

    <?php if (isset($site_maintenance) and is_array($site_maintenance)): ?>
    function generateSiteNoty() {

        var s = noty({
            text: '<?php echo $site_maintenance['text']?>',
            type: '<?php echo $site_maintenance['type']?>',
            dismissQueue: true,
            layout: '<?php echo $site_maintenance['layout']?>',
            theme: '<?php echo $site_maintenance['theme']?>',
            closeWith:['button'],
            callback: {
                afterClose: function() {
                    $('body').css('margin-top', parseInt($('body').css('margin-top'))-37);
                }
            },
        });
    }
    <?php endif?>

    <?php if (isset($user_switch) and is_array($user_switch)): ?>
    function generateUserNoty() {

        var u = noty({
            text: '<?php echo $user_switch['text']?>',
            type: '<?php echo $user_switch['type']?>',
            dismissQueue: true,
            layout: '<?php echo $user_switch['layout']?>',
            theme: '<?php echo $user_switch['theme']?>',
            closeWith:['button'],
            callback: {
                afterClose: function() {
                    $('body').css('margin-top', parseInt($('body').css('margin-top'))-37);
                }
            }

        });
    }
    <?php endif?>




</script>

</body>

</html>