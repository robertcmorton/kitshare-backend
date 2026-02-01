<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cancellation_policy extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','pagination','email'));
		$this->load->model(array('common_model','mail_model'));
		if($this->session->userdata('ADMIN_ID') =='') {
		  redirect('login');
		}
	}
	
	public function index()
	{
		$data=array();
		$where = "";
		$limit=10;
		
		$data['gear_name']				= $this->input->get('gear_name');
		if($data['gear_name'] != ''){
				$where .=  " where b.gear_name LIKE '%".trim($data['gear_name'])."%' AND ";
				//$where .=  " a.is_active == 'y' ";
			}
		
		if($this->input->get("per_page")!= '')
		{
		$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		$nSer="SELECT a.*,b.* 
			   FROM ks_user_gear_cancel_policy a
			   INNER JOIN ks_user_gear_description as b ON a.user_gear_desc_id=b.user_gear_desc_id
			  ".$where." where a.is_active = 'y'";
		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		//echo $this->db->last_query();
		//die ();
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $limit;
		$config['page_query_string'] = TRUE;
	    $config['full_tag_open'] = "<ul class='pagination pagination-sm text-center'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		$this->pagination->initialize($config);
		$paginator = $this->pagination->create_links();
//////////////////////////////Pagination config//////////////////////////////////				
		
		$data['paginator'] = $paginator;
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('cancellation_policy/list', $data);
		$this->load->view('common/footer');		
	}
	
	public function view()
	{
	$data = array();
		$id = $this->uri->segment(3);
		//$where_array = array('user_gear_cancel_policy_id'=>$id);
		//$data['result']= $this->common_model->get_all_record('ks_user_gear_cancel_policy',$where_array);
		$where_array = array('user_gear_cancel_policy_id'=>$id);
			
		
		$sql="SELECT a.user_gear_cancel_policy_id, a.user_gear_cancel_policy,a.user_gear_cancel_price,a.create_user, a.is_active, b.app_user_first_name ,b.app_user_last_name
		       FROM ks_user_gear_cancel_policy a 
		       INNER JOIN ks_users b ON a.create_user = b.app_user_id
			   WHERE a.user_gear_cancel_policy_id=".$id;
		$result = $this->db->query($sql);
		$data['result'] = $result->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('cancellation_policy/view', $data);
		$this->load->view('common/footer');		
	}
	public function edit()
	{
	    $data = array();
		$id = $this->uri->segment(3);
		
		$where_array = array('user_gear_cancel_policy_id'=>$id);
		$sql="SELECT a.user_gear_cancel_policy_id, a.user_gear_cancel_policy,a.user_gear_cancel_price,a.create_user, a.is_active, b.app_user_first_name ,b.app_user_last_name
		       FROM ks_user_gear_cancel_policy a 
		       INNER JOIN ks_users b ON a.create_user = b.app_user_id
			   WHERE a.user_gear_cancel_policy_id=".$id;
		$result = $this->db->query($sql);
		$data['result'] = $result->result();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('cancellation_policy/edit', $data);
		$this->load->view('common/footer');		
	}
	
	public function update()
	{
	$data = array();
    $id = $this->input->post('user_gear_cancel_policy_id');
	$data['user_gear_cancel_policy']= $this->input->post('user_gear_cancel_policy');
	$data['user_gear_cancel_price']= $this->input->post('user_gear_cancel_price');
	$data['is_active']= $this->input->post('is_active');
	$data['update_date']= date('Y-m-d') ;
	$this->db->where('user_gear_cancel_policy_id', $id);
     $this->db->update('ks_user_gear_cancel_policy', $data); 
	$message = '<div class="callout callout-success"><p>Policy updated successfully.</p></div>';
	 $this->session->set_flashdata('success', $message);
	 redirect('cancellation_policy');
	
	}
	

}?>