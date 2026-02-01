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
	public function loginCheck($email,$password,$user_signup_type="") 
	{
		$this->db->select('*');
		$this->db->from('ks_users');
		$this->db->where(array('primary_email_address' => $email, 'app_password' => $password));
		if($user_signup_type!="")
			$this->db->where(array('user_signup_type' => $user_signup_type));
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
   	public function ProductReview($app_user_id,$owner_type = 'Owners & Renters',$limit='',$offset=0,$show_all='Y',$order_id=0)
    {
    	if($show_all!='C'){
			$this->db->select('a.* ,a.cust_gear_review_desc AS cust_gear_review_desc ,e.app_user_first_name reviewer_fname,
				 e.app_user_last_name reviewer_lname,e.show_business_name,e.bussiness_name,
				 d.app_user_first_name profile_fname,
				 d.app_user_last_name profile_lname,
				 d.app_user_id profile_id,
				 e.user_profile_picture_link,
                 d.app_user_id responses');
        }else{
        
        	$this->db->select('a.* ,a.cust_gear_review_desc AS cust_gear_review_desc ,e.app_user_first_name reviewer_fname,
				 e.app_user_last_name reviewer_lname,e.show_business_name,e.bussiness_name,
				 d.app_user_first_name profile_fname,
				 d.app_user_last_name profile_lname,
				 d.app_user_id profile_id,
				 e.user_profile_picture_link');
        
        }
		  $this->db->from('ks_cust_gear_reviews a');

		  $this->db->join('ks_users d', 'd.app_user_id = a.app_user_id');
		  $this->db->join('ks_users e', 'e.app_user_id = a.create_user');
		  $this->db->where(array('a.app_user_id'=>$app_user_id)); 
		  
		  if($show_all=='P') //For parent records only
			$this->db->where(array('a.parent_gear_review_id'=>0));
		  else if($show_all=='C'){ //For child records only
			$this->db->where(array('a.parent_gear_review_id!='=>0,'a.order_id'=>$order_id));
		  }
		  
		  $this->db->group_by('a.order_id');
		  $this->db->order_by('a.ks_cust_gear_review_id', 'DESC');
		  
		  if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		  }
		  
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
	
	public function GearList($app_user_id ,$limit ,$offset='',$order_by='')
	{	
		
		if (!empty($limit)) {
			$limit =  "LIMIT ".  $offset . "," .$limit ; 

		}else{
			$limit = '';
		}
		 $sql = "Select * FROM   ( Select a.gear_name,a.create_date,a.is_active,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,a.gear_slug_name,
						b.gear_display_image,a.ks_gear_type_id,
						c.app_user_first_name,
						c.app_user_last_name,a.gear_hide_search_results,
						a.ks_category_id AS gear_category_id,a.user_gear_desc_id,a.per_weekend_cost_aud_ex_gst,a.per_weekend_cost_aud_inc_gst,a.per_week_cost_aud_ex_gst,a.per_week_cost_aud_inc_gst  

						FROM  ks_user_gear_description as a
						LEFT JOIN  ks_user_gear_images as b ON 	 a.user_gear_desc_id = b.user_gear_desc_id  AND b.is_deleted = 0
						INNER JOIN  ks_users as c ON a.app_user_id = c.app_user_id 
						LEFT JOIN  ks_models as d ON a.model_id = d.model_id 
						WHERE a.app_user_id='".$app_user_id. "' AND a.is_deleted != 'Yes'
						AND a.is_active = 'Y'  AND  a.gear_type !='3'
						AND a.ks_gear_type_id != '3' group  BY a.user_gear_desc_id 
						 ) As z order by z.create_date DESC    ".$limit." 

						" ;  
		// 				echo "<br>";
		// $this->db->select('a.gear_name,a.create_date,a.is_active,
		// 				a.per_day_cost_aud_ex_gst,
		// 				a.per_day_cost_aud_inc_gst,a.gear_slug_name,
		// 				b.gear_display_image,a.ks_gear_type_id,
		// 				c.app_user_first_name,
		// 				c.app_user_last_name,a.gear_hide_search_results,
		// 				a.ks_category_id AS gear_category_id,a.user_gear_desc_id,a.per_weekend_cost_aud_ex_gst,a.per_weekend_cost_aud_inc_gst,a.per_week_cost_aud_ex_gst,a.per_week_cost_aud_inc_gst ');
		// $this->db->from('ks_user_gear_description as a');
		// $this->db->join('ks_user_gear_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id  AND b.is_deleted = 0',"LEFT");
		// $this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		// $this->db->join('ks_models as d', 'a.model_id = d.model_id','left');
		
		// 	$this->db->where(array('a.app_user_id'=>$app_user_id, ));
		// 	  $this->db->where(array('a.is_deleted !='=>'Yes'));		
		// 	 $this->db->where(array('a.is_active ='=>'Y' ));
		// 	  $this->db->where(array('a.gear_type !='=>'3'));
		// 	 $this->db->where(array('a.ks_gear_type_id !='=>'3'));
		
		// 		$this->db->limit($limit,$offset);
					
		// $this->db->group_by('a.user_gear_desc_id');
		// // $this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->query($sql);
		// return $query->result();
		// print_r($query);
		// echo $this->db->last_query();
		// die;
					return $query->result_array();
		
	}
	public function GearListConditons($app_user_id ,$where,$limit='', $offset='')
	{
		
		$this->db->select('a.gear_name,a.create_date,a.is_active,
						a.per_day_cost_aud_ex_gst,a.gear_slug_name,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						c.app_user_first_name,a.ks_gear_type_id,
						c.app_user_last_name,
						a.gear_hide_search_results,
						a.ks_category_id AS gear_category_id,a.user_gear_desc_id, a.ks_category_id,a.per_weekend_cost_aud_ex_gst,a.per_weekend_cost_aud_inc_gst,a.per_week_cost_aud_ex_gst,a.per_week_cost_aud_inc_gst');
		$this->db->from('ks_user_gear_description as a');
		$this->db->join('ks_user_gear_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id AND b.is_deleted = 0 ',"LEFT");
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');

		
		$this->db->where(array('a.app_user_id'=>$app_user_id ));
		$this->db->where(array('a.is_active ='=>'Y' ));
		$this->db->where(array('a.gear_type !='=>'3'));
		$this->db->where(array('a.ks_gear_type_id !='=>'3'));
		$this->db->where(array('a.is_deleted !='=>'Yes'));
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
		if(!empty($where))		
			$this->db->where($where);
		$this->db->group_by('a.user_gear_desc_id');
		$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();

		return $query->result_array();
		
	}
	
	//Profile Page Gear Search
	public function ProfileGearSearch($app_user_id,$search_txt,$where_array,$limit='',$offset='',$order_by='DESC'){
		
		if($search_txt!=""){
			
		$sql="SELECT a.gear_name,a.create_date,
				a.is_active,
				a.per_day_cost_aud_ex_gst,
				a.gear_slug_name,
				a.per_day_cost_aud_inc_gst,
				c.gear_display_image,
				b.app_user_first_name,
				a.ks_gear_type_id,
				b.app_user_last_name,
				a.gear_hide_search_results,
				a.ks_category_id AS gear_category_id,
				a.user_gear_desc_id, a.ks_category_id,
				a.per_weekend_cost_aud_ex_gst,
				a.per_weekend_cost_aud_inc_gst,
				a.per_week_cost_aud_ex_gst,
				a.per_week_cost_aud_inc_gst,
				a.model_name,
				a.manufacturer_name,
			   MATCH(gear_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance,
			   MATCH(model_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance1,
			   MATCH(manufacturer_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance2,
			   MATCH(gear_description_1) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance4,
			   MATCH(gear_description_2) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance5,	
			   MATCH(gear_category_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance6,
			   MATCH(gear_subcategory_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance7,
			   MATCH(gear_name,model_name,manufacturer_name,gear_description_1,gear_description_2,gear_category_name,gear_subcategory_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance3
			   FROM `ks_user_gear_search` a
			   INNER JOIN `ks_users` b ON a.`app_user_id`=b.`app_user_id`
			   LEFT JOIN `ks_user_gear_images` c ON a.`user_gear_desc_id` = c.`user_gear_desc_id` AND c.`is_deleted` = 0";
		}else{
			
			$sql="SELECT a.gear_name,a.create_date,
				a.is_active,
				a.per_day_cost_aud_ex_gst,
				a.gear_slug_name,
				a.per_day_cost_aud_inc_gst,
				c.gear_display_image,
				b.app_user_first_name,
				a.ks_gear_type_id,
				b.app_user_last_name,
				a.gear_hide_search_results,
				a.ks_category_id AS gear_category_id,
				a.user_gear_desc_id, a.ks_category_id,
				a.per_weekend_cost_aud_ex_gst,
				a.per_weekend_cost_aud_inc_gst,
				a.per_week_cost_aud_ex_gst,
				a.per_week_cost_aud_inc_gst,
				a.model_name,
				a.manufacturer_name
			   FROM `ks_user_gear_search` a
			   INNER JOIN `ks_users` b ON a.`app_user_id`=b.`app_user_id`
			   LEFT JOIN `ks_user_gear_images` c ON a.`user_gear_desc_id` = c.`user_gear_desc_id` AND c.`is_deleted` = 0";
			
		}
			   
		$sql.=" WHERE ";
		if(empty($search_txt)==false)
			$sql.="MATCH(gear_name,model_name,manufacturer_name,gear_description_1,gear_description_2,gear_category_name,gear_subcategory_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AND ";
		
		$sql.="a.`app_user_id`='".$app_user_id."' AND a.`is_deleted`='No' AND a.`is_active`='Y' AND a.`gear_type`!='3'";
		
		if($where_array!="")
			$sql.= " AND ".$where_array;
		
		$sql.=" GROUP BY a.`user_gear_desc_id`";
		
		$sql.=" ORDER BY ";
		
		
		if(empty($search_txt)==true)
			$sql.="a.`user_gear_desc_id` ".$order_by;
		else if(empty($search_txt)==false)
			$sql.= "relevance+relevance1+relevance2+relevance6+relevance7+relevance3+relevance4+relevance5 DESC";
	
		if (!empty($limit)) {
			$sql.=" LIMIT ".$offset.",".$limit;
		}
		
		$result = $this->common_model->get_records_from_sql($sql);
	
		$gears = json_decode(json_encode($result), true);
		
		return $gears;
		
	}
	
	
    //Method to pickup the categories against which the user has gears
    public function GearCategories($app_user_id,$where, $limit='',$offset='',$order_by='',$search_txt='')
	{
		
	  $this->db->select('ks_user_gear_description.user_gear_desc_id,ks_user_gear_description.gear_slug_name,
						  ks_user_gear_description.model_id,ks_user_gear_description.gear_slug_name,
						  ks_user_gear_description.app_user_id,
						  ks_user_gear_description.is_active,
						  ks_user_gear_description.ks_category_id AS gear_category_id,
						  ks_gear_categories.gear_category_name ');
		$this->db->from('ks_user_gear_description');
		// $this->db->join('ks_models','ks_user_gear_description.model_id=ks_models.model_id',"LEFT");
		$this->db->join('ks_gear_categories','ks_user_gear_description.ks_category_id=ks_gear_categories.gear_category_id');
		
		$this->db->where(array('ks_user_gear_description.app_user_id'=>$app_user_id ));
		$this->db->where(array('ks_user_gear_description.is_active ='=>'Y' ));
		$this->db->where(array('ks_user_gear_description.gear_type !='=>'3'));
		$this->db->where(array('ks_user_gear_description.ks_gear_type_id !='=>'Yes'));
		
		$this->db->order_by('ks_gear_categories.gear_category_name', 'ASC');
		$this->db->group_by('ks_gear_categories.gear_category_id'); 
		
		$query = $this->db->get();
		$result_categories = $query->result_array();
		
		if (!empty($result_categories)) {
			$i = 0 ;
			foreach ($result_categories as  $value) {
			 	$result_categories[$i]['gear_category_name'] =  htmlspecialchars_decode($value['gear_category_name']  ,ENT_QUOTES) ;
				 $i++;
			}
		}
			
		//$gears=$this->GearListConditons($app_user_id,$where,$limit,$offset,$order_by );
			
		$gears = $this->ProfileGearSearch($app_user_id,$search_txt,$where,$limit,$offset);
		
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
			  			if (file_exists(BASE_IMG.'site_upload/gear_images/'.$value)) {
			  				$img_path = GEAR_IMAGE.$value;	
			  			}else{
			  				$img_path = BASE_URL."/site_upload/gear_images/default_product.jpg";
						
						}
					}	
				//$img_path = "";
				$gear_lists[$key]=$img_path;
			  }
			}
			$this->db->select('u_a.*,ks_states.ks_state_name ,ks_suburbs.suburb_name,a.per_day_cost_aud_inc_gst,
							   a.per_day_cost_aud_ex_gst');
			$this->db->from('ks_gear_location As g_l');
			$this->db->where('g_l.user_gear_desc_id',$gear_lists['user_gear_desc_id']);
			$this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
			$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_a.ks_suburb_id " ,"INNER");
			$this->db->join('ks_states'," ks_states.ks_state_id = u_a.ks_state_id " ,"INNER");
			$this->db->join('ks_user_gear_description AS a '," g_l.user_gear_desc_id = a.user_gear_desc_id " ,"INNER");
			$query = $this->db->get();
			$address  =$query->result_array();	

			
			$sql = " SELECT * FROM  ks_gear_unavailable WHERE  user_gear_description_id = '".$gear_lists['user_gear_desc_id']."' AND    date(unavailable_from_date) >= '".date('Y-m-d')."'     AND  date(unavailable_to_date) <= '".date('Y-m-d')."'" ;
			$query = $this->db->query($sql);
			$values =  $query->result();
			if (!empty($values)) {
				$gear_lists['is_setting'] = '1';
			}else{
				$gear_lists['is_setting'] = '0';
			}
			$gear_lists['address'] =  $address;
			array_push($gears_listings,$gear_lists);

		}		
		
				
		$arr=array('gear_categories'=>$result_categories,
				   'gear_lists'=>$gears_listings,
				   'gear_ratings'=>$gear_ratings);
				
		return $arr;
		
   }
   
   //Method to find out the rating against a gear
   public function GetGearRatings($app_user_id='',$user_gear_desc_id=''){
   		
		$this->db->select('AVG(a.star_rating) as rating, a.`user_gear_desc_id`');
		$this->db->from('ks_cust_gear_reviews as a');
		$this->db->join('ks_user_gear_description b','b.app_user_id=a.app_user_id');
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
						   c.gear_display_image,a.gear_hide_search_results,
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
				 	$gear_lists[$key]=BASE_URL."server/assets/images/profile.png";
				 }
			  }	
			  
			  if($key=="user_gear_desc_id"){
			  		$rating=$this->GetGearRatings($gear_lists['app_user_id'],'');
					if(count($rating)>0)
						$gear_lists['rating']= number_format((float)( $rating[0]['rating']), 0, '.', '');
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
   		$this->db->select('a.*,m.model_name,k_m.manufacturer_name, k_g_c.gear_category_name, k_g_s_c.gear_category_name  AS gear_sub_category_name ,k_u.bussiness_name ,k_u.show_business_name ,k_u.app_user_first_name,k_u.app_username,k_u.app_user_last_name,k_u.user_profile_picture_link,k_u.user_description,k_u.primary_email_address
   			');
   		$this->db->from('ks_user_gear_description a');
   		$this->db->join('ks_users  k_u','a.app_user_id=k_u.app_user_id','INNER');
		$this->db->join('ks_models m','a.model_id=m.model_id','LEFT');
		$this->db->join('ks_manufacturers  k_m','a.ks_manufacturers_id=k_m.manufacturer_id','LEFT');
		$this->db->join('ks_gear_categories  k_g_c','a.ks_category_id=k_g_c.gear_category_id','LEFT');
		$this->db->join('ks_gear_categories  k_g_s_c','a.ks_sub_category_id=k_g_s_c.gear_category_id','LEFT');
		$this->db->where(array('a.user_gear_desc_id'=>$gear_id));
		$this->db->where(array('a.is_active ='=>'Y' ));
		$query = $this->db->get();
		$gear_details  =$query->row();
		$this->db->select('AVG(gear_star_rating_value) AS rating');
		$this->db->from('ks_cust_gear_star_rating ');
		$this->db->where('user_gear_desc_id',$gear_id);
		$query = $this->db->get();
		$rating  =$query->row();
		if (!empty($gear_details)) {
					$gear_details->avg_rating  = $rating->rating;
					$gear_details->user_description = nl2br($gear_details->user_description);
		}else{
		//	$gear_details->avg_rating   = '0';
		}

		if (empty($gear_details->user_profile_picture_link)) {
					$gear_details->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
		}
		$gear_details->gear_description_1 = html_entity_decode($gear_details->gear_description_1) ;
		$gear_details->owners_remark = html_entity_decode($gear_details->owners_remark) ;
		$gear_details->gear_description_2 = html_entity_decode($gear_details->gear_description_2) ;
		$gear_details->gear_category_name = htmlspecialchars_decode($gear_details->gear_category_name ,ENT_QUOTES) ;
		$gear_details->gear_sub_category_name = htmlspecialchars_decode($gear_details->gear_sub_category_name ,ENT_QUOTES) ;

		if (  $gear_details->show_business_name=='Y') {
			$gear_details->app_user_first_name  = $gear_details->bussiness_name  ;
			$gear_details->app_user_last_name  = ''  ;
		}
			//gear_description_1
		$this->db->select('*');
		$this->db->from('ks_user_gear_images');
		$this->db->where('user_gear_desc_id',$gear_id);
		$this->db->where('is_deleted','0');
		$this->db->order_by('gear_display_seq_id','ASC');
		$query = $this->db->get();
		$images  =$query->result_array();
		if (!empty($gear_details)) {
			if (!empty($images)) {
				$i=0;
				foreach ($images as $value) {
					
					//$images[$i]['gear_display_image']=  BASE_URL.'uploads/gear/'.$value['gear_display_image'];
					if(file_exists(BASE_IMG.'site_upload/gear_images/'.$value['gear_display_image'])) {
						$images[$i]['gear_display_image']= GEAR_IMAGE.$value['gear_display_image'] ;
 					}else{
 						$images[$i]['gear_display_image'] = BASE_URL."/site_upload/gear_images/default_product.jpg";
 					}
					
					$i++;
				}
			}else{
				$images[0]['gear_display_image'] = BASE_URL."/site_upload/gear_images/default_product.jpg";
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
			
			$where = "((`unavailable_from_date`<=CURDATE() AND `unavailable_to_date`>=CURDATE()) OR (`unavailable_from_date`>=CURDATE() AND `unavailable_to_date`>=CURDATE()))";
				
			$this->db->where($where);
			
			$query1 = $this->db->get();
			$ks_gear_unavailable  =$query1->result_array();
			$rent_days_details = array();
			if (!empty($ks_gear_unavailable)) {
				foreach ($ks_gear_unavailable as  $rentedgear) {
					$rent_days_details[] =  $rentedgear;

				}	
			}
			$this->db->select('*'); 
			$this->db->from('ks_user_gear_rent_details ');
			$this->db->where('user_gear_desc_id',$gear_id);
			//$this->db->where('is_payment_completed','Y');
			$this->db->where("(order_status != 5 AND order_status != 6 AND  order_status != 8 )");
			$where = "is_payment_completed='Y' AND gear_rent_end_date>=CURDATE()";
			$this->db->where($where);
			$query1 = $this->db->get();
			$rented_gear   =$query1->result_array();
			if (!empty($rented_gear)) {
				foreach ($rented_gear as  $rentedgear) {
				
					$rent_days_details[] = array(
											'ks_gear_unavailable_id'=> '',
											'user_gear_description_id'=> $gear_id,
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
			if (!empty($rent_days_details)) {
				$gear_details->gear_unavailable   =  $rent_days_details; 
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
   		$this->db->select('a.*,m.model_name,k_m.manufacturer_name, k_g_c.gear_category_name, k_g_s_c.gear_category_name  AS gear_sub_category_name ,k_u.show_business_name ,k_u.bussiness_name,k_u.app_user_first_name,k_u.app_user_last_name,k_u.app_username,k_u.user_profile_picture_link,k_u.user_description ,images.gear_display_image, images.is_deleted');
   		$this->db->from('ks_user_gear_description a');
   		$this->db->join('ks_users  k_u','a.app_user_id=k_u.app_user_id','INNER');
		$this->db->join('ks_models m','a.model_id=m.model_id','LEFT');
		$this->db->join('ks_manufacturers  k_m','a.ks_manufacturers_id=k_m.manufacturer_id','LEFT');
		$this->db->join('ks_gear_categories  k_g_c','a.ks_category_id=k_g_c.gear_category_id','LEFT');
		$this->db->join('ks_gear_categories  k_g_s_c','a.ks_sub_category_id=k_g_s_c.gear_category_id','LEFT');
		$this->db->join('ks_user_gear_images  images','a.user_gear_desc_id=images.user_gear_desc_id','LEFT');
		$this->db->where(array('a.app_user_id'=>$app_user_id));
		$this->db->where(array('a.is_active ='=>'Y' ));
		$this->db->where(array('a.is_deleted ='=>'No' ));
		$this->db->where(array('k_u.is_active ='=>'Y' ));
		$this->db->where(array('a.gear_hide_search_results !='=>'N' ));
        $this->db->where(array('images.is_deleted'=>0 ));
		$this->db->order_by('rand()');
    	$this->db->limit(6);
    	$this->db->group_by('a.user_gear_desc_id');
		$query = $this->db->get();
		$gear_details  =$query->result_array();
		$gears=$query->result_array();
		
		
		$gears_listings=array();
		
		//Gear images are assigned with full path
		foreach($gears as $gear_lists){
			foreach($gear_lists as $key=>$value){
				
				
			  
			  if($key=="user_gear_desc_id"){
			  		$rating=$this->GetGearRatings($gear_lists['app_user_id'],'');
					if(count($rating)>0)
						$gear_lists['rating']= number_format((float)( $rating[0]['rating']), 0, '.', '');
					else
						$gear_lists['rating']=0;
			  }	
			  	  
			}
			 	if ($gear_lists['gear_display_image']=='' || $gear_lists['is_deleted']==1) {
					 $gear_lists['gear_display_image']=GEAR_IMAGE."default_product.jpg";
				}else{

					 $gear_lists['gear_display_image']=GEAR_IMAGE.$gear_lists['gear_display_image'];
				}
				if ($gear_lists['user_profile_picture_link'] =='') {
					$gear_lists['user_profile_picture_link']=BASE_URL."server/assets/images/profile.png";
				}else{

				}
				if ($gear_lists['show_business_name'] == 'Y') {
					$gear_lists['app_user_first_name'] = $gear_lists['bussiness_name'];
					$gear_lists['app_user_last_name'] = '';
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
   		$this->db->select('a.*,m.model_name,k_m.manufacturer_name, k_g_c.gear_category_name, k_g_s_c.gear_category_name  AS gear_sub_category_name , k_u.show_business_name,k_u.bussiness_name,k_u.app_user_first_name,k_u.app_username,k_u.app_user_last_name,k_u.user_profile_picture_link,k_u.user_description ,images.gear_display_image');
   		$this->db->from('ks_user_gear_description a');
   		$this->db->join('ks_users  k_u','a.app_user_id=k_u.app_user_id','INNER');
		$this->db->join('ks_models m','a.model_id=m.model_id','LEFT');
		$this->db->join('ks_manufacturers  k_m','a.ks_manufacturers_id=k_m.manufacturer_id','LEFT');
		$this->db->join('ks_gear_categories  k_g_c','a.ks_category_id=k_g_c.gear_category_id','LEFT');
		$this->db->join('ks_gear_categories  k_g_s_c','a.ks_sub_category_id=k_g_s_c.gear_category_id','LEFT');
		$this->db->join('ks_user_gear_images  images','a.user_gear_desc_id=images.user_gear_desc_id','LEFT');
		$this->db->where(array('a.ks_category_id'=>$k_category_id));
		$this->db->where(array('a.user_gear_desc_id != ' =>$gear_id));
		$this->db->where(array('a.is_active ='=>'Y' ));
		$this->db->where(array('a.is_deleted !='=>'Yes' ));
		$this->db->where(array('k_u.is_active'=>'Y' ));
		$this->db->where(array('a.gear_hide_search_results !='=>'N' ));
		$this->db->order_by('rand()');
    	$this->db->limit(6);
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
			  		$rating=$this->GetGearRatings($gear_lists['app_user_id'],'');
					if(count($rating)>0)
						$gear_lists['rating']= number_format((float)( $rating[0]['rating']), 0, '.', '');
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
				if ($gear_lists['show_business_name'] == 'Y') {
						$gear_lists['app_user_first_name'] = $gear_lists['bussiness_name'];
						$gear_lists['app_user_last_name']  = '';
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
		$this->db->where(array('a.is_active ='=>'Y' ));
		$query = $this->db->get();
		$gear_details  =$query->row();
		
		$gear_details->gear_description_1 = html_entity_decode($gear_details->gear_description_1) ;
		$gear_details->owners_remark = html_entity_decode($gear_details->owners_remark) ;
		$gear_details->gear_description_2 = html_entity_decode($gear_details->gear_description_2) ;
		$gear_details->gear_category_name = htmlspecialchars_decode($gear_details->gear_category_name,ENT_QUOTES) ;
		$gear_details->gear_sub_category_name = htmlspecialchars_decode($gear_details->gear_sub_category_name,ENT_QUOTES) ;
		$gear_details->model_name = htmlspecialchars_decode($gear_details->model_name,ENT_QUOTES) ;
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
		$this->db->order_by('gear_display_seq_id','ASC');
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
			$this->db->where('unavailable_from_date > ',date('Y-m-d'));
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
	
	$this->db->select('a.user_gear_desc_id,a.gear_slug_name,
						   a.ks_gear_type_id,
						   a.gear_name,
						   a.model_id,
						   a.ks_category_id,
						   a.ks_manufacturers_id,
						   a.app_user_id,
						   a.per_day_cost_aud_inc_gst,
						   a.per_day_cost_aud_ex_gst,
						    b.app_user_first_name,
						    b.app_user_last_name,b.app_username,
						   b.user_profile_picture_link,
						   b.owner_type_id,b.bussiness_name,b.show_business_name,
						   c.gear_display_image,
						   c.gear_display_seq_id ,
						   d.manufacturer_name,e.model_name,f.gear_category_name');
		$this->db->from('ks_user_gear_description a');
		$this->db->join('ks_users b','a.app_user_id=b.app_user_id', 'INNER');
		$this->db->join('ks_manufacturers d','a.ks_manufacturers_id=d.manufacturer_id','LEFT');
		$this->db->join('ks_user_gear_images c','a.user_gear_desc_id=c.user_gear_desc_id  AND c.is_deleted = 0', 'LEFT');
		$this->db->join('ks_models e','a.model_id=e.model_id', 'LEFT');
		$this->db->join('ks_gear_categories f','a.ks_category_id=f.gear_category_id', 'INNER');
		// if($this->input->get('from_date') != '' && $this->input->get('to_date') != '' ){
		$this->db->join('ks_gear_unavailable','a.user_gear_desc_id = ks_gear_unavailable.user_gear_description_id', 'LEFT');
		 //}
		if ($this->input->get('feature_master_id')) {
			$this->db->join('ks_user_gear_features AS g_feat ','a.user_gear_desc_id = g_feat.user_gear_desc_id', 'INNER');	
		}else{
			$this->db->join('ks_user_gear_features AS g_feat ','a.user_gear_desc_id = g_feat.user_gear_desc_id', 'LEFT');	
		}
		
		if ($this->input->get('address') || $this->input->get('suburb_name') || (!empty($this->input->get('lngWest')) && !empty($this->input->get('lngEast')) && !empty($this->input->get('latSouth')) && !empty($this->input->get('latNorth')))) {
		$this->db->join('ks_gear_location AS g_loc ','a.user_gear_desc_id = g_loc.user_gear_desc_id', 'INNER');	
		$this->db->join('ks_user_address AS u_add ','g_loc.user_address_id = u_add.user_address_id', 'INNER');	
		$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_add.ks_suburb_id " ,"INNER");
		$this->db->join('ks_states'," ks_states.ks_state_id = u_add.ks_state_id " ,"INNER");
		}
		// $this->db->where(array('a.is_active'=>'Y'));
		// $this->db->where(array('c.is_deleted'=>'0'));
		// $this->db->where(array('a.gear_hide_search_results'=>'Y'));
		// 	$this->db->where(array('a.gear_type !='=>'3'));
		// 	$this->db->where(array('a.ks_gear_type_id !='=>'3'));
			
		$this->db->where('a.is_deleted','No');
		if ($where_array != '') {
			$this->db->where($where_array);
		}
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
			
		if ($order_by_fld =='') {
			$this->db->order_by('rand()');
		}else{	
		//	echo $order_by_fld;
			$this->db->order_by('a.'.$order_by_fld,$order_by);
		}
		$this->db->group_by('a.user_gear_desc_id');
		$query = $this->db->get();
			
		$gears=$query->result_array();
	// echo $this->db->last_query();
		// die;
		//exit();
		$gears_listings=array();
	//	echo "<pre>";
		
		//Gear images are assigned with full path
		foreach($gears as $gear_lists){
			  $gear_lists['per_day_cost_aud_inc_gst']= number_format((float)( $gear_lists['per_day_cost_aud_inc_gst']), 2, '.', '');
			  $gear_lists['per_day_cost_aud_ex_gst']= number_format((float)( $gear_lists['per_day_cost_aud_ex_gst']), 2, '.', '');
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
			  		$rating=$this->GetGearRatings($gear_lists['app_user_id'],'');
					if(count($rating)>0){
						$gear_lists['rating']= number_format((float)( $rating[0]['rating']), 0, '.', '');
						$gear_lists['rating1']=  $rating[0]['rating'];
					}else{
						$gear_lists['rating']=0;
					}	
			  }	
			  	  
			}
			 	if ($gear_lists['gear_display_image']=='') {
					 $gear_lists['gear_display_image']=GEAR_IMAGE."default_product.jpg";
				}else{

					 if (file_exists(BASE_IMG.'site_upload/gear_images/'.$gear_lists['gear_display_image'])) {
					  			# code...
					  			
					 	$gear_lists['gear_display_image']=GEAR_IMAGE.$gear_lists['gear_display_image'];
					 }else{
					 	$gear_lists['gear_display_image']=GEAR_IMAGE."default_product.jpg";
					 }	
				}
				if ($gear_lists['user_profile_picture_link'] =='') {
					$gear_lists['user_profile_picture_link']=BASE_URL."site_upload/profile_img/profile-default-pic.png";
				}else{

				}
				if ($gear_lists['show_business_name'] == 'Y') {
					 $gear_lists['app_user_first_name'] = $gear_lists['bussiness_name'];
					 $gear_lists['app_user_last_name'] = '';
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
		$this->db->select('a.gear_name,a.gear_slug_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						c.app_user_first_name,c.bussiness_name,c.show_business_name,
						c.app_user_last_name,c.app_user_id,c.app_username,
						d.gear_category_id,a.user_gear_desc_id ,e.ks_favourite_id,c.user_profile_picture_link');
		$this->db->from('ks_user_gear_description as a');
		$this->db->join('ks_user_gear_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_models as d', 'a.model_id = d.model_id' ,"LEFT");
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
		//echo "<pre>";
		// print_r($gears);die;
		foreach($gears as $gear_lists){
			foreach($gear_lists as $key=>$value){
			  if($key=="gear_display_image"){
			  	//$img_path = "";
				$img_path=GEAR_IMAGE.$value;
				$gear_lists[$key]=$img_path;
				
			  }else if($key=="user_profile_picture_link"){
			  
			  	if($gear_lists[$key]!=""){
					if(!strstr($gear_lists[$key],"https:")){	
						//$img_path = "";			
						$img_path=$gear_lists[$key];
						$gear_lists[$key]=$img_path;
					}
				 }else{
				 	//$gear_lists[$key]=BASE_URL.PROFILE_IMG."default-profile-pic.jpg";
				 	$gear_lists[$key]=BASE_URL."site_upload/profile_img/profile-default-pic.png";
				 }
			  }	
			  
			  if($key=="user_gear_desc_id"){
			  		$rating=$this->GetGearRatings($gear_lists['app_user_id'],'');
					if(count($rating)>0)
						$gear_lists['rating']=  number_format((float)( $rating[0]['rating']), 0, '.', '');
					else
						$gear_lists['rating']=0;
			  }	
			  	  
			}

			if ($gear_lists['show_business_name'] == 'Y') {
				 $gear_lists['app_user_first_name'] =  $gear_lists['bussiness_name'];
				 $gear_lists['app_user_last_name'] = '';
			}
			
			//Checked whether this gear is already added to cart
			$cart_row = $this->CheckGearInCart($gear_lists['user_gear_desc_id'], $app_user_id);
			
			if($cart_row!='')
				$gear_lists['in_cart'] = 'Y';
			else
				$gear_lists['in_cart'] = 'N';
			

			array_push($gears_listings,$gear_lists);
		}
		//print_r($gear_lists); die;
		return $gears_listings;		
	}

	public function getUserCart1($app_user_id,$order_id='')
	{
		
			$this->db->select('cart.insurance_amount,cart.owner_insurance_amount,cart.user_gear_rent_detail_id,cart.ks_insurance_category_type_id,cart.user_gear_desc_id,cart.user_gear_rent_id,cart.security_deposit,
						cart.gear_rent_requested_on,cart.gear_rent_request_from_date,cart.gear_rent_request_to_date,
						cart.gear_total_rent_request_days,cart.total_rent_amount_ex_gst,cart.gst_amount,cart.other_charges,cart.total_rent_amount,a.gear_name,
						a.per_day_cost_aud_ex_gst,cart.beta_discount,cart.community_fee,cart.insurance_fee,
						a.per_day_cost_aud_inc_gst,a.ks_category_id,a.ks_sub_category_id,
						b.gear_display_image,a.security_deposit_check,a.gear_slug_name,cart.order_id,
						c.app_user_first_name,c.app_user_id,c.registered_for_gst, gear.user_address_id,
						c.app_user_last_name,c.primary_email_address,c.registered_for_gst,c.bussiness_name AS owner_bussiness_name,c.show_business_name As  owner_show_business_name,
						renter_users.app_user_first_name As renter_firstname,renter_users.app_user_id As renter_app_user_id,
						renter_users.app_user_last_name As renter_lastname,renter_users.primary_email_address As renter_email,renter_users.user_profile_picture_link as renter_image,renter_users.bussiness_name AS renter_bussiness_name,renter_users.show_business_name As renter_show_business_name,
						d.gear_category_id,a.user_gear_desc_id,cart.user_gear_desc_id ,a.replacement_value_aud_inc_gst ,a.replacement_value_aud_ex_gst , cart.total_rent_days,cart.total_rent_amount_ex_gst ,a.ks_gear_type_id');

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');	
		$this->db->join('ks_user_gear_description as a' , ' a.user_gear_desc_id = cart.user_gear_desc_id' ,'INNER');
		 $this->db->join('ks_user_gear_images as b', '  a.user_gear_desc_id = b.user_gear_desc_id', 'LEFT');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_users as renter_users', 'cart.create_user = renter_users.app_user_id');
		$this->db->join('ks_models as d', 'a.model_id = d.model_id', 'LEFT');
		$this->db->where('gear.app_user_id',$app_user_id );
		$this->db->where('status_master.is_active','N' );
		$this->db->where('cart.is_rent_approved','N' );
		$this->db->where('cart.is_payment_completed','N' );
		$this->db->where('cart.is_rent_cancelled','N' );
		$this->db->where('cart.is_rent_rejected','N' );
		$this->db->where('cart.order_id','' );
		
		$this->db->group_by('a.user_gear_desc_id');
		//	$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		$data = 	$query->result_array();
		
		return  $data ;
	}

	public function getUserCart($app_user_id)
	{
		
			$this->db->select('cart.user_gear_rent_detail_id,cart.ks_insurance_category_type_id,cart.user_gear_desc_id,cart.user_gear_rent_id,cart.security_deposit,
						cart.gear_rent_requested_on,cart.gear_rent_request_from_date,cart.gear_rent_request_to_date,
						cart.gear_total_rent_request_days,cart.total_rent_amount_ex_gst,cart.gst_amount,cart.other_charges,cart.total_rent_amount,a.gear_name,
						a.per_day_cost_aud_ex_gst,cart.beta_discount,cart.community_fee,cart.insurance_fee,
						a.per_day_cost_aud_inc_gst,a.ks_category_id,a.ks_sub_category_id,
						b.gear_display_image,a.security_deposit_check,a.gear_slug_name,cart.order_id,
						a.owner_app_user_first_name AS app_user_first_name,c.app_user_id,gear.user_address_id,
						a.owner_app_user_last_name AS app_user_last_name,c.primary_email_address,a.registered_for_gst,a.owner_bussiness_name AS owner_bussiness_name,a.app_show_business_name As owner_show_business_name,
						a.renter_app_user_first_name As renter_firstname,renter_users.app_user_id As renter_app_user_id,
						a.renter_app_user_last_name As renter_lastname,renter_users.primary_email_address As renter_email,renter_users.user_profile_picture_link as renter_image,a.renter_bussiness_name AS renter_bussiness_name,a.renter_app_show_business_name As renter_show_business_name,
						d.ks_category_id AS gear_category_id,a.user_gear_desc_id,cart.user_gear_desc_id ,a.replacement_value_aud_inc_gst ,a.replacement_value_aud_ex_gst , cart.total_rent_days,cart.total_rent_amount_ex_gst ,a.ks_gear_type_id')		;

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
		$this->db->join('ks_user_gear_order_description as a' , 'a.order_id = cart.order_id AND a.user_gear_desc_id = cart.user_gear_desc_id' ,'INNER');
		 $this->db->join('ks_user_gear_order_images as b', ' a.order_id = b.order_id AND a.user_gear_desc_id = b.user_gear_desc_id', 'LEFT');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_users as renter_users', 'cart.create_user = renter_users.app_user_id');
		// $this->db->join('ks_models as d', 'a.model_id = d.model_id', 'LEFT');
		$this->db->where('gear.app_user_id',$app_user_id );
		$this->db->where('status_master.is_active','N' );
		$this->db->where('cart.is_rent_approved','N' );
		$this->db->where('cart.is_payment_completed','N' );
		
		$this->db->group_by('a.user_gear_desc_id');
		//	$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		$data = 	$query->result_array();
		
		return  $data ;
	}
	public function getUserCart_deposite($app_user_id,$order_id)
	{
		
		$this->db->select('cart.user_gear_rent_detail_id,cart.ks_insurance_category_type_id,cart.user_gear_desc_id,cart.user_gear_rent_id,cart.security_deposit,
		cart.gear_rent_requested_on,cart.gear_rent_request_from_date,cart.gear_rent_request_to_date,
		cart.gear_total_rent_request_days,cart.total_rent_amount_ex_gst,cart.gst_amount,cart.other_charges,cart.total_rent_amount,a.gear_name,
						a.per_day_cost_aud_ex_gst,cart.beta_discount,cart.community_fee,cart.insurance_fee,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,a.gear_slug_name,
						a.owner_app_user_first_name AS app_user_first_name,c.app_user_id,gear.user_address_id,
						a.owner_app_user_last_name AS app_user_last_name,c.primary_email_address,a.is_registered_for_gst,
						a.renter_app_user_first_name As renter_firstname,renter_users.app_user_id As renter_app_user_id,
						a.renter_app_user_last_name As renter_lastname,renter_users.primary_email_address As renter_email,renter_users.user_profile_picture_link as renter_image
						,a.user_gear_desc_id,cart.user_gear_desc_id ,a.replacement_value_aud_inc_gst, cart.total_rent_days,cart.total_rent_amount_ex_gst ,a.ks_gear_type_id');

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
		$this->db->join('ks_user_gear_order_description as a' , 'a.order_id = cart.order_id AND a.user_gear_desc_id = cart.user_gear_desc_id' ,'INNER');
		 $this->db->join('ks_user_gear_order_images as b', 'a.order_id = b.order_id AND a.user_gear_desc_id = b.user_gear_desc_id', 'LEFT');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_users as renter_users', 'cart.create_user = renter_users.app_user_id');
		// $this->db->join('ks_models as d', 'a.model_id = d.model_id', 'LEFT');
		$this->db->where('gear.app_user_id',$app_user_id );
		$this->db->where('cart.order_id',$order_id );
		// $this->db->where('status_master.is_active','N' );
		// $this->db->where('cart.is_rent_approved','N' );
		// $this->db->where('cart.is_payment_completed','N' );
		
		$this->db->group_by('a.user_gear_desc_id');
	//	$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		$data = 	$query->result_array();
		
		return  $data ;
	}
	// public function CheckGearInCart($user_gear_desc_id, $app_user_id)
	// {
	// 	$this->db->select('cart.user_gear_rent_detail_id,cart.user_gear_desc_id,cart.user_gear_rent_id,
	// 	cart.gear_rent_requested_on,cart.gear_rent_request_from_date,cart.gear_rent_request_to_date,
	// 	cart.gear_total_rent_request_days,cart.total_rent_amount_ex_gst,cart.gst_amount,cart.other_charges,cart.total_rent_amount,a.gear_name,
	// 					a.per_day_cost_aud_ex_gst,a.gear_slug_name,
	// 					a.per_day_cost_aud_inc_gst,
	// 					b.gear_display_image,
	// 					c.app_user_first_name,
	// 					c.app_user_last_name,
	// 					d.gear_category_id,a.user_gear_desc_id,status_master.ks_rent_status_master_id');

	// 	$this->db->from('ks_user_gear_rent_details AS cart');
	// 	$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
	// 	$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
	// 	$this->db->join('ks_user_gear_description as a' , 'a.user_gear_desc_id = cart.user_gear_desc_id' ,'INNER');
	// 	$this->db->join('ks_user_gear_images as b', ' a.user_gear_desc_id = b.user_gear_desc_id', 'LEFT');
	// 	$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
	// 	$this->db->join('ks_models as d', 'a.model_id = d.model_id','LEFT');
	// 	$this->db->where('gear.app_user_id',$app_user_id );
	// 	$this->db->where('status_master.is_active','N' );
	// 	$this->db->where('cart.user_gear_desc_id',$user_gear_desc_id );
		
	// 	$this->db->group_by('a.user_gear_desc_id');
	
	// 	$query = $this->db->get();
	// 	$data = 	$query->row();
		
	// 	return  $data ;
	// }
	public function CheckGearInCart($user_gear_desc_id, $app_user_id)
	{
		$this->db->select('cart.user_gear_rent_detail_id,cart.user_gear_desc_id,cart.user_gear_rent_id,
		cart.gear_rent_requested_on,cart.gear_rent_request_from_date,cart.gear_rent_request_to_date,
		cart.gear_total_rent_request_days,cart.total_rent_amount_ex_gst,cart.gst_amount,cart.other_charges,cart.total_rent_amount,a.gear_name,
						a.per_day_cost_aud_ex_gst,a.gear_slug_name,
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
		$this->db->join('ks_models as d', 'a.model_id = d.model_id','LEFT');
		$this->db->where('gear.app_user_id',$app_user_id );
		$this->db->where('status_master.is_active','N' );
		$this->db->where('cart.user_gear_desc_id',$user_gear_desc_id );
		$this->db->where('cart.order_id', '');
		
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
		$this->db->from('ks_user_ref_comment As refernce');
		$this->db->where('refernce.app_user_id',$app_user_id);
		$this->db->where('refernce.parent_gear_review_id','0');
		$this->db->where('refernce.is_approved', 'Y');
		$this->db->join('ks_users AS users'," users.app_user_id = refernce.create_user  " ,"INNER");
		$query = $this->db->get();
		$reference  =$query->result_array();
		return $reference ;
	}
	public function GetAllReview($app_user_id='')
	{
		$this->db->select('reviews.*,users.app_user_first_name,users.app_user_last_name,users.app_username,users.user_profile_picture_link');
		$this->db->from('ks_cust_gear_reviews As reviews');
		$this->db->where('reviews.app_user_id',$app_user_id);
		$this->db->where('reviews.parent_gear_review_id','0');
		$this->db->join('ks_user_gear_order_description AS gears'," gears.order_id = reviews.order_id " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = reviews.app_user_id " ,"INNER");
		$query = $this->db->get();
		$reviews  =$query->result_array();
		
		return $reviews ;
	}
	public function getMyorderList($app_user_id,$where,$limit='',$offset='',$order_by='')
	{
		$this->db->select('rent_details.*,gears.renter_app_bussiness_name AS bussiness_name,gears.renter_app_show_business_name AS show_business_name,gears.renter_app_user_first_name AS app_user_first_name,gears.renter_app_user_last_name AS app_user_last_name,gears.renter_app_username  AS app_username,users.user_profile_picture_link,gears.gear_name,gears.per_week_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,gears.per_week_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,rent_details.is_payment_completed,order_master.name As status_master_name ,order_payments.status AS payment_status ,gears.gear_slug_name' );
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payments'," order_payments.gear_order_id = rent_details.order_id  AND order_payments.payment_type  = 'Gear Payment'" ,"LEFT");
		$this->db->where('gears.app_user_id',$app_user_id);
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		if (!empty($where)) {
				$this->db->where($where);
		}
		$this->db->join('ks_user_gear_order_description AS gears'," gears.order_id = rent_details.order_id AND gears.user_gear_desc_id = rent_details.user_gear_desc_id " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.app_user_id " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('rent_details.gear_rent_requested_on', 'DESC');
		$this->db->group_by('rent_details.order_id');
		$query = $this->db->get();
		return $query ;
	}

	// public function getRequestedorderList($app_user_id,$where,$limit='',$offset='',$order_by='')
	// {
	// 	$this->db->select('rent_details.*,users.app_user_id,users.bussiness_name,users.show_business_name,users.app_user_first_name,users.app_user_last_name,users.app_username,users.user_profile_picture_link,gears.gear_name,order_master.name As status_master_name,order_payments.status AS payment_status' );
	// 	$this->db->from('ks_user_gear_rent_details As rent_details');
	// 	$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = rent_details.user_gear_rent_id' ,'INNER');
	// 	$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	// 	$this->db->join('ks_user_gear_description AS gears'," gears.user_gear_desc_id = rent_details.user_gear_desc_id " ,"INNER");
	// 	$this->db->join('ks_users AS b'," b.app_user_id = gears.create_user " ,"INNER");
	// 	$this->db->join('ks_users AS users'," users.app_user_id = gears.app_user_id " ,"INNER");
	// 	$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
	// 	$this->db->join('ks_user_gear_payments AS order_payments'," order_payments.gear_order_id = rent_details.order_id  AND order_payments.payment_type  = 'Gear Payment'" ,"LEFT");
	// 	$this->db->where('rent_details.create_user',$app_user_id);
	// 	$this->db->where("rent_details.is_payment_completed","Y");
	// 	$this->db->where("rent_details.order_id !=",''	);
	// 	if (!empty($where)) {
	// 			$this->db->where($where);
	// 	}
	// 	if (!empty($limit && $offset)) {
	// 		$this->db->limit($limit,$offset);
	// 	}
		
	// 	$this->db->group_by('rent_details.order_id');
	// 	$query = $this->db->get();
	// 	//echo $this->db->last_query();
	// 	return $query ;
	// }


	public function getRequestedorderList($app_user_id,$where,$limit='',$offset='',$order_by='')
	{
		$this->db->select('rent_details.*,users.app_user_id,gears.owner_app_bussiness_name AS bussiness_name,gears.owner_app_show_business_name	 AS show_business_name,gears.owner_app_user_first_name As app_user_first_name,gears.owner_app_user_last_name AS app_user_last_name,gears.owner_app_username AS app_username,users.user_profile_picture_link,gears.gear_name,order_master.name As status_master_name,order_payments.status AS payment_status' );
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = rent_details.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
		$this->db->join('ks_user_gear_order_description AS gears',"gears.user_gear_desc_id = rent_details.user_gear_desc_id AND  gears.order_id = rent_details.order_id " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.create_user " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = gears.app_user_id " ,"INNER");
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payments'," order_payments.gear_order_id = rent_details.order_id  AND order_payments.payment_type  = 'Gear Payment'" ,"LEFT");
		$this->db->where('rent_details.create_user',$app_user_id);
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		if (!empty($where)) {
				$this->db->where($where);
		}
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
		$this->db->order_by('rent_details.gear_rent_requested_on', 'DESC');
		$this->db->group_by('rent_details.order_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
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

	public function OwnerOrderSummary($order_id='')
	{
		$this->db->select('cart.*,a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						a.renter_app_user_first_name As app_user_first_name,c.app_user_id As renter_app_user_id,
						a.renter_app_user_last_name As app_user_last_name,c.primary_email_address,a.renter_is_registered_for_gst AS registered_for_gst,
						a.ks_category_id As gear_category_id,a.user_gear_desc_id,cart.user_gear_desc_id,cart.create_user ,a.app_user_id ,a.accessories');

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
		$this->db->join('ks_user_gear_order_description as a' , 'a.user_gear_desc_id = cart.user_gear_desc_id AND  a.order_id = cart.order_id' ,'INNER');
		 $this->db->join('ks_user_gear_order_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id AND  a.order_id = b.order_id', 'LEFT');
		$this->db->join('ks_users as c', 'cart.create_user = c.app_user_id');
		$this->db->join('ks_models as d', 'a.model_id = d.model_id', 'LEFT');
		$this->db->where('cart.order_id',$order_id );
		// $this->db->where('status_master.is_active','Y' );
		//	$this->db->where('cart.is_rent_approved','N' );
		$this->db->where('cart.is_payment_completed','Y' );
		
		$this->db->group_by('a.user_gear_desc_id');
		//	$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		$data = 	$query->result_array();
		
		return  $data ;
	}

	public function OwnerOrderSummary2($order_id='')
	{
		$this->db->select('cart.*,a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						a.renter_app_user_first_name As app_user_first_name,c.app_user_id As renter_app_user_id,
						a.renter_app_user_last_name As app_user_last_name,c.primary_email_address,a.renter_is_registered_for_gst As registered_for_gst,
						a.ks_category_id As gear_category_id,a.user_gear_desc_id,cart.user_gear_desc_id,cart.create_user ,a.app_user_id,a.replacement_value_aud_inc_gst,gear.user_address_id');

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
		$this->db->join('ks_user_gear_order_description as a' , 'a.user_gear_desc_id = cart.user_gear_desc_id AND a.order_id = cart.order_id' ,'INNER');
		 $this->db->join('ks_user_gear_order_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id AND a.order_id = b.order_id', 'LEFT');
		$this->db->join('ks_users as c', 'cart.create_user = c.app_user_id');
		// $this->db->join('ks_models as d', 'a.model_id = d.model_id', 'LEFT');
		$this->db->where('cart.order_id',$order_id );
		// $this->db->where('status_master.is_active','Y' );
		//	$this->db->where('cart.is_rent_approved','N' );
		$this->db->where('cart.is_payment_completed','Y' );
		
		$this->db->group_by('a.user_gear_desc_id');
		//	$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		$data = 	$query->result_array();
		
		return  $data ;
	}

	public function OwnerOrderSummary1($order_id='')
	{
		$this->db->select('cart.*,a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						a.owner_app_user_first_name AS app_user_first_name,c.app_user_id As renter_app_user_id,
						a.owner_app_user_last_name As app_user_last_name,c.primary_email_address,a.is_registered_for_gst AS registered_for_gst,
						a.ks_category_id As gear_category_id,a.user_gear_desc_id,cart.user_gear_desc_id,cart.create_user ,a.app_user_id,a.replacement_value_aud_inc_gst ,gear.user_address_id');

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
		$this->db->join('ks_user_gear_order_description as a' , 'a.user_gear_desc_id = cart.user_gear_desc_id AND a.order_id = cart.order_id' ,'INNER');
		 $this->db->join('ks_user_gear_order_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id AND a.order_id = b.order_id', 'LEFT');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		// $this->db->join('ks_models as d', 'a.model_id = d.model_id', 'LEFT');
		$this->db->where('cart.order_id',$order_id );
		// $this->db->where('status_master.is_active','Y' );
		//	$this->db->where('cart.is_rent_approved','N' );
		$this->db->where('cart.is_payment_completed','Y' );
		
		$this->db->group_by('a.user_gear_desc_id');
		//	$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		$data = 	$query->result_array();
		
		return  $data ;
	}

	public function getUserCartbyOrderId($order_id)
	{
		
		$this->db->select('cart.owner_insurance_amount,cart.order_id,cart.create_user,cart.user_gear_rent_detail_id,
						cart.user_gear_desc_id,cart.user_gear_rent_id,cart.security_deposit,
						cart.gear_rent_requested_on,cart.gear_rent_request_from_date,cart.gear_rent_request_to_date,
						cart.gear_total_rent_request_days,cart.total_rent_amount_ex_gst,cart.gst_amount,cart.other_charges,cart.total_rent_amount,
						cart.user_gear_desc_id ,cart.braintree_token,cart.security_deposite_token_braintree,cart.beta_discount,
						cart.insurance_fee,cart.community_fee,cart.project_name,cart.is_rent_approved,
						a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						a.replacement_value_aud_inc_gst,
						gear.user_address_id,
						a.owner_app_user_first_name As app_user_first_name,
						c.app_user_id,
						a.owner_app_user_last_name As app_user_last_name,
						c.primary_email_address,a.is_registered_for_gst  AS registered_for_gst,
						c.user_profile_picture_link,a.owner_app_show_business_name  AS owner_show_business_name,
						a.owner_app_bussiness_name As  owner_bussiness_name,  
						a.renter_app_user_first_name As renter_firstname,renter_users.app_user_id As renter_app_user_id,
						a.renter_app_user_last_name As renter_lastname,renter_users.primary_email_address As renter_email,a.renter_app_show_business_name As renter_show_business_name ,a.renter_app_bussiness_name As renter_bussiness_name  ,renter_users.user_profile_picture_link as renter_image,
						a.ks_category_id As gear_category_id,a.user_gear_desc_id');

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
		$this->db->join('ks_user_gear_order_description as a' , 'a.user_gear_desc_id = cart.user_gear_desc_id AND a.order_id = cart.order_id' ,'INNER');
		 $this->db->join('ks_user_gear_order_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id AND a.order_id = b.order_id', 'LEFT');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_users as renter_users', 'cart.create_user = renter_users.app_user_id');
		
		// $this->db->join('ks_models as d', 'a.model_id = d.model_id', 'LEFT');
		$this->db->where('cart.order_id',$order_id );
		//$this->db->where('status_master.is_active','N' );
		//$this->db->where('cart.is_rent_approved','N' );
	//	$this->db->where('cart.is_payment_completed','Y' );
		
		$this->db->group_by('a.user_gear_desc_id');
	//	$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		$data = 	$query->result_array();
		
		return  $data;
	}

	public function GearLocation($ks_user_gear_id='')
	{
		$this->db->select('u_a.*,ks_states.ks_state_name ,ks_suburbs.suburb_name,a.per_day_cost_aud_inc_gst,
							   a.per_day_cost_aud_ex_gst');
			$this->db->from('ks_gear_location As g_l');
			$this->db->where('g_l.user_gear_desc_id',$ks_user_gear_id);
			$this->db->join('ks_user_address AS u_a  '," u_a.user_address_id = g_l.user_address_id " ,"INNER");
			$this->db->join('ks_suburbs'," ks_suburbs.ks_suburb_id = u_a.ks_suburb_id " ,"INNER");
			$this->db->join('ks_states'," ks_states.ks_state_id = u_a.ks_state_id " ,"INNER");
			$this->db->join('ks_user_gear_description AS a '," g_l.user_gear_desc_id = a.user_gear_desc_id " ,"INNER");
			$query = $this->db->get();
			$address  =$query->result_array();	
			return $address ;
	}
	public function zipcodeRadius($lat, $lon, $radius){	
		$sql = 'SELECT distinct(postcode) FROM `ks_user_address`  WHERE (3958*3.1415926*sqrt((lat-'.$lat.')*(lat-'.$lat.') + cos(lat/57.29578)*cos('.$lat.'/57.29578)*(lng-'.$lon.')*(lng-'.$lon.'))/180) <= '.$radius.';';
		$query = $this->db->query($sql);
		// get each result
		$zipcodeList = array();
		
		foreach ($query->result() as $row)
		{
			array_push($zipcodeList, "'".$row->postcode."'");
		}
		return $zipcodeList;
	
	}

	public function zipcodeRadiusLatLon($lngWest,$lngEast,$latSouth,$latNorth){	
	
		
		$sql = "SELECT distinct(postcode) FROM `ks_user_address`  WHERE `lat` <= '".$latNorth."' AND `lat` >= '".$latSouth."' AND ";
		
		if($lngWest>0 && $lngEast>0)
			$sql .= "`lng` >= '".$lngWest."' AND `lng` <= '".$lngEast."'";
		else if($lngWest<0 && $lngEast<0)
			$sql .= "`lng` <= '".$lngWest."' AND `lng` >= '".$lngEast."'";
		else if($lngWest>0 && $lngEast<0)
			$sql .= "(`lng` >= '".$lngWest."' AND `lng`<='180') OR (`lng`<='-180' AND `lng` >= '".$lngEast."')";
		
		
		$query = $this->db->query($sql);
		// get each result
		$zipcodeList = array();
		
		foreach ($query->result() as $row)
		{
			array_push($zipcodeList, "'".$row->postcode."'");
		}
		return $zipcodeList;
	
	}
	public function homeUserDetails()
	{
		$this->db->select("app_user_id , app_username,app_user_first_name,app_user_last_name,user_profile_picture_link,show_business_name,bussiness_name,user_profile_picture_link") ; 
		$this->db->from('ks_users');
		$this->db->order_by('rand()');
		$this->db->where('is_active','Y');
		$this->db->where('user_profile_picture_link !=','');
		 $this->db->limit(4);
		$query = $this->db->get();
		$features_details  =  $query->result();

		if(count($features_details) < 4){
			$limit_value =  4  -  count($features_details) ;
			$this->db->select("*") ; 
			$this->db->from('ks_users');
			$this->db->order_by('rand()');
			$this->db->where('is_active','Y');
			$this->db->where('user_profile_picture_link ','');
			$this->db->limit($limit_value);
			$query = $this->db->get();
			$user_details =  $query->result();
			$daata =  array_merge($features_details,$user_details);
			$features_details = $daata;
		}	
		$i = 0 ;
		foreach ($features_details as  $value) {
			
			$rating =$this->GetUSerRatings($value->app_user_id);
			$features_details[$i]->rating =number_format((float)( $rating->rating), 0, '.', '');;
			$review_result= $this->ProductReview($value->app_user_id); ////// get user's all product review array			
					$reviews=$review_result->result_array();
					if (!empty($reviews)) {
						$features_details[$i]->review =count($reviews);
					}else{
						$features_details[$i]->review ='0';
					}
				$features_details[$i]->response_rate ='100';	
			$i++;
		}
		
		return $features_details ;
	}
	public function GetUSerRatings($app_user_id){
   		
			$sql= "SELECT AVG(star_rating) As rating FROM ks_cust_gear_reviews WHERE  app_user_id = '".$app_user_id."'";
					$query = $this->db->query($sql);
					$rating = $query->row();
				
		return $rating;
		
   	}
	public function PageDetails($page_code='')
	{
		$this->db->select("cms_page.* ,ks_p_c.content,ks_p_c.page_content_id") ; 
		$this->db->from('ks_cms_pages As cms_page');
		$this->db->join('ks_page_content As ks_p_c','cms_page.cms_page_id  =  ks_p_c.cms_page_id ','INNER');
		$this->db->where('cms_page.page_code',$page_code);
		
		$query = $this->db->get();
		$page_details  =  $query->row();
		return $page_details ;
	}
	public function GetFAQ($table_name,$where_clause,$search_string='')
	{
		if($where_clause != '')
			$this->db->where($where_clause);
		if ($search_string !='') {
			$this->db->like('category_name',$search_string, 'both');
			$this->db->or_like('title',$search_string, 'both');
		}
		
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->order_by('order_by','ASC');
		$query = $this->db->get();  
		return $query;
	}
	public function GetFaqList($table_name,$where_clause)
	{
		if($where_clause != '')
			$this->db->where($where_clause);
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->order_by('order_by','ASC');

		$query = $this->db->get();  
		return $query;
	}

	public function OwnerRemindersExpiry($where)
	{
		$this->db->select('cart.*,cart.create_user,cart.user_gear_rent_detail_id,cart.user_gear_desc_id,cart.user_gear_rent_id,cart.security_deposit,
		cart.gear_rent_requested_on,cart.gear_rent_request_from_date,cart.gear_rent_request_to_date,
		cart.gear_total_rent_request_days,cart.total_rent_amount_ex_gst,cart.gst_amount,cart.other_charges,cart.total_rent_amount,a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,a.replacement_value_aud_inc_gst,gear.user_address_id,
						a.owner_app_user_first_name As app_user_first_name,c.app_user_id,
						a.owner_app_user_last_name As app_user_last_name,c.primary_email_address,a.registered_for_gst,c.user_profile_picture_link,
						renter_users.app_user_first_name As renter_firstname,renter_users.app_user_id As renter_app_user_id,
						a.renter_app_user_last_name As renter_lastname,renter_users.primary_email_address As renter_email,,renter_users.user_profile_picture_link as renter_image,
						a.ks_category_id AS gear_category_id,a.user_gear_desc_id,cart.user_gear_desc_id ,cart.braintree_token,cart.security_deposite_token_braintree,cart.beta_discount,cart.insurance_fee,cart.community_fee');

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
		$this->db->join('ks_user_gear_order_description as a' , 'a.user_gear_desc_id = cart.user_gear_desc_id AND  a.order_id = cart.order_id' ,'INNER');
		 $this->db->join('ks_user_gear_order_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id AND a.order_id = b.order_id', 'LEFT');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_users as renter_users', 'cart.create_user = renter_users.app_user_id');
		
		$this->db->where($where);
		//$this->db->where('cart.is_rent_approved','N' );
	//	$this->db->where('cart.is_payment_completed','Y' );
		
		$this->db->group_by('cart.order_id');
	//	$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		$data = 	$query->result_array();
		
		return  $data ;
	}

	public function OwnerReviewList($where)
	{
		$today_date = date('Y-m-d');
		$next_day = date("Y-m-d", strtotime("-14 days", strtotime(date('Y-m-d'))));
		$this->db->select('a.* ,orders.project_name, orders.order_id, a.app_user_id AS app_user_id_given_by , users.app_user_id ,users.app_user_first_name AS app_user_first_name_given_by, users.app_user_last_name AS app_user_last_name_given_by ,users.user_profile_picture_link As user_profile_picture_link_given_by,users.bussiness_name ,users.show_business_name ');
		$this->db->from('ks_cust_gear_reviews AS a');
		$this->db->join('ks_users as users' , 'a.create_user = users.app_user_id' ,'INNER');
		$this->db->join('ks_user_gear_rent_details as orders' , ' orders.order_id = a.order_id' ,'INNER');
		$this->db->join('ks_user_gear_order_description as gears' , 'gears.user_gear_desc_id = orders.user_gear_desc_id AND   a.app_user_id = orders.create_user  AND  orders.order_id = gears.order_id' ,'INNER');
		$this->db->group_by('a.order_id');
		$this->db->order_by('a.ks_cust_gear_review_id','DESC');
		$this->db->where($where);
		$this->db->where('orders.gear_rent_requested_on > ',$next_day);
		$query = $this->db->get();
		return  $query ;
	}


	public function RenterReviewList($where)
	{
		$today_date = date('Y-m-d');
		$next_day = date("Y-m-d", strtotime("-14 days", strtotime(date('Y-m-d'))));
		$this->db->select('a.* ,orders.project_name, orders.order_id, users.app_user_first_name AS app_user_first_name_given_by, users.app_user_last_name AS app_user_last_name_given_by ,users.user_profile_picture_link As user_profile_picture_link_given_by,,users.bussiness_name ,users.show_business_name ');
		$this->db->from('ks_cust_gear_reviews AS a');
		$this->db->join('ks_users as users' , 'a.create_user = users.app_user_id' ,'INNER');
		$this->db->join('ks_user_gear_rent_details as orders' , 'orders.order_id = a.order_id' ,'INNER');
		$this->db->join('ks_user_gear_order_description as gears' , 'a.app_user_id =gears.app_user_id   AND     orders.order_id = gears.order_id' ,'INNER');
		$this->db->where($where);
		$this->db->where('orders.gear_rent_requested_on > ',$next_day);
		$this->db->group_by('a.order_id');
		$this->db->order_by('a.ks_cust_gear_review_id','DESC');
		$query = $this->db->get();
		return  $query ;
	}

	public function getOwnerReviewOrderList($app_user_id,$where,$limit='',$offset='',$order_by='')
	{
		$today_date = date('Y-m-d');
		$next_day = date("Y-m-d", strtotime("-14 days", strtotime(date('Y-m-d'))));
		$this->db->select('rent_details.*,users.user_profile_picture_link As user_profile_picture_link_given_by,ks_cust_gear_review_id,users.bussiness_name,users.show_business_name,users.app_user_first_name,users.app_user_last_name,users.app_username,users.user_profile_picture_link,gears.gear_name,gears.per_week_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,gears.per_week_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,rent_details.is_payment_completed,order_master.name As status_master_name ,order_payments.status AS payment_status ,gears.gear_slug_name' );
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payments'," order_payments.gear_order_id = rent_details.order_id  AND order_payments.payment_type  = 'Gear Payment'" ,"LEFT");
		$this->db->where('gears.app_user_id = "'.$app_user_id.'"' );
		// $this->db->where('( rent_details.create_user ="'.$app_user_id.'")' );
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		if (!empty($where)) {
				$this->db->where($where);
		}
		$this->db->where('rent_details.gear_rent_requested_on > ',$next_day);
		$this->db->join('ks_user_gear_order_description AS gears',"gears.user_gear_desc_id = rent_details.user_gear_desc_id AND gears.order_id = rent_details.order_id " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.app_user_id " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		$this->db->join('ks_cust_gear_reviews AS reivews'," reivews.order_id != rent_details.order_id " ,"LEFt");
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
		
		$this->db->group_by('rent_details.order_id');
		$query = $this->db->get();
		return $query ;
	}
	public function getOwnerReviewOrderListrenter($app_user_id,$where,$limit='',$offset='',$order_by='')
	{
		$today_date = date('Y-m-d');
		$next_day = date("Y-m-d", strtotime("-14 days", strtotime(date('Y-m-d'))));
		$this->db->select('rent_details.*,users.user_profile_picture_link As user_profile_picture_link_given_by,ks_cust_gear_review_id,users.app_user_id ,users.app_user_id As create_user,users.bussiness_name,users.show_business_name,users.app_user_first_name,users.app_user_last_name,users.app_username,users.user_profile_picture_link,gears.gear_name,gears.per_week_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,gears.per_week_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,rent_details.is_payment_completed,order_master.name As status_master_name ,order_payments.status AS payment_status ,gears.gear_slug_name' );
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payments'," order_payments.gear_order_id = rent_details.order_id  AND order_payments.payment_type  = 'Gear Payment'" ,"LEFT");
		// $this->db->where('gears.app_user_id = "'.$app_user_id.'"' );
		$this->db->where('( rent_details.create_user ="'.$app_user_id.'")' );
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		if (!empty($where)) {
				$this->db->where($where);
		}
		$this->db->where('rent_details.gear_rent_requested_on > ',$next_day);
		$this->db->join('ks_user_gear_order_description AS gears',"gears.user_gear_desc_id = rent_details.user_gear_desc_id AND gears.order_id = rent_details.order_id " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id =  rent_details.create_user " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = gears.app_user_id " ,"INNER");
		$this->db->join('ks_cust_gear_reviews AS reivews'," reivews.order_id != rent_details.order_id " ,"LEFt");
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
		
		$this->db->group_by('rent_details.order_id');
		$query = $this->db->get();
		return $query ;
	}
	public function getOwnerReviewOrderList1($app_user_id,$where,$limit='',$offset='',$order_by='')
	{
		$today_date = date('Y-m-d');
		$next_day = date("Y-m-d", strtotime("-14 days", strtotime(date('Y-m-d'))));
		$this->db->select('rent_details.*,users.app_user_id,users.user_profile_picture_link As user_profile_picture_link_given_by,ks_cust_gear_review_id,users.bussiness_name,users.show_business_name,users.app_user_first_name,users.app_user_last_name,users.app_username,users.user_profile_picture_link,gears.gear_name,gears.per_week_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,gears.per_week_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,rent_details.is_payment_completed,order_master.name As status_master_name ,order_payments.status AS payment_status ,gears.gear_slug_name' );
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payments'," order_payments.gear_order_id = rent_details.order_id  AND order_payments.payment_type  = 'Gear Payment'" ,"LEFT");
		$this->db->where('rent_details.create_user = "'.$app_user_id.'"' );
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_status","4");
		$this->db->where("rent_details.order_id !=",''	);
		if (!empty($where)) {
				$this->db->where($where);
		}
		$this->db->where('rent_details.gear_rent_requested_on > ',$next_day);
		$this->db->join('ks_user_gear_order_description AS gears',"gears.user_gear_desc_id = rent_details.user_gear_desc_id AND gears.order_id = rent_details.order_id " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.app_user_id " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		$this->db->join('ks_cust_gear_reviews AS reivews'," reivews.order_id != rent_details.order_id " ,"LEFT");
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
		
		$this->db->group_by('rent_details.order_id');
		$query = $this->db->get();
		return $query ;
	}
	public function getRenterReviewOrderList($app_user_id,$where,$limit='',$offset='',$order_by='')
	{
		$today_date = date('Y-m-d');
		$next_day = date("Y-m-d", strtotime("-20 days", strtotime(date('Y-m-d'))));
	
		$this->db->select('rent_details.*,b.app_user_id,b.bussiness_name,b.show_business_name,b.app_user_first_name,b.app_user_last_name,b.app_username,b.user_profile_picture_link,gears.gear_name,gears.per_week_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,gears.per_week_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,rent_details.is_payment_completed,order_master.name As status_master_name ,order_payments.status AS payment_status ,gears.gear_slug_name' );
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payments'," order_payments.gear_order_id = rent_details.order_id  AND order_payments.payment_type  = 'Gear Payment'" ,"LEFT");
		$this->db->join('ks_user_gear_order_description AS gears',"gears.user_gear_desc_id = rent_details.user_gear_desc_id AND  gears.order_id = rent_details.order_id " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.app_user_id " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		$this->db->join('ks_cust_gear_reviews AS reivews'," reivews.order_id != rent_details.order_id " ,"LEFT");
		$this->db->where('rent_details.create_user',$app_user_id);
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		$this->db->where('rent_details.gear_rent_requested_on > ',$next_day);
		if (!empty($where)) {
				$this->db->where($where);
		}
		
		
		$this->db->limit($limit,$offset);
		$this->db->group_by('rent_details.order_id');
		$query = $this->db->get();
		return $query ;
	}

	public function GetAllunavailableDates($where)
	{
		$this->db->select('k_g_u.*,gears.gear_name' );
		$this->db->from('ks_gear_unavailable As k_g_u');
		$this->db->join('ks_user_gear_description AS gears'," gears.user_gear_desc_id = k_g_u.user_gear_description_id " ,"LEFT");
		$this->db->where($where);
		$query = $this->db->get();
		return $query ;
	}
	
	public function CheckOwnerInsurancePolicyCalulation($app_user_id, $gear_id)
	{
		$current_gear_id = $gear_id ;
		//Owner Insurance Details
		$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);

		$where = array( 'app_user_id'=>$Cart_details[0]['app_user_id'] ,
						 					'is_visiible'=>'Y' ,
						 					'owner_insurance_provided'=>'Yes',
						 					'owner_insurance_status' =>'1' ,
						 					'ks_user_certificate_currency_start <= ' => date('Y-m-d') , 
						 					'ks_user_certificate_currency_exp >= ' => date('Y-m-d')  
						 		);
		$query1 =  $this->common_model->GetAllWhere('ks_user_insurance_proof' ,$where);
	 	$insurance_detail = $query1->row();

	 	$owner_insurance_amount_total_allowed = 0 ;
	 	if (!empty($insurance_detail)) {
	 			$owner_insurance_amount_total_allowed = $insurance_detail->owner_insurance_amount ;
	 	}
	 	// echo $Cart_details[0]['app_user_id'] ;
	 	// echo "<br>";
	 	// Today Owner 	Insurance  Details 
	 	//$sql =  "SELECT  k_g_d.replacement_value_aud_ex_gst FROM ks_user_gear_rent_details  AS k_r_d INNER JOIN ks_user_gear_description As k_g_d ON  k_g_d.user_gear_desc_id =  k_r_d.user_gear_desc_id WHERE  k_g_d.app_user_id= '".$Cart_details[0]['app_user_id']."' AND k_r_d.order_id != ''  AND k_r_d.ks_insurance_category_type_id = '8'   AND date(k_r_d.gear_rent_request_from_date) = '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_from_date']))."'  " ; 	
    	 
 		$sql =  "SELECT   k_g_d.replacement_value_aud_ex_gst FROM ks_user_gear_rent_details  AS k_r_d INNER JOIN ks_user_gear_description As k_g_d ON  k_g_d.user_gear_desc_id =  k_r_d.user_gear_desc_id WHERE  k_g_d.app_user_id = '".$Cart_details[0]['app_user_id']."' AND k_r_d.order_id != ''  AND k_r_d.ks_insurance_category_type_id = '8' AND ((date(k_r_d.gear_rent_request_from_date) <= '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_from_date']))."' AND date(k_r_d.gear_rent_request_to_date) >= '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_to_date']))."') OR 
(date(k_r_d.gear_rent_request_from_date) BETWEEN '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_from_date']))."' AND '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_to_date']))."') OR (date(k_r_d.gear_rent_request_to_date) BETWEEN '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_from_date']))."' AND '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_to_date']))."') OR
 (date(k_r_d.gear_rent_request_from_date) <= '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_from_date']))."' AND date(k_r_d.gear_rent_request_to_date) >= '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_to_date']))."')) " ; 
    	
    	$today_details = $this->common_model->get_records_from_sql($sql) ;		
	 
    	$sum_repalcement_value_today = 0 ;
	 	if (!empty($today_details)) {
	 		foreach ($today_details as  $value) {
	 			$sum_repalcement_value_today += $value->replacement_value_aud_ex_gst;
	 		}
	 	}
	 	  $today_left_insurance_amount = $owner_insurance_amount_total_allowed -  $sum_repalcement_value_today ; 
	 	// Cart Owner Insurance Value 
		$sum_repalcement_value = 0 ;
		foreach ($Cart_details as $value) {
			if ($value['user_gear_desc_id'] == $current_gear_id) {
						$sum_repalcement_value += $value['replacement_value_aud_ex_gst']	 ;	
			}else{
				if ($value['ks_insurance_category_type_id'] == '8') {
						$sum_repalcement_value += $value['replacement_value_aud_ex_gst']	 ;	
				}
			}
		}
		// echo "<br>";
		 //echo $sum_repalcement_value;
		// echo "<br>";
		// echo $today_left_insurance_amount ;
		// die;
		if ($today_left_insurance_amount > $sum_repalcement_value) {
			return true;
		}else{
			return false;
		}
		
	}

	//Check Renter Insurance calculation 
	public function CheckRenterInsurancePolicyCalculation($app_user_id,$gear_id)
	{
		$current_gear_id = $gear_id ;
		//Owner Insurance Details
		$Cart_details = 	 $this->home_model->getUserCart1($app_user_id);
		$where = array( 'app_user_id'=>$app_user_id ,
						 					'is_visiible'=>'Y' ,
						 					'renter_insurance_provided'=>'Yes',
						 					'renter_insurance_status' =>'1' ,
						 					'ks_user_certificate_currency_start <= ' => date('Y-m-d') , 
						 					'ks_user_certificate_currency_exp >= ' => date('Y-m-d')  
						 		);
		$query1 =  $this->common_model->GetAllWhere('ks_user_insurance_proof' ,$where);
	 	$insurance_detail = $query1->row();
	 	$renter_insurance_amount_total_allowed = 0 ;
	 	if (!empty($insurance_detail)) {
	 			$renter_insurance_amount_total_allowed = $insurance_detail->renter_insurance_amount ;
	 	}
	 	// Today total purchase buy renter 
	 	//$sql =  "SELECT  replacement_value_aud_ex_gst FROM ks_user_gear_rent_details  AS k_r_d INNER JOIN ks_user_gear_description As k_g_d ON  k_g_d.user_gear_desc_id =  k_r_d.user_gear_desc_id WHERE  k_r_d.create_user = '".$app_user_id."' AND k_r_d.order_id != ''  AND k_r_d.ks_insurance_category_type_id = '3'   AND date(k_r_d.gear_rent_request_from_date) = '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_from_date']))."'  " ; 	
	 	
 		
 		  $sql =  "SELECT  replacement_value_aud_ex_gst FROM ks_user_gear_rent_details  AS k_r_d INNER JOIN ks_user_gear_description As k_g_d ON  k_g_d.user_gear_desc_id =  k_r_d.user_gear_desc_id WHERE  k_r_d.create_user = '".$app_user_id."' AND k_r_d.order_id != ''  AND k_r_d.ks_insurance_category_type_id = '3' AND ((date(k_r_d.gear_rent_request_from_date) <= '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_from_date']))."' AND date(k_r_d.gear_rent_request_to_date) >= '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_to_date']))."') OR 
(date(k_r_d.gear_rent_request_from_date) BETWEEN '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_from_date']))."' AND '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_to_date']))."') OR (date(k_r_d.gear_rent_request_to_date) BETWEEN '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_from_date']))."' AND '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_to_date']))."')
 OR (date(k_r_d.gear_rent_request_from_date) <= '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_from_date']))."' AND date(k_r_d.gear_rent_request_to_date) >= '".date('Y-m-d',strtotime($Cart_details[0]['gear_rent_request_to_date']))."')) " ; 
    	
    	$today_details = $this->common_model->get_records_from_sql($sql) ;		
	 	
	 	$total_rent_value_today = 0;
	 	if (!empty($today_details)) {
	 		foreach ($today_details as  $value) {
	 			$total_rent_value_today += $value->replacement_value_aud_ex_gst;
	 		}
	 	}
	 	$today_left_insurance_amount = $renter_insurance_amount_total_allowed -  $total_rent_value_today ; 
	 	$cart_total_value = 0 ;
		foreach ($Cart_details as $value) {
			if ($value['user_gear_desc_id'] == $current_gear_id) {
						$cart_total_value += $value['replacement_value_aud_ex_gst']	 ;	
			}else{
				if ($value['ks_insurance_category_type_id'] == '3') {
						$cart_total_value += $value['replacement_value_aud_ex_gst']	 ;	
				}
			}
		}
		
		if ($today_left_insurance_amount > $cart_total_value) {
			// echo true;
			return true;
		}else{
			// echo false;
			return false;
		}

	}
	
	
	//Profile Unavailable Dates
	public function fetchProfileUnvailableDates($app_user_id){
		
		$this->db->select('ks_gear_unavailable_id,unavailable_from_date,unavailable_to_date');
		$this->db->from('ks_gear_unavailable AS a');
		$this->db->where(array("create_user"=>$app_user_id));
		$this->db->order_by('ks_gear_unavailable_id','DESC');
		$this->db->limit(1,0);
		$query = $this->db->get();
		return  $query ;
		
	}
	
	
	///Modified Search Query
	public function getSearchList($search_txt,$where_array,$limit=0,$offset=0,$order_by_fld='',$order_by=''){
		
		//Different variables are defined here
		$sql_match = "";
		$order_by_match = "";
		$sql_where_match = "";
		$search_text_arr = array();
		$model_search = "";
		$manufacturer_search = "";
		
		$sql = "SELECT ";
	
		/*if($search_txt!=""){

			
			$i=0;
			foreach($search_text_arr as $keyword){
				
				$sql_match.="MATCH(a.`gear_name`) AGAINST('".$keyword."') AS `relevance".$i."`,";
				$sql_where_match.="MATCH(a.`gear_name`) AGAINST('".$keyword."') OR MATCH(e.`model_name`) AGAINST('".$keyword."') OR MATCH(d.`manufacturer_name`) AGAINST('".$keyword."') OR MATCH(f.`gear_category_name`) AGAINST('".$keyword."') OR ";
				$order_by_match.= "`relevance".$i."` DESC, ";
				
				//column names
				$model_search.= "MATCH(e.`model_name`) AGAINST('".$keyword."') OR ";
				$manufacturer_search.= "MATCH(d.`manufacturer_name`) AGAINST('".$keyword."') OR ";
				
				$i++;
				
			}
			
			
			
			if($sql_match!="")
				$sql_match = substr($sql_match,0,-1);
			
			if($order_by_match!="")
				$order_by_match = substr($order_by_match,0,-2);
			
			if($sql_where_match!="")
				$sql_where_match = substr($sql_where_match,0,-3);
			
			$model_search = "(".substr($model_search,0,-3).") AND";
			$manufacturer_search = "(".substr($manufacturer_search,0,-3).") AND";
			
			$sql.=$sql_match.",";
		}*/
		
		if($search_txt!=""){
			$sql.="a.user_gear_desc_id,a.gear_slug_name,
					   a.ks_gear_type_id,
					   a.gear_name,
					   a.manufacturer_name,
					   a.model_name,
					   a.model_id,
					   a.ks_category_id,
					   a.ks_manufacturers_id,
					   a.app_user_id,
					   a.per_day_cost_aud_inc_gst,
					   a.per_day_cost_aud_ex_gst,
					   b.app_user_first_name,
					   b.app_user_last_name,b.app_username,
					   b.user_profile_picture_link,
					   b.owner_type_id,b.bussiness_name,b.show_business_name,
					   c.gear_display_image,
					   c.gear_display_seq_id,
					   a.gear_category_name,
					   a.gear_subcategory_name,
					   MATCH(gear_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance,
					   MATCH(model_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance1,
					   MATCH(manufacturer_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance2,
					   MATCH(gear_description_1) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance4,
					   MATCH(gear_description_2) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance5,	
					   MATCH(gear_category_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance6,
					   MATCH(gear_subcategory_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance7,
					   MATCH(gear_name,model_name,manufacturer_name,gear_description_1,gear_description_2,gear_category_name,gear_subcategory_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AS relevance3
					   FROM `ks_user_gear_search` a
					   INNER JOIN `ks_users` b ON a.`app_user_id`=b.`app_user_id`";
					   
		}else{
		
			$sql.= "a.user_gear_desc_id,a.gear_slug_name,
					   a.ks_gear_type_id,
					   a.gear_name,
					   a.manufacturer_name,
					   a.model_name,
					   a.model_id,
					   a.ks_category_id,
					   a.ks_manufacturers_id,
					   a.app_user_id,
					   a.per_day_cost_aud_inc_gst,
					   a.per_day_cost_aud_ex_gst,
					   b.app_user_first_name,
					   b.app_user_last_name,b.app_username,
					   b.user_profile_picture_link,
					   b.owner_type_id,b.bussiness_name,b.show_business_name,
					   c.gear_display_image,
					   c.gear_display_seq_id ,
					   f.gear_category_name
					   FROM `ks_user_gear_search` a
					   INNER JOIN `ks_users` b ON a.`app_user_id`=b.`app_user_id`
					   INNER JOIN `ks_gear_categories` f ON a.`ks_category_id`=f.`gear_category_id`";
		}
					   
		if ($this->input->get('address') || $this->input->get('suburb_name') || (!empty($this->input->get('lngWest')) && !empty($this->input->get('lngEast')) && !empty($this->input->get('latSouth')) && !empty($this->input->get('latNorth')))) {
			
			$sql.=" INNER JOIN `ks_gear_location` AS g_loc ON a.user_gear_desc_id = g_loc.user_gear_desc_id";
			$sql.=" INNER JOIN `ks_user_address` AS u_add ON g_loc.user_address_id = u_add.user_address_id";
			$sql.=" INNER JOIN `ks_suburbs` ON ks_suburbs.ks_suburb_id = u_add.ks_suburb_id";
			$sql.=" INNER JOIN `ks_states` ON ks_states.ks_state_id = u_add.ks_state_id ";
			
			
		}
		if ($this->input->get('feature_master_id')) {
		
			$sql.="INNER JOIN `ks_user_gear_features` g_feat ON a.`user_gear_desc_id` = g_feat.`user_gear_desc_id` ";
		}else{

			$sql.="LEFT JOIN `ks_user_gear_features` g_feat ON a.`user_gear_desc_id` = g_feat.`user_gear_desc_id` ";
		}
				   
		$sql.="LEFT JOIN `ks_user_gear_images` c ON a.`user_gear_desc_id`=c.`user_gear_desc_id` AND c.`is_deleted` = '0'";
			   //LEFT JOIN `ks_gear_unavailable` ON a.user_gear_desc_id = ks_gear_unavailable.user_gear_description_id";
			   
		$sql.=" LEFT JOIN (SELECT ks_gear_unavailable.user_gear_description_id  FROM `ks_gear_unavailable` group by ks_gear_unavailable.user_gear_description_id) `ks_gear_unavailable` ON a.user_gear_desc_id = ks_gear_unavailable.user_gear_description_id";		   
	   
		
		$sql.=" WHERE ";
		if(empty($search_txt)==false)
			$sql.="MATCH(gear_name,model_name,manufacturer_name,gear_description_1,gear_description_2,gear_category_name,gear_subcategory_name) AGAINST ('".htmlspecialchars($search_txt,ENT_QUOTES)."') AND ";
		
		$sql.="a.`is_deleted`='No'";
		
		if($where_array!="")
			$sql.= " AND ".$where_array;
		
		$sql.=" GROUP BY a.`user_gear_desc_id`";
		
		$sql.=" ORDER BY ";
		
		if(empty($search_txt)==true && $order_by_fld =='')
			$sql.="rand()";
		else if(empty($search_txt)==false && $order_by_fld =='')
			$sql.= "relevance+relevance1+relevance2+relevance6+relevance7+relevance3+relevance4+relevance5 DESC";
		else
			$sql.="a.".$order_by_fld." ".$order_by;
		
		if (!empty($limit)) {
			$sql.=" LIMIT ".$offset.",".$limit;
		}
		
		$result = $this->common_model->get_records_from_sql($sql);
	
		$gears = json_decode(json_encode($result), true);
	
		$gears_listings=array();
		
		//Gear images are assigned with full path
		foreach($gears as $gear_lists){
			  $gear_lists['per_day_cost_aud_inc_gst']= number_format((float)( $gear_lists['per_day_cost_aud_inc_gst']), 2, '.', '');
			  $gear_lists['per_day_cost_aud_ex_gst']= number_format((float)( $gear_lists['per_day_cost_aud_ex_gst']), 2, '.', '');
			foreach($gear_lists as $key=>$value){			

			  if($key=="user_gear_desc_id"){
			  		$rating=$this->GetGearRatings($gear_lists['app_user_id'],'');
					if(count($rating)>0){
						$gear_lists['rating']= number_format((float)( $rating[0]['rating']), 0, '.', '');
						$gear_lists['rating1']=  $rating[0]['rating'];
					}else{
						$gear_lists['rating']=0;
					}	
			  }	
			  	  
			}
			 	if ($gear_lists['gear_display_image']=='') {
					 $gear_lists['gear_display_image']=GEAR_IMAGE."default_product.jpg";
				}else{

					 if (file_exists(BASE_IMG.'site_upload/gear_images/'.$gear_lists['gear_display_image'])) {
					  			# code...
					  			
					 	$gear_lists['gear_display_image']=GEAR_IMAGE.$gear_lists['gear_display_image'];
					 }else{
					 	$gear_lists['gear_display_image']=GEAR_IMAGE."default_product.jpg";
					 }	
				}
				if ($gear_lists['user_profile_picture_link'] =='') {
					$gear_lists['user_profile_picture_link']=BASE_URL."site_upload/profile_img/profile-default-pic.png";
				}else{

				}
				if ($gear_lists['show_business_name'] == 'Y') {
					 $gear_lists['app_user_first_name'] = $gear_lists['bussiness_name'];
					 $gear_lists['app_user_last_name'] = '';
				}else{

				}
			array_push($gears_listings,$gear_lists);
		}	
		return $gears_listings;		
	
	}
	
	
	public function fetchRentalYears($app_user_id){
		
		$sql = "SELECT DISTINCT(YEAR(`gear_rent_requested_on`)) AS year FROM `ks_user_gear_rent_details` WHERE `create_user`='".$app_user_id."'";
		$result = $this->common_model->get_records_from_sql($sql);
		
		$rental_years = json_decode(json_encode($result), true);
		return $rental_years;
		
	}
	
	public function checkGearAvailability($user_gear_desc_id,$from_date,$to_date){
		
		$this->db->select('user_gear_description_id'); 
		$this->db->from('ks_gear_unavailable');
		$this->db->where('user_gear_description_id',$user_gear_desc_id);		
		$where = "(`unavailable_from_date`<='".$from_date."' AND `unavailable_to_date`>='".$to_date."')";		
		$this->db->where($where);
		
		$query1 = $this->db->get();
		$ks_gear_unavailable  =$query1->result_array();
		
		return $ks_gear_unavailable;
		
	}
	
	public function gearOwner($order_id){
		
		$this->db->select('a.user_gear_desc_id,a.create_user');
		$this->db->from('ks_user_gear_description a');
		$this->db->join('ks_user_gear_rent_details b','a.user_gear_desc_id=b.user_gear_desc_id');
		$this->db->where('b.order_id',$order_id);	
		$query = $this->db->get();
		if($query->num_rows()>0){
			$row = $query->row_array();
			return $row['create_user'];
		}else
			return 0;
		
	}
	
	
}
//end of class
?>
