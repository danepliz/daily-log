<?php //
//	echo get_header();
////	echo tb_getTopBar();
//	echo tb_getBrandBar();
//	echo tb_getMainNav();
//?>
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
				
				<div class="grid_12">
				<h2><?php echo lang('change_password'); ?></h2>
	
				<div class="section">
        		<div>
        			<form class="validate" action="" method="post">
        				<p class="element">
	        				<label><?php echo lang('old_password'); ?></label>
	        				<input type="password" name="oldpwd" class="required" />
        				</p>
        				<p class="element">
        					<label><?php echo lang('new_password'); ?></label>
        					<input type="password" name="newpwd" class="required" />
        				</p>
        				<p class="element">
        					<label><?php echo lang('confirm_password'); ?></label>
        					<input type="password" name="conpwd" class="required" />
        				</p>
        				
        				
        				<p>
        					<label>&nbsp;</label>
        					<input type="submit" value="Change" />
        					
        				</p>
        			</form>	
        		</div>
        		</div>
		</div>

			
        
<?php 
//echo get_footer();
?>        