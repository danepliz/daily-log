<?php

class MainMenu{
	
	private $ci;
	
	private static $index;
	
	private static $registry;
	
	private static $output;
	
	private function __construct(){
		
	}
	
	public static function init(){
		//$this->ci =& \CI::$APP;
		self::$index = array();
		self::$registry = array();
	}
	
	public static function getById($id){
		if(!isset(self::$registry[$id]))
			throw new InvalidArgumentException("No menu found with ID :: ".$id);
		
		return self::$registry[$id];
	}
	
	public static function register(\MainMenuItem &$menuitem, $beforeAfterFlag = NULL, $targetId = NULL){
		
		$identifier = $menuitem->getId();
		
		if(array_key_exists($identifier, self::$registry))
			throw new Exception("Menu with ID ".$identifier." already exists.");
			
		if(!is_null($beforeAfterFlag) && is_null($targetId))
			throw new InvalidArgumentException("Target ID is required when using beforeAfterFlag ".$beforeAfterFlag);
		
		if(is_null($menuitem->getParent())){
			self::$registry[$identifier] = $menuitem;
			
			if(is_null($beforeAfterFlag))
				self::$index[] = $identifier;
			else{
				$before = ($beforeAfterFlag == 'before') ? TRUE:FALSE;
				self::updateIndex($identifier,$targetId,$before);
			}
			
		}else{
			//find the parent node
			$parent_node = &self::findMenuById($menuitem->getParent()->getId());
			if($parent_node === FALSE)
				throw new InvalidArgumentException("Parent menu ".$menuitem->getParent()->getId()." not found.");
			
			/*@var $parentMenu MainMenuItem */
// 			$parentMenu = self::$registry[$menuitem->getParent()->getId()];
			$parentMenu = $parent_node;
			$parentMenu->addChild($menuitem);
			
			//update the index
			if(!is_null($beforeAfterFlag)){
				//find the children of the specified parent with the given identifier(targetId)
 				$_children = $parentMenu->getChildren();
 				$_children_array = array();
 				
 				foreach($_children as $c){
 					$_children_array[] = $c->getId();
 				}
 				
				$_offset = array_search($targetId, $_children_array);
				if($_offset === FALSE)
					throw new InvalidArgumentException("Target ID ".$targetId." not found inside parent menu ".$menuitem->getParent()->getId());
				
				$before = ($beforeAfterFlag == 'before') ? TRUE:FALSE;
				self::updateIndex($identifier,$targetId,$before);
			}else{
				self::$index[] = $identifier;
			}
		}
	}
	
	/**
	 * Only supports upto 3 levels
	 */
	
	private static function findMenuById($id){
		if(isset(self::$registry[$id]))
			return self::$registry[$id];
		
		foreach(self::$registry as $m){
			if($m->getId() == $id){
				return $m;
			}
			
			if(count($m->getChildren()) > 0){
				foreach($m->getChildren() as $c){
					if($c->getId() == $id){
						return $c;
					}
					
					if(count($c->getChildren()) > 0){
						foreach($c->getChildren() as $cc){
							if($cc->getId() == $id){
								return $cc;
							}
						}
					}
				}
			}
		}
		
		return FALSE;
	}
	
	
	
	public static function render(){
		return self::wrap(self::$registry);
	}


    private static function wrap($source){

        $output = '';

        usort($source,array("MainMenu","_compare"));

        $currentRoute = current_url();

        foreach($source as $v){

            $route = $v->getRoute();

            $activeLink = ( $currentRoute == $v->getRoute() ) ? 'active' : '';

            if(is_array($v->getPermissions())){
                if (!user_access($v->getPermissions())) {

                    if(count($v->getChildren()) > 0) {

                        $all_child_accessible = FALSE;

                        foreach ($v->getChildren() as $sv) {
                            if (user_access($sv->getPermissions())) $all_child_accessible = TRUE;
                        }

                        if (!$all_child_accessible) continue;
                        else $route = '#';

                    } else continue;

                }
            }

            $treeClass = "";
            $pullDownSpan = "";
            $subMenu = "";

            $iconWrapper = $v->getParent() ? '' : '<i class="fa '.$v->getIcon().'"></i> ';

            if( count($v->getChildren()) > 0 ){
                $pullDownSpan = "<span class='fa fa-chevron-down'></span>";

                $subMenu .= "<ul  class='nav child_menu style='display: none'>";
                $subMenu .= self::wrap($v->getChildren());
                $subMenu .= "</ul>";
            }

            $linkWrapper =  "<a href='{$route}'>{$iconWrapper} {$v->getName()} {$pullDownSpan}</a>";


            $output .= "<li>";
            $output .= $linkWrapper;
            $output .= $subMenu;
            $output .= "</li>";
        }

        return $output;
    }

	private static function wrap2($source){
		
		$output = '';
		
		usort($source,array("MainMenu","_compare"));

        $currentRoute = current_url();
		
		foreach($source as $v){
			
			$route = $v->getRoute();

            $activeLink = ( $currentRoute == $v->getRoute() ) ? 'active' : '';

			if(is_array($v->getPermissions())){
				if (!user_access($v->getPermissions())) {
					
					if(count($v->getChildren()) > 0) {
						
						$all_child_accessible = FALSE;
						
						foreach ($v->getChildren() as $sv) {
								if (user_access($sv->getPermissions())) $all_child_accessible = TRUE;
							}
							
						if (!$all_child_accessible) continue;
                        else $route = '#';
						
					} else continue;
					
				}
			}

            $treeClass = "";
            $pullDownSpan = "";
            $subMenu = "";

            if( count($v->getChildren()) > 0 ){
                $treeClass = "treeview";
                $pullDownSpan = '<i class="fa fa-angle-left pull-right"></i>';

                $subMenu .= "<ul class='treeview-menu'>";
                $subMenu .= self::wrap($v->getChildren());
                $subMenu .= "</ul>";
            }

            $iconSpan = ( $v->getIcon() )? ' <i class="fa '.$v->getIcon().'"></i>' : '';

			$output .= "<li class='".$treeClass.' '. $activeLink. "'>";
			$output .= "<a href='{$route}'>".$iconSpan."<span>{$v->getName()}</span>".$pullDownSpan."</a>";
            $output .= $subMenu;
			$output .= "</li>";
		}
		
		return $output;
	}
	
	public static function _compare($a,$b){
		$indexOfA = array_search($a->getId(), self::$index);
		$indexOfB = array_search($b->getId(), self::$index);
		
		if($indexOfA == $indexOfB)
			return 0;
		
		return ($indexOfA < $indexOfB) ? -1 : 1;
	}

	
	private static function updateIndex($identifier,$position,$before = FALSE){
		$offset = array_search($position,self::$index);
		
		if($offset === FALSE){
			unset(self::$registry[$identifier]);
			throw new InvalidArgumentException("Identifier ".$position." not found");
		}
		
		$pos = ($before === FALSE) ? ($offset+1):($offset-1);
// 		echo $offset;
		if($pos < 0){
			array_unshift(self::$index, $identifier);	
		}else{
			array_splice(self::$index,$pos,0,$identifier);
		}
	}
	

	
	public static function getRegistry(){
		return self::$registry;
	}
	
	public static function getIndex(){
		return self::$index;
	}
}