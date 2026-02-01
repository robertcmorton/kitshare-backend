<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'/third_party/jwt/vendor/autoload.php');
use \Firebase\JWT\JWT;

class Authuser extends CI_Controller {
	 public function __construct() {
	 	header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email'));
		$this->load->model(array('common_model','home_model'));
		}
	
	public function index()
	{
			$response		   = array();
			$json_response	   = "";			
		
			//Checked whether cookie is set or not
			$cookie = get_cookie("kitshare_login");
			
			if($cookie!=""){
		
				//Users details are fetched
				$username = base64_decode($cookie);
				
				$user_details = $this->common_model->fetchUserDetails($username);
				
				if($user_details!=0){
				
					$app_user_id = $user_details[0]['app_user_id'];
					
					//Once the login credentials are matched token is generated
					$token = bin2hex(openssl_random_pseudo_bytes(16));
	
					# or in php7
					//$token = bin2hex(random_bytes(16));
					
					//Token is stored in cookie for 7 days
					set_cookie("kitshare_access_token",$token,"604800");
					
					$data = $this->common_model->checkDevice();
					
					//Checked whether the user has logged in using the same browser or device
					$where_clause = array("browser"=>$data['browser'],"browser_version"=>$data['browser_version'],"device"=>$data['device'],"device_type"=>$data['device_type'],"app_user_id"=>$app_user_id);
					$query = $this->common_model->RetriveRecordByWhereLimit("ks_user_activity_log",$where_clause,1,"activity_log_id","DESC");
					
					
					if($query->num_rows()>0){			
						
						$row = $query->result_array();
						$activity_log_id = $row[0]['activity_log_id'];
						
						//Update array
						$update_arr = array();
						$updated_arr['access_token']	 = $token;
						$updated_arr['updated_date'] = date("Y-m-d");
						$updated_arr['updated_time'] = date("H:i:s");
						
						//Record is updated in the table
						$this->common_model->UpdateRecord($updated_arr,"ks_user_activity_log",'activity_log_id',$activity_log_id);					

						
					}else{		
						
						$data['access_token']	= $token;
						$data['login_time'] 	= date("Y-m-d H:i:s");
						$data['created_date'] 	= date("Y-m-d");
						$data['created_time'] 	= date("H:i:s");
					
						//Record is inserted into the table
						$this->common_model->AddRecord($data,"ks_user_activity_log");
					}
					
					
					$response=array("status"=>200,
									"status_message"=>"success",
									"auth_token"=>$token);
					
					
					$json_response = json_encode($response);
					echo $json_response;
					exit();	
				
				}
				
				
			}else{
			
					$response=array("status"=>401,
									"status_message"=>"Unauthenticated user");
					
					
					$json_response = json_encode($response);
					echo $json_response;
					exit();	
			}
		
	}
	
	public function create_token(){
		
		$response		   = array();
		$post_data		   = array();
		$json_response	   = "";
			
		$post_data         = json_decode(file_get_contents("php://input"),true);
		$email			   = $post_data['primary_email_address'];
		$app_password	   = md5(strrev($post_data['app_password']));
		
		$query = $this->home_model->loginCheck( $email, $app_password);
				
		if($query->num_rows()>0)  
		{
			$row 		= $query->row_array();		
	
			$secretKey 	= SECRET_KEY;
			$issuedAt 	= time();
			$issued_nbf = $issuedAt + 10;
			$expire 	= $issued_nbf + 120;

			$payload = array(
				"iss" => WEB_URL_1,
				"aud" => WEB_URL_1,
				"iat" => $issuedAt,
				"nbf" => $issued_nbf,
				'exp'  => $expire, 
				'data' => [                  // Data related to the signer user
						'userId'   => $row['app_user_id'], // userid from the users table
						'userName' => $row['app_username'], // User name
				]
			);

			$jwt = JWT::encode($payload, $secretKey);
			
			$response=array("status"=>200,
									"status_message"=>"success",
									"auth_token"=>$jwt);
					
					
			$json_response = json_encode($response);
			echo $json_response;
			exit();
			
		}else{
			
			$response['status'] = 401;
			$response['status_message'] = "Invalid login details.";
			$json_response = json_encode($response);
			echo $json_response;

			exit();
			
		}
	}
	
	public function decode_token(){
		
		$jwt 			= $this->input->get('token');
		
		$secretKey = SECRET_KEY;
		
		try {
			$decoded = JWT::decode($jwt, $secretKey, array('HS256'));
			
			$response=array("status"=>200,
									"status_message"=>"success",
									"username"=>$decoded->data->userName,
									"user_id"=>$decoded->data->userId);
					
					
			$json_response = json_encode($response);
			echo $json_response;
			exit();
	
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			
			$response=array("status"=>401,
									"status_message"=>$e->getMessage());
					
					
			$json_response = json_encode($response);
			echo $json_response;
			exit();
		}

	}

}?>
