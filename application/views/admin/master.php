<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>|| Transborder ||</title>
<link href="<?php echo base_url() ?>assets/css/layout.css" rel="stylesheet" type="text/css" />
 <link href="<?php echo $mainstyler; ?>" rel="stylesheet" type="text/css" /> <!---->
<link href="<?php echo $menustyler;?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url()?>assets/css/ui/jquery-ui-1.8.6.custom.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url()?>assets/css/jquery.loadmask.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo site_url('JS/core')?>"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.js"></script>
<script type='text/javascript' src='<?php echo base_url()?>assets/js/jquery.loadmask.min.js'></script>
<script type='text/javascript' src='<?php echo base_url()?>assets/js/transborder.js'></script>

<script type="text/javascript">
$(function(){
	/**/$('form.validate').validate({
		errorElement:'span'
	});

	$('.cancelaction').click(function(){
		window.history.back();
	});
});
</script>
<base href="<?php echo base_url()?>" />
</head>

<body>

	<div class="container container_12" id="wrapper">
	
		<!-- TOP SECTION START -->
		<?php $this->load->view('includes/topbar')?>
		<!-- TOP SECTION END -->
		
		<!-- BRANDING START -->
		<?php $this->load->view('includes/brand')?>
		<!-- BRANDING END -->
		
		<!-- MAIN NAVIGATION START -->
		<?php $this->load->view('includes/mainnav')?>
		<!-- MAIN NAVIGATION END -->
		
		<div class="maincontainer">
			<!-- BREAD CRUMB START -->
			<div class="grid_12">
				<div class="breadcrumb">
		        	<?php echo $this->breadcrumb->output();?>
		        </div>
		    </div>
		    <div class="clear"></div>
			<!-- BREAD CRUMB END -->
			
			<!-- MODULE SECTION START -->
			<div class="main">
				
				<?php 
					if(isset($maincontent))
						$this->load->view($maincontent);
				?>
				
			
			<!-- MODULE SECTION END -->
			<div class="clear"></div>
    		</div>
    	
        
        
	    <div class="clear"></div>
	    </div>	

</body>
</html>