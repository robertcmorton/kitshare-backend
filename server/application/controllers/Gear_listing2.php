	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	require_once(APPPATH.'/third_party/braintree-php-3.36.0/lib/autoload.php');

	class Gear_listing extends CI_Controller {
		
		 public function __construct() {
			header('Access-Control-Allow-Origin: *');
			header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
	        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
			parent::__construct();
			$this->load->helper(array('url','form','html','text'));
			$this->load->library(array('session','form_validation','email','upload','pagination'));
			$this->load->model(array('common_model','home_model'));
			}
		
		public function index()
		{
				
				//$email='imcsuvankar@gmail.com';
				$email=$this->input->get('email');
				
				$result= $this->common_model->RetriveRecordByWhereRow('ks_users', array('primary_email_address'=>$email)); ////// Get user all information
				$app_user_id= $result['app_user_id'];	
				
				//Gear lists are pushed into the array
				$gear_lists=$this->home_model->GetGearLists();
				$result['gear_lists']=$gear_lists;
							
				//print_r($result); exit();
				if(!empty($result))
				{
					echo json_encode($result);
					exit();
				}else{
					header('HTTP/1.1 400 Bad Request');
					exit();
				}
			
		}
		//public gear list
		public function SignleGearList($gear_id='')
		{
			$data = $this->home_model->geratDetails($gear_id);
			// $address	  =
			// 	array_merge( $data,$address);
			if (!empty($data)) {
				$response['status_code'] = 200;
				$response['status_message'] = "Successfully gear list found";
				$response['result'] =$data;
			}else{
				header('HTTP/1.1 404 Page Not Found');
				$response['status_code'] = 404;
				$response['status_message'] = "No gear found";
				//$response['result'] =$data;
			}
			echo json_encode($response);
		}

		public function ViewSingleGearList($gear_id='')
		{
			$data['gear_details'] = $this->home_model->geratDetails($gear_id);
			$data['related_listings'] = $this->home_model->UserrelatedGear(  $data['gear_details']->ks_category_id ,$gear_id);
			$data['user_gears'] = $this->home_model->UsergearDetails( $data['gear_details']->app_user_id);
			// $address	  =
			// 	array_merge( $data,$address);
			$app_user_id =  $data['gear_details']->app_user_id ; 
			$sql= "SELECT AVG(gear_star_rating_value) As rating FROM ks_cust_gear_star_rating WHERE  app_user_id = '".$app_user_id."'";
						$query = $this->db->query($sql);
						$rating = $query->row();
						// review Count

						// $sql= "SELECT count(ks_cust_gear_review_id) As review  FROM ks_cust_gear_reviews As tbl_review WHERE  app_user_id = '".$app_user_id."'";
						// $query = $this->db->query($sql);
						// $review = $query->row(); 

						$review_result= $this->home_model->ProductReview($app_user_id); ////// get user's all product review array			
						$reviews=$review_result->result_array();
						//print_r(count($reviews));
						//reference count
						$sql= "SELECT count(ks_user_reference_id) As reference FROM ks_user_reference WHERE  app_user_id = '".$app_user_id."'";
						$query = $this->db->query($sql);
						$reference = $query->row();

					

						$data['gear_details']->rating = $rating->rating;
						$data['gear_details']->review = count($reviews);
						$data['gear_details']->reference =$reference->reference;
						$data['gear_details']->response_time ='24 hrs Response time';
						$data['gear_details']->acceptance_rate ='100% Acceptance Rate';
						


			if (!empty($data)) {
				$response['status_code'] = 200;
				$response['status_message'] = "Successfully gear list found";
				$response['result'] =$data;
			}else{
				header('HTTP/1.1 404 Page Not Found');
				$response['status_code'] = 404;
				$response['status_message'] = "No gear found";
				//$response['result'] =$data;
			}
			echo json_encode($response);
		}		
		
		
			
		//private gear list 
		public function privategearDetails($gear_id)
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			$data = $this->home_model->privategeratDetails($gear_id,$app_user_id);

			if (!empty($data)) {
				if ($data->accessories != '') {
					$accessorie = explode(',', $data->accessories);
					foreach ($accessorie as  $value) {
						 $query = $this->common_model->GetAllWhere('ks_user_gear_description',array('user_gear_desc_id'=>$value));
						 $gear_details=  $query->row();
						 if (!empty($gear_details)) {
						 	$itmes[] =array( 'gear_name'=> $gear_details->gear_name , 'serial_numbe'=>$gear_details->serial_number);
						 }else{
						 	$itmes[] =array();
						 }
					}
				}else{
					$itmes =array();
				}
				$data->extraItems = $itmes;
				$response['status_code'] = 200;
				$response['status_message'] = "Successfully gear list found";
				$response['result'] =$data;
			}else{
				header('HTTP/1.1 404 Page Not Found');
				$response['status_code'] = 404;
				$response['status_message'] = "No gear found";
				//$response['result'] =$data;
			}
			echo json_encode($response);
		}

		public function ViewprivategearDetails($gear_id)
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
			$data['gear_details'] = $this->home_model->privategeratDetails($gear_id,$app_user_id);;

			if (!empty($data['gear_details'])) {
				$data['related_listings'] = $this->home_model->UserrelatedGear(  $data['gear_details']->ks_category_id ,$gear_id);
				$data['user_gears'] = $this->home_model->UsergearDetails( $data['gear_details']->app_user_id);
			}
			$sql= "SELECT AVG(gear_star_rating_value) As rating FROM ks_cust_gear_star_rating WHERE  app_user_id = '".$app_user_id."'";
						$query = $this->db->query($sql);
						$rating = $query->row();
						// review Count

						// $sql= "SELECT count(ks_cust_gear_review_id) As review  FROM ks_cust_gear_reviews As tbl_review WHERE  app_user_id = '".$app_user_id."'";
						// $query = $this->db->query($sql);
						// $review = $query->row(); 

						$review_result= $this->home_model->ProductReview($app_user_id); ////// get user's all product review array			
						$reviews=$review_result->result_array();
						//print_r(count($reviews));
						//reference count
						$sql= "SELECT count(ks_user_reference_id) As reference FROM ks_user_reference WHERE  app_user_id = '".$app_user_id."'";
						$query = $this->db->query($sql);
						$reference = $query->row();

					

						$data['gear_details']->rating = $rating->rating;
						$data['gear_details']->review = count($reviews);
						$data['gear_details']->reference =$reference->reference;
						$data['gear_details']->response_time ='24 hrs Response time';
						$data['gear_details']->acceptance_rate ='100% Acceptance Rate';
						

			if (!empty($data)) {
				$response['status_code'] = 200;
				$response['status_message'] = "Successfully gear list found";
				$response['result'] =$data;
			}else{
				header('HTTP/1.1 404 Page Not Found');
				$response['status_code'] = 404;
				$response['status_message'] = "No gear found";
				//$response['result'] =$data;
			}
			echo json_encode($response);
		}

		public function Categorylist()
	    {
	    	$table = "ks_gear_categories";
			$where_clause = "is_active='Y' AND gear_sub_category_id = '0' ";
			$query = $this->common_model->RetriveRecordByWhere($table,$where_clause);
			$result_arr = array();
			
			if($query->num_rows()>0){
				
				$i=0;
				foreach($query->result_array() as $row){
					$result_arr[$i]['gear_category_id']=$row['gear_category_id'];
					$result_arr[$i]['gear_category_name']=$row['gear_category_name'];

					$result_arr[$i]['gear_sub_category_id']=$row['gear_sub_category_id'];
					

					$i++;
				}
			
			}
			
			$response=array("status"=>200,
							"status_message"=>"success",
							"result"=>$result_arr);
			echo json_encode($response);
			exit();
	    }
	    public function Subcategorylist($gear_category_id='')
	    {
			$gear_category_id = $this->input->get('gear_category_id');
			$gear_category_id1 = 	 strstr($gear_category_id,"'") ; 
			if (!empty($gear_category_id1)) {
			$gear_category_id =str_replace("'","''",$gear_category_id);
			}
	    	$table = "ks_gear_categories";
			$where_clause = "is_active='Y'  AND gear_category_name = '".$gear_category_id."' ";
			$query1 = $this->common_model->RetriveRecordByWhere($table,$where_clause);
			$value = $query1->row();
			if (empty($value)) {
				$response=array("status"=>200,
							"status_message"=>"success",
							"result"=>array());
			echo json_encode($response);
			exit();
			}
			$query = $this->common_model->RetriveRecordByWhere($table,array('gear_sub_category_id'=>$value->gear_category_id));
			$result_arr = array();
			if($query->num_rows()>0){
				$i=0;
				foreach($query->result_array() as $row){
					$result_arr[$i]['gear_category_id']=$row['gear_category_id'];
					$result_arr[$i]['gear_category_name']=$row['gear_category_name'];
					$result_arr[$i]['gear_sub_category_id']=$row['gear_sub_category_id'];
					$i++;
				}
			}
			$response=array("status"=>200,
							"status_message"=>"success",
							"result"=>$result_arr);
			echo json_encode($response);
			exit();
	    }
	    public function brandlist()
	    {
	    	//ks_manufacturers
	    	$table = "ks_manufacturers";
			$where_clause = "is_active='Y' ";
			$query = $this->common_model->RetriveRecordByWhere($table,$where_clause);
			$result_arr = $query->result_array();
	;
			//echo $this->db->last_query();
			//print_r($query);die;
			
			$response=array("status"=>200,
							"status_message"=>"success",
							"result"=>$result_arr);
			echo json_encode($response);
			exit();
	    }
		public function getModallist(){
			$brand_id =  $this->input->get('brand_id');
			//print_r($brand_id);//die;

			$query1 = $this->common_model->GetAllWhere('ks_manufacturers',array( 'manufacturer_name'=>$brand_id )) ;	
			$value = $query1->row()	;
			if(empty($value)){
				

				$result['status'] = '200';
				$result['status_message'] = 'No model found';
				$result['result'] = '';
				echo json_encode( $result, true);
				exit ;
			}

			$query = $this->common_model->GetAllWhere('ks_models',array( 'manufacturer_id'=>$value->manufacturer_id )) ;
			$result_arr = $query->result();
			if(count($result_arr) > 0){
				

				$i=0 ;
				foreach ($result_arr as $key => $value) {
					$result_arr[$i]->model_name   = stripslashes($value->model_name) ;
					$i++;
				}
				$result['status'] = '200';
				$result['status_message'] = 'Success';
				$result['result'] = $result_arr;
				
			}else{
				
				$result['status'] = '200';
				$result['status_message'] = 'No model found';
				$result['result'] = $result_arr;
			}
			echo json_encode( $result, true);
		}
		public function ModelDetails(){
			$post_data      = json_decode(file_get_contents("php://input"),true);
			//print_r($post_data);//die;
			//check manufacturers
			$query_manufacturers = $this->common_model->GetAllWhere('ks_manufacturers',array( 'manufacturer_name'=>$post_data['manufacturer_name'] ,)) ;
			$manufacturers = $query_manufacturers->row();
			if(empty($manufacturers)){
				$result['status'] = '400';
				$result['status_message'] = 'manufacturer does not exist.';
				$result['result'] = '';
				echo json_encode($result,true);
					exit ;
			}
			$query_category = $this->common_model->GetAllWhere('ks_gear_categories','') ;
				$category = $query_category->result();
			//check Models
				$post_data['model_name']  = urldecode($post_data['model_name'] );
			$query_model = $this->common_model->GetAllWhere('ks_models',array( 'model_name'=>$post_data['model_name'] ,)) ;
			$models = $query_model->row(); 
			if(empty($models)){
				$result['status'] = '400';
				$result['status_message'] = 'Model does not exist.';
				$result['result'] = '';
					$result['category'] = $category ;
				echo json_encode($result,true);
					exit ;
			}
			//GET models
			$query = $this->common_model->GetAllWhere('ks_models',array( 'manufacturer_id'=>$manufacturers->manufacturer_id , 'model_id'=> $models->model_id)) ;
			$result_arr = $query->row();

			$sub_category = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_id '=>$result_arr->gear_sub_category_id)) ;
			$sub_category	= $sub_category->row();
			$result_arr->gear_sub_category_name =  $sub_category->gear_category_name;

			$category = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_id '=>$result_arr->gear_category_id)) ;
			$category	= $category->row();
			if (!empty($category)) {
			$result_arr->gear_category_name =  $category->gear_category_name;
			$result_arr->average_value =  $category->average_value;
			}else{
				$result_arr->gear_category_name =  '';
				$result_arr->average_value =  '';
			}
			// Get All gear Category 
				$query_category = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_sub_category_id'=>'0')) ;
				$category = $query_category->result();

				$query_sub_category = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_sub_category_id !='=>'0')) ;
				$sub_category = $query_sub_category->result();
			if(count($result_arr) > 0 ){
				if($result_arr->model_image != ''){
					$result_arr->model_image = FRONT_URL.'model_images/'.$result_arr->model_image ;
				}else{
					$result_arr->model_image =  GEAR_IMAGE."default_product.jpg";
				}
				$feature_data =  $this->getCategoryFeature1($result_arr->gear_category_id);
				
				$result['status'] = '200';
				$result['status_message'] = 'Success';
				$result['match_found'] = 1;
				$result['result'] = $result_arr;
				$result['category'] = $category ;
				$result['sub_category'] = $sub_category ;
				$result['feature'] = $feature_data ;
				echo json_encode($result,true);
					exit ;
				
			}else{
				//echo $post_data['gear_category_name'] ;
				if($post_data['gear_category_name']  == '' ){ 
				$result['status'] = '200';
				$result['status_message'] = 'Data not Found';
				$result['match_found'] = 0;
				$result['result'] = $result_arr;
				$result['category'] = $category ;
				$result['sub_category'] = $sub_category ;
				echo json_encode($result,true);
					exit ;
				}
				$query_category_id = $this->common_model->GetAllWhere('ks_gear_categories',array( 'gear_category_name'=>$post_data['gear_category_name'] )) ;
				$category_details = $query_category_id->row();
				//print_r($category_details);
				if(empty($category_details)){
					
					$result['status'] = '400';
					$result['status_message'] = 'Category does not exist.';
					$result['result'] = '';
					echo json_encode($result,true);
					exit ;
				}
				
				$query = $this->common_model->GetAllWhere('ks_models',array( 'manufacturer_id'=>$manufacturers->manufacturer_id , 'gear_category_id'=> $category_details->gear_category_id)) ;
				$result_arr = $query->row();
				
				if(count($result_arr) > 0){
					
					if($result_arr->model_image != ''){
					$result_arr->model_image = FRONT_URL.'model_images/'.$result_arr->model_image ;
				}
				$result['status'] = '200';
				$result['status_message'] = 'Success';
				$result['match_found'] = 1;
				$result['result'] = $result_arr;
				
				echo json_encode($result,true);
					exit ;
				}else{
				
						$query = $this->common_model->GetAllWhere('ks_gear_categories',array( 'gear_category_name'=>$post_data['gear_category_name'] )) ;
						$result_arr = $query->result();
						$result['status'] = '200';
						$result['status_message'] = 'Success';
						$result['match_found'] = 0;
						$result['result'] = $result_arr;
						echo json_encode($result,true);
						exit ;
				}
				
			}
			
		
			
			
		}
		public function searchGear(){
			
			$gear_name =   $this->input->post('gear_name');
			$this->db->select('*');
			
			$this->db->from('ks_user_gear_description');
			$this->db->where("gear_name LIKE '%".$gear_name."%'");
			$query = $this->db->get();
			$response = $query->result();
			
			$result['status'] = '200';
			$result['status_message'] = 'Success';
			$result['result'] = $response;
				
			echo json_encode($result,true);
		}
		public function cpoyimage($image='')
		{
			 $imagePath =  BASE_IMG.'site_upload/model_images/'.$image;
			$newPath = BASE_IMG.'site_upload/gear_images/';
			$ext = '.jpg';
			$newName  = time()."-".$ext;
			//die;
			$copied = copy($imagePath , $newPath.$newName);

			if ((!$copied)) 
			{
			    $array['message'] = '0' ;
			     $array['name_image'] = ''  ;
			}
			else
			{ 
			    $array['message'] = '1' ;
			    $array['name_image'] = $newName  ;

			}
			return $array;
		}
		
		public function addGearList(){
			
			$post_data  = json_decode(file_get_contents("php://input"),true);
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			// echo "<pre>";
			// print_r($post_data);die;
			if(count($post_data['address']) > 0 ){
				
				$address_ids =  implode(',',$post_data['address']);
			}else{
				
				$address_ids = '' ; 
			}
			
			//echo  $app_user_id ;
			 
			 // check manufacturer details 
			$query =$this->common_model->GetAllWhere('ks_manufacturers',array('manufacturer_name' => $post_data['gear_brand_name']));
			$manufacturers_data = $query->row();
			if(!empty($manufacturers_data)){ // if present 
				 $manufacturer_id = $manufacturers_data->manufacturer_id ;
			}else{ // else not present then add 
				$insert_manufacturers = array(
				'manufacturer_name'=>$post_data['gear_brand_name'],
				'create_date'=>date('Y-m-d'),
				'create_user' =>$app_user_id,
				'is_active'=>'Y',
				);
				$manufacturer_id = $this->common_model->InsertData('ks_manufacturers' ,$insert_manufacturers);
			}
			
			// Check Parent  Category details 
			$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => $post_data['gear_category_name']));
			$category_data = $query->row();
			//print_r($category_data);
			if(!empty($category_data)){
				$category_id = $category_data->gear_category_id ;
			}else{ // Add Parent Category
				$category_insert = array(
				'gear_category_name' =>$this->input->post('category_name'),
				'gear_sub_category_id'=> '0',
				'security_deposit'=> 'N',
				'average_value' =>'00.00',
				'is_active'=>'Y',
				'create_user'=>$app_user_id,
				'create_date'=>date('Y-m-d')
								);
				$category_id = $this->common_model->InsertData('ks_gear_categories',$category_insert);				
				
			}
			// End For Parent Cateogry Check 
			
			// Check For Child Category Check 
			if(!empty($post_data['gear_sub_category_name'])){ // if not empty 
				$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => $post_data['gear_sub_category_name']));
				$category_data = $query->row();
				if(!empty($category_data)){
					$sub_category_id = $category_data->gear_category_id ;
				}else{ // Add Sub  Category
					$category_insert = array(
					'gear_category_name' =>$this->input->post('category_name'),
					'gear_sub_category_id'=> '0',
					'security_deposit'=> 'N',
					'average_value' =>'00.00',
					'is_active'=>'Y',
					'create_user'=>$app_user_id,
					'create_date'=>date('Y-m-d')
									);
					$sub_category_id = $this->common_model->InsertData('ks_gear_categories',$category_insert);				
				
				}
				
			}else{
				$sub_category_id =  '0';
				
			}
			  
			// End sub category Check  
			  
			  
			// check for Modals  Start

			$query =$this->common_model->GetAllWhere('ks_models',array('model_name' => $post_data['gear_model_name']));
			$modal_data = $query->row();	
			//print_r($modal_data);die;
			
			if(!empty($modal_data)){ // if modal exist
					 $model_id = $modal_data->model_id ;
			}else{ // if modal not  exist add modal  
				
				// if (!empty($sub_category_id)) {
				// 	$gear_category_id = $sub_category_id;
				// }else{
				// 	$gear_category_id = $category_id;
				// }
				$modal_insert = array(
					'model_name'=> $post_data['gear_model_name'],
					'model_description'=> '',
					'manufacturer_id'=> $manufacturer_id,
					'gear_category_id'=> $category_id,
					'gear_sub_category_id'=> $sub_category_id,
					'is_approved'=>'Y',
					'is_active' => 'Y',
					'approved_on'=> date('Y-m-d'),
					'create_date'=> date('Y-m-d'),
					'create_user' => $app_user_id,
					'approved_by_user_id'=>$app_user_id,

							);
			$model_id =	$this->common_model->InsertData('ks_models',$modal_insert);
			}	
			// End Modal check 
			if($post_data['listing_option'] == 'not_listed'){
				 $listed = 'N';
				
			}else{
				 $listed = 'Y';
			}	

			if(!empty($post_data['extraItems'][0]) ){
				
				//$accessories1 =  implode(',',$post_data['extraItems']);
				foreach ($post_data['extraItems'] as  $value) {
					
				$query =	$this->common_model->GetAllWhere('ks_user_gear_description' , array('gear_name'=>$value ));
				$gear_details = $query->row();
				if (!empty($gear_details)) {
					$values[] =  $gear_details->user_gear_desc_id;	
				}else{
						//print_r($value); die;

				}
				//print_r($gear_details->user_gear_desc_id);
				 
				}
					$accessories =  implode(',',$values);
					


			}else{
				
					$accessories = '' ; 
			}
			// print_r($accessories);die;
			// price Calcuation 
			$query_sett=   $this->common_model->GetAllWhere('ks_settings', '');	
			$settings = $query_sett->row();
				
			  $row['per_weekend_cost_aud_ex_gst'] =  ($post_data['per_day_cost_aud_ex_gst']*3) ; 
			  $row['per_week_cost_aud_ex_gst'] =  ($post_data['per_day_cost_aud_ex_gst']*3) ; 
			
			  $row['per_weekend_cost_aud_inc_gst'] =  $row['per_weekend_cost_aud_ex_gst'] + ($row['per_weekend_cost_aud_ex_gst']*$settings->gst_percent)/100 ;
			  $row['per_week_cost_aud_inc_gst'] =  $row['per_week_cost_aud_ex_gst'] + ($row['per_week_cost_aud_ex_gst']*$settings->gst_percent)/100 ;
			
			$row['gear_name'] =  $post_data['gearName'];
			$row['ks_manufacturers_id'] = $manufacturer_id ;
			$row['ks_category_id'] = $category_id ;
			$row['ks_sub_category_id'] = $sub_category_id ;
			$row['model_id'] = $model_id;
			$row['ks_gear_address_id'] = $address_ids ;
			$row['ks_user_gear_condition'] =  $post_data['gearCondition'];
			$row['serial_number'] = $post_data['serialNumber'];
			$row['gear_description_1'] = $post_data['model_description'];
			$row['security_deposite'] = $post_data['security_deposite'];
			$row['security_deposite_inc_gst'] = $post_data['security_deposite'] + ($post_data['security_deposite']*$settings->gst_percent)/100;
			if (isset($post_data['feature_master_id'])) {
				  $row['feature_master_id'] = implode(',', $post_data['feature_master_id']);
			}	

			if (array_key_exists("security_deposit_check",$post_data))
			{
			      $security_deposit_check = $post_data['security_deposit_check'] ;
			}else{
				  $security_deposit_check ='0';
			}
			//$row['gear_description_2'] = $post_data['additional_description'];
			$row['owners_remark'] = $post_data['additional_description'];
			$row['security_deposit_check'] =$security_deposit_check;
			$row['per_day_cost_aud_ex_gst'] = $post_data['per_day_cost_aud_ex_gst'];
			$row['per_day_cost_aud_inc_gst'] = $post_data['per_day_cost_aud_ex_gst']+  ($post_data['per_day_cost_aud_ex_gst']*$settings->gst_percent)/100 ;
			$row['replacement_value_aud_inc_gst'] = $post_data['replacement_value_aud_inc_gst'];
			$row['replacement_value_aud_ex_gst'] = $post_data['replacement_value_aud_ex_gst'];
			$row['gear_hide_search_results'] = $listed;
			$row['accessories'] = $accessories ;
			$row['app_user_id'] = $app_user_id ;
			$row['gear_listing_date'] =date('Y-m-d');
			$row['is_active'] = 'Y';
			$row['create_user'] = $app_user_id ;
			$row['create_date'] = date('Y-m-d');
			$row['google_360_link'] = $post_data['image360Link'];
			$row['ks_gear_type_id']=  $post_data['gearTypeId'];
			$row['gear_type']= $post_data['gearTypeId'];
			// print_r($row);die;
			 $user_gear_id =   $this->common_model->InsertData('ks_user_gear_description',$row);
			 //echo $this->db->last_query();
			//die;
		// 	print_r($modal_data);
		// echo 	$_SERVER['DOCUMENT_ROOT'].'/kitshare/site_upload/model_images/'.$modal_data->model_image ;
			if (array_key_exists("model_image_deleted",$post_data))
			{		
					if ($post_data['model_image_deleted'] == '1') {
							$model_image_deleted = '1';
						}else{
							$model_image_deleted = '0';
						}	
			      
			}else{
				  $model_image_deleted ='0';
			}
			if($model_image_deleted =='0'){
				if(!empty($modal_data->model_image)){
					 	if (file_exists(BASE_IMG.'site_upload/model_images/'.$modal_data->model_image )) {
							$response_image =  $this->cpoyimage($modal_data->model_image);
							if ($response_image['message'] == '1' ) {
				 					# code...
				 					
				 		 		 $data_images = array(

				         					'model_id' =>  $modal_data->model_id,
				         					'gear_display_image' =>$response_image['name_image'] ,
				         					'user_gear_desc_id'=> $user_gear_id,
				         					'type' =>'model' ,
				         					'is_active'=> 'Y',
				         					 'create_user'=> $app_user_id,
				         					 'create_date'=>date('Y-m-d')
				         				);
										         $this->common_model->InsertData('ks_user_gear_images',$data_images);
							}
				 	}	
				}
			 }	
			 if(count($post_data['address']) > 0 ){
				foreach ($post_data['address'] AS  $value) {
					$address_insert =  array(
												'user_gear_desc_id' => $user_gear_id,
												'user_address_id'=> $value,
												'is_active' => 'Y',
												'create_date'=> date('Y-m-d'),
												'create_user'=>$app_user_id,

											 );
					$this->common_model->InsertData( 'ks_gear_location' ,$address_insert);
				}
					
			}else{
				
				$address_ids = '' ; 
			}
			if (isset($post_data['feature_details_id'])) {
				
				if (count($post_data['feature_details_id']) > 0 ) {
			 		for ($i=0; $i < count($post_data['feature_details_id'] ); $i++) { 
			 			
			 			$value_insert = array(
			 								'user_gear_desc_id'=>$user_gear_id ,
			 								'feature_details_id	'=>$post_data['feature_details_id'][$i],
			 								'is_active'=>'Y',
			 								'create_user'=>$app_user_id,
			 								'create_date'=>date('Y-m-d')
			 							);
			 			$this->common_model->InsertData('ks_user_gear_features',$value_insert);
			 		}
				}	
			}
			if (isset($post_data['unAvailableDates'])) {
				if (count($post_data['unAvailableDates']) > 0 ) {
					$i=0 ;
					foreach ($post_data['unAvailableDates'] as  $value) { 
						
						$unavailble_insert = array(
												'user_gear_description_id'=>$user_gear_id,
												'unavailable_from_date'=>$value[0] , 
												'unavailable_to_date' =>$value[1] , 
												'create_user' => $app_user_id ,
												'create_date'=> date('Y-m-d')
											);

							$this->common_model->InsertData( 'ks_gear_unavailable' ,$unavailble_insert);
					
							$i++;
					}
				}
			}
			 if($user_gear_id > 0 ) {
				// echo "hello";
					$response['status'] = 200;
					$response['status_code']='success';
				//	$response['user_gear_desc_id']=$user_gear_id;
					//$response['model_id']=$model_id;
					$response['status_message'] = "Gear added sucessfully";
					//json_encode($response,true);
					$response['result'] = array(
												'model_id'=>$model_id,
												'user_gear_desc_id'=> $user_gear_id
											);
				 
			 }else{
				 $response['status'] = 400;
					$response['status_code']='failed';
					$response['status_message'] = "Something went wrong";
			 }
			
			
			
			echo json_encode($response);
		}
		private function set_upload_options()
	{   
	    //upload an image options
	    $config = array();
	    $config['upload_path'] = BASE_IMG.'site_upload/gear_images';;
	    $config['allowed_types'] = 'gif|jpg|png|jpeg|svg';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;

	    return $config;
	}
	public function resize($image_data) {
		$img = substr($image_data['full_path'], 63);
		$config['image_library'] = 'gd2';
		$config['source_image'] = $image_data['full_path'];
		$config['new_image'] = BASE_IMG.'site_upload/gear_images/'.$img;
		$config['width'] = '300';
		$config['height'] = '300';

		//send config array to image_lib's  initialize function
		$this->image_lib->initialize($config);
		$src = $config['new_image'];
		$data['new_image'] = 'new_' . $img;
		$data['img_src'] = base_url() . $data['new_image'];
		// Call resize function in image library.
		$this->image_lib->resize();
		// Return new image contains above properties and also store in "upload" folder.
		return $data;
	}
		public function GearImageUpload(){
			
			$token  = $this->input->post('token');
			$user_gear_desc_id  = $this->input->post('user_gear_desc_id');
			$model_id  = $this->input->post('model_id');
			$query = $this->common_model->GetAllWhere('ks_user_gear_description	',array('user_gear_desc_id'=>$user_gear_desc_id));
			$values = $query->row();
			if (empty($values)) {
				 $response['status'] = '404';
				 $response['message'] = 'No gear exist';
				 echo json_encode($response,true); 
				 exit;
			}
			if(isset($_FILES['image'])){
				$image 			= $_FILES['image'];
			}else{
				$result['status'] = '400' ;
				$result['status_message'] = 'Plese Select a file' ;
				echo json_encode($result,true);
				exit;
			}
			$app_user_id = $this->userinfo($token);

			if($app_user_id > 0 ){
				$files = $_FILES;
				 	
					$original_image_name =  explode('.',$files['image']['name']);
					$_FILES['image']['name']= $files['image']['name'];
					$_FILES['image']['type']= $files['image']['type'];
					$_FILES['image']['tmp_name']= $files['image']['tmp_name'];
					$_FILES['image']['error']= $files['image']['error'];
					$_FILES['image']['size']= $files['image']['size'];    
					$this->upload->initialize($this->set_upload_options());
					$this->upload->do_upload('image');
					$dataInfo = $this->upload->data();
					$image_data = $this->upload->data();
					$error = array('error' => $this->upload->display_errors());
				
			        if ($image_data['image_width'] > '300' || $image_data['image_width'] > 300 ) {
			        	$dataInfo1 = $this->resize($image_data);
			        	// unlink($dataInfo['full_path']);
			        	  $image = $dataInfo['file_name'] ; 
			       
			        }else{

			        	$image =  $dataInfo['file_name'] ; 
			        }
						
					$data_images = array(

										'model_id' =>$model_id ,
										'gear_display_image' =>$image ,
										'user_gear_desc_id'=> $user_gear_desc_id,
										'is_active'=> 'Y',
										 'create_user'=>$app_user_id ,
										 'create_date'=>date('Y-m-d')
									);
						         $this->common_model->InsertData('ks_user_gear_images',$data_images);
					
					$result['status'] = 200;
						$result['status_code']='success';
					
						$result['status_message'] = "Gear Image added sucessfully";
					//die;
					
			}else{
				$result['status'] = '400' ;
				$result['status_message'] = 'Not a valid user' ;
			}
			echo  json_encode($result,true);

			
		} 
		public function base64decode()
		{
			$image = $this->input->post('image');
			define('UPLOAD_DIR', 'images/');
			$img = $image;
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$data = base64_decode($img);
			$file =  BASE_IMG.'site_upload/'. uniqid() . '.png';
			$success = file_put_contents($file, $data);
		}
		public function GetGearlist()
		{	
			$where_clause = array('is_active'=>'Y');
			$query = $this->common_model->GetAllWhere('ks_user_gear_description' , $where_clause);
			$gear_list  = $query->result();

			$query = $this->common_model->GetAllWhere('ks_settings','');
			$setting  = $query->row();
			if (count($gear_list) > 0 ) {
				foreach ($gear_list as  $value) {
					$values[] =  array(
										'gear_id'=> $value->user_gear_desc_id ,
										'gear_name'=> $value->gear_name ,
										'gst_rate'=>$setting->gst_percent
						);
				}
				$result['status'] = 200;
				$result['status_code']='success';
				
				$result['result'] = $values;
			}else{
				$result['status'] = 200;
				$result['status_code']='success';
				
				$result['result'] = $values;
			}
			echo 		json_encode($result, true) ;
			//die;
		}

		public function GetUserGearlist()
		{	
			$post_data  = json_decode(file_get_contents("php://input"),true);
			
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id !=='0') {
				
				$where_clause = array(
										'is_active'=>'Y',
										'app_user_id'=>$app_user_id	
										);
				$query = $this->common_model->GetAllWhere('ks_user_gear_description' , $where_clause);
				$gear_list  = $query->result();

				$query = $this->common_model->GetAllWhere('ks_settings','');
				$setting  = $query->row();
				if (count($gear_list) > 0 ) {
					foreach ($gear_list as  $value) {
						$values[] =  array(
											'gear_id'=> $value->user_gear_desc_id ,
											'app_user_id'=> $value->app_user_id ,
											'gear_name'=> $value->gear_name ,
											'gst_rate'=>$setting->gst_percent
							);
					}
					$result['status'] = 200;
					$result['status_code']='success';
					
					$result['result'] = $values;
				}else{
					$result['status'] = 200;
					$result['status_code']='success';
					
					$result['result'] = array();
				}
				echo 		json_encode($result, true) ;
			}else{

			}	
			//die;
		}
		
		public function FAQCatgoryList(){
			
			$post_data  = json_decode(file_get_contents("php://input"),true);
			if (!empty($post_data)) {
				$where_clause = array('status'=>'Y') ;
				$search_string = $post_data['search'];
				$query = $this->home_model->GetFAQ('ks_faq_category' ,$where_clause ,$search_string);
			}else{
				$search_string = '';
				$query = $this->home_model->GetFAQ('ks_faq_category' , array('status'=>'Y') ,$search_string);
			}
			$gear_list  = $query->result();
			if (count($gear_list) > 0 ) {
				foreach ($gear_list as  $value) {
					if ($value->image == '') {
						$value->image = FAQ_IMAGE.'no-image-icon.jpg';
					}else{
						$value->image =  FAQ_IMAGE.$value->image;
					}
					$query = $this->common_model->GetAllWhere('ks_faq' , array('status'=>'Y','category_id'=>$value->id  ));
					$child_data  = $query->result();
					$value->count = 				count($child_data);			
				}
			}
			$result['status'] = 200;
			$result['status_code']='success';
			$result['result'] = $gear_list;
			echo 		json_encode($result, true) ;
			
		}
		public function GetFaqList(){


			$category_id =  $this->input->get('category_id');


			$query = $this->common_model->GetAllWhere('ks_faq_category' , array('status'=>'Y', 'id'=>$category_id));
			$category  = $query->row();
			$query = $this->home_model->GetFaqList('ks_faq' , array('status'=>'Y','category_id'=>$category_id ));
			$gear_list  = $query->result();
			if (!empty($gear_list)) {
				foreach ($gear_list as  $value) {
					if ($value->image == '') {
						$value->image = FAQ_IMAGE.'no-image-icon.jpg';
					}
				}
			}
			$category->count = count($gear_list);
			$result['status'] = 200;
			$result['status_code']='success';
			$result['result'] = array(
									'category'=>$category,
									'list'=>$gear_list
								);;
			echo 		json_encode($result, true) ;
			
		}
		public function  GetFaqDetails(){
			$faq_id =  $this->input->get('faq_id');
			$query = $this->common_model->GetAllWhere('ks_faq' , array('status'=>'Y','faq_id'=>$faq_id ));
			$gear_list  = $query->row();

			if ($gear_list->image == '') {
							$gear_list->image = FAQ_IMAGE.'no-image-icon.jpg';
			}
			$gear_list->description  =  html_entity_decode($gear_list->description);
			$query = $this->common_model->GetAllWhere('ks_faq_category' , array('status'=>'Y', 'id'=>$gear_list->category_id));
			$category  = $query->row();
			$result['status'] = 200;
			$result['status_code']='success';
			$result['result'] =  array(
									'category'=>$category,
									'details'=>$gear_list);
			echo 		json_encode($result, true) ;
			
			
		}
		
		public function  getCategoryFeature($gear_category_name){
			

			$gear_category_name = urldecode($gear_category_name);
			$query = $this->common_model->GetAllWhere('ks_gear_categories' , array('is_active'=>'Y','gear_category_name'=>$gear_category_name ));
			$gear_details  = $query->row();
			
			$data =  $this->getCategoryFeature1($gear_details->gear_category_id);
			echo json_encode($data, true);
		}
		

		public function  getCategoryFeature1($gear_category_id){
			
			$query = $this->common_model->GetAllWhere('ks_feature_master' , array('is_active'=>'Active','gear_category_id'=>$gear_category_id ));
			$feature_list  = $query->result();
			
			if(count($feature_list) > 0 ){
				foreach ($feature_list AS  $feature){
					
					$query = $this->common_model->GetAllWhere('ks_gear_feature_details' , array('is_active'=>'Y','feature_master_id'=>$feature->feature_master_id ));
					$feature_details  = $query->result();
					$values[] = array(
								'feature_master_id'=>$feature->feature_master_id ,
								'feature_name'=> $feature->feature_name,
								'feature_details'=>$feature_details
					); 	
				}
			}else{
				$values[] = array(
								'feature_master_id'=>'' ,
								'feature_name'=> '',
								'feature_details'=>''
					);
			}
			return $values ;
		}    

		public function GetLatLng($address = '')
		{
			
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address='".urlencode($address)."'&key=AIzaSyCPU8keawNREwb4_tHM8D1mcw4bRuSEoUQ",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "",
			  CURLOPT_HTTPHEADER => array(
			    "Postman-Token: d7147561-0a27-4abb-a97b-43bd08cc3917",
			    "cache-control: no-cache"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  return "cURL Error #:" . $err;
			} else {
			  return $response;
			}
		}

		// HOME PAge Search 
		public function Search()
		{
		 	
			$data=array();
			$where = " ";
			$URL = '';
			$post_data  = json_decode(file_get_contents("php://input"),true);
			
			$postal_arr_list = '';
			if($this->input->get('limit') != ''){
					$data['limit']	= $this->input->get('limit');
			}
			else{
				$data['limit']	= 5;
			}
			
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
			
			$data['offset'] = $offset;
			 $data['key_word']				= urldecode($this->input->get('key_word'));

			if(!empty($data['key_word']) ){
					$where .=  "(a.gear_name LIKE '%".trim($data['key_word'])."%'  OR e.model_name  LIKE '%".trim($data['key_word'])."%'  OR  d.manufacturer_name LIKE  '%".trim($data['key_word'])."%' OR f.gear_category_name LIKE   '%".trim($data['key_word'])."%' )   AND ";
			}
			$data['ks_gear_type_id']				= urldecode($this->input->get('ks_gear_type_id'));
			if($data['ks_gear_type_id'] != ''){
				$ks_gear_type =  explode(',', $data['ks_gear_type_id']) ;
					$ks_gear_type_ids = '' ;
					foreach ($ks_gear_type as $gear_type) {
					$ks_gear_type_ids.= "'". $gear_type."',";
					}
					//$where .=  "a.ks_gear_type_id =  '".trim($data['ks_gear_type_id'])."' AND ";
					$where .=  "a.ks_gear_type_id IN (".rtrim($ks_gear_type_ids , ',').") AND ";
			}

			// gear category loops start
			$data['gear_category_id']				= urldecode($this->input->get('gear_category_id'));
			if($data['gear_category_id'] != ''){
					$gear_category_array =  explode(',', $data['gear_category_id']) ;
					$gear_category_ids = '' ;
					foreach ($gear_category_array as $gear_category) {
					$gear_category_ids.= "'". $gear_category."',";
					}
				 
					$where .=  "a.ks_category_id IN (".rtrim($gear_category_ids , ',').") AND ";
			}
			// gear category loops end 

			
			// manufaturer loops start
			$data['manufacturer_id']				= urldecode($this->input->get('manufacturer_id'));
			if($data['manufacturer_id'] != ''){
				$manufacturers_array =  explode(',', $data['manufacturer_id']) ;
				$manufacturers_ids = '' ;
				foreach ($manufacturers_array as $manufacturers) {
					$manufacturers_ids.= "'". $manufacturers."',";
				}
				 
				 $where .=  "a.ks_manufacturers_id IN (".rtrim($manufacturers_ids , ',').")  AND ";
			}	

			// manufaturer loops end 

			// model loops start
			$data['model_id']				= urldecode($this->input->get('model_id'));
			if($data['model_id'] != ''){
				$model_id_array =  explode(',', $data['model_id']) ;
				$models_ids = '' ;
				foreach ($model_id_array as $models) {
					$models_ids.= "'". $models."',";
				}
				 
				 $where .=  "a.model_id IN (".rtrim($models_ids , ',').")  AND ";
			}	

			// model loops end 


			//Avalbilty date check  start

			 $date['from_date'] =  urldecode($this->input->get('from_date'));
		 	$date['to_date'] =  urldecode($this->input->get('to_date'));
		
			if ( !empty($date['from_date'])  && !empty($date['to_date'])) {
						//$where .= " a.user_gear_desc_id NOT  IN ( SELECT a1.user_gear_desc_id FROM ks_user_gear_description AS a1  INNER JOIN ks_gear_unavailable  ON a1.user_gear_desc_id = ks_gear_unavailable.user_gear_description_id    WHERE  `a1`.`is_active` = 'Y'AND `a1`.`gear_hide_search_results` = 'Y'AND `a1`.`gear_type` != '3'AND `a1`.`ks_gear_type_id` != '3'  AND (ks_gear_unavailable.unavailable_from_date < '".$date['from_date']."'   AND  ks_gear_unavailable.unavailable_to_date <'".$date['to_date']."' ))  AND " ;
						// print_r($where);die;
			}else{
				// /echo "hello";
				$date['from_date'] =date('Y-m-d');
				$date['to_date']   =date('Y-m-d') ;
					//$where .= " a.user_gear_desc_id NOT  IN ( SELECT a1.user_gear_desc_id FROM ks_user_gear_description AS a1  INNER JOIN ks_gear_unavailable  ON a1.user_gear_desc_id = ks_gear_unavailable.user_gear_description_id    WHERE  `a1`.`is_active` = 'Y'AND `a1`.`gear_hide_search_results` = 'Y'AND `a1`.`gear_type` != '3'AND `a1`.`ks_gear_type_id` != '3'  AND (ks_gear_unavailable.unavailable_from_date < '".$date['from_date']."'   AND  ks_gear_unavailable.unavailable_to_date <'".$date['to_date']."' ))  AND " ;
			}

			//echo $where ;die;
			//Avalbilty date check  start

			//FEATURES CHECK   Start
				$data['feature_master_id'] = urldecode($this->input->get('feature_master_id'));
				if ($data['feature_master_id'] != '') {
					
					$where .= " g_feat.feature_master_id = ".$data['feature_master_id']." AND  "; 
				}


			//FEATURES CHECK   End	

			//Owner Type Check start 
			
			$data['owner_type_id'] = urldecode($this->input->get('owner_type_id'));
			if ($data['owner_type_id'] != '') {

				$owner_type_id_array =  explode(',', $data['owner_type_id']) ;
				$owner_type_ids = '' ;
				foreach ($owner_type_id_array as $owners_type) {
					$owner_type_ids.= "'". $owners_type."',";
				}
				 
				 $where .=  "b.owner_type_id IN (".rtrim($owner_type_ids , ',').")  AND ";
					// $where .= " b.owner_type_id = ".$data['owner_type_id']." AND  "; 
			}

			//Owner Type Check END 
			
			//SUB_CATEGRY_iD Check Start 
			 $data['sub_category_id'] = urldecode($this->input->get('sub_category_id'));
			if ($data['sub_category_id'] != '') {
					$sub_category_id_array =  explode(',', $data['sub_category_id']) ;
				$sub_category_ids = '' ;
				foreach ($sub_category_id_array as $subcategory) {
					$sub_category_ids.= "'". $subcategory."',";
				}
				 $where .=  "a.ks_sub_category_id IN (".rtrim($sub_category_ids , ',').")  AND ";
					//$where .= " a.ks_sub_category_id = ".$data['sub_category_id']." AND  "; 
			}
			//echo $where ;
			//SUB_CATEGRY_iD Check END

			//Address Check Start

			$data['address'] = urldecode($this->input->get('address'));
			// if ($data['address'] != '') {
			// 	if (strpos($data['address'], ', Australia') !== false) {
			// 		$data['address'] = 	str_replace(', Australia', '', $data['address']) ;
			// 	}	
			// 	echo   $data['address']  ;//die;
			// 		//print_r($data['address']);die;
			// 		$where .= " u_add.street_address_line1 LIKE  '%".trim($data['address'])."%' OR u_add.street_address_line2 LIKE  '%".trim($data['address'])."%'   OR ks_suburbs.suburb_name LIKE  '%".trim($data['address'])."%' OR ks_states.ks_state_name LIKE  '%".trim($data['address'])."%'    AND  "; 
			// }
		//Address Check END

			if($this->input->get('order_by_fld')!=""){
				if ($this->input->get('order_by_fld')== 'price') {
					$order_by_fld ='per_day_cost_aud_ex_gst'; 
				}else{
					$order_by_fld = $this->input->get('order_by_fld') ;
				}
				$order_by_fld = $order_by_fld;
			}else
				$order_by_fld = "";
			//

			
			$data['order_by_fld']=$order_by_fld;

			// order by fileds distance

			$data['searchby_fields'] = urldecode($this->input->get('searchby_fields'));
			if (!empty($data['address'])) {
				//echo $data['searchby_fields'] ;
				if (!empty($data['address'])) { 
					$lat_array =  $this->GetLatLng($data['address']);

					$lat_array  = json_decode($lat_array);
					// print_r($lat_array);die;
					
						if ($lat_array->status  != 'ZERO_RESULTS'){
							 $lat = $lat_array->results[0]->geometry->location->lat;
							 $lng = $lat_array->results[0]->geometry->location->lng;
							if (empty(urldecode($this->input->get('radius')))) {
								 $radius = '25';

							}else{
								$radius = urldecode($this->input->get('radius'));
							}
							$postal_arr=$this->home_model->zipcodeRadius($lat,$lng,$radius);
							// print_r($postal_arr);
							// die;
							if(count($postal_arr)>0){
										$postal_arr_list=implode(",",$postal_arr);

										// $where .="	 (u_add.`postcode` IN (".$postal_arr_list.")) AND ";
							}
						}else{
							
						}
						
					

					$where .= " ( 3959 * acos( cos( radians('".$lat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$lng."') ) + sin( radians('".$lat."') ) * sin( radians( lat ) ) ) ) < '".$radius."' AND  "; 
				}	
					//print_r($data['address']);die;
					//$where .= " u_add.street_address_line1 LIKE  '%".trim($data['address'])."%' OR u_add.street_address_line2 LIKE  '%".trim($data['address'])."%'   OR ks_suburbs.suburb_name LIKE  '%".trim($data['address'])."%' OR ks_states.ks_state_name LIKE  '%".trim($data['address'])."%'    AND  "; 
			}
			
			$where .= "a.is_active='Y' AND a.gear_hide_search_results ='Y' ";

			$query = $this->home_model->getModelsList($where,$data['limit'],$offset,$order_by_fld,$order_by);

			$query1 = $this->home_model->getModelsList($where); 
			$total=$query;
			$total_rows=count($query1);
			
			$data['result'] = $total;
			$data['total_rows']=$total_rows;
			$data['limit']=$data['limit'];

			if ($data['key_word'] != '') {
				$URL .='&key_word='.$data['key_word'] ; 
			}
			if ($data['gear_category_id'] != '') {
				$URL .='&gear_category_id='.$data['gear_category_id'] ; 
			}


			$config['base_url'] = base_url()."Gear_listing/Search?order_by=".$order_by."&order_by_fld=".$order_by_fld."&limit=".$data['limit'].$URL;
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
			
			//print_r($data['result']);
			if(count($total)){
				foreach($total as $values ){
					//print_r($values['model_id']);die;

					$models_array[]  = $values['model_id'] ;
					$category_array[]  = $values['ks_category_id'] ;
					$brand_array[]  = $values['ks_manufacturers_id'] ;
					$user_gear_array[]  = $values['user_gear_desc_id'] ;
				}
				// Models data list 
				$models_array =   array_unique($models_array) ; 
				if (count($models_array) > 0 ) {
					$models_list = '';
					foreach ($models_array as $models) {
						$models_list .= "'". $models."',";
					}
					$sql =  "SELECT model_id,model_name FROM ks_models WHERE  model_id IN (".rtrim($models_list , ',').") " ;
					$model_data = $this->common_model->get_records_from_sql($sql);
					$data['model_data'] = $model_data;
				}else{
					$data['model_data'] = array();
				}

				// category List

					$query =  $this->common_model->GetAllWhere('ks_gear_categories',  array('is_active' => 'Y' , 'gear_sub_category_id'=>'0' ));
					$category_data = $query->result_array();
					if (!empty($category_data)) {
						$i  = 0 ;
						foreach ($category_data as  $value) {
							
							$query =  $this->common_model->GetAllWhere('ks_gear_categories',  array('is_active' => 'Y' , 'gear_sub_category_id'=>$value['gear_category_id'] ));
							$subcateory_data = $query->result_array();
							$j = 0 ;
							foreach($subcateory_data as $row){
						
							$result_arr[$j]['gear_sub_category_id']=$row['gear_category_id'];
							$result_arr[$j]['gear_sub_category_name']=$row['gear_category_name'];

							$result_arr[$j]['gear_parent_id']=$row['gear_sub_category_id'];
							$result_arr[$j]['type'] = 'sub_category';

							$j++;
						}
							$category_data[$i]['type'] = 'category';
							$category_data[$i]['gear_sub_category'] = $result_arr;

							$i++;
						}
					}
					$data['category_data'] = $category_data ;
				// $category_array =  array_unique($category_array) ; 
				// if (count($category_array) > 0 ) {
				// 	$category_list = '';
				// 	foreach ($category_array as $category) {
				// 		$category_list .= "'". $category."',";
				// 	}
				// 	$sql =  "SELECT gear_category_id,gear_category_name FROM ks_gear_categories WHERE  gear_sub_category_id = ''" ;
				// 	$category_data = $this->common_model->get_records_from_sql($sql);
				// 	$data['category_data'] = $category_data;
				// }else{

				// 	$data['category_data'] = array();
				// }
				// brand List
				$brand_array =  array_unique($brand_array) ;
				if (count($brand_array) > 0 ) {
					$brand_list = '';
					foreach ($brand_array as $brands) {
						$brand_list .= "'". $brands."',";
					}
					$sql =  "SELECT manufacturer_id,manufacturer_name	 FROM ks_manufacturers WHERE  manufacturer_id IN (".rtrim($brand_list , ',').") " ;
					$brand_data = $this->common_model->get_records_from_sql($sql);
					$data['brand_data'] = $brand_data;
				}else{
					$data['brand_data'] = array();
				}

				// location list
				$user_gear_array =  array_unique($user_gear_array) ;
				if (count($user_gear_array) > 0 ) {
					$address_list = '';
					foreach ($user_gear_array as $geasrs_ids) {
						$address_list .= "'". $geasrs_ids."',";
					}
					// if (empty($postal_arr_list)) {
					// 		$sql =  "SELECT ks_user_address.*,ks_states.ks_state_name ,ks_suburbs.suburb_name,a.per_day_cost_aud_inc_gst,
					// 		   a.per_day_cost_aud_ex_gst	 FROM ks_gear_location   INNER JOIN  ks_user_address ON ks_user_address.user_address_id  =  ks_gear_location.user_address_id  INNER JOIN  ks_states ON   ks_states.ks_state_id = ks_user_address.ks_state_id  INNER JOIN ks_suburbs   ON ks_suburbs.ks_suburb_id = ks_user_address.ks_suburb_id  INNER JOIN ks_user_gear_description As a ON  ks_gear_location.user_gear_desc_id = a.user_gear_desc_id  WHERE   ks_gear_location.user_gear_desc_id IN (".rtrim($address_list , ',').") GROUP " ;			
					// }else{
					// 		$sql =  "SELECT ks_user_address.*,ks_states.ks_state_name ,ks_suburbs.suburb_name,a.per_day_cost_aud_inc_gst,
					// 		   a.per_day_cost_aud_ex_gst	 FROM ks_gear_location   INNER JOIN  ks_user_address ON ks_user_address.user_address_id  =  ks_gear_location.user_address_id  INNER JOIN  ks_states ON   ks_states.ks_state_id = ks_user_address.ks_state_id  INNER JOIN ks_suburbs   ON ks_suburbs.ks_suburb_id = ks_user_address.ks_suburb_id  INNER JOIN ks_user_gear_description As a ON  ks_gear_location.user_gear_desc_id = a.user_gear_desc_id  WHERE   ks_user_address.postcode IN (".rtrim($postal_arr_list , ',').") " ;
					// }
					$sql =  "SELECT ks_user_address.*,ks_states.ks_state_name ,ks_suburbs.suburb_name,a.per_day_cost_aud_inc_gst,a.per_day_cost_aud_ex_gst	 FROM ks_gear_location   INNER JOIN  ks_user_address ON ks_user_address.user_address_id  =  ks_gear_location.user_address_id  INNER JOIN  ks_states ON   ks_states.ks_state_id = ks_user_address.ks_state_id  INNER JOIN ks_suburbs   ON ks_suburbs.ks_suburb_id = ks_user_address.ks_suburb_id  INNER JOIN ks_user_gear_description As a ON  ks_gear_location.user_gear_desc_id = a.user_gear_desc_id  WHERE   ks_gear_location.user_gear_desc_id IN (".rtrim($address_list , ',').")  " ;
					$address_data = $this->common_model->get_records_from_sql($sql);
					$data['address_data'] = $address_data;
				}else{
					$data['address_data'] = array();
				}


				// print_r(array_unique($brand_array));


				if (!empty($data['gear_category_id'])) {
					//print_r($data['gear_category_id']);
						$gear_category_array =  explode(',', $data['gear_category_id']) ;
					$gear_category_ids = '' ;
					foreach ($gear_category_array as $gear_category) {
					$gear_category_ids.= "'". $gear_category."',";
					}
				 
						$sql1 =  "SELECT * FROM ks_feature_master WHERE   gear_category_id IN (".rtrim($gear_category_ids , ',').")  ";
						$data['category_feature'] = $this->common_model->get_records_from_sql($sql1) ;	
						//= $query1->result_array()	;

						$sql2 = "SELECT * FROM ks_gear_categories WHERE gear_sub_category_id   IN (".rtrim($gear_category_ids , ',').")  " ;
						$data['subcategorylist']= $this->common_model->get_records_from_sql($sql2) ;	
						//$data['subcategorylist'] = $query1->result_array()	;




				}	
				
				$query1 = $this->common_model->GetAllWhere('ks_renter_type',"") ;	
				$data['owner_data'] = $query1->result_array()	;

				$query = $this->common_model->GetAllWhere('ks_gear_type',array('ks_gear_type_id !='=> '3')) ;	
				$data['gear_type'] = $query->result_array()	;


				
			}
			
			//die;
			echo json_encode($data, true);
		 	
		} 

		// GET ADDRESS FROM GOOGLE PLACE API ADDRESS 

		public function AddressAPi()
		{
			
			$post_data  = json_decode(file_get_contents("php://input"),true);
			
			$curl = curl_init();
			$six_digit_random_number = mt_rand(100000, 999999);
			curl_setopt_array($curl, array(
			  //CURLOPT_URL => "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=".urlencode($post_data['address'])."&key=AIzaSyAf45CFtRHn_UGLWKP1bQODqssLoBR42xA&sessiontoken=".$six_digit_random_number,
			  CURLOPT_URL => "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=".urlencode($post_data['address'])."&key=AIzaSyCPU8keawNREwb4_tHM8D1mcw4bRuSEoUQ&components=country:Au&sessiontoken=".$six_digit_random_number,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",	
			  CURLOPT_HTTPHEADER => array(
			    
			    "postman-token: 98dabf75-69d4-5531-fa2c-4c9242d3c56a"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {

				$response=  json_decode($response);
				
			
				if (array_key_exists('error_message',$response)) {
					$response1['status'] = 400;
					$response1['status_message'] = ' limit exist';
					$response1['result'] = array();
					$json_response = json_encode($response1);
					echo $json_response;

					exit();

				}	
				if (!empty($response->predictions)) {

					foreach ($response->predictions as  $value) {
						
						$address_list[] = $value->description;
					}

					$response1['status'] = 200;
					$response1['status_message'] = 'address list';
					$response1['result'] = $address_list;
					$json_response = json_encode($response1);
					echo $json_response;
					
					exit();
					//print_r($address_list);
				}else{
					$response1['status'] = 200;
					$response1['status_message'] = ' no address list found';
					$response1['result'] = array();
					$json_response = json_encode($response1);
					echo $json_response;
					
					exit();

				}

			 // echo $response;
			}
		}

		// Add Cart 


		public function AddCart()
		{
			
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);

			 if ($app_user_id != '') {
			 		$user_gear_desc_id =  $post_data['user_gear_desc_id'];
					$date_from =  strtotime($post_data['date_from']);
					$date_to =  strtotime($post_data['date_to']);
					$Cart_details = $this->home_model->getUserCart($app_user_id);
					if (!empty($Cart_details)) {
						 $date_from =  strtotime(date('d-m-Y',strtotime($Cart_details[0]['gear_rent_request_from_date'])));	
						 $date_to =  strtotime( date('d-m-Y',strtotime($Cart_details[0]['gear_rent_request_to_date'])));	
						
					}
					$dates_array = $this->getDateList($date_from,$date_to);
					
					
					$gaer_data = $this->home_model->geratDetails($user_gear_desc_id);
					// print_r($gaer_data);die;
					if (count($Cart_details) > 0 ) {
						if($Cart_details[0]['app_user_id'] != $gaer_data->app_user_id){
							$app_user_id = '';
							$response['status'] = 400;
							$response['status_message'] = 'Empty your Cart before adding another Members listing';
							$json_response = json_encode($response);
							echo $json_response;
							
							exit();

						}	
					}
					
					//print_r($gaer_data);die;
					if ($gaer_data->app_user_id  == $app_user_id) {
							$app_user_id = '';
							$response['status'] = 400;
							$response['status_message'] = 'Cannot Add own gear to cart';
							$json_response = json_encode($response);
							echo $json_response;
							
							exit();
					}
					if (!empty($Cart_details)) {
						
						$query = $this->common_model->GetAllWhere('ks_gear_location', array('user_gear_desc_id'=>$Cart_details[0]['user_gear_desc_id']));
						$cart_gear_location = $query->result();

						$query = $this->common_model->GetAllWhere('ks_gear_location', array('user_gear_desc_id'=>$user_gear_desc_id));
						$add_gear_location = $query->result();
						$address_list = array();
						foreach ($cart_gear_location as  $gear_location) {
							
							foreach ($add_gear_location as  $add_gear_value) {
								if($add_gear_value->user_address_id  == $gear_location->user_address_id ){
									$address_list[]= $gear_location;	
								}else{
								}
							}
						}
						if(empty($address_list)){
								$app_user_id = '';
								$response['status'] = 400;
								$response['status_message'] = 'Gear cannot be added to cart because it is not available at same location as other items';
								$json_response = json_encode($response);
								echo $json_response;
								
								exit();

						}
					}	
					

					$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
					$user_details = $query->row();
	 				$query1	= $this->common_model->GetAllWhere('ks_settings',"");
	 				$setting = $query1->row();

	 				if ($gaer_data->ks_gear_type_id == '1') {
	 					$rentDays =count($dates_array);
	 				}else{
	 					$rentDays = 	$this->GetRentDays($date_from,$date_to);
	 				}
	 				
	 				
	 				$security_deposit =  $gaer_data->security_deposite;
	 				$total_cost_ex_gst =  $gaer_data->per_day_cost_aud_ex_gst * $rentDays ;
					
					

							$security_deposit =   $gaer_data->security_deposite_inc_gst;
							$total_cost_non_gst = $total_cost_ex_gst;
						 	$beta_discount = ($total_cost_non_gst*15)/100 ; 
					 		$insurance_fee = '0'; 
					 		$community_fee = ($total_cost_non_gst*5)/100 ; 
					 	    $sub_total = $total_cost_ex_gst - $beta_discount + $insurance_fee + $community_fee ;
							if ($user_details->registered_for_gst != 'Y' ) {
								 $totla_gst =   ((($total_cost_ex_gst + $insurance_fee + $community_fee -$beta_discount)*$setting->gst_percent  )/100 ) ;
							}else{
								 $totla_gst =   ((($total_cost_ex_gst + $insurance_fee + $community_fee -$beta_discount)*$setting->gst_percent  )/100 ) ;
							}
							$total_gst =  $totla_gst ;
						 	$total_cost_with_gst = $sub_total  +  $totla_gst;
					 $date_from= 	date('Y-m-d h:i:s',$date_from);
					 $date_to= 	date('Y-m-d h:i:s',$date_to);
					
						$ks_rent_status_master =  array(
								"ks_rent_status_master_text"=> 'ADD CART' ,
								"effective_from_date"=> $date_from,
								"effective_to_date"=> $date_to,
								'is_active'=>'N',
								'create_user'=> $app_user_id,
								"create_date"=> date('Y-m-d')
							);
					$ks_rent_status_master_id = 	$this->common_model->AddRecord($ks_rent_status_master,'ks_rent_status_master');
					$add_data = array(
					         "app_user_id" => $app_user_id,
					         "gear_rent_requested_on"=>date('Y-m-d h:i:m'),
					         "gear_rent_request_from_date"=>$date_from,
					         "gear_rent_request_to_date" =>$date_to,
					         "gear_total_rent_request_days" => $rentDays,
					         "gear_rent_start_date"=>$date_from,
					         "gear_rent_end_date"=>$date_to,
					         "total_rent_days"=>count($dates_array),
					         "total_discount"=>'0',
					         "total_rent_amount_ex_gst"=> $total_cost_non_gst,
					         "gst_amount"=>$total_gst ,
					         "other_charges" => 0 ,
					         "security_deposit" => $security_deposit,
					         "total_rent_amount"=>$total_cost_with_gst ,
					         "rent_request_sent_by"=> $app_user_id,
					         "create_user"=>$app_user_id,
					         "create_date"=> date('Y-m-d'),
					         "ks_rent_status_master_id"=>$ks_rent_status_master_id
					);
					$user_gear_rent_id = 	$this->common_model->AddRecord($add_data,'ks_user_gear_rent_master');
					// $user_gear_rent_id = '';
					$detials_data=array(
						"user_gear_desc_id" => $user_gear_desc_id,
						"user_gear_rent_id" => $user_gear_rent_id,
						"gear_rent_requested_on" => date('Y-m-d h:i:m'),
						"gear_rent_request_from_date"=>$date_from,
						"gear_rent_request_to_date"=>$date_to ,
						"gear_total_rent_request_days" =>$rentDays,
						"gear_rent_start_date" =>$date_from ,
						"gear_rent_end_date" =>$date_to  ,
						"total_rent_days" =>$rentDays ,
						"gear_discount" =>'0' ,
						"total_rent_amount_ex_gst"=>$total_cost_non_gst ,
						"beta_discount"=>$beta_discount,
						"insurance_fee"=>$insurance_fee,
						"community_fee"=>$community_fee,
						"gst_amount"=> $total_gst,
						"other_charges" =>'0',
						"security_deposit" => $security_deposit,
						"total_rent_amount"=>$total_cost_with_gst,
						"is_rent_approved"=>'N',
						"rent_approved_rejected_on"=> '',
						"is_rent_cancelled" => 'N',
						"create_user"=>$app_user_id
						);
					$user_gear_rent_id = 	$this->common_model->AddRecord($detials_data,'ks_user_gear_rent_details');
					$this->db->last_query();
					// die;
					$response['status'] = 200;
					$response['status_message'] = 'Gear added to cart';
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

		// User Cart List
		public function UserCart($value='')
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				 $Cart_details = 	 $this->home_model->getUserCart($app_user_id);
					if (count($Cart_details)) {
					// print_r($Cart_details);
					 $total_rent_amount_ex_gst = '0';
					 $gst_amount = '0';
					 $other_charges = '0';
					 $total_rent_amount = '0';
					 $security_deposit = '0';
					 $beta_discount = '0';
					 $insurance_fee = '0';
					 $community_fee = '0';

					$query =  $this->common_model->GetAllWhere('ks_settings','');
					$settings =  $query->row();

					 $i= 0 ;
				 	foreach ($Cart_details as  $value) {
				 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
				 			
				 			 $other_charges  += $value['other_charges'] ; 
				 			 $user_address_id = $value['user_address_id'] ;
				 			// $total_rent_amount  += $value['total_rent_amount'] ; 
				 			 $security_deposit +=   '0';
				 			 $beta_discount += $value['beta_discount'] ; 
					 		 $insurance_fee += $value['insurance_fee']; 
					 		 $community_fee += $value['community_fee']; 
					 		 $sub_amount = $value['total_rent_amount_ex_gst'] - $value['beta_discount'] +  $value['insurance_fee'] +  $value['community_fee'] ; 
					 		 $total_rent_amount  += $sub_amount ;
					 		 
				 			 $app_user_id_array[] = $value['app_user_id'] ;
				 			 if ($value['gear_display_image'] == '') {
				 			 	 $Cart_details[$i]['gear_display_image']   = '';
				 			 }else{
				 			 $Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image']; 
				 			}

				 			if ($value['security_deposit_check'] != '1') {
				 				 $Cart_details[$i]['security_deposit']   = '0.00';
				 			}

				 			if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value) {
				 				 $Cart_details[$i]['insurance_check_key']   = 1;	
				 			}else{
				 				 $Cart_details[$i]['insurance_check_key']   = 0;	
				 			}
				 			 $i++;
				 		}	

				 		if (!empty($app_user_id_array)) {	
				 			$app_user_id_array =  array_unique($app_user_id_array);
				 		//	$app_user_id_array =  explode(',', $app_user_id_array) ;
							$app_user_ids = '' ;
							foreach ($app_user_id_array as $app_user) {
								$app_user_ids.= "'". $app_user."',";
							}
						$sql1 =  "SELECT app_user_id ,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
						$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
						$app_users_details[0]->owner_id =  $app_users_details[0]->app_user_id;
						$app_users_details[0]->renter_id = $app_user_id;
						$rating =$this->home_model->GetUSerRatings($app_users_details[0]->app_user_id);
						$app_users_details[0]->rating =$rating->rating;

						$review_result= $this->home_model->ProductReview($app_users_details[0]->app_user_id);
						 $review_result=$review_result->result_array();
						 if (!empty($review_result)) {
						 	$app_users_details[0]->reviews = count($review_result) ;
						 }else{
						 	$app_users_details[0]->reviews = 0 ;
						 }
						// print_r($review_result);die;
				 		}
				 		
				 		$gst_amount = ($total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee)*10/100;
				 		$rent_summary = array(
				 								'user_address_id'=>$user_address_id,
				 								'gear_total_rent_request_days'=>$Cart_details[0]['gear_total_rent_request_days'],
				 								'per_day_cost_aud_ex_gst'=>$Cart_details[0]['per_day_cost_aud_ex_gst'],
				 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
				 								'gst_amount'=> number_format((float)( $gst_amount), 2, '.', '')  ,
				 								'beta_discount'=> number_format((float)( $beta_discount), 2, '.', '') ,
				 								'insurance_fee'=>  number_format((float)( $insurance_fee), 2, '.', ''),
				 								'community_fee'=>  number_format((float)( $community_fee), 2, '.', '') ,
				 								'other_charges'=>  number_format((float)( $other_charges), 2, '.', ''),
				 								'sub_total'=> number_format((float)( $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee), 2, '.', '')  ,
				 								'total_rent_amount'=> number_format((float)($total_rent_amount+$gst_amount ), 2, '.', '') ,
												'security_deposit' => number_format((float)( $security_deposit), 2, '.', '')  ,		 								
				 								'total_amount'=>  number_format((float)( $gst_amount+$other_charges+$total_rent_amount + $security_deposit), 2, '.', '')   
				 							);
				 	$response['status'] = 200;
					$response['status_message'] = 'User cart List';
					$response['result'] = array('cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details );
					$json_response = json_encode($response);
					echo $json_response;
				 }
				 else{
				 	$response['status'] = 200;
					$response['status_message'] = 'No gear incart List';
					$response['result'] = array('cart'=>"",'cart_summary'=>"");
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
		//Remove Gear From Cart
		public function RemoveGearFromCart()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
					$check_cart = $this->home_model->CheckGearInCart($post_data['user_gear_desc_id'], $app_user_id);
					if (count($check_cart)) {
						//print_r($check_cart);
						 $user_gear_rent_detail_id=  $check_cart->user_gear_rent_detail_id;
						 $user_gear_rent_id = $check_cart->user_gear_rent_id;
						 $ks_rent_status_master_id = $check_cart->ks_rent_status_master_id;

						$remove_cart  =  $this->home_model->RemoveGearCart($user_gear_rent_detail_id,$user_gear_rent_id,$ks_rent_status_master_id);
						$response['status'] = 200;
						$response['status_message'] = 'Gear removed from Cart Successfully';
						$json_response = json_encode($response);
						echo $json_response;
					}else{
							$response['status'] = 400;
							$response['status_message'] = 'Gear not in cart';
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

		//  GET subcategry from category by category id
		public function SubcategorylistByID($gear_category_id='')
	    {
			 $gear_category_id = $this->input->get('gear_category_id');
			
	    	$table = "ks_gear_categories";
			$where_clause = "is_active='Y'  AND gear_sub_category_id = '".$gear_category_id."' ";
			$query1 = $this->common_model->RetriveRecordByWhere($table,$where_clause);
			//echo $this->db->last_query();die;
			$value = $query1->result();
			if (empty($value)) {
				$response=array("status"=>200,
							"status_message"=>"success",
							"result"=>array());
				echo json_encode($response);
				exit();
			}
			
			
			$i=0;
				foreach($value as $row){
					//print_r($row->gear_category_name);die;
					$result_arr[$i]['gear_sub_category_id']=$row->gear_category_id;
					$result_arr[$i]['gear_sub_category_name']=$row->gear_category_name;

					$result_arr[$i]['gear_parent_id']=$row->gear_sub_category_id;
					

					$i++;
				}
			
			$response=array("status"=>200,
							"status_message"=>"success",
							"result"=>$result_arr);
			echo json_encode($response);
			exit();
	    }

	    // GET day and dates between 2 days 
		public function getDateList($date_from,$date_to)
		{
			
				$dates = array();
				while($date_from <= $date_to)
				{
					$values= array( 'date'=>date( 'Y-m-d',$date_from) ,
							'day'=>date('D', strtotime(date( 'Y-m-d',$date_from)))
						)  ; 

				    array_push( $dates,$values);
				    $date_from += 86400;
				}
				return $dates ;
		}


	 	// Insurance Check 

	 	public function InsuranceCheck()
	 	{
	 		$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {

				$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
				$insurance_type = $query->row();
				if ($post_data['insurance_type'] == '1' ) { /// Damage Waiver
					
					$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
					$insurance_type = $query->row();

					// $query =  $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=> $app_user_id) ) ; 
					// $user_details =  $query->row();
					//  $gaer_data = $this->home_model->geratDetails($post_data['user_gear_desc_id']);
				 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'], 'order_id ='=>''));
				 	$Cart_details = $query1->row();
				 	$date_from = strtotime($Cart_details->gear_rent_request_from_date);
				 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
				 	$diff = abs($date_to - $date_from);
					//$date_array =  $this->getDateList($date_from,$date_to);
					 $date_array =  $this->getDateList($date_from,$date_to);
					
					$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
				 	$gear_details = $query1->row();
				 	$query =  $this->common_model->GetAllWhere('ks_settings','');
					$settings =  $query->row();
					
					if($gear_details->replacement_value_aud_ex_gst > $settings->max_replacement_value){
								$app_user_id = '';
								$response['status'] = 401;
								$response['status_message'] = ' The Replacement value for the total cart must not exceed  '.$settings->max_replacement_value .' ex GST';
								$json_response = json_encode($response);
								echo $json_response;
								exit();
							}
				 	// print_r($gear_details->replacement_value_aud_ex_gst);die;
				 	if (count($date_array ) <= 1 ) {
				 		# code...
				 	}elseif(count($date_array ) == 2 ){
				 			   $date_array;

				 	}else{
				 		foreach ($date_array as $value) {
				 			 $val_date[] =   $value['date'];
				 		}
				 		 $date_array =  $val_date;
							array_pop($date_array); 
					}						  
					
					$insurance_days = count($date_array);
					 
				 	// print_r($Cart_details);die;
				 	// if ($user_details->registered_for_gst == 'Y') {
				 	// 		$gear_replacement_value = $gaer_data->replacement_value_aud_inc_gst;
				 	// }else{
				 	// 		$gear_replacement_value = $gaer_data->replacement_value_aud_ex_gst ;
				 	// }
				 		// $where_clause1 = array(
				 		// 					'initial_value <' => $gear_replacement_value,
				 		// 					'end_value > ' => $gear_replacement_value,
				 		// 					'status'=> '0',
				 		// 					'is_deleted' => '0',
				 		// 					'ks_insurance_category_type_id'=> $post_data['insurance_type']
					 	// 			  );

				 		$where_clause1 = array(
				 							'initial_value <' => $Cart_details->total_rent_amount_ex_gst,
				 							'end_value > ' => $Cart_details->total_rent_amount_ex_gst,
				 							'status'=> '0',
				 							'is_deleted' => '0',
				 							'ks_insurance_category_type_id'=> $post_data['insurance_type']
					 				  );
					$query =  $this->common_model->GetAllWhere(' ks_insurance_tiers	' , $where_clause1) ; 
					$insurance_tier_type = $query->row();
					
				 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
				 	$rent_master_details = $query2->row();
				 		
				 	//$Cart_details->other_charges =  ($Cart_details->total_rent_amount_ex_gst*  $insurance_type->percent)/100 ;
				 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit; 
					
					

					$Cart_details->insurance_amount = number_format((float)(($insurance_tier_type->tiers_percentage *$insurance_days*$gear_details->per_day_cost_aud_ex_gst)/100) , 2, '.', '');  
					$Cart_details->insurance_fee =  number_format((float)(($insurance_tier_type->tiers_percentage *$insurance_days*$gear_details->per_day_cost_aud_ex_gst)/100) , 2, '.', '');
				 	$gear_insurance_value = $Cart_details->insurance_amount;	
				 	$rent_master_details->other_charges	 =  '0' ;
				 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
				 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
				 	$Cart_details->insurance_tier_id =  $insurance_tier_type->tiers_id  ; 
				 	$Cart_details->other_charges =  '0' ; 
				 	$Cart_details->security_deposit =  '0' ; 
				 	$Cart_details->deposite_status =  'NONE' ; 
				 		
				 
				 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
				 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
				 	 $Cart_details = 	 $this->home_model->getUserCart($app_user_id);
					//echo  $this->db->last_query();
					if (count($Cart_details)) {
						// print_r($Cart_details);
						 $total_rent_amount_ex_gst = '0';
						 $gst_amount = '0';
						 $other_charges = '0';
						 $total_rent_amount = '0';
						 $security_deposit = '0';
						 $beta_discount = '0' ; 
				 		 $insurance_fee = '0' ; 
				 		 $community_fee = '0' ; 
						 $i= 0 ;
					 	foreach ($Cart_details as  $value) {
					 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
					 			 $gst_amount  += $value['gst_amount'] ; 
					 			 $other_charges = '0';
					 			 $total_rent_amount  += $value['total_rent_amount'] ; 
					 			 $security_deposit +=   '0';
					 			 $beta_discount += $value['beta_discount']; 
						 		 $insurance_fee += $value['insurance_fee'] ; 
						 		 $community_fee += $value['community_fee'] ; 
					 			 $app_user_id_array[] = $value['app_user_id'] ;
					 			 $Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image']; 
					 			 $i++;
					 		}	

					 		if (!empty($app_user_id_array)) {	
					 			$app_user_id_array =  array_unique($app_user_id_array);
					 		//	$app_user_id_array =  explode(',', $app_user_id_array) ;
								$app_user_ids = '' ;
								foreach ($app_user_id_array as $app_user) {
									$app_user_ids.= "'". $app_user."',";
								}

									$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
									$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
						
					 		}
					 			$insurance_type->amount = $gear_insurance_value  ;
					 		//$gst_amount = ($total_rent_amount_ex_gst*10)/100;
					 		$rent_summary = array(
					 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
					 								'gst_amount'=>$gst_amount,
					 								'beta_discount'=> $beta_discount,
					 								'insurance_fee'=> $insurance_fee,
					 								'community_fee'=> $community_fee,
					 								'other_charges'=>$other_charges,
					 								'sub_total'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee,
					 								'total_rent_amount'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee + $gst_amount,
													'security_deposit' => $security_deposit,		 								
					 								'total_amount'=> $total_rent_amount+$community_fee+$insurance_fee -$beta_discount 
					 							);
					 	$response['status'] = 200;
						$response['status_message'] = 'User cart List';
						$response['result'] = array('cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details ,'insurance_type'=>$insurance_type);
						$json_response = json_encode($response);
						echo $json_response;	
					}		
					
				}elseif($post_data['insurance_type'] == '4'){	// No Insurance
						$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
				 		$Cart_details = $query1->row();
						 
						 $query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
				 		 $rent_master_details = $query2->row();

						$rent_master_details->other_charges = '0' ;
					 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
					 	$Cart_details->insurance_amount =  '0'; 
					 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
					 	$Cart_details->insurance_tier_id =  '0'  ; 
					 	$Cart_details->other_charges =  '0' ; 
					 	$Cart_details->security_deposit =  '0' ; 
				 		$Cart_details->deposite_status =  'NONE' ; 
					 	$insurance_type->amount = '0'; ;
					 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
					 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
						 $Cart_details = 	 $this->home_model->getUserCart($app_user_id);
							//echo  $this->db->last_query();
					if (count($Cart_details)) {
						// print_r($Cart_details);
						 $total_rent_amount_ex_gst = '0';
						 $gst_amount = '0';
						 $other_charges = '0';
						 $total_rent_amount = '0';
						 $security_deposit = '0';
						 $i= 0 ;
					 	 foreach ($Cart_details as  $value) {
				 			//print_r($value);
				 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
				 			 $gst_amount  += $value['gst_amount'] ; 
				 			 $other_charges  += '0' ; 
				 			 $total_rent_amount  += $value['total_rent_amount'] ; 
				 			 $security_deposit +=   '0';
				 			 $app_user_id_array[] = $value['app_user_id'] ;
				 			 $Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image']; 
				 			 $i++;
				 		 }	

				 		if (!empty($app_user_id_array)) {	
				 			$app_user_id_array =  array_unique($app_user_id_array);
				 			//	$app_user_id_array =  explode(',', $app_user_id_array) ;
							$app_user_ids = '' ;
							foreach ($app_user_id_array as $app_user) {
								$app_user_ids.= "'". $app_user."',";
							}

							$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
							$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
					
				 		}
				 		$beta_discount = ($total_rent_amount_ex_gst*15)/100 ; 
				 		$insurance_fee = ($total_rent_amount_ex_gst*10)/100 ; 
				 		$community_fee = ($total_rent_amount_ex_gst*5)/100 ; 
				 		//$gst_amount = ($total_rent_amount_ex_gst*10)/100;
				 		$rent_summary = array(
				 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
				 								'gst_amount'=>$gst_amount,
				 								'beta_discount'=> $beta_discount,
				 								'insurance_fee'=> $insurance_fee,
				 								'community_fee'=> $community_fee,
				 								'other_charges'=>$other_charges,
				 								'sub_total'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee,
				 								'total_rent_amount'=>$total_rent_amount,
												'security_deposit' => $security_deposit,		 								
				 								'total_amount'=>  $gst_amount +$other_charges+$total_rent_amount+$community_fee+$insurance_fee -$beta_discount + $security_deposit 
				 							);
					 	$response['status'] = 200;
						$response['status_message'] = 'User cart List';
						$response['result'] = array('cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details,'insurance_type'=>$insurance_type );
						$json_response = json_encode($response);
						echo $json_response;	
					}
				}elseif($post_data['insurance_type'] == '5'){ // Security Deposi


					$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
				 		$Cart_details = $query1->row();
						 $query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
				 		 $rent_master_details = $query2->row();
				 		 $gaer_data = $this->home_model->geratDetails($post_data['user_gear_desc_id']);
						
					 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount ; 
					 	$Cart_details->insurance_amount =  $gaer_data->security_deposite_inc_gst; 
					 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 

					 	$Cart_details->insurance_tier_id =  '0'  ; 
					 	$Cart_details->other_charges =  '0' ; 
					 	$Cart_details->security_deposit =   $gaer_data->security_deposite_inc_gst ; 
				 		$Cart_details->deposite_status =  'MARK STORED' ;
				 		// echo "<pre>";
				 		// print_r($gaer_data->security_deposite_inc_gst);
					 	$insurance_type->amount = $gaer_data->security_deposite_inc_gst; ;
					 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
					 	// echo $this->db->last_query();die;
					 	if (!empty($rent_master_details)) {
					 		$rent_master_details->other_charges ='' ;
					 		$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
					 	}
					 	
						 $Cart_details = 	 $this->home_model->getUserCart($app_user_id);
							//echo  $this->db->last_query();
					if (count($Cart_details)) {
						// print_r($Cart_details);
						 $total_rent_amount_ex_gst = '0';
						 $gst_amount = '0';
						 $other_charges = '0';
						 $total_rent_amount = '0';
						 $security_deposit = '0';
						 $i= 0 ;
					 	 foreach ($Cart_details as  $value) {
				 			//print_r($value);
				 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
				 			 $gst_amount  += $value['gst_amount'] ; 
				 			 $other_charges  += '0' ; 
				 			 $total_rent_amount  += $value['total_rent_amount'] ; 
				 			 $security_deposit +=   '0';
				 			 $app_user_id_array[] = $value['app_user_id'] ;
				 			 $Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image']; 
				 			 $i++;
				 		 }	

				 		if (!empty($app_user_id_array)) {	
				 			$app_user_id_array =  array_unique($app_user_id_array);
				 			//	$app_user_id_array =  explode(',', $app_user_id_array) ;
							$app_user_ids = '' ;
							foreach ($app_user_id_array as $app_user) {
								$app_user_ids.= "'". $app_user."',";
							}

							$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
							$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
					
				 		}
				 		$beta_discount = ($total_rent_amount_ex_gst*15)/100 ; 
				 		$insurance_fee = ($total_rent_amount_ex_gst*10)/100 ; 
				 		$community_fee = ($total_rent_amount_ex_gst*5)/100 ; 
				 		//$gst_amount = ($total_rent_amount_ex_gst*10)/100;
				 		$rent_summary = array(
				 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
				 								'gst_amount'=>$gst_amount,
				 								'beta_discount'=> $beta_discount,
				 								'insurance_fee'=> $insurance_fee,
				 								'community_fee'=> $community_fee,
				 								'other_charges'=>$other_charges,
				 								'sub_total'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee,
				 								'total_rent_amount'=>$total_rent_amount,
												'security_deposit' => $security_deposit,		 								
				 								'total_amount'=>  $gst_amount +$other_charges+$total_rent_amount+$community_fee+$insurance_fee -$beta_discount + $security_deposit 
				 							);
					 	$response['status'] = 200;
						$response['status_message'] = 'User cart List';
						$response['result'] = array('cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details,'insurance_type'=>$insurance_type );
						$json_response = json_encode($response);
						echo $json_response;	
					}

				}elseif ($post_data['insurance_type'] == '2' ) {  //Replacement Value
						
						$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
				 		$Cart_details = $query1->row();
						 
						 $query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
				 		 $rent_master_details = $query2->row();
				 			
						$rent_master_details->other_charges =  $rent_master_details->total_rent_amount_ex_gst  ; 
					 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
					 	$Cart_details->insurance_amount = number_format((float)$rent_master_details->total_rent_amount_ex_gst , 2, '.', ''); 
					 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
					 	$Cart_details->insurance_tier_id =  '0'  ; 
					 	$Cart_details->other_charges =  '0' ; 
					 	$Cart_details->security_deposit =  '0' ; 
				 		$Cart_details->deposite_status =  'MARK STORED' ; 
					 	$insurance_type->amount =number_format((float)$rent_master_details->total_rent_amount_ex_gst , 2, '.', ''); ;
					 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
					 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
						 $Cart_details = 	 $this->home_model->getUserCart($app_user_id);
							//echo  $this->db->last_query();
					if (count($Cart_details)) {
						// print_r($Cart_details);
						 $total_rent_amount_ex_gst = '0';
						 $gst_amount = '0';
						 $other_charges = '0';
						 $total_rent_amount = '0';
						 $security_deposit = '0';
						 $community_fee = '0';
						 $beta_discount = '0';
						 $insurance_fee = '0';
						 $i= 0 ;
					 	 foreach ($Cart_details as  $value) {
				 			//print_r($value);
				 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
				 			 $gst_amount  += $value['gst_amount'] ; 
				 			 $other_charges  += '0' ; 
				 			 $total_rent_amount  += $value['total_rent_amount'] ; 
				 			 $security_deposit +=   '0';
				 			 $beta_discount +=  $value['beta_discount']  ; 
							 $insurance_fee +=  $value['insurance_fee']  ; 
							 $community_fee +=  $value['community_fee']  ; 
				 			 $app_user_id_array[] = $value['app_user_id'] ;
				 			 $Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image']; 
				 			 $i++;
				 		 }	

				 		if (!empty($app_user_id_array)) {	
				 			$app_user_id_array =  array_unique($app_user_id_array);
				 			//	$app_user_id_array =  explode(',', $app_user_id_array) ;
							$app_user_ids = '' ;
							foreach ($app_user_id_array as $app_user) {
								$app_user_ids.= "'". $app_user."',";
							}

							$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
							$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
					
				 		}
				 		
				 		//$gst_amount = ($total_rent_amount_ex_gst*10)/100;
				 		$rent_summary = array(
				 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
				 								'gst_amount'=>$gst_amount,
				 								'beta_discount'=> $beta_discount,
				 								'insurance_fee'=> $insurance_fee,
				 								'community_fee'=> $community_fee,
				 								'other_charges'=>$other_charges,
				 								'sub_total'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee,
				 								'total_rent_amount'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee +$gst_amount,
												'security_deposit' => $security_deposit,		 								
				 								'total_amount'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee +$gst_amount 
				 							);
					 	$response['status'] = 200;
						$response['status_message'] = 'User cart List';
						$response['result'] = array('cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details,'insurance_type'=>$insurance_type );
						$json_response = json_encode($response);
						echo $json_response;	
					}	
				}
				elseif( $post_data['insurance_type'] == '3'){  //USER INSURANCE 
					
					$where_clause = array(
											'app_user_id'=> $app_user_id ,
											'is_active' => 'Y',
											'is_approved'=> '1' ,
											'ks_user_certificate_currency_exp >' =>date('Y-m-d')
										);
					$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
					$insurance =  $query->row();
					if(!empty($insurance)){

							$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
							$insurance_type = $query->row();

						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
						 	$Cart_details = $query1->row();

						 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
						 	$rent_master_details = $query2->row();

				 
						 	$Cart_details->other_charges =   '0' ;
						 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges; 
						 	$rent_master_details->other_charges = '0' ;
						 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount +  $rent_master_details->other_charges; 
						 	$Cart_details->insurance_amount = '0'; 
						 	$Cart_details->insurance_fee = '0'; 
						 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
						 	$Cart_details->insurance_tier_id =  '0'  ; 
						 	$Cart_details->other_charges =  '0' ; 
						 	$Cart_details->security_deposit =  '0' ; 
					 		$insurance_type->amount ='0' ;
					 		$Cart_details->deposite_status =  'NONE' ; 
							$insurance_type->amount ='0' ;					 	
							
						 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
						 	// echo $this->db->last_query(); die;
						 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
					 	 	$Cart_details = 	 $this->home_model->getUserCart($app_user_id);
							if (count($Cart_details)) {
								// print_r($Cart_details);
								 $total_rent_amount_ex_gst = '0';
								 $gst_amount = '0';
								 $other_charges = '0';
								 $total_rent_amount = '0';
								 $security_deposit = '0';
								 $community_fee = '0';
								 $beta_discount = '0';
								 $insurance_fee = '0';
								 $i= 0 ;
							 	foreach ($Cart_details as  $value) {
							 			//print_r($value);
							 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
							 			 $gst_amount  += $value['gst_amount'] ; 
							 			 $other_charges  += $value['other_charges'] ; 
							 			 $total_rent_amount  += $value['total_rent_amount'] ; 
							 			 $security_deposit =   '0';
							 			 $beta_discount +=  $value['beta_discount']  ; 
									 	 $insurance_fee +=  $value['insurance_fee']  ; 
									 	 $community_fee +=  $value['community_fee']  ; 
							 			 $app_user_id_array[] = $value['app_user_id'] ;
							 			 $Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image']; 
							 			 $i++;
							 		}	

							 		if (!empty($app_user_id_array)) {	
							 			$app_user_id_array =  array_unique($app_user_id_array);
							 			//	$app_user_id_array =  explode(',', $app_user_id_array) ;
										$app_user_ids = '' ;
										foreach ($app_user_id_array as $app_user) {
											$app_user_ids.= "'". $app_user."',";
										}

											$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
											$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
								
							 		}
							 	
							 		//$gst_amount = ($total_rent_amount_ex_gst*10)/100;
							 		$rent_summary = array(
							 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
							 								'gst_amount'=>$gst_amount,
							 								'beta_discount'=> $beta_discount,
							 								'insurance_fee'=> $insurance_fee,
							 								'community_fee'=> $community_fee,
							 								'other_charges'=>'0',
							 								'sub_total'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee,
							 								'total_rent_amount'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee + $gst_amount,
															'security_deposit' => '0',		 								
							 								'total_amount'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee + $gst_amount   
							 							);
							 	$response['status'] = 200;
								$response['status_message'] = 'User cart List';
								$response['flag'] = '1';
								$response['result'] = array('cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details ,'insurance_type'=>$insurance_type);
								$json_response = json_encode($response);
								echo $json_response;
								exit;	
							}
						

					}else{
							$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
							$insurance_type = $query->row();

						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
						 	$Cart_details = $query1->row();

						 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
						 	$rent_master_details = $query2->row();

				 
						 	$Cart_details->other_charges =  '0' ;
						 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges; 
						 	
						 	$rent_master_details->other_charges = '0' ;
						 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount +  $rent_master_details->other_charges; 
						 	$Cart_details->insurance_amount = '0'; 
						 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
						 	$Cart_details->insurance_tier_id =  '0'  ; 
						 	$Cart_details->other_charges =  '0' ; 
						 	$Cart_details->security_deposit =  '0' ; 
				 			$Cart_details->deposite_status =  'NONE' ; 
						 	$insurance_type->amount = '0' ;
						 	$Cart_details->insurance_fee = '0'; 
						 	
						 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
						 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
					 	 	$Cart_details = 	 $this->home_model->getUserCart($app_user_id);
							if (count($Cart_details)) {
								// print_r($Cart_details);
								 $total_rent_amount_ex_gst = '0';
								 $gst_amount = '0';
								 $other_charges = '0';
								 $total_rent_amount = '0';
								 $security_deposit = '0';
								 $community_fee = '0';
								 $beta_discount = '0';
								 $insurance_fee = '0';
								 $i= 0 ;
							 	foreach ($Cart_details as  $value) {
							 			//print_r($value);
							 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
							 			 $gst_amount  += $value['gst_amount'] ; 
							 			 $other_charges  += '0' ; 
							 			 $total_rent_amount  += $value['total_rent_amount'] ; 
							 			 $security_deposit =   '0';
							 			 $beta_discount +=  $value['beta_discount']  ; 
									 	 $insurance_fee +=  $value['insurance_fee']  ; 
									 	 $community_fee +=  $value['community_fee']  ; 
							 			 $app_user_id_array[] = $value['app_user_id'] ;
							 			 $Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image']; 
							 			 $i++;
							 		}	

							 		if (!empty($app_user_id_array)) {	
							 			$app_user_id_array =  array_unique($app_user_id_array);
							 			//	$app_user_id_array =  explode(',', $app_user_id_array) ;
										$app_user_ids = '' ;
										foreach ($app_user_id_array as $app_user) {
											$app_user_ids.= "'". $app_user."',";
										}

											$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
											$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
								
							 		}
							 		
							 		//$gst_amount = ($total_rent_amount_ex_gst*10)/100;
							 		$rent_summary = array(
							 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
							 								'gst_amount'=>$gst_amount,
							 								'beta_discount'=> $beta_discount,
							 								'insurance_fee'=> $insurance_fee,
							 								'community_fee'=> $community_fee,
							 								'other_charges'=>$other_charges,
							 								'sub_total'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee,
							 								'total_rent_amount'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee + $gst_amount,
															'security_deposit' => $security_deposit,		 								
							 								'total_amount'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee + $gst_amount  
							 							);
							 	$response['status'] = 200;
								$response['status_message'] = 'User cart List';
								$response['flag'] = '0';
								// $insurance_type= array("description"=>'No insurance Available/Insurance Not approved  by the kitshare');
								$insurance_type= array("description"=>'Provide your own insurance policy');
								$response['result'] = array('cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details ,'insurance_type'=>$insurance_type);
								$json_response = json_encode($response);
								echo $json_response;
								exit;	
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

		// CHECK Austalian digital Post API  &  Hyperwallet
		public function CheckValidDetails()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				$query =   $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id ) );
				$user_details = $query->row();
				
				if($user_details->aus_post_verified == 'N'){

						$response['status'] = 400;
						$response['status_message'] = 'User is not verified  Digital Post API ';
						$json_response = json_encode($response);
						echo $json_response;
						exit;
				}else{
					$sql = "SELECT k_u.* FROM ks_users As k_u INNER JOIN  ks_user_gear_description AS g_d ON g_d.app_user_id = k_u.app_user_id  WHERE g_d.user_gear_desc_id =  '".$post_data['user_gear_desc_id']."'" ; 
					$gear_details =  $this->common_model->get_records_from_sql($sql);
					
					if(!empty($gear_details[0]) ){
						if ($gear_details[0]->aus_post_verified != 'Y' ) {
								$response['status'] = 400;
								$response['status_message'] = 'Owner is not verified  Digital Post API ';
								$json_response = json_encode($response);
								echo $json_response;
								exit;
						}elseif ($gear_details[0]->token_hyperwallet == '' &&  $gear_details[0]->token_card_hyperwallet == '') {
								$response['status'] = 400;
								$response['status_message'] = 'Owner is not verified  Hyperwallet Account ';
								$json_response = json_encode($response);
								echo $json_response;
								exit;
						}
						$response['status'] = 200;
						$response['status_message'] = 'Every Thing is valid can add to cart ';
						$json_response = json_encode($response);
						echo $json_response;
						exit;

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

		// GET CATEGORY AND SUB CATEGORY IN SINGLE API 
		public function CategoryListAPI()
		{
			$query =  $this->common_model->GetAllWhere('ks_gear_categories',  array('is_active' => 'Y' , 'gear_sub_category_id'=>'0' ));
			$category_data = $query->result_array();
			if (!empty($category_data)) {
				$i  = 0 ;
				foreach ($category_data as  $value) {

					$sql = "SELECT gear_category_id AS  gear_sub_category_id ,  gear_category_name AS gear_sub_category_name , gear_sub_category_id AS gear_parent_id  FROM ks_gear_categories  WHERE  gear_sub_category_id = '".$value['gear_category_id']."' AND is_active = 'Y' ";
					$query =  $this->common_model->get_records_from_sql($sql);
					$subcateory_data = $query;
					$j = 0 ;
					// if (!empty($subcateory_data)) {
					// 	# code...
					
					// 		foreach($subcateory_data as $row){
						
					// 		$result_arr[$j]['gear_sub_category_id']=$row['gear_category_id'];
					// 		$result_arr[$j]['gear_sub_category_name']=$row['gear_category_name'];

					// 		$result_arr[$j]['gear_parent_id']=$row['gear_sub_category_id'];
					// 		$result_arr[$j]['type'] = 'sub_category';

					// 		$j++;
					// 		}
					// }
					$category_data[$i]['type'] = 'category';
					$category_data[$i]['gear_sub_category'] = $subcateory_data;

					$i++;
				}
			}
			
				$response=array("status"=>200,
							"status_message"=>"success",
							"result"=>$category_data);
				echo json_encode($response);
				exit();
		}
	 	
		// Insurance Category Type
	 	public function InsuranceCategoryTypeList()
	 	{

	 		$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {

				
						$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active'))	;
						$category_array = $query->result_array();

						$query =  $this->common_model->GetAllWhere('ks_user_insurance_proof',array('app_user_id'=>$app_user_id ,'is_active' => 'Y','is_approved'=> '1' ,'ks_user_certificate_currency_exp >' =>date('Y-m-d')))	;
						$insurance_proof = $query->row();
						
						if (!empty($insurance_proof)) {
							$response=array("status"=>200,
									"status_message"=>"success",
									"result"=>$category_array);
							echo json_encode($response);
							exit();
						}else{
							foreach ($category_array as  $value) {
								if ($value['ks_insurance_category_type_id']  == '3') {
									
								}else{
									$category_array1 [] = $value ;
								}
							}

							$response=array("status"=>200,
									"status_message"=>"success",
									"result"=>$category_array1);
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

	 	public function checksecuritydeposite()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$category_name 			= urldecode($post_data['category_name']);
			//$category_name =  $this->input->post('category_name');
			$query = $this->common_model->GetAllWhere('ks_gear_categories',array('is_active'=>'Y', 'gear_category_name'=>$category_name));
			$gear_name = $query->row();
			if (empty($gear_name)) {
				$response=array("status"=>400,
								"status_message"=>"No gear found",
								"result"=>array());
					echo json_encode($response);
					exit();
			}else{
				 if ($gear_name->security_deposit == 'Y') {
					$security_deposit = "Yes" ;
				 }else{
				 	$security_deposit = "No" ;
				 }

				 $response=array("status"=>200,
								"status_message"=>"success",
								"result"=>$security_deposit);
					echo json_encode($response);
					exit();
			}	
		} 

		//Edit  Gear  Listing
		
		public function EditListing()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);

			if ($app_user_id != '') {
				if(count($post_data['address']) > 0 ){
					
					$address_ids =  implode(',',$post_data['address']);
				}else{
					
					$address_ids = '' ; 
				}
				//check manufacturers 
				$query =$this->common_model->GetAllWhere('ks_manufacturers',array('manufacturer_name' => $post_data['gear_brand_name']));
				$manufacturers_data = $query->row();
				if(!empty($manufacturers_data)){ // if present 
				 $manufacturer_id = $manufacturers_data->manufacturer_id ;
				}else{
					$app_user_id = '';
					$response['status'] = 400;
					$response['status_message'] = 'Manufacturer Does not exist ';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
				}
				//Check Gear  Category 
				$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => $post_data['gear_category_name']));
				$category_data = $query->row();
				if(!empty($category_data)){
					 $category_id = $category_data->gear_category_id ;
				}else{ // Add Parent Category
					$app_user_id = '';
					$response['status'] = 400;
					$response['status_message'] = 'Gear Category  Does not exist ';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
				}	

				//Check Gear  Sub Category 
				if(!empty($post_data['gear_sub_category_name'])){ // if not empty 
					$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => $post_data['gear_sub_category_name']));
					$category_data1 = $query->row();
				
					if(!empty($category_data1)){
						$sub_category_id = $category_data1->gear_category_id ;
					}else{ // Add Sub  Category
						$app_user_id = '';
						$response['status'] = 400;
						$response['status_message'] = 'Gear Sub Category  Does not exist ';
						$json_response = json_encode($response);
						echo $json_response;
						header('HTTP/1.1 401 Unauthorized');
						exit();
					}
					
				}else{
					$sub_category_id =  '0';
					
				}

				//Check for Gear  Modal 
				$query =$this->common_model->GetAllWhere('ks_models',array('model_name' => $post_data['gear_model_name']));
				$modal_data = $query->row();
				if(!empty($modal_data)){ // if modal exist
					 $model_id = $modal_data->model_id ;
				}else{
					$app_user_id = '';
					$response['status'] = 400;
					$response['status_message'] = 'Modal  Does not exist ';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
				}
				if($post_data['listing_option'] == 'not_listed'){
				 	$listed = 'N';
				}else{
					 $listed = 'Y';
				}
				if(!empty($post_data['extraItems'][0]) ){
				
				//$accessories1 =  implode(',',$post_data['extraItems']);
					foreach ($post_data['extraItems'] as  $value) {
						//print_r($value); 

					$query =	$this->common_model->GetAllWhere('ks_user_gear_description' , array('gear_name'=>$value ));
					$gear_details = $query->row();
					//print_r($gear_details->user_gear_desc_id);
					 $values[] =  $gear_details->user_gear_desc_id;
					}
					$accessories =  implode(',',$values);
					$row['accessories'] = $accessories ;


			}
				// price Calcuation 
			$query_sett=   $this->common_model->GetAllWhere('ks_settings', '');	
			$settings = $query_sett->row();

			if (array_key_exists("security_deposit_check",$post_data))
			{
			      $security_deposit_check = $post_data['security_deposit_check'] ;
			}else{
				  $security_deposit_check ='0';
			}

			$row['security_deposit_check'] =  $security_deposit_check;
			$row['per_weekend_cost_aud_ex_gst'] =  ($post_data['per_day_cost_aud_ex_gst']*3) ; 
			$row['per_week_cost_aud_ex_gst'] =  ($post_data['per_day_cost_aud_ex_gst']*3) ; 
			$row['per_weekend_cost_aud_inc_gst'] =  $row['per_weekend_cost_aud_ex_gst'] + ($row['per_weekend_cost_aud_ex_gst']*$settings->gst_percent)/100 ;
			$row['per_week_cost_aud_inc_gst'] =  $row['per_week_cost_aud_ex_gst'] + ($row['per_week_cost_aud_ex_gst']*$settings->gst_percent)/100 ;
			$row['gear_name'] =  $post_data['gearName'];
			$row['ks_manufacturers_id'] = $manufacturer_id ;
			$row['ks_category_id'] = $category_id ;
			$row['ks_sub_category_id'] = $sub_category_id ;
			$row['model_id'] = $model_id;
			$row['ks_gear_address_id'] = $address_ids ;
			$row['ks_user_gear_condition'] =  $post_data['gearCondition'];
			$row['serial_number'] = $post_data['serialNumber'];
			$row['gear_description_1'] = $post_data['model_description'];
			$row['security_deposite'] = $post_data['security_deposite'];
			if (empty($post_data['feature_master_id'][0]) ) {
				  $row['feature_master_id'] = implode(',', $post_data['feature_master_id']);
			}	
			//$row['gear_description_2'] = $post_data['additional_description'];
			$row['owners_remark'] = $post_data['additional_description']; 
			$row['per_day_cost_aud_ex_gst'] = $post_data['per_day_cost_aud_ex_gst'];
			$row['per_day_cost_aud_inc_gst'] = $post_data['per_day_cost_aud_ex_gst']+  ($post_data['per_day_cost_aud_ex_gst']*$settings->gst_percent)/100 ;
			$row['replacement_value_aud_inc_gst'] = $post_data['replacement_value_aud_inc_gst'];
			$row['replacement_value_aud_ex_gst'] = $post_data['replacement_value_aud_ex_gst'];	
			$row['gear_hide_search_results'] = $listed;
			
			$row['app_user_id'] = $app_user_id ;
			$row['gear_listing_date'] =date('Y-m-d');
			$row['is_active'] = 'Y';
			$row['create_user'] = $app_user_id ;
			$row['create_date'] = date('Y-m-d');
			$row['google_360_link'] = $post_data['image360Link'];	
			$row['ks_gear_type_id']=$post_data['gearTypeId'];
			$row['gear_type']= $post_data['gearTypeId'];;
			$this->common_model->UpdateRecord($row,"ks_user_gear_description","user_gear_desc_id",$post_data['user_gear_desc_id']);
			if(count($post_data['address']) > 0 ){
				$i = 0 ;
				foreach ($post_data['address'] AS  $value) {
					if ($i == 0) {
						$this->common_model->delete("ks_gear_location",$post_data['user_gear_desc_id'],"user_gear_desc_id");	
					}
					$address_insert =  array(
												'user_gear_desc_id' => $post_data['user_gear_desc_id'],
												'user_address_id'=> $value,
												'is_active' => 'Y',
												'create_date'=> date('Y-m-d'),
												'create_user'=>$app_user_id,

											 );
					$this->common_model->InsertData( 'ks_gear_location' ,$address_insert);
					$i++;
				}
					
			}else{
				
				$address_ids = '' ; 
			}

			if (isset($post_data['feature_details_id'])) {
				if (count($post_data['feature_details_id']) > 0 ) {

			 		for ($i=0; $i < count($post_data['feature_details_id'] ); $i++) { 
			 			if ($i == 0) {
			 				$this->common_model->delete("ks_user_gear_features",$post_data['user_gear_desc_id'],"user_gear_desc_id");	
			 			}
			 			$value_insert = array(
			 								'user_gear_desc_id'=>$post_data['user_gear_desc_id'] ,
			 								'feature_details_id	'=>$post_data['feature_details_id'][$i],
			 								'is_active'=>'Y',
			 								'create_user'=>$app_user_id,
			 								'create_date'=>date('Y-m-d')
			 							);
			 			$this->common_model->InsertData('ks_user_gear_features',$value_insert);
			 		}
				}	
			}

			if (isset($post_data['unAvailableDates'])) {
				if (count($post_data['unAvailableDates']) > 0 ) {
					$i=0 ;
					foreach ($post_data['unAvailableDates'] as  $value) { 
						if ($i== 0) {
							$this->common_model->delete("ks_gear_unavailable",$post_data['user_gear_desc_id'],"user_gear_description_id");	
						}
						$unavailble_insert = array(
												'user_gear_description_id'=>$post_data['user_gear_desc_id'],
												'unavailable_from_date'=>$value[0] , 
												'unavailable_to_date' =>$value[1] , 
												'create_user' => $app_user_id ,
												'create_date'=> date('Y-m-d')
											);

							$this->common_model->InsertData( 'ks_gear_unavailable' ,$unavailble_insert);
					
							$i++;
					}
				}
			}
				$response['status'] = 200 ;
				$response['status_message'] = 'Gear Updated sucessfully';
					$response['result'] = array(
												'model_id'=>$model_id,
												'user_gear_desc_id'=> $post_data['user_gear_desc_id']
											);
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
		
		// home page gear list random gear

		public function  homegears()
		{

			$where ='';
			$where .= "a.is_active='Y' ";
			$result = $this->home_model->getModelsList($where,'9');
			//$result = $this->home_model->getModelsList($where);
			//	$result =  $query->result();
			//$this->common_model->UpdateRecord(array('is_deleted'=>'1') , 'ks_user_gear_images' , 'user_gear_image_id', $user_gear_image_id);

						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Gear List';
						$response['result'] = $result;
						$json_response = json_encode($response);
						echo $json_response;
			
		}

		public function DeletGearImage($value='')
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				$user_gear_image_id = $post_data['user_gear_image_id'];
				$query =$this->common_model->GetAllWhere("ks_user_gear_images",array('user_gear_image_id'=>$user_gear_image_id));
				$image_data = $query->row();
				if (!empty($image_data)) {
						$this->common_model->UpdateRecord(array('is_deleted'=>'1') , 'ks_user_gear_images' , 'user_gear_image_id', $user_gear_image_id);

						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Image Deleted Successfully';
						$json_response = json_encode($response);
						echo $json_response;
				}else{
						$app_user_id = '';
						$response['status'] = 404;
						$response['status_message'] = 'Image does not exist';
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
		public function digitalIdVerfication()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				if ($post_data['status'] == 'Y') {
						$update_data = array(
												'aus_post_transcation_id'=>$post_data['transaction_id'],
												'aus_post_verified'=>'Y',
											);
						$this->common_model->UpdateRecord($update_data , 'ks_users' , 'app_user_id', $app_user_id);
						$response['status'] = 200;
						$response['status_message'] = 'DigitalIdVerfication Successfully done. ';
						$json_response = json_encode($response);
						echo $json_response;
				}else{
					$response['status'] = 400;
					$response['status_message'] = 'DigitalIdVerfication failed '.$post_data['message'];
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
		public function GearPayment()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				 $Cart_details = 	 $this->home_model->getUserCart($app_user_id);
				 $six_digit_random_number = mt_rand(100000, 999999);
				 $gear_order_id = 'Ks-'.$six_digit_random_number .time();
				 $insert_payment = array(
				 							'gear_order_id'=>$gear_order_id,
				 							'payment_mode_abbr'=>'Online', 
				 							'transaction_id'=>'', 
				 							'transaction_amount'=>$post_data['transaction_amount'], 
				 							'transaction_timestamp'=>time(), 
				 							'create_user'=>$app_user_id, 
				 							'create_date'=>date('Y-m-d'), 
				 							'app_user_id'=>$app_user_id, 
				 							'status'=>'STORED',
				 						);
				 $order_id = $this->common_model->InsertData('ks_user_gear_payments' ,$insert_payment);
				 $this->OwnerMail($app_user_id);
				 $this->RenterRequestMail($app_user_id);
				  foreach ($Cart_details as $value) {
				 	$update_cart  = array( 
				 							'is_payment_completed'=> 'Y',
				 							'is_rent_approved'=>'N',
				 							'order_status'=> '1',
				 							'order_status_date'=> date('Y-m-d')	,
				 							 'order_id'=> $gear_order_id,
				 							); 


				 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 	

				 }
				 // Mail for gear mail confirmation
				
				 // Mail for the gear  requested person 

				$response['status'] = 200;
				$response['status_message'] = 'Payment Made Successfully done';
				$response['result'] =$gear_order_id;
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
		public function GearPayment2()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			$Cart_details = 	 $this->home_model->getUserCart($app_user_id);
			if ($app_user_id != '') {
				$Cart_details = 	 $this->home_model->getUserCart($app_user_id);
				
				 $gear_order_id = $post_data['order_id'];
				 $insert_payment = array(
				 							'gear_order_id'=>$gear_order_id,
				 							'payment_mode_abbr'=>'Online',
				 							'payment_type'=>'Deposite Payment' ,
				 							'transaction_id'=>'', 
				 							'transaction_amount'=>$post_data['transaction_amount'], 
				 							'transaction_timestamp'=>time(), 
				 							'create_user'=>$app_user_id, 
				 							'create_date'=>date('Y-m-d'), 
				 							'status'=>'STORED',
				 							'app_user_id'=>$app_user_id, 
				 						);
				 $order_id = $this->common_model->InsertData('ks_user_gear_payments' ,$insert_payment); 
				 $update_cart  = array( 
				 							
				 							'deposite_status'=> 'STORED'
				 						
				 							);
				$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","order_id",$post_data['order_id']);
				$response['status'] = 200;
				$response['status_message'] = 'Payment Made Successfully done';
				$response['result'] =$gear_order_id;
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

		public function OwnerMail($app_user_id)
		{
			$Cart_details = 	 $this->home_model->getUserCart($app_user_id);
			$mail_content ='<!doctype html>
					<html>
					<head>
					<meta charset="utf-8">
					<title>kitshare</title>
					</head>

					<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">
					<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
						<table width="940" style="    margin: 0px auto;background-color:#095cab; padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
							<tr>
							<td><img src="'.BASE_URL.'/assets/images/logo.png"></td>
							</tr>
						</table>	
							<table class="table table-condensed" width="940" style="margin:25px auto;padding: 0px 10px;" cellpadding="0" cellspacing="0">
								<tr>
									<td style="font-size:20px; padding-bottom:10px;">Hi '. $Cart_details[0]['app_user_first_name'].' '.$Cart_details[0]['app_user_last_name'].'</td>
								</tr>
								<tr>
									<td style="font-size:18px; padding-bottom:10px;">Great news! You have a reservation request from  '.$Cart_details[0]['renter_firstname'].' '. $Cart_details[0]['renter_lastname'].'.</td>
								</tr>
								<tr>
									<td style="font-size:18px; padding-bottom:10px;">'.$Cart_details[0]['renter_firstname'].' '. $Cart_details[0]['renter_lastname'].' would like to rent the following between '. date('d-M-Y',strtotime($Cart_details[0]['gear_rent_request_from_date'])).' - '.date('d-M-Y',strtotime($Cart_details[0]['gear_rent_request_to_date'])).'</td>
								</tr>
							</table>
					<table class="table table-condensed" width="940" style="margin:25px auto;padding: 0px 10px;" cellpadding="0" cellspacing="0">
					  <thead>
					    <tr>
					      <td width="500" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Description </strong></td>
					      <td width="150" class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Replacement Value</strong></td>
					      <td width="150" class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Rented Days </strong></td>
					      <td width="150" class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Daily Rate</strong></td>
					      <td width="150"class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd; text-align:right"><strong>Amount AUD</strong></td>
					    </tr>
					  </thead>
					  <tbody>';
					 $str = '';
					$total_rent_amount_ex_gst = '0';
					$gst_amount = '0';
					$other_charges = '0';
					$total_rent_amount = '0';
					$total_rent_amount2 = '0';
					$security_deposit = '0';
					$beta_discount = '0' ; 
			 		$insurance_fee = '0';
			 		$community_fee = '0' ; 
					   foreach ($Cart_details as $line){
					    $str .= '<tr>
					      <td style="padding:5px 0px; border-bottom:1px solid #ddd">'.$line['gear_name'].'</td>
					      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd">'.number_format((float)$line['replacement_value_aud_inc_gst'], 2, '.', '').'</td>
					      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.$line['gear_total_rent_request_days'].'</td>
					      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.number_format((float)$line['per_day_cost_aud_ex_gst'], 2, '.', '').'</td>
					      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd;  text-align:right">'.number_format((float)$line['total_rent_amount_ex_gst'], 2, '.', '').'</td>
					    </tr>';
						$total_rent_amount_ex_gst  += $line['total_rent_amount_ex_gst'] ; 
						$gst_amount  += $line['gst_amount']; 
						$other_charges  += $line['other_charges'] ; 
						$total_rent_amount  += $line['total_rent_amount_ex_gst'] ; 
						$total_rent_amount2  += $line['total_rent_amount'] ; 
						// $total_rent_amount  += $value['total_rent_amount'] ; 
						$security_deposit +=   $line['security_deposit']; 
						$beta_discount += $line['beta_discount'] ; 
				 		$insurance_fee += $line['insurance_fee']; 
				 		$community_fee += $line['community_fee'] ; 

					   } 
					    
				$mail_content .=$str ; 	   
				$mail_content .='
					    <tr>
					      <td class="thick-line" colspan="4" style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Subtotal</strong></td>
					      <td class="thick-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right" colspan="4"  style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Beta Discount(15%)</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$beta_discount, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right "  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Insurance Fee</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$insurance_fee, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Community Fee(5%)</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$community_fee, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL ex GST</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL GST 10%</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$gst_amount, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"  style="border-top:2px solid #000; padding-top: 10px; font-size:20px; font-weight:bolder; text-align:right"><strong>TOTAL AUD</strong></td>
					      <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder; text-align:right">'.number_format((float)$total_rent_amount2, 2, '.', '').'</td>
					    </tr>
					  </tbody>
					</table>
					<table width="940" border="0" style="margin:0 auto;padding: 0px 10px;">
					  <tr>
					    <td style="font-size:18px;">
					      <p>Based on a price of $'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').' with the associated costs, your estimated payment for this booking is '.number_format((float)$total_rent_amount_ex_gst -$beta_discount , 2, '.', '').'</p>
					      </td>
					  </tr>
					  <tr>
					    <td>
					     <a href="'.WEB_URL.'"> <button  style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">ACCEPT</button> </a>
					     <a href="'.WEB_URL.'" ><button style="background-color:#ff0000; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">DECLINE</button></a>
					    </td>
					  </tr>
					</table>
					<table width="940" style="  margin: 0px auto; background-color:#ddd;padding: 5px 0px; text-align: center;" cellpadding="0" cellspacing="0">
						<tr>
						<td><p style="margin:0">&#169;2018 KitShare. All rights reserved</p></td>
						</tr>
					</table>
				</div>
				</body>
				</html>
					';
			
			$mail_body = $mail_content;
			
			$to= $Cart_details[0]['primary_email_address'];

			//$to= 'singhaniagourav@gmail.com';
			$subject = "A renter has made a request";		

			$mail_data = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"kitshare ",
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
		}
		public function  RenterRequestMail($app_user_id='')
		{
			$Cart_details = 	 $this->home_model->getUserCart($app_user_id);
			 //print_r($Cart_details);
			// die;
			$mail_content= '<!doctype html>
					<html>
					<head>
					<meta charset="utf-8">
					<title>kitshare</title>
					</head>

					<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">
					<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
					<table width="940" style="margin: 0px auto;background-color:#095cab; padding: 0px 10px;text-align: center;" cellpadding="0" cellspacing="0">
							<tr>
							<td><img src="'.BASE_URL.'/assets/images/logo.png"></td>
							</tr>
					</table>
					<table class="table table-condensed" width="940" style="margin:25px auto;padding: 0px 10px;" cellpadding="0" cellspacing="0">
					<tr>
					<td style="font-size:20px; padding-bottom:10px; text-align:center;">Request sent</td>
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px; text-align:center;">This is not a confirmed booking - at least not yet. You'."'".'ll get a response within 24 hours.</td>
					</tr>

					</table>
					<table width="940" style="margin:25px auto;padding: 0px 10px;" cellpadding="0" cellspacing="0">
					<tr>
					<td width="500"><h2>Collection</h2>
					<p>'.date('d-M-Y',strtotime($Cart_details[0]['gear_rent_request_from_date'])).'</p>
					</td>
					<td><h2>Return</h2>
					<p>'.date('d-M-Y',strtotime($Cart_details[0]['gear_rent_request_to_date'])).'</p>
					</td>
					</tr>
					</table>



					<table class="table table-condensed" width="940" style="margin:25px auto;" cellpadding="0" cellspacing="0">
					  <thead>
					    <tr>
					      <td width="500" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Description </strong></td>
					      <td width="150" class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Replacement Value </strong></td>
					      <td width="150" class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Rented Days</strong></td>
					      <td class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Daily Price</strong></td>
					      <td width="150"class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd; text-align:right"><strong>Amount AUD</strong></td>
					    </tr>
					  </thead>
					  <tbody>';
					   $str = '';
					$total_rent_amount_ex_gst = '0';
					$gst_amount = '0';
					$other_charges = '0';
					$total_rent_amount = '0';
					$total_rent_amount2 = '0';
					$security_deposit = '0';
					$beta_discount =  '0';
			 		$insurance_fee =  '0';
			 		$community_fee =  '0';
					   foreach ($Cart_details as $line){
					    $str .= '<tr>
					      <td style="padding:5px 0px; border-bottom:1px solid #ddd">'.$line['gear_name'].'</td>
					      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd">'.number_format((float)$line['replacement_value_aud_inc_gst'], 2, '.', '').'</td>
					      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.$line['gear_total_rent_request_days'].'</td>
					      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.number_format((float)$line['per_day_cost_aud_ex_gst'], 2, '.', '').'</td>
					      
					      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd;  text-align:right">'.number_format((float)$line['total_rent_amount_ex_gst'], 2, '.', '').'</td>
					    </tr>';
						$total_rent_amount_ex_gst  += $line['total_rent_amount_ex_gst'] ; 
						$gst_amount  += $line['gst_amount']; 
						$other_charges  += $line['other_charges'] ; 
						$total_rent_amount  += $line['total_rent_amount_ex_gst'] ; 
						$total_rent_amount2  += $line['total_rent_amount'] ; 
						// $total_rent_amount  += $value['total_rent_amount'] ; 
						$security_deposit +=   $line['security_deposit']; 
						$beta_discount +=  $line['beta_discount'];  ; 
				 		$insurance_fee +=  $line['insurance_fee'];  ; 
				 		$community_fee +=  $line['community_fee'];  ; 

					   } 
					    
				$mail_content .=$str ; 	   
				$mail_content .=' 
					    
					   <tr>
					      <td class="thick-line" colspan="4" style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Subtotal</strong></td>
					      <td class="thick-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right" colspan="4"  style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Beta Discount(15%)</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$beta_discount, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right "  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Insurance Fee</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$insurance_fee, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Community Fee(5%)</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$community_fee, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL ex GST</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL GST 10%</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$gst_amount, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"  style="border-top:2px solid #000; padding-top: 10px; font-size:20px; font-weight:bolder; text-align:right"><strong>TOTAL AUD</strong></td>
					      <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder; text-align:right">'.number_format((float)$total_rent_amount2, 2, '.', '').'</td>
					    </tr>
					  </tbody>
					</table>
					<table width="940" border="0" style="margin:0 auto;padding: 0px 10px;">
					  <tr>
					    <td style="font-size:18px;">
					      <p>Your card will be authorised 48hrs prior and charged when your booking is collected.</p>
					      </td>
					  </tr>
					  <tr>
					    <td>
					     <a href="'.base_url().'"><button type="submit" style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">View your Rentals Dashboard </button></a>
					     <a href="'.base_url().'"><button type="submit" style="background-color:#ff0000; color:#fff; padding:10px 12px; border:none; margin-bottom:20px"">Cancel request </button><a>
					      </td>
					  </tr>
					</table>
					<table width="940" style="  margin: 0px auto; background-color:#ddd;padding: 5px 0px; text-align: center;" cellpadding="0" cellspacing="0">
						<tr>
						<td><p style="margin:0">&#169;2018 KitShare. All rights reserved</p></td>
						</tr>
					</table>
					</div>
					</body>
					</html>
				';
			$mail_body = $mail_content;
			
			//$to= "singhaniagourav@gmail.com";
			 $to= $Cart_details[0]['renter_email'];
			$subject = "RENTER REQUESTS DETAILS";		
			$mail_data = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"kitshare ",
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
		}

		public function AcceptByOwnerGearRequest($order_id='')
		{

			$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
			$this->AcceptOwnerMail($cart_details);
			$this->AcceptRenterMail($cart_details);
			foreach ($cart_details as  $value) {
						
					$update_cart  = array( 
				 							'is_rent_approved'=>'Y',
				 							'is_rent_rejected'=>'N',
				 							'order_status'=> '2',
				 							'order_status_date'=> date('Y-m-d')	
				 							); 


				 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 		

				 }

						$date_order_pickup =  date('Y-m-d 2:00:00',strtotime($cart_details[0]['gear_rent_request_from_date'])) ;
						$date1 = new DateTime($date_order_pickup);
						$date2 = $date1->diff(new DateTime(date('Y-m-d H:i:m')));
						$total_hrs =  ($date2->d*24)+ $date2->h ; 
						if ($total_hrs < 48) {
								
							$query =  $this->common_model->GetAllWhere('ks_users', array('app_user_id'=>$cart_details[0]['create_user']));
							$users_details = $query->row();		
							if (version_compare(PHP_VERSION, '5.4.0', '<')) {
							    throw new Braintree_Exception('PHP version >= 5.4.0 required');
							}
							
							// Instantiate a Braintree Gateway either like this:
							$gateway = new Braintree_Gateway([
							    'environment' => 'sandbox',
							    'merchantId' => 'zmdpynpfyrtg74pn',
							    'publicKey' => '5mx3mprhdtxkw97z',
							    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
							]);

							$config = new Braintree_Configuration([
							    'environment' => 'sandbox',
							     'merchantId' => 'zmdpynpfyrtg74pn',
							    'publicKey' => '5mx3mprhdtxkw97z',
							    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
							]);
							$result = $gateway->paymentMethodNonce()->create($cart_details[0]['braintree_token']);
							$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$order_id,'payment_type'=>'Gear Payment'));
							$order_details = $query->row();
								$result = $gateway->transaction()->sale([
								    							'amount' =>number_format((float) $order_details->transaction_amount , 2, '.', '') ,
												    			'paymentMethodNonce' => $result->paymentMethodNonce->nonce
															]); 
										$update_Data = array(
														
														'transaction_id'=> $result->transaction->id,
														'status' => 'AUTHORISED'
													);
							$this->db->where('user_gear_payment_id', $order_details->user_gear_payment_id);
							$this->db->update('ks_user_gear_payments', $update_Data); 

							// Authorize the deposite payment
							$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$order_id,'payment_type'=>'Deposite Payment'));
							$order_details_deposite = $query->row();
							if (!empty($order_details_deposite)) {
							 	// print_r();die;
							 		$result = $gateway->paymentMethodNonce()->create($cart_details[0]['security_deposite_token_braintree']);
								$result = $gateway->transaction()->sale([
								    							'amount' =>number_format((float) $order_details_deposite->transaction_amount , 2, '.', '') ,
												    			'paymentMethodNonce' => $result->paymentMethodNonce->nonce
															]);
								$update_Data = array(
														'transaction_id'=> $result->transaction->id,
														'status' => 'AUTHORISED'
													);
								$this->db->where('user_gear_payment_id', $order_details_deposite->user_gear_payment_id);
								$this->db->update('ks_user_gear_payments', $update_Data); 
								foreach ($cart_details as  $value) {
									$update_cart  = array( 
								 							'deposite_status' => 'AUTHORISED'
								 							); 
								 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 		
								 }
							}
						}

						if ($total_hrs < 24) {
							 
								$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$order_id ,'payment_type'=>'Gear Payment' ,'status'=>'AUTHORISED' ));
								$order_payments = $query->row();
								if (!empty($order_payments)) {
									if ($order_payments->transaction_id  !='') {
										
										if (version_compare(PHP_VERSION, '5.4.0', '<')) {
										    throw new Braintree_Exception('PHP version >= 5.4.0 required');
										}
								
										// Instantiate a Braintree Gateway either like this:
										$gateway = new Braintree_Gateway([
										    'environment' => 'sandbox',
										    'merchantId' => 'zmdpynpfyrtg74pn',
										    'publicKey' => '5mx3mprhdtxkw97z',
										    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
										]);

										$config = new Braintree_Configuration([
										    'environment' => 'sandbox',
										     'merchantId' => 'zmdpynpfyrtg74pn',
										    'publicKey' => '5mx3mprhdtxkw97z',
										    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
										]);
										$result = $gateway->transaction()->submitForSettlement($order_payments->transaction_id,number_format((float) $order_payments->transaction_amount , 2, '.', ''));
										$update_Data = array(
															
															'status'=>'RECEIVED'
														);
										$this->db->where('user_gear_payment_id', $order_payments->user_gear_payment_id);
										$this->db->update('ks_user_gear_payments', $update_Data); 


										// foreach ($cart_details as  $value) {
									
										// 	$update_cart  = array( 
										//  							'status' => ''
										//  							); 


										//  	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 		

										//  }

									}
								}
						}
					
				 	$response['status'] = 200;
					$response['status_message'] = ' Owner Accepted Gear Successfully';
					$json_response = json_encode($response);
					echo $json_response;
			
		}
		public function AcceptRenterMail($data)
		{
			if (empty($data[0]['user_profile_picture_link'])) {
				$data[0]['user_profile_picture_link'] = BASE_URL."server/assets/images/profile.png"; 
			}
				
				// echo "<pre>";

				$this->db->select('u_a.*,ks_states.ks_state_name ,ks_suburbs.suburb_name
								   ');
				$this->db->from('ks_gear_location As g_l');
				$this->db->where('g_l.user_address_id',$data[0]['user_address_id']);
				$this->db->where('g_l.user_gear_desc_id',$data[0]['user_gear_desc_id']);
				$this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
				$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_a.ks_suburb_id " ,"INNER");
				$this->db->join('ks_states'," ks_states.ks_state_id = u_a.ks_state_id " ,"INNER");
				// $this->db->join('ks_user_gear_description AS a '," g_l.user_gear_desc_id = a.user_gear_desc_id " ,"INNER");
				$query = $this->db->get();
				$address  =$query->row();	
				// print_r($address);
				// print_r($data);die;
			$mail_content = '<!doctype html>
							<html>
							<head>
							<meta charset="utf-8">
							<title>kitshare</title>
							</head>

							<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">
							<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
							<table width="940" style="margin: 0px auto;background-color:#095cab; padding: 0px 10px;text-align: center;" cellpadding="0" cellspacing="0">
									<tr>
									<td><img src="'.BASE_URL.'/assets/images/logo.png"></td>
									</tr>
							</table>
							<table class="table table-condensed" width="940" style="margin:25px auto;padding: 0px 10px" cellpadding="0" cellspacing="0">
								<tr>
									<td style="font-size:20px; padding-bottom:10px;">Hi '.$data[0]['renter_firstname'].'  '.$data[0]['renter_lastname'].'</td>
								</tr>
								<tr>
									<td style="font-size:18px; padding-bottom:10px;">Great news! Your reservation request has been accepted by '.$data[0]['app_user_first_name'].'  '. $data[0]['app_user_last_name'].'.</td>
								</tr>
								<tr>
									<td>
									<img src="'.$data[0]['user_profile_picture_link'].'" width="70px" height="70px">
									<p style="font-size:18px; padding-bottom:10px; margin:0">'.$data[0]['app_user_first_name'].'  '. $data[0]['app_user_last_name'].'.</p>
									</td>
								</tr>
							</table>

							<table width="940" border="0" style="margin:0 auto;padding: 0px 10px">
							  <tbody><tr>
							    <td><h2 style="color:#095cab; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pr", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; border-bottom:2px solid #095cab; padding-bottom:10px; margin:0">OWNER ADDRESS</h2>
							    '; 

							   	if (!empty($address)) {
							   	 	$mail_content.=   '<p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">'.$address->suburb_name.'</p>

							      	<p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condense", Helvetica, Arial, sans-serif;">'.$address->ks_state_name.'</p></td>';
							   	 } else{
										 	$mail_content.=   '<p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">No Address Available</p>';		   	 	
							   	 }
							    
							    
							$mail_content.=  '</tr>
							</tbody></table>

							<table width="940" style="margin:25px auto;padding: 0px 10px" cellpadding="0" cellspacing="0">
								<tbody>
									<tr>
										<td width="500"><h2 style="margin:0">Amount</h2>';
										$total_rent_amount3 = '0';
										foreach ($data as $line){
											$total_rent_amount3 += $line['total_rent_amount'] ; 
										}
										$mail_content.=	'<p style="margin:0px">$'.$total_rent_amount3.'</p>
											<p style="margin:0"><a href="'.BASE_URL.'" style="text-decoration:none;">View Receipt </a></p>
										</td>	
										<td>
											<h2 style="margin:0">Reservation Code</h2>
											<p style="margin:0">'. $this->uri->segment(3).'</p>
										</td>
									</tr>
								</tbody>
							</table>

							<table class="table table-condensed" width="940" style="margin:25px auto;padding: 0px 10px" cellpadding="0" cellspacing="0">
							  <thead>
							    <tr>
							      <td width="400" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Description </strong></td>
							      <td width="150" class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Replacement Value</strong></td>
							      <td width="150" class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Rented days </strong></td>
							      <td width="150" class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Daily Price</strong></td>
							      <td width="150"class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd; text-align:right"><strong>Amount AUD</strong></td>
							    </tr>
							  </thead>
							  <tbody>';
							   $str = '';
					$total_rent_amount_ex_gst = '0';
					$gst_amount = '0';
					$other_charges = '0';
					$total_rent_amount = '0';
					$total_rent_amount2 = '0';
					$security_deposit = '0';
					$beta_discount =   '0'; 
					$insurance_fee =   '0';
					$community_fee =   '0';
					   foreach ($data as $line){
						//print_r($line['gear_name']);			    
					$str .= '<tr>
					      <td style="padding:5px 0px; border-bottom:1px solid #ddd">'.$line['gear_name'].'</td>
					      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd">'.number_format((float)$line['replacement_value_aud_inc_gst'], 2, '.', '').'</td>
					      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.$line['gear_total_rent_request_days'].'</td>
					      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.number_format((float)$line['total_rent_amount_ex_gst'], 2, '.', '').'</td>
					      
					      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd;  text-align:right">'.number_format((float)$line['total_rent_amount'], 2, '.', '').'</td>
					    </tr>';
						$total_rent_amount_ex_gst  += $line['total_rent_amount_ex_gst'] ; 
						$gst_amount  += $line['gst_amount']; 
						$other_charges  += $line['other_charges'] ; 
						$total_rent_amount  += $line['total_rent_amount_ex_gst'] ; 
						$total_rent_amount2  += $line['total_rent_amount'] ; 
						// $total_rent_amount  += $value['total_rent_amount'] ; 
						$security_deposit +=   $line['security_deposit'];
						$beta_discount +=   $line['beta_discount'];
						$insurance_fee +=   $line['insurance_fee'];
						$community_fee +=   $line['community_fee'];
					   } 
					    $total_rent_amount_ex_gst =  $total_rent_amount_ex_gst - $beta_discount +$insurance_fee + $community_fee; 
					$mail_content .=$str ; 	   
					$mail_content .=' <tr>
					      <td class="thick-line" colspan="4" style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Subtotal</strong></td>
					      <td class="thick-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right" colspan="4"  style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Beta Discount(15%)</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$beta_discount, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right "  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Insurance Fee</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$insurance_fee, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Community Fee(5%)</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$community_fee, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL ex GST</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL GST 10%</strong></td>
					      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$gst_amount, 2, '.', '').'</td>
					    </tr>
					    <tr>
					      <td class="no-line text-right"  colspan="4"  style="border-top:2px solid #000; padding-top: 10px; font-size:20px; font-weight:bolder; text-align:right"><strong>TOTAL AUD</strong></td>
					      <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder; text-align:right">'.number_format((float)$total_rent_amount2, 2, '.', '').'</td>
					    </tr>
					   					
							  </tbody>
							</table>
							<table width="940" border="0" style="margin:0 auto;padding: 0px 10px">
							  <tr>
							    <td style="font-size:18px;">
							      <p>You won&#39;t be charged until your booking is confirmed.</p>
							      <p>We understand that things can change so please let the '.$data[0]['app_user_first_name'].' know as soon as possible so they can make alternate arrangements.</p>
							      </td>
							  </tr>
							  <tr>
							    <td>
							    <a href="'.BASE_URL.'"><button type="submit" style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">View your Rentals Dashboard </button></a>
							    <a href="'.BASE_URL.'"><button type="submit" style="background-color:#ff0000; color:#fff; padding:10px 12px; border:none; margin-bottom:20px"">Change your Reservation</button></a>
							      </td>
							  </tr>
							</table>
							<table width="940" style="  margin: 0px auto; background-color:#ddd;padding: 5px 0px; text-align: center;" cellpadding="0" cellspacing="0">
								<tr>
								<td><p style="margin:0">&#169;2018 KitShare. All rights reserved</p></td>
								</tr>
							</table>
							</div>
							</body>
							</html>
						';
						//echo $mail_content;die;
			//$sender_mail= 'noreply@inferasolz.com';
			
			$mail_body = $mail_content;
			
			//	$to= "singhaniagourav@gmail.com";
			 $to= $data[0]['renter_email'];
			$subject = "RENTER REQUESTS ORDER ACCEPTED ";		
			$mail_data = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"kitshare ",
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

		}

		public function AcceptOwnerMail($data='')
		{
			if(empty($data[0]['renter_image'])){		
				$data[0]['renter_image'] = BASE_URL."server/assets/images/profile.png"; 		
			}
			$this->db->select('u_a.*,ks_states.ks_state_name ,ks_suburbs.suburb_name
								   ');
				$this->db->from('ks_gear_location As g_l');
				$this->db->where('g_l.user_address_id',$data[0]['user_address_id']);
				$this->db->where('g_l.user_gear_desc_id',$data[0]['user_gear_desc_id']);
				$this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
				$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_a.ks_suburb_id " ,"INNER");
				$this->db->join('ks_states'," ks_states.ks_state_id = u_a.ks_state_id " ,"INNER");
				// $this->db->join('ks_user_gear_description AS a '," g_l.user_gear_desc_id = a.user_gear_desc_id " ,"INNER");
				$query = $this->db->get();
				$address  =$query->row();	
			//print_r($data[0]['gear_rent_request_from_date'][gear_rent_request_to_date]);die;
			$mail_content = '<!doctype html>
								<html>
								<head>
								<meta charset="utf-8">
								<title>kitshare</title>
								</head>

								<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">
								<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
									<table width="940" style="margin: 0px auto;background-color:#095cab; padding: 0px 10px;text-align: center;" cellpadding="0" cellspacing="0">
											<tr>
											<td><img src="'.BASE_URL.'/assets/images/logo.png"></td>
											</tr>
									</table>
								<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0px 10px" cellpadding="0" cellspacing="0">
									<tr>
									<td style="font-size:20px; padding-bottom:10px;">Hi '.$data[0]['app_user_first_name'].'  '. $data[0]['app_user_last_name'].'</td>
									</tr>
									<tr>
									<td style="font-size:18px; padding-bottom:10px;">Congratulations! You have accepted the reservation request from  '.$data[0]['renter_firstname'].'  '. $data[0]['renter_lastname'].'.</td>
									</tr>
									<tr>
									<td>
									<img src="'.$data[0]['renter_image'].'" height="70px" width="70px">
									<p style="font-size:18px; padding-bottom:10px; margin:0">'.$data[0]['renter_firstname'].'  '.$data[0]['renter_lastname'].'.</p>
									</td>

									</tr>
								</table> <table width="940" border="0" style="margin:0 auto;padding: 0px 10px">
							  <tbody><tr>
							    <td>
							    '; 

							   	if (!empty($address)) {
							   	 	$mail_content.=   '<p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">'.$address->suburb_name.'/'. $address->ks_state_name.'</p>';

							      	
							   	 } else{
										 	$mail_content.=   '<p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">No Address Available</p></td>';		   	 	
							   	 }
							    
							    
							$mail_content.=  '</tr>
								</tbody></table>

								<table width="940" style="margin:25px auto; padding: 0px 10px" cellpadding="0" cellspacing="0">
								<tbody><tr>
								<td width="500"><h2 style="margin:0">Pick Up 14:00	</h2>
								<p style="margin:0px">'.date('d-M-Y',strtotime($data[0]['gear_rent_request_from_date'])).'</p>

								</td>
								<td><h2 style="margin:0">Drop Off 12:00</h2>
								<p style="margin:0">'.date('d-M-Y',strtotime($data[0]['gear_rent_request_to_date'])).'</p>
								</td>
								</tr>
								</tbody></table>

								<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0px 10px" cellpadding="0" cellspacing="0">
								  <thead>
								    <tr>
								      <td width="400" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Description </strong></td>
								      <td width="150" class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Replacement Value</strong></td>
								      <td width="150" class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Rented days </strong></td>
								      <td width="150" class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Daily Price</strong></td>
								      <td width="150"class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd; text-align:right"><strong>Amount AUD</strong></td>
								    </tr>
								  </thead>
								  <tbody>';
								    
								    
							   $str = '';
					$total_rent_amount_ex_gst = '0';
					$gst_amount = '0';
					$other_charges = '0';
					$total_rent_amount = '0';
					$total_rent_amount2 = '0';
					$security_deposit = '0';
					$beta_discount =   '0';		
					$insurance_fee =   '0'; 		
					$community_fee =   '0';
					   foreach ($data as $line){
						//print_r($line['gear_name']);			    
							$str .= '<tr>
					      <td style="padding:5px 0px; border-bottom:1px solid #ddd">'.$line['gear_name'].'</td>
					      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd">'.number_format((float)$line['replacement_value_aud_inc_gst'], 2, '.', '').'</td>
					      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.$line['gear_total_rent_request_days'].'</td>
					      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.number_format((float)$line['total_rent_amount_ex_gst'], 2, '.', '').'</td>
					      
					      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd;  text-align:right">'.number_format((float)$line['total_rent_amount'], 2, '.', '').'</td>
					    </tr>';
						$total_rent_amount_ex_gst  += $line['total_rent_amount_ex_gst'] ;
						$gst_amount  += $line['gst_amount'];
						$other_charges  += $line['other_charges'] ;
						$total_rent_amount  += $line['total_rent_amount_ex_gst'] ;
						$total_rent_amount2  += $line['total_rent_amount'] ;
						// $total_rent_amount  += $value['total_rent_amount'] ; 
						$security_deposit +=   $line['security_deposit'];
						$beta_discount +=   $line['beta_discount'];
						$insurance_fee +=   $line['insurance_fee'];
						$community_fee +=   $line['community_fee'];
					   } 
					 	$total_rent_amount_ex_gst = $total_rent_amount_ex_gst - $beta_discount +$insurance_fee + $community_fee;
						$mail_content .=$str ; 	   
						$mail_content .=' <tr>
							      <td class="thick-line" colspan="4" style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Subtotal</strong></td>
							      <td class="thick-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
							    </tr>
							    <tr>
							      <td class="no-line text-right" colspan="4"  style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Beta Discount(15%)</strong></td>
							      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$beta_discount, 2, '.', '').'</td>
							    </tr>
							    <tr>
							      <td class="no-line text-right "  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Insurance Fee</strong></td>
							      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$insurance_fee, 2, '.', '').'</td>
							    </tr>
							    <tr>
							      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Community Fee(5%)</strong></td>
							      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$community_fee, 2, '.', '').'</td>
							    </tr>
							    <tr>
							      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL ex GST</strong></td>
							      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
							    </tr>
							    <tr>
							      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL GST 10%</strong></td>
							      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$gst_amount, 2, '.', '').'</td>
							    </tr>
							    <tr>
							      <td class="no-line text-right"  colspan="4"  style="border-top:2px solid #000; padding-top: 10px; font-size:20px; font-weight:bolder; text-align:right"><strong>TOTAL AUD</strong></td>
							      <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder; text-align:right">'.number_format((float)$total_rent_amount2, 2, '.', '').'</td>
							    </tr>
					   				
								  </tbody>
								</table>
								<table width="940" style="margin:25px auto; padding: 0px 10px" cellpadding="0" cellspacing="0">
								<tbody><tr>
								<td width="500"><h2 style="margin:0">Amount</h2>
								<p style="margin:0px">'.number_format((float)$total_rent_amount2, 2, '.', '').'</p>

								</td>
								<td><h2 style="margin:0">Reservation Code</h2>
								<p style="margin:0">'.$this->uri->segment(3).'</p>
								</td>
								</tr>
								</tbody></table>


								<table width="940" border="0" style="margin:0 auto; padding: 0px 10px">
								  <tr>
								    <td style="font-size:18px;">
								      <p>We highly discourage Owners from cancelling  - you should never accept a rental unless you are certain you can fulfill it. However, if you are an owner and need to cancel because of an unforeseen event, email us at <a href="#" style="text-decoration:none">hello@kitshare.com.au.</a> When you cancel a rental, you’ll get an automated review on your profile saying that you cancelled. After the first time you cancel, there will be a $50 penalty fee per cancellation. Any applicable cancellation fees are automatically deducted from your next rental payout.</p>
								      
								      </td>
								  </tr>
								  <tr>
								    <td>
								     <a href="'.BASE_URL.'"><button type="submit" style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">View Your Rentals Dashboard </button></a>
								     <a href="'.BASE_URL.'"><button type="submit" style="background-color:#ff0000; color:#fff; padding:10px 12px; border:none; margin-bottom:20px"">Change Your Reservation</button></a>
								      </td>
								  </tr>
								</table>
								<table width="940" style="  margin: 0px auto; background-color:#ddd;padding: 5px 0px; text-align: center;" cellpadding="0" cellspacing="0">
									<tr>
									<td><p style="margin:0">&#169;2018 KitShare. All rights reserved</p></td>
									</tr>
								</table>
								</div>
								</body>
								</html>
								';	
				
			$mail_body = $mail_content;
			//$to= "singhaniagourav@gmail.com";
			$to= $data[0]['primary_email_address'];
			$subject = "RENTER REQUESTS ORDER ACCEPTED ";
			$mail_data = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"kitshare ",
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
		}
		public function RejectByOwnerGearRequest($order_id='')
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {	

				$query = $this->common_model->GetAllWhere('ks_user_gear_rent_details',array('order_id'=>$order_id));
				$cart_details	= $query->result();

				$this->RenterRejectmailOrder($order_id);
				foreach ($cart_details as  $value) {
						$update_cart  = array( 
					 							'is_rent_approved'=>'N',
					 							'is_rent_rejected' => 'Y',
					 							'rent_approved_rejected_on'=> date('Y-m-d'),
					 							'order_status'=>'6',
					 							'order_status_date'=>date('Y-m-d')

					 							); 


					 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value->user_gear_rent_detail_id);						 	
					 	$query = $this->common_model->GetAllWhere('ks_user_gear_rent_details',array('order_id'=>$order_id));
				
					 }
						$response['status'] = 200;
						$response['status_message'] = ' Owner Rejected Gear Successfully';
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
		public function RenterRejectmailOrder($order_id='')
		{
			$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
			// echo $this->db->last_query();
			// print_r($cart_details);die;
			if (empty($cart_details)) {
					$response['status'] = 200;
					$response['status_message'] = ' Order is present ';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
			}
			$msg= '<!doctype html>
					<html>
					<head>
					<meta charset="utf-8">
					<title>kitshare</title>
					</head>

					<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">

					<div class="wrapper" style="border:0px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">

					</div>


					<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
					<table width="940" style="    margin: 0px auto;
					    background-color:#095cab;
					    padding: 10px 0px;
					    text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
					</tr>
					</table>
					<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
					<td style="font-size:20px; padding-bottom:10px;">
					Hi '.$cart_details[0]['renter_firstname'].'  '.$cart_details[0]['renter_lastname'].' , </td>
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">We’re sorry to say that  '.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].' declined your reservation request.</td>
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">Owners may decline for many reasons, including conflicting requests on your shoot dates and not having enough time between reservations.</td>

					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">We know that this particular reservation didn’t work out, but there are plenty more listing and we encourage you to keeping searching. We’ve gone ahead and removed the temporary authorization on your payment method so you can make a new booking.</td>

					</tr>
					<td style="font-size:18px; padding-bottom:10px;">Thanks,
					<p>The Kitshare Team</p>

					</td>

					</tr>

					</table>

					<table width="940" style="    margin: 0px auto;
					    background-color:#ddd;
					    padding: 5px 0px;
					    text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><p style="margin:0">&#169;2018 KitShare. All rights reserved</p></td>
					</tr>
					</table>
					</div>

					</body>
					</html>
					';

			$mail_body = $msg;
			//$to= "snigdha0706@yahoo.co.in";
			$to= $cart_details[0]['renter_email'];
			$subject = "RENTER HAS DECLINED RENTAL";		
			$mail_data = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"kitshare ",
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
			// $response['status'] = 200;
			// $response['status_message'] = ' Order is Rejected sucessfully';
			// $json_response = json_encode($response);
			// echo $json_response;
			// exit;
		}
		public function CancelByRenterOrder($order_id='')
		{
			$query = $this->common_model->GetAllWhere('ks_user_gear_rent_details',array('order_id'=>$order_id));
			$cart_details	= $query->result();
			 $this->SendOwnerCancelMail($order_id);
			 $this->SendRenterCancelMail($order_id);
			foreach ($cart_details as  $value) {
					$update_cart  = array( 
				 							'is_rent_cancelled'=>'Y',
				 							'order_status'=>'5',
				 							'order_status_date'=>date('Y-m-d'),
				 							'rent_request_cancelled_by'=>$value->create_user
				 							); 


				 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value->user_gear_rent_detail_id);						 	
				 	$response['status'] = 200;
					$response['status_message'] = ' Order is Cancelled Successfully ';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
			}
		}

		public function SendOwnerCancelMail($order_id)
		{

			
			$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
			
			$msg= '<!doctype html>
					<html>
					<head>
					<meta charset="utf-8">
					<title>kitshare</title>
					</head>

					<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">

					<div class="wrapper" style="border:0px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">

					</div>


					<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
					<table width="940" style="    margin: 0px auto;
					    background-color:#095cab;
					    padding: 10px 0px;
					    text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
					</tr>
					</table>
					<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
					<td style="font-size:20px; padding-bottom:10px;">
					Hi'.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].' , </td>
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">We regret to inform you that your '.$cart_details[0]['renter_firstname'].'  '.$cart_details[0]['renter_lastname'].'  cancelled reservation '. $order_id.' starting on '.   date('d-m-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).'. Per our cancellation policy, you have the option to request a cancellation fee of up to 50% of the Rental value if cancelled within 48hrs or 100% of the rental Rental value if cancelled within 24hrs of the Pickup time.</td>

					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">For more information on cancellation, please see our <a href="'.WEB_URL.'/faq" target="_blank" >FAQ</a> .</td>
					
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">Your calender has also been updated to show that the previously booked dates are now available.</td>

					</tr>
					<td style="font-size:18px; padding-bottom:10px;"><h2>Regards,</h2>
					<p>The Kitshare Team</p>

					</td>

					</tr>

					</table>

					<table width="940" style="    margin: 0px auto;
					    background-color:#ddd;
					    padding: 5px 0px;
					    text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><p style="margin:0">&#169;2018 KitShare. All rights reserved</p></td>
					</tr>
					</table>
					</div>

					</body>
					</html>
					';

					$mail_body = $msg;
			//print_r($mail_body);die;

			$to= $cart_details[0]['primary_email_address'];
			//$to= 'singhaniagourav@gmail.com';
			$subject = "RENTER HAS CANCELLED RENTAL";		
			$mail_data = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"kitshare ",
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
		}

		public function SendRenterCancelMail($order_id)
		{
			$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
			// echo "<pre>";
			// print_r($cart_details);
			$msg= '<!doctype html>
					<html>
					<head>
					<meta charset="utf-8">
					<title>kitshare</title>
					</head>

					<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">

					<div class="wrapper" style="border:0px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">

					</div>


					<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
					<table width="940" style="    margin: 0px auto;
					    background-color:#095cab;
					    padding: 10px 0px;
					    text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
					</tr>
					</table>
					<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
					<td style="font-size:20px; padding-bottom:10px;">
					Hi,'.$cart_details[0]['renter_firstname'].'  '.$cart_details[0]['renter_lastname'].' </td>
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">We regret to inform you that you has cancelled reservation '.$order_id.' starting on '. date('d-m-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).'. </td>
					</tr>
					
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">We’ve cancelled your reservation request and you will not be charged for it. To make sure you still have a great shoot, let’s find you new kit in REQUESTED SUBURB. </td>
					
					</tr>
					
					<td style="font-size:18px; padding-bottom:10px;"><h2>Regards,</h2>
					<p>The Kitshare Team</p>

					</td>

					</tr>

					</table>

					<table width="940" style="    margin: 0px auto;
					    background-color:#ddd;
					    padding: 5px 0px;
					    text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><p style="margin:0">&#169;2018 KitShare. All rights reserved</p></td>
					</tr>
					</table>
					</div>

					</body>
					</html>
					';

					$mail_body = $msg;
			// print_r($mail_body);die;
			$to= $cart_details[0]['renter_email'];				
			// $to= 'singhaniagourav@gmail.com';
			$subject = "RENTER HAS CANCELLED RENTAL";		
			$mail_data = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"kitshare ",
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
		}

		public function OwnerOrderSummary()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {	
				$Cart_details = $this->home_model->OwnerOrderSummary1($post_data['order_id']);
				if (!empty($Cart_details)) {
				
					$total_rent_amount_ex_gst = '0';
					$gst_amount = '0';
					$other_charges = '0';
					$total_rent_amount = '0';
					$security_deposit = '0';
					$beta_discount = '0'; 
			 		$insurance_fee = '0' ; 
			 		$community_fee = '0' ; 
					 $i= 0 ;
				 	foreach ($Cart_details as  $value) {
				 			//print_r($value);
				 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
				 			 $gst_amount  += $value['gst_amount'] ; 
				 			 $other_charges  += $value['other_charges'] ; 
				 			 $total_rent_amount  += $value['total_rent_amount_ex_gst'] ; 
				 			// $total_rent_amount  += $value['total_rent_amount'] ; 
				 			 $security_deposit +=   $value['security_deposit'];
				 			 $beta_discount += $value['beta_discount'] ; 
					 		 $insurance_fee += $value['insurance_fee']; 
					 		 $community_fee += $value['community_fee'] ; 
				 			 $app_user_id_array[] = $value['create_user'] ;
				 			 if ($value['gear_display_image'] == '') {
				 			 	 $Cart_details[$i]['gear_display_image']   = '';
				 			 }else{
				 			 $Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image']; 
				 			}
				 			if ($value['order_status'] == '2') {
				 				$Cart_details[$i]['order_status']  = 'Reservation';
				 			}elseif($value['order_status'] == '3'){
				 				$Cart_details[$i]['order_status']  = 'Contract';
				 			}elseif($value['order_status'] == '4'){
				 				$Cart_details[$i]['order_status']  = 'Completed';
				 			}elseif($value['order_status'] == '5'){
				 				$Cart_details[$i]['order_status']  = 'Cancelled';
				 			}elseif($value['order_status'] == '6'){
				 				$Cart_details[$i]['order_status']  = 'Rejected';
				 			}elseif($value['order_status'] == '7'){
				 				$Cart_details[$i]['order_status']  = 'Archived';
				 			}elseif($value['order_status'] == '8'){
				 				$Cart_details[$i]['order_status']  = 'Expired';
				 			}else{
				 				$Cart_details[$i]['order_status']  = 'Quote';
				 			}
				 			$date_from = strtotime(date('d-m-Y',strtotime($value['gear_rent_start_date'])));
							$date_to = strtotime(date('d-m-Y',strtotime($value['gear_rent_end_date'])));

							$diff = abs($date_to - $date_from);
							$date_array =  $this->getDateList($date_from,$date_to);
							$insurance_days = count($date_array);
						 	array_pop($date_array); 
						 	array_shift($date_array);
							$shoot_days = count($date_array);
							if(empty($shoot_days)){
								$shoot_days = 1;
							}	


							$this->db->select('u_a.*,ks_states.ks_state_name ,ks_suburbs.suburb_name');
							$this->db->from('ks_user_address As u_a');
							$this->db->where('u_a.user_address_id',$value['user_address_id']);
							$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_a.ks_suburb_id " ,"INNER");
							$this->db->join('ks_states'," ks_states.ks_state_id = u_a.ks_state_id " ,"INNER");
							$query = $this->db->get();
							$address  =$query->result_array();	
							
							$Cart_details[$i]['address']  = $address;
							$Cart_details[$i]['shoot_days'] = $shoot_days;

				 			 $i++;
				 		}
					if (!empty($app_user_id_array)) {	
				 			$app_user_id_array =  array_unique($app_user_id_array);
				 		//	$app_user_id_array =  explode(',', $app_user_id_array) ;
							$app_user_ids = '' ;
							foreach ($app_user_id_array as $app_user) {
								$app_user_ids.= "'". $app_user."',";
							}

								$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link,primary_email_address,primary_mobile_number FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
						$app_users_details = $this->common_model->get_records_from_sql($sql1) ;
							if (!empty($app_users_details)) {

								if ($app_users_details[0]->user_profile_picture_link =='') {
									$app_users_details[0]->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
								}
							}
				 		}
				 		
				 		//$gst_amount = ($total_rent_amount_ex_gst*10)/100;
				 		$rent_summary = array(
				 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee,
				 								'gst_amount'=>$gst_amount,
				 								'beta_discount'=> $beta_discount,
				 								'insurance_fee'=> $insurance_fee,
				 								'community_fee'=> $community_fee,
				 								'other_charges'=>$other_charges,
				 								'sub_total'=>$total_rent_amount_ex_gst,
				 								'total_rent_amount'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee+$gst_amount,
												'security_deposit' => $security_deposit,		 								
				 								'total_amount'=>   $gst_amount+$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee 
				 							);	

				 	
					$data['Cart_details'] =  $Cart_details;
					$data['rent_summary'] = $rent_summary;	
					$data['app_users_details'] = $app_users_details;
					$data['order_status'] = $Cart_details[0]['order_status'];
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'Order Summary Details';
					$response['result'] =$data ;
					$json_response = json_encode($response);
					echo $json_response;							 
					
				}else{
					$response['status'] = 401;
					$response['status_message'] = ' No Order Summary found';
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

		public function RentalsOrderSummary()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {	
				$Cart_details = $this->home_model->OwnerOrderSummary2($post_data['order_id']);

				if (!empty($Cart_details)) {
				
					$total_rent_amount_ex_gst = '0';
					 $gst_amount = '0';
					 $other_charges = '0';
					 $total_rent_amount = '0';
					 $security_deposit = '0';
					 $beta_discount ='0';
			 		 $insurance_fee ='0';
			 		 $community_fee ='0';
					 $i= 0 ;
				 	foreach ($Cart_details as  $value) {
				 			//print_r($value);
				 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
				 			 $gst_amount  += $value['gst_amount'] ; 
				 			 $other_charges  += $value['other_charges'] ; 
				 			 $total_rent_amount  += $value['total_rent_amount_ex_gst'] ; 
							 $beta_discount += $value['beta_discount'];
					 		 $insurance_fee += $value['insurance_fee'];
					 		 $community_fee +=  $value['community_fee'];
				 			// $total_rent_amount  += $value['total_rent_amount'] ; 
				 			 $security_deposit +=   $value['security_deposit'];
				 			 $app_user_id_array[] = $value['app_user_id'] ;
				 			 if ($value['gear_display_image'] == '') {
				 			 	 $Cart_details[$i]['gear_display_image']   = '';
				 			 }else{
				 			 $Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image']; 
				 			}

				 			if ($value['order_status'] == '2') {
				 				$Cart_details[$i]['order_status']  = 'Reservation';
				 			}elseif($value['order_status'] == '3'){
				 				$Cart_details[$i]['order_status']  = 'Contract';
				 			}elseif($value['order_status'] == '4'){
				 				$Cart_details[$i]['order_status']  = 'Completed';
				 			}elseif($value['order_status'] == '5'){
				 				$Cart_details[$i]['order_status']  = 'Cancelled';
				 			}elseif($value['order_status'] == '6'){
				 				$Cart_details[$i]['order_status']  = 'Rejected';
				 			}elseif($value['order_status'] == '7'){
				 				$Cart_details[$i]['order_status']  = 'Archived';
				 			}elseif($value['order_status'] == '8'){
				 				$Cart_details[$i]['order_status']  = 'Expired';
				 			}else{
				 				$Cart_details[$i]['order_status']  = 'Quote';
				 			}

				 			$date_from = strtotime(date('d-m-Y',strtotime($value['gear_rent_start_date'])));
							$date_to = strtotime(date('d-m-Y',strtotime($value['gear_rent_end_date'])));

							$diff = abs($date_to - $date_from);
							$date_array =  $this->getDateList($date_from,$date_to);
							$insurance_days = count($date_array);
						 	array_pop($date_array); 
						 	array_shift($date_array);
							$shoot_days = count($date_array);
							if(empty($shoot_days)){
								$shoot_days = 1;
							}

								$this->db->select('u_a.*,ks_states.ks_state_name ,ks_suburbs.suburb_name');
							$this->db->from('ks_user_address As u_a');
							$this->db->where('u_a.user_address_id',$value['user_address_id']);
							$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_a.ks_suburb_id " ,"INNER");
							$this->db->join('ks_states'," ks_states.ks_state_id = u_a.ks_state_id " ,"INNER");
							$query = $this->db->get();
							$address  =$query->result_array();	
							
							$Cart_details[$i]['address']  = $address;
							$Cart_details[$i]['shoot_days'] = $shoot_days;

				 			 $i++;
				 		}
					if (!empty($app_user_id_array)) {	
				 			$app_user_id_array =  array_unique($app_user_id_array);
				 		//	$app_user_id_array =  explode(',', $app_user_id_array) ;
							$app_user_ids = '' ;
							foreach ($app_user_id_array as $app_user) {
								$app_user_ids.= "'". $app_user."',";
							}

								$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link,primary_email_address,primary_mobile_number FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
						$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
							if (!empty($app_users_details)) {

								if ($app_users_details[0]->user_profile_picture_link =='') {
									$app_users_details[0]->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
								}
							}
				 		}
				 		
				 		//$gst_amount = ($total_rent_amount_ex_gst*10)/100;
				 		$rent_summary = array(
				 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee,
				 								'gst_amount'=>$gst_amount,
				 								'beta_discount'=> $beta_discount,
				 								'insurance_fee'=> $insurance_fee,
				 								'community_fee'=> $community_fee,
				 								'other_charges'=>$other_charges,
				 								'sub_total'=> $total_rent_amount_ex_gst,
				 								'total_rent_amount'=>$total_rent_amount_ex_gst -$beta_discount + $community_fee +$insurance_fee + $gst_amount,
												'security_deposit' => $security_deposit,		 								
				 								'total_amount'=>    $total_rent_amount_ex_gst -$beta_discount + $community_fee +$insurance_fee + $gst_amount 
				 							);	

				 	
					$data['Cart_details'] =  $Cart_details;
					$data['rent_summary'] = $rent_summary;	
					$data['app_users_details'] = $app_users_details;
					$data['order_status'] = $Cart_details[0]['order_status'];
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'Order Summary Details';
					$response['result'] =$data ;
					$json_response = json_encode($response);
					echo $json_response;							 
					
				}else{
					$response['status'] = 401;
					$response['status_message'] = ' No Order Summary found';
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
		private function set_upload_options1()
		{   
		    //upload an image options
		    $config = array();
		    $config['upload_path'] = BASE_IMG.'site_upload/checklist/';;
		    $config['allowed_types'] = 'gif|jpg|png|jpeg|svg';
		    $config['max_size']      = '0';
		    $config['overwrite']     = FALSE;

		    return $config;
		}
		public function OwnerCheckListUpload()
		{
			$post_data =  $this->input->post();
			$token = $post_data['token'];
			if (empty($_FILES)) {
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'Select A Image first ';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
			}
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				$query = $this->common_model->GetAllWhere('ks_user_gear_rent_details',array('order_id'=>$post_data['order_id']));
				$order_details = $query->row();
				if (!empty($order_details)) {
					$files = $_FILES;
					$original_image_name =  explode('.',$files['image']['name']);
					$_FILES['image']['name']= $files['image']['name'];
					$_FILES['image']['type']= $files['image']['type'];
					$_FILES['image']['tmp_name']= $files['image']['tmp_name'];
					$_FILES['image']['error']= $files['image']['error'];
					$_FILES['image']['size']= $files['image']['size'];    
					$this->upload->initialize($this->set_upload_options1());
					$this->upload->do_upload('image');
					$dataInfo = $this->upload->data();
					$error = array('error' => $this->upload->display_errors());
					if (!empty($error['error'])) {
						$app_user_id = '';
						$response['status'] = 400;
						$response['status_message'] = $error['error'];
						$json_response = json_encode($response);
						echo $json_response;
						header('HTTP/1.1 401 Unauthorized');
						exit();
					}else{
							$insert_checklist =array(
													'order_id'=>$post_data['order_id'],
													'image'=>$dataInfo['file_name'],
													'created_date'=>date('Y-m-d'),
													'created_by'=>$app_user_id ,
													'created_time'=>date('h:i:m'),
													'status'=>'Active',
													'is_deleted'=>'0',
													'type'=>'Owner'
												);
							$category_id = $this->common_model->InsertData('ks_order_checklist',$insert_checklist);
							$app_user_id = '';
							$response['status'] = 200;
							$response['status_message'] = 'CheckList uploaded Successfully';
							$json_response = json_encode($response);
							echo $json_response;

					}
				}else{
							$app_user_id = '';
							$response['status'] = 200;
							$response['status_message'] = 'Order does not exist';
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

		public function OwnerCheckListList()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {	
					$conditions = array(
										'order_id'=>$post_data['order_id'],
										'created_by'=>$app_user_id,
										'type'=>'Owner',
										'is_deleted'=>'0',
										'status'=>'Active'

									);
					$query = $this->common_model->GetAllWhere('ks_order_checklist',$conditions);
					$checklist = $query->result();
					if (!empty($checklist)) {
						$i= 0 ;
						foreach ($checklist as  $value) {
								$checklist[$i]->image = FRONT_URL.'/checklist/'.$value->image ;

							$i++;
						}
						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Checklist Found Successfully';
						$response['result'] = $checklist;
						$json_response = json_encode($response);
						echo $json_response;

					}else{
						$app_user_id = '';
						$response['status'] = 404;
						$response['status_message'] = 'No Checklist Found';
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


		public function OwnerCheckListDelete()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {	
					$conditions = array(
										'order_id'=>$post_data['order_id'],
										'created_by'=>$app_user_id,
										'type'=>'Owner',
										'checklist_id'=> $post_data['checklist_id'],

									);
					$query = $this->common_model->GetAllWhere('ks_order_checklist',$conditions);
					$checklist = $query->row();
					if (!empty($checklist)) {

						$update_checklist  = array( 
				 							'is_deleted'=>'1',
				 							'updated_date'=>date('Y-m-d'),
				 							'updated_time'=>date('H:i:m'),
				 							'updated_by'=>$app_user_id,
				 							); 


				 	$this->common_model->UpdateRecord($update_checklist,"ks_order_checklist","checklist_id",$checklist->checklist_id);						 	

						//print_r($checklist );die;
						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Checklist Found Successfully';
						//$response['result'] = $checklist;
						$json_response = json_encode($response);
						echo $json_response;

					}else{
						$app_user_id = '';
						$response['status'] = 404;
						$response['status_message'] = 'No Checklist Found';
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

		public function RenterCheckListUpload()
		{
			$post_data =  $this->input->post();
			$token = $post_data['token'];
			if (empty($_FILES)) {
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'Select A Image first ';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
			}
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				$query = $this->common_model->GetAllWhere('ks_user_gear_rent_details',array('order_id'=>$post_data['order_id']));
				$order_details = $query->row();
				if (!empty($order_details)) {
					$files = $_FILES;
					$original_image_name =  explode('.',$files['image']['name']);
					$_FILES['image']['name']= $files['image']['name'];
					$_FILES['image']['type']= $files['image']['type'];
					$_FILES['image']['tmp_name']= $files['image']['tmp_name'];
					$_FILES['image']['error']= $files['image']['error'];
					$_FILES['image']['size']= $files['image']['size'];    
					$this->upload->initialize($this->set_upload_options1());
					$this->upload->do_upload('image');
					$dataInfo = $this->upload->data();
					$error = array('error' => $this->upload->display_errors());
					if (!empty($error['error'])) {
						$app_user_id = '';
						$response['status'] = 400;
						$response['status_message'] = $error['error'];
						$json_response = json_encode($response);
						echo $json_response;
						header('HTTP/1.1 401 Unauthorized');
						exit();
					}else{
							$insert_checklist =array(
													'order_id'=>$post_data['order_id'],
													'image'=>$dataInfo['file_name'],
													'created_date'=>date('Y-m-d'),
													'created_by'=>$app_user_id ,
													'created_time'=>date('h:i:m'),
													'status'=>'Active',
													'is_deleted'=>'0',
													'type'=>'Renter'
												); 
							$category_id = $this->common_model->InsertData('ks_order_checklist',$insert_checklist);				
							$app_user_id = '';
							$response['status'] = 200;
							$response['status_message'] = 'CheckList uploaded Successfully';
							$json_response = json_encode($response);
							echo $json_response;

					}
				}else{
							$app_user_id = '';
							$response['status'] = 200;
							$response['status_message'] = 'Order does not exist';
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

		public function RenterCheckListList()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {	
					$conditions = array(
										'order_id'=>$post_data['order_id'],
										'created_by'=>$app_user_id,
										'type'=>'Renter',
										'is_deleted'=>'0',
										'status'=>'Active'

									);
					$query = $this->common_model->GetAllWhere('ks_order_checklist',$conditions);
					$checklist = $query->result();
					if (!empty($checklist)) {
						$i= 0 ;
						foreach ($checklist as  $value) {
								$checklist[$i]->image = FRONT_URL.'checklist/'.$value->image ; 

							$i++;
						}
						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Checklist Found Successfully';
						$response['result'] = $checklist;
						$json_response = json_encode($response);
						echo $json_response;

					}else{
						$app_user_id = '';
						$response['status'] = 404;
						$response['status_message'] = 'No Checklist Found';
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

		public function RenterCheckListDelete()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {	
					$conditions = array(
										'order_id'=>$post_data['order_id'],
										'created_by'=>$app_user_id,
										'type'=>'Renter',
										'checklist_id'=> $post_data['checklist_id'],

									);
					$query = $this->common_model->GetAllWhere('ks_order_checklist',$conditions);
					$checklist = $query->row();
					if (!empty($checklist)) {

						$update_checklist  = array( 
				 							'is_deleted'=>'1',
				 							'updated_date'=>date('Y-m-d'),
				 							'updated_time'=>date('H:i:m'),
				 							'updated_by'=>$app_user_id,
				 							); 


				 	$this->common_model->UpdateRecord($update_checklist,"ks_order_checklist","checklist_id",$checklist->checklist_id);						 	

						
						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Checklist deleted Successfully';
					//		$response['result'] = $checklist;
						$json_response = json_encode($response);
						echo $json_response;

					}else{
						$app_user_id = '';
						$response['status'] = 404;
						$response['status_message'] = 'No Checklist Found';
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

		public function GETCartAddressList()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
					$Cart_details = $this->home_model->getUserCart($app_user_id);
					if (!empty($Cart_details)) {
						$gear_ids = '';
						foreach ($Cart_details as $value) {
							$user_gear_desc_id_array[] =$value['user_gear_desc_id'];
							$gear_ids.= "'". $value['user_gear_desc_id']."',"; 
						}
						$sql = "SELECT `u_a`.*, `ks_states`.`ks_state_name`, `ks_suburbs`.`suburb_name`, `a`.`per_day_cost_aud_inc_gst`, `a`.`per_day_cost_aud_ex_gst`FROM `ks_gear_location` As `g_l`INNER JOIN `ks_user_address` AS `u_a` ON `u_a`.`user_address_id` = `g_l`.`user_address_id`INNER JOIN `ks_suburbs` ON `ks_suburbs`.`ks_suburb_id` = `u_a`.`ks_suburb_id`INNER JOIN `ks_states` ON `ks_states`.`ks_state_id` = `u_a`.`ks_state_id`INNER JOIN `ks_user_gear_description` AS `a` ON `g_l`.`user_gear_desc_id` = `a`.`user_gear_desc_id` WHERE `g_l`.`user_gear_desc_id` IN (".rtrim($gear_ids , ',').") GROUP BY  g_l.user_address_id " ; 
						$query = $this->db->query($sql);

						$address=  $query->result_array();					
						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Address Found';
						$response['result'] = $address;
						$json_response = json_encode($response);
						echo $json_response;
						exit;
					}else{
						$app_user_id = '';
						$response['status'] = 404;
						$response['status_message'] = 'No Address Found';
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

		public function ChangeDatesForCart()
		{	
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				$Cart_details = $this->home_model->getUserCart($app_user_id);
				if (empty($Cart_details)) {
					$app_user_id = '';
					$response['status'] = 404;
					$response['status_message'] = 'No Gear in Cart';
					$json_response = json_encode($response);
					echo $json_response;
					exit();
				}

				foreach ($Cart_details as  $gear_arr) {

					// print_r($gear_arr['user_gear_rent_id']);
					// print_r($gear_arr['user_gear_rent_detail_id']);
					$date_from =  strtotime($post_data['date_from']);
					$date_to =  strtotime($post_data['date_to']);
					$dates_array = $this->getDateList($date_from,$date_to);
					$gear_arr['user_gear_desc_id'];
					$gaer_data = $this->home_model->geratDetails($gear_arr['user_gear_desc_id']);
					$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
					$user_details = $query->row();
	 				$query1	= $this->common_model->GetAllWhere('ks_settings',"");
	 				$setting = $query1->row();
	 				if ($gaer_data->ks_gear_type_id == '1') {
	 					$rentDays =count($dates_array);
	 				}else{
	 					$rentDays = 	$this->GetRentDays($date_from,$date_to);
	 				}
	 				$date_array =  $this->getDateList($date_from,$date_to);
					
					$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$gear_arr['user_gear_desc_id']));
				 	$gear_details = $query1->row();
					if (count($date_array ) <= 1 ) {
				 		# code...
				 	}elseif(count($date_array ) == 2 ){
				 			   $date_array;

				 	}else{
				 		 $val_date = array();
				 		foreach ($date_array as $value) {
				 			 $val_date[] =   $value['date'];
				 		}
				 		$date_array =  $val_date;
						array_pop($date_array); 
					}
					$insurance_days = count($date_array);

					// echo "<br>";
					// echo $gaer_data->per_day_cost_aud_ex_gst ;
					// echo "<br>";
	 				$security_deposit =  $gaer_data->security_deposite_inc_gst;
	 				$total_cost_ex_gst =  $gaer_data->per_day_cost_aud_ex_gst * $rentDays ;
					
					$where_clause1 = array(
				 							'initial_value <' => $total_cost_ex_gst,
				 							'end_value > ' => $total_cost_ex_gst,
				 							'status'=> '0',
				 							'is_deleted' => '0',
				 							'ks_insurance_category_type_id'=> $gear_arr['ks_insurance_category_type_id']
					 				  );
					$query =  $this->common_model->GetAllWhere(' ks_insurance_tiers	' , $where_clause1) ; 
					$insurance_tier_type = $query->row();
					$security_deposit =   $gaer_data->security_deposite_inc_gst;
					$total_cost_non_gst = $total_cost_ex_gst ;
					$beta_discount = ($total_cost_non_gst*15)/100 ; 
					if(empty($insurance_tier_type)){
						$insurance_fee  = 0 ;
					}else{
				  		$insurance_fee  =  number_format((float)(($insurance_tier_type->tiers_percentage *$insurance_days*$gear_details->per_day_cost_aud_ex_gst)/100) , 2, '.', '');
				  	}
				 	$community_fee = ($total_cost_non_gst*5)/100 ;
					if ($user_details->registered_for_gst != 'Y' ) {
						 $totla_gst =   ((($total_cost_ex_gst + $insurance_fee + $community_fee -$beta_discount)*$setting->gst_percent  )/100 ) ;
					}else{
						 $totla_gst =   ((($total_cost_ex_gst + $insurance_fee + $community_fee -$beta_discount)*$setting->gst_percent  )/100 ) ;
					}
					$total_gst =  $totla_gst ;
					$total_cost_with_gst = $total_cost_ex_gst +  $totla_gst;
					$total_gst =  $totla_gst ;
					 
				 	$sub_total = $total_cost_ex_gst - $beta_discount + $insurance_fee + $community_fee ;
				 	
				 	$total_cost_with_gst = $sub_total  +  $totla_gst;
					 $date_from  = 	date('Y-m-d',$date_from);
					 $date_to    = 	date('Y-m-d',$date_to);
					$add_data = array(
					         //"app_user_id" => $app_user_id,
					         //"gear_rent_requested_on"=>date('Y-m-d h:i:m'),
					         "gear_rent_request_from_date"=>$date_from,
					         "gear_rent_request_to_date" =>$date_to,
					         "gear_total_rent_request_days" => $rentDays,
					         "gear_rent_start_date"=>$date_from,
					         "gear_rent_end_date"=>$date_to,
					         "total_rent_days"=>count($dates_array),
					         "total_discount"=>'0',
					         "total_rent_amount_ex_gst"=> $total_cost_non_gst,
					         "gst_amount"=>$total_gst ,
					         "other_charges" => 0 ,
					         "security_deposit" => $security_deposit,
					         "total_rent_amount"=>$total_cost_with_gst ,
					         "rent_request_sent_by"=> $app_user_id,
					         "update_user"=>$app_user_id,
					         "update_date"=> date('Y-m-d'),
					);
					//$user_gear_rent_id = 	$this->common_model->AddRecord($add_data,'ks_user_gear_rent_master');
					$this->common_model->UpdateRecord($add_data,"ks_user_gear_rent_master","user_gear_rent_id",$gear_arr['user_gear_rent_id']);						 	
					$detials_data=array(
						"user_gear_desc_id" => $gear_arr['user_gear_desc_id'],
						//"gear_rent_requested_on" => date('Y-m-d h:i:m'),
						"gear_rent_request_from_date"=>$date_from,
						"gear_rent_request_to_date"=>$date_to ,
						"gear_total_rent_request_days" =>$rentDays,
						"gear_rent_start_date" =>$date_from ,
						"gear_rent_end_date" =>$date_to  ,
						"total_rent_days" =>$rentDays ,
						"gear_discount" =>'0' ,
						"beta_discount"=>$beta_discount,
						"insurance_fee"=>$insurance_fee,
							"community_fee"=>$community_fee,
						"total_rent_amount_ex_gst"=>$total_cost_non_gst ,
						"gst_amount"=> $total_gst,
						"other_charges" =>'0',
						"security_deposit" => $security_deposit,
						"total_rent_amount"=>$total_cost_with_gst,
						"is_rent_approved"=>'N',
						"rent_approved_rejected_on"=> '',
						"is_rent_cancelled" => 'N',
						"update_user"=>$app_user_id,
						"update_date"=>date('Y-m-d')
						);
					//$user_gear_rent_id = 	$this->common_model->AddRecord($detials_data,'ks_user_gear_rent_details');
					$this->common_model->UpdateRecord($detials_data,"ks_user_gear_rent_details","user_gear_rent_detail_id",$gear_arr['user_gear_rent_detail_id']);						 	
					
				}
				$response['status'] = 200;
					$response['status_message'] = 'Gear Dates Updated Successfully';
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
		// public function ChangeDateRequestMail($order_id)
		// {
			
		// }
		public function AddAddresstoCartGear()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				$Cart_details = $this->home_model->getUserCart($app_user_id);
				if (empty($Cart_details)) {
					$app_user_id = '';
					$response['status'] = 404;
					$response['status_message'] = 'No Gear in Cart';
					$json_response = json_encode($response);
					echo $json_response;
					exit();
				}
				foreach ($Cart_details as  $value) {
						$add_data = array('user_address_id'=>$post_data['user_address_id'] );
						$this->common_model->UpdateRecord($add_data,"ks_user_gear_rent_master","user_gear_rent_id",$value['user_gear_rent_id']);						 	



				}
					$response['status'] = 200;
					$response['status_message'] = 'Gear Pickup Address Updated Successfully';
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

		public function AddCartProjects()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if (array_key_exists("project_name",$post_data))
			{}else{
					$response['status'] = 404;
					$response['status_message'] = 'please provide a project name';
					$json_response = json_encode($response);
					echo $json_response;
					exit();
			}
			if (array_key_exists("project_description",$post_data))
			{}else{
					$response['status'] = 404;
					$response['status_message'] = 'please provide a project description';
					$json_response = json_encode($response);
					echo $json_response;
					exit();
			}		
			if ($app_user_id != '') {
				$Cart_details = $this->home_model->getUserCart($app_user_id);
				if (empty($Cart_details)) {
					$app_user_id = '';
					$response['status'] = 404;
					$response['status_message'] = 'No Gear in Cart';
					$json_response = json_encode($response);
					echo $json_response;
					exit();
				}
				foreach ($Cart_details as  $value) {

					$add_data = array(
								'project_description'=>$post_data['project_description'],
								'project_name'=>$post_data['project_name'],
							);
						$this->common_model->UpdateRecord($add_data,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 	
				}
					$response['status'] = 200;
					$response['status_message'] = 'Project  Details Added Successfully';
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

		public function SerailNumberBYGear()
		{
			
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '' ) {
				// print_r($app_user_id);
				// print_r($post_data['gear_name']);
				$query = $this->common_model->GetAllWhere('ks_user_gear_description',array('gear_name'=>$post_data['gear_name']));
				$gear_details =$query->row();
				// print_r($gear_details->serial_number);
				if (!empty($gear_details)) {
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'serialNumber found';
					$response['result'] = array('serial_number'=>$gear_details->serial_number);
					$json_response = json_encode($response);
					echo $json_response;
				}else{
					$app_user_id = '';
					$response['status'] = 404;
					$response['status_message'] = 'SerialNumber Not found ';
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
		public function CancelOrderByRenter()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '' ) {
				$Cart_details = $this->home_model->OwnerOrderSummary($post_data['order_id']);
				if (!empty($Cart_details)) {
					$this->OwnerCancelMail($post_data['order_id']);
					if ($Cart_details[0]['is_rent_approved'] != 'Y') {
						$total_rent_amount = '';
						foreach ($Cart_details as  $value) {
							$update_cart  = array( 
				 							'is_rent_approved'=>'N',
				 							'is_rent_cancelled' => 'Y',
				 							'order_status'=>'5',
				 							'order_status_date'=>date('Y-m-d'),
				 							'rent_approved_rejected_on'=> date('Y-m-d'),
				 							'rent_request_cancelled_by'=>$app_user_id
				 							); 
				 			$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 	
				 			$total_rent_amount +=$value['total_rent_amount'];
						}
						$insert_refund = array(
				 						'order_id'=>$post_data['order_id'],
				 						'user_id'=>$app_user_id,
				 						'amount' => $total_rent_amount,
				 						'reason'=>'Order is been cancelled No cancellation charge charged As order is not confirmed',
				 						'refund_date'=>date('Y-m-d H:i:m'),
				 						'refund_type'=>'Payment',
				 						'created_date'=>date('Y-m-d'),
				 						'created_time'=>date('H:i:m'),
				 						'created_by'=>$app_user_id
				 					);
						 $this->common_model->InsertData('ks_refund_order' ,$insert_refund);

						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Order is been cancelled No cancellation charge charged As order is not confirmed';
						$json_response = json_encode($response);
						echo $json_response;
						exit;
					}else{
						 $current_time = strtotime(date('Y-m-d H:i:s', strtotime("-48 hours"  ,strtotime($Cart_details[0]['gear_rent_request_from_date']))));
						if (strtotime(date('Y-m-d h:i:s')) < $current_time ) {
							$total_rent_amount = '';
							foreach ($Cart_details as  $value) {
								$update_cart  = array( 
					 							'is_rent_approved'=>'N',
					 							'is_rent_cancelled' => 'Y',
					 							'order_status'=>'5',
				 								'order_status_date'=>date('Y-m-d'),
					 							'rent_approved_rejected_on'=> date('Y-m-d'),
					 							'rent_request_cancelled_by'=>$app_user_id
					 							); 
					 			$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 	
					 			$total_rent_amount +=$value['total_rent_amount'];
							}
							$insert_refund = array(
					 						'order_id'=>$post_data['order_id'],
					 						'user_id'=>$app_user_id,
					 						'amount' => $total_rent_amount,
					 						'reason'=>'Order is been cancelled No cancellation charge charged As order is cancelled before 48 hrs of pickup time',
					 						'refund_date'=>date('Y-m-d H:i:m'),
					 						'refund_type'=>'Payment',
					 						'created_date'=>date('Y-m-d'),
					 						'created_time'=>date('H:i:m'),
					 						'created_by'=>$app_user_id
					 					);
							 $this->common_model->InsertData('ks_refund_order' ,$insert_refund);


							$app_user_id = '';
							$response['status'] = 200;
							$response['status_message'] = 'Order is been cancelled No cancellation charge charged As order is cancelled before 48 hrs of pickup time';
							$json_response = json_encode($response);
							echo $json_response;
							exit;
						}else{
							 $current_time2 = strtotime(date('Y-m-d H:i:s', strtotime("-24 hours"  ,strtotime($Cart_details[0]['gear_rent_request_from_date']))));
							 if (strtotime(date('Y-m-d h:i:s')) < $current_time2 ) {
							 	$total_rent_amount = '';
								foreach ($Cart_details as  $value) {
									$update_cart  = array( 
						 							'is_rent_approved'=>'N',
						 							'is_rent_cancelled' => 'Y',
						 							'order_status'=>'5',
				 									'order_status_date'=>date('Y-m-d'),
						 							'rent_approved_rejected_on'=> date('Y-m-d'),
						 							'rent_request_cancelled_by'=>$app_user_id
						 							); 
						 			$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 	
						 			$total_rent_amount +=$value['total_rent_amount'];
								}
								 $total_rent_amount = $total_rent_amount - (($total_rent_amount *50)/100);
								$insert_refund = array(
						 						'order_id'=>$post_data['order_id'],
						 						'user_id'=>$app_user_id,
						 						'amount' => $total_rent_amount,
						 						'reason'=>'Order is been cancelled 50% cancellation charge charged As order is cancelled within 48 hrs of pickup time',
						 						'refund_date'=>date('Y-m-d H:i:m'),
						 						'refund_type'=>'Payment',
						 						'created_date'=>date('Y-m-d'),
						 						'created_time'=>date('H:i:m'),
						 						'created_by'=>$app_user_id
						 					);
								 $this->common_model->InsertData('ks_refund_order' ,$insert_refund);
								 $app_user_id = '';
								$response['status'] = 200;
								$response['status_message'] = 'Order is been cancelled 50% cancellation charge charged As order is cancelled within 48 hrs of pickup time';
								$json_response = json_encode($response);
								echo $json_response;
								exit;
							 }else{
							 		$total_rent_amount = '';
									foreach ($Cart_details as  $value) {
										$update_cart  = array( 
							 							'is_rent_approved'=>'N',
							 							'is_rent_cancelled' => 'Y',
							 							'order_status'=>'5',
				 										'order_status_date'=>date('Y-m-d'),
							 							'rent_approved_rejected_on'=> date('Y-m-d'),
							 							'rent_request_cancelled_by'=>$app_user_id
							 							); 
							 			$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 	
							 			$total_rent_amount +=$value['total_rent_amount'];
									}
									 $total_rent_amount = $total_rent_amount - (($total_rent_amount *100)/100);
									$insert_refund = array(
						 						'order_id'=>$post_data['order_id'],
						 						'user_id'=>$app_user_id,
						 						'amount' => '0',
						 						'reason'=>'Order is been cancelled 100% cancellation charge charged As order is cancelled within 24 hrs of pickup time',
						 						'refund_date'=>date('Y-m-d H:i:m'),
						 						'refund_type'=>'Payment',
						 						'created_date'=>date('Y-m-d'),
						 						'created_time'=>date('H:i:m'),
						 						'created_by'=>$app_user_id
						 					);
									 $this->common_model->InsertData('ks_refund_order' ,$insert_refund);
									 $app_user_id = '';
									$response['status'] = 200;
									$response['status_message'] = 'Order is been cancelled 100% cancellation charge charged As order is cancelled within 24 hrs of pickup time';
									$json_response = json_encode($response);
									echo $json_response;
									exit;
							 }	
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

		// Owner Cancel mail

		public function OwnerCancelMail($order_id)
		{
			$this->OwnerRenterCanelMail($order_id);
			$this->SendOwnerCancelMailOwner($order_id);
		}

			//Owner Cancel Mail to Renter
		public function OwnerRenterCanelMail($order_id='')
		{
			$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
			
			$msg= '<!doctype html>
					<html>
					<head>
					<meta charset="utf-8">
					<title>kitshare</title>
					</head>

					<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">

					<div class="wrapper" style="border:0px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">

					</div>


					<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
					<table width="940" style="    margin: 0px auto;
					    background-color:#095cab;
					    padding: 10px 0px;
					    text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
					</tr>
					</table>
					<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
					<td style="font-size:20px; padding-bottom:10px;">
					Hi,'.$cart_details[0]['renter_firstname'].'  '.$cart_details[0]['renter_lastname'].' </td>
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">We’re sorry to say that the  '. $cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'] .' Cancelled your reservation request.</td>
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">We regret to inform you that your Owner cancelled reservation '. $order_id.' starting on '.   date('d-m-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).'. Per our cancellation policy, you have the option to request a cancellation fee of up to 50% of the Rental value if cancelled within 48hrs or 100% of the rental Rental value if cancelled within 24hrs of the Pickup time.</td>

					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">For more information on cancellation, please see our <a href="'.WEB_URL.'/faq" target="_blank" >FAQ</a> .</td>
					
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">Your calender has also been updated to show that the previously booked dates are now available.</td>

					</tr>
					<td style="font-size:18px; padding-bottom:10px;"><h2>Regards,</h2>
					<p>The Kitshare Team</p>

					</td>

					</tr>

					</table>

					<table width="940" style="    margin: 0px auto;
					    background-color:#ddd;
					    padding: 5px 0px;
					    text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><p style="margin:0">&#169;2018 KitShare. All rights reserved</p></td>
					</tr>
					</table>
					</div>

					</body>
					</html>
					';

					$mail_body = $msg;

			$to= $cart_details[0]['renter_email'];
			//$to= 'singhaniagourav@gmail.com';
			$subject = "OWNER HAS CANCELLED RENTAL";		
			$mail_data = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"kitshare ",
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
		}
		//Owner Cancel Mail to Owner
		public function SendOwnerCancelMailOwner($order_id='')
		{
			$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
				$msg= '<!doctype html>
					<html>
					<head>
					<meta charset="utf-8">
					<title>kitshare</title>
					</head>

					<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">

					<div class="wrapper" style="border:0px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">

					</div>


					<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
					<table width="940" style="    margin: 0px auto;
					    background-color:#095cab;
					    padding: 10px 0px;
					    text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
					</tr>
					</table>
					<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
					<td style="font-size:20px; padding-bottom:10px;">
					Hi,'.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].' </td>
					</tr>
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">We regret to inform you that you has cancelled reservation '.$order_id.' starting on '. date('d-m-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).'. </td>
					</tr>
					
					<tr>
					<td style="font-size:18px; padding-bottom:10px;">We’ve cancelled your reservation request and you will not be charged for it. </td>
					
					</tr>
					
					<td style="font-size:18px; padding-bottom:10px;"><h2>Regards,</h2>
					<p>The Kitshare Team</p>

					</td>

					</tr>

					</table>

					<table width="940" style="    margin: 0px auto;
					    background-color:#ddd;
					    padding: 5px 0px;
					    text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><p style="margin:0">&#169;2018 KitShare. All rights reserved</p></td>
					</tr>
					</table>
					</div>

					</body>
					</html>
					';

					$mail_body = $msg;
			$to= $cart_details[0]['primary_email_address'];				
			// $to= 'singhaniagourav@gmail.com';
			$subject = "OWNER HAS CANCELLED RENTAL";		
			$mail_data = array(
							'Messages'=>array(array(
											"From"=>array(
													"Email"=>"support@kitshare.com.au",
													"Name"=>"kitshare ",
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
		}

		public function Gearaveragevalue($sub_categoryname)
		{
			 $sub_categoryname = urldecode($sub_categoryname);
			$sql = "SELECT average_value FROM ks_gear_categories WHERE  gear_category_name LIKE '%".$sub_categoryname."%' "; 
			$query = $this->db->query($sql);
			$cat_details = $query->row();
			if (!empty($cat_details)) {
				$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'Average value found';
					$response['result'] = $cat_details;
					$json_response = json_encode($response);
					echo $json_response;
					exit;
			}else{
					$response['status'] = 200;
					$response['status_message'] = 'No data Found';
					
					$json_response = json_encode($response);
					echo $json_response;
					exit;
			}
		}

		public function OrderMarkContract()
		{

			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
				$Cart_details = $this->home_model->OwnerOrderSummary($post_data['order_id']);
				if (empty($Cart_details)) {
					$response['status'] = 404;
					$response['status_message'] = 'No data Found';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
				}
				foreach ($Cart_details as  $value) {
					if ($value['is_rent_approved'] == 'Y' && $value['is_payment_completed']== 'Y' && $value['is_rent_cancelled'] =='N' && $value['is_rent_rejected'] =='N')  {
							$update_cart  = array( 
				 							'is_rent_started' => 'Y',
				 							'order_status'=>'3',
				 							'order_status_date'=>date('Y-m-d'),
				 							'start_date'=> date('Y-m-d'),
				 							'started_by'=>$app_user_id
				 							); 


				 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 	

					}

				}
					$response['status'] = 200;
					$response['status_message'] = 'Order marked as Contact  successfully';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
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

		public function OrderMarkCompleted()
		{

			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
				$Cart_details = $this->home_model->OwnerOrderSummary($post_data['order_id']);
				if (empty($Cart_details)) {
					$response['status'] = 404;
					$response['status_message'] = 'No data Found';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
				}
				foreach ($Cart_details as  $value) {
					if ($value['is_rent_approved'] == 'Y' && $value['is_rent_started']== 'Y' )  {
							$update_cart  = array( 
				 							'owner_rent_complete' => 'Y',
				 							'order_status'=>'4',
				 							'order_status_date'=>date('Y-m-d'),
				 							'owner_rent_complete_date'=> date('Y-m-d'),
				 							'completed_by'=> $app_user_id
				 							); 
				 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 	
					}
				}
					$response['status'] = 200;
					$response['status_message'] = 'Order marked as Completed  successfully by owner';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
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
		public function OrderMarkCompletedRenter()
		{

			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
				$Cart_details = $this->home_model->OwnerOrderSummary($post_data['order_id']);
				if (empty($Cart_details)) {
					$response['status'] = 404;
					$response['status_message'] = 'No data Found';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
				}
				foreach ($Cart_details as  $value) {
				//	if ($value['is_rent_approved'] == 'Y' && $value['is_rent_started']== 'Y' )  {
							$update_cart  = array( 
				 							'renter_rent_complete' => 'Y',
				 							'order_status'=>'4',
				 							'order_status_date'=>date('Y-m-d'),
				 							'renter_rent_complete_date'=> date('Y-m-d'),
				 							'completed_by'=> $app_user_id
				 							); 
				 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 	
					//}
				}
					$response['status'] = 200;
					$response['status_message'] = 'Order marked as Completed  successfully by Renter';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
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
		// Kit Listing
		public function Kitlisting()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
				$order_id 	= $post_data['order_id'];
				$Cart_details = $this->home_model->OwnerOrderSummary($post_data['order_id']);

				// print_r($Cart_details);
				if (!empty($Cart_details)) {
					$i = 0 ;
					foreach ($Cart_details as  $gears) {
							if (!empty($gears['accessories'])) {
								$gear_ids_array =  explode(',', $gears['accessories']);
								$gear_ids = '' ;
								foreach ($gear_ids_array as $gear_category) {
									$gear_ids.= "'". $gear_category."',";
								}
								// print_r($gear_ids);
								 $sql =  "SELECT gear_name FROM ks_user_gear_description WHERE user_gear_desc_id IN (".rtrim($gear_ids , ',').") ";
								$query = $this->db->query($sql);
								$accessories_name_list = $query->result();
								$Cart_details[$i]['accessories_list']=$accessories_name_list ; 	
							}else{
								$Cart_details[$i]['accessories_list']=array() ; 	
							}
						$i++;
					}
				}
					$response['status'] = 200;
					$response['status_message'] = 'Kitlisting details';
					$response['result'] = $Cart_details;
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
		// Home User List
		public function homeUserDetails()
		{
			$user_details = $this->home_model->homeUserDetails();
			$i= 0;
			foreach ($user_details as  $value) {
					unset($user_details[$i]->auth_secret_key);
					unset($user_details[$i]->app_password);
					if ($value->user_profile_picture_link =='') {
						$user_details[$i]->user_profile_picture_link =  BASE_URL."server/assets/images/profile.png" ;
					}
				$i++ ;
			}
			// echo "<pre>";
			// print_r($user_details);die;
			$response['status'] = 200;
			$response['status_message'] = 'User List';
			$response['result'] = $user_details;
			$json_response = json_encode($response);
			echo $json_response;
		}

		public function PageDetails($page_code)
		{
			$page_content  = $this->home_model->PageDetails($page_code);
			$page_content->content = html_entity_decode($page_content->content);
			
			$response['status'] = 200;
			$response['status_message'] = 'User List';
			$response['result'] = $page_content;
			$json_response = json_encode($response);
			echo $json_response;
		}


		public function generateInvoice()
		{

			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
				$order_id = $post_data['order_id'];
				$sql = "SELECT o_d.* ,g_tbl.gear_name FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$order_id."'  "	;
				$order_details   = $this->common_model->get_records_from_sql($sql);
				foreach ($order_details as  $value) {
				 $users[] = 	$value->create_user;
				}
				
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
				$url = '';
				$data['six_digit_random_number'] = $order_id; 
				$this->uri->segment('3');
				$data['order_details'] = $order_details;
				//print_r($data);
				$mail_body = $this->load->view('order-template',$data ,true);
				//print_r($mail_body);die;
				$this->load->helper('pdf');
				$pdf_url =  gen_pdf($mail_body,$order_id);
				//echo $pdf_url ;
				$response['status'] = 200;
				$response['status_message'] = 'PDF URL';
				$response['result'] =$pdf_url ;
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

		public function SocailLinks()
		{
			$query =  $this->common_model->GetAllWhere('ks_settings' ,'' );
			$setting_data =  $query->row();
			$SocailLinks = array(
								'fb_link'=> $setting_data->fb_link,
								'twitter_link'=> $setting_data->twitter_link,
								'instagram_link'=> $setting_data->instagram_link,
								);
			$response['status'] = 200;
			$response['status_message'] = 'Socail Links';
			$response['result'] = $SocailLinks;
			$json_response = json_encode($response);
			echo $json_response;
		}
		public function BraintreeDetails()
		{
			$query = $this->common_model->GetAllWhere('ks_settings','');
			$braintree_details = $query->row();
			 $data = 	array(
						'braintree_url'=>$braintree_details->braintree_url,
						'braintree_merchant_id'=>$braintree_details->braintree_merchant_id,
						'braintree_public_key'=>$braintree_details->braintree_public_key,
						'braintree_private_key'=>$braintree_details->braintree_private_key,
					);
			$response['status'] = 200;
			$response['status_message'] = 'Braintree Details';
			$response['result'] = $data;
			$json_response = json_encode($response);
			echo $json_response;
		}
		//Add Gear View Count
		public function AddGearViewCount()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
					$ip =$_SERVER['REMOTE_ADDR']; 
					$user_gear_desc_id = $post_data['user_gear_desc_id'];
					$where_clause =  array(
											'app_user_id'=>$app_user_id , 
											'gear_id'=>$user_gear_desc_id,
											'ip_address'=>$ip
										);
					$query = $this->common_model->GetAllWhere('ks_gear_user_view_tracking',$where_clause);
					$review_check =  $query->row();
					if (empty($review_check)) {
						$insert_view  = array(
									'ip_address'=>$ip,
									'gear_id'	=>$user_gear_desc_id,
									'app_user_id'=>$app_user_id,
									'create_date'=>date('Y-m-d'),
									'created_time'=>date('H:i:s'),
									'created_by'=> $app_user_id
								  );
						$this->common_model->AddRecord($insert_view,'ks_gear_user_view_tracking');
						$view_count =  $this->GETGearViewCount($user_gear_desc_id);
						$app_user_id = '';
						$response['status'] = 200;

						$response['status_message'] = 'View added to this gear successfully';
						$response['view_count'] = $view_count;
						$json_response = json_encode($response);
						echo $json_response;
						exit;
					}else{
						$view_count =  $this->GETGearViewCount($user_gear_desc_id);
						$app_user_id = '';
						$response['status'] = 404;
						$response['status_message'] = 'Alreday view added to this gear';
						$response['view_count'] = $view_count;
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

		//Gear View Count
		public function GETGearViewCount($user_gear_desc_id)
		{
			$where_clause =  array(
									'gear_id'=>$user_gear_desc_id,
								);
			$query = $this->common_model->GetAllWhere('ks_gear_user_view_tracking',$where_clause);
			$review_check =  $query->result();
			if (empty($review_check)) {
				$count = '0';
			}else{
				$count = count($review_check);
			}
			return $count;
		}

		// Add Issue
		public function DamageIssue()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
				$input_data = array(
									'order_id'=> $post_data['order_id'] ,
									'type' => $post_data['type'],
									'issue' => $post_data['issue'],
									'app_user_id' => $post_data['app_user_id'],
									'created_date'=>date('Y-m-d'),
									'created_time'=>time('H:i:m'),
									'created_by' => $app_user_id,
									'status' =>'1'
								);	
				$issue_id = $this->common_model->InsertData('ks_order_issues',$input_data);				
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Issue added successfully';
				$json_response = json_encode($response);
				echo $json_response;
				//	header('HTTP/1.1 200 Unauthorized');
				exit();	
			}else{
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 200 Unauthorized');
				exit();
			}	
		}

		// Fetch  Issues List

		public function GetOrderIssueList()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
				$where_clause = array('order_id'=>$post_data['order_id'] ,'created_by =' =>$app_user_id);
				$query = $this->common_model->GetAllWhere('ks_order_issues',$where_clause);
				$issue_list =  $query->result();
				if (!empty($issue_list)) {
						$response['status'] = 200;
						$response['status_message'] = ' issue list found';
						$response['result'] = $issue_list;
						$json_response = json_encode($response);
						echo $json_response;
						//header('HTTP/1.1 200 authorized');
						exit();
				}else{
						$response['status'] = 404;
						$response['status_message'] = 'No issue list found';
						$json_response = json_encode($response);
						echo $json_response;
					//	header('HTTP/1.1 401 Unauthorized');
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

		// Fetch Single List

		public function GetSingleOrderIssueList()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
				$where_clause = array(
									  'order_id' => $post_data['order_id'],
									  'ks_order_issues_id' => $post_data['ks_order_issues_id']

									  );
				$query = $this->common_model->GetAllWhere('ks_order_issues',$where_clause);
				$issue_list =  $query->row();
				if (!empty($issue_list)) {
						$response['status'] = 200;
						$response['status_message'] = ' issue list Details';
						$response['result'] = $issue_list;
						$json_response = json_encode($response);
						echo $json_response;
						header('HTTP/1.1 200 Unauthorized');
						exit();
				}else{
						$response['status'] = 404;
						$response['status_message'] = 'No issue list Details';
						$json_response = json_encode($response);
						echo $json_response;
						header('HTTP/1.1 200 Unauthorized');
						exit();
				}

			}else{
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 200 Unauthorized');
				exit();
			}		
		} 

		// Edit Single Listings

		public function EditIssueListings($value='')
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
					$where_clause = array(
									  'order_id' => $post_data['order_id'],
									  'ks_order_issues_id' => $post_data['ks_order_issues_id']
									  );
					$query = $this->common_model->GetAllWhere('ks_order_issues',$where_clause);
					$issue_list =  $query->row();
					if ($issue_list){
							$input_data = array(
									'order_id'=> $post_data['order_id'] ,
									'type' => $post_data['type'],
									'issue' => $post_data['issue'],
									'app_user_id' => $post_data['app_user_id'],
									'update_date'=>date('Y-m-d'),
									'update_time'=>time('H:i:m'),
									'updated_by' => $app_user_id,
									
								);	
							//$issue_id = $this->common_model->InsertData('ks_order_issues',$input_data);				

							$this->common_model->UpdateRecord($input_data,"ks_order_issues","ks_order_issues_id",$post_data['ks_order_issues_id']);						 	
							$app_user_id = '';
							$response['status'] = 200;
							$response['status_message'] = 'Issue updated successfully';
							$json_response = json_encode($response);
							echo $json_response;
							exit ;

					}else{
						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'No Issue Found';
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
					header('HTTP/1.1 200 Unauthorized');
					exit();
			}	
		}

		// Add User Rating
		public function AddUserRating()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);	
			if ($app_user_id != '') {
					$where_clause = array( 'created_by'=>$app_user_id ,'app_user_id'=>$post_data['app_user_id']);
					$query = $this->common_model->GetAllWhere('ks_user_rating',$where_clause);
					$rating =  $query->row();
					if(!empty($rating)){
						$response['status'] = 200;
						$response['status_message'] = 'Rating already given';
						$json_response = json_encode($response);
						echo $json_response;
						//header('HTTP/1.1 401 Unauthorized');
						exit();	
					}
					$input_data = array(
									'app_user_id'=> $post_data['app_user_id'] ,
									'rating' => $post_data['rating'],
									'created_date'=>date('Y-m-d'),
									'created_time'=>time('h:i:m'),
									'created_by' => $app_user_id,
									'status' =>'1'
								);	
					$issue_id = $this->common_model->InsertData('ks_user_rating',$input_data);				
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'Rating added successfully';
					$json_response = json_encode($response);
					echo $json_response;
					//header('HTTP/1.1 401 Unauthorized');
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

		public function TierFunction()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);

			if ($app_user_id != '') {
				print_r($post_data);
				$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
				$insurance_type = $query->row();

				$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
				$Cart_details = $query1->row();
				$where_clause1 = array(
				 							'initial_value <' => $Cart_details->total_rent_amount_ex_gst,
				 							'end_value > ' => $Cart_details->total_rent_amount_ex_gst,
				 							'status'=> '0',
				 							'is_deleted' => '0',
				 							'ks_insurance_category_type_id'=> $post_data['insurance_type']
					 				  );
					$query =  $this->common_model->GetAllWhere('ks_insurance_tiers' , $where_clause1) ; 
					$insurance_tier_type = $query->row();

					
					$where_clause1 = array(
				 							'status'=> '0',
				 							'is_deleted' => '0',
				 							'tiers_id <=' => $insurance_tier_type->tiers_id,
				 							'ks_insurance_category_type_id'=> $post_data['insurance_type']
					 				  );
					$query =  $this->common_model->GetAllWhere('ks_insurance_tiers' , $where_clause1) ; 
					$insurance_tier_type = $query->result();
					print_r($insurance_tier_type);
					$sum_value = 0;
					if (!empty($insurance_tier_type)) {
						foreach ($insurance_tier_type as $value) {
							$sum_value += ($value->difference_amount* $value->tiers_percentage)/100;
						}
					}
					echo $sum_value;
			}	
		}


		public function CloseUserListing()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);

			if ($app_user_id != '') {
				$where_clause  = array(
										'app_user_id' => $app_user_id,
										'user_gear_desc_id'=>$post_data['user_gear_desc_id']
									 );
				$query = $this->common_model->GetAllWhere('ks_user_gear_description',$where_clause);
				$gear_info =  $query->row();
				if (empty($gear_info)) {
						$app_user_id = '';
						$response['status'] = 404;
						$response['status_message'] = 'No gear found';
						$json_response = json_encode($response);
						echo $json_response;
						exit();
				}else{

						$update_data =array(
												'is_active'=>'C',
												'update_date'=>date('Y-m-d'),
												'update_user'=> $app_user_id

											);
						$this->common_model->UpdateRecord($update_data , 'ks_user_gear_description' , 'user_gear_desc_id', $post_data['user_gear_desc_id']);
						$app_user_id = '';
						$response['status'] = 200;
						$response['status_message'] = 'Gear is marked as closed';
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

		/// function CHeck User Checkout Details 

		public function Usercheckoutcheck()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				$query =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
				$user_details =  $query->row();
				if ($user_details->mobile_number_verfiy != 1) {
						$app_user_id = '';
						$response['status'] = 400;
						$response['status_message'] = 'Mobile number is not verified';
						$json_response = json_encode($response);
						echo $json_response;
						exit();
				}
				if ($user_details->aus_post_verified !='Y') {
								$app_user_id = '';
								$response['status'] = 401;
								$response['status_message'] = ' Digital ID is not verified';
								$json_response = json_encode($response);
								echo $json_response;
								exit();
				}
				$Cart_details = $this->home_model->getUserCart($app_user_id);
				$sum = 0 ;
				$query =  $this->common_model->GetAllWhere('ks_settings','');
				$settings =  $query->row();
				foreach ($Cart_details as $value) {
						if ($value['ks_insurance_category_type_id'] == '1') {
							if( $value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value){
								$app_user_id = '';
								$response['status'] = 401;
								$response['status_message'] = ' The Replacement value for the total cart must not exceed  '.$settings->max_replacement_value .' ex GST';
								$json_response = json_encode($response);
								echo $json_response;
								exit();
							}  
						}
				}
					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'User can Checkout';
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
		
		
				public function testmail($value='')
		{
			
						
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.mailjet.com/v3.1/send",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "{\r\n        \"Messages\":[\r\n                {\r\n                        \"From\": {\r\n                                \"Email\": \"support@kitshare.com.au\",\r\n                                \"Name\": \"kitshare\"\r\n                        },\r\n                        \"To\": [\r\n                                {\r\n                                        \"Email\": \"singhaniagourav@gmail.com\",\r\n                                        \"Name\": \"You\"\r\n                                }\r\n                        ],\r\n                        \"Subject\": \"My first Mailjet Email!\",\r\n                        \"TextPart\": \"Greetings from Mailjet!\",\r\n                        \"HTMLPart\": \"<h3>Dear passenger 1, welcome to <a href=\\\"https://www.mailjet.com/\\\">Mailjet</a>!</h3><br />May the delivery force be with you!\"\r\n                }\r\n        ]\r\n    }",
			  CURLOPT_HTTPHEADER => array(
			    "Authorization: Basic MTE1MGEzYWZkMzg3MzM0ZjU1YmJiMWQ5MTI3YWE5Y2M6N2U3MzdkNjNiMWE1NDQ3NjgwNzNlZjVjOTlmMTQ2YmU=",
			    "Content-Type: application/json",
			    "Postman-Token: 5dacc0e7-eff8-430b-ba53-b9a520abed72",
			    "cache-control: no-cache"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  echo $response;
			}

				
		}

		public function CartunavailableseDates()
		{
			$post_data  = json_decode(file_get_contents("php://input"),true);
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				$Cart_details = 	 $this->home_model->getUserCart($app_user_id);
				$rent_days_details = array();
				foreach ($Cart_details as  $value) {
					//echo $value['user_gear_desc_id'] ;
					$this->db->select('*'); 
					$this->db->from('ks_gear_unavailable');
					$this->db->where('user_gear_description_id',$value['user_gear_desc_id']);
					$query1 = $this->db->get();
					$ks_gear_unavailable  =$query1->result_array();
					
					if (!empty($ks_gear_unavailable)) {
						foreach ($ks_gear_unavailable as  $rentedgear) {
							$rent_days_details[] =  $rentedgear;

						}	
					}

					$this->db->select('*'); 
					$this->db->from('ks_user_gear_rent_details ');
					$this->db->where('user_gear_desc_id',$value['user_gear_desc_id']);
					$this->db->where('is_payment_completed','Y');
					$query1 = $this->db->get();
					$rented_gear   =$query1->result_array();
					if (!empty($rented_gear)) {
						foreach ($rented_gear as  $rentedgear) {
						
							$rent_days_details[] = array(
													'ks_gear_unavailable_id'=> '',
													'user_gear_description_id'=> $value['user_gear_desc_id'],
													'ks_gear_kit_id'=> '',
													'unavailability_reason'=> '',
													'unavailable_from_date'=>date('Y-m-d' ,strtotime($rentedgear['gear_rent_request_from_date'])),
													'unavailable_to_date'=>date('Y-m-d' ,strtotime($rentedgear['gear_rent_request_to_date'])),
													'create_user'=> '',
													'create_date'=> '',
													'update_user'=> '',
													'update_date'=>''
								);
						}
					
					}
				}
					$response['status'] = 200;
					$response['status_message'] = 'unavailable Dates list';
					$response['result'] = $rent_days_details;
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
		