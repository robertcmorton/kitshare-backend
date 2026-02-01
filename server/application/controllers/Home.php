<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email'));
		$this->load->model(array('common_model','home_model'));
		}
	
	public function index()
	{
		$this->load->view('index');
	}
	
	public function contents(){
		
		//Contents are fetched corresponding to code
		$page_code=$this->uri->segment(3);
		$result = array();
		$content = array();
		
		if($page_code!=""){
		
			$content=$this->home_model->pageContents($page_code);
			if(count($content)>0)
			{
				$result['page_content']=$content;
				
			}
		}
				
		if(count($result)>0){
			echo json_encode($result);
			exit();
		}else{
			//header('HTTP/1.1 400 Bad Request');
			//exit();
			$response['status_code'] = 400;
			$response['status_message'] = "Bad Request";
			echo json_encode($response);
			exit();
		}
		
		
	}
	

}?>
