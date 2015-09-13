
<?php /* <html>
<head>
<title>Error</title>
<style type="text/css">

body {
	background:#076c38;
	font-family:Arial, Helvetica, sans-serif;
    font-size:11px;
}


#content  {
	border:				#999 1px solid;
	background-color:	#fff;
	padding:			20px 20px 12px 20px;
}

h1 {
font-weight:		normal;
font-size:			14px;
color:				#990000;
margin:				0 0 4px 0;
}
</style>
</head>
<body>
	<div id="content">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div>
</body>
</html>
 */ ?>

<?php echo get_header(); ?>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger">
            <h3><?php echo $heading; ?></h3>
            <p><?php echo $message; ?></p>
    </div>
</div>

<?php echo get_footer(); ?>