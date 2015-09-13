<h2><?php echo lang('configuration'); ?></h2>
<div class="grid_12">
	<fieldset>
		<legend><?php echo lang('configuration'); ?></legend>
		
		<div class="grid_12">
			<?php 
				echo (strtotime(\Options::get('site_maintenance_resume_after', '0000-00-00 00:00:00')) > 0 
						and (\Options::get('site_maintenance_resume','0')=='1')
					) ? 
					'The Transborder Services will probably resume on '.\Options::get('site_maintenance_resume_after', '0').' !'
					 :
					'The Transborder Services will resume soon.'
				; 
			?>
			
		</div>
		
	</fieldset>
</div>

<?php
	echo get_footer();
	exit; // exit is required
?>