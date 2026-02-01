<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forget_password extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email'));
		$this->load->model(array('common_model','home_model','mail_model'));
		}
	
	public function index()
	{
			
		$post_data = json_decode(file_get_contents("php://input"));
		$emailid=$post_data->primary_email_address;
		
		if ($emailid==''){ ////////// if email field left blank
				header('HTTP/1.1 204 No Content');
				exit();
		}else{
				$user = $this->common_model->RetriveRecordByWhereRow('gs_users', array('primary_email_address' => $emailid, 'user_account_type'=>'EM'));
				//echo $this->db->last_query(); 
				$name = $user['app_user_first_name'].' '.$user['app_user_last_name'];
				$status =  $user['is_active'];
				$block =  $user['is_blocked'];
				$app_user_id = $user['app_user_id'];
				$time=time();
				if ( !empty ($user['app_user_id']) > 0){
					
					if($status=='N')	{
						header('HTTP/1.1 403 Forbidden');
						exit();
					}
					if($block=='Y')	{
						header('HTTP/1.1 410 Gone');
						exit();
					}		
				$to = $emailid;
				$name = ucwords($name);
				$sender_mail= 'info@kitshare.com';
				$url = base_url()."resetpassword/email=".base64_encode($emailid)."&key=".md5($time);
				////////////// get mail templete from mail_model
				$mail_body = $this->mail_model->forgetpassword_mail($name,$url);
				$subject = "Reset your password, and we'll get you on your way.";
				$this->home_model->send_email($sender_mail,$to,$subject,$mail_body);
					//echo $this->email->print_debugger(); exit;
				$data['reset_key']= $time;
				$data['reset_pwd_date']= date('Y-m-d');
				$nowtime = date("H:i:s");
				$data['reset_pwd_expire_time'] = date('H:i:s', strtotime($nowtime . ' + 60 minute'));
				$this->db->where('app_user_id', $app_user_id);
				$this->db->update('gs_users', $data); 
				header('HTTP/1.1 200 OK');
				exit();	
				}else{
				header('HTTP/1.1 401 Unauthorized');
				exit();
				}
		
		}		
	}		
			
	

}?>
