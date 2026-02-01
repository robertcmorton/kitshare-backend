<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email'));
		$this->load->model(array('common_model','home_model','mail_model'));
		}
	
	public function index()
	{
			$post_data = json_decode(file_get_contents("php://input"));
			$email=$post_data->primary_email_address;
			$app_user_first_name =$post_data->app_user_first_name;
			$app_user_last_name =$post_data->app_user_last_name;
			$app_password =$post_data->app_password;
			
			
			if ($email=='' && $app_user_first_name=='' && $app_user_last_name=='' && $app_password==''){ ////////// if all field left blank
				header('HTTP/1.1 204 No Content');
				exit();
			}
			
			if($app_user_first_name!=""){
				$data['app_user_first_name']	=	$app_user_first_name;
			}else{
				header('HTTP/1.1 204 No Content');
				exit();
			}
			if($app_user_last_name!=""){
				$data['app_user_last_name']		=	$app_user_last_name;
			}else{
				header('HTTP/1.1 204 No Content');
				exit();
			}
			if($email==''){
				header('HTTP/1.1 204 No Content');
				exit();
			}else{
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					header('HTTP/1.1 406 Not Acceptable');
					exit();
				
				}
				$result  	= 	$this->common_model->RetriveRecordByWhere('ks_users',array('primary_email_address'=> $email));
				if ($result->num_rows() > 0){  /////////// check email exist or not
					header('HTTP/1.1 409 Conflict');
					exit();
				}
			$data['primary_email_address']= $email;
			}	
			if($app_password!=""){
				$data['app_password']		 = md5(strrev($app_password));
				if(strlen($app_password) < 6){
						header('HTTP/1.1 411 Length Required');
						exit();				
				}
				
			}else{
				header('HTTP/1.1 204 No Content');
				exit();
			}
		$time = time();
		$last_userid= $this->common_model->RetriveRecordByWhereLimit('ks_users', array(), 1,'app_user_id','DESC');
		$last_userid = $last_userid->row_array();
		$app_user_id= $last_userid['app_user_id']+1;
		$app_username= 'KIT_'.$app_user_id;
		$data['app_username']		= $app_username;
		$data['owner_type_id']		=  4; 
		$data['user_signup_type']	= 'EM';
		$data['authentication_code']= md5($time);
		$data['is_active']			= 'N';
		$data['create_date']		= date('Y-m-d');
		$insertid  					= $this->common_model->AddRecord($data,'ks_users');
		
		if($insertid){
			/////////////// Welcome mail ////////
			$to =$email;
			      
			$name = ucwords($app_user_first_name);
			$sender_mail= 'info@kitshare.com';
			$url = base_url()."registration/account_verify?email=".urlencode($email)."&key=".md5($time);
			////////////// get mail templete from mail_model
			$mail_body = $this->mail_model->welcome_mail($name,$url);
			$subject = 'Welcome to Kitshare';
			$this->home_model->send_email($sender_mail,$to,$subject,$mail_body);
			header('HTTP/1.1 200 OK');
			echo $this->email->print_debugger();
			exit();
		} else {
			header('HTTP/1.1 417 Expectation Failed');
			exit();
		}		
	}
	
	public function check_email()
	{
	    $email = $this->input->post('primary_email_address'); 
		///// Check email already exist in table or not
		$email_exist = $this->common_model->RetriveRecordByWhere('ks_users', array('primary_email_address'=>$email));
			 if( $email_exist->num_rows() > 0 ){
				header('HTTP/1.1 409 Conflict');
				exit();
			 } else {
				header('HTTP/1.1 200 OK');
				exit();
			}	
	}
	public function account_verify()
	{
		$email 		 = $this->input->get('email');
		$key 		 = $this->input->get('key');
		$user = $this->common_model->RetriveRecordByWhereRow('ks_users', array('authentication_code' => $key , 'primary_email_address' => $email));
				 
		if (!empty($user)){
			$app_user_id 		  = $user['app_user_id'];
			$data['authentication_code']= 0;
			$data['is_active']			= 'Y';
			$data['update_date']=  date('Y-m-d');
			$this->common_model->UpdateRecord($data, 'ks_users', 'app_user_id', $app_user_id);
			header('Refresh:2; url= '. base_url()); 
			exit();
		}else{
			header('HTTP/1.1 417 Expectation Failed');
			exit();
		}
		//redirect('home');
	
	}
	
	

}?>
