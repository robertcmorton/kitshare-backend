<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class App_users_certificate extends CI_Controller {

	 public function __construct() {

		parent::__construct();

		$this->load->helper(array('url','form','html','text','common_helper'));

		$this->load->library(array('session','form_validation','pagination','email','upload'));

		$this->load->model(array('common_model','mail_model','model'));

		if($this->session->userdata('ADMIN_ID') =='') {

          redirect('login');

		  }

	}

	

	



	public function index()

	{



	    $data=array();

		

		$where =' ';

		

		$limit=10;

		



		

		

			

		

			

		

		

		if($this->input->get("per_page")!= '')

		{

			$offset = $this->input->get("per_page");

		}

		else

		{

			$offset=0;

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

		$nSer="SELECT e.* ,c.app_username,c.app_user_first_name,c.app_user_last_name FROM ks_user_insurance_proof e 

		

		INNER JOIN ks_users c ON e.app_user_id = c.app_user_id

		".$where." ORDER BY e.user_insurance_proof_id";

		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";

		$result=$this->db->query($sql);

		$result=		$this->db->query($nSer)->result();

		$total_rows=count($this->db->query($nSer)->result());	

		$data['result'] = $result;

		$data['total_rows']=$total_rows;

		$data['limit']=$limit;

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





		//echo $this->db->last_query(); exit();

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('app_users/certificate-list', $data);

		$this->load->view('common/footer');		



	



	}

	public function delete_record($value='')

	{



		error_reporting(0);



		$id=$this->uri->segment(3);



		$where_array = array('user_insurance_proof_id'=>$id);



		$img= $this->common_model->get_all_record('ks_user_insurance_proof',$where_array);



		foreach($img as $im){



			$this->load->helper("file");



			 $oldfile = PROFILE_IMAGE."/".$im->image_url ; 
			// die


			unlink($oldfile);



		}



		$this->db->where('user_insurance_proof_id', $id);



		$this->db->delete('ks_user_insurance_proof');    



		$message = '<div class="alert alert-success"><p>User Insurance has been deleted successfully.</p></div>';



		$this->session->set_flashdata('success', $message);



		redirect('App_users_certificate');







	}



	public function select_delete()



	{


		error_reporting(0);
		if(isset($_POST['bulk_delete_submit']))



		{

			$idArr = $this->input->post('checked_id');

			foreach($idArr as $id){

				$where_array = array('user_insurance_proof_id'=>$id);

				$img= $this->common_model->get_all_record('ks_user_insurance_proof',$where_array);

				foreach($img as $im){

					$this->load->helper("file");
					 $im->image_url ;
					 $oldfile = $im->image_url ; 
					
					unlink($oldfile);

				}
				


				$this->db->where('user_insurance_proof_id', $id);



				$this->db->delete('ks_user_insurance_proof');  

			}

			$message = '<div class="alert alert-success"><p>Users certificate  have been deleted successfully.</p></div>';



			$this->session->set_flashdata('success', $message);



			redirect('App_users_certificate');



		 }

	}

	public function edit($id='')

	{

		$where_array = array('user_insurance_proof_id'=>$id);



		$data['app_users']= $this->common_model->get_all_record('ks_user_insurance_proof',$where_array);

		

		$this->load->view('common/header');	



		$this->load->view('common/left-menu');					



		$this->load->view('app_users/edit-user-certificate', $data);



		$this->load->view('common/footer');	



	}

	private function set_upload_options()
	{   
    //upload an image options
	    $config = array();
	    $config['upload_path'] = UPLOAD_IMAGES .'/site_upload/insurance_img/';
	    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;

    	return $config;
	}

	public function update($value='')

	{

		$update_data = $this->input->post();
		if (!empty($_FILES['image']['name']) ) {
					$this->load->library('upload');
				    $dataInfo = array();
				    $files = $_FILES;
				    $cpt = count($_FILES['image']['name']);
				        $_FILES['image']['name']= $files['image']['name'];
				        $_FILES['image']['type']= $files['image']['type'];
				        $_FILES['image']['tmp_name']= $files['image']['tmp_name'];
				        $_FILES['image']['error']= $files['image']['error'];
				        $_FILES['image']['size']= $files['image']['size'];    
				        $this->upload->initialize($this->set_upload_options());
				        $this->upload->do_upload('image');
				        $dataInfo = $this->upload->data();
				       $errors = $this->upload->display_errors();
				       print_r($errors);
				  	$image = ROOT_PATH.'site_upload/insurance_img/'.$dataInfo['file_name'];
					$update_data['image_url	'] = $image;			
			}else{
				
			}

		$update_data['ks_user_certificate_currency_exp'] = date('Y-m-d',strtotime($update_data['ks_user_certificate_currency_exp']));
		$update_data['ks_user_certificate_currency_start'] = date('Y-m-d',strtotime($update_data['ks_user_certificate_currency_start']));

		$this->db->where('user_insurance_proof_id',$update_data['user_insurance_proof_id']);

		$this->db->update('ks_user_insurance_proof',$update_data);
		
		$message = '<div class="alert alert-success">User Insurance has been successfully Updated.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('App_users_certificate');

	}

	

	

	

	

}?>

