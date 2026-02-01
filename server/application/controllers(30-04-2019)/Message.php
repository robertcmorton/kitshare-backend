<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message extends CI_Controller {

	  public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email','upload','pagination'));
		$this->load->model(array('common_model','home_model','mail_model'));
		}
	
	
	public function index(){  
		echo "hello";
      
	}
	//SendMessage
	public function AddUserContact(){
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		 $app_user_id = $this->userinfo($token);
		
		if ($app_user_id != '') {
			$sql = "SELECT * FROM ks_chat_user WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$post_data['renter_id']."')  AND  user_gear_desc_id = '".$post_data['user_gear_desc_id']."' " ; 
			$check_response = $this->common_model->get_records_from_sql($sql);
			if (empty($check_response)) {
				$insert_data = 	array(
										'owner_id'=>$app_user_id , 
										'renter_id'=>$post_data['renter_id'] , 
										'user_gear_desc_id'=>$post_data['user_gear_desc_id'] , 
										'order_id'=>$post_data['order_id'] , 
										'status'=>'Active',
										'create_date'=>date('Y-m-d'),
										'created_time' => date('H:i:m')
									 );
				$chat_user_id =  $this->common_model->InsertData('ks_chat_user',$insert_data);
			}else{ 
				$chat_user_id =  $check_response[0]->chat_user_id;

			}

			 $insert_msg = array('chat_user_id'=>$chat_user_id ,
			 					 'sender_id'=>$app_user_id ,  
			 					 'receiver_id' =>$post_data['renter_id'] ,
			 					 'message' =>$post_data['message'] 	,
			 					 'create_date'=>date('Y-m-d'),
			 					 'created_time'=>date('H:i:m'),
			 					 'created_by'=>$app_user_id
			 					) ;
			  $chat_user_id =  $this->common_model->InsertData('ks_user_chatmessage',$insert_msg);					 
			  	$response['status'] = 200;
				$response['status_message'] = 'Message Send Successfully';
				$json_response = json_encode($response);
				echo $json_response;
		}else{
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();

		}	
	}
	// Get MessageDetails
	public function GetAllmessageList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		 $app_user_id = $this->userinfo($token);

		if ($app_user_id != '') {
			$sql = "SELECT * FROM ks_chat_user WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$post_data['renter_id']."')  AND  user_gear_desc_id = '".$post_data['user_gear_desc_id']."' " ; 
			$check_response = $this->common_model->get_records_from_sql($sql);
			if (empty($check_response)) {
				$response['status'] = 200;
				$response['status_message'] = 'Message Found Successfully';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}else{ 
				$chat_user_id =  $check_response[0]->chat_user_id;

			}
			  	$query  =  $this->common_model->GetAllWhere('ks_user_chatmessage',array('chat_user_id'=>$chat_user_id));					 
			  	$message = $query->result();
			  	if (!empty($message)) {
			  		$i = 0 ;	
			  		 foreach ($message as  $value) {
			  		 		// sender details
			  		 		$query_sender  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$value->sender_id));					 
			  				$sender_details = $query_sender->result();
			  				if (!empty($sender_details)) {
			  					$sender_detail =array(
			  											'app_username'=>$sender_details[0]->app_username,
			  											'app_user_first_name'=>$sender_details[0]->app_user_first_name,
			  											'app_user_last_name'=>$sender_details[0]->app_user_last_name,
			  											'user_profile_picture_link'=>$sender_details[0]->user_profile_picture_link,
			  											'chat_status'=>$sender_details[0]->chat_status,
			  											);
			  					$message[$i]->sender_details = $sender_detail;
			  				}else{
			  					$message[$i]->sender_details = array();
			  				}

			  				$query_receiver  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$value->receiver_id));					 
			  				$receiver_details = $query_receiver->result();
			  				if (!empty($receiver_details)) {
			  					$receiver_detail =array(
			  											'app_username'=>$receiver_details[0]->app_username,
			  											'app_user_first_name'=>$receiver_details[0]->app_user_first_name,
			  											'app_user_last_name'=>$receiver_details[0]->app_user_last_name,
			  											'user_profile_picture_link'=>$receiver_details[0]->user_profile_picture_link,
			  											'chat_status'=>$receiver_details[0]->chat_status,
			  											);
			  					$message[$i]->receiver_details = $receiver_detail;
			  				}else{
			  					$message[$i]->receiver_details = array();
			  				}
			  				//print_r($sender_details[0]);	
			  		 	$i++;
			  		 }
			  	}
			  	$response['status'] = 200;
				$response['status_message'] = 'Message Send Successfully';
				$response['message'] = $message;
				$json_response = json_encode($response);
				echo $json_response;
		}else{
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();

		}	
	}

	// Get status
	public function GetMessageStatus()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id)); 
			$result = $query->row();
			$response['status'] = 200;
			$response['status_message'] = 'User Current Status';
			$response['status_message'] = $result;
			$json_response = json_encode($response);
			echo $json_response;
		}else{
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();

		}	
	}

	//  update Status
	public function updateUserStatus($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$chat_status = array('chat_status'=>$post_data['chat_status'])	;
			$this->common_model->UpdateRecord($chat_status, 'ks_users' ,'app_user_id' ,$app_user_id);
			$response['status'] = 200;
			$response['status_message'] = 'User Chat status is been updated Successfully';
			$json_response = json_encode($response);
			echo $json_response;

		}else{
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();

		}	
	}

	public function ContactDetails()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$sql = "SELECT * FROM ks_chat_user WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$app_user_id."')     " ; 
			$check_response = $this->common_model->get_records_from_sql($sql);
			if (!empty($check_response)) {
				$app_users_ids = '' ;
				$i = 0 ;
				foreach ($check_response as  $value) {
					if ($value->owner_id == $app_user_id) {
							$app_users_ids = "'". $value->renter_id."',";
						}elseif ($value->renter_id == $app_user_id) {
							$app_users_ids = "'". $value->owner_id."',";
						}
				$sql ="SELECT project_name   FROM ks_user_gear_rent_details WHERE order_id = '".$value->order_id."' " ;
				$order_details = $this->common_model->get_records_from_sql($sql);
				if (!empty($order_details)) {
						$check_response[$i]->project_name =  $order_details[0]->project_name ;
				}else{
						$check_response[$i]->project_name = '';
				}
						
				$sql ="SELECT app_user_id , app_username,app_user_first_name,app_user_last_name,chat_status,user_profile_picture_link  FROM ks_users WHERE app_user_id IN (".rtrim($app_users_ids , ',').") " ;
				$user_details = $this->common_model->get_records_from_sql($sql);
				$check_response[$i]->user_details = $user_details	;

				
					$i++ ;
				}
				
				$response['status'] = 200;
				$response['status_message'] = 'Chat Users found Successfully';
				$response['result'] = $check_response;
				$json_response = json_encode($response);
				echo $json_response;
			}else{

				$response['status'] = 200;
				$response['status_message'] = 'No chat found';
				$response['result'] = array();
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}
			
		}else{
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();

		}		
	}




	// Get UserInfo
	function userinfo($token){
		if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
     		exit();
		}
		$token_decrypt = base64_decode($token);
		$token_array = explode("|",$token_decrypt);
		$email = $token_array[0];
		$secret_key = $token_array[1];
		$expire_time = $token_array[2];	
	
		$columns = "app_user_id";
		$table = "ks_users";
		$where_clause = array('primary_email_address'=>$email,'auth_secret_key'=>$secret_key,'expire_time'=>$expire_time);
		
		$query = $this->common_model->GetSpecificValues($columns,$table,$where_clause);
		$row = $query->result_array();
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
	}
	
	public function SearchUsers()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {

			 $sql = "SELECT ks_chat_user.* FROM ks_chat_user INNER JOIN ks_users ON ks_users.app_user_id =  ks_chat_user.renter_id  WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$app_user_id."')   AND (ks_users.app_username LIKE '%".$post_data['search_key']."%' OR  ks_users.app_user_first_name LIKE '%".$post_data['search_key']."%' OR  ks_users.app_user_last_name LIKE '%".$post_data['search_key']."%' ) " ; 
			 $check_response2 = $this->common_model->get_records_from_sql($sql);

			 $sql1 = "SELECT ks_chat_user.* FROM ks_chat_user INNER JOIN ks_users ON ks_users.app_user_id =  ks_chat_user.owner_id  WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$app_user_id."')   AND ks_users.app_username LIKE '%".$post_data['search_key']."%'   " ; 
			 $check_response1 = $this->common_model->get_records_from_sql($sql1);
			 $check_response =  array_merge($check_response2, $check_response1);
			if (!empty($check_response)) {
				$app_users_ids = '' ;
				$i = 0 ;
				foreach ($check_response as  $value) {
					if ($value->owner_id == $app_user_id) {
							$app_users_ids = "'". $value->renter_id."',";
						}elseif ($value->renter_id == $app_user_id) {
							$app_users_ids = "'". $value->owner_id."',";
						}
				$sql ="SELECT app_user_id , app_username,app_user_first_name,app_user_last_name,chat_status,user_profile_picture_link  FROM ks_users WHERE app_user_id IN (".rtrim($app_users_ids , ',').")  " ;
				$user_details = $this->common_model->get_records_from_sql($sql);
				$check_response[$i]->user_details = $user_details	;

					$i++ ;
				}
				$response['status'] = 200;
				$response['status_message'] = 'Chat Users found Successfully';
				$response['result'] = $check_response;
				$json_response = json_encode($response);
				echo $json_response;
			}else{

				$response['status'] = 200;
				$response['status_message'] = 'No chat found';
				$response['result'] = array();
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}
		}else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
		}	
	}	
}	
?>