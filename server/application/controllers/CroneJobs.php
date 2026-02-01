<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'/third_party/braintree-php-3.36.0/lib/autoload.php');
class CroneJobs extends CI_Controller {
	 public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email','pagination'));
		$this->load->model(array('common_model','home_model','mail_model'));
	}
	
	//This is a test comment to check the pipeline
	//Comment made on 16-09-2020
	public function index(){

		$msg= '<!doctype html>
				<html>
				<head>
				<meta charset="utf-8">
				<title>kitshare</title>
				</head>

				<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">
					zxczxcxzcxz	

scxzczxcxzcxzc

				</body>
				</html>
				';

		   $mail_body = $msg; 
			
		$to= 'sanidha@gmail.com';
		$subject = "test mail";		
		$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia ",
											),
										"To"=>array(
												array("Email"=>$to,
												"Name"=>"",
												),
											),
										"Subject"=> $subject,
				                        "TextPart"=> "",
				                        "HTMLPart"=> $mail_body
									),
								)
							);	
							

		//$this->common_model->sendMail($mail_data);
		
		$insert_data=array("name"=>"abc");
		
		$this->common_model->InsertData('tbl_test',$insert_data);
	
	
	}
	
	public function SameDayExpiryCrone()
	{
	
		$where = "is_payment_completed='Y' AND is_rent_approved='N' AND order_status='1' AND DATE(gear_rent_requested_on)='".date('Y-m-d')."' AND DATE(gear_rent_request_from_date)='".date('Y-m-d')."' AND DATE(gear_rent_request_to_date)='".date('Y-m-d')."'";
		
	
		//$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y' ,'is_rent_approved'=>'N' ,'order_status'=> '1'));
		
		//$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',$where);
		
		//$order_list = $query->result();
		
		$sql = "SELECT * FROM `ks_user_gear_rent_details` WHERE ".$where." GROUP BY `order_id`";
		$order_list = $this->common_model->get_records_from_sql($sql);
		
		$today_date = date('Y-m-d');
		
		foreach ($order_list as  $value) {
			$time =  date('H:i:s' ,strtotime($value->gear_rent_requested_on));
			
			$requested_date  = date('Y-m-d' ,strtotime($value->gear_rent_requested_on));
			$today_date1 = date('Y-m-d');
			
			if ($requested_date == $today_date1) {
			 	  $today_date = date('Y-m-d H:i:s');
			 	 
			 		$hourdiff = round((strtotime(  $today_date ) - strtotime(date('Y-m-d H:i:s' ,strtotime($value->gear_rent_requested_on)) ))/3600, 1);
					
			 	 	if ($hourdiff >= 2 &&  $hourdiff <= 3 ) {
									    
				  		$update_data = array(
					 							'order_status'=>'8'
					 						);
						$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'order_id', $value->order_id);
						$insert_data  = array('type'=> 'Expired Crone Same day' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'Expired');
		  				$this->common_model->InsertData('ks_crone_log',$insert_data);
		  				$this->ExpiredEmail($value->order_id);
					 }
			}
		}	
			
	}
	
	public function NextDayExpiryCrone()
	{
		//Previous day's records are fetched
		$previous_day = date("Y-m-d", strtotime("-1 days", strtotime(date('Y-m-d'))));
		$today_date = date('Y-m-d') ;
		
		$where_array = "is_payment_completed='Y' AND is_rent_approved='N' AND order_status='1' AND DATE(gear_rent_requested_on)='".$previous_day."' AND DATE(gear_rent_request_from_date)='".$today_date."'";
	
		//$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',$where_array);
		//$order_list = $query->result();
		
		$sql = "SELECT * FROM `ks_user_gear_rent_details` WHERE ".$where_array." GROUP BY `order_id`";
		$order_list = $this->common_model->get_records_from_sql($sql);
		
		//$next_day = date("Y-m-d", strtotime("-1 days", strtotime(date('Y-m-d'))));
		
		foreach ($order_list as  $value) {
			//if(date('Y-m-d' ,strtotime($value->gear_rent_requested_on)) <= $next_day){
				   //$today_date = date('Y-m-d H:i:s');
				   // $today_date = date('2019-07-23 05:24:00');
				     //$hourdiff = round((strtotime(  $today_date ) - strtotime(date('Y-m-d H:i:s' ,strtotime($value->gear_rent_requested_on)) ))/3600, 1);
				   // die;
					//if ($hourdiff > 12 &&  $hourdiff <= 24  ) {
				    
							//IF the Security Deposit payment is authorised then we have to void that transaction
							$where_clause = array('gear_order_id'=> $value->order_id, 'payment_type'=> 'Deposite Payment' );
							$query =  $this->common_model->GetAllWhere('ks_user_gear_payments', $where_clause);						
							$gear_payment =  $query->row();
							if (!empty($gear_payment->transaction_id)) {
								if (version_compare(PHP_VERSION, '5.4.0', '<')) {
								    throw new Braintree_Exception('PHP version >= 5.4.0 required');
								}

									$query =  $this->common_model->GetAllWhere('ks_settings',array() );
									$settings = $query->row();		
						
								// Instantiate a Braintree Gateway either like this:
								
								$gateway = new Braintree_Gateway([
		      									'environment' => BRAINTREE_ENVIORMENT,
											    'merchantId' => $settings->braintree_merchant_id,
											    'publicKey' => $settings->braintree_public_key,
											    'privateKey' => $settings->braintree_private_key
								]);

								$result = $gateway->transaction()->void($gear_payment->transaction_id);
								
								$update_cart  = array( 
					 							'status'=>'Void',
					 							'update_date'=> date('Y-m-d'),
					 							); 
								$where = array(
												'gear_order_id'=>$value->order_id,
												'payment_type'=>'Deposite Payment'
											);
								
								$this->db->where($where);
								$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
								
								
								$insert_cron= array('type' => 'trancation void',
												'date_time' => date('Y-m-d H:i:m'),
												'order_id' => $value->order_id,
												'status' => 'Void',
											);

								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
							}
							
							//IF the Payment is authorised then we have to void that transaction
							$where_clause = array('gear_order_id'=> $value->order_id, 'payment_type'=> 'Gear Payment' );
							$query =  $this->common_model->GetAllWhere('ks_user_gear_payments', $where_clause);						
							$gear_payment =  $query->row();
							if (!empty($gear_payment->transaction_id)) {
								if (version_compare(PHP_VERSION, '5.4.0', '<')) {
								    throw new Braintree_Exception('PHP version >= 5.4.0 required');
								}

									$query =  $this->common_model->GetAllWhere('ks_settings',array() );
									$settings = $query->row();		
						
								// Instantiate a Braintree Gateway either like this:
								
								$gateway = new Braintree_Gateway([
		      									'environment' => BRAINTREE_ENVIORMENT,
											    'merchantId' => $settings->braintree_merchant_id,
											    'publicKey' => $settings->braintree_public_key,
											    'privateKey' => $settings->braintree_private_key
								]);

								$result = $gateway->transaction()->void($gear_payment->transaction_id);
								
								$update_cart  = array( 
					 							'status'=>'Void',
					 							'update_date'=> date('Y-m-d'),
					 							); 
								$where = array(
												'gear_order_id'=>$value->order_id,
												'payment_type'=>'Gear Payment'
											);
								
								$this->db->where($where);
								$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
								
								
								$insert_cron= array('type' => 'trancation void',
												'date_time' => date('Y-m-d H:i:m'),
												'order_id' => $value->order_id,
												'status' => 'Void',
											);

								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
							}

											
					
					
				 		$update_data = array(
					 							'order_status'=>'8'
					 						);
						$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'order_id', $value->order_id);
						
						$insert_data  = array('type'=> 'Expired Crone next day ' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'Expired');
		  				$this->common_model->InsertData('ks_crone_log',$insert_data);
		  				$this->ExpiredEmail($value->order_id);
					//}	
			 //}	

		}	
			
	}
	
	public function ExpiryCron()
	{
		/*$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y' ,'is_rent_approved'=>'N' ,'order_status'=> '1'));
		$order_list = $query->result();*/
		
		
		$sql = "SELECT * FROM `ks_user_gear_rent_details` WHERE `is_payment_completed`='Y' AND `is_rent_approved`='N' AND `order_status`='1' GROUP BY `order_id`";
		$order_list = $this->common_model->get_records_from_sql($sql);
				
		$today_date = date('Y-m-d') ;
		$next_day = date("Y-m-d", strtotime("-1 days", strtotime(date('Y-m-d'))));
		
		foreach ($order_list as  $value) {
			if(date('Y-m-d' ,strtotime($value->gear_rent_requested_on)) <= $next_day){
				   $today_date = date('Y-m-d H:i:s');
				    $hourdiff = round((strtotime(  $today_date ) - strtotime(date('Y-m-d H:i:s' ,strtotime($value->gear_rent_requested_on)) ))/3600, 1);
					if ($hourdiff > 24  ) {
				    
				 		$update_data = array(
					 							'order_status'=>'8'
					 						);
						$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'order_id', $value->order_id);
						$insert_data  = array('type'=> 'Expired Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'Expired');
		  				$this->common_model->InsertData('ks_crone_log',$insert_data);
		  				$this->ExpiredEmail($value->order_id);
					 }	
			 }	

		}	
			
	}
	// public function UpdateOrderExpiredStatus()
	// {
	// 	$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y' ,'is_rent_approved'=>'N' ,'order_status'=> '1'));
	// 	$order_list = $query->result();
			
	//  $two_days_backdays = date("Y-m-d", strtotime("-2 days", strtotime(date('Y-m-d'))));
	// 	$today_date = date('Y-m-d') ;
	// 	echo "<pre>";
	// 	die;
	// 	foreach ($order_list as  $value) {
		    
	// 		if ( date('Y-m-d' ,strtotime($value->gear_rent_start_date)) < $two_days_backdays) {
			  
	// 				$update_data = array(
	// 		  	 							'order_status'=>'8'
	// 		  	 						);
	// 		  		$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value->user_gear_rent_detail_id);
	// 		  		$insert_data  = array('type'=> 'Expired Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'Expired');
	// 	  			$this->common_model->InsertData('ks_crone_log',$insert_data);
	// 	  			$this->ExpiredEmail($value->order_id);

	// 		}elseif(date('Y-m-d' ,strtotime($value->gear_rent_start_date)) == $today_date){
           
	// 			   $today_date = date('Y-m-d h:i:s');
	// 			   echo $hourdiff = round((strtotime(  $today_date ) - strtotime(date('Y-m-d h:i:s' ,strtotime($value->gear_rent_requested_on)) ))/3600, 1);
	// 			  	die;
	// 			 if ($hourdiff > 1 &&  $hourdiff < 24  ) {
				    
				     
	// 			 		$update_data = array(
	// 				 							'order_status'=>'8'
	// 				 						);
	// 					$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value->user_gear_rent_detail_id);
	// 					$insert_data  = array('type'=> 'Expired Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'Expired');
	// 	  				$this->common_model->InsertData('ks_crone_log',$insert_data);
	// 	  				$this->ExpiredEmail($value->order_id);
	// 			 }

	// 		}elseif(date('Y-m-d' ,strtotime($value->gear_rent_start_date)) > $two_days_backdays &&  date('Y-m-d' ,strtotime($value->gear_rent_start_date)) < $today_date){
						
	// 			  $today_date = date('Y-m-d h:i:s');
	// 			  $hourdiff = round((strtotime(  $today_date ) - strtotime(date('Y-m-d h:00:00' ,strtotime($value->gear_rent_start_date)) ))/3600, 1);
	// 			  if ($hourdiff > 12  ) {
	// 				 		$update_data = array(
	// 					 							'order_status'=>'8'
	// 					 						);
	// 						$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value->user_gear_rent_detail_id);
	// 						$insert_data  = array('type'=> 'Expired Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'Expired');
	// 	  					$this->common_model->InsertData('ks_crone_log',$insert_data);
	// 	  					$this->ExpiredEmail($value->order_id);
	// 			  }
	// 		}

	// 	}


	// }

	//Expiry Mail 
	public function ExpiredEmail($order_id)
	{
		$this->SendOwnerExpiredMail($order_id);
		$this->SendRenterExpiryMail($order_id);
	}

	//send Owner Expiry Mail 


	public function SendOwnerExpiredMail($order_id)
	{
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);

		
		if (empty($cart_details)) {
				$response['status'] = 200;
				$response['status_message'] = ' Order is present ';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
		}
		if ($cart_details[0]['owner_show_business_name'] == 'y') {
			 $cart_details[0]['app_user_first_name']  = $cart_details[0]['owner_bussiness_name'];
			 $cart_details[0]['app_user_last_name']  = '';
		}
		if ($cart_details[0]['renter_show_business_name'] == 'y') {
			 $cart_details[0]['renter_firstname']  = $cart_details[0]['renter_bussiness_name'];
			 $cart_details[0]['renter_lastname']  = '';
		}
		$this->db->select('*');
		$this->db->from('ks_gear_order_location ');
		$this->db->where('user_address_id',$cart_details[0]['user_address_id']);
		$this->db->where('order_id',$order_id);
		$query = $this->db->get();
		$address  =$query->row();	
		if (!empty($address)) {
			$suburb_name = $address->suburb_name ;
		}else{
			$suburb_name = '';
		}				
		// echo "<pre>";
		// print_r($cart_details);die;
		
		$msg= '<!doctype html>
				<html>
				<head>
				<meta charset="utf-8">
				<title>kitshare</title>
				</head>

				<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">

				<div class="wrapper" style="border:0px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">

				</div>


				<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="    margin: 0px auto;
				    background-color:#095cab;
				    padding: 10px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
				</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
				<tr>
				<td style="font-size:20px; padding-bottom:10px;">
				Hi '.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].' , </td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">Because you didn&rsquo;t accept or decline   '.$cart_details[0]['renter_firstname'].'  reservation request within 24 hours, it has expired. Renters need Owners to make booking decisions quickly, so if you consistently don&rsquo;t respond with 24 hours your listing&rsquo;s search placement may be negatively impacted and we may temporarily turn off your listing.</td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">If you still want to book, ask '.$cart_details[0]['renter_firstname'].' to submit a new reservation request.</td>

				</tr>
				
				<td style="font-size:18px; padding-bottom:10px;">Regards,
				<p>The Kitshare Team</p>

				</td>

				</tr>

				</table>

				<table width="940" style="    margin: 0px auto;
				    background-color:#ddd;
				    padding: 5px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
				</tr>
				</table>
				</div>

				</body>
				</html>
				';

		    $mail_body = $msg; 
			// die;	
		// $to= "singhaniagourav@gmail.com";
		$to= $cart_details[0]['primary_email_address'];
		$subject = "A Rental Request has expired";		
		$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia ",
											),
										"To"=>array(
												array("Email"=>$to,
												"Name"=>"",
												),
											),
										"Subject"=> $subject,
				                        "TextPart"=> "",
				                        "HTMLPart"=> $mail_body
									),
								)
							);	
		$this->common_model->sendMail($mail_data);
		
		$insert_array = array("type"=>"A Rental Request has expired",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"A Rental Request has expired");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
	}

	//send Renter Expiry Mail 

	public function SendRenterExpiryMail($order_id)
	{
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		
		if (empty($cart_details)) {
				$response['status'] = 200;
				$response['status_message'] = ' Order is present ';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
		}
		if ($cart_details[0]['owner_show_business_name'] == 'y') {
			 $cart_details[0]['app_user_first_name']  = $cart_details[0]['owner_bussiness_name'];
			 $cart_details[0]['app_user_last_name']  = '';
		}
		if ($cart_details[0]['renter_show_business_name'] == 'y') {
			 $cart_details[0]['renter_firstname']  = $cart_details[0]['renter_bussiness_name'];
			 $cart_details[0]['renter_lastname']  = '';
		}
		$this->db->select('*');
		$this->db->from('ks_gear_order_location ');
		$this->db->where('user_address_id',$cart_details[0]['user_address_id']);
		$this->db->where('order_id',$order_id);
		$query = $this->db->get();
		$address  =$query->row();	
		if (!empty($address)) {
			$suburb_name = $address->suburb_name ;
		}else{
			$suburb_name = '';
		}				
		
		$msg= '<!doctype html>
				<html>
				<head>
				<meta charset="utf-8">
				<title>kitshare</title>
				</head>

				<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">

				<div class="wrapper" style="border:0px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">

				</div>


				<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="    margin: 0px auto;
				    background-color:#095cab;
				    padding: 10px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
				</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
				<tr>
				<td style="font-size:20px; padding-bottom:10px;">
				Hi '.$cart_details[0]['renter_firstname'].'  </td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">  '.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].' hasn&rsquo;t responded to your request, but we&rsquo;re here to help.</td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">We&rsquo;ve cancelled your request and you weren&rsquo;t charged for it. To make sure you still have a great shoot, let&rsquo;s find you new kit in  '.$suburb_name.'</td>

				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">Here&rsquo;s a reminder of your shoot details: </td>

				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;"> <b>'.date('Y-m-d' ,strtotime($cart_details[0]['gear_rent_request_from_date'])).'-' .date('Y-m-d' ,strtotime($cart_details[0]['gear_rent_request_to_date'])).'</b></td>

				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;"><b>'.$suburb_name.'</b></td>

				</tr>
				
				
				<td style="font-size:18px; padding-bottom:10px;">Regards,
				<p>The Kitshare Team</p>

				</td>

				</tr>
				<tr>
				<td><a href="'.$web_url.'search"> <button  style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">Browse other kit</button> </a></td>
				</tr>
				</table>

				<table width="940" style="margin: 0px auto;
				    background-color:#ddd;
				    padding: 5px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
				</tr>
				
				</table>
				</div>

				</body>
				</html>
				';

		 $mail_body = $msg; 
		// die;
		// $to= "singhaniagourav@gmail.com";
		$to= $cart_details[0]['renter_email'];
		$subject = "Your rental request has expired";		
		$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia ",
											),
										"To"=>array(
												array("Email"=>$to,
												"Name"=>"",
												),
											),
										"Subject"=> $subject,
				                        "TextPart"=> "",
				                        "HTMLPart"=> $mail_body
									),
								)
							);	
		$this->common_model->sendMail($mail_data);
		
		$insert_array = array("type"=>"Your rental request has expired",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Your rental request has expired");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
		
	}


	//Different Order Status Values - 1,2,3,4,5,6,7,8
	//For ORDER STATUS = 1 denotes "Payment Stored/Quote"
	//For ORDER STATUS = 2 denotes "Reservation"
	//For ORDER STATUS = 3 denotes "Contract"
	//For ORDER STATUS = 4 denotes "Completed"
	//For ORDER STATUS = 5 denotes "Cancelled"
	//For ORDER STATUS = 6 denotes "Order Declined / Rejected"
	//For ORDER STATUS = 7 denotes "Archived"
	//For ORDER STATUS = 8 denotes "Expired"	
	

	// Mark  order as contract on same day of rent
	public function UpdateOrderContractStatus()
	{
		$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y' ,'is_rent_approved'=>'Y' ,'order_status' => '2' ));
		$order_list = $query->result();
		
		$today_date = date('Y-m-d');
    

		foreach ($order_list as  $value) {
			  if ( date('Y-m-d' ,strtotime($value->gear_rent_start_date)) == $today_date ) {
			  		
					$authflag = $this->Authorizetrascation('1');
					
					if($authflag == 1){
						
						$dep_flag = $this->DepositeAuthorizetrascation('1');
						
						
                        $flag = $this->SettleBraintreeOrderstranscation('1');

                        if($flag == 1){
                                $update_data = array(
                                                        'order_status'=>'3'
                                                    );
                                $this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value->user_gear_rent_detail_id);
                                $insert_data  = array('type'=> 'Contract Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'Contract');
                                $this->common_model->InsertData('ks_crone_log',$insert_data);
                         }
						
					}
			  }
		}
	}

	// Mark archived order more than 6 month old
	public function UpdateOrderArchivedStatus()
	{
		$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y'));
		$order_list = $query->result();
		die;
		//print_r($order_list);
		$back_date = date("Y-m-d", strtotime("-6 days", strtotime(date('Y-m-d'))));
		
		foreach ($order_list as  $value) {
			
			  if ( date('Y-m-d' ,strtotime($value->gear_rent_start_date)) < $back_date ) {

			  	 	$update_data = array(
			  	 							'order_status'=>'7'
			  	 						);
			  		$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value->user_gear_rent_detail_id);
			  		$insert_data  = array('type'=> 'archived Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'7');
		  			$this->common_model->InsertData('ks_crone_log',$insert_data);
			  }else{
			  	echo "bye";
			  }

		}
	}

	public function DepositeAuthorizetrascation($return="")
	{
		$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y','order_status'=>'2', 'gear_rent_request_from_date >='=> date('Y-m-d'), 'security_deposite_token_braintree !=' =>''));
		
		$order_list = $query->result();

		if (!empty($order_list)) {
			 foreach ($order_list  As $orders) {
			 	$today_date = date('Y-m-d H:i:s');
			 	$order_date =  $orders->gear_rent_start_date;
				$hourdiff = round((strtotime($order_date) - strtotime($today_date))/3600, 1);
			 	$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$orders->order_id,'payment_type'=>'Deposite Payment','status'=>'STORED'));
								
				if ($hourdiff <= 48 && $query->num_rows()>0) {
				
					$order_payments = $query->row();
					
					if ($order_payments->transaction_id=='') {
						$query =  $this->common_model->GetAllWhere('ks_settings',array() );
						$settings = $query->row();		
						$gateway = new Braintree_Gateway([
      									'environment' => BRAINTREE_ENVIORMENT,
									    'merchantId' => $settings->braintree_merchant_id,
									    'publicKey' => $settings->braintree_public_key,
									    'privateKey' => $settings->braintree_private_key
						]);

					
						$result = $gateway->paymentMethodNonce()->create($orders->security_deposite_token_braintree);
						$result = $gateway->transaction()->sale([
							    							'amount' =>number_format((float) $order_payments->transaction_amount , 2, '.', '') ,
											    			'paymentMethodNonce' => $result->paymentMethodNonce->nonce
														]);
						if ($result->transaction->processorResponseCode != 1000) {
								$query =  $this->common_model->GetAllWhere('tbl_braintree_error_details',array('error_code'=>$result->transaction->processorResponseCode));
								$data =  $query->row();
								
								
								//Record is inserted into the Transaction Error Log table
								$insert_error = array("order_id"=>$orders->order_id,
													  "processorResponseCode"=>$result->transaction->processorResponseCode,
													  "processorResponseMessage"=>$result->message,
													  "created_date"=>date("Y-m-d"),
													  "created_time"=>date("H:i:s"));
								
								$this->common_model->InsertData('tbl_transaction_error_log' ,$insert_error); 
								
								$insert_cron= array('type' => 'Authorize Payment',
									'date_time' => date('Y-m-d H:i:s'),
									'order_id' => $orders->order_id,
									'status' => $result->message,
									);


								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
								
								
								/*$response['status'] = 400;
								$response['status_message'] =  $data->message;
								$json_response = json_encode($response);
								echo $json_response;
								exit();*/
								if(empty($return)==false)
									return 0;
								else
									echo 0;
								
								
						}else {
							
							$update_Data = array(
														'transaction_id'=> $result->transaction->id,
														'status' => 'AUTHORISED'
													);
							$this->db->where('user_gear_payment_id', $order_payments->user_gear_payment_id);
							$this->db->update('ks_user_gear_payments', $update_Data); 
							
							$update_cart  = array( 
													'deposite_status' => 'AUTHORISED'
													); 
							$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","order_id",$orders->order_id);

							$insert_cron= array('type' => 'Authorize Depsoite Payment Crone',
							'date_time' => date('Y-m-d H:i:m'),
							'order_id' => $orders->order_id,
							'status' => 'Authorize Depsoite Payment',
							);

							$this->common_model->InsertData('ks_crone_log' ,$insert_cron); 
							
							if(empty($return)==false)
									return 1;
								else
									echo 1;
						}
					}else{
						
						$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$orders->order_id,'payment_type'=>'Deposite Payment','status'=>'AUTHORISED'));
						$order_payments = $query->row();
						
						if(!empty($order_payments)){
								
								if(empty($return)==false)
									return 1;
								else
									echo 1;
								
						}else{
							
							if(empty($return)==false)
								return 0;
							else
								echo 0;
						}
						
					}
				}	
			 }	
		 }	
	}

	//
	public function Authorizetrascation($return="")
	{
		 $query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y','order_status'=>'2', 'gear_rent_request_from_date >='=> date('Y-m-d')));
    
    	 $order_list = $query->result();
		 
		 if (!empty($order_list)) {
		 
			 foreach ($order_list  As $orders) {

			 	$today_date = date('Y-m-d H:i:s');
			 	$order_date =  $orders->gear_rent_start_date;
				$hourdiff = round((strtotime( $order_date) - strtotime($today_date))/3600, 1);

			 	$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$orders->order_id ,'payment_type'=>'Gear Payment' ,'status'=>'STORED'));
			 	
				$order_payments = $query->row();

				if ($hourdiff <= 48 ) {
						
					if (!empty($order_payments)) {
						if ($order_payments->transaction_id  =='') {
							// print_r($orders);
							// print_r($order_payments); die;
							if (version_compare(PHP_VERSION, '5.4.0', '<')) {
							    throw new Braintree_Exception('PHP version >= 5.4.0 required');
							}
					
							// Instantiate a Braintree Gateway either like this:
							$query =  $this->common_model->GetAllWhere('ks_settings',array() );
							$settings = $query->row();		
							
							// Instantiate a Braintree Gateway either like this:
							$gateway = new Braintree_Gateway([
	      									'environment' => BRAINTREE_ENVIORMENT,
										    'merchantId' => $settings->braintree_merchant_id,
										    'publicKey' => $settings->braintree_public_key,
										    'privateKey' => $settings->braintree_private_key
							]);

							$config = new Braintree_Configuration([
			      								'environment' => BRAINTREE_ENVIORMENT,
											    'merchantId' => $settings->braintree_merchant_id,
											    'publicKey' => $settings->braintree_public_key,
											    'privateKey' => $settings->braintree_private_key
							]);
							$result = $gateway->paymentMethodNonce()->create($orders->braintree_token);
							
							//$sql = "SELECT o_d.* ,g_tbl.gear_name,g_tbl.replacement_value_aud_inc_gst,g_tbl.replacement_value_aud_ex_gst ,g_tbl.per_day_cost_aud_ex_gst FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_order_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$orders->order_id."'"	;
							
							/*$sql = "SELECT total_rent_amount FROM `ks_user_gear_rent_details` WHERE `order_id` LIKE '".$orders->order_id."'";							
							$cart_details   = $this->common_model->get_records_from_sql($sql);
							$sum= 0;
							foreach ($cart_details as  $value) {
										$sum	+= $value->total_rent_amount;
							}*/
							
							$result = $gateway->transaction()->sale([
						    							'amount' =>number_format((float) $order_payments->transaction_amount , 2, '.', ''),
										    			'paymentMethodNonce' => $result->paymentMethodNonce->nonce
													]); 
							if ($result->transaction->processorResponseCode != 1000) {
								$query =  $this->common_model->GetAllWhere('tbl_braintree_error_details',array('error_code'=>$result->transaction->processorResponseCode));
								$data =  $query->row();
								
								//Record is inserted into the Transaction Error Log table
								$insert_error = array("order_id"=>$orders->order_id,
													  "processorResponseCode"=>$result->transaction->processorResponseCode,
													  "processorResponseMessage"=>$result->message,
													  "created_date"=>date("Y-m-d"),
													  "created_time"=>date("H:i:s"));
								
								$this->common_model->InsertData('tbl_transaction_error_log' ,$insert_error); 
								
								$insert_cron= array('type' => 'Authorize Payment',
									'date_time' => date('Y-m-d H:i:s'),
									'order_id' => $orders->order_id,
									'status' => $result->message,
									);


								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);								
								
								/*$response['status'] = 400;
								$response['status_message'] =  $data->message;
								$json_response = json_encode($response);
								echo $json_response;
								exit();*/
								if(empty($return)==false)
									return 0;
								else
									echo 0;
							}else{
							
								$insert_data  = array('type'=> 'Authorization Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$orders->order_id , 'status'=>'Authorizetrascation');
								$this->common_model->InsertData('ks_crone_log',$insert_data);
								$update_Data = array(
													
													'transaction_id'=> $result->transaction->id,
													'status'=>'AUTHORISED'
												);
								$this->db->where('user_gear_payment_id', $order_payments->user_gear_payment_id);
								$this->db->update('ks_user_gear_payments', $update_Data); 
								if(empty($return)==false)
									return 1;
								else
									echo 1;
							}

						}else{
							
							//Checked whether this order has already been authorized or not
							$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$orders->order_id ,'payment_type'=>'Gear Payment' ,'status'=>'AUTHORISED'));			 	
							$order_payments = $query->row();
							if(!empty($order_payments)){
								
								if(empty($return)==false)
									return 1;
								else
									echo 1;
								
							}else{
								
								if(empty($return)==false)
									return 0;
								else
									echo 0;
							}
							
						}
					}else{
                    
                    	//Checked whether this order has already been authorized or not
							$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$orders->order_id ,'payment_type'=>'Gear Payment' ,'status'=>'AUTHORISED'));			 	
							$order_payments = $query->row();
							if(!empty($order_payments)){
								
								if(empty($return)==false)
									return 1;
								else
									echo 1;
								
							}else{
								
								if(empty($return)==false)
									return 0;
								else
									echo 0;
							}
                    
                    }
				}	
				
			 }
		 }
	}


	public function SettleBraintreeOrderstranscation($return='')
	{
		 $query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y','order_status'=>'2', 'gear_rent_request_from_date >='=> date('Y-m-d ')));
		 $order_list = $query->result();
		
		 if (!empty($order_list)) {

		 	foreach ($order_list as $orders) {
				$today_date = date('Y-m-d H:i:s');
				$today_date1 = date('Y-m-d');
				$order_date =  date('Y-m-d ', strtotime($orders->gear_rent_start_date))  ;
				$order_date1 =  date('Y-m-d', strtotime($orders->gear_rent_start_date))  ;
				$hourdiff = round((strtotime( $order_date) - strtotime(date('Y-m-d' ,strtotime(   $today_date )) ))/3600, 1);
				
		 		if ($today_date1 == $order_date1 ) {

		 			
		 			//$order_id = $post_data['order_id'] ;
					$query=  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('order_id'=>$orders->order_id));
					$user_data =  $query->result();
					$total_rent_amount_ex_gst = '0';
					 $gst_amount = '0';
					 $other_charges = '0';
					 $total_rent_amount = '0';
					 $security_deposit = '0';
					 $beta_discount = '0';
					 $insurance_fee = '0';
					 $community_fee = '0';
					 $sub_amount = '0' ;
					foreach ($user_data AS  $value) {
						 	$total_rent_amount_ex_gst  += $value->total_rent_amount_ex_gst ; 
						 	$gst_amount  += $value->gst_amount ; 
				 			$security_deposit +=   '0';
				 			 $beta_discount += $value->beta_discount ; 
					 		 $community_fee += $value->community_fee; 
					 		 $sub_amount += $value->total_rent_amount_ex_gst - $value->beta_discount  +  $value->community_fee + $value->gst_amount + $value->insurance_fee ; 
					 		 $total_rent_amount  += $sub_amount ;
					 		 
					}
					$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$orders->order_id ,'payment_type'=>'Gear Payment' ,'status'=>'AUTHORISED' ));
					$order_payments = $query->row();
					if (!empty($order_payments)) {
						if ($order_payments->transaction_id  !='') {
							
							if (version_compare(PHP_VERSION, '5.4.0', '<')) {
							    throw new Braintree_Exception('PHP version >= 5.4.0 required');
							}
					
							// Instantiate a Braintree Gateway either like this:
							$query =  $this->common_model->GetAllWhere('ks_settings',array() );
							$settings = $query->row();		
							
							// Instantiate a Braintree Gateway either like this:
							
							$gateway = new Braintree_Gateway([
	      									'environment' => BRAINTREE_ENVIORMENT,
										    'merchantId' => $settings->braintree_merchant_id,
										    'publicKey' => $settings->braintree_public_key,
										    'privateKey' => $settings->braintree_private_key
							]);

							$config = new Braintree_Configuration([
			      								'environment' => BRAINTREE_ENVIORMENT,
											    'merchantId' => $settings->braintree_merchant_id,
											    'publicKey' => $settings->braintree_public_key,
											    'privateKey' => $settings->braintree_private_key
							]);
							//$result = $gateway->transaction()->submitForSettlement($order_payments->transaction_id,number_format((float) $total_rent_amount , 2, '.', ''));
							$result = $gateway->transaction()->submitForSettlement($order_payments->transaction_id,number_format((float)$order_payments->transaction_amount,2,'.', ''));
							
							if($result->errors){
								$query =  $this->common_model->GetAllWhere('tbl_braintree_error_details',array('error_code'=>$result->transaction->processorResponseCode));
								$data =  $query->row();
								
								//Record is inserted into the Transaction Error Log table
								$insert_error = array("order_id"=>$orders->order_id,
													  "transaction_id"=>$order_payments->transaction_id,
													  "processorResponseCode"=>$result->transaction->processorResponseCode,
													  "processorResponseMessage"=>$result->message,
													  "created_date"=>date("Y-m-d"),
													  "created_time"=>date("H:i:s"));
								
								$this->common_model->InsertData('tbl_transaction_error_log' ,$insert_error); 
								
								$insert_cron= array('type' => 'Settlement Crone',
									'date_time' => date('Y-m-d H:i:s'),
									'order_id' => $order_id,
									'status' => $result->message,
								);
								
								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);	
								
								if(empty($return)==false)								
									return 0;
								else
									echo 0;
							}else{
								$insert_data  = array('type'=> 'Settlement Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$orders->order_id , 'status'=>'Settlement');
								$this->common_model->InsertData('ks_crone_log',$insert_data);
								$update_Data = array(
													
													'status'=>'RECEIVED'
												);
								$this->db->where('user_gear_payment_id', $order_payments->user_gear_payment_id);
								$this->db->update('ks_user_gear_payments', $update_Data); 
								
								if(empty($return)==false)								
									return 1;
								else
									echo 1;
							}

						}
					}

		 		}	
		 	}




		 }	



	}
	
	
	public function SendInvoiceEmail()
	{
		 $query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y','order_status'=>'2', 'gear_rent_request_from_date >='=> date('Y-m-d ')));
		 $order_list = $query->result();
		 // echo "<pre>";
		 // print_r($order_list);
		 if (!empty($order_list)) {
		 	foreach ($order_list as $orders) {
				$today_date = date('Y-m-d H:i:s');
				$today_date1 = date('Y-m-d');
				$order_date =  date('Y-m-d 12:00:00', strtotime($orders->gear_rent_start_date))  ;
				$order_date1 =  date('Y-m-d', strtotime($orders->gear_rent_start_date))  ;
				$hourdiff = round((strtotime( $order_date) - strtotime(date('Y-m-d' ,strtotime(   $today_date )) ))/3600, 1);
				// print_r($orders);

				$order_id = $orders->order_id;
				$sql = "SELECT o_d.* ,g_tbl.gear_name FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$order_id."'  "	;
				$order_details   = $this->common_model->get_records_from_sql($sql);
				foreach ($order_details as  $value) {
				 $users[] = 	$value->create_user;
				}
				
				$this->db->from('ks_user_gear_rent_master g_r_m');
				$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.user_gear_rent_id = g_r_m.user_gear_rent_id','inner');
				$this->db->join('ks_gear_order_location as u_add', ' g_r_d.order_id = u_add.order_id AND  g_r_m.user_address_id = u_add.user_address_id','inner');
				$this->db->join('ks_user_gear_order_description as desc', ' desc.order_id = g_r_d.order_id','inner');
				$this->db->join('ks_users as u', ' desc.app_user_id = u.app_user_id','inner');
				$query = $this->db->get();
				$data['addrsss'] =  $query->row();
				$url = '';
				$data['six_digit_random_number'] = $order_id; 
				$this->uri->segment('3');
				$data['order_details'] = $order_details;
				// print_r($data['order_details']);
				$mail_body = $this->load->view('order-template',$data ,true);
				$data =  file_get_contents('http://kitshare.com.au/kitlab/site_upload/invocies/Ks-9857891556952925.pdf');
				// print_r($mail_body);die;
				$this->load->helper('pdf');
				$pdf_url =  gen_pdf($mail_body,$order_id);

			


					$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia ",
											),
										"To"=>array(
												array("Email"=>'singhaniagourav@gmail.com',
												"Name"=>"",
												),
											),
										"Subject"=> 'Invocie Mail',
				                        "TextPart"=> "",
				                        "HTMLPart"=> 'Invocie Mail',
				                        "Attachments"=> array(
							               array(
							                 "Filename"=> "test.pdf",
							                    "ContentType"=> "application/pdf",
							                    "Base64Content"=> base64_encode($data)	  
							               )),
									),
								)
							);	
				$this->common_model->sendMail($mail_data);
		 		if ($order_date1 == $today_date1 ) {
		 			// echo "string";
		 			// echo $hourdiff;
		 			// 	$order_id = $post_data['order_id'] ;
					// $query=  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('order_id'=>$orders->order_id));
					// $user_data =  $query->result();
					// $total_rent_amount_ex_gst = '0';
					//  $gst_amount = '0';
					//  $other_charges = '0';
					//  $total_rent_amount = '0';
					//  $security_deposit = '0';
					//  $beta_discount = '0';
					//  $insurance_fee = '0';
					//  $community_fee = '0';
					//  $sub_amount = '0' ;
					// foreach ($user_data AS  $value) {
					// 	 	$total_rent_amount_ex_gst  += $value->total_rent_amount_ex_gst ; 
					// 	 	$gst_amount  += $value->gst_amount ; 
					 // 			$security_deposit +=   '0';
					 //	 			 $beta_discount += $value->beta_discount ; 
					//  		 $community_fee += $value->community_fee; 
					//  		 $sub_amount += $value->total_rent_amount_ex_gst - $value->beta_discount  +  $value->community_fee + $value->gst_amount + $value->insurance_fee ; 
					//  		 $total_rent_amount  += $sub_amount ;
					 		 
					// }
					// $query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$orders->order_id ,'payment_type'=>'Gear Payment' ,'status'=>'AUTHORISED' ));
					// $order_payments = $query->row();
					

		 		}	
		 	}


		 }	
	}

	public function OrderReminderMail()
	{
		$sql =  "SELECT * FROM  ks_user_gear_rent_details WHERE is_payment_completed='Y' AND is_rent_approved='N' AND order_status= '1' AND reminder_mai_sendl_status='0' GROUP by order_id " ;
		$order_list   = $this->common_model->get_records_from_sql($sql);
		$two_days_backdays = date("Y-m-d", strtotime("-1 days", strtotime(date('Y-m-d'))));
		$today_date = date('Y-m-d');
		if (!empty($order_list)) {
			foreach ($order_list as  $value) {
				 $next_day =  date("Y-m-d", strtotime("+1 days", strtotime(date($value->gear_rent_requested_on))));
				 $next_day_date =  date("Y-m-d", strtotime("+2 days", strtotime(date($value->gear_rent_requested_on))));
				
				//Same day order Reminder Mail
				if ( ( date('Y-m-d' ,strtotime($value->gear_rent_request_from_date)) == date('Y-m-d' ,strtotime($value->gear_rent_requested_on))) && ( date('Y-m-d' ,strtotime($value->gear_rent_request_from_date)) == date('Y-m-d'))) {
					echo "same day ";	
				}else{
					$today_date = date('Y-m-d H:i:s');
					
					$hourdiff = round((strtotime(  $today_date ) - strtotime(date('Y-m-d  H:i:s' ,strtotime($value->gear_rent_requested_on)) ))/3600, 1);	
					if ($hourdiff >= 12 &&  $hourdiff < '15:30') {
							$this->OwnerReminderMail($value->order_id);
							$insert_data  = array('type'=> 'Reminder Mail Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'reminder Mail');
				  			$this->common_model->InsertData('ks_crone_log',$insert_data);
				  			$update_Data = array(
												
												'reminder_mai_sendl_status'=>'1',
												'reminder_mai_sendl_date'=>date('Y-m-d H:i:m'),
											);
							$this->db->where('order_id', $value->order_id);
							$this->db->update('ks_user_gear_rent_details', $update_Data); 

					}
					// die;
				}
				

				//  date('Y-m-d  H:i:s' ,strtotime($value->gear_rent_requested_on)) ;
				// echo "<br>";
				//	echo  $hourdiff = round((strtotime(  $today_date ) - strtotime(date('Y-m-d  H:i:s' ,strtotime($value->gear_rent_requested_on)) ))/3600, 1);	
				
					// if($hourdiff >= 9 &&  $hourdiff < '23:30'){
					// 	// print_r($value);die	;
											
					// 		$this->OwnerReminderMail($value->order_id);
					// 		$insert_data  = array('type'=> 'Reminder Mail Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'reminder Mail');
				 //  			$this->common_model->InsertData('ks_crone_log',$insert_data);
				 //  			$update_Data = array(
												
					// 							'reminder_mai_sendl_status'=>'1',
					// 							'reminder_mai_sendl_date'=>date('Y-m-d H:i:m'),
					// 						);
					// 		$this->db->where('order_id', $value->order_id);
					// 		$this->db->update('ks_user_gear_rent_details', $update_Data); 

					// }else{
					
					//   $today_date = date('Y-m-d H:i:s');
					//   $hourdiff = round((strtotime(  $today_date ) - strtotime(date('Y-m-d H:i:s' ,strtotime($value->gear_rent_start_date)) ))/3600, 1);
					//   if ($hourdiff > 24 && $hourdiff < '24:30'   ) {
			  // 					$this->OwnerReminderMail($value->order_id);
			  // 					$insert_data  = array('type'=> 'Reminder Mail Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'reminder Mail');
				 //  				$this->common_model->InsertData('ks_crone_log',$insert_data);
				 //  				$update_Data = array(
												
					// 							'reminder_mai_sendl_status'=>'1',
					// 							'reminder_mai_sendl_date'=>date('Y-m-d H:i:m'),
					// 						);
					// 		$this->db->where('order_id', $value->order_id);
					// 		$this->db->update('ks_user_gear_rent_details', $update_Data); 

					//   }
					// }

			}
			
		}
	}


	public function OwnerReminderMail($order_id)
	{
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'] 	;
			$cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'] 	;
			$cart_details[0]['renter_lastname'] ='' 	;
		}
		
		if (empty($cart_details)) {
				$response['status'] = 200;
				$response['status_message'] = ' Order is present ';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
		}
		$this->db->select('*');
		$this->db->from('ks_gear_order_location ');
		$this->db->where('user_address_id',$cart_details[0]['user_address_id']);
		$this->db->where('order_id',$order_id);
		$query = $this->db->get();
		$address  =$query->row();	
		if (!empty($address)) {
			$suburb_name = $address->suburb_name ;
		}else{
			$suburb_name = '';
		}	
				
		// echo "<pre>";
		// print_r($cart_details);die;
		
		$msg= '<!doctype html>
				<html>
				<head>
				<meta charset="utf-8">
				<title>kitshare</title>
				</head>

				<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">

				<div class="wrapper" style="border:0px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">

				</div>


				<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="    margin: 0px auto;
				    background-color:#095cab;
				    padding: 10px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
				</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
				<tr>
				<td style="font-size:20px; padding-bottom:10px;">
				Hi '.$cart_details[0]['app_user_first_name'].', </td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">This is just a friendly reminder to let you know that you have not responded to the rental '.$order_id.' from '.$cart_details[0]['renter_firstname'].' .</td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">If you accept the rental from '.$cart_details[0]['renter_firstname'].',  we&apos;ll immediately update your reservation. If you choose to decline or do not respond to the request, your reservation will be cancelled.</td>

				</tr>
				 <tr>
				    <td>
				     <a href="'.$web_url.'"> <button  style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">ACCEPT</button> </a>
				     <a href="'.$web_url.'" ><button style="background-color:#ff0000; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">DECLINE</button></a>
				    </td>
				  </tr>	
				<td style="font-size:18px; padding-bottom:10px;">Regards,
				<p>The Kitshare Team</p>

				</td>

				</tr>

				</table>

				<table width="940" style="    margin: 0px auto;
				    background-color:#ddd;
				    padding: 5px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
				</tr>
				</table>
				</div>

				</body>
				</html>
				';

		   $mail_body = $msg;
		   // $to= "singhaniagourav@gmail.com";
		$to= $cart_details[0]['primary_email_address'];
		$subject = "You have forgotten to respond to a rental request";		
		$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia ",
											),
										"To"=>array(
												array("Email"=>$to,
												"Name"=>"",
												),
											),
										"Subject"=> $subject,
				                        "TextPart"=> "",
				                        "HTMLPart"=> $mail_body
									),
								)
							);	
		$this->common_model->sendMail($mail_data);
		
		$insert_array = array("type"=>"You have forgotten to respond to a rental request",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"You have forgotten to respond to a rental request");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
	}
  
	/// Order Completed Reminder  Mail
	public function SendOwnerCompleteReminderMail()
	{
	
		$curdate = date('Y-m-d', strtotime(date("Y-m-d"). ' -1 day'));
	
		$where = "`is_payment_completed`='Y' AND `is_rent_approved`='Y' AND `order_status`='3' AND DATE(`gear_rent_end_date`) = '".$curdate."'";
		//$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',$where);
		//$order_list =  $query->result();
		
		$sql = "SELECT * FROM `ks_user_gear_rent_details` WHERE ".$where." GROUP BY `order_id`";		
		$order_list = $this->common_model->get_records_from_sql($sql);			
		
		if (!empty($order_list)) {
			foreach ($order_list as  $value) {
			
				$this->OwnerReminderCompleteMail($value->order_id);						
				$insert_data  = array('type'=> 'Reminder Mail for Mark Order Completed' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'Reminder Mail for Mark Order Completed');
				$this->common_model->InsertData('ks_crone_log',$insert_data);
					
			}
		}
		


	} 

	public function OwnerReminderCompleteMail($order_id='')
	{
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		
		
		if(count($cart_details)>0){
		
			if ($cart_details[0]['owner_show_business_name']=='Y') {
				$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'];
				$cart_details[0]['app_user_last_name'] ='';
			}
			if ($cart_details[0]['renter_show_business_name']=='Y') {
				$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'];
				$cart_details[0]['renter_lastname'] ='';
			}
			
			$this->db->select('*');
			$this->db->from('ks_gear_order_location ');
			$this->db->where('user_address_id',$cart_details[0]['user_address_id']);
			$this->db->where('order_id',$order_id);
			$query = $this->db->get();
			$address  =$query->row();	
			if (!empty($address)) {
				$suburb_name = $address->suburb_name ;
			}else{
				$suburb_name = '';
			}	
			$msg= '<!doctype html>
					<html>
					<head>
					<meta charset="utf-8">
					<title>Kitshare</title>
					</head>
					<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">
					<div class="wrapper" style="border:0px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
					</div>
					<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
					<table width="940" style="margin: 0px auto;background-color:#095cab;padding: 10px 0px;text-align:center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
					</tr>
					</table>
					<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
					<td style="font-size:20px; padding-bottom:10px;">
					Hi '.$cart_details[0]['app_user_first_name'].', </td>
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">This is just a friendly reminder to let you know that you have not marked your rental '.$order_id.' as Completed. If your Listing has been returned and your booking is not marked as Completed, you may not be covered by Kitshare\'s insurance policy if there is damage or loss to the listing. </td>
					</tr>
					
					 <tr>
						<td>
						 <a href="'.$web_url.'rentals-dashboard"> <button  style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">End Rental</button> </a>
						
						</td>
					  </tr>	
					<td style="font-size:18px; padding-bottom:10px;">Regards,
					<p>The Kitshare Team</p>
					</td>
					</tr>
					</table>
					<table width="940" style="margin: 0px auto;background-color:#ddd;padding: 5px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved.</p></td>
					</tr>
					</table>
					</div>
					</body>
					</html>
					';
	
				 $mail_body = $msg;
			// print_r($cart_details);die;
			$to= $cart_details[0]['primary_email_address'];
			//$to1 = "sanidha@gmail.com";
			//$to = 'singhaniagourav@gmail.com';
			$subject = "Your order has not been marked as completed";		
			$mail_data = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"Kitshare Australia ",
												),
											"To"=>array(
													array("Email"=>$to,
													"Name"=>"",
													),
												),
											"Subject"=> $subject,
											"TextPart"=> "",
											"HTMLPart"=> $mail_body
										)
									)
								);	
			$this->common_model->sendMail($mail_data);
			
			$insert_array = array("type"=>"Your order has not been marked as completed",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Your order has not been marked as completed");
		
			$this->common_model->InsertData('ks_crone_log' ,$insert_array);
			
		}else{
	
				$response['status'] = 200;
				$response['status_message'] = 'Order is present';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
		}

	}


	// Mark order as completed
	public function MarkOrderAscompeted()
	{
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		$curdate = date('Y-m-d', strtotime(date("Y-m-d"). ' -1 day'));
		$where = "is_payment_completed='Y' AND is_rent_approved='Y' AND order_status='3' AND DATE(gear_rent_request_to_date)<'".$curdate."'";
		
		//$where = array('is_payment_completed'=>'Y' ,'is_rent_approved'=>'Y' ,'order_status'=> 3);
		//$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',$where);
		//$order_list =  $query->result();
		
		
		$sql = "SELECT * FROM `ks_user_gear_rent_details` WHERE ".$where;
		$order_list =$this->common_model->get_records_from_sql($sql);

		if (!empty($order_list)) {
			foreach ($order_list as  $value) {
			 	//$next_day_date  = date("Y-m-d", strtotime("+1 days", strtotime( $value->gear_rent_request_to_date)));
				//if (date('Y-m-d')  >  $next_day_date ) {
					
				$query =  $this->common_model->GetAllWhere('ks_user_gear_payments', array('gear_order_id'=>$value->order_id , 'payment_type' =>'Deposite Payment'));
				$deposite_Details =  $query->row();
				
				if (!empty($deposite_Details)) {
					if ($deposite_Details->transaction_id!= '') {
						if (version_compare(PHP_VERSION, '5.4.0', '<')) {
						    throw new Braintree_Exception('PHP version >= 5.4.0 required');
						}

						$query =  $this->common_model->GetAllWhere('ks_settings',array() );
						$settings = $query->row();		
						
						// Instantiate a Braintree Gateway either like this:
						
						$gateway = new Braintree_Gateway([
      									'environment' => BRAINTREE_ENVIORMENT,
									    'merchantId' => $settings->braintree_merchant_id,
									    'publicKey' => $settings->braintree_public_key,
									    'privateKey' => $settings->braintree_private_key
						]);

						$result = $gateway->transaction()->void($deposite_Details->transaction_id);
						$update_payment_status = array('deposite_status'=> 'Void');
						$this->common_model->UpdateRecord($update_payment_status,"ks_user_gear_rent_details","order_id",$value->order_id);

						$update_deposite_status = array('status'=>'Void');
						$this->common_model->UpdateRecord($update_deposite_status,"ks_user_gear_payments","user_gear_payment_id",$deposite_Details->user_gear_payment_id);
					
					}
				}
					
				$update_Data = array(
										
										'order_status'=>'4',
										'owner_rent_complete_date'=>date('Y-m-d H:i:m'),
										'owner_rent_complete'=> 'Y'
									);
				$this->db->where('order_id', $value->order_id);
				$this->db->update('ks_user_gear_rent_details', $update_Data); 
				$insert_data  = array('type'=> 'Marked as Completed Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$value->order_id , 'status'=>'Marked as Completed');
				$this->common_model->InsertData('ks_crone_log',$insert_data);
				//}
				
				//Mail is sent to both Owner and Renter informing them that the Order has been marked as completed
				//Both renter and owner's email are fetched
				$cart_details = $this->home_model->getUserCartbyOrderId($value->order_id);		
				
				$date=date_create(date("Y-m-d"));
				date_add($date,date_interval_create_from_date_string("14 days"));
				
		
				if(count($cart_details)>0){
				
					if ($cart_details[0]['owner_show_business_name']=='Y') {
						$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'];
						$cart_details[0]['app_user_last_name'] ='';
					}
					if ($cart_details[0]['renter_show_business_name']=='Y') {
						$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'];
						$cart_details[0]['renter_lastname'] ='';
					}
				
				$owner_address = $cart_details[0]['primary_email_address'];
				$renter_address = $cart_details[0]['renter_email'];
					
				$subject_owner = "Rental Completed, Write a Review";
				$subject_renter = "Rental Completed, Write a Review";
					
				$body_owner= '<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="margin: 0px auto;background-color:#095cab;padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
					</tr>
				</table>
				<table width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
						<td style="font-size:20px; padding-bottom:10px;">
							Hi  '.$cart_details[0]['app_user_first_name'].'</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">
							You have 14 days to write a review for '.$cart_details[0]['renter_firstname'].' '.$cart_details[0]['renter_lastname'].'.
						</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">Write a review to show your gratitude or provide helpful feedback for '.$cart_details[0]['renter_firstname'].'.</td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">How Reviews Work:</td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">You each have 14 days to write a review. Once you\'ve submitted your feedback, the review will be posted to their Kitshare profile.</td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">Reviews are shared publicly on Kitshare profiles, it’s important that you don’t include any personal information like an address or contact details.</td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;"><a href="'.$web_url.'rentals-dashboard" style="background-color:#f26522;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:15px;font-weight:bold;line-height:44px;text-align:center;text-decoration:none;width:180px" target="_blank" >Write a Review</a></td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">
							Regards,
							<p>The Kitshare Team</p>
						</td>
					</tr>
				</table>
				<table width="940" style="margin: 0px auto;background-color:#ddd;padding: 5px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
					</tr>
				</table>
				</div>
				';
				
				$body_renter= '<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="margin: 0px auto;background-color:#095cab;padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
					</tr>
				</table>
				<table width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
						<td style="font-size:20px; padding-bottom:10px;">
							Hi  '.$cart_details[0]['renter_firstname'].'</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">
							You have 14 days to write a review for '.$cart_details[0]['app_user_first_name'].' '.$cart_details[0]['app_user_last_name'].'.
						</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">Write a review to show your gratitude or provide helpful feedback for '.$cart_details[0]['app_user_first_name'].'.</td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">How Reviews Work:</td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">You each have 14 days to write a review. Once you\'ve submitted your feedback, the review will be posted to their Kitshare profile.</td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">Reviews are shared publicly on Kitshare profiles, it’s important that you don’t include any personal information like an address or contact details.</td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;"><a href="'.$web_url.'rentals-dashboard" style="background-color:#f26522;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:15px;font-weight:bold;line-height:44px;text-align:center;text-decoration:none;width:180px" target="_blank" >Write a Review</a></td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">
							Regards,
							<p>The Kitshare Team</p>
						</td>
					</tr>
				</table>
				<table width="940" style="margin: 0px auto;background-color:#ddd;padding: 5px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
					</tr>
				</table>
				</div>
				';
					
				$this->markedCompletedEmail($owner_address,$renter_address,$subject_owner,$subject_renter,$body_owner,$body_renter,$value->order_id);
				//sleep(10);
			}
		}
		
		}
	}
	
	//Mark completed email
	public function markedCompletedEmail($owner_address,$renter_address,$subject_owner,$subject_renter,$body_owner,$body_renter,$order_id){
			
		$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia",
											),
										"To"=>array(
												array("Email"=>$owner_address,
												"Name"=>"",
												),
											),
										"Subject"=> $subject_owner,
										"TextPart"=> "",
										"HTMLPart"=> $body_owner
									),array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia",
											),
										"To"=>array(
												array("Email"=>$renter_address,
												"Name"=>"",
												),
											),
										"Subject"=> $subject_renter,
										"TextPart"=> "",
										"HTMLPart"=> $body_renter
									),
								)
							);	
		$this->common_model->sendMail($mail_data);
		
		$insert_array = array("type"=>$subject_owner,
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>$subject_owner);
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
		
		$insert_array = array("type"=>$subject_renter,
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>$subject_renter);
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
	
	}
	
	
	//Function to send reminder emails for next day
	public function nextdayreminderemails(){
	
		$where = "is_payment_completed='Y' AND is_rent_approved='N' AND order_status='1' AND reminder_mai_sendl_status='0' AND DATE(gear_rent_requested_on)<='".date('Y-m-d')."' AND DATE(gear_rent_request_from_date)>='".date('Y-m-d')."'";
	
			
		$this->db->select('*');
		$this->db->from('ks_user_gear_rent_details');
		$this->db->where($where);
		$this->db->group_by('order_id');
		$query = $this->db->get();
		
		foreach($query->result() as $row){
		
			$request_date 	= date("Y-m-d",strtotime($row->gear_rent_requested_on));
			$from_date 	= date("Y-m-d",strtotime($row->gear_rent_request_from_date));
			$to_date 		= date("Y-m-d",strtotime($row->gear_rent_request_to_date));
			$cur_date = date("Y-m-d");
			
			//Date difference between two dates
			$date1=date_create($cur_date);
			$date2=date_create($request_date);
			$diff=date_diff($date1,$date2);
			$diff_date = $diff->format("%a");
			
			//Difference between request date and pickup date
			$date3 = date_create($from_date);
			$diff_pickup=date_diff($date2,$date3);
			
			$difference = $diff_pickup->format("%a");
			
			if($request_date != $from_date){
			
				if($request_date <= $cur_date &&  ($difference>=1 || $diff==1)){
				
					//$timestamp = strtotime(date("H:i", strtotime($row->gear_rent_requested_on))) + 12*60*60;
					
					$timestamp = strtotime($row->gear_rent_requested_on) + 12*60*60;
					//$time = date('H:i', $timestamp);
					$time = $timestamp;
					//$current_time = date("H:i");
					$current_time = strtotime(date("Y-m-d H:i:s"));
					
					if($time<=$current_time){
					
						$this->OwnerReminderMail($row->order_id);
						$insert_data  = array('type'=> 'Reminder Mail Crone' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$row->order_id , 'status'=>'reminder Mail');
						$this->common_model->InsertData('ks_crone_log',$insert_data);
						$update_data = array(
											'reminder_mai_sendl_status'=>'1',
											'reminder_mai_sendl_date'=>date('Y-m-d H:i:m'),
										);
						$this->db->where('order_id', $row->order_id);
						$this->db->update('ks_user_gear_rent_details', $update_data); 
					
					}
					
					
				}
			
			}
		
		}
		
	
	}
	
	public function SendChatMessageMail()
	{
		$web_url = WEB_URL;
				
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		
		//All the distinct receiver_id are fetched
		$this->db->select("DISTINCT(receiver_id),app_user_first_name,app_user_last_name,primary_email_address,show_business_name,bussiness_name,user_profile_picture_link");
		$this->db->from("ks_user_chatmessage");
		$this->db->join("ks_users","ks_user_chatmessage.receiver_id = ks_users.app_user_id");
		$this->db->where(array("ks_users.is_active"=>'Y'));
		$query = $this->db->get();
		
		foreach($query->result() as $row){
		
			//Unread messages are fetched from the table
			$this->db->select("ks_user_chatmessage.chat_message_id,ks_user_chatmessage.chat_user_id,ks_user_chatmessage.message,ks_user_chatmessage.sender_id,ks_user_chatmessage.receiver_id,ks_user_chatmessage.message_type,ks_user_chatmessage.message_code,
			ks_user_chatmessage.create_date,ks_user_chatmessage.created_time,ks_user_chatmessage.created_by,ks_user_chatmessage.is_seen,
			ks_users.app_user_first_name,ks_users.app_user_last_name,ks_users.show_business_name,ks_users.bussiness_name,ks_users.user_profile_picture_link");
			$this->db->from("ks_user_chatmessage");
			$this->db->join("ks_users","ks_user_chatmessage.sender_id = ks_users.app_user_id");
			$this->db->where(array("message_type"=>'Contact',"mail_sent"=>'N',"receiver_id"=>$row->receiver_id));
			//$this->db->where(array("mail_sent"=>'N',"receiver_id"=>$row->receiver_id));
			$this->db->group_by("chat_user_id");
			
			$query = $this->db->get();
			
			$msg = '';
			
			if($query->num_rows()>0){			
			
			foreach($query->result() as $row1){	
			
				$created_date = $row1->create_date." ".$row1->created_time;
                $now          = date("Y-m-d")." ".date("H:i:s");
            
            	$start_date = new DateTime($created_date);
				$since_start = $start_date->diff(new DateTime($now));
				$mins = $since_start->i;

            
            	if($mins>=15){
					
					if ($row->show_business_name=='Y') {
						$row->app_user_first_name = $row->bussiness_name;
						$row->app_user_last_name ='';
					}				
					
					if (empty($row1->user_profile_picture_link)) {
						$row1->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
					}
					
					$msg= '<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
						<table width="940" style="border:1px solid #ddd; margin:0px auto; background-color:#095cab; padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
							<tr>
								<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
							</tr>
						</table>
						<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0px 10px" cellpadding="0" cellspacing="0">		
						<tr>
							<td height="15"></td></tr>
						<tr>
							<td style="font-size:20px; padding-bottom:10px;"> Hi '.$row->app_user_first_name.',</td>
						</tr>
							<td>
								<img src="'.$row1->user_profile_picture_link.'" height="100px" width="100px" >
							</td>
						<tr>
							<td style="font-size:18px; padding-bottom:10px;">'.$row1->app_user_first_name.' '.date("d/m/Y",strtotime($row1->create_date)).' at '.date("h:i a",strtotime($row1->created_time)).'</td>
						</tr>
						';	
						
						$this->db->select("chat_message_id,chat_user_id,message,sender_id,receiver_id,message_type,message_code,create_date,created_time,created_by,is_seen");
						$this->db->from("ks_user_chatmessage");
						$this->db->where(array("message_type"=>'Contact',"mail_sent"=>'N',"receiver_id"=>$row1->receiver_id, "sender_id"=>$row1->sender_id, "chat_user_id"=>$row1->chat_user_id));
						//$this->db->where(array("mail_sent"=>'N',"receiver_id"=>$row1->receiver_id, "sender_id"=>$row1->sender_id));
						$this->db->order_by("create_date","ASC");
						$this->db->order_by("created_time","ASC");
						
						$query1 = $this->db->get();
						
						foreach($query1->result() as $row2){					
							
							$msg.='<tr>
									<td style="font-size:18px; font-family:Gill Sans, Gill Sans MT, Myriad Pro, DejaVu Sans Condensed, Helvetica, Arial, sans-serif; padding-bottom:10px">
										<div style="background-color: lightgrey;border: 2px solid black;padding: 50px;margin: 20px;">'.$row2->message.'</div>
									</td>
							   </tr>';	
						}
						
						$msg.='<tr>
								<td><a href="'.$web_url.'messaging">
								<button type="submit" style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">Reply Message </button>
								</a> </td>
								</tr>
								</tr>
								<tr><td height="15"></td></tr>
								<tr>
								<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
								</tr>
							</table>
						</div>';
						
					$mail_body = $msg; 

					$to= $row->primary_email_address;
					$subject = "Someone has left a message in your inbox.";		
					$mail_data = array(
									'Messages'=>array(array(
													"From"=>array(
															"Email"=>"support@kitshare.com.au",
															"Name"=>"Kitshare Australia ",
														),
													"To"=>array(
															array("Email"=>$to,
															"Name"=>"",
															),
														),
													"Subject"=> $subject,
													"TextPart"=> "",
													"HTMLPart"=> $mail_body
												),
											)
										);
                
                	if($query1->num_rows()>0)
						$sendmail = $this->common_model->sendMail($mail_data);
                	else
                    	$sendmail = 0;
                
					if($sendmail==1){
						
						//Message is marked as seen
						$update_msg  = array( 
												'mail_sent'=>'Y',
												'updated_date'=>date("Y-m-d"),
												'updated_time'=>date("H:i:s")
											); 

						$this->common_model->UpdateRecord($update_msg,"ks_user_chatmessage","receiver_id",$row1->receiver_id);
						
						
					}
					
				}
			}		
			
			}
		}
	}
	
	public function SendChatMessageMailOrder()
	{
		$web_url = WEB_URL;
				
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		
		//All the distinct receiver_id are fetched
		$this->db->select("DISTINCT(receiver_id),app_user_first_name,app_user_last_name,primary_email_address,show_business_name,bussiness_name,user_profile_picture_link");
		$this->db->from("ks_user_chatmessage");
		$this->db->join("ks_users","ks_user_chatmessage.receiver_id = ks_users.app_user_id");
		$this->db->where(array("ks_users.is_active"=>'Y'));
        //$this->db->group_by('ks_user_chatmessage.chat_user_id');
		$query = $this->db->get();
		
    
		foreach($query->result() as $row){
		
			//Unread messages are fetched from the table
			$this->db->select("ks_user_chatmessage.chat_message_id,ks_user_chatmessage.chat_user_id,ks_user_chatmessage.message,ks_user_chatmessage.sender_id,ks_user_chatmessage.receiver_id,ks_user_chatmessage.message_type,ks_user_chatmessage.message_code,
			ks_user_chatmessage.create_date,ks_user_chatmessage.created_time,ks_user_chatmessage.created_by,ks_user_chatmessage.is_seen,
			ks_users.app_user_first_name,ks_users.app_user_last_name,ks_users.show_business_name,ks_users.bussiness_name,ks_users.user_profile_picture_link");
			$this->db->from("ks_user_chatmessage");
			$this->db->join("ks_users","ks_user_chatmessage.sender_id = ks_users.app_user_id");
			$this->db->where(array("message_type"=>'Order',"mail_sent"=>'N',"receiver_id"=>$row->receiver_id));
			//$this->db->where(array("mail_sent"=>'N',"receiver_id"=>$row->receiver_id));
			$this->db->group_by("chat_user_id");
			
			$query = $this->db->get();
        
        
			$msg = '';
			
			if($query->num_rows()>0){			
			
			foreach($query->result() as $row1){
            
            	$created_date = $row1->create_date." ".$row1->created_time;
                $now          = date("Y-m-d")." ".date("H:i:s");
            
            	$start_date = new DateTime($created_date);
				$since_start = $start_date->diff(new DateTime($now));
				/*$since_start->days.' days total<br>';
				$since_start->y.' years<br>';
				$since_start->m.' months<br>';
				$since_start->d.' days<br>';
				$since_start->h.' hours<br>';*/
				$mins = $since_start->i;
				//echo $since_start->s.' seconds<br>';
            
            	if($mins>=15){
                
                if ($row->show_business_name=='Y') {
					$row->app_user_first_name = $row->bussiness_name;
					$row->app_user_last_name ='';
				}				
				
				if (empty($row1->user_profile_picture_link)) {
					$row1->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
				}
				
				$msg= '<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
						<table width="940" style="border:1px solid #ddd; margin:0px auto; background-color:#095cab; padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
							<tr>
								<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
							</tr>
						</table>
						<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0px 10px" cellpadding="0" cellspacing="0">		
						<tr>
							<td height="15"></td></tr>
						<tr>
							<td style="font-size:20px; padding-bottom:10px;"> Hi '.$row->app_user_first_name.',</td>
						</tr>
							<td>
								<img src="'.$row1->user_profile_picture_link.'" height="100px" width="100px" >
							</td>
						<tr>
							<td style="font-size:18px; padding-bottom:10px;">'.$row1->app_user_first_name.' '.date("d/m/Y",strtotime($row1->create_date)).' at '.date("h:i a",strtotime($row1->created_time)).'</td>
						</tr>
						';	
						
					$this->db->select("chat_message_id,chat_user_id,message,sender_id,receiver_id,message_type,message_code,create_date,created_time,created_by,is_seen");
					$this->db->from("ks_user_chatmessage");
					$this->db->where(array("message_type"=>'Order',"mail_sent"=>'N',"receiver_id"=>$row1->receiver_id, "chat_user_id"=>$row1->chat_user_id));
					//$this->db->where(array("mail_sent"=>'N',"receiver_id"=>$row1->receiver_id, "sender_id"=>$row1->sender_id));
					$this->db->order_by("create_date","ASC");
					$this->db->order_by("created_time","ASC");
					
					$query1 = $this->db->get();
                
					
					foreach($query1->result() as $row2){
						
						$msg.='<tr>
								<td style="font-size:18px; font-family:Gill Sans, Gill Sans MT, Myriad Pro, DejaVu Sans Condensed, Helvetica, Arial, sans-serif; padding-bottom:10px">
									<div style="background-color: lightgrey;border: 2px solid black;padding: 50px;margin: 20px;">'.$row2->message.'</div>
								</td>
						   </tr>';	
					}
					
					$msg.='<tr>
							<td><a href="'.$web_url.'messaging">
							<button type="submit" style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">Reply Message </button>
							</a> </td>
							</tr>
							</tr>
							<tr><td height="15"></td></tr>
							<tr>
							<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
							</tr>
						</table>
					</div>';
					
				    $mail_body = $msg; 

				$to= $row->primary_email_address;
				$subject = "Someone has left a message in your inbox.";		
				$mail_data = array(
								'Messages'=>array(array(
												"From"=>array(
														"Email"=>"support@kitshare.com.au",
														"Name"=>"Kitshare Australia ",
													),
												"To"=>array(
														array("Email"=>$to,
														"Name"=>"",
														),
													),
												"Subject"=> $subject,
												"TextPart"=> "",
												"HTMLPart"=> $mail_body
											),
										)
									);	
                	if($query1->num_rows()>0){
						$sendmail = $this->common_model->sendMail($mail_data);
                    }else
                    	$sendmail = 0;
					if($sendmail==1){
					
						//Message is marked as seen
						$update_msg  = array( 
											'mail_sent'=>'Y',
											'updated_date'=>date("Y-m-d"),
											'updated_time'=>date("H:i:s")
			 							); 

						$this->common_model->UpdateRecord($update_msg,"ks_user_chatmessage","receiver_id",$row1->receiver_id);
					
					
					}
                }
			
				
			
				}		
			
			}
		}
	}

}?>