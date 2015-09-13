<?php use models\Customer;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class OnlineCustomer_Controller extends MY_Controller
{
	
	var $t_data = array();
	
	var $current_customer = NULL;
	
	var $current_agent = NULL;
	
	var $current_user = NULL;
	
	var $is_current_customer_active = FALSE;
	
	public function __construct()
	{
		
		parent::__construct();
		
		if( $this->session->userdata('user_id') or \Current_User::user() ) redirect('dashboard');
					
		if( !$this->session->userdata('customer_id') or ! \Current_Customer::customer() ) redirect('web/auth/login');
		
		$this->load->library('breadcrumb');
		
		$this->load->helper(array('web/online'));
		
		$this->t_data['isLoggedIn'] = (Current_Customer::customer())? TRUE : FALSE;
		
		$this->t_data['flashdata'] = $this->session->flashdata('feedback');
		
		$_critical_messages = $this->message->get('alert','critical');
		
		if(count($_critical_messages) > 0 ){
			$this->t_data['critical_alerts'] = $_critical_messages;
		}
		
		$_feedbacks = $this->message->get(FALSE,'feedback');
		if(count($_feedbacks) > 0){
			$this->t_data['feedback'] = $_feedbacks;
		}
		
		$this->load->helper('content/menu');
		
		$currentCustomer = \Current_Customer::customer();
		
		$this->current_customer = $currentCustomer;
		
		$this->current_agent = getOnlineAgent();
		
		$this->current_user = getOnlineAgentUser();
		
		$this->is_current_customer_active = ( $this->current_customer->getStatus() !== Customer::CUSTOMER_STATUS_ACTIVE )? FALSE : TRUE;
		
		$this->t_data['currentCustomer'] = $this->current_customer;
		
		$this->t_data['currentAgent'] = $this->current_agent;
		
		$this->t_data['currentUser'] = $this->current_user;
		
		$this->t_data['isCustomerActive'] = $this->is_current_customer_active;
		
		$customerCurrency = $currentCustomer->getCountry()->getCurrency();
		
		$customerLedger = $currentCustomer->getLedger();
		
		$customerCurrentBalance = $customerLedger->getBalance();
		
		$this->t_data['customer'] = $currentCustomer;
		
		$this->t_data['customer_current_balance'] = $customerCurrentBalance;
		
		$this->t_data['customer_currency_code'] = $customerCurrency->getIsoCode();
		
	}
	
	
}