<?php

class Current_Customer
{
	
	/**
	 * @var models\Customer
	 */
	private static $customer;
	
	
	public static function customer()
	{
		if( !isset( self::$customer ) )
		{
			$CI = &CI::get_instance();
			
			$CI->load->library('session');
			
			if( ! $CI->session->userdata('customer_id') ) return FALSE;
			
			$customer_id = $CI->session->userdata('customer_id');
			
			$customer = \CI::$APP->doctrine->em->find('models\Customer', $customer_id );
			
			if( ! $customer ) return FALSE;
			
			self::$customer = $customer;
		}
		
		return self::$customer;
	}
	
	public static function login( $username, $password )
	{
		$CI =& get_instance();
		
		$query = $CI->doctrine->em->createQuery("SELECT c FROM models\Customer c LEFT JOIN c.emails ce WHERE ce.email = '$username' AND ce.isPrimary = 1");
		
		$customer = $query->getResult();
		
		if($customer)
		{
			$customer = $customer[0];
									
			
			
			if( $customer->isActive() == FALSE )
			{ 
				$CI->message->set("Please activate your account first.", 'error',TRUE,'feedback');
 				redirect('web/auth/login');
			}
								
			if( $customer->getPassword() == md5($password) )
			{
				$CI->load->library('session');
				
				$tokenid = $CI->session->userdata('tokenid');
				if($tokenid && $tokenid!='')
				{
					$OntempRepo  = $CI->doctrine->em->getRepository('models\Transaction\OnlineTemporaryTransaction');
					$onlineTempTrans  = $OntempRepo->findOneBy(array('tokenId'=>$tokenid));
					if($onlineTempTrans)
					{
						$onlineTempTrans->setCustomerId($customer);
						$CI->doctrine->em->persist($onlineTempTrans);
						$CI->doctrine->em->flush();
					}
				}
				
				$CI->session->sess_destroy();
				
				$CI->session->set_userdata('customer_id',$customer->id());
				
				self::$customer = $customer;
				
				// Set last log in for first time
				if($customer->getLast_logged_in() == '')
				{
					$customer->setLast_logged_in( new \DateTime() );
					$CI->doctrine->em->persist($customer);
					$CI->doctrine->em->flush();
				}
// 				\Events::trigger('post_customer_login', array('customer' => self::$customer) );
				
				return TRUE;
			}
		}
		return FALSE;
	}
	
	
}