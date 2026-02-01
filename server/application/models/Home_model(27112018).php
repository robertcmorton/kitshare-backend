<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home_model extends CI_Model {

	public function __construct() {

		parent::__construct();
	}
	
	public function send_email($sender, $to , $subject, $mail_body)
	{
		
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset']  = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$this->load->library('email',$config);

		$this->email->initialize($config);	
		$this->email->from($sender, 'Kitshare');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($mail_body);
		$this->email->send();
	}
	public function loginCheck($email,$password) 
	{
		$this->db->select('*');
		$this->db->from('ks_users');
		$this->db->where(array('primary_email_address' => $email, 'app_password' => $password));
		$query = $this->db->get(); 
		return $query;
	} /* end of login check function */	
	
	
	public function RandomNumber($length='', $datetime = TRUE) 
		{
			if(empty($length)===true) 
			{
				$random = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s');
			} 
			else 
			{				 
				srand((double)microtime()*1000000); 
				$data = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
				for($i = 0;$i < $length;$i++) 
				{
					$random.= substr($data,(rand()%(strlen($data))),1); 
				}
				if($datetime === TRUE) $random = $random."-".date("Ymd")."-".date("His");
			}
			return $random;
		}
    public function ProductReview($app_user_id,$owner_type = 'Owners & Renters')
    {
		$this->db->select('a.app_user_id as reviewer_id,
				 a.ks_cust_gear_review_id,
				 a.cust_gear_review_desc,
				 DATE_FORMAT(a.`cust_gear_review_date`,"%M %Y") as cust_gear_review_date,
				 a.owner_type_id,
				 b.owner_type_name,
				 c.gear_name,
				 c.gear_description_1,
				 d.app_user_first_name reviewer_fname,
				 d.app_user_last_name reviewer_lname,
				 e.app_user_first_name profile_fname,
				 e.app_user_last_name profile_lname,
				 e.app_user_id profile_id,
				 e.user_profile_picture_link,
				 f.gear_star_rating_value,
				 f.app_user_id as rating_reviewer_id');
				 
		  $this->db->from('ks_cust_gear_reviews a');
		  $this->db->join('ks_owner_types b', 'b.owner_type_id = a.owner_type_id');
		  $this->db->join('ks_user_gear_description c', 'c.user_gear_desc_id = a.user_gear_desc_id');  
		  $this->db->join('ks_users d', 'd.app_user_id = a.app_user_id');
		  $this->db->join('ks_users e', 'e.app_user_id = c.app_user_id');
		  $this->db->join('ks_cust_gear_star_rating f', 'f.app_user_id = a.app_user_id AND f.user_gear_desc_id = a.user_gear_desc_id', 'left');
		  
		 
		  $this->db->where('c.app_user_id' ,$app_user_id); 
		  if($owner_type!='Owners & Renters'){
			$this->db->where('a.owner_type_id' ,$owner_type); 
		  }
		  
		  $this->db->order_by('a.ks_cust_gear_review_id', 'DESC');
		  $query = $this->db->get();
		  
		  return $query;
    }
	public function UserAddress($app_user_id)
	{
		$this->db->select('a.user_address_id,a.business_address,a.appartment_number,
						   a.street_address_line1,a.street_address_line2,a.default_equipment_address_1,
						   a.default_equipment_address_2,b.suburb_name,b.suburb_postcode,c.ks_state_name,d.ks_country_name');
		$this->db->from('ks_user_address as a');
		$this->db->join('ks_suburbs as b', 'a.ks_suburb_id = b.ks_suburb_id');
		$this->db->join('ks_states as c', 'c.ks_state_id = b.ks_state_id');
		$this->db->join('ks_countries as d', 'd.ks_country_id = c.ks_country_id');
		$this->db->where('a.app_user_id' , $app_user_id);
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;
	}
	public function BankDetails($app_user_id)
	{
		$this->db->select('a.user_bank_detail_id,a.bank_id,a.account_type,a.branch_code,a.bsb_number,a.branch_address,a.branch_street,a.branch_city,a.branch_zip_code,a.user_account_number,b.suburb_name as bank_suburb_name,b.suburb_postcode as bank_suburb_postcode,c.ks_state_name as bank_ks_state_name,d.ks_country_name as bank_ks_country_name');
		$this->db->from('ks_user_bank_details as a');
		$this->db->join('ks_suburbs as b', 'a.branch_suburb_id = b.ks_suburb_id');
		$this->db->join('ks_states as c', 'c.ks_state_id = a.branch_state_id');
		$this->db->join('ks_countries as d', 'd.ks_country_id = a.branch_country_id');
		$this->db->where('a.app_user_id' , $app_user_id);
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;
	}
	
	public function GearList($app_user_id  )
	{
		$this->db->select('a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						c.app_user_first_name,
						c.app_user_last_name,
						d.gear_category_id,a.user_gear_desc_id');
		$this->db->from('ks_user_gear_description as a');
		$this->db->join('ks_user_gear_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id',"LEFT");
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_models as d', 'a.model_id = d.model_id');
		
			$this->db->where(array('a.app_user_id'=>$app_user_id, ));
		
		$this->db->group_by('a.user_gear_desc_id');
		$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->result_array();
		
	}
	public function GearListConditons($app_user_id ,$where)
	{
		//print_r();die;
		$this->db->select('a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						c.app_user_first_name,
						c.app_user_last_name,
						d.gear_category_id,a.user_gear_desc_id, a.ks_category_id');
		$this->db->from('ks_user_gear_description as a');
		$this->db->join('ks_user_gear_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id',"LEFT");
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_models as d', 'a.model_id = d.model_id');
		
		$this->db->where(array('a.app_user_id'=>$app_user_id ));

		//$this->db->where('a.user_gear_desc_id',$where['user_gear_desc_id'] );
		$this->db->where($where);
		$this->db->group_by('a.user_gear_desc_id');
		$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->result_array();
		
	}
    //Method to pickup the categories against which the user has gears
    public function GearCategories($app_user_id,$where)
	{

	  $this->db->select('ks_user_gear_description.user_gear_desc_id,
						  ks_user_gear_description.model_id,
						  ks_user_gear_description.app_user_id,
						  ks_models.gear_category_id,
						  ks_user_gear_description.ks_category_id,
						  ks_gear_categories.gear_category_name');
		$this->db->from('ks_user_gear_description');
		$this->db->join('ks_models','ks_user_gear_description.model_id=ks_models.model_id');
		$this->db->join('ks_gear_categories','ks_models.gear_category_id=ks_gear_categories.gear_category_id');
		
		$this->db->where(array('ks_user_gear_description.app_user_id'=>$app_user_id));		
		$this->db->order_by('ks_gear_categories.gear_category_name', 'ASC');
		$this->db->group_by('ks_gear_categories.gear_category_id'); 
		
		$query = $this->db->get();
		$result_categories=$query->result_array();

		if (!empty($where)) {
			
			$gears=$this->GearListConditons($app_user_id,$where );
		}else{
			
			$gears=$this->GearList($app_user_id );
		}		
		$gear_ratings=$this->GetGearRatings($app_user_id);
		
		$gears_listings=array();
		
		//Gear images are assigned with full path
		foreach($gears as $gear_lists){
			foreach($gear_lists as $key=>$value){
			  if($key=="gear_display_image"){
			  		if ($value== '') {
			  			$img_path=GEAR_IMAGE.'default_product.jpg';
			  			//$img_path= '';
			  		}else{
						$img_path=GEAR_IMAGE.$value;
					}	
				//$img_path = "";
				$gear_lists[$key]=$img_path;
			  }
			}
			array_push($gears_listings,$gear_lists);
		}		
		
				
		$arr=array('gear_categories'=>$result_categories,
				   'gear_lists'=>$gears_listings,
				   'gear_ratings'=>$gear_ratings);
				
		return $arr;
		
   }
   
   //Method to find out the rating against a gear
   public function GetGearRatings($app_user_id='',$user_gear_desc_id=''){
   		
		$this->db->select('AVG(a.gear_star_rating_value) as rating, a.`user_gear_desc_id`');
		$this->db->from('ks_cust_gear_star_rating as a');
		$this->db->join('ks_user_gear_description b','b.user_gear_desc_id=a.user_gear_desc_id');
		$this->db->join('ks_users c','c.app_user_id=b.app_user_id');
		$this->db->where(array('a.is_active'=>'Y'));
		if($app_user_id!="")
			$this->db->where(array('b.app_user_id'=>$app_user_id));
		if($user_gear_desc_id!="")
			$this->db->where(array('a.user_gear_desc_id'=>$user_gear_desc_id));
			
		$query = $this->db->get();
		return $query->result_array();
		
   }
   
   //Method to get the logged in user's name and profile pic
   public function GetProfileInfo($app_user_id){
   	
		$this->db->select('app_user_first_name,app_user_last_name,user_profile_picture_link');
		$this->db->from('ks_users');
		$this->db->where(array('app_user_id'=>$app_user_id));
		$query = $this->db->get();
		return $query->result_array();
   }
   
   //Method to get the contact info
   public function GetContactInfo($app_user_id){
   
   		$this->db->select('a.user_address_id,a.app_user_id,a.business_address,a.appartment_number,a.street_address_line1,a.street_address_line2,a.ks_suburb_id,
						  a.default_equipment_address_1,a.default_equipment_address_2,
						  b.suburb_name,b.suburb_postcode,b.ks_state_id,
						  c.ks_state_name,c.ks_country_id,d.ks_country_name');
		$this->db->from('ks_user_address a');
		$this->db->join('ks_suburbs b','a.ks_suburb_id=b.ks_suburb_id');
		$this->db->join('ks_states c','b.ks_state_id=c.ks_state_id');
		$this->db->join('ks_countries d','c.ks_country_id=d.ks_country_id');
		$this->db->where(array('a.app_user_id'=>$app_user_id,'b.is_active'=>'Y','c.is_active'=>'Y','d.is_active'=>'Y'));
   		$query = $this->db->get();
		return $query->result_array();
   }
   
   //Method to get bank details
   public function GetBankDetails($app_user_id){
   
   		$this->db->select('a.app_user_id,a.bank_id,a.account_type,a.branch_code,a.bsb_number,
						  a.branch_address,a.branch_street,a.branch_city,a.branch_zip_code,
						  a.branch_suburb_id,a.branch_state_id,a.branch_country_id,a.user_account_number,
						  a.accept_stripe_connection,b.bank_name,b.bank_logo,b.bank_head_office');
		$this->db->from('ks_user_bank_details a');
		$this->db->join('ks_banks b','a.bank_id=b.bank_id');
   		$this->db->where(array('a.app_user_id'=>$app_user_id,'b.is_active'=>'Y'));
		$this->db->order_by('b.bank_name', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
   }
   
   //Method to populate the Gear Lists
   public function GetGearLists(){
   		
		$this->db->select('a.user_gear_desc_id,
						   a.ks_gear_type_id,
						   a.gear_name,
						   a.model_id,
						   a.app_user_id,
						   a.per_day_cost_aud_inc_gst,
						   b.app_user_first_name,
						   b.app_user_last_name,
						   b.user_profile_picture_link,
						   c.gear_display_image,
						   c.gear_display_seq_id');
		$this->db->from('ks_user_gear_description a');
		$this->db->join('ks_users b','a.app_user_id=b.app_user_id');
		$this->db->join('ks_user_gear_images c','a.user_gear_desc_id=c.user_gear_desc_id AND a.model_id=c.model_id');
		$this->db->where(array('a.is_active'=>'Y','a.gear_hide_search_results'=>'N','c.gear_display_seq_id'=>1));
		$this->db->order_by('a.gear_view_count','DESC');
		$query = $this->db->get();
		//echo $this->db->last_query();exit();
		
		$gears=$query->result_array();
		
		$gears_listings=array();
		//Gear images are assigned with full path
		foreach($gears as $gear_lists){
			foreach($gear_lists as $key=>$value){
			  if($key=="gear_display_image"){
			  	$img_path = "";
				//$img_path=base_url().GEAR_IMG.$value;
				$gear_lists[$key]=$img_path;
				
			  }else if($key=="user_profile_picture_link"){
			  
			  	if($gear_lists[$key]!=""){
					if(!strstr($gear_lists[$key],"https:")){	
						$img_path = "";			
						//$img_path=base_url().PROFILE_IMG.$gear_lists[$key];
						$gear_lists[$key]=$img_path;
					}
				 }else{
				 	//$gear_lists[$key]=BASE_URL.PROFILE_IMG."default-profile-pic.jpg";
				 	$gear_lists[$key]=BASE_URL."site_upload/profile_img/profile-default-pic.png";
				 }
			  }	
			  
			  if($key=="user_gear_desc_id"){
			  		$rating=$this->GetGearRatings('',$gear_lists['user_gear_desc_id']);
					if(count($rating)>0)
						$gear_lists['rating']=$rating[0]['rating'];
					else
						$gear_lists['rating']=0;
			  }	
			  	  
			}
			array_push($gears_listings,$gear_lists);
		}	
		
		return $gears_listings;		
   }
   
   
   public function pageContents($page_code){
   	
		$this->db->select('a.cms_page_id,a.page,a.page_code,b.content');
		$this->db->from('ks_cms_pages a');
		$this->db->join('ks_page_content b','a.cms_page_id=b.cms_page_id');
		$this->db->where(array('a.page_code'=>$page_code));
		$query = $this->db->get();
		$pages=$query->result_array();
		
		return $pages;
		
   }
   public function geratDetails($gear_id='')
   {
   		$this->db->select('a.*,m.model_name,k_m.manufacturer_name, k_g_c.gear_category_name, k_g_s_c.gear_category_name  AS gear_sub_category_name , k_u.app_user_first_name,k_u.app_user_last_name,k_u.user_profile_picture_link,k_u.user_description');
   		$this->db->from('ks_user_gear_description a');
   		$this->db->join('ks_users  k_u','a.app_user_id=k_u.app_user_id','INNER');
		$this->db->join('ks_models m','a.model_id=m.model_id','INNER');
		$this->db->join('ks_manufacturers  k_m','a.ks_manufacturers_id=k_m.manufacturer_id','INNER');
		$this->db->join('ks_gear_categories  k_g_c','a.ks_category_id=k_g_c.gear_category_id','LEFT');
		$this->db->join('ks_gear_categories  k_g_s_c','a.ks_sub_category_id=k_g_s_c.gear_category_id','LEFT');
		$this->db->where(array('a.user_gear_desc_id'=>$gear_id));
		$query = $this->db->get();
		$gear_details  =$query->row();
		$this->db->select('AVG(gear_star_rating_value) AS rating');
		$this->db->from('ks_cust_gear_star_rating ');
		$this->db->where('user_gear_desc_id',$gear_id);
		$query = $this->db->get();
		$rating  =$query->row();
		if (!empty($gear_details)) {
					$gear_details->avg_rating  = $rating->rating;
		}else{
		//	$gear_details->avg_rating   = '0';
		}
		$this->db->select('*');
		$this->db->from('ks_user_gear_images');
		$this->db->where('user_gear_desc_id',$gear_id);
		$this->db->where('is_deleted','0');
		$query = $this->db->get();
		$images  =$query->result_array();
		if (!empty($gear_details)) {
			if (!empty($images)) {
				$i=0;
				foreach ($images as $value) {

					//$images[$i]['gear_display_image']=  BASE_URL.'uploads/gear/'.$value['gear_display_image'];
					$images[$i]['gear_display_image']=  GEAR_IMAGE.$value['gear_display_image'];
					$i++;
				}
			}
					$gear_details->images  = $images;

			$this->db->select('u_a.*,ks_states.ks_state_name ,ks_suburbs.suburb_name,a.per_day_cost_aud_inc_gst,
							   a.per_day_cost_aud_ex_gst');
			$this->db->from('ks_gear_location As g_l');
			$this->db->where('g_l.user_gear_desc_id',$gear_id);
			$this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
			$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_a.ks_suburb_id " ,"INNER");
			$this->db->join('ks_states'," ks_states.ks_state_id = u_a.ks_state_id " ,"INNER");
			$this->db->join('ks_user_gear_description AS a '," g_l.user_gear_desc_id = a.user_gear_desc_id " ,"INNER");
			$query = $this->db->get();
			$address  =$query->result_array();	
			//print_r($address);
			$gear_details->address =  $address; 
			$this->db->select('*'); 
			$this->db->from('ks_gear_unavailable ');
			$this->db->where('user_gear_description_id',$gear_id);
			$query1 = $this->db->get();
			$ks_gear_unavailable  =$query1->result_array();
			if (!empty($ks_gear_unavailable)) {
				$gear_details->gear_unavailable   =  $ks_gear_unavailable; 
			}else{
				$gear_details->gear_unavailable   =  array(); 
			}
			$ks_gear_feature =   $this->GetFeatureList($gear_id);
			if (empty($ks_gear_feature)) {
				$gear_details->ks_gear_feature_details = array()	;
			}else{
			 	$gear_details->ks_gear_feature_details = $ks_gear_feature ;
			} 
				
		}else{
		//	$gear_details->avg_rating   = '0';
		}
	
		return $gear_details; 
   }
   public function UsergearDetails($app_user_id='')
   {
   		$this->db->select('a.*,m.model_name,k_m.manufacturer_name, k_g_c.gear_category_name, k_g_s_c.gear_category_name  AS gear_sub_category_name , k_u.app_user_first_name,k_u.app_user_last_name,k_u.user_profile_picture_link,k_u.user_description ,images.gear_display_image');
   		$this->db->from('ks_user_gear_description a');
   		$this->db->join('ks_users  k_u','a.app_user_id=k_u.app_user_id','INNER');
		$this->db->join('ks_models m','a.model_id=m.model_id','INNER');
		$this->db->join('ks_manufacturers  k_m','a.ks_manufacturers_id=k_m.manufacturer_id','INNER');
		$this->db->join('ks_gear_categories  k_g_c','a.ks_category_id=k_g_c.gear_category_id','LEFT');
		$this->db->join('ks_gear_categories  k_g_s_c','a.ks_sub_category_id=k_g_s_c.gear_category_id','LEFT');
		$this->db->join('ks_user_gear_images  images','a.user_gear_desc_id=images.user_gear_desc_id','LEFT');
		$this->db->where(array('a.app_user_id'=>$app_user_id));
		$this->db->order_by('rand()');
    	$this->db->limit(2);
    	$this->db->group_by('a.user_gear_desc_id');
		$query = $this->db->get();
		$gear_details  =$query->result_array();
		$gears=$query->result_array();
		//echo $this->db->last_query();exit();
		
		$gears_listings=array();
		
		//Gear images are assigned with full path
		foreach($gears as $gear_lists){
			foreach($gear_lists as $key=>$value){
				
				
			  
			  if($key=="user_gear_desc_id"){
			  		$rating=$this->GetGearRatings('',$gear_lists['user_gear_desc_id']);
					if(count($rating)>0)
						$gear_lists['rating']=$rating[0]['rating'];
					else
						$gear_lists['rating']=0;
			  }	
			  	  
			}
			 	if ($gear_lists['gear_display_image']=='') {
					 $gear_lists['gear_display_image']=GEAR_IMAGE."default_product.jpg";
				}else{

					 $gear_lists['gear_display_image']=GEAR_IMAGE.$gear_lists['gear_display_image'];
				}
				if ($gear_lists['user_profile_picture_link'] =='') {
					$gear_lists['user_profile_picture_link']=BASE_URL."site_upload/profile_img/profile-default-pic.png";
				}else{

				}

			array_push($gears_listings,$gear_lists);
		}	
		
		return $gears_listings;		
		// $this->db->select('AVG(gear_star_rating_value) AS rating');
		// $this->db->from('ks_cust_gear_star_rating ');
		// $this->db->where('user_gear_desc_id',$gear_id);
		// $query = $this->db->get();
		// $rating  =$query->row();
		// if (!empty($gear_details)) {
		// 			$gear_details->avg_rating  = $rating->rating;
		// }else{
		// //	$gear_details->avg_rating   = '0';
		// }
		// $this->db->select('*');
		// $this->db->from('ks_user_gear_images');
		// $this->db->where('user_gear_desc_id',$gear_id);
		// $query = $this->db->get();
		// $images  =$query->result_array();
		// if (!empty($gear_details)) {
		// 	if (!empty($images)) {
		// 		$i=0;
		// 		foreach ($images as $value) {

		// 			//$images[$i]['gear_display_image']=  BASE_URL.'uploads/gear/'.$value['gear_display_image'];
		// 			$images[$i]['gear_display_image']=  GEAR_IMAGE.$value['gear_display_image'];
		// 			$i++;
		// 		}
		// 	}
		// 			$gear_details->images  = $images;

		// $this->db->select('u_a.*,ks_states.ks_state_name ,ks_suburbs.suburb_name,a.per_day_cost_aud_inc_gst,
		// 				   a.per_day_cost_aud_ex_gst');
		// $this->db->from('ks_gear_location As g_l');
		// $this->db->where('g_l.user_gear_desc_id',$gear_id);
		// $this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
		// $this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_a.ks_suburb_id " ,"INNER");
		// $this->db->join('ks_states'," ks_states.ks_state_id = u_a.ks_state_id " ,"INNER");
		// $this->db->join('ks_user_gear_description AS a '," g_l.user_gear_desc_id = a.user_gear_desc_id " ,"INNER");
		// $query = $this->db->get();
		// $address  =$query->result_array();	
		// //print_r($address);
		// $gear_details->address =  $address; 
		// $this->db->select('*'); 
		// $this->db->from('ks_gear_unavailable ');
		// $this->db->where('user_gear_description_id',$gear_id);
		// $query1 = $this->db->get();
		// $ks_gear_unavailable  =$query1->result_array();
		// if (!empty($ks_gear_unavailable)) {
		// 	$gear_details->gear_unavailable   =  $ks_gear_unavailable; 
		// }else{
		// 	$gear_details->gear_unavailable   =  array(); 
		// }
			
		// }else{
		// //	$gear_details->avg_rating   = '0';
		// }
	
		return $gear_details; 
   }
    public function UserrelatedGear($k_category_id='',$gear_id='')
   {
   		$this->db->select('a.*,m.model_name,k_m.manufacturer_name, k_g_c.gear_category_name, k_g_s_c.gear_category_name  AS gear_sub_category_name , k_u.app_user_first_name,k_u.app_user_last_name,k_u.user_profile_picture_link,k_u.user_description ,images.gear_display_image');
   		$this->db->from('ks_user_gear_description a');
   		$this->db->join('ks_users  k_u','a.app_user_id=k_u.app_user_id','INNER');
		$this->db->join('ks_models m','a.model_id=m.model_id','INNER');
		$this->db->join('ks_manufacturers  k_m','a.ks_manufacturers_id=k_m.manufacturer_id','INNER');
		$this->db->join('ks_gear_categories  k_g_c','a.ks_category_id=k_g_c.gear_category_id','LEFT');
		$this->db->join('ks_gear_categories  k_g_s_c','a.ks_sub_category_id=k_g_s_c.gear_category_id','LEFT');
		$this->db->join('ks_user_gear_images  images','a.user_gear_desc_id=images.user_gear_desc_id','LEFT');
		$this->db->where(array('a.ks_category_id'=>$k_category_id));
		$this->db->where(array('a.user_gear_desc_id != ' =>$gear_id));
		$this->db->order_by('rand()');
    	$this->db->limit(2);
    	$this->db->group_by('a.user_gear_desc_id');
		$query = $this->db->get();
		$gear_details  =$query->result_array();
		$gears=$query->result_array();
		//echo $this->db->last_query();exit();
		
		$gears_listings=array();
		
		//Gear images are assigned with full path
		foreach($gears as $gear_lists){
			foreach($gear_lists as $key=>$value){
				
				
			  
			  if($key=="user_gear_desc_id"){
			  		$rating=$this->GetGearRatings('',$gear_lists['user_gear_desc_id']);
					if(count($rating)>0)
						$gear_lists['rating']=$rating[0]['rating'];
					else
						$gear_lists['rating']=0;
			  }	
			  	  
			}
			 	if ($gear_lists['gear_display_image']=='') {
					 $gear_lists['gear_display_image']=GEAR_IMAGE."default_product.jpg";
				}else{

					 $gear_lists['gear_display_image']=GEAR_IMAGE.$gear_lists['gear_display_image'];
				}
				if ($gear_lists['user_profile_picture_link'] =='') {
					$gear_lists['user_profile_picture_link']=BASE_URL."site_upload/profile_img/profile-default-pic.png";
				}else{

				}

			array_push($gears_listings,$gear_lists);
		}	
		
		return $gears_listings;		
	
   }
   public function privategeratDetails($gear_id='',$app_user_id)
   {
   		$this->db->select('a.*,m.model_name,k_m.manufacturer_name, k_g_c.gear_category_name,k_g_s_c.gear_category_name AS gear_sub_category_name ,k_u.app_user_first_name,k_u.app_user_last_name,k_u.user_profile_picture_link,k_u.user_description');
   		$this->db->from('ks_user_gear_description a');
   		$this->db->join('ks_users  k_u','a.app_user_id=k_u.app_user_id','INNER');
		$this->db->join('ks_models m','a.model_id=m.model_id','LEFT');
		$this->db->join('ks_manufacturers  k_m','a.ks_manufacturers_id=k_m.manufacturer_id','LEFT');
		
		$this->db->join('ks_gear_categories  k_g_c','a.ks_category_id=k_g_c.gear_category_id','LEFT');
		$this->db->join('ks_gear_categories  k_g_s_c','a.ks_sub_category_id=k_g_s_c.gear_category_id','LEFT');
		$this->db->where(array('a.user_gear_desc_id'=>$gear_id , 'a.app_user_id'=>$app_user_id));
		$query = $this->db->get();
		$gear_details  =$query->row();

		$this->db->select('AVG(gear_star_rating_value) AS rating');
		$this->db->from('ks_cust_gear_star_rating ');
		$this->db->where('user_gear_desc_id',$gear_id);
		$query = $this->db->get();
		$rating  =$query->row();
		if (!empty($gear_details)) {
					$gear_details->avg_rating  = $rating->rating;
		}else{
		//	$gear_details->avg_rating   = '0';
		}
		$this->db->select('*');
		$this->db->from('ks_user_gear_images');
		$this->db->where('user_gear_desc_id',$gear_id);
		$this->db->where('is_deleted','0');
		$query = $this->db->get();
		$images  =$query->result_array();

		if (!empty($gear_details)) {
			if (!empty($images)) {
				$i=0;
				foreach ($images as $value) {

					//$images[$i]['gear_display_image']=  BASE_URL.'site_upload/gear_images/'.$value['gear_display_image'];
					$images[$i]['gear_display_image']=  GEAR_IMAGE.$value['gear_display_image'];
					$i++;
				}
			}
					$gear_details->images  = $images;
			$this->db->select('u_a.*,ks_states.ks_state_name ,ks_suburbs.suburb_name,a.per_day_cost_aud_inc_gst,
							   a.per_day_cost_aud_ex_gst');
			$this->db->from('ks_gear_location As g_l');
			$this->db->where('g_l.user_gear_desc_id',$gear_id);
			$this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
			$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_a.ks_suburb_id " ,"INNER");
			$this->db->join('ks_states'," ks_states.ks_state_id = u_a.ks_state_id " ,"INNER");
			$this->db->join('ks_user_gear_description AS a '," g_l.user_gear_desc_id = a.user_gear_desc_id " ,"INNER");
			$query = $this->db->get();
			$address  =$query->result_array();	
			//print_r($address);
			$gear_details->address =  $address; 	

			$this->db->select('*'); 
			$this->db->from('ks_gear_unavailable ');
			$this->db->where('user_gear_description_id',$gear_id);
			$query1 = $this->db->get();
			$ks_gear_unavailable  =$query1->result_array();
			if (!empty($ks_gear_unavailable)) {
				$gear_details->gear_unavailable   =  $ks_gear_unavailable; 
			}else{
				$gear_details->gear_unavailable   =  array(); 
			}	

			$ks_gear_feature =   $this->GetFeatureList($gear_id);
			//print_r($ks_gear_feature);
			if (empty($ks_gear_feature)) {
				$gear_details->ks_gear_feature_details = array()	;
			}else{
			 $gear_details->ks_gear_feature_details = $ks_gear_feature ;
			} 
			if (!empty($gear_details->feature_master_id)) {
				$feature_id_array =  explode(',', $gear_details->feature_master_id) ;
				$gear_feature_ids = '';
				foreach ($feature_id_array as  $value) {
					$gear_feature_ids.= "'". $value."',";
				}
				$where =  "feature_master_id IN (".rtrim($gear_feature_ids , ',').")  ";
				$query = $this->db->query("SELECT * FROM  ks_feature_master WHERE " .$where);
				$feature_list =   $query->result(); 
				if (!empty($feature_list )) {
						$gear_details->feature_list = $feature_list ; 
				}else{
						$gear_details->feature_list =array() ; 
				}
				
				
			}else{
				$gear_details->feature_list =array(); 
			}

		}else{
		//	$gear_details->avg_rating   = '0';
		}
		
		return $gear_details;  
   }


   public function getModelsList($where_array,$limit=0,$offset=0,$order_by_fld='',$order_by=''){
	
	$this->db->select('a.user_gear_desc_id,
						   a.ks_gear_type_id,
						   a.gear_name,
						   a.model_id,
						   a.ks_category_id,
						   a.ks_manufacturers_id,
						   a.app_user_id,
						   a.per_day_cost_aud_inc_gst,
						   a.per_day_cost_aud_ex_gst,
						    b.app_user_first_name,
						    b.app_user_last_name,
						   b.user_profile_picture_link,
						   b.owner_type_id,
						   c.gear_display_image,
						   c.gear_display_seq_id ,
						   d.manufacturer_name,e.model_name,f.gear_category_name');
		$this->db->from('ks_user_gear_description a');
		$this->db->join('ks_users b','a.app_user_id=b.app_user_id', 'LEFT');
		$this->db->join('ks_manufacturers d','a.ks_manufacturers_id=d.manufacturer_id','INNER');
		$this->db->join('ks_user_gear_images c','a.user_gear_desc_id=c.user_gear_desc_id AND a.model_id=c.model_id', 'LEFT');
		$this->db->join('ks_models e','a.model_id=e.model_id', 'INNER');
		$this->db->join('ks_gear_categories f','a.ks_category_id=f.gear_category_id', 'INNER');
		if($this->input->get('from_date') != '' && $this->input->get('to_date') != '' ){
		$this->db->join('ks_gear_unavailable','a.user_gear_desc_id = ks_gear_unavailable.user_gear_description_id', 'LEFT');
		}
		if ($this->input->get('feature_master_id')) {
			$this->db->join('ks_user_gear_features AS g_feat ','a.user_gear_desc_id = g_feat.user_gear_desc_id', 'INNER');	
		}else{
			$this->db->join('ks_user_gear_features AS g_feat ','a.user_gear_desc_id = g_feat.user_gear_desc_id', 'LEFT');	
		}
		if ($this->input->get('address') || $this->input->get('searchby_fields') == 'distance') {
		$this->db->join('ks_gear_location AS g_loc ','a.user_gear_desc_id = g_loc.user_gear_desc_id', 'INNER');	
		$this->db->join('ks_user_address AS u_add ','g_loc.user_address_id = u_add.user_address_id', 'INNER');	
		$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_add.ks_suburb_id " ,"INNER");
		$this->db->join('ks_states'," ks_states.ks_state_id = u_add.ks_state_id " ,"INNER");
		}else
		$this->db->where(array('a.is_active'=>'Y'));
		$this->db->where(array('a.gear_hide_search_results'=>'Y'));
			$this->db->where(array('a.gear_type !='=>'3'));
			$this->db->where(array('a.ks_gear_type_id !='=>'3'));
			

		if ($where_array != '') {
			$this->db->where($where_array);
		}
			$this->db->limit($limit,$offset);
		if ($order_by =='') {
			$this->db->order_by('rand()');
		}else{	
			$this->db->order_by('a.user_gear_desc_id',$order_by);
		}
		$this->db->group_by('a.user_gear_desc_id');
		$query = $this->db->get();
			
		$gears=$query->result_array();
		//echo $this->db->last_query();exit();
		
		$gears_listings=array();
	//	echo "<pre>";
		
		//Gear images are assigned with full path
		foreach($gears as $gear_lists){
			foreach($gear_lists as $key=>$value){
				
				// if ($value->gear_display_image=='') {
				// 	 $gear_lists['gear_display_image']=PROFILE_IMG."default-profile-pic.jpg";
				// }else{


				// }
			  //  if($key=="user_profile_picture_link"){
			  // 	//print_r($gear_lists[$key]);
			  // 	if($gear_lists[$key]!=""){
					// if(!strstr($gear_lists[$key],"https:")){	
					// 	$img_path = "";			
					// 	//$img_path=base_url().PROFILE_IMG.$gear_lists[$key];
					// 	$gear_lists[$key]=$img_path;
					// }
				 // }else{
				 // $gear_lists[$key]=PROFILE_IMG."default-profile-pic.jpg";
				 // }
			  // }	
			  
			  if($key=="user_gear_desc_id"){
			  		$rating=$this->GetGearRatings('',$gear_lists['user_gear_desc_id']);
					if(count($rating)>0)
						$gear_lists['rating']=$rating[0]['rating'];
					else
						$gear_lists['rating']=0;
			  }	
			  	  
			}
			 	if ($gear_lists['gear_display_image']=='') {
					 $gear_lists['gear_display_image']=GEAR_IMAGE."default_product.jpg";
				}else{

					 $gear_lists['gear_display_image']=GEAR_IMAGE.$gear_lists['gear_display_image'];
				}
				if ($gear_lists['user_profile_picture_link'] =='') {
					$gear_lists['user_profile_picture_link']=BASE_URL."site_upload/profile_img/profile-default-pic.png";
				}else{

				}

			array_push($gears_listings,$gear_lists);
		}	
		
		return $gears_listings;		


		// $this->db->select('a.model_id,a.model_name,a.gear_category_id,a.manufacturer_id,a.model_image,a.is_active,a.model_description,a.per_day_cost_usd,a.per_day_cost_aud_ex_gst,						  						  a.per_day_cost_aud_inc_gst,a.replacement_value_usd,
		// 				  a.replacement_value_aud_ex_gst,a.replacement_value_aud_inc_gst,a.replacement_day_rate_percent,
		// 				  b.gear_category_name,c.manufacturer_name');
		// $this->db->from('ks_models a');
		// $this->db->join('ks_gear_categories b','a.gear_category_id=b.gear_category_id');
		// $this->db->join('ks_manufacturers c','a.manufacturer_id=c.manufacturer_id');
		// $this->db->where($where_array);
		// if($limit>0)
		// 	$this->db->limit($limit, $offset);
		// if($order_by!='')
		// 	$this->db->order_by($order_by_fld,$order_by);
		
		// $query = $this->db->get();
				
		// return $query;
	
	}

	public function GearFavList($app_user_id ,$where=""	)
	{
		//print_r();die;
		$this->db->select('a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						c.app_user_first_name,
						c.app_user_last_name,
						d.gear_category_id,a.user_gear_desc_id ,e.ks_favourite_id');
		$this->db->from('ks_user_gear_description as a');
		$this->db->join('ks_user_gear_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_models as d', 'a.model_id = d.model_id');
		$this->db->join('ks_user_gear_favourite as e', 'a.user_gear_desc_id = e.user_gear_desc_id');
		
		$this->db->where(array('e.app_user_id'=>$app_user_id ));

		//$this->db->where('a.user_gear_desc_id',$where['user_gear_desc_id'] );
		if($where != ''){
		$this->db->where($where);
		}
		$this->db->group_by('a.user_gear_desc_id');
		$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		//print_r($this->db->last_query());
		$gears =  $query->result_array();
		$gears_listings=array();

		foreach($gears as $gear_lists){
			foreach($gear_lists as $key=>$value){
			  if($key=="gear_display_image"){
			  	$img_path = "";
				//$img_path=base_url().GEAR_IMG.$value;
				$gear_lists[$key]=$img_path;
				
			  }else if($key=="user_profile_picture_link"){
			  
			  	if($gear_lists[$key]!=""){
					if(!strstr($gear_lists[$key],"https:")){	
						$img_path = "";			
						//$img_path=base_url().PROFILE_IMG.$gear_lists[$key];
						$gear_lists[$key]=$img_path;
					}
				 }else{
				 	//$gear_lists[$key]=BASE_URL.PROFILE_IMG."default-profile-pic.jpg";
				 	$gear_lists[$key]=BASE_URL."site_upload/profile_img/profile-default-pic.png";
				 }
			  }	
			  
			  if($key=="user_gear_desc_id"){
			  		$rating=$this->GetGearRatings('',$gear_lists['user_gear_desc_id']);
					if(count($rating)>0)
						$gear_lists['rating']=$rating[0]['rating'];
					else
						$gear_lists['rating']=0;
			  }	
			  	  
			}

				array_push($gears_listings,$gear_lists);
		}
		//print_r($gear_lists); die;
		return $gears_listings;		
	}

		public function getUserCart($app_user_id)
	{
		
		$this->db->select('cart.user_gear_rent_detail_id,cart.user_gear_desc_id,cart.user_gear_rent_id,cart.security_deposit,
		cart.gear_rent_requested_on,cart.gear_rent_request_from_date,cart.gear_rent_request_to_date,
		cart.gear_total_rent_request_days,cart.total_rent_amount_ex_gst,cart.gst_amount,cart.other_charges,cart.total_rent_amount,a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						c.app_user_first_name,c.app_user_id,
						c.app_user_last_name,c.registered_for_gst,
						d.gear_category_id,a.user_gear_desc_id,cart.user_gear_desc_id');

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
		$this->db->join('ks_user_gear_description as a' , 'a.user_gear_desc_id = cart.user_gear_desc_id' ,'INNER');
		 $this->db->join('ks_user_gear_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id', 'LEFT');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_models as d', 'a.model_id = d.model_id');
		$this->db->where('gear.app_user_id',$app_user_id );
		$this->db->where('status_master.is_active','N' );
		
		$this->db->group_by('a.user_gear_desc_id');
	//	$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		$data = 	$query->result_array();
		
		return  $data ;
	}

	public function CheckGearInCart($user_gear_desc_id, $app_user_id)
	{
		$this->db->select('cart.user_gear_rent_detail_id,cart.user_gear_desc_id,cart.user_gear_rent_id,
		cart.gear_rent_requested_on,cart.gear_rent_request_from_date,cart.gear_rent_request_to_date,
		cart.gear_total_rent_request_days,cart.total_rent_amount_ex_gst,cart.gst_amount,cart.other_charges,cart.total_rent_amount,a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						c.app_user_first_name,
						c.app_user_last_name,
						d.gear_category_id,a.user_gear_desc_id,status_master.ks_rent_status_master_id');

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
		$this->db->join('ks_user_gear_description as a' , 'a.user_gear_desc_id = cart.user_gear_desc_id' ,'INNER');
		$this->db->join('ks_user_gear_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id', 'LEFT');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_models as d', 'a.model_id = d.model_id');
		$this->db->where('gear.app_user_id',$app_user_id );
		$this->db->where('status_master.is_active','N' );
		$this->db->where('cart.user_gear_desc_id',$user_gear_desc_id );
		
		$this->db->group_by('a.user_gear_desc_id');
	
		$query = $this->db->get();
		$data = 	$query->row();
		
		return  $data ;
	}

	public function RemoveGearCart($user_gear_rent_detail_id,$user_gear_rent_id,$ks_rent_status_master_id)
	{
		$this->db->where('user_gear_rent_detail_id', $user_gear_rent_detail_id);
		$this->db->delete('ks_user_gear_rent_details');

		$this->db->where('user_gear_rent_id', $user_gear_rent_id);
		$this->db->delete('ks_user_gear_rent_master');

		$this->db->where('ks_rent_status_master_id', $ks_rent_status_master_id);
		$this->db->delete('ks_rent_status_master');


	}
	public function GearAddress($gear_id)
	{
		$this->db->select('u_a.*,ks_states.ks_state_name ,ks_suburbs.suburb_name');
		$this->db->from('ks_gear_location As g_l');
		$this->db->where('user_gear_desc_id',$gear_id);
		$this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
		$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_a.ks_suburb_id " ,"INNER");
		$this->db->join('ks_states'," ks_states.ks_state_id = u_a.ks_state_id " ,"INNER");
		$query = $this->db->get();
		$address  =$query->result_array();
		return $address ;
	}
	public function GetAllRefernce($app_user_id)
	{
		$this->db->select('users.*,refernce.*');
		$this->db->from('ks_user_reference As refernce');
		$this->db->where('refernce.create_user',$app_user_id);
		$this->db->join('ks_users AS users'," users.app_user_id = refernce.app_user_id " ,"INNER");
		$query = $this->db->get();
		$reference  =$query->result_array();
		return $reference ;
	}
	public function GetAllReview($app_user_id='')
	{
		$this->db->select('reviews.*,users.app_user_first_name,users.app_user_last_name,users.app_username,users.user_profile_picture_link');
		$this->db->from('ks_cust_gear_reviews As reviews');
		$this->db->where('gears.app_user_id',$app_user_id);
		$this->db->join('ks_user_gear_description AS gears'," gears.user_gear_desc_id = reviews.user_gear_desc_id " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = reviews.app_user_id " ,"INNER");
		$query = $this->db->get();
		$reviews  =$query->result_array();
		
		return $reviews ;
	}
	public function getMyorderList($app_user_id,$where)
	{
		$this->db->select('rent_details.*,users.app_user_first_name,users.app_user_last_name,users.app_username,users.user_profile_picture_link,gears.gear_name' );
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->where('rent_details.create_user',$app_user_id);
		if (!empty($where)) {
				$this->db->where($where);
		}
		$this->db->join('ks_user_gear_description AS gears'," gears.user_gear_desc_id = rent_details.user_gear_desc_id " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = gears.app_user_id " ,"INNER");
		$query = $this->db->get();
		return $query ;
	}

	public function getRequestedorderList($app_user_id,$where)
	{
		$this->db->select('rent_details.*,users.app_user_first_name,users.app_user_last_name,users.app_username,users.user_profile_picture_link,gears.gear_name' );
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_user_gear_description AS gears'," gears.user_gear_desc_id = rent_details.user_gear_desc_id " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		$this->db->where('gears.app_user_id',$app_user_id);
		if (!empty($where)) {
				$this->db->where($where);
		}
		$query = $this->db->get();
		return $query ;
	}

	public function GetFeatureList($gear_id='')
	{
		$this->db->select("*") ; 
		$this->db->from('ks_user_gear_features As features_details');
		$this->db->join('ks_gear_feature_details ','features_details.feature_details_id  =ks_gear_feature_details.feature_details_id ','INNER');
		$this->db->where('features_details.user_gear_desc_id',$gear_id);
		$query = $this->db->get();
		$features_details  =  $query->result();
		return $features_details ;
	}


}
//end of class
?>