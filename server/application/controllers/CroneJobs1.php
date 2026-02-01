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
	
	public function index(){

						
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.mailjet.com/v3.1/send",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "{\r\n        \"Messages\":[\r\n                {\r\n                        \"From\": {\r\n                                \"Email\": \"support@kitshare.com.au\",\r\n                                \"Name\": \"kitshare\"\r\n                        },\r\n                        \"To\": [\r\n                                {\r\n                                        \"Email\": \"singhaniagourav@gmail.com\",\r\n                                        \"Name\": \"You\"\r\n                                }\r\n                        ],\r\n                        \"Subject\": \"My first Mailjet Email!\",\r\n                        \"TextPart\": \"Greetings from Mailjet!\",\r\n                        \"HTMLPart\": \"<h3>Dear passenger 1, welcome to <a href=\\\"https://www.mailjet.com/\\\">Mailjet</a>!</h3><br />May the delivery force be with you!\"\r\n                }\r\n        ]\r\n    }",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Basic MTE1MGEzYWZkMzg3MzM0ZjU1YmJiMWQ5MTI3YWE5Y2M6N2U3MzdkNjNiMWE1NDQ3NjgwNzNlZjVjOTlmMTQ2YmU=",
		    "Content-Type: application/json",
		    "Postman-Token: 5dacc0e7-eff8-430b-ba53-b9a520abed72",
		    "cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}

		
	}
	
	public function UpdateOrderExpiredStatus()
	{
		$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y' ,'is_rent_approved'=>'N'));
		$order_list = $query->result();
	
		 $two_days_backdays = date("Y-m-d", strtotime("-2 days", strtotime(date('Y-m-d'))));
		$today_date = date('Y-m-d') ;
		
		foreach ($order_list as  $value) {

			if ( date('Y-m-d' ,strtotime($value->gear_rent_start_date)) < $two_days_backdays) {
					$update_data = array(
			  	 							'order_status'=>'8'
			  	 						);
			  		$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value->user_gear_rent_detail_id);


			}elseif(date('Y-m-d' ,strtotime($value->gear_rent_start_date)) == $today_date){

				  $today_date = date('Y-m-d H:i:s');
				  $hourdiff = round((strtotime(  $today_date ) - strtotime(date('Y-m-d 12:00:00' ,strtotime($value->gear_rent_start_date)) ))/3600, 1);
				 if ($hourdiff > 4 &&  $hourdiff < 24  ) {
				 		$update_data = array(
					 							'order_status'=>'8'
					 						);
								$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value->user_gear_rent_detail_id);
				 }

			}elseif(date('Y-m-d' ,strtotime($value->gear_rent_start_date)) > $two_days_backdays &&  date('Y-m-d' ,strtotime($value->gear_rent_start_date)) < $today_date){
					
				  $today_date = date('Y-m-d H:i:s');
				  $hourdiff = round((strtotime(  $today_date ) - strtotime(date('Y-m-d 12:00:00' ,strtotime($value->gear_rent_start_date)) ))/3600, 1);
				  if ($hourdiff > 12  ) {
					 		$update_data = array(
						 							'order_status'=>'8'
						 						);
							$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value->user_gear_rent_detail_id);
				  }
			}

		}	
	}
	// Mark  order as contract on same day of rent
	public function UpdateOrderContractStatus()
	{
		$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y' ,'is_rent_approved'=>'Y'));
		$order_list = $query->result();
		$today_date = date('Y-m-d') ;

		foreach ($order_list as  $value) {
			  if ( date('Y-m-d' ,strtotime($value->gear_rent_start_date)) == $today_date ) {

			  	 	$update_data = array(
			  	 							'order_status'=>'3'
			  	 						);
			  		$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value->user_gear_rent_detail_id);
			  }else{
			  	
			  }

		}
	}

	// Mark archived order more than 6 month old
	public function UpdateOrderArchivedStatus()
	{
		$query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y'));
		$order_list = $query->result();

		//print_r($order_list);
		$back_date = date("Y-m-d", strtotime("-6 days", strtotime(date('Y-m-d'))));
		
		foreach ($order_list as  $value) {
			
			  if ( date('Y-m-d' ,strtotime($value->gear_rent_start_date)) < $back_date ) {

			  	 	$update_data = array(
			  	 							'order_status'=>'7'
			  	 						);
			  		$this->common_model->UpdateRecord($update_data , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value->user_gear_rent_detail_id);
			  }else{
			  	echo "bye";
			  }

		}
	}

	//
	public function Authorizetrascation()
	{
		 $query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y','order_status'=>'2', 'gear_rent_request_from_date >='=> date('Y-m-d')));
		 $order_list = $query->result();
		 // echo "<pre>";
		 // print_r($order_list);
		 // die;
		 if (!empty($order_list)) {
		 
			 foreach ($order_list  As $orders) {
			 	   $today_date = date('Y-m-d H:i:s');
			 	   $order_date =  date('Y-m-d 12:00:00', strtotime($orders->gear_rent_start_date))  ;
				   $hourdiff = round((strtotime( $order_date) - strtotime(date('Y-m-d' ,strtotime(   $today_date )) ))/3600, 1);
			 		//print_r($orders);
			 	  //echo "<br>";	
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
							$gateway = new Braintree_Gateway([
							    'environment' => 'sandbox',
							    'merchantId' => 'zmdpynpfyrtg74pn',
							    'publicKey' => '5mx3mprhdtxkw97z',
							    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
							]);

							$config = new Braintree_Configuration([
							    'environment' => 'sandbox',
							     'merchantId' => 'zmdpynpfyrtg74pn',
							    'publicKey' => '5mx3mprhdtxkw97z',
							    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
							]);
							$result = $gateway->paymentMethodNonce()->create($orders->braintree_token);
							$sql = "SELECT o_d.* ,g_tbl.gear_name,g_tbl.replacement_value_aud_inc_gst,g_tbl.replacement_value_aud_ex_gst ,g_tbl.per_day_cost_aud_ex_gst FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$orders->order_id."'  "	;
							$cart_details   = $this->common_model->get_records_from_sql($sql);
							$sum= 0 ;
							foreach ($cart_details as  $value) {
										$sum	+= $value->total_rent_amount ;
							}
							
							$result = $gateway->transaction()->sale([
						    							'amount' =>number_format((float) $sum , 2, '.', '') ,
										    			'paymentMethodNonce' => $result->paymentMethodNonce->nonce
													]); 
					
							$update_Data = array(
												
												'transaction_id'=> $result->transaction->id,
												'status'=>'AUTHORISED'
											);
							$this->db->where('user_gear_payment_id', $order_payments->user_gear_payment_id);
							$this->db->update('ks_user_gear_payments', $update_Data); 
							// print_r($result);

						}
					}
				}	
				
			 }
		 }
	}


	public function SettleBraintreeOrderstranscation($value='')
	{
		 $query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('is_payment_completed'=>'Y','order_status'=>'2', 'gear_rent_request_from_date >='=> date('Y-m-d ')));
		 $order_list = $query->result();
		 // echo "<pre>";
		 // print_r($order_list);
		 if (!empty($order_list)) {

		 	foreach ($order_list as $orders) {
				$today_date = date('Y-m-d H:i:s');
				$order_date =  date('Y-m-d 12:00:00', strtotime($orders->gear_rent_start_date))  ;
				$hourdiff = round((strtotime( $order_date) - strtotime(date('Y-m-d' ,strtotime(   $today_date )) ))/3600, 1);
			
		 		if ($hourdiff <= 24 ) {
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
							$gateway = new Braintree_Gateway([
							    'environment' => 'sandbox',
							    'merchantId' => 'zmdpynpfyrtg74pn',
							    'publicKey' => '5mx3mprhdtxkw97z',
							    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
							]);

							$config = new Braintree_Configuration([
							    'environment' => 'sandbox',
							     'merchantId' => 'zmdpynpfyrtg74pn',
							    'publicKey' => '5mx3mprhdtxkw97z',
							    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
							]);
							$result = $gateway->transaction()->submitForSettlement($order_payments->transaction_id,number_format((float) $total_rent_amount , 2, '.', ''));
							$update_Data = array(
												
												'status'=>'RECEIVED'
											);
							$this->db->where('user_gear_payment_id', $order_payments->user_gear_payment_id);
							$this->db->update('ks_user_gear_payments', $update_Data); 

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
		 echo "<pre>";
		 print_r($order_list);
		 if (!empty($order_list)) {
		 	foreach ($order_list as $orders) {
				$today_date = date('Y-m-d H:i:s');
				$order_date =  date('Y-m-d 12:00:00', strtotime($orders->gear_rent_start_date))  ;
				$hourdiff = round((strtotime( $order_date) - strtotime(date('Y-m-d' ,strtotime(   $today_date )) ))/3600, 1);
				print_r($orders);

				$order_id = $orders->order_id;
				$sql = "SELECT o_d.* ,g_tbl.gear_name FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$order_id."'  "	;
				$order_details   = $this->common_model->get_records_from_sql($sql);
				foreach ($order_details as  $value) {
				 $users[] = 	$value->create_user;
				}
				
				$this->db->from('ks_user_gear_rent_master g_r_m');
				$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.user_gear_rent_id = g_r_m.user_gear_rent_id','inner');
				$this->db->join('ks_user_address as u_add', 'g_r_m.user_address_id = u_add.user_address_id','inner');
				$this->db->join('ks_states  As state', 'state.ks_state_id  = u_add.ks_state_id','inner');
				$this->db->join('ks_suburbs  As suburbs', 'suburbs.ks_suburb_id  = u_add.ks_suburb_id','inner');
				$this->db->join('ks_countries  As countries', 'countries.ks_country_id  = u_add.ks_country_id','inner');
				$this->db->join('ks_users  As users', 'users.app_user_id  = u_add.app_user_id','inner');
				$this->db->where('u_add.app_user_id' , $users[0]);
				$query = $this->db->get();
				$data['addrsss'] =  $query->row();
				$url = '';
				$data['six_digit_random_number'] = $order_id; 
				$this->uri->segment('3');
				$data['order_details'] = $order_details;
				print_r($data['order_details']);
		 		if ($hourdiff <= 24 ) {
		 			echo "string";
		 			echo $hourdiff;
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
				 // 			 $beta_discount += $value->beta_discount ; 
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
}?>
