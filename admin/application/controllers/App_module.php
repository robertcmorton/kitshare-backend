<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class App_module extends CI_Controller {

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

                'field' => 'app_module_page',

                'label' => 'page',

                'rules' => 'trim|required'

            ),

			array(

                'field'   => 'app_module_desc',

                'label'   => 'description',

                'rules'   => 'trim|required'

            )

        ),

    );

	public function index()

	{

	$data=array();

		$where = " ";

		$limit=10;

	

		$data['app_module_page']				= $this->input->get('app_module_page');

				if($data['app_module_page'] != ''){

				$where .= "app_module_page LIKE '%".trim($data['app_module_page'])."%' AND ";

			}

		$where = substr($where,0,(strlen($where)-4));

		
		
		$where_clause				= '';
		
		
		$total_rows					= $this->model->TotalRecords(M_APP_MODULE,$where);
		$qStr 						= http_build_query($_GET); //$_SERVER['QUERY_STRING']
		$key						= "per_page";
		parse_str($qStr,$ar);
		$qrl 						=  http_build_query(array_diff_key($ar,array($key=>"")));
		$limit 						= 10;
		$config['base_url'] 		= base_url()."app_module?".$qrl;
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $limit;
		$config['page_query_string']= TRUE;
		$config['full_tag_open'] 	= "<ul class='pagination pagination-sm text-center'>";
		$config['full_tag_close'] 	= "</ul>";
		$config['num_tag_open'] 	= '<li>';
		$config['num_tag_close'] 	= '</li>';
		$config['cur_tag_open'] 	= "<li><li class='active'><a href='#'>";
		$config['cur_tag_close'] 	= "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] 	= "<li>";
		$config['next_tagl_close'] 	= "</li>";
		$config['prev_tag_open'] 	= "<li>";
		$config['prev_tagl_close'] 	= "</li>";
		$config['first_tag_open'] 	= "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] 	= "<li>";
		$config['last_tagl_close'] 	= "</li>";
		
		$offset = $this->input->get('per_page');
		
		$this->pagination->initialize($config);
		
		$data['total_rows'] 	= $total_rows;
		
		$data['paginator'] 		= $this->pagination->create_links();
		
		$data['result']		= $this->model->RetriveRecordByWhereLimit(M_APP_MODULE,$where,$limit,$offset,'mod_id','DESC');
		
		

//////////////////////////////Pagination config//////////////////////////////////				


		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('app-module/list', $data);
		$this->load->view('common/footer');		

	

	}

	

	public function add()
	{
		$data=array();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('app-module/add', $data);
		$this->load->view('common/footer');
	}

	public function save()

	{

	

	$data=array();

	$this->form_validation->set_rules($this->validation_rules['Add']);

	if($this->form_validation->run())
	{
		$q = $this->common_model->GetAllWhere(M_APP_MODULE,array("app_module_page"=>$this->input->post('app_module_page')));
		if($q->num_rows()>0){
			$message = '<div class="alert alert-success">Module is already added.</p></div>';
		}else{
			$data['app_module_page']= $this->input->post('app_module_page');
			$data['app_module_desc']= $this->input->post('app_module_desc');
			$data['created_date'] = date('Y-m-d');
			$data['created_time'] = date('H:i:s');
			$this->common_model->addRecord(M_APP_MODULE,$data);
			$message = '<div class="alert alert-success">Module has been successfully added.</p></div>';
		}
		$this->session->set_flashdata('success', $message);

	    redirect('app_module');

	 }else{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('app-module/add', $data);
		$this->load->view('common/footer');	

	  }

	}

	

	public function edit()
	{

	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('mod_id'=>$id);
		$data['result']= $this->common_model->get_all_record(M_APP_MODULE,$where_array);	
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('app-module/edit', $data);
		$this->load->view('common/footer');		

	}

	public function update()
	{

		$data = array();
		$mod_id= $this->input->post('mod_id');
		$q = $this->common_model->GetAllWhere(M_APP_MODULE,array("app_module_page"=>$this->input->post('app_module_page'),"mod_id !="=>$mod_id));
		if($q->num_rows()>0){
			$message = '<div class="alert alert-success">Module is already added.</p></div>';
		}else{
			$data['app_module_page']= $this->input->post('app_module_page');
			$data['app_module_desc']= $this->input->post('app_module_desc');
			$data['active']= $this->input->post('active');
			$data['updated_date']= date('Y-m-d') ;
			$data['updated_time']=  date('H:i:s') ;
			$this->db->where('mod_id', $mod_id);
			$this->db->update(M_APP_MODULE, $data); 
			$message = '<div class="alert alert-success">Module has been successfully updated.</p></div>';
		}
		$this->session->set_flashdata('success', $message);
		redirect('app_module');

	}

	public function view()
	{

		$data = array();
		$id = $this->uri->segment(3);
		$where_array = array('mod_id'=>$id);
		$data['result']= $this->common_model->get_all_record(M_APP_MODULE,$where_array);	
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('app-module/view', $data);
		$this->load->view('common/footer');		

	}

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
			
			    $this->db->where('mod_id', $id);
		        $this->db->delete(M_APP_MODULE); 
		
				    
	
			}
	
			$message = '<div class="alert alert-success"><p>Modules have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('app_module');
	
		 }

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('mod_id', $id);
		$this->db->delete(M_APP_MODULE); 
		$message = '<div class="alert alert-success"><p>Module has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('app_module');

	}

	

}?>