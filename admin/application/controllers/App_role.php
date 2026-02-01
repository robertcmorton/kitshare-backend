<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class App_role extends CI_Controller {

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

                'field' => 'app_role_name',

                'label' => 'role',

                'rules' => 'trim|required'

            ),

			array(

                'field'   => 'app_role_desc',

                'label'   => 'description',

                'rules'   => 'trim|required'

            )

        ),

    );

	public function index()

	{

	$data=array();
		$where = " ";
		
		
		if($this->input->get('limit') != ''){
				$data['limit']	= $this->input->get('limit');
		}
		else{
			$data['limit']	= 25;
		}
		
		$data['role']				= $this->input->get('role');
		if($data['role'] != ''){
				$where .=  " where app_role_name LIKE '%".trim($data['role'])."%'";
			}
			
			
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
		$data['offset'] = $offset;
		$nSer="SELECT * FROM m_app_role ".$where." ORDER BY app_role_name ".$order_by;
		$sql=$nSer." LIMIT ".$data['limit']." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$data['limit'];
		$config['base_url'] = base_url()."app_role/?role=".$data['role']."&order_by=".$order_by."&limit=".$data['limit'];
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
		
//////////////////////////////Pagination config//////////////////////////////////				
		
		$data['paginator'] = $paginator;
		
		
		
//////////////////////////////Pagination config//////////////////////////////////				


		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('app-role/list', $data);
		$this->load->view('common/footer');		

	

	}

	

	public function add()
	{
		$data=array();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('app-role/add', $data);
		$this->load->view('common/footer');
	}

	public function save()

	{

	

	$data=array();

	$this->form_validation->set_rules($this->validation_rules['Add']);

	if($this->form_validation->run())
	{

		$data['app_role_name']= $this->input->post('app_role_name');
		$data['app_role_desc']= $this->input->post('app_role_desc');
		$data['creator'] = $this->session->userdata('ADMIN_ID');
		$data['created_date'] = date('Y-m-d');
		$data['created_time'] = date('H:i:s');
		$this->common_model->addRecord(M_APP_ROLE,$data);
		$message = '<div class="alert alert-success">Role has been successfully added.</p></div>';
		$this->session->set_flashdata('success', $message);

	    redirect('app_role');

	 }else{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('app-role/add', $data);
		$this->load->view('common/footer');	

	  }

	}

	

	public function edit()
	{

	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('app_role_id'=>$id);
		$data['result']= $this->common_model->get_all_record(M_APP_ROLE,$where_array);	
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('app-role/edit', $data);
		$this->load->view('common/footer');		

	}

	public function update()
	{

		$data = array();
		$app_role_id= $this->input->post('app_role_id');
		$data['app_role_name']= $this->input->post('app_role_name');
		$data['app_role_desc']= $this->input->post('app_role_desc');
		$data['active']= $this->input->post('active');
		$data['modifier'] = $this->session->userdata('ADMIN_ID');
		$data['updated_date']= date('Y-m-d') ;
		$data['updated_time']=  date('H:i:s') ;
		$this->db->where('app_role_id', $app_role_id);
		$this->db->update(M_APP_ROLE, $data); 
		$message = '<div class="alert alert-success">Role has been successfully updated.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('app_role');

	}

	public function view()
	{

	  $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('app_role_id'=>$id);
		$data['result']= $this->common_model->get_all_record(M_APP_ROLE,$where_array);	
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('app-role/view', $data);
		$this->load->view('common/footer');		

	}

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
				$this->db->where('app_role_id', $id);
				$this->db->delete(M_APP_ROLE);    
	
			}
	
			$message = '<div class="alert alert-success"><p>Roles have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('app_role');
	
		 }

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('app_role_id', $id);
		$this->db->delete(M_APP_ROLE);  
		$message = '<div class="alert alert-success"><p>Role has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('app_role');

	}

	

}?>