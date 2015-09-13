<?php

class WidgetManager
{
	static $widgets = array();
	
	/**
	 * 
	 * @param array $widget
	 * 
	 * Example:
	 * 
	 * [php]
	 * $widget = array(	'name'			=>	"Today's Transaction",
	 * 					'ID'			=>	"WD_TODAY_TXN",
	 * 					'script'		=>	"transaction/TxnAmount",
	 * 					'description'	=>	"Shows todays transaction summary"
	 * 					'permissions'	=>	"transaction status" //ANDed or ORed permission list
	 * 				)
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 */
	public static function register(array $widget){
		if(!isset($widget['name']) || 
			!isset($widget['ID']) ||
			!isset($widget['script'])){
			
				throw new \InvalidArgumentException('Incomplete widgets definition.');
		}
		
		$ID = $widget['ID'];
		if(isset(self::$widgets[$ID]))
			throw new \Exception("A widget with ID :: {$ID} is already registered.");
		$permission = TRUE;
		if(isset($widget['permissions']) && !user_access($widget['permissions'])) $permission = FALSE;
		if ($permission == TRUE) 
			self::$widgets[$ID] = $widget;
	} 
	
	public static function render(){
		if(count(self::$widgets))
			foreach(self::$widgets as $widget){
			
				self::renderWidget($widget);
				
		}
	}
	
	private static function renderWidget($widget){
		if(!isset($widget['size'])){
			$class = 'grid_3';
		}else{
			$class = 'grid_'.$widget['size'];
		}
		
		echo '<div class="'.$class.' widget-container">
	   		<div class="widget">';
		
		echo "<h3>{$widget['name']}</h3>";
		
		echo "<div class='widget-content'>";
		
		\Widget::run($widget['script']);
		
		echo "</div>
			</div>
		</div>";
	}
	
	public static function renderMarquee()
	{
		$ci = & \CI::$APP;
		$msgRepo = & $ci->doctrine->em->getRepository('models\Common\Message');
		$agentRepo = & $ci->doctrine->em->getRepository('models\Agent');
		
		$agent = Current_User::user()->getAgent();
		$pagent = $agent->getParentAgent();
		$agentID = ($pagent) ? $pagent->id() : $agent->id();
		if (Current_User::isSuperUser()) $agentID = NULL;
		
		$data['msgs'] 	= $msgRepo->getMsgs($agentID, 4);
		//	$data['msgs'] = NULL;
		$data['agents'] = $agentRepo->principalAgentsArray();
			
		
		$current_theme = CI::$APP->config->item('current_theme');
		extract($data);
		$path = './assets/themes/'.$current_theme.'/';
		include $path.'message'.'/widgets/'.'marquee_message.php';
	}
	
}