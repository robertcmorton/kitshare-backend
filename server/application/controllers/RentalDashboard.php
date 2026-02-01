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
		
		if(is_array($post_data) && count($post_data)>0 ){
		
			if (array_key_exists('token',$post_data)) {
				
				$token 			= $post_data['token'];
				$app_user_id = $this->userinfo($token);
				
				if($app_user_id!=""){

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
					
					header('HTTP/1.1 400 Session Expired');
					exit();
					
				}
			}else{

				//header('HTTP/1.1 404 Page Not Found');
				/*$response['status_code'] = 404;
				$response['status_message'] = "No gear found";
				echo json_encode($response);*/
				header('HTTP/1.1 400 Session Expired');
				exit();

			}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	public function Reviews()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
			if (array_key_exists('token',$post_data)) {
				
					$token 			= $post_data['token'];
					$app_user_id = $this->userinfo($token);
					
					if($app_user_id!=""){

						$reviews =  $this->home_model->GetAllReview($app_user_id);
						
						if(count($reviews)>0){
				
							$i=0;
							foreach($reviews as $review_result){	
									
									//Checked the owner of the product
									$user_id = $this->home_model->gearOwner($review_result['order_id']);
									
									if($user_id == $review_result['create_user'])
										$user_type = "Owner";
									else
										$user_type = "Renter";
									
									$reviews[$i]['user_type'] = $user_type;  
									
									$i++;
							}
							
						}
						
						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Reviews list';
						$response['result'] = $reviews;
						$json_response = json_encode($response);
						echo $json_response;

						exit();
						
					}else{
						
						header('HTTP/1.1 400 Session Expired');
						exit();
					}


			}else{

						/*$app_user_id = '';
						$response['status'] = 401;
						$response['status_message'] = 'User Already logged in';
						$json_response = json_encode($response);
						echo $json_response;*/
						
						header('HTTP/1.1 400 Session Expired');
						exit();

			}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}

	public function ManageListing()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){
			
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
				
				header('HTTP/1.1 400 Session Expired');
				exit();
			}



		}else{

					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();

		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	public function AddUnavailbleDates ()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		
		if (array_key_exists('token',$post_data)) {

			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){

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
				
				header('HTTP/1.1 400 Session Expired');
				exit();
			}

		}else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();
		}
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	public function DeactiveGear()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){

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
				
				header('HTTP/1.1 400 Session Expired');
				exit();
			}

		}else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();
		}
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	public function GearPublicPrivate()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){

		
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);

			if($app_user_id!=""){
			
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

						//Transaction begins
						$this->db->trans_begin();						
						
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
						
						$this->common_model->UpdateRecord($insert_data , 'ks_user_gear_search' ,'user_gear_desc_id' , $post_data['user_gear_desc_id']);
						
						///Transaction ends here
						if ($this->db->trans_status() === FALSE)
						{	
							
							$this->db->trans_rollback();
							
							$response['status'] = 400;
							$response['status_code']='failed';
							$response['status_message'] = 'Something went wrong! Please try again.';
							
						}else{
							
							$this->db->trans_commit();
							$app_user_id = '';
							$response['status'] = 200;
							$response['status_code']='success';
							$response['status_message'] = 'Gear Status successfully';
							
						}

						$json_response = json_encode($response);
						echo $json_response;
						exit();

				}
			}else{
				
				header('HTTP/1.1 400 Session Expired');
				exit();
			}

		}else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();
		}
		
		}else{
			header('HTTP/1.1 200 Success');
			exit();	
			
		}
	}

	public function DeleteGear()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){

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
						
						//Transaction begins here
						$this->db->trans_begin();						
						
						$insert_data=  array('is_deleted'=>'Yes',
											'is_active'=>'N',
											);
						$insert_id =  	$this->common_model->UpdateRecord($insert_data , 'ks_user_gear_description' ,'user_gear_desc_id' , $post_data['user_gear_desc_id']);
						
						$this->common_model->UpdateRecord($insert_data , 'ks_user_gear_search' ,'user_gear_desc_id' , $post_data['user_gear_desc_id']);
						
						$insert_data=  array('is_active'=>'N');
						
						$this->common_model->UpdateRecord($insert_data , 'ks_gear_location' ,'user_gear_desc_id' , $post_data['user_gear_desc_id']);
						
						if ($this->db->trans_status() === FALSE)
						{
								$this->db->trans_rollback();
								
								$response['status'] = 400;
								$response['status_code']='failed';
								$response['status_message'] = 'Something went wrong! Please try again.';
						}
						else
						{
								$this->db->trans_commit();
								$app_user_id = '';
								$response['status'] = 200;
								$response['status_code']='success';
								$response['status_message'] = 'Gear deleted  successfully';
						}

						
						$json_response = json_encode($response);
						echo $json_response;
						exit();

				}
				
			}else{
				
				header('HTTP/1.1 400 Session Expired');
				exit();
				
			}

		}else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();
		}
		}else{
			
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}

	// Owner List
	public function OwnerList($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){

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
					$where .= " AND (rent_details.order_status	=  '5'  OR  rent_details.order_status	=  '8')  ";
				}else{
					$where .= " (rent_details.order_status	=  '5'   OR  rent_details.order_status	=  '8' ) ";
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
				$days_ago = date('Y-m-d', strtotime('-6 days', strtotime($current_date)));
				//echo $days_ago ;die;
				if ($where != '') {
					$where .= " AND (rent_details.order_status	=  '7'  ) ";
				}else{
					$where .= "  (rent_details.order_status	=  '7'  ) ";
				}

			}

			if(isset($post_data['Date']) && $post_data['Date']!="All" && $post_data['Date']!=''){
				
				
				if(!array_key_exists("is_completed",$post_data) && !array_key_exists("is_rent_cancelled",$post_data) && !array_key_exists("is_rent_rejected",$post_data) && !array_key_exists("is_rent_approved",$post_data) && !array_key_exists("is_achive",$post_data)){
					
					$where .= " Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."'  ";
					
				}else{
				
					if(empty($where)){
						
						$where .= " Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."'  ";
						
					}else{
						
						$where .= " AND Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."'  ";
						
					}
				}
				
			}
			
			if(empty($where)){
				$where .= "  rent_details.order_status	!=  '7'   ";
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
			
			//Offset is calculated here
			if($offset >0){
				
				$offset = $offset*$limit+1;
			}

			 $limitype['limit'] = $limit ;
			 $limitype['offset'] = $offset;

			$query =  $this->home_model->getMyorderList($app_user_id,$where,$limit,$offset);			
			$gears=$this->home_model->getMyorderList($app_user_id,$where);
			
			$order_list	   =  $query->result_array();
			$gears1	   =  $gears->result_array();

				$config['base_url'] = base_url()."RentalDashboard/OwnerList?app_user_id=".$app_user_id."&limit=".$limit;
				$counts = (count($gears1));
				$gears2['total_rows']= $counts;
				$gears2['limit']=$limit;
				$config['total_rows'] = $counts;
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
				$gears2['paginator'] = $paginator;
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
					}elseif ($value['order_status'] =='8') {
						$order_list[$i]['order_status'] = 'Expired';
					}
					else{
						$order_list[$i]['order_status'] = 'Quote';
					}
					if ($value['payment_status'] == 'RECEIVED') {
						$order_list[$i]['payment_status'] = 'SETTLED';
					}

					if($order_list[$i]['show_business_name'] == 'Y'){
						$order_list[$i]['app_user_first_name'] = $order_list[$i]['bussiness_name']	;
						$order_list[$i]['app_user_last_name'] = ''	;
					}
						// $query = $this->common_model->GetAllWhere('ks_user_gear_rent_details',array('order_id'=>$value['order_id']));
						// $order_data =  $query->result();
						// print_r($order_data);
					$this->db->select('*');
					$this->db->where('order_id',$value['order_id']);
					$this->db->from('ks_user_gear_rent_details');
					$query = $this->db->get();
					$order_itme=  $query->result();
					$total_rent_amount_ex_gst = '0';
					$gst_amount = '0';
					$other_charges = '0';
					$total_rent_amount = '0';
					$total_rent_amount2 = '0';
					$security_deposit = '0';
					$beta_discount = '0' ; 
					$insurance_fee = '0';
					$community_fee = '0' ; 
					$owner_insurance_amount  =  0 ;
					$total_rent_amount_ex_gst1 = '0';
					foreach ($order_itme as  $orders) {
						$total_rent_amount_ex_gst  += $orders->total_rent_amount_ex_gst ; 
						$total_rent_amount_ex_gst1  += $orders->total_rent_amount_ex_gst ; 
						$gst_amount  += ($orders->total_rent_amount_ex_gst -$orders->beta_discount +  $orders->insurance_fee + $orders->community_fee +  number_format((float)( $orders->owner_insurance_amount), 2, '.', '')   )*10/100;; ; 
						$other_charges  += $orders->other_charges ; 
						$owner_insurance_amount +=  number_format((float)($orders->owner_insurance_amount), 2, '.', '') ;
						$total_rent_amount  += $orders->total_rent_amount_ex_gst -$orders->beta_discount + $orders->insurance_fee + $orders->community_fee +  number_format((float)( $orders->owner_insurance_amount), 2, '.', '') ; 
	        			$total_rent_amount2  += $orders->total_rent_amount_ex_gst -$orders->beta_discount + $orders->insurance_fee + $orders->community_fee +  number_format((float)( $orders->owner_insurance_amount), 2, '.', '') + $gst_amount  ; 
						// $total_rent_amount  += $value['total_rent_amount'] ; 
						$security_deposit +=   $orders->security_deposit;
						$beta_discount +=   $orders->beta_discount;
						$insurance_fee +=   $orders->insurance_fee;
						$community_fee +=   $orders->community_fee;

					}
					
					$order_list[$i]['total_rent_amount'] = number_format((float)$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount + $gst_amount, 2, '.', '');
					$order_list[$i]['security_deposit'] = $security_deposit;


					$sql1 = "SELECT app_user_id FROM ks_user_gear_description WHERE user_gear_desc_id = '".$value['user_gear_desc_id']."'  ";
					$query = $this->db->query($sql1);
					$values1 =  $query->row();
					if (!empty($values1)) {
							$order_list[$i]['renter_id'] = $value['create_user'];
					}else{
						$order_list[$i]['renter_id'] = '';
					}

					//$date_array[] = date('Y', strtotime($value['gear_rent_requested_on']));
					$i++;

				}
				# code...
			}
			/*$values = array();
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
			}*/
			
			
				$date_array = array();
				
				$date_array = $this->home_model->fetchRentalYears($app_user_id);

				$values = array();
				$values[]="All";
			
				rsort($date_array);
			
				foreach ($date_array as  $value) {
					/*if (empty($values)) {
						$values[] = $value;
					}else{
						if(in_array( $value, $values)){
						}else{
							$values[] = $value;
						}
					}*/
					
					$values [] = $value['year'];
				}

			//$date_array = array_map("unserialize", array_unique(array_map("serialize", $date_array)));
			//	echo  $this->db->last_query();die;
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Gear list';
				$response['result'] = array('order_list' => $order_list,'date_array'=> $values ,'pagination'=>$gears2); ;
				$json_response = json_encode($response);
				echo $json_response;

				exit();
				
			}else{
				
				header('HTTP/1.1 400 Session Expired');
				exit();
				
			}
		}else{
			/*$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Already logged in';
			$json_response = json_encode($response);
			echo $json_response;*/
			header('HTTP/1.1 400 Session Expired');
			exit();

		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}

	// public function   RenterList()
	// {
	// 	$post_data  = json_decode(file_get_contents("php://input"),true);
	// 	$token 			= $post_data['token'];

	// 	$app_user_id = $this->userinfo($token);
	// 	if ($app_user_id != '') {
	// 			$where = '';
	// 		if (isset($post_data['is_completed']) && $post_data['is_completed'] !='') {
	// 			//	print_r($post_data['ks_gear_id']);
	// 			if ($where != '') {
	// 				$where .= " AND rent_details.order_status	=  '4' ";
	// 			}else{
	// 				$where .= " rent_details.order_status	=  '4'  ";
	// 			}

	// 		}
	// 		if (isset($post_data['is_rent_cancelled']) && $post_data['is_rent_cancelled'] !='') {
	// 			//	print_r($post_data['ks_gear_id']);
	// 			if ($where != '') {
	// 				$where .= " AND (rent_details.order_status	=  '5'  OR  rent_details.order_status	=  '8' ) ";
	// 			}else{
	// 				$where .= " (rent_details.order_status	=  '5'   OR  rent_details.order_status	=  '8'  )";
	// 			}

	// 		}
	// 		if (isset($post_data['is_rent_rejected']) && $post_data['is_rent_rejected'] !='') {
	// 			//	print_r($post_data['ks_gear_id']);
	// 			if ($where != '') {
	// 				$where .= " AND rent_details.order_status	=  '6' ";
	// 			}else{
	// 				$where .= " rent_details.order_status	=  '6'  ";
	// 			}

	// 		}
	// 		if (isset($post_data['is_rent_approved']) && $post_data['is_rent_approved'] !='') {
	// 			//	print_r($post_data['ks_gear_id']);
	// 			if ($where != '') {
	// 				$where .= " AND (rent_details.order_status	=  '3'  OR rent_details.order_status = 2 OR  rent_details.order_status =1) ";
	// 			}else{
	// 				$where .= "(rent_details.order_status	=  '3'  OR rent_details.order_status = 2 OR  rent_details.order_status =1)   ";
	// 			}

	// 		}
	// 		if (isset($post_data['Date']) && $post_data['Date'] !='') {
	// 			//	print_r($post_data['ks_gear_id']);
	// 			if ($where != '') {
	// 				$where .= " AND Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."' ";
	// 			}else{
	// 				$where .= " Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."'  ";
	// 			}

	// 		}
	// 		if (isset($post_data['is_achive']) && $post_data['is_achive'] !='') {
	// 			//	print_r($post_data['ks_gear_id']);
	// 			if ($where != '') {
	// 				$where .= " AND rent_details.order_status	=  '7'  ";
	// 			}else{
	// 				$where .= " rent_details.order_status	=  '7'   ";
	// 			}

	// 		}

	// 		if(empty($where)){
	// 			$where .= "  rent_details.order_status	!=  '7'   ";
	// 		}

	// 		if($this->input->get("per_page")!= '')
	// 		{
	// 			$offset = $this->input->get("per_page");
	// 		}
	// 		else
	// 		{
	// 			$offset=0;
	// 		}
	// 		if($this->input->get("limit")!= '')
	// 		{
	// 			$limit = $this->input->get("limit");
	// 		}
	// 		else
	// 		{
	// 			$limit=10;
	// 		}

	// 		 $limitype['limit'] = $limit ;
	// 		 $limitype['offset'] = $offset;

	// 		$query =  $this->home_model->getRequestedorderList($app_user_id,$where,$limit,$offset);
	// 		$gears=$this->home_model->getRequestedorderList($app_user_id,$where);
	// 		$order_list	   =  $query->result_array();
	// 		$gears1	   =  $gears->result_array();
	// 		$config['base_url'] = base_url()."RentalDashboard/OwnerList?app_user_id=".$app_user_id."&limit=".$limit;
	// 		$counts = (count($gears1));
	// 		$gears2['total_rows']= $counts;
	// 		$gears2['limit']=$limit;
	// 		$config['total_rows'] = $counts;
	// 		$config['per_page'] = $limit;
	// 		$config['page_query_string'] = TRUE;
	// 	    $config['full_tag_open'] = "<ul class='pagination pagination-sm text-center'>";
	// 		$config['full_tag_close'] = "</ul>";
	// 		$config['num_tag_open'] = '<li>';
	// 		$config['num_tag_close'] = '</li>';
	// 		$config['cur_tag_open'] = "<li><li class='active'><a href='#'>";
	// 		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	// 		$config['next_tag_open'] = "<li>";
	// 		$config['next_tagl_close'] = "</li>";
	// 		$config['prev_tag_open'] = "<li>";
	// 		$config['prev_tagl_close'] = "</li>";
	// 		$config['first_tag_open'] = "<li>";
	// 		$config['first_tagl_close'] = "</li>";
	// 		$config['last_tag_open'] = "<li>";
	// 		$config['last_tagl_close'] = "</li>";
	// 		$this->pagination->initialize($config);
	// 		$paginator = $this->pagination->create_links();
	// 		$gears2['paginator'] = $paginator;
	// 		$date_array =array();
	// 		if (!empty($order_list)) {
	// 				$i=0 ;
	// 			foreach ($order_list as   $value) {
	// 				$sql = " SELECT * FROM  ks_gear_unavailable WHERE  user_gear_description_id = '".$value['user_gear_desc_id']."' AND    date(unavailable_from_date) >= '".date('Y-m-d')."'     AND  date(unavailable_to_date) <= '".date('Y-m-d')."'   " ;
	// 				$query = $this->db->query($sql);
	// 				$values =  $query->result();
	// 				if (!empty($values)) {
	// 					$order_list[$i]['is_setting'] = '1';
	// 				}else{
	// 					$order_list[$i]['is_setting'] = '0';
	// 				}
	// 				if ($value['order_status'] ==2) {
	// 					$order_list[$i]['order_status'] = 'Reservation';
	// 				}elseif ($value['order_status'] =='3') {
	// 					$order_list[$i]['order_status'] = 'Contract';
	// 				}elseif ($value['order_status'] =='4') {
	// 					$order_list[$i]['order_status'] = 'Completed';
	// 				}elseif ($value['order_status'] =='5') {
	// 					$order_list[$i]['order_status'] = 'Cancelled';
	// 				}elseif ($value['order_status'] =='6') {
	// 					$order_list[$i]['order_status'] = 'Declined';
	// 				}elseif ($value['order_status'] =='7') {
	// 					$order_list[$i]['order_status'] = 'Archived';
	// 				}elseif ($value['order_status'] =='8') {
	// 					$order_list[$i]['order_status'] = 'Expired';
	// 				}else{
	// 					$order_list[$i]['order_status'] = 'Quote';
	// 				}

	// 				if ($order_list[$i]['show_business_name'] == 'Y') {
	// 					$order_list[$i]['app_user_first_name'] = $order_list[$i]['bussiness_name'] ;
	// 					$order_list[$i]['app_user_last_name'] =  '' ;
	// 				}

	// 				if ($value['payment_status'] == 'RECEIVED') {
	// 					$order_list[$i]['payment_status'] = 'SETTLED';
	// 				}
	// 						// print_r($order_data);
	// 				$this->db->select('SUM(total_rent_amount) AS  total_rent_amount , SUM(security_deposit)  AS security_deposit');
	// 				$this->db->where('order_id',$value['order_id']);
	// 				$this->db->from('ks_user_gear_rent_details');
	// 				$query = $this->db->get();
	// 				$order_itme=  $query->row();
	// 				$order_list[$i]['total_rent_amount'] = $order_itme->total_rent_amount;
	// 				$order_list[$i]['security_deposit'] = $order_itme->security_deposit;

	// 				$date_array[] = date('Y' ,strtotime($value['gear_rent_requested_on']));
	// 				$order_list[$i]['renter_id'] = $value['app_user_id'];
	// 				$i++;
	// 			}
	// 			# code...
	// 		}

	// 		$values = array();
	// 		if (empty($date_array)) {
	// 			$values = array();
	// 		}else{
	// 			foreach ($date_array as  $value) {
	// 				if (empty($values)) {
	// 					$values[] = $value;
	// 				}else{
	// 					if(in_array( $value, $values)){
	// 					}else{
	// 						$values[] = $value;
	// 					}
	// 				}
	// 			}
	// 		}

	// 		//print_r($order_list);die;
	// 			$app_user_id = '';
	// 			$response['status'] = 200;
	// 			$response['status_message'] = 'Gear rented List';
	// 			$response['result'] = array('order_list'=>$order_list ,  'date_array'=>$values ,'pagination'=>$gears2) ;
	// 			$json_response = json_encode($response);
	// 			echo $json_response;
	// 			exit();
	// 	}else{
	// 		$app_user_id = '';
	// 		$response['status'] = 401;
	// 		$response['status_message'] = 'User Already logged in';
	// 		$json_response = json_encode($response);
	// 		echo $json_response;
	// 		header('HTTP/1.1 401 Unauthorized');
	// 		exit();

	// 	}
	// }

	// GET GEAR ADDRESS
	public function getAddressListByGear()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
			if (array_key_exists('token',$post_data)) {
				
				$token 			= $post_data['token'];
				$app_user_id = $this->userinfo($token);
				
				if($app_user_id!=""){
					$addres = array();
					$user_gear_desc_id = $post_data['user_gear_desc_id'] ;
					
					$sql = "SELECT user_address_id FROM ks_gear_location WHERE user_gear_desc_id = '".$user_gear_desc_id."'  ";
					$addrrss_data = $this->common_model->get_records_from_sql( $sql);
					if (empty($addrrss_data)) {
						$app_user_id = '';
						// $response['status'] = 200;
						// $response['status_message'] = 'No Address Present';
						// $json_response = json_encode($response);
						// echo $json_response;
					}else{
						foreach ($addrrss_data as $value) {
							 $addres[] = $value->user_address_id;
						}
					}
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'Gear Address List';
					$response['result'] = $addres;
					$json_response = json_encode($response);
					echo $json_response;
				
				} else{
					
					header('HTTP/1.1 400 Session Expired');
					exit();
				}
			} else{
				header('HTTP/1.1 400 Session Expired');
				exit();
			}
		} else{
			header('HTTP/1.1 200 Success');
			exit();
		}
	}
	public function GearAddressList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){

				$user_gear_desc_id = $post_data['user_gear_desc_id'] ;
				$table = 'ks_user_address';
				
				$where_clause = array('app_user_id'=> $app_user_id);
				$data = $this->common_model->get_all_record($table,$where_clause);
				if (count($data) > 0) {
					$data_value = array();
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
				
				header('HTTP/1.1 400 Session Expired');
				exit();
			}
		}else{
			/*$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Already logged in';
			$json_response = json_encode($response);
			echo $json_response;*/
			header('HTTP/1.1 400 Session Expired');
			exit();
		}
		}else{
			
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	//REmove Gear Address


	public function RemoveGearAddress()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){

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
				
				header('HTTP/1.1 400 Session Expired');
				exit();
				
			}


		}else{
			/*$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Already logged in';
			$json_response = json_encode($response);
			echo $json_response;*/
			header('HTTP/1.1 400 Session Expired');
			exit();
		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	public function AddGearAddress()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
			// print_r($post_data);
			if (array_key_exists('token',$post_data)) {
				
				$token 			= $post_data['token'];
				$app_user_id = $this->userinfo($token);
				
				if($app_user_id!=""){
				
					$response['status_message'] = '';
					if(count($post_data['user_gear_desc_id']) > 0) {
						foreach ($post_data['user_gear_desc_id'] AS  $value) {
						 	$user_gear_desc_id = $value;
						 	$this->common_model->delete("ks_gear_location",$user_gear_desc_id,"user_gear_desc_id");
						 		$i = 0;
								foreach ($post_data['user_address_id'] AS  $address_id) {
									$address_insert =  array(
																'user_gear_desc_id' => $user_gear_desc_id,
																'user_address_id'=> $address_id,
																'is_active' => 'Y',
																'create_date'=> date('Y-m-d'),
																'create_user'=>$app_user_id,

															 );
									$this->common_model->InsertData( 'ks_gear_location' ,$address_insert);
									$i++;
								}
								$response['status'] = 200;
								$response['status_message'] = 'Address Assigned to gear successfully  ';
							
						}
					}
					$app_user_id = '';
											
											//$response['status_message'] = 'Address Assigned to gear successfully  ';
											$json_response = json_encode($response);
											echo $json_response;
											exit();
										
				} else{
					
					header('HTTP/1.1 400 Session Expired');
					exit();  
					
				}
			} else{
				header('HTTP/1.1 400 Session Expired');
				exit();
			}

		}
		else {
			header('HTTP/1.1 200 Success');
			exit();
		}
	}
	public function AddGearAddress_old()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		print_r($post_data);
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){
			
			
				 $user_gear_desc_id_array = explode(',', $post_data['user_gear_desc_id']) ;
				 $user_address_id = $post_data['user_address_id'] ;
				 foreach ($user_gear_desc_id_array as  $value) {
				 	// echo "string"; die;
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
				
				header('HTTP/1.1 400 Session Expired');
				exit();  
				
			}
		}else{
			/*$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Already logged in';
			$json_response = json_encode($response);
			echo $json_response;*/
			header('HTTP/1.1 400 Session Expired');
			exit();
		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}

	}
	// Invoice
	public function OrderInvoice()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		
		if (array_key_exists('token',$post_data)) {
			
				$token 			= $post_data['token'];
				$app_user_id = $this->userinfo($token);
				
				if($app_user_id!=""){

					$order_id =  $post_data['order_id'];
					$sql = "SELECT  u.show_business_name,u.bussiness_name,u.app_user_last_name,u.app_user_last_name,o_d.* ,g_tbl.gear_name,g_tbl.replacement_value_aud_inc_gst,g_tbl.replacement_value_aud_ex_gst ,g_tbl.per_day_cost_aud_ex_gst FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_order_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id AND g_tbl.order_id  = o_d.order_id  INNER JOIN ks_users As u  ON   g_tbl.app_user_id = u.app_user_id   WHERE o_d.order_id = '".$order_id."'  "	;
					$order_details   = $this->common_model->get_records_from_sql($sql);
					foreach ($order_details as  $value) {
					 $users[] = 	$value->create_user;
					}
					//$this->db->select('*');
					$this->db->from('ks_user_gear_rent_master g_r_m');
					$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.user_gear_rent_id = g_r_m.user_gear_rent_id','inner');
					$this->db->join('ks_gear_order_location as u_add', ' g_r_d.order_id = u_add.order_id AND  g_r_m.user_address_id = u_add.user_address_id','inner');
					$this->db->join('ks_user_gear_order_description as desc', ' desc.order_id = g_r_d.order_id','inner');
					$this->db->join('ks_users as u', ' desc.app_user_id = u.app_user_id','inner');
					
					// $this->db->where('u_add.app_user_id' , $users[0]);
					$query = $this->db->get();
					$data['addrsss'] =  $query->row();
					$url = '';
					$data['six_digit_random_number'] = $order_id; 
					$this->uri->segment('3');
					$data['order_details'] = $order_details;
					$mail_body = $this->load->view('email-template1',$data ,true);
					$this->load->helper('pdf');
					$url =  gen_pdf($mail_body,$order_id);	
					
					 // $url =  BASE_URL."site_upload/invocies/invoice-template-pdf-generic.pdf" ;
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'Invocie URL';
					$response['result'] = $url;
					$json_response = json_encode($response);
					echo $json_response;
			
				}else{
					
					header('HTTP/1.1 400 Session Expired');
					exit();
				}
		}else{
			/*$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Already logged in';
			$json_response = json_encode($response);
			echo $json_response;*/
			header('HTTP/1.1 400 Session Expired');
			exit();
		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	function userinfo($token){

		$app_user_id = $this->common_model->fetchTokenDetails($token);
		return $app_user_id;

	}
	//Add reference User

	public function AddRefernce()
	{

		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){
			
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
				$response['status_message'] = ' Mail Sent Successfully';
				$json_response = json_encode($response);
				echo $json_response;
				
			}else{
					header('HTTP/1.1 400 Session Expired');
					exit();
			}

		}else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();
		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}

	}
	public function AddRefernceReview()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		
		if(array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){

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
				
				header('HTTP/1.1 400 Session Expired');
				exit();
				
			}
		}else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();
		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	public function ReferenceApporove()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){
			
				$ks_user_ref_comment_id =  $post_data['ks_user_ref_comment_id'];
					$this->common_model->UpdateRecord(array('is_approved'=>'Y'), 'ks_user_ref_comment' ,'ks_user_ref_comment_id' , $post_data['ks_user_ref_comment_id']);
				 $response['status'] = 200;
				 $response['status_message'] = ' Status Appoved Successfully';
				 $json_response = json_encode($response);
				 echo $json_response;
			 
			}else{
				
				header('HTTP/1.1 400 Session Expired');
				exit();
			}

		 }else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();
		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}

	public function AddUserReviews()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		/*$where = array(
						'order_id'=> $post_data['order_id']	,
						'app_user_id'=>$post_data['app_user_id'] ,
						'create_user'=>$app_user_id ,
						'parent_gear_review_id'=>$post_data['parent_gear_review_id'] ,

					  ) ;
		$query =  $this->common_model->GetAllWhere('ks_cust_gear_reviews' , $where);
		
		$data = $query->row();
		if (!empty($data)) {
				$app_user_id = '';
				$response['status'] = 404;
				$response['status_message'] = 'Review given already.';
				$json_response = json_encode($response);
				echo $json_response;
				exit();
		}*/
		if ($app_user_id != '') {
			$insert_data = array(
									'order_id'=> $post_data['order_id'],
									'app_user_id'=>$post_data['app_user_id'] ,
									'cust_gear_review_desc'=>$post_data['cust_gear_review_desc'] ,
									'star_rating'=>$post_data['star_rating'] ,
									'parent_gear_review_id'=>$post_data['parent_gear_review_id'] ,
									'create_user'=>$app_user_id ,
									'create_date'=>date('Y-m-d'),
									'cust_gear_review_date'=>date('Y-m-d'),
									'is_active'=>'Y',
								);

			 $this->common_model->InsertData('ks_cust_gear_reviews',$insert_data);
			 $response['status'] = 200;
			 $response['status_message'] = ' Review Given Successfully';
			 $json_response = json_encode($response);
			 echo $json_response;
		}else{
				/*$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;*/
				header('HTTP/1.1 400 Session Expired');
				exit();

		}
		}else{
			
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}


	public function GetOwnerReviews()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		if (array_key_exists('token',$post_data)) {
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){
			
			$where = array(
							'a.parent_gear_review_id' =>0,
							'a.app_user_id' =>$app_user_id,
            				'a.is_active' => 'Y'
						);

			$query =  $this->home_model->OwnerReviewList( $where);
            
            //echo $this->db->last_query();
            
			$user_data = array();
            $data = array();
			$result = $query->result();

			if (!empty($result)) {
				foreach ($result as  $value) {
                
                	$data[] = $value;

					$this->db->select('a.* ,orders.project_name, orders.order_id, users.app_user_first_name AS app_user_first_name_given_by, users.app_user_last_name AS app_user_last_name_given_by ,users.user_profile_picture_link As user_profile_picture_link_given_by,users.bussiness_name ,users.show_business_name ');
					$this->db->from('ks_cust_gear_reviews AS a');
					$this->db->join('ks_users as users' , 'a.create_user = users.app_user_id' ,'INNER');
					$this->db->join('ks_user_gear_rent_details as orders' , 'orders.order_id = a.order_id ' ,'INNER');
					//$this->db->join('ks_user_gear_description as gears' , 'gears.app_user_id = a.app_user_id ' ,'INNER');
					//$this->db->where('parent_gear_review_id' ,0	 );
					//$this->db->where('a.app_user_id' ,$app_user_id	 );
					$this->db->where(array('a.order_id'=>$value->order_id,'a.parent_gear_review_id!='=>0));
					//$this->db->where('parent_gear_review_id' ,$value->ks_cust_gear_review_id );
					//$this->db->group_by('a.order_id');
                    $this->db->order_by('a.ks_cust_gear_review_id','DESC');
					$query = $this->db->get();
					
					
					foreach($query->result() as $row){
						$data[] = $row;
					}				
					
					
					
					if (!empty($data)) {	
						// $data->project_name = '';
					 //    $data->app_user_id_given_by = '';
					 //    $data->app_user_first_name_given_by = '';
					 //    $data->user_profile_picture_link_given_by = '';
					 //    $data->app_user_last_name_given_by = '';
						$user_data[] = $data;
					}
				}
			}
            
            
            
			if (!empty($data)) {
				$result =   $data;
			}			
			
			if (!empty($result)) {
				$i =0 ;
				foreach ($result as $val) {   
               

                    //Checked the owner of the product
                   $user_id = $this->home_model->gearOwner($val->order_id);                    
                    
                    if($user_id == $val->create_user)
                        $user_typee = "Owner";
                    else
                        $user_typee = "Renter";
                  
					if($val->create_user!='')
                    	$result[$i]->user_type = $user_typee; 

                    if ($val->user_profile_picture_link_given_by == '') {
                        $result[$i]->user_profile_picture_link_given_by =  BASE_URL."server/assets/images/profile.png";
                    }
                    if($val->show_business_name == 'Y'){
                        $result[$i]->app_user_first_name_given_by   =   $val->bussiness_name ;
                        $result[$i]->app_user_last_name_given_by   =  '' ;

                    }

					 $i++;
                    
				}
			# code...
			}

			if (!empty($result)) {
				$response['status'] = 200;
					$response['status_message'] = ' Review found for Owner';
					$response['result'] = $result;
					$json_response = json_encode($response);
					echo $json_response;
			}else{
					$response['status'] = 200;
					$response['status_message'] = ' No review found for owners';
					$response['result'] = array();
					$json_response = json_encode($response);
					echo $json_response;
			}
			}else{
				header('HTTP/1.1 400 Session Expired');
				exit();
				
			}

		}else{
				/*$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;*/
				header('HTTP/1.1 400 Session Expired');
				exit();

		}
		}else{
			
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	public function  GetRenterReview()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		
		if(array_key_exists('token',$post_data)){
		
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {


				$where = array(
								'a.app_user_id'=>$app_user_id ,'a.parent_gear_review_id' =>0, 'a.is_active' => 'Y'
							);

				$query =  $this->home_model->RenterReviewList($where);

				$result = $query->result();
				$user_data =array();
				if (!empty($result)) {
					foreach ($result as  $value) {
						
						$data[] = $value;

						$this->db->select('a.* ,orders.project_name, orders.order_id, users.app_user_first_name AS app_user_first_name_given_by, users.app_user_last_name AS app_user_last_name_given_by ,users.user_profile_picture_link As user_profile_picture_link_given_by,,users.bussiness_name ,users.show_business_name ');
						$this->db->from('ks_cust_gear_reviews AS a');
						$this->db->join('ks_users as users' , 'a.create_user = users.app_user_id' ,'INNER');
						$this->db->join('ks_user_gear_rent_details as orders' , 'orders.order_id = a.order_id' ,'INNER');
						// $this->db->join('ks_user_gear_description as gears' , 'gears.app_user_id = a.app_user_id AND orders.user_gear_desc_id = gears.user_gear_desc_id' ,'INNER');
						//$this->db->where('parent_gear_review_id' ,$value->ks_cust_gear_review_id );
						$this->db->where(array('a.order_id'=>$value->order_id,'a.parent_gear_review_id!='=>0));
						//$this->db->group_by('a.order_id');
						$this->db->order_by('a.ks_cust_gear_review_id','DESC');
						$query = $this->db->get();
						//$data = $query->row();
						
						foreach($query->result() as $row){
							$data[] = $row;
						}				
					
						if (!empty($data)) {
							// $data->project_name = '';
						 //    $data->app_user_id_given_by = '';
						 //    $data->app_user_first_name_given_by = '';
						 //    $data->user_profile_picture_link_given_by = '';
						 //    $data->app_user_last_name_given_by = '';
						 //    $data->show_business_name = '';
						 //    $data->bussiness_name = '';
							$user_data[] = $data ;
						}
					}
				}
				/*if (!empty($user_data)) {
					$result =  array_merge($result , $user_data);
				}*/
				
				if (!empty($data)) {
					$result =   $data;
				}	
				
				if (!empty($result)) {
					$i =0 ;
					foreach ($result as $value) {
						
						//Checked the owner of the product
						$user_id = $this->home_model->gearOwner($value->order_id);
						
						if($user_id == $value->create_user)
							$user_type = "Owner";
						else
							$user_type = "Renter";
						
						$result[$i]->user_type = $user_type;  
						
						if ($result[$i]->user_profile_picture_link_given_by == '') {
							$result[$i]->user_profile_picture_link_given_by =  BASE_URL."server/assets/images/profile.png";
						}
						if($result[$i]->show_business_name == 'Y'){
							$result[$i]->app_user_first_name_given_by   =   $result[$i]->bussiness_name ;
							$result[$i]->app_user_last_name_given_by   =  '' ;

						}

						 $i++;
					}
				# code...
				}
				if (!empty($result)) {
					$response['status'] = 200;
						$response['status_message'] = ' Review found for Ownere';
						$response['result'] = $result;
						$json_response = json_encode($response);
						echo $json_response;
				}else{
						$response['status'] = 200;
						$response['status_message'] = ' No review found for owners';
						$response['result'] = array();
						$json_response = json_encode($response);
						echo $json_response;
				}

			}else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();
			}
		}else{
			
				header('HTTP/1.1 400 Session Expired');
				exit();
		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}


	public function ReviewOrderList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		
			if(array_key_exists('token',$post_data)){
				$token 			= $post_data['token'];
				$app_user_id = $this->userinfo($token);
				if ($app_user_id != '') {


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
					 $where = array('rent_details.order_status'=>4);
					 $limitype['limit'] = $limit ;
					 $limitype['offset'] = $offset;

					$gears=$this->home_model->getOwnerReviewOrderList($app_user_id,$where);
					$result	   =  $gears->result_array();

					$gears=$this->home_model->getOwnerReviewOrderListrenter($app_user_id,$where);
					$result1	   =  $gears->result_array();
					$result =  array_merge($result , $result1);
					// echo "<pre>";
					// print_r($result);
					if (!empty($result)) {
						$i = 0 ;
						foreach ($result as  $value) {
							if($result[$i]['show_business_name'] == 'Y'	){
								$result[$i]['app_user_last_name'] = '';
								$result[$i]['app_user_first_name'] = $result[$i]['bussiness_name'];

							}
							$result[$i]['app_user_id'] = $result[$i]['create_user'];
							$i++;
						}
					}

					$gears=$this->home_model->getRenterReviewOrderList($app_user_id,$where);
					$result1 = $gears->result_array();
					$result =  array_merge($result , $result1);
					$values = array();
					if (!empty($result)) {
						foreach ($result as  $value) {
							// print_r($value['order_id']);die;
							$query = $this->common_model->GetAllWhere('ks_cust_gear_reviews',array('order_id'=>$value['order_id'] , 'create_user' =>$app_user_id));
							$review_details = $query->row();
							if (empty($review_details)) {
								$values[] = $value ;
							}
						}
					}

					if (!empty($values)) {
						$i =0 ;
						foreach ($values as $value) {
							if ($value['user_profile_picture_link'] == '') {
								$values[$i]['user_profile_picture_link'] =  BASE_URL."server/assets/images/profile.png";;
							}

							$i++;
						}
					}
					
					$response['status'] = 200;
							$response['status_message'] = ' Review found for Ownere';
							$response['result'] = $values;
							$json_response = json_encode($response);
							echo $json_response;

				}else{
						/*$app_user_id = '';
						$response['status'] = 401;
						$response['status_message'] = 'User Already logged in';
						$json_response = json_encode($response);
						echo $json_response;*/
						header('HTTP/1.1 400 Session Expired');
						exit();
				}
			
			}else{
				header('HTTP/1.1 400 Session Expired');
					exit();
			}
		}else{
			
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}

	public function RenterList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
			
		
			if(array_key_exists('token',$post_data)){
			
			$token 			= $post_data['token'];

			$app_user_id = $this->userinfo($token);
			
			if($app_user_id!=""){
			
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
					$where .= " AND (rent_details.order_status	=  '5'  OR  rent_details.order_status	=  '8' ) ";
				}else{
					$where .= " (rent_details.order_status	=  '5'   OR  rent_details.order_status	=  '8'  )";
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
			
			
			if(isset($post_data['Date']) && $post_data['Date']!="All" && $post_data['Date']!=''){
				
				
				if(!array_key_exists("is_completed",$post_data) && !array_key_exists("is_rent_cancelled",$post_data) && !array_key_exists("is_rent_rejected",$post_data) && !array_key_exists("is_rent_approved",$post_data) && !array_key_exists("is_achive",$post_data)){
					
					$where .= " Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."'  ";
					
				}else{
				
					if(empty($where)){
						
						$where .= " Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."'  ";
						
					}else{
						
						$where .= " AND Year(rent_details.gear_rent_requested_on)	=  '".$post_data['Date']."'  ";
						
					}
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
			
			if($offset>0){
				
				$offset = $offset*$limit+1;
			}

			 $limitype['limit'] = $limit ;
			 $limitype['offset'] = $offset;
			 
			$query =  $this->home_model->getRequestedorderList($app_user_id,$where,$limit,$offset);
			
					
			$gears=$this->home_model->getRequestedorderList($app_user_id,$where);
			$order_list	   =  $query->result_array();
			$gears1	   =  $gears->result_array();
			
			
			$config['base_url'] = base_url()."RentalDashboard/OwnerList?app_user_id=".$app_user_id."&limit=".$limit;
			$counts = (count($gears1));
			$gears2['total_rows']= $counts;
			$gears2['limit']=$limit;
			$config['total_rows'] = $counts;
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
			$gears2['paginator'] = $paginator;
			$date_array =array();
			if (!empty($order_list)) {
					$i=0 ;
				foreach ($order_list as   $value) {
					$sql = " SELECT * FROM  ks_gear_unavailable WHERE  user_gear_description_id = '".$value['user_gear_desc_id']."' AND    date(unavailable_from_date) >= '".date('Y-m-d')."' AND  date(unavailable_to_date) <= '".date('Y-m-d')."'   " ;
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
					}elseif ($value['order_status'] =='8') {
						$order_list[$i]['order_status'] = 'Expired';
					}else{
						$order_list[$i]['order_status'] = 'Quote';
					}

					if ($order_list[$i]['show_business_name'] == 'Y') {
						$order_list[$i]['app_user_first_name'] = $order_list[$i]['bussiness_name'] ;
						$order_list[$i]['app_user_last_name'] =  '' ;
					}

					if ($value['payment_status'] == 'RECEIVED') {
						$order_list[$i]['payment_status'] = 'SETTLED';
					}
							// print_r($order_data);
					// $this->db->select('SUM(total_rent_amount) AS  total_rent_amount , SUM(security_deposit)  AS security_deposit');
					$this->db->select('*');
					$this->db->where('order_id',$value['order_id']);
					$this->db->from('ks_user_gear_rent_details');
					$query = $this->db->get();
					$order_itme =  $query->result();
					$total_rent_amount_ex_gst = '0';
					$gst_amount = '0';
					$other_charges = '0';
					$total_rent_amount = '0';
					$total_rent_amount2 = '0';
					$security_deposit = '0';
					$beta_discount = '0' ; 
					$insurance_fee = '0';
					$community_fee = '0' ; 
					$owner_insurance_amount  =  0 ;
					$total_rent_amount_ex_gst1 = '0';
					foreach ($order_itme as  $orders) {
						$total_rent_amount_ex_gst  += $orders->total_rent_amount_ex_gst ; 
						$total_rent_amount_ex_gst1  += $orders->total_rent_amount_ex_gst ; 
						$gst_amount  += ($orders->total_rent_amount_ex_gst -$orders->beta_discount +  $orders->insurance_fee + $orders->community_fee +  number_format((float)( $orders->owner_insurance_amount), 2, '.', '')  )*10/100;; ; 
						$other_charges  += $orders->other_charges ; 
						$owner_insurance_amount += number_format((float)( $orders->owner_insurance_amount), 2, '.', '');
						$total_rent_amount  += $orders->total_rent_amount_ex_gst -$orders->beta_discount + $orders->insurance_fee + $orders->community_fee +  number_format((float)( $orders->owner_insurance_amount), 2, '.', '') ; 
	        			$total_rent_amount2  += $orders->total_rent_amount_ex_gst -$orders->beta_discount + $orders->insurance_fee + $orders->community_fee +  number_format((float)( $orders->owner_insurance_amount), 2, '.', '')  + $gst_amount  ; 
						// $total_rent_amount  += $value['total_rent_amount'] ; 
						$security_deposit +=   $orders->security_deposit;
						$beta_discount +=   $orders->beta_discount;
						$insurance_fee +=   $orders->insurance_fee;
						$community_fee +=   $orders->community_fee;

					}
					
					$order_list[$i]['total_rent_amount'] = number_format((float)$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount + $gst_amount, 2, '.', '');
					$order_list[$i]['security_deposit'] = $security_deposit;

					//$date_array[] = date('Y' ,strtotime($value['gear_rent_requested_on']));
					$order_list[$i]['renter_id'] = $value['app_user_id'];
					$i++;
				}
				# code...
			}
			
			$date_array = array();
			
			$date_array = $this->home_model->fetchRentalYears($app_user_id);

			$values = array();
			$values[]="All";
			
			rsort($date_array);
			
			foreach ($date_array as  $value) {
				/*if (empty($values)) {
					$values[] = $value;
				}else{
					if(in_array( $value, $values)){
					}else{
						$values[] = $value;
					}
				}*/
				
				$values [] = $value['year'];
			}
			

			//print_r($order_list);die;
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Gear rented List';
				$response['result'] = array('order_list'=>$order_list ,  'date_array'=>$values ,'pagination'=>$gears2) ;
				$json_response = json_encode($response);
				echo $json_response;
				exit();
				
			}else{
				
				header('HTTP/1.1 400 Session Expired');
				exit();
			}
		}else{
			/*$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Already logged in';
			$json_response = json_encode($response);
			echo $json_response;*/
			header('HTTP/1.1 400 Session Expired');
			exit();

		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}


}?>