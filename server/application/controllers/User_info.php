<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_info extends CI_Controller {
		 public function __construct() {
			header('Access-Control-Allow-Origin: *');
			header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
			parent::__construct();
			$this->load->helper(array('url','form','html','text'));
			$this->load->library(array('session','form_validation','email','pagination'));
			$this->load->model(array('common_model','home_model','mail_model'));
		}

		public function profile_data(){


		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
			
			
			if(array_key_exists('token',$post_data)){
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);		
			
			if($app_user_id!=''){

					if ($post_data['app_user_id'] != '') {
						$app_user_id 			= $post_data['app_user_id'];
					}
					$columns ="app_user_id,app_user_first_name,app_user_last_name,app_username,australian_business_number,user_unique_id_number,
							   primary_email_address,primary_mobile_number,user_description,user_profile_picture_link,imdb_link,
								showreel_link,instagram_link,facebook_link,vimeo_link,youtube_link,user_website,show_business_name,								flikr_link,twitter_link,linkedin_link,create_date,user_birth_date,ks_renter_type_id,profession_type_id,aus_post_verified,aus_post_transcation_id,is_active,user_website,bussiness_name ,registered_for_gst,user_profession_other AS other_profession ,mobile_number_verfiy,aus_post_verified";
					$table = "ks_users";
					$where_clause = array('app_user_id'=>$app_user_id);

					$query = $this->common_model->GetSpecificValues($columns,$table,$where_clause);
					
					$result = $query->result_array();

					//$result[0]['review'] = '0';

					if($query->num_rows()>0){
						//Average rating
						$sql= "SELECT AVG(star_rating)  As rating FROM ks_cust_gear_reviews WHERE  app_user_id = '".$app_user_id."'";
						$query = $this->db->query($sql);
						$rating = $query->row();
						// review Count

						$sql= "SELECT profession_name  FROM ks_profession_types  WHERE  profession_type_id  = '".$result[0]['profession_type_id']."'";
						$query = $this->db->query($sql);
						$profession_details = $query->row();

						if (!empty($profession_details)) {
							$result[0]['profession_name']= $profession_details->profession_name;
						}else{
							$result[0]['profession_name']= '';
						}

						$this->db->select('g_l.*,ks_states.ks_state_name ,ks_suburbs.suburb_name');
						$this->db->from('ks_user_address As g_l');
						$this->db->where('g_l.app_user_id',$app_user_id);
						$this->db->where('g_l.is_active','Y');
						// $this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
						$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = g_l.ks_suburb_id " ,"INNER");
						$this->db->join('ks_states'," ks_states.ks_state_id = g_l.ks_state_id " ,"INNER");
						$query = $this->db->get();
						$address  =$query->result_array();

						if (!empty($address)) {
							$result[0]['address_found']='Y';
							$result[0]['address'] = $address;						
						}else{
							$result[0]['address_found']='N';
							$result[0]['address'] = array();
							
						}
						$review_result= $this->home_model->ProductReview($app_user_id); ////// get user's all product review array
						$reviews=$review_result->result_array();
						//print_r(count($reviews));
						//reference count
						$sql= "SELECT count(ks_user_reference_id) As reference FROM ks_user_reference WHERE  app_user_id = '".$app_user_id."'";
						$query = $this->db->query($sql);
						$reference = $query->row();


						if ($rating->rating > 0 ) {
							 $rating =number_format((float)( $rating->rating), 0, '.', '') ;
						}else{
							 $rating  = 0 ;
						}

						$result[0]['rating'] = $rating;
						$result[0]['review'] = count($reviews);
						$result[0]['reference'] =$reference->reference;
						// if($result[0]['user_description']=="")
						// 	$result[0]['user_description']="";

						if($result[0]['user_profile_picture_link']=="")
							$result[0]['user_profile_picture_link']= base_url()."/assets/images/profile.png";
						if ($result[0]['user_birth_date'] != '0000-00-00') {

							$result[0]['user_birth_date'] = $result[0]['user_birth_date'];
						}
						if ($post_data['action'] != 'edit') {
							$result[0]['user_description'] =nl2br($result[0]['user_description']);
							if ( $result[0]['show_business_name']  == 'Y') {
								 $result[0]['app_user_first_name'] =  $result[0]['bussiness_name'] ;
								 $result[0]['app_user_last_name'] =  '' ;
							}
						}else{

						}

						$response=array("status"=>200,
										"status_message"=>"success",
										"result"=>$result

										);
						echo json_encode($response);
						exit();
					}else{
						/*$response['status'] = 400;
						$response['status_message'] = "User not found";
						echo json_encode($response);
						exit();*/
						header('HTTP/1.1 400 Session Expired');
						exit();

					}
					
				}else{
					header('HTTP/1.1 400 Session Expired');
					exit();
				}
			}else{
				
				header('HTTP/1.1 400 Session Expired');
				exit();
				/*$response['status'] = 400;
				$response['status_message'] = "User not found";
				echo json_encode($response);
				exit();*/
			}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}


	}

	public function gear_data(){

		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
			if(array_key_exists('token',$post_data))
				$token 			= $post_data['token'];
			else
				$token = "";

			$data=array();
			$limit=12;
			$order_by ='';
			$where = '';
			$search_txt = '';
			
			$flag = 1;
			
			if($token!=""){
				
				$app_user_id = $this->userinfo($token);
				$flag = 0;
				 
			}
			
			if (isset($post_data['app_user_id']) && !empty($post_data['app_user_id'])) {
				
				if($token!=""){
					
					if($post_data['app_user_id'] != $app_user_id){
						$app_user_id = $post_data['app_user_id'];
						$flag = 1;
					}else
						$flag = 0;
					
				}else
					$app_user_id = $post_data['app_user_id'];
				
			}
			
			if(array_key_exists('app_username',$post_data) && $post_data['app_username']!=""){
				
				$app_user_id = $this->common_model->fetchUserId($post_data['app_username']);
				$flag = 1;
				
			}
		
			if (isset($post_data['gear_category_id']) && $post_data['gear_category_id'] !='') {

				//$where['a.user_gear_desc_id'] =$post_data['ks_gear_id'];
				$where .= "a.ks_category_id = '".$post_data['gear_category_id']."'  ";
			}

			if (isset($post_data['gear_name']) && $post_data['gear_name'] !='') {
			
				/*if ($where != '') {
					$where .= " AND a.gear_name LIKE '%".trim($post_data['gear_name'])."%' ";
				}else{
					$where .= " a.gear_name LIKE '%".trim($post_data['gear_name'])."%' ";
				}*/
				$search_txt = $post_data['gear_name'];

			}
			//if (isset($post_data['gear_status']) && $post_data['gear_status'] !='') {

				if ($where != '') {
					$where .= " AND a.is_active = 'Y' ";
				}else{
					$where .= " a.is_active = 'Y' ";
				}

			//}
			
			if (isset($post_data['type']) && $post_data['type'] !='') {
				
				if ($post_data['type'] == 'public') {
					
					/*if(isset($post_data['app_user_id']) && $flag==0 && array_key_exists('token',$post_data) && $post_data['token']!=""){
					
						if ($where != '') {
								$where .= " AND (a.gear_hide_search_results = 'Y' OR a.gear_hide_search_results = 'N')";
						}else{
							$where .= "  (a.gear_hide_search_results = 'Y' OR a.gear_hide_search_results = 'N') ";
						}
					
					}else{
						
						if ($where != '') {
							$where .= " AND a.gear_hide_search_results = 'Y' ";
						}else{
							$where .= "  a.gear_hide_search_results = 'Y' ";
						}
					}*/
					
					/*if($flag == 0){
						
						if ($where != '') {
								$where .= " AND (a.gear_hide_search_results = 'Y' OR a.gear_hide_search_results = 'N')";
						}else{
							$where .= "  (a.gear_hide_search_results = 'Y' OR a.gear_hide_search_results = 'N') ";
						}
					}else{*/
					
						if ($where != '') {
							$where .= " AND a.gear_hide_search_results = 'Y' ";
						}else{
							$where .= "  a.gear_hide_search_results = 'Y' ";
						}
					//}
					

				}elseif($post_data['type'] == 'private') {
					if ($where != '') {
						$where .= " AND a.gear_hide_search_results = 'N' ";
					}else{
						$where .= "  a.gear_hide_search_results = 'N' ";
					}
				}else if($post_data['type'] == 'all'){
					
					if($flag == 0){
						
						if ($where != '') {
								$where .= " AND (a.gear_hide_search_results = 'Y' OR a.gear_hide_search_results = 'N')";
						}else{
							$where .= "  (a.gear_hide_search_results = 'Y' OR a.gear_hide_search_results = 'N') ";
						}
					}else{
					
						if ($where != '') {
							$where .= " AND a.gear_hide_search_results = 'Y' ";
						}else{
							$where .= "  a.gear_hide_search_results = 'Y' ";
						}
					}
				}


			}else{
				
				if(!isset($post_data['app_user_id']) && $flag==0 && array_key_exists('token',$post_data) && $post_data['token']!=""){
					
					if ($where != '') {
							$where .= " AND (a.gear_hide_search_results = 'Y' OR a.gear_hide_search_results = 'N')";
					}else{
						$where .= "  (a.gear_hide_search_results = 'Y' OR a.gear_hide_search_results = 'N') ";
					}
					
				}else{				
				
					if ($where != '') {
							$where .= " AND a.gear_hide_search_results = 'Y' ";
					}else{
						$where .= "  a.gear_hide_search_results = 'Y' ";
					}
				}
			}

			

			
			
			if($app_user_id>0 && $app_user_id!=""){

				if($this->input->get("per_page")!= ''){
					$offset = $this->input->get("per_page");
				}else{
					$offset=0;
				}

				$gear1  = $this->home_model->GearCategories($app_user_id,$where,$limit,$offset,$order_by,$search_txt);	
				
				$gears = $this->home_model->GearCategories($app_user_id,$where,'','','',$search_txt);
				
				
				//Gear Categories as well as Gear lists are popluated in the array
				$total_rows =count($gears['gear_lists']);

				$data['total_rows']=$total_rows;

				$data['limit']= $limit;

				$config['base_url'] = base_url()."user_info/gear_data?order_by=".$order_by;

				$config['total_rows'] = $total_rows;

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

				$query['gears']=$gear1;
				$query['count']=$total_rows;
				$query['paginator']=$paginator;

				if(count($gears)>0){

					$response=array("status"=>200,
									"status_message"=>"success",
									"result"=>$query);

					echo json_encode($response);
					exit();

				}else{
					//header('HTTP/1.1 400 Bad Request');
					//$response['status'] = 400;
					//$response['status_message'] = "Gears not found";
					
					$response=array("status"=>400,
										"status_message"=>"Gears not found");
					echo json_encode($response);
					exit();

				}
			}else{
				
				$response=array("status"=>400,
								"status_message"=>"Gears not found");
				echo json_encode($response);
				exit();
			}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}


	}


	public function product_reviews(){

		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
			
		if(array_key_exists("token",$post_data) && array_key_exists('app_user_id', $post_data)){
			
			
			$token 			= $post_data['token'];
			
			if($token!="" && $post_data['app_user_id']=="")
				$app_user_id = $this->userinfo($token);
			else if ($post_data['app_user_id'] != '' && $token!="") 
				$app_user_id = $post_data['app_user_id'];
				
			
		}else if(array_key_exists("app_username",$post_data)){
			
			$app_user_id = $this->common_model->fetchUserId($post_data['app_username']);
		}
		
		//No. of records to be shown
		if(array_key_exists('limit',$post_data))
			$limit = $post_data['limit'];
		else
			$limit = 10;
		
		
		if(array_key_exists('page',$post_data)){		
			$offset=($post_data['page']-1)*$limit;
		}else
			$offset=0;
		
		
		if($app_user_id!=''){
			
			$review_result= $this->home_model->ProductReview($app_user_id,'Owners & Renters',$limit,$offset,'P'); ////// get user's all product review array
			$reviews=$review_result->result_array();
			
			if(count($reviews)>0){
				
				$i=0;
				foreach($reviews as $review_result){	
						
						//Checked the owner of the product
						$user_id = $this->home_model->gearOwner($review_result['order_id']);
						
						if($user_id>0){
						
							if($user_id == $review_result['create_user'])
								$user_type = "Owner";
							else
								$user_type = "Renter";
							
							$reviews[$i]['user_type'] = $user_type;  
							
							$i++;
						}
				}
				
			}
			
        	$reviews_final_arr = $reviews;
			
			
			$review_result_tot= $this->home_model->ProductReview($app_user_id,'Owners & Renters','',0,'P'); ////// get user's all product review array
			$reviews_tot=$review_result_tot->result_array();

			$review_lists=array();

			if(count($reviews)>0){
				$i= 0;
				foreach($reviews as $review_result){	
                
					$responses = array();
                
					$review_response = $this->home_model->ProductReview($app_user_id,$owner_type = 'Owners & Renters','',0,'C',$review_result['order_id']);
					$responses_arr = $review_response->result_array();
                    if(count($responses_arr)>0){
						
						//Checked the owner of the product
						$user_id = $this->home_model->gearOwner($responses_arr[0]['order_id']);
                    	
						if($user_id == $responses_arr[0]['create_user'])
							$user_type = "Owner";
						else
							$user_type = "Renter";
						
						$responses_arr[0]['user_type'] = $user_type;
						
                    	$responses = $responses_arr;
                    }   
					
					 //Array is replaced with the image path
					 foreach($review_result as $key=>$value){
						  if($key=="user_profile_picture_link"){
							if( $review_result[$key] ==""){
								//$img_path=base_url().PROFILE_IMG.$value;
								$img_path =  BASE_URL."server/assets/images/profile.png"; ;
								//$review_result[$key]=$img_path;
                               $review_result[$key]=$img_path;
                               
							}
						  }
					 }

					if ($review_result['show_business_name'] =='Y') {

						$review_result['reviewer_fname'] = $review_result['bussiness_name'];
						$review_result['reviewer_lname'] = '';
					}

					$review_result['cust_gear_review_desc'] = nl2br($review_result['cust_gear_review_desc']);
					
					$review_result['responses'] = $responses;
                
                	$reviews_final_arr[$i] = array_replace($reviews_final_arr[$i],$review_result);				
					
					
					 $i++ ;
				}
				
			}
			


			$response=array("status"=>200,
							"status_message"=>"success",
							"result"=>$reviews_final_arr,
                            "total_reviews"=>count($reviews_tot));



			echo json_encode($response);
			exit();
			
		}else{
			
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}

	}


	public function user_credentials(){

		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
			$token 			= $post_data['token'];
			$user_info = $this->common_model->fetchTokenDetails($token,'all','login');		
			
			if(count($user_info)>0){
				if($user_info[0]['app_user_id']>0){
				
					$email = $user_info[0]['primary_email_address'];
				
					$response=array("status"=>200,
									"name"=>$user_info[0]['app_user_first_name']." ".$user_info[0]['app_user_last_name'],
									"email"=>$email,
									"auth_token"=>$token);
				
				}
			}else{
				
				//$response['status'] = 400;
				//$response['status_message'] = "User not found";
				header('HTTP/1.1 400 Session Expired');
				exit();

			}
			
			if(!empty($response))
			{
				echo json_encode($response);
				exit();
			}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
		
	}

	public function profile_settings(){

		$email=$this->input->get('email');
		$result= $this->common_model->RetriveRecordByWhereRow('ks_users', array('primary_email_address'=>$email)); ////// Get user all information
		$app_user_id= $result['app_user_id'];				////////// Get user id

		$contact_info=$this->home_model->GetContactInfo($app_user_id); //Contact info of users
		$result['contact_info']=$contact_info;

		$bank_info=$this->home_model->GetBankDetails($app_user_id); //Contact info of users
		$result['bank_info']=$bank_info;

		if(!empty($result))
		{
			echo json_encode($result);
			exit();
		}else{
			//header('HTTP/1.1 400 Bad Request');
			//exit();
			$response=array("status"=>400,
							"status_message"=>"Bad Request");
			echo json_encode($response);
			exit();
		}

	}


	function userinfo($token){

		$app_user_id = $this->common_model->fetchTokenDetails($token);
		return $app_user_id;
	}

	function alphanumericAndSpace( $string )
    {
        return preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
    }

	public function modify_data(){

		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){

		$token 			= $post_data['token'];

		$app_user_id = $this->userinfo($token);

		if($app_user_id>0){

			//Record is inserted into the table
			if(isset($post_data['app_username']) && empty($post_data['app_username'])==false){

				$slug_name =  $this->alphanumericAndSpace(str_replace('-', " ", trim($post_data['app_username'])) );
				$slug_name = str_replace(' ', "-", $slug_name );

					$data['app_username'] = $slug_name;
					$where = array(
									'app_username'=>$data['app_username'],
									'app_user_id !='=>$app_user_id
									);
					$query =  $this->common_model->GetAllWhere('ks_users',$where);
					$user_data	= $query->row();
					if (!empty($user_data)) {
						$response['status'] = 204;
						$response['status_message'] = 'Username already exists!';
						$json_response = json_encode($response);
						echo $json_response;
						exit();
					}

			}

			else if(!isset($post_data['app_username']) && empty($post_data['app_username'])==true){

				$response['status'] = 204;
				$response['status_message'] = 'Username is required';
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			}
			// if (isset($_FILES['profile_photo_image']) && empty($_FILES['photo'])==false) {
			// 	# code...
			// }
			/*if(isset($_FILES['photo']) && empty($_FILES['photo'])==false){

				$tmp_name = $_FILES["photo"]["tmp_name"];

				$upload_url = "http://www.ingminds.com/projects/kitshare/server/profile/file_upload";

				//getting file info from the request
				$fileinfo = pathinfo($_FILES['photo']['name']);

				//getting the file extension
				$extension = $fileinfo['extension'];

				$params = array(
						 	'photo'=>$tmp_name,
							'extension'=>$extension
						 );

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_VERBOSE, 1);
				curl_setopt($ch, CURLOPT_URL, $upload_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
				echo $response = curl_exec($ch);
				curl_close($ch);

				exit();
			}*/
			if (array_key_exists("registered_for_gst",$post_data))
			{
				$data['registered_for_gst'] = $post_data['registered_for_gst'];
			}

			if(isset($post_data['app_user_first_name']) && empty($post_data['app_user_first_name'])==false)
				$data['app_user_first_name'] = $post_data['app_user_first_name'];
			else if(!isset($post_data['app_username']) && empty($post_data['app_username'])==true){
				$data['app_user_first_name'] = "";
			}

			if(isset($post_data['app_user_last_name']) && empty($post_data['app_user_last_name'])==false)
				$data['app_user_last_name'] = $post_data['app_user_last_name'];
			else if(!isset($post_data['app_username']) && empty($post_data['app_username'])==true){
				$data['app_user_last_name'] = "";
			}

			if(isset($post_data['user_birth_date']) && $post_data['user_birth_date']!=""){


				$data['user_birth_date']= $post_data['user_birth_date'] ;

			}else if(!isset($post_data['user_birth_date']) && empty($post_data['user_birth_date'])==true){

				$data['user_birth_date'] = "";
			}

			// if(isset($post_data['australian_business_number']) && $post_data['australian_business_number']!=""){
				$data['australian_business_number'] = $post_data['australian_business_number'];
			// }else if(!isset($post_data['australian_business_number']) && empty($post_data['australian_business_number'])==true){
			// 	$data['australian_business_number'] = "";
			// }

			// if(isset($post_data['user_description']) && $post_data['user_description']!=""){
				$data['user_description'] = $post_data['user_description'];
			// }else if(!isset($post_data['user_description']) && empty($post_data['user_description'])==true){
			// 	$data['user_description'] = "";
			// }

			// if(isset($post_data['primary_mobile_number']) && $post_data['primary_mobile_number']!=""){
			// 	$data['primary_mobile_number'] = $post_data['primary_mobile_number'];
			// }else if(!isset($post_data['primary_mobile_number']) && empty($post_data['primary_mobile_number'])==true){
			// 	$data['primary_mobile_number'] = "";
			// }

			if(isset($post_data['ks_renter_type_id']) && $post_data['ks_renter_type_id']!=""){
				$data['ks_renter_type_id'] = $post_data['ks_renter_type_id'];
			}else if(!isset($post_data['ks_renter_type_id']) && empty($post_data['ks_renter_type_id'])==true){
				$data['ks_renter_type_id'] = "";
			}

			if(isset($post_data['profession_type_id']) && $post_data['profession_type_id']!="" && $post_data['profession_type_id']=="Other"){
				$profession_type_id = $post_data['profession_type_id'];
				//$data['profession_type_id'] = $post_data['profession_type_id'];

				//Checked whether there is any data in the ks_user_professions table against this profession corresponding to the user
				$where_clause = array("app_user_id"=>$app_user_id);
				$cnt = $this->common_model->CountWhere("ks_user_professions",$where_clause);

				if($cnt>0){

					//Record is updated
					$update_data['profession_type_id'] = $profession_type_id;
					$update_data['update_user'] = $app_user_id;
					$update_data['update_date'] = date("Y-m-d");

					$this->common_model->UpdateRecord($update_data,"ks_user_professions","app_user_id",$app_user_id);

				}else{

					$insert_data['app_user_id'] = $app_user_id;
					$insert_data['profession_type_id'] = $profession_type_id;
					$insert_data['create_user'] = $app_user_id;
					$insert_data['create_date'] = date("Y-m-d");

					$this->common_model->AddRecord($insert_data,"ks_user_professions");

				}


			}else if(isset($post_data['profession_type_id']) && $post_data['profession_type_id']=="Other"){

				$profession_type_id = "";

				if(isset($post_data['other_profession']) && empty($post_data['other_profession'])==false){

					$insert_data['app_user_id'] = $app_user_id;
					$insert_data['profession_name'] = $post_data['profession_name'];
					$insert_data['add_profession_requested_by'] = $app_user_id;
					$insert_data['add_profession_requested_date'] = date("Y-m-d");
					$insert_data['is_active'] = 'N';

					$this->common_model->AddRecord($insert_data,"ks_profession_types");

				}

			}else if(!isset($post_data['profession_type_id']) && empty($post_data['profession_type_id'])==true){

				$profession_type_id = "";

			}

			
				$data['imdb_link'] = $post_data['imdb_link'];
			

			if(isset($post_data['showreel_link']) && $post_data['showreel_link']!=""){
				$data['showreel_link'] = $post_data['showreel_link'];
			}else if(!isset($post_data['showreel_link']) && empty($post_data['showreel_link'])==true){
				$data['showreel_link'] = "";
			}

			
				$data['instagram_link'] = $post_data['instagram_link'];
			
			
				$data['facebook_link'] = $post_data['facebook_link'];
			

			
			$data['vimeo_link'] = $post_data['vimeo_link'];
			
			//if(isset($post_data['youtube_link']) && $post_data['youtube_link']!=""){
				$data['youtube_link'] = $post_data['youtube_link'];

			if (array_key_exists('show_business_name' ,$post_data)) {
				$data['show_business_name'] = $post_data['show_business_name'];
			}else{
				$data['show_business_name'] = 'N';
			}


		//	}else if(!isset($post_data['youtube_link']) && empty($post_data['youtube_link'])==true){
			//	$data['youtube_link'] = "";
			//}
			//youtube_link
			if(isset($post_data['flikr_link']) && $post_data['flikr_link']!=""){
				$data['flikr_link'] = $post_data['flikr_link'];
			}else if(!isset($post_data['flikr_link']) && empty($post_data['flikr_link'])==true){
				$data['flikr_link'] = "";
			}

			if(isset($post_data['app_business_name']) && $post_data['app_business_name']!=""){
				$data['bussiness_name'] = $post_data['app_business_name'];
			}else if(!isset($post_data['app_business_name']) && empty($post_data['app_business_name'])==true){
				$data['bussiness_name'] = "";
			}
			
				$data['twitter_link'] = $post_data['twitter_link'];
			
			
				$data['linkedin_link'] = $post_data['linkedin_link'];
			

				$data['user_website'] = $post_data['website_link'];
			
			if(isset($post_data['profession_type_id']) && $post_data['profession_type_id']!=""){
					if ($post_data['profession_type_id'] == '166') {
						$data['user_profession_other']  = $post_data['other_profession'] ;
					}else{
						$data['user_profession_other']  = '';
					}

				$data['profession_type_id'] = $post_data['profession_type_id'];
			}else if(!isset($post_data['profession_type_id']) && empty($post_data['profession_type_id'])==true){
				$data['profession_type_id'] = "";
			}
			
			if($this->common_model->UpdateRecord($data,"ks_users","app_user_id",$app_user_id)){
			//echo $this->db->last_query();die;
				//$data['profession_type_id'] = $profession_type_id;

				$response=array("status"=>200,
								"status_message"=>"success",
								"result"=>$data);
				echo json_encode($response);
				exit();

			}else{

				//header('HTTP/1.1 417 Expectation Failed');
				//exit();
				
				$response=array("status"=>417,
								"status_message"=>"Expectation Failed");
				echo json_encode($response);
				exit();
			}
		}else{

			header('HTTP/1.1 400 Session Expired');
			exit();
			/*$response=array("status"=>401,
								"status_message"=>"Unauthorized");
			echo json_encode($response);
			exit();*/

		}
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}

	//Addfavourite
	public function Addfavourite()
	{

		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id!="") {
			
			//Checked whether this is logged in user's gear only
			$query =$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y','user_gear_desc_id'=>$post_data['user_gear_desc_id'],'create_user'=>$app_user_id));
			$num_gears = $query->row();
			if($num_gears!=''){
				
				$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'You are the owner of this gear, it cannot be added to favourites');
				echo json_encode($response);
				exit();
				
			}			

			$query =$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y','user_gear_desc_id'=>$post_data['user_gear_desc_id']));
			$gears = $query->row();

			if ($gears == ''){
				$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'No gear found.');
							echo json_encode($response);
							exit();
			}else{

				$query =$this->common_model->GetAllWhere("ks_user_gear_favourite",array('user_gear_desc_id'=>$post_data['user_gear_desc_id'] , 'app_user_id'=> $app_user_id));
				$gears_fav = $query->row();
				if($gears_fav != ''){
						$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Gear Already Added to favourite list ');
							echo json_encode($response);
							exit();
				}else{
					$add_data = array(
										'app_user_id'=>$app_user_id,
										'user_gear_desc_id'=> $post_data['user_gear_desc_id'],
										'created_date'=> date('Y-m-d'),
										'create_time'=>date('H:i:s')
									);
					$response = $this->common_model->AddRecord($add_data,"ks_user_gear_favourite");
					if($response > 1 ){
						$response=array("status"=>200,
								"status_message"=>"sucess",
								"result"=>'Added gear to favourite list sucessfully');
							echo json_encode($response);
							exit();
					}else{
							$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Something went wrong while adding gear to favourite list ');
							echo json_encode($response);
							exit();
					}
				}

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

	 // User  favouriteList / Search favouriteList
	public function favouriteList()
	{

		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id!="") {
				$where='';
				if (isset($post_data['gear_name']) && $post_data['gear_name'] !='') {

					$where .= " a.gear_name LIKE '%".trim($post_data['gear_name'])."%' ";

				}

				$fav_list =$this->home_model->GearFavList($app_user_id,$where);

				$response=array("status"=>200,
								"status_message"=>"sucess",
								"result"=>$fav_list);
							echo json_encode($response);
							exit();

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

	public function Removefavourite()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id!="") {
			$query =$this->common_model->GetAllWhere("ks_user_gear_favourite",array('ks_favourite_id'=>$post_data['ks_favourite_id'] , 'app_user_id'=> $app_user_id));
			$gears_fav = $query->row();
			if ($gears_fav =='') {
						$response=array("status"=>400,
								"status_message"=>"success",
								"result"=>'Gear is not added to list.');
							echo json_encode($response);
							exit();
			}else{
					$this->common_model->delete("ks_user_gear_favourite",$post_data['ks_favourite_id'],"ks_favourite_id");
					$response=array("status"=>200,
								"status_message"=>"sucess",
								"result"=>'Deleted gear to favourite list sucessfully');
							echo json_encode($response);
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

	public function UserProfileDetails($value='')
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		if(array_key_exists("app_username",$post_data)){
			
			$app_username = $post_data['app_username'];
			
			$columns ="app_user_id,app_user_first_name,app_user_last_name,app_username,australian_business_number,user_unique_id_number,
					   primary_email_address,primary_mobile_number,user_description,user_profile_picture_link,imdb_link,
					   showreel_link,instagram_link,facebook_link,vimeo_link,youtube_link,user_website,show_business_name,bussiness_name,					   flikr_link,twitter_link,linkedin_link,create_date,user_birth_date,ks_renter_type_id,profession_type_id,user_profession_other,aus_post_verified,mobile_number_verfiy";
			
			$table = "ks_users";
			$where_clause = array('app_username'=>$app_username);			
			$query = $this->common_model->GetSpecificValues($columns,$table,$where_clause);
			$result = $query->result_array();
			$app_user_id = $result[0]['app_user_id'];
			
			unset($result[0]['app_user_id']);
			
		}else if(array_key_exists("app_user_id",$post_data)){
			
			$app_user_id =  $post_data['app_user_id'];
			
		}else if(array_key_exists("token",$post_data)){
		
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
		}

		if ($app_user_id!="") {
			
			if(!array_key_exists("app_username",$post_data)){
			
				if ($post_data['app_user_id'] != '') {
					$app_user_id 			= $post_data['app_user_id'];
				}

				$columns ="app_user_first_name,app_user_last_name,app_username,australian_business_number,user_unique_id_number,
						   primary_email_address,primary_mobile_number,user_description,user_profile_picture_link,imdb_link,
							showreel_link,instagram_link,facebook_link,vimeo_link,youtube_link,user_website,show_business_name,bussiness_name,
							flikr_link,twitter_link,linkedin_link,create_date,user_birth_date,ks_renter_type_id,profession_type_id,user_profession_other,aus_post_verified,mobile_number_verfiy";
				$table = "ks_users";
				$where_clause = array('app_user_id'=>$app_user_id);

				$query = $this->common_model->GetSpecificValues($columns,$table,$where_clause);
				$result = $query->result_array();
				//$result[0]['review'] = '0';

			}
				if($query->num_rows()>0){
					//Average rating
					$sql= "SELECT AVG(star_rating)  As rating FROM ks_cust_gear_reviews WHERE  app_user_id = '".$app_user_id."'";
					$query = $this->db->query($sql);
					$rating = $query->row();
					// review Count

					$sql= "SELECT AVG(rating) As rating  FROM ks_user_rating As tbl_rating WHERE  app_user_id = '".$app_user_id."'";
					$query = $this->db->query($sql);
					$user_ratig = $query->row();
					$user_ratig = round($user_ratig->rating);

					$review_result= $this->home_model->ProductReview($app_user_id); ////// get user's all product review array
					$reviews=$review_result->result_array();
					//print_r(count($reviews));
					//reference count
					$sql= "SELECT count(ks_user_reference_id) As reference FROM ks_user_reference WHERE  app_user_id = '".$app_user_id."'";
					$query = $this->db->query($sql);
					$reference = $query->row();


					$query = $this->common_model->GetAllWhere('ks_profession_types',array('profession_type_id'=> $result[0]['profession_type_id']));
					$profession=  $query->row();



					$this->db->select('ks_states.ks_state_name');
					$this->db->from('ks_user_address As g_l');
					$this->db->where('g_l.app_user_id',$app_user_id);
					$this->db->where('g_l.is_active','Y');
					// $this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
					$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = g_l.ks_suburb_id " ,"INNER");
					$this->db->join('ks_states'," ks_states.ks_state_id = g_l.ks_state_id " ,"INNER");
					$query = $this->db->get();
					$address  =$query->result_array();
					//print_r($address);
					unset($result[0]['primary_email_address']);
					unset($result[0]['primary_mobile_number']);
					unset($result[0]['australian_business_number']);

					$result[0]['address'] = $address;
					
					
					//Checked whether autralian post digital verified address
					/*$query = $this->common_model->GetAllWhere('ks_austlian_digitalid_details',array('app_user_id '=> $app_user_id));
					$aus_address=  $query->row();
					
					if(!empty($aus_address))
						$result[0]['address_found']="Y";
					else
						$result[0]['address_found']="N";*/
					
					if($result[0]['aus_post_verified']=='Y')
						$result[0]['address_found']="Y";
					else
						$result[0]['address_found']="N";	
					
					
					$result[0]['rating'] = number_format((float)( $rating->rating), 0, '.', '');
					if (!empty($profession)) {
						$result[0]['profession_name'] = $profession->profession_name;
					}else{
						$result[0]['profession_name'] = array();
					}

					$result[0]['review'] = count($reviews);
					$result[0]['user_rating'] = $rating ;
					$result[0]['reference'] =$reference->reference;
					// if($result[0]['user_description']=="")
					// 	$result[0]['user_description']="Say something about yourself...";
					$result[0]['user_description'] =nl2br($result[0]['user_description']);
					if($result[0]['user_profile_picture_link']=="")
						$result[0]['user_profile_picture_link']=base_url()."/assets/images/profile.png";
					if ($result[0]['user_birth_date'] != '0000-00-00') {

						$result[0]['user_birth_date'] = date('d-m-Y' , strtotime($result[0]['user_birth_date']));
					}
					unset($result[0]['user_birth_date']);
					if ($result[0]['show_business_name'] == 'Y') {
						$result[0]['app_user_first_name'] = $result[0]['bussiness_name'];
						$result[0]['app_user_last_name'] = '';
					}

					$response=array("status"=>200,
									"status_message"=>"success",
									"result"=>$result

									);
					echo json_encode($response);
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

	public function baseimage()
	{
		$image = $this->input->post('image');
			 $data = explode( 'data:image/jpeg;base64,	', $image );
			$image_data = base64_decode($data[1]);
			echo $file=$_SERVER["DOCUMENT_ROOT"].'/projects/kitshare/server/uploads/profile/' .uniqid().'.png';
		file_put_contents($image, $image_data);
	}

	public function VerifyMobile()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id!="") {
			$six_digit_random_number = mt_rand(100000, 999999);
			$msg=  $six_digit_random_number. "  is your security code for mobile verification. Please do not reply";
			$to = $post_data['phone_number'];
			// $to = '61416144773';
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://api.smsbroadcast.com.au/api-adv.php?username=kitshare&password=88sms11gate&to=".$to."&from=kitshare&message=".urlencode($msg)."&ref=112233&maxsplit=1",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "",
			  CURLOPT_HTTPHEADER => array(
			    "Postman-Token: 89e64ffe-784d-4d89-9292-91b035e6a6dd",
			    "cache-control: no-cache"
			  ),
			));
			$response_msg = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
			 // echo "cURL Error #:" . $err;
			  	$app_user_id = '';
				$response['status'] = 404;
				$response['status_message'] = $err;
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			} else {
			   $update_data = array(
			   					'mobile_number_otp'=>$six_digit_random_number ,
			   					'primary_mobile_number'=>$post_data['phone_number'],
			   					'mobile_number_verfiy'=>'0'
			   					);
			  	$this->common_model->UpdateRecord($update_data,"ks_users","app_user_id",$app_user_id);
			  	$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Message is sent to registered mobile';
				$response['otp_code'] = $six_digit_random_number;
				$json_response = json_encode($response);
				echo $json_response;
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

	public function VerifyOTP()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id!="") {
			if (array_key_exists("otp",$post_data))
			{

			}else{
				$app_user_id = '';
				$response['status'] =400;
				$response['status_message'] = 'OTP is required';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}
			if (array_key_exists("phone_number",$post_data))
			{

			}else{
				$app_user_id = '';
				$response['status'] =400;
				$response['status_message'] = 'Phone Number is required';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}
			 $query =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
			 $user_data = $query->row();
			 if (!empty($user_data)) {
			  	//print_r($user_data->mobile_number_otp);
			  	if( $post_data['otp'] != $user_data->mobile_number_otp ) {
			  		$response['status'] =400;
					$response['status_message'] = 'Otp does not match';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
			  	}else{
			  		$update_data = array(
			   					'mobile_number_otp'=>'' ,
			   					'mobile_number_verfiy'=>'1'
			   					);
			  		$this->common_model->UpdateRecord($update_data,"ks_users","app_user_id",$app_user_id);
			  		$response['status'] =200;
					$response['status_message'] = 'Mobile Number verification successfully done';
					$json_response = json_encode($response);
					echo $json_response;
			  	}
			 }else{
			 		$response['status'] =404;
					$response['status_message'] = 'User Does not exist';
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
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	public function UpdateUserEmail()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

			if (array_key_exists("primary_email_address",$post_data) && !empty($post_data['primary_email_address']))
			{

			}else{
				$app_user_id = '';
				$response['status'] =400;
				$response['status_message'] = 'Email Address is required';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}
		if ($app_user_id!="") {
			$update_data = array(
			   					'primary_email_address'=>$post_data['primary_email_address']

			   					);
			  		$this->common_model->UpdateRecord($update_data,"ks_users","app_user_id",$app_user_id);
			  		$response['status'] =200;
					$response['status_message'] = 'Email Address Updated Successfully ';
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
	public function UpdateUserUsername()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

			if (array_key_exists("app_username",$post_data) && !empty($post_data['app_username']))
			{

			}else{
				$app_user_id = '';
				$response['status'] =400;
				$response['status_message'] = ' User Name  is required';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}
		if ($app_user_id!="") {
			$query = $this->common_model->GetAllWhere('ks_users',array('app_username'=>$post_data['app_username'] , 'app_user_id !='=>$app_user_id) );
			$user_details =  $query->row();

			if (!empty($user_details)) {
				$app_user_id = '';
				$response['status'] =400;
				$response['status_message'] = ' Username is not available' ;
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}else{
			$update_data = array(
			   					'app_username'=>$post_data['app_username']

			   					);
			  		$this->common_model->UpdateRecord($update_data,"ks_users","app_user_id",$app_user_id);
			  		$response['status'] =200;
					$response['status_message'] = 'UserName Updated Successfully ';
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
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}

	public function ChangePassword()
	{

		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id , 'app_password'=>md5(strrev($post_data['old_password']))));
			$user_details = 	$query->row();
			//print_r($user_details);
			if (!empty($user_details)) {
					$update_data = array(
			   					'app_password'=>md5(strrev($post_data['new_password']))

			   					);
			  		$this->common_model->UpdateRecord($update_data,"ks_users","app_user_id",$app_user_id);
			  		$response['status'] =200;
					$response['status_message'] = 'Password Updated Successfully ';
					$json_response = json_encode($response);
					echo $json_response;
			}else{
				$app_user_id = '';
				$response['status'] = 400;
				$response['status_message'] = 'Old password does not match';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
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

	public function AddUnavailbleDates()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$query = $this->common_model->GetAllWhere('ks_user_gear_description',array('app_user_id'=>$app_user_id));
			$gear_details = 	$query->result();
			if (!empty($gear_details)) {
				foreach ($gear_details as  $value) {
					$unavailble_dates = array(
											'user_gear_description_id'=>$value->user_gear_desc_id ,
											'unavailable_from_date'=>$post_data['from_date'],
											'unavailable_to_date'=> $post_data['to_date'],
											'create_user'=> $app_user_id,
											'create_date'=> date('Y-m-d'),
										);
					$this->common_model->InsertData('ks_gear_unavailable',$unavailble_dates);
				}
				$response['status'] = 200;
				$response['status_message'] = 'Unavailble Dates added successfully';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}else{
				$response['status'] = 404;
				$response['status_message'] = 'No Gear Found';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
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

	//connect Payout

	public function ConnectPayoutAdd()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){

		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$where_clause = array(
									'app_user_id' => $app_user_id ,
									'is_active' => 'Y',
								);
			$query = $this->common_model->GetAllWhere('ks_user_bank_details',$where_clause);
			$data = $query->row();
			if (!empty($data)) {
					$where_clause = array(
									'app_user_id' => $app_user_id ,
								);
					$this->db->where('app_user_id',$app_user_id);
					$this->db->update('ks_user_bank_details',array('is_active'=> 'N'));
			}
			$insert_data = array(
									'bsb_number'=> $post_data['bsb_number'],
									'bank_id'=> $post_data['bank_id'],
									'user_account_number'=> $post_data['user_account_number'],
									'user_account_name'=> $post_data['user_account_name'],
									'is_active'=> 'Y',
									'create_date'=> date('Y-m-d'),
									'create_user'=> $app_user_id,
									'app_user_id'=> $app_user_id,
								);
			$this->common_model->InsertData('ks_user_bank_details',$insert_data);
			$app_user_id = '';
			$response['status'] = 200;
			$response['status_message'] = 'Bank Details added successfully ';
			$json_response = json_encode($response);
			echo $json_response;
			exit ;
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

	// Get User Bank Details

	public function GetUserBankDetails()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$where_clause = array(
									'app_user_id' => $app_user_id ,
									'is_active' => 'Y',
								);
			$query = $this->common_model->GetAllWhere('ks_user_bank_details',$where_clause);
			$data = $query->row();
			if (!empty($data)) {
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'User Bank Details';
					$response['result'] = $data;
					$json_response = json_encode($response);
					echo $json_response;
			}else{
					$app_user_id = '';
					$response['status'] = 404;
					$response['status_message'] = 'No Bank Details Found';
					$json_response = json_encode($response);
					echo $json_response;
			}
		}else{
				/**$app_user_id = '';
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


	// Bank List
	public function GetBankList()
	{
		$query = $this->common_model->GetAllWhere('ks_banks',array('is_active'=>'Y'));
		$bank_data = $query->result();
		$response['status'] = 200;
		$response['status_message'] = 'Bank List';
		$response['bank_list'] = $bank_data;
		$json_response = json_encode($response);
		echo $json_response;
		exit;

	}

	// Connect Owner API

	public function ContactOwner()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
			$sender_details = $query->row();

			$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$post_data['app_user_id']));
			$receiver_details = $query->row();
			
			//Checked whether there is any data corresponding to this user in the ks_owner_mail table
			$where_array = "((sender_id='".$app_user_id."' and receiver_id='".$post_data['app_user_id']."') OR (sender_id='".$post_data['app_user_id']."' and receiver_id='".$app_user_id."')) AND message_code!=''";
			$query = $this->common_model->GetAllWhere('ks_owner_mail',$where_array);
			$message_details = $query->row();
			
			if($message_details->message_code!="")
				$message_code = $message_details->message_code;
			else
				$message_code = time()."-".$app_user_id.$post_data['app_user_id'];
			
         	$insert_data = array(
								'message_code'=>$message_code,
         						'sender_id' => $app_user_id ,
         						'receiver_id' => $post_data['app_user_id'] ,
         						'mail_type' => 'Sender' ,
         						'mail_text' => $post_data['mail_text'] ,
         						'mail_status' => 'Send' ,
         						'sender_email_id' =>$sender_details->primary_email_address,
         						'receiver_email_id' => $receiver_details->primary_email_address,
         						'created_by' => $app_user_id ,
         						'created_date'=>date('Y-m-d'),
         						'created_time'=>date('H:m:i'),
         					);

         	$insert_id =  $this->common_model->InsertData('ks_owner_mail',$insert_data);
			
			if($insert_id>0){
				
				//Record is inserted into the Chat table 
				$chat_insert_data = array(
								'chat_user_id' => $app_user_id,
         						'sender_id' => $app_user_id,
         						'receiver_id' => $post_data['app_user_id'] ,
         						'message_type' => 'Contact' ,
         						'message' => $post_data['mail_text'] , 
								'message_code'=>$message_code,
								'created_by' => $app_user_id ,
         						'create_date'=>date('Y-m-d'),
         						'created_time'=>date('H:m:i'),
         		);
				
				$this->common_model->InsertData('ks_user_chatmessage',$chat_insert_data);
				
			}
			
			
			
			
         	$url = WEB_URL_1.'/reply/'.$receiver_details->primary_email_address.'/'.$insert_id.'/'.$sender_details->app_username ;
         	$user_name = $receiver_details->app_user_first_name .' ' .$receiver_details->app_user_last_name ;
         	$sender_name = $sender_details->app_user_first_name .' ' .$sender_details->app_user_last_name ;
			$data = $this->mail_model->Contact_mail( $user_name,$post_data['mail_text'],$url,$sender_name);
		    //$sender_mail= 'noreply@inferasolz.com';
			$mail_body = $data;
			// print_r($mail_body);die;
			 $to= $receiver_details->primary_email_address;
			//$to= 'singhaniagourav@gmail.com';
			$subject = "Someone sent you a message via Kitshare";
			$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia ",
											),
										"To"=>array(
												array("Email"=>$to,
												"Name"=>"",
												),
											),
										"Subject"=> $subject,
				                        "TextPart"=> "",
				                        "HTMLPart"=> $mail_body
									),
								)
							);
			$this->common_model->sendMail($mail_data);
		//	$this->home_model->send_email($sender_mail,$to,$subject,$mail_body);
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Mail Sent successfully';
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

	//

	public function GetReplyMessage()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
			$where_clause = array(
									'ks_owner_mail_id'=> $post_data['ks_owner_id'],
									'receiver_email_id'=> $post_data['receiver_email_id']
								);
			$query =  $this->common_model->GetAllWhere('ks_owner_mail',$where_clause);
			$data=$query->row();
			if (!empty($data)) {
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = ' Email Details Found';
					$response['result'] = $data;
					$json_response = json_encode($response);
					echo $json_response;
			}else{
					$app_user_id = '';
					$response['status'] = 404;
					$response['status_message'] = 'No Email Details Found';
					$json_response = json_encode($response);
					echo $json_response;
			}
		}else{
			header('HTTP/1.1 200 Success');
			exit();
		}

	}

	//reply message

	public function ReplyMessage()
	{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			
			if(is_array($post_data) && count($post_data)>0 ){
			
			$where_clause = array(
									'ks_owner_mail_id'=> $post_data['ks_owner_id'],
									'receiver_email_id'=> $post_data['receiver_email_id']
								);
			$query =  $this->common_model->GetAllWhere('ks_owner_mail',$where_clause);
			$data = $query->row();
			
			
			//Checked whether there is any data corresponding to this user in the ks_owner_mail table
			$where_array = "((sender_id='".$data->sender_id."' and receiver_id='".$data->receiver_id."') OR (sender_id='".$data->receiver_id."' and receiver_id='".$data->receiver_id."')) AND message_code!=''";
			$query = $this->common_model->GetAllWhere('ks_owner_mail',$where_array);
			$message_details = $query->row();
			
			if($message_details->message_code!="")
				$message_code = $message_details->message_code;
			else
				$message_code = time()."-".$data->sender_id.$data->receiver_id;
			
			$insert_data = array(
								'message_code'=>$message_code,
         						'sender_id' => $data->receiver_id ,
         						'receiver_id' =>$data->sender_id ,
         						'mail_type' => 'Sender' ,
         						'mail_text' => $post_data['mail_text'] ,
         						'mail_status' => 'Send' ,
         						'sender_email_id' =>$data->receiver_email_id ,
         						'receiver_email_id' => $data->sender_email_id ,
         						'created_by' => $data->receiver_id ,
         						'created_date'=>date('Y-m-d'),
         						'created_time'=>date('H:m:i'),
         					);
         	$insert_id =  $this->common_model->InsertData('ks_owner_mail',$insert_data);
			
			if($insert_id>0){
				
				
				//Record is inserted into the Chat table 
				$chat_insert_data = array(
								'chat_user_id' => $data->receiver_id,
         						'sender_id' => $data->receiver_id,
         						'receiver_id' => $data->sender_id,
         						'message_type' => 'Contact' ,
         						'message' => $post_data['mail_text'] , 
								'message_code'=>$message_code,
								'created_by' => $data->receiver_id,
         						'create_date'=>date('Y-m-d'),
         						'created_time'=>date('H:m:i'),
         		);
				
				$this->common_model->InsertData('ks_user_chatmessage',$chat_insert_data);
				
			}
			
			
			
         	$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$data->receiver_id));
			$sender_details = $query->row();
         	$url = WEB_URL_1.'/reply/'.$data->sender_email_id.'/'.$insert_id.'/'.$sender_details->app_username;
         		$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$data->sender_id));
			$receiver_details = $query->row();
         	$user_name = $receiver_details->app_user_first_name .' ' .$receiver_details->app_user_last_name ;
         	$sender_name = $sender_details->app_user_first_name .' ' .$sender_details->app_user_last_name ;
			$data = $this->mail_model->Contact_mail( $user_name,$post_data['mail_text'],$url,$sender_name);
		    //$sender_mail= 'noreply@inferasolz.com';
			$mail_body = $data;
			$to= $receiver_details->primary_email_address;
			// $to= 'singhaniagourav@gmail.com';
			$subject = "Someone sent you a message via Kitshare";
			$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia",
											),
										"To"=>array(
												array("Email"=>$to,
												"Name"=>"",
												),
											),
										"Subject"=> $subject,
				                        "TextPart"=> "",
				                        "HTMLPart"=> $mail_body
									),
								)
							);
			$this->common_model->sendMail($mail_data);

			//$this->home_model->send_email($sender_mail,$to,$subject,$mail_body);

				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Message Sent successfully';
				$json_response = json_encode($response);
				echo $json_response;
				
			}else{
				header('HTTP/1.1 200 Success');
				exit();
				
			}

	}

	//test  Owner Mail
	public function Mail()
	{
		$user_name ='Gourav' ;
		$mail_content ='What is Lorem Ipsum?
		Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

		Why do we use it?
		It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose

		' ;
		 $url = WEB_URL;
		$data = $this->mail_model->Contact_mail( $user_name,$mail_content,$url);
     	print_r($data);
     	die;
	}

	public function CheckduplicateUserName()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){

		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if($app_user_id>0){

				$where = array(
								'app_user_id !='=>$app_user_id,
								'app_username'=>$post_data['app_username'],
							);
				$query =  $this->common_model->GetAllWhere('ks_users',$where);
				$data = $query->row();
				if (empty($data)) {
						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Username is unique';
						$json_response = json_encode($response);
						echo $json_response;
						exit();
				}else{
						$app_user_id = '';
						$response['status'] = 406;
						$response['status_message'] = 'Username already exists!';
						$response['result'] = array();
						$json_response = json_encode($response);
						echo $json_response;
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

	public function GetUserInfoId()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		
		if(is_array($post_data) && count($post_data)>0 ){
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			$where = array('app_username'=>$post_data['app_username']);
			$query = $this->common_model->GetAllWhere('ks_users',$where);
			$data =$query->row();
			
			if ($data->app_user_id!="") {
					$response['status'] = 200;
					$response['status_message'] = 'User Details Found';
					$response['result'] = array('app_user_id'=>$data->app_user_id);
					$json_response = json_encode($response);
					echo $json_response;
					exit();

			}else{
					/*$app_user_id = '';
					$response['status'] = 404;
					$response['status_message'] = 'Users Does not exist';
					$json_response = json_encode($response);
					echo $json_response;
					exit();*/
					header('HTTP/1.1 400 Session Expired');
					exit();
			}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}
	
	
	public function DeleteUnavailableDates(){
		
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$from_date      = $post_data['from_date'];
		$to_date		= $post_data['to_date'];
		
		
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			
			$where_array 	= array('create_user'=>$app_user_id,'unavailable_from_date'=>$from_date,'unavailable_to_date'=>$to_date);
		
		
			//Checked if user_gear_description_id exists in the post data
			if(array_key_exists("gear_id",$post_data)){
				
				$user_gear_description_id 	= $post_data['gear_id'];			
				$where_array1 				= array('user_gear_description_id'=>$user_gear_description_id);			
				$where_array 				= array_merge($where_array,$where_array1);
				
			}
			
			$query = $this->common_model->GetAllWhere('ks_gear_unavailable',$where_array);
			$unavailable_date = 	$query->result();
			

			if (!empty($unavailable_date)) {
				
				foreach($unavailable_date as $value){
				
					//Record is deleted from the table
					$this->common_model->delete("ks_gear_unavailable",$value->ks_gear_unavailable_id,"ks_gear_unavailable_id");
				
				}
				
				$response['status'] = 200;
				$response['status_message'] = 'Unavailable Date is deleted successfully';
				$json_response = json_encode($response);
				echo $json_response;				
				exit;
				
			}else{
				
				$response['status'] = 404;
				$response['status_message'] = 'Date not found.';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
				
			}
		}else{
				/*$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Not Found.';
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
	
	
	public function ListUnavailableDates(){
		
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);
		
		if ($app_user_id != '') {
			
			$where_array 	= array('create_user'=>$app_user_id,'unavailable_to_date>='=>date("Y-m-d"));
			
			
			//Checked if user_gear_description_id exists in the post data
			if(array_key_exists("gear_id",$post_data)){
				
				$user_gear_description_id 	= $post_data['gear_id'];			
				$where_array1 				= array('user_gear_description_id'=>$user_gear_description_id);			
				$where_array 				= array_merge($where_array,$where_array1);
				
			}
			
			$this->db->select('*');
			$this->db->where($where_array);
			$this->db->from("ks_gear_unavailable");
			$this->db->group_by(array("unavailable_from_date", "unavailable_to_date")); 
			$query = $this->db->get();   
			$result = $query->result();
			
			$arr = array();
			$i=0;
			if(count($result)>0){
				foreach($result as $val){
					
					$arr[$i]['from_date']=$val->unavailable_from_date;
					$arr[$i]['to_date']=$val->unavailable_to_date;
					$i++;
				}
				
				$response['status'] = 200;
				$response['status_message'] = 'success';
				$response['result'] = $arr;
				$json_response = json_encode($response);
				echo $json_response;				
				exit;
				
			}else{
				
				$response['status'] = 404;
				$response['status_message'] = 'Date not found.';
				$json_response = json_encode($response);
				echo $json_response;
				exit;				
				
			}		
			
			
			
		}else{
				/*$response['status'] = 401;
				$response['status_message'] = 'User Not Found.';
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
