<div class="grid_12">
	<h2><?php echo lang('dashboard'); ?></h2>
	<div class="section" style="display: none;">
	
     	<?php if(isset($critical_alerts)): ?>
     	<div class="grid_12 critical_message_alert">
     		<h3><img src="<?php echo base_url().'assets/images/delete.png' ?>" /><?php echo lang('critical_messages'); ?> </h3>
		     	<?php foreach($critical_alerts as $k=>$v): $sn = $k+1; ?>
		     			<p class="critical_message"><?php echo $sn.'.  '.$v; ?></p>
	     	<?php 	
				endforeach;
		     	echo '</div>';
		     	endif;
     		?>
     	</div>
   		
   	<div class="clear"></div>
</div>
<div class="clear"></div>
<div class="marquee">
	<div class="grid_1"><strong><?php // echo lang('messages'); ?> </strong></div>
	<?php // WidgetManager::renderMarquee();?>
	<div class="clear"></div>
</div>

<?php 
// 	if(\Current_User::user() && !Current_User::isSuperUser() && user_access('new remittance entry')){ 
// 		echo '<div class="grid_12">';
// 		echo '<a href="'.site_url('remittance/newentry').'" class=" css3button button" style="float:right !important;">'.lang('send_money').'</a>';
// 		echo '</div><p>&nbsp;</p><div class="clear"></div>';
// 	}
?>

<div class="dashboard-widgets sortable" id="masonry">
	<?php 
		//WidgetManager::render();
	?>
</div>
<div class="clear"></div>
<script>

$(document).ready(function(){

	$('div.widget-content').each(function(){
		if($(this).html() == "") $(this).closest('div.widget-container').hide();
	});
	
});

</script>