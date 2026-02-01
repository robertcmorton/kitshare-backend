<?php
  
   class Upload extends CI_Controller {
	
      public function __construct() { 
         parent::__construct(); 
         		$this->load->helper(array('url','form','html','text','common_helper'));
		$this->load->library(array('session','form_validation','pagination','email'));
		$this->load->model(array('common_model','mail_model','model'));
				if($this->session->userdata('ADMIN_ID') =='') {
          redirect('login');
		  }
	}
      
		
      	public function index()
	{
		$data=array();
		
		$id = $_GET['id'];
		$where_array = array('user_gear_desc_id'=>$id);
		$device= $this->model->device($where_array);
		$data['result'] = $device->result();
		
		$gear_categories=$this->common_model->GetAllWhere("gs_user_gear_description",array("is_active"=>'Y'));
		$data['gear_categories'] = $gear_categories->result();
		
			
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('device/uploadpic', $data);
		$this->load->view('common/footer');		
	}
		
      public function do_upload() { 
         $config['upload_path']   = './uploads/'; 
         $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
         $config['max_size']      = ""; 
         $config['max_width']     = ""; 
         $config['max_height']    = "";  
         $this->load->library('upload', $config);
			
         if ( ! $this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors()); 
            $this->load->view('device/uploadpic', $error); 
         }
			
         else { 
            $data = array('upload_data' => $this->upload->data()); 
            $this->load->view('device/upload_success', $data); 
         } 
      } 
   }
?>