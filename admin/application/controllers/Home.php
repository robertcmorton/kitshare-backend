<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Home extends CI_Controller {

	 public function __construct() {

		parent::__construct();

		$this->load->helper(array('url','form','html','text'));

		$this->load->library(array('session','form_validation','image_lib','email'));

		$this->load->model(array('common_model'));

		if($this->session->userdata('ADMIN_ID') =='') {

          redirect('login');

		}



	}

	

	public function index()

	{

		$data = array();

					

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('dashboard', $data);

		$this->load->view('common/footer');		

	}



}?>

