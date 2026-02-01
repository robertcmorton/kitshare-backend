<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_login extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email'));
		$this->load->model(array('common_model','home_model','mail_model'));
		}
	
	public function index()
	{
			$post_data = json_decode(file_get_contents("php://input"));
			
			$name=urldecode($post_data['name']);
			$email=urldecode($post_data['email']);
			$facebook_id=urldecode($post_data['id']);
			$json_response = ""; 
			
			$secret_key='';
			//If email field is not blank
			if($email!=""){
			//Secret key is generated here
			$secret_key=base64_encode($this->home_model->RandomNumber());

				//Checked whether the user already exists or not
				$where = array('primary_email_address'=>$email);
				$user_check = $this->common_model->RetriveRecordByWhere('ks_users',$where);
				if ($user_check->num_rows() > 0){
					$row = $user_check->row_array();
					$ip_address =  $this->getUserIpAddr();
					if($row['is_active'] == 'Y'){
								$update_array=array(
													"secret_key"=>$secret_key,
													"ip_address" => $ip_address,
													"update_date"=>date('Y-m-d')
													);
								$this->common_model->UpdateRecord($update_array, 'ks_users', 'app_user_id' , $row['app_user_id']);
										
								$app_user_id = $row['app_user_id'];

					}else{
						$response['status'] = 401;
						$response['status_message'] = 'Account is inactive';
						$json_response = json_encode($response);
						echo $json_response;
						exit;
					}		
				
				}else{
				
					$last_userid= $this->common_model->RetriveRecordByWhereLimit('ks_users', array(), 1,'app_user_id','DESC');
					$last_userid = $last_userid->row_array();
					$app_user_id= $last_userid['app_user_id']+1;
					$app_username= 'KIT_'.$app_user_id;
					$ip_address =  $this->getUserIpAddr();
					//Record is inserted
					$insert_array=array("app_user_first_name"=>$name,
										"primary_email_address"=>$email,
										"secret_key"=>$secret_key,
										"owner_type_id" => 4,
										"app_username" => $app_username,
										"ip_address" => $ip_address,
										"is_active"=>"Y",
										"user_signup_type"=>"FB",
										"create_date"=>date('Y-m-d')
										);
					
							$this->common_model->AddRecord( $insert_array, 'ks_users');
					
				}
				
				$expiry_time=date("F j, Y, H:i", time()+3600);
				$expire_time=date("h:i",strtotime($expiry_time));
				
				$expire_time = 86400; //86400
				
				//JWT Token is created here
				$token = $this->common_model->create_token($app_user_id,$expire_time);				
				
				$data = $this->common_model->checkDevice();
				
				$data['app_user_id']	= $app_user_id;
				$data['access_token']	= $token;
				$data['login_time'] 	= date("Y-m-d H:i:s");
				$data['created_date'] 	= date("Y-m-d");
				$data['created_time'] 	= date("H:i:s");
			
				//Record is inserted into the table
				$this->common_model->AddRecord($data,"ks_user_activity_log");
				
				$auth_arr=array("auth_token"=>$token,
								"email"=>$email);
				echo json_encode($auth_arr);
				
				
			}else{
			
				$response['status'] = 401;
				$response['status_message'] = 'Email is required';
				$json_response = json_encode($response);
				echo $json_response;
			}


	}

	function getUserIpAddr(){
        $ip = $_SERVER['REMOTE_ADDR'];
    	return $ip;
	}
	
}?>
