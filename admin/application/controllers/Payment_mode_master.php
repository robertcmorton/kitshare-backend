<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Payment_mode_master extends CI_Controller {

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

                'field' => 'payment_mode_name',

                'label' => 'Payment Mode Name',

                'rules' => 'trim|required'

            )

        ),

    );

	public function index()

	{

	$data=array();

		$where = " ";

		

		$data['payment_mode_name']				= $this->input->get('payment_mode_name');

				if($data['payment_mode_name'] != ''){

				$where .= "payment_mode_name LIKE '%".trim($data['payment_mode_name'])."%' AND ";

			}

		$where = substr($where,0,(strlen($where)-4));

		
		
		$where_clause				= '';
		
		
		$total_rows					= $this->model->TotalRecords('ks_payment_mode_master',$where);
		$qStr 						= http_build_query($_GET); //$_SERVER['QUERY_STRING']
		$key						= "per_page";
		parse_str($qStr,$ar);
		$qrl 						=  http_build_query(array_diff_key($ar,array($key=>"")));
		$limit 						= 10;
		$config['base_url'] 		= base_url()."payment_mode_master?".$qrl;
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
		
		$data['result']		= $this->model->RetriveRecordByWhereLimit('ks_payment_mode_master',$where,$limit,$offset,'payment_mode_master_id','DESC');
		
		//print_r($data['app_users']->result()); exit();

//////////////////////////////Pagination config//////////////////////////////////				


		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('payment_mode_master/list', $data);
		$this->load->view('common/footer');		

	

	}

	

	public function add()
	{
		$data=array();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('payment_mode_master/add', $data);
		$this->load->view('common/footer');
	}

	public function save()

	{

	

	$data=array();

	$this->form_validation->set_rules($this->validation_rules['Add']);

	if($this->form_validation->run())
	{

		$data['payment_mode_name']= $this->input->post('payment_mode_name');
		$data['payment_mode_abbr']= $this->input->post('payment_mode_abbr');
		$data['create_user'] = $this->session->userdata('ADMIN_ID');
		$data['create_date'] = date('Y-m-d');
		$this->common_model->addRecord('ks_payment_mode_master',$data);
		$message = '<div class="callout callout-success">Payment Mode has been successfully added.</p></div>';
		$this->session->set_flashdata('success', $message);
	    redirect('payment_mode_master');

	 }else{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('payment_mode_master/add', $data);
		$this->load->view('common/footer');	

	  }

	}

	

	public function edit()
	{

	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('payment_mode_master_id'=>$id);
		$data['result']= $this->common_model->get_all_record('ks_payment_mode_master',$where_array);	
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('payment_mode_master/edit', $data);
		$this->load->view('common/footer');		

	}

	public function update()
	{

		$data = array();
		
		$this->form_validation->set_rules($this->validation_rules['Add']);
		if($this->form_validation->run()){
		
			$payment_mode_master_id= $this->input->post('payment_mode_master_id');
			
			$data['payment_mode_name']= $this->input->post('payment_mode_name');
			$data['payment_mode_abbr']= $this->input->post('payment_mode_abbr');
			$data['update_date'] = date('Y-m-d');
			$this->db->where('payment_mode_master_id', $payment_mode_master_id);
			$this->db->update('ks_payment_mode_master', $data); 
			$message = '<div class="callout callout-success">Payment Mode has been successfully updated.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('payment_mode_master');
			
		}else{
		
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');					
			$this->load->view('payment_mode_master/edit', $data);
			$this->load->view('common/footer');
		
		}
	}

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
				$this->db->where('payment_mode_master_id', $id);
				$this->db->delete('ks_payment_mode_master');    
	
			}
	
			$message = '<div class="callout callout-success"><p>Payment Modes have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('payment_mode_master');
	
		 }

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('payment_mode_master_id', $id);
		$this->db->delete('ks_payment_mode_master'); 
		$message = '<div class="callout callout-success"><p>Payment Modes has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('payment_mode_master');

	}

	

}?>