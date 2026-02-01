	<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class ProfilepageDetails extends CI_Controller {
 
    public function __construct() {
       header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->model(array('common_model','home_model'));
		$this->load->library(array('email','upload'));
		
    }
 
    // upload xlsx|xls file
    public function index() {
       
    }
    // add user address 
    public function Adduseraddress()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
			
		$token 			= $post_data['token'];
		 $app_user_id = $this->userinfo($token);

		
		if ($app_user_id == '0') {
			$response['status'] = 400;
			$response['status_message'] = "User not found";
		}else{
		$query =$this->common_model->GetAllWhere('ks_user_address',array('app_user_id'=>$app_user_id));
		$value_check = $query->result_array();
		
		 $count = count($value_check) ;
		//	 print_r( $count);
		// if(count($value_check) > 0){
		// 	$this->common_model->delete('ks_user_address' ,$app_user_id ,'app_user_id');
		// }
		 foreach($post_data['data'] As $data_array){ 
		 	$queryks_suburbs =$this->common_model->GetAllWhere('ks_suburbs',array('ks_suburb_id'=>$data_array['ks_suburb_id']));
			$queryks_suburbs_details = $queryks_suburbs->row();

			$queryks_states =$this->common_model->GetAllWhere('ks_states',array('ks_state_id'=>$data_array['ks_state_id']));
			$ks_states = $queryks_states->row();
					 	$string = '';

		 	if (!empty($data_array['street_address_line1'])) {
		 		$string .= $data_array['street_address_line1'].',';
		 	}
		 	if (!empty($data_array['street_address_line2'])) {
		 		$string .= $data_array['street_address_line2'].',';
		 	}
		 	if (!empty($data_array['ks_suburb_id'])) {
		 		$string .= $queryks_suburbs_details->suburb_name.',';
		 	}
		 	if (!empty($data_array['ks_state_id'])) {
		 		$string .= $ks_states->ks_state_name.',';
		 	}
		 	if (!empty($data_array['ks_country_id'])) {
		 		$string .= 'Australia ';
		 	}
		 	if (!empty($data_array['is_default'])) {
		 		if($data_array['is_default']=='true')
		 			$default_address= '1';
		 		else
		 			$default_address= '0';
		 	}else{
		 		$default_address= '0';
		 	}

		 	$lat_lng=   $this->GetLatLngFromAddress($string);
		 	
		// 	//print_r($data_array['business_address']);die;
		$data = array(
				'app_user_id'=>$app_user_id,
				'street_address_line1'=>$data_array['street_address_line1'],
				'street_address_line2'=>$data_array['street_address_line2'],
				'default_address'=>$default_address,
				'postcode'=>$data_array['postcode'],
				'lat'=>$lat_lng['lat'],
				'lng'=>$lat_lng['lng'],
				'default_address'=>$default_address,
				'ks_country_id'=>$data_array['ks_country_id'],
				'ks_state_id'=>$data_array['ks_state_id'],	
				'ks_suburb_id'=>$data_array['ks_suburb_id'],
				'create_date'=>date('Y-m-d'),
		 		'is_active'=>'Y',
		 		);
		 	$table= 'ks_user_address';
		 	if (!empty($data_array['user_address_id'])) {

		 			$data_response =	$this->common_model->UpdateRecord($data , 'ks_user_address' , 'user_address_id', $data_array['user_address_id']);	
		 	}else{
		 			$data_response = $this->common_model->InsertData($table,$data);
			}
		 }	
		//die;
			
			
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
		//print_r($post_data);die;
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id == 0 ) {
			$response['status'] = 400;
			$response['status_message'] = "User not found";
		}else{
			$table = 'ks_user_address';
			$where_clause = array('app_user_id'=> $app_user_id ,'is_active'=> 'Y');
			
			$sql = "SELECT k_u_a.*,k_s.ks_state_name,k_sub.suburb_name FROM ks_user_address As k_u_a INNER JOIN ks_states AS k_s ON  k_u_a.ks_state_id = k_s.ks_state_id  INNER JOIN ks_suburbs As k_sub ON k_u_a.ks_suburb_id = k_sub.ks_suburb_id  WHERE k_u_a.app_user_id  = '".$app_user_id."' AND k_u_a.is_active ='Y'";	
			$query = $this->db->query($sql);
			$data = $query->result();
			// $data = $this->common_model->get_all_record($table,$where_clause);
				
			if (count($data) > 0) {
				$data_value = ''; 
				 foreach ($data as $value) {
				 		if ($value->default_address == '1') {
				 			$value->default_address = 'true';
				 		}else{
				 			$value->default_address = 'false';
				 		}

					 $a = array(
						'user_address_id'=>$value->user_address_id,
						'is_default'=>$value->default_address,
						'ks_state_name'=>$value->ks_state_name,
						'street_address_line1'=>$value->street_address_line1,
						'street_address_line2'=>$value->street_address_line2,
						'suburb_name'=>$value->suburb_name,
						'ks_state_id'=>$value->ks_state_id,
						'ks_country_id'=>$value->ks_country_id,
						'ks_suburb_id'=>$value->ks_suburb_id,
						'postcode'=>$value->postcode,
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
    }

    // DELETE ADDRESS 
    public function DeleteUserAddress()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		//print_r($post_data);die;
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id == 0 ) {
			$response['status'] = 400;
			$response['status_message'] = "User not found";
		}else{
			$table = 'ks_user_address';
			$where_clause = array(
									'app_user_id'=> $app_user_id ,
									'user_address_id'=> $post_data['user_address_id']

									);
			$data = $this->common_model->get_all_record($table,$where_clause);
			
			if (count($data) > 0) {
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
    }

    // acount settings
    public function accounsettingadd()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		//print_r($post_data);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		//print_r($app_user_id);
		if ($app_user_id == 0 ) {
			$response['status_code'] = 400;
			$response['status_message'] = "User not found";
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
    }

    //Get  Notification update

    public function UserNotificatonSetting()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		//print_r($post_data);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		//print_r($app_user_id);
		if ($app_user_id == 0 ) {
			$response['status_code'] = 400;
			$response['status_message'] = "User not found";
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

    }
    public function Getnotifcationlist()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		//print_r($post_data);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		//print_r($app_user_id);
		if ($app_user_id == 0 ) {
			$response['status_code'] = 400;
			$response['status_message'] = "User not found";
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
    }
    public function accounsettingget()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		//print_r($post_data);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		//print_r($app_user_id);
		if ($app_user_id == 0 ) {
			$response['status_code'] = 400;
			$response['status_message'] = "User not found";
		}else{

			$query = $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$app_user_id));
			$data =$query->row();
		//	print_r($data);
			if (!empty($data)) {
				$values = array(

				'notification'=>$data->notification,
				
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
    }

    public function addInsurance()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ) {
			$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',array('app_user_id'=>$app_user_id));
			$ddata = $query->row();
			if (!empty($ddata)) {
				$value=  array(
						
						'is_active'=> 'N',
						'is_approved' => '2',
						'update_date'=>date('Y-m-d'),
						);

				$this->db->where('app_user_id',$app_user_id);
				$this->db->update('ks_user_insurance_proof',$value);
				$value=  array(
						'ks_user_certificate_currency_desc' => $post_data['insurance_description'],
						'ks_user_certificate_currency_exp' => date('Y-m-d',strtotime($post_data['expire_date'])),
						'app_user_id' => $app_user_id,
						'create_date' => date('Y-m-d'),
						'is_active' => 'Y',
						'is_approved' => '0',

						);
				$this->common_model->AddRecord($value,'ks_user_insurance_proof');
				$response=array("status"=>200,
						"status_message"=>"Insurance updated successffully",
						);
				echo json_encode($response);
				exit();
			}else{
				$value=  array(
						'ks_user_certificate_currency_desc'=>$post_data['insurance_description'],
						'ks_user_certificate_currency_exp'=>date('Y-m-d',strtotime($post_data['expire_date'])),
						'app_user_id'=>$app_user_id,
						'create_date'=>date('Y-m-d'),
						'is_active'=> 'Y',

						);
				$this->common_model->AddRecord($value,'ks_user_insurance_proof');
				$response=array("status"=>200,
						"status_message"=>"Insurance Added successffully",
						);
				echo json_encode($response);
				exit();
			}
		}else{
			$response=array("status"=>400,
						"status_message"=>"User Not Valid",
						);
			echo json_encode($response);
			exit();
		}
    }
    public function getInsurance()
    {
    	$post_data      = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ) {
			$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',array('app_user_id'=>$app_user_id , 'is_approved != '=>'2'));
			$ddata = $query->row();
			if (!empty($ddata)) {
				$result['status']  = '200';
			$result['message'] = 'Insurance Found successffully';
			$result['result'] =$ddata ;
			}else{
				$result['status']  = '403';
				$result['message'] = 'No insurance found';
			}
		}else{
			$result['status']  = '403';
			$result['message'] = 'No user found';
		}	
		echo json_encode($result, true)	;
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
					 $config['upload_path'] = BASE_IMG.'site_upload/profile_img/';
				}elseif($type == 'insurance'){
					$config['upload_path'] =BASE_IMG.'site_upload/insurance_img/';
				}
				
				$config['allowed_types'] = 'jpg|jpeg|gif|png|pdf|pdf|doc|docx';
				$config['max_size']	= '2000';
				$config['max_width'] = '0';
				$config['max_height'] = '0';
				$config['overwrite'] = TRUE;
				$ext =  strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
			    $file_name = $original_image_name[0]. time().'.'.$ext;				
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
								$result['status'] = '200' ;
								$result['status_message'] = 'Image uploaded successfully' ;
							}else{
								$result['status'] = '400' ;
								$result['status_message'] = 'Something went wrong' ;
							}
						}else{
							$result['status'] = '400' ;
							$result['status_message'] = 'No insurance yet added' ;
						}
					}elseif ($type == 'profile') {
						$query =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
						$check_response = $query->row();
						if (!empty($check_response)) {
							$image_url = FRONT_URL.'profile_img/'.$file_name; 
							$this->db->where('app_user_id',$app_user_id);
							$response_upload = $this->db->update('ks_users',array('user_profile_picture_link'=>$image_url));
							if ($response_upload = '1') {
								$result['status'] = '200' ;
								$result['status_message'] = 'Image uploaded successfully' ;
							}else{
								$result['status'] = '400' ;
								$result['status_message'] = 'Something went wrong' ;
							}
						}
					}
				}else{
					$error = array('error' => $this->upload->display_errors());
					$result['status'] = '400' ;
					$result['status_message'] = 'Unable Upload a image' ;
					
				}
			}

		}else{
			$result['status'] = '400' ;
			$result['status_message'] = 'Not a valid user' ;
		}
		echo  json_encode($result,true);
    }

    function userinfo($token){
	
		$token_decrypt = base64_decode($token);
		if(strstr($token_decrypt,"|")){
			$token_array = explode("|",$token_decrypt);
			
			if(count($token_array)==3){
				$email = $token_array[0];
				$secret_key = $token_array[1];
				$expire_time = $token_array[2];	
			
				$columns = "app_user_id";
				$table = "ks_users";
				$where_clause = array('primary_email_address'=>$email,'auth_secret_key'=>$secret_key,'expire_time'=>$expire_time);
				
				$query = $this->common_model->GetSpecificValues($columns,$table,$where_clause);
				
				if($query->num_rows()>0){
					$row = $query->result_array();
					
					$app_user_id = $row[0]['app_user_id'];
					
					return $app_user_id;
				}else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
				}
			}else
				return 0;
		}else
			return 0;

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
}
?>