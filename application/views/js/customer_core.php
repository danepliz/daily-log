<?php

//This is the core TRANSBORDER JS library

?>

var Transborder = 
{
	config:{
		base_url : '<?php echo base_url();?>'
	},
	validator : null,
	allcountries: <?php echo json_encode($allCountries);?>,
	destinationCountries: <?php echo json_encode($destinationCountries);?>
};