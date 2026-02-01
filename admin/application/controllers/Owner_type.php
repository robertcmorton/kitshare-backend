<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');







class Owner_type extends CI_Controller {



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



                'field' => 'owner_type_name',



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

		}

		else{

			$data['limit']	= 25;

		}

		

		$data['owner_type_name']				= $this->input->get('owner_type_name');



				if($data['owner_type_name'] != ''){



				$where .= "owner_type_name LIKE '%".trim($data['owner_type_name'])."%' AND ";



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

		$nSer="SELECT * FROM ks_owner_types ".$where." ORDER BY owner_type_name ".$order_by;

		$sql=$nSer." LIMIT ".$data['limit']." OFFSET  ".$offset." ";

		$result=$this->db->query($sql);		

		$total_rows=count($this->db->query($nSer)->result());	

		$data['result'] = $result;

		$data['total_rows']=$total_rows;

		$data['limit']=$data['limit'];

		

		$config['base_url'] = base_url()."owner_type/?owner_type_name=".$data['owner_type_name']."&order_by=".$order_by."&limit=".$data['limit'];

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

		

		//print_r($data['app_users']->result()); exit();



//////////////////////////////Pagination config//////////////////////////////////				





		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('owner_type/list', $data);

		$this->load->view('common/footer');		



	



	}



	



	public function add()

	{

		$data=array();

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('owner_type/add', $data);

		$this->load->view('common/footer');

	}



	public function save()

	{

		$data=array();

	

		$this->form_validation->set_rules($this->validation_rules['Add']);

	

		if($this->form_validation->run())

		{

		    $q = $this->common_model->GetAllWhere("ks_owner_types",array("owner_type_name"=>$this->input->post('owner_type_name')));

		

			if($q->num_rows()>0){

			

				$message = '<div class="alert alert-success">Owner Type is already added.</p></div>';

			

			}

			else{



				$data['owner_type_name']= $this->input->post('owner_type_name');

				$data['is_active'] = 'Y';

				$data['create_user'] = $this->session->userdata('ADMIN_ID');

				$data['create_date'] = date('Y-m-d');

				$this->common_model->addRecord('ks_owner_types',$data);

				$message = '<div class="alert alert-success">Owner Type has been successfully added.</p></div>';

			}

			$this->session->set_flashdata('success', $message);

			redirect('owner_type');

	

		 }else{

		 

			$this->load->view('common/header');	

			$this->load->view('common/left-menu');					

			$this->load->view('owner_type/add', $data);

			$this->load->view('common/footer');	

	

		  }



	}



	



	public function edit()

	{



	    $data = array();

		$id = $this->uri->segment(3);

		$where_array = array('owner_type_id'=>$id);

		$data['result']= $this->common_model->get_all_record('ks_owner_types',$where_array);	

	

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');					

		$this->load->view('owner_type/edit', $data);

		$this->load->view('common/footer');		



	}



	public function update()

	{



		$data = array();

		

		$owner_type_id= $this->input->post('owner_type_id');

		$where_array = array('owner_type_id'=>$owner_type_id);

		$data['result']= $this->common_model->get_all_record('ks_owner_types',$where_array);

		

		$this->form_validation->set_rules($this->validation_rules['Add']);

		if($this->form_validation->run()){

		

		$q = $this->common_model->GetAllWhere("ks_owner_types",array("owner_type_name"=>$this->input->post('owner_type_name'),"owner_type_id !="=>$owner_type_id));

		if($q->num_rows()>0){

		

			$message = '<div class="alert alert-success">Owner Type is already added.</p></div>';

		

		}else{

			

			$row['owner_type_name']= $this->input->post('owner_type_name');

			$row['is_active'] = $this->input->post('is_active');

			$row['update_date'] = date('Y-m-d');

			$this->db->where('owner_type_id', $owner_type_id);

			$this->db->update('ks_owner_types', $row); 

			$message = '<div class="alert alert-success">Owner Type has been successfully updated.</p></div>';

			

		}

			$this->session->set_flashdata('success', $message);

			redirect('owner_type');

			

		}else{

		

			$this->load->view('common/header');	

			$this->load->view('common/left-menu');					

			$this->load->view('owner_type/edit', $data);

			$this->load->view('common/footer');

		

		}

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

				$this->db->where('owner_type_id', $id);

				$this->db->delete('ks_owner_types');    

	

			}

	

			$message = '<div class="alert alert-success"><p>Owner Type have been deleted successfully.</p></div>';

			$this->session->set_flashdata('success', $message);

			redirect('owner_type');

	

		 }



	}



	public function delete_record()

	{



		$id=$this->uri->segment(3);

		$this->db->where('owner_type_id', $id);

		$this->db->delete('ks_owner_types'); 

		$message = '<div class="alert alert-success"><p>Owner Type has been deleted successfully.</p></div>';

		$this->session->set_flashdata('success', $message);

		redirect('owner_type');



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

								

								   $q = $this->common_model->GetAllWhere("ks_owner_types",array("owner_type_name"=>trim($importdata[1])));

								   if($q->num_rows()==0){

										

										$data['owner_type_name'] = trim($importdata[1]);

										$data['create_user']     = $this->session->userdata('ADMIN_ID');

										$data['is_active']       = 'Y';

										$data['create_date']     = date('Y-m-d');

										$this->common_model->addRecord('ks_owner_types',$data);

									

									}

								

								}

						$count++;

				   }                    

				   fclose($file);

				   $message = '<div class="alert alert-success">Data are imported successfully..</p></div>';

				   $this->session->set_flashdata('success', $message);

				   redirect('owner_type');

				   }else{

				   $message = '<div class="alert alert-danger">Something went wrong..</p></div>';

				   $this->session->set_flashdata('success', $message);

				   redirect('owner_type');

				   }

			}

	}

	

	public function downloadallcsv(){

	$sql = "SELECT * FROM ks_owner_types  ORDER BY owner_type_name DESC ";

	$query = $this->db->query($sql);

		

	

		$fp = fopen('php://output', 'w');

						

		if ($fp && $query) {

			header('Content-Type: text/csv');

			header('Content-Disposition: attachment; filename="exportall.csv"');

			header('Pragma: no-cache');

			header('Expires: 0');

			$i=0;

			foreach($query->result('array') as $row){

				if($i==0)

					fputcsv($fp, array_keys($row));

			

				fputcsv($fp, array_values($row));

				$i++;

			}

			fclose($fp);

			

			die;



		}



 	}



}?>