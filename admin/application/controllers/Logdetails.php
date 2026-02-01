<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logdetails extends CI_Controller {

	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text','common_helper'));
		$this->load->library(array('session','form_validation','pagination','email','upload','excel'));
		$this->load->model(array('common_model','mail_model','model'));
		if($this->session->userdata('ADMIN_ID') =='') {
          redirect('login');
		  }
	}
	
	


	public function index()
	{
		 $query = $this->common_model->GetAllWhere('ks_crone_log',''); 
		 $data['result'] =  $query->result();
		 $this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('logs/list', $data);
		$this->load->view('common/footer');		
	}

}
?>