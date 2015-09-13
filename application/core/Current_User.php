<?php

use user\models\Group;

use user\models\User,
	Doctrine\Common\Util\Debug;
	
class Current_User {
	
	/**
	 * 
	 * @var user/models
	 */
	private static $user;
	
	private static $permissions = array();
	
	private static $remitCountry;

	private function __construct() {}

	public static function user() {
		
		if(!isset(self::$user)) {
			$CI =& get_instance();
			$CI->load->library('session');
			
			if (!$user_id = $CI->session->userdata('user_id')) {
				return FALSE;
			}
			
			$user = \CI::$APP->doctrine->em->find('user\models\User',$user_id);

			if(!$user)
				return FALSE;
			
			self::$user =& $user;
			
		}

		return self::$user;
	}

	public static function login($email, $password) {
		
		$CI =& get_instance();
		
		$query = $CI->doctrine->em->createQuery("SELECT u FROM user\models\User u WHERE u.email = :email");
		$query->setParameter(':email', $email);
		$user = $query->getResult();
		
		
		if($user)
		{
			$user = $user[0];
			
			if($user->isActive() == FALSE){
				return FALSE;
			}

            $CI->load->library('password');
            if( $CI->password->validate_password($password, $user->getSalt(),$user->getSecrete()) )
			{
				$CI->load->library('session');
				$CI->session->set_userdata('user_id',$user->id());
				self::$user = $user;
				
				//call the post_user_login hook
				\Events::trigger('post_user_login',self::$user);
				
				return TRUE;
				
			}/**/
		}
		
		return FALSE;

	}
	
	public function switchto($user_id) {
			
			$CI =& get_instance();
			$CI->load->library('session');
			
			if (is_numeric($main_user = $CI->session->userdata('main_user')) and $main_user > 0) return FALSE;
			
			$CI->session->set_userdata('main_user', self::$user->id());
			$CI->session->set_userdata('user_id', $user_id);
			if ($CI->session->userdata('user_id') == $user_id) return TRUE;
			else return FALSE;
	}
	
	public static function can($seek_permission) {
		
		$CI =& get_instance();
		$CI->load->library('session');
		
		if (!$user_id = $CI->session->userdata('user_id')) {
			return FALSE;
		}
		
		$given_permissions = array();
		
		$group = self::user()->getGroup();
		
		foreach($group->getPermissions() as $rp){
			$p = strtolower(trim($rp->getName()));
			$given_permissions[$p] = TRUE;
		}
		
		if (is_array($seek_permission)) {
			
			foreach ($seek_permission as $p) {
				
				if (isset($given_permissions[strtolower(trim($p))])) return TRUE;	
				
			}	
			
		} else {
			
			if (isset($given_permissions[strtolower(trim($seek_permission))])) return TRUE;		
			
		}
		
		return FALSE;
		
				
	}

	public function isAlreadyLogged($user_id=NULL){
		
		if (!isset($user_id)) $user_id = self::user()->id();
		
		$CI =& get_instance();
		$CI->load->library('session');
	
		$CI->load->database();
	
		$sesstimeout = time() - $CI->config->item('sess_time_to_update');
		$sessID = $CI->session->userdata('session_id');
		$delim_str = 's:7:"user_id";i:'.$user_id.';';
		$ip = $CI->input->ip_address();
		$ua = $CI->input->user_agent();
		
		$query = $this->db->query(
					" SELECT session_id, ip_address, user_agent FROM ys_sessions
					WHERE last_activity > $sesstimeout 
					AND user_data LIKE '%".$delim_str."%' 
					AND session_id != '$sessID' " 
				);
		
		if ($query->num_rows() > 0) {
			
			$result = $query->row_array();
			
			if ($result['ip_address'] == $ip && $result['user_agent'] == trim(substr($ua, 0, 50))) {
				
				$this->db->where('session_id', $result['session_id']);
				$this->db->update($CI->config->item('sess_table_name'), array('user_data' => ''));
				return FALSE;
			}
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	public function setUser($user)
	{
		self::$user = $user;
	}
	
	public static function isSuperUser(){
	
		return (self::user() and self::user()->getGroup()->id() ==  Group::SUPER_ADMIN);
		
	}
	
	public static function isSubagentUser(){
		if (self::user()->id() == 1 ||  self::user()->getGroup()->id() ==  Group::SUPER_ADMIN || self::user()->getGroup()->id() == Group::SUPER_AGENT_ADMIN)
			return FALSE;
		if (self::user()->getAgent()->getParentAgent()) 
			return TRUE;
		else 
			return FALSE;
		
	}

	/**
	 * checks if currentUser can access targetUser based on group herirarchy and user level 
	 * @param $targetUser | userID or userObject
	 * @return bool 
	 */
	public static function canActOn($targetUser = NULL){
		
		if (! $targetUser or ! self::$user) return FALSE;
		
		if ( is_numeric($targetUser) ) {
			$targetUser = CI::$APP->doctrine->em->find('user\models\User', $targetUser);
		}
		
		if (! is_object($targetUser)) return FALSE;
		
		$targetUserGroup  = $targetUser->getGroup()->id();
		$currentUserGroup = self::$user->getGroup()->id();

		if ($currentUserGroup == Group::SUPER_ADMIN) return TRUE;
		if ($targetUserGroup  == Group::SUPER_ADMIN) return FALSE;
		
		return FALSE;
	}
	
	public static function isPrivilegedUser(){
		return ( self::isSuperUser());
	}
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	public static function setRemitDestCountry(\location\models\Country $country)
	{
		self::$remitCountry = $country;
	}
	
	public static function getRemitDestCountry()
	{
		return self::$remitCountry;
	}

	public static function getAgents($user = NULL){
		
		if (!isset(self::$user)) return FALSE;
		
		$uid = self::$user->id(); 

		$agent = \CI::$APP->db->query(
"SELECT a.id as agent_id, a.parentAgent_id FROM ys_agents a JOIN ys_users u on u.agent_id = a.id WHERE u.id = '{$uid}'"
					)->row();
		
		return $agent;
		
	}
	
	public static function accessibleLevels()
	{
		$userLevels = array( // the array keys should NOT be changed to prevent repercussions
				'PA' => User::USERLEVEL_PA,
				'SA' => User::USERLEVEL_SA,
		);
		
		$userLevel = self::user()->getLevel();
		if($userLevel == User::USERLEVEL_SA)
		{
			unset($userLevels['PA']);
		}
		
		return $userLevels;
	}
	
}
