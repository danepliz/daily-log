<?php
class Template_Controller extends MY_Controller
{
	public function get($template_name, $agent = NULL){
		
		$user = Current_User::user();
		$agent = ( is_null($agent) )? $user->getAgent() : $this->doctrine->em->find('models\Agent', $agent);
		$country = $agent->getCountry();
		
		if(file_exists(theme_path().'common/xhrtemplates/'.$template_name.".php")){
			$this->load->helper('country/country');
			
			$data = array(	'country'	=>	$country->getName(), 'country_id' => $country->id());
			
			$sRepo = $this->doctrine->em->getRepository('models\Common\State');
			$states = $sRepo->getStates($country->id());
			
			$data['states'] = array();
			
			if(count($states)){
			
				foreach($states as $s){
					$data['states'][$s->id()] = $s->getName();
				}
			}
			
			$data['agent'] = $agent;
			
			$this->load->theme('common/xhrtemplates/'.$template_name, $data);
		}
			
		else show_404();
	}
}