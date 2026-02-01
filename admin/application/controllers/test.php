<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation'));
		$this->load->model(array('adminlogin','common_model'));
	}
	
	public function index()
	{
		$data = array();
		$this->load->view('common/header.php', $data);
		$this->load->view('common/left-menu.php', $data);
		$this->load->view('test', $data);
		$this->load->view('common/footer', $data);
	}


}?>
