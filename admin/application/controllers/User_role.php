<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_role extends CI_Controller {

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
                'field' => 'app_user_id',
                'label' => 'user',
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
		
		if($this->input->get('limit') != ''){
				$data['limit']	= $this->input->get('limit');
		}
		else{
			$data['limit']	= 25;
		}
		$data['role']		  = $this->input->get('role');
				if($data['role'] != ''){
				$where .= "d.app_role_name LIKE '%".trim($data['role'])."%' AND ";
			}
			
		$data['username']		  = $this->input->get('username');
				if($data['username'] != ''){
				$where .= "b.app_user_name LIKE '%".trim($data['username'])."%' AND ";
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
		if($this->input->get('field')!="")
			$data['field_name'] = $this->input->get('field');
		else
			$data['field_name'] = "app_role_id";
		 
		$n=$this->model->user_role($where);
		$sql=$this->model->user_role_order_by($where,$data['limit'],$offset,$order_by,$data['field_name']);
		
		$result=$sql->result();	
		
		//print_r($result); die;
		
		$total_rows=$n->num_rows();
		$data['offset'] = $offset;	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$data['limit'];
		$config['base_url'] = base_url()."user_role?role=".$data['role']."&username=".$data['username']."&order_by=".$order_by."&limit=".$data['limit'];
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $data['limit'];
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
		$this->load->view('user-role/list', $data);
		$this->load->view('common/footer');		

	

	}

	

	public function add()
	{
		$data=array();
		$data['users'] = $this->common_model->get_all_record(M_APP_USER,array('active'=>'Y'));	
		$data['roles'] = $this->common_model->get_all_record(M_APP_ROLE,array());	
		
		//print_r($data['users']); die;
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('user-role/add', $data);
		$this->load->view('common/footer');
	}

	public function save()
	{

	$data=array();

	$this->form_validation->set_rules($this->validation_rules['Add']);

	if($this->form_validation->run())
	{
		$app_user_id= $this->input->post('app_user_id');
		$app_role_id= $this->input->post('app_role_id');
	    
		$data['app_user_id']= $app_user_id;
		$data['app_role_id']= $app_role_id;
		$data['created_date'] = date('Y-m-d');
		$data['created_time'] = date('H:i:s');
		$data['active'] = 'Y';
		
		
		$this->common_model->addRecord(APP_USER_ROLES,$data);
		//echo $this->db->last_query(); exit();
		$message = '<div class="alert alert-success">User Role has been successfully added.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('user_role');
	    

	 }else{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('user-role/add', $data);
		$this->load->view('common/footer');	

	  }

	}

	

	public function edit()
	{

	    $data = array();
		$id = $this->uri->segment(3);

		$where_array = array('user_role_id'=>$id);
		$data['result'] = $this->common_model->get_all_record(APP_USER_ROLES,$where_array);	
		
		$data['users'] = $this->common_model->get_all_record(M_APP_USER,array('active'=>'Y'));	
		$data['roles'] = $this->common_model->get_all_record(M_APP_ROLE,array());
	
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('user-role/edit', $data);
		$this->load->view('common/footer');		

	}

	public function update()
	{

		$data = array();
		$user_role_id= $this->input->post('user_role_id');
		
		$app_user_id= $this->input->post('app_user_id');
		$app_role_id= $this->input->post('app_role_id');
		
		$data['app_user_id']= $app_user_id;
		$data['app_role_id']= $app_role_id;
		$data['active'] = $this->input->post('active');
		$data['updated_date']= date('Y-m-d') ;
		$data['updated_time']=  date('H:i:s') ;
		$this->db->where('user_role_id', $user_role_id);
		$this->db->update(APP_USER_ROLES, $data); 
		$message = '<div class="alert alert-success">User role has been successfully updated.</p></div>';
		$this->session->set_flashdata('success', $message);
	    redirect('user_role');
		
		

	}

	public function view()
	{
		$data = array();
		
		$data = array();
		$id = $this->uri->segment(3);

		$where_array = array('user_role_id'=>$id);
		$data['result'] = $this->common_model->get_all_record(APP_USER_ROLES,$where_array);	
		
		$data['users'] = $this->common_model->get_all_record(M_APP_USER,array('active'=>'Y'));	
		$data['roles'] = $this->common_model->get_all_record(M_APP_ROLE,array());
	
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('user-role/view', $data);
		$this->load->view('common/footer');		

	}

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
				$this->db->where('user_role_id', $id);
		        $this->db->delete(APP_USER_ROLES);     
	
			}
	
			$message = '<div class="alert alert-success"><p>User roles have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('user_role');
	
		 }

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('user_role_id', $id);
		$this->db->delete(APP_USER_ROLES);  
		$message = '<div class="alert alert-success"><p>User role has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('user_role');

	}
	
	
	
	public function role_details(){
		$role_id = $this->input->post('role_id');
		$data['role_id'] = $role_id;
		$this->load->view('user-role/role_details', $data);
	
	
	
	}
	 
	

}?>