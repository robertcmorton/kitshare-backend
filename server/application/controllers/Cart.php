<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends CI_Controller {
	
	 public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email','upload','pagination'));
		$this->load->model(array('common_model','home_model'));
	}
	
	
	/// function to Check User Checkout Details 
	public function Usercheckoutcheck()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 		= $post_data['token'];
		$app_user_id = $this->common_model->fetchTokenDetails($token);
		if ($app_user_id != '') {
			$query =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
			
			//User details are fetched along with the address details
			$where_clause = array('ks_users.app_user_id'=>$app_user_id,'ks_user_address.default_address'=>'1');
		
			$this->db->select('ks_users.*,ks_user_address.user_address_id');		
			$this->db->from('ks_users');
			$this->db->join("ks_user_address","ks_users.app_user_id=ks_user_address.app_user_id");
			$this->db->where($where_clause);
			$this->db->group_by('ks_user_address.user_address_id');
			$query = $this->db->get(); 
			
			if($query->num_rows()>0){
				
				$user_details =  $query->row();
				
			}else{
				
				$app_user_id = '';
				$response['status'] = 400;
				$response['status_message'] = 'Address has not been verified';
				$response['button_status'] = false;
				$json_response = json_encode($response);
				echo $json_response;
				exit();
				
			}
			
			
			
			if ($user_details->mobile_number_verfiy != 1) {
					$app_user_id = '';
					$response['status'] = 400;
					$response['status_message'] = 'Mobile number is not verified!';
					$response['button_status'] = false;
					$json_response = json_encode($response);
					echo $json_response;
					exit();
			}
			if ($user_details->aus_post_verified !='Y') {
							$app_user_id = '';
							$response['status'] = 400;
							$response['status_message'] = 'Digital ID is not verified!';
							$response['button_status'] = false;
							$json_response = json_encode($response);
							echo $json_response;
							exit();
			}
			$Cart_details = $this->home_model->getUserCart1($app_user_id);
			
			$sum = 0 ;
			$query =  $this->common_model->GetAllWhere('ks_settings','');
			$settings =  $query->row();
			foreach ($Cart_details as $value) {
			
					
		
					$query = $this->common_model->GetAllWhere('ks_gear_categories',array( 'security_deposit'=>'Y','is_active'=>'Y', 'gear_category_id'=>$value['ks_sub_category_id']));
					$security_deposit_check = $query->row();
			 			
					if (!empty($security_deposit_check)) {

						if ($value['security_deposit_check'] == '0') {
							 $insurance_check_key  = 2;
						}else{
							 $insurance_check_key   = 0;
						}
						
					}else{
						if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value && $value['ks_insurance_category_type_id']==0) {
							$insurance_check_key   = 1;	
						}else{
							$insurance_check_key   = 0;	
						}
					}
					
					$insurance_amount = $value['insurance_amount'];
					
					//If Owner Insurance amount is greater than 0 and insurance amount is 0 then insurance amount will have owner's insurance amount
					if($value['owner_insurance_amount']>0 && $value['insurance_amount']==0)
						 $insurance_amount = $value['owner_insurance_amount'];	
					 
					 
					 
					//Checked whether User Address Id is blank or not
					if(empty($value['user_address_id'])==true && $value['ks_insurance_category_type_id']>0){
						
							if(empty($value['user_address_id'])==true)
								$msg = "Select a Pickup / Return address";
							else
								$msg = '';
							$app_user_id = '';
							$response['status'] = 400;
							$response['status_message'] = $msg;
							$response['button_status'] = false;
							$json_response = json_encode($response);
							echo $json_response;
							exit();
					 
					}
			

					if ($insurance_check_key == 1){
					
						if($value['ks_insurance_category_type_id']==0){
						
								$response['status'] = 400;
								$response['status_message'] = 'Please select an Insurance option for this listing';
								$response['button_status'] = false;
								$json_response = json_encode($response);
								echo $json_response;
								exit();		
						
						}else{
					
							if($value['ks_insurance_category_type_id']==8){
								$check_response = $this->home_model->CheckOwnerInsurancePolicyCalulation($app_user_id, $value['user_gear_desc_id']);
								
								if($check_response == false){
								
									$response['status'] = 400;
									$response['status_message'] = 'The Owner has exceeded the coverage on their Insurance Policy during this period. Please select another Insurance option for this listing';
									$response['button_status'] = false;
									$json_response = json_encode($response);
									echo $json_response;
									exit();							
								
								}
							}
						}
					
					}
					
					//For Renter Insurance					
					if($value['ks_insurance_category_type_id']==3){
						$check_response =  $this->home_model->CheckRenterInsurancePolicyCalculation($app_user_id,$value['user_gear_desc_id']);
						
						if($check_response == false){
							
							$response['status'] = 400;
							$response['status_message'] = 'Your Cart exceeds the Total Sum Insured on your Policy';
							$response['button_status'] = false;
							$json_response = json_encode($response);
							echo $json_response;
							exit();							
							
						}
					}
					
					
					if ($insurance_check_key == 1 && ($insurance_amount == 0.00 || $insurance_amount == "")) 
					{//If replacement value is greater than max replacement value set from the admin then 
					//following error message will be generated
					
							$app_user_id = '';
							$response['status'] = 400;
							$response['status_message'] = ' The Replacement value for the total cart must not exceed  '.$settings->max_replacement_value .' ex GST!';
							$response['button_status'] = false;
							$json_response = json_encode($response);
							echo $json_response;
							exit();
					 }
					 
					 if ($insurance_check_key == 0 && ( $value['insurance_amount'] == 0.00 ||  $value['insurance_amount'] == "") && $value['security_deposit_check']==0 && ($value['ks_insurance_category_type_id']==0 || $value['ks_insurance_category_type_id']=="")) {
					 
					 		$app_user_id = '';
							$response['status'] = 400;
							$response['status_message'] = 'Insurance type is not selected';
							$response['button_status'] = false;
							$json_response = json_encode($response);
							echo $json_response;
							exit();
							
					 }else if($insurance_check_key == 0 && ( $value['insurance_amount'] == 0.00 ||  $value['insurance_amount'] == "") && $value['security_deposit_check']==0 && $value['ks_insurance_category_type_id']>0 && $value['owner_insurance_amount']==0){
					 		
							$check_response =  $this->home_model->CheckRenterInsurancePolicyCalculation($app_user_id,$value['user_gear_desc_id']);
							
							if ($check_response != 1) {
							
						 		$response['status'] = 400;
								$response['status_message'] = 'Your Cart exceeds the Total Sum Insured on your Policy';
								$response['button_status'] = false;
								$json_response = json_encode($response);
								echo $json_response;
								exit();
							}
							
					 }
					 
					 //Checked whether User Address Id is blank or not
					 if(empty($value['user_address_id'])==true){
							$app_user_id = '';
							$response['status'] = 400;
							$response['status_message'] = 'Pick Up/Drop Off Address is left blank!';
							$response['button_status'] = false;
							$json_response = json_encode($response);
							echo $json_response;
							exit();
					 
					 }
					 
					 //If Pickup from date and Pickup to date
					 if(empty($value['gear_rent_request_from_date']) == true || empty($value['gear_rent_request_to_date']) == true){
					 
					 	$app_user_id = '';
						$response['status'] = 400;
					 	if(empty($value['gear_rent_request_from_date']) == true){
						
							$response['status_message'] = 'Pickup date is left blank';
							
						}else if(empty($value['gear_rent_request_to_date']) == true){
						
							$response['status_message'] = 'Return date is left blank';
							
						}
						$response['button_status'] = false;
						$json_response = json_encode($response);
						echo $json_response;
						exit();
					 
					 }
					 
			}
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'User can Checkout';
				$response['button_status'] = true;
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			
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
	
	
	public function itemCalculation()
	{	
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		if(in_array("token",$post_data)){
			$token 				= $post_data['token'];
			$app_user_id 		= $this->userinfo($token);
		}else
			$app_user_id = "";
		
		$user_gear_desc_id 	= $post_data['user_gear_desc_id'];
		
		$dateflag = 0;
		//if ($app_user_id != '') {

				$date_from 	=  strtotime($post_data['date_from']);
				$date_to 	=  strtotime($post_data['date_to']);
				
				
				//User Gear Category ID is fetched
				$where_clause = "user_gear_desc_id='".$user_gear_desc_id."'";
				$query = $this->common_model->GetSpecificValues("ks_category_id,per_day_cost_aud_inc_gst,per_day_cost_aud_ex_gst","ks_user_gear_description",$where_clause);
				$row = $query->result_array();
				$ks_category_id = $row[0]['ks_category_id'];
				
				if($date_from  == $date_to ){
					
					$current_time = date('H:i:s');
					
					$curdate = strtotime(date('Y-m-d'));
					 
					if($current_time >= '22:00:00' && $date_from==$curdate){
					
						$date_from 	= date('Y-m-d', strtotime($post_data['date_from'] . ' +1 day'));
						$date_to 	= date('Y-m-d', strtotime($post_data['date_to'] . ' +1 day'));
						$date_from 	= strtotime($date_from);
						$date_to 	= strtotime($date_to);
						
					}else 
					
					if($date_from==$curdate){
					
						$date_from 	= strtotime(date('Y-m-d', strtotime($post_data['date_from'])));
						$date_to 	= strtotime(date('Y-m-d', strtotime($post_data['date_to'])));
						
						//If pickup date is today's date then this flag is turned on
						$dateflag = 1;
						
					}else{

						$dateflag = 1;
					}
					
				}else
					$dateflag = 2;
				
				$dates_array 	= $this->getDateList($date_from,$date_to);
				
				$gaer_data 		= $this->home_model->geratDetails($user_gear_desc_id);
				
				if($app_user_id!=""){
					$query 			= $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
					$user_details 	= $query->row();
				}
				
 				$query1			= $this->common_model->GetAllWhere('ks_settings',"");
 				$setting 		= $query1->row();
 				if ($gaer_data->ks_gear_type_id == '1') {
 					$rentDays 	= count($dates_array);
 				}else{
 					$rentDays 	= $this->GetRentDays($date_from,$date_to);
 				}
 				$date_array 	= $this->getDateList($date_from,$date_to);
				
				$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$user_gear_desc_id));
			 	$gear_details = $query1->row();
				if (count($date_array ) <= 1 ) {
			 		# code...
			 	}elseif(count($date_array ) == 2 ){
			 			   $date_array;

			 	}else{
			 		 $val_date = array();
			 		foreach ($date_array as $value) {
			 			 $val_date[] =   $value['date'];
			 		}
			 		$date_array =  $val_date;
					array_pop($date_array); 
				}
				$insurance_days = count($date_array);

				$gaer_data->per_day_cost_aud_ex_gst;

 				$security_deposit =  $gaer_data->security_deposite_inc_gst;
 				$total_cost_ex_gst = $gaer_data->per_day_cost_aud_ex_gst * $rentDays ;
				
				$total_cost_non_gst = $total_cost_ex_gst ;
				$beta_discount = ($total_cost_non_gst*15)/100; 
			 	$community_fee = ($total_cost_non_gst*5)/100;		
				$insurance_fee = 0;
				
				
				if ($app_user_id!="" && $user_details->registered_for_gst != 'Y' ) {
					 $totla_gst =   ((($total_cost_ex_gst + $insurance_fee + $community_fee -$beta_discount)*$setting->gst_percent  )/100 ) ;
				}else{
					 $totla_gst =   ((($total_cost_ex_gst + $insurance_fee + $community_fee -$beta_discount)*$setting->gst_percent  )/100 ) ;
				}
				$total_gst =  $totla_gst ;
				$total_cost_with_gst = $total_cost_ex_gst +  $totla_gst;
				$total_gst =  $totla_gst ;
				 
			 	$sub_total = $total_cost_ex_gst - $beta_discount + $insurance_fee + $community_fee ;
			 	
			 	$total_cost_with_gst = $sub_total  +  $totla_gst;
				
				//Pickup time will always be 14:00 hrs. and Drop off will be 12:00 hrs if it is not Creative Space and if it is not a Single day Rent
				if($ks_category_id == 162){
					
					$date_from		= 	date('Y-m-d',$date_from);
					$date_to		= 	date('Y-m-d',$date_to);
				
				}else{
				
					if($dateflag == 1){
					
						$date_from		= 	date('Y-m-d',$date_from);
						$date_to		= 	date('Y-m-d',$date_to);
					
					}else{
						$date_from		= 	date('Y-m-d',$date_from);
						$date_to		= 	date('Y-m-d',$date_to);
					}
					
				}
				
				$result['date_from']			= $date_from;
				$result['date_to']				= $date_to;	
				$result['day_rate_inc_gst']		= number_format($row[0]['per_day_cost_aud_inc_gst'],2); 
				$result['day_rate_ex_gst']		= number_format($row[0]['per_day_cost_aud_ex_gst'],2); 
				$result['rent_days'] 			= $rentDays;			
				$result['security_deposit']		= number_format($security_deposit,2);
				$result['total_cost_non_gst']	= number_format($sub_total,2);
				$result['total_gst']			= number_format($total_gst,2);
				$result['beta_discount']		= number_format($beta_discount,2);
				$result['community_fee']		= number_format($community_fee,2);
				$result['insurance_fee']		= number_format($insurance_fee,2);
				$result['subtotal']				= number_format($total_cost_ex_gst,2);
				$result['total_cost_with_gst']	= number_format($total_cost_with_gst,2);
				
				$response['status'] = 200;
				$response['status_message'] = 'Item Details';
				$response['result'] = $result;
				$json_response = json_encode($response);
				echo $json_response;
				

			/*}else{
					header('HTTP/1.1 400 Session Expired');
					exit();
			}*/	
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
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
		
		$total_days = '' ;
        $count = '';
        $count1 = '';
		$diff = abs($date_to-$date_from);
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
             return (int)$count+ (int)$total_days  ;    

        }elseif($tola_days_reamining == '1'  && $tola_week ==  0 || $tola_days_reamining == '0'  &&  $tola_week ==  0 ){
            $count = 1 ;
        }elseif($tola_days_reamining == '1' &&  $tola_week  > 0     ){
            $count = 1 ;
        }
        return  (int)$count+ (int)$total_days  ;       
	}
	
	function userinfo($token){
	
		$app_user_id = $this->common_model->fetchTokenDetails($token);
		return $app_user_id;
		
	}
	
}