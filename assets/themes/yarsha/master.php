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
        // loadCSS(['bootstrap.min', 'fonts/css/font-awesome.min', 'animate.min', 'custom', 'icheck/flat/green.css' ]);
        loadCSS(['bootstrap.min', 'fonts/css/font-awesome.min', 'custom', 'style' ]);

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


<body>


<div id="header-row">
    <div class="head-wrapper">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo base_url() ?>">DALOG</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
<!--                        <li><a href="#"><i class="fa fa-unlock-alt"></i> Login</a></li>-->
                        <li><a href="#"><i class="fa fa-bell-o"></i></a></li>
                        <li><a href="#"><i class="fa fa-gear"></i></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle top-profile-pic" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Danepliz
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </div>
</div>


<div class="">
    <div class="content-wrapper">

        <?php if (isset($maincontent)) $this->load->theme($maincontent); ?>


    </div>
</div><!-- content -->





</body>

</html>