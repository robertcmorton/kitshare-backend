<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_login extends CI_Controller {
	 public function __construct() {
		parent::__construct();
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
				//echo $this->db->last_query();
				//echo $user_check->num_rows(); exit();
				if ($user_check->num_rows() > 0){
					$row = $user_check->row_array();
					$update_array=array(
										"secret_key"=>$secret_key,
										"update_date"=>date('Y-m-d')
										);
										$this->common_model->UpdateRecord($update_array, 'ks_users', 'app_user_id' , $row['app_user_id']);
										
							header('HTTP/1.1 200 OK');
							exit();
				
				}else{
				$last_userid= $this->common_model->RetriveRecordByWhereLimit('ks_users', array(), 1,'app_user_id','DESC');
				$last_userid = $last_userid->row_array();
				$app_user_id= $last_userid['app_user_id']+1;
				$app_username= 'KIT_'.$app_user_id;
					//Record is inserted
					$insert_array=array("app_user_first_name"=>$name,
										"primary_email_address"=>$email,
										"secret_key"=>$secret_key,
										"owner_type_id" => 4,
										"app_username" => $app_username,
										"is_active"=>"Y",
										"user_signup_type"=>"FB",
										"create_date"=>date('Y-m-d')
										);
					
							$this->common_model->AddRecord( $insert_array, 'ks_users');
					
				}
				
				$expiry_time=date("F j, Y, H:i", time()+3600);
				$expire_time=date("h:i",strtotime($expiry_time));
				
				//Authentication token is generated
				$authen_token=base64_encode($email."|".$secret_key."|".$expire_time);
				$auth_arr=array("auth_token"=>$authen_token,
								"email"=>$email);
				echo json_encode($auth_arr);
			}else{
			
				$response['status'] = 401;
				$response['status_message'] = 'Email is required';
				$json_response = json_encode($response);
				echo $json_response;
				//header('HTTP/1.1 401 Unauthorized');
			}


	}
	
	
	
	

}?>
