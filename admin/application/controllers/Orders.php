<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'/third_party/braintree-php-3.36.0/lib/autoload.php');
require_once APPPATH.'third_party/PHPExcel.php';


class Orders extends CI_Controller {

	 public function __construct() {

		parent::__construct();

		$this->load->helper(array('url','form','html','text','common_helper'));

		$this->load->library(array('session','form_validation','pagination','email','upload','image_lib','excel'));
		
 

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

			$data['limit']	= 100;

		}



		$data['amount']		  = $this->input->get('amount');

		if($data['amount'] != ''){

				$where .= "rent_details.total_rent_amount = '".$data['amount']."' AND ";

		}

			

	    

		 $data['is_payment_completed'] = $this->input->get('is_payment_completed');

		if($data['is_payment_completed'] != ''){

				$where .= "rent_details.is_payment_completed = '".$data['is_payment_completed']."' AND ";

		}

		

		$data['duration']		  = $this->input->get('duration');

		if($data['duration'] != ''){
			$date_array = explode(' - ',$data['duration']);
			$from_date_array = 	explode('/', $date_array[0]);
			$from_date = $from_date_array[2] .'-'.$from_date_array[0].'-'.$from_date_array[1] ;
			
			 $to_date_array = 	explode('/', $date_array[1]);
			 $to_date = $to_date_array[2] .'-'.$to_date_array[0].'-'.$to_date_array[1] ;
				 $where .= "rent_details.create_date BETWEEN '".$from_date."' AND '".$to_date."'    AND ";

		}
		$data['status']		  = $this->input->get('status');

		if($data['status'] != ''){
			if ($data['status'] == 'Reservation') {
					$update_Data = '2' ;
				 	$where .= "rent_details.order_status =  '".$update_Data."'  AND ";
				// die;
				}elseif ($data['status'] == 'Contract') {
						$update_Data ='3';
					 	$where .= "rent_details.order_status =  '".$update_Data."'  AND ";
				}elseif ($data['status'] == 'Completed') {
					$update_Data = '4';
					$where .= "rent_details.order_status =  '".$update_Data."'  AND ";
				}elseif ($data['status'] == 'Cancelled') {
					$update_Data = '5' ;
					$where .= "rent_details.order_status =  '".$update_Data."'  AND ";
				}elseif ($data['status'] == 'Rejected') {
						$update_Data = '6';
					 	$where .= "rent_details.order_status =  '".$update_Data."'  AND ";
				}elseif ($data['status'] == 'Expired') {
						$update_Data = '8';
					 	$where .= "rent_details.order_status =  '".$update_Data."'  AND ";
				}
				else{
					$update_Data = '1' ;
				 	$where .= "rent_details.order_status =  '".$update_Data."' OR rent_details.order_status =  ''   AND ";
				}	
			//$where .= "a.create_date BETWEEN '".$from_date."' AND '".$to_date."'    AND ";

		}

		$data['renter']		  = $this->input->get('renter');
		if ($data['renter'] !='') {
		
			$where .= "rent_details.create_user =  '".$data['renter']."'  AND ";

		}

		$data['owner']		  = $this->input->get('owner');
		if ($data['owner'] !='') {
		
			$where .= "gears.app_user_id =  '".$data['owner']."'  AND ";

		}
		$where = substr($where,0,(strlen($where)-4));
		$data['order_by']				= $this->input->get('order_by');
		if($data['order_by'] != ''){
				$order_by = $data['order_by'];
		}
		else{
			$order_by = 'DESC';
		}
		if($this->input->get("per_page")!= '')
		{
			$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}

		if($this->input->get('order_by_fld')!=""){

			if($this->input->get('order_by_fld')  == 'payment_status'){

				$data['field_name'] = " order_payment.status";
			}elseif ($this->input->get('order_by_fld')  == 'owner') {
				$data['field_name'] = " b.app_user_id ";
			}elseif ($this->input->get('order_by_fld')  == 'renter') {
				$data['field_name'] = " rent_details.create_user ";
			}elseif ($this->input->get('order_by_fld')  == 'request_period') {
				$data['field_name'] = " rent_details.gear_rent_request_from_date ";
			}
			else{
				$data['field_name'] = $this->input->get('order_by_fld');
			}	

		}else{

			$data['field_name'] = "rent_details.gear_rent_request_from_date";
		}
		$n=$this->model->OrderList($where);
		$sql = $this->model->OrderList($where,$data['limit'],$offset,$order_by,$data['field_name']);
		$data['offset'] = $offset;
		$result=$sql->result();
		
		$sql_tot = $this->model->OrderList($where);
		$result_tot=$sql_tot->result();
		
		$total_rows=count($result_tot);
		if (!empty($result)) {
			$i = 0 ;
			$total_rent_amount_ex_gst = '0';
           	$gst_amount = '0';
           	$other_charges = '0';
           	$total_rent_amount = '0';
           	$total_rent_amount2 = '0';
           	$security_deposit = '0';
          	$beta_discount = '0' ; 
            $insurance_fee = '0' ; 
            $community_fee = '0' ; 
            $owner_insurance_amount = 0;
			foreach ($result as $value) {
				$this->db->select(' *');
				$this->db->where('order_id',$value->order_id);
				$this->db->from('ks_user_gear_rent_details');
				$query = $this->db->get();
				$order_itme=  $query->result();

				$total_rent_amount_ex_gst = '0';
	           	$gst_amount = 0;
	           	$other_charges = 0;
	           	$total_rent_amount = 0;
	           	$total_rent_amount2 = 0;
	           	$security_deposit = 0;
	          	$beta_discount = 0 ; 
	            $insurance_fee = 0 ; 
	            $community_fee = 0 ; 
	            $owner_insurance_amount = 0;
				foreach ($order_itme as  $value1) {
					
					$total_rent_amount_ex_gst  += $value1->total_rent_amount_ex_gst ; 
		            $gst_amount  += ($value1->total_rent_amount_ex_gst -$value1->beta_discount +  $value1->insurance_fee + $value1->community_fee + number_format((float)( $value1->owner_insurance_amount), 2, '.', '') )*10/100;; 
		            $other_charges  += $value1->other_charges ; 
		            $total_rent_amount  += $value1->total_rent_amount_ex_gst -$value1->beta_discount + $value1->insurance_fee + $value1->community_fee + number_format((float)( $value1->owner_insurance_amount), 2, '.', '') ; 
		            $owner_insurance_amount += number_format((float)( $value1->owner_insurance_amount), 2, '.', '');
		            $security_deposit +=   $value1->security_deposit; 
		            $beta_discount += $value1->beta_discount ; 
		            $insurance_fee += $value1->insurance_fee ; 
		            $community_fee += $value1->community_fee ;
                    
				}
				
					// $total_rent_amount2  = $total_rent_amount + $gst_amount  ;                      
					$result[$i]->total_rent_amount = $total_rent_amount+ $gst_amount;
					$result[$i]->security_deposit = $security_deposit;
				$i++;
				// echo "<pre>";
		
			}

		}
		
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


		$query =  $this->model->GeOrderListRenterList();
		$data['renterlist'] = $query->result();

		$query =  $this->model->GeOrderListOwnerList();
		$data['ownerlist'] = $query->result();
		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('orders/list', $data);

		$this->load->view('common/footer');		



	}

	public function Invocie($order_id='')
	{
		
		$sql = "SELECT o_d.* ,g_tbl.gear_name,g_tbl.replacement_value_aud_inc_gst,g_tbl.replacement_value_aud_ex_gst ,g_tbl.per_day_cost_aud_ex_gst FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_order_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id AND g_tbl.order_id = o_d.order_id  WHERE o_d.order_id = '".$order_id."'  "	;
		
		$order_details   = $this->common_model->get_records_from_sql($sql);
		
		foreach ($order_details as  $value) {
		 $users[] = 	$value->create_user;
		}
		$this->db->select('*');
		$this->db->from('ks_gear_order_location u_add');
		$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.order_id = u_add.order_id','inner');
		$this->db->join('ks_user_gear_order_description as g_r_o_d', 'g_r_o_d.order_id = g_r_d.order_id','inner');
		$this->db->join('ks_users  As users', 'users.app_user_id  = g_r_d.create_user','inner');
		$this->db->where('g_r_o_d.order_id' , $order_id);
		$this->db->where('u_add.default_address' , 1);
		$query = $this->db->get();
		$address =  $query->row();
		
		if ($address) {
			
			/*if ($address->owner_app_show_business_name == 'Y') {
				 $address->owner_app_user_first_name = $address->owner_app_bussiness_name ;  
				 $address->owner_app_user_last_name = '';  
			}
			if ($address->renter_app_show_business_name == 'Y') {
				 $address->renter_app_user_first_name = $address->renter_app_bussiness_name ;  
				 $address->renter_app_user_last_name = '';  
			}*/
			
			if($address->show_business_name == 'Y' && $address->show_business_name!=""){
				if($address->show_business_name!=""){
					$address->renter_app_user_first_name = $address->bussiness_name ;  
					$address->renter_app_user_last_name = '';
				}else
					$address->renter_app_user_first_name = "";
			}else
				$address->renter_app_user_first_name = "";
		}
		
		$this->db->select('*');
		$this->db->from('ks_gear_order_location u_add');
		$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.order_id = u_add.order_id','inner');
		$this->db->join('ks_user_gear_order_description as g_r_o_d', 'g_r_o_d.order_id = g_r_d.order_id','inner');
		$this->db->join('ks_users  As users', 'users.app_user_id  = g_r_d.create_user','inner');
		$this->db->where('g_r_o_d.order_id' , $order_id);
		$query = $this->db->get();
		$address1 =  $query->row();
		$data['addrsss1'] =  $address1;
		$data['addrsss'] =  $address;
		$url = '';
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
	 //  	$cart_details = $this->model->getUserCartbyOrderId($order_id);
	 //  	 // $sql = "SELECT o_d.* ,g_tbl.gear_name,g_tbl.replacement_value_aud_inc_gst,g_tbl.replacement_value_aud_ex_gst ,g_tbl.per_day_cost_aud_ex_gst FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_order_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id AND g_tbl.order_id = o_d.order_id  WHERE o_d.order_id = '".$order_id."'  "	;
		
		// // $cart_details   = $this->common_model->get_records_from_sql($sql);
		
	 //  	echo "<pre>";
	 //  	print_r($cart_details);
	 //  	die;
	 //  	foreach ($cart_details as  $value) {
	  		
		//  $users[] = 	$value['create_user'];
		// }
		$sql = "SELECT o_d.* ,g_tbl.gear_name,g_tbl.replacement_value_aud_inc_gst,g_tbl.replacement_value_aud_ex_gst ,g_tbl.per_day_cost_aud_ex_gst FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_order_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id AND g_tbl.order_id = o_d.order_id  WHERE o_d.order_id = '".$order_id."'  "	;
		$order_details   = $this->common_model->get_records_from_sql($sql);
		foreach ($order_details as  $value) {
		 $users[] = 	$value->create_user;
		}
		$this->db->select('*');
		$this->db->from('ks_gear_order_location u_add');
		$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.order_id = u_add.order_id','inner');
		$this->db->join('ks_user_gear_order_description as g_r_o_d', 'g_r_o_d.order_id = g_r_d.order_id','inner');
		$this->db->join('ks_users  As users', 'users.app_user_id  = g_r_d.create_user','inner');
		$this->db->where('g_r_o_d.order_id' , $order_id);
		$this->db->where('u_add.default_address' , 1);
		$query = $this->db->get();
		$address =  $query->row();
		
		if (empty($address)) {
			
		}
		if ($address) {
			
			if ($address->owner_app_show_business_name == 'Y') {
				 $address->owner_app_user_first_name = $address->owner_app_bussiness_name ;  
				 $address->owner_app_user_last_name = '';  
			}
			if ($address->renter_app_show_business_name == 'Y') {
				 $address->renter_app_user_first_name = $address->renter_app_bussiness_name ;  
				 $address->renter_app_user_last_name = '';  
			}
		}
		$this->db->select('*');
		$this->db->from('ks_gear_order_location u_add');
		$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.order_id = u_add.order_id','inner');
		$this->db->join('ks_user_gear_order_description as g_r_o_d', 'g_r_o_d.order_id = g_r_d.order_id','inner');
		$this->db->join('ks_users  As users', 'users.app_user_id  = g_r_d.create_user','inner');
		$this->db->where('g_r_o_d.order_id' , $order_id);
		$query = $this->db->get();
		$address1 =  $query->row();
		// echo "<pre>";
		// print_r($address1);die;
		$data['addrsss1'] =  $address1;
		$data['addrsss'] = $address ;
	  	$data['six_digit_random_number'] = $order_id ;
	  	$data['cart_details']= $order_details ;
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
			  		$query =  $this->common_model->GetAllWhere('ks_user_gear_order_description' ,array('user_gear_desc_id'=>$user_gear_desc_id));
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
	public function UpdateStatus()
	{
			$post_data =  $this->input->post();
			$query = $this->common_model->GetAllWhere('ks_user_gear_rent_details',array('order_id'=>$post_data['order_id']));
			$order_details = $query->row();			
			if (!empty($order_details)) {
				if ($post_data['status_order'] == 'Reservation') {
					$update_Data = array(
											'is_rent_approved'=>'Y',
											'is_rent_rejected'=>'N',
											'order_status'=>'2',
											'order_status_date'=>date('Y-m-d')
										);

					$insert_cron = array(

			 						'type' => 'Reservation',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $post_data['order_id'],
			 						'status' => 'Reservation',
			 					);

			  		$this->common_model->addRecord('ks_crone_log' ,$insert_cron);


				}elseif ($post_data['status_order'] == 'Contract') {
					$update_Data = array(
											'is_rent_started'=>'Y',
											'start_date'=>date('Y-m-d'),
											'started_by'=> 0 ,
											'order_status'=>'3',
											'order_status_date'=>date('Y-m-d')
										);
					$insert_cron = array(

			 						'type' => 'Contract',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $post_data['order_id'],
			 						'status' => 'Contract',
			 					);

			  		$this->common_model->addRecord('ks_crone_log' ,$insert_cron);

				}elseif ($post_data['status_order'] == 'Completed') {
						$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$post_data['order_id'] ,'payment_type'=>'Deposite Payment'));
						$order_details = $query->row();
						if (!empty($order_details)) {
							
						
								if($order_details->transaction_id !='') {

										$query =  $this->common_model->GetAllWhere('ks_settings',array() );
										$settings = $query->row();		


										// print_r($order_details);
										if (version_compare(PHP_VERSION, '5.4.0', '<')) {
										    throw new Braintree_Exception('PHP version >= 5.4.0 required');
										}
										
										// Instantiate a Braintree Gateway either like this:
										$gateway = new Braintree_Gateway([
										    'environment' => BRAINTREE_ENVIORMENT,
										    'merchantId' => $settings->braintree_merchant_id,
										    'publicKey' => $settings->braintree_public_key,
										    'privateKey' => $settings->braintree_private_key
										]);

										$config = new Braintree_Configuration([
										   'environment' => BRAINTREE_ENVIORMENT,
										    'merchantId' => $settings->braintree_merchant_id,
										    'publicKey' => $settings->braintree_public_key,
										    'privateKey' => $settings->braintree_private_key
										]);

										$result = $gateway->transaction()->void($order_details->transaction_id);
										if($result->success == '1'){
												$this->db->where('order_id', $post_data['order_id']);
												$this->db->update('ks_user_gear_rent_details', array('deposite_status'=>'Cancelled')); 


												$this->db->where('gear_order_id', $post_data['order_id']);
												$this->db->where('payment_type', 'Deposite Payment');
												$this->db->update('ks_user_gear_payments', array('status'=>'Cancelled')); 

												$insert_cron = array(

								 						'type' => 'Transaction Void',
								 						'date_time' => date('Y-m-d H:i:m'),
								 						'order_id' => $post_data['order_id'],
								 						'status' => 'Transaction Void',
								 					);
												$this->common_model->addRecord('ks_crone_log' ,$insert_cron);

										}

								}
						}		
						$insert_cron = array(

			 						'type' => 'Completed',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $post_data['order_id'],
			 						'status' => 'Completed',
			 					);

			  			$this->common_model->addRecord('ks_crone_log' ,$insert_cron);

					$update_Data = array(
											'owner_rent_complete'=>'Y',
											'owner_rent_complete_date'=>date('Y-m-d'),
											'completed_by'=> 0 ,
											'order_status'=>'4',
											'order_status_date'=>date('Y-m-d')
										);
				}elseif ($post_data['status_order'] == 'Cancelled') {
					$update_Data = array(
											'is_rent_cancelled'=>'Y',
											'rent_calcelled'=>'Y',
											'rent_request_cancelled_by' => '0' ,
											'order_status'=>'5',
											'order_status_date'=>date('Y-m-d')
										);

					$insert_cron = array(

			 						'type' => 'Cancelled',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $post_data['order_id'],
			 						'status' => 'Cancelled',
			 					);

			  		$this->common_model->addRecord('ks_crone_log' ,$insert_cron);

				}elseif ($post_data['status_order'] == 'Rejected') {
					$update_Data = array(
											'is_rent_rejected'=>'Y',
											'rent_request_cancelled_by' => 0,
											'rent_approved_rejected_on'=>date('Y-m-d'),
											'order_status'=>'6',
											'order_status_date'=>date('Y-m-d')
										);

					$insert_cron = array(

			 						'type' => 'Rejected',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $post_data['order_id'],
			 						'status' => 'Rejected',
			 					);

			  		$this->common_model->addRecord('ks_crone_log' ,$insert_cron);
				}elseif ($post_data['status_order'] == 'Expired') {
					$update_Data = array(
											'is_rent_cancelled'=>'Y',
											'rent_calcelled'=>'Y',
											'rent_request_cancelled_by' => '0' ,
											'order_status'=>'8',
											'order_status_date'=>date('Y-m-d')
										);

					$insert_cron = array(

			 						'type' => 'Expired',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $post_data['order_id'],
			 						'status' => 'Expired',
			 					);

			  		$this->common_model->addRecord('ks_crone_log' ,$insert_cron);
				}
				elseif ($post_data['status_order'] == 'Archived') {
					$update_Data = array(
											'is_rent_rejected'=>'Y',
											'rent_request_cancelled_by' => 0,
											'rent_approved_rejected_on'=>date('Y-m-d'),
											'order_status'=>'7',
											'order_status_date'=>date('Y-m-d')
										);
				}else{
					$update_Data = array(
											'is_rent_rejected'=>'Y',
											'rent_request_cancelled_by' => 0,
											'rent_approved_rejected_on'=>date('Y-m-d'),
											'order_status'=>'1',
											'order_status_date'=>date('Y-m-d')
										);

					$insert_cron = array(

			 						'type' => 'Quote',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $post_data['order_id'],
			 						'status' => 'Quote',
			 					);

			  		$this->common_model->addRecord('ks_crone_log' ,$insert_cron);
				}
			//	die;
				$this->db->where('order_id', $post_data['order_id']);
				$this->db->update('ks_user_gear_rent_details', $update_Data); 
				$response['status']= '1';
				$response['message']= '<div class="alert alert-success"><p>Order status id been updated </p></div>';
			}else{
				$response['status']= '0';
				$response['message']= '<div class="alert alert-danger"><p>No order found </p></div>';
			}
			echo json_encode($response);
	}
	public function UpdatePaymentStatus()
	{
		$post_data =  $this->input->post();
		$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$post_data['order_id'] ,'payment_type'=>'Gear Payment'));
		$order_details = $query->row();
		// print_r($order_details->transaction_id)	;die;	
		if ($post_data['payment_status'] ==  'AUTHORISED') {
				$order_id = $post_data['order_id'] ;
				$query=  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('order_id'=>$post_data['order_id']));
				$user_data =  $query->row();
				//	print_r($user_data);die();	
				if (empty($user_data->braintree_token)) {
					$response['status']= '0';
					 $response['message']= '<div class="alert alert-danger"><p>No order found </p></div>';
					 echo json_encode($response); 
					 die;
					 exit;
				}
				if (version_compare(PHP_VERSION, '5.4.0', '<')) {
				    throw new Braintree_Exception('PHP version >= 5.4.0 required');
				}
				
				$query =  $this->common_model->GetAllWhere('ks_settings',array() );
				$settings = $query->row();	
				// Instantiate a Braintree Gateway either like this:
				$gateway = new Braintree_Gateway([
				    'environment' => BRAINTREE_ENVIORMENT,
				    'merchantId' => $settings->braintree_merchant_id,
				    'publicKey' => $settings->braintree_public_key,
				    'privateKey' => $settings->braintree_private_key
				]);

				$config = new Braintree_Configuration([
				    'environment' => BRAINTREE_ENVIORMENT,
				    'merchantId' => $settings->braintree_merchant_id,
				    'publicKey' => $settings->braintree_public_key,
				    'privateKey' => $settings->braintree_private_key
				]);
				$result = $gateway->paymentMethodNonce()->create($user_data->braintree_token);
				/*$sql = "SELECT o_d.* ,g_tbl.gear_name,g_tbl.replacement_value_aud_inc_gst,g_tbl.replacement_value_aud_ex_gst ,g_tbl.per_day_cost_aud_ex_gst FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$order_id."'  "	;
				$cart_details   = $this->common_model->get_records_from_sql($sql);
				$sum= 0 ;
				foreach ($cart_details as  $value) {
							$sum	+= $value->total_rent_amount ;
				}*/
				
				/*$result = $gateway->transaction()->sale([
					    							'amount' =>number_format((float) $sum , 2, '.', '') ,
									    			'paymentMethodNonce' => $result->paymentMethodNonce->nonce
												]); */
												
				$result = $gateway->transaction()->sale([
						    							'amount' =>number_format((float) $order_details->transaction_amount , 2, '.', ''),
										    			'paymentMethodNonce' => $result->paymentMethodNonce->nonce
													]); 
				if ($result->transaction->processorResponseCode != 1000) {
					$query =  $this->common_model->GetAllWhere('tbl_braintree_error_details',array('error_code'=>$result->transaction->processorResponseCode));
					$data =  $query->row();
					
					
					//Record is inserted into the Transaction Error Log table
					$insert_error = array("order_id"=>$order_id,
										  "processorResponseCode"=>$result->transaction->processorResponseCode,
										  "processorResponseMessage"=>$result->message,
										  "created_date"=>date("Y-m-d"),
										  "created_time"=>date("H:i:s"));
					
					$this->common_model->InsertData('tbl_transaction_error_log' ,$insert_error); 
					
					$insert_cron= array('type' => 'Authorize Payment',
							'date_time' => date('Y-m-d H:i:s'),
							'order_id' => $order_id,
							'status' => $result->message,
							);


					$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
					
					
					
					$response['status'] = 400;
					$response['status_message'] =  $data->message;
					$json_response = json_encode($response);
					echo $json_response;
					exit();
				}
				
				$insert_cron = array(

			 						'type' => 'Authorize Payment',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $post_data['order_id'],
			 						'status' => 'Authorize Payment',
			 					);

				$this->common_model->addRecord('ks_crone_log' ,$insert_cron);
				
				$update_Data = array(
								
								'transaction_id'=> $result->transaction->id,
							);
				$this->db->where('user_gear_payment_id', $order_details->user_gear_payment_id);
				$this->db->update('ks_user_gear_payments', $update_Data); 
		}	
		
		if ($post_data['payment_status'] ==  'RECEIVED') {
			
				$order_id = $post_data['order_id'] ;
				$query=  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('order_id'=>$post_data['order_id']));
				$user_data =  $query->result();
				$total_rent_amount_ex_gst = '0';
				 $gst_amount = '0';
				 $other_charges = '0';
				 $total_rent_amount = '0';
				 $security_deposit = '0';
				 $beta_discount = '0';
				 $insurance_fee = '0';
				 $community_fee = '0';
				 $sub_amount = '0' ;
				foreach ($user_data AS  $value) {
					 	$total_rent_amount_ex_gst  += $value->total_rent_amount_ex_gst ; 
					 	$gst_amount  += $value->gst_amount ; 
			 			$security_deposit +=   '0';
			 			 $beta_discount += $value->beta_discount ; 
				 		 $community_fee += $value->community_fee; 
				 		 $sub_amount += $value->total_rent_amount_ex_gst - $value->beta_discount  +  $value->community_fee + $value->gst_amount + $value->insurance_fee ; 
				 		 $total_rent_amount  += $sub_amount ;
				 		 
				}


				$query =  $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=> $user_data[0]->order_id ,'payment_type'=>'Gear Payment'));
				$transaction_details = $query->row();
				

				if (!empty($transaction_details->transaction_id)) {

						if (version_compare(PHP_VERSION, '5.4.0', '<')) {
						    throw new Braintree_Exception('PHP version >= 5.4.0 required');
						}
						
						// Instantiate a Braintree Gateway either like this:
						$query =  $this->common_model->GetAllWhere('ks_settings',array() );
						$settings = $query->row();	
						// Instantiate a Braintree Gateway either like this:
						$gateway = new Braintree_Gateway([
						    'environment' => BRAINTREE_ENVIORMENT,
						    'merchantId' => $settings->braintree_merchant_id,
						    'publicKey' => $settings->braintree_public_key,
						    'privateKey' => $settings->braintree_private_key
						]);

						$config = new Braintree_Configuration([
						    'environment' => BRAINTREE_ENVIORMENT,
						    'merchantId' => $settings->braintree_merchant_id,
						    'publicKey' => $settings->braintree_public_key,
						    'privateKey' => $settings->braintree_private_key
						]);

						 $result = $gateway->transaction()->submitForSettlement($transaction_details->transaction_id,number_format((float) $transaction_details->transaction_amount , 2, '.', ''));
						 if($result->errors){
								//$query =  $this->common_model->GetAllWhere('tbl_braintree_error_details',array('error_code'=>$result->transaction->processorResponseCode));
								//$data =  $query->row();
								
								//Record is inserted into the Transaction Error Log table
								$insert_error = array("order_id"=>$order_id,
													  "transaction_id"=>$transaction_details->transaction_id,
													  "processorResponseCode"=>$result->transaction->processorResponseCode,
													  "processorResponseMessage"=>$result->message,
													  "created_date"=>date("Y-m-d"),
													  "created_time"=>date("H:i:s"));
								
								$this->common_model->InsertData('tbl_transaction_error_log' ,$insert_error); 
								
								$insert_cron= array('type' => 'Received Payment',
									'date_time' => date('Y-m-d H:i:s'),
									'order_id' => $order_id,
									'status' => $result->message,
								);								
								
								$response['status'] = 400;
								$response['status_message'] =  $data->message;
								$json_response = json_encode($response);
								echo $json_response;
								exit();
						}
						 $insert_cron= array('type' => 'Received  Payment',
										'date_time' => date('Y-m-d H:i:m'),
										'order_id' => $order_id,
										'status' => 'Received Payment',
										);

						$this->common_model->addRecord('ks_crone_log' ,$insert_cron); 
						
						if ($result->success) {
							$settledTransaction = $result->transaction;
						} else {
						} 
				}


		}

		if ($post_data['payment_status'] ==  'Void') {
			if (condition) {
				# code...
			}
		}
		// die;
		if (!empty($order_details)) {
				if ($post_data['payment_status']  != 'RECEIVED' || $post_data['payment_status']  != 'AUTHORISED' ) {
					
					$insert_cron = array(

			 						'type' => ucfirst(strtolower($post_data['payment_status'])),
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $post_data['order_id'],
			 						'status' => ucfirst(strtolower($post_data['payment_status'])),
			 					);

			  		$this->common_model->addRecord('ks_crone_log' ,$insert_cron);
			  	}	
					$update_Data = array(
											
											'update_date'=>date('Y-m-d'),
											'status'=>$post_data['payment_status'],
											'update_user'=>$this->session->userdata('ADMIN_ID'),

										);
				$this->db->where('gear_order_id', $post_data['order_id']);
				$this->db->where('payment_type', 'Gear Payment');
				$this->db->update('ks_user_gear_payments', $update_Data); 
				$response['status']= '1';
				$response['message']= '<div class="alert alert-success"><p>Order status id been updated </p></div>';
		}else{
				$response['status']= '0';
				$response['message']= '<div class="alert alert-danger"><p>No order found </p></div>';
		}
		echo json_encode($response);
	} 

	// Update Secuity Status and braintree transcation update
	public function UpdateDepositStatus()
	{
	 	$post_data =  $this->input->post();
	 	
	 	
	 	$post_data =  $this->input->post();
		$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$post_data['order_id'] ,'payment_type'=>'Deposite Payment'));
		$order_details = $query->row();
		// // echo $this->db->last_query();
		if ($post_data['deposite_status'] == 'AUTHORISED') {
			$order_id = $post_data['order_id'] ;
			$query=  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('order_id'=>$post_data['order_id']));
			$user_data =  $query->result();
			$security_deposite_token_braintree = array();
			foreach ($user_data as $value) {
				if (!empty($value->security_deposite_token_braintree &&  $value->security_deposit)) {
					$security_deposite_token_braintree[] = $value->security_deposite_token_braintree ;
				}else{
					
				}
			}
			if (!empty($security_deposite_token_braintree)) {
			  $security_deposite_token_braintree   = 	array_unique($security_deposite_token_braintree);
			  	$query =  $this->common_model->GetAllWhere('ks_settings',array() );
				$settings = $query->row();	
				// Instantiate a Braintree Gateway either like this:
				$gateway = new Braintree_Gateway([
				    'environment' => BRAINTREE_ENVIORMENT,
				    'merchantId' => $settings->braintree_merchant_id,
				    'publicKey' => $settings->braintree_public_key,
				    'privateKey' => $settings->braintree_private_key
				]);

				$config = new Braintree_Configuration([
				    'environment' => BRAINTREE_ENVIORMENT,
				    'merchantId' => $settings->braintree_merchant_id,
				    'publicKey' => $settings->braintree_public_key,
				    'privateKey' => $settings->braintree_private_key
				]);
				$result = $gateway->paymentMethodNonce()->create($security_deposite_token_braintree[0]);
				$sql = "SELECT o_d.* ,g_tbl.gear_name,g_tbl.replacement_value_aud_inc_gst,g_tbl.replacement_value_aud_ex_gst ,g_tbl.per_day_cost_aud_ex_gst FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$order_id."'  "	;
				$cart_details   = $this->common_model->get_records_from_sql($sql);
				$sum= 0 ;
				
				foreach ($cart_details as  $value) {
							$sum	+= $value->security_deposit ;
				}
				$result = $gateway->transaction()->sale([
					    							'amount' =>number_format((float) $sum , 2, '.', '') ,
									    			'paymentMethodNonce' => $result->paymentMethodNonce->nonce
												]); 
				if ($result->transaction->processorResponseCode != 1000) {
					$query =  $this->common_model->GetAllWhere('tbl_braintree_error_details',array('error_code'=>$result->transaction->processorResponseCode));
					$data =  $query->row();
					$response['status'] = 400;
					$response['status_message'] =  $data->message;
					$json_response = json_encode($response);
					echo $json_response;
					exit();
				}
							$update_Data = array(
											
											'transaction_id'=> $result->transaction->id,
										);
				$this->db->where('user_gear_payment_id', $order_details->user_gear_payment_id);
				$this->db->update('ks_user_gear_payments', $update_Data); 
				

			}
			
			
		}

		if ($post_data['deposite_status'] ==  'RECEIVED') {
			$query =  $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$post_data['order_id'] ,'payment_type'=>'Deposite Payment'));
			$transaction_details = $query->row();
				if (version_compare(PHP_VERSION, '5.4.0', '<')) {
						    throw new Braintree_Exception('PHP version >= 5.4.0 required');
						}
						
						// Instantiate a Braintree Gateway either like this:
						$query =  $this->common_model->GetAllWhere('ks_settings',array() );
						$settings = $query->row();	
						// Instantiate a Braintree Gateway either like this:
						$gateway = new Braintree_Gateway([
						    'environment' => BRAINTREE_ENVIORMENT,
						    'merchantId' => $settings->braintree_merchant_id,
						    'publicKey' => $settings->braintree_public_key,
						    'privateKey' => $settings->braintree_private_key
						]);

						$config = new Braintree_Configuration([
						    'environment' => BRAINTREE_ENVIORMENT,
						    'merchantId' => $settings->braintree_merchant_id,
						    'publicKey' => $settings->braintree_public_key,
						    'privateKey' => $settings->braintree_private_key
						]);
						
						 $result = $gateway->transaction()->submitForSettlement($transaction_details->transaction_id,number_format((float) $transaction_details->transaction_amount , 2, '.', ''));
						if ($result->transaction->processorResponseCode != 1000) {
								$query =  $this->common_model->GetAllWhere('tbl_braintree_error_details',array('error_code'=>$result->transaction->processorResponseCode));
								$data =  $query->row();
								$response['status'] = 400;
								$response['status_message'] =  $data->message;
								$json_response = json_encode($response);
								echo $json_response;
								exit();
							}
						if ($result->success) {
				    		$settledTransaction = $result->transaction;
						} else {
						} 





		}
		if ($post_data['deposite_status'] == 'Void') {
			
			if (!empty($order_details->transaction_id)) {
				$query =  $this->common_model->GetAllWhere('ks_settings',array() );
				$settings = $query->row();		


				// print_r($order_details);
				if (version_compare(PHP_VERSION, '5.4.0', '<')) {
				    throw new Braintree_Exception('PHP version >= 5.4.0 required');
				}
										
				// Instantiate a Braintree Gateway either like this:
				$gateway = new Braintree_Gateway([
				    'environment' => BRAINTREE_ENVIORMENT,
				    'merchantId' => $settings->braintree_merchant_id,
				    'publicKey' => $settings->braintree_public_key,
				    'privateKey' => $settings->braintree_private_key
				]);

				$config = new Braintree_Configuration([
				   'environment' => BRAINTREE_ENVIORMENT,
				    'merchantId' => $settings->braintree_merchant_id,
				    'publicKey' => $settings->braintree_public_key,
				    'privateKey' => $settings->braintree_private_key
				]);

				$result = $gateway->transaction()->void($order_details->transaction_id);
				if ($result->success ==1 ) {
					
				}else{
					$response['status']= '0';
					$response['message']= '<div class="alert alert-danger"><p>No transaction amount found </p></div>';		
					echo  json_encode($response);
					exit;
				}
			}else{

				$response['status']= '0';
				$response['message']= '<div class="alert alert-danger"><p>Transaction is not authorized. </p></div>';


				exit;
			}

			
		}	
		if (!empty($order_details)) {
					$update_Data = array(
											
											'update_date'=>date('Y-m-d'),
											'status'=>$post_data['deposite_status'],
											'update_user'=>$this->session->userdata('ADMIN_ID'),

										);
				$this->db->where('gear_order_id', $post_data['order_id']);
				$this->db->where('payment_type', 'Deposite Payment');
				$this->db->update('ks_user_gear_payments', $update_Data); 

				$this->db->where('order_id', $post_data['order_id']);
				
				$this->db->update('ks_user_gear_rent_details', array('deposite_status'=>$post_data['deposite_status'] )); 


				$response['status']= '1';
				$response['message']= '<div class="alert alert-success"><p>Deposite status id been updated </p></div>';
		}else{
				$response['status']= '0';
				$response['message']= '<div class="alert alert-danger"><p>No order found </p></div>';
		}
		//die;
		echo json_encode($response);

		// print_r($order_details);die;	
	}


	public function OrdersSummmary()
	{	
		error_reporting(0);
		$where = '';
			if(!empty($this->input->get())){
				$start_date =$this->input->get('start_date')  ;
				$end_date =  $this->input->get('end_date');
				if(!empty($start_date)){
					$start_date  =explode('/',$start_date);
					 $start_date = $start_date['2'].'-' .$start_date['0'].'-'.$start_date['1'] ;
					$where .= "   rent_details.gear_rent_end_date >= '".$start_date."'  "; 						
				}
				if(!empty($end_date)){

					$end_date  =explode('/',$end_date);
					 $end_date = $end_date['2'].'-' .$end_date['0'].'-'.$end_date['1'] ;
					if(!empty($where)){
						$where .= " AND  rent_details.gear_rent_end_date <= '".$end_date."'  ";

					}else{
					$where .= "   rent_details.gear_rent_end_date <= '".$end_date."'  ";

					}
				}
				if(!empty($end_date) && !empty($start_date) ){

				 $calender_date =  $start_date.' -  '.$end_date;
				}else{
					$calender_date = date('d-m-Y');
				}
			}
			$n=$this->model->OrderListSummary($where);
			$result = $n->result();
			// echo $this->db->last_query();
			// echo "<pre>";
			// print_r($result);die;
			if (!empty($result)) {
				$j = 0;
				foreach ($result as  $orders) {
						
						$query = $this->common_model->GetAllWhere('ks_user_gear_rent_details', array('order_id' => $orders->order_id ));
						$data =  $query->result();
						$beta_discount = 0;
						$insurance_fee = 0;
						$community_fee = 0;
						$gst_amount = 0;
						$deposit_amount = 0;
						$total_rent_amount = 0;
						$total_rent_amount_ex_gst = 0;
						$owner_insurance_amount = 0;
						if (!empty($data)) {
							foreach ($data as  $order_array) {
									$beta_discount += number_format((float)$order_array->beta_discount, 2, '.', '');
				 					$insurance_fee += number_format((float)$order_array->insurance_fee, 2, '.', '');
				 					$community_fee += number_format((float)$order_array->community_fee, 2, '.', '');
				 					$gst_amount +=	number_format((float)$order_array->gst_amount, 2, '.', '');
				 					$deposit_amount +=	number_format((float)$order_array->insurance_amount, 2, '.', '');
				 					$owner_insurance_amount +=	number_format((float)$order_array->owner_insurance_amount, 2, '.', '');
				 					$total_rent_amount += number_format((float)$order_array->total_rent_amount, 2, '.', '');
				 					$total_rent_amount_ex_gst += number_format((float)($order_array->total_rent_amount_ex_gst), 2, '.', '');
							}
							$count = count($data);
						}else{
								$beta_discount += number_format((float)$orders->beta_discount, 2, '.', '');
			 					$insurance_fee += number_format((float)$orders->insurance_fee, 2, '.', '');
			 					$community_fee += number_format((float)$orders->community_fee, 2, '.', '');
			 					$gst_amount +=	number_format((float)$orders->gst_amount, 2, '.', '');
			 					$deposit_amount +=	number_format((float)$orders->insurance_amount, 2, '.', '');
			 					$owner_insurance_amount +=	number_format((float)$order_array->owner_insurance_amount, 2, '.', '');
			 					$total_rent_amount += number_format((float)$orders->total_rent_amount, 2, '.', '');
			 					$total_rent_amount_ex_gst += number_format((float)$orders->total_rent_amount_ex_gst, 2, '.', '');
			 					$count = count($orders);
						}	
						
						$result[$j]->beta_discount =$beta_discount;
						$result[$j]->insurance_fee =$insurance_fee;
						$result[$j]->community_fee =$community_fee;
						$result[$j]->gst_amount =$gst_amount;
						$result[$j]->deposit_amount =$deposit_amount;
						$result[$j]->owner_insurance_amount =$owner_insurance_amount;

						$result[$j]->total_rent_amount =$total_rent_amount_ex_gst -$beta_discount + $community_fee + $gst_amount + $insurance_fee + $owner_insurance_amount ;
						$result[$j]->total_rent_amount_ex_gst =$total_rent_amount_ex_gst -$beta_discount + $community_fee + $insurance_fee + $owner_insurance_amount;
						$result[$j]->listing_count =$count;
					$j++;

				}

			}
			// echo "<pre>";
			// print_r($result);die;
			if(!empty($result)){
				$i= 0;
			
				foreach ($result as  $value) {

						if($value->order_status =='1' || $value->order_status ==' ' ){
							$status = 'Quote' ;
						}elseif($value->order_status == '2'){
							$status = 'Reservation' ;
						}elseif($value->order_status == '3'){
							$status = 'Contract' ;
						}elseif($value->order_status == '4'){
							$status = 'Completed' ;
						}elseif($value->order_status == '5'){
							$status = 'Cancelled' ;
						}elseif($value->order_status == '6'){
							$status = 'Rejected' ;
						}elseif($value->order_status == '7'){
							$status = 'Archived' ;
						}elseif ($value->order_status == '8') {
							$status = 'Expired';
						}

						if($value->paymnet_status == ''){
							$paymnet_status = 'STORE';
						}elseif($value->paymnet_status == 'RECEIVED'){
							$paymnet_status = 'SETTLED';
						}else{
							$paymnet_status = $value->paymnet_status;
						}
						$date_from = strtotime(date('d-m-Y',strtotime($value->gear_rent_start_date)));
						$date_to = strtotime(date('d-m-Y',strtotime($value->gear_rent_end_date)));
						$diff = abs($date_to - $date_from);
						$date_array =  $this->getDateList($date_from,$date_to);
						array_pop($date_array); 
						$insurance_days = count($date_array);
					 	if ($insurance_days == 0) {
					 		$insurance_days = 1;
					 	}
					 	array_shift($date_array);
						$shoot_days = count($date_array);
						if(empty($shoot_days)){
							$shoot_days = 1;
						}
						if($value->renter_rent_complete_date =='0000-00-00'){
							$renter_rent_complete_date = '';
						}else{
							$renter_rent_complete_date = $value->renter_rent_complete_date;
						}

						$end_date =  date('d-m-Y',strtotime($value->gear_rent_end_date)) ;
						$start_date =  date('d-m-Y',strtotime($value->gear_rent_start_date)) ;
						$gear_data[] =  array(
											'Serial_no'=> $i+1,	
				 							'order_id' => $value->order_id,
				 							'project_name'=> $value->project_name ,
				 							'owner_name'=>$value->app_user_first_name .' ' .$value->app_user_last_name,
				 							'buyer_name'=>$value->buyer_first_name.' '.$value->buyer_last_name,
				 							'start_date' => $start_date.' 14:00:00',
				 							'end_date' => $end_date .' 12:00:00',
				 							'requested_date'=> $value->gear_rent_requested_on,
				 							'renter_rent_complete_date' =>$renter_rent_complete_date,
				 							'gear_total_rent_request_days'=> $value->gear_total_rent_request_days,
											'total_shoot_days'=>$shoot_days,
				 							'insurance_days'=>$insurance_days,					 								
				 							'sub_total'=> number_format((float)$value->total_rent_amount_ex_gst, 2, '.', ''),
				 							'beta_discount'=>	number_format((float)$value->beta_discount, 2, '.', ''),
				 							'insurance_fee'=>	number_format((float)$value->insurance_fee, 2, '.', ''),
				 							'community_fee'=>	number_format((float)$value->community_fee, 2, '.', ''),
				 							'owner_insurance_amount'=>	number_format((float)$value->owner_insurance_amount, 2, '.', ''),
				 							'sub_total_ex_gst'=> number_format((float)$value->total_rent_amount_ex_gst, 2, '.', ''),
				 							'gst_amount'=>	number_format((float)$value->gst_amount, 2, '.', ''),
				 							'total_rent_amount'=> number_format((float)$value->total_rent_amount, 2, '.', ''),
				 							'deposit_amount' =>	number_format((float)$value->security_deposit, 2, '.', ''),
				 							'deposite_status'=> $value->deposite_status,
				 							'order_status'=>$status ,
				 							'payment_status'=>$paymnet_status,
				 							'Insurance_type'=>$value->name ,
				 							'tier_type'=>$value->tier_name ,
				 							'tiers_percentage'=>$value->tiers_percentage,
				 					);	
						$n=$this->model->OrderListItem(array('gears.order_id' => $value->order_id ));
						$items = $n->result();
						if (!empty($items)) {
							$j = 0 ;
							foreach ($items as  $value1) {
								if($value1->order_status =='1' || $value1->order_status ==' ' ){
									$status = 'Quote' ;
								}elseif($value1->order_status == '2'){
									$status = 'Reservation' ;
								}elseif($value->order_status == '3'){
									$status = 'Contract' ;
								}elseif($value1->order_status == '4'){
									$status = 'Completed' ;
								}elseif($value1->order_status == '5'){
									$status = 'Cancelled' ;
								}elseif($value1->order_status == '6'){
									$status = 'Rejected' ;
								}elseif($value1->order_status == '7'){
									$status = 'Archived' ;
								}elseif ($value->order_status == '8') {
									$status = 'Expired';
								}
						
								if($value1->paymnet_status == ''){
									$paymnet_status = 'STORE';
								}elseif($value->paymnet_status == 'RECEIVED'){
									$paymnet_status = 'SETTLED';
								}else{
									$paymnet_status = $value1->paymnet_status;
								}
								$date_from = strtotime(date('d-m-Y',strtotime($value1->gear_rent_start_date)));
								$date_to = strtotime(date('d-m-Y',strtotime($value1->gear_rent_end_date)));
								$diff = abs($date_to - $date_from);
								$date_array =  $this->getDateList($date_from,$date_to);
								array_pop($date_array); 
								$insurance_days = count($date_array);
							 	if ($insurance_days == 0) {
							 		$insurance_days = 1;
							 	}
							 	array_shift($date_array);
								$shoot_days = count($date_array);
								if(empty($shoot_days)){
									$shoot_days = 1;
								}
								if($value->renter_rent_complete_date =='0000-00-00'){
									$renter_rent_complete_date = '';
								}else{
									$renter_rent_complete_date = $value1->renter_rent_complete_date;
								}

								$end_date =  date('d-m-Y',strtotime($value1->gear_rent_end_date)) ;
								$start_date =  date('d-m-Y',strtotime($value1->gear_rent_start_date)) ;

								$gear_data[] =  array(
											'Serial_no'=> $j+1,	
				 							'order_id' => $value1->order_id,
				 							'project_name'=> $value1->gear_name ,
				 							'owner_name'=>$value1->app_user_first_name .' ' .$value1->app_user_last_name,
				 							'buyer_name'=>$value1->buyer_first_name.' '.$value1->buyer_last_name,
				 							'start_date' => $start_date.' 14:00:00',
				 							'end_date' => $end_date .' 12:00:00',
				 							'requested_date'=> $value1->gear_rent_requested_on,
				 							'renter_rent_complete_date' =>$renter_rent_complete_date,
				 							'gear_total_rent_request_days'=> $value1->gear_total_rent_request_days,
											'total_shoot_days'=>$shoot_days,
				 							'insurance_days'=>$insurance_days,					 								
				 							'sub_total'=> number_format((float)$value1->total_rent_amount_ex_gst - $value1->beta_discount + $value1->community_fee + $value1->insurance_fee+ $value1->owner_insurance_amount, 2, '.', ''),
				 							'beta_discount'=>	number_format((float)$value1->beta_discount, 2, '.', ''),
				 							'insurance_fee'=>	number_format((float)$value1->insurance_fee, 2, '.', ''),
				 							'community_fee'=>	number_format((float)$value1->community_fee, 2, '.', ''),
				 							'owner_insurance_amount'=>	number_format((float)$value1->owner_insurance_amount, 2, '.', ''),
				 							'sub_total_ex_gst'=> number_format((float)$value1->total_rent_amount_ex_gst - $value1->beta_discount + $value1->community_fee + $value1->insurance_fee + $value1->owner_insurance_amount, 2, '.', ''),
				 							'gst_amount'=>	number_format((float)$value1->gst_amount, 2, '.', ''),
				 							'total_rent_amount'=> number_format((float)$value1->total_rent_amount_ex_gst - $value1->beta_discount + $value1->community_fee + $value1->insurance_fee + $value1->owner_insurance_amount + $value1->gst_amount, 2, '.', ''),
				 							'deposit_amount' =>	number_format((float)$value1->security_deposit, 2, '.', ''),
				 							'deposite_status'=> number_format((float)$value1->deposite_status, 2, '.', ''),
				 							'order_status'=>$status ,
				 							'payment_status'=>$paymnet_status,
				 							'Insurance_type'=>$value1->name ,
				 							'tier_type'=>$value1->tier_name ,
				 							'tiers_percentage'=>$value1->tiers_percentage,
				 					);

								$j++;
							}
						}
						$gear_data[] =  array(
											'Serial_no'=> '',	
				 							'order_id' => '',
				 							'project_name'=> '' ,
				 							'owner_name'=>'',
				 							'buyer_name'=>'',
				 							'start_date' =>'',
				 							'end_date' => '',
				 							'requested_date'=> '',
				 							'renter_rent_complete_date' =>'',
				 							'gear_total_rent_request_days'=> '',
											'total_shoot_days'=>'',
				 							'insurance_days'=>'',					 								
				 							'sub_total'=> '',
				 							'beta_discount'=> '' ,
				 							'insurance_fee'=>	'' ,
				 							'community_fee'=>	'',
				 							'owner_insurance_amount'=>'',
				 							'sub_total_ex_gst'=> '',
				 							'gst_amount'=>	'',
				 							'total_rent_amount'=> '',
				 							'deposit_amount' =>	'',
				 							'deposite_status'=>'',
				 							'order_status'=>'' ,
				 							'payment_status'=>'',
				 							'Insurance_type'=>'' ,
				 							'tier_type'=>''  ,
				 							'tiers_percentage'=> '' ,
				 					);
				// 		echo "<pre>";
				// print_r($gear_data);die;
				 					$i++;			
				} 
				
					 $this->excel->setActiveSheetIndex(0);
				//name the worksheet
				$this->excel->getActiveSheet()->setTitle('Orders Summmary List');
				$this->excel->getActiveSheet()->setCellValue('A1', 'Reference Number');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Order Id');
				$this->excel->getActiveSheet()->setCellValue('C1', 'Project Name');
				$this->excel->getActiveSheet()->setCellValue('D1', ' Owner Name ');
				$this->excel->getActiveSheet()->setCellValue('E1', 'Renter Name ');
				$this->excel->getActiveSheet()->setCellValue('F1', 'Rent Start Date');
				$this->excel->getActiveSheet()->setCellValue('G1', 'Rent End Date');
				$this->excel->getActiveSheet()->setCellValue('H1', 'Rent Requested Date');
				$this->excel->getActiveSheet()->setCellValue('I1', 'Owner Rent End Date');
				$this->excel->getActiveSheet()->setCellValue('J1', 'Rented Days');
				$this->excel->getActiveSheet()->setCellValue('K1', 'Total Shoot Days ');
				$this->excel->getActiveSheet()->setCellValue('L1', 'Total Insurance Days ');
				$this->excel->getActiveSheet()->setCellValue('M1', 'SubTotal');
				$this->excel->getActiveSheet()->setCellValue('N1', 'Discount');
				$this->excel->getActiveSheet()->setCellValue('O1', 'Insurance Fee');
				$this->excel->getActiveSheet()->setCellValue('P1', 'Community Fee');
				$this->excel->getActiveSheet()->setCellValue('Q1', 'Owner Insurance Fee');
				$this->excel->getActiveSheet()->setCellValue('R1', 'Total Amount Ex GST');
				$this->excel->getActiveSheet()->setCellValue('S1', 'GST Fee');
				
				$this->excel->getActiveSheet()->setCellValue('T1', ' Total Amount');
				$this->excel->getActiveSheet()->setCellValue('T1', 'Total inc GST');
				$this->excel->getActiveSheet()->setCellValue('U1', 'Deposit Amount');
				$this->excel->getActiveSheet()->setCellValue('V1', 'Deposit Status');
				$this->excel->getActiveSheet()->setCellValue('W1', 'Order Status');
				$this->excel->getActiveSheet()->setCellValue('X1', 'Payment Status');
				$this->excel->getActiveSheet()->setCellValue('Y1', 'Insurace Type');
				$this->excel->getActiveSheet()->setCellValue('Z1', 'Tier Type');
				$this->excel->getActiveSheet()->setCellValue('AA1', 'Tier percentage(%)');
				
				for($col = ord('A'); $col <= ord('AA'); $col++){ //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
					$this->excel->getActiveSheet()->getStyle(chr($col)."1")->getFont()->setBold(true);
					$this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
				}


				$this->excel->getActiveSheet()->fromArray($gear_data, null, 'A4');
				$filename='OrderSummary'.$calender_date.'.xls'; //save our workbook as this file name
				header('Content-Type: application/vnd.ms-excel'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0');
				//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
				//if you want to save it as .XLSX Excel 2007 format
				$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
				//force user to download the Excel file without writing it to server's HD
				$objWriter->save('php://output');
			}else{

				$message = '<div class="alert alert-danger"><p>No  Listing Ordered During the period .</p></div>';
				$this->session->set_flashdata('success', $message);
				redirect('Orders');
			}
		
			// echo "<pre>";
			// print_r($gear_data);
	}

	public function GetRentDays($date_from,$date_to)
	{
		
		$total_days = '' ;
        $count = '';
        $count1 = '';
		$diff = abs($date_to -$date_from);
        $days = floor(($diff)/ (60*60*24));
        if ($days > 1) {
           $days =   $days +1-2 ; 
        }else{
             $days =   1;
        }
        $tola_days_reamining =   $days%7 ;  
        $tola_week =  floor( $days/7) ;
        if  ($tola_week > 0 ){
             $total_days = $tola_week*3 ;
        }
          if ($tola_days_reamining  > 1 && $tola_days_reamining <= 6  ) {
            for ($i=1; $i <= $tola_days_reamining ; $i++) { 
                $days1[] =   date('D', strtotime('+'.$i.' day', $date_from));
            }
            $count = 0;
            $count1 = 0;
            foreach ($days1 as $value) {
                if ($value == 'Sat'  || $value == 'Sun'  ) {
                    $count1 += 1; 
                }else{
                    $count += 1;
                    if($count >= 3)
                        break;
                }
            }
            $next_day =  date('D', strtotime('+1 day', strtotime($date_from)));
             if ( $count1 >=1 && $count < 3) {
                 $count = $count + 1 ;
             }
             return $count+ $total_days  ;    

        }elseif($tola_days_reamining == '1'  && $tola_week ==  0 || $tola_days_reamining == '0'  &&  $tola_week ==  0 ){
            $count = 1 ;
        }elseif($tola_days_reamining == '1' &&  $tola_week  > 0     ){
            $count = 1 ;
        }
        return  $count+ $total_days  ;       
	}
	public function getDateList($date_from,$date_to)
	{
		
			$dates = array();
			while($date_from <= $date_to)
			{
				$values= date( 'Y-m-d',$date_from) ;
						
					

			    array_push( $dates,$values);
			    $date_from += 86400;
			}
			return $dates ;
	}
	public function IssueList($order_id)
	{
			$data['result'] = $this->model->getOrderIssueList($order_id);
			$data['issuelist'] = $this->model->getOrderCheckList($order_id);
			// $query = $this->common_model->GetAllWhere('ks_order_checklist', array('order_id' => $order_id ));
			// $data['issuelist'] =$query->result();
			

			$data['total_rows'] =count($data['result']);
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('orders/issuelist',$data);
			$this->load->view('common/footer');
	}

	public function Edit($order_id)
	{
		// print_r($order_id);
		$query=  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('order_id'=>$order_id));
		$data['user_data'] =  $query->result();
		// echo "<pre>";
			// print_r($user_data);


		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('orders/edit', $data);
		$this->load->view('common/footer');		

	}

	public function updateDate()
	{
		$data =  $this->input->post();
		
		$this->db->where('order_id', $data['order_id']);

		$update_data = array(
								"gear_rent_requested_on"=>$data['app_username']
							);	

		$this->db->update('ks_user_gear_rent_details', $update_data); 

		$insert_data  = array('type'=> 'Admin Updated Datetime' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$data['order_id'] , 'status'=>'Admin Updated Datetime for quote');
		$this->common_model->addRecord('ks_crone_log',$insert_data);
		// $this->common_model->addRecord('ks_users',$data);

		 redirect('orders');
	}

	public function GetLogDetails()
	{
		$data =  $this->input->post();
		$where = array('order_id'=>$data['order_id']);

		$query = $this->common_model->GetAllWhere('ks_crone_log',$where);
		$result= $query->result();
		if (empty($result)) {
			$response['status']= '0';
			$response['message']= '<div class="alert alert-danger"><p>No Log Found. </p></div>';
		}else{

			$html =  '<table class="table">
			              <thead>
			                <tr>
			                  <th>Date</th>
			                  <th>Status</th>
			                  <th>Orders Id</th>
			                </tr>
			              </thead>
			              <tbody>';

			foreach ($result as  $value) {
								
					

				$html .= ' <tr>
			                  <td>'.$value->date_time.'</td>
			                  <td>'.$value->status.'</td>
			                  <td>'.$value->order_id.'</td>
			                </tr>';


			}              
			    $html .=  '  </tbody>
			            </table>';
			$response['status']= '1';
			$response['data']= $html;
			$response['message']= '';
		}

		echo json_encode($response ,true);


	}
}?>