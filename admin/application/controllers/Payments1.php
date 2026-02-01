<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Payments extends CI_Controller {

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

                'field' => 'app_username',

                'label' => 'Username',

                'rules' => 'trim|required'
            ),
			array(

                'field' => 'app_user_first_name',

                'label' => 'First Name',

                'rules' => 'trim|required'
            ),
			array(
                'field' => 'app_user_last_name',
                'label' => 'Last Name',
                'rules' => 'trim|required'
            ),
			array(
                'field'   => 'app_password',
                'label'   => 'Password',
                'rules'   => 'trim|required'
            ),
			array(
                'field'   => 'conf_password',
                'label'   => 'Confirm Password',
                'rules'   => 'trim|required|matches[app_password]'
            )
        ),

    );



	public function index()

	{

	    $data=array();

		$where ='';

		if($this->input->get('limit') != ''){

				$data['limit']	= $this->input->get('limit');

		}

		else{

			$data['limit']	= 25;

		}
		$where = substr($where,0,(strlen($where)-4));
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

		

		if($this->input->get('field')!="")

			$data['field_name'] = $this->input->get('field');

		else

			$data['field_name'] = "app_user_id";

		



		$n=$this->model->OrderPayments($where);

		//$sql=$this->model->app_users($where,$data['limit'],$offset);

		

		$sql = $this->model->OrderPayments($where,$data['limit'],$offset,$order_by,$data['field_name']);

		//echo $this->db->last_query();

        
	
		$data['offset'] = $offset;

		$result=$sql->result();
		// echo "<pre>"	;
		// print_r($result);die;
		$total_rows=$n->num_rows();	

		$data['result'] = $result;

		$data['total_rows']=$total_rows;

		$data['limit']=$data['limit'];
	
		$config['base_url'] = base_url()."orders?order_by=".$order_by."&limit=".$data['limit'];

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



//////////////////////////////Pagination config//////////////////////////////////				

		//echo $this->db->last_query(); exit();



		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('payment/list', $data);

		$this->load->view('common/footer');		



	}

	public function Invocie($order_id='')
	{
		$sql = "SELECT o_d.* ,g_tbl.gear_name FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$order_id."'  "	;
		$order_details   = $this->common_model->get_records_from_sql($sql);
		foreach ($order_details as  $value) {
		 $users[] = 	$value->create_user;
		}
		$this->db->select('users.*,u_add.*,state.ks_state_name,suburbs.suburb_name,countries.ks_country_name ');
		$this->db->from('ks_user_gear_rent_master g_r_m');
		$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.user_gear_rent_id = g_r_m.user_gear_rent_id','inner');
		$this->db->join('ks_user_address as u_add', 'g_r_m.user_address_id = u_add.user_address_id','inner');
		$this->db->join('ks_states  As state', 'state.ks_state_id  = u_add.ks_state_id','inner');
		$this->db->join('ks_suburbs  As suburbs', 'suburbs.ks_suburb_id  = u_add.ks_suburb_id','inner');
		$this->db->join('ks_countries  As countries', 'countries.ks_country_id  = u_add.ks_country_id','inner');
		$this->db->join('ks_users  As users', 'users.app_user_id  = u_add.app_user_id','inner');
		$this->db->where('u_add.app_user_id' , $users[0]);
		$query = $this->db->get();
		$data['addrsss'] =  $query->row();
		// print_r($data['addrsss']);
		// print_r(array_unique($users));die;
		$url = '';
		$data['six_digit_random_number'] = $order_id.mt_rand(1000, 9999); 
		$this->uri->segment('3');
		 	$data['order_details'] = $order_details;
		// print_r($order_details);
		$mail_body = $this->mail_model->OrderInvocies($url , $data);
		//print_r($mail_body);die;
		$this->load->helper('pdf');
		gen_pdf($mail_body,$this->uri->segment('3'));
	}

	

	public function TestMail($order_id)
	{
		$sql = "SELECT o_d.* ,g_tbl.gear_name FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$order_id."'  "	;
		$order_details   = $this->common_model->get_records_from_sql($sql);
		foreach ($order_details as  $value) {
		 $users[] = 	$value->create_user;
		}
		//$this->db->select('*');
		$this->db->from('ks_user_gear_rent_master g_r_m');
		$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.user_gear_rent_id = g_r_m.user_gear_rent_id','inner');
		$this->db->join('ks_user_address as u_add', 'g_r_m.user_address_id = u_add.user_address_id','inner');
		$this->db->join('ks_states  As state', 'state.ks_state_id  = u_add.ks_state_id','inner');
		$this->db->join('ks_suburbs  As suburbs', 'suburbs.ks_suburb_id  = u_add.ks_suburb_id','inner');
		$this->db->join('ks_countries  As countries', 'countries.ks_country_id  = u_add.ks_country_id','inner');
		$this->db->join('ks_users  As users', 'users.app_user_id  = u_add.app_user_id','inner');
		$this->db->where('u_add.app_user_id' , $users[0]);
		$query = $this->db->get();
		$data['addrsss'] =  $query->row();
		// echo "<pre>";
		//  print_r($data['addrsss']);die;
		// print_r(array_unique($users));die;
		$url = '';
		//$data['six_digit_random_number'] = $order_id.mt_rand(1000, 9999); 
		$data['six_digit_random_number'] = $order_id; 
		$this->uri->segment('3');
		 	$data['order_details'] = $order_details;
		$mail_body = $this->load->view('orders/email-template1',$data ,true);
		// print_r($mail_body);die;	
		$this->load->helper('pdf');
		gen_pdf($mail_body,$this->uri->segment('3'));
	}

	


	
}?>