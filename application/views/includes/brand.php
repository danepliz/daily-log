	<div class="brand">
		<div class="grid_3">
			<div>
            	<p><img src="assets/images/janta_bank_logo.gif" alt="Janta Bank Limited" title="Janta Bank Limited" /></p>
            </div>
		</div>
		
		<div class="grid_7">
			<?php 
//				$this->message->display('topmessage');
// 				$this->message->display('feedback');
			?>
			 <?php
				if($validation_errors = validation_errors('<p>','</p>'))
			 		echo '<div class="response error">'.$validation_errors.'</div>'; 
			?>
		</div>
		
		<div class="grid_2">
			<div style="float:right;padding-top:25px;">
                    <p><img src="assets/images/logo_sm.png" alt="Transborder" title="Transborder" /></p>
                </div>
			</div>
			
			<div class="clear"></div>
		</div>