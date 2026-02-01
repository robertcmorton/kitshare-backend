<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home_model extends CI_Model {

	public function __construct() {

		parent::__construct();
	}
	
	public function send_email($sender, $to , $subject, $mail_body)
	{
		
//		$config['protocol'] = 'sendmail';
//		$config['charset']  = 'utf-8';
//		$config['mailtype'] = 'html';
//		$config['newline']  = "\r\n";

		$config = array('protocol'  => 'smtp',
					   'smtp_host' => 'smtp.sendgrid.net',
					   'smtp_port' => 587,
					   'smtp_user' => 'apikey',
					   'smtp_pass' => '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',
					   'mailtype'  => 'html',
					   'charset'   => 'utf-8'
					  );
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
		$this->db->from('gs_users');
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
				 a.gs_cust_gear_review_id,
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
				 g.app_user_id as rating_reviewer_id');
				 
		  $this->db->from('gs_cust_gear_reviews a');
		  $this->db->join('gs_owner_types b', 'b.owner_type_id = a.owner_type_id');
		  $this->db->join('gs_user_gear_description c', 'c.user_gear_desc_id = a.user_gear_desc_id');  
		  $this->db->join('gs_users d', 'd.app_user_id = a.app_user_id');
		  $this->db->join('gs_users e', 'e.app_user_id = c.app_user_id');
		  $this->db->join('gs_cust_gear_star_rating f', 'f.app_user_id = a.app_user_id', 'left');
		  $this->db->join('gs_cust_gear_star_rating g', 'g.user_gear_desc_id = a.user_gear_desc_id', 'left');
		  
		 
		  $this->db->where('c.app_user_id' ,$app_user_id); 
		  if($owner_type!='Owners & Renters'){
			$this->db->where('a.owner_type_id' ,$owner_type); 
		  }
		  
		  $this->db->order_by('a.gs_cust_gear_review_id', 'DESC');
		  $query = $this->db->get();
		  return $query;
    }
	public function UserAddress($app_user_id)
	{
		$this->db->select('a.user_address_id,a.business_address,a.apartment_number,a.street_address,a.default_equipment_address_1,a.default_equipment_address_2,b.suburb_name,b.suburb_postcode,c.gs_state_name,d.gs_country_name');
		$this->db->from('gs_user_address as a');
		$this->db->join('gs_suburbs as b', 'a.suburb_id = b.gs_suburb_id');
		$this->db->join('gs_states as c', 'c.gs_state_id = b.gs_state_id');
		$this->db->join('gs_countries as d', 'd.gs_country_id = c.gs_country_id');
		$this->db->where('a.app_user_id' , $app_user_id);
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;
	}
	public function BankDetails($app_user_id)
	{
		$this->db->select('a.user_bank_detail_id,a.bank_id,a.account_type,a.branch_code,a.bsb_number,a.branch_address,a.branch_street,a.branch_city,a.branch_zip_code,a.user_account_number,b.suburb_name as bank_suburb_name,b.suburb_postcode as bank_suburb_postcode,c.gs_state_name as bank_gs_state_name,d.gs_country_name as bank_gs_country_name');
		$this->db->from('gs_user_bank_details as a');
		$this->db->join('gs_suburbs as b', 'a.branch_suburb_id = b.gs_suburb_id');
		$this->db->join('gs_states as c', 'c.gs_state_id = a.branch_state_id');
		$this->db->join('gs_countries as d', 'd.gs_country_id = a.branch_country_id');
		$this->db->where('a.app_user_id' , $app_user_id);
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;
	}
	
	public function GearList($app_user_id)
	{
		$this->db->select('a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_in_gst,
						b.gear_display_image,
						c.app_user_first_name,
						c.app_user_last_name,
						d.gear_category_id');
		$this->db->from('gs_user_gear_description as a');
		$this->db->join('gs_user_gear_images as b', 'a.user_gear_desc_id = b.user_gear_desc_id');
		$this->db->join('gs_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('gs_models as d', 'a.model_id = d.model_id');
		$this->db->where(array('a.app_user_id'=>$app_user_id, 'b.gear_display_seq_id'=>1));
		$this->db->group_by('a.user_gear_desc_id');
		$this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}
    //Method to pickup the categories against which the user has gears
    public function GearCategories($app_user_id)
	{
	  $this->db->select('gs_user_gear_description.user_gear_desc_id,
						  gs_user_gear_description.model_id,
						  gs_user_gear_description.app_user_id,
						  gs_models.gear_category_id,
						  gs_gear_categories.gear_category_name');
		$this->db->from('gs_user_gear_description');
		$this->db->join('gs_models','gs_user_gear_description.model_id=gs_models.model_id');
		$this->db->join('gs_gear_categories','gs_models.gear_category_id=gs_gear_categories.gear_category_id');
		$this->db->where(array('gs_user_gear_description.app_user_id'=>$app_user_id));		
		$this->db->order_by('gs_gear_categories.gear_category_name', 'ASC');
		
		$query = $this->db->get();
		$result_categories=$query->result_array();
		
		$gears=$this->GearList($app_user_id);
				
		$gear_ratings=$this->GetGearRatings($app_user_id);
		
		$gears_listings=array();
		
		//Gear images are assigned with full path
		foreach($gears as $gear_lists){
			foreach($gear_lists as $key=>$value){
			  if($key=="gear_display_image"){
				$img_path=base_url().GEAR_IMG.$value;
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
   public function GetGearRatings($app_user_id){
   		
		$this->db->select('AVG(a.gear_star_rating_value) as rating, a.`user_gear_desc_id`');
		$this->db->from('gs_cust_gear_star_rating as a');
		$this->db->join('gs_user_gear_description b','b.user_gear_desc_id=a.user_gear_desc_id');
		$this->db->join('gs_users c','c.app_user_id=b.app_user_id');
		$this->db->where(array('b.app_user_id'=>$app_user_id, 'a.is_active'=>'y'));
		$query = $this->db->get();
		return $query->result_array();
		
   }
   
   //Method to get the logged in user's name and profile pic
   public function GetProfileInfo($app_user_id){
   	
		$this->db->select('app_user_first_name,app_user_last_name,user_profile_picture_link');
		$this->db->from('gs_users');
		$this->db->where(array('app_user_id'=>$app_user_id));
		$query = $this->db->get();
		return $query->result_array();
   }
   
   //Method to get the contact info
   public function GetContactInfo($app_user_id){
   
   		$this->db->select('a.user_address_id,a.app_user_id,a.business_address,a.apartment_number,a.street_address,a.suburb_id,
						  a.default_equipment_address_1,a.default_equipment_address_2,a.default_equipment_address_3,
						  a.default_equipment_address_4,a.default_equipment_address_5,
						  b.suburb_name,b.suburb_postcode,b.gs_state_id,
						  c.gs_state_name,c.gs_country_id,d.gs_country_name');
		$this->db->from('gs_user_address a');
		$this->db->join('gs_suburbs b','a.suburb_id=b.gs_suburb_id');
		$this->db->join('gs_states c','b.gs_state_id=c.gs_state_id');
		$this->db->join('gs_countries d','c.gs_country_id=d.gs_country_id');
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
		$this->db->from('gs_user_bank_details a');
		$this->db->join('gs_banks b','a.bank_id=b.bank_id');
   		$this->db->where(array('a.app_user_id'=>$app_user_id,'b.is_active'=>'Y'));
		$this->db->order_by('b.bank_name', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
   }
	
}
//end of class
?>