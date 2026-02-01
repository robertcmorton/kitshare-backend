<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot_password extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text','common_helper'));
		$this->load->library(array('session','form_validation','pagination','email'));
		$this->load->model(array('common_model','mail_model'));
	}
	
	protected $validation_rules = array
        (
		'forgotPass' => array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required'
            ),
			
			
        ),
    );
	
	
	protected $validation_rules1 = array
        (
		'passChange' => array(
			array(
                'field'   => 'password',
                'label'   => 'New Password',
                'rules'   => 'trim|required'
            ),
			array(
                'field'   => 'conf_password',
                'label'   => 'Confirm Password',
                'rules'   => 'trim|required|matches[password]'
            )
			
        ),
    );
	
	
	
	public function index()
	{		
	    $data = array();
		$this->load->view('forgot-pass');
	}
	
	
	public function send()
	{
	
	$data=array();
	
	$email = $this->input->post('email');
	
	$this->form_validation->set_rules($this->validation_rules['forgotPass']);
	if($this->form_validation->run())
	{
		
		$sql="select m_app_user_id,app_user_name,app_user_email from m_app_user where app_user_email='".$email."'";
		$record = $this->common_model->get_records_from_sql($sql);
		
		//print_r($record); die;
		if(count($record)>0)
		{
			$data['reset_key']=  RandomNumber(4); 
			$data['reset_date_time']= date('Y-m-d H:i:s') ;
			$this->db->where('app_user_email', $email);
			$this->db->update('m_app_user', $data);
			
			echo $mailContent = 'Dear '.$record[0]->app_user_name.',<br/>
							<a href="'.base_url().'forgot_password/reset_password?reset_key='.$data['reset_key'].'&email='.$email.'">Click Here to reset password for your Kitshare account</a><br/>';
			
			$sender = "info@kitshare.com";
			
			$to = $email;
			$subject = "Forgot Password";
			$mail_body = $this->mail_model->mail_content($mailContent);
			
			//echo $mail_body; die;
			
			send_email($sender, $to , $subject, $mail_body);
			
			
			
			$message = '<div class="alert alert-success"><p>A mail has been sent to your email address!</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('forgot_password');
		 }else{
			$message = '<div class="alert alert-danger" role="alert">Email address not exists.</div>';
			$this->session->set_flashdata('success', $message);
			redirect('forgot_password');	
		 }
	}else{
					
		$this->load->view('forgot-pass');
	
		}
	
	}
	
	
	public function reset_password(){
	
		if($this->input->get()!='')
		{
			$reset_key = $this->input->get('reset_key');
			$app_user_email = $this->input->get('email');
			
			$sql="select * from m_app_user where app_user_email='".$app_user_email."' AND reset_key='".$reset_key."'";
		    $record = $this->common_model->get_records_from_sql($sql);
			
			if(count($record)>0)
			{
				//$str = $record[0]->reset_date_time;
				//$arr = explode(" ",$str);
				//$reset_pwd_date = strtotime($arr[0]);
				//$reset_pwd_expire_time = strtotime($arr[1]);
				
			    //if($reset_pwd_date == date('Y-m-d') and $reset_pwd_expire_time >= date("H:i:s") ){
				   
					$this->session->set_userdata('ID', $record[0]->m_app_user_id); 
					$this->load->view('reset-password');
				//}
			}
			else
			{
				//echo "something went wrong";
			   $message = '<div class="alert alert-danger"><p>Something went wrong</p></div>';
			   $this->session->set_flashdata('success', $message);
			   $this->load->view('reset-password');
			
			}
			
			
			
		}
	}
	
	
	public function change_password()
	{
	    $id = $this->session->userdata('ID');
		$this->form_validation->set_rules($this->validation_rules1['passChange']);
		if($this->form_validation->run()){

			$data['app_user_pwd']=  $this->common_model->base64En(2,$this->input->post('password')); 
			$data['reset_key'] = 0;
			$data['reset_date_time'] = '0000-00-00 00:00:00';
			$data['updated_date']= date('Y-m-d') ;
			$data['updated_time']=  date('H:i:s') ;
			$this->db->where('m_app_user_id', $id);
			$this->db->update('m_app_user', $data); 
			$message = '<div class="alert alert-success"><p>Password has been successfully updated. Please login. </p></div>';
			$this->session->set_flashdata('success', $message);
			redirect();
		}
		else
		{
		
			$this->load->view('reset-password');
		}
		
	
	}
	
	
	
	


	
}?>