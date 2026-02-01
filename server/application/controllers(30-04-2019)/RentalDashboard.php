<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class RentalDashboard extends CI_Controller {
	
	 public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email','upload','pagination'));
		$this->load->model(array('common_model','home_model','mail_model'));
		}
	

	public function index()
	{
		echo CI_VERSION;
	}
	public function Reference()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		 $app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			
			$reference =  $this->home_model->GetAllRefernce($app_user_id) ; 
			//$reference =  $query->result_array();
			//print_r($reference);
			if (!empty($reference)) {
				$response=array("status"=>200,
						"status_message"=>"success",
						"result"=>$reference);
				echo json_encode($response);
				exit();
			}else{
				$response=array("status"=>200,
						"status_message"=>"success",
						"result"=>$reference);
				echo json_encode($response);
				exit();
			}
		}else{

			header('HTTP/1.1 404 Page Not Found');
			$response['status_code'] = 404;
			$response['status_message'] = "No gear found";
			echo json_encode($response);
			
		}
	}

	public function Reviews()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {

				$reviews =  $this->home_model->GetAllReview($app_user_id) ; 
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'Reviews list';
					$response['result'] = $reviews;
					$json_response = json_encode($response);
					echo $json_response;
					
					exit();
			

		}else{

					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();

		}	
	}

	public function ManageListing()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$where = '';
			if (isset($post_data['gear_name']) && $post_data['gear_name'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND a.gear_name LIKE '%".trim($post_data['gear_name'])."%' ";	
				}else{
					$where .= " a.gear_name LIKE '%".trim($post_data['gear_name'])."%' ";	
				}
			
			}
			if (isset($post_data['is_active']) && $post_data['is_active'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND a.is_active = '".$post_data['is_active']."' ";	
				}else{
					$where .= " a.is_active = '".$post_data['is_active']."' ";	
				}
				
			}
			if (isset($post_data['gear_hide_search_results']) && $post_data['gear_hide_search_results'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND a.gear_hide_search_results = '".$post_data['gear_hide_search_results']."' ";	
				}else{
					$where .= " a.gear_hide_search_results = '".$post_data['gear_hide_search_results']."' ";	
				}
				
			}
			if($this->input->get("per_page")!= '')
			{
				$offset = $this->input->get("per_page");
			}
			else
			{
				$offset=0;
			}
			if($this->input->get("limit")!= '')
			{
				$limit = $this->input->get("limit");
			}
			else
			{
				$limit=10;
			}

			
			 $limitype['limit'] = $limit ;
			 $limitype['offset'] = $offset;
			$gears=$this->home_model->GearCategories($app_user_id,$where,$limit,$offset);
			$gears1=$this->home_model->GearCategories($app_user_id,$where);
			
			$config['base_url'] = base_url()."RentalDashboard/ManageListing?app_user_id=".$app_user_id."&limit=".$limit;
		$gears['total_rows']=count($gears1['gear_lists']);
		$gears['limit']=$limit;
		$config['total_rows'] = count($gears1['gear_lists']);;
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
		$gears['paginator'] = $paginator; 
			$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'ManageListing  ';
					$response['result'] = $gears;
					$json_response = json_encode($response);
					echo $json_response;
					
					exit();

			

		}else{

					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();

		}	
	}

	public function AddUnavailbleDates ()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			
		$query=	$this->common_model->GetAllWhere('ks_user_gear_description' , array('user_gear_desc_id'=>$post_data['user_gear_desc_id']));
		$gearcheck = $query->result_array();
		
		if (empty($gearcheck)) {
					$app_user_id = '';
					$response['status'] = 400;
					$response['status_message'] = 'Gear not found';
					$json_response = json_encode($response);
					echo $json_response;
					
					exit();		
		}else{

				$insert_data=  array('user_gear_description_id'=> $post_data['user_gear_desc_id'],
										'unavailable_from_date'=> $post_data['date_from'],
										'unavailable_to_date'=> $post_data['date_to'],
										'create_user'=>$app_user_id,
										'create_date'=>date('Y-m-d')
									);
			$insert_id =  	$this->common_model->AddRecord($insert_data , 'ks_gear_unavailable');
			if ($insert_id > 0 ) {
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'Unavailable Date Added successfully';
					$json_response = json_encode($response);
					echo $json_response;
					
					exit();	
			}else{
				$app_user_id = '';
					$response['status'] = 400;
					$response['status_message'] = 'Something went wrong';
					$json_response = json_encode($response);
					echo $json_response;
					
					exit();	
			}			
		}

		}else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();		
		}	
	}

	public function DeactiveGear()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			
			$query=	$this->common_model->GetAllWhere('ks_user_gear_description' , array('user_gear_desc_id'=>$post_data['user_gear_desc_id']));
			$gearcheck = $query->result_array();
		
			if (empty($gearcheck)) {
						$app_user_id = '';
						$response['status'] = 400;
						$response['status_message'] = 'Gear not found';
						$json_response = json_encode($response);
						echo $json_response;
						
						exit();		
			}else{

					$insert_data=  array('is_active'=>'N',
											
										);
					$insert_id =  	$this->common_model->UpdateRecord($insert_data , 'ks_user_gear_description' ,'user_gear_desc_id' , $post_data['user_gear_desc_id']);
					
							$app_user_id = '';
							$response['status'] = 200;
							$response['status_message'] = 'Gear Deactivated  successfully';
							$json_response = json_encode($response);
							echo $json_response;
							
							exit();	
							
			}

		}else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();		
		}	
	}

	public function GearPublicPrivate()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			
			$query=	$this->common_model->GetAllWhere('ks_user_gear_description' , array('user_gear_desc_id'=>$post_data['user_gear_desc_id']));
			$gearcheck = $query->result_array();
		
			if (empty($gearcheck)) {
						$app_user_id = '';
						$response['status'] = 400;
						$response['status_message'] = 'Gear not found';
						$json_response = json_encode($response);
						echo $json_response;
						
						exit();		
			}else{


					$insert_data=  array(
						'gear_hide_search_results'=>$post_data['status'],
						'gear_list_delist_flag'=>$post_data['status'],
											
										);

					if($post_data['status'] == 'Y'){
							$insert_data['gear_listing_date']=date('Y-m-d') ;
					}else{
							$insert_data['gear_delisting_date']=date('Y-m-d') ;
					}
					$insert_id =  	$this->common_model->UpdateRecord($insert_data , 'ks_user_gear_description' ,'user_gear_desc_id' , $post_data['user_gear_desc_id']);
					
							$app_user_id = '';
							$response['status'] = 200;
							$response['status_message'] = 'Gear Status  successfully';
							$json_response = json_encode($response);
							echo $json_response;
							
							exit();	
							
			}

		}else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();		
		}	
	}

	public function DeleteGear()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			
			$query=	$this->common_model->GetAllWhere('ks_user_gear_description' , array('user_gear_desc_id'=>$post_data['user_gear_desc_id']));
			$gearcheck = $query->result_array();
		
			if (empty($gearcheck)) {
						$app_user_id = '';
						$response['status'] = 400;
						$response['status_message'] = 'Gear not found';
						$json_response = json_encode($response);
						echo $json_response;
						
						exit();		
			}else{

					$insert_data=  array('is_deleted'=>'Yes',
											
										);
					$insert_id =  	$this->common_model->UpdateRecord($insert_data , 'ks_user_gear_description' ,'user_gear_desc_id' , $post_data['user_gear_desc_id']);
					
							$app_user_id = '';
							$response['status'] = 200;
							$response['status_message'] = 'Gear deleted  successfully';
							$json_response = json_encode($response);
							echo $json_response;
							
							exit();	
							
			}

		}else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();		
		}	
	}

	// Owner List
	public function OwnerList($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			
			$where = "";
		if (isset($post_data['is_completed']) && $post_data['is_completed'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND rent_details.order_status	=  '4' ";	
				}else{
					$where .= " rent_details.order_status	=  '4'  ";	
				}
			
			}
			if (isset($post_data['is_rent_cancelled']) && $post_data['is_rent_cancelled'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND rent_details.order_status	=  '5' ";	
				}else{
					$where .= " rent_details.order_status	=  '5'  ";	
				}
			
			}
			if (isset($post_data['is_rent_rejected']) && $post_data['is_rent_rejected'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND rent_details.order_status	=  '6' ";	
				}else{
					$where .= " rent_details.order_status	=  '6'  ";	
				}
			
			}
			if (isset($post_data['is_rent_approved']) && $post_data['is_rent_approved'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND (rent_details.order_status	=  '3'  OR rent_details.order_status = 2 OR  rent_details.order_status =1) ";	
				}else{
					$where .= "(rent_details.order_status	=  '3'  OR rent_details.order_status = 2 OR  rent_details.order_status =1)   ";	
				}
			
			}
			if (isset($post_data['is_achive']) && $post_data['is_achive'] !='') {
				//	print_r($post_data['ks_gear_id']);
				$current_date = date('Y-m-d');
				$days_ago = date('Y-m-d', strtotime('-25 days', strtotime($current_date)));
				//echo $days_ago ;die;
				if ($where != '') {
					$where .= " AND DATE(rent_details.create_date)	<  '".$days_ago."' ";	
				}else{
					$where .= " DATE(rent_details.create_date)	<  '".$days_ago."'  ";	
				}
			
			}
			
			if (isset($post_data['Date']) && $post_data['Date'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."' ";	
				}else{
					$where .= " Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."'  ";	
				}
			
			}
			if(empty($where)){
				$where .= "  rent_details.order_status	!=  '7'   ";
			}
			$query =  $this->home_model->getMyorderList($app_user_id,$where); 

			$order_list	   =  $query->result_array();
			// echo "<pre>";
			// print_r($order_list);
			// die;
				$date_array= array();
			if (!empty($order_list)) {
					$i=0 ;
				foreach ($order_list as   $value) {
					$sql = " SELECT * FROM  ks_gear_unavailable WHERE  user_gear_description_id = '".$value['user_gear_desc_id']."' AND    date(unavailable_from_date) >= '".date('Y-m-d')."'     AND  date(unavailable_to_date) <= '".date('Y-m-d')."'   " ;
					$query = $this->db->query($sql);
					$values =  $query->result();
					if (!empty($values)) {
						$order_list[$i]['is_setting'] = '1';
					}else{
						$order_list[$i]['is_setting'] = '0';
					}
					if ($value['order_status'] =='2') {
						$order_list[$i]['order_status'] = 'Reservation';
					}elseif ($value['order_status'] =='3') {
						$order_list[$i]['order_status'] = 'Contract';
					}elseif ($value['order_status'] =='4') {
						$order_list[$i]['order_status'] = 'Completed';
					}elseif ($value['order_status'] =='5') {
						$order_list[$i]['order_status'] = 'Cancelled';
					}elseif ($value['order_status'] =='6') {
						$order_list[$i]['order_status'] = 'Declined';
					}elseif ($value['order_status'] =='7') {
						$order_list[$i]['order_status'] = 'Archived';
					}else{
						$order_list[$i]['order_status'] = 'Quote';
					}
					if ($value['is_payment_completed'] == 'Y') {
						$order_list[$i]['payment_status'] = 'Completed';
					}else{
						$order_list[$i]['payment_status'] = 'Pending';
					}
					
						// $query = $this->common_model->GetAllWhere('ks_user_gear_rent_details',array('order_id'=>$value['order_id']));
						// $order_data =  $query->result();
						// print_r($order_data);
					$this->db->select('SUM(total_rent_amount) AS  total_rent_amount , SUM(security_deposit)  AS security_deposit');
					$this->db->where('order_id',$value['order_id']);
					$this->db->from('ks_user_gear_rent_details');
					$query = $this->db->get();
					$order_itme=  $query->row();
					$order_list[$i]['total_rent_amount'] = $order_itme->total_rent_amount;
					$order_list[$i]['security_deposit'] = $order_itme->security_deposit;


					$sql1 = "SELECT app_user_id FROM ks_user_gear_description WHERE user_gear_desc_id = '".$value['user_gear_desc_id']."'  ";
					$query = $this->db->query($sql1);
					$values1 =  $query->row();
					if (!empty($values1)) {
							$order_list[$i]['renter_id'] = $value['create_user'];
					}else{
						$order_list[$i]['renter_id'] = '';
					}

					$date_array[] = date('Y', strtotime($value['gear_rent_requested_on']));				
					$i++;

				}
				# code...
			}
			$values = array();
			if (empty($date_array)) {
				$values = array();
			}else{
				foreach ($date_array as  $value) {
					if (empty($values)) {
						$values[] = $value;
					}else{
						if(in_array( $value, $values)){
						}else{
							$values[] = $value;
						}
					}
				}
			}
			
			//$date_array = array_map("unserialize", array_unique(array_map("serialize", $date_array)));
		//	echo  $this->db->last_query();die;
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Gear list';
				$response['result'] = array('order_list' => $order_list,'date_array'=> $values); ;
				$json_response = json_encode($response);
				echo $json_response;
				
				exit();	
		}else{
			$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Alreday  Logged In';
			$json_response = json_encode($response);
			echo $json_response;
			header('HTTP/1.1 401 Unauthorized');
			exit();		

		}	
	}

	public function   RenterList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
				$where = '';
			if (isset($post_data['is_completed']) && $post_data['is_completed'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND rent_details.order_status	=  '4' ";	
				}else{
					$where .= " rent_details.order_status	=  '4'  ";	
				}
			
			}
			if (isset($post_data['is_rent_cancelled']) && $post_data['is_rent_cancelled'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND rent_details.order_status	=  '5' ";	
				}else{
					$where .= " rent_details.order_status	=  '5'  ";	
				}
			
			}
			if (isset($post_data['is_rent_rejected']) && $post_data['is_rent_rejected'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND rent_details.order_status	=  '6' ";	
				}else{
					$where .= " rent_details.order_status	=  '6'  ";	
				}
			
			}
			if (isset($post_data['is_rent_approved']) && $post_data['is_rent_approved'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND (rent_details.order_status	=  '3'  OR rent_details.order_status = 2 OR  rent_details.order_status =1) ";	
				}else{
					$where .= "(rent_details.order_status	=  '3'  OR rent_details.order_status = 2 OR  rent_details.order_status =1)   ";	
				}
			
			}
			if (isset($post_data['Date']) && $post_data['Date'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."' ";	
				}else{
					$where .= " Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."'  ";	
				}
			
			}
			if (isset($post_data['is_achive']) && $post_data['is_achive'] !='') {
				//	print_r($post_data['ks_gear_id']);
				if ($where != '') {
					$where .= " AND rent_details.order_status	=  '7'  ";	
				}else{
					$where .= " rent_details.order_status	=  '7'   ";
				}
			
			}

			if(empty($where)){
				$where .= "  rent_details.order_status	!=  '7'   ";
			}
			
			$query =  $this->home_model->getRequestedorderList($app_user_id,$where); 
			$order_list	   =  $query->result_array();
			$date_array =array();
			if (!empty($order_list)) {
					$i=0 ;
				foreach ($order_list as   $value) {
					$sql = " SELECT * FROM  ks_gear_unavailable WHERE  user_gear_description_id = '".$value['user_gear_desc_id']."' AND    date(unavailable_from_date) >= '".date('Y-m-d')."'     AND  date(unavailable_to_date) <= '".date('Y-m-d')."'   " ;
					$query = $this->db->query($sql);
					$values =  $query->result();
					if (!empty($values)) {
						$order_list[$i]['is_setting'] = '1';
					}else{
						$order_list[$i]['is_setting'] = '0';
					}
					if ($value['order_status'] ==2) {
						$order_list[$i]['order_status'] = 'Reservation';
					}elseif ($value['order_status'] =='3') {
						$order_list[$i]['order_status'] = 'Contract';
					}elseif ($value['order_status'] =='4') {
						$order_list[$i]['order_status'] = 'Completed';
					}elseif ($value['order_status'] =='5') {
						$order_list[$i]['order_status'] = 'Cancelled';
					}elseif ($value['order_status'] =='6') {
						$order_list[$i]['order_status'] = 'Declined';
					}elseif ($value['order_status'] =='7') {
						$order_list[$i]['order_status'] = 'Archived';
					}else{
						$order_list[$i]['order_status'] = 'Quote';
					}
						
					if ($value['is_payment_completed'] == 'Y') {
						$order_list[$i]['payment_status'] = 'Completed';
					}else{
						$order_list[$i]['payment_status'] = 'Pending';
					}
							// print_r($order_data);
					$this->db->select('SUM(total_rent_amount) AS  total_rent_amount , SUM(security_deposit)  AS security_deposit');
					$this->db->where('order_id',$value['order_id']);
					$this->db->from('ks_user_gear_rent_details');
					$query = $this->db->get();
					$order_itme=  $query->row();
					$order_list[$i]['total_rent_amount'] = $order_itme->total_rent_amount;
					$order_list[$i]['security_deposit'] = $order_itme->security_deposit;

					$date_array[] = date('Y' ,strtotime($value['gear_rent_requested_on']));
					$order_list[$i]['renter_id'] = $value['app_user_id'];
					$i++;
				}
				# code...
			}
			
			$values = array();
			if (empty($date_array)) {
				$values = array();
			}else{
				foreach ($date_array as  $value) {
					if (empty($values)) {
						$values[] = $value;
					}else{
						if(in_array( $value, $values)){
						}else{
							$values[] = $value;
						}
					}
				}
			}
			
			//print_r($order_list);die;
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Gear rented List';
				$response['result'] = array('order_list'=>$order_list ,  'date_array'=>$values) ;
				$json_response = json_encode($response);
				echo $json_response;
				exit();	
		}else{
			$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Alreday  Logged In';
			$json_response = json_encode($response);
			echo $json_response;
			header('HTTP/1.1 401 Unauthorized');
			exit();		

		}	
	}

	// GET GEAR ADDRESS 

	public function GearAddressList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {

			$user_gear_desc_id = $post_data['user_gear_desc_id'] ;
				$table = 'ks_user_address';
			$where_clause = array('app_user_id'=> $app_user_id);
			$data = $this->common_model->get_all_record($table,$where_clause);
			if (count($data) > 0) {
				$data_value = ''; 
				 foreach ($data as $value) {
					 $a = array(
						'user_address_id'=>$value->user_address_id,
						'street_address_line1'=>$value->street_address_line1,
						'street_address_line2'=>$value->street_address_line2,
						'ks_state_id'=>$value->ks_state_id,
						'ks_country_id'=>$value->ks_country_id,
						'ks_suburb_id'=>$value->ks_suburb_id,
						'postcode'=>$value->postcode,
						) ;
					$data_value[] = $a; 
				 }
				
			}
			$sql = "SELECT user_address_id FROM ks_gear_location WHERE user_gear_desc_id = '".$user_gear_desc_id."'  "; 
			$addrrss_data = $this->common_model->get_records_from_sql( $sql);		 
			if (empty($addrrss_data)) {
				$app_user_id = '';
			$response['status'] = 200;
			$response['status_message'] = 'No Address Present';
			$json_response = json_encode($response);
			echo $json_response;
			}else{
				foreach ($addrrss_data as $value) {
					 $addres[] = $value->user_address_id ;
				}

				$i=0;
				foreach ($data_value as  $value) {
					if(in_array($value['user_address_id'],$addres )){
						$data_value[$i]['is_gear_address']= 'Y';
					}else{
						$data_value[$i]['is_gear_address']= 'N';
					}
					$i++;
				}
			}	
			$app_user_id = '';
			$response['status'] = 200;
			$response['status_message'] = 'Gear Address List';
			$response['result'] = $data_value;
			$json_response = json_encode($response);
			echo $json_response;
		}else{
			$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Alreday  Logged In';
			$json_response = json_encode($response);
			echo $json_response;
			header('HTTP/1.1 401 Unauthorized');
			exit();	
		}	
	}

	//REmove Gear Address 


	public function RemoveGearAddress()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {

			$user_gear_desc_id = $post_data['user_gear_desc_id'] ;
			$user_address_id = $post_data['user_address_id'] ;
				$table = 'ks_gear_location';
			$where_clause = array(
					'user_address_id'=> $user_address_id,
					'user_gear_desc_id'=> $user_gear_desc_id
				);
			$data = $this->common_model->get_all_record($table,$where_clause);
			if (!empty($data)) {
				$this->db->where('ks_gear_location_id',$data[0]->ks_gear_location_id);
				$this->db->delete('ks_gear_location');
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Gear Deleted successfully';
				$json_response = json_encode($response);
				echo $json_response;
			}else{
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'No Address Present';
				$json_response = json_encode($response);
				echo $json_response;
			}


		}else{
			$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Alreday  Logged In';
			$json_response = json_encode($response);
			echo $json_response;
			header('HTTP/1.1 401 Unauthorized');
			exit();	
		}	
	}

	public function AddGearAddress()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			 $user_gear_desc_id_array = explode(',', $post_data['user_gear_desc_id']) ;
			 $user_address_id = $post_data['user_address_id'] ;
			 foreach ($user_gear_desc_id_array as  $value) { 
			 	$user_gear_desc_id = $value ;
			 	$query = $this->common_model->GetAllWhere('ks_user_gear_description', array('user_gear_desc_id'=>$user_gear_desc_id));
					$gear_check = $query->row();
					if (!empty($gear_check)) {
						$query = $this->common_model->GetAllWhere('ks_user_address', array('user_address_id'=>$user_address_id));	
						$address_check = $query->row();
						if (!empty($address_check)) {
							$query = $this->common_model->GetAllWhere('ks_gear_location',array('user_gear_desc_id'=>$user_gear_desc_id ,'user_address_id'=>$user_address_id));
							$gear_location_check =$query->row();
							if (empty($gear_location_check)) {
									$insert_data=array(
														'user_gear_desc_id'=>$user_gear_desc_id,
														'user_address_id'=>$user_address_id,
														'is_active'=>'Y',
														'create_user'=>$app_user_id,
														'create_date'=>date('Y-m-d'),
														
													);
									$this->common_model->InsertData( 'ks_gear_location' ,$insert_data);
									
							}
						}
			 }
								
			}
			$app_user_id = '';
									$response['status'] = 200;
									$response['status_message'] = 'Address Assigned to gear successfully  ';
									$json_response = json_encode($response);
									echo $json_response;
									exit();
		}else{
				$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Alreday  Logged In';
			$json_response = json_encode($response);
			echo $json_response;
			header('HTTP/1.1 401 Unauthorized');
			exit();	
		}	

	}
	// Invoice 
	public function OrderInvoice()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {

			$user_gear_desc_id = $post_data['user_gear_desc_id'] ;
			$query = $this->common_model->GetAllWhere('ks_user_gear_description', array('user_gear_desc_id'=>$user_gear_desc_id));
			$gear_check = $query->row();

			 $url =  BASE_URL."site_upload/invocies/invoice-template-pdf-generic.pdf" ; 
			$app_user_id = '';
			$response['status'] = 200;
			$response['status_message'] = 'Invocie URL';
			$response['result'] = $url;
			$json_response = json_encode($response);
			echo $json_response;
		}else{
			$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Alreday  Logged In';
			$json_response = json_encode($response);
			echo $json_response;
			header('HTTP/1.1 401 Unauthorized');
			exit();	
		}	
	}
	
	function userinfo($token){
		if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
     		exit();
		}
		$token_decrypt = base64_decode($token);
		$token_array = explode("|",$token_decrypt);
		
		
		$email = $token_array[0];
		$secret_key = $token_array[1];
		$expire_time = $token_array[2];	
	
		$columns = "app_user_id";
		$table = "ks_users";
		$where_clause = array('primary_email_address'=>$email,'auth_secret_key'=>$secret_key,'expire_time'=>$expire_time);
		
		$query = $this->common_model->GetSpecificValues($columns,$table,$where_clause);
		$row = $query->result_array();
		if($query->num_rows()>0){
					$row = $query->result_array();
					
					$app_user_id = $row[0]['app_user_id'];
					
					return $app_user_id;
		}else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
		}

		
	}
	//Add reference User

	public function AddRefernce()
	{
		
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$email_address_list = $post_data['email_address_list'];
			$message = $post_data['message'];
			
			$email_array = explode(',', $email_address_list);
			foreach ($email_array as $email) {
				$to =$email;
				$six_digit_random_number = mt_rand(100000, 999999);
				$name = ucwords('Gourav');
				$time  =time();
				$sender_mail= 'noreply@inferasolz.com';
				$url = "http://inferasolz.com/kitshare/login?reference_code=".urlencode($six_digit_random_number)."&key=".md5($time);
				
				////////////// get mail templete from mail_model
				$mail_body = $this->mail_model->Reference_mail($message,$url);
				$subject = 'Welcome to Kitshare';
				$this->home_model->send_email($sender_mail,$to,$subject,$mail_body);
				//header('HTTP/1.1 200 OK');
				$insert_data = array(
								'app_user_id'=> $app_user_id,
								'ks_user_reference_email'=>$email,
								'ks_user_reference_msg'=>$message,
								'reference_code'=>$six_digit_random_number,
								'create_user'=>$app_user_id,
								'create_date'=>date('Y-m-d'),

							);
				$this->common_model->InsertData('ks_user_reference',$insert_data);
			}
				$response['status'] = 200;
				$response['status_message'] = ' Mail Send Successfully';
				$json_response = json_encode($response);
				echo $json_response; 			
					
		}else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
		}

	}
	public function AddRefernceReview()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {

			 $query = $this->common_model->GetAllWhere('ks_user_ref_comment',array('create_user'=>$app_user_id ,'app_user_id' =>$post_data['user_id'] ));
			 $check_commnet = $query->row();
			 if (empty($check_commnet)) {
			 		 $insert_data['ks_user_ref_comment_msg'] = $post_data['message'];
					 $insert_data['app_user_id'] = $post_data['user_id'];
					 $insert_data['is_approved'] = 'N';
					 $insert_data['create_user'] = $app_user_id;
					 $insert_data['create_date'] = date('Y-m-d');
					 $this->common_model->InsertData('ks_user_ref_comment',$insert_data);
					 $response['status'] = 200;
					 $response['status_message'] = ' Successfully Comment posted';
					 $json_response = json_encode($response);
					 echo $json_response; 
			 }else{
					$response['status'] = 200;
					$response['status_message'] = ' Alreday  Comment Provided for this User';
					$json_response = json_encode($response);
					echo $json_response; 	
			 }
		}else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
		}	
	}

	public function ReferenceApporove()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$ks_user_ref_comment_id =  $post_data['ks_user_ref_comment_id'];
			  	$this->common_model->UpdateRecord(array('is_approved'=>'Y'), 'ks_user_ref_comment' ,'ks_user_ref_comment_id' , $post_data['ks_user_ref_comment_id']);
			 $response['status'] = 200;
			 $response['status_message'] = ' Status Appoved Successfully';
			 $json_response = json_encode($response);
			 echo $json_response; 			

		 }else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
		}	
	}

}?>
