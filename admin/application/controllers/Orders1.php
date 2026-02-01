<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Orders extends CI_Controller {

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



		// $data['username']		  = $this->input->get('username');

		// if($data['username'] != ''){

		// 		$where .= "a.app_username LIKE '%".trim($data['username'])."%' AND ";

		// }

			

	    

		// $data['first_name']		  = $this->input->get('first_name');

		// if($data['first_name'] != ''){

		// 		$where .= "a.app_user_first_name LIKE '%".trim($data['first_name'])."%' AND ";

		// }

		

		// $data['last_name']		  = $this->input->get('last_name');

		// if($data['last_name'] != ''){

		// 		$where .= "a.app_user_last_name LIKE '%".trim($data['last_name'])."%' AND ";

		// }





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

		



		$n=$this->model->OrderList($where);

		//$sql=$this->model->app_users($where,$data['limit'],$offset);

		

		$sql = $this->model->OrderList($where,$data['limit'],$offset,$order_by,$data['field_name']);

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

		$this->load->view('orders/list', $data);

		$this->load->view('common/footer');		



	}

	public function Invocie($order_id='')
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

	



	


	public function details($order_id)
	{
	  	$cart_details = $this->model->getUserCartbyOrderId($order_id);
	  	
	  	foreach ($cart_details as  $value) {
	  		
		 $users[] = 	$value['create_user'];
		}
		// print_r($users);die;
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
		
	  	$data['six_digit_random_number'] = $order_id ;
	  	$data['cart_details']= $cart_details ;
	  	$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('orders/rent_details', $data);

		$this->load->view('common/footer');	
	}

	  

	  public function rent_details()

	  {

	  	$data = array();

		$app_user_id              = $this->uri->segment(3);

		$user_gear_rent_detail_id = $this->uri->segment(4);

		$where = array("a.user_gear_rent_detail_id"=>$user_gear_rent_detail_id);

		$details = $this->model->app_users_rent($where,$limit='0',$offset='0',$app_user_id);

		$data['details']= $details->result();

		$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$data['details'][0]->create_user));
		$data['renter_details'] = $query->row();
		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('app_users/rent_details', $data);

		$this->load->view('common/footer');	

	  }

	  

	  public function import()

	  {

		 $data = array();

		 $heading = array();

		 if($this->input->post()!='')

			{

			      

				  $filename=$_FILES["file"]["tmp_name"];

				  if($_FILES["file"]["size"] > 0)

				  {

						$file = fopen($filename, "r");

						$count = 0;

						while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE)

						{

									if($count==0){

										

										$heading = $importdata;										

										

									}

									

									if($count>0){

									

										$cnt = count($importdata);

									

										for($i=1;$i<$cnt;$i++){

										

											if($i<52){

												$key = $heading[$i];

												$data[$key] = $importdata[$i];

											}

											

										}

									

										$user_data = $this->common_model->GetAllWhere('ks_users',array('primary_email_address'=>$data['primary_email_address']));

										$response = $user_data->result();

										//die;

										if (!empty($response)) {

											$this->db->where('app_user_id', $response['0']->app_user_id);

											$this->db->update('ks_users', $data); 

										}else{

											$this->common_model->addRecord('ks_users',$data);

										}

									

									}

									$count++;

					   }                    

					   fclose($file);

					   $message = '<div class="alert alert-success">Data imported successfully..</p></div>';

					   $this->session->set_flashdata('success', $message);

					   redirect('app_users');

				   }else{

				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';

				   $this->session->set_flashdata('success', $message);

				   redirect('app_users');

				   }

			}

	}

	

	public function downloadallcsv(){

	

		$query = $this->model->app_users();		

		$data = $query->result();
	//	echo "<pre>";print_r($data);die;
		$fp = fopen('php://output', 'w');

						

		if ($fp && $query) {

			header('Content-Type: text/csv');

			header('Content-Disposition: attachment; filename="app_user.csv"');

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

	public function getStateList(){

		

		$country_id = $this->input->post('country_id');

		$user_data = $this->common_model->GetAllWhere('ks_states',array('ks_country_id'=>$country_id));

		$response = $user_data->result();

		//print_r($response);die;

		if(!empty($response)){

			$value= '<option value="" >--Select State--</option>';

			foreach ($response AS $countries){

				$value .= '<option value="'.$countries->ks_state_id.'" >'.$countries->ks_state_name.'</option>';

			}

		}else{

			$value  = '<option value="" >--No State Found --</option>';

		}

		echo $value ;

	}

	public function getCityList(){

		

		$state_id = $this->input->post('state_id');

		$user_data = $this->common_model->GetAllWhere('ks_suburbs',array('ks_state_id'=>$state_id));

		$response = $user_data->result();

		//print_r($response);die;

		if(!empty($response)){

			$value= '<option value="" >--Select Suburb--</option>';

			foreach ($response AS $countries){

				$value .= '<option value="'.$countries->ks_suburb_id.'" >'.$countries->suburb_name.'</option>';

			}

		}else{

			$value  = '<option value="" >--No State Found --</option>';

		}

		echo $value ;

	}

	public function getpincode(){

		

		$suburb_id = $this->input->post('suburb_id');

		$user_data = $this->common_model->GetAllWhere('ks_suburbs',array('ks_suburb_id'=>$suburb_id));

		$response = $user_data->row();

		//print_r($response);die;

		if(!empty($response)){

			$value  = $response->suburb_postcode;

		}else{

			$value  = '';

		}

		echo $value ;

	}
	public function addaccountaddress()
 	{

 		$data[]= array();
 		$data['counter'] = $this->input->post('counter');
 		$data['countries']= $this->common_model->get_all_record('ks_countries',array("is_active"=>'Y'));
 		$this->load->view('app_users/add-account-address', $data);
 	}
	


 	public function chat_history($owner_id,$gear_id,$renter)
 	{
 		
 		$data = array();
 		$app_user_id = $owner_id;
 		$user_gear_desc_id = $gear_id ; 
 		$renter_id = $renter ; 
 		$sql = "SELECT * FROM ks_chat_user WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$app_user_id."')  AND  user_gear_desc_id = '".$user_gear_desc_id."' " ; 
			$check_response = $this->common_model->get_records_from_sql($sql);
			if (empty($check_response)) {
				$response['status'] = 200;
				$response['status_message'] = 'Message Found Successfully';
				$response['message'] = '';
				$data['response']= $response;
				
			}else{ 
				$chat_user_id =  $check_response[0]->chat_user_id;
					
			  	$query  =  $this->common_model->GetAllWhere('ks_user_chatmessage',array('chat_user_id'=>$chat_user_id));					 
			  	$message = $query->result();
			  	if (!empty($message)) {
			  		$i = 0 ;	
			  		 foreach ($message as  $value) {
			  		 		// sender details
			  		 		$query_sender  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$value->sender_id));					 
			  				$sender_details = $query_sender->result();
			  				if (!empty($sender_details)) {
			  					$sender_detail =array(
			  											'app_username'=>$sender_details[0]->app_username,
			  											'app_user_first_name'=>$sender_details[0]->app_user_first_name,
			  											'app_user_last_name'=>$sender_details[0]->app_user_last_name,
			  											'user_profile_picture_link'=>$sender_details[0]->user_profile_picture_link,
			  											'chat_status'=>$sender_details[0]->chat_status,
			  											);
			  					$message[$i]->sender_details = $sender_detail;
			  				}else{
			  					$message[$i]->sender_details = array();
			  				}

			  				$query_receiver  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$value->receiver_id));					 
			  				$receiver_details = $query_receiver->result();
			  				if (!empty($receiver_details)) {
			  					$receiver_detail =array(
			  											'app_username'=>$receiver_details[0]->app_username,
			  											'app_user_first_name'=>$receiver_details[0]->app_user_first_name,
			  											'app_user_last_name'=>$receiver_details[0]->app_user_last_name,
			  											'user_profile_picture_link'=>$receiver_details[0]->user_profile_picture_link,
			  											'chat_status'=>$receiver_details[0]->chat_status,
			  											);
			  					$message[$i]->receiver_details = $receiver_detail;
			  				}else{
			  					$message[$i]->receiver_details = array();
			  				}
			  				//print_r($sender_details[0]);	
			  		 	$i++;
			  		 }
			  		$query =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$user_gear_desc_id));
			  		$chatDetails['gear_detais'] =$query->row();
			  		$chatDetails['sender_details'] =  $this->common_model->get_records_from_sql("SELECT app_user_id,app_user_first_name ,app_user_last_name FROM ks_users WHERE app_user_id = '".$app_user_id."' ");
			  		$chatDetails['receiver_detail'] =   $this->common_model->get_records_from_sql("SELECT app_user_id,app_user_first_name ,app_user_last_name FROM ks_users WHERE app_user_id = '".$renter_id."' ");

			  	}
			  	$response['status'] = 200;
				$response['status_message'] = 'Message Send Successfully';
				$response['message'] = $message;
				$response['chatDetais'] = $chatDetails;
				$data['response'] =$response;
			}
				


 		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('app_users/chat_history', $data);

		$this->load->view('common/footer');
 	}

 	public function ViewList()
 	{
 		$this->load->view('orders/invoice_pdf');
 	}
	  

}?>