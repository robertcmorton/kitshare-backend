<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration extends CI_Controller {
	 public function __construct($config = 'rest') {
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
			$response = array();
			$data = array();
			$json_response = "";
			
			$post_data = json_decode(file_get_contents('php://input'), true);
			
						
			$email=$post_data['primary_email_address'];
			$app_user_first_name =$post_data['app_user_first_name'];
			$app_user_last_name =$post_data['app_user_last_name'];
			$app_password =$post_data['app_password'];
			
			if (!isset($email) && empty($email)==true && !isset($app_user_first_name) && empty($app_user_first_name)==true && !isset($app_user_last_name) && empty($app_user_last_name)==true && !isset($app_password) && empty($app_password)==true){ ////////// if all field left blank
				
				$response['status'] = 204;
				$response['status_message'] = 'Marked fields are mandatory';
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			}
			
			if(isset($app_user_first_name) && empty($app_user_first_name)==false){
			
				$data['app_user_first_name']	=	$app_user_first_name;
				
			}else if(!isset($app_user_first_name) && empty($app_user_first_name)==true){
			
				$response['status'] = 204;
				$response['status_message'] = 'Firstname is required';
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			}
			if(isset($app_user_last_name) && empty($app_user_last_name)==false){
			
				$data['app_user_last_name']		=	$app_user_last_name;
				
			}else if(!isset($app_user_last_name) && empty($app_user_last_name)==true){
			
				$response['status'] = 204;
				$response['status_message'] = 'Lastname is required';
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			}
			
			if(!isset($email) && empty($email)==true){
			
				$response['status'] = 204;
				$response['status_message'] = 'Email is required';
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			}else if(isset($email) && empty($email)==false){
			
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					
					$response['status'] = 406;
					$response['status_message'] = 'Invalid Email Address';
					$json_response = json_encode($response);
					echo $json_response;
					exit();
				
				}
				$result  	= 	$this->common_model->RetriveRecordByWhere('ks_users',array('primary_email_address'=> $email));
				if ($result->num_rows() > 0){  /////////// check email exist or not
					
					$response['status'] = 409;
					$response['status_message'] = 'Email already exists';
					$json_response = json_encode($response);
					echo $json_response;
					exit();
					
				}
				$data['primary_email_address']= $email;
			}
			if(isset($app_password) && empty($app_password)==false){
			
				$data['app_password']		 = md5(strrev($app_password));
				if(strlen($app_password) < 6){
						
						$response['status'] = 411;
						$response['status_message'] = 'Password should not be less than 6 characters';
						$json_response = json_encode($response);
						echo $json_response;
						exit();				
				}
				
			}else if(!isset($app_password) && empty($app_password)==true){
			
				$response['status'] = 204;
				$response['status_message'] = 'Password is required';
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			}
			
			if(count($data)>0){
			
				$auth_secret_key = md5($this->common_model->RandomNumber(8));
				$ip_address =  $this->getUserIpAddr();
				$time = time();
				$last_userid= $this->common_model->RetriveRecordByWhereLimit('ks_users', array(), 1,'app_user_id','DESC');
				$last_userid = $last_userid->row_array();
				$app_user_id= $last_userid['app_user_id']+1;
				$slug_name =  $this->alphanumericAndSpace(trim($post_data['app_user_first_name'].'-'.$post_data['app_user_last_name'] ));
				$slug_name = str_replace(' ', "-", $slug_name );
				$app_username= $slug_name.'-'.$app_user_id;
				$data['app_username']		= $app_username;
				$data['ip_address']		= $ip_address;
				$data['owner_type_id']		=  4; 
				$data['user_signup_type']	= 'EM';
				$data['authentication_code']= md5($time);
				$data['auth_secret_key']	= $auth_secret_key;
				$data['is_active']			= 'N';
				$data['create_date']		= date('Y-m-d');
				$insertid  					= $this->common_model->AddRecord($data,'ks_users');
			
			if($insertid){
				/////////////// Welcome mail ////////
				$to =$email;
					  
				$name = ucwords($app_user_first_name);
				$sender_mail= 'hello@kitshare.com.au';
				$url = base_url()."registration/account_verify?email=".urlencode($email)."&key=".md5($time);
				
				////////////// get mail templete from mail_model
				$mail_body = $this->mail_model->welcome_mail($name,$url);
				$subject = 'Welcome to Kitshare';
				
				$val = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"kitshare ",
												),
											"To"=>array(
													array("Email"=>$to,
													"Name"=>"",
													),
												),
											"Subject"=> $subject,
					                        "TextPart"=> "",
					                        "HTMLPart"=> $mail_body
										),
									)
								);	
				//print_r(json_encode($val));		    
    			//$this->sendMail($val);
				$this->common_model->sendMail($val);
				
				$response['status'] = 200;
				$response['status_message'] = 'Thanks for registering with Kitshare. An Activation email has been sent to your email address';
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			} else {
				
				$response['status'] = 417;
				$response['status_message'] = 'Oops! Something went wrong. Try again!';
				$json_response = json_encode($response);
				echo $json_response;
				exit();	
			}	
		}	
	}
	
	public function check_email()
	{
		$post_data = json_decode(file_get_contents('php://input'), true);	
			
	    $email = $post_data['primary_email_address'];
		$response = array();
		
		$json_response = "";
		
		///// Check email already exist in table or not
		$email_exist = $this->common_model->RetriveRecordByWhere('ks_users', array('primary_email_address'=>$email));
				
		 if( $email_exist->num_rows() > 0 ){
		 
			$response['status'] = 409;
			$response['status_message'] = 'Email already exists!';
			$json_response = json_encode($response);
			echo $json_response;
			exit();
			
			
		 } else {
		 
			$response['status'] = 200;
			$response['status_message'] = "Email doesn't exist!";
			$json_response = json_encode($response);
			echo $json_response;
			exit();
		}	
	}
	public function account_verify()
	{
		$email 		 = $this->input->get('email');
		$key 		 = $this->input->get('key');
		$data 		 = array();
		$response    = array();
		
		$json_response = "";
		$user = $this->common_model->RetriveRecordByWhereRow('ks_users', array('authentication_code' => $key , 'primary_email_address' => $email));
				 
		if (!empty($user)){
			$app_user_id 		  = $user['app_user_id'];
			$data['authentication_code']= 0;
			$data['is_active']			= 'Y';
			$data['update_date']=  date('Y-m-d');
			$this->common_model->UpdateRecord($data, 'ks_users', 'app_user_id', $app_user_id);
			header("location:".REDIRECT_HOST."/login/success");
			exit();
		}else{
		
			$response['status'] = 417;
			$response['status_message'] = "User not found.";
			$json_response = json_encode($response);
			header("location:".REDIRECT_HOST."/login/error");
			exit();
		}
	
	}
	
	public function userRegistration(){
		$this->load->library('MailChimp');
		$list_id = '3d52edc3ec';
		
		$post_data = json_decode(file_get_contents("php://input"));		
		$email = $post_data->emailid;
		
		$json_response = "";
		
		$data['email_id'] = $email; 
		$insertid = $this->common_model->AddRecord($data,'ks_user_email_subscription');
		if($insertid){
			$update_data['create_user'] = $insertid;
			$update_data['create_date'] = date('Y-m-d');
			$update_data['update_user'] = '0';
			$update_data['update_date'] = '0000-00-00';			
			$this->common_model->UpdateRecord($update_data, 'ks_user_email_subscription', 'email_id', $email);
			
			$result = $this->mailchimp->post("lists/$list_id/members", [
                'email_address' => $email,
                'status'        => 'subscribed',
            ]);
			
			$response['status'] = 200;
			$response['status_message'] = 'User Subscribed';
			$json_response = json_encode($response);
		}
		
		return $json_response;
	}
	
	public function sendMail($data)
	{
		
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.mailjet.com/v3.1/send",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => json_encode($data) ,
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Basic MTE1MGEzYWZkMzg3MzM0ZjU1YmJiMWQ5MTI3YWE5Y2M6N2U3MzdkNjNiMWE1NDQ3NjgwNzNlZjVjOTlmMTQ2YmU=",
			    "Content-Type: application/json",
			    "Postman-Token: 5dacc0e7-eff8-430b-ba53-b9a520abed72",
			    "cache-control: no-cache"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			//  echo "cURL Error #:" . $err;
			} else {
			//  echo $response;
			}

	}

	function getUserIpAddr(){
        $ip = $_SERVER['REMOTE_ADDR'];
    	return $ip;
	}

	function alphanumericAndSpace( $string )
    {
        return preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
    }
}?>
