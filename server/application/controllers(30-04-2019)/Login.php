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
			$email			   = $post_data['primary_email_address'];
			$app_password	   = $post_data['app_password'];
			
			
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
			$query = $this->home_model->loginCheck( $email, $app_password);
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
				
					$auth_secret_key = md5($this->common_model->RandomNumber(8));
				
					$expiry_time=date("F j, Y, H:i", time()+3600);
					$expire_time=date("h:i",strtotime($expiry_time));
					
					$name = $row['app_user_first_name']." ".$row['app_user_last_name'];				
					
					//Update the expire time
					$update_arr = array();
					$update_arr['auth_secret_key'] = $auth_secret_key;
					$update_arr['expire_time'] = $expire_time;
					$this->common_model->UpdateRecord($update_arr,"ks_users","primary_email_address",$row['primary_email_address']);
					
					
					//Authentication token is generated
					$authen_token=base64_encode($row['primary_email_address']."|".$auth_secret_key."|".$expire_time);
					$response=array("status"=>200,
									"status_message"=>"success",
									"auth_token"=>$authen_token);
					
					
					$json_response = json_encode($response);
					echo $json_response;
					exit();
				}
			}else{
			
				$response['status'] = 401;
				$response['status_message'] = "Invalid login details.";
				$json_response = json_encode($response);
				echo $json_response;
					
				//header('HTTP/1.1 401 Unauthorized');
				exit();
			}
		
	}
	

}?>
