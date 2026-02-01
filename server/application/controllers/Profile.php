<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
	 public function __construct() {
	 	header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email'));
		$this->load->model(array('common_model','home_model'));
	}
	
	public function modify_data(){
	
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		
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
				
			if(isset($post_data['date']) && $post_data['date']!=""){
			
				$date = $post_data['date'];
				$month = $post_data['month'];
				$year = $post_data['year'];
				$data['user_birth_date']=$year."-".$month."-".$date;
				
			}else if(!isset($post_data['date']) && empty($post_data['date'])==true){
			
				$data['user_birth_date'] = "";
			}
			
			if(isset($post_data['australian_business_number']) && $post_data['australian_business_number']!=""){
				$data['australian_business_number'] = $post_data['australian_business_number'];
			}else if(!isset($post_data['app_username']) && empty($post_data['app_username'])==true){
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
			
			if(isset($post_data['youtube_link']) && $post_data['youtube_link']!=""){
				$data['youtube_link'] = $post_data['youtube_link'];
			}else if(!isset($post_data['youtube_link']) && empty($post_data['youtube_link'])==true){
				$data['youtube_link'] = "";
			}
			
			if(isset($post_data['flikr_link']) && $post_data['flikr_link']!=""){
				$data['flikr_link'] = $post_data['flikr_link'];
			}else if(!isset($post_data['flikr_link']) && empty($post_data['flikr_link'])==true){
				$data['flikr_link'] = "";
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
			
			if($this->common_model->UpdateRecord($data,"ks_users","app_user_id",$app_user_id)){
			
				$data['profession_type_id'] = $profession_type_id;
			
				$response=array("status"=>200,
								"status_message"=>"success",
								"result"=>$data);
				echo json_encode($response);
				exit();
			
			}else{
				
				//header('HTTP/1.1 417 Expectation Failed');
				
				$response=array("status"=>417,
								"status_message"=>"Expectation Failed");
				echo json_encode($response);
				exit();
			}
		}else{

			/*$response=array("status"=>400,
								"status_message"=>"Unauthorized");
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
	
	function userinfo($token){
	
		$app_user_id = $this->common_model->fetchTokenDetails($token);
		return $app_user_id;

	}
	
	
	public function file_upload(){
	
	    $uploads_dir = $_SERVER['DOCUMENT_ROOT'].'/kitshare/site_upload/profile_img';
		
		$filename = $_POST['photo'];
		$extension = $_POST['extension'];
		
		$new_name = date("YmdHis").".".$extension;
		if(move_uploaded_file($filename, "$uploads_dir/$new_name")){
		
			echo $data['user_profile_picture_link'] = BASE_URL."kitshare/site_upload/profile_img/".$new_name;
		}else
			echo "failed";
				
	}
	
	
	

}?>
