<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Role_priv extends CI_Controller {

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
                'field' => 'app_role_id',
                'label' => 'role',
                'rules' => 'trim|required'
            ),
			array(
                'field' => 'app_priv_id',
                'label' => 'privilege',
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
				$where .= "b.app_role_name LIKE '%".trim($data['role'])."%' AND ";
			}
			
		$data['privilege']		  = $this->input->get('privilege');
				if($data['privilege'] != ''){
				$where .= "c.privilege_type LIKE '%".trim($data['privilege'])."%' AND ";
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
		 
		$n=$this->model->role_priv($where);
		$sql=$this->model->role_priv($where,$limit,$offset);
		
		
		$result=$sql->result();	
		
		$total_rows=$n->num_rows();	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = base_url()."user_role?username=".$data['role']."";
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


		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('role-priv/list', $data);
		$this->load->view('common/footer');		

	

	}

	

	public function add()
	{
		$data=array();
		$data['roles'] = $this->common_model->get_all_record(M_APP_ROLE,array('active'=>'Y'));	
		$data['privileges'] = $this->common_model->get_all_record(M_APP_PRIVILEGE,array());	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('role-priv/add', $data);
		$this->load->view('common/footer');
	}

	public function save()
	{

	$data=array();

	$this->form_validation->set_rules($this->validation_rules['Add']);

	if($this->form_validation->run())
	{
		$app_role_id= $this->input->post('app_role_id');
		$app_priv_id= $this->input->post('app_priv_id');
	    $priv = $this->common_model->get_all_record(APP_ROLE_PRIV,array('app_role_id'=>$app_role_id,'app_priv_id'=>$app_priv_id));	
		if(count($priv)>0)
		{
			$message = '<div class="callout callout-success">Privilege has been already added.</p></div>';
		
		}
		else
		{

			$data['app_role_id']= $this->input->post('app_role_id');
			$data['app_priv_id']= $this->input->post('app_priv_id');
			$data['created_date'] = date('Y-m-d');
		    $data['created_time'] = date('H:i:s');
			$this->common_model->addRecord(APP_ROLE_PRIV,$data);
			$message = '<div class="callout callout-success">Role Privilege has been successfully added.</p></div>';
			
			
		}
		$this->session->set_flashdata('success', $message);
	    redirect('role_priv');

	 }else{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('role-priv/add', $data);
		$this->load->view('common/footer');	

	  }

	}

	

	public function edit()
	{

	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('app_role_id'=>$id);
		$data['roles'] = $this->common_model->get_all_record(M_APP_ROLE,array('active'=>'Y'));	
		$data['privileges'] = $this->common_model->get_all_record(M_APP_PRIVILEGE,array());
		$role_privilege = $this->model->role_priv(array("role_priv_id"=>$id));
		$data['result'] = $role_privilege->result();
		
	
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('role-priv/edit', $data);
		$this->load->view('common/footer');		

	}

	public function update()
	{

		$data = array();
		$role_priv_id= $this->input->post('role_priv_id');
		
		$app_role_id= $this->input->post('app_role_id');
		$app_priv_id= $this->input->post('app_priv_id');
	    $priv = $this->common_model->get_all_record(APP_ROLE_PRIV,array('app_role_id'=>$app_role_id,'app_priv_id'=>$app_priv_id,'role_priv_id !='=>$role_priv_id));	
		if(count($priv)>0)
		{
			$message = '<div class="callout callout-success">Privilege has been already added.</p></div>';
		
		}
		else
		{

			$data['app_role_id']= $this->input->post('app_role_id');
			$data['app_priv_id']= $this->input->post('app_priv_id');
			$data['updated_date']= date('Y-m-d') ;
		    $data['updated_time']=  date('H:i:s') ;
			$this->db->where('role_priv_id', $role_priv_id);
		    $this->db->update(APP_ROLE_PRIV, $data); 
			$message = '<div class="callout callout-success">Role Privilege has been successfully updated.</p></div>';
			
			
		}
		$this->session->set_flashdata('success', $message);
	    redirect('role_priv');
		
		

	}

	public function view()
	{
		$data = array();
		
		$id = $this->uri->segment(3);
		$where_array = array('app_role_id'=>$id);
		$data['roles'] = $this->common_model->get_all_record(M_APP_ROLE,array('active'=>'Y'));	
		$data['privileges'] = $this->common_model->get_all_record(M_APP_PRIVILEGE,array());
		$role_privilege = $this->model->role_priv(array("role_priv_id"=>$id));
		$data['result'] = $role_privilege->result();	
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('role-priv/view', $data);
		$this->load->view('common/footer');		

	}

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
				$this->db->where('role_priv_id', $id);
		        $this->db->delete(APP_ROLE_PRIV);     
	
			}
	
			$message = '<div class="callout callout-success"><p>Roles have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('role_priv');
	
		 }

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('role_priv_id', $id);
		$this->db->delete(APP_ROLE_PRIV); 
		$message = '<div class="callout callout-success"><p>Role has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('role_priv');

	}
	 
	

}?>