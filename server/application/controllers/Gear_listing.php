<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'/third_party/braintree-php-3.36.0/lib/autoload.php');
// require_once(APPPATH.'/third_party/braintree_php/lib/autoload.php');



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
			
			$email=$this->input->get('email');
			
			$result= $this->common_model->RetriveRecordByWhereRow('ks_users', array('primary_email_address'=>$email)); ////// Get user all information
			$app_user_id= $result['app_user_id'];	
			
			//Gear lists are pushed into the array
			$gear_lists=$this->home_model->GetGearLists();
			$result['gear_lists']=$gear_lists;
						
			unset($result['app_user_id']);
						
			if(!empty($result))
			{
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
			//header('HTTP/1.1 404 Page Not Found');
			$response['status_code'] = 404;
			$response['status_message'] = "No gear found";
			//$response['result'] =$data;
		}
		echo json_encode($response);
		exit();
	}

	public function ViewSingleGearList($gear_id='')
	{
		$app_user_id ="";
		
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
			
			$token 		= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			
		}

		$query =  $this->common_model->GetAllWhere('ks_user_gear_description' , array('gear_slug_name'=>$gear_id));
		$gear_details =  $query->row();

		$gear_id = $gear_details->user_gear_desc_id ;
		$data['gear_details'] = $this->home_model->geratDetails($gear_id);
		$data['related_listings'] = $this->home_model->UserrelatedGear(  $data['gear_details']->ks_category_id ,$gear_id);
		$data['user_gears'] = $this->home_model->UsergearDetails( $data['gear_details']->app_user_id);
		
		
		if($app_user_id!=""){
			
			$table = "ks_user_gear_favourite";
			$where_clause = array("app_user_id"=>$app_user_id,"user_gear_desc_id"=>$gear_id);
			
			$fav_res = $this->common_model->RetriveRecordByWhereRow($table,$where_clause);
			
			if(!empty($fav_res))
				$data['gear_details']->is_favourite='Y';
			else
				$data['gear_details']->is_favourite='N';
			
		}else
			$data['gear_details']->is_favourite='N';
		
		
		
		// $address	  =
		// 	array_merge( $data,$address);
		$app_user_id =  $data['gear_details']->app_user_id ; 
		$sql= "SELECT AVG(star_rating)  As rating FROM ks_cust_gear_reviews WHERE  app_user_id = '".$app_user_id."'";
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

				

					$data['gear_details']->rating = number_format((float)( $rating->rating), 0, '.', '') ;
					$data['gear_details']->review = count($reviews);
					$data['gear_details']->reference =$reference->reference;
					$data['gear_details']->response_time ='24 hrs Response time';
					$data['gear_details']->acceptance_rate ='100% Acceptance Rate';
					


		if (!empty($data)) {
			$response['status_code'] = 200;
			$response['status_message'] = "Successfully gear list found";
			$response['result'] =$data;
		}else{
			//header('HTTP/1.1 404 Page Not Found');
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
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		
		if($app_user_id!=''){
		
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
				//header('HTTP/1.1 404 Page Not Found');
				$response['status_code'] = 404;
				$response['status_message'] = "No gear found";
				//$response['result'] =$data;
			}
			echo json_encode($response);
			
		}else{
			
			header('HTTP/1.1 400 Session Expired');
			exit();
		}
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	public function ViewprivategearDetails($gear_id)
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		
		if($app_user_id!=''){
		
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
				//header('HTTP/1.1 404 Page Not Found');
				$response['status_code'] = 404;
				$response['status_message'] = "No gear found";
				//$response['result'] =$data;
			}
			echo json_encode($response);
			
		}else{
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
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
				$result_arr[$i]['gear_category_name']= htmlspecialchars_decode($row['gear_category_name'] ,ENT_QUOTES	);

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
		$gear_category_id = htmlspecialchars(urldecode($this->input->get('gear_category_id')) ,ENT_QUOTES);
		
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
				$result_arr[$i]['gear_category_name']=htmlspecialchars_decode($row['gear_category_name'] ,ENT_QUOTES);
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
				$result_arr[$i]->model_name   = htmlspecialchars_decode(stripcslashes($value->model_name) ,ENT_QUOTES) ;
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

		if(is_array($post_data) && count($post_data)>0 ){
			
		$query_category = $this->common_model->GetAllWhere('ks_gear_categories','') ;
		$category = $query_category->result();
			
		if(empty($post_data['manufacturer_name'])==true){
			
			$result['status'] = '400';
			$result['status_message'] = 'Manufacturer does not exist.';
			$result['result'] = '';
			$result['category'] = $category;
			echo json_encode($result,true);
			exit ;
			
		}
		
		//check Models
		if(empty($post_data['model_name'])==true){
			
			$result['status'] = '400';
			$result['status_message'] = 'Model does not exist.';
			$result['result'] = '';
			$result['category'] = $category;
			echo json_encode($result,true);
			exit ;
			
		}
		
		$query_manufacturers = $this->common_model->GetAllWhere('ks_manufacturers',array( 'manufacturer_name'=>$post_data['manufacturer_name'] ,)) ;
		$manufacturers = $query_manufacturers->row();
		if(empty($manufacturers)){
			$result['status'] = '400';
			$result['status_message'] = 'Manufacturer does not exist.';
			$result['result'] = '';
			$result['category'] = $category;
			echo json_encode($result,true);
			exit ;
		}
		
		
		$post_data['model_name']  =htmlspecialchars(urldecode($post_data['model_name'] ),ENT_QUOTES);
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
		$result_arr->gear_sub_category_name =  htmlspecialchars_decode($sub_category->gear_category_name, ENT_QUOTES);

		$category = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_id '=>$result_arr->gear_category_id)) ;
		$category	= $category->row();
		if (!empty($category)) {
			$result_arr->gear_category_name =  htmlspecialchars_decode($category->gear_category_name,ENT_QUOTES);
			$result_arr->average_value =  $category->average_value;
		}else{
			$result_arr->gear_category_name =  '';
			$result_arr->average_value =  '';
		}
		// Get All gear Category 
			$query_category = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_sub_category_id'=>'0')) ;
			$category = $query_category->result();
			if (!empty($category)) {
				$i=0 ;
				foreach ($category as $value) {
						$category[$i]->gear_category_name = htmlspecialchars_decode($value->gear_category_name , ENT_QUOTES )  ;

					$i++;
				}
			}
			$query_sub_category = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_sub_category_id !='=>'0')) ;
			$sub_category = $query_sub_category->result();
			if (!empty($sub_category)) {
				$i=0 ;
				foreach ($sub_category as $value) {
						$sub_category[$i]->gear_category_name = htmlspecialchars_decode($value->gear_category_name ,ENT_QUOTES) ;

					$i++;
				}
			}
		if(!empty($result_arr)){
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
			$query_category_id = $this->common_model->GetAllWhere('ks_gear_categories',array( 'gear_category_name'=>htmlspecialchars($post_data['gear_category_name'] ,ENT_QUOTES ))) ;
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
					if (!empty($result_arr)) {
						$i=0 ;
						foreach ($result_arr as $value) {
								$result_arr[$i]->gear_category_name = htmlspecialchars_decode($value->gear_category_name , ENT_QUOTES )  ;

							$i++;
						}
					}
					$result['status'] = '200';
					$result['status_message'] = 'Success';
					$result['match_found'] = 0;
					$result['result'] = $result_arr;
					echo json_encode($result,true);
					exit ;
			}
			
		}
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
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
		$newName  = time()."".$ext;
		//die;
		$copied = copy($imagePath , $newPath.$newName);
		$configer =  array(
					              'image_library'   => 'gd2',
					              'source_image'    =>  BASE_IMG.'/site_upload/gear_images/'.$image,
					              'maintain_ratio'  =>  true,
					              'width'           =>  IMG_WIDTH,
					              'height'          =>  IMG_HEIGHT,
					            );
							   $this->image_lib->clear();
					           $this->image_lib->initialize($configer);
					           $this->image_lib->resize();
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
		
		if(!empty($post_data)){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		
		if($app_user_id!=''){
			
		//Transaction begins here
		$this->db->trans_begin();
		
		if(count($post_data['address']) > 0 ){
			
			$address_ids =  implode(',',$post_data['address']);
		}else{
			
			$address_ids = '' ; 
		}
		
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
		$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => htmlspecialchars($post_data['gear_category_name'],ENT_QUOTES) ));
		$category_data = $query->row();
		if(!empty($category_data)){
			$category_id = $category_data->gear_category_id ;
		}else{ // Add Parent Category
			$category_insert = array(
			'gear_category_name' =>htmlspecialchars($post_data['gear_category_name'],ENT_QUOTES),
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
			$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => htmlspecialchars($post_data['gear_sub_category_name'] , ENT_QUOTES)));
			$category_data = $query->row();
			if(!empty($category_data)){
				$sub_category_id = $category_data->gear_category_id ;
			}else{ // Add Sub  Category
				$category_insert = array(
				'gear_category_name' =>htmlspecialchars($post_data['gear_sub_category_name'],ENT_QUOTES),
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

		$query =$this->common_model->GetAllWhere('ks_models',array('model_name' => htmlspecialchars(stripcslashes($post_data['gear_model_name']),ENT_QUOTES)));
		$modal_data = $query->row();	
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
			 
			}
				$accessories =  implode(',',$values);
				


		}else{
			
				$accessories = '' ; 
		}
		
		
		
		
		// price Calcuation 
		$query_sett=   $this->common_model->GetAllWhere('ks_settings', '');	
		$settings = $query_sett->row();
			
		  $row['per_weekend_cost_aud_ex_gst'] =  ($post_data['per_day_cost_aud_ex_gst']*3) ; 
		  $row['per_week_cost_aud_ex_gst'] =  ($post_data['per_day_cost_aud_ex_gst']*3) ; 
		
		  $row['per_weekend_cost_aud_inc_gst'] =  $row['per_weekend_cost_aud_ex_gst'] + ($row['per_weekend_cost_aud_ex_gst']*$settings->gst_percent)/100 ;
		  $row['per_week_cost_aud_inc_gst'] =  $row['per_week_cost_aud_ex_gst'] + ($row['per_week_cost_aud_ex_gst']*$settings->gst_percent)/100 ;
		
		$row['gear_name'] =  $post_data['gearName'];

		$slug_name =  $this->alphanumericAndSpace(trim($post_data['gearName']));
		$slug_name = str_replace(' ', "-", $slug_name );		

		$row['gear_slug_name'] =  $slug_name;
		$row['ks_manufacturers_id'] = $manufacturer_id ;
		$row['ks_category_id'] = $category_id ;
		$row['ks_sub_category_id'] = $sub_category_id ;
		$row['model_id'] = $model_id;
		$row['ks_gear_address_id'] = $address_ids ;
		$row['ks_user_gear_condition'] =  $post_data['gearCondition'];
		$row['serial_number'] = $post_data['serialNumber'];
		$row['gear_description_1'] = $post_data['model_description'];
		$row['security_deposite'] = $post_data['security_deposite'];
		if (!empty($post_data['security_deposite'])) {
			$row['security_deposite_inc_gst'] = $post_data['security_deposite'] + ($post_data['security_deposite']*$settings->gst_percent)/100;
		}else{
			$row['security_deposite_inc_gst'] =  '0';
		}	
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
		$user_gear_id =   $this->common_model->InsertData('ks_user_gear_description',$row);
		
		
		$query = $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$user_gear_id));
		$gear_details =  $query->row();
		$update_data = array(
		 						'gear_slug_name'=> $slug_name.'-'.$user_gear_id
		 					);
		$this->common_model->UpdateRecord($update_data , 'ks_user_gear_description' , 'user_gear_desc_id', $user_gear_id);
		

		
		//Record is inserted into the search table 
		$search_array = array();
		$search_array = $row;
		$search_array['user_gear_desc_id'] = $user_gear_id;
		$search_array['model_name'] = $post_data['gear_model_name'];
		$search_array['manufacturer_name'] = $post_data['gear_brand_name'];
		$search_array['gear_slug_name'] =  $slug_name.'-'.$user_gear_id;
		$search_array['gear_category_name'] = htmlspecialchars($post_data['gear_category_name'],ENT_QUOTES);
		$search_array['gear_subcategory_name'] = htmlspecialchars($post_data['gear_sub_category_name'],ENT_QUOTES);
		
		$this->common_model->InsertData('ks_user_gear_search',$search_array);		 
		////////////////////
		 
		 
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
							
							$res = $this->common_model->get_records_from_sql("SELECT MAX(gear_display_seq_id) max_id FROM ks_user_gear_images WHERE user_gear_desc_id='".$user_gear_id."' AND is_active='Y' AND is_deleted=0");
							$gear_sequence_id = $res[0]->max_id;
					
							if($gear_sequence_id==NULL || $gear_sequence_id=="" ||  $gear_sequence_id==0)
								$gear_sequence_id = 1;
							else
								$gear_sequence_id = $gear_sequence_id+1;
							
			 					# code...
			 					
			 		 		 $data_images = array(

			         					'model_id' =>  $modal_data->model_id,
			         					'gear_display_image' =>$response_image['name_image'] ,
			         					'user_gear_desc_id'=> $user_gear_id,
										'gear_display_seq_id'=> $gear_sequence_id,
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
		
		//Transaction ends here
		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				
				$response['status'] = 400;
				$response['status_code']='failed';
				$response['status_message'] = "Something went wrong! Please try again.";
		}
		else
		{
				$this->db->trans_commit();
				
				$response['status'] = 200;
				$response['status_code']='success';
				$response['status_message'] = "Gear added sucessfully";
				$response['result'] = array(
											'model_id'=>$model_id,
											'user_gear_desc_id'=> $user_gear_id
										);
				
		}
				
		
		/* if($user_gear_id > 0 ) {
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
		 }*/
		
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
	private function set_upload_options()
{   
    //upload an image options
    $config = array();
    $config['upload_path'] = BASE_IMG.'site_upload/gear_images_original';;
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
		$existing_imgs = $this->input->post('existing_images');
		
		$query = $this->common_model->GetAllWhere('ks_user_gear_description	',array('user_gear_desc_id'=>$user_gear_desc_id));
		$values = $query->row();
		if (empty($values)) {
			 $response['status'] = '404';
			 $response['message'] = 'No gear exist';
			 echo json_encode($response,true); 
			 exit;
		}
		
		if($existing_imgs!=''){
				
				$existing_images = explode(",",$existing_imgs);
				
				//Table is updated for sequence = 0
				$update_data = array();
				$update_data['gear_display_seq_id'] = 0;
				
				$this->common_model->UpdateRecord($update_data,"ks_user_gear_images","user_gear_desc_id",$user_gear_desc_id);
				
				//Now table is updated for the provided order
				foreach($existing_images as $key=>$val){
					
					$update_data = array();

					$update_data['gear_display_seq_id']=$key;
					$this->common_model->UpdateRecord($update_data,"ks_user_gear_images","user_gear_image_id",$val);
					
				}

		}
   		
    	$app_user_id = $this->userinfo($token);
    	$files = $_FILES;
    
    	if(count($files)>0 && $app_user_id > 0){
  
        
        		$images = array();
				
				
				
				foreach($files['image']['name'] as $key=>$val){
				
					$original_image_name =  explode('.',$files['image']['name'][$key]);
					$_FILES['images[]']['name']= $files['image']['name'][$key];
					$_FILES['images[]']['type']= $files['image']['type'][$key];
					$_FILES['images[]']['tmp_name']= $files['image']['tmp_name'][$key];
					$_FILES['images[]']['error']= $files['image']['error'][$key];
					$_FILES['images[]']['size']= $files['image']['size'][$key];   
					
					$this->upload->initialize($this->set_upload_options());
					$this->upload->do_upload('images[]');
					$dataInfo = $this->upload->data();
					$image_data = $this->upload->data();
					$error = array('error' => $this->upload->display_errors());
					

					if (!empty($error['error'])) {
						$result['status'] = 400;
						$result['status_code']='error';
					
						$result['status_message'] = $error['error'];
						echo  json_encode($result,true);
						exit;
					}
					 $image =  $dataInfo['file_name'] ; 

					$imagePath =  BASE_IMG.'site_upload/gear_images_original/'.$image;
					$newPath = BASE_IMG.'/site_upload/gear_images/';
					$ext = '.jpg';
					$newName  = $image;
					$copied = copy($imagePath , $newPath.$newName);	       	
					$configer =  array(
						  'image_library'   => 'gd2',
						  'source_image'    =>  BASE_IMG.'/site_upload/gear_images/'.$image,
						  'maintain_ratio'  =>  true,
						  'width'           =>  IMG_WIDTH,
						  'height'          =>  IMG_HEIGHT,
					);
					$this->image_lib->clear();
					$this->image_lib->initialize($configer);
					$this->image_lib->resize();
					
					
					//MAX gear_display_seq_id is fetched
					$res = $this->common_model->get_records_from_sql("SELECT MAX(gear_display_seq_id) max_id FROM ks_user_gear_images WHERE user_gear_desc_id='".$user_gear_desc_id."' AND is_active='Y' AND is_deleted=0");
					$gear_sequence_id = $res[0]->max_id;
			
					if($gear_sequence_id==NULL || $gear_sequence_id=="" ||  $gear_sequence_id==0)
						$gear_sequence_id = 1;
					else
						$gear_sequence_id = $gear_sequence_id+1;
			
					$data_images = array(

										'model_id' => $model_id,
										'gear_display_image' => $image,
										'user_gear_desc_id'=> $user_gear_desc_id,
										'gear_display_seq_id'=> $gear_sequence_id,
										'is_active'=> 'Y',
										 'create_user'=> $app_user_id ,
										 'create_date'=> date('Y-m-d')
									);
					$this->common_model->InsertData('ks_user_gear_images',$data_images);
				
				}
        
        	$result['status'] = 200;
			$result['status_code']='success';				
			$result['status_message'] = "Gear Image added sucessfully";
        
        }else if(count($_FILES)==0 && $app_user_id!='' && count($update_data)>0){
        
        			$result['status'] = 200;
					$result['status_code']='success';				
					$result['status_message'] = "Gear Image updated sucessfully";
        
        }else{
			header('HTTP/1.1 400 Session Expired');
			exit();
		}
		echo json_encode($result,true);
		
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
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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
			
			header('HTTP/1.1 400 Session Expired');
			exit();
		}	
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
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


		$permalink =   $this->input->get('category_id') ;
		
		$query = $this->common_model->GetAllWhere('ks_faq_category' , array('status'=>'Y', 'permalink'=>$permalink));
		$category  = $query->row();
		// print_r();die;
		$query = $this->home_model->GetFaqList('ks_faq' , array('status'=>'Y','category_id'=>$category->id ));
		$gear_list  = $query->result();
		if (!empty($gear_list)) {
			foreach ($gear_list as  $value) {
				if ($value->image == '') {
					$value->image = FAQ_IMAGE.'no-image-icon.jpg';
				}else{
					$value->image = FAQ_IMAGE.$value->image;
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
		// $faq_id =  str_replace('-', " ", $faq_id) ;
		$query = $this->common_model->GetAllWhere('ks_faq' , array('status'=>'Y','permalink'=>$faq_id ));
		$gear_list  = $query->row();

		if ($gear_list->image == '') {
						$gear_list->image = FAQ_IMAGE.'no-image-icon.jpg';
		}else{
						$gear_list->image = FAQ_IMAGE.$gear_list->image;
		}
		$gear_list->description  =  html_entity_decode($gear_list->description);
		$query = $this->common_model->GetAllWhere('ks_faq_category' , array('status'=>'Y', 'id'=>$gear_list->category_id));
		$category  = $query->row();
		if (empty($category)) {
			$category = array();
		}
		$result['status'] = 200;
		$result['status_code']='success';
		$result['result'] =  array(
								'category'=>$category,
								'details'=>$gear_list);
		echo 		json_encode($result, true) ;
		
		
	}
	
	public function  getCategoryFeature($gear_category_name=""){
		$gear_category_name = urldecode($this->uri->segment(3));
		$query = $this->common_model->GetAllWhere('ks_gear_categories' , array('is_active'=>'Y','gear_category_name'=>htmlspecialchars($gear_category_name  ,ENT_QUOTES)));
		
		if(empty($gear_category_name)==false){		
			$gear_details  = $query->row();
			$gear_category_id = $gear_details->gear_category_id;
		}else
			$gear_category_id = "";
		$data =  $this->getCategoryFeature1($gear_category_id);
		echo json_encode($data, true);
	}
	

	public function  getCategoryFeature1($gear_category_id=""){
		
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
	/*public function Search()
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
			 
			 $where .=  "b.ks_renter_type_id IN (".rtrim($owner_type_ids , ',').")  AND ";
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
				
					if ($lat_array->status  != 'ZERO_RESULTS'){
						 $lat = $lat_array->results[0]->geometry->location->lat;
						 $lng = $lat_array->results[0]->geometry->location->lng;
						if (empty(urldecode($this->input->get('radius')))) {
							 $radius = '25';

						}else{
							$radius = urldecode($this->input->get('radius'));
						}
						
						

						$postal_arr_list = $this->fetchPostCodes($lat,$lng,$radius,0);
						
						if($postal_arr_list!="")						
							$where .= "u_add.postcode IN (".$postal_arr_list.") AND ";
						else
							$where .= "u_add.postcode ='' AND ";
						
					}
					
				

				//$where .= " ( 3959 * acos( cos( radians('".$lat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$lng."') ) + sin( radians('".$lat."') ) * sin( radians( lat ) ) ) ) < '".$radius."' AND  "; 
			}	
				//print_r($data['address']);die;
				//$where .= " u_add.street_address_line1 LIKE  '%".trim($data['address'])."%' OR u_add.street_address_line2 LIKE  '%".trim($data['address'])."%'   OR ks_suburbs.suburb_name LIKE  '%".trim($data['address'])."%' OR ks_states.ks_state_name LIKE  '%".trim($data['address'])."%'    AND  "; 
		}
		$data['suburb_name'] = urldecode($this->input->get('suburb_name'));
		if (!empty($data['suburb_name'])) {
			$where .= " u_add.street_address_line1 LIKE  '%".trim($data['suburb_name'])."%' OR u_add.street_address_line2 LIKE  '%".trim($data['suburb_name'])."%'   OR ks_suburbs.suburb_name LIKE  '%".trim($data['suburb_name'])."%' OR ks_states.ks_state_name LIKE  '%".trim($data['suburb_name'])."%'    AND  "; 
		}
			
		// echo $where ;
		$where .= "a.is_active='Y' AND a.gear_hide_search_results ='Y' AND b.is_active ='Y' ";

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
						$result_arr[$j]['gear_sub_category_name']= htmlspecialchars_decode($row['gear_category_name'] ,ENT_QUOTES);

						$result_arr[$j]['gear_parent_id']=$row['gear_sub_category_id'];
						$result_arr[$j]['type'] = 'sub_category';

						$j++;
					}
						$category_data[$i]['type'] = 'category';
						$category_data[$i]['gear_sub_category'] = $result_arr;
						$category_data[$i]['gear_category_name'] = htmlspecialchars_decode($value['gear_category_name'] ,ENT_QUOTES);

						$i++;
					}
				}
				$data['category_data'] = $category_data ;
				// print_r($data['category_data']);die;
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
	 	
	} */

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
			
			if (empty($response->predictions)) {
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
		
		if(is_array($post_data) && count($post_data)>0 ){
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		 if ($app_user_id != '') {
		 		$user_gear_desc_id =  $post_data['user_gear_desc_id'];
				$date_from =  strtotime($post_data['date_from']);
				$date_to =  strtotime($post_data['date_to']);
				$Cart_details = $this->home_model->getUserCart1($app_user_id);				
				
				//Checked whether this gear is available for that date or not
				$unvailable_dates = $this->home_model->checkGearAvailability($user_gear_desc_id,$post_data['date_from'],$post_data['date_to']);
				if(count($unvailable_dates)>0){
                	if($unvailable_dates[0]['user_gear_description_id']>0)
                	{
                    	$response['status'] = 400;
                    	$response['status_message'] = 'This gear is unavailable on this date, please pickup another date!';
                   	 	$json_response = json_encode($response);
                    	echo $json_response;
                    	exit();

                	}
                }
				
				$val = 0;
				$dateflag = 0;	//Flag is used to check whether the pickup date is today's date or not
				
				foreach ($Cart_details as  $value) {
					if ($value['user_gear_desc_id'] == $post_data['user_gear_desc_id'] ) {
						$val = 1;
					}
				}
				if ($val == 1) {
					$app_user_id = '';
					$response['status'] = 400;
					$response['status_message'] = 'Already added to cart';
					$json_response = json_encode($response);
					echo $json_response;
					
					exit();
				}
				
				//User Gear Category ID is fetched
				$where_clause = "user_gear_desc_id='".$user_gear_desc_id."'";
				$query = $this->common_model->GetSpecificValues("ks_category_id","ks_user_gear_description",$where_clause);
				$row = $query->result_array();
				$ks_category_id = $row[0]['ks_category_id'];
				
			 	if($date_from  == $date_to ){
					
					$current_time = date('H:i:s');
					
					$curdate = strtotime(date('Y-m-d'));
					 
					if($current_time >= '22:00:00' && $date_from==$curdate){
					
						$date_from 	= date('Y-m-d', strtotime($post_data['date_from'] . ' +1 day'));
						$date_to 	= date('Y-m-d', strtotime($post_data['date_to'] . ' +1 day'));
						$date_from 	= strtotime($date_from);
						$date_to 	= strtotime($date_to);
						
						//If pickup date is today's date then this flag is turned on
						$dateflag = 1;
						
					}else if($date_from==$curdate){
					
						$date_from 	= strtotime(date('Y-m-d', strtotime($post_data['date_from'])));
						$date_to 	= strtotime(date('Y-m-d', strtotime($post_data['date_to'])));
						
						//If pickup date is today's date then this flag is turned on
						$dateflag = 1;
						
					}else{
					
						/*$date_from 	= strtotime(date('Y-m-d', strtotime($post_data['date_from'])));
						$date_to 	= date('Y-m-d', strtotime($post_data['date_to'] . ' +1 day'));
						$date_to 	= strtotime($date_to);
						
						$dateflag = 2;*/
						$dateflag = 1;
					}
						
					
					
				}else
					$dateflag = 2;
					
				if (!empty($Cart_details)) {
					 $date_from 	=  strtotime(date('d-m-Y',strtotime($Cart_details[0]['gear_rent_request_from_date'])));	
					 $date_to 		=  strtotime( date('d-m-Y',strtotime($Cart_details[0]['gear_rent_request_to_date'])));	
					
				}
				$dates_array = $this->getDateList($date_from,$date_to);
				
				
				$gaer_data = $this->home_model->geratDetails($user_gear_desc_id);
				
				
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
				
				if ($gaer_data->app_user_id  == $app_user_id) {
						$app_user_id = '';
						$response['status'] = 400;
						$response['status_message'] = 'Cannot add own gear to cart';
						$json_response = json_encode($response);
						echo $json_response;
						
						exit();
				}
				if (!empty($Cart_details)) {
					$gear_ids = '';
					$where = '';
					$JOIN = '';
					$i = 0 ;
					foreach ($Cart_details as $value) {

						$user_gear_desc_id_array[] =$value['user_gear_desc_id'];
						$gear_ids.= "'". $value['user_gear_desc_id']."',"; 
						$JOIN .= " INNER JOIN ks_gear_location AS k_l_".$i."  ON g_l.user_address_id =  k_l_".$i.".user_address_id "; 
						$where .= " k_l_".$i.".user_gear_desc_id = '".$value['user_gear_desc_id']."'  AND    ";
						$i++;
					} 
					
					  $sql = "SELECT `u_a`.*, `ks_states`.`ks_state_name`, `ks_suburbs`.`suburb_name`, `a`.`per_day_cost_aud_inc_gst`, `a`.`per_day_cost_aud_ex_gst`, a.`ks_category_id` FROM `ks_gear_location` As `g_l` INNER JOIN `ks_user_address` AS `u_a` ON `u_a`.`user_address_id` = `g_l`.`user_address_id`INNER JOIN `ks_suburbs` ON `ks_suburbs`.`ks_suburb_id` = `u_a`.`ks_suburb_id`INNER JOIN `ks_states` ON `ks_states`.`ks_state_id` = `u_a`.`ks_state_id`INNER JOIN `ks_user_gear_description` AS `a` ON `g_l`.`user_gear_desc_id` = `a`.`user_gear_desc_id` ".$JOIN."  WHERE   ".rtrim($where , ' AND')." GROUP BY  g_l.user_address_id " ; 
					  
				
					$query = $this->db->query($sql);

					$cart_gear_location =  $query->result_array();	
					
					$query = $this->common_model->GetAllWhere('ks_gear_location', array('user_gear_desc_id'=>$user_gear_desc_id));
					$add_gear_location = $query->result();
					$address_list = array();
					foreach ($cart_gear_location as  $gear_location) {
						foreach ($add_gear_location as  $add_gear_value) {
							if($add_gear_value->user_address_id  == $gear_location['user_address_id'] ){
								$address_list[]= $gear_location;	
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
				$is_present = '0';
				if (!empty($Cart_details[0]['user_address_id'])) {
					foreach ($add_gear_location as  $new_location) {
						if ($Cart_details[0]['user_address_id'] == $new_location->user_address_id) {
							$is_present = '1';
						}
					}
					if ($is_present == '0' ) {
						$app_user_id = '';
						$response['status'] = 400;
						$response['status_message'] = 'Gear is not present at selected Location ';
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
						
				//Pickup time will always be 14:00 hrs. and Drop off will be 12:00 hrs if it is not Creative Space and if it is not a Single day Rent
				if($ks_category_id == 162){
					
					$date_from		= 	date('Y-m-d',$date_from)." 00:00:00";
					$date_to		= 	date('Y-m-d',$date_to)." 23:59:00";
				
				}else{
				
					if($dateflag == 1){
					
						$date_from		= 	date('Y-m-d',$date_from)." 00:00:00";
						$date_to		= 	date('Y-m-d',$date_to)." 23:59:00";
					
					}else{
						$date_from		= 	date('Y-m-d',$date_from)." 14:00:00";
						$date_to		= 	date('Y-m-d',$date_to)." 12:00:00";
					}
					
				}
				
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
				         "gear_rent_requested_on"=>date('Y-m-d H:i:m'),
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
				if ($is_present == 1) {
					$add_data['user_address_id'] = $Cart_details[0]['user_address_id'] ;
				}
				$user_gear_rent_id = 	$this->common_model->AddRecord($add_data,'ks_user_gear_rent_master');
				// $user_gear_rent_id = '';
				$detials_data=array(
					"user_gear_desc_id" => $user_gear_desc_id,
					"user_gear_rent_id" => $user_gear_rent_id,
					"gear_rent_requested_on" => date('Y-m-d H:i:m'),
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
					"create_user"=>$app_user_id,
					"create_date"=> date('Y-m-d'),
					);
				if ($detials_data['security_deposit'] > 0 ) {
					$detials_data['ks_insurance_category_type_id'] = 5;
				}
				$user_gear_rent_id = 	$this->common_model->AddRecord($detials_data,'ks_user_gear_rent_details');
				$this->db->last_query();
				// die;
				$response['status'] = 200;
				$response['status_message'] = 'Gear added to cart';
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

	public function GetRentDays($date_from,$date_to)
	{
		
		$total_days = '' ;
        $count = '';
        $count1 = '';
		$diff = abs($date_to-$date_from);
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
             return (int)$count+ (int)$total_days  ;    

        }elseif($tola_days_reamining == '1'  && $tola_week ==  0 || $tola_days_reamining == '0'  &&  $tola_week ==  0 ){
            $count = 1 ;
        }elseif($tola_days_reamining == '1' &&  $tola_week  > 0     ){
            $count = 1 ;
        }
        return  (int)$count+ (int)$total_days  ;       
	}

	// User Cart List
	public function UserCart($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		
		if(is_array($post_data) && count($post_data)>0 ){
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			 $Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
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
				 $owner_insurance_amount = 0 ;

				$query =  $this->common_model->GetAllWhere('ks_settings','');
				$settings =  $query->row();

				 $i= 0 ;
			 	foreach ($Cart_details as  $value) {
				
						 //If Owner Insurance amount is greater than 0 and insurance amount is 0 then insurance amount will have owner's insurance amount
						 if($value['owner_insurance_amount']>0 && $value['insurance_amount']==0)
						 	$Cart_details[$i]['insurance_amount'] = $value['owner_insurance_amount'];	
						//This piece of code is for front end display as from insurance amount they are displaying the amount	
						//Code ends///				
			 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
			 			 $other_charges  += $value['other_charges'] ; 
			 			 $user_address_id = $value['user_address_id'] ;
			 			// $total_rent_amount  += $value['total_rent_amount'] ; 
			 			 $security_deposit +=   $value['security_deposit'];
			 			 $owner_insurance_amount  += number_format((float)($value['owner_insurance_amount']), 2, '.', '')  ; 
			 			 $beta_discount += $value['beta_discount']; 
				 		 $insurance_fee += $value['insurance_fee']; 
				 		 $community_fee += $value['community_fee']; 
				 		 $sub_amount = $value['total_rent_amount_ex_gst'] - $value['beta_discount'] +  $value['insurance_fee'] +  $value['community_fee'] + (int) $value['owner_insurance_amount']; 
				 		 $total_rent_amount  += $sub_amount ;
				 		 
			 			 $app_user_id_array[] = $value['app_user_id'];
						 
			 			 if ($value['gear_display_image'] == '') {
			 			 	 $Cart_details[$i]['gear_display_image']   = BASE_URL."/site_upload/gear_images/default_product.jpg";
			 			 }else{
			 			 	if(file_exists(BASE_IMG.'site_upload/gear_images/'.$value['gear_display_image'])) {
								$Cart_details[$i]['gear_display_image'] = GEAR_IMAGE.$value['gear_display_image'] ;
		 					}else{
		 						$Cart_details[$i]['gear_display_image'] = BASE_URL."/site_upload/gear_images/default_product.jpg";
		 					}
			 			}

			 			if ($value['security_deposit_check'] != '1') {
			 				 $Cart_details[$i]['security_deposit']   = '0.00';
			 			}

			 			$query = $this->common_model->GetAllWhere('ks_gear_categories',array( 'security_deposit'=>'Y','is_active'=>'Y', 'gear_category_id'=>$value['ks_sub_category_id']));
						$security_deposit_check = $query->row();
			 			
						if (!empty($security_deposit_check)) {

							if ($value['security_deposit_check'] == '0') {
								 $Cart_details[$i]['insurance_check_key']   = 2;
							}else{
								 $Cart_details[$i]['insurance_check_key']   = 0;
							}
							
						}else{
							if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value) {
			 				 	$Cart_details[$i]['insurance_check_key']   = 1;	
				 			}else{
				 				$Cart_details[$i]['insurance_check_key']   = 0;	
				 			}
						}
						
							if (!empty($Cart_details[$i]['ks_insurance_category_type_id'])) {
								$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active' ,'ks_insurance_category_type_id'=> $Cart_details[$i]['ks_insurance_category_type_id']) ) ; 
								$insurance_type1 = $query->row();
								if (empty($insurance_type1)) {
									$Cart_details[$i]['insurance_type_name'] =  '' ;
									//$Cart_details[$i]['insurance_type_description'] =  '' ;
									$Cart_details[$i]['description']['message'] = "";
									$Cart_details[$i]['description']['error'] = "";
									
								}else{
									$Cart_details[$i]['insurance_type_name'] =  $insurance_type1->name ;
									//$Cart_details[$i]['insurance_type_description'] =  $insurance_type1->description ;
									$Cart_details[$i]['description']['message'] =  $insurance_type1->description;
									$Cart_details[$i]['description']['error'] = "";
								}	
								
							}else{
							
								$Cart_details[$i]['description']['message'] = "";
								$Cart_details[$i]['description']['error'] = "";
							}
	
							//IF there is no insurance the message will be customed
							if($Cart_details[$i]['insurance_check_key']==2){
								$Cart_details[$i]['description']['message'] = "Insurance is not required to rent this listing";
								$Cart_details[$i]['description']['error'] = "";
							}
							
							if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value && $Cart_details[$i]['insurance_check_key']==1) {
								//Renter insurance Check 
								$where_clause = array(
										'app_user_id'=> $app_user_id ,
										'renter_insurance_provided' => 'Yes',
										'renter_insurance_status'=> '1' ,
										'is_visiible'=> 'Y' ,
										'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
										'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

									);

									$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause);
									$renter_insurance =  $query->row();
									
									if (empty($renter_insurance)) {
									
										//Check For Owner Insurance 
										$where = array( 'app_user_id'=>$value['app_user_id'] ,
														'is_visiible'=> 'Y' ,
														'owner_insurance_provided'=>'Yes',
														'owner_insurance_status' =>'1',
														'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
														'ks_user_certificate_currency_start <= ' =>date('Y-m-d'), 
														);
										$query1 =  $this->common_model->GetAllWhere('ks_user_insurance_proof' ,$where);
										$owner_insurance_detail = $query1->row();

										if (empty($owner_insurance_detail)) {
											//$Cart_details[$i]['insurance_type_description'] =  'This listing exceeds the limit of liability for Kitshare Insurance' ;
											//$Cart_details[$i]['error']   = 'Please upload a certificate of currency to rent this listings.' ;		
											$Cart_details[$i]['description']['message']='This listing exceeds the limit of liability for Kitshare Insurance';	
											$Cart_details[$i]['description']['error']='Please upload a certificate of currency to rent this listings.';	
										}
										
									}else{
										$Cart_details[$i]['description']['error'] = ' ' ;		
									}
									
									
				 			}else{
				 				 $Cart_details[$i]['description']['error'] = '';	
				 			}
						
							//Conditions start here
							$where_array = "status='Active'";
						
							//if $value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value then the dropdown won't have Kitshare Insurance Policy in it							
							// if($Cart_details[$i]['insurance_check_key'] == 1){
								
							// 	$where_array .= " AND ks_insurance_category_type_id!='1'";
							// }
			 				
							// $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , $where_array ) ; 
							// $insurance_type = $query->result();
							if ($Cart_details[$i]['insurance_check_key'] ==2) {
								$category_array = array();
							}
							elseif ($value['security_deposit'] >  "0.00") {
								$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>5))	;
								$category_array = $query->result_array();
								// $category_array = array();
							}else{
								$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>1))	;
								$category_array = $query->result_array();
								$query_sett=   $this->common_model->GetAllWhere('ks_settings', '');	
								$settings = $query_sett->row();
								if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value) {
									$category_array = array();
								}
								$where_clause = array(
													'app_user_id'=> $app_user_id ,
													'renter_insurance_provided' => 'Yes',
													'renter_insurance_status'=> '1' ,
													'is_visiible'=> 'Y' ,
													'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
													'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

												);
								$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
								$renter_insurance  =  $query->row();
								if (!empty($renter_insurance)) {
									$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>3))	;
									$renter_insurance = $query->row();	
									array_push($category_array, $renter_insurance);
								}
								//Owner inurance
								$Cart_details1 = 	 $this->home_model->getUserCart1($app_user_id);
						 		$where_clause = array(
															'app_user_id'=> $Cart_details1[0]['app_user_id'] ,
															'owner_insurance_provided' => 'Yes',
															'owner_insurance_status'=> '1' ,
															'is_visiible'=> 'Y' ,
															'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
															'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

														);
								$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
								$owner_insurance  =  $query->row();
								if (!empty($owner_insurance)) {
									$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>8))	;
									$owner_insurance = $query->row();							
									array_push($category_array, $owner_insurance);
								}
							}	

							$Cart_details[$i]['insurance_type']  = $category_array;
							unset($Cart_details[$i]['primary_email_address']);
							

			 			 $i++;
			 		}	
			 		

			 		if (!empty($app_user_id_array)) {	
			 			$app_user_id_array =  array_unique($app_user_id_array);
			 		//	$app_user_id_array =  explode(',', $app_user_id_array) ;
						$app_user_ids = '' ;
						foreach ($app_user_id_array as $app_user) {
							$app_user_ids.= "'". $app_user."',";
						}
					$sql1 =  "SELECT app_user_id ,bussiness_name,show_business_name,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
					$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
					$app_users_details[0]->owner_id =  $app_users_details[0]->app_user_id;
					$app_users_details[0]->renter_id = $app_user_id;
					$rating =$this->home_model->GetUSerRatings($app_users_details[0]->app_user_id);
					$app_users_details[0]->rating =number_format((float)( $rating->rating), 0, '.', '');

					$review_result= $this->home_model->ProductReview($app_users_details[0]->app_user_id);
					 $review_result=$review_result->result_array();
					 if (!empty($review_result)) {
					 	$app_users_details[0]->reviews = count($review_result) ;
					 }else{
					 	$app_users_details[0]->reviews = 0 ;
					 }
					 if ($app_users_details[0]->show_business_name == 'Y') {
					 	$app_users_details[0]->app_user_first_name = $app_users_details[0]->bussiness_name ; 
					 	$app_users_details[0]->app_user_last_name= ''; 
					 }
					// print_r($review_result);die;
			 		}
			 		
			 		$gst_amount = ($total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount)*10/100;
			 		$rent_summary = array(
			 								'user_address_id'=>$user_address_id,
			 								'gear_total_rent_request_days'=>$Cart_details[0]['gear_total_rent_request_days'],
			 								'per_day_cost_aud_ex_gst'=>$Cart_details[0]['per_day_cost_aud_ex_gst'],
			 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
			 								'gst_amount'=> number_format((float)( $gst_amount), 2, '.', '')  ,
			 								'owner_insurance_amount'=> number_format((float)($owner_insurance_amount), 2, '.', '')  ,
			 								'beta_discount'=> number_format((float)( $beta_discount), 2, '.', '') ,
			 								'insurance_fee'=>  number_format((float)( $insurance_fee), 2, '.', ''),
			 								'community_fee'=>  number_format((float)( $community_fee), 2, '.', '') ,
			 								'other_charges'=>  number_format((float)( $other_charges), 2, '.', ''),
			 								'sub_total'=> number_format((float)( $total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount ), 2, '.', '')  ,
			 								'total_rent_amount'=> number_format((float)( $total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount + $gst_amount ), 2, '.', '')  ,
											'security_deposit' => number_format((float)( $security_deposit), 2, '.', '')  ,		 								
			 								'total_amount'=>  number_format((float)( $total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount + $gst_amount ), 2, '.', '')  
			 							);
					// echo "<pre>";
					// print_r($Cart_details);die;
					
			 	$response['status'] = 200;
				$response['status_message'] = 'User cart List';
				$response['result'] = array( 'user_address_id'=> $user_address_id, 'cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details );
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

	//Remove Gear From Cart
	public function RemoveGearFromCart()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id != '') {
				$check_cart = $this->home_model->CheckGearInCart($post_data['user_gear_desc_id'], $app_user_id);
				
				if (!empty($check_cart)) {

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
		
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {

			$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type'] ) ) ; 
			$insurance_type = $query->row();

			if ($post_data['insurance_type'] == 0 ) {

				$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'] ,'order_id = '=>''));
			 	$Cart_details = $query1->row();
			 	
			 	//kitshare insurance
			 	if ($Cart_details->ks_insurance_category_type_id == 1) {
			 		$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id ,'order_id'=>''));
			 		$rent_master_details = $query2->row();
			 		$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit; 
					$Cart_details->insurance_amount = number_format((float)((0)/100) , 2, '.', '');  
					$Cart_details->insurance_fee =  number_format((float)((0)/100) , 2, '.', '');
				 	$gear_insurance_value = $Cart_details->insurance_amount;	
				 	$rent_master_details->other_charges	 =  '0' ;
				 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
				 	$Cart_details->ks_insurance_category_type_id = '0'  ; 
				 	$Cart_details->insurance_tier_id =  '0' ; 
				 	$Cart_details->other_charges =  '0' ; 
				 	$Cart_details->security_deposit =  '0' ; 
				 	$Cart_details->deposite_status =  'NONE' ; 
				 		
				 
				 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
				 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
				 	
			 	}
			 	// Security Deposite
			 	if ($Cart_details->ks_insurance_category_type_id == 5) {
			 		$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'],'order_id'=>''));
			 		$Cart_details = $query1->row();
					 $query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
			 		 $rent_master_details = $query2->row();
			 		 $gaer_data = $this->home_model->geratDetails($post_data['user_gear_desc_id']);
					
				 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount ; 
				 	$Cart_details->insurance_amount =  0; 
				 	$Cart_details->ks_insurance_category_type_id = '0' ; 

				 	$Cart_details->insurance_tier_id =  '0'  ; 
				 	$Cart_details->other_charges =  '0' ; 
				 	$Cart_details->security_deposit =   '0'; 
			 		$Cart_details->deposite_status =  'MARK STORED' ;
			 		// echo "<pre>";
			 		// print_r($gaer_data->security_deposite_inc_gst);
				 	$insurance_type->amount = '0'; ;
				 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
				 	// echo $this->db->last_query();die;
				 	if (!empty($rent_master_details)) {
				 		$rent_master_details->other_charges ='' ;
				 		$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
				 	}
			 	}	
			 	//Replacement Value test
			 	if ($Cart_details->ks_insurance_category_type_id == 2) {
			 		$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'],'order_id'=>''));
			 		$Cart_details = $query1->row();
					 
					 $query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
			 		 $rent_master_details = $query2->row();
			 			
					$rent_master_details->other_charges =  $rent_master_details->total_rent_amount_ex_gst  ; 
				 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
				 	$Cart_details->insurance_amount = '0'; 
				 	$Cart_details->ks_insurance_category_type_id = '0'; 
				 	$Cart_details->insurance_tier_id =  '0'  ; 
				 	$Cart_details->other_charges =  '0' ; 
				 	$Cart_details->security_deposit =  '0' ; 
			 		$Cart_details->deposite_status =  'MARK STORED' ; 
				 	$insurance_type->amount ='0'; ;
				 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
				 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
			 	}

			 	if ($Cart_details->ks_insurance_category_type_id == 4) {
			 		$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'],'order_id'=>''));
			 		$Cart_details = $query1->row();
					 
					 $query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
			 		 $rent_master_details = $query2->row();

					$rent_master_details->other_charges = '0' ;
				 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
				 	$Cart_details->insurance_amount =  '0'; 
				 	$Cart_details->ks_insurance_category_type_id = '0' ; 
				 	$Cart_details->insurance_tier_id =  '0'  ; 
				 	$Cart_details->other_charges =  '0' ; 
				 	$Cart_details->security_deposit =  '0' ; 
			 		$Cart_details->deposite_status =  'NONE' ; 
				 	$insurance_type->amount = '0'; ;
				 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
				 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
			 	}

			 	if ($Cart_details->ks_insurance_category_type_id == 3) {
			 		$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'] ,'order_id'=>''));
			 		$Cart_details = $query1->row();
					 
					 $query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
			 		 $rent_master_details = $query2->row();

					$rent_master_details->other_charges = '0' ;
				 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
				 	$Cart_details->insurance_amount =  '0'; 
				 	$Cart_details->ks_insurance_category_type_id = '0' ; 
				 	$Cart_details->insurance_tier_id =  '0'  ; 
				 	$Cart_details->other_charges =  '0' ; 
				 	$Cart_details->security_deposit =  '0' ; 
			 		$Cart_details->deposite_status =  'NONE' ; 
				 	
				 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
				 	
				 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
			 	}

			 	$Cart_details  =  $this->home_model->getUserCart1($app_user_id);
			 	if (count($Cart_details)) {
					 $total_rent_amount_ex_gst = '0';
					 $gst_amount = '0';
					 $other_charges = '0';
					 $total_rent_amount = '0';
					 $security_deposit = '0';
					 $beta_discount = '0';
					 $community_fee = '0';
					 $insurance_fee = 0 ;
					 $i= 0 ;

				 	 foreach ($Cart_details as  $value) {
				 	 	if (file_exists(BASE_IMG.'site_upload/gear_images/'.$value['gear_display_image'])) {
				 	 		$value['gear_display_image'] = $value['gear_display_image'] ;  
				 	 	}else{
				 	 		$value['gear_display_image'] = GEAR_IMAGE."default_product.jpg";
				 	 	}

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
			 			 $Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image']; 
			 			 $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active') ) ; 
						 $insurance_type = $query->result();

							$Cart_details[$i]['insurance_type']  = $insurance_type;
			 			 $i++;
			 		 }	

			 		if (!empty($app_user_id_array)) {	
			 			$app_user_id_array =  array_unique($app_user_id_array);
						$app_user_ids = '' ;
						foreach ($app_user_id_array as $app_user) {
							$app_user_ids.= "'". $app_user."',";
						}

						$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
						$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
				
			 		}
			 		$beta_discount = ($total_rent_amount_ex_gst*15)/100 ; 
			 		$insurance_fee = $insurance_fee; 
			 		$community_fee = ($total_rent_amount_ex_gst*5)/100 ; 
			 		$gst_amount = ($total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee)*10/100;
			 		$rent_summary = array(
			 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
			 								'gst_amount'=>$gst_amount,
			 								'beta_discount'=> $beta_discount,
			 								'insurance_fee'=> $insurance_fee,
			 								'community_fee'=> $community_fee,
			 								'other_charges'=>$other_charges,
			 								'sub_total'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee,
			 								'total_rent_amount'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee + $gst_amount,
											'security_deposit' => '0',		 								
			 								'total_amount'=>  $total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee + $gst_amount 
			 							);
				 	$response['status'] = 200;
					$response['status_message'] = 'User cart List';
					$response['result'] = array('cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details,'insurance_type'=>$insurance_type );
					$json_response = json_encode($response);
					echo $json_response;	
				}

			 	
			}


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
			 	 $Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
				//echo  $this->db->last_query();
				if (count($Cart_details)) {
					
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
				 			 $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active') ) ; 
							 $insurance_type1 = $query->result();

							 $Cart_details[$i]['insurance_type']  = $insurance_type1; 
				 			 $i++;
				 		}	

				 		if (!empty($app_user_id_array)) {	
				 			$app_user_id_array =  array_unique($app_user_id_array);
							$app_user_ids = '' ;
							foreach ($app_user_id_array as $app_user) {
								$app_user_ids.= "'". $app_user."',";
							}

								$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
								$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
					
				 		}
				 		
				 		
				 		$insurance_type->amount = $gear_insurance_value  ;
				 		$gst_amount = ($total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee)*10/100;
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
					$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'], 'order_id ='=>''));
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
					 $Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
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
			 			 $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active') ) ; 
						 $insurance_type1 = $query->result();
						 $Cart_details[$i]['insurance_type']  = $insurance_type1;
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


					$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'],'order_id ='=>''));
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
				 	
					 $Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
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
			 			 $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active') ) ; 
						 $insurance_type1 = $query->result();
						 $Cart_details[$i]['insurance_type']  = $insurance_type1; 
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
			 		$gst_amount = ($total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee)*10/100;
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
					 $Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
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
			 			 $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active') ) ; 
						 $insurance_type1 = $query->result();
						 $Cart_details[$i]['insurance_type']  = $insurance_type1; 
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
			elseif( $post_data['insurance_type'] == '3'){  //USER INSURANCE #3 rd Part
				$paymentDate = date('Y-m-d');
				$paymentDate=date('Y-m-d', strtotime($paymentDate));
					
				$where_clause = array(
										'app_user_id'=> $app_user_id ,
										'renter_insurance_provided' => 'Yes',
										'renter_insurance_status'=> '1' ,
										'is_visiible'=> 'Y' ,
										'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
										'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

									);
				$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
				$insurance =  $query->row();
				
				if(!empty($insurance)){
						$replacement_value_aud_ex_gst = 0 ;
						$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
						$insurance_type = $query->row();

					 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'] ,'order_id ='=>''));
					 	$Cart_details = $query1->row();

					 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
					 	$rent_master_details = $query2->row();

					 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id ,'ks_insurance_category_type_id'=>'3','order_id !=' =>'' ,'create_date' => date('Y-m-d')));
					 	$today_order_Details = $query1->row();
					 	if (!empty($today_order_Details)) {
					 		$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$today_order_Details->user_gear_desc_id ));
						 	$Gear_details_today_order  = $query1->row();

						 	$replacement_value_aud_ex_gst += $Gear_details_today_order->replacement_value_aud_ex_gst ;
					 	}
					 	
					 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$post_data['user_gear_desc_id'] ));
					 	$Gear_details = $query1->row();

					 	$replacement_value_aud_ex_gst +=  $Gear_details->replacement_value_aud_ex_gst ;

					 	if ($replacement_value_aud_ex_gst >  $insurance->insurance_amount) {
					 		$response['status'] = 404;
							$response['status_message'] = 'Cannot checkout for this insurance';
							$response['result'] = array();
							$json_response = json_encode($response);
							echo $json_response;	
							exit();
					 	}
				 		$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
						$Cart_details->esl_rate =  $address_details->ks_state_esl ; 
						$Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
						$Cart_details->premium_base_rate =  $address_details->base_rate ; 

						$Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
						$Cart_details->esl_amount =  $insurance_Details['esl']  ; 
						$Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
						$Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
						$Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
						$Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
						$Cart_details->insurance_amount =  $insurance_Details['insurance_fee']    ; 

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
					 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
				 	 	$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
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
						 			 $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active') ) ; 
									 $insurance_type1 = $query->result();
									 $Cart_details[$i]['insurance_type']  = $insurance_type1; 
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
						 		$gst_amount = ($total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee)*10/100;
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
							$response['result'] = array('cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details );
							$json_response = json_encode($response);
							echo $json_response;
							exit;	
						}
				}else{
						$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
						$insurance_type = $query->row();

					 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'] ,' order_id='=>''));
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
				 	 	$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
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
						 			 $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active') ) ; 
									 $insurance_type1 = $query->result();
									 $Cart_details[$i]['insurance_type']  = $insurance_type1;
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
			elseif( $post_data['insurance_type'] == '8'){ // Owner Insurance
				$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
				$insurance_type = $query->row();

			 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id'], 'order_id ='=>''));
			 	$Cart_details = $query1->row();
			 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
			 	$rent_master_details = $query2->row();
			 	$date_from = strtotime($Cart_details->gear_rent_request_from_date);
			 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
			 	$diff = abs($date_to - $date_from);
				$date_array =  $this->GetRentDays($date_from,$date_to);
				
				$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
			 	$gear_details = $query1->row();
			 	$query =  $this->common_model->GetAllWhere('ks_settings','');
				// print_r($gear_details);
				$where = array( 'app_user_id'=>$app_user_id , 'is_approved'=>'1' ,'owner_insurance_provided'=>'Yes', 'owner_insurance_status' =>1 );
				$query1 =  $this->common_model->GetAllWhere('ks_user_insurance_proof' ,$where);
			 	$insurance_detail = $query1->row();
			 	if (empty($insurance_detail)) {
			 		$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'Not Approved Owner Insurance';
					$json_response = json_encode($response);
					echo $json_response;
					//header('HTTP/1.1 401 Unauthorized');
					exit();
			 	}else{
			 		// 	if (count($date_array ) <= 1 ) {
					 // 		# code...
					 // 	}elseif(count($date_array ) == 2 ){
					 // 			   $date_array;

					 // 	}else{
					 // 		foreach ($date_array as $value) {
					 // 			 $val_date[] =   $value['date'];
					 // 		}
					 // 		 $date_array =  $val_date;
						// 		array_pop($date_array); 
						// }						  
				
						$insurance_days = $date_array;
				 		echo $inusrance_value = ($gear_details->replacement_value_aud_ex_gst * $insurance_detail->owner_insurance_percentage *$insurance_days)/100 ;
												
				 		if ($inusrance_value <  $insurance_detail->owner_insurance_amount ) { 
				 				
				 				$Cart_details->insurance_amount = number_format((float)(($inusrance_value)) , 2, '.', ''); 
								$Cart_details->insurance_fee =  number_format((float)(($inusrance_value)) , 2, '.', ''); 
			 					$gear_insurance_value = $Cart_details->insurance_amount;	
				 				$Cart_details->owner_insurance_status = 1 ;  
				 				$Cart_details->insurance_percentage = $insurance_detail->owner_insurance_percentage ;  
							 	$rent_master_details->other_charges	 =  '0' ;
							 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
							 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
							 	$Cart_details->insurance_tier_id =  0 ; 
							 	$Cart_details->other_charges =  '0' ; 
							 	$Cart_details->security_deposit =  '0' ; 
							 	$Cart_details->deposite_status =  'NONE' ;
							 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
			 					$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
			 	 				$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
			 	 				if (count($Cart_details)) {
					
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
								 			 $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active') ) ; 
											 $insurance_type1 = $query->result();

											 $Cart_details[$i]['insurance_type']  = $insurance_type1; 
								 			 $i++;
								 		}	

								 		if (!empty($app_user_id_array)) {	
								 			$app_user_id_array =  array_unique($app_user_id_array);
											$app_user_ids = '' ;
											foreach ($app_user_id_array as $app_user) {
												$app_user_ids.= "'". $app_user."',";
											}

												$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
												$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
									
								 		}
								 		
								 		
								 		$insurance_type->amount = $gear_insurance_value  ;
								 		$gst_amount = ($total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee)*10/100;
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
				 		}
				 		// print_r($gear_details->replacement_value_aud_ex_gst);die;
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

	public function CartDetails($app_user_id)
	{
		if ($app_user_id != '') {
			 $Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
				if (count($Cart_details)) {
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

				// Insurance cateogry 

				$query = $this->common_model->GetAllWhere('ks_insurance_category_type', array('status'=>'Active'));
				$insurance_list = $query->result();
				// $gear_insurane = array();
				 $i= 0 ;
			 	foreach ($Cart_details as  $value) {
			 		 $gear_insurane = array();
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

			 			$query = $this->common_model->GetAllWhere('ks_gear_categories',array( 'security_deposit'=>'Y','is_active'=>'Y', 'gear_category_id'=>$value['ks_sub_category_id']));
						$security_deposit_check = $query->row();
			 			
						if (!empty($security_deposit_check)) {

							if ($value['security_deposit_check'] == '0') {
								 $Cart_details[$i]['insurance_check_key']   = 2;
							}else{
								 $Cart_details[$i]['insurance_check_key']   = 0;
							}
							
						}else{
							if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value) {
			 				 $Cart_details[$i]['insurance_check_key']   = 1;	
				 			}else{
				 				 $Cart_details[$i]['insurance_check_key']   = 0;	
				 			}
						}

						//Insurace Check 

						if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value) {
			 				 	
				 		}else{
				 				 $gear_insurane[]   = $insurance_list[0];	
				 		}

				 		//Secuirty Check 	
				 		if ($value['security_deposit_check'] == '0') {
						}else{
								$gear_insurane[]  = $insurance_list[2];
						}


						//  Renter Insurance Check (3rd part insurance check )
			 				
						$where_clause = array(
										'app_user_id'=> $app_user_id ,
										'is_active' => 'Y',
										'is_approved'=> '1' ,
										'ks_user_certificate_currency_exp >' =>date('Y-m-d')
										);
			 			$query =  $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause);	
			 			$renter_insurance_check = $query->row();
			 			$sql = "SELECT sum(replacement_value_aud_ex_gst) As total_amount_insurance FROM `ks_user_gear_rent_details`  INNER JOIN ks_user_gear_description  ON  ks_user_gear_description.user_gear_desc_id = ks_user_gear_rent_details.user_gear_desc_id WHERE ks_user_gear_rent_details.create_user = '".$app_user_id."' AND order_id !='' AND  DATE(`gear_rent_requested_on`) = '".date('Y-m-d')."' AND ks_insurance_category_type_id = '3' order by user_gear_rent_detail_id DESC" ;
			 			$today_purchase =  $this->common_model->get_records_from_sql($sql);
			 			

			 			if (!empty($renter_insurance_check)) {
			 				 $left_over_amount = $renter_insurance_check->insurance_amount -$today_purchase[0]->total_amount_insurance  ; 
			 				if ( $left_over_amount >= $value['replacement_value_aud_ex_gst']  ) {
			 						$gear_insurane[]  = $insurance_list[1];
			 				}else{
			 				}		
			 			}

			 			// Owner InsuranceCheck
			 			
			 			$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$value['app_user_id']));
			 			$owner_details =  $query->row();
			 			if ($owner_details->owner_insurance == 'Y') {
			 				$gear_insurane[] = $insurance_list[3];
			 			}
			 			 $Cart_details[$i]['insurace_list']  = $gear_insurane;
			 			 $i++;
			 		}	
			 		
			 		if (!empty($app_user_id_array)) {	
			 			$app_user_id_array =  array_unique($app_user_id_array);
						$app_user_ids = '' ;
						foreach ($app_user_id_array as $app_user) {
							$app_user_ids.= "'". $app_user."',";
						}
					$sql1 =  "SELECT app_user_id ,bussiness_name,show_business_name,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
					$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
					$app_users_details[0]->owner_id =  $app_users_details[0]->app_user_id;
					$app_users_details[0]->renter_id = $app_user_id;
					$rating =$this->home_model->GetUSerRatings($app_users_details[0]->app_user_id);
					$app_users_details[0]->rating =number_format((float)( $rating->rating), 0, '.', '');

					$review_result= $this->home_model->ProductReview($app_users_details[0]->app_user_id);
					 $review_result=$review_result->result_array();
					 if (!empty($review_result)) {
					 	$app_users_details[0]->reviews = count($review_result) ;
					 }else{
					 	$app_users_details[0]->reviews = 0 ;
					 }
					 if ($app_users_details[0]->show_business_name == 'Y') {
					 	$app_users_details[0]->app_user_first_name = $app_users_details[0]->bussiness_name ; 
					 	$app_users_details[0]->app_user_last_name= ''; 
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
			 	
				  $result = array('cart'=>$Cart_details,'cart_summary'=>$rent_summary , 'app_users_details'=>$app_users_details );
				return $result ;
			 }
			
		}else{
			
			header('HTTP/1.1 400 Session Expired');
			exit();
			
		}
	}

	public function OwnerInsuranceCaclulation($ks_insurance_category_id ,$carts)
	{
		$post_data['user_gear_desc_id']  = $carts['user_gear_desc_id'];
		$app_user_id = $carts['renter_app_user_id'];
		$post_data['insurance_type']  = $ks_insurance_category_id ; 
		$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
		$insurance_type = $query->row();
		$query =  $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=> $carts['app_user_id']) ) ; 
		$ks_users = $query->row();	
		
		if ($ks_users->owner_insurance == 'N') {
			$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = ' User OwnerInsurance is not allowed.';
			return  $json_response = json_encode($response);
		}else{
				$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$carts['renter_app_user_id'] , 'user_gear_desc_id'=>$carts['user_gear_desc_id'], 'order_id ='=>''));
			 	$Cart_details = $query1->row();

			 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$carts['user_gear_desc_id']));
			 	$gear_details = $query1->row();
			 	$insurance_amount =  ($gear_details->replacement_value_aud_inc_gst*$ks_users->owner_insurance_percentage)/100 ;
			 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$Cart_details->create_user , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
			 	$rent_master_details = $query2->row();
			 		
			 	//$Cart_details->other_charges =  ($Cart_details->total_rent_amount_ex_gst*  $insurance_type->percent)/100 ;
			 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit; 

				$Cart_details->insurance_amount = number_format((float)($insurance_amount) , 2, '.', '');  
				$Cart_details->insurance_fee =  number_format((float)($insurance_amount) , 2, '.', '');
			 	$gear_insurance_value = $Cart_details->insurance_amount;	
			 	$rent_master_details->other_charges	 =  '0' ;
			 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
			 	$Cart_details->ks_insurance_category_type_id = $ks_insurance_category_id ; 
			 	$Cart_details->insurance_tier_id =  '0' ; 
			 	$Cart_details->other_charges =  '0' ; 
			 	$Cart_details->security_deposit =  '0' ; 
			 	$Cart_details->deposite_status =  'NONE' ; 
			 		
			 
			 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
			 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
			 	 $Cart_details = 	 $this->home_model->getUserCart1($carts['renter_app_user_id']);
				if (count($Cart_details)) {
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
							$app_user_ids = '' ;
							foreach ($app_user_id_array as $app_user) {
								$app_user_ids.= "'". $app_user."',";
							}

								$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
								$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
					
				 		}
				 			$insurance_type->amount = $gear_insurance_value  ;
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
				}

		}

	}

	public function  RenterInsuranceCaclulation($ks_insurance_category_id ,$carts)
	{
		$post_data['user_gear_desc_id']  = $carts['user_gear_desc_id'];
		$app_user_id = $carts['renter_app_user_id'];
		$post_data['insurance_type']  = $ks_insurance_category_id ; 
		$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
		$insurance_type = $query->row();

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
				 	 	$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
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
				 	 	$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
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
						 	
						}
						
				}
	}

	public function SecurityDepsoiteCaclulation($ks_insurance_category_id ,$carts)
	{
		 $post_data['user_gear_desc_id']  = $carts['user_gear_desc_id'];
		 $app_user_id = $carts['renter_app_user_id'];
		 $post_data['insurance_type']  = $ks_insurance_category_id ; 

			$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
			$insurance_type = $query->row();
			$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$app_user_id , 'user_gear_desc_id'=>$post_data['user_gear_desc_id']));
			 		$Cart_details = $query1->row();
					 $query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
			 		 $rent_master_details = $query2->row();
			 		 $gaer_data = $this->home_model->geratDetails($post_data['user_gear_desc_id']);
					// print_r($gaer_data);
				 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount ; 
				 	$Cart_details->insurance_amount =  $gaer_data->security_deposite_inc_gst; 
				 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 

				 	$Cart_details->insurance_tier_id =  '0'  ; 
				 	$Cart_details->other_charges =  '0' ; 
				 	$Cart_details->security_deposit =   $gaer_data->security_deposite_inc_gst ; 
			 		$Cart_details->deposite_status =  'MARK STORED' ;
			 		// echo "<pre>";
			 		// print_r($gaer_data->security_deposite_inc_gst);
			 		
				 	$insurance_type->amount = $gaer_data->security_deposite_inc_gst; 
				 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
				 	// echo $this->db->last_query();die;
				 	if (!empty($rent_master_details)) {
				 		$rent_master_details->other_charges ='' ;
				 		$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
				 	}
				 	
					 $Cart_details = 	 $this->home_model->getUserCart($app_user_id);
				if (count($Cart_details)) {
					// print_r($Cart_details);
					 $total_rent_amount_ex_gst = '0';
					 $gst_amount = '0';
					 $other_charges = '0';
					 $total_rent_amount = '0';
					 $security_deposit = '0';
					 $i= 0 ;
				 	 foreach ($Cart_details as  $value) {
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


			 	}	
	}

	public function kitshareInsuranceCalculation($ks_insurance_category_id ,$carts)
	{
	
		$query =  $this->common_model->GetAllWhere('ks_settings','');
		$settings =  $query->row();


		if ($carts['replacement_value_aud_ex_gst'] > $settings->max_replacement_value) {
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = ' The Replacement value for the total cart must not exceed  '.$settings->max_replacement_value .' ex GST';
				$json_response = json_encode($response);
				// echo $json_response;
							
		}else{
				$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $ks_insurance_category_id) ) ; 
				$insurance_type = $query->row();

			
			 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$carts['renter_app_user_id'] , 'user_gear_desc_id'=>$carts['user_gear_desc_id'], 'order_id ='=>''));
			 	$Cart_details = $query1->row();
			 	$date_from = strtotime($Cart_details->gear_rent_request_from_date);
			 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
			 	$diff = abs($date_to - $date_from);
				 $date_array =  $this->getDateList($date_from,$date_to);
				
				$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$carts['user_gear_desc_id']));
			 	$gear_details = $query1->row();
			 	$query =  $this->common_model->GetAllWhere('ks_settings','');
				$settings =  $query->row();
				
				if($gear_details->replacement_value_aud_ex_gst > $settings->max_replacement_value){
							$app_user_id = '';
							$response['status'] = 401;
							$response['status_message'] = ' The Replacement value for the total cart must not exceed  '.$settings->max_replacement_value .' ex GST';
							$json_response = json_encode($response);
							// echo $json_response;
							// exit();
				}		
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
				 
			 		$where_clause1 = array(
			 							'initial_value <' => $Cart_details->total_rent_amount_ex_gst,
			 							'end_value > ' => $Cart_details->total_rent_amount_ex_gst,
			 							'status'=> '0',
			 							'is_deleted' => '0',
			 							'ks_insurance_category_type_id'=> $ks_insurance_category_id
				 				  );
				$query =  $this->common_model->GetAllWhere(' ks_insurance_tiers	' , $where_clause1) ; 
				$insurance_tier_type = $query->row();
				
			 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$Cart_details->create_user , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
			 	$rent_master_details = $query2->row();
			 		
			 	//$Cart_details->other_charges =  ($Cart_details->total_rent_amount_ex_gst*  $insurance_type->percent)/100 ;
			 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit; 

				$Cart_details->insurance_amount = number_format((float)(($insurance_tier_type->tiers_percentage *$insurance_days*$gear_details->per_day_cost_aud_ex_gst)/100) , 2, '.', '');  
				$Cart_details->insurance_fee =  number_format((float)(($insurance_tier_type->tiers_percentage *$insurance_days*$gear_details->per_day_cost_aud_ex_gst)/100) , 2, '.', '');
			 	$gear_insurance_value = $Cart_details->insurance_amount;	
			 	$rent_master_details->other_charges	 =  '0' ;
			 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
			 	$Cart_details->ks_insurance_category_type_id = $ks_insurance_category_id ; 
			 	$Cart_details->insurance_tier_id =  $insurance_tier_type->tiers_id  ; 
			 	$Cart_details->other_charges =  '0' ; 
			 	$Cart_details->security_deposit =  '0' ; 
			 	$Cart_details->deposite_status =  'NONE' ; 
			 		
			 
			 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
			 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
			 	 $Cart_details = 	 $this->home_model->getUserCart($carts['renter_app_user_id']);
				if (count($Cart_details)) {
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
							$app_user_ids = '' ;
							foreach ($app_user_id_array as $app_user) {
								$app_user_ids.= "'". $app_user."',";
							}

								$sql1 =  "SELECT app_user_id,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
								$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
					
				 		}
				 			$insurance_type->amount = $gear_insurance_value  ;
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
				}

		}		 		
	}

	// CHECK Austalian digital Post API  &  Hyperwallet
	public function CheckValidDetails()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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


	/*function userinfo($token){
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
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
		}

		
	}*/
	
	function userinfo($token){
	
		$app_user_id = $this->common_model->fetchTokenDetails($token);
		return $app_user_id;
		
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
				if (!empty($subcateory_data)) {
					# code...
				
						foreach($subcateory_data as $row){

						$subcateory_data[$j]->gear_sub_category_name= htmlspecialchars_decode($row->gear_sub_category_name ,ENT_QUOTES);
						

						$j++;
						}
				}
				$category_data[$i]['type'] = 'category';
				$category_data[$i]['gear_sub_category'] = $subcateory_data;	
				$category_data[$i]['gear_category_name'] = htmlspecialchars_decode($value['gear_category_name'] ,ENT_QUOTES);	
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
		
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
					$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
					$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>1))	;
					$category_array = $query->result_array();
					$query_sett=   $this->common_model->GetAllWhere('ks_settings', '');	
					$settings = $query_sett->row();
					// if($Cart_details[0]['replacement_value_aud_ex_gst'] > $settings->max_replacement_value){
					// 	$category_array = array();
					// }
					// Renter Insurane
					$where_clause = array(
												'app_user_id'=> $app_user_id ,
												'renter_insurance_provided' => 'Yes',
												'renter_insurance_status'=> '1' ,
												'is_visiible'=> 'Y' ,
												'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
												'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

											);
					$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
					$renter_insurance  =  $query->row();	
					
					if (!empty($renter_insurance)) {

						$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>3))	;
						$renter_insurance = $query->row();	
												
						array_push($category_array, $renter_insurance);
					}

					//Owner inurance
					$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
			 		$where_clause = array(
												'app_user_id'=> $Cart_details[0]['app_user_id'] ,
												'owner_insurance_provided' => 'Yes',
												'owner_insurance_status'=> '1' ,
												'is_visiible'=> 'Y' ,
												'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
												'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

											);
					$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
					$owner_insurance  =  $query->row();
					if (!empty($owner_insurance)) {
						$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>8))	;
						
						$owner_insurance = $query->row();							
						array_push($category_array, $owner_insurance);
					}
					
					$insurance_proof = '';
					
					if (!empty($insurance_proof)) {
						$response=array("status"=>200,
								"status_message"=>"success",
								"result"=>$category_array);
						echo json_encode($response);
						exit();
					}else{
						

						$response=array("status"=>200,
								"status_message"=>"success",
								"result"=>$category_array);
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

 	public function checksecuritydeposite()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$category_name = urldecode($post_data['category_name']) ;
		$category_name = htmlspecialchars(urldecode($category_name),ENT_QUOTES);
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
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	} 

	//Edit  Gear  Listing
	
	public function EditListing()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id != '') {
			
			//Transaction begins here
			$this->db->trans_begin();
			
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
					$insert_manufacturers = array(
						'manufacturer_name'=>$post_data['gear_brand_name'],
						'create_date'=>date('Y-m-d'),
						'create_user' =>$app_user_id,
						'is_active'=>'Y',
						);
					$manufacturer_id = $this->common_model->InsertData('ks_manufacturers' ,$insert_manufacturers);


			}
			//Check Gear  Category 
			$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => htmlspecialchars($post_data['gear_category_name'] ,ENT_QUOTES)));
			$category_data = $query->row();
			if(!empty($category_data)){
				 $category_id = $category_data->gear_category_id ;
			}else{ // Add Parent Category
					$category_insert = array(
						'gear_category_name' =>htmlspecialchars($post_data['gear_category_name'],ENT_QUOTES),
						'gear_sub_category_id'=> '0',
						'security_deposit'=> 'N',
						'average_value' =>'00.00',
						'is_active'=>'Y',
						'create_user'=>$app_user_id,
						'create_date'=>date('Y-m-d')
									);
					$category_id = $this->common_model->InsertData('ks_gear_categories',$category_insert);				
			
			}	

			//Check Gear  Sub Category 
			if(!empty($post_data['gear_sub_category_name'])){ // if not empty 
				$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => htmlspecialchars($post_data['gear_sub_category_name'],ENT_QUOTES)));
				$category_data1 = $query->row();
			
				if(!empty($category_data1)){
					$sub_category_id = $category_data1->gear_category_id ;
				}else{ // Add Sub  Category
						$category_insert = array(
							'gear_category_name' =>htmlspecialchars($post_data['gear_sub_category_name'],ENT_QUOTES),
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

			//Check for Gear  Modal 
			$query =$this->common_model->GetAllWhere('ks_models',array('model_name' =>  htmlspecialchars(stripcslashes($post_data['gear_model_name']),ENT_QUOTES) ));
			$modal_data = $query->row();
			if(!empty($modal_data)){ // if modal exist
				 $model_id = $modal_data->model_id ;
			}else{
				
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

		// if (array_key_exists("security_deposit_check",$post_data))
		// {
		//       $security_deposit_check = $post_data['security_deposit_check'] ;
		// }else{
		// 	  $security_deposit_check ='0';
		// }

		
		$row['per_weekend_cost_aud_ex_gst'] =  ($post_data['per_day_cost_aud_ex_gst']*3) ; 
		$row['per_week_cost_aud_ex_gst'] =  ($post_data['per_day_cost_aud_ex_gst']*3) ; 
		$row['per_weekend_cost_aud_inc_gst'] =  $row['per_weekend_cost_aud_ex_gst'] + ($row['per_weekend_cost_aud_ex_gst']*$settings->gst_percent)/100 ;
		$row['per_week_cost_aud_inc_gst'] =  $row['per_week_cost_aud_ex_gst'] + ($row['per_week_cost_aud_ex_gst']*$settings->gst_percent)/100 ;
		$row['gear_name'] =  $post_data['gearName'];
		$slug_name =  $this->alphanumericAndSpace(trim($post_data['gearName']));
		$slug_name = str_replace(' ', "-", $slug_name );		
		
		$row['gear_slug_name'] =  $slug_name.'-'.$post_data['user_gear_desc_id'];
		$row['ks_manufacturers_id'] = $manufacturer_id ;
		$row['ks_category_id'] = $category_id ;
		$row['ks_sub_category_id'] = $sub_category_id ;
		$row['model_id'] = $model_id;
		$row['ks_gear_address_id'] = $address_ids ;
		$row['ks_user_gear_condition'] =  $post_data['gearCondition'];
		$row['serial_number'] = $post_data['serialNumber'];
		$row['gear_description_1'] = $post_data['model_description'];
		$row['security_deposite'] = $post_data['security_deposite'];
		if ($post_data['security_deposite'] != 0  ) {
			$row['security_deposite_inc_gst'] =	$row['security_deposite'] + ($row['security_deposite']*$settings->gst_percent)/100;
			$security_deposit_check ='1';
			$row['security_deposit_check'] =  $security_deposit_check;
		}else{
			$row['security_deposite_inc_gst'] = 0 ;
			$security_deposit_check ='0';
			$row['security_deposit_check'] =  $security_deposit_check;
		}
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
		
		//If the gear is already present in the Search table, then it is deleted
		$this->common_model->Delete('ks_user_gear_search', $post_data['user_gear_desc_id'], 'user_gear_desc_id');
		
		///Again this inserted into the search table
		$search_array = array();
		$search_array = $row;
		$search_array['user_gear_desc_id'] = $post_data['user_gear_desc_id'];
		$search_array['model_name'] = $post_data['gear_model_name'];
		$search_array['manufacturer_name'] = $post_data['gear_brand_name'];
		$search_array['gear_slug_name'] =  $slug_name.'-'.$post_data['user_gear_desc_id'];
		$search_array['gear_category_name'] = htmlspecialchars($post_data['gear_category_name'],ENT_QUOTES);
		$search_array['gear_subcategory_name'] = htmlspecialchars($post_data['gear_sub_category_name'],ENT_QUOTES);
		
		$this->common_model->InsertData('ks_user_gear_search',$search_array);
		
		/////
		
		
		
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
					
					$response['status'] = 200 ;
					$response['status_code']='success';
					$response['status_message'] = 'Gear Updated sucessfully';
					$response['result'] = array(
													'model_id'=>$model_id,
													'user_gear_desc_id'=> $post_data['user_gear_desc_id']
												);
					
			}
			
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
	
	// home page gear list random gear

	public function  homegears()
	{

		$where ='';
		$where .= "a.is_active='Y'AND b.is_active ='Y' AND   a.gear_hide_search_results = 'Y' ";
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
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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
	public function digitalIdVerfication()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){	
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		// print_r($app_user_id);die;
		if ($app_user_id != '') {
			if ($post_data['status'] == 'Y') {

					
					$update_data = array(
											'aus_post_transcation_id'=>$post_data['transaction_id'],
											'aus_post_code'=>$post_data['code'],
											'aus_post_verified'=>'Y',
										);
					$this->common_model->UpdateRecord($update_data , 'ks_users' , 'app_user_id', $app_user_id);
					$data = $this->AustalianDigitalIDAuth($post_data['code']);
					
					if (!empty($data)) {
						
						if (isset($data->name)){
							$data->date = date('Y-m-d') ;

							/*$address = $data->address->street_address;
							$address .= ', '.$data->address->locality;
							$address .= ', '.$data->address->region;
							$address .= ', '.$data->address->postal_code;
							$address .= ' '.$data->address->country;*/
							$address = '';

							$data1 = array(
										'app_user_id' => $app_user_id ,
										'date' =>  date('Y-m-d') ,
										'name'=> $data->name,
										'given_name'=>$data->given_name,	
										'family_name'=>$data->family_name,	
										'middle_name'=>$data->middle_name,	
										'birthdate'=>$data->birthdate,	
										'updated_at'=>$data->updated_at,	

								);
							$this->common_model->InsertData( 'ks_austlian_digitalid_details' ,$data1);
							
							$response['status'] = 200;
							$response['status_message'] = 'DigitalIdVerfication Successfully done. ';
							$json_response = json_encode($response);
							echo $json_response;
							
						}else{
							
							$response['status'] = 400;
							$response['status_message'] = $this->session->userdata('error_digital_id');
							$json_response = json_encode($response);
							echo $json_response;
						}
					}else{
						
						$response['status'] = 400;
						$response['status_message'] = $this->session->userdata('error_digital_id');
						$json_response = json_encode($response);
						echo $json_response;
					}

					
					
					
			}else{
				$response['status'] = 400;
				$response['status_message'] = 'DigitalIdVerfication failed '.$post_data['message'];
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


	public function AustalianDigitalIDAuth($code)
	{
					
			$curl = curl_init();
			
			//DigitalId API link is fetched
			$api_row = $this->common_model->RetriveRecordByWhereRow("ks_settings","");
			$url = $api_row['digitalId_url_backend'];
			$digitalId_clientid = $api_row['digitalId_clientid'];
			$digitalId_secretid = $api_row['digitalId_secretid'];
			$auth = base64_encode($digitalId_clientid.":".$digitalId_secretid);
			$host = $api_row['api_host'];
			
			
			//Sandbox url
			//$url = "https://api.digitalid-sandbox.com/oauth2/token?redirect_uri=https://digitalid-sandbox.com/oauth2/echo";
			//Sandbox authorization header
			//$auth = base64_encode("ctid4o1mEByVe7orZMcoxdOKph:3fed6d043b286e812553c164a7009704");
            //$auth = base64_encode("ctid7EbZNsujVgyvv08YA7W7FV:f1e49580d624e6e4ab0cdf18b6cad369");
			//$host = "api.digitalid-sandbox.com"; 
			
			
			//Production url
			//$url = "https://api.digitalid.com/oauth2/token?redirect_uri=https://digitalid.com/oauth2/echo";			
			//Authorization header Production
			//$auth = "Y3RpZDZGNGVHbko4TDFjU0d6dUlaRE42UXk6ODk3ZGQ3YzczYmNiZjI5MjZlMjI5ZjRkYWZhN2M0NDk=";
			//$host = "api.digitalid.com";
			
			

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $url."&grant_type=authorization_code&code=".$code,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_HTTPHEADER => array(
			   
			    "Authorization: Basic ".$auth,
			    "Content-Type: application/x-www-form-urlencoded",
			    "Host: ".$host,
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  
			  /*echo "cURL Error #:" . $err;
			  exit();*/
			  $this->session->set_userdata('error_digital_id',$err);
			  $data = "";
			  return $data;
			  
				
			} else {
			 	$response =  $response;
  			 	$response =  json_decode($response,true) ;
  			 	// echo $code.
  			 	// echo "<pre>";
  			 // print_r($response); die;
  			 	if (array_key_exists("access_token",$response)) {
				$data =  $this->GetUserDetailsAustraliDigitalId($response['access_token']);
				
				return $data;
  			 	}
  			 	

			}
	}


	public function GetUserDetailsAustraliDigitalId($access_token)
	{
		
			//DigitalId API link is fetched
			$api_row = $this->common_model->RetriveRecordByWhereRow("ks_settings","");
			$url = $api_row['digitalId_url_userinfo'];
			$host = $api_row['api_host'];
		
			//Sandbox URL
			
			//$url = "https://api.digitalid-sandbox.com/oidc1/userinfo";
			//$host = "api.digitalid-sandbox.com";
			
			//Production URL
			//$url = "https://api.digitalid.com/oidc1/userinfo";
			//$host = "api.digitalid.com";
		
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $url,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			   "Accept: */*",
			    "Authorization: Bearer" .$access_token , 
			    "Cache-Control: no-cache",
			    "Connection: keep-alive",
			    "Host: ".$host,
			    "Postman-Token: 819e6485-9fd0-4f12-b85a-1b3163f4719a,27c7574d-fab7-4adf-854b-80620dc730ff",
			    "User-Agent: PostmanRuntime/7.13.0",
			    "accept-encoding: gzip, deflate",
			    "cache-control: no-cache"
			   
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  //echo "cURL Error #:" . $err;
			  
				$this->session->set_userdata('error_digital_id',$err);
				$response = "";
				return $response;
			  
			} else {
			 	$response =  $response;
  			 	$response =  json_decode($response) ;
  			 	return $response ;
			}
	}

	public function GearPayment()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			 $Cart_details = 	 $this->home_model->getUserCart1($app_user_id);

			 $six_digit_random_number = mt_rand(100000, 999999);
			 $gear_order_id = 'Ks-'.$six_digit_random_number .time();
			 foreach ($Cart_details as $value) {
			 		 	 // print_r($value['app_user_id']);

			 		 	$query =  $this->common_model->GetAllWhere('ks_settings',array() );
						$settings = $query->row();		
						
						// Catrogry List
			 		 	$query =  $this->common_model->GetAllWhere('ks_gear_categories' ,array('gear_category_id'=>$value['ks_category_id']));
			 		 	$category_details = $query->row();

			 		 	// Sub Catrogry List
			 		 	$query =  $this->common_model->GetAllWhere('ks_gear_categories' ,array('gear_category_id'=>$value['ks_sub_category_id']));
			 		 	$sub_category_details = $query->row();

			 		 	//Gear Details 
			 		 	$where = array('user_gear_desc_id'=>$value['user_gear_desc_id']);
			 		 	$query =  $this->common_model->GetAllWhere('ks_user_gear_description' ,$where);
			 		 	$gear_details = $query->row();

			 		 			

			 		 	$query = $this->common_model->GetAllWhere('ks_user_gear_images', $where);	
			 		 	$gear_image_details = $query->result();


			 		 	$gear_details->gear_category_name =  $category_details->gear_category_name;
			 		 	$gear_details->gear_sub_category_name =  $sub_category_details->gear_category_name;
			 		 
			 		 	//Manufaturer  Details
			 		 	$query =  $this->common_model->GetAllWhere('ks_manufacturers' ,array('manufacturer_id'=>$gear_details->ks_manufacturers_id));
			 		 	$manufaturer_details = $query->row();

			 		 	//Model Details
			 		 	$query =  $this->common_model->GetAllWhere('ks_models' ,array('model_id'=>$gear_details->model_id));
			 		 	$model_details = $query->row();
			 		 	if (!empty($model_details)) {
			 		 		$gear_details->model_name =  $model_details->model_name;
			 		 	}else{
			 		 		$gear_details->model_name =  '0';
			 		 	}

			 		 	$query  = $this->common_model->GetAllWhere('ks_users', array('app_user_id'=>$app_user_id));
			 		 	$renter_details = $query->row();
			 		 	// User Details
			 		 	$query =  $this->common_model->GetAllWhere('ks_users',array( 'app_user_id' => $gear_details->app_user_id) );
						$user_details = $query->row();	

			 		 	$gear_details->manufacturer_name =  $manufaturer_details->manufacturer_name;
			 		 	//owner details
			 		 	$gear_details->gst_rate =  $settings->gst_percent;
			 		 	$gear_details->is_registered_for_gst =  $user_details->registered_for_gst;
			 		 	$gear_details->owner_app_username =  $user_details->app_username;
			 		 	$gear_details->owner_app_user_first_name =  $user_details->app_user_first_name;
			 		 	$gear_details->owner_app_user_last_name =  $user_details->app_user_last_name;
			 		 	$gear_details->owner_app_show_business_name =  $user_details->show_business_name;
			 		 	$gear_details->owner_app_bussiness_name =  $user_details->bussiness_name;
			 		 	$gear_details->owner_australian_business_number =  $user_details->australian_business_number;
			 		 	
			 		 	//renter details
			 		 	
			 		 	$gear_details->renter_is_registered_for_gst =  $renter_details->registered_for_gst;
			 		 	$gear_details->renter_app_username =  $renter_details->app_username;
			 		 	$gear_details->renter_app_user_first_name =  $renter_details->app_user_first_name;
			 		 	$gear_details->renter_app_user_last_name =  $renter_details->app_user_last_name;
			 		 	$gear_details->renter_app_show_business_name =  $renter_details->show_business_name;
			 		 	$gear_details->renter_app_bussiness_name =  $renter_details->bussiness_name;
			 		 	$gear_details->renter_australian_business_number =  $renter_details->australian_business_number;
			 		 	$gear_details->order_id =  $gear_order_id;

			 		  	$order_user_gear_desc_id =	$this->common_model->InsertData('ks_user_gear_order_description',$gear_details);
						
			 		 	if (!empty($gear_details->ks_gear_address_id)) {
								
								// if(strstr($gear_details->ks_gear_address_id,","))
			 		 	// 			$gear_address_array =  explode(',', $gear_details->ks_gear_address_id);	
								// else
									$gear_address_array[0] = $Cart_details[0]['user_address_id'];
									$query = $this->common_model->GetAllWhere('ks_user_address' ,array('app_user_id'=>$Cart_details[0]['renter_app_user_id'] , 'default_address'=>'1'));		
		 		 					$address_detail = $query->row();
		 		 	
		 		 	
						 		 	if (!empty($address_detail)) {
						 		 		$gear_address_array[1] = $address_detail->user_address_id;
						 		 	}
									
						 		 	// // print_r($address_detail);
						 		 	// print_r($gear_address_array);die;
						 		 	// die;
								// print_r($Cart_details[0]['user_address_id']);die;	
			 		 			foreach ($gear_address_array as  $gear_address) {
			 		 				$query = $this->common_model->GetAllWhere('ks_user_address' ,array('user_address_id'=>$gear_address));		
			 		 				$address_detail = $query->row();

			 		 				if (!empty($address_detail)) {
			 		 						$query = $this->common_model->GetAllWhere('ks_states' ,array('ks_state_id'=>$address_detail->ks_state_id));		
					 		 				$state_detail = $query->row();
											$query = $this->common_model->GetAllWhere('ks_suburbs' ,array('ks_suburb_id'=>$address_detail->ks_suburb_id));		
					 		 				$suburbs_details = $query->row();

					 		 				$query = $this->common_model->GetAllWhere('ks_countries' ,array('ks_country_id'=>$address_detail->ks_country_id));		
					 		 				$countrie_details = $query->row();		 		 				
					 		 				
					 		 				$address_detail->ks_state_name = $state_detail->ks_state_name;
					 		 				$address_detail->suburb_name = $suburbs_details->suburb_name;
					 		 				$address_detail->ks_country_name = $countrie_details->ks_country_name;
					 		 				$address_detail->update_user = '0';
					 		 				$address_detail->update_date = date('Y-m-d');
					 		 				$address_detail->create_user = $app_user_id;
					 		 				$address_detail->create_date = date('Y-m-d');
					 		 				$address_detail->order_id = $gear_order_id;
					 		 				$address_detail->order_user_gear_desc_id = $order_user_gear_desc_id;
					 		 				unset($address_detail->app_user_id);
					 		 				unset($address_detail->business_address);
					 		 				unset($address_detail->appartment_number);
					 		 					$order_user_gear_desc_id1 =	$this->common_model->InsertData('ks_gear_order_location',$address_detail);
			 		 				}
			 		 				
			 		 			}
			 		 	}
			 		 	if (!empty($gear_image_details)) {
			 		 		 foreach ($gear_image_details as  $image) {
			 		 		 	
			 		 		 		$image->order_id = $gear_order_id;
			 		 		 		$image->order_user_gear_desc_id = $order_user_gear_desc_id;
			 		 		 		$order_user_gear_desc_id =	$this->common_model->InsertData('ks_user_gear_order_images',$image);
			 		 		 }
			 		 	}

			 		 	
			 }	
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

			 $insert_cron = array(

			 						'type' => 'Quote',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $gear_order_id,
			 						'status' => 'Quote',
			 					);

			  $this->common_model->InsertData('ks_crone_log' ,$insert_cron);

			  $insert_cron = array(

			 						'type' => 'Payment Stored',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $gear_order_id,
			 						'status' => 'Stored',
			 					);

			  $this->common_model->InsertData('ks_crone_log' ,$insert_cron);
			
			 $this->OwnerMail($app_user_id,$gear_order_id);
			 $this->RenterRequestMail($app_user_id,$gear_order_id);
			  foreach ($Cart_details as $value) {
			 	$update_cart  = array( 
			 							'gear_rent_requested_on'=> date('Y-m-d H:i:m'),
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
	public function GearPayment2()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
		if ($app_user_id != '') {
			$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
			
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

			$insert_cron = array(

			 						'type' => 'Deposite Stored',
			 						'date_time' => date('Y-m-d H:i:m'),
			 						'order_id' => $gear_order_id,
			 						'status' => 'Deposite Stored',
			 					);

			  $this->common_model->InsertData('ks_crone_log' ,$insert_cron);
			$response['status'] = 200;
			$response['status_message'] = 'Payment Made Successfully done';
			$response['result'] =$gear_order_id;
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
	}

	public function OwnerMail($app_user_id,$order_id)
	{
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		$Cart_details = 	 $this->home_model->getUserCart1($app_user_id,$order_id);
		if ($Cart_details[0]['owner_show_business_name']  =='Y') {
			$Cart_details[0]['app_user_first_name'] =$Cart_details[0]['owner_bussiness_name'] 	;
			$Cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($Cart_details[0]['renter_show_business_name']  =='Y') {
			$Cart_details[0]['renter_lastname'] =$Cart_details[0]['renter_bussiness_name'] 	;
			$Cart_details[0]['renter_firstname'] ='' 	;
		}
		
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
						<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
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
		 		$owner_insurance_amount  =  '0' ;
		 		$total_rent_amount_ex_gst1 = '0';
				   foreach ($Cart_details as $line){
				    $str .= '<tr>
				      <td style="padding:5px 0px; border-bottom:1px solid #ddd">'.$line['gear_name'].'</td>
				      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd">$'.number_format((float)$line['replacement_value_aud_inc_gst'], 2, '.', '').'</td>
				      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.$line['gear_total_rent_request_days'].'</td>
				      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">$'.number_format((float)$line['per_day_cost_aud_ex_gst'], 2, '.', '').'</td>
				      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd;  text-align:right">$'.number_format((float)$line['total_rent_amount_ex_gst'], 2, '.', '').'</td>
				    </tr>';
					$total_rent_amount_ex_gst  += $line['total_rent_amount_ex_gst'] ; 
					$total_rent_amount_ex_gst1  += $line['total_rent_amount_ex_gst'] ; 
					$gst_amount  += ($line['total_rent_amount_ex_gst'] -$line['beta_discount'] +  $line['insurance_fee'] + $line['community_fee'] + number_format((float)($line['owner_insurance_amount']), 2, '.', ''))*10/100;; ; 
					$other_charges  += $line['other_charges'] ; 
					$owner_insurance_amount += number_format((float)( $line['owner_insurance_amount']), 2, '.', '') ;
					$total_rent_amount  += $line['total_rent_amount_ex_gst'] -$line['beta_discount'] + $line['insurance_fee'] + $line['community_fee'] + number_format((float)( $line['owner_insurance_amount']), 2, '.', '') ; 
        			$total_rent_amount2  += $line['total_rent_amount_ex_gst'] -$line['beta_discount'] + $line['insurance_fee'] + $line['community_fee'] + number_format((float)( $line['owner_insurance_amount']), 2, '.', '') + $gst_amount  ; 
					// $total_rent_amount  += $value['total_rent_amount'] ; 
					$security_deposit +=   $line['security_deposit'];
					$beta_discount +=   $line['beta_discount'];
					$insurance_fee +=   $line['insurance_fee'];
					$community_fee +=   $line['community_fee'];

				   } 
				    
			$mail_content .=$str ; 	   
			$mail_content .='
				    <tr>
				      <td class="thick-line" colspan="4" style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Subtotal</strong></td>
				      <td class="thick-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right" colspan="4"  style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Discount (15%)</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">- ($'.number_format((float)$beta_discount, 2, '.', '').')</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right "  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Insurance Fee</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$insurance_fee, 2, '.', '').'</td>
				    </tr>';
				    if ($owner_insurance_amount > 0 ) {
				    	$mail_content .= '<tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Owner Insurance Fee</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$owner_insurance_amount, 2, '.', '').'</td>
				    </tr>';		
				    		
				    }	

				    $mail_content.= '<tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Community Fee</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$community_fee, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL ex GST</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL GST 10%</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$gst_amount, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"  style="border-top:2px solid #000; padding-top: 10px; font-size:20px; font-weight:bolder; text-align:right"><strong>TOTAL AUD</strong></td>
				      <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder; text-align:right">$'.number_format((float)$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount +$gst_amount, 2, '.', '').'</td>
				    </tr>
				  </tbody>
				</table>
				<table width="940" border="0" style="margin:0 auto;padding: 0px 10px;">
				  <tr>';
				    if($Cart_details[0]["registered_for_gst"] == "Y") {
                       $subtotal =  ((($total_rent_amount_ex_gst *85)/100)+ $owner_insurance_amount) *1.1 ;
                       $total = $subtotal  ;
                       $owner_amount =  number_format((float) $total, 2, '.', '');
                  	}else{  
                       $owner_amount =  number_format((float) ((($total_rent_amount_ex_gst )*85)/100)+ $owner_insurance_amount, 2, '.', '') ; 
               		 }
				 $mail_content .='
				    <td style="font-size:18px;">
				      <p>Based on a price of $'.number_format((float)$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount +$gst_amount, 2, '.', '').' with the associated costs, your estimated payment for this booking is $'.number_format((float)$owner_amount , 2, '.', '').'</p>
				      </td>
				  </tr>
				  <tr>
				    <td>
				     <a href="'.$web_url.'order-summary/'.$order_id.'/owner"> <button  style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">ACCEPT</button> </a>
				     <a href="'.$web_url.'order-summary/'.$order_id.'/owner" ><button style="background-color:#ff0000; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">DECLINE</button></a>
				    </td>
				  </tr>
				</table>
				<table width="940" style="  margin: 0px auto; background-color:#ddd;padding: 5px 0px; text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
					</tr>
				</table>
			</div>
			</body>
			</html>
				';
		
		$mail_body = $mail_content;

		$to= $Cart_details[0]['primary_email_address'];

		$subject = "A renter has made a request";		

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
		
		$insert_array = array("type"=>"Renter request email",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Renter request email");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
	
	}
	public function  RenterRequestMail($app_user_id='',$order_id)
	{
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);

		if ($Cart_details[0]['owner_show_business_name']  =='Y') {
			$Cart_details[0]['app_user_first_name'] =$Cart_details[0]['owner_bussiness_name'] 	;
			$Cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($Cart_details[0]['renter_show_business_name']  =='Y') {
			$Cart_details[0]['renter_lastname'] =$Cart_details[0]['renter_bussiness_name'] 	;
			$Cart_details[0]['renter_firstname'] ='' 	;
		}
		$mail_content= '<!doctype html>
				<html>
				<head>
				<meta charset="utf-8">
				<title>kitshare</title>
				</head>

				<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">
				<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="margin: 0px auto;background-color:#095cab; padding: 10px 10px;text-align: center;" cellpadding="0" cellspacing="0">
						<tr>
						<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
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
		 		$owner_insurance_amount  =  0 ;
		 		$total_rent_amount_ex_gst1 = 0 ;
		 		$community_fee = '0' ; 
				   foreach ($Cart_details as $line){
				    $str .= '<tr>
				      <td style="padding:5px 0px; border-bottom:1px solid #ddd">'.$line['gear_name'].'</td>
				      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd">$'.number_format((float)$line['replacement_value_aud_inc_gst'], 2, '.', '').'</td>
				      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.$line['gear_total_rent_request_days'].'</td>
				      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">$'.number_format((float)$line['per_day_cost_aud_ex_gst'], 2, '.', '').'</td>
				      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd;  text-align:right">$'.number_format((float)$line['total_rent_amount_ex_gst'], 2, '.', '').'</td>
				    </tr>';
					$total_rent_amount_ex_gst  += $line['total_rent_amount_ex_gst'] ; 
					$total_rent_amount_ex_gst1  += $line['total_rent_amount_ex_gst'] ; 
					$gst_amount  += ($line['total_rent_amount_ex_gst'] -$line['beta_discount'] +  $line['insurance_fee'] + $line['community_fee'] + number_format((float)($line['owner_insurance_amount']), 2, '.', ''))*10/100;; ; 
					$other_charges  += $line['other_charges'] ; 
					$owner_insurance_amount += number_format((float)($line['owner_insurance_amount']), 2, '.', '');
					$total_rent_amount  += $line['total_rent_amount_ex_gst'] -$line['beta_discount'] + $line['insurance_fee'] + $line['community_fee'] + number_format((float)($line['owner_insurance_amount']), 2, '.', '') ; 
        			$total_rent_amount2  += $line['total_rent_amount_ex_gst'] -$line['beta_discount'] + $line['insurance_fee'] + $line['community_fee'] + number_format((float)($line['owner_insurance_amount']), 2, '.', '')  + $gst_amount  ; 
					// $total_rent_amount  += $value['total_rent_amount'] ; 
					$security_deposit +=   $line['security_deposit'];
					$beta_discount +=   $line['beta_discount'];
					$insurance_fee +=   $line['insurance_fee'];
					$community_fee +=   $line['community_fee'];

				   } 
				    
			$mail_content .=$str ; 	   
			$mail_content .='
				    <tr>
				      <td class="thick-line" colspan="4" style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Subtotal</strong></td>
				      <td class="thick-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right" colspan="4"  style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Discount (15%)</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">- ($'.number_format((float)$beta_discount, 2, '.', '').')</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right "  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Insurance Fee</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$insurance_fee, 2, '.', '').'</td>
				    </tr>';
				     if ($owner_insurance_amount > 0 ) {
				    	
				    	$mail_content.='<tr>
				    				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Owner Insurance Fee</strong></td>
				    				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$owner_insurance_amount, 2, '.', '').'</td>
				    				    </tr>';
				    }
				    $mail_content.='<tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Community Fee </strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$community_fee, 2, '.', '').'</td>
				    </tr>
				   
				    <tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL ex GST</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL GST 10%</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$gst_amount, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"  style="border-top:2px solid #000; padding-top: 10px; font-size:20px; font-weight:bolder; text-align:right"><strong>TOTAL AUD</strong></td>
				      <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder; text-align:right">$'.number_format((float)$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount +$gst_amount, 2, '.', '').'</td>
				    </tr>
				  </tbody>
				</table>	


				
				<table width="940" border="0" style="margin:0 auto;padding: 0px 10px;">
				  <tr>
				    <td style="font-size:18px;">
				      <p>Your card will be authorised 48hrs prior and charged on the pickup day.</p>
				      </td>
				  </tr>
				  <tr>
				    <td>
				     <a href="'.$web_url.'order-summary/'.$order_id.'/renter"><button type="submit" style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">View your Rental Order </button></a>
				     
				      </td>
				  </tr>
				</table>
				<table width="940" style="  margin: 0px auto; background-color:#ddd;padding: 5px 0px; text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
					</tr>
				</table>
				</div>
				</body>
				</html>
			';
		 $mail_body = $mail_content;
	
	
		//$to= "singhaniagourav@gmail.com";
		$to= $Cart_details[0]['renter_email'];
		$subject = "Your rental request details";		
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
		
		$insert_array = array("type"=>"Renter request details",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Renter request details");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
	}

	public function AcceptByOwnerGearRequest($order_id='')
	{
		if( ini_get('safe_mode') )
		{
		//don't do anything
		}else
		{
		//change the maximum execution time of
		//a script from 30 seconds to 300 secs
		ini_set('max_execution_time',300);
		ini_set('memory_limit','2048M');
		}
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		
		if($cart_details[0]['is_rent_approved']=="Y"){
			$api_logs  =array(
								'api_name'  =>'AcceptByOwnerGearRequest',
								'type' 		=>'sucesss',
								'status' 	=>'sucesss',
								'order_id' 	=>$order_id,
								'error_message' =>'',
								'date_time' =>date('Y-m-d H:i:m'),
						);
			$this->common_model->InsertData('ks_api_logs',$api_logs);
			
			$response['status'] = 400;
			$response['status_message'] = 'Rent is already accepted by the owner';
			$json_response = json_encode($response);
			echo $json_response;
			exit();
			
		}else{	
			
			try{
				redirect('Gear_listing/Braintreeprocess/'.$order_id);	
			} 
			catch(Error $e) {
				$trace = $e->getTrace();
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine().' called from '.$trace[0]['file'].' on line '.$trace[0]['line'];
				$api_logs  =array(
									'api_name'  =>'AcceptByOwnerGearRequest',
									'type' 		=>'error',
									'status' 	=>'error',
									'order_id' 	=>$order_id,
									'error_message' =>json_encode($e->getMessage()),
									'date_time' =>date('Y-m-d H:i:m'),
							);
				$this->common_model->InsertData('ks_api_logs',$api_logs);
			}
			
		}		
			 
		
	}


	public function Braintreeprocess($order_id)
	{

					$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
					
					$date_order_pickup =  date('Y-m-d 2:00:00',strtotime($cart_details[0]['gear_rent_request_from_date'])) ;
					$date_order_pickup1  = date("Y-m-d", strtotime("-1 days", strtotime($cart_details[0]['gear_rent_request_from_date'])));
					$date_order_pickup3  = date("Y-m-d", strtotime("+2 days", strtotime(date('Y-m-d'))));
					$date_order_pickup2  = date("Y-m-d",  strtotime($cart_details[0]['gear_rent_request_from_date']));
					$todays =  date('Y-m-d') ;
					$date1 = new DateTime($date_order_pickup);
					$date2 = $date1->diff(new DateTime(date('Y-m-d H:i:m')));
					$total_hrs =  ($date2->d*24)+ $date2->h ; 

					if ( $date_order_pickup1 == $todays) {
							
						$query =  $this->common_model->GetAllWhere('ks_users', array('app_user_id'=>$cart_details[0]['create_user']));
						$users_details = $query->row();		
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
						$result = $gateway->paymentMethodNonce()->create($cart_details[0]['braintree_token']);
						
						$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$order_id,'payment_type'=>'Gear Payment'));
						$order_details = $query->row();
						$result = $gateway->transaction()->sale([
														'amount' =>number_format((float) $order_details->transaction_amount , 2, '.', '') ,
														'paymentMethodNonce' => $result->paymentMethodNonce->nonce
													]); 
						// print($result);die;
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
							
							$api_logs  =array(
									'api_name'  =>'Braintreeprocess',
									'type' 		=>'Braintree Payment Error',
									'status' 	=>'error',
									'order_id' 	=>$order_id,
									'error_message' =>json_encode($result->transaction->processorResponseCode),
									'date_time' =>date('Y-m-d H:i:m'),
							);
							$this->common_model->InsertData('ks_api_logs',$api_logs);
							
							$response['status'] = 200;
							$response['status_message'] =  $result->message;
							$json_response = json_encode($response);
							echo $json_response;
							exit();
						}
						
						//Rent approved status is updated
						foreach ($cart_details as  $value) {
							$update_cart  = array( 
													'is_rent_approved'=>'Y',
													'is_rent_rejected'=>'N',
													'order_status'=> '2',
													'order_status_date'=> date('Y-m-d')	
													); 
							$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 		
						 }

						////Log for accept rental
						$insert_cron = array(

							'type' => 'Reservation',
							'date_time' => date('Y-m-d H:i:m'),
							'order_id' => $order_id,
							'status' => 'Reservation',
						);
						
						$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
						
						$update_Data = array(
													
										'transaction_id'=> $result->transaction->id,
										'status' => 'AUTHORISED'
										);
						$this->db->where('user_gear_payment_id', $order_details->user_gear_payment_id);
						$this->db->update('ks_user_gear_payments', $update_Data); 
						
						
						//CROne store data 
						$insert_cron= array('type' => 'Authorize Payment',
							'date_time' => date('Y-m-d H:i:m'),
							'order_id' => $order_id,
							'status' => 'Authorize Payment',
						);

						$this->common_model->InsertData('ks_crone_log' ,$insert_cron);

						$api_logs  =array(
									'api_name'  =>'Braintreeprocess',
									'type' 		=>'Braintree Payment Authorize',
									'status' 	=>'success',
									'order_id' 	=>$order_id,
									'error_message' =>'',
									'date_time' =>date('Y-m-d H:i:m'),
							);
						$this->common_model->InsertData('ks_api_logs',$api_logs);

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


								$insert_cron= array('type' => 'Authorize Depsoite Payment',
									'date_time' => date('Y-m-d H:i:s'),
									'order_id' => $order_id,
									'status' => $result->message,
									);


								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
								
								
								$response['status'] = 200;
								$response['status_message'] =  $result->message;
								$json_response = json_encode($response);
								echo $json_response;
								exit();
							}
							$update_Data = array(
													'transaction_id'=> $result->transaction->id,
													'status' => 'AUTHORISED'
												);
							$this->db->where('user_gear_payment_id', $order_details_deposite->user_gear_payment_id);
							$this->db->update('ks_user_gear_payments', $update_Data); 
							foreach ($cart_details as  $value) {
								$update_cart  = array( 
							 							'deposite_status' => 'AUTHORISED',
														'order_status_date'=> date('Y-m-d')
							 							); 
							 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 		
							 }

							$insert_cron= array('type' => 'Authorize Depsoite Payment',
							'date_time' => date('Y-m-d H:i:m'),
							'order_id' => $order_id,
							'status' => 'Authorize Depsoite Payment',
							);

							$this->common_model->InsertData('ks_crone_log' ,$insert_cron); 
							$api_logs  =array(
									'api_name'  =>'Braintreeprocess',
									'type' 		=>'Authorize Depsoite Payment',
									'status' 	=>'success',
									'order_id' 	=>$order_id,
									'error_message' =>'',
									'date_time' =>date('Y-m-d H:i:m'),
							);
							$this->common_model->InsertData('ks_api_logs',$api_logs);
						}


					}
					else if ( $date_order_pickup3 == $date_order_pickup2) {
							
						$query =  $this->common_model->GetAllWhere('ks_users', array('app_user_id'=>$cart_details[0]['create_user']));
						$users_details = $query->row();		
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
						$result = $gateway->paymentMethodNonce()->create($cart_details[0]['braintree_token']);
						
						$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$order_id,'payment_type'=>'Gear Payment'));
						$order_details = $query->row();
						$result = $gateway->transaction()->sale([
														'amount' =>number_format((float) $order_details->transaction_amount , 2, '.', '') ,
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
								'date_time' => date('Y-m-d H:i:m'),
								'order_id' => $order_id,
								'status' => $result->message,
							);
							$api_logs  =array(
									'api_name'  =>'Braintreeprocess',
									'type' 		=>'Braintree Payment Error',
									'status' 	=>'error',
									'order_id' 	=>$order_id,
									'error_message' =>json_encode($result->transaction->processorResponseCode),
									'date_time' =>date('Y-m-d H:i:m'),
							);
							$this->common_model->InsertData('ks_api_logs',$api_logs);
							
							
							$response['status'] = 200;
							$response['status_message'] =  $result->message;
							$json_response = json_encode($response);
							echo $json_response;
							exit();
						}
						
						foreach ($cart_details as  $value) {
							$update_cart  = array( 
													'is_rent_approved'=>'Y',
													'is_rent_rejected'=>'N',
													'order_status'=> '2',
													'order_status_date'=> date('Y-m-d')	
													); 
							$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 		
						 }

						////Log for accept rental
						$insert_cron = array(

							'type' => 'Reservation',
							'date_time' => date('Y-m-d H:i:m'),
							'order_id' => $order_id,
							'status' => 'Reservation',
						);

						$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
						
						$update_Data = array(
													
											'transaction_id'=> $result->transaction->id,
											'status' => 'AUTHORISED'
										);
						$this->db->where('user_gear_payment_id', $order_details->user_gear_payment_id);
						$this->db->update('ks_user_gear_payments', $update_Data); 
						//CROne store data 
						$insert_cron= array('type' => 'Authorize Payment',
						'date_time' => date('Y-m-d H:i:m'),
						'order_id' => $order_id,
						'status' => 'Authorize Payment',
						);

						$this->common_model->InsertData('ks_crone_log' ,$insert_cron);

						$api_logs  =array(
									'api_name'  =>'Braintreeprocess',
									'type' 		=>'Reservation',
									'status' 	=>'success',
									'order_id' 	=>$order_id,
									'error_message' =>'',
									'date_time' =>date('Y-m-d H:i:m'),
							);
							$this->common_model->InsertData('ks_api_logs',$api_logs);

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
								
								
								$insert_cron= array('type' => 'Authorize Depsoite Payment',
									'date_time' => date('Y-m-d H:i:s'),
									'order_id' => $order_id,
									'status' => $result->message,
									);


								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
								
								
								$response['status'] = 200;
								$response['status_message'] =  $result->message;
								$json_response = json_encode($response);
								echo $json_response;
								exit();
							}
							$update_Data = array(
													'transaction_id'=> $result->transaction->id,
													'status' => 'AUTHORISED'
												);
							$this->db->where('user_gear_payment_id', $order_details_deposite->user_gear_payment_id);
							$this->db->update('ks_user_gear_payments', $update_Data); 
							foreach ($cart_details as  $value) {
								$update_cart  = array( 
							 							'deposite_status' => 'AUTHORISED',
														'order_status_date'=> date('Y-m-d')	
							 						); 
							 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 		
							 }

							$insert_cron= array('type' => 'Authorize Depsoite Payment',
							'date_time' => date('Y-m-d H:i:m'),
							'order_id' => $order_id,
							'status' => 'Authorize Depsoite Payment',
							);

							$this->common_model->InsertData('ks_crone_log' ,$insert_cron); 

							$api_logs  =array(
									'api_name'  =>'Braintreeprocess',
									'type' 		=>'Authorize Depsoite Payment',
									'status' 	=>'success',
									'order_id' 	=>$order_id,
									'error_message' =>'',
									'date_time' =>date('Y-m-d H:i:m'),
							);
							$this->common_model->InsertData('ks_api_logs',$api_logs);
						}


					}
					else if ($date_order_pickup2  == $todays ) {

						//Authorization
						$query =  $this->common_model->GetAllWhere('ks_settings',array() );
						$settings = $query->row();		
						$gateway = new Braintree\Gateway([
      									'environment' => BRAINTREE_ENVIORMENT,
									    'merchantId' => $settings->braintree_merchant_id,
									    'publicKey' => $settings->braintree_public_key,
									    'privateKey' => $settings->braintree_private_key
						]);
						$result = $gateway->paymentMethodNonce()->create($cart_details[0]['braintree_token']);
						print_r($cart_details[0]['braintree_token']);
						print_r($result);
						die;
						$result_payment_nonce =  $result ;
						

						// Authorize the deposite payment
						$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$order_id,'payment_type'=>'Deposite Payment'));
						$order_details_deposite = $query->row();
						if (!empty($order_details_deposite)) {

						 	$result = $gateway->paymentMethodNonce()->create($cart_details[0]['security_deposite_token_braintree']);
							$result = $gateway->transaction()->sale([
							    							'amount' =>number_format((float) $order_details_deposite->transaction_amount , 2, '.', '') ,
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
								
								$insert_cron= array('type' => 'Authorize Depsoite Payment',
									'date_time' => date('Y-m-d H:i:s'),
									'order_id' => $order_id,
									'status' => $result->message,
									);


								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
								$api_logs  =array(
										'api_name'  =>'Braintreeprocess',
										'type' 		=>'Braintree Payment Error',
										'status' 	=>'error',
										'order_id' 	=>$order_id,
										'error_message' =>json_encode($result->transaction->processorResponseCode),
										'date_time' =>date('Y-m-d H:i:m'),
								);
								$this->common_model->InsertData('ks_api_logs',$api_logs);
								
								$response['status'] = 200;
								$response['status_message'] =  $result->message;
								$json_response = json_encode($response);
								echo $json_response;
								exit();
							}
							
							//Authorization END 
								$insert_cron= array('type' => 'Authorize Payment',
								'date_time' => date('Y-m-d H:i:m'),
								'order_id' => $order_id,
								'status' => 'Authorize Payment',
								);


							$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
							
							
							$update_Data = array(
													'transaction_id'=> $result->transaction->id,
													'status' => 'AUTHORISED'
												);
							$this->db->where('user_gear_payment_id', $order_details_deposite->user_gear_payment_id);
							$this->db->update('ks_user_gear_payments', $update_Data); 
							foreach ($cart_details as  $value) {
								$update_cart  = array( 
							 							'deposite_status' => 'AUTHORISED',
														'is_rent_approved'=>'Y',
														'is_rent_rejected'=>'N',
														'order_status'=> '3',
														'order_status_date'=> date('Y-m-d')	
							 						); 
							 	$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 		
							 }

							 $insert_cron= array('type' => 'Authorize Depsoite Payment',
								'date_time' => date('Y-m-d H:i:m'),
								'order_id' => $order_id,
								'status' => 'Authorize Depsoite Payment',
							);

							$this->common_model->InsertData('ks_crone_log' ,$insert_cron); 
						}
						
						
						///Gear Payment is authorized
						$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$order_id,'payment_type'=>'Gear Payment'));
						$order_details = $query->row();
					
							$result = $gateway->transaction()->sale([
							    							'amount' =>number_format((float) $order_details->transaction_amount , 2, '.', '') ,
											    			'paymentMethodNonce' => $result_payment_nonce->paymentMethodNonce->nonce
														]); 
							
							// $result->transaction->processorResponseCode = 2000 ;
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
								
								
								$response['status'] = 200;
								$response['status_message'] =  $result->message;
								$json_response = json_encode($response);
								echo $json_response;
								exit();
							}

							$update_Data = array(
													
										'transaction_id'=> $result->transaction->id,
										'status' => 'AUTHORISED'
									);
							$this->db->where('user_gear_payment_id', $order_details->user_gear_payment_id);
							$this->db->update('ks_user_gear_payments', $update_Data); 

							$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$order_id ,'payment_type'=>'Gear Payment' ,'status'=>'AUTHORISED' ));
							$order_payments = $query->row();
							if (!empty($order_payments)) {
								if ($order_payments->transaction_id  !='') {
									
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

									$result = $gateway->transaction()->submitForSettlement($order_payments->transaction_id,number_format((float) $order_payments->transaction_amount, 2, '.', ''));
									
									if($result->errors){
								
										//if ($result->transaction->processorResponseCode != 4000 ) {
										//$query =  $this->common_model->GetAllWhere('tbl_braintree_error_details',array('error_code'=>$result->transaction->processorResponseCode));
										//$data =  $query->row();
										
										//Record is inserted into the Transaction Error Log table
										$insert_error = array("order_id"=>$order_id,
															  "transaction_id"=>$order_payments->transaction_id,
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
										
										$response['status'] = 200;
										$response['status_message'] =  $result->message;
										$json_response = json_encode($response);
										echo $json_response;
										exit();
									}
									
									$insert_cron= array('type' => 'Received  Payment',
										'date_time' => date('Y-m-d H:i:s'),
										'order_id' => $order_id,
										'status' => 'Received Payment',
									);

									$this->common_model->InsertData('ks_crone_log' ,$insert_cron); 
									
								
									////Log for accept rental
									$insert_cron = array(

									'type' => 'Reservation',
									'date_time' => date('Y-m-d H:i:m'),
									'order_id' => $order_id,
									'status' => 'Reservation',
									);
									
									$this->common_model->InsertData('ks_crone_log' ,$insert_cron);

									$update_Data = array(
														
														'status'=>'RECEIVED'
													);
									$this->db->where('user_gear_payment_id', $order_payments->user_gear_payment_id);
									$this->db->update('ks_user_gear_payments', $update_Data); 

									$api_logs  =array(
											'api_name'  =>'Braintreeprocess',
											'type' 		=>'Authorize Depsoite Payment Received',
											'status' 	=>'success',
											'order_id' 	=>$order_id,
											'error_message' =>'',
											'date_time' =>date('Y-m-d H:i:m'),
									);
									$this->common_model->InsertData('ks_api_logs',$api_logs);			
									
									foreach ($cart_details as  $value) {
								
										$update_cart  = array( 
																'is_rent_approved'=>'Y',
																'order_status' => '3',
																'is_rent_rejected'=>'N',
																'order_status_date'=> date('Y-m-d')	
																); 


										$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 		

									}
									$insert_cron = array(

											'type' => 'Contract',
											'date_time' => date('Y-m-d H:i:m'),
											'order_id' => $order_id,
											'status' => 'Contract',
									);

									$this->common_model->InsertData('ks_crone_log' ,$insert_cron);

								}
							}
					}else{
							
						foreach ($cart_details as  $value) {
							$update_cart  = array( 
													'is_rent_approved'=>'Y',
													'is_rent_rejected'=>'N',
													'order_status'=> '2',
													'order_status_date'=> date('Y-m-d')	
													); 
							$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);						 		
						 }

								//  //Log for accept rental

						$insert_cron = array(

												'type' => 'Reservation',
												'date_time' => date('Y-m-d H:i:m'),
												'order_id' => $order_id,
												'status' => 'Reservation',
											);

						  $this->common_model->InsertData('ks_crone_log' ,$insert_cron);
						
						$api_logs  =array(
											'api_name'  =>'Braintreeprocess',
											'type' 		=>'sucess',
											'status' 	=>'sucess',
											'order_id' 	=>$order_id,
											'error_message' =>'',
											'date_time' =>date('Y-m-d H:i:m'),
									);
						$this->common_model->InsertData('ks_api_logs',$api_logs);				
										
					}
					
			if ($cart_details[0]['owner_show_business_name']  =='Y') {
				$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'] 	;
				$cart_details[0]['app_user_last_name'] ='' 	;
			}
			if ($cart_details[0]['renter_show_business_name']  =='Y') {
				$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'] 	;
				$cart_details[0]['renter_lastname'] ='' 	;
			}
					
						$api_logs  =array(
											'api_name'  =>'Braintreeprocess',
											'type' 		=>'sucess',
											'status' 	=>'sucess',
											'order_id' 	=>$order_id,
											'error_message' =>'',
											'date_time' =>date('Y-m-d H:i:m'),
									);
						$this->common_model->InsertData('ks_api_logs',$api_logs);		
			
			try{
				$this->AcceptOwnerMail($cart_details);		
				$this->AcceptRenterMail($order_id);
				$api_logs  =array(
							'api_name'  =>'Accept Message',
							'type' 		=>'success',
							'status' 	=>'success',
							'order_id' 	=>"",
							'error_message' =>"",
							'date_time' =>date('Y-m-d H:i:m'),
					);
				$this->common_model->InsertData('ks_api_logs',$api_logs);
				$response['status'] = 200;
				$response['status_message'] = ' Owner Accepted Gear Successfully';
				$json_response = json_encode($response);
				echo $json_response;
		
			} 
			catch(Error $e) {

				$trace = $e->getTrace();
				echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine().' called from '.$trace[0]['file'].' on line '.$trace[0]['line'];
				$api_logs  =array(
									'api_name'  =>'Braintreeprocess',
									'type' 		=>'error',
									'status' 	=>'error',
									'order_id' 	=>$order_id,
									'error_message' =>json_encode($e->getMessage()),
									'date_time' =>date('Y-m-d H:i:m'),
							);
				$this->common_model->InsertData('ks_api_logs',$api_logs);
			}
			
			/*$response['status'] = 200;
			$response['status_message'] = ' Owner Accepted Gear Successfully';
			$json_response = json_encode($response);
			echo $json_response;
			exit();*/
			// redirect('Gear_listing/accept_message');
			
			
	}
	
	public function accept_message(){
		$api_logs  =array(
							'api_name'  =>'Accept Message',
							'type' 		=>'success',
							'status' 	=>'success',
							'order_id' 	=>"",
							'error_message' =>"",
							'date_time' =>date('Y-m-d H:i:m'),
					);
		$this->common_model->InsertData('ks_api_logs',$api_logs);
		$response['status'] = 200;
		$response['status_message'] = ' Owner Accepted Gear Successfully';
		$json_response = json_encode($response);
		echo $json_response;
		
	}
	
	
	
	
	public function AcceptRenterMail($order_id)
	{
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		
		$data = $this->home_model->getUserCartbyOrderId($order_id);
		if (empty($data[0]['user_profile_picture_link'])) {
			$data[0]['user_profile_picture_link'] = BASE_URL."server/assets/images/profile.png"; 
		}
		
			$this->db->select('*');
			$this->db->from('ks_gear_order_location');
			$this->db->where('order_id',$data[0]['order_id']);				
			$query = $this->db->get();
			$address  =$query->row();	
			
			
			$pdf_url =  $this->generateInvoiceMail($data[0]['order_id']); 
			
			//Total rent amount paid
			$this->db->select('transaction_amount');
			$this->db->from('ks_user_gear_payments');
			$this->db->where('gear_order_id',$data[0]['order_id']);
			$query = $this->db->get();
			$rent_amount_paid =$query->row();	
		
			$mail_content = '<!doctype html>
						<html>
						<head>
						<meta charset="utf-8">
						<title>kitshare</title>
						</head>

						<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">
						<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
						<table width="940" style="margin: 0px auto;background-color:#095cab; padding: 10px 10px;text-align: center;" cellpadding="0" cellspacing="0">
								<tr>
								<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
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

						<table width="940" style="margin:25px auto;padding: 10px 10px" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td width="500"><h2 style="margin:0">Amount</h2>';
									/*$total_rent_amount3 = '0';
									foreach ($data as $line){
										$total_rent_amount3 += $line['total_rent_amount'] ; 
									}*/
									$mail_content.=	'<p style="margin:0px">$'.number_format((float)$rent_amount_paid->transaction_amount, 2, '.', '').'</p>
										<p style="margin:0"><a href="'.$pdf_url.'" style="text-decoration:none;">View Receipt </a></p>
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
				$total_rent_amount_ex_gst1 = '0';
				$gst_amount = '0';
				$other_charges = '0';
				$total_rent_amount = '0';
				$total_rent_amount2 = '0';
				$security_deposit = '0';
				$beta_discount =   '0'; 
				$insurance_fee =   '0';
				$community_fee =   '0';
				$owner_insurance_amount = 0;
				   foreach ($data as $line){
					//print_r($line['gear_name']);			    
				$str .= '<tr>
				      <td style="padding:5px 0px; border-bottom:1px solid #ddd">'.$line['gear_name'].'</td>
				      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd">$'.number_format((float)$line['replacement_value_aud_inc_gst'], 2, '.', '').'</td>
				      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.$line['gear_total_rent_request_days'].'</td>
				      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">$'.number_format((float)$line['total_rent_amount_ex_gst'], 2, '.', '').'</td>
				      
				      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd;  text-align:right">$'.number_format((float)$line['total_rent_amount_ex_gst'], 2, '.', '').'</td>
				    </tr>';
					$total_rent_amount_ex_gst  += $line['total_rent_amount_ex_gst'] ; 
					$total_rent_amount_ex_gst1  += $line['total_rent_amount_ex_gst'] ; 
					$gst_amount  += ($line['total_rent_amount_ex_gst'] -$line['beta_discount'] +  $line['insurance_fee'] + $line['community_fee'] + $line['owner_insurance_amount'])*10/100;; ; 
					$other_charges  += $line['other_charges'] ; 
					$owner_insurance_amount += $line['owner_insurance_amount'];
					$total_rent_amount  += $line['total_rent_amount_ex_gst'] -$line['beta_discount'] + $line['insurance_fee'] + $line['community_fee'] + $line['owner_insurance_amount'] ; 
        			$total_rent_amount2  += $line['total_rent_amount_ex_gst'] -$line['beta_discount'] + $line['insurance_fee'] + $line['community_fee'] + $line['owner_insurance_amount']  + $gst_amount  ; 
					// $total_rent_amount  += $value['total_rent_amount'] ; 
					$security_deposit +=   $line['security_deposit'];
					$beta_discount +=   $line['beta_discount'];
					$insurance_fee +=   $line['insurance_fee'];
					$community_fee +=   $line['community_fee'];
				   } 
				   $total_rent_amount_ex_gst =  $total_rent_amount_ex_gst - $beta_discount +$insurance_fee + $community_fee + $owner_insurance_amount; 
				   $total_rent_amount2 = $total_rent_amount + $gst_amount ;
				$mail_content .=$str ; 	   
				$mail_content .=' <tr>
				      <td class="thick-line" colspan="4" style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Subtotal</strong></td>
				      <td class="thick-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$total_rent_amount_ex_gst1, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right" colspan="4"  style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Discount (15%)</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">- ($'.number_format((float)$beta_discount, 2, '.', '').')</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right "  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Insurance Fee</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$insurance_fee, 2, '.', '').'</td>
				    </tr>';

				    if ($owner_insurance_amount > 0 ) {
				    
				    $mail_content .= '<tr>
									      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Owner Insurance Fee</strong></td>
									      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$owner_insurance_amount, 2, '.', '').'</td>
									  </tr>';

					}				  
				$mail_content .= '<tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Community Fee </strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$community_fee, 2, '.', '').'</td>
				    </tr>
				    
				    <tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL ex GST</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL GST 10%</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$gst_amount, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"  style="border-top:2px solid #000; padding-top: 10px; font-size:20px; font-weight:bolder; text-align:right"><strong>TOTAL AUD</strong></td>
				      <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder; text-align:right">$'.number_format((float)$total_rent_amount2, 2, '.', '').'</td>
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
						    <a href="'.$web_url.'order-summary/'.$order_id.'/renter"><button type="submit" style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">View your Rental Order </button></a>
						    <a href="'.$pdf_url.'"><button type="submit" style="background-color:#ff0000; color:#fff; padding:10px 12px; border:none; margin-bottom:20px"">View Receipt</button></a>
						      </td>
						  </tr>
						</table>
						<table width="940" style="  margin: 0px auto; background-color:#ddd;padding: 5px 0px; text-align: center;" cellpadding="0" cellspacing="0">
							<tr>
							<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
							</tr>
						</table>
						</div>
						</body>
						</html>
					';
					//echo $mail_content;die;
		//$sender_mail= 'noreply@inferasolz.com';
		
		   $mail_body = $mail_content;
		 // die;
		//$Invocie = $this->SendInvoiceEmail($data[0]['order_id']);
		
		$Invocie = file_get_contents($pdf_url);
		
		
		$to= $data[0]['renter_email'];
		$subject = "Your rental request has been accepted";		
		$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>" Kitshare Australia",
											),
										"To"=>array(
												array("Email"=>$to,
												"Name"=>"",
												),
											),
										"Subject"=> $subject,
				                        "TextPart"=> "",
				                        "HTMLPart"=> $mail_body,
				                        "Attachments"=> array(
							               array(
							                 "Filename"=> $data[0]['order_id'].".pdf",
							                    "ContentType"=> "application/pdf",
							                    "Base64Content"=> base64_encode($Invocie)	  
							               )),
									),
								)
							);	
		$this->common_model->sendMail($mail_data);

		$insert_array = array("type"=>"Renter request accepted",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Renter request accepted");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
		$api_logs  =array(
											'api_name'  =>'AcceptRenterMail',
											'type' 		=>'sucess',
											'status' 	=>'sucess',
											'order_id' 	=>$order_id,
											'error_message' =>'',
											'date_time' =>date('Y-m-d H:i:m'),
									);
						$this->common_model->InsertData('ks_api_logs',$api_logs);		

	}

	public function AcceptOwnerMail($data='')
	{
		
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		if(empty($data[0]['renter_image'])){		
			$data[0]['renter_image'] = BASE_URL."server/assets/images/profile.png"; 		
		}
		$this->db->select('*');
		$this->db->from('ks_gear_order_location ');
		$this->db->where('order_id',$data[0]['order_id']);
			
			
		$query = $this->db->get();
		$address  =$query->row();	

		$mail_content = '<!doctype html>
							<html>
							<head>
							<meta charset="utf-8">
							<title>kitshare</title>
							</head>

							<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">
							<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
								<table width="940" style="margin: 0px auto;background-color:#095cab; padding: 10px 10px;text-align: center;" cellpadding="0" cellspacing="0">
										<tr>
										<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
										</tr>
								</table>
							<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0px 10px" cellpadding="0" cellspacing="0">
								<tr>
								<td style="font-size:20px; padding-bottom:10px;">Hi '.$data[0]['app_user_first_name'].'  '. $data[0]['app_user_last_name'].'</td>
								</tr>
								<tr>
								<td style="font-size:18px; padding-bottom:10px;">Congratulations! You have accepted the reservation request from  '.$data[0]['renter_firstname'].'  '. $data[0]['renter_lastname'].'</td>
								</tr>
								<tr>
								<td>
								<img src="'.$data[0]['renter_image'].'" height="70px" width="70px">
								<p style="font-size:18px; padding-bottom:10px; margin:0">'.$data[0]['renter_firstname'].'  '.$data[0]['renter_lastname'].'</p>
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
				$total_rent_amount_ex_gst1 = '0';
				$gst_amount = '0';
				$other_charges = '0';
				$total_rent_amount = '0';
				$total_rent_amount2 = '0';
				$security_deposit = '0';
				$beta_discount =   '0';		
				$insurance_fee =   '0'; 		
				$community_fee =   '0';
				$owner_insurance_amount = 0;
				   foreach ($data as $line){
					//print_r($line['gear_name']);			    
						$str .= '<tr>
				      <td style="padding:5px 0px; border-bottom:1px solid #ddd">'.$line['gear_name'].'</td>
				      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd">$'.number_format((float)$line['replacement_value_aud_inc_gst'], 2, '.', '').'</td>
				      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.$line['gear_total_rent_request_days'].'</td>
				      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">$'.number_format((float)$line['total_rent_amount_ex_gst'], 2, '.', '').'</td>
				      
				      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd;  text-align:right">$'.number_format((float)$line['total_rent_amount_ex_gst'], 2, '.', '').'</td>
				    </tr>';
					$total_rent_amount_ex_gst  += $line['total_rent_amount_ex_gst'] ; 
					$total_rent_amount_ex_gst1  += $line['total_rent_amount_ex_gst'] ; 
					$gst_amount  += ($line['total_rent_amount_ex_gst'] -$line['beta_discount'] +  $line['insurance_fee'] + $line['community_fee'] + number_format((float)($line['owner_insurance_amount']), 2, '.', '') )*10/100; 
					$other_charges  += $line['other_charges'] ; 
					$owner_insurance_amount += number_format((float)($line['owner_insurance_amount']), 2, '.', '');
					$total_rent_amount  += $line['total_rent_amount_ex_gst'] -$line['beta_discount'] + $line['insurance_fee'] + $line['community_fee'] + number_format((float)($line['owner_insurance_amount']), 2, '.', '') ; 
        			$total_rent_amount2  += $line['total_rent_amount_ex_gst'] -$line['beta_discount'] + $line['insurance_fee'] + $line['community_fee'] + number_format((float)($line['owner_insurance_amount']), 2, '.', '') + $gst_amount  ; 
					// $total_rent_amount  += $value['total_rent_amount'] ; 
					$security_deposit +=   $line['security_deposit'];
					$beta_discount +=   $line['beta_discount'];
					$insurance_fee +=   $line['insurance_fee'];
					$community_fee +=   $line['community_fee'];
				   } 
				 	$total_rent_amount_ex_gst = $total_rent_amount_ex_gst - $beta_discount +$insurance_fee + $community_fee + $owner_insurance_amount;
				 	$total_rent_amount2 = $gst_amount + $total_rent_amount ; 
					$mail_content .=$str ; 	   
					$mail_content .=' <tr>
						      <td class="thick-line" colspan="4" style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Subtotal</strong></td>
						      <td class="thick-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$total_rent_amount_ex_gst1, 2, '.', '').'</td>
						    </tr>
						    <tr>
						      <td class="no-line text-right" colspan="4"  style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Discount (15%)</strong></td>
						      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">- ($'.number_format((float)$beta_discount, 2, '.', '').')</td>
						    </tr>
						    <tr>
						      <td class="no-line text-right "  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Insurance Fee</strong></td>
						      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$insurance_fee, 2, '.', '').'</td>
						    </tr>';
						    if (!empty($owner_insurance_amount > 0 )) {
						    	
						    
							    $mail_content.= '<tr>
											      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Owner Insurance Fee</strong></td>
											      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$owner_insurance_amount, 2, '.', '').'</td>
											    </tr>';
							}
						    $mail_content.= '<tr>
						      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Community Fee</strong></td>
						      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$community_fee, 2, '.', '').'</td>
						    </tr>
						   
						    <tr>
						      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL ex GST</strong></td>
						      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
						    </tr>
						    <tr>
						      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL GST 10%</strong></td>
						      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">$'.number_format((float)$gst_amount, 2, '.', '').'</td>
						    </tr>
						    <tr>
						      <td class="no-line text-right"  colspan="4"  style="border-top:2px solid #000; padding-top: 10px; font-size:20px; font-weight:bolder; text-align:right"><strong>TOTAL AUD</strong></td>
						      <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder; text-align:right">$'.number_format((float)$total_rent_amount2, 2, '.', '').'</td>
						    </tr>
				   				
							  </tbody>
							</table>
							<table width="940" style="margin:25px auto; padding: 0px 10px" cellpadding="0" cellspacing="0">
							<tbody><tr>
							<td width="500"><h2 style="margin:0">Amount</h2>
							<p style="margin:0px">$'.number_format((float)$total_rent_amount2, 2, '.', '').'</p>

							</td>
							<td><h2 style="margin:0">Reservation Code</h2>
							<p style="margin:0">'.$this->uri->segment(3).'</p>
							</td>
							</tr>
							</tbody></table>


							<table width="940" border="0" style="margin:0 auto; padding: 0px 10px">
							  <tr>
							    <td style="font-size:18px;">
							      <p>We highly discourage Owners from cancelling  - you should never accept a rental unless you are certain you can fulfill it. However, if you are an owner and need to cancel because of an unforeseen event, email us at <a href="#" style="text-decoration:none">hello@kitshare.com.au.</a> When you cancel a rental, you&#39;ll get an automated review on your profile saying that you cancelled. After the first time you cancel, there will be a $50 penalty fee per cancellation. Any applicable cancellation fees are automatically deducted from your next rental payout.</p>
							      
							      </td>
							  </tr>
							  <tr>
							    <td>
								<a href="'.$web_url.'order-summary/'.$data[0]['order_id'].'/owner"><button type="submit" style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">View your Rental Order </button></a>
							     
							      </td>
							  </tr>
							</table>
							<table width="940" style="  margin: 0px auto; background-color:#ddd;padding: 5px 0px; text-align: center;" cellpadding="0" cellspacing="0">
								<tr>
								<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
								</tr>
							</table>
							</div>
							</body>
							</html>
							';	
			
		   $mail_body = $mail_content;
		  
		$to= $data[0]['primary_email_address'];
		//$to = "sanidha@gmail.com";
		$subject = "You have accepted a Rental request";
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

		$insert_array = array("type"=>"Owner request acceptance email",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$data[0]['order_id'],
							  "status"=>"Owner request acceptance email");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
		$api_logs  =array(
											'api_name'  =>'AcceptOwnerMail',
											'type' 		=>'sucess',
											'status' 	=>'sucess',
											'order_id' 	=>$data[0]['order_id'],
											'error_message' =>'',
											'date_time' =>date('Y-m-d H:i:m'),
									);
						$this->common_model->InsertData('ks_api_logs',$api_logs);
	}
	public function RejectByOwnerGearRequest($order_id='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		$token 			= $post_data['token'];

		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {

			//Checked whether there is any Security deposit transaction corresponding to this Order
			$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$order_id,'payment_type'=>'Deposite Payment'));
			$order_details = $query->row();
			
			if(!empty($order_details->transaction_id)){
				
					//Void the transaction				
					if (version_compare(PHP_VERSION, '5.4.0', '<')) {
						throw new Braintree_Exception('PHP version >= 5.4.0 required');
					}

						$query =  $this->common_model->GetAllWhere('ks_settings',array());
						$settings = $query->row();		
			
					// Instantiate a Braintree Gateway either like this:					
					$gateway = new Braintree_Gateway([
									'environment' => BRAINTREE_ENVIORMENT,
									'merchantId' => $settings->braintree_merchant_id,
									'publicKey' => $settings->braintree_public_key,
									'privateKey' => $settings->braintree_private_key
					]);
					
					$transaction = $gateway->transaction()->find($order_details->transaction_id);
					
					//print_r($transaction);exit();

					$result = $gateway->transaction()->void($order_details->transaction_id);
					
				if($order_details->gear_order_id >0){
					$update_cart  = array( 
										'status'=>'Void',
										'update_date'=> date('Y-m-d'),
										); 
					$where = array(
									'gear_order_id'=>$order_id,
									'payment_type'=>'Deposite Payment'
									);
					$this->db->where($where);
					$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
					
					$insert_cron= array('type' => 'Security Deposit Transaction Void',
								'date_time' => date('Y-m-d H:i:m'),
								'order_id' => $post_data['order_id'],
								'status' => 'Void',
								);

					$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
				}
			
			}
			
			
			//Checked whether there is any transaction corresponding to this Order
			$query = $this->common_model->GetAllWhere('ks_user_gear_payments',array('gear_order_id'=>$order_id,'payment_type'=>'Gear Payment','status'=>'STORED'));
			$order_details = $query->row();
			
			if(!empty($order_details->transaction_id)){
				
				//Void the transaction
				
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

					$result = $gateway->transaction()->void($order_details->transaction_id);
					
					if($order_details->gear_order_id >0){
						$update_cart  = array( 
											'status'=>'Void',
											'update_date'=> date('Y-m-d'),
											); 
						$where = array(
										'gear_order_id'=>$order_id,
										'payment_type'=>'Gear Payment'
										);
						$this->db->where($where);
						$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
						
						$insert_cron= array('type' => 'Transaction Void',
									'date_time' => date('Y-m-d H:i:m'),
									'order_id' => $post_data['order_id'],
									'status' => 'Void',
									);

						$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
					}
			}			
			
			///Table is updated
			$query = $this->common_model->GetAllWhere('ks_user_gear_rent_details',array('order_id'=>$order_id));
			$cart_details	= $query->result();

			
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
			
			

			$insert_cron = array(
				'type' => 'Order Declined',
				'date_time' => date('Y-m-d H:i:m'),
				'order_id' => $order_id,
				'status' => 'Declined',
			);
			
			$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
				
			$this->RenterRejectmailOrder($order_id);
			$this->OwnerRejectmailOrder($order_id);

			
			$response['status'] = 200;
			$response['status_message'] = ' Owner Rejected Gear Successfully';
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
	public function RenterRejectmailOrder($order_id='')
	{
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'];
			$cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_lastname'] = '';
			$cart_details[0]['renter_firstname'] = $cart_details[0]['renter_bussiness_name'];
		}
		// print_r($cart_details);die;
		$this->db->select('*');
		$this->db->from('ks_gear_order_location ');
		$this->db->where('user_address_id',$cart_details[0]['user_address_id']);
		$this->db->where('order_id',$order_id);
		$query = $this->db->get();
		$address  =$query->row();	
		if (!empty($address)) {
			$suburb_name = $address->suburb_name ;
		}else{
			$suburb_name = '';
		}				
		
		if (empty($cart_details)) {
				$response['status'] = 200;
				$response['status_message'] = ' Order is present ';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
		}
		$msg= '<div style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="margin: 0px auto;background-color:#095cab;padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
					</tr>
				</table>
				<table width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
						<td style="font-size:20px; padding-bottom:10px;">Hi '.$cart_details[0]['renter_firstname'].'</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">We\'re sorry to say that  <b>'.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].'</b>  declined your reservation request.</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">Owners may decline for many reasons, including conflicting requests on your shoot dates and not having enough time between reservations.</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">We know that this particular reservation didn\'t work out, but there are plenty more listings and we encourage you to keep searching. We\'ve gone ahead and removed any temporary authorisation on your payment method so you can make a new booking.</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">
						Thanks,
						<p>The Kitshare Team</p>
						</td>
					</tr>
				</table>
				<table width="940" style="margin: 0px auto;background-color:#ddd;padding: 5px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
					</tr>
				</table>
				</div>';

		   $mail_body = $msg; 

		$to= $cart_details[0]['renter_email'];
		$subject = "Your rental request has been declined";
		$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare  Australia",
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
		$value =  $this->common_model->sendMail($mail_data);
		
		$insert_array = array("type"=>"Rental request declined",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Rental request declined");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
		
	}
	public function OwnerRejectmailOrder($order_id='')
	{	
		
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);

		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] ='';
			$cart_details[0]['app_user_last_name'] =$cart_details[0]['owner_bussiness_name'];
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_lastname'] = '';
			$cart_details[0]['renter_firstname'] = $cart_details[0]['renter_bussiness_name'];
		}
		$this->db->select('*');
		$this->db->from('ks_gear_order_location ');
		$this->db->where('user_address_id',$cart_details[0]['user_address_id']);
		$this->db->where('order_id',$order_id);
		$query = $this->db->get();
		$address  =$query->row();	
		if (!empty($address)) {
			$suburb_name = $address->suburb_name ;
		}else{
			$suburb_name = '';
		}				
		
		if (empty($cart_details)) {
				$response['status'] = 200;
				$response['status_message'] = ' Order is present ';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
		}

		$msg= '<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="margin: 0px auto;background-color:#095cab;padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
					</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
						<td style="font-size:20px; padding-bottom:10px;">
							Hi '.$cart_details[0]['app_user_first_name'].'</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">We’re sorry to hear that you declined the reservation request for <b>'.$cart_details[0]['renter_firstname'].' '.$cart_details[0]['renter_lastname'].'</b>.</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">We understand that you may need to decline for many reasons, including conflicting requests on your shoot dates and not having enough time between reservations.</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">We know that this particular reservation didn’t work out, but there are plenty more opportunities on Kitshare. Your calendar has also been updated to show that the previously booked dates are now available.</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">
							Thanks,
							<p>The Kitshare Team</p>
						</td>
					</tr>
				</table>
				<table width="940" style="margin: 0px auto; background-color:#ddd;padding: 5px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
					</tr>
				</table>
				</div>';

		  $mail_body = $msg; 
		 //$to= "sanidha@gmail.com";
		$to= $cart_details[0]['primary_email_address'];
		$subject = "Rental Declined";		
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
		
		$insert_array = array("type"=>"Owner declined request",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Owner declined request");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
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
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'] 	;
			$cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'] 	;
			$cart_details[0]['renter_lastname'] ='' 	;
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
				<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
				</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
				<tr>
				<td style="font-size:20px; padding-bottom:10px;">
				Hi, '.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].'  </td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">We regret to inform you that your '.$cart_details[0]['renter_firstname'].'  '.$cart_details[0]['renter_lastname'].'  cancelled reservation '. $order_id.' starting on '.   date('d-m-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).'. Per our cancellation policy, you have the option to request a cancellation fee of up to 50% of the Rental value if cancelled within 48hrs or 100% of the rental Rental value if cancelled within 24hrs of the Pickup time.</td>

				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">For more information on cancellation, please see our <a href="'.$web_url.'faq" target="_blank" >FAQ</a> .</td>
				
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
				<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
				</tr>
				</table>
				</div>

				</body>
				</html>
				';

				$mail_body = $msg;

		$to= $cart_details[0]['primary_email_address'];

		$subject = "Renter has cancelled reservation request";		
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
		
		$insert_array = array("type"=>"Renter cancelled reservation",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Renter cancelled reservation");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
	}

	public function SendRenterCancelMail($order_id)
	{
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'] 	;
			$cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'] 	;
			$cart_details[0]['renter_lastname'] ='' 	;
		}
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
				<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
				</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
				<tr>
				<td style="font-size:20px; padding-bottom:10px;">
				Hi, '.$cart_details[0]['renter_firstname'].'  '.$cart_details[0]['renter_lastname'].' </td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">We regret to inform you that you has cancelled reservation '.$order_id.' starting on '. date('d-m-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).'. </td>
				</tr>
				
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">We�ve cancelled your reservation request and you will not be charged for it. To make sure you still have a great shoot, let�s find you new kit in REQUESTED SUBURB. </td>
				
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
				<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
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
		$subject = "Renter has cancelled reservation request";		
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
		
		$insert_array = array("type"=>"Renter cancelled reservation",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Renter cancelled reservation");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
		
	}

	public function OwnerOrderSummary()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
			
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
		 		$owner_insurance_amount = 0;
				 $i= 0 ;
			 	foreach ($Cart_details as  $value) {
			 			//print_r($value);
			 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
			 			 $gst_amount  += $value['gst_amount'] ; 
			 			 $other_charges  += $value['other_charges'] ; 
			 			 $total_rent_amount  += $value['total_rent_amount_ex_gst'] ; 
			 			  $owner_insurance_amount += number_format((float)($value['owner_insurance_amount']), 2, '.', '') ;
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


						$this->db->select('u_a.*,');
						$this->db->from('ks_gear_order_location As u_a');
						// $this->db->where('u_a.user_address_id',$value['user_address_id']);
						$this->db->where('u_a.order_id',$value['order_id']);
						$this->db->limit('1');
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

							//$sql1 =  "SELECT app_user_id,show_business_name,bussiness_name,app_username,app_user_first_name,app_user_last_name,user_profile_picture_link,primary_email_address,primary_mobile_number FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
						$sql1 =  "SELECT ks_users.app_username,ks_users.app_user_id, k_u_g_o_d.renter_app_bussiness_name AS bussiness_name, k_u_g_o_d.renter_app_username,k_u_g_o_d.renter_app_show_business_name As show_business_name,k_u_g_o_d.renter_app_user_first_name As app_user_first_name, k_u_g_o_d.renter_app_user_last_name AS app_user_last_name,ks_users.user_profile_picture_link,ks_users.primary_email_address,ks_users.primary_mobile_number FROM ks_user_gear_order_description As k_u_g_o_d INNER JOIN ks_users  ON k_u_g_o_d.renter_app_username = ks_users.app_username  WHERE   order_id =  '".$post_data['order_id']."'  ";
						$app_users_details = $this->common_model->get_records_from_sql($sql1) ;
						if (!empty($app_users_details)) {

							if ($app_users_details[0]->user_profile_picture_link =='') {
								$app_users_details[0]->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
							}
							if ($app_users_details[0]->show_business_name == 'Y') {
								$app_users_details[0]->app_user_first_name = $app_users_details[0]->bussiness_name;
								$app_users_details[0]->app_user_last_name = '' ;
							}
						}
			 	}
			 		
			 		$gst_amount = ($total_rent_amount_ex_gst -$beta_discount +  $insurance_fee + $community_fee + $owner_insurance_amount)*10/100;
			 		$rent_summary = array(
			 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount,
			 								'gst_amount'=>$gst_amount,
			 								'beta_discount'=> $beta_discount,
			 								'insurance_fee'=> $insurance_fee,
			 								'community_fee'=> $community_fee,
			 								'owner_insurance_amount'=> number_format((float)($owner_insurance_amount), 2, '.', '') ,
			 								'other_charges'=>$other_charges,
			 								'sub_total'=>$total_rent_amount_ex_gst,
			 								'total_rent_amount'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee+$gst_amount +$owner_insurance_amount,
											'security_deposit' => $security_deposit,		 								
			 								'total_amount'=>   $gst_amount+$total_rent_amount_ex_gst -$beta_discount + $insurance_fee + $community_fee +$owner_insurance_amount
			 							);	

			 	$query =  $this->common_model->GetAllWhere('ks_cust_gear_reviews', array('order_id'=>$post_data['order_id']));
			 	$review_details =  $query->result() ;
			 	if (!empty($review_details)) {
			 	 	$review_count  = count($review_details);
			 	}else{
			 		$review_count = '0' ;
			 	}
				$data['Cart_details'] =  $Cart_details;
				$data['rent_summary'] = $rent_summary;	
				$data['app_users_details'] = $app_users_details;
				$data['order_status'] = $Cart_details[0]['order_status'];
				$data['review_count'] = $review_count;
				$app_user_id = '';
				$api_logs  =array(
							'api_name'  =>'OwnerOrderSummary',
							'type' 		=>'sucess',
							'status' 	=>'success',
							'order_id' 	=>"",
							'error_message' =>"",
							'date_time' =>date('Y-m-d H:i:m'),
					);
				$this->common_model->InsertData('ks_api_logs',$api_logs);
				$response['status'] = 200;
				$response['status_message'] = 'Order Summary Details';
				$response['result'] =$data ;
				$json_response = json_encode($response);
				echo $json_response;							 
				
			}else{
				$api_logs  =array(
							'api_name'  =>'OwnerOrderSummary',
							'type' 		=>'error',
							'status' 	=>'errro',
							'order_id' 	=>"",
							'error_message' =>json_encode("No Order Summary found"),
							'date_time' =>date('Y-m-d H:i:m'),
					);
				$this->common_model->InsertData('ks_api_logs',$api_logs);
				$response['status'] = 401;
				$response['status_message'] = ' No Order Summary found';
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

	public function RentalsOrderSummary()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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
		 		 $owner_insurance_amount = 0;
				 $i= 0 ;
			 	foreach ($Cart_details as  $value) {
			 			//print_r($value);
			 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
			 			 $gst_amount  += $value['gst_amount'] ; 
			 			 $other_charges  += $value['other_charges'] ; 
			 			 $total_rent_amount  += $value['total_rent_amount_ex_gst'] ; 
						 $beta_discount += $value['beta_discount'];
				 		 $insurance_fee += $value['insurance_fee'];
				 		 $owner_insurance_amount +=  number_format((float)( $value['owner_insurance_amount']), 2, '.', '');
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

							$this->db->select('u_a.*');
						$this->db->from('ks_gear_order_location As u_a');

						$this->db->where('u_a.order_id',$value['order_id']);
						$this->db->limit('1');
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

							// $sql1 =  "SELECT app_user_id,bussiness_name,show_business_name, app_username,bussiness_name,show_business_name,app_user_first_name,app_user_last_name,user_profile_picture_link,primary_email_address,primary_mobile_number FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
						$sql1 =  "SELECT ks_users.app_username,ks_users.app_user_id, k_u_g_o_d.owner_app_bussiness_name AS bussiness_name, k_u_g_o_d.owner_app_username,k_u_g_o_d.owner_app_show_business_name As show_business_name,k_u_g_o_d.owner_app_user_first_name As app_user_first_name, k_u_g_o_d.owner_app_user_last_name AS app_user_last_name,ks_users.user_profile_picture_link,ks_users.primary_mobile_number FROM ks_user_gear_order_description As k_u_g_o_d INNER JOIN ks_users  ON k_u_g_o_d.app_user_id = ks_users.app_user_id  WHERE   order_id =  '".$post_data['order_id']."'  ";
					
					$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
						if (!empty($app_users_details)) {

							if ($app_users_details[0]->user_profile_picture_link =='') {
								$app_users_details[0]->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
							}

							if ($app_users_details[0]->show_business_name == 'Y') {
								 $app_users_details[0]->app_user_first_name =$app_users_details[0]->bussiness_name ;
								 $app_users_details[0]->app_user_last_name = '' ;
							}
						}
			 		}
			 		 $gst_amount = ($total_rent_amount_ex_gst -$beta_discount +  $insurance_fee + $community_fee + $owner_insurance_amount)*10/100;
			 		$rent_summary = array(
			 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount,
			 								'gst_amount'=>$gst_amount,
			 								'beta_discount'=> $beta_discount,
			 								'insurance_fee'=> $insurance_fee,
			 								'community_fee'=> $community_fee,
			 								'other_charges'=>$other_charges,
			 								'owner_insurance_amount'=> number_format((float)($owner_insurance_amount), 2, '.', '') ,
			 								'sub_total'=> $total_rent_amount_ex_gst,
			 								'total_rent_amount'=>$total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount + $gst_amount,
											'security_deposit' => $security_deposit,		 								
			 								'total_amount'=>  $total_rent_amount_ex_gst - $beta_discount + $insurance_fee + $community_fee + $owner_insurance_amount+ $gst_amount 
			 							);	
			 	$query =  $this->common_model->GetAllWhere('ks_cust_gear_reviews', array('order_id'=>$post_data['order_id']));
			 	$review_details =  $query->result() ;
			 	if (!empty($review_details)) {
			 	 	$review_count  = count($review_details);
			 	}else{
			 		$review_count = '0' ;
			 	} 
				$data['Cart_details'] =  $Cart_details;
				$data['rent_summary'] = $rent_summary;	
				$data['app_users_details'] = $app_users_details;
				$data['order_status'] = $Cart_details[0]['order_status'];
				$data['review_count'] = $review_count;
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
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token = $post_data['token'];
		if (empty($_FILES)) {
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'Select A Image first ';
				$json_response = json_encode($response);
				echo $json_response;
				//header('HTTP/1.1 401 Unauthorized');
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
					//header('HTTP/1.1 401 Unauthorized');
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

	public function OwnerCheckListList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {	
				$conditions = array(
									'order_id'=>$post_data['order_id'],
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


	public function OwnerCheckListDelete()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {	
				$conditions = array(
									'order_id'=>$post_data['order_id'],
									'created_by'=>$app_user_id,
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

	public function RenterCheckListUpload()
	{
		$post_data =  $this->input->post();
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token = $post_data['token'];
		if (empty($_FILES)) {
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'Select A Image first ';
				$json_response = json_encode($response);
				echo $json_response;
				//header('HTTP/1.1 401 Unauthorized');
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
					//header('HTTP/1.1 401 Unauthorized');
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

	public function RenterCheckListList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {	
				$conditions = array(
									'order_id'=>$post_data['order_id'],
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

	public function RenterCheckListDelete()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {	
				$conditions = array(
									'order_id'=>$post_data['order_id'],
									'created_by'=>$app_user_id,
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

	public function GETCartAddressList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
				$Cart_details = $this->home_model->getUserCart1($app_user_id);
				if (!empty($Cart_details)) {
					$gear_ids = '';
					$where = '';
					$JOIN = '';
					$i = 0 ;
					foreach ($Cart_details as $value) {

						$user_gear_desc_id_array[] =$value['user_gear_desc_id'];
						$gear_ids.= "'". $value['user_gear_desc_id']."',"; 
						$JOIN .= " INNER JOIN ks_gear_location AS k_l_".$i."  ON g_l.user_address_id =  k_l_".$i.".user_address_id "; 
						$where .= " k_l_".$i.".user_gear_desc_id = '".$value['user_gear_desc_id']."'  AND    ";
						$i++;
					}
					 $sql = "SELECT `u_a`.*, `ks_states`.`ks_state_name`, `ks_suburbs`.`suburb_name`, `a`.`per_day_cost_aud_inc_gst`, `a`.`per_day_cost_aud_ex_gst`FROM `ks_gear_location` As `g_l`INNER JOIN `ks_user_address` AS `u_a` ON `u_a`.`user_address_id` = `g_l`.`user_address_id`INNER JOIN `ks_suburbs` ON `ks_suburbs`.`ks_suburb_id` = `u_a`.`ks_suburb_id`INNER JOIN `ks_states` ON `ks_states`.`ks_state_id` = `u_a`.`ks_state_id`INNER JOIN `ks_user_gear_description` AS `a` ON `g_l`.`user_gear_desc_id` = `a`.`user_gear_desc_id` ".$JOIN."  WHERE   ".rtrim($where , ' AND')." GROUP BY  g_l.user_address_id " ; 
				
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

	public function ChangeDatesForCart()
	{	
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		
		$dateflag = 0;
		if ($app_user_id != '') {
			$Cart_details = $this->home_model->getUserCart1($app_user_id);

			$query =  $this->common_model->GetAllwhere('ks_user_gear_rent_master' , array('user_gear_rent_id'=>$Cart_details[0]['user_gear_rent_id']));
			$rent_master_details =  $query->row();			
			

			if (empty($Cart_details)) {
				$app_user_id = '';
				$response['status'] = 404;
				$response['status_message'] = 'No Gear in Cart';
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			}

			foreach ($Cart_details as  $gear_arr) {

				$date_from =  strtotime($post_data['date_from']);
				$date_to =  strtotime($post_data['date_to']);
				
				
				//User Gear Category ID is fetched
				$where_clause = "user_gear_desc_id='".$gear_arr['user_gear_desc_id']."'";
				$query = $this->common_model->GetSpecificValues("ks_category_id","ks_user_gear_description",$where_clause);
				$row = $query->result_array();
				$ks_category_id = $row[0]['ks_category_id'];
				
				if($date_from  == $date_to ){
					
					$current_time = date('H:i:s');
					
					$curdate = strtotime(date('Y-m-d'));
					 
					if($current_time >= '22:00:00' && $date_from==$curdate){
					
						$date_from 	= date('Y-m-d', strtotime($post_data['date_from'] . ' +1 day'));
						$date_to 	= date('Y-m-d', strtotime($post_data['date_to'] . ' +1 day'));
						$date_from 	= strtotime($date_from);
						$date_to 	= strtotime($date_to);
						
					}else 
					
					if($date_from==$curdate){
					
						$date_from 	= strtotime(date('Y-m-d', strtotime($post_data['date_from'])));
						$date_to 	= strtotime(date('Y-m-d', strtotime($post_data['date_to'])));
						
						//If pickup date is today's date then this flag is turned on
						$dateflag = 1;
						
					}else{
						/*$date_from 	= strtotime(date('Y-m-d', strtotime($post_data['date_from'])));
						$date_to 	= date('Y-m-d', strtotime($post_data['date_to'] . ' +1 day'));
						$date_to 	= strtotime($date_to);
						
						$dateflag = 2;*/
						$dateflag = 1;
					}
					
				}else
					$dateflag = 2;
					
				/*if (!empty($gear_arr)) {
					 $date_from 	=  strtotime(date('d-m-Y',strtotime($gear_arr['gear_rent_request_from_date'])));	
					 $date_to 		=  strtotime( date('d-m-Y',strtotime($gear_arr['gear_rent_request_to_date'])));	
					
				}*/
				
				$dates_array = $this->getDateList($date_from,$date_to);
				
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
				// print_r($gear_arr);
				// echo "<br>";
				 $gaer_data->per_day_cost_aud_ex_gst ;
				// echo "<br>";
				// die;
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
				
				//Pickup time will always be 14:00 hrs. and Drop off will be 12:00 hrs if it is not Creative Space and if it is not a Single day Rent
				if($ks_category_id == 162){
					
					$date_from		= 	date('Y-m-d',$date_from)." 00:00:00";
					$date_to		= 	date('Y-m-d',$date_to)." 23:59:00";
				
				}else{
				
					if($dateflag == 1){
					
						$date_from		= 	date('Y-m-d',$date_from)." 00:00:00";
						$date_to		= 	date('Y-m-d',$date_to)." 23:59:00";
					
					}else{
						$date_from		= 	date('Y-m-d',$date_from)." 14:00:00";
						$date_to		= 	date('Y-m-d',$date_to)." 12:00:00";
					}
					
				}
				
				$add_data = array(
				         //"app_user_id" => $app_user_id,
				         //"gear_rent_requested_on"=>date('Y-m-d h:i:m'),
				         "gear_rent_request_from_date"=>$date_from,
				         "gear_rent_request_to_date" =>$date_to,
				         "gear_total_rent_request_days" => $rentDays,
				         "gear_rent_start_date"=>$date_from,
				         "gear_rent_end_date"=>$date_to,
				         "user_address_id"=>$rent_master_details->user_address_id ,
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
			$this->CheckCartInsurance($Cart_details,$rent_master_details->user_address_id);
			$this->GETCartCalculation($app_user_id);
			$response['status'] = 200;
			$response['status_message'] = 'Gear Dates Updated Successfully';
				$json_response = json_encode($response);
				echo $json_response;
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
	// public function ChangeDateRequestMail($order_id)
	// {
		
	// }
	public function AddAddresstoCartGear()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$Cart_details = $this->home_model->getUserCart1($app_user_id);
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
			
			//user_address_id is checked
			$user_address_id = $post_data['user_address_id'];
			
			
			$this->CheckCartInsurance($Cart_details,$post_data['user_address_id']);
			$data =  $this->GETCartCalculation($app_user_id);
			// print_r($data);die;
			$data['cart_summary']['user_address_id'] = $user_address_id ;
			$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , '' ) ; 
			$insurance_type = $query->row();
			$response['status'] = 200;
			$response['status_message'] = 'Gear Pickup Address Updated Successfully';
			$response['result'] = array('user_address_id'=>$user_address_id,'cart'=>$data['Cart_details'],'cart_summary'=>$data['cart_summary'] , 'app_users_details'=>$data['app_users_details'] ,'insurance_type'=>$insurance_type);
			$json_response = json_encode($response);
			echo $json_response;
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


	public function CheckCartInsurance($Cart,$user_address_id)
	{
		foreach ($Cart AS  $Cart_details1) {
			$this->db->select('rent_details.user_address_id ,state.* ');
	  		$this->db->from('ks_user_gear_rent_master As  rent_details');
			$this->db->join('ks_user_address As address', 'address.user_address_id = rent_details.user_address_id','LEFT');
			$this->db->join('ks_states As state', 'state.ks_state_id = address.ks_state_id','LEFT');
			$this->db->where('rent_details.user_gear_rent_id' ,$Cart_details1['user_gear_rent_id']); 
			$this->db->where('address.user_address_id' ,$user_address_id); 
			$query = $this->db->get();	
	  		$address_details = $query->row();	
	  		$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $Cart_details1['ks_insurance_category_type_id']) ) ; 
			$insurance_type = $query->row();
			if (empty($address_details->user_address_id)) {

			 		
			}else{

				if ($Cart_details1['ks_insurance_category_type_id'] == '1' ) { // kitshare inurance cover
			 				$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=>$Cart_details1['ks_insurance_category_type_id']) ) ; 
							$insurance_type = $query->row();
							$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$Cart_details1['renter_app_user_id'] , 'user_gear_desc_id'=>$Cart_details1['user_gear_desc_id'], 'order_id ='=>''));
						 	$Cart_details = $query1->row();
						 	$date_from = strtotime($Cart_details->gear_rent_request_from_date);
						 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
						 	$diff = abs($date_to - $date_from);
							$date_array =  $this->getDateList($date_from,$date_to);
							if (count($date_array ) <= 1 ) {
						 		
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

						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$Cart_details->user_gear_desc_id));
						 	$gear_details = $query1->row();
						 	$query =  $this->common_model->GetAllWhere('ks_settings','');
							$settings =  $query->row();
							if($gear_details->replacement_value_aud_ex_gst > $settings->max_replacement_value){
								
							}
								$where_clause1 = array(
			 							'initial_value <' => $Cart_details->total_rent_amount_ex_gst,
			 							'end_value > ' => $Cart_details->total_rent_amount_ex_gst,
			 							'status'=> '0',
			 							'is_deleted' => '0',
			 							'ks_insurance_category_type_id'=> $Cart_details->ks_insurance_category_type_id
				 				  );
							$query =  $this->common_model->GetAllWhere(' ks_insurance_tiers	' , $where_clause1) ; 
							$insurance_tier_type = $query->row();
							
						 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$Cart_details->create_user , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
						 	$rent_master_details = $query2->row();

						 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit  - $Cart_details->beta_discount ; 
						 	$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
							$Cart_details->esl_rate =  $address_details->ks_state_esl ; 
							$Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
							$Cart_details->premium_base_rate =  $address_details->base_rate ; 

							$Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
							$Cart_details->esl_amount =  $insurance_Details['esl']  ; 
							$Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
							$Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
							$Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
							$Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
							$Cart_details->insurance_amount =  $insurance_Details['insurance_fee']    ; 
							$Cart_details->owner_insurance_amount = number_format((float)((0)) , 2, '.', ''); 
				 			$Cart_details->owner_insurance_status = 0 ;  
				 			$Cart_details->insurance_percentage = '0' ;  
					 		$Cart_details->insurance_amount =  $insurance_Details['insurance_fee']  ;  
							$Cart_details->insurance_fee =  $insurance_Details['insurance_fee']  ;
						 	// $gear_insurance_value = $Cart_details->insurance_amount;	
						 	$rent_master_details->other_charges	 =  '0' ;
						 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit  - $Cart_details->beta_discount  + $Cart_details->insurance_fee + $Cart_details->community_fee ; ; 
						 	$Cart_details->ks_insurance_category_type_id = $Cart_details->ks_insurance_category_type_id  ; 
						 	if (!empty($insurance_tier_type)) {
									$Cart_details->insurance_tier_id =  $insurance_tier_type->tiers_id  ; 
							}else{
									$Cart_details->insurance_tier_id =  0 ; 
							}
						 	$Cart_details->other_charges =  '0' ; 
						 	$Cart_details->security_deposit =  '0' ; 
						 	$Cart_details->deposite_status =  'NONE' ; 
						 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
						 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
			 	}

			 	if ($Cart_details1['ks_insurance_category_type_id'] == '3') { // own insurance/3rd party insurance
			 			$paymentDate = date('Y-m-d');
						$paymentDate=date('Y-m-d', strtotime($paymentDate));
							
						$where_clause = array(
												'app_user_id'=> $Cart_details1['renter_app_user_id'] ,
												'renter_insurance_provided' => 'Yes',
												'renter_insurance_status'=> '1' ,
												'is_visiible'=> 'Y' ,
												'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
												'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

											);
						$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
						$insurance =  $query->row();
						if(!empty($insurance)){
							$replacement_value_aud_ex_gst = 0 ;
							$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $Cart_details1['renter_app_user_id']) ) ; 
							$insurance_type = $query->row();
						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$Cart_details1['renter_app_user_id'], 'user_gear_desc_id'=>$Cart_details1['user_gear_desc_id'] ,'order_id ='=>''));
						 	$Cart_details = $query1->row();

						 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$Cart_details1['renter_app_user_id'] , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
						 	$rent_master_details = $query2->row();

						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$Cart_details1['renter_app_user_id'] ,'ks_insurance_category_type_id'=>'3','order_id !=' =>'' ,'create_date' => date('Y-m-d')));
						 	$today_order_Details = $query1->row();
						 	if (!empty($today_order_Details)) {
						 		$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$today_order_Details->user_gear_desc_id ));
							 	$Gear_details_today_order  = $query1->row();

							 	$replacement_value_aud_ex_gst += $Gear_details_today_order->replacement_value_aud_ex_gst ;
						 	}
						 
						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$Cart_details->user_gear_desc_id ));
						 	$Gear_details = $query1->row();

						 	$replacement_value_aud_ex_gst +=  $Gear_details->replacement_value_aud_ex_gst ;


						 	// if ($replacement_value_aud_ex_gst >  $insurance->owner_insurance_amount) {
						 	// 	$response['status'] = 404;
								// $response['status_message'] = 'Cannot checkout for this insurance';
								// $response['result'] = array();
								// $json_response = json_encode($response);
								// echo $json_response;	
								// exit();
						 	// }

						 	$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
							// print_r($insurance_Details['total_premium_ex_gst']);die;
							// $Cart_details->esl_rate =  $address_details->ks_state_esl ; 
							// $Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
							// $Cart_details->premium_base_rate =  $address_details->base_rate ; 

							// $Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
							// $Cart_details->esl_amount =  $insurance_Details['esl']  ; 
							// $Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
							// $Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
							// $Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
							// $Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
							$Cart_details->insurance_amount =  0   ; 
							$Cart_details->owner_insurance_amount = number_format((float)((0)) , 2, '.', ''); 
				 			$Cart_details->owner_insurance_status = 0 ;  
				 			$Cart_details->insurance_percentage = '0' ;  
						 	$Cart_details->other_charges =   '0' ;
						 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges; 
						 	$rent_master_details->other_charges = '0' ;
						 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount +  $rent_master_details->other_charges; 
						 	$Cart_details->insurance_fee = '0'; 
						 	$Cart_details->ks_insurance_category_type_id = $Cart_details->ks_insurance_category_type_id ; 
						 	$Cart_details->insurance_tier_id =  '0'  ; 
						 	$Cart_details->other_charges =  '0' ; 
						 	$Cart_details->security_deposit =  '0' ; 
					 		
					 		$Cart_details->deposite_status =  'NONE' ; 
					 		if (!empty($insurance_type)) {
					 			$insurance_type->amount =0 ;
								$insurance_type->amount ='0';
					 		}
					 							 	
							
						 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
						 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);

						}else{

							$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('ks_insurance_category_type_id'=> $post_data['insurance_type']) ) ; 
							$insurance_type = $query->row();

						 	$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$Cart_details1['renter_app_user_id'] , 'user_gear_desc_id'=>$Cart_details1['user_gear_desc_id'], 'order_id ='=>''));
						 	$Cart_details = $query1->row();

						 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
						 	$rent_master_details = $query2->row();
						 	$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
							// print_r($insurance_Details['total_premium_ex_gst']);die;
							$Cart_details->esl_rate =  $address_details->ks_state_esl ; 
							$Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
							$Cart_details->premium_base_rate =  $address_details->base_rate ; 

							$Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
							$Cart_details->esl_amount =  $insurance_Details['esl']  ; 
							$Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
							$Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
							$Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
							$Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
							$Cart_details->insurance_amount =  $insurance_Details['insurance_fee']    ; 
							
			 
						 	$Cart_details->other_charges =  '0' ;
						 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges; 
						 	$rent_master_details->other_charges = '0' ;
						 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount +  $rent_master_details->other_charges; 
						 	
						 	$Cart_details->ks_insurance_category_type_id = $post_data['insurance_type']  ; 
						 	$Cart_details->insurance_tier_id =  '0'  ; 
						 	$Cart_details->other_charges =  '0' ; 
						 	$Cart_details->security_deposit =  '0' ; 
				 			$Cart_details->deposite_status =  'NONE' ; 
						 	$insurance_type->amount = '0' ;
						 	$Cart_details->insurance_fee = '0'; 
						 	
						 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
						 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);

						}	
				
			 	}

			 	if ($Cart_details1['ks_insurance_category_type_id'] == '4') { // NO insurance
					 		$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$Cart_details1['renter_app_user_id'] , 'user_gear_desc_id'=>$Cart_details1['user_gear_desc_id'], 'order_id ='=>''));
						 	$Cart_details = $query1->row();
							 
							$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$app_user_id , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
					 		$rent_master_details = $query2->row();

							$rent_master_details->other_charges = '0' ;
						 	$Cart_details->total_rent_amount =  $rent_master_details->total_rent_amount_ex_gst +  $rent_master_details->gst_amount ; 
						 	$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
							// $Cart_details->esl_rate =  $address_details->ks_state_esl ; 
							// $Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
							// $Cart_details->premium_base_rate =  $address_details->base_rate ; 

							// $Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
							// $Cart_details->esl_amount =  $insurance_Details['esl']  ; 
							// $Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
							// $Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
							// $Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
							// $Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
							$Cart_details->insurance_amount =  0    ; 
							
						 	
						 	$Cart_details->ks_insurance_category_type_id = $Cart_details1['ks_insurance_category_type_id']  ; 
						 	$Cart_details->insurance_tier_id =  '0'  ; 
						 	$Cart_details->other_charges =  '0' ; 
						 	$Cart_details->security_deposit =  '0' ; 
					 		$Cart_details->deposite_status =  'NONE'; 
						 	$insurance_type->amount = '0'; ;
						 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
						 	$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
			 	}
			 	
			 	if ($Cart_details1['ks_insurance_category_type_id'] == '5') { // security Deposit
			 				$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$Cart_details1['renter_app_user_id'] , 'user_gear_desc_id'=>$Cart_details1['user_gear_desc_id'], 'order_id ='=>''));
						 	$Cart_details = $query1->row();
							$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$Cart_details1['renter_app_user_id'] , 'user_gear_rent_id'=>$Cart_details1['user_gear_rent_id']));
							$rent_master_details = $query2->row();
							$gaer_data = $this->home_model->geratDetails($Cart_details1['user_gear_desc_id']);

							$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount ; 
							// $Cart_details->insurance_amount =  $gaer_data->security_deposite_inc_gst; 
							$Cart_details->ks_insurance_category_type_id = $Cart_details1['ks_insurance_category_type_id'] ; 
							
							$insurance_Details =  $this->BaseInsuranceCacluation($Cart_details, $address_details);
							// print_r($insurance_Details['total_premium_ex_gst']);die;
							// $Cart_details->esl_rate =  $address_details->ks_state_esl ; 
							// $Cart_details->stamp_duty_rate =  $address_details->ks_state_sd ; 
							// $Cart_details->premium_base_rate =  $address_details->base_rate ; 

							// $Cart_details->premium_base_rate_amount =  $insurance_Details['base_amount'] ; 
							// $Cart_details->esl_amount =  $insurance_Details['esl']  ; 
							// $Cart_details->duty_rate_amount =  $insurance_Details['stamp_duty']  ; 
							// $Cart_details->total_premium_ex_gst =  $insurance_Details['total_premium_ex_gst']  ; 
							// $Cart_details->premium_gst =  $insurance_Details['premium_gst']  ; 
							// $Cart_details->total_premium_inc_gst =  $insurance_Details['total_premium_inc_gst']  ; 
							$Cart_details->insurance_amount =  0   ; 
							

							$Cart_details->insurance_tier_id =  '0'  ; 
							$Cart_details->other_charges =  '0' ; 
							$Cart_details->security_deposit =   $gaer_data->security_deposite_inc_gst ; 
							$Cart_details->deposite_status =  'MARK STORED' ;
							
							$insurance_type->amount = $gaer_data->security_deposite_inc_gst; ;
							$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
							if (!empty($rent_master_details)) {
							$rent_master_details->other_charges ='' ;
							$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);
							}
			 	}

			 	if ($Cart_details1['ks_insurance_category_type_id'] == '8') {
			 				$query1 =  $this->common_model->GetAllWhere('ks_user_gear_rent_details' ,array('create_user'=>$Cart_details1['renter_app_user_id'] , 'user_gear_desc_id'=>$Cart_details1['user_gear_desc_id'], 'order_id ='=>''));
						 	$Cart_details = $query1->row();

						 	$query2 =  $this->common_model->GetAllWhere('ks_user_gear_rent_master' ,array('app_user_id'=>$Cart_details1['renter_app_user_id'] , 'user_gear_rent_id'=>$Cart_details->user_gear_rent_id));
						 	$rent_master_details = $query2->row();
						 	$date_from = strtotime($Cart_details->gear_rent_request_from_date);
						 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
						 	$diff = abs($date_to - $date_from);
							$date_array =  $this->GetRentDays($date_from,$date_to);
							
							$query1 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array( 'user_gear_desc_id'=>$Cart_details1['user_gear_desc_id']));
						 	$gear_details = $query1->row();
						 	$query =  $this->common_model->GetAllWhere('ks_settings','');
						 	$settings = $query->row();

						 	$where = array( 
						 					'app_user_id'=>$Cart_details1['app_user_id'] , 
						 					'is_visiible'=>'Y' ,
						 					'owner_insurance_provided'=>'Yes', 
						 					'owner_insurance_status' =>'1' ,
						 					'ks_user_certificate_currency_start <= ' => date('Y-m-d') , 
						 					'ks_user_certificate_currency_exp >= ' => date('Y-m-d')  
						 		);
						 	
							$query1 =  $this->common_model->GetAllWhere('ks_user_insurance_proof' ,$where);
						 	$insurance_detail = $query1->row();
						
				 			if (!empty($insurance_detail)) {
				 				$paymentDate = date('Y-m-d');
								$paymentDate=date('Y-m-d', strtotime($paymentDate));
								$contractDateBegin = date('Y-m-d', strtotime($insurance_detail->ks_user_certificate_currency_start));
								$contractDateEnd = date('Y-m-d', strtotime($insurance_detail->ks_user_certificate_currency_exp));
								if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
								 //  	if (count($date_array ) <= 1 ) {
									//  		# code...
								 // 	}elseif(count($date_array ) == 2 ){
								 // 			   $date_array;

								 // 	}else{
								 // 		foreach ($date_array as $value) {
								 // 			 $val_date[] =   $value['date'];
								 // 		}
								 // 		 $date_array =  $val_date;
									// 		array_pop($date_array); 
									// }							  
									 $insurance_days = $date_array;
							 		$inusrance_value = ($gear_details->per_day_cost_aud_ex_gst * $insurance_detail->owner_insurance_percentage *$Cart_details->gear_total_rent_request_days)/100 ;
							 		$Cart_details->owner_insurance_amount = number_format((float)(($inusrance_value)) , 2, '.', ''); 	
				 			}
				 			
								$Cart_details->insurance_fee =  0; 
								$Cart_details->insurance_amount =  0; 
			 					$gear_insurance_value = $Cart_details->insurance_amount;	
				 				$Cart_details->owner_insurance_status = 1 ;  
				 				$Cart_details->insurance_percentage = $insurance_detail->owner_insurance_percentage ;  
							 	$rent_master_details->other_charges	 =  '0' ;
							 	$Cart_details->total_rent_amount =  $Cart_details->total_rent_amount_ex_gst +  $Cart_details->gst_amount +  $Cart_details->other_charges+ $Cart_details->security_deposit  - $Cart_details->beta_discount  + $Cart_details->insurance_fee + $Cart_details->community_fee  ; 

							 	$Cart_details->ks_insurance_category_type_id = $Cart_details1['ks_insurance_category_type_id']  ; 
							 	$Cart_details->insurance_tier_id =  0 ; 
							 	$Cart_details->other_charges =  '0' ; 
							 	$Cart_details->security_deposit =  '0' ; 
							 	$Cart_details->deposite_status =  'NONE' ;
							 	$this->common_model->UpdateRecord($Cart_details , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $Cart_details->user_gear_rent_detail_id);
			 					$this->common_model->UpdateRecord($rent_master_details , 'ks_user_gear_rent_master' , 'user_gear_rent_id', $rent_master_details->user_gear_rent_id);

							}else{
							  
							
							}
				 			
			 		}
			}	
		}
	}

	public function AddCartProjects()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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
			$Cart_details = $this->home_model->getUserCart1($app_user_id);
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

	public function SerailNumberBYGear()
	{
		
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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
	public function CancelOrderByRenter()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		
		if ($app_user_id != '' ) {
			$Cart_details = $this->home_model->OwnerOrderSummary($post_data['order_id']);
			if (!empty($Cart_details)) {
					
				//Checked whether the logged in user is a renter
				if ($Cart_details[0]['renter_app_user_id'] == $app_user_id) {
					
					$where_clause = array('gear_order_id'=> $post_data['order_id'], 'payment_type'=> 'Gear Payment' );
					$query =  $this->common_model->GetAllWhere('ks_user_gear_payments', $where_clause);
					$gear_payment =  $query->row();
					
					//Order is cancelled
					if (empty($gear_payment->transaction_id)) {
							$insert_data  = array('type'=> 'Transaction Void' ,'date_time'=>date('Y-m-d H:i:s') ,'order_id'=>$Cart_details[0]['order_id'] , 'status'=>'Void');
		  					$this->common_model->InsertData('ks_crone_log',$insert_data);
							$update_Data = array(
												'status'=>'Void'
											);
							$this->db->where('user_gear_payment_id', $gear_payment->user_gear_payment_id);
							$this->db->update('ks_user_gear_payments', $update_Data); 
					}
					
					$today_date = date('Y-m-d H:i:s');
					$order_date =  $Cart_details[0]['gear_rent_start_date'];
					$hourdiff = round((strtotime( $order_date) - strtotime($today_date))/3600, 1);
					
					// //owner didn't accept the order
					//if($Cart_details[0]['is_rent_approved'] == 'N' || $hourdiff > 48){
					if( $hourdiff > 48){
							$where_clause = array('gear_order_id'=> $post_data['order_id'], 'payment_type'=> 'Gear Payment' );
							$query =  $this->common_model->GetAllWhere('ks_user_gear_payments', $where_clause);
							$gear_payment =  $query->row();
							if (!empty($gear_payment->transaction_id)) {
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

								$result = $gateway->transaction()->void($gear_payment->transaction_id);								
						
							
							
								$update_cart  = array( 
													'status'=>'Void',
													'update_date'=> date('Y-m-d'),
													); 
								$where = array(
												'gear_order_id'=>$post_data['order_id'],
												'payment_type'=>'Gear Payment'
												);
								$this->db->where($where);
								$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
								
								$insert_cron= array('type' => 'Transaction Void',
									'date_time' => date('Y-m-d H:i:m'),
									'order_id' => $post_data['order_id'],
									'status' => 'Gear Payment Void',
								);

								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);											
							}	

					}
					
					// Deposite Payment Void
					$where_clause = array('gear_order_id'=> $post_data['order_id'], 'payment_type'=> 'Deposite Payment');
					$query =  $this->common_model->GetAllWhere('ks_user_gear_payments', $where_clause);
					$deposite_payment =  $query->row();
					if (!empty($deposite_payment)) {
						if ($deposite_payment->transaction_id!= '') {
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

								$result = $gateway->transaction()->void($deposite_payment->transaction_id);
								$update_cart  = array( 														
												'status'=>'Void',
												'update_date'=> date('Y-m-d'),														
											); 
								$where = array(
												'gear_order_id'=>$post_data['order_id'],
												'payment_type'=>'Deposite Payment'
											);
								$this->db->where($where);
								
								$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
								
								foreach ($Cart_details as  $value) {

										$update_cart  = array( 
											'deposite_status'=>'Void',												
										); 

										$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);

								}	
								$insert_cron= array('type' => 'Deposit transaction void',
									'date_time' => date('Y-m-d H:i:m'),
									'order_id' => $post_data['order_id'],
									'status' => 'Deposit Void',
								);

								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);

						}else{
								$update_cart  = array( 
												
												'status'=>'Cancelled',
												'update_date'=> date('Y-m-d'),
												
												); 
								$where = array(
												'gear_order_id'=>$post_data['order_id'],
												'payment_type'=>'Deposite Payment'
												);
								$this->db->where($where);
								
								$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
								foreach ($Cart_details as  $value) {

										$update_cart  = array( 												
											'deposite_status'=>'Cancelled',												
										); 

										$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);

								}	
						}
					}			
					
					
					$insert_cron= array('type' => 'Order Cancelled by renter',
						'date_time' => date('Y-m-d H:i:m'),
						'order_id' => $post_data['order_id'],
						'status' => 'Cancelled',
					);

					$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
					
					$this->RenterCancelMail($post_data['order_id']);
					
				}else{
					
					// Gear payment Void 
					$where_clause = array('gear_order_id'=> $post_data['order_id'], 'payment_type'=> 'Gear Payment' );
					$query =  $this->common_model->GetAllWhere('ks_user_gear_payments', $where_clause);
					$gear_payment =  $query->row();

					if (!empty($gear_payment->transaction_id)) {
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

						$result = $gateway->transaction()->void($gear_payment->transaction_id);
						$update_cart  = array( 
			 							
			 							'status'=>'Void',
			 							'update_date'=> date('Y-m-d'),
			 							
			 							); 
						$where = array(
										'gear_order_id'=>$post_data['order_id'],
										'payment_type'=>'Gear Payment'
										);
						$this->db->where($where);
						$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
						$insert_cron= array('type' => 'trancation void',
						'date_time' => date('Y-m-d H:i:m'),
						'order_id' => $post_data['order_id'],
						'status' => 'Void',
						);

						$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
				
					}else{
							$update_cart  = array( 
			 							
			 							'status'=>'Void',
			 							'update_date'=> date('Y-m-d'),
			 							
			 							); 
							$where = array(
											'gear_order_id'=>$post_data['order_id'],
											'payment_type'=>'Gear Payment'
											);
							$this->db->where($where);
							$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
					}

					// Deposite Payment Void
					$where_clause = array('gear_order_id'=> $post_data['order_id'], 'payment_type'=> 'Deposite Payment' );
					$query =  $this->common_model->GetAllWhere('ks_user_gear_payments', $where_clause);
					$deposite_payment =  $query->row();
					if (!empty($deposite_payment)) {
						if ($deposite_payment->transaction_id!= '') {
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

								$result = $gateway->transaction()->void($deposite_payment->transaction_id);
									$update_cart  = array( 
					 							
					 							'status'=>'Void',
					 							'update_date'=> date('Y-m-d'),
					 							
					 							); 
								$where = array(
												'gear_order_id'=>$post_data['order_id'],
												'payment_type'=>'Deposite Payment'
												);
								$this->db->where($where);
								
								$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
								
								foreach ($Cart_details as  $value) {

										$update_cart  = array( 
			 							
			 							'deposite_status'=>'Void',
			 							
			 							); 

			 							$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);

								}	
								$insert_cron= array('type' => 'Deposit trancation void',
								'date_time' => date('Y-m-d H:i:m'),
								'order_id' => $post_data['order_id'],
								'status' => 'Deposit Void',
								);

								$this->common_model->InsertData('ks_crone_log' ,$insert_cron);

						}else{
							$update_cart  = array( 
					 							
					 							'status'=>'Cancelled',
					 							'update_date'=> date('Y-m-d'),
					 							
					 							); 
								$where = array(
												'gear_order_id'=>$post_data['order_id'],
												'payment_type'=>'Deposite Payment'
												);
								$this->db->where($where);
								
								$query = $this->db->update( 'ks_user_gear_payments',$update_cart);
								foreach ($Cart_details as  $value) {

										$update_cart  = array( 
			 							
			 							'deposite_status'=>'Cancelled',
			 							
			 							); 

			 							$this->common_model->UpdateRecord($update_cart,"ks_user_gear_rent_details","user_gear_rent_detail_id",$value['user_gear_rent_detail_id']);

								}	
						}
					}
					
					$insert_cron= array('type' => 'Order Cancelled by Owner',
						'date_time' => date('Y-m-d H:i:m'),
						'order_id' => $post_data['order_id'],
						'status' => 'Cancelled',
						);

					$this->common_model->InsertData('ks_crone_log' ,$insert_cron);

				
					$this->OwnerCancelMail($post_data['order_id']);
				}

				
				if ($Cart_details[0]['is_rent_approved'] != 'Y') {
					$total_rent_amount = 0;
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
			 			// echo (int) $value['total_rent_amount'] ;die;
			 			$total_rent_amount += (int) $value['total_rent_amount'];
					}
					$insert_refund = array(
			 						'order_id'=>$post_data['order_id'],
			 						'user_id'=>$app_user_id,
			 						'amount' => $total_rent_amount,
			 						'reason'=>'Order has been cancelled. No cancellation charge has been deducted as the order was not confirmed.',
			 						'refund_date'=>date('Y-m-d H:i:m'),
			 						'refund_type'=>'Payment',
			 						'created_date'=>date('Y-m-d'),
			 						'created_time'=>date('H:i:m'),
			 						'created_by'=>$app_user_id
			 					);
					 $this->common_model->InsertData('ks_refund_order' ,$insert_refund);

					$app_user_id = '';
					$response['status'] = 200;
					$response['status_message'] = 'Order has been cancelled. No cancellation charge has been deducted as the order was not confirmed.';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
				}else{
					 $current_time = strtotime(date('Y-m-d H:i:s', strtotime("-48 hours"  ,strtotime($Cart_details[0]['gear_rent_request_from_date']))));
					if (strtotime(date('Y-m-d h:i:s')) < $current_time ) {
						$total_rent_amount = 0;
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
				 			$total_rent_amount += (int) $value['total_rent_amount'];
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
						 		$total_rent_amount = 0;
								foreach ($Cart_details as  $value) {
									$update_cart  = array( 
						 							'is_rent_approved'=>'N',
						 							'is_rent_cancelled' => 'Y',
						 							'order_status'=>'5',
						 							'deposite_status'=>'Void',
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

	// Owner Cancel mails
	public function OwnerCancelMail($order_id)
	{
		$this->OwnerCanelMailRenter($order_id);
		$this->SendOwnerCancelMailOwner($order_id);
	}

	//Owner Cancel Mail to Renter
	public function OwnerCanelMailRenter($order_id='')
	{
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}		
		
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'];
			$cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'];
			$cart_details[0]['renter_lastname'] ='';
		}
		$this->db->from('ks_gear_order_location As u_a');
		$this->db->where('u_a.user_address_id',$cart_details[0]['user_address_id']);
		$this->db->where('u_a.order_id',$order_id);
		
		$query = $this->db->get();
		$address  =$query->row();	
		if (!empty($address)) {
			$suburb_name = $address->suburb_name ;
		}else{
			$suburb_name = '';
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
				<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
				</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
				<tr>
				<td style="font-size:20px; padding-bottom:10px;">
				Hi '. $cart_details[0]['renter_firstname'].'  '.$cart_details[0]['renter_lastname'].' </td>
				</tr>
			
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">We regret to inform you that '.$cart_details[0]['app_user_first_name'].'  '. $cart_details[0]['app_user_last_name'].' has cancelled reservation '. $order_id.' starting on '.   date('d-m-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).'.</td>

				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">We&#39;ve cancelled your reservation request and you will not be charged for it. To make sure you still have a great shoot, let&#39;s find you new kit in <a href="'.$web_url.'search?cat_type=&cat_id=&sub_cat_id=&address=&cat_sub_cat_name=&suburb_name='.$suburb_name.'">'.$suburb_name.'</a></td>
				
				</tr>
				
				<td style="font-size:18px; padding-bottom:10px;">Regards,
				<p>The Kitshare Team</p>

				</td>

				</tr>

				</table>

				<table width="940" style="    margin: 0px auto;
				    background-color:#ddd;
				    padding: 5px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
				</tr>
				</table>
				</div>

				</body>
				</html>
				';


				$mail_body = $msg;
		
		//$to= 'sanidha@gmail.com';
		$to= $cart_details[0]['renter_email'];				
		$subject = "Your order has been cancelled";		
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
		
		$insert_array = array("type"=>"Order cancelled",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Order cancelled");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
		
	}
	//Owner Cancel Mail to Owner
	public function SendOwnerCancelMailOwner($order_id='')
	{
		
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'];
			$cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'];
			$cart_details[0]['renter_lastname'] ='' 	;
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
				<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
				</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
				<tr>
				<td style="font-size:20px; padding-bottom:10px;">
				Hi  '.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].' </td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">This is a confirmation email indicating you have cancelled reservation '.$order_id.' starting on '. date('d-m-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).'. </td>
				</tr>
				
				
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">When you cancel a rental, you&#39;ll get an automated review on your profile saying that you cancelled. After the first time you cancel, there will be a $50 ex GST penalty fee per cancellation. Any applicable cancellation fee are automatically deducated from your next rental payout. </td>
				
				</tr>

				<tr>
				<td style="font-size:18px; padding-bottom:10px;">  For more information on cancellation, please see our <a href="'.$web_url.'faq" target="_blank" >FAQ</a>. </td>
				
				</tr>
				
				<td style="font-size:18px; padding-bottom:10px;">Regards,
				<p>The Kitshare Team</p>

				</td>

				</tr>

				</table>

				<table width="940" style="    margin: 0px auto;
				    background-color:#ddd;
				    padding: 5px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
				</tr>
				</table>
				</div>

				</body>
				</html>
				';

		$mail_body = $msg;
		$to= $cart_details[0]['primary_email_address'];				
		//$to= 'sanidha@gmail.com';
		$subject = "Order Cancelled";		
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
		
		$insert_array = array("type"=>"Order cancelled",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Order cancelled");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
		
	}

	public function RenterCancelMail($order_id)
	{
		$this->RenterCancelMailtoOwner($order_id);
		sleep(10);
		$this->RenterCancelMailtoRenter($order_id);
	}
	//Mail when renter cancells order to owner mail
	public function RenterCancelMailtoOwner($order_id)
	{
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		// echo "<pre>";
		// print_r($cart_details);die;
		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'] 	;
			$cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'] 	;
			$cart_details[0]['renter_lastname'] ='' 	;
		}
		// echo "<pre>";
		// print_r($cart_details);die;
			$msg= '<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="margin: 0px auto;background-color:#095cab;padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
					</tr>
				</table>
				<table width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
						<td style="font-size:20px; padding-bottom:10px;">
							Hi  '.$cart_details[0]['app_user_first_name'].'</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">
							We regret to inform you that your Renter cancelled reservation <b>'.$order_id.'</b> starting on '. date('d-m-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).'. Per our cancellation policy, you have the option to request a cancellation fee of up to 50% of the Rental value if cancelled within 48hrs or 100% of the rental value if cancelled within 24hrs of the Pickup time.
						</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">For more information on cancellation, please see our <a href="'.$web_url.'faq" target="_blank" >FAQ</a>. </td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">Your calendar has also been updated to show that the previously booked dates are now available.</td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">
							Regards,
							<p>The Kitshare Team</p>
						</td>
					</tr>
				</table>
				<table width="940" style="margin: 0px auto;background-color:#ddd;padding: 5px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
					</tr>
				</table>
				</div>
				';

		$mail_body = $msg;
		$to= $cart_details[0]['primary_email_address'];				
		//$to= 'sanidha@gmail.com';
		$subject = "Your order has been cancelled";		
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
		
		$insert_array = array("type"=>"Your order has been cancelled",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Your order has been cancelled");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
	}

	//Mail when renter cancells order to  renter mail
	public function RenterCancelMailtoRenter($order_id)
	{
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'] 	;
			$cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'] 	;
			$cart_details[0]['renter_lastname'] ='' 	;
		}
		
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		// echo "<pre>";
		// print_r($cart_details);die;
			$msg= '<div style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="margin: 0px auto;background-color:#095cab;padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
					</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
						<td style="font-size:20px; padding-bottom:10px;">
						Hi '. $cart_details[0]['renter_firstname'].'</td>
					</tr>			
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">				
							This is a confirmation email indicating you have cancelled reservation <b>'.$order_id.'</b> starting on '.date('d-m-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).'.
						</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">				
							Per our cancellation policy, the Owner may request a cancellation fee of up to 50% of the Rental value if cancelled within 48hrs or 100% of the rental value if cancelled within 24hrs of the Pickup time.
						</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">
						When you cancel a rental, you\'ll get an automated review on your profile saying that you cancelled.  For more information on cancellation, please see our <a href="'.$web_url.'faq" target="_blank" >FAQ</a></td>				
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">
							Regards,
							<p>The Kitshare Team</p>
						</td>
					</tr>
				</table>
				<table width="940" style="margin: 0px auto;background-color:#ddd;padding: 5px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
					</tr>
				</table>
				</div>
				';

				$mail_body = $msg;
		$to= $cart_details[0]['renter_email'];				
		// $to= 'singhaniagourav@gmail.com';
		$subject = "Order cancelled";		
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
		
		
		$insert_array = array("type"=>"Order cancelled",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Order cancelled");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
		
		
		
	}
	public function Gearaveragevalue($sub_categoryname)
	{
		$sub_categoryname =  urldecode($sub_categoryname );
		$sub_categoryname =  htmlspecialchars(urldecode($sub_categoryname ),ENT_QUOTES);
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
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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
			$insert_cron = array(

				'type' => 'Order Contract',
				'date_time' => date('Y-m-d H:i:m'),
				'order_id' => $order_id,
				'status' => 'Contract',
				);

				$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
				$response['status'] = 200;
				$response['status_message'] = 'Order marked as Contact  successfully';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
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

	public function OrderMarkCompleted()
	{

		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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

				$this->ReminderForReviewOrders($post_data['order_id']);
				$insert_cron = array(

				'type' => 'Order Completed',
				'date_time' => date('Y-m-d H:i:m'),
				'order_id' => $post_data['order_id'],
				'status' => 'Completed',
				);

				$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
				$response['status'] = 200;
				$response['status_message'] = 'Order marked as Completed  successfully by owner';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
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
	public function OrderMarkCompletedRenter()
	{

		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		
		if(is_array($post_data) && count($post_data)>0 ){
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);	
		if ($app_user_id != '') {
			$Cart_details = $this->home_model->OwnerOrderSummary($post_data['order_id']);
			$query =  $this->common_model->GetAllWhere('ks_user_gear_payments', array('gear_order_id'=>$post_data['order_id'] , 'payment_type' =>'Deposite Payment'));
			$deposite_Details =  $query->row();
			
			if (!empty($deposite_Details)) {
				if ($deposite_Details->transaction_id!= '') {
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

						$result = $gateway->transaction()->void($deposite_Details->transaction_id);
						$update_payment_status = array('deposite_status'=> 'Void');
						$this->common_model->UpdateRecord($update_payment_status,"ks_user_gear_rent_details","order_id",$post_data['order_id']);

						$update_deposite_status = array('status'=>'Void');
						$this->common_model->UpdateRecord($update_deposite_status,"ks_user_gear_payments","user_gear_payment_id",$deposite_Details->user_gear_payment_id);
					
				}
			}
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
				$this->ReminderForReviewOrders($post_data['order_id']);
			//

				$insert_cron = array(

				'type' => 'Order Completed by renter',
				'date_time' => date('Y-m-d H:i:m'),
				'order_id' => $post_data['order_id'],
				'status' => 'Completed',
				);

				$this->common_model->InsertData('ks_crone_log' ,$insert_cron);
				$response['status'] = 200;
				$response['status_message'] = 'Order marked as Completed  successfully by Renter';
				$json_response = json_encode($response);
				echo $json_response;
				exit;
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

	// Send ENTAL COMPLETED, REVIEW OWNER / RENTER MAil 

	public function ReminderForReviewOrders($order_id)
	{
		$this->OwnerReminderForReviewOrders($order_id);
		$this->RenterReminderForReviewOrders($order_id);
	}

	public function OwnerReminderForReviewOrders($order_id)
	{
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		
		if ($cart_details[0]['owner_show_business_name'] == 'y') {
			 $cart_details[0]['app_user_first_name']  = $cart_details[0]['owner_bussiness_name'];
			 $cart_details[0]['app_user_last_name']  = '';
		}else{
			$cart_details[0]['app_user_first_name']  = $cart_details[0]['app_user_first_name'] .' '. $cart_details[0]['app_user_last_name'] ; 
			$cart_details[0]['app_user_last_name']  = '' ; 
		}
		if ($cart_details[0]['renter_show_business_name'] == 'y') {
			 $cart_details[0]['renter_firstname']  = $cart_details[0]['renter_bussiness_name'];
			 $cart_details[0]['renter_lastname']  = '';
		}else{

			 $cart_details[0]['renter_firstname'] =  $cart_details[0]['renter_firstname'] .' ' . $cart_details[0]['renter_lastname']  ;
			 $cart_details[0]['renter_lastname']  = '';
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
				<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
				</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
				<tr>
				<td style="font-size:20px; padding-bottom:10px;">
				Hi '.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].' </td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">You have 14 days to write a review for  <b>'.$cart_details[0]['renter_firstname'].' '.$cart_details[0]['renter_lastname'].'</b>.</td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">Write a review to show your gratitude or provide helpful feedback.</td>

				</tr>
				
				
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">Because reviews are shared publicly on Kitshare profiles, it&#39;s important that you don&#39;t include any personal information like an address or last name.</td>

				</tr>

				
				
				<td style="font-size:18px; padding-bottom:10px;">Thanks for sharing your thoughts,
				<p>The Kitshare Team</p>

				</td>

				</tr>
				<tr>
				    <td>
				     <a href="'.$web_url.'rentals-dashboard"> <button  style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">Write a Review</button> </a>
				
				    </td>
				</tr>
				</table>

				<table width="940" style="    margin: 0px auto;
				    background-color:#ddd;
				    padding: 5px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
				</tr>
				</table>
				</div>

				</body>
				</html>
				';

		  $mail_body = $msg; 
		$to = $cart_details['0']['primary_email_address'];
		// $to= 'singhaniagourav@gmail.com';
		$subject = "Rental Completed, Write a review";		
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
		
		$insert_array = array("type"=>"Rental Completed, Write a review",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Rental Completed, Write a review");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
	}

	public function RenterReminderForReviewOrders($order_id)
	{
		
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}
		
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		if ($cart_details[0]['owner_show_business_name'] == 'y') {
			 $cart_details[0]['app_user_first_name']  = $cart_details[0]['owner_bussiness_name'];
			 $cart_details[0]['app_user_last_name']  = '';
		}else{
			$cart_details[0]['app_user_first_name']  = $cart_details[0]['app_user_first_name'] .' '. $cart_details[0]['app_user_last_name'] ; 
			$cart_details[0]['app_user_last_name']  = '' ; 
		}
		if ($cart_details[0]['renter_show_business_name'] == 'y') {
			 $cart_details[0]['renter_firstname']  = $cart_details[0]['renter_bussiness_name'];
			 $cart_details[0]['renter_lastname']  = '';
		}else{

			 $cart_details[0]['renter_firstname'] =  $cart_details[0]['renter_firstname'] .' ' . $cart_details[0]['renter_lastname']  ;
			 $cart_details[0]['renter_lastname']  = '';
		}
			$msg= '<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="margin: 0px auto;background-color:#095cab;padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
					</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
					<tr>
						<td style="font-size:20px; padding-bottom:10px;">
						Hi '.$cart_details[0]['renter_firstname'].'  '.$cart_details[0]['renter_lastname'].'</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">You have 14 days to write a review for  <b>'.$cart_details[0]['app_user_first_name'].''.trim($cart_details[0]['app_user_last_name']).'</b>.</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">Write a review to show your gratitude or provide helpful feedback.</td>
					</tr>
					<tr>
						<td style="font-size:18px; padding-bottom:10px;">Because reviews are shared publicly on Kitshare profiles, it&#39;s important that you don&#39;t include any personal information like an address or last name.</td>
					</tr>
				<tr>				
					<td style="font-size:18px; padding-bottom:10px;">Thanks for sharing your thoughts,
					<p>The Kitshare Team</p>
					</td>
				</tr>
					<tr>
				    <td>
				     <a href="'.$web_url.'rentals-dashboard"> <button  style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">Write a Review</button> </a>
				    </td>
				</tr>
				</table>
				<table width="940" style="margin: 0px auto;background-color:#ddd;padding: 5px 0px;text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
						<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
					</tr>
				</table>
				</div>';

		   $mail_body = $msg;
		$to= $cart_details[0]['renter_email'];
		//$to = "sanidha@gmail.com";
		$subject = "Rental Completed, Write a review";		
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
		
		$insert_array = array("type"=>"Rental Completed, Write a review (Reminder)",
							  "date_time"=>date("Y-m-d H:i:s"),
							  "order_id"=>$order_id,
							  "status"=>"Rental Completed, Write a review (Reminder)");
		
		$this->common_model->InsertData('ks_crone_log' ,$insert_array);
	}

	// Kit Listing
	public function Kitlisting()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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
				
				/*$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;*/
				//header('HTTP/1.1 401 Unauthorized');
				exit();
		}
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();	
		}


	}
	// Home User List
	public function homeUserDetails()
	{
		$user_details = $this->home_model->homeUserDetails();
		$i= 0;
		foreach ($user_details as  $value) {
				
				if ($value->user_profile_picture_link =='') {
					$user_details[$i]->user_profile_picture_link =  BASE_URL."server/assets/images/profile.png" ;
				}
				if ($value->show_business_name =='Y') {
					$user_details[$i]->app_user_first_name = $user_details[$i]->bussiness_name ;	
					$user_details[$i]->app_user_last_name = '' ;	
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
		error_reporting(0);
		$post_data  = json_decode(file_get_contents("php://input"),true);
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);	
		if ($app_user_id != '') {
			$order_id = $post_data['order_id'];
			$sql = "SELECT o_d.* ,g_tbl.owner_app_show_business_name,g_tbl.renter_app_show_business_name,g_tbl.gear_name,g_tbl.owner_app_bussiness_name ,g_tbl.owner_app_show_business_name, g_tbl.renter_app_user_first_name,g_tbl.renter_app_user_last_name FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_order_description As g_tbl  ON g_tbl.order_id  = o_d.order_id  WHERE o_d.order_id = '".$order_id."'  GROUP By  user_gear_rent_detail_id "	;
			$order_details   = $this->common_model->get_records_from_sql($sql);

			foreach ($order_details as  $value) {
			 $users[] = 	$value->create_user;
			}
			
			$this->db->from('ks_user_gear_rent_master g_r_m');
			$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.user_gear_rent_id = g_r_m.user_gear_rent_id','inner');
			$this->db->join('ks_gear_order_location as u_add', 'u_add.order_id =g_r_d.order_id AND    g_r_m.user_address_id = u_add.user_address_id','inner');
			$query = $this->db->get();
			$data['addrsss'] =  $query->row();
			$url = '';
			$data['six_digit_random_number'] = $order_id; 
			$this->uri->segment('3');
			$data['order_details'] = $order_details;
			//print_r($data);
			 $mail_body = $this->load->view('order-template',$data ,true);
			 print_r($mail_body);
			die;
			$this->load->helper('pdf');
			$pdf_url =  gen_pdf($mail_body,$order_id);
			//echo $pdf_url ;
			$response['status'] = 200;
			$response['status_message'] = 'PDF URL';
			$response['result'] =$pdf_url ;
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
	}
	public function generateInvoiceMail($order_id)
	 {error_reporting(0);

		
			// $order_id = $post_data['order_id'];
			 $sql = "SELECT o_d.* ,
			 o_d.create_user AS renter,
			 g_tbl.replacement_value_aud_inc_gst,
			 g_tbl.per_day_cost_aud_ex_gst,
			 g_tbl.renter_app_bussiness_name,
			 g_tbl.owner_app_show_business_name,
			 g_tbl.renter_app_show_business_name,
			 g_tbl.gear_name,
			 g_tbl.owner_app_bussiness_name,
			 g_tbl.owner_app_show_business_name, 
			 g_tbl.renter_app_user_first_name,
			 g_tbl.renter_app_user_last_name, 
			 g_tbl.owner_app_user_last_name AS app_user_last_name,  
			 g_tbl.owner_app_user_first_name AS app_user_first_name,
			 usr.show_business_name,
			 usr.bussiness_name,usr.app_user_first_name AS fname,usr.app_user_last_name AS lname
			 FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_order_description As g_tbl  ON g_tbl.user_gear_desc_id = o_d.user_gear_desc_id
			 INNER JOIN ks_users AS usr ON o_d.create_user=usr.app_user_id
			 WHERE o_d.order_id = '".$order_id."' AND  g_tbl.order_id='".$order_id."' GROUP By  o_d.user_gear_rent_detail_id ";

			$order_details   = $this->common_model->get_records_from_sql($sql);
			
			$i = 0 ;
			foreach ($order_details as  $value)	 {
			 $users[] = 	$value->create_user;
			 
			 
			 if($value->show_business_name == 'Y' && $value->bussiness_name!=""){
				if($value->bussiness_name!="")
					$order_details[$i]->business_name = $value->bussiness_name ;  
			}else
				$order_details[$i]->business_name = "";
			 
			 $order_details[$i]->renter_firstname =  $value->fname;  
			 $order_details[$i]->renter_lastname =  $value->lname;  
		
			 if($i==0){
				
				$sql = "SELECT k_u_a.*,k_s.ks_state_name,k_sub.suburb_name ,k_coun.ks_country_name FROM ks_user_address As k_u_a INNER JOIN ks_states AS k_s ON  k_u_a.ks_state_id = k_s.ks_state_id  INNER JOIN ks_suburbs As k_sub ON k_u_a.ks_suburb_id = k_sub.ks_suburb_id  INNER JOIN ks_countries As k_coun ON k_u_a.ks_country_id = k_coun.ks_country_id  WHERE k_u_a.app_user_id  = '".$value->renter."' AND k_u_a.is_active ='Y' AND k_u_a.default_address='1'";	
				$query = $this->db->query($sql);
				$data['addrsss'] =  $query->row();
				
			 }

			 $i++;
			}
			
			
			$url = '';
			$data['six_digit_random_number'] = $order_id; 
			$this->uri->segment('3');
			$data['order_details'] = $order_details;
			
			$mail_body = $this->load->view('order-template',$data ,true);

			$this->load->helper('pdf');
			$pdf_url =  gen_pdf($mail_body,$order_id);

			return $pdf_url ;

		
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
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		if (array_key_exists('token', $post_data)) {
				$token 			= $post_data['token'];
				$app_user_id = $this->userinfo($token);	
		}else{
			$app_user_id = '';
		}
		
		
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
					$response['status_message'] = 'Already view added to this gear';
					$response['view_count'] = $view_count;
					$json_response = json_encode($response);
					echo $json_response;
					exit;
				}
		}else{

				$view_count =  $this->GETGearViewCount($post_data['user_gear_desc_id']);
				$app_user_id = '';
				$response['status'] = 200;
				$app_user_id = '';
				$response['view_count'] = $view_count;
				//$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;				
				exit();
		}
		
		}else{
			
			header('HTTP/1.1 200 Success');
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
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);	
		if ($app_user_id != '') {
			$input_data = array(
								'order_id'=> $post_data['order_id'] ,
								'type' => $post_data['type'],
								'issue' => $post_data['issue'],
								'app_user_id' => $post_data['app_user_id'],
								'created_date'=>date('Y-m-d'),
								'created_time'=>date('H:i:s'),
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

	// Fetch  Issues List

	public function GetOrderIssueList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);	
		if ($app_user_id != '') {
			$where_clause = array('order_id'=>$post_data['order_id']);
			$query = $this->common_model->GetAllWhere('ks_order_issues',$where_clause);
			$issue_list =  $query->result();
			if (!empty($issue_list)) {
					$api_logs  =array(
							'api_name'  =>'GetOrderIssueList',
							'type' 		=>'success',
							'status' 	=>'success',
							'order_id' 	=>"",
							'error_message' =>"",
							'date_time' =>date('Y-m-d H:i:m'),
					);
					$this->common_model->InsertData('ks_api_logs',$api_logs);
					$response['status'] = 200;
					$response['status_message'] = ' issue list found';
					$response['result'] = $issue_list;
					$json_response = json_encode($response);
					echo $json_response;
					//header('HTTP/1.1 200 authorized');
					exit();
			}else{
					$api_logs  =array(
							'api_name'  =>'GetOrderIssueList',
							'type' 		=>'error',
							'status' 	=>'errro',
							'order_id' 	=>"",
							'error_message' =>json_encode("No data found"),
							'date_time' =>date('Y-m-d H:i:m'),
					);
					$this->common_model->InsertData('ks_api_logs',$api_logs);
					$response['status'] = 404;
					$response['status_message'] = 'No issue list found';
					$json_response = json_encode($response);
					echo $json_response;
				//	header('HTTP/1.1 401 Unauthorized');
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

	// Fetch Single List

	public function GetSingleOrderIssueList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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
					//header('HTTP/1.1 200 Unauthorized');
					exit();
			}else{
					$response['status'] = 404;
					$response['status_message'] = 'No issue list Details';
					$json_response = json_encode($response);
					echo $json_response;
					//header('HTTP/1.1 200 Unauthorized');
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

	// Edit Single Listings

	public function EditIssueListings($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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

	// Add User Rating
	public function AddUserRating()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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

	public function TierFunction()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id != '') {

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
		}else{
			
			header('HTTP/1.1 400 Session Expired');
			exit();
		}

		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}		
	}


	public function CloseUserListing()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
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

	/// function CHeck User Checkout Details 

	public function Usercheckoutcheck()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){

		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$query =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
			$user_details =  $query->row();
			if ($user_details->mobile_number_verfiy != 1) {
					$app_user_id = '';
					$api_logs  =array(
									'api_name'  =>'Usercheckoutcheck',
									'type' 		=> 'Mobile Number check Error',
									'status' 	=>'error',
									'order_id' 	=>'',
									'error_message' =>json_encode('Mobile number is not verified'),
									'date_time' =>date('Y-m-d H:i:m'),
							);
					$this->common_model->InsertData('ks_api_logs',$api_logs);
					$response['status'] = 400;
					$response['status_message'] = 'Mobile number is not verified';
					$json_response = json_encode($response);
					echo $json_response;
					exit();
			}
			if ($user_details->aus_post_verified !='Y') {
					$app_user_id = '';
					$api_logs  =array(
									'api_name'  =>'Usercheckoutcheck',
									'type' 		=> 'Digital ID check Error',
									'status' 	=>'error',
									'order_id' 	=>'',
									'error_message' =>json_encode('Digital ID is not verified'),
									'date_time' =>date('Y-m-d H:i:m'),
							);
					$this->common_model->InsertData('ks_api_logs',$api_logs);
					$response['status'] = 401;
					$response['status_message'] = ' Digital ID is not verified';
					$json_response = json_encode($response);
					echo $json_response;
					exit();
			}
			$Cart_details = $this->home_model->getUserCart1($app_user_id);
			$sum = 0 ;
			$query =  $this->common_model->GetAllWhere('ks_settings','');
			$settings =  $query->row();
			foreach ($Cart_details as $value) {
					if ($value['ks_insurance_category_type_id'] == '1') {
						if( $value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value){
							$app_user_id = '';
							$api_logs  =array(
									'api_name'  =>'Usercheckoutcheck',
									'type' 		=> 'Cart value check Error',
									'status' 	=>'error',
									'order_id' 	=>'',
									'error_message' =>json_encode('The Replacement value for the total cart must not exceed  '.$settings->max_replacement_value .' ex GST'),
									'date_time' =>date('Y-m-d H:i:m'),
							);
							$this->common_model->InsertData('ks_api_logs',$api_logs);
							$response['status'] = 401;
							$response['status_message'] = ' The Replacement value for the total cart must not exceed  '.$settings->max_replacement_value .' ex GST';
							$json_response = json_encode($response);
							echo $json_response;
							exit();
						}  
					 }
			}
				$app_user_id = '';
				$api_logs  =array(
						'api_name'  =>'Usercheckoutcheck',
						'type' 		=> 'Success',
						'status' 	=>'success',
						'order_id' 	=>'',
						'error_message' =>'',
						'date_time' =>date('Y-m-d H:i:m'),
				);
				$this->common_model->InsertData('ks_api_logs',$api_logs);
				
				$response['status'] = 200;
				$response['status_message'] = 'User can Checkout';
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			
		}else{
				$api_logs  =array(
									'api_name'  =>'Usercheckoutcheck',
									'type' 		=> 'Login Error',
									'status' 	=>'error',
									'order_id' 	=>'',
									'error_message' =>'Alreday Logged In',
									'date_time' =>date('Y-m-d H:i:m'),
							);
				$this->common_model->InsertData('ks_api_logs',$api_logs);
				/*$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;*/
				header('HTTP/1.1 400 Session Expired');
				exit();
		}	
		
		}else{
			$api_logs  =array(
									'api_name'  =>'Usercheckoutcheck',
									'type' 		=> 'Login Error',
									'status' 	=>'error',
									'order_id' 	=>'',
									'error_message' =>'Alreday Logged In',
									'date_time' =>date('Y-m-d H:i:m'),
							);
			$this->common_model->InsertData('ks_api_logs',$api_logs);
			header('HTTP/1.1 200 Success');
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
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
			$rent_days_details = array();
			foreach ($Cart_details as  $value) {
				//echo $value['user_gear_desc_id'] ;
				$this->db->select('*'); 
				$this->db->from('ks_gear_unavailable');
				$this->db->where('user_gear_description_id',$value['user_gear_desc_id']);
				
				$where = "(`unavailable_from_date`<=CURDATE() AND `unavailable_to_date`>=CURDATE()) OR (`unavailable_from_date`>=CURDATE() AND `unavailable_to_date`>=CURDATE())";
				
				$this->db->where($where);
				
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
				$this->db->where("(order_status != 5 AND order_status != 6 AND  order_status != 8 )");
				$where = "is_payment_completed='Y' AND gear_rent_end_date>=CURDATE()";
				//$this->db->where('is_payment_completed','Y');
				$this->db->where($where);
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
	
	public function UnreadMessage()
	{
		
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		if (empty($post_data)) {
				$response['status'] = 200;
				$response['status_message'] = 'Unseen Message';
				$response['result'] = 0;
				$json_response = json_encode($response); 
				echo $json_response;
				exit();
		}
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != ''){

			$query =  $this->common_model->GetAllWhere('ks_user_chatmessage',array('receiver_id'=>$app_user_id ,'is_seen'=>'unseen'));
			$unseen_message_array =  $query->result();
			if (!empty($unseen_message_array)) {
				$response['status'] = 200;
				$response['status_message'] = 'Unseen Message';
				$response['result'] = count($unseen_message_array);
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			}else{
				$response['status'] = 200;
				$response['status_message'] = 'Unseen Message';
				$response['result'] = 0;
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

	//Slug for gears 

	public function CreateGearSlug()
	{
		$query = $this->common_model->GetAllWhere('ks_user_gear_description',array());
		$result = $query->result();
		$string = '(1234) S*m@#ith S)&+*t `E}{xam)ple?>land 1!_2)#3)(*4""5';

        /*** call the function and strip the bad characters ***/
        
		foreach ($result as  $value) {
			 
			// echo $value->gear_name;
			// echo "<br>";
			$value->gear_name = trim($value->gear_name);
			// $value->gear_name = str_replace(' ', "-", $value->gear_name);	
			$value->gear_name =  $this->alphanumericAndSpace($value->gear_name  );
			$value->gear_name = str_replace(' ', "-", $value->gear_name );		
			$gear_slug_name =  $value->gear_name .'-'.$value->user_gear_desc_id;
			
			$this->common_model->UpdateRecord(array('gear_slug_name'=>$gear_slug_name), 'ks_user_gear_description' ,'user_gear_desc_id' , $value->user_gear_desc_id);

		}

	}

	 function alphanumericAndSpace( $string )
    {
        return preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
    }


    public function AddInsuranceData()
    {
    	header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    	
    	$post_data = json_decode($this->input->post('insurance_data'),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
    	$token 	= $this->input->post('token');
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ){
			if(isset($_FILES['image'])){
				$image 			= $_FILES['image'];
			}else{
				$result['status'] = '400' ;
				$result['status_message'] = 'Plese Select a Renter Insurance file' ;
				echo json_encode($result,true);
				exit;
			}
		
			//Renter Image Upload Code
			$image =  $this->InsuranceImage($image);
			if($image['status'] == 1){
				$image_url = $image['image_url'];
			}else{
				$image_url = '';
			}


			if ($post_data['renter_insurance_provided'] == 1) {
				$post_data['renter_insurance_provided'] = 'Yes';
			}else{
				$post_data['renter_insurance_provided'] = 'No';
			}

			if ($post_data['owner_insurance_provided'] == 1) {
				$post_data['owner_insurance_provided'] = 'Yes';
			}else{
				$post_data['owner_insurance_provided'] = 'No';
			}
			
			if(array_key_exists('renter_insurance_amount', $post_data)){

				if($post_data['renter_insurance_amount']>0)
					$insurance_amount =  $post_data['renter_insurance_amount'];
				else
					$insurance_amount =  0;
			}else{
				$insurance_amount =  0;

			}	
			
			$owner_insurance_amount = 0;
			
			
			if(array_key_exists('owner_insurance_amount', $post_data)){

					if($post_data['owner_insurance_amount']>0)
						$owner_insurance_amount =  $post_data['owner_insurance_amount'];
					else
						$owner_insurance_amount =  0;
			}else{
				$owner_insurance_amount =  0;

			}	
			
			if(array_key_exists('owner_insurance_percentage', $post_data)){

					if($post_data['owner_insurance_percentage']>0)
						$owner_insurance_percentage =  $post_data['owner_insurance_percentage'];
					else
						$owner_insurance_percentage =  0;
			}else{
				$owner_insurance_percentage =  0;

			}	
				
			
			if ($post_data['renter_insurance_provided'] == 'Yes' &&  $post_data['owner_insurance_provided'] == 'No') {			
				
				
				$value=  array(
								'ks_user_certificate_currency_desc' => $post_data['insurance_description'],
								'ks_user_certificate_currency_exp' => date('Y-m-d',strtotime($post_data['expire_date'])),
								'ks_user_certificate_currency_start'=>date('Y-m-d',strtotime($post_data['start_date'])),
								'renter_insurance_provided' => $post_data['renter_insurance_provided'],
								'owner_insurance_amount' => $owner_insurance_amount,
								'renter_insurance_amount'=>$insurance_amount,
								'owner_insurance_amount'=>'0',
								'owner_insurance_percentage' => '0',
								'image_url'=>$image_url,
								'app_user_id' => $app_user_id,
								'create_date' => date('Y-m-d'),
								'create_user' => $app_user_id,
								'owner_insurance_status'=>'0',
								'renter_insurance_status'=>'0',
								'is_visiible'=> 'Y',
								'renter_is_active'=>'0',
								'owner_is_active'=>'1',
									

							);
				$insurance_id  =  $this->common_model->AddRecord($value,'ks_user_insurance_proof');
				$query  =  $this->common_model->GetAllWhere('ks_user_insurance_proof', array('app_user_id' => $app_user_id ,'user_insurance_proof_id'=>$insurance_id));
				$response_data =  $query->row();
					

					if ($response_data->renter_insurance_provided =='Yes') {
						$response_data->renter_insurance_provided = true; 
					}else{
						$response_data->renter_insurance_provided  = false; 
					}

					if ($response_data->renter_is_active =='0') {
						$response_data->renter_is_active = true; 
					}else{
						$response_data->renter_is_active = false; 
					}

					if ($response_data->owner_is_active =='0') {
						$response_data->owner_is_active = true; 
					}else{
						$response_data->owner_is_active = false; 
					}

					if ($response_data->is_visiible =='Y') {
						$response_data->is_visiible = true; 
					}else{
						$response_data->is_visiible = false; 
					}
					if ($response_data->is_visiible =='Y') {
						$response_data->is_visiible = true; 
					}else{
						$response_data->is_visiible = false; 
					}
					if ($response_data->owner_insurance_status == 0 )  {
						$response_data->owner_insurance_status = 'Pending'; 
					}elseif ($response_data->owner_insurance_status == 2 ) {
						$response_data->owner_insurance_status = 'Expired'; 
					}else{
						$response_data->owner_insurance_status = 'Approved'; 
					}
					if ($response_data->renter_insurance_status == 0 )  {
						$response_data->renter_insurance_status = 'Pending'; 
					}elseif ($response_data->renter_insurance_status == 2 ) {
						$response_data->renter_insurance_status = 'Expired'; 
					}else{
						$response_data->renter_insurance_status = 'Approved'; 
					}
					if ($response_data->owner_insurance_status == 0 )  {
						$response_data->owner_insurance_status = 'Pending'; 
					}elseif ($response_data->owner_insurance_status == 2 ) {
						$response_data->owner_insurance_status = 'Expired'; 
					}else{
						$response_data->owner_insurance_status = 'Approved'; 
					}
					if ($response_data->renter_insurance_status == 0 )  {
						$response_data->renter_insurance_status = 'Pending'; 
					}elseif ($response_data->renter_insurance_status == 2 ) {
						$response_data->renter_insurance_status = 'Expired'; 
					}else{
						$response_data->renter_insurance_status = 'Approved'; 
					}

					$paymentDate = date('Y-m-d');
					$paymentDate=date('Y-m-d', strtotime($paymentDate));
					$contractDateBegin = date('Y-m-d', strtotime($response_data->ks_user_certificate_currency_start));
					$contractDateEnd = date('Y-m-d', strtotime($response_data->ks_user_certificate_currency_exp));
					if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
					  	$response_data->is_active = true; 	
					}else{
					   	$response_data->is_active = false; 	
					}
					$response_data->user_insurance_proof_id = (int) $response_data->user_insurance_proof_id; 
					$response_data->app_user_id = (int) $response_data->app_user_id; 
					// $response_data->insurance_percentage = (int) $response_data->insurance_percentage; 

					$response_data->owner_insurance_percentage = (int) $response_data->owner_insurance_percentage; 
					$response_data->owner_insurance_amount =  number_format((float)(  $response_data->owner_insurance_amount), 2, '.', '') ; 
					$response_data->renter_insurance_amount = (int) $response_data->renter_insurance_amount; 
					$response_data->owner_insurance_provided   = false; 
				
					$this->SendAdminMaill($app_user_id);
					$response=array("status"=>200,
								"result"=>$response_data,
								"status_message"=>"Insurance added successffully",
						);
					echo json_encode($response);
					exit();

			}else if($post_data['renter_insurance_provided'] == 'No' &&  $post_data['owner_insurance_provided'] == 'Yes'){
					$value=  array(
								'ks_user_certificate_currency_desc' => $post_data['insurance_description'],
								'ks_user_certificate_currency_exp' => date('Y-m-d',strtotime($post_data['expire_date'])),
								'ks_user_certificate_currency_start'=>date('Y-m-d',strtotime($post_data['start_date'])),
								'renter_insurance_provided' => $post_data['renter_insurance_provided'],
								'owner_insurance_provided' => $post_data['owner_insurance_provided'],
								'renter_insurance_amount'=>0,
								'owner_insurance_amount'=>$owner_insurance_amount,
								'owner_insurance_percentage' =>$owner_insurance_percentage,
								'image_url'=>$image_url,
								'app_user_id' => $app_user_id,
								'create_date' => date('Y-m-d'),
								'create_user' => $app_user_id,
								'owner_insurance_status'=>'0',
								'renter_insurance_status'=>'0',
								'is_visiible'=> 'Y',
								'renter_is_active'=>'1',
								'owner_is_active'=>'0',
							);
					$insurance_id  =  $this->common_model->AddRecord($value,'ks_user_insurance_proof');
					$query  =  $this->common_model->GetAllWhere('ks_user_insurance_proof', array('app_user_id' => $app_user_id ,'user_insurance_proof_id'=>$insurance_id));
					$response_data =  $query->row();
					

					if ($response_data->owner_insurance_provided =='Yes') {
						$response_data->owner_insurance_provided = true; 
					}else{
						$response_data->owner_insurance_provided = false; 
					}
					if ($response_data->renter_insurance_provided	 =='Yes') {
						$response_data->renter_insurance_provided	 = true; 
					}else{
						$response_data->renter_insurance_provided	 = false; 
					}
					if ($response_data->owner_is_active =='0') {
						$response_data->owner_is_active = true; 
					}else{
						$response_data->owner_is_active = false; 
					}
					if ($response_data->renter_is_active =='0') {
						$response_data->renter_is_active = true; 
					}else{
						$response_data->renter_is_active = false; 
					}
					if ($response_data->is_visiible =='Y') {
						$response_data->is_visiible = true; 
					}else{
						$response_data->is_visiible = false; 
					}
					if ($response_data->owner_insurance_status == 0 )  {
						$response_data->owner_insurance_status = 'Pending'; 
					}elseif ($response_data->owner_insurance_status == 2 ) {
						$response_data->owner_insurance_status = 'Expired'; 
					}else{
						$response_data->owner_insurance_status = 'Approved'; 
					}
					if ($response_data->renter_insurance_status == 0 )  {
						$response_data->renter_insurance_status = 'Pending'; 
					}elseif ($response_data->renter_insurance_status == 2 ) {
						$response_data->renter_insurance_status = 'Expired'; 
					}else{
						$response_data->renter_insurance_status = 'Approved'; 
					}
					$paymentDate = date('Y-m-d');
					$paymentDate=date('Y-m-d', strtotime($paymentDate));
					$contractDateBegin = date('Y-m-d', strtotime($response_data->ks_user_certificate_currency_start));
					$contractDateEnd = date('Y-m-d', strtotime($response_data->ks_user_certificate_currency_exp));
					if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
					  	$response_data->is_active = true; 	
					}else{
					   	$response_data->is_active = false; 	
					}
					$response_data->user_insurance_proof_id = (int) $response_data->user_insurance_proof_id; 
					$response_data->app_user_id = (int) $response_data->app_user_id; 
					$response_data->owner_insurance_percentage = (int) $response_data->owner_insurance_percentage; 
					$response_data->renter_insurance_amount = (int) $response_data->renter_insurance_amount; 
					$response_data->owner_insurance_amount = number_format((float)($response_data->owner_insurance_amount), 2, '.', '') ; 
					

					$this->SendAdminMaill($app_user_id);
					$response=array("status"=>200,
								"result"=>$response_data,
								"status_message"=>"Insurance added successffully",
						);
					echo json_encode($response);
					exit();


			}else if ($post_data['renter_insurance_provided'] == 'Yes' &&  $post_data['owner_insurance_provided'] == 'Yes') {

					$value=  array(
								'ks_user_certificate_currency_desc' => $post_data['insurance_description'],
								'ks_user_certificate_currency_exp' => date('Y-m-d',strtotime($post_data['expire_date'])),
								'ks_user_certificate_currency_start'=>date('Y-m-d',strtotime($post_data['start_date'])),
								'renter_insurance_provided' => $post_data['renter_insurance_provided'],
								'owner_insurance_provided' => $post_data['owner_insurance_provided'],
								'renter_insurance_amount'=>$insurance_amount,
								'owner_insurance_amount'=>$owner_insurance_amount,
								'owner_insurance_percentage' => $owner_insurance_percentage,
								'image_url'=>$image_url,
								'app_user_id' => $app_user_id,
								'create_date' => date('Y-m-d'),
								'create_user' => $app_user_id,
								'owner_insurance_status'=>'0',
								'renter_insurance_status'=>'0',
								'is_visiible'=> 'Y',
								'renter_is_active'=>'0',
								'owner_is_active'=>'0',
							);
					$insurance_id  =  $this->common_model->AddRecord($value,'ks_user_insurance_proof');
					$query  =  $this->common_model->GetAllWhere('ks_user_insurance_proof', array('app_user_id' => $app_user_id ,'user_insurance_proof_id'=>$insurance_id));
					$response_data =  $query->row();
				

					if ($response_data->owner_insurance_provided =='Yes') {
						$response_data->owner_insurance_provided = true; 
					}else{
						$response_data->owner_insurance_provided = false; 
					}
					if ($response_data->renter_insurance_provided	 =='Yes') {
						$response_data->renter_insurance_provided	 = true; 
					}else{
						$response_data->renter_insurance_provided	 = false; 
					}
					if ($response_data->owner_is_active =='0') {
						$response_data->owner_is_active = true; 
					}else{
						$response_data->owner_is_active = false; 
					}
					if ($response_data->renter_is_active =='0') {
						$response_data->renter_is_active = true; 
					}else{
						$response_data->renter_is_active = false; 
					}
					if ($response_data->is_visiible =='Y') {
						$response_data->is_visiible = true; 
					}else{
						$response_data->is_visiible = false; 
					}
					if ($response_data->owner_insurance_status == 0 )  {
						$response_data->owner_insurance_status = 'Pending'; 
					}elseif ($response_data->owner_insurance_status == 2 ) {
						$response_data->owner_insurance_status = 'Expired'; 
					}else{
						$response_data->owner_insurance_status = 'Approved'; 
					}
					if ($response_data->renter_insurance_status == 0 )  {
						$response_data->renter_insurance_status = 'Pending'; 
					}elseif ($response_data->renter_insurance_status == 2 ) {
						$response_data->renter_insurance_status = 'Expired'; 
					}else{
						$response_data->renter_insurance_status = 'Approved'; 
					}
					$paymentDate = date('Y-m-d');
					$paymentDate=date('Y-m-d', strtotime($paymentDate));
					$contractDateBegin = date('Y-m-d', strtotime($response_data->ks_user_certificate_currency_start));
					$contractDateEnd = date('Y-m-d', strtotime($response_data->ks_user_certificate_currency_exp));
					if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
					  	$response_data->is_active = true; 	
					}else{
					   	$response_data->is_active = false; 	
					}
					$response_data->user_insurance_proof_id = (int) $response_data->user_insurance_proof_id; 
					$response_data->app_user_id = (int) $response_data->app_user_id; 
					$response_data->owner_insurance_percentage = (int) $response_data->owner_insurance_percentage; 
					$response_data->renter_insurance_amount = (int) $response_data->renter_insurance_amount; 
					$response_data->owner_insurance_amount = number_format((float)($response_data->owner_insurance_amount), 2, '.', ''); 
					

					$this->SendAdminMaill($app_user_id);
					$response=array("status"=>200,
								"result"=>$response_data,
								"status_message"=>"Insurance added successffully",
						);
					echo json_encode($response);
					exit();
			}

		}else{

			/*$response=array("status"=>400,
						"status_message"=>"User Not Valid",
						);
			echo json_encode($response);
			exit();*/
			header('HTTP/1.1 400 Session Expired');
			exit();
		}

		}else{
			
			
			header('HTTP/1.1 200 Success');
			exit();
		}		
    }
    // Add UserInsurane
    public function AddInsuranceData1()
    {
    	header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    	$token 			= $this->input->post('token');
    	$post_data = json_decode($this->input->post('insurance_data'),true);
		
		if(isset($_FILES['image'])){
			$image 			= $_FILES['image'];
		}else{
			$result['status'] = '400' ;
			$result['status_message'] = 'Plese Select a Renter Insurance file' ;
			echo json_encode($result,true);
			exit;
		}
		
		//Renter Image Upload Code
		$image =  $this->InsuranceImage($image);
		if($image['status'] == 1){
			$image_url = $image['image_url'];
		}else{
			$image_url = '';
		}

		
		

		if(array_key_exists('insurance_amount', $post_data)){

			$insurance_amount =  $post_data['insurance_amount'] ;
		}else{
			$insurance_amount =  '0';

		}
		if ($post_data['renter_insuance_provided'] == 1) {
			$post_data['renter_insuance_provided'] = 'Yes';
		}else{
			$post_data['renter_insuance_provided'] = 'No';
		}

		if ($post_data['owner_insurance_provided'] == 1) {
			$post_data['owner_insurance_provided'] = 'Yes';
		}else{
			$post_data['owner_insurance_provided'] = 'No';
		}
		
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ){
			$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',array('app_user_id'=>$app_user_id));
			$ddata = $query->row();
			if (!empty($ddata)) {
					$value=  array(
									'ks_user_certificate_currency_desc' => $post_data['insurance_description'],
									'ks_user_certificate_currency_exp' => date('Y-m-d',strtotime($post_data['expire_date'])),
									'renter_insuance_provided' => $post_data['renter_insuance_provided'],
									'owner_insurance_percentage' => $post_data['owner_insurance_percentage'],
									'owner_insurance_provided' => $post_data['owner_insurance_provided'],
									'owner_insurance_amount' => $post_data['owner_insurance_amount'],
									'image_url'=>$image_url,
									'start_date'=>date('Y-m-d',strtotime($post_data['start_date'])),
									'owner_insurance_status' => '0',
									'app_user_id' => $app_user_id,
									'insurance_amount'=>$insurance_amount,
									'create_date' => date('Y-m-d'),
									'is_active' => 'Y',
									'is_approved' => '0',
								 );
				$insurance_id  =  $this->common_model->AddRecord($value,'ks_user_insurance_proof');
				$query  =  $this->common_model->GetAllWhere('ks_user_insurance_proof', array('app_user_id' => $app_user_id));
				$response_data =  $query->result();
				$i = 0 ;
				foreach ($response_data as  $value) {
					
					if ($value->is_approved == 0 )  {
						 $response_data[$i]->is_approved_status_renter = 'Pending'; 
					}elseif ($value->is_approved == 2 ) {
						$response_data[$i]->is_approved_status_renter = 'Expired'; 
					}elseif ($value->is_approved == 3 ) {
						$response_data[$i]->is_approved_status_renter = 'Active'; 
					}elseif ($value->is_approved == 4 ) {
						$response_data[$i]->is_approved_status_renter = 'InActive'; 
					}else{
						$response_data[$i]->is_approved_status_renter = 'Approved'; 
					}	

					if ($value->owner_insurance_status == 0 )  {
						$response_data[$i]->is_approved_status_owner = 'Pending'; 
					}elseif ($value->owner_insurance_status == 2 ) {
						$response_data[$i]->is_approved_status_owner = 'Expired'; 
					}elseif ($value->is_approved == 3 ) {
						$response_data[$i]->is_approved_status_owner = 'Active'; 
					}elseif ($value->is_approved == 4 ) {
						$response_data[$i]->is_approved_status_owner = 'InActive'; 
					}else{
						$response_data[$i]->is_approved_status_owner = 'Approved'; 
					}

					if ($value->renter_insuance_provided =='Yes') {
						$response_data[$i]->renter_insuance_provided = true; 
					}else{
						$response_data[$i]->renter_insuance_provided = false; 
					}

					if ($value->owner_insurance_provided =='Yes') {
						$response_data[$i]->owner_insurance_provided = true; 
					}else{
						$response_data[$i]->owner_insurance_provided = false; 
					}
					if ($value->is_active =='Y') {
						$response_data[$i]->is_active = true; 
					}else{
						$response_data[$i]->is_active = false; 
					}
					$response_data[$i]->owner_insurance_amount = (float) $value->owner_insurance_amount; 
					$response_data[$i]->renter_insurance_amount = (float) $value->insurance_amount; 
					$response_data[$i]->user_insurance_proof_id = (int) $value->user_insurance_proof_id; 
					$response_data[$i]->app_user_id = (int) $value->app_user_id; 
					$response_data[$i]->owner_insurance_status = (int) $value->owner_insurance_status; 
					$response_data[$i]->is_approved = (int) $value->is_approved; 
					$response_data[$i]->user_insurance_proof_id = (int) $value->user_insurance_proof_id; 
					$response_data[$i]->owner_insurance_percentage = (int) $value->owner_insurance_percentage; 
					unset( $response_data[$i]->insurance_amount);

					$i++;
				}
				// echo "<pre>";
				// print_r($response_data);die;
				$this->SendAdminMaill($app_user_id);
				$response=array("status"=>200,
								"result"=>$response_data,
								"status_message"=>"Insurance updated successffully",
						);
				echo json_encode($response);
				exit();
			}else{
				$value=  array(
						'ks_user_certificate_currency_desc'=>$post_data['insurance_description'],
						'ks_user_certificate_currency_exp'=>date('Y-m-d',strtotime($post_data['expire_date'])),
						'start_date'=>date('Y-m-d',strtotime($post_data['start_date'])),
						
						'image_url' => $image_url,
						'renter_insuance_provided' => $post_data['renter_insuance_provided'],
						'owner_insurance_percentage' => $post_data['owner_insurance_percentage'],
						'owner_insurance_provided' => $post_data['owner_insurance_provided'],
						'owner_insurance_amount' => $post_data['owner_insurance_amount'],
						'owner_insurance_status' => '0',
						'app_user_id'=>$app_user_id,
						'insurance_amount'=>$insurance_amount,
						'create_date'=>date('Y-m-d'),
						'is_active'=> 'Y',

						);

				$insurance_id  = $this->common_model->AddRecord($value,'ks_user_insurance_proof');
				$query =  $this->common_model->GetAllWhere('ks_user_insurance_proof', array('app_user_id'=>$app_user_id));
				$response_data =  $query->result();
					$i = 0 ;
				foreach ($response_data as  $value) {
				
					if ($value->is_approved == 0 )  {
						 $response_data[$i]->is_approved_status_renter = 'Pending'; 
					}elseif ($value->is_approved == 2 ) {
						$response_data[$i]->is_approved_status_renter = 'Expired'; 
					}elseif ($value->is_approved == 3 ) {
						$response_data[$i]->is_approved_status_renter = 'Active'; 
					}elseif ($value->is_approved == 4 ) {
						$response_data[$i]->is_approved_status_renter = 'InActive'; 
					}else{
						$response_data[$i]->is_approved_status_renter = 'Approved'; 
					}	

					if ($value->owner_insurance_status == 0 )  {
						$response_data[$i]->is_approved_status_owner = 'Pending'; 
					}elseif ($value->owner_insurance_status == 2 ) {
						$response_data[$i]->is_approved_status_owner = 'Expired'; 
					}elseif ($value->is_approved == 3 ) {
						$response_data[$i]->is_approved_status_owner = 'Active'; 
					}elseif ($value->is_approved == 4 ) {
						$response_data[$i]->is_approved_status_owner = 'InActive'; 
					}else{
						$response_data[$i]->is_approved_status_owner = 'Approved'; 
					}

					if ($value->renter_insuance_provided =='Yes') {
						$response_data[$i]->renter_insuance_provided = true; 
					}else{
						$response_data[$i]->renter_insuance_provided = false; 
					}

					if ($value->owner_insurance_provided =='Yes') {
						$response_data[$i]->owner_insurance_provided = true; 
					}else{
						$response_data[$i]->owner_insurance_provided = false; 
					}
					if ($value->is_active =='Y') {
						$response_data[$i]->is_active = true; 
					}else{
						$response_data[$i]->is_active = false; 
					}
					$response_data[$i]->owner_insurance_amount = (float) $value->owner_insurance_amount; 
					$response_data[$i]->renter_insurance_amount = (float) $value->insurance_amount; 
					$response_data[$i]->user_insurance_proof_id = (int) $value->user_insurance_proof_id; 
					$response_data[$i]->app_user_id = (int) $value->app_user_id; 
					$response_data[$i]->owner_insurance_status = (int) $value->owner_insurance_status; 
					$response_data[$i]->is_approved = (int) $value->is_approved; 
					$response_data[$i]->user_insurance_proof_id = (int) $value->user_insurance_proof_id; 
					$response_data[$i]->owner_insurance_percentage = (int) $value->owner_insurance_percentage; 
					unset( $response_data[$i]->insurance_amount);

					$i++;
				}

				$this->SendAdminMaill($app_user_id);
				$response=array("status"=>200,
						"result"=>$response_data,
						"status_message"=>"Insurance Added successffully",
						);
				echo json_encode($response);
				exit();
			}	
		}else{
			/*$response=array("status"=>400,
						"status_message"=>"User Not Valid",
						);
			echo json_encode($response);
			exit();*/
			header('HTTP/1.1 400 Session Expired');
			exit();
		}
		die;
		
    }
  

  	public function InsuranceImage($Image_details)
  	{

  		if (!empty($Image_details)) {
			$original_image_name =  explode('.',$Image_details['name']);
			$config['upload_path'] =BASE_IMG.'site_upload/insurance_img/';
			$config['allowed_types'] = 'jpg|jpeg|gif|png|pdf|pdf|doc|docx';
			$config['max_size']	= '2000';
			$config['max_width'] = '0';
			$config['max_height'] = '0';
			$config['overwrite'] = TRUE;
			$ext =  strtolower(pathinfo($Image_details['name'], PATHINFO_EXTENSION));
		    $file_name =  time().'.'.$ext;				
			$config['file_name'] = $file_name;
			$config['orig_name'] =$Image_details['name'];
			$config['image'] = $file_name;
			$this->upload->initialize($config);
				
				if($this->upload->do_upload('image')){
					$image_url = FRONT_URL.'/insurance_img/'.$file_name;
					$result['image_url'] = $image_url; 
					$result['message']   = 'Image Uplaoded' ;
					$result['status']    = 1; 
				}else{
					$result['image_url'] = ''; 
					$result['message']   = $this->upload->display_errors(); 
					$result['status']    = 0; 
				}
					return $result ;
					
		}
				
			
  	}

  	// User Allunavailbes Date


  	public function OwnerUnavaliableDate()
  	{
  		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {

			$where_clause = array(
										'k_g_u.create_user' =>$app_user_id,
										'unavailable_from_date > ' => date('Y-m-d')
								);

			$query =  $this->home_model->GetAllunavailableDates($where_clause);
			$data =  $query->result();
			if (!empty($data)) {
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Data found';
				$response['result'] = $data;
				$json_response = json_encode($response);
				echo $json_response;
				exit();
			}else{
				$app_user_id = '';
				$response['status'] = 200;
				$response['status_message'] = 'Data not found';
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


  	public function SendAdminMaill($app_user_id)
  	{
  		$query = $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$app_user_id));
  		$user_details   = $query->row();


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
						<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
						</tr>
					</table>	
						<table class="table table-condensed" width="940" style="margin:25px auto;padding: 0px 10px;" cellpadding="0" cellspacing="0">
							<tr>
								<td style="font-size:20px; padding-bottom:10px;">Hi support Team</td>
							</tr>
							<tr>
								<td style="font-size:18px; padding-bottom:10px;">An insurance policy has been uplaoded to the system By  '.$user_details->app_user_first_name.' '. $user_details->app_user_last_name.'.</td>
							</tr>
							
						</table>
				
				
				<table width="940" style="  margin: 0px auto; background-color:#ddd;padding: 5px 0px; text-align: center;" cellpadding="0" cellspacing="0">
					<tr>
					<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
					</tr>
				</table>
			</div>
			</body>
			</html>
				';
		
	 	  $mail_body = $mail_content;
	 	
		$to= 'support@kitshare.com.au';

		// $to= 'singhaniagourav@gmail.com';

		$subject = "An Insurance Policy has been Uploaded";		

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
  	}	


  	public function SendInvoiceEmail($order_id)
	{
		error_reporting(0);
		 $query = $this->common_model->GetAllwhere('ks_user_gear_rent_details',array('order_id'=>$order_id));
		 $order_list = $query->result();
		 if (!empty($order_list)) {
		 	foreach ($order_list as $orders) {
				$today_date = date('Y-m-d H:i:s');
				$today_date1 = date('Y-m-d');
				$order_date =  date('Y-m-d 12:00:00', strtotime($orders->gear_rent_start_date))  ;
				$order_date1 =  date('Y-m-d', strtotime($orders->gear_rent_start_date))  ;
				$hourdiff = round((strtotime( $order_date) - strtotime(date('Y-m-d' ,strtotime(   $today_date )) ))/3600, 1);
				// print_r($orders);

				$order_id = $orders->order_id;
				/*$sql = "SELECT o_d.* ,g_tbl.renter_app_bussiness_name,g_tbl.owner_app_show_business_name,g_tbl.renter_app_show_business_name,g_tbl.gear_name,g_tbl.owner_app_bussiness_name ,g_tbl.owner_app_show_business_name, g_tbl.renter_app_user_first_name,g_tbl.renter_app_user_last_name ,g_tbl.owner_app_user_first_name As app_user_first_name  ,g_tbl.owner_app_user_last_name As app_user_last_name,g_tbl.owner_app_bussiness_name FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_order_description As g_tbl  ON g_tbl.order_id  = o_d.order_id  WHERE o_d.order_id = '".$order_id."'  GROUP By  user_gear_rent_detail_id "	;*/
				
				 $sql = "SELECT o_d.* ,
				 g_tbl.replacement_value_aud_inc_gst,
				 g_tbl.per_day_cost_aud_ex_gst,				 g_tbl.renter_app_bussiness_name,g_tbl.owner_app_show_business_name,g_tbl.renter_app_show_business_name,g_tbl.gear_name,g_tbl.owner_app_bussiness_name ,g_tbl.owner_app_show_business_name, g_tbl.renter_app_user_first_name,g_tbl.renter_app_user_last_name ,g_tbl.owner_app_user_first_name As app_user_first_name  ,g_tbl.owner_app_user_last_name As app_user_last_name,g_tbl.owner_app_bussiness_name			 
				 FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_order_description As g_tbl  ON g_tbl.user_gear_desc_id = o_d.user_gear_desc_id 
				 WHERE o_d.order_id = '".$order_id."' AND  g_tbl.order_id='".$order_id."' GROUP By  o_d.user_gear_rent_detail_id ";
				
				
				$order_details   = $this->common_model->get_records_from_sql($sql);
				$i =0 ;
				foreach ($order_details as  $value) {
				 $users[] = 	$value->create_user;
				 $order_details[$i]->renter_firstname =  $value->renter_app_user_first_name;  
			 	 $order_details[$i]->renter_lastname =  $value->renter_app_user_last_name; 

					if($i==0){
						
						$sql = "SELECT k_u_a.*,k_s.ks_state_name,k_sub.suburb_name ,k_coun.ks_country_name FROM ks_user_address As k_u_a INNER JOIN ks_states AS k_s ON  k_u_a.ks_state_id = k_s.ks_state_id  INNER JOIN ks_suburbs As k_sub ON k_u_a.ks_suburb_id = k_sub.ks_suburb_id  INNER JOIN ks_countries As k_coun ON k_u_a.ks_country_id = k_coun.ks_country_id  WHERE k_u_a.app_user_id  = '".$value->create_user."' AND k_u_a.is_active ='Y' AND k_u_a.default_address='1'";	
						$query = $this->db->query($sql);
						$data['addrsss'] =  $query->row();
					}
				 
			 	 $i++;
				}
				/*$this->db->select('u_add.*');
				$this->db->from('ks_user_gear_rent_master g_r_m');
				$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.user_gear_rent_id = g_r_m.user_gear_rent_id','inner');
				$this->db->join('ks_gear_order_location as u_add', ' g_r_d.order_id = u_add.order_id','inner');
				$this->db->join('ks_user_gear_order_description as desc', ' desc.order_id = g_r_d.order_id','inner');
				$this->db->join('ks_users as u', ' desc.app_user_id = u.app_user_id','inner');
				$this->db->where('u_add.order_id',$order_id);
				$this->db->where('u_add.default_address',1);
				$query = $this->db->get();
				$data['addrsss'] =  $query->row();*/
				$url = '';
				$data['six_digit_random_number'] = $order_id; 
				$this->uri->segment('3');
				$data['order_details'] = $order_details;
				$mail_body = $this->load->view('order-template',$data,true);
				$data =  file_get_contents(UPLOAD_IMAGES.'admin/uploads/invoices/'.$order_id.'.pdf');
				$this->load->helper('pdf');
				$pdf_url =  gen_pdf($mail_body,$order_id);

				return $data ; 


					
		 		
		 	}
		 	// $mail_data = array(
				// 		'Messages'=>array(array(
				// 						"From"=>array(
				// 								"Email"=>"support@kitshare.com.au",
				// 								"Name"=>"Kitshare Australia ",
				// 							),
				// 						"To"=>array(
				// 								array("Email"=>'singhaniagourav@gmail.com',
				// 								"Name"=>"",
				// 								),
				// 							),
				// 						"Subject"=> 'Invocie Mail',
				//                         "TextPart"=> "",
				//                         "HTMLPart"=> 'Invocie Mail',
				//                         "Attachments"=> array(
				// 			               array(
				// 			                 "Filename"=> $order_id.".pdf",
				// 			                    "ContentType"=> "application/pdf",
				// 			                    "Base64Content"=> base64_encode($data)	  
				// 			               )),
				// 					),
				// 				)
				// 			);	
				// $this->common_model->sendMail($mail_data);

		 }
		
	}
	
	public function GETCartCalculation($app_user_id)
	{
			$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
			
			if (count($Cart_details)) {
					 $total_rent_amount_ex_gst = '0';
					 $gst_amount = '0';
					 $other_charges = '0';
					 $insurance_amount = '0';
					 $owner_insurance_amount = 0;
					 $total_rent_amount = '0';
					 $security_deposit = '0';
					 $i= 0;
					 
					$query =  $this->common_model->GetAllWhere('ks_settings','');
					$settings =  $query->row();

					 
				 	 foreach ($Cart_details as  $value) {
			 			 $total_rent_amount_ex_gst  += $value['total_rent_amount_ex_gst'] ; 
			 			 $gst_amount  += $value['gst_amount'] ; 
			 			 $other_charges  += '0' ; 
			 			 $total_rent_amount  += $value['total_rent_amount'] ; 
			 			 $insurance_amount  += number_format((float)$value['insurance_amount'] , 2, '.', ''); 
			 			 $owner_insurance_amount  += number_format((float) $value['owner_insurance_amount'], 2, '.', '');
						 
						 //If Owner Insurance amount is greater than 0 and insurance amount is 0 then insurance amount will have owner's insurance amount
						 if($value['owner_insurance_amount']>0 && $value['insurance_amount']==0)
						 	$Cart_details[$i]['insurance_amount'] = $value['owner_insurance_amount'];	
						//This piece of code is for front end display as from insurance amount they are displaying the amount	
						//Code ends///
						  
			 			 $security_deposit +=    $value['security_deposit'] ; 
			 			 $app_user_id_array[] = $value['app_user_id'] ;
			 			 //$Cart_details[$i]['gear_display_image']   =  GEAR_IMAGE .$value['gear_display_image'];
						 
			 			if ($value['gear_display_image'] == '') {
			 			 	 $Cart_details[$i]['gear_display_image']   = BASE_URL."/site_upload/gear_images/default_product.jpg";
			 			 }else{
			 			 	if(file_exists(BASE_IMG.'site_upload/gear_images/'.$value['gear_display_image'])) {
								$Cart_details[$i]['gear_display_image'] = GEAR_IMAGE.$value['gear_display_image'] ;
		 					}else{
		 						$Cart_details[$i]['gear_display_image'] = BASE_URL."/site_upload/gear_images/default_product.jpg";
		 					}
			 			}
						
						if ($value['security_deposit_check'] != '1') {
			 				 $Cart_details[$i]['security_deposit']   = '0.00';
			 			}
						
												
						$query = $this->common_model->GetAllWhere('ks_gear_categories',array( 'security_deposit'=>'Y','is_active'=>'Y', 'gear_category_id'=>$value['ks_sub_category_id']));
						$security_deposit_check = $query->row();
			 			
						if (!empty($security_deposit_check)) {

							if ($value['security_deposit_check'] == '0') {
								 $Cart_details[$i]['insurance_check_key']   = 2;
							}else{
								 $Cart_details[$i]['insurance_check_key']   = 0;
							}
							
						}else{
							if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value) {
			 				 	$Cart_details[$i]['insurance_check_key']   = 1;	
				 			}else{
				 				$Cart_details[$i]['insurance_check_key']   = 0;	
				 			}
						}
						
						if (!empty($Cart_details[$i]['ks_insurance_category_type_id'])) {
								$query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , array('status'=>'Active' ,'ks_insurance_category_type_id'=> $Cart_details[$i]['ks_insurance_category_type_id']) ) ; 
								$insurance_type1 = $query->row();
								if (empty($insurance_type1)) {
									$Cart_details[$i]['insurance_type_name'] =  '' ;
									//$Cart_details[$i]['insurance_type_description'] =  '' ;
									$Cart_details[$i]['description']['message'] = "";
									$Cart_details[$i]['description']['error'] = "";
									
								}else{
									$Cart_details[$i]['insurance_type_name'] =  $insurance_type1->name ;
									//$Cart_details[$i]['insurance_type_description'] =  $insurance_type1->description ;
									$Cart_details[$i]['description']['message'] =  $insurance_type1->description;
									$Cart_details[$i]['description']['error'] = "";
								}	
								
							}else{
							
								$Cart_details[$i]['description']['message'] = "";
								$Cart_details[$i]['description']['error'] = "";
							}
	
							//IF there is no insurance the message will be customed
							if($Cart_details[$i]['insurance_check_key']==2){
								$Cart_details[$i]['description']['message'] = "Insurance is not required to rent this listing";
								$Cart_details[$i]['description']['error'] = "";
							}
							
							if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value && $Cart_details[$i]['insurance_check_key']==1) {
								//Renter insurance Check 
								$where_clause = array(
										'app_user_id'=> $app_user_id ,
										'renter_insurance_provided' => 'Yes',
										'renter_insurance_status'=> '1' ,
										'is_visiible'=> 'Y' ,
										'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
										'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

									);

									$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause);
									$renter_insurance =  $query->row();
									
									if (empty($renter_insurance)) {
									
										//Check For Owner Insurance 
										$where = array( 'app_user_id'=>$value['app_user_id'] ,
														'is_visiible'=> 'Y' ,
														'owner_insurance_provided'=>'Yes',
														'owner_insurance_status' =>'1',
														'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
														'ks_user_certificate_currency_start <= ' =>date('Y-m-d'), 
														);
										$query1 =  $this->common_model->GetAllWhere('ks_user_insurance_proof' ,$where);
										$owner_insurance_detail = $query1->row();

										if (empty($owner_insurance_detail)) {
											//$Cart_details[$i]['insurance_type_description'] =  'This listing exceeds the limit of liability for Kitshare Insurance' ;
											//$Cart_details[$i]['error']   = 'Please upload a certificate of currency to rent this listings.' ;		
											$Cart_details[$i]['description']['message']='This listing exceeds the limit of liability for Kitshare Insurance';	
											$Cart_details[$i]['description']['error']='Please upload a certificate of currency to rent this listings.';	
										}
										
									}else{
										$Cart_details[$i]['description']['error'] = ' ' ;		
									}
									
									
				 			}else{
				 				 $Cart_details[$i]['description']['error'] = '';	
				 			}
						
							//Conditions start here
							$where_array = "status='Active'";
						
							//if $value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value then the dropdown won't have Kitshare Insurance Policy in it							
							// if($Cart_details[$i]['insurance_check_key'] == 1){
								
							// 	$where_array .= " AND ks_insurance_category_type_id!='1'";
							// }
			 				
							// $query =  $this->common_model->GetAllWhere('ks_insurance_category_type' , $where_array ) ; 
							// $insurance_type = $query->result();

							// $Cart_details[$i]['insurance_type']  = $insurance_type;

							if ($Cart_details[$i]['insurance_check_key'] ==2) {
								$category_array = array();
							}
							elseif ($value['security_deposit'] >  "0.00") {
								$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>5))	;
								$category_array = $query->result_array();
								// $category_array = array();
							}else{
								$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>1))	;
								$category_array = $query->result_array();
								$query_sett=   $this->common_model->GetAllWhere('ks_settings', '');	
								$settings = $query_sett->row();
								if ($value['replacement_value_aud_ex_gst'] > $settings->max_replacement_value) {
									$category_array = array();
								}
								$where_clause = array(
													'app_user_id'=> $app_user_id ,
													'renter_insurance_provided' => 'Yes',
													'renter_insurance_status'=> '1' ,
													'is_visiible'=> 'Y' ,
													'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
													'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

												);
								$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
								$renter_insurance  =  $query->row();
								if (!empty($renter_insurance)) {
									$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>3))	;
									$renter_insurance = $query->row();	
									array_push($category_array, $renter_insurance);
								}
								//Owner inurance
								$Cart_details1 = 	 $this->home_model->getUserCart1($app_user_id);
						 		$where_clause = array(
															'app_user_id'=> $Cart_details1[0]['app_user_id'] ,
															'owner_insurance_provided' => 'Yes',
															'owner_insurance_status'=> '1' ,
															'is_visiible'=> 'Y' ,
															'ks_user_certificate_currency_exp >=' =>date('Y-m-d'),
															'ks_user_certificate_currency_start <= ' =>date('Y-m-d'),

														);
								$query = $this->common_model->GetAllWhere('ks_user_insurance_proof',$where_clause) ;
								$owner_insurance  =  $query->row();
								if (!empty($owner_insurance)) {
									$query =  $this->common_model->GetAllWhere('ks_insurance_category_type',array('status'=>'Active' , 'ks_insurance_category_type_id' =>8))	;
									$owner_insurance = $query->row();							
									array_push($category_array, $owner_insurance);
								}
							}
							$Cart_details[$i]['insurance_type']  = $category_array;	
							unset($Cart_details[$i]['primary_email_address']); 
			 			 $i++;
			 		 }	

			 		if (!empty($app_user_id_array)) {	
			 			$app_user_id_array =  array_unique($app_user_id_array);
						$app_user_ids = '' ;
						foreach ($app_user_id_array as $app_user) {
							$app_user_ids.= "'". $app_user."',";
						}

						$sql1 =  "SELECT app_user_id,app_username,bussiness_name,show_business_name,app_user_first_name,app_user_last_name,user_profile_picture_link FROM ks_users WHERE   app_user_id IN (".rtrim($app_user_ids , ',').")  ";
						$app_users_details = $this->common_model->get_records_from_sql($sql1) ;	
						if (empty($app_users_details[0]->user_profile_picture_link)) {
							$app_users_details[0]->user_profile_picture_link = BASE_URL."site_upload/profile_img/profile-default-pic.png" ;
						}
						if ($app_users_details[0]->show_business_name =='Y') {
							$app_users_details[0]->app_user_first_name =$app_users_details[0]->bussiness_name ;
							$app_users_details[0]->app_user_last_name ='';
						}
			 		}
			 		$beta_discount = ($total_rent_amount_ex_gst*15)/100 ; 
			 		$insurance_fee = 0; 
			 		$community_fee = ($total_rent_amount_ex_gst*5)/100 ; 
			 		 $gst_amount = ($total_rent_amount_ex_gst -$beta_discount + $insurance_amount +  $insurance_fee + $community_fee + $owner_insurance_amount)*10/100;
			 		$rent_summary = array(
			 								'total_rent_amount_ex_gst'=>$total_rent_amount_ex_gst,
			 								'gst_amount'=>$gst_amount,
			 								'beta_discount'=> $beta_discount,
			 								'insurance_fee'=> number_format((float)($insurance_amount), 2, '.', '') ,
			 								'owner_insurance_amount'=> number_format((float)$owner_insurance_amount , 2, '.', ''),
			 								'community_fee'=> $community_fee,
			 								'other_charges'=>$other_charges,
			 								'sub_total'=> $total_rent_amount_ex_gst -$beta_discount + $insurance_amount + $community_fee + $owner_insurance_amount,
			 								'total_rent_amount'=>$total_rent_amount_ex_gst -$beta_discount + $insurance_amount + $community_fee + $gst_amount + $owner_insurance_amount,
											'security_deposit' => $security_deposit,		 								
			 								'total_amount'=>  $total_rent_amount_ex_gst -$beta_discount + $insurance_amount + $community_fee + $gst_amount + $owner_insurance_amount 
			 							);
					$data['Cart_details'] = $Cart_details ;
					$data['cart_summary'] = $rent_summary ;
					$data['app_users_details'] = $app_users_details ;
					return $data ;
				}
	}

	public function BaseInsuranceCacluation($Cart_details , $address_details )
	{
			//Insurance Day Calculation
							$date_from = strtotime($Cart_details->gear_rent_request_from_date);
						 	$date_to   = strtotime($Cart_details->gear_rent_request_to_date);
						 	$diff = abs($date_to - $date_from);
							$date_array =  $this->getDateList($date_from,$date_to);
							if (count($date_array ) <= 1 ) {
						 		
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
							$query =  $this->common_model->GetAllWhere('ks_settings','');
							$settings = $query->row();
							$query2 =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$Cart_details->user_gear_desc_id));
							$gear_details = $query2->row();
							$base_amount =  number_format((float)(($insurance_days *  $address_details->base_rate * $gear_details->per_day_cost_aud_ex_gst)) , 2, '.', ''); 
							$esl = 	 number_format((float)(($base_amount * $address_details->ks_state_esl)) , 2, '.', ''); 
							$stamp_duty =  (($base_amount + $esl )+ (($base_amount + $esl)/$settings->gst_percent) ) *  $address_details->ks_state_sd; 
							$stamp_duty = 	 number_format((float)(($stamp_duty)) , 2, '.', ''); 
							$total_premium_ex_gst = $base_amount + $esl + $stamp_duty  ;
							$premium_gst = ($base_amount + $esl)/$settings->gst_percent ;
							 $total_premium_inc_gst = $total_premium_ex_gst + $premium_gst   ;
							$insurance_fee = $total_premium_inc_gst/1.1  ;
							$data['base_amount'] = $base_amount;
							$data['esl'] = $esl;
							$data['stamp_duty'] = $stamp_duty;
							$data['total_premium_ex_gst'] = $total_premium_ex_gst;
							$data['premium_gst'] = $premium_gst;
							$data['insurance_fee'] = $insurance_fee;
							$data['total_premium_inc_gst'] = $total_premium_inc_gst;
							
							return $data ;
	}

	//Recursive function to find the postcodes
	function fetchPostCodes($lat,$lng,$radius,$level){
	
		$postal_arr=$this->home_model->zipcodeRadius($lat,$lng,$radius);
		$postal_arr_list="";
		
		
		if(count($postal_arr)>5 || $level==200){
		
			if(count($postal_arr)>1){
				$postal_arr_list=implode(",",$postal_arr);
			}else
				$postal_arr_list=$postal_arr[0];
				
			return $postal_arr_list;
		}
		else{
			$level=$level+5;
			$radius = $radius+5;
			return $this->fetchPostCodes($lat,$lng,$radius,$level);
		}
		
	
	}
	
}?>