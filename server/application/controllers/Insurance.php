<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'/third_party/braintree-php-3.36.0/lib/autoload.php');

class Insurance extends CI_Controller {
	
	public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email','upload','pagination'));
		$this->load->model(array('common_model','home_model'));
	}

	public function index()
	{
		echo "hello";
	}
	
	//Insurance Check 

	public function InsuranceCheck()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		
		$response['status'] = "";
		
		if ($app_user_id != '') {
				$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'] ,'order_id = '=>''));
			 	$Cart_details = $query1->row();
			 	// Address is present in the gear
			 	
			 	$this->db->select('rent_details.user_address_id ,state.* ');
		  		$this->db->from('ks_user_gear_rent_master As  rent_details');
				$this->db->join('ks_user_address As address', 'address.user_address_id = rent_details.user_address_id','LEFT');
				$this->db->join('ks_states As state', 'state.ks_state_id = address.ks_state_id','LEFT');
				$this->db->where('rent_details.user_gear_rent_id' ,$Cart_details->user_gear_rent_id); 
				$this->db->where('address.user_address_id' ,$post_data['user_address_id']); 
				$query = $this->db->get();	
		  		$address_details = $query->row();	
		  		$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
				$insurance_type = $query->row();
			 	

			 		if ($post_data['insurance_type'] == '0') { // reset value
			 			
			 			$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'] ,'order_id = '=>''));
			 			$Cart_details = $query1->row();

                    
			 				if ($Cart_details->ks_insurance_category_type_id == 1) {
			 						$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
									$insurance_type = $query->row();
									$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'], 'order_id ='=>''));
								 	$Cart_details = $query1->row();
								 	$date_from = strtotime($Cart_details->gear_rent_request_from_date);
								 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
								 	$diff = abs($date_to - $date_from);
									$date_array =  $this->getDateList($date_from,$date_to);
									if (count($date_array ) <= 1 ) {
								 		
								 	}elseif(count($date_array ) == 2 ){
								 			   $date_array;
								 	}else{
								 		foreach ($date_array as $value) {
								 			 $val_date[] =   $value['date'];
								 		}
								 		 $date_array =  $val_date;
											array_pop($date_array); 
									}						  
									$insurance_days = count($date_array);
								 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
								 	$gear_details = $query1->row();
								 	$query =  $this->common_model->GetAllWhere('ks_settings','');
									$settings =  $query->row();
									if($gear_details->replacement_value_aud_ex_gst > $settings->max_replacement_value){
										$app_user_id = '';
										$response['status'] = 401;
										$response['status_message'] = ' The Replacement value for the total cart must not exceed  '.$settings->max_replacement_value .' ex GST';
										$json_response = json_encode($response);
										echo $json_response;
										exit();
									}
									$where_clause1 = array(
				 							'initial_value <' => $Cart_details->total_rent_amount_ex_gst,
				 							'end_value > ' => $Cart_details->total_rent_amount_ex_gst,
				 							'status'=> '0',
				 							'is_deleted' => '0',
				 							'ks_insurance_category_type_id'=> $post_data['insurance_type']
					 				  );
									$query =  $this->common_model->GetAllWhere(' ks_insurance_tiers	' , $where_clause1) ; 
									$insurance_tier_type = $query->row();
									
								 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
								 	$rent_master_details = $query2->row();
								 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit  - $Cart_details->beta_discount ; 
								 	if (!empty($address_details)) {
								 		$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
										$Cart_details->esl_rate =  0 ; 
										$Cart_details->stamp_duty_rate =  0; 
										$Cart_details->premium_base_rate =  0 ; 

										$Cart_details->premium_base_rate_amount = 0; 
										$Cart_details->esl_amount =  0 ; 
										$Cart_details->duty_rate_amount =  0 ; 
										$Cart_details->total_premium_ex_gst =  0 ; 
										$Cart_details->premium_gst = 0; 
										$Cart_details->total_premium_inc_gst =  0  ; 
										$Cart_details->insurance_amount =  0  ; 
										$Cart_details->owner_insurance_amount = 0; 
							 			$Cart_details->owner_insurance_status = 0 ;  
								 	}
						 			$Cart_details->insurance_percentage = '0' ;  
							 		$Cart_details->insurance_amount =  0 ;  
									$Cart_details->insurance_fee =  0 ;
								 	// $gear_insurance_value = $Cart_details->insurance_amount;	
								 	$rent_master_details->other_charges	 =  '0' ;
								 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit  - $Cart_details->beta_discount  + $Cart_details->insurance_fee + $Cart_details->community_fee ; ; 
								 	$Cart_details->ks_insurance_category_type_id = 0  ; 
								 	$Cart_details->insurance_tier_id =  0  ; 
								 	$Cart_details->other_charges =  '0' ; 
								 	$Cart_details->security_deposit =  '0' ; 
								 	$Cart_details->deposite_status =  'NONE' ; 
								 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
								 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
			 				}

			 				if ($Cart_details->ks_insurance_category_type_id == 8) {

			 					$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'], 'order_id ='=>''));
							 	$Cart_details = $query1->row();
							 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
							 	$rent_master_details = $query2->row();
							 	$date_from = strtotime($Cart_details->gear_rent_request_from_date);
							 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
							 	$diff = abs($date_to - $date_from);
								$date_array =  $this->GetRentDays($date_from,$date_to);
								
								$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
							 	$gear_details = $query1->row();
							 	$query =  $this->common_model->GetAllWhere('ks_settings','');
							 	$settings = $query->row();
							 	$Cart = $this->home_model->getUserCart1($app_user_id);
						 		$where = array( 'app_user_id'=>$Cart[0]['app_user_id'] ,
						 					'is_visiible'=>'Y' ,
						 					'owner_insurance_provided'=>'Yes',
						 					'owner_insurance_status' =>'1' ,
						 					'ks_user_certificate_currency_start <= ' => date('Y-m-d') , 
						 					'ks_user_certificate_currency_exp >= ' => date('Y-m-d')  
						 		);
							 	// $where = array( 'app_user_id'=>$app_user_id , 'is_visiible'=>'Y' ,'owner_insurance_provided'=>'Yes', 'owner_insurance_status' =>1 ,

						 		// 	'ks_user_certificate_currency_start < ' => date('Y-m-d') , 'ks_user_certificate_currency_exp > ' => date('Y-m-d')  
						 		// );
								$query1 =  $this->common_model->GetAllWhere('ks_user_insurance_proof' ,$where);
							 	$insurance_detail = $query1->row();
					 			if (empty($insurance_detail)) {
					 				$response['status'] = 400;
									$response['status_message'] = 'Owner Insurance Not found';
									$response['result'] = array();
									$json_response = json_encode($response);
									echo $json_response;
									exit;
				 				}
					 			$paymentDate = date('Y-m-d');
								$paymentDate=date('Y-m-d', strtotime($paymentDate));
								$contractDateBegin = date('Y-m-d', strtotime($insurance_detail->ks_user_certificate_currency_start));
								$contractDateEnd = date('Y-m-d', strtotime($insurance_detail->ks_user_certificate_currency_exp));
								if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
								 //  	if (count($date_array ) <= 1 ) {
									//  		# code...
								 // 	}elseif(count($date_array ) == 2 ){
								 // 			   $date_array;

								 // 	}else{
								 // 		foreach ($date_array as $value) {
								 // 			 $val_date[] =   $value['date'];
								 // 		}
								 // 		 $date_array =  $val_date;
									// 		array_pop($date_array); 
									// }							  
									// print_r($gear_details)	;
									 $insurance_days = $date_array;
							 		 $inusrance_value = 0 ;
							 		$Cart_details->owner_insurance_amount = 0; 
									$Cart_details->insurance_fee =  0; 
									$Cart_details->insurance_amount =  0; 
				 					$gear_insurance_value = 0;	
					 				$Cart_details->owner_insurance_status = 0;  
					 				$Cart_details->insurance_percentage = 0;  
								 	$rent_master_details->other_charges	 =  '0' ;
								 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit  - $Cart_details->beta_discount  + $Cart_details->insurance_fee + $Cart_details->community_fee  ; 
								 	$Cart_details->ks_insurance_category_type_id = 0  ; 
								 	$Cart_details->insurance_tier_id =  0 ; 
								 	$Cart_details->other_charges =  '0' ; 
								 	$Cart_details->security_deposit =  '0' ; 
								 	$Cart_details->deposite_status =  'NONE' ;
								 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
				 					$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);

				 				}	
				 			}		
							
							if($Cart_details->ks_insurance_category_type_id == 3){
                            
                      
								
								$paymentDate = date('Y-m-d');
								$paymentDate=date('Y-m-d', strtotime($paymentDate));
									
								$where_clause = array(
														'app_user_id'=> $app_user_id ,
														'renter_insurance_provided' => 'Yes',
														'renter_insurance_status'=> '1' ,
														'is_visiible'=> 'Y' ,
														'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
														'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

													);
								$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
								$insurance =  $query->row();
                            
                            	if (empty($insurance)) {
					 				$response['status'] = 400;
									$response['status_message'] = 'Renter Insurance Not found';
									$response['result'] = array();
									$json_response = json_encode($response);
									echo $json_response;
									exit;
				 				}
                            
								

									$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
									$insurance_type = $query->row();

									$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'] ,' order_id='=>''));
									$Cart_details = $query1->row();

									$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
									$rent_master_details = $query2->row();
									$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
									// print_r($insurance_Details['total_premium_ex_gst']);die;
									$Cart_details->esl_rate =  $address_details->ks_state_esl ; 
									$Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
									$Cart_details->premium_base_rate =  $address_details->base_rate ; 

									$Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
									$Cart_details->esl_amount =  $insurance_Details['esl']  ; 
									$Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
									$Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
									$Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
									$Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
									$Cart_details->insurance_amount =  0 ; 
									
					 
									$Cart_details->other_charges =  '0' ;
									$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges; 
									$rent_master_details->other_charges = '0' ;
									$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount +  $rent_master_details->other_charges; 
									
									$Cart_details->ks_insurance_category_type_id = 0; 
									$Cart_details->insurance_tier_id =  '0'  ; 
									$Cart_details->other_charges =  '0' ; 
									$Cart_details->security_deposit =  '0' ; 
									$Cart_details->deposite_status =  'NONE' ; 
									//$insurance_type->amount = '0' ;
									$Cart_details->insurance_fee = '0'; 
									
									$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
									$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);

								
							}
			 		}	


			 		if ($post_data['insurance_type'] == '1' ) { // kitshare inurance cover

			 				$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
							$insurance_type = $query->row();
							$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'], 'order_id ='=>''));
						 	$Cart_details = $query1->row();
						 	$date_from = strtotime($Cart_details->gear_rent_request_from_date);
						 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
						 	$diff = abs($date_to - $date_from);
							$date_array =  $this->getDateList($date_from,$date_to);
							if (count($date_array ) <= 1 ) {
						 		
						 	}elseif(count($date_array ) == 2 ){
						 			   $date_array;
						 	}else{
						 		foreach ($date_array as $value) {
						 			 $val_date[] =   $value['date'];
						 		}
						 		 $date_array =  $val_date;
									array_pop($date_array); 
							}						  
							$insurance_days = count($date_array);
						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
						 	$gear_details = $query1->row();
						 	$query =  $this->common_model->GetAllWhere('ks_settings','');
							$settings =  $query->row();
							if($gear_details->replacement_value_aud_ex_gst > $settings->max_replacement_value){
								$app_user_id = '';
								$response = array();
								$response['status'] = 400;
								$response['status_message'] = ' The Replacement value for the total cart must not exceed  '.$settings->max_replacement_value .' ex GST';
								$json_response = json_encode($response);
								echo $json_response;
								exit();
							}
								$where_clause1 = array(
			 							'initial_value <' => $Cart_details->total_rent_amount_ex_gst,
			 							'end_value > ' => $Cart_details->total_rent_amount_ex_gst,
			 							'status'=> '0',
			 							'is_deleted' => '0',
			 							'ks_insurance_category_type_id'=> $post_data['insurance_type']
				 				  );
							$query =  $this->common_model->GetAllWhere(' ks_insurance_tiers	' , $where_clause1) ; 
							$insurance_tier_type = $query->row();
							
						 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
						 	$rent_master_details = $query2->row();
						 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit  - $Cart_details->beta_discount ; 
						 	if (!empty($address_details)) {
						 		
							 	$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
								$Cart_details->esl_rate =  '0' ; 
								$Cart_details->stamp_duty_rate =  '0' ; 
								$Cart_details->premium_base_rate =  '0'; 
								$Cart_details->premium_base_rate_amount = '0'; 
								$Cart_details->esl_amount =  '0' ; 
								$Cart_details->duty_rate_amount =  '0' ; 
								$Cart_details->total_premium_ex_gst =  '0' ; 
								$Cart_details->premium_gst =  '0' ; 
								$Cart_details->total_premium_inc_gst = '0' ; 
								$Cart_details->insurance_amount =  '0'    ; 
								$Cart_details->owner_insurance_amount =0; 
					 			$Cart_details->owner_insurance_status = 0 ;  
					 			$Cart_details->insurance_percentage = '0' ;  
						 		$Cart_details->insurance_amount =  $insurance_Details['insurance_fee']  ;  
								$Cart_details->insurance_fee =  $insurance_Details['insurance_fee']  ;

								if(empty($insurance_tier_type)){
									$insurance_fee  = 0 ;
								}else{
							  		$insurance_fee  =  number_format((float)(($insurance_tier_type->tiers_percentage *$insurance_days*$gear_details->per_day_cost_aud_ex_gst)/100) , 2, '.', '');
							  	}
						 	}
						 	// $gear_insurance_value = $Cart_details->insurance_amount;	
						 	$rent_master_details->other_charges	 =  '0' ;
						 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit  - $Cart_details->beta_discount  + $Cart_details->insurance_fee + $Cart_details->community_fee ; ; 
						 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
						 	if(empty($insurance_tier_type)){
								$Cart_details->insurance_tier_id  = 0 ;
							}else{
								$Cart_details->insurance_tier_id =  $insurance_tier_type->tiers_id  ; 
							 }
						 	
						 	$Cart_details->other_charges =  '0' ; 
						 	$Cart_details->security_deposit =  '0' ; 
						 	$Cart_details->deposite_status =  'NONE' ; 
						 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
						 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
			 		}

			 		if ($post_data['insurance_type'] == '3') { // Renter insurance/3rd party insurance
			 			$paymentDate = date('Y-m-d');
						$paymentDate=date('Y-m-d', strtotime($paymentDate));
							
						$where_clause = array(
												'app_user_id'=> $app_user_id ,
												'renter_insurance_provided' => 'Yes',
												'renter_insurance_status'=> '1' ,
												'is_visiible'=> 'Y' ,
												'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
												'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

											);
						$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
						$insurance =  $query->row();
						if(!empty($insurance)){
							$replacement_value_aud_ex_gst = 0 ;
							$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
							$insurance_type = $query->row();

						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'] ,'order_id ='=>''));
						 	$Cart_details = $query1->row();

						 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
						 	$rent_master_details = $query2->row();

						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id ,'ks_insurance_category_type_id'=>'3','order_id !=' =>'' ,'create_date' => date('Y-m-d')));
						 	$today_order_Details = $query1->row();
						 	$check_response =  $this->home_model->CheckRenterInsurancePolicyCalculation($app_user_id,$post_data['user_gear_desc_id']);
							
						 	if ($check_response != 1) {
								
								$response = array();
								$insurance_type = array();
						 		$response['status'] = 400;
								$response['status_message'] = 'Your Cart exceeds the Total Sum Insured on your Policy';
								
								$insurance_type['ks_insurance_category_type_id']=0;
								
								$data =  $this->GETCartCalculation($app_user_id);
								$data['cart_summary']['user_address_id'] = $data['Cart_details'][0]['user_address_id'] ;
								$response['result'] = array( 'user_address_id' =>$data['Cart_details'][0]['user_address_id'] ,'cart'=>$data['Cart_details'],'cart_summary'=>$data['cart_summary'] , 'app_users_details'=>$data['app_users_details'] ,'insurance_type'=>$insurance_type);
								
								$json_response = json_encode($response);
								echo $json_response;
								exit;
						 	}
							
						 	if (!empty($today_order_Details)) {
						 		$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$today_order_Details->user_gear_desc_id ));
							 	$Gear_details_today_order  = $query1->row();

							 	$replacement_value_aud_ex_gst += $Gear_details_today_order->replacement_value_aud_ex_gst ;
						 	}
						 
						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$post_data['user_gear_desc_id'] ));
						 	$Gear_details = $query1->row();

						 	$replacement_value_aud_ex_gst +=  $Gear_details->replacement_value_aud_ex_gst ;


						 	// if ($replacement_value_aud_ex_gst >  $insurance->owner_insurance_amount) {
						 	// 	$response['status'] = 404;
								// $response['status_message'] = 'Cannot checkout for this insurance';
								// $response['result'] = array();
								// $json_response = json_encode($response);
								// echo $json_response;	
								// exit();
						 	// }

						 	$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
							// print_r($insurance_Details['total_premium_ex_gst']);die;
							// $Cart_details->esl_rate =  $address_details->ks_state_esl ; 
							// $Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
							// $Cart_details->premium_base_rate =  $address_details->base_rate ; 

							// $Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
							// $Cart_details->esl_amount =  $insurance_Details['esl']  ; 
							// $Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
							// $Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
							// $Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
							// $Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
							$Cart_details->insurance_amount =  0   ; 
							$Cart_details->owner_insurance_amount = number_format((float)((0)) , 2, '.', ''); 
				 			$Cart_details->owner_insurance_status = 0 ;  
				 			$Cart_details->insurance_percentage = '0' ;  
						 	$Cart_details->other_charges =   '0' ;
						 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges; 
						 	$rent_master_details->other_charges = '0' ;
						 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount +  $rent_master_details->other_charges; 
						 	$Cart_details->insurance_fee = '0'; 
						 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
						 	$Cart_details->insurance_tier_id =  '0'  ; 
						 	$Cart_details->other_charges =  '0' ; 
						 	$Cart_details->security_deposit =  '0' ; 
					 		$insurance_type->amount ='0' ;
					 		$Cart_details->deposite_status =  'NONE' ; 
							$insurance_type->amount ='0' ;					 	
							
						 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
						 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);

						}else{

							$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
							$insurance_type = $query->row();

						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'] ,' order_id='=>''));
						 	$Cart_details = $query1->row();

						 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
						 	$rent_master_details = $query2->row();
						 	$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
							// print_r($insurance_Details['total_premium_ex_gst']);die;
							$Cart_details->esl_rate =  $address_details->ks_state_esl ; 
							$Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
							$Cart_details->premium_base_rate =  $address_details->base_rate ; 

							$Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
							$Cart_details->esl_amount =  $insurance_Details['esl']  ; 
							$Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
							$Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
							$Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
							$Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
							$Cart_details->insurance_amount =  $insurance_Details['insurance_fee']    ; 
							
			 
						 	$Cart_details->other_charges =  '0' ;
						 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges; 
						 	$rent_master_details->other_charges = '0' ;
						 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount +  $rent_master_details->other_charges; 
						 	
						 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
						 	$Cart_details->insurance_tier_id =  '0'  ; 
						 	$Cart_details->other_charges =  '0' ; 
						 	$Cart_details->security_deposit =  '0' ; 
				 			$Cart_details->deposite_status =  'NONE' ; 
						 	$insurance_type->amount = '0' ;
						 	$Cart_details->insurance_fee = '0'; 
						 	
						 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
						 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);

						}	
				
			 		}	
			 		if ($post_data['insurance_type'] == '4') { // NO insurance
					 		$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'], 'order_id ='=>''));
					 		$Cart_details = $query1->row();
							 
							$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
					 		$rent_master_details = $query2->row();

							$rent_master_details->other_charges = '0' ;
						 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
						 	$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
							// $Cart_details->esl_rate =  $address_details->ks_state_esl ; 
							// $Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
							// $Cart_details->premium_base_rate =  $address_details->base_rate ; 

							// $Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
							// $Cart_details->esl_amount =  $insurance_Details['esl']  ; 
							// $Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
							// $Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
							// $Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
							// $Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
							$Cart_details->insurance_amount =  0    ; 
							
						 	
						 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
						 	$Cart_details->insurance_tier_id =  '0'  ; 
						 	$Cart_details->other_charges =  '0' ; 
						 	$Cart_details->security_deposit =  '0' ; 
					 		$Cart_details->deposite_status =  'NONE' ; 
						 	$insurance_type->amount = '0'; ;
						 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
						 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);

			 		}
			 		if ($post_data['insurance_type'] == '5') { // security Deposit
							$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
							$rent_master_details = $query2->row();
							$gaer_data = $this->home_model->geratDetails($post_data['user_gear_desc_id']);

							$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount ; 
							// $Cart_details->insurance_amount =  $gaer_data->security_deposite_inc_gst; 
							$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
							
							$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
							// print_r($insurance_Details['total_premium_ex_gst']);die;
							// $Cart_details->esl_rate =  $address_details->ks_state_esl ; 
							// $Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
							// $Cart_details->premium_base_rate =  $address_details->base_rate ; 

							// $Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
							// $Cart_details->esl_amount =  $insurance_Details['esl']  ; 
							// $Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
							// $Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
							// $Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
							// $Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
							$Cart_details->insurance_amount =  0   ; 
							

							$Cart_details->insurance_tier_id =  '0'  ; 
							$Cart_details->other_charges =  '0' ; 
							$Cart_details->security_deposit =   $gaer_data->security_deposite_inc_gst ; 
							$Cart_details->deposite_status =  'MARK STORED' ;
							
							$insurance_type->amount = $gaer_data->security_deposite_inc_gst; ;
							$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
							if (!empty($rent_master_details)) {
							$rent_master_details->other_charges ='' ;
							$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
							}
			 		}

			 		if ($post_data['insurance_type'] == '8') { // Owner
			 				$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'], 'order_id ='=>''));
						 	$Cart_details = $query1->row();
						 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
						 	$rent_master_details = $query2->row();
						 	$date_from = strtotime($Cart_details->gear_rent_request_from_date);
						 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
						 	$diff = abs($date_to - $date_from);
							$date_array =  $this->GetRentDays($date_from,$date_to);
							// print_r($date_array);die;
							$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
						 	$gear_details = $query1->row();
													
						 	$query =  $this->common_model->GetAllWhere('ks_settings','');
						 	$settings = $query->row();
						 	
						 	
						 	$Cart = $this->home_model->getUserCart1($app_user_id);
						 	$where = array( 'app_user_id'=>$Cart[0]['app_user_id'] ,
						 					'is_visiible'=>'Y' ,
						 					'owner_insurance_provided'=>'Yes',
						 					'owner_insurance_status' =>'1' ,
						 					'ks_user_certificate_currency_start <= ' => date('Y-m-d') , 
						 					'ks_user_certificate_currency_exp >= ' => date('Y-m-d')  
						 		);
						 	
							$query1 =  $this->common_model->GetAllWhere('ks_user_insurance_proof' ,$where);
						 	$insurance_detail = $query1->row();
						 	
				 			if (empty($insurance_detail)) {
				 				$response['status'] = 400;
								$response['status_message'] = 'Owner Insurance Not found';
								$response['result'] = array();
								$json_response = json_encode($response);
								echo $json_response;
								exit;
				 			}
				 			$check_response =  $this->home_model->CheckOwnerInsurancePolicyCalulation($app_user_id,$post_data['user_gear_desc_id']);
						 	if ($check_response != 1) {
						 		$response['status'] = 400;
								$response['status_message'] = 'The Owner has exceeded the coverage on their Insurance Policy during this period. Please select another Insurance option for this listing';
								
						 	}
				 			$paymentDate = date('Y-m-d');
							$paymentDate=date('Y-m-d', strtotime($paymentDate));
							$contractDateBegin = date('Y-m-d', strtotime($insurance_detail->ks_user_certificate_currency_start));
							$contractDateEnd = date('Y-m-d', strtotime($insurance_detail->ks_user_certificate_currency_exp));
							if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
							 //  	if (count($date_array ) <= 1 ) {
								//  		# code...
							 // 	}elseif(count($date_array ) == 2 ){
							 // 			   $date_array;

							 // 	}else{
							 // 		foreach ($date_array as $value) {
							 // 			 $val_date[] =   $value['date'];
							 // 		}
							 // 		 $date_array =  $val_date;
								// 		array_pop($date_array); 
								// }							  
								// print_r($gear_details)	;
								  $insurance_days = $date_array;

								
						 		 $inusrance_value = ($gear_details->per_day_cost_aud_ex_gst * $insurance_detail->owner_insurance_percentage *$Cart_details->gear_total_rent_request_days)/100 ;
						 		 $Cart_details->owner_insurance_amount = number_format((float)(($inusrance_value)) , 2, '.', ''); 
						 		
								$Cart_details->insurance_fee =  0; 
								$Cart_details->insurance_amount =  0; 
			 					$gear_insurance_value = $Cart_details->insurance_amount;	
				 				$Cart_details->owner_insurance_status = 1 ;  
				 				$Cart_details->insurance_percentage = $insurance_detail->owner_insurance_percentage ;  
							 	$rent_master_details->other_charges	 =  '0' ;
							 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit  - $Cart_details->beta_discount  + $Cart_details->insurance_fee + $Cart_details->community_fee  ; 
								
								if ($check_response != 1) {
									$Cart_details->ks_insurance_category_type_id = 0; 
								}else
									$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']; 
								
							 	$Cart_details->insurance_tier_id =  0 ; 
							 	$Cart_details->other_charges =  '0' ; 
							 	$Cart_details->security_deposit =  '0' ; 
							 	$Cart_details->deposite_status =  'NONE' ;
								
								if(empty($response['status'])==true){
							 		$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
			 						$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
								}
							}else{
							  
								$response['status'] = 400;
								$response['status_message'] = 'Owner Insurance Expired';
								$response['result'] = array();
								$json_response = json_encode($response);
								echo $json_response;
								exit;
							}
				 			
			 		}



			 		$data =  $this->GETCartCalculation($app_user_id);
			 		$data['cart_summary']['user_address_id'] = $data['Cart_details'][0]['user_address_id'] ;
			 		if(empty($response['status'])==true){
			 			$response['status'] = 200;
						$response['status_message'] = 'User cart List';
					}
					
					$response['result'] = array( 'user_address_id' =>$data['Cart_details'][0]['user_address_id'] ,'cart'=>$data['Cart_details'],'cart_summary'=>$data['cart_summary'] , 'app_users_details'=>$data['app_users_details'] ,'insurance_type'=>$insurance_type);
					$json_response = json_encode($response);
					echo $json_response;
			 	
			 	
		}else{
				/*$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;*/
				header('HTTP/1.1 400 Session Expired');
				exit();
		}
		
		}else{
			
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}



	public function GETCartCalculation($app_user_id)
	{
			$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
			if (count($Cart_details)) {
					 $total_rent_amount_ex_gst = '0';
					 $gst_amount = '0';
					 $other_charges = '0';
					 $insurance_amount = '0';
					 $owner_insurance_amount = 0;
					 $total_rent_amount = '0';
					 $security_deposit = '0';
					 $i= 0 ;
					 
					 $query =  $this->common_model->GetAllWhere('ks_settings','');
					 $settings =  $query->row();

					 
				 	 foreach ($Cart_details as  $value) {
			 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
			 			 $gst_amount  += $value['gst_amount'] ; 
			 			 $other_charges  += '0' ; 
			 			 $total_rent_amount  += $value['total_rent_amount'] ; 
			 			 $insurance_amount  += number_format((float)$value['insurance_amount'] , 2, '.', ''); 
			 			 $owner_insurance_amount  += number_format((float)$value['owner_insurance_amount'] , 2, '.', ''); 
						 $security_deposit += $value['security_deposit'];
						 //If Owner Insurance amount is greater than 0 and insurance amount is 0 then insurance amount will have owner's insurance amount
						 if($value['owner_insurance_amount']>0 && $value['insurance_amount']==0)
						 	$Cart_details[$i]['insurance_amount'] = $value['owner_insurance_amount'];	
						//This piece of code is for front end display as from insurance amount they are displaying the amount	
						//Code ends///
						  
			 			 
						 
						 if ($value['security_deposit_check'] != '1') {
			 				 $Cart_details[$i]['security_deposit']   = '0.00';
			 			}
						 
			 			 $app_user_id_array[] = $value['app_user_id'] ;
			 			if ($value['gear_display_image'] == '') {
			 			 	 $Cart_details[$i]['gear_display_image']   = BASE_URL."/site_upload/gear_images/default_product.jpg";
			 			 }else{
			 			 	if(file_exists(BASE_IMG.'site_upload/gear_images/'.$value['gear_display_image'])) {
								$Cart_details[$i]['gear_display_image'] = GEAR_IMAGE.$value['gear_display_image'] ;
		 					}else{
		 						$Cart_details[$i]['gear_display_image'] = BASE_URL."/site_upload/gear_images/default_product.jpg";
		 					}
			 			}
						 
						 $query = $this->common_model->GetAllWhere('ks_gear_categories',array( 'security_deposit'=>'Y','is_active'=>'Y', 'gear_category_id'=>$value['ks_sub_category_id']));
						$security_deposit_check = $query->row();
			 			
						if (!empty($security_deposit_check)) {

							if ($value['security_deposit_check'] == '0') {
								 $Cart_details[$i]['insurance_check_key']   = 2;
							}else{
								 $Cart_details[$i]['insurance_check_key']   = 0;
							}
							
						}else{
							if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value) {
			 				 	$Cart_details[$i]['insurance_check_key']   = 1;	
				 			}else{
				 				$Cart_details[$i]['insurance_check_key']   = 0;	
				 			}
						}
						
							if (!empty($Cart_details[$i]['ks_insurance_category_type_id'])) {
								$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active' ,'ks_insurance_category_type_id'=> $Cart_details[$i]['ks_insurance_category_type_id']) ) ; 
								$insurance_type1 = $query->row();
								if (empty($insurance_type1)) {
									$Cart_details[$i]['insurance_type_name'] =  '' ;
									//$Cart_details[$i]['insurance_type_description'] =  '' ;
									$Cart_details[$i]['description']['message'] = "";
									$Cart_details[$i]['description']['error'] = "";
									
								}else{
									$Cart_details[$i]['insurance_type_name'] =  $insurance_type1->name ;
									//$Cart_details[$i]['insurance_type_description'] =  $insurance_type1->description ;
									$Cart_details[$i]['description']['message'] =  $insurance_type1->description;
									$Cart_details[$i]['description']['error'] = "";
								}	
								
							}else{
							
								$Cart_details[$i]['description']['message'] = "";
								$Cart_details[$i]['description']['error'] = "";
							}
	
							//IF there is no insurance the message will be customed
							if($Cart_details[$i]['insurance_check_key']==2){
								$Cart_details[$i]['description']['message'] = "Insurance is not required to rent this listing";
								$Cart_details[$i]['description']['error'] = "";
							}
							
							if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value && $Cart_details[$i]['insurance_check_key']==1) {
								//Renter insurance Check 
								$where_clause = array(
										'app_user_id'=> $app_user_id ,
										'renter_insurance_provided' => 'Yes',
										'renter_insurance_status'=> '1' ,
										'is_visiible'=> 'Y' ,
										'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
										'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

									);

									$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause);
									$renter_insurance =  $query->row();
									
									if (empty($renter_insurance)) {
									
										//Check For Owner Insurance 
										$where = array( 'app_user_id'=>$value['app_user_id'] ,
														'is_visiible'=> 'Y' ,
														'owner_insurance_provided'=>'Yes',
														'owner_insurance_status' =>'1',
														'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
														'ks_user_certificate_currency_start <= ' =>date('Y-m-d'), 
														);
										$query1 =  $this->common_model->GetAllWhere('ks_user_insurance_proof' ,$where);
										$owner_insurance_detail = $query1->row();

										if (empty($owner_insurance_detail)) {
											//$Cart_details[$i]['insurance_type_description'] =  'This listing exceeds the limit of liability for Kitshare Insurance' ;
											//$Cart_details[$i]['error']   = 'Please upload a certificate of currency to rent this listings.' ;		
											$Cart_details[$i]['description']['message']='This listing exceeds the limit of liability for Kitshare Insurance';	
											$Cart_details[$i]['description']['error']='Please upload a certificate of currency to rent this listings.';	
										}
										
									}else{
										$Cart_details[$i]['description']['error'] = ' ' ;		
									}
									
									
				 			}else{
				 				 $Cart_details[$i]['description']['error'] = '';	
				 			}
						
							//Conditions start here
							$where_array = "status='Active'";
						
							//if $value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value then the dropdown won't have Kitshare Insurance Policy in it							
							// if($Cart_details[$i]['insurance_check_key'] == 1){
								
							// 	$where_array .= " AND ks_insurance_category_type_id!='1'";
							// }
			 				
							// $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , $where_array ) ; 
							// $insurance_type = $query->result();	
							if ($Cart_details[$i]['insurance_check_key'] ==2) {
								$category_array = array();
							}
							elseif ($value['security_deposit'] >  "0.00") {
								$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>5))	;
								$category_array = $query->result_array();
								// $category_array = array();
							}else{
								$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>1))	;
								$category_array = $query->result_array();
								$query_sett=   $this->common_model->GetAllWhere('ks_settings', '');	
								$settings = $query_sett->row();
								if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value) {
									$category_array = array();
								}
								$where_clause = array(
													'app_user_id'=> $app_user_id ,
													'renter_insurance_provided' => 'Yes',
													'renter_insurance_status'=> '1' ,
													'is_visiible'=> 'Y' ,
													'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
													'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

												);
								$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
								$renter_insurance  =  $query->row();
								if (!empty($renter_insurance)) {
									$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>3))	;
									$renter_insurance = $query->row();	
									array_push($category_array, $renter_insurance);
								}
								//Owner inurance
								$Cart_details1 = 	 $this->home_model->getUserCart1($app_user_id);
						 		$where_clause = array(
															'app_user_id'=> $Cart_details1[0]['app_user_id'] ,
															'owner_insurance_provided' => 'Yes',
															'owner_insurance_status'=> '1' ,
															'is_visiible'=> 'Y' ,
															'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
															'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

														);
								$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
								$owner_insurance  =  $query->row();
								if (!empty($owner_insurance)) {
									$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>8))	;
									$owner_insurance = $query->row();							
									array_push($category_array, $owner_insurance);
								}
							}	

							$Cart_details[$i]['insurance_type']  = $category_array;	
							// $Cart_details[$i]['insurance_type']  = $insurance_type;
						 	unset($Cart_details[$i]['primary_email_address']); 
			 			 $i++;
			 		 }	

			 		if (!empty($app_user_id_array)) {	
			 			$app_user_id_array =  array_unique($app_user_id_array);
						$app_user_ids = '' ;
						foreach ($app_user_id_array as $app_user) {
							$app_user_ids.= "'". $app_user."',";
						}

						$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
						$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
						if (empty($app_users_details[0]->user_profile_picture_link)) {
							$app_users_details[0]->user_profile_picture_link = BASE_URL."site_upload/profile_img/profile-default-pic.png" ;
						}
			 		}
			 		$beta_discount = ($total_rent_amount_ex_gst*15)/100 ; 
			 		$insurance_fee = 0; 
			 		$community_fee = ($total_rent_amount_ex_gst*5)/100 ; 
			 		 $gst_amount = ($total_rent_amount_ex_gst -$beta_discount + $insurance_amount +  $insurance_fee + $community_fee + $owner_insurance_amount)*10/100;
			 		$rent_summary = array(
			 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
			 								'gst_amount'=>$gst_amount,
			 								'beta_discount'=> $beta_discount,
			 								'insurance_fee'=> number_format((float)($insurance_amount), 2, '.', '') ,
			 								'owner_insurance_amount'=>  number_format((float)($owner_insurance_amount), 2, '.', '') ,
			 								'community_fee'=> $community_fee,
			 								'other_charges'=>$other_charges,
			 								'sub_total'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_amount + $community_fee + $owner_insurance_amount,
			 								'total_rent_amount'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_amount + $community_fee + $gst_amount + $owner_insurance_amount,
											'security_deposit' => $security_deposit,		 								
			 								'total_amount'=>  $total_rent_amount_ex_gst -$beta_discount + $insurance_amount + $community_fee + $gst_amount + $owner_insurance_amount 
			 							);
					$data['Cart_details'] = $Cart_details ;
					$data['cart_summary'] = $rent_summary ;
					$data['app_users_details'] = $app_users_details ;
					return $data ;
				}
	}

	public function BaseInsuranceCacluation($Cart_details , $address_details )
	{
			//Insurance Day Calculation
							$date_from = strtotime($Cart_details->gear_rent_request_from_date);
						 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
						 	$diff = abs($date_to - $date_from);
							$date_array =  $this->getDateList($date_from,$date_to);
							if (count($date_array ) <= 1 ) {
						 		
						 	}elseif(count($date_array ) == 2 ){
						 			   $date_array;
						 	}else{
						 		foreach ($date_array as $value) {
						 			 $val_date[] =   $value['date'];
						 		}
						 		 $date_array =  $val_date;
									array_pop($date_array); 
							}						  
							$insurance_days = count($date_array);
							$query =  $this->common_model->GetAllWhere('ks_settings','');
							$settings = $query->row();
							$query2 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$Cart_details->user_gear_desc_id));
							$gear_details = $query2->row();
							$base_amount =  number_format((float)(($insurance_days *  $address_details->base_rate * $gear_details->per_day_cost_aud_ex_gst)) , 2, '.', ''); 
							$esl = 	 number_format((float)(($base_amount * $address_details->ks_state_esl)) , 2, '.', ''); 
							$stamp_duty =  (($base_amount + $esl )+ (($base_amount + $esl)/$settings->gst_percent) ) *  $address_details->ks_state_sd; 
							$stamp_duty = 	 number_format((float)(($stamp_duty)) , 2, '.', ''); 
							$total_premium_ex_gst = $base_amount + $esl + $stamp_duty  ;
							$premium_gst = ($base_amount + $esl)/$settings->gst_percent ;
							 $total_premium_inc_gst = $total_premium_ex_gst + $premium_gst   ;
							$insurance_fee = $total_premium_inc_gst/1.1  ;
							$data['base_amount'] = $base_amount;
							$data['esl'] = $esl;
							$data['stamp_duty'] = $stamp_duty;
							$data['total_premium_ex_gst'] = $total_premium_ex_gst;
							$data['premium_gst'] = $premium_gst;
							$data['insurance_fee'] = $insurance_fee;
							$data['total_premium_inc_gst'] = $total_premium_inc_gst;
							
							return $data ;
	}

	// GET day and dates between 2 days 
	public function getDateList($date_from,$date_to)
	{
		
			$dates = array();
			while($date_from <= $date_to)
			{
				$values= array( 'date'=>date( 'Y-m-d',$date_from) ,
						'day'=>date('D', strtotime(date( 'Y-m-d',$date_from)))
					)  ; 

			    array_push( $dates,$values);
			    $date_from += 86400;
			}
			return $dates ;
	}
	public function GetRentDays($date_from,$date_to)
	{
		
		$total_days = 0 ;
        $count = 0;
        $count1 = 0;
		$diff = abs($date_to -$date_from);
        $days = floor(($diff)/ (60*60*24));
        if ($days > 1) {
           $days =   $days +1-2 ; 
        }else{
             $days =   1;
        }
        $tola_days_reamining =   $days%7 ;  
        $tola_week =  floor( $days/7) ;
        if  ($tola_week > 0 ){
             $total_days = $tola_week*3 ;
        }
          if ($tola_days_reamining  > 1 && $tola_days_reamining <= 6  ) {
            for ($i=1; $i <= $tola_days_reamining ; $i++) { 
                $days1[] =   date('D', strtotime('+'.$i.' day', $date_from));
            }
            $count = 0;
            $count1 = 0;
            foreach ($days1 as $value) {
                if ($value == 'Sat'  || $value == 'Sun'  ) {
                    $count1 += 1; 
                }else{
                    $count += 1;
                    if($count >= 3)
                        break;
                }
            }
            $next_day =  date('D', strtotime('+1 day', strtotime($date_from)));
             if ( $count1 >=1 && $count < 3) {
                 $count = $count + 1 ;
             }
             return $count+ $total_days  ;    

        }elseif($tola_days_reamining == '1'  && $tola_week ==  0 || $tola_days_reamining == '0'  &&  $tola_week ==  0 ){
            $count = 1 ;
        }elseif($tola_days_reamining == '1' &&  $tola_week  > 0     ){
            $count = 1 ;
        }
        return   $count+ $total_days  ;       
	}
	
	function userinfo($token){
	
		$app_user_id = $this->common_model->fetchTokenDetails($token);
		return $app_user_id;

	}

	
	
}
?>