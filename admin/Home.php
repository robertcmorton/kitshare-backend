<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','image_lib','email'));
		$this->load->model(array('common_model'));
		if($this->session->userdata('ADMIN_ID') =='') {
		  redirect('login');
		}

	}
	
	public function index()
	{
	    
	    
		$data = array();
		
		$dt= date('Y-m-d');
		$where="created_date like '%".$dt."%'";
		$data['newsletter'] = $this->common_model->countAll('tbl_newsletter',$where);
		$where=array('newsletter_id >'=>0);
		$data['newsLetterTotal'] = $this->common_model->countAll('tbl_newsletter',$where);
		$where="created_date like '%".$dt."%'";
		$data['contact'] = $this->common_model->countAll('tbl_contact',$where);
		$where=array('contact_id >'=>0);
		$data['contactTotal'] = $this->common_model->countAll('tbl_contact',$where);
		
		$where="created_date like '%".$dt."%'";
		$data['student'] = $this->common_model->countAll('tbl_registered_student',$where);
		
		$where=array('reg_student_id >'=>0);
		$data['studentTotal'] = $this->common_model->countAll('tbl_registered_student',$where);
		
		
		$where="created_date like '%".$dt."%'";
		$data['blog'] = $this->common_model->countAll('tbl_blog',$where);
		
		$where=array('blog_id >'=>0);
		$data['blogTotal'] = $this->common_model->countAll('tbl_blog',$where);
		
		
		$where="created_date like '%".$dt."%'";
		$data['user'] = $this->common_model->countAll('tbl_admin',$where);
		
		$where=array('admin_id >'=>0);
		$data['userTotal'] = $this->common_model->countAll('tbl_admin',$where);
		
		$where="created_date like '%".$dt."%'";
		$data['exam_instruction'] = $this->common_model->countAll('tbl_instruction',$where);
		
		$where=array('instruction_id >'=>0);
		$data['instructionTotal'] = $this->common_model->countAll('tbl_instruction',$where);
		
		
		$where="created_date like '%".$dt."%'";
		$data['exam_instruction'] = $this->common_model->countAll('tbl_m_exam_category',$where);
		
		$where=array('exam_cat_id >'=>0);
		$data['instructionTotal'] = $this->common_model->countAll('tbl_m_exam_category',$where);
		
		
		$where="created_date like '%".$dt."%'";
		$data['exam_package'] = $this->common_model->countAll('tbl_exam_package',$where);
		
		$where=array('exam_package_id >'=>0);
		$data['packageTotal'] = $this->common_model->countAll('tbl_exam_package',$where);
		
		
		$where="created_date like '%".$dt."%' AND paid='Y' AND yearly='Y'";
		$data['yearly_subscription'] = $this->common_model->countAll('tbl_student_exam_payment',$where);
		
		$where=array('paid'=>'Y','yearly'=>'Y');
		$data['yearlySubscriptionTotal'] = $this->common_model->countAll(' tbl_student_exam_payment',$where);
		
		
		$where="created_date like '%".$dt."%' AND paid='Y' AND yearly='N'";
		$data['subscription'] = $this->common_model->countAll('tbl_student_exam_payment',$where);
		
		$where=array('paid'=>'Y','yearly'=>'N');
		$data['subscriptionTotal'] = $this->common_model->countAll(' tbl_student_exam_payment',$where);
		
		
		
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('dashboard', $data);
		$this->load->view('common/footer');		
	}

}?>
