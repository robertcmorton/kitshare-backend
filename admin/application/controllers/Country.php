<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Country extends CI_Controller {

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

                'field' => 'ks_country_name',

                'label' => 'Country',

                'rules' => 'trim|required'

            )

        ),

    );

	public function index()

	{

	$data=array();

		$where = " ";

		

		$data['country']				= $this->input->get('country');

		if($data['country'] != ''){

				$where .= "ks_country_name LIKE '%".trim($data['country'])."%' AND ";

		}

		$where = substr($where,0,(strlen($where)-4));

		
		
		$where_clause				= '';
		
		
		$total_rows					= $this->model->TotalRecords('ks_countries',$where);
		$qStr 						= http_build_query($_GET); //$_SERVER['QUERY_STRING']
		$key						= "per_page";
		parse_str($qStr,$ar);
		$qrl 						=  http_build_query(array_diff_key($ar,array($key=>"")));
		$limit 						= 10;
		$config['base_url'] 		= base_url()."country?".$qrl;
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
		
		$data['result']		= $this->model->RetriveRecordByWhereLimit('ks_countries',$where,$limit,$offset,'ks_country_id','DESC');
		
		//print_r($data['app_users']->result()); exit();

//////////////////////////////Pagination config//////////////////////////////////				


		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('country/list', $data);
		$this->load->view('common/footer');		

	

	}

	

	public function add()
	{
		$data=array();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('country/add', $data);
		$this->load->view('common/footer');
	}

	public function save()
	{


	$data=array();

	$this->form_validation->set_rules($this->validation_rules['Add']);

	if($this->form_validation->run())
	{
		$q = $this->common_model->GetAllWhere("ks_countries",array("ks_country_name"=>$this->input->post('ks_country_name')));
		if($q->num_rows()>0){
			$message = '<div class="alert alert-success">Country is already added.</p></div>';
		}else{
			$data['ks_country_name']= $this->input->post('ks_country_name');
			$data['create_user'] = $this->session->userdata('ADMIN_ID');
			$data['is_active'] = 'Y';
			$data['create_date'] = date('Y-m-d');
			$this->common_model->addRecord('ks_countries',$data);
			$message = '<div class="alert alert-success">Country has been successfully added.</p></div>';
		}
		$this->session->set_flashdata('success', $message);
	    redirect('country');

	 }else{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('country/add', $data);
		$this->load->view('common/footer');	

	  }

	}

	

	public function edit()
	{

	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('ks_country_id'=>$id);
		$data['result']= $this->common_model->get_all_record('ks_countries',$where_array);	
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('country/edit', $data);
		$this->load->view('common/footer');		

	}

	public function update()
	{

		$data = array();
		$ks_country_id= $this->input->post('ks_country_id');
		
		$q = $this->common_model->GetAllWhere("ks_countries",array("ks_country_name"=>$this->input->post('ks_country_name'),"ks_country_id !="=>$ks_country_id));
		if($q->num_rows()>0){
		
			$message = '<div class="alert alert-success">Country is already added.</p></div>';
		
		}else{
			$data['ks_country_name']= $this->input->post('ks_country_name');
			$data['update_user'] = $this->session->userdata('ADMIN_ID');
			$data['is_active'] = $this->input->post('is_active');
			$data['update_date'] = date('Y-m-d');
			$this->db->where('ks_country_id', $ks_country_id);
			$this->db->update('ks_countries', $data); 
			$message = '<div class="alert alert-success">Country has been successfully updated.</p></div>';
		}
		$this->session->set_flashdata('success', $message);
		redirect('country');

	}

	public function view()
	{

	  $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('app_role_id'=>$id);
		$data['result']= $this->common_model->get_all_record(M_APP_ROLE,$where_array);	
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('country/view', $data);
		$this->load->view('common/footer');		

	}

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
				$this->db->where('ks_country_id', $id);
				$this->db->delete('ks_countries');    
	
			}
	
			$message = '<div class="alert alert-success"><p>Countries have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('country');
	
		 }

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('ks_country_id', $id);
		$this->db->delete('ks_countries');
		$message = '<div class="alert alert-success"><p>Country has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('country');

	}
	
	
	public function import()
	{
		 $data = array();
		 if($this->input->post()!='')
			{
			      
				  $filename=$_FILES["file"]["tmp_name"];
				  if($_FILES["file"]["size"] > 0)
				  {
						$file = fopen($filename, "r");
						$count = 0;
						while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE)
						/* print_r($importdata);echo count($importdata); exit();*/
						{
								if($count>0){
								
								   $q = $this->common_model->GetAllWhere("ks_countries",array("ks_country_name"=>trim($importdata[1])));
								   if($q->num_rows()==0){
										
										$data['ks_country_name'] = trim($importdata[1]);
										$data['create_user']     = $this->session->userdata('ADMIN_ID');
										$data['is_active']       = 'Y';
										$data['create_date']     = date('Y-m-d');
										$this->common_model->addRecord('ks_countries',$data);
									
									}
								
								}
						$count++;
				   }                    
				   fclose($file);
				   $message = '<div class="alert alert-success">Data are imported successfully..</p></div>';
				   $this->session->set_flashdata('success', $message);
				   redirect('country');
				   }else{
				   $message = '<div class="alert alert-danger">Something went wrong..</p></div>';
				   $this->session->set_flashdata('success', $message);
				   redirect('country');
				   }
			}
	}
	

	

}?>