<?php
function get_validation_errors(){
	if($validation_errors = validation_errors('<p>','</p>'))
		echo '<div class="alert alert-danger alert-dismissable">'.$validation_errors.'</div>';
}

function getMessages(){
	
}