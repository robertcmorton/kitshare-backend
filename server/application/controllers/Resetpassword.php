<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resetpassword extends CI_Controller {
	 public function __construct() {
	 	header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email'));
		$this->load->model(array('common_model','home_model','mail_model'));
		}
	
	
	public function index()
	{
		$post_data = json_decode(file_get_contents("php://input"),true);
		$password_1 = $post_data['newpassword'];
		$password_2 = $post_data['confirmpassword']; 
		$key = $post_data['token'];
		$json_response = ""; 
		
		//$email=base64_decode($post_data['email']);	
		// echo $email=base64_decode($key);	
		// die;

		
		if ($password_1=='' && $password_2==''){ ////////// if password field left blank
				
				$response['status'] = 204;
				$response['status_message'] = 'Password field should not be left blank.';
				$json_response = json_encode($response);
				echo $json_response;
				
				//header('HTTP/1.1 204 No Content');
				exit();
		}else{
			if(strlen($password_1) < 6){
						
						$response['status'] = 411;
						$response['status_message'] = 'Password should not be less than 6 characters.';
						$json_response = json_encode($response);
						echo $json_response;
						
						//header('HTTP/1.1 411 Length Required');
						exit();				
				}		
			if($password_1 == $password_2 ){
				$save_data = array(
				   'app_password' =>  md5(strrev($password_1)) ,
				   'update_date' => date('Y-m-d') ,
				   'reset_key' => 0
				);
			$key_exist = $this->db->get_where('ks_users', array("md5(`reset_key`)" => $key  ));	  
			if ($key_exist->num_rows() > 0)
			{
				$reset_pwd_expire_time = $key_exist->row()->reset_pwd_expire_time;
				$reset_pwd_date = $key_exist->row()->reset_pwd_date;
				if($reset_pwd_date == date('Y-m-d') and $reset_pwd_expire_time >= date("H:i:s") )
				{
					$this->db->where("md5(`reset_key`)", $key);
					$this->db->update('ks_users', $save_data); 
					
					$response['status'] = 200;
					$response['status_message'] = 'Password has been reset successfully!';
					$json_response = json_encode($response);
					echo $json_response;
					exit();
					}else{
					   
					    $response['status'] = 504;
						$response['status_message'] = 'This link has expired.';
						$json_response = json_encode($response);
						echo $json_response;
					   
					    //header('HTTP/1.1 504 Gateway Time-out');
						exit();
				   }

			}else {
				
				$response['status'] = 401;
				$response['status_message'] = 'Invalid Access Code';
				$json_response = json_encode($response);
				echo $json_response;
				
				//header('HTTP/1.1 401 Unauthorized');
				exit();
			 }
		}else{
		
			$response['status'] = 417;
			$response['status_message'] = 'Password and Confirm Password do not match.';
			$json_response = json_encode($response);
			echo $json_response;
		
		
			//header('HTTP/1.1 417 Expectation Failed');
			exit();
		}
	 }
	
	}		
			
	

}?>
