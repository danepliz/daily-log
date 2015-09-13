<?php
/**
 *This Helper is intended to work with user defined custom function related to file 
 *@author: Madhab Acharya
 */

function isFileExists($fileName, $dir){
	
		if(!is_dir($dir)){
// 			echo "$dir is Not Writable";
			return false;
		}
		
		$dirHandle = opendir($dir);
		
		if(!$dirHandle){
// 			echo "$dir is Not Writable";
			return false;
		}
		while($file = readdir($dirHandle)){
			if($file=='.' || $file == '..' || is_dir($file)) continue;
			list($file_name, $ext) = explode('.',$file);
			
			if($file_name == $fileName)
			{
				return $dir.$file;	
			}
		}
		
		return false;
}


