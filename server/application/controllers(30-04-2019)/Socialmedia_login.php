<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Socialmedia_login extends CI_Controller {
	 public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email'));
		$this->load->model(array('common_model','home_model','mail_model'));
		}
		
	public function index(){
	
	}
	
	public function facebook()
	{
			$post_data = json_decode(file_get_contents("php://input"),true);
			
			//print_r($post_data);
			
			$name=urldecode($post_data['name']);
			$email=urldecode($post_data['email']);
			$facebook_id=urldecode($post_data['id']);
			$Image =urldecode($post_data['image']);
			
			$response = array();
			
			$json_response = ""; 
			
			//Auth secret key is generated
			$auth_secret_key = md5($this->common_model->RandomNumber(8));

			
			$auth_secret_key='';
			$firstname = '';
			$lastname = '';
			
			
			//If email field is not blank
			if($email!=""){
			

				//Checked whether the user already exists or not
				$where = array('primary_email_address'=>$email);
				$user_check = $this->common_model->RetriveRecordByWhere('ks_users',$where);
				
				$expiry_time=date("F j, Y, H:i", time()+3600);
				$expire_time=date("h:i",strtotime($expiry_time));

				if ($user_check->num_rows() > 0){
					$row = $user_check->row_array();
					$update_array=array(
										"user_profile_picture_link"=> $Image,
										"auth_secret_key"=>$auth_secret_key,
										"expire_time"=>$expire_time,
										"update_date"=>date('Y-m-d')
										);
					$this->common_model->UpdateRecord($update_array, 'ks_users', 'app_user_id' , $row['app_user_id']);
					$app_user_id = $row['app_user_id'];
				
				}else{
				
					$last_userid= $this->common_model->RetriveRecordByWhereLimit('ks_users', array(), 1,'app_user_id','DESC');
					$last_userid = $last_userid->row_array();
					$app_user_id= $last_userid['app_user_id']+1;
					$app_username= 'KIT_'.$app_user_id;
					
					if(strstr($name," ")){
						$name = explode(" ",$name);
						$firstname = $name[0];
						$lastname = $name[1];
					}else
						$firstname = $name;
					
					//Record is inserted
					$insert_array=array("app_user_first_name"=>$firstname,
										"app_user_last_name"=>$lastname,
										"primary_email_address"=>$email,
										"auth_secret_key"=>$auth_secret_key,
										"expire_time"=>$expire_time,
										"owner_type_id" => 4,
										"user_profile_picture_link"=> $Image,
										"app_username" => $app_username,
										"is_active"=>"Y",
										"user_signup_type"=>"FB",
										"create_date"=>date('Y-m-d')
										);
					
					$insert_id = $this->common_model->AddRecord( $insert_array, 'ks_users');
					$app_user_id = $insert_id;
					
				}
				
				
				
				//Authentication token is generated
				$authen_token=base64_encode($email."|".$auth_secret_key."|".$expire_time);
								
				$auth_arr=array("status"=>200,
								"status_message"=>"success",
								"auth_token"=>$authen_token);				
				
				echo json_encode($auth_arr);
				
			}else{
			
				$response['status'] = 401;
				$response['status_message'] = 'Email is required';
				$json_response = json_encode($response);
				echo $json_response;
				//header('HTTP/1.1 401 Unauthorized');
			}


	}
	
	public function gmail(){
		
			$post_data = json_decode(file_get_contents("php://input"),true);
			
			$name=urldecode($post_data['name']);
			$email=urldecode($post_data['email']);
			$gmail_id=urldecode($post_data['id']);
			$Image =urldecode($post_data['image']);
			$response = array();
			
			$json_response = ""; 
			
			//Auth secret key is generated
			$auth_secret_key = md5($this->common_model->RandomNumber(8));
			
			$firstname = '';
			$lastname = '';
			 
			//If email field is not blank
			if($email!=""){
							
				//Checked whether the user already exists or not
				$where = array('primary_email_address'=>$email);
				$user_check = $this->common_model->RetriveRecordByWhere('ks_users',$where);
				
				$expiry_time=date("F j, Y, H:i", time()+3600);
				$expire_time=date("h:i",strtotime($expiry_time));
				


				if ($user_check->num_rows() > 0){
					$row = $user_check->row_array();
					$update_array=array(
					                    "user_profile_picture_link"=> $Image,
										"auth_secret_key"=>$auth_secret_key,
										"expire_time"=>$expire_time,
										"update_date"=>date('Y-m-d')
										);
					$this->common_model->UpdateRecord($update_array, 'ks_users', 'app_user_id' , $row['app_user_id']);
					$app_user_id = $row['app_user_id'];
				
				}else{
				
					$last_userid = $this->common_model->RetriveRecordByWhereLimit('ks_users', array(), 1,'app_user_id','DESC');
					$last_userid = $last_userid->row_array();
					$app_user_id = $last_userid['app_user_id']+1;
					$app_username= 'KIT_'.$app_user_id;
					
					
					if(strstr($name," ")){
						$name = explode(" ",$name);
						$firstname = $name[0];
						$lastname = $name[1];
					}else{
						$firstname = $name;
						$lastname = '';
					}
					
					//Record is inserted
					$insert_array=array("app_user_first_name"=>$firstname,
						"app_user_last_name"=>$lastname,
										"primary_email_address"=>$email,
										"auth_secret_key"=>$auth_secret_key,
										"expire_time"=>$expire_time,
										"owner_type_id" => 4,
										"user_profile_picture_link"=> $Image,
										"app_username" => $app_username,
										"is_active"=>"Y",
										"user_signup_type"=>"GM",
										"create_date"=>date('Y-m-d')
										);
					//print_r($insert_array);die;
					$insert_id = $this->common_model->AddRecord( $insert_array, 'ks_users');
					$app_user_id = $insert_id;
					
				}
				
				//Authentication token is generated
				$authen_token=base64_encode($email."|".$auth_secret_key."|".$expire_time);
								
				$auth_arr=array("status"=>200,
								"status_message"=>"success",
								"auth_token"=>$authen_token);				
				
				echo json_encode($auth_arr);
			}else{
				//header('HTTP/1.1 401 Unauthorized');
				$response['status'] = 401;
				$response['status_message'] = 'Email is required';
				$json_response = json_encode($response);
				echo $json_response;
			}
		
	}
	
	
	
	

}?>
