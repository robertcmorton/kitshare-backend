<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends CI_Controller {

	public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text','cookie'));
		$this->load->model(array('common_model','home_model'));
	}
	
	public function index()
	{
	
		$response		   = array();
		$post_data		   = array();
		$json_response	   = "";
		
		
		//Checked whether the cookie is set or not
		$cookie = get_cookie("kitshare_login");
		
		if(empty($cookie)==false){
			//User's record is updated with logout details depending upon his device and browser type
			$username = base64_decode($cookie);
			
			//Device detection is done
			$device_data = $this->common_model->checkDevice();
			
			$data['user_details'] = $this->common_model->fetchUserDetails($username);
			
			if($data['user_details']!=0){
				$app_user_id = $data['user_details'][0]['app_user_id'];
			
				//Checked whether the user has logged in using the same browser or device
				$where_clause = array("browser"=>$device_data['browser'],"browser_version"=>$device_data['browser_version'],"device"=>$device_data['device'],"device_type"=>$device_data['device_type'],"app_user_id"=>$app_user_id);
				$query = $this->common_model->RetriveRecordByWhereLimit("ks_user_activity_log",$where_clause,1,"activity_log_id","DESC");
			
				if($query->num_rows()>0){			
					
					$row = $query->result_array();
					$activity_log_id = $row[0]['activity_log_id'];

				
					//Table is updated
					$update_arr = array();
					$updated_arr['access_token'] = "";
					$updated_arr['logout_time'] = date("Y-m-d H:i:s");
					$updated_arr['updated_date'] = date("Y-m-d");
					$updated_arr['updated_time'] = date("H:i:s");
					
					//Record is updated in the table
					$this->common_model->UpdateRecord($updated_arr,"ks_user_activity_log",'activity_log_id',$activity_log_id);
				
				}
				
			
			}
			
			//Cookie is deleted
			delete_cookie("kitshare_login");
			
			//Cookie is deleted
			delete_cookie("kitshare_access_token");
			
			$response['status'] = 200;
			$response['status_message'] = 'Successfully logged out!';
			
			$json_response = json_encode($response);
			echo $json_response;
			exit();
			
			
		}else{
		
			$post_data         = json_decode(file_get_contents("php://input"),true);
			
			if(is_array($post_data) && count($post_data)>0 ){
		
				//Token is grabbed here for login
				$token = $post_data['token'];
				
				$userinfo = $this->common_model->fetchTokenDetails($token,'all');
				
				if(count($userinfo)>0){
				
					//Table is updated
					$activity_log_id = $userinfo[0]['activity_log_id'];
					
					//Table is updated
					$update_arr = array();
					$updated_arr['access_token'] = "";
					$updated_arr['logout_time'] = date("Y-m-d H:i:s");
					$updated_arr['updated_date'] = date("Y-m-d");
					$updated_arr['updated_time'] = date("H:i:s");
					
					//Record is updated in the table
					$this->common_model->UpdateRecord($updated_arr,"ks_user_activity_log",'activity_log_id',$activity_log_id);
					
					//Cookie is deleted
					delete_cookie("kitshare_access_token");
					
					$response['status'] = 200;
					$response['status_message'] = 'Successfully logged out!';
					
					$json_response = json_encode($response);
					echo $json_response;
					exit();
				
				}else{
				
					$response['status'] = 204;
					$response['status_message'] = 'This user doesn\'t exist.';
				
					$json_response = json_encode($response);
					echo $json_response;
					exit();
				
				}
			}else{
				header('HTTP/1.1 200 Success');
				exit();
			}
		
		}
		
	}
	
	
}