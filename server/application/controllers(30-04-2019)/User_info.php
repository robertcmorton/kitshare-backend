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
		//$post_data = $_POST;
		
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);
		
		if($app_user_id>0){
			
			if ($post_data['app_user_id'] != '') {
				$app_user_id 			= $post_data['app_user_id'];
			}
				$columns ="app_user_id,app_user_first_name,app_user_last_name,app_username,australian_business_number,user_unique_id_number,
						   primary_email_address,primary_mobile_number,user_description,user_profile_picture_link,imdb_link,
							showreel_link,instagram_link,facebook_link,vimeo_link,youtube_link,
							flikr_link,twitter_link,linkedin_link,create_date,user_birth_date,ks_renter_type_id,profession_type_id,aus_post_verified,aus_post_transcation_id,is_active,user_website,bussiness_name ,registered_for_gst,user_profession_other AS other_profession ,mobile_number_verfiy,aus_post_verified";	
				$table = "ks_users";
				$where_clause = array('app_user_id'=>$app_user_id);
				
				$query = $this->common_model->GetSpecificValues($columns,$table,$where_clause);
				$result = $query->result_array();
				
				//$result[0]['review'] = '0';
				
				
				if($query->num_rows()>0){
					//Average rating 
					$sql= "SELECT AVG(gear_star_rating_value) As rating FROM ks_cust_gear_star_rating WHERE  app_user_id = '".$app_user_id."'";
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
						$result[0]['address'] = $address;
					}else{
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
						 $rating =$rating->rating ;
					}else{
						 $rating  = 0 ;
					}

					$result[0]['rating'] = $rating;
					$result[0]['review'] = count($reviews);
					$result[0]['reference'] =$reference->reference;
					if($result[0]['user_description']=="")
						$result[0]['user_description']="Say something about yourself...";
						
					if($result[0]['user_profile_picture_link']=="")
						$result[0]['user_profile_picture_link']= base_url()."/assets/images/profile.png";
					if ($result[0]['user_birth_date'] != '0000-00-00') {

						$result[0]['user_birth_date'] = date('d-m-Y' , strtotime($result[0]['user_birth_date']));
					}
					if ($post_data['action'] != 'edit') {
						$result[0]['user_description'] =nl2br($result[0]['user_description']);
					}else{

					}
					
					$response=array("status"=>200,
									"status_message"=>"success",
									"result"=>$result
										
									);
					echo json_encode($response);
					exit();
				}else{
					header('HTTP/1.1 400 Bad Request');
					exit();
					//$response['status'] = 400;
					//$response['status_message'] = "User not found";
				
				}
			}else{
			
				header('HTTP/1.1 401 Unauthorized');
				exit();
			}
		
	
	}
	
	public function gear_data(){
	
		$post_data      = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		//print_r($post_data);
		$data=array();
		$limit=12;
		$data['limit'] = $limit ;
		$order_by ='';
		$where = '';
		//print_r($post_data['gear_category_id']);

		if (isset($post_data['gear_category_id']) && $post_data['gear_category_id'] !='') {
			
			//$where['a.user_gear_desc_id'] =$post_data['ks_gear_id'];
			$where .= "a.ks_category_id = '".$post_data['gear_category_id']."'  ";
		}

		if (isset($post_data['gear_name']) && $post_data['gear_name'] !='') {
		//	print_r($post_data['ks_gear_id']);
			if ($where != '') {
				$where .= " AND a.gear_name LIKE '%".trim($post_data['gear_name'])."%' ";	
			}else{
				$where .= " a.gear_name LIKE '%".trim($post_data['gear_name'])."%' ";	
			}
			
		}
		if (isset($post_data['gear_status']) && $post_data['gear_status'] !='') {
		//	print_r($post_data['ks_gear_id']);
			if ($where != '') {
				$where .= " AND a.is_active = 'Y' ";	
			}else{
				$where .= " a.is_active = 'Y' ";	
			}
			
		}
		if (isset($post_data['type']) && $post_data['type'] !='') {
		//	print_r($post_data['ks_gear_id']);
			if ($post_data['type'] == 'public') {
				if ($where != '') {
					$where .= " AND a.gear_hide_search_results = 'Y' ";	
				}else{
					$where .= "  a.gear_hide_search_results = 'Y' ";	
				}
				
			}elseif($post_data['type'] == 'private') {
				if ($where != '') {
					$where .= " AND a.gear_hide_search_results = 'N' ";	
				}else{
					$where .= "  a.gear_hide_search_results = 'N' ";	
				}
			}
			
			
		}	
 		
		//die;
		if (!empty($post_data['app_user_id'])) {
			$app_user_id = $post_data['app_user_id'];
		}else{
			 $app_user_id = $this->userinfo($token);		
		}

		if($this->input->get("per_page")!= ''){
			$offset = $this->input->get("per_page");
		}else{
			$offset=0;
		}
		 
		$gear1  = $this->home_model->GearCategories($app_user_id,$where,$data['limit'],$offset,$order_by);			      
		// echo "<pre>";
		// print_r($gear1);die;	
		$gears = $this->home_model->GearCategories($app_user_id,$where);//Gear Categories as well as Gear lists are popluated in the array     
		
		 $total_rows =count($gears['gear_lists']);
		
		$data['total_rows']=$total_rows;

		$data['limit']=$data['limit'];
	
		$config['base_url'] = base_url()."user_info/gear_data?order_by=".$order_by."&limit=".$data['limit'];

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
			header('HTTP/1.1 400 Bad Request');
			//$response['status'] = 400;
			//$response['status_message'] = "Gears not found";
		
		}
		
	
	}
	
	
	public function product_reviews(){
	
		$post_data      = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);		
	
		$review_result= $this->home_model->ProductReview($app_user_id); ////// get user's all product review array			
		$reviews=$review_result->result_array();
		
		
		$review_lists=array();
			
		if(count($reviews)>0){
			foreach($reviews as $review_result){
				 //Array is replaced with the image path
				 foreach($review_result as $key=>$value){
					 //  if($key=="user_profile_picture_link"){
						// if( $review_result[$key]!=""){				
						// 	//$img_path=base_url().PROFILE_IMG.$value;
						// 	$img_path = "";
						// 	$review_result[$key]=$img_path;
						// }else if(!strstr($value,"https:") && $review_result[$key]==""){
						// 	//$review_result[$key]=base_url().PROFILE_IMG."default-profile-pic.jpg";
						// 	$img_path = "";
						// 	$review_result[$key]=$img_path;
						// }
					 //  }
				 }
				 
				 array_push($review_lists,$review_result);
				
			}	 
		}
			
		$query['review_result']=$review_lists;	
		
		
		$response=array("status"=>200,
						"status_message"=>"success",
						"result"=>$query['review_result']);
							
		
		
		echo json_encode($response);
		exit();
	
	}
	
	
	public function user_credentials(){
		
		$post_data      = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		//$email=$this->input->get('email');
		
		$token_decrypt = base64_decode($token);
		$token_array = explode("|",$token_decrypt);
		
		
		$email = $token_array[0];
		$secret_key = $token_array[1];
		$expire_time = $token_array[2];
		
		
		
		$query= $this->common_model->RetriveRecordByWhereRow('ks_users', array('primary_email_address'=>$email,'auth_secret_key'=>$secret_key,'expire_time'=>$expire_time)); ////// Get user all information
		$app_user_id= $query['app_user_id'];				////////// Get user id
		
		if($app_user_id!=""){
			
			//User info are fetched
			$user_info=$this->home_model->GetProfileInfo($app_user_id);
			
			
			/*if($user_info[0]['user_profile_picture_link']!=""){
				if(!strstr($user_info[0]['user_profile_picture_link'],"https:")){				
					$img_path=base_url().PROFILE_IMG.$user_info[0]['user_profile_picture_link'];
					$user_info[0]['user_profile_picture_link']=$img_path;
				}
			 }else{
				 $user_info[0]['user_profile_picture_link']=base_url().PROFILE_IMG."default-profile-pic.jpg";
			 }*/
			
			$result['user_info']=$user_info;	
			
			$response=array("status"=>200,
							"name"=>$user_info[0]['app_user_first_name']." ".$user_info[0]['app_user_last_name'],
							"email"=>$email,
							"auth_token"=>$token);
		}else{
			
			$response['status'] = 400;
			$response['status_message'] = "User not found";
		
		}
		
		if(!empty($response))
		{
			echo json_encode($response);
			exit();
		}
		
	}
	
	public function profile_settings(){
	
		$email=$this->input->get('email');
		//$email='imcsuvankar@gmail.com';
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
			header('HTTP/1.1 400 Bad Request');
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
		if (count($row) > 0) {
			$app_user_id = $row[0]['app_user_id'];
		}else{

			$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}
		
		
		return $app_user_id;

	}
	
	
	public function modify_data(){
	
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		//$post_data = $_POST;
		
		$token 			= $post_data['token'];
		//echo $token ;
		//print_r($post_data);die;
		$app_user_id = $this->userinfo($token);
		//print_r($app_user_id);die;
		if($app_user_id>0){
		
			//Record is inserted into the table
			if(isset($post_data['app_username']) && empty($post_data['app_username'])==false)
				$data['app_username'] = $post_data['app_username'];
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
			
			if(isset($post_data['australian_business_number']) && $post_data['australian_business_number']!=""){
				$data['australian_business_number'] = $post_data['australian_business_number'];
			}else if(!isset($post_data['australian_business_number']) && empty($post_data['australian_business_number'])==true){
				$data['australian_business_number'] = "";
			}
			
			if(isset($post_data['user_description']) && $post_data['user_description']!=""){
				$data['user_description'] = $post_data['user_description'];
			}else if(!isset($post_data['user_description']) && empty($post_data['user_description'])==true){
				$data['user_description'] = "";
			}
			
			if(isset($post_data['primary_mobile_number']) && $post_data['primary_mobile_number']!=""){
				$data['primary_mobile_number'] = $post_data['primary_mobile_number'];
			}else if(!isset($post_data['primary_mobile_number']) && empty($post_data['primary_mobile_number'])==true){
				$data['primary_mobile_number'] = "";
			}
			
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
			
			if(isset($post_data['imdb_link']) && $post_data['imdb_link']!=""){
				$data['imdb_link'] = $post_data['imdb_link'];
			}else if(!isset($post_data['imdb_link']) && empty($post_data['imdb_link'])==true){
				$data['imdb_link'] = "";
			}
			
			if(isset($post_data['showreel_link']) && $post_data['showreel_link']!=""){
				$data['showreel_link'] = $post_data['showreel_link'];
			}else if(!isset($post_data['showreel_link']) && empty($post_data['showreel_link'])==true){
				$data['showreel_link'] = "";
			}
			
			if(isset($post_data['instagram_link']) && $post_data['instagram_link']!=""){
				$data['instagram_link'] = $post_data['instagram_link'];
			}else if(!isset($post_data['instagram_link']) && empty($post_data['instagram_link'])==true){
				$data['instagram_link'] = "";
			}
			
			if(isset($post_data['facebook_link']) && $post_data['facebook_link']!=""){
				$data['facebook_link'] = $post_data['facebook_link'];
			}else if(!isset($post_data['facebook_link']) && empty($post_data['facebook_link'])==true){
				$data['facebook_link'] = "";
			}
			
			if(isset($post_data['vimeo_link']) && $post_data['vimeo_link']!=""){
				$data['vimeo_link'] = $post_data['vimeo_link'];
			}else if(!isset($post_data['vimeo_link']) && empty($post_data['vimeo_link'])==true){
				$data['vimeo_link'] = "";
			}
			
			//if(isset($post_data['youtube_link']) && $post_data['youtube_link']!=""){
				$data['youtube_link'] = $post_data['youtube_link'];
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
			if(isset($post_data['twitter_link']) && $post_data['twitter_link']!=""){
				$data['twitter_link'] = $post_data['twitter_link'];
			}else if(!isset($post_data['twitter_link']) && empty($post_data['twitter_link'])==true){
				$data['twitter_link'] = "";
			}
			
			if(isset($post_data['linkedin_link']) && $post_data['linkedin_link']!=""){
				$data['linkedin_link'] = $post_data['linkedin_link'];
			}else if(!isset($post_data['linkedin_link']) && empty($post_data['linkedin_link'])==true){
				$data['linkedin_link'] = "";
			}
			if(isset($post_data['website_link']) && $post_data['website_link']!=""){
				$data['user_website'] = $post_data['website_link'];
			}else if(!isset($post_data['website_link']) && empty($post_data['website_link'])==true){
				$data['user_website'] = "";
			}
			if(isset($post_data['profession_type_id']) && $post_data['profession_type_id']!=""){
					if ($post_data['profession_type_id'] == '166') {
						$data['user_profession_other']  = $post_data['other_profession'] ;
					}else{
						$data['user_profession_other']  = '';
					}

				$data['profession_type_id'] = $post_data['profession_type_id'];
			}else if(!isset($post_data['profession_type_id']) && empty($post_data['profession_type_id'])==true){
				$data['profession_type_id	'] = "";
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
				
				header('HTTP/1.1 417 Expectation Failed');
				exit();
			}
		}else{
			
			header('HTTP/1.1 401 Unauthorized');
			exit();
		
		}
		
		
		
	}
	
	//Addfavourite 
	public function Addfavourite()
	{
		
		$post_data      = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);	
	
		if ($app_user_id!="") {

			$query =$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y','user_gear_desc_id'=>$post_data['user_gear_desc_id']));
			$gears = $query->row();
		
			if ($gears == ''){
				$response=array("status"=>400,
								"status_message"=>"success",
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
								"result"=>'Went something  wrong while adding gear to favourite list ');
							echo json_encode($response);
							exit();
					} 
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

	 // User  favouriteList / Search favouriteList
	public function favouriteList()
	{
		
		$post_data      = json_decode(file_get_contents("php://input"),true);
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
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}

	public function Removefavourite()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
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
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}

	public function UserProfileDetails($value='')
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);	
	
		if ($app_user_id!="") {
			if ($post_data['app_user_id'] != '') {
				$app_user_id 			= $post_data['app_user_id'];
			}
			
			$columns ="app_user_first_name,app_user_last_name,app_username,australian_business_number,user_unique_id_number,
						   primary_email_address,primary_mobile_number,user_description,user_profile_picture_link,imdb_link,
							showreel_link,instagram_link,facebook_link,vimeo_link,youtube_link,
							flikr_link,twitter_link,linkedin_link,create_date,user_birth_date,ks_renter_type_id,profession_type_id,user_profession_other,aus_post_verified,mobile_number_verfiy";
				$table = "ks_users";
				$where_clause = array('app_user_id'=>$app_user_id);
				
				$query = $this->common_model->GetSpecificValues($columns,$table,$where_clause);
				$result = $query->result_array();
				//$result[0]['review'] = '0';
				
				
				if($query->num_rows()>0){
					//Average rating 
					$sql= "SELECT AVG(gear_star_rating_value) As rating FROM ks_cust_gear_star_rating WHERE  app_user_id = '".$app_user_id."'";
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



					$this->db->select('g_l.*,ks_states.ks_state_name ,ks_suburbs.suburb_name');
					$this->db->from('ks_user_address As g_l');
					$this->db->where('g_l.app_user_id',$app_user_id);
					$this->db->where('g_l.is_active','Y');
					// $this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
					$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = g_l.ks_suburb_id " ,"INNER");
					$this->db->join('ks_states'," ks_states.ks_state_id = g_l.ks_state_id " ,"INNER");
					$query = $this->db->get();
					$address  =$query->result_array();
					//print_r($address);
					$result[0]['address'] = $address;
					$result[0]['rating'] = $rating->rating;
					$result[0]['profession_name'] = $profession->profession_name;
					$result[0]['review'] = count($reviews);
					$result[0]['user_rating'] = $user_ratig ;
					$result[0]['reference'] =$reference->reference;
					if($result[0]['user_description']=="")
						$result[0]['user_description']="Say something about yourself...";
						
					if($result[0]['user_profile_picture_link']=="")
						$result[0]['user_profile_picture_link']=base_url()."/assets/images/profile.png";
					if ($result[0]['user_birth_date'] != '0000-00-00') {

						$result[0]['user_birth_date'] = date('d-m-Y' , strtotime($result[0]['user_birth_date']));
					}
					$response=array("status"=>200,
									"status_message"=>"success",
									"result"=>$result
										
									);
					echo json_encode($response);
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
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);	
	
		if ($app_user_id!="") {
			$six_digit_random_number = mt_rand(100000, 999999);
			$msg=  $six_digit_random_number. "  is your Kitshare verfication OTP for mobile verification for kitshare .Do not share this with anyone.";   
			$to = $post_data['phone_number'];
			// $to = '61416144773';
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://api.smsbroadcast.com.au/api-adv.php?username=kitshare&password=88sms11gate&to=".$to."&from=kitshare&message=".$msg."&ref=112233&maxsplit=1",
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
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}

	public function VerifyOTP()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
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
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}

	public function UpdateUserEmail()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
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
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}
	public function UpdateUserUsername()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
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
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}

	public function ChangePassword()
	{
		
		$post_data      = json_decode(file_get_contents("php://input"),true);
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
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}
	}

	public function AddUnavailbleDates()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
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
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}

	//connect Payout

	public function ConnectPayoutAdd()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
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
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}

	// Get User Bank Details  

	public function GetUserBankDetails()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
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
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
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
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
			$sender_details = $query->row();
			
			$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$post_data['app_user_id']));
			$receiver_details = $query->row();
			
			if (empty($receiver_details)) {

			}
         	$insert_data = array(
         						'sender_id' => $app_user_id ,
         						'receiver_id' => $post_data['app_user_id'] ,
         						'mail_type' => 'Send' ,
         						'mail_text' => $post_data['mail_text'] ,
         						'mail_status' => 'Send' ,
         						'sender_email_id' =>$sender_details->primary_email_address ,
         						'receiver_email_id' => $receiver_details->primary_email_address ,
         						'created_by' => $app_user_id ,
         						'created_date'=>date('Y-m-d'),
         						'created_time'=>date('H:m:i'),
         					);
         	$insert_id =  $this->common_model->InsertData('ks_owner_mail',$insert_data);
         	$url = WEB_URL.'/reply/'.$receiver_details->primary_email_address.'/'.$insert_id;
         	$user_name = $receiver_details->app_user_first_name .' ' .$receiver_details->app_user_last_name ;
			$data = $this->mail_model->Contact_mail( $user_name,$post_data['mail_text'],$url);
		    $sender_mail= 'noreply@inferasolz.com';
			$mail_body = $data;
			$to= $receiver_details->primary_email_address;
			//$to= 'singhaniagourav@gmail.com';
			$subject = "Someone Message You via KITSHARE";		
			$this->home_model->send_email($sender_mail,$to,$subject,$mail_body);		
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Mail Send successfully';
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

	// 

	public function GetReplyMessage()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
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

	}

	//reply message 

	public function ReplyMessage()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$where_clause = array(
								'ks_owner_mail_id'=> $post_data['ks_owner_id'],
								'receiver_email_id'=> $post_data['receiver_email_id']
							);
		$query =  $this->common_model->GetAllWhere('ks_owner_mail',$where_clause);
		$data = $query->row();
		$insert_data = array(
         						'sender_id' => $data->receiver_id ,
         						'receiver_id' =>$data->sender_id ,
         						'mail_type' => 'Send' ,
         						'mail_text' => $post_data['mail_text'] ,
         						'mail_status' => 'Send' ,
         						'sender_email_id' =>$data->receiver_email_id ,
         						'receiver_email_id' => $data->sender_email_id ,
         						'created_by' => $data->receiver_id ,
         						'created_date'=>date('Y-m-d'),
         						'created_time'=>date('H:m:i'),
         					);
         	$insert_id =  $this->common_model->InsertData('ks_owner_mail',$insert_data);
         	$url = WEB_URL.'/reply/'.$data->sender_email_id.'/'.$insert_id;
         		$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$data->sender_id));
			$receiver_details = $query->row();

         	$user_name = $receiver_details->app_user_first_name .' ' .$receiver_details->app_user_last_name ;
			$data = $this->mail_model->Contact_mail( $user_name,$post_data['mail_text'],$url);
		    $sender_mail= 'noreply@inferasolz.com';
			$mail_body = $data;
			$to= $receiver_details->primary_email_address;
			//$to= 'singhaniagourav@gmail.com';
			$subject = "Someone Message You via KITSHARE";		
			$this->home_model->send_email($sender_mail,$to,$subject,$mail_body);		
	
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Mail Send successfully';
				$json_response = json_encode($response);
				echo $json_response;

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


}?>
