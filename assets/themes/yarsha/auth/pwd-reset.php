<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo lang('first_global_money_pwd_reset'); ?></title>
<?php loadCss(array('login',)); ?>
<?php loadJS(array('jquery','jquery.validate',))?>
<base href="<?php echo base_url()?>" />
</head>

<body>

	<div id="wrapper">
    	<div class="adHolder">
        	<img src="<?php echo theme_url()?>assets/images/adHolder.png" />
        </div>
      
        <form method="post" action="" id="transborder-login">
        
        <div class="formHolder">
        	<div class="logoHolder"><img src="<?php echo theme_url()?>assets/images/fgm_logo.png" /></div>
        	<?php 
        		echo get_validation_errors();
				if(isset($feedback)){
					foreach($feedback as $type => $messages){
						foreach($messages as $msg)
							echo '<p class="instruction '.$type.'">'.$msg.'</p>';
					}
				}
			?>
			<p class="instruction"><?php echo lang('hello'); echo $user->getUsername();?>,<?php echo lang('new_pwd'); ?></p>
            <div class="inputHolder">
            	<p><?php echo lang('new_password'); ?>: <input name="transborder_new_pwd" class="inputarea pwdreset required" type="password" /></p>
            </div>
            <div class="inputHolder">
                <p><?php echo lang('confirm_password'); ?>: <input name="transborder_new_pwd_confirm" class="inputarea pwdreset required" type="password" /></p>
            </div>
            
            <div class="submitHolder">
        		<p>
        			<input type="submit" value="Change Password" class="login" />
        		</p>
        		
        		<p class="bcklogin">
        			<img src="<?php echo theme_url()?>assets/images/icon_arrow.png" style="vertical-align: text-bottom;" />
        			<span class="backtologin"><a href="<?php echo site_url('auth/login');?>"><?php echo lang('back_to_login'); ?></a> </span>
        		</p>
        	</div>
     
        </div>
        </form>
        
     	<div class="contactHolder">
        	<p><img src="<?php echo theme_url()?>assets/images/icon_tel.png" style="vertical-align: text-bottom;" /> 1+(800) 675 2270</p>
            <p><img src="<?php echo theme_url()?>assets/images/icon_email.png" style="vertical-align:text-bottom" /><?php echo lang('company_email'); ?></p>
        </div>  
        
        <div class="siteLinks">
         <p><a href="#"><?php echo lang('corporate_fgdc_site'); ?></a> <img src="<?php echo theme_url()?>assets/images/icon_sepArrow.png" style="vertical-align: text-bottom;" /> <a href="#"><?php echo lang('corporate_remitx_site'); ?></a> <img src="<?php echo theme_url()?>assets/images/icon_sepArrow.png" style="vertical-align: text-bottom;" /> <a href="#"><?php echo lang('corporate_site'); ?></a> <img src="<?php echo theme_url()?>assets/images/icon_sepArrow.png" style="vertical-align: text-bottom;" /> <a href="#"><?php echo lang('sitio_corporativo'); ?>Sitio Corporativo</a></p>
        </div> 
    
    </div>
    
   <div id="footerHolder">
    	<div class="ftcontentHolder">
        	<div class="ftcontentLeft">
            	<p><?php echo lang('notice'); ?></p>
				<p><?php echo lang('legal_action'); ?></p>
            </div>
            <div class="ftcontentRight">
            	<p><?php echo lang('swapp'); ?></p>
				<p><?php echo lang('copyright'); ?></p>
				<p><?php echo lang('license_note'); ?></p>
            </div>
        </div>
    </div>
    <script>
$(function(){
	$('#transborder-login').validate({
		errorElement:'span'
	});
	
});
</script>
</body>
</html>
