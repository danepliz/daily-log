<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>An Exception was caught</h4>

<p><strong>File : </strong><?php echo $exception->getFile();?></p>
<p><strong>Line NUmber : </strong><?php echo $exception->getLine();?></p>
<p><strong>Message : </strong><?php echo $exception->getMessage();?></p>
<p><strong>Logged In User : </strong><?php echo (class_exists('Current_User') && \Current_User::user()) ? Current_User::user()->getUsername() : 'Authentication Layer Not Reached';?></p>
<p><strong>Request URI : </strong><?php echo $_SERVER['REQUEST_URI']?></p>
<p><strong>Request Method : </strong><?php echo $_SERVER['REQUEST_METHOD']?></p>
<p><strong>Stack Trace : </strong></p>
<p>
	<table>
	<?php 
		$trace = $exception->getTrace();	
		$file = NULL;
		$line = NULL;
		$function = NULL;
		$class = NULL;
		foreach($trace as $t){
	?>
		<tr>
			<td>&nbsp;</td>
			<td>
				<?php
					if(array_key_exists('file', $t)){
						$file = $t['file'];
					}
					echo $file;
				
				?>
			</td>
			<td>
				<?php 
					if(array_key_exists('line', $t)){
						$line = $t['line'];
					}
					echo $line;
				?>
			</td>
			
			<td>
				<?php 
					if(array_key_exists('class', $t)){
						$class = $t['class'];
					}
					echo $class;
				?>
			</td>
			
			<td>
				<?php 
					if(array_key_exists('function', $t)){
						$function = $t['function'];
					}
					echo $function;
				?>
			</td>
			
	<?php
		}
	?>
		</tr>
	</table>
</p>
<?php show_pre($exception->getTraceAsString())?>
</div>