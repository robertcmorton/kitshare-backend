<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
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
			$post_data		   = array();
			$json_response	   = "";
			
			$post_data         = json_decode(file_get_contents("php://input"),true);
			
			if(is_array($post_data) && count($post_data)>0 ){
				$email			   = $post_data['primary_email_address'];
				$app_password	   = $post_data['app_password'];
				
				if(array_key_exists("keep_me_signed_in",$post_data)){
					$keep_me_signed_in = $post_data['keep_me_signed_in'];
				}else
					$keep_me_signed_in = 0;
				
			
				if ($email=='' &&  $app_password==''){ ////////// if all field left blank
					
					$response['status'] = 204;
					$response['status_message'] = 'Marked fields are mandatory.';
					$json_response = json_encode($response);
					echo $json_response;
					
					//header('HTTP/1.1 204 No Content');
					exit();
				}
			
				if($email==''){
				
					$response['status'] = 204;
					$response['status_message'] = 'Email is required.';
					$json_response = json_encode($response);
					echo $json_response;
				
					//header('HTTP/1.1 204 No Content');
					exit();
				}else{
					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						
						$response['status'] = 406;
						$response['status_message'] = 'Invalid Email Address.';
						$json_response = json_encode($response);
						echo $json_response;
						
						//header('HTTP/1.1 406 Not Acceptable');
						exit();
					
					}
				}
				if($app_password!=""){
					if(strlen($app_password) < 6){
					
							$response['status'] = 411;
							$response['status_message'] = 'Password should not be less than 6 characters.';
							$json_response = json_encode($response);
							echo $json_response;
							
							//header('HTTP/1.1 411 Length Required');
							exit();						
					}
					$app_password		 = md5(strrev($app_password));
					
				}else{
				
					$response['status'] = 204;
					$response['status_message'] = 'Password should not be left blank.';
					$json_response = json_encode($response);
					echo $json_response;
				
					//header('HTTP/1.1 204 No Content');
					exit();
				}
				$query = $this->home_model->loginCheck( $email, $app_password, "EM");
				//echo $this->db->last_query();
					//print_r($query->num_rows());die;		
				if($query->num_rows()>0)  
				{
					$row = $query->row_array();	
					
					if($row['is_active']=='N')	{
						
						$response['status'] = 403;
						$response['status_message'] = 'Account is inactive.';
						$json_response = json_encode($response);
						echo $json_response;
						
						//header('HTTP/1.1 403 Forbidden');
						exit();
					}else if($row['is_blocked']=='Y')	{
					
						$response['status'] = 410;
						$response['status_message'] = 'Account is blocked.';
						$json_response = json_encode($response);
						echo $json_response;
					
						//header('HTTP/1.1 410 Gone');
						exit();
					}else{	
					
						//Once the login credentials are matched token is generated
						//$token = bin2hex(openssl_random_pseudo_bytes(16));
		
						# or in php7
						//$token = bin2hex(random_bytes(16));						
						
						$data1['app_user_id'] = $row['app_user_id'];
						$user_data['user_details'] = $row;
						
						
						//Checked whether the user has opted for Keep me signed in
						if($keep_me_signed_in == 1){
						
							//Cookie value is generated
							//$cookie_value = base64_encode($row['app_username']);
							
							//set_cookie("kitshare_login",$cookie_value,"120");
							
							$expire_time = 31556952; //1 year
						
						}else
							$expire_time = 86400;  //24 hours
							//$expire_time = 120;  //24 hours
						
						//JWT Token is created here
						$token = $this->common_model->create_token($row['app_user_id'],$expire_time);
						
						//Token is stored in cookie for 7 days
						set_cookie("kitshare_access_token",$token,"604800");
						
						$device_data = $this->common_model->checkDevice();
					
						$data = array_merge($data1,$device_data);
					
						//Checked whether the user has logged in using the same browser or device
						$where_clause = array("browser"=>$data['browser'],"browser_version"=>$data['browser_version'],"device"=>$data['device'],"device_type"=>$data['device_type'],"app_user_id"=>$data['app_user_id']);
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
					
					//Checked whether the user has signed up using FB or GM
					$query = $this->home_model->loginCheck( $email, "");
					
					if($query->num_rows()>0)  
					{
						$row = $query->row_array();	
						
						if($row['user_signup_type']=="FB")
							$msg = 'This account has been created using Facebook Signup, so please try to login with <b>Continue with Facebook</b> button below.';
						else if($row['user_signup_type']=="GM")
							$msg = 'This account has been created using Google Signup, so please try to login with <b>Continue with Google</b> button below.';
					
						$response['status'] = 401;
						$response['status_message'] = $msg;
						
					}else{
					
						$response['status'] = 401;
						$response['status_message'] = "Invalid login details.";
					}
					$json_response = json_encode($response);
					echo $json_response;
						
					//header('HTTP/1.1 401 Unauthorized');
					exit();
				}
			}else{
				header('HTTP/1.1 200 Success');
				exit();
			}
		
	}

	function getUserIpAddr(){
        $ip = $_SERVER['REMOTE_ADDR'];
    	return $ip;
	}
	

}?>
