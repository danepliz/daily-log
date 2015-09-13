<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yeti Billing <?php echo isset($page_title)? ' | '.$page_title : '' ?></title>
<!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Theme style -->
    <?php loadCSS(array('font-awesome.min','font', 'bootstrap.min','AdminLTE', 'select2.min', 'multi-select', 'jquery-ui.min', 'jquery-ui.theme.min', 'jquery.loadmask', 'yarsha')); ?>

    <script src="<?php echo base_url().'JS/core' ?>"></script>

    <?php loadJS(array('jquery.min', 'jquery-ui.min', 'jquery.validate', 'select2.min', 'jquery.multi-select', 'notify.min', 'bootstrap.min', 'jquery.loadmask.min', 'bootbox.min.js', 'yarsha')) ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="skin-blue">