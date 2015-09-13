<!-- Bootstrap -->
<!--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script>-->
<!-- AdminLTE App -->
<?php loadJS(array('noty/jquery.noty', 'noty/layouts/top', 'noty/themes/default', 'admin-app', 'jquery.multi-select' ))?>
<?php
$notyUI = (
		(isset($site_maintenance) and is_array($site_maintenance)) 
		or 
		(isset($user_switch) and is_array($user_switch))
		) ? TRUE : FALSE; 

if ($notyUI): ?>

<?php endif?>
	    
<script type="text/javascript">
if ( ! window.console || typeof console === "undefined" ) {
	console = {};
	console.log = function(arg){};
}

$(function(){

    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true
    });

    var parentMenu = $('.sidebar-menu').find('li.active').parent('ul').parent('li');
    parentMenu.addClass('active');
    parentMenu.find('ul').show();
    var height = window.innerHeight - 70;
    $('section.content').css({'min-height':height});

	<?php if (isset($site_maintenance) and is_array($site_maintenance)): ?>  	
	generateSiteNoty();
	<?php endif?>  
	<?php if (isset($user_switch) and is_array($user_switch)): ?>  	
	generateUserNoty();
	<?php endif?> 

	<?php if ($notyUI):?> 
	//$('body').css('margin-top', parseInt($('body').css('margin-top'))+37);
	<?php endif?>
	Yarsha.validator = $('form.validate').validate({
		errorElement:'span'
	});

	$(".multiselect").multiSelect();

    $(".search-select").select2();
	
	$('.cancelaction').click(function(){
		window.history.back();
	});

	$('.backlink').click(function(){
		window.location = $(this).attr('link');
	});
});

<?php if (isset($site_maintenance) and is_array($site_maintenance)): ?>
function generateSiteNoty() {
	
  	var s = noty({
  		text: '<?php echo $site_maintenance['text']?>',
  		type: '<?php echo $site_maintenance['type']?>',
      	dismissQueue: true,
  		layout: '<?php echo $site_maintenance['layout']?>',
  		theme: '<?php echo $site_maintenance['theme']?>',
  		closeWith:['button'],
  		callback: {
  			afterClose: function() {
  	  	  		$('body').css('margin-top', parseInt($('body').css('margin-top'))-37);
  	  		}
  		  },
  	});
  }
<?php endif?>

<?php if (isset($user_switch) and is_array($user_switch)): ?> 
function generateUserNoty() {
	
  	var u = noty({
  		text: '<?php echo $user_switch['text']?>',
  		type: '<?php echo $user_switch['type']?>',
      	dismissQueue: true,
  		layout: '<?php echo $user_switch['layout']?>',
  		theme: '<?php echo $user_switch['theme']?>',
  		closeWith:['button'],
  		callback: {
  			afterClose: function() {
  	  	  		$('body').css('margin-top', parseInt($('body').css('margin-top'))-37);
  	  		}
  		  },
  		
  	});
  }
<?php endif?>




</script>
</div>
</body>
</html>