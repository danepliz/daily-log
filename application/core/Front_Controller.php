<?php use models\Customer;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Front_Controller extends MY_Controller
{
	
	var $t_data = array();
	
	var $current_customer = NULL;
	
	var $is_current_customer_active = FALSE;
	
	public function __construct()
	{
		
		parent::__construct();
		
// 		if( \Current_Customer::customer() ) redirect('web');
		
		if( \Current_User::user() ) redirect('dashboard');
		
		$customerCurrentBalance = '0.00';
		
		$customerCurrencyCode = 'USD';
		
		$this->load->library('breadcrumb');
		
		$this->breadcrumb->append_crumb('Home', site_url());
		
		$this->load->helper('content/menu');
		
		$this->current_customer = \Current_Customer::customer();
		
		if( $this->current_customer )
		{
			$this->is_current_customer_active = ( $this->current_customer->getStatus() !== Customer::CUSTOMER_STATUS_ACTIVE )? FALSE : TRUE;
			
			$customerCurrency = $this->current_customer->getCountry()->getCurrency();
			
			$customerLedger = $this->current_customer->getLedger();
			
			$customerCurrentBalance = $customerLedger->getBalance();
			
			$customerCurrencyCode = $customerCurrency->getIsoCode();
			
		}
		
		$this->t_data['currentCustomer'] = $this->current_customer;
			
		$this->t_data['isCustomerActive'] = $this->is_current_customer_active;
		
		$this->t_data['customer'] = $this->current_customer;
			
		$this->t_data['customer_current_balance'] = $customerCurrentBalance;
			
		$this->t_data['customer_currency_code'] = $customerCurrencyCode;
	}
	
	
}