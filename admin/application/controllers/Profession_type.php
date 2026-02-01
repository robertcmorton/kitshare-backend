<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Profession_type extends CI_Controller {

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

                'field' => 'profession_name',

                'label' => 'role',

                'rules' => 'trim|required'

            )

        ),

    );

	public function index()

	{

	$data=array();

		$where = " ";
		if($this->input->get('limit') != ''){
				$data['limit']	= $this->input->get('limit');
		}else{
				$data['limit']	= 25;
		}

		if($this->input->get("per_page")!= ''){
			$offset = $this->input->get("per_page");
		}else{
			$offset=0;
		}
		$data['offset'] = $offset;
		$data['order_by']= $this->input->get('order_by');
		if($data['order_by'] != ''){
				$order_by = $data['order_by'];
		}else{
			$order_by = 'ASC';
		}
		$data['profession_name']				= $this->input->get('profession_name');

				if($data['profession_name'] != ''){

				$where .= "profession_name LIKE '%".trim($data['profession_name'])."%' AND ";

			}

		$where = substr($where,0,(strlen($where)-4));

		
		
		$where_clause				= '';
		
		
		$total_rows					= $this->model->TotalRecords('ks_profession_types',$where);
		$qStr 						= http_build_query($_GET); //$_SERVER['QUERY_STRING']
		$key						= "per_page";
		parse_str($qStr,$ar);
		$qrl 						=  http_build_query(array_diff_key($ar,array($key=>"")));
		$limit 						= 10;
		$config['base_url'] 		= base_url()."profession_type?".$qrl;
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $data['limit'];
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
		
		$data['result']		= $this->model->RetriveRecordByWhereLimit('ks_profession_types',$where,$limit,$offset,'profession_type_id',$order_by);
		
		//print_r($data['app_users']->result()); exit();

//////////////////////////////Pagination config//////////////////////////////////				


		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('profession_type/list', $data);
		$this->load->view('common/footer');		

	

	}

	

	public function add()
	{
		$data=array();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('profession_type/add', $data);
		$this->load->view('common/footer');
	}

	public function save()
	{
		$data=array();
	
		$this->form_validation->set_rules($this->validation_rules['Add']);
	
		if($this->form_validation->run())
		{
		    $q = $this->common_model->GetAllWhere("ks_profession_types",array("profession_name"=>$this->input->post('profession_name')));
		
			if($q->num_rows()>0){
			
				$message = '<div class="alert alert-success">Profession Type is already added.</p></div>';
			
			}
			else{

				$data['profession_name']= $this->input->post('profession_name');
				$data['profession_desc']= $this->input->post('profession_desc');
				$data['is_active'] = 'Y';
				$data['create_user'] = $this->session->userdata('ADMIN_ID');
				$data['create_date'] = date('Y-m-d');
				$this->common_model->addRecord('ks_profession_types',$data);
				$message = '<div class="alert alert-success">Profession Type has been successfully added.</p></div>';
			}
			$this->session->set_flashdata('success', $message);
			redirect('profession_type');
	
		 }else{
		 
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');					
			$this->load->view('profession_type/add', $data);
			$this->load->view('common/footer');	
	
		  }

	}

	

	public function edit()
	{

	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('profession_type_id'=>$id);
		$data['result']= $this->common_model->get_all_record('ks_profession_types',$where_array);	
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('profession_type/edit', $data);
		$this->load->view('common/footer');		

	}

	public function update()
	{

		$data = array();
		
		$profession_type_id= $this->input->post('profession_type_id');
		$where_array = array('profession_type_id'=>$profession_type_id);
		$data['result']= $this->common_model->get_all_record('ks_profession_types',$where_array);
		
		$this->form_validation->set_rules($this->validation_rules['Add']);
		if($this->form_validation->run()){
		
		$q = $this->common_model->GetAllWhere("ks_profession_types",array("profession_name"=>$this->input->post('profession_name'),"profession_type_id !="=>$profession_type_id));
		if($q->num_rows()>0){
		
			$message = '<div class="alert alert-success">Profession Type is already added.</p></div>';
		
		}else{
			
			$row['profession_name']= $this->input->post('profession_name');
			$row['profession_desc']= $this->input->post('profession_desc');
			$row['is_active'] = $this->input->post('is_active');
			$row['is_approved'] = $this->input->post('is_approved');
			//$row['approved_by_user_id'] = $this->session->userdata('ADMIN_ID');
			$row['approved_on'] = date('Y-m-d H:i:s');
			$row['update_date'] = date('Y-m-d');
			$this->db->where('profession_type_id', $profession_type_id);
			$this->db->update('ks_profession_types', $row); 
			$message = '<div class="alert alert-success">Profession Type has been successfully updated.</p></div>';
			
		}
			$this->session->set_flashdata('success', $message);
			redirect('profession_type');
			
		}else{
		
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');					
			$this->load->view('profession_type/edit', $data);
			$this->load->view('common/footer');
		
		}
	}

	

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
				$this->db->where('profession_type_id', $id);
				$this->db->delete('ks_profession_types');    
	
			}
	
			$message = '<div class="alert alert-success"><p>Profession Type have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('profession_type');
	
		 }

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('profession_type_id', $id);
		$this->db->delete('ks_profession_types'); 
		$message = '<div class="alert alert-success"><p>Profession Type has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('profession_type');

	}

	

}?>