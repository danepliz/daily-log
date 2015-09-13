<?php


class Settings_Controller extends Admin_Controller{

	public function __construct(){
		parent::__construct();


		$this->breadcrumb->append_crumb('Settings', site_url('config/settings'));
	}


	public function index(){

		$this->load->helper(['location/country','currency/currency']);
//		$lrepo = $this->doctrine->em->getRepository('models\Ledger');
//		$ledgers = $lrepo->findAll();

		if ($this->input->post()) {
            if($_FILES['config_stamp']['name'] != '') {
                $config['upload_path'] = STAMP_UPLOAD_PATH;
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']	= '1024';
                $config['max_width'] = '700';
                $config['max_height'] = '200';
                $config['file_name'] = 'stamp';

                $this->load->library('upload', $config);

				$currencyRepo = $this->doctrine->em->getRepository('currency\models\Currency');
				$currencies = $currencyRepo->findBy(
					[], //condition
					array('name' =>'ASC')
				);

                if (!$this->upload->do_upload('config_stamp'))
                {
                    $this->message->set($this->upload->display_errors(), 'danger', TRUE,'feedback');
                    redirect('config/settings');
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $file_name = base_url().ltrim(STAMP_UPLOAD_PATH, './').$upload_data['file_name'];
                    \Options::update('config_stamp', $file_name);
                }

            }

            foreach($this->input->post() as $k => $v){
                \Options::update($k,$v);
            }

			$this->_loginfo();

			$this->message->set('Settings updated successfully.', 'success', TRUE, 'feedback');
			redirect('config/settings');

		}
		$this->templatedata['maincontent'] = 'config/settings/list';
		$this->load->theme('master',$this->templatedata);
	}


	/**
	 * Logs info messages
	 * Intended for boolean types of events [1 = forced , 0 = cancelled]
	 *
	 */
	public function _loginfo(){

		$loggables = array(	//  'post name' 		=> 'log msg label'
								'isTxnHalt' 		=> 'Entire Trasaction Halt',
								'site_maintenance'  => 'Site Maintenance',
								'config_aml_enabled'=> 'AML (AntiMoney Laundering) verification',
								'use_ctbs'			=> 'Integration with CTBS (Central TransBorder System)',
							);

		foreach ($loggables as $post => $label) {

			if (isset($_POST[$post])) {
				($_POST[$post]) ?	log_message('info', $label.' forced.') : log_message('info', $label.' cancelled.');

			}
		}

	}

	public function webagents()
	{
		if( $this->input->post() )
		{
			$webAgents = array();

			foreach( $this->input->post('web-agent') as $country => $agent )
			{
				if( $agent != "" )
				{
					$webAgents[] = array($country,$agent);
				}
			}

			if( count( $webAgents ) > 0 )
			{
				\Options::update('web_agents', serialize($webAgents) );

				$this->message->set('Settings updated successfully.', 'success', TRUE, 'feedback');
			}
			else
			{
				$this->message->set('Please Choose web agents for particular country.', 'error', TRUE, 'feedback');
			}
		}

		redirect('config/settings/#webagent');
	}

}