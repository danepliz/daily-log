<?php 
 	$CI = \CI::$APP;
	$CI->load->theme('web/page_common/header');
	$CI->load->theme('web/page_common/fgd-brand');
	$CI->load->theme('web/page_common/mainnav');
?>

<div class="container_12">
	<div id="affiliated_wrapper">
		<!-- MODULE SECTION START -->
		<?php $CI->load->theme('web/pages/show_404'); ?>
	</div>
</div><!--  CONTAINER 12 -->

<div class="clear"></div>

<?php $CI->load->theme('web/page_common/footer');  //*/ ?>        