<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_module extends CI_Controller {

	 public function __construct() {

		parent::__construct();

		$this->load->helper(array('url','form','html','text','common_helper'));
		$this->load->library(array('session','form_validation','pagination','email','upload','image_lib'));
		$this->load->model(array('common_model','mail_model','model'));
		if($this->session->userdata('ADMIN_ID') =='') {
          redirect('login');
		 }

	}

		

		protected $validation_rules = array

        (

		'Add' => array(
			array(
                'field' => 'mod_id',
                'label' => 'module',
                'rules' => 'trim|required'
            ),
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
		
		$limit=10;
		$data['role']		  = $this->input->get('role');
				if($data['role'] != ''){
				$where .= "c.app_role_name LIKE '%".trim($data['role'])."%' AND ";
			}
			
		$data['module']		  = $this->input->get('module');
				if($data['module'] != ''){
				$where .= "b.app_module_page LIKE '%".trim($data['module'])."%' AND ";
			}
			
		$where = substr($where,0,(strlen($where)-4));

		
		if($this->input->get("per_page")!= '')
		{
		$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		 
		$n=$this->model->role_module($where);
		$sql=$this->model->role_module($where,$limit,$offset);
		
		
		$result=$sql->result();	
		
		//print_r($result); die;
		
		$total_rows=$n->num_rows();	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = base_url()."role_module?username=".$data['role']."";
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

//////////////////////////////Pagination config//////////////////////////////////				

		//echo $this->db->last_query(); exit();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('role-module/list', $data);
		$this->load->view('common/footer');		

	

	}

	

	public function add()
	{
		$data=array();
		$data['modules'] = $this->common_model->get_all_record(M_APP_MODULE,array('active'=>'Y'));	
		$data['roles'] = $this->common_model->get_all_record(M_APP_ROLE,array());	
		
		//print_r($data['users']); die;
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('role-module/add', $data);
		$this->load->view('common/footer');
	}

	public function save()
	{

	$data=array();
	
	$data['modules'] = $this->common_model->get_all_record(M_APP_MODULE,array('active'=>'Y'));	
	$data['roles'] = $this->common_model->get_all_record(M_APP_ROLE,array());

	$this->form_validation->set_rules($this->validation_rules['Add']);

	if($this->form_validation->run())
	{
	    
		$row['mod_id']= $this->input->post('mod_id');
		$row['app_role_id']= $this->input->post('app_role_id');
		$row['creator'] = $this->session->userdata('ADMIN_ID');
		$row['created_date'] = date('Y-m-d');
		$row['created_time'] = date('H:i:s');
		
		$this->common_model->addRecord(APP_ROLE_MODULE,$row);
		$message = '<div class="callout callout-success">Role Module has been successfully added.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('role_module');
	    

	 }else{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('role-module/add', $data);
		$this->load->view('common/footer');	

	  }

	}

	

	public function edit()
	{

	    $data = array();
		$id = $this->uri->segment(3);

		$where_array = array('role_mod_id'=>$id);
		$data['result'] = $this->common_model->get_all_record(APP_ROLE_MODULE,$where_array);	
		
		$data['modules'] = $this->common_model->get_all_record(M_APP_MODULE,array('active'=>'Y'));	
	    $data['roles'] = $this->common_model->get_all_record(M_APP_ROLE,array());
	
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('role-module/edit', $data);
		$this->load->view('common/footer');		

	}

	public function update()
	{

		$data = array();
		$role_mod_id= $this->input->post('role_mod_id');
		
		$data['mod_id']= $this->input->post('mod_id');
		$data['app_role_id']= $this->input->post('app_role_id');
		$data['modifier'] = $this->session->userdata('ADMIN_ID');
		$data['updated_date']= date('Y-m-d') ;
		$data['updated_time']=  date('H:i:s') ;
		$this->db->where('role_mod_id', $role_mod_id);
		$this->db->update(APP_ROLE_MODULE, $data); 
		$message = '<div class="callout callout-success">Role module has been successfully updated.</p></div>';
		$this->session->set_flashdata('success', $message);
	    redirect('role_module');
		
		

	}

	public function view()
	{
		$data = array();
		$id = $this->uri->segment(3);

		$where_array = array('role_mod_id'=>$id);
		$data['result'] = $this->common_model->get_all_record(APP_ROLE_MODULE,$where_array);	
		
		$data['modules'] = $this->common_model->get_all_record(M_APP_MODULE,array('active'=>'Y'));	
	    $data['roles'] = $this->common_model->get_all_record(M_APP_ROLE,array());
	
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('role-module/view', $data);
		$this->load->view('common/footer');	

	}

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
				$this->db->where('role_mod_id', $id);
		        $this->db->delete(APP_ROLE_MODULE);     
	
			}
	
			$message = '<div class="callout callout-success"><p>Role module have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('role_module');
	
		 }

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('role_mod_id', $id);
		$this->db->delete(APP_ROLE_MODULE); 
		$message = '<div class="callout callout-success"><p>Role module has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('role_module');

	}
	
	
	
	public function role_details(){
		$role_id = $this->input->post('role_id');
		$data['role_id'] = $role_id;
		$this->load->view('role-module/role_details', $data);
	
	
	
	}
	 
	

}?>