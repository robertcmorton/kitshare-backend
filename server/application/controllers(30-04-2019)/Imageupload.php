<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Imageupload extends CI_Controller {

	 public function __construct() {

		parent::__construct();

		$this->load->helper(array('url','form','html','text',));

		$this->load->library(array('session','form_validation','pagination','email','upload','image_lib'));

		$this->load->model(array('common_model','mail_model'));


	}

		

		protected $validation_rules = array

        (

		'Add' => array(

			array(

                'field' => 'app_module_page',

                'label' => 'page',

                'rules' => 'trim|required'

            ),

			array(

                'field'   => 'app_module_desc',

                'label'   => 'description',

                'rules'   => 'trim|required'

            )

        ),

    );

	public function index()
	{


	}

	public function Image($value='')
	{
		$data= array();
			$this->load->view('Image', $data);
	}

	

	









	

}?>