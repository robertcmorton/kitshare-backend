<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_privilege extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','pagination','email'));
		$this->load->model(array('common_model','mail_model','model'));
		if($this->session->userdata('ADMIN_ID') =='') {
          redirect('login');
		  }
		  error_reporting(0);
	}

	protected $validation_rules = array
        (
		'Add' => array(
			array(
                'field' => 'app_role_id',
                'label' => 'role',
                'rules' => 'trim|required'
            )
        ),

    );

	public function index()
	{

	    $data=array();
		
		$where ='';
		if($this->input->get('limit') != ''){
				$data['limit']	= $this->input->get('limit');
		}
		else{
			$data['limit']	= 25;
		}
		
		$limit=10;
		$data['role']		  = $this->input->get('role');
			if($data['role'] != ''){
			$where .= "b.app_role_name LIKE '%".trim($data['role'])."%' AND ";
		}
			
		$data['privilege']		  = $this->input->get('privilege');
			if($data['privilege'] != ''){
			$where .= "c.privilege_type LIKE '%".trim($data['privilege'])."%' AND ";
		}
			
		$where = substr($where,0,(strlen($where)-4));
        $data['order_by']				= $this->input->get('order_by');
		if($data['order_by'] != ''){
				$order_by = $data['order_by'];
		}
		else{
			$order_by = 'ASC';
		}
			
		
		if($this->input->get("per_page")!= '')
		{
			$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		 
		$n=$this->model->privileges_group_by($where);
		$sql=$this->model->privileges_group_by($where,$limit,$offset);
		
		 //echo $this->db->last_query();
		 //die ();
		$result=$sql->result();	
		
		$total_rows=$n->num_rows();	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = base_url()."menu_privilege/?app_role_name=".$data['role']."&order_by=".$order_by."&limit=".$data['limit'];
		//$config['base_url'] = base_url()."menu_privilege?app_role_name=".$data['role']."";
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
		$data['paginator'] = $paginator; 
		
		
		//print_r($result);

//////////////////////////////Pagination config//////////////////////////////////				


		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('menu-privilege/list', $data);
		$this->load->view('common/footer');		

	

	}

	public function add()
	{
	
	$data=array();
	
	$ap = $this->common_model->GetAllWhere("m_app_privilege",array());
	$ar = $this->common_model->GetAllWhere("m_app_role",array("active"=>'Y'));
	
	$data['privilege'] = $ap->result();
	$data['role'] =  $ar->result();
	
	
	$this->load->view('common/header');	
	$this->load->view('common/left-menu');	
	$this->load->view('menu-privilege/add', $data);
	$this->load->view('common/footer');	

	}

	public function save()
	{
		$data=array();
		
		$ap = $this->common_model->GetAllWhere("m_app_privilege",array());
		$ar = $this->common_model->GetAllWhere("m_app_role",array("active"=>'Y'));
		
		$data['privilege'] = $ap->result();
		$data['role'] =  $ar->result();
	
		$this->form_validation->set_rules($this->validation_rules['Add']);
		if($this->form_validation->run())
		{
		
		 
		    $privilege_type = $this->input->post('privilege_type');
			
			foreach($privilege_type as $key=>$val){
			
			   $role = $this->common_model->GetAllWhere(APP_ROLE_PRIV,array("app_role_id"=>$this->input->post('app_role_id'),"app_priv_id"=>$val));
			   //echo $this->db->last_query();
			   if($role->num_rows()>0){
			   
					
				}
				else{
				
					$row['app_role_id']= $this->input->post('app_role_id');
					$row['app_priv_id']= $val;
					$row['created_date']= date('Y-m-d') ;
					$row['created_time']=  date('H:i:s') ;
					
					$this->common_model->addRecord(APP_ROLE_PRIV,$row);
					
				
				}
				
				//exit();
			}
			//echo $this->db->last_query();
					//exit ();
			
			$message = '<div class="alert alert-success">Role Privilege has been successfully added.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('menu_privilege');
			
		}else{
		
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');					
			$this->load->view('menu-privilege/add', $data);
			$this->load->view('common/footer');	
		}

	}
	
	public function view()
	{
	    $data = array();
		
		$data = array();
		$id = $this->uri->segment(3);
		$where_array = array('app_role_id'=>$id);
		
		$role_privilege = $this->common_model->GetAllWhere(APP_ROLE_PRIV,$where_array);
		$data['result'] = $role_privilege->result();
		
		$ap = $this->common_model->GetAllWhere("m_app_privilege",array());
		$ar = $this->common_model->GetAllWhere("m_app_role",array("active"=>'Y'));
		
		$data['privilege'] = $ap->result();
		$data['role'] =  $ar->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('menu-privilege/view', $data);
		$this->load->view('common/footer');		
	}

	public function edit()
	{
	    $data = array();
		
		$data = array();
		$id = $this->uri->segment(3);
		$where_array = array('app_role_id'=>$id);
		
		$role_privilege = $this->common_model->GetPrivID(APP_ROLE_PRIV,$where_array);
		$data['result'] = $role_privilege->result();
		
		$ap = $this->common_model->GetAllWhere("m_app_privilege",array());
		$ar = $this->common_model->GetAllWhere("m_app_role",array("active"=>'Y'));
		
		$data['privilege'] = $ap->result();
		$data['role'] =  $ar->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('menu-privilege/edit', $data);
		$this->load->view('common/footer');		
	}

	public function update()
	{
		$data = array();
		$app_role_id = $this->input->post('app_role_id');
		
		$ap = $this->common_model->GetAllWhere("m_app_privilege",array());
		$ar = $this->common_model->GetAllWhere("m_app_role",array("active"=>'Y'));
		
		$data['privilege'] = $ap->result();
		$data['role'] =  $ar->result();
		
		$this->db->where('app_role_id', $app_role_id);
        $this->db->delete(APP_ROLE_PRIV);
		
			
		$this->form_validation->set_rules($this->validation_rules['Add']);
		if($this->form_validation->run())
		{
			
			
			$privilege_type = $this->input->post('privilege_type');
			
			foreach($privilege_type as $key=>$val){
			
				$row['app_role_id']= $this->input->post('app_role_id');
				$row['app_priv_id']= $val;
				$row['created_date']= date('Y-m-d') ;
				$row['created_time']=  date('H:i:s') ;
				
				$this->common_model->addRecord(APP_ROLE_PRIV,$row);
			}
			
			
			$message = '<div class="alert alert-success"><p>Role Privilege has been modified successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('menu_privilege');
			
		}
		
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');					
			$this->load->view('menu-privilege/edit', $data);
			$this->load->view('common/footer');	
		
	}

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
			
				$this->db->where('app_role_id', $id);
		        $this->db->delete(APP_ROLE_PRIV); 
				  
			}
	
			$message = '<div class="alert alert-success"><p>Role Privileges have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('menu_privilege');
	
		 }

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('app_role_id', $id);
		$this->db->delete(APP_ROLE_PRIV); 
		$message = '<div class="alert alert-success"><p>Role Privilege has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('menu_privilege');

	}

	

}?>