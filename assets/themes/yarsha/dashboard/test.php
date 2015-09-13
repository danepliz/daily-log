<div class="grid_12">
	<h2><?php echo lang('dashboard')?></h2>
	<div class="section">
     	<?php 
     	if(isset($critical_alerts))
     		show_pre($critical_alerts,"Critical Messages");
     	?>
   	</div>
   	
   	<div class="dashboard-widgets sortable" id="masonry">
   		
   				<div class="grid_3 widget-container">
   					<div class="widget">
						<h3><?php echo lang('today_transaction_count')?></h3>
						<div class="widget-content">
							<?php Widget::run('transaction/TxnNotes'); ?>
						</div>
					</div>
			   	</div>
   			
   				<div class="grid_3 widget-container">
   					<div class="widget">
						<h3><?php echo lang('today_transaction_amounts')?></h3>
						<div class="widget-content">
							<?php  Widget::run('transaction/TxnAmount'); ?>
						</div>
					</div>
			   	</div>
   			
   				<div class="grid_3 widget-container">
   					<div class="widget">
				   		<h3><?php echo lang('payout_search')?></h3>
				   		<div class="widget-content">
				   			<?php Widget::run('transaction/PayoutSearch')?>
				   		</div>
			   		</div>
			   	</div>
   			
   				<div class="grid_3 widget-container">
   					<div class="widget">
			   			<h3><?php echo lang('exchange_rates')?></h3>
			   			<div class="widget-content">
			   			<?php Widget::run('forex/ExchangeRates'); ?>
	   					</div>
	   				</div>
   				</div>
   				
   				<div class="grid_3 widget-container">
   					<div class="widget">
			   			<h3><?php echo lang('exchange_rates')?></h3>
			   			<div class="widget-content">
			   			Widget Widget
	   					</div>
	   				</div>
   				</div>
   				
   				<div class="grid_3 widget-container">
   					<div class="widget">
			   			<h3><?php echo lang('closed_transaction')?></h3>
			   			<div class="widget-content">
			   			<?php Widget::run('transaction/ClosedTransaction') ?>
	   					</div>
	   				</div>
   				</div>
   				
   			
   				
   	</div>
   	<div class="clear"></div>
   	<script type="text/javascript" src="<?php base_url()?>assets/js/masonry.js"></script>
   	<script type="text/javascript">
   	$(function () 
   			{
		    var t = $('#masonry');
		    
		    t.masonry({
		        itemSelector:        '.widget-container',
		        isResizable:        false,
		    });
		    
		    t.sortable({
		        distance: 12,
		        forcePlaceholderSize: true,
		        items: '.widget-container',
		        placeholder: 'card-sortable-placeholder',
		        tolerance: 'pointer',
		        
		        start:  function(event, ui) {            
		                 console.log(ui); 
		            //ui.item.addClass('dragging').removeClass('layout-card');
		             		ui.placeholder.width(ui.item.width());
		            
		                   ui.item.parent().masonry('reload')
		                },
		        change: function(event, ui) {
		                   ui.item.parent().masonry('reload');
		                },
		        stop:   function(event, ui) { 
		                   ui.item.removeClass('dragging').addClass('layout-card');
		                   ui.item.parent().masonry('reload');
		        }
		   });
		});
   	</script>
 </div>
<div class="clear"></div>