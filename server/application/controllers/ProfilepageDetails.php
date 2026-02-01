<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class ProfilepageDetails extends CI_Controller {
 
    public function __construct() {
        header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->model(array('common_model','home_model'));
		$this->load->library(array('email','upload','image_lib'));
		
    }
 
    // upload xlsx|xls file
    public function index() {
       
    }
    // add user address 
    public function Adduseraddress()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
			
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		
		if ($app_user_id == 0 || $app_user_id == "") {
			
			/*$response['status'] = 400;
			$response['status_message'] = "User not found";*/
			
			header('HTTP/1.1 400 Session Expired');
			exit();
			
			
		}else{
		$query =$this->common_model->GetAllWhere('ks_user_address',array('app_user_id'=>$app_user_id,'is_active'=>'Y'));
		$value_check = $query->result_array();
		
		$count = count($value_check);

		 foreach($post_data['data'] As $data_array){ 

		 	$queryks_suburbs =$this->common_model->GetAllWhere('ks_countries',array('ks_country_name'=>trim($data_array['ks_country_name'])));
			$ks_country_details = $queryks_suburbs->row();
			if (empty($ks_country_details)) {
				$country_data = array(
									'ks_country_name' =>trim($data_array['ks_country_name']) ,
									'is_active' => 'Y' ,
									'create_date' => date('Y-m-d'),
									'create_user' => $app_user_id
								);
				 $ks_country_id =  $this->common_model->AddRecord( $country_data,'ks_countries');
			}else{
				
				 $ks_country_id =$ks_country_details->ks_country_id;
			}

			$queryks_states =$this->common_model->GetAllWhere('ks_states',array('ks_state_name'=>trim($data_array['ks_state_name'])));
			$ks_states = $queryks_states->row();
			if(empty($ks_states)){
				$state_data = array(
									'ks_state_code' => '',
									'ks_state_name' => trim($data_array['ks_state_name']),
									'ks_country_id' => $ks_country_id ,
									'ks_state_esl' => '0',
									'ks_state_sd' => '0',
									'base_rate' => '0',
									'is_active'=>'Y',
									'create_user'=> $app_user_id ,
									'create_date'=> date('Y-m-d')
							);
				 $ks_state_id =  $this->common_model->AddRecord( $state_data,'ks_states');
			}else{
				$ks_state_id =$ks_states->ks_state_id;
			}
			

		 	$queryks_suburbs =$this->common_model->GetAllWhere('ks_suburbs',array('suburb_name'=>trim($data_array['suburb_name'])));
			$ks_suburbs_details = $queryks_suburbs->row();
			if (empty($ks_suburbs_details)) {
					$suburb_data = array(
										'ks_state_id' =>$ks_state_id ,
										'suburb_name' =>trim($data_array['suburb_name']),
										'suburb_postcode' => $data_array['postcode'],
										
										'latitude'=>$data_array['lat'],
										'longitude'=>$data_array['lng'],
										'is_active'=>'Y',
										'create_user'=>$app_user_id,
										'create_date'=>date('Y-m-d')
									);
					$ks_suburb_id =  $this->common_model->AddRecord( $suburb_data,'ks_suburbs');
			}else{
				$ks_suburb_id = $ks_suburbs_details->ks_suburb_id ;
			}
			
			$string = '';
		 	
			//If there is no record corresponding to that user then the provided address will become default address.
			if($count==0)
				$default_address= '1';
		 	else if (!empty($data_array['is_default'])) {
		 		if($data_array['is_default']==true)
		 			$default_address= '1';
		 		else
		 			$default_address= '0';
		 	}else{
		 		$default_address= '0';
		 	}

		 	// $lat_lng=   $this->GetLatLngFromAddress($string);
		 	
		$data = array(
				'app_user_id'=>$app_user_id,
				
				'street_address_line2'=>trim($data_array['street_address_line2']),
				'route'=>trim($data_array['route']),
				'default_address'=>$default_address,
				'postcode'=>trim($data_array['postcode']),
				'lat'=>$data_array['lat'],
				'lng'=>$data_array['lng'],
				
				'default_address'=>$default_address,
				'ks_country_id'=>$ks_country_id,
				'ks_state_id'=>$ks_state_id,	
				'ks_suburb_id'=>$ks_suburb_id,
				'create_date'=>date('Y-m-d'),
		 		'is_active'=>'Y',
		 		);
		if (array_key_exists("street_address_line1",$data_array)) {
			$data['street_address_line1'] = trim($data_array['street_address_line1']) ; 
		}
		if (array_key_exists('administrive_area_level_2' ,$data_array)) {
			$data['administrive_area_level_2'] = trim($data_array['administrive_area_level_2']) ; 
		}
		if (array_key_exists('timezone',$data_array)) {
				$data['timezone']  =  trim($data_array['timezone']) ;
		}
		
		 	$table= 'ks_user_address';
		 	$where = array(
		 					'ks_country_id'=>$ks_country_id,
							'ks_state_id'=>$ks_state_id,
							'ks_suburb_id'=>$ks_suburb_id,
							'street_address_line2'=>trim($data_array['street_address_line2']),
							'postcode'=>trim($data_array['postcode']),
							'route'=>trim($data_array['route']),
							'is_active'=> 'Y',
							'app_user_id'=>$app_user_id
		 					);
		 		if (array_key_exists("street_address_line1",$data_array)) {
					$where['street_address_line1'] = trim($data_array['street_address_line1']) ; 
				}
				if (array_key_exists('administrive_area_level_2' ,$data_array)) {
					$where['administrive_area_level_2'] = trim($data_array['administrive_area_level_2']) ; 
				}
				
		 	$this->db->Select('*');
		 	$this->db->from($table);
		 	$this->db->where($where);
		 	$query = $this->db->get();
			$address_check =  $query->result_array();
			// echo $this->db->last_query();
			// print_r($address_check);
			
		 	if (!empty($address_check)) {
		 		$data_response = 0 ;
		 			// $data_response =	$this->common_model->UpdateRecord($data , 'ks_user_address' , 'user_address_id', $data_array['user_address_id']);	
		 	}else{
		 		$query =  $this->common_model->GetAllWhere($table,array('app_user_id'=>$app_user_id,'is_active'=>'Y'));
		 		$address_details =  $query->result();
		 		if (empty($address_details) && count($post_data['data']) =='1') {
		 			$data['default_address'] ='1';
		 		}else{
		 			$data['default_address'] ='0';
		 		}
		 			$data_response = $this->common_model->InsertData($table,$data);
			}
		 }	
		
			
			
			if ($data_response > 0) {
				$response['status'] = 200;
				$response['status_code']='success';
				$response['status_message'] = "Address added sucessfully";
			}else{
				$response['status'] = 400;
				$response['status_code']='failed';
				$response['status_message'] = "Something went wrong";
			}

		}
		echo json_encode($response);
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
    }

    public function  GetLatLngFromAddress($string='')
    {
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($string)."&key=AIzaSyCPU8keawNREwb4_tHM8D1mcw4bRuSEoUQ",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "",
			  CURLOPT_HTTPHEADER => array(
			    "Postman-Token: 8f73ce74-3855-44cb-8b1e-79469ad9a72f",
			    "cache-control: no-cache"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
				$response =  json_decode($response, true) ; 
			   return $response['results'][0]['geometry']['location'];
			}
    }
    public function GetUseraddress()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id == 0 || $app_user_id == "") {
			
			//$response['status'] = 400;
			//$response['status_message'] = "User not found";
			
			header('HTTP/1.1 400 Session Expired');
			exit();
						
		}else{
			$table = 'ks_user_address';
			$where_clause = array('app_user_id'=> $app_user_id ,'is_active'=> 'Y');
			
			$sql = "SELECT k_u_a.*,k_s.ks_state_name,k_sub.suburb_name ,k_coun.ks_country_name FROM ks_user_address As k_u_a INNER JOIN ks_states AS k_s ON  k_u_a.ks_state_id = k_s.ks_state_id  INNER JOIN ks_suburbs As k_sub ON k_u_a.ks_suburb_id = k_sub.ks_suburb_id  INNER JOIN ks_countries As k_coun ON k_u_a.ks_country_id = k_coun.ks_country_id  WHERE k_u_a.app_user_id  = '".$app_user_id."' AND k_u_a.is_active ='Y'";	
			$query = $this->db->query($sql);
			$data = $query->result();
			// $data = $this->common_model->get_all_record($table,$where_clause);
			if (count($data) > 0) {
				 
				 foreach ($data as $value) {
				 		if ($value->default_address == '1') {
				 			$value->default_address = true;
				 		}else{
				 			$value->default_address = false;
				 		}

					 $a = array(
						'user_address_id'=>$value->user_address_id,
						'is_default'=>$value->default_address,
						'ks_state_name'=>$value->ks_state_name,
						'street_address_line1'=>$value->street_address_line1,
						'street_address_line2'=>$value->street_address_line2,
						'suburb_name'=>$value->suburb_name,
						'ks_country_name'=>$value->ks_country_name,
						'ks_state_id'=>$value->ks_state_id,
						'ks_country_id'=>$value->ks_country_id,
						'ks_suburb_id'=>$value->ks_suburb_id,
						'postcode'=>(int)$value->postcode,
						'timezone'=>(int)$value->timezone,
						'administrive_area_level_2'=>$value->administrive_area_level_2,
						'route'  => $value->route ,
						'lat'=>(float)$value->lat,
						'lng'=>(float)$value->lng,
						) ;
					$data_value[] = $a; 
					//die;
				 }
				$response['status'] = 200;
				$response['status_message'] = "sucess";
				$response['result'] = $data_value;
			}else{
				$response['status'] = 200;
				$response['status_message'] = "sucess";
				$response['result'] = array();
			}
		}
		echo json_encode($response);
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
    }

    // DELETE ADDRESS 
    public function DeleteUserAddress()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		
		if ($app_user_id == 0  || $app_user_id == "") {
			//$response['status'] = 400;
			//$response['status_message'] = "User not found";
			
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}else{
			$table = 'ks_user_address';
			$where_clause = array(
									'app_user_id'=> $app_user_id ,
									'user_address_id'=> $post_data['user_address_id']

									);
			$data = $this->common_model->get_all_record($table,$where_clause);
			
			if (count($data) > 0) {

				$where_clause = array(
									'create_user'=> $app_user_id ,
									'user_address_id'=> $post_data['user_address_id'],
									'is_active'=>'Y'

									);
				$data_check = $this->common_model->get_all_record('ks_gear_location',$where_clause);	
				if (!empty($data_check)) {
					$response['status'] = 400;
					$response['status_message'] = "This Address is assigned to a listing. Modify in 'Manage Listings' before deleting";
					echo json_encode($response);
					exit();	
				}

					$data_value = array(
						'is_active'=>'N',
					) ;
				$this->common_model->UpdateRecord($data_value , 'ks_user_address' , 'user_address_id', $post_data['user_address_id']);	
				//echo "string";
				$response['status'] = 200;
				$response['status_message'] = "Address Deleted sucessfully";
			}else{
				$response['status'] = 200;
				$response['status_message'] = " Address not Found";
				
			}
		}
		echo json_encode($response);
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
    }

    // acount settings
    public function accounsettingadd()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id == 0 || $app_user_id == "" ) {
			
			//$response['status_code'] = 400;
			//$response['status_message'] = "User not found";
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}else{
		
			 $row['notification']  = $post_data['notification'];
		$this->db->where('app_user_id', $app_user_id);
		$value = $this->db->update('ks_users', $row); 
		 if ($value == '1') {
		 		$response['status_code'] = 200;
		 		//$response['status']= 'success';
				$response['status_message'] = "Updated notification status";
		 }else{
		 		$response['status_code'] = 400;
		 		//$response['status']= 'failur';
				$response['status_message'] = "Something went wrong";
		 }
		}
		echo json_encode($response);
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
    }

    //Get  Notification update

    public function UserNotificatonSetting()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		
		
		if ($app_user_id == 0 || $app_user_id == "") {
			
			//$response['status_code'] = 400;
			//$response['status_message'] = "User not found";
			
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}else{
			$data = array(
					'rental_message'=>$post_data['rental_message'],
					'send_message'=>$post_data['send_message'],
					'comment_on_offer'=>$post_data['comment_on_offer'],
					'accept_offer'=>$post_data['accept_offer'],
					'reject_offer'=>$post_data['reject_offer'],
					'review'=>$post_data['review'],
					'forget_to_aceept_offer'=>$post_data['forget_to_aceept_offer'],

						);
			$this->db->where('app_user_id', $app_user_id);
			$value = $this->db->update('ks_users', $data); 
		 	if ($value == '1') {
		 		$response['status_code'] = 200;
		 	//	$response['status']= 'success';
				$response['status_message'] = "Updated notification status";
		 	}else{
		 		$response['status_code'] = 400;
		 		//$response['status']= 'failur';
				$response['status_message'] = "Something went wrong";
		 	}
		}
		echo json_encode($response);
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}

    }
    public function Getnotifcationlist()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){

		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id == 0 || $app_user_id == "") {
			
			//$response['status_code'] = 400;
			//$response['status_message'] = "User not found";
			
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}else{

			$query = $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$app_user_id));
			$data =$query->row();
		//	print_r($data);
			if (!empty($data)) {
				$values = array(

				'rental_message'=>$data->rental_message,
				'send_message'=>$data->send_message,
				'comment_on_offer'=>$data->comment_on_offer,
				'accept_offer'=>$data->accept_offer,
				'reject_offer'=>$data->reject_offer,
				'review'=>$data->review,
				'forget_to_aceept_offer' => $data->forget_to_aceept_offer,

					);
				$response['status_code'] = 200;
				$response['status_message'] = "success";
				$response['result'] = $values;
			}else{
				$response['status_code'] = 400;
				$response['status_message'] = "User not found" ;
			}

			//echo $app_user_id ;
		}
		echo json_encode($response);
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
    }
    public function accounsettingget()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){

		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id == 0 || $app_user_id == "") {
			
			//$response['status_code'] = 400;
			//$response['status_message'] = "User not found";
			
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}else{

			$query = $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$app_user_id));
			$data =$query->row();
	
			if (!empty($data)) {
				$values = array(

							'notification'=>$data->notification
				
					);
				$response['status_code'] = 200;
				$response['status_message'] = "success";
				$response['result'] = $values;
			}else{
				$response['status_code'] = 400;
				$response['status_message'] = "User not found" ;
			}

			//echo $app_user_id ;
		}
		echo json_encode($response);
		
		}else{
			header('HTTP/1.1 200 Success');
			exit();
			
		}
    }

    public function addInsurance()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if(array_key_exists('insurance_amount', $post_data)){

			$insurance_amount =  $post_data['insurance_amount'] ;
		}else{
			$insurance_amount =  '0';

		}
		if ($app_user_id > 0 ) {
			$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',array('app_user_id'=>$app_user_id));
			$ddata = $query->row();
			if (!empty($ddata)) {
				// $value=  array(
						
				// 		'is_active'=> 'N',
				// 		'is_approved' => '2',
				// 		'update_date'=>date('Y-m-d'),
				// 		);

				// $this->db->where('app_user_id',$app_user_id);
				// $this->db->update('ks_user_insurance_proof',$value);
				$value=  array(
						'ks_user_certificate_currency_desc' => $post_data['insurance_description'],
						'ks_user_certificate_currency_exp' => date('Y-m-d',strtotime($post_data['expire_date'])),
						'renter_insuance_provided' => $post_data['renter_insuance_provided'],
						'owner_insurance_percentage' => $post_data['owner_insurance_percentage'],
						'owner_insurance_provided' => $post_data['owner_insurance_provided'],
						'owner_insurance_amount' => $post_data['owner_insurance_amount'],
						'image_url'=>$post_data['image_url'],
						'owner_insurance_status' => '0',
						'app_user_id' => $app_user_id,
						'insurance_amount'=>$insurance_amount,
						'create_date' => date('Y-m-d'),
						'is_active' => 'Y',
						'is_approved' => '0',

						);
				$insurance_id  =  $this->common_model->AddRecord($value,'ks_user_insurance_proof');
				$query  =  $this->common_model->GetAllWhere('ks_user_insurance_proof', array('app_user_id' => $app_user_id));
				$response_data =  $query->result();
				foreach ($response_data as  $value) {
					$i = 0 ;
					if ($value->is_approved == 0 )  {
						 $response_data[$i]->is_approved_status_renter = 'Pending'; 
					}elseif ($value->is_approved == 2 ) {
						$response_data[$i]->is_approved_status_renter = 'Expired'; 
					}elseif ($value->is_approved == 3 ) {
						$response_data[$i]->is_approved_status_renter = 'Active'; 
					}elseif ($value->is_approved == 4 ) {
						$response_data[$i]->is_approved_status_renter = 'InActive'; 
					}else{
						$response_data[$i]->is_approved_status_renter = 'Approved'; 
					}	

					if ($value->owner_insurance_status == 0 )  {
						$response_data[$i]->is_approved_status_owner = 'Pending'; 
					}elseif ($value->owner_insurance_status == 2 ) {
						$response_data[$i]->is_approved_status_owner = 'Expired'; 
					}elseif ($value->is_approved == 3 ) {
						$response_data[$i]->is_approved_status_owner = 'Active'; 
					}elseif ($value->is_approved == 4 ) {
						$response_data[$i]->is_approved_status_owner = 'InActive'; 
					}else{
						$response_data[$i]->is_approved_status_owner = 'Approved'; 
					}


					$i++;
				}
				

				
				$response=array("status"=>200,
								"result"=>$response_data,
								"status_message"=>"Insurance updated successffully",
						);
				echo json_encode($response);
				exit();
			}else{
				$value=  array(
						'ks_user_certificate_currency_desc'=>$post_data['insurance_description'],
						'ks_user_certificate_currency_exp'=>date('Y-m-d',strtotime($post_data['expire_date'])),
						'start_date'=>date('Y-m-d',strtotime($post_data['start_date'])),
						
						'renter_insuance_provided' => $post_data['renter_insuance_provided'],
						'owner_insurance_percentage' => $post_data['owner_insurance_percentage'],
						'owner_insurance_provided' => $post_data['owner_insurance_provided'],
						'owner_insurance_amount' => $post_data['owner_insurance_amount'],
						'owner_insurance_status' => '0',
						'app_user_id'=>$app_user_id,
						'insurance_amount'=>$insurance_amount,
						'create_date'=>date('Y-m-d'),
						'is_active'=> 'Y',

						);
				$insurance_id  = $this->common_model->AddRecord($value,'ks_user_insurance_proof');
				$query =  $this->common_model->GetAllWhere('ks_user_insurance_proof', array('app_user_id'=>$app_user_id));
				$response_data =  $query->result();
				foreach ($response_data as  $value) {
					$i = 0 ;
					if ($value->is_approved == 0 )  {
						 $response_data[$i]->is_approved_status_renter = 'Pending'; 
					}elseif ($value->is_approved == 2 ) {
						$response_data[$i]->is_approved_status_renter = 'Expired'; 
					}elseif ($value->is_approved == 3 ) {
						$response_data[$i]->is_approved_status_renter = 'Active'; 
					}elseif ($value->is_approved == 4 ) {
						$response_data[$i]->is_approved_status_renter = 'InActive'; 
					}else{
						$response_data[$i]->is_approved_status_renter = 'Approved'; 
					}	

					if ($value->owner_insurance_status == 0 )  {
						$response_data[$i]->is_approved_status_owner = 'Pending'; 
					}elseif ($value->owner_insurance_status == 2 ) {
						$response_data[$i]->is_approved_status_owner = 'Expired'; 
					}elseif ($value->is_approved == 3 ) {
						$response_data[$i]->is_approved_status_owner = 'Active'; 
					}elseif ($value->is_approved == 4 ) {
						$response_data[$i]->is_approved_status_owner = 'InActive'; 
					}else{
						$response_data[$i]->is_approved_status_owner = 'Approved'; 
					}


					$i++;
				}
				

				$response=array("status"=>200,
						"result"=>$response_data,
						"status_message"=>"Insurance Added successffully",
						);
				echo json_encode($response);
				exit();
			}
		}else{
			/*$response=array("status"=>400,
						"status_message"=>"User Not Valid",
						);
			echo json_encode($response);
			exit();*/
			header('HTTP/1.1 400 Session Expired');
			exit();
		}
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
    }
    public function getInsurance()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ) {
			$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',array('app_user_id'=>$app_user_id,'is_deleted'=>'N'));
			$ddata = $query->result();
			if (!empty($ddata)) {
				$result['status']  = '200';
				$i = 0 ;
				foreach ($ddata as  $value) {
					
					if ($value->owner_insurance_provided =='Yes') {
						$ddata[$i]->owner_insurance_provided = true; 
					}else{
						$ddata[$i]->owner_insurance_provided = false; 
					}
					if ($value->renter_insurance_provided	 =='Yes') {
						$ddata[$i]->renter_insurance_provided	 = true; 
					}else{
						$ddata[$i]->renter_insurance_provided	 = false; 
					}
					if ($value->owner_is_active =='0') {
						$ddata[$i]->owner_is_active = true; 
					}else{
						$ddata[$i]->owner_is_active = false; 
					}
					if ($value->renter_is_active =='0') {
						$ddata[$i]->renter_is_active = true; 
					}else{
						$ddata[$i]->renter_is_active = false; 
					}
					if ($value->is_visiible =='Y') {
						$ddata[$i]->is_visiible = true; 
					}else{
						$ddata[$i]->is_visiible = false; 
					}
					if ($value->owner_insurance_status == 0 )  {
						$ddata[$i]->owner_insurance_status = 'Pending'; 
					}elseif ($value->owner_insurance_status == 2 ) {
						$ddata[$i]->owner_insurance_status = 'Expired'; 
					}else{
						$ddata[$i]->owner_insurance_status = 'Approved'; 
					}
					if ($value->renter_insurance_status == 0 )  {
						$ddata[$i]->renter_insurance_status = 'Pending'; 
					}elseif ($value->renter_insurance_status == 2 ) {
						$ddata[$i]->renter_insurance_status = 'Expired'; 
					}else{
						$ddata[$i]->renter_insurance_status = 'Approved'; 
					}
					$paymentDate = date('Y-m-d');
					$paymentDate=date('Y-m-d', strtotime($paymentDate));
					$contractDateBegin = date('Y-m-d', strtotime($value->ks_user_certificate_currency_start));
					$contractDateEnd = date('Y-m-d', strtotime($value->ks_user_certificate_currency_exp));
					if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
					  	$ddata[$i]->is_active = true; 	
					}else{
					   	$ddata[$i]->is_active = false; 	
					}
					
					$ddata[$i]->user_insurance_proof_id = (int) $value->user_insurance_proof_id; 
					$ddata[$i]->app_user_id = (int) $value->app_user_id; 
					$ddata[$i]->owner_insurance_percentage = (int) $value->owner_insurance_percentage; 
					$ddata[$i]->renter_insurance_amount = (int) $value->renter_insurance_amount; 
					$ddata[$i]->owner_insurance_amount = (int) $value->owner_insurance_amount; 
					$ddata[$i]->is_visiible =  $value->is_visiible ;

					$i++;
				}
				$result['result'] =$ddata ;
			}else{
				$result['status']  = '404';
				$result['message'] = 'No insurance found';
			}
		}else{
			//$result['status']  = '404';
			//$result['message'] = 'No user found';
			
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}	
		echo json_encode($result, true)	;
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
    }
	
	
	public function removeInsurance(){
		
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id > 0 ) {
				
				//Insurance ID
				$user_insurance_proof_id = $post_data['user_insurance_proof_id'];
				
				$where_clause = array("user_insurance_proof_id"=>$user_insurance_proof_id,"app_user_id"=>$app_user_id);
				
				//Checked whether any insurance exists corresponding to this id or not
				$row = $this->common_model->RetriveRecordByWhereRow("ks_user_insurance_proof",$where_clause);

				if($row){					
					
					//table is updated 
					$update_arr = array("is_deleted"=>'Y', "renter_is_active" => '1', "owner_is_active" => '1', "owner_insurance_status" => '0', "renter_insurance_status" => '0');
					
					$this->common_model->UpdateRecord($update_arr,"ks_user_insurance_proof","user_insurance_proof_id",$user_insurance_proof_id);
					
					$response_data['user_insurance_proof_id']=$user_insurance_proof_id;
					
					$response=array("status"=>200,
									"result"=>$response_data,
									"status_message"=>"Insurance Deleted Successffully",
					);
					
					
				}else{
					
					$response['status']  = '404';
					$response['message'] = 'No insurance found';
							
				}
				echo json_encode($response,true);
				
			}else{
				
				header('HTTP/1.1 400 Session Expired');
				exit();
			
			}	
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
		
	}
	
	public function ImageUpload()
    {
		$token 			= $this->input->post('token');
		$type 			= $this->input->post('type');
	
		if(isset($_FILES['image'])){
			$image 			= $_FILES['image'];
		}else{
			$result['status'] = '400' ;
			$result['status_message'] = 'Plese Select a file' ;
			echo json_encode($result,true);
			exit;
		}
		$app_user_id = $this->userinfo($token);

		if($app_user_id > 0 ){
			if (!empty($_FILES['image'])) {
				$original_image_name =  explode('.',$_FILES['image']['name']);
				if($type == 'profile'){
					
					$config['upload_path'] = BASE_IMG.'site_upload/profile_img_original/';
					$config['allowed_types'] = 'jpg|jpeg|gif|png';
				}elseif($type == 'insurance' || $type == 'owner_insurance'){
					$config['upload_path'] =BASE_IMG.'site_upload/insurance_img/';
					$config['allowed_types'] = 'jpg|jpeg|gif|png|pdf|pdf|doc|docx';
				}
				
				//$config['max_size']	= '2000';
				$config['max_width'] = '0';
				$config['max_height'] = '0';
				$config['overwrite'] = TRUE;
				$ext =  strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
				
				$fname = time();
				
			    $file_name =  $fname.'.'.$ext;				
				$config['file_name'] = $file_name;
				$config['orig_name'] =$_FILES['image']['name'];
				$config['image'] = $file_name;
				$this->upload->initialize($config);
				
				if($this->upload->do_upload('image')){
					if ($type == 'insurance' ) {
						$query =  $this->common_model->GetAllWhere('ks_user_insurance_proof',array('app_user_id'=>$app_user_id));
						$check_response = $query->row();
						if (!empty($check_response)) {
							$image_url = FRONT_URL.'/insurance_img/'.$file_name; 
							
							$this->db->where('app_user_id',$app_user_id );
							$this->db->where('is_active','Y');
							$response_upload = $this->db->update('ks_user_insurance_proof',array('image_url'=>$image_url));
							if ($response_upload = '1') {
								$result['status'] = 200;
								$result['result'] = array('image_url'=> $image_url);
								$result['status_message'] = 'Image uploaded successfully' ;
							}else{
								$result['status'] = 400 ;
								$result['result'] = array('image_url'=> $image_url);
								$result['status_message'] = 'Something went wrong' ;
							}
						}else{
							$result['status'] = 400 ;
							$result['status_message'] = 'No insurance yet added' ;
						}
					}elseif ($type == 'profile') {
						
						
						      $source_path = BASE_IMG.'site_upload/profile_img_original/'.$file_name;

							  $target_path = BASE_IMG.'site_upload/profile_img/';

							  $config_manip = array(

								  'image_library' => 'gd2',

								  'source_image' => $source_path,

								  'new_image' => $target_path,

								  'maintain_ratio' => TRUE,

								  'create_thumb' => TRUE,

								  'thumb_marker' => '_thumb',

								  'width' => 640,

								  'height' => 480

							  );

							  $this->image_lib->initialize($config_manip);

							  if (!$this->image_lib->resize()) {

									$result['status'] = 400;
									$result['result'] = array('image_url'=> '');
									$result['status_message'] = $this->image_lib->display_errors();
									echo  json_encode($result,true);
									exit();

							  }else{
								  
								  
								  
							  }


							  $this->image_lib->clear();
						
						$query =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
						$check_response = $query->row();
						if (!empty($check_response)) {
							
							$old_img_path = $check_response->user_profile_picture_link;			
							
							$image_url = FRONT_URL.'profile_img/'.$fname."_thumb.".$ext; 
							$this->db->where('app_user_id',$app_user_id);
							$response_upload = $this->db->update('ks_users',array('user_profile_picture_link'=>$image_url));
							if ($response_upload = '1') {
								
								//Old thumb image is unlinked								
								if($old_img_path!=""){
                            
									
									//Original filename is extracted
									if(strstr($old_img_path,"/") && strstr($old_img_path,"_thumb")){
										
										$oldname = explode("/",$old_img_path);
										$old_fname = end($oldname);
										
										unlink(BASE_IMG.'site_upload/profile_img/'.$old_fname);
									
										$fname = explode("_thumb",$old_fname);
										$ext =  strtolower(pathinfo($old_fname, PATHINFO_EXTENSION));
										$old_fname = $fname[0].".".$ext;
										
										unlink(BASE_IMG.'site_upload/profile_img_original/'.$old_fname);
										
									}
									
									
								}
								
								$result['status'] = 200;
								$result['result'] = array('image_url'=> $image_url);
								$result['status_message'] = 'Image uploaded successfully' ;
							}else{
								$result['status'] = 400;
								$result['result'] = array('image_url'=> '');
								$result['status_message'] = 'Something went wrong' ;
							}
						}
					}elseif ($type == 'owner_insurance') {
						$query =  $this->common_model->GetAllWhere('ks_user_insurance_proof',array('app_user_id'=>$app_user_id));
						$check_response = $query->row();
						if (!empty($check_response)) {
							$image_url = FRONT_URL.'/insurance_img/'.$file_name; 
							
							$this->db->where('app_user_id',$app_user_id );
							$this->db->where('is_active','Y');
							$response_upload = $this->db->update('ks_user_insurance_proof',array('owner_image_url'=>$image_url));
							if ($response_upload = '1') {
								$result['status'] = 200;
								$result['result'] = array('image_url'=> $image_url);
								$result['status_message'] = 'Image uploaded successfully' ;
							}else{
								$result['status'] = 400;
								$result['result'] = array('image_url'=> '');
								$result['status_message'] = 'Something went wrong' ;
							}
						}else{
							$result['status'] = 400;
							$result['status_message'] = 'No insurance yet added' ;
						}
					}
				}else{
					$error = array('error' => $this->upload->display_errors());
					// print_r($error);
					$result['status'] = 400;
					$result[''] = array('image_url'=> '');
					$result['status_message'] = 'Unable to Upload a image' ;
					
				}
			}

		}else{
			//$result['status'] = '400' ;
			//$result['status_message'] = 'Not a valid user' ;
			
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}
		echo  json_encode($result,true);
    }

    public function ChangeInsuranceStatus()
    {
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token = $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id == 0 || $app_user_id == "") {
			
			//$response['status_code'] = 400;
			//$response['status_message'] = "User not found";
			
			header('HTTP/1.1 400 Session Expired');
			exit();

		}else{
			
			$user_insurance_proof_id  =  $post_data['user_insurance_proof_id'];
			$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',array('user_insurance_proof_id'=>$user_insurance_proof_id));
			$insurance_res =  $query->row();
			if (!empty($insurance_res)) {
				if ($post_data['is_active'] =='1') {
					$data = array('is_visiible'=>'N');
				}else{
					$data = array('is_visiible'=>'Y');
				}
				
				$this->db->where('user_insurance_proof_id',$user_insurance_proof_id );
				$response_upload = $this->db->update('ks_user_insurance_proof',$data);

				$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',array('user_insurance_proof_id'=>$user_insurance_proof_id ));
				$response_data = $query->row();
					if ($response_data->owner_insurance_provided =='Yes') {
						$response_data->owner_insurance_provided = true; 
					}else{
						$response_data->owner_insurance_provided = false; 
					}
					if ($response_data->renter_insurance_provided	 =='Yes') {
						$response_data->renter_insurance_provided	 = true; 
					}else{
						$response_data->renter_insurance_provided	 = false; 
					}
					if ($response_data->owner_is_active =='0') {
						$response_data->owner_is_active = true; 
					}else{
						$response_data->owner_is_active = false; 
					}
					if ($response_data->renter_is_active =='0') {
						$response_data->renter_is_active = true; 
					}else{
						$response_data->renter_is_active = false; 
					}
					if ($response_data->is_visiible =='Y') {
						$response_data->is_visiible = true; 
					}else{
						$response_data->is_visiible = false; 
					}
					if ($response_data->owner_insurance_status == 0 )  {
						$response_data->owner_insurance_status = 'Pending'; 
					}elseif ($response_data->owner_insurance_status == 2 ) {
						$response_data->owner_insurance_status = 'Expired'; 
					}else{
						$response_data->owner_insurance_status = 'Approved'; 
					}
					if ($response_data->renter_insurance_status == 0 )  {
						$response_data->renter_insurance_status = 'Pending'; 
					}elseif ($response_data->renter_insurance_status == 2 ) {
						$response_data->renter_insurance_status = 'Expired'; 
					}else{
						$response_data->renter_insurance_status = 'Approved'; 
					}
					$paymentDate = date('Y-m-d');
					$paymentDate=date('Y-m-d', strtotime($paymentDate));
					$contractDateBegin = date('Y-m-d', strtotime($response_data->ks_user_certificate_currency_start));
					$contractDateEnd = date('Y-m-d', strtotime($response_data->ks_user_certificate_currency_exp));
					if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
					  	$response_data->is_active = true; 	
					}else{
					   	$response_data->is_active = false; 	
					}
					$response_data->user_insurance_proof_id = (int) $response_data->user_insurance_proof_id; 
					$response_data->app_user_id = (int) $response_data->app_user_id; 
					$response_data->owner_insurance_percentage = (int) $response_data->owner_insurance_percentage; 
					$response_data->renter_insurance_amount = (int) $response_data->renter_insurance_amount; 
					$response_data->owner_insurance_amount = (int) $response_data->owner_insurance_amount; 
				$response['status'] = '200' ;
				$response['result'] = $response_data ;
				$response['status_message'] = 'insurance status is been updated' ;
			}else{
					$response['status_code'] = 400;
					$response['status_message'] = "No insurance  data found ";
			}
			

		}
		echo json_encode($response,true);
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
    }

    public function ChangeInsuranceStatus1()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		$token = $post_data['token'];
		 $app_user_id = $this->userinfo($token);
		if ($app_user_id == 0 || $app_user_id == "") {
			
			//$response['status_code'] = 400;
			//$response['status_message'] = "User not found";
			
			header('HTTP/1.1 400 Session Expired');
			exit();

		}else{
			$user_insurance_proof_id  =  $post_data['user_insurance_proof_id'];
			$query = $this->common_model->GetAllWhere('ks_user_insurance_proof_new',array('user_insurance_proof_id'=>$user_insurance_proof_id));
			$insurance_res =  $query->row();	
			$this->db->where('user_insurance_proof_id',$user_insurance_proof_id );
			$response_upload = $this->db->update('ks_user_insurance_proof_new',array('is_active'=>'N'));
			$user_insurance_proof_id  =  $post_data['user_insurance_proof_id'];
			$query = $this->common_model->GetAllWhere('ks_user_insurance_proof_new',array('user_insurance_proof_id'=>$user_insurance_proof_id));
			$response_data =  $query->row();
					if ($response_data->is_approved == 0 )  {
						$response_data->is_approved_status = 'Pending'; 
					}elseif ($response_data->is_approved == 2 ) {
						$response_data->is_approved_status = 'Expired'; 
					}elseif ($response_data->is_approved == 3 ) {
						$response_data->is_approved_status = 'Active'; 
					}elseif ($response_data->is_approved == 4 ) {
						$response_data->is_approved_status = 'InActive'; 
					}else{
						$response_data->is_approved_status = 'Approved'; 
					}	

					if ($response_data->insurance_provided =='Yes') {
						$response_data->owner_insurance_provided = true; 
					}else{
						$response_data->owner_insurance_provided  = false; 
					}
					if ($response_data->is_active =='Y') {
						$response_data->is_active = true; 
					}else{
						$response_data->is_active = false; 
					}
					$response_data->user_insurance_proof_id = (int) $response_data->user_insurance_proof_id; 
					$response_data->app_user_id = (int) $response_data->app_user_id; 
					// $response_data->insurance_percentage = (int) $response_data->insurance_percentage; 

					$response_data->insurance_percentage = (int) $response_data->insurance_percentage; 
					$response_data->owner_insurance_amount = (int) $response_data->owner_insurance_amount; 
					$response_data->renter_insurance_amount = (int) $response_data->insurance_amount; 
					$response_data->is_approved = (int) $response_data->is_approved; 

					$response_data->owner_insurance_provided   = false; 
					unset( $response_data->insurance_amount);
					$response=array("status"=>200,
								"result"=>$response_data,
								"status_message"=>"Insurance added successffully",
						);
					echo json_encode($response);
					exit();
		}

    }
    public function getInsurance1()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ) {
			$query = $this->common_model->GetAllWhere('ks_user_insurance_proof_new',array('app_user_id'=>$app_user_id ));
			$response_data = $query->result();
			if (!empty($response_data)) {
				$result['status']  = '200';
				$i = 0 ;
				foreach ($response_data as  $value) {
					
					if ($value->is_approved == 0 )  {
						$response_data[$i]->is_approved_status = 'Pending'; 
					}elseif ($value->is_approved == 2 ) {
						$response_data[$i]->is_approved_status = 'Expired'; 
					}else{
						$response_data[$i]->is_approved_status = 'Approved'; 
					}	

					if ($value->insurance_type =='renter') {
						if ($value->insurance_provided =='Yes') {
							$response_data[$i]->renter_insurance_provided = true; 
						}else{
							$response_data[$i]->renter_insurance_provided  = false; 
						}
							$response_data[$i]->owner_insurance_provided  = false; 
					}elseif($value->insurance_type =='owner'){
						if ($value->insurance_provided =='Yes') {
							$response_data[$i]->owner_insurance_provided = true; 
						}else{
							$response_data[$i]->owner_insurance_provided  = false; 
						}
						$response_data[$i]->renter_insurance_provided = false; 	
					}elseif($value->insurance_type =='both'){

							$response_data[$i]->owner_insurance_provided = true; 	
							$response_data[$i]->renter_insurance_provided = true; 	
					}
					if ($value->is_active =='Y') {
						$response_data[$i]->is_active = true; 
					}else{
						$response_data[$i]->is_active = false; 
					}
					$response_data[$i]->user_insurance_proof_id = (int) $value->user_insurance_proof_id; 
					$response_data[$i]->app_user_id = (int) $value->app_user_id; 
					// $response_data->insurance_percentage = (int) $response_data->insurance_percentage; 

					$response_data[$i]->insurance_percentage = (int) $value->insurance_percentage; 
					$response_data[$i]->owner_insurance_amount = (int) $value->owner_insurance_amount; 
					$response_data[$i]->renter_insurance_amount = (int) $value->insurance_amount; 
					$response_data[$i]->is_approved = (int) $value->is_approved; 

					
					unset( $response_data[$i]->insurance_amount);

					$i++;
				}
				$result['result'] =$response_data ;
			}else{
				$result['status']  = '403';
				$result['message'] = 'No insurance found';
			}
		}else{
			//$result['status']  = '403';
			//$result['message'] = 'No user found';
			
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}	
		echo json_encode($result, true)	;
    }
    
	function userinfo($token){
	
		$app_user_id = $this->common_model->fetchTokenDetails($token);
		return $app_user_id;

	}

	public function DigitalIdDetails()
	{
	    $query  = $this->common_model->GetAllWhere('ks_settings', '');
	    $data  = $query->row();
	    $data1 = array(
	    			'digitalId_clientid' => $data->digitalId_clientid,
	    			'digitalId_secretid' => $data->digitalId_secretid,
	    			'digitalId_url' => $data->digitalId_url,
	    	);
	    $response['status'] ='200';
	    $response['message'] ='Digital ID Details ';
	    $response['result'] =$data1;
	    echo  json_encode($response ,true);
    }
	
	public function modifyAddress(){
	
		$method = $_SERVER['REQUEST_METHOD'];
		if ('PUT' === $method) {
			$post_data  = json_decode(file_get_contents("php://input"),true);
						
			$token 				= $post_data['token'];
			$user_address_id 	= $post_data['user_address_id'];
			$is_default 		= $post_data['is_default'];
			$app_user_id 		= $this->userinfo($token);	
			$data_response 		= 0;
			
			
			if($is_default==true)
				$is_default = 1;
			else
				$is_default = 0;
			
			
			//Checking whether the user is a valid user or not
			if ($app_user_id == '0' || $app_user_id == "") {
				
				//$response['status'] = 400;
				//$response['status_message'] = "User not found";
				
				header('HTTP/1.1 400 Session Expired');
				exit();
				
			}else{	//
			
				
				if($is_default == 1){
				
					//Checked whether there is any record against this app_user_id and user_address_id
					$where = "app_user_id='".$app_user_id."' AND user_address_id='".$user_address_id."' AND is_active='Y'";
					$count = $this->common_model->CountWhere("ks_user_address",$where);
					
					if($count>0){
			
						//Default address is modified			
						$this->db->set('default_address','1');
						$this->db->where($where);
						$this->db->update('ks_user_address');
						
						//All the address corresponding to this user is modified except the provided user_address_id
						$where = "app_user_id='".$app_user_id."' AND user_address_id!='".$user_address_id."'";
						
						$this->db->set('default_address','0');
						$this->db->where($where);
						$this->db->update('ks_user_address');
						
						$data_response = 1;
					}
					
				}
				
				if ($data_response > 0) {
					$response['status'] = 200;
					$response['status_code']='success';
					$response['status_message'] = "Address modified successfully";
				}else{
					$response['status'] = 400;
					$response['status_code']='failed';
					$response['status_message'] = "Something went wrong";
				}
			
			}
			
			
			echo json_encode($response);
			
		}
		
		
	}    
}
?>