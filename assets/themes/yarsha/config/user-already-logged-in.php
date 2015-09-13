	<div class="grid_12">
				<h2><?php echo lang('already_logged_in'); ?></h2>
				
				<div class="grid_12">
					<fieldset>
						<legend>The user <?php echo Current_User::user()?> has already logged in to Transborder from another machine.</legend>
						
						<div class="grid_12">
							
							<?php echo lang('sry_login_already'); ?><br/> 
							Try logging in as <a href="<?php echo site_url('auth/logout')?>">another user</a>. 
							
						</div>
						
					</fieldset>
				</div>
				
		</div>
		
<?php
	echo get_footer();
	exit; // exit is required
?>