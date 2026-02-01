<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forget_password extends CI_Controller {

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
		$emailid = $post_data['primary_email_address'];
		$json_response = ""; 
		
		if ($emailid==''){ ////////// if email field left blank
				
				$response['status'] = 204;
				$response['status_message'] = 'Email is required';
				$json_response = json_encode($response);
				echo $json_response;				
				
				//header('HTTP/1.1 204 No Content');
				exit();
		}else{
				$user = $this->common_model->RetriveRecordByWhereRow('ks_users', array('primary_email_address' => $emailid, 'user_signup_type'=>'EM'));
				//echo $this->db->last_query(); 
				
				$name = $user['app_user_first_name'].' '.$user['app_user_last_name'];
				$status =  $user['is_active'];
				$block =  $user['is_blocked'];
				$app_user_id = $user['app_user_id'];
				$time=time();
				if ( $user['app_user_id'] > 0){
					
					if($status=='N')	{
					
						$response['status'] = 403;
						$response['status_message'] = 'Account is inactive.';
						echo $json_response;
					
						//header('HTTP/1.1 403 Forbidden');
						exit();
					}
					if($block=='Y')	{
					
						$response['status'] = 410;
						$response['status_message'] = 'Account is blocked.';
						echo $json_response;
					
						//header('HTTP/1.1 410 Gone');
						exit();
					}		
					$to = $emailid;
					$name = ucwords($name);
					//$sender_mail= 'noreply@inferasolz.com';
					
					$key = md5($this->common_model->RandomNumber(15));
										
					$url = REDIRECT_HOST."/reset-password/".md5($key);
					////////////// get mail templete from mail_model
					$mail_body = $this->mail_model->forgetpassword_mail($name,$url);
					$subject = "Reset your password, and we'll get you on your way.";


					$mail_data = array(
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
					$this->common_model->sendMail($mail_data);
					$data['reset_key']= $key;
					$data['reset_pwd_date']= date('Y-m-d');
					$nowtime = date("H:i:s");
					$data['reset_pwd_expire_time'] = date('H:i:s', strtotime($nowtime . ' + 60 minute'));
					$this->db->where('app_user_id', $app_user_id);
					$this->db->update('ks_users', $data); 
					
					$response['status'] = 200;
					$response['status_message'] = 'Reset password link has been sent to your email.';
					$json_response = json_encode($response);				
					
					echo $json_response;
					//header('HTTP/1.1 200 OK');
					exit();	
				}else{
				
					$response['status'] = 401;
					$response['status_message'] = 'User not found.';
					$json_response = json_encode($response);
					echo $json_response;
				
					//header('HTTP/1.1 401 Unauthorized');
					exit();
				}
		
		}		
	}		
			
	

}?>
