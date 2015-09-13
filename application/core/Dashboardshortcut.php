<?php

class Dashboardshortcut{
	
	static $shortcuts = array();
	
	static function register($shortcut)
	{
		self::$shortcuts[] = $shortcut;
	}
	/**/
	

}
