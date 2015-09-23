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
        loadCSS(['bootstrap.min', 'fonts/css/font-awesome.min', 'style' ]);

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
                    <a class="navbar-brand" href="#">DaLog</a>
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

        <div class="col-md-4 col-lg-3 col-sm-12 col-xs-12" id="sidebar-left">
            <div class="box">
                <div class="box-heading"><h2 class="box-title">Test</h2></div>
                <ul>
                    <li>Test</li>
                    <li>Test</li>
                    <li>Test</li>
                    <li>Test</li>
                </ul>
            </div>
        </div>


        <div class="col-md-8 col-lg-9 col-sm-12 col-xs-12" id="container-right">
            ssh-rsa AAAAB3NzaC1yc2EAAAADAQAB AAABAQC6CZGjHjqxhtx/cM3
            kT06Z8LGUMEHxohBSU 4U+LaICOWDaLC0O/oG q89vM5m30bDlsxLHirQm knIybtOHwhSMWllYn \
            k37Pc02vLaMsGeVMI ii14b8vUlOIJw9jR R7XDgj70zuhzK4ErHeNt8/T xScZ7qPU1syfqnoBhLym
             M9zNrXlXSVF55e BzLzTGAjgp373CnnSL1 mturubVz4By+j5c4In8 yBPXXyc4KvgvGIj6YrTvLix
            gHLMvPRWi4IqJUb 3wWzcnDJWoV2Vx+Ec 4cNgGd3MX4q+pxl rI7kUb43HBiL96HC l6WgfEPOf2iKv
            BrrlmV1p6qbatN 9scO3Uh/GPh danepliz@gmail.com
        </div>


    </div>
</div><!-- content -->





</body>

</html>