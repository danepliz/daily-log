<?php

function theme_url()
{
	$current_theme = CI::$APP->config->item('current_theme');
	$path = base_url().'assets/themes/'.$current_theme.'/';
	
	return $path;
}


function theme_path()
{
	$current_theme = CI::$APP->config->item('current_theme');
	$path = './assets/themes/'.$current_theme.'/';
	
	return $path;
}

function get_header()
{
	//$current_theme = CI::$APP->config->item('current_theme');
	//$path = './assets/themes/'.$current_theme.'/header';
	CI::$APP->load->theme('common/header');
}

function get_footer()
{
	CI::$APP->load->theme('common/footer');
}

function get_main_nav(){
	CI::$APP->load->theme('common/mainnav');
}

/**
*	global function to get the theme configs
*	defined in template.php within the theme
*/
function _t($config)
{
	$args = func_get_args();
	$current_theme = CI::$APP->config->item('current_theme');
	$function = $current_theme.'_'.$config;
	
	if(function_exists($function))
		return call_user_func_array($function,array_slice($args, 1));
	else return FALSE;
}

function f1_slider()
{
	echo Modules::run('slider');
}

function loadCSS( $files, $print = false ){
	
	$cssPath = BASEPATH.'../assets/themes/' . config_item('current_theme') . '/resources/css/';
	
	foreach ((array) $files as $f) {
		
		if (substr($f, 0, 8) == 'https://' || substr($f, 0, 7) == 'http://' || is_file( strstr($f, '?', true) ) )
		{
			$url = $f;
		}
		else {
			$f = ( strtolower(substr($f,-4)) == '.css' ) ? $f : $f . '.css';
			$url = (is_file(theme_path() . 'css/' . $f)) ? theme_url() : base_url() . 'assets/themes/yarsha/';
			
			$crushedFile = CssCrush::file( $cssPath.$f );
			
// 			$url .= 'css/' . $crushedFile;

			$url = $crushedFile;
		}
		
		echo "<link rel='stylesheet' type='text/css' href='{$url}' " . ($print ? ' media="print"' : '') ."/>\n";

	}
}

function loadJS( $files ){
	
	foreach ((array) $files as $f) {
		
		if (substr($f, 0, 8) == 'https://' || substr($f, 0, 7) == 'http://' || is_file( strstr($f, '?', true) ) )
			$url = $f;
		else {
			$f = ( strtolower(substr($f,-3)) == '.js' ) ? $f : $f . '.js';
			$url = (is_file(theme_path() . 'js/' . $f)) ? theme_url() : base_url() . 'assets/themes/yarsha/';

			$url .= 'resources/js/' . $f;
		}
			
		echo "<script type='text/javascript' src='{$url}'></script>\n";

	}
}

function loadImage( $image, $attributes = array()) {
	
	if ( substr($image, 0, 8) == 'https://' || substr($image, 0, 7) == 'http://'  )
		$url = $image;
	else {
		$url = (is_file(theme_path() . 'resources/images/' . $image)) ? theme_url() : base_url() . 'assets/themes/yarsha/';
	
		$url .= 'resources/images/' . $image;
	}
	
	echo "<img src='{$url}'" . _parse_attributes($attributes) . " />";
}

function locateIcon( $image ){
	
	if (strstr($image, '.') === FALSE ) $image .= '.png'; 
	
	$loc = (is_file(theme_path() . 'resources/icons/' . $image)) ? theme_url() : base_url() . 'assets/themes/yarsha/';
	
	$loc .= 'resources/icons/' . $image;
	
	return $loc;
}

function image_url($string = ''){
    return theme_url().'resources/images/'.$string;
}

function getImageTag($src, $attr = array()){
    $attributes = '';
    if(count($attr)){
        foreach($attr as $key => $val){
            $attributes .= $key .'="' . $val . '" ';
        }
    }

    return '<img class="img-responsive" src="'.$src.'" '.$attributes.' />';
}
?>
