<?php
class Logout extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->library(array('session'));
	}
	
	function index() {		
		$this->session->sess_destroy();		
		redirect(base_url());
	}
	
}
// end of class
?>