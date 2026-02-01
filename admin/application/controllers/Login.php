<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Login extends CI_Controller {

	 public function __construct() {

		parent::__construct();

		$this->load->helper(array('url','form','html','text'));

		$this->load->library(array('session','form_validation'));

		$this->load->model(array('adminlogin','common_model'));

	}

	

	protected $validation_rules = array

        (

		'Login' => array(

			array(

                'field' => 'username',

                'label' => 'Username',

                'rules' => 'trim|required'

            ),

			array(

                'field'   => 'admin_password',

                'label'   => 'password',

                'rules'   => 'trim|required'

            )

        ),

    );

	

	public function index()

	{

		$data = array();
		

		$this->load->view('login', $data);

	}

	public function admin_login()

	{

	   

		 $data=array();

		 

		 $this->form_validation->set_rules($this->validation_rules['Login']);

		 if($this->form_validation->run())

		 {

		 

		 $user_name = $this->input->post('username');

		 $user_password = $this->input->post('admin_password');

		 $user_password = $this->common_model->base64En(2,$user_password);

			

			$query = $this->adminlogin->loginCheck($user_name,$user_password); 

			

			 $this->db->last_query(); 

			$row = $query->row(); 
			print_r($query->num_rows());

			if ($query->num_rows() > 0) 

			{ 

				$row = $query->row(); 

								

				        $this->session->set_userdata('ADMIN_EMAIL_ID', $row->app_user_email); 

						$this->session->set_userdata('ADMIN_USERNAME', $row->app_user_name); 

						$this->session->set_userdata('ADMIN_ID', $row->app_user_id); 
						redirect('home'); 
				

				}



			else { 


				echo "string";die;
				$message = '<div class="alert alert-danger">'."Login Error!!".'</div>';

				$this->session->set_flashdata('success', $message);

				redirect('login'); 

			} 



		}

		else

		{

		
			print_r($row);die;

			$this->load->view('login', $data);

		

		}

	

	}



}?>

