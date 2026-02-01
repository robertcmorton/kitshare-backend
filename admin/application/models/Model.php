<?php
class Model extends CI_Model { 

	public function __construct() { 
		parent::__construct(); 
	}
	
	
	public function privileges_group_by($where='',$limit='',$offset='',$id='')
	{
	
	    //echo $limit;
		$this->db->select('b.app_role_name,c.privilege_type,a.role_priv_id,a.app_role_id,a.app_priv_id');
		$this->db->from('app_role_priv a');
		$this->db->join('m_app_role as b', 'a.app_role_id = b.app_role_id','inner');
		$this->db->join('m_app_privilege c', 'a.app_priv_id  = c.app_priv_id','inner');
		if($where!=''){
			$this->db->where($where);
		}
		//if($limit!='' and $offset!=''){
			$this->db->limit($limit,$offset);
		//}
		$this->db->group_by("a.app_role_id");
		$this->db->order_by("role_priv_id","desc");
		$query = $this->db->get(); 
		return $query;
	
	
	}
	
	public function privileges($where='',$limit='',$offset='',$id=''){
	
	
		$this->db->select('b.app_role_name,c.privilege_type,a.role_priv_id,a.app_role_id,a.app_priv_id');
		$this->db->from('app_role_priv a');
		$this->db->join('m_app_role as b', 'a.app_role_id = b.app_role_id','inner');
		$this->db->join('m_app_privilege c', 'a.app_priv_id  = c.app_priv_id','inner');
		if($where!=''){
			$this->db->where($where);
		}
		
		$this->db->limit($limit,$offset);
		
		
		$this->db->order_by("role_priv_id","desc");
		$query = $this->db->get(); 
		return $query;
	
	
	}
	
	
	public function role_module($where='',$limit='',$offset='',$id=''){
	
	
		$this->db->select('a.role_mod_id,a.mod_id,a.app_role_id,a.creator,a.created_date,b.app_module_page,c.app_role_name,d.app_user_name as creator,e.app_user_name as modifier');
		$this->db->from('app_role_module a');
		$this->db->join('m_app_module as b', 'a.mod_id = b.mod_id','inner');
		$this->db->join('m_app_role c', 'a.app_role_id  = c.app_role_id','inner');
		$this->db->join("m_app_user as d", "a.creator = d.app_user_id","left");
		$this->db->join("m_app_user as e", "a.modifier = e.app_user_id","left");
		
		if($where!=''){
			$this->db->where($where);
		}
		
		$this->db->limit($limit,$offset);
		
		$this->db->order_by("role_mod_id","desc");
		$query = $this->db->get(); 
		return $query;
	
	
	}
	
	public function role_priv($where='',$limit='',$offset='',$id=''){
	
	
		$this->db->select('b.app_role_name,c.privilege_type,a.role_priv_id,a.app_role_id,a.app_priv_id');
		$this->db->from('app_role_priv a');
		$this->db->join('m_app_role as b', 'a.app_role_id = b.app_role_id','inner');
		$this->db->join('m_app_privilege c', 'a.app_priv_id  = c.app_priv_id','inner');
		if($where!=''){
			$this->db->where($where);
		}
		
		$this->db->limit($limit,$offset);
		
		$this->db->order_by("role_priv_id","desc");
		$query = $this->db->get(); 
		return $query;
	
	
	}
	
	public function user_role($where='',$limit='',$offset='',$id='') 
	{
		$this->db->select('a.user_role_id,a.app_user_id,b.app_user_name,b.app_user_id,d.app_role_name,a.app_role_id,e.app_priv_id,e.privilege_type,a.created_date,a.updated_date,a.active');
		$this->db->from("app_user_roles as a");
		$this->db->join("m_app_user as b", "a.app_user_id = b.app_user_id","inner");
		$this->db->join('m_app_role as d', 'a.app_role_id = d.app_role_id','inner');
		$this->db->join('app_role_priv as f', 'a.app_role_id = d.app_role_id','inner');
		$this->db->join('m_app_privilege e', 'f.app_priv_id  = e.app_priv_id','inner');
		if($where!=''){
			$this->db->where($where);
		}
		
		$this->db->limit($limit,$offset);
		
		$this->db->group_by("a.app_user_id");
		$this->db->order_by("a.user_role_id","desc");
		$query = $this->db->get(); 
		
		
		return $query;
	}
	public function user_role_order_by($where='',$limit='',$offset='',$order_by='', $order_by_field='') 
	{   
	   $fieldname = "d.".$order_by_field;
		$this->db->select('a.user_role_id,a.app_user_id,b.app_user_name,b.app_user_id,d.app_role_name,a.app_role_id,e.app_priv_id,e.privilege_type,a.created_date,a.updated_date,a.active');
		$this->db->from("app_user_roles as a");
		$this->db->join("m_app_user as b", "a.app_user_id = b.app_user_id","inner");
		$this->db->join('m_app_role as d', 'a.app_role_id = d.app_role_id','inner');
		$this->db->join('app_role_priv as f', 'a.app_role_id = d.app_role_id','inner');
		$this->db->join('m_app_privilege e', 'f.app_priv_id  = e.app_priv_id','inner');
		if($where!=''){
			$this->db->where($where);
		}
		
		$this->db->limit($limit,$offset);
		
		$this->db->group_by("a.app_user_id");
		$this->db->order_by($fieldname,$order_by);
		$query = $this->db->get(); 
		
		
		return $query;
	}
	
	
	public function pages($where='',$limit='',$offset='',$id='')
	{
		$this->db->select('a.page_id,a.lang_id,a.page_name,a.status,a.created_date,b.lang_name');
		$this->db->from("tbl_pages as a");
		$this->db->join("tbl_language as b", "a.lang_id = b.lang_id","inner");
		if($where!=''){
			$this->db->where($where);
		}
		
		$this->db->limit($limit,$offset);
		
		$this->db->order_by("a.page_id","desc");
		$query = $this->db->get();
		
		return $query;
	
	
	}
	
	//gear_categories
	public function gear_categories($where='',$limit='',$offset='',$id='')
	{
		$this->db->select('a.gear_category_id,a.gear_category_name,a.gear_sub_category_id,a.security_deposit,
						  a.average_value,a.is_active,a.create_date,b.gear_category_name as parentcategory_name');
		$this->db->from("ks_gear_categories as a");
		$this->db->join("ks_gear_categories as b", "a.gear_sub_category_id = b.gear_category_id","left");
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->group_by('a.gear_category_id');
		
		$this->db->limit($limit,$offset);
		
		$this->db->order_by("a.gear_category_name","asc");
		$query = $this->db->get();
		
		return $query;
	
	
	}
	public function gear_categories1($where='',$limit='',$offset='',$id='')
	{
		$this->db->select('a.gear_category_id,a.gear_category_name,a.gear_sub_category_id,a.security_deposit,
						  a.average_value,a.is_active,a.create_date,b.gear_category_name as parentcategory_name');
		$this->db->from("ks_gear_categories as a");
		$this->db->join("ks_gear_categories as b", "a.gear_sub_category_id = b.gear_category_id","left");
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->group_by('a.gear_category_id');
		
		$this->db->limit($limit,$offset);
		
		$this->db->order_by("a.gear_category_name","asc");
		$query = $this->db->get();
		
		return $query;
	
	
	}
	
	public function gear($where='',$limit='',$offset='',$id='')
	{
	   
		$this->db->select('a.user_gear_desc_id,a.gear_name,a.gear_desc_1,a.gear_desc_2,a.gear_category_id,a.brand_ida.app_user_id,a.per_day_cost,a.per_weekend_cost,a.owner_remark,a.gear_list_details_flag,a.gear_listing_date,a.gear_delisting_date,a.gear_view_count,a.gear_review_count,a.gear_star_rating_avg,a.gear_lending_count,a.is_active,a.create_user,a.create_date,b.brand_name,');
		$this->db->from("ks_user_gear_description as a");
		$this->db->join("ks_gear_categories as b", "a.gear_category_id = b.gear_category_id","inner");
		$this->db->join("ks_brands as c", "a.brand_id = b.brand_id","inner");
		$this->db->join("m_app_user as d", "a.app_user_id = d.app_user_id","inner");
		if($where!=''){
			$this->db->where($where);
		}
		
		$this->db->limit($limit,$offset);
		$this->db->order_by("a.gear_category_id","desc");
		$query = $this->db->get();
		return $query;
	
	
	}
	
	
	
	public function state($where='',$limit='',$offset='',$id='') 
	{
		$this->db->select('a.`base_rate`,,a.`ks_state_esl`,a.`ks_state_sd`,a.`ks_state_id`,a.`ks_state_name`,a.`ks_state_code`,a.`ks_country_id`, a.`is_active`, a.`create_user`, a.`create_date`, a.`update_user`, a.`update_date`, b.`ks_country_name`');
		$this->db->from("ks_states as a");
		$this->db->join("ks_countries as b", "b.ks_country_id = a.ks_country_id","inner");
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit,$offset);
		$this->db->order_by("a.ks_country_id","desc");
		$query = $this->db->get(); 
		
		return $query;
	}
	
	public function suburb($where='',$limit='',$offset='',$id='') 
	{
		$this->db->select('a.`ks_suburb_id`,a.`suburb_name`,a.`suburb_postcode`,a.`time_zone`,a.`ks_state_id`,a.`latitude`,a.`longitude`, a.`is_active`, a.`create_user`, a.`create_date`, a.`update_user`, a.`update_date`, b.`ks_state_name`');
		$this->db->from("ks_suburbs as a");
		$this->db->join("ks_states as b", "b.ks_state_id = a.ks_state_id","inner");
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit,$offset);
		$this->db->order_by("a.suburb_name","asc");
		$query = $this->db->get(); 
		
		return $query;
	}
	
	public function app_users($where='',$limit='',$offset='',$id='') 
	{
		$this->db->select('a.*,b.owner_type_name,c.ks_renter_type,address.street_address_line1, address.street_address_line2 ,state.ks_state_name, suburb.suburb_name ');
		$this->db->from("ks_users as a");
		$this->db->join("ks_owner_types as b", "b.owner_type_id = a.owner_type_id","inner");
		$this->db->join("ks_renter_type as c", "a.ks_renter_type_id = c.ks_renter_type_id","inner");
		$this->db->join("ks_user_address as address", "a.app_user_id = address.app_user_id AND address.default_address ='1' ","LEFT");
		$this->db->join("ks_states as state", "state.ks_state_id = address.ks_state_id ","LEFT");
		$this->db->join("ks_suburbs as suburb", "suburb.ks_suburb_id = address.ks_suburb_id ","LEFT");
		$this->db->group_by('a.app_user_id');
		
		
		
		//	$this->db->where('address.default_address', '1');
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
		
		$this->db->order_by("a.app_user_id","desc");
		$query = $this->db->get(); 
		
		return $query;
	}
	
	public function app_users_orderby($where='',$limit='',$offset='',$order_by='', $order_by_field='') 
	{
		
		$fieldname = "a.".$order_by_field;
		$this->db->select('a.*,b.owner_type_name,c.ks_renter_type');
		$this->db->from("ks_users as a");
		$this->db->join("ks_owner_types as b", "b.owner_type_id = a.owner_type_id","inner");
		$this->db->join("ks_renter_type as c", "a.ks_renter_type_id = c.ks_renter_type_id","inner");
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit,$offset);
		$this->db->order_by($fieldname,$order_by);
		$query = $this->db->get(); 
		
		return $query;
	}
	
	
	public function app_users_rent($where='',$limit='',$offset='',$id='',$order_by ='') 
	{
		$this->db->select('a.*,a.total_rent_amount_ex_gst,a.gst_amount,a.security_deposit,a.order_id ,a.is_payment_completed ,a.user_gear_rent_id,a.user_gear_desc_id,a.gear_rent_requested_on,a.gear_rent_request_from_date,a.gear_rent_request_to_date,a.gear_total_rent_request_days,a.gear_rent_start_date,a.gear_rent_end_date,a.total_rent_days,a.total_rent_amount,a.is_rent_approved,a.rent_approved_rejected_on,a.is_rent_cancelled,a.rent_calcelled,a.rent_request_cancelled_by,a.create_user,b.user_gear_rent_id,b.rent_request_sent_by,b.rent_request_sent_to,b.delivery_address_id,b.total_discount,b.other_charges,b.total_rent_amount,c.user_gear_desc_id,c.gear_name,c.gear_description_1,c.gear_description_2,c.model_id,c.app_user_id,c.serial_number,c.replacement_value_aud_ex_gst,c.replacement_value_aud_inc_gst,c.per_day_cost_aud_ex_gst,c.per_day_cost_aud_inc_gst,c.per_weekend_cost_aud_ex_gst,c.per_weekend_cost_aud_inc_gst,c.per_week_cost_aud_ex_gst,c.per_week_cost_aud_inc_gst,c.owners_remark,d.app_username,d.app_user_first_name,d.app_user_last_name,d.owner_type_id,d.user_birth_date,d.australian_business_number,d.user_description,c.gear_list_delist_flag,c.gear_listing_date,c.gear_delisting_date,c.gear_view_count,c.gear_review_count,c.gear_star_rating_avg,c.gear_lending_count,c.is_active,e.model_name,e.model_description');
		$this->db->from("ks_user_gear_rent_details as a");
		$this->db->join("ks_user_gear_rent_master as b", "a.user_gear_rent_id = b.user_gear_rent_id","inner");
		$this->db->join("ks_user_gear_description as c", "a.user_gear_desc_id = c.user_gear_desc_id","inner");
		$this->db->join("ks_users as d", "c.app_user_id = d.app_user_id","inner");
		$this->db->join("ks_models as e", "c.model_id = e.model_id","inner");
		$this->db->where("c.app_user_id",$id);
		$this->db->where("a.is_payment_completed","Y");
		$this->db->where("a.order_id !=",''	);
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit,$offset);
		$this->db->order_by("a.user_gear_rent_detail_id",$order_by);
		$query = $this->db->get(); 
		return $query;
	}
		public function app_users_rentedList($where='',$limit='',$offset='',$id='',$order_by='') 
	{
		$this->db->select('a.user_gear_rent_detail_id,a.user_gear_rent_id,a.user_gear_desc_id,a.gear_rent_requested_on,a.gear_rent_request_from_date,a.gear_rent_request_to_date,a.gear_total_rent_request_days,a.gear_rent_start_date,a.gear_rent_end_date,a.total_rent_days,a.total_rent_amount,a.is_rent_approved,a.rent_approved_rejected_on,a.is_rent_cancelled,a.rent_calcelled,a.is_payment_completed,a.rent_request_cancelled_by,a.create_user,b.user_gear_rent_id,b.rent_request_sent_by,b.rent_request_sent_to,b.delivery_address_id,b.total_discount,b.other_charges,b.total_rent_amount,c.user_gear_desc_id,c.gear_name,c.gear_description_1,c.gear_description_2,c.model_id,c.app_user_id,c.serial_number,c.replacement_value_aud_ex_gst,c.replacement_value_aud_inc_gst,c.per_day_cost_aud_ex_gst,c.per_day_cost_aud_inc_gst,c.per_weekend_cost_aud_ex_gst,c.per_weekend_cost_aud_inc_gst,c.per_week_cost_aud_ex_gst,c.per_week_cost_aud_inc_gst,c.owners_remark,d.app_username,d.app_user_first_name,d.app_user_last_name,d.owner_type_id,d.user_birth_date,d.australian_business_number,d.user_description,c.gear_list_delist_flag,c.gear_listing_date,c.gear_delisting_date,c.gear_view_count,c.gear_review_count,c.gear_star_rating_avg,c.gear_lending_count,c.is_active,c.model_name');
		$this->db->from("ks_user_gear_rent_details as a");
		$this->db->join("ks_user_gear_rent_master as b", "a.user_gear_rent_id = b.user_gear_rent_id","inner");
		$this->db->join("ks_user_gear_order_description as c", " a.order_id = c.order_id AND  a.user_gear_desc_id = c.user_gear_desc_id","inner");
		$this->db->join("ks_users as d", "c.app_user_id = d.app_user_id","inner");
		
		$this->db->where("a.create_user",$id);
		$this->db->where("a.is_payment_completed","Y");
		$this->db->where("a.order_id !=",''	);
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit,$offset);
		$this->db->order_by("a.user_gear_rent_detail_id",$order_by);
		$query = $this->db->get(); 
		return $query;
	}
	
	
	
	public function app_users_rent_delivery_details($where='',$limit='',$offset='',$id='') 
	{
		$this->db->select('a.user_gear_rent_delivery_id,a.user_gear_rent_id,a.delivery_token,a.delivery_token_generated_on,a.is_delivered,a.delivery_timestamp,a.return_token,a.return_token_generated_on,a.is_returned,a.return_timestamp,c.appartment_number,c.street_address_line1,c.street_address_line2,c.business_address,c.is_active');
		$this->db->from("ks_user_gear_rent_deliveries as a");
		$this->db->join("ks_user_gear_rent_master as b", "a.user_gear_rent_id = b.user_gear_rent_id","inner");
		$this->db->join("ks_user_address as c", "b.delivery_address_id = c.user_address_id","inner");
		$this->db->where("c.app_user_id",$id);
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit,$offset);
		$this->db->order_by("a.user_gear_rent_delivery_id","desc");
		$query = $this->db->get(); 
		return $query;
	}
	
	
	public function app_users_payment_details($where='',$limit='',$offset='',$userid='',$rent_delivery_id='') 
	{
	    //SELECT `bank_id`, `bank_name`, `bank_logo`, `bank_head_office`, `is_active`, `create_user`, `create_date`, `update_user`, `update_date` FROM `ks_banks` WHERE 1
		$this->db->select('a.user_gear_payment_id,a.app_user_id,a.user_gear_rent_delivery_id,a.payment_mode_abbr,a.bank_id,a.trasaction_id,a.transaction_amount,a.transaction_timestamp,a.transaction_timestamp,b.bank_name,b.bank_head_office');
		$this->db->from("ks_user_gear_payments as a");
		$this->db->join("ks_banks as b", "a.bank_id = b.bank_id","left");
		$this->db->where("a.app_user_id",$userid);
		$this->db->where("a.user_gear_rent_delivery_id",$rent_delivery_id);
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit,$offset);
		$this->db->order_by("a.app_user_id ","desc");
		$query = $this->db->get(); 
		return $query;
	}
	
	
	
	
	public function TotalRecords($table,$where_clause) {
		$this->db->select('*');
		$this->db->from($table);
		if(!empty($where_clause)){
			$this->db->where($where_clause);
		}
		
		$query = $this->db->get();
		return $query->num_rows();
	}

	
	public function retriveValue($table,$where_clause) {
		$this->db->select('*');
		$this->db->from($table);
		if(!empty($where_clause)){
			$this->db->where($where_clause);
		}
	
		$query = $this->db->get();
		//echo $this->db->last_query();
		
		return $query;
	}
	
	public function RetriveRecordByWhereLimit($table,$where_clause,$limit,$offset,$orderbyfld,$orderby) {
		$this->db->select('*');
		$this->db->from($table);
		if(!empty($where_clause)){
			$this->db->where($where_clause);
		}
	
		$this->db->order_by($orderbyfld, $orderby);
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		//echo $this->db->last_query();
		
		return $query;
	}
	
	
	
	public function getPrice($site_id){
	
		$query = "SELECT SUM(a.sale_price) as price
				  FROM tbl_block_units a
				  JOIN tbl_blocks b ON a.block_id=b.block_id
				  WHERE b.site_id=".$site_id;
		$query = $this->db->query($query);
		return $query;
	
	}
	
	
	public function RetriveRecordBysitesWhere($id) {
		
		
		$query = "SELECT a.site_form_id, a.site_field_label,a.site_field_code, b.site_id, b.site_form_value_id, b.site_field_value, b.created_date, b.created_time 
		         FROM  tbl_site_form a INNER JOIN tbl_site_form_value b ON a.site_form_id = b.site_form_id 
				 WHERE b.site_id=".$id;
		$query = $this->db->query($query);
		return $query;
		
		
	}
	
	
	public function getBlockName($id){
	
		$this->db->select('*');
		$this->db->from('tbl_blocks');
		$this->db->where('site_id',$id);
		//$this->db->group_by('block_name');
		$query = $this->db->get();
		
		//print_r($query->result()); die;
		return $query->result();
	
	
	}
	
	
	public function getDevelopmentType($id){
	
	
		$query = "SELECT c.development_type,c.development_type_id
				 FROM  tbl_block_units a 
				 INNER JOIN tbl_blocks b ON a.block_id = b.block_id
				 INNER JOIN tbl_development_type c ON a.development_type_id=c.development_type_id 
				 WHERE b.site_id=".$id." GROUP BY c.development_type_id";
		$query = $this->db->query($query);
		return $query->result();
	
	
	
	}
	
		public function device($where='1')
	{
		$this->db->select('a.* ,m.manufacturer_name,modal.model_name,category.gear_category_name, sub_category.gear_category_name As sub_category_name,gear_type.ks_gear_type_name');
		$this->db->from("ks_user_gear_description as a");
		$this->db->join('ks_manufacturers As m' ,'a.ks_manufacturers_id = m.manufacturer_id ','LEFT');
		$this->db->join('ks_models As modal' ,'a.model_id = modal.model_id ','LEFT');
		$this->db->join('ks_gear_categories As category' ,'a.ks_category_id = category.gear_category_id ','LEFT');
		$this->db->join('ks_gear_categories As sub_category' ,'a.ks_sub_category_id = sub_category.gear_category_id ','LEFT');
		$this->db->join('ks_gear_type As gear_type' ,'a.ks_gear_type_id = gear_type.ks_gear_type_id ','LEFT');
		//$this->db->join("ks_gear_categories as b", "a.gear_category_id = b.gear_sub_category_id","left");
		if($where!=''){
			$this->db->where($where);
		}
		
		
		$this->db->order_by("a.user_gear_desc_id","desc");
		$query = $this->db->get();
		
		return $query;
	
	
	}
	
	
	
	public function getAllSiteData($siteId){
	
	
	   $sql = "SELECT b.block_id, b.site_id, b.block_code, b.block_name, b.no_of_floors, b.no_of_units_floor, b.no_of_units,a.block_unit_id, a.block_id, a.development_type_id, a.block_unit_code, a.unit_location, a.unit_type_id, a.sale_price,d.development_type,c.unit_type
	             FROM tbl_block_units a 
				 INNER JOIN tbl_blocks b ON a.block_id=b.block_id
				 INNER JOIN tbl_unit_type c ON a.unit_type_id=c.unit_type_id
				 INNER JOIN tbl_development_type d ON a.development_type_id=d.development_type_id
				 WHERE b.site_id=".$siteId;
				 
	   $query = $this->db->query($sql);
	   return $query->result();
	
	
	}
	
	public function CountWhere($table_name,$where_clause) {
	
		  $this->db->select('*');
		  if(!empty($where_clause))
		  $this->db->where($where_clause);
		  $this->db->from($table_name);
		  $query = $this->db->get();  
		  $tot_rec = $query->num_rows();
		  return $tot_rec;
	 }
	 
	 
	public function record_count() {
		return $this->db->count_all("tbl_investor_payment_plan");
	}
 
	public function fetch($limit,$start,$where) {
	    
		$this->db->select('*');
		$this->db->from('tbl_investor_payment_plan a');
		$this->db->join('tbl_investor_payment_schedule b', 'a.inv_pay_plan_id = b.inv_pay_plan_id', 'inner');
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit, $start);
		$this->db->group_by("a.inv_pay_plan_id");
		$this->db->order_by("a.inv_pay_plan_id","desc");
		$query = $this->db->get();
		
		//echo $this->db->last_query(); die;
		return $query;
	}
	
	public function fetchUp($limit,$start,$where) {
	    
		$this->db->select('*');
		$this->db->from('tbl_investor_payment_plan a');
		$this->db->join('tbl_investor_payment_schedule b', 'a.inv_pay_plan_id = b.inv_pay_plan_id', 'inner');
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit, $start);
		$this->db->order_by("a.inv_pay_plan_id","desc");
		$query = $this->db->get();
		
		//echo $this->db->last_query(); die;
		return $query;
	}
	
	
	
	public function record_count_da() {
		return $this->db->count_all("tbl_developer_agreement");
	}
	
	
	public function fetchDA($limit,$start,$where) {
	    
		$this->db->select('a.`developer_agreement_id`, a.`developer_id`, a.`site_id`, a.`development_type_id`, a.`purchase_price`, a.`purchase_price_currency`, a.`purchase_price_pound`, a.`no_of_units`, a.`no_of_payments`, a.`construction_start_date`, a.`total_percentage`, a.`total_gbp`, a.`total_local_curr`, a.`created_date`,b.`developer_name`,c.`site_name`,d.`development_type`');
		$this->db->from('tbl_developer_agreement a');
		$this->db->join('tbl_developer b', 'a.developer_id = b.developer_id', 'inner');
		$this->db->join('tbl_site c', 'a.site_id = c.site_id', 'inner');
		$this->db->join('tbl_development_type d', 'a.development_type_id = d.development_type_id', 'inner');
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit, $start);
		$this->db->order_by("a.developer_agreement_id","desc");
		$query = $this->db->get();
		
		
		return $query;
	}
	
	
	public function fetchDAUp($where) {
	    
		$this->db->select('*');
		$this->db->from('tbl_developer_agreement a');
		$this->db->join('tbl_developer b', 'a.developer_id = b.developer_id', 'inner');
		$this->db->join('tbl_site c', 'a.site_id = c.site_id', 'inner');
		$this->db->join('tbl_development_type d', 'a.development_type_id = d.development_type_id', 'inner');
		$this->db->join('tbl_payment_schedule e', 'a.developer_agreement_id = e.developer_agreement_id', 'inner');
		if($where!=''){
			$this->db->where($where);
		}
		$query = $this->db->get();
		
		
		return $query;
	}
	
	
	
	public function get_bolock_units($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_block_units a');
		$this->db->join('tbl_blocks b', 'a.block_id = b.block_id', 'inner');
		$this->db->where(array("site_id"=>$id));
		return $query = $this->db->get();
	
	}
	
	public function get_dev_types($id){
	
	  $query = $this->db->query("SELECT DISTINCT(e.development_type_id) FROM tbl_site_form AS a INNER JOIN tbl_site_form_value AS b ON a.site_form_id=b.site_form_id INNER JOIN tbl_blocks AS c ON b.site_id=c.site_id INNER JOIN tbl_block_units AS d ON c.block_id=d.block_id INNER JOIN tbl_development_type AS e ON d.development_type_id=e.development_type_id INNER JOIN tbl_unit_type AS f ON d.unit_type_id=f.unit_type_id WHERE b.site_id=".$id." AND a.site_form_id=6 GROUP BY d.block_id");
	  return $result = $query->result();
	  
	
	}
	
	public function get_dev_types_det($id,$dev_type_id){
	
	  $query = $this->db->query("SELECT a.site_form_id,a.site_field_code,b.site_field_value,c.block_id,c.block_name, c.no_of_floors,c.no_of_units_floor,c.no_of_units,d.development_type_id,d.unit_type_id,e.development_type,f.unit_type FROM tbl_site_form AS a INNER JOIN tbl_site_form_value AS b ON a.site_form_id=b.site_form_id INNER JOIN tbl_blocks AS c ON b.site_id=c.site_id INNER JOIN tbl_block_units AS d ON c.block_id=d.block_id INNER JOIN tbl_development_type AS e ON d.development_type_id=e.development_type_id INNER JOIN tbl_unit_type AS f ON d.unit_type_id=f.unit_type_id 
WHERE b.site_id=".$id." AND e.development_type_id=".$dev_type_id." AND a.site_form_id=6 GROUP BY d.block_id");
	  return $result = $query->result();
	  
	
	}
	
	
	public function checkAppUserAd($username) {
		$sql = "SELECT * FROM `ks_users` WHERE (app_username='".$username."') " ;
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function checkAppUserEdit($username,$id) {
		$sql = "SELECT * FROM `ks_users` WHERE app_username='".$username."' AND app_user_id !=".$id;
		$query = $this->db->query($sql);
		
		return $query;
	}
	
	public function checkUserAd($username) {
		$sql = "SELECT * FROM `".M_APP_USER."` WHERE (app_user_name='".$username."') " ;
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function checkUserEdit($username,$id) {
		$sql = "SELECT * FROM `".M_APP_USER."` WHERE app_user_name='".$username."' AND app_user_id !=".$id;
		$query = $this->db->query($sql);
		return $query;
	}
	
	
	public function checkAppUser($username) {
		$sql = "SELECT * FROM ks_users  WHERE (app_username='".$username."') " ;
		$query = $this->db->query($sql);
		return $query;
	}
	
	
	public function checkAppUserId($user_unique_id_number) {
		$sql = "SELECT * FROM ks_users WHERE (user_unique_id_number='".$user_unique_id_number."') " ;
		$query = $this->db->query($sql);
		return $query;
	}
	
	/*public function checkAppUserEdit($username,$id) {
		$sql = "SELECT * FROM `".ks_USERS."` WHERE app_username='".$username."' AND app_user_id !=".$id;
		$query = $this->db->query($sql);
		return $query;
	}*/
	
	public function get_username($id)
	{
	
		  $sql = "SELECT * FROM `".M_APP_USER."` WHERE app_user_id =".$id;
		  $query = $this->db->query($sql);
		  $users = $query->result();
		  return $users[0]->app_user_name;
	
	}
	
	
	public function get_category_name($id)
	{
	
		  $sql = "SELECT gear_category_name FROM `ks_gear_categories` WHERE gear_category_id =".$id;
		  $query = $this->db->query($sql);
		  $ct = $query->result();
		  return $ct[0]->gear_category_name;
	
	}
	
	public function get_all_suburb_data(){
	
		$this->db->select('a.ks_suburb_id AS id,a.suburb_name AS name, a.urban_area, b.ks_state_code AS state_code, b.ks_state_name AS state, 
						  a.suburb_postcode AS postcode, a.suburb_type AS type, a.latitude, a.longitude, a.elevation, a.population,
						  a.area_sq_km, a.local_government_area, a.time_zone'); 
		$this->db->from('ks_suburbs a');
		$this->db->join('ks_states b','a.ks_state_id = b.ks_state_id','inner');
		$this->db->order_by('a.ks_suburb_id asc');
		$query = $this->db->get();
		
		return $query;
	
	}
	
	public function get_feature_list($feature_master_id){
		
		$this->db->select('feature_values');
		$this->db->from('ks_gear_feature_details');
		$this->db->where(array('is_active'=>'Y', 'feature_master_id'=>$feature_master_id));
		$this->db->order_by('feature_details_id asc');
		$query = $this->db->get();
		
		return $query;
		
	}
	
	public function get_feature_master_id($feature_name,$gear_category_name){
		
		$this->db->select('a.feature_master_id');
		$this->db->from('ks_feature_master a');
		$this->db->join('ks_gear_categories b','b.gear_category_id=a.gear_category_id');
		$this->db->where(array('a.feature_name'=>$feature_name,'b.gear_category_name'=>$gear_category_name));
		$query = $this->db->get();
		$res = $query->result_array();
		
		if(count($res)>0)
			$feature_master_id = $res[0]['feature_master_id'];
		else
			$feature_master_id = 0;
		
		return $feature_master_id;
		
	}
	
	
	public function getModelsList($where_array,$limit=0,$offset=0,$order_by_fld='',$order_by=''){
	
		$this->db->select('a.model_id,a.model_name,a.gear_category_id,a.manufacturer_id,a.model_image,a.is_active,a.model_description,a.per_day_cost_usd,a.per_day_cost_aud_ex_gst,						  						  a.per_day_cost_aud_inc_gst,a.replacement_value_usd,
						  a.replacement_value_aud_ex_gst,a.replacement_value_aud_inc_gst,a.replacement_day_rate_percent,
						  b.gear_category_name,d.gear_category_name AS gear_sub_category_name ,c.manufacturer_name,a.create_date');
		$this->db->from('ks_models a');
		$this->db->join('ks_gear_categories b','a.gear_category_id=b.gear_category_id', 'LEFT');
		$this->db->join('ks_gear_categories d','a.gear_sub_category_id=d.gear_category_id','LEFT');
		$this->db->join('ks_manufacturers c','a.manufacturer_id=c.manufacturer_id','LEFT');
		$this->db->where($where_array);
		if($limit>0)
			$this->db->limit($limit, $offset);
		if($order_by!='')
			$this->db->order_by($order_by_fld,$order_by);
		
		$query = $this->db->get();
				
		return $query;
	
	}
	
	public function parent_category($gear_category_id){
	
		$this->db->select('b.gear_category_name');
		$this->db->from('ks_gear_categories a');
		$this->db->join('ks_gear_categories b','a.gear_sub_category_id=b.gear_category_id','left');
		$this->db->where(array("a.gear_category_id"=>$gear_category_id));
		$query = $this->db->get();
		
		return $query;
		
	}
	
	public function checkDuplicateModel($model_name,$manufacturer_name=''){
	
		$this->db->select('a.model_id');
		$this->db->from('ks_models a');
		if($manufacturer_name!=''){
			$this->db->join('ks_manufacturers b','a.manufacturer_id=b.manufacturer_id');
			$this->db->where("LOWER(b.manufacturer_name)='".strtolower($manufacturer_name)."'");
		}
		$this->db->where("LOWER(a.model_name)='".strtolower($model_name)."'");
		$query = $this->db->get();
		
		return $query->result();
	
	}
	
	//gear_percentage category
	public function getGearPercentRate($where='',$limit='',$offset='',$id='')
	{
		$this->db->select('a.*,b.gear_category_name AS category_name, c.gear_category_name As sub_category_name');
		$this->db->from("ks_gear_percentage_rate as a");
		$this->db->join("ks_gear_categories as b", "a.category_id = b.gear_category_id","left");
		$this->db->join("ks_gear_categories as c", "a.sub_category_id = c.gear_category_id","left");
		if($where!=''){
			$this->db->where($where);
		}
		
		$this->db->limit($limit,$offset);
		
		$this->db->order_by("a.id","asc");
		$query = $this->db->get();
		
		return $query;
	
	
	}
	 public function gear_percentage_rate($where='')
	 {
	 	$this->db->select('a.*,b.gear_category_name AS category_name, c.gear_category_name As sub_category_name');
		$this->db->from("ks_gear_percentage_rate as a");
		$this->db->join("ks_gear_categories as b", "a.category_id = b.gear_category_id","left");
		$this->db->join("ks_gear_categories as c", "a.sub_category_id = c.gear_category_id","left");
		if($where!=''){
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query;
	 }
	 public function OrderList($where='',$limit='',$offset='',$order_by='' ,$order_column ='')
	{
		$this->db->select('rent_details.is_rent_rejected,rent_details.order_status,rent_details.is_rent_cancelled,rent_details.user_gear_rent_detail_id,rent_details.user_gear_rent_id,rent_details.user_gear_desc_id,rent_details.gear_rent_requested_on,rent_details.gear_rent_request_from_date,rent_details.gear_rent_request_to_date,rent_details.gear_total_rent_request_days,rent_details.gear_rent_start_date,rent_details.gear_rent_end_date,rent_details.total_rent_days,rent_details.total_rent_amount,rent_details.is_rent_approved,rent_details.rent_approved_rejected_on,rent_details.is_rent_cancelled,rent_details.rent_calcelled,rent_details.is_payment_completed,rent_details.rent_request_cancelled_by,rent_details.create_user,gear.user_gear_rent_id,gear.rent_request_sent_by,gear.rent_request_sent_to,gear.delivery_address_id,gear.total_discount,gear.other_charges,gear.total_rent_amount,gears.user_gear_desc_id,gears.gear_name,gears.gear_description_1,gears.gear_description_2,gears.model_id,gears.app_user_id,gears.serial_number,gears.replacement_value_aud_ex_gst,gears.replacement_value_aud_inc_gst,gears.per_day_cost_aud_ex_gst,gears.per_day_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_week_cost_aud_inc_gst,gears.owners_remark,gears.owner_app_username As app_username,gears.owner_app_user_first_name As app_user_first_name,gears.owner_app_user_last_name As app_user_last_name,b.owner_type_id,b.user_birth_date,b.australian_business_number,b.user_description,gears.gear_list_delist_flag,gears.gear_listing_date,gears.gear_delisting_date,gears.gear_view_count,gears.gear_review_count,gears.gear_star_rating_avg,gears.gear_lending_count,gears.is_active,gears.renter_app_username As buyer_username,gears.renter_app_user_first_name As buyer_first_name,gears.renter_app_user_last_name As buyer_last_name ,rent_details.order_id,rent_details.project_name,order_payment.user_gear_payment_id,order_payment.status As paymnet_status,rent_details.insurance_amount,rent_details.ks_insurance_category_type_id,rent_details.deposite_status ,gears.owner_app_show_business_name ,gears.owner_app_bussiness_name , gears.renter_app_show_business_name,gears.renter_app_bussiness_name ');
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = rent_details.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
		$this->db->join('ks_user_gear_order_description AS gears'," gears.order_id = rent_details.order_id  " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.create_user " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payment'," order_payment.gear_order_id = rent_details.order_id " ,"LEFT");
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		if($where!=''){
			$this->db->where($where);
		}
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
		
		$this->db->group_by("rent_details.order_id");
		if (empty($order_column) && empty($order_by)) {
			$this->db->order_by('rent_details.gear_rent_request_from_date','DESC');
		}else{
			$this->db->order_by($order_column,$order_by);
		}
		$query = $this->db->get();
		// echo $this->db->last_query();die;
		return $query;

	}	
	public function OrderPayments($where='',$limit='',$offset='',$order_by='')
	{	
		$this->db->select('p.*,a.owner_insurance_amount,a.total_rent_amount_ex_gst,c.is_registered_for_gst,a.gst_amount,a.is_rent_rejected,a.is_rent_cancelled,a.user_gear_rent_detail_id,a.user_gear_rent_id,a.user_gear_desc_id,a.gear_rent_requested_on,a.gear_rent_request_from_date,a.gear_rent_request_to_date,a.gear_total_rent_request_days,a.gear_rent_start_date,a.gear_rent_end_date,a.total_rent_days,a.total_rent_amount,a.is_rent_approved,a.rent_approved_rejected_on,a.is_rent_cancelled,a.rent_calcelled,a.is_payment_completed,a.rent_request_cancelled_by,a.create_user,b.user_gear_rent_id,b.rent_request_sent_by,b.rent_request_sent_to,b.delivery_address_id,b.total_discount,b.other_charges,b.total_rent_amount,c.user_gear_desc_id,c.gear_name,c.gear_description_1,c.gear_description_2,c.model_id,c.app_user_id,c.serial_number,c.replacement_value_aud_ex_gst,c.replacement_value_aud_inc_gst,c.per_day_cost_aud_ex_gst,c.per_day_cost_aud_inc_gst,c.per_weekend_cost_aud_ex_gst,c.per_weekend_cost_aud_inc_gst,c.per_week_cost_aud_ex_gst,c.per_week_cost_aud_inc_gst,c.owners_remark,c.owner_app_username As app_username,c.owner_app_user_first_name AS app_user_first_name,c.owner_app_user_last_name As app_user_last_name,d.owner_type_id,d.user_birth_date,d.australian_business_number,d.user_description,c.gear_list_delist_flag,c.gear_listing_date,c.gear_delisting_date,c.gear_view_count,c.gear_review_count,c.gear_star_rating_avg,c.gear_lending_count,c.is_active,c.renter_app_username  As buyer_username,c.renter_app_user_first_name As buyer_first_name,c.renter_app_user_last_name  As buyer_last_name ,a.order_id , tbl_refund.amount As refund_amount,tbl_refund.refund_date,c.is_registered_for_gst,c.gst_rate,c.owner_app_show_business_name,c.owner_app_bussiness_name,c.renter_app_show_business_name,c.renter_app_bussiness_name');
		$this->db->from("ks_user_gear_payments as p");
		$this->db->join("ks_user_gear_rent_details as a" , "a.order_id =p.gear_order_id","INNER");
		$this->db->join("ks_user_gear_rent_master as b", "a.user_gear_rent_id = b.user_gear_rent_id","inner");
		$this->db->join("ks_user_gear_order_description as c", "a.order_id = c.order_id","inner");
		$this->db->join("ks_users as d", "c.app_user_id = d.app_user_id","inner");
		// $this->db->join("ks_models as e", "c.model_id = e.model_id","inner");
		// $this->db->join("ks_users as buyer", "buyer.app_user_id = a.create_user","inner");
		$this->db->join("ks_refund_order as tbl_refund", "tbl_refund.order_id = p.gear_order_id","LEFT");
		$this->db->where("a.is_payment_completed","Y");
		$this->db->where("a.order_id !=",''	);
		if($where!=''){
			$this->db->where($where);
		}
		$this->db->limit($limit,$offset);
		$this->db->order_by("p.user_gear_payment_id",$order_by);
		$this->db->group_by('gear_order_id');
		$query = $this->db->get(); 
		return $query;

	}
	

		public function getUserCartbyOrderId($order_id)
	{
		
		$this->db->select('cart.community_fee,cart.insurance_fee,cart.beta_discount,cart.create_user,cart.user_gear_rent_detail_id,cart.user_gear_desc_id,cart.user_gear_rent_id,cart.security_deposit,
		cart.gear_rent_requested_on,cart.gear_rent_request_from_date,cart.gear_rent_request_to_date,
		cart.gear_total_rent_request_days,cart.total_rent_amount_ex_gst,cart.gst_amount,cart.other_charges,cart.total_rent_amount,a.gear_name,
						a.per_day_cost_aud_ex_gst,
						a.per_day_cost_aud_inc_gst,
						b.gear_display_image,
						c.app_user_first_name,c.app_user_id,c.bussiness_name As owner_bussiness_name ,c.show_business_name As owner_show_bussiness_name,
						c.app_user_last_name,c.primary_email_address,c.registered_for_gst,c.user_profile_picture_link,
						renter_users.app_user_first_name As renter_firstname,renter_users.app_user_id As renter_app_user_id,renter_users.bussiness_name As renter_bussiness_name ,renter_users.show_business_name As renter_show_bussiness_name,
						renter_users.app_user_last_name As renter_lastname,renter_users.primary_email_address As renter_email,,renter_users.user_profile_picture_link as renter_image,
						d.gear_category_id,a.user_gear_desc_id,cart.user_gear_desc_id,a.replacement_value_aud_ex_gst,a.per_day_cost_aud_ex_gst,a.replacement_value_aud_inc_gst');

		$this->db->from('ks_user_gear_rent_details AS cart');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = cart.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
	
		$this->db->join('ks_user_gear_order_description as a' , 'a.order_id = cart.order_id' ,'INNER');
		 $this->db->join('ks_user_gear_order_images as b', 'a.order_id = b.order_id', 'LEFT');
		$this->db->join('ks_users as c', 'a.app_user_id = c.app_user_id');
		$this->db->join('ks_users as renter_users', 'cart.create_user = renter_users.app_user_id');
		
		$this->db->join('ks_models as d', 'a.model_id = d.model_id', 'LEFT');
		$this->db->where('cart.order_id',$order_id );
		//$this->db->where('status_master.is_active','N' );
		//$this->db->where('cart.is_rent_approved','N' );
		$this->db->where('cart.is_payment_completed','Y' );
		
		$this->db->group_by('cart.user_gear_rent_detail_id');
		// $this->db->order_by('a.user_gear_desc_id', 'DESC');
		$query = $this->db->get();
		$data = 	$query->result_array();
		
		return  $data ;
	}
	
	 public function InsuranceListSummary($where='',$limit='',$offset='',$id='')
	{
	
		$this->db->select('rent_details.*,rent_details.order_status,rent_details.is_rent_cancelled,rent_details.user_gear_rent_detail_id,rent_details.user_gear_rent_id,rent_details.user_gear_desc_id,rent_details.gear_rent_requested_on,rent_details.gear_rent_request_from_date,rent_details.gear_rent_request_to_date,rent_details.gear_total_rent_request_days,rent_details.gear_rent_start_date,rent_details.gear_rent_end_date,rent_details.total_rent_days,rent_details.total_rent_amount,rent_details.is_rent_approved,rent_details.rent_approved_rejected_on,rent_details.is_rent_cancelled,rent_details.rent_calcelled,rent_details.is_payment_completed,rent_details.rent_request_cancelled_by,rent_details.create_user,gear.user_gear_rent_id,gear.rent_request_sent_by,gear.rent_request_sent_to,gear.delivery_address_id,gear.total_discount,gear.other_charges,gear.total_rent_amount,gears.user_gear_desc_id,gears.gear_name,gears.gear_description_1,gears.gear_description_2,gears.model_id,gears.app_user_id,gears.serial_number,gears.replacement_value_aud_ex_gst,gears.replacement_value_aud_inc_gst,gears.per_day_cost_aud_ex_gst,gears.per_day_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_week_cost_aud_inc_gst,gears.owners_remark,b.app_username,b.app_user_first_name,b.app_user_last_name,b.owner_type_id,b.user_birth_date,b.australian_business_number,b.user_description,gears.gear_list_delist_flag,gears.gear_listing_date,gears.gear_delisting_date,gears.gear_view_count,gears.gear_review_count,gears.gear_star_rating_avg,gears.gear_lending_count,gears.is_active,users.app_username As buyer_username,users.app_user_first_name As buyer_first_name,users.app_user_last_name As buyer_last_name ,rent_details.project_name,order_payment.user_gear_payment_id,order_payment.status As paymnet_status,rent_details.insurance_amount,rent_details.ks_insurance_category_type_id,rent_details.deposite_status ,tier_type.tier_name,insurance_type.name,tier_type.tiers_percentage ,gear_location.*');
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = rent_details.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
		$this->db->join('ks_user_gear_order_description AS gears'," gears.order_id = rent_details.order_id AND gears.user_gear_desc_id = rent_details.user_gear_desc_id " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.create_user " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payment'," order_payment.gear_order_id = rent_details.order_id " ,"LEFT");
		$this->db->join('ks_insurance_category_type AS insurance_type'," insurance_type.ks_insurance_category_type_id = rent_details.ks_insurance_category_type_id " ,"LEFT");
		$this->db->join('ks_insurance_tiers AS tier_type'," tier_type.tiers_id = rent_details.insurance_tier_id " ,"LEFT");
		$this->db->join('ks_gear_order_location AS gear_location',"  gear_location.order_id = rent_details.order_id " ,"LEFT");

		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		if($where!=''){
			$this->db->where($where);
		}
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
		
		$this->db->order_by("rent_details.gear_rent_start_date","desc");
		$this->db->group_by("rent_details.user_gear_rent_detail_id");
		$query = $this->db->get();
			
		return $query;

	}
	public function PaymentSummary($where='')
	{
		
	
		$this->db->select('p.*,p.status As paymnet_status,a.*,a.order_status,a.is_rent_rejected,a.is_rent_cancelled,a.user_gear_rent_detail_id,a.user_gear_rent_id,a.user_gear_desc_id,a.gear_rent_requested_on,a.gear_rent_request_from_date,a.gear_rent_request_to_date,a.gear_total_rent_request_days,a.gear_rent_start_date,a.gear_rent_end_date,a.total_rent_days,a.total_rent_amount,a.is_rent_approved,a.rent_approved_rejected_on,a.is_rent_cancelled,a.rent_calcelled,a.is_payment_completed,a.rent_request_cancelled_by,a.create_user,b.user_gear_rent_id,b.rent_request_sent_by,b.rent_request_sent_to,b.delivery_address_id,b.total_discount,b.other_charges,b.total_rent_amount,c.user_gear_desc_id,c.gear_name,c.gear_description_1,c.gear_description_2,c.model_id,c.app_user_id,c.serial_number,c.replacement_value_aud_ex_gst,c.replacement_value_aud_inc_gst,c.per_day_cost_aud_ex_gst,c.per_day_cost_aud_inc_gst,c.per_weekend_cost_aud_ex_gst,c.per_weekend_cost_aud_inc_gst,c.per_week_cost_aud_ex_gst,c.per_week_cost_aud_inc_gst,c.owners_remark,d.app_username,d.app_user_first_name,d.app_user_last_name,d.owner_type_id,d.user_birth_date,d.australian_business_number,d.user_description,c.gear_list_delist_flag,c.gear_listing_date,c.gear_delisting_date,c.gear_view_count,c.gear_review_count,c.gear_star_rating_avg,c.gear_lending_count,c.is_active,e.model_name,e.model_description,buyer.app_username As buyer_username,buyer.app_user_first_name As buyer_first_name,buyer.app_user_last_name As buyer_last_name ,a.order_id , tbl_refund.amount As refund_amount,tbl_refund.refund_date,tier_type.tier_name,insurance_type.name,tier_type.tiers_percentage,buyer.registered_for_gst,d.registered_for_gst AS owner_gst ');
		$this->db->from("ks_user_gear_payments as p");
		$this->db->join("ks_user_gear_rent_details as a" , "a.order_id =p.gear_order_id","INNER");
		$this->db->join("ks_user_gear_rent_master as b", "a.user_gear_rent_id = b.user_gear_rent_id","inner");
		$this->db->join("ks_user_gear_description as c", "a.user_gear_desc_id = c.user_gear_desc_id","inner");
		$this->db->join("ks_users as d", "c.app_user_id = d.app_user_id","inner");
		$this->db->join("ks_models as e", "c.model_id = e.model_id","inner");
		$this->db->join("ks_users as buyer", "buyer.app_user_id = a.create_user","inner");
		$this->db->join("ks_refund_order as tbl_refund", "tbl_refund.order_id = p.gear_order_id","LEFT");
		$this->db->join('ks_insurance_category_type AS insurance_type'," insurance_type.ks_insurance_category_type_id = a.ks_insurance_category_type_id " ,"LEFT");
		$this->db->join('ks_insurance_tiers AS tier_type'," tier_type.tiers_id = a.insurance_tier_id " ,"LEFT");
		$this->db->where("a.is_payment_completed","Y");
		$this->db->where("a.order_id !=",''	);
		if($where!=''){
			$this->db->where($where);
		}
		//$this->db->limit($limit,$offset);
		$this->db->order_by("a.gear_rent_start_date","desc");
		// $this->db->group_by('p.user_gear_desc_id');
		$this->db->group_by("a.user_gear_rent_detail_id");
		$query = $this->db->get(); 
		return $query;

	}
	 public function OrderListSummary($where='',$limit='',$offset='',$id='')
	{
	
		$this->db->select('rent_details.*,rent_details.order_status,rent_details.is_rent_cancelled,rent_details.user_gear_rent_detail_id,rent_details.user_gear_rent_id,rent_details.user_gear_desc_id,rent_details.gear_rent_requested_on,rent_details.gear_rent_request_from_date,rent_details.gear_rent_request_to_date,rent_details.gear_total_rent_request_days,rent_details.gear_rent_start_date,rent_details.gear_rent_end_date,rent_details.total_rent_days,rent_details.total_rent_amount,rent_details.is_rent_approved,rent_details.rent_approved_rejected_on,rent_details.is_rent_cancelled,rent_details.rent_calcelled,rent_details.is_payment_completed,rent_details.rent_request_cancelled_by,rent_details.create_user,gear.user_gear_rent_id,gear.rent_request_sent_by,gear.rent_request_sent_to,gear.delivery_address_id,gear.total_discount,gear.other_charges,gear.total_rent_amount,gears.user_gear_desc_id,gears.gear_name,gears.gear_description_1,gears.gear_description_2,gears.model_id,gears.app_user_id,gears.serial_number,gears.replacement_value_aud_ex_gst,gears.replacement_value_aud_inc_gst,gears.per_day_cost_aud_ex_gst,gears.per_day_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_week_cost_aud_inc_gst,gears.owners_remark,b.app_username,b.app_user_first_name,b.app_user_last_name,b.owner_type_id,b.user_birth_date,b.australian_business_number,b.user_description,gears.gear_list_delist_flag,gears.gear_listing_date,gears.gear_delisting_date,gears.gear_view_count,gears.gear_review_count,gears.gear_star_rating_avg,gears.gear_lending_count,gears.is_active,users.app_username As buyer_username,users.app_user_first_name As buyer_first_name,users.app_user_last_name As buyer_last_name ,rent_details.order_id,rent_details.project_name,order_payment.user_gear_payment_id,order_payment.status As paymnet_status,rent_details.insurance_amount,rent_details.ks_insurance_category_type_id,rent_details.deposite_status ,tier_type.tier_name,insurance_type.name,tier_type.tiers_percentage ');
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = rent_details.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
		$this->db->join('ks_user_gear_order_description AS gears'," gears.user_gear_desc_id = rent_details.user_gear_desc_id AND gears.order_id = rent_details.order_id  " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.create_user " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payment'," order_payment.gear_order_id = rent_details.order_id " ,"LEFT");
		$this->db->join('ks_insurance_category_type AS insurance_type'," insurance_type.ks_insurance_category_type_id = rent_details.ks_insurance_category_type_id " ,"LEFT");
		$this->db->join('ks_insurance_tiers AS tier_type'," tier_type.tiers_id = rent_details.insurance_tier_id " ,"LEFT");
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		if($where!=''){
			$this->db->where($where);
		}
		// $this->db->limit($limit,$offset);
		$this->db->group_by("rent_details.order_id");
		$this->db->order_by("rent_details.user_gear_rent_detail_id","desc");
		$query = $this->db->get();
			
		return $query;

	}

	public function OrderListItem($where='',$limit='',$offset='',$id='')
	{
	
		$this->db->select('rent_details.*,rent_details.order_status,rent_details.is_rent_cancelled,rent_details.user_gear_rent_detail_id,rent_details.user_gear_rent_id,rent_details.user_gear_desc_id,rent_details.gear_rent_requested_on,rent_details.gear_rent_request_from_date,rent_details.gear_rent_request_to_date,rent_details.gear_total_rent_request_days,rent_details.gear_rent_start_date,rent_details.gear_rent_end_date,rent_details.total_rent_days,rent_details.total_rent_amount,rent_details.is_rent_approved,rent_details.rent_approved_rejected_on,rent_details.is_rent_cancelled,rent_details.rent_calcelled,rent_details.is_payment_completed,rent_details.rent_request_cancelled_by,rent_details.create_user,gear.user_gear_rent_id,gear.rent_request_sent_by,gear.rent_request_sent_to,gear.delivery_address_id,gear.total_discount,gear.other_charges,gear.total_rent_amount,gears.user_gear_desc_id,gears.gear_name,gears.gear_description_1,gears.gear_description_2,gears.model_id,gears.app_user_id,gears.serial_number,gears.replacement_value_aud_ex_gst,gears.replacement_value_aud_inc_gst,gears.per_day_cost_aud_ex_gst,gears.per_day_cost_aud_inc_gst,gears.per_weekend_cost_aud_ex_gst,gears.per_weekend_cost_aud_inc_gst,gears.per_week_cost_aud_ex_gst,gears.per_week_cost_aud_inc_gst,gears.owners_remark,b.app_username,b.app_user_first_name,b.app_user_last_name,b.owner_type_id,b.user_birth_date,b.australian_business_number,b.user_description,gears.gear_list_delist_flag,gears.gear_listing_date,gears.gear_delisting_date,gears.gear_view_count,gears.gear_review_count,gears.gear_star_rating_avg,gears.gear_lending_count,gears.is_active,users.app_username As buyer_username,users.app_user_first_name As buyer_first_name,users.app_user_last_name As buyer_last_name ,rent_details.order_id,rent_details.project_name,order_payment.user_gear_payment_id,order_payment.status As paymnet_status,rent_details.insurance_amount,rent_details.ks_insurance_category_type_id,rent_details.deposite_status ,tier_type.tier_name,insurance_type.name,tier_type.tiers_percentage ');
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = rent_details.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
		$this->db->join('ks_user_gear_order_description AS gears'," gears.order_id = rent_details.order_id AND gears.user_gear_desc_id =rent_details.user_gear_desc_id   " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.create_user " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payment'," order_payment.gear_order_id = rent_details.order_id " ,"LEFT");
		$this->db->join('ks_insurance_category_type AS insurance_type'," insurance_type.ks_insurance_category_type_id = rent_details.ks_insurance_category_type_id " ,"LEFT");
		$this->db->join('ks_insurance_tiers AS tier_type'," tier_type.tiers_id = rent_details.insurance_tier_id " ,"LEFT");
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		if($where!=''){
			$this->db->where($where);
		}
		if (!empty($limit)) {
			$this->db->limit($limit,$offset);
		}
		
		$this->db->order_by("rent_details.user_gear_rent_detail_id","desc");
		$this->db->group_by("rent_details.user_gear_rent_detail_id","desc");
		$query = $this->db->get();
		return $query;

	}

	public function CheckGearSubCategory($db , $where)
	{
		$this->db->select('a.*');	
		$this->db->from('ks_gear_categories As a ');
		$this->db->join('ks_gear_categories AS b' ,'a.gear_category_id = b.gear_sub_category_id' ,'LEFT');
		$this->db->where($where);
		$query = $this->db->get();
		return $query ;
	}
	
	public function getOrderIssueList($order_id)
	{
		$this->db->select('a.*,b.app_user_first_name As create_user_firstname ,b.app_user_last_name As create_user_lastname ,c.app_user_first_name As send_user_firstname ,c.app_user_last_name As send_user_lastname');	
		$this->db->from('ks_order_issues As a ');
		$this->db->join('ks_users  AS b ' ,'a.created_by = b.app_user_id' ,'LEFT');
		$this->db->join('ks_users  AS c ' ,'a.app_user_id = c.app_user_id' ,'LEFT');
		$this->db->where('order_id',$order_id);
		$query = $this->db->get();
		$data =  $query->result();
		return $data ;	
	}
	public function getOrderCheckList($order_id)
	{
		$this->db->select('a.*,b.app_user_first_name As create_user_firstname ,b.app_user_last_name As create_user_lastname' );	
		$this->db->from('ks_order_checklist As a ');
		$this->db->join('ks_users  AS b ' ,'a.created_by = b.app_user_id' ,'LEFT');
		// $this->db->join('ks_users  AS c ' ,'a.app_user_id = c.app_user_id' ,'LEFT');
		$this->db->where('order_id',$order_id);
		$query = $this->db->get();
		$data =  $query->result();
		return $data ;	
	}

	public function GeOrderListRenterList()
	{
		$this->db->select('users.app_username As buyer_username,users.app_user_first_name As buyer_first_name,users.app_user_last_name As buyer_last_name ,rent_details.create_user ');
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = rent_details.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
		$this->db->join('ks_user_gear_description AS gears'," gears.user_gear_desc_id = rent_details.user_gear_desc_id " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.create_user " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payment'," order_payment.gear_order_id = rent_details.order_id " ,"LEFT");
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		
		// $this->db->limit($limit,$offset);
		$this->db->order_by("rent_details.user_gear_rent_detail_id","desc");
		$this->db->group_by("rent_details.create_user");
		return $query = $this->db->get();
	}

	public function GeOrderListOwnerList()
	{
		$this->db->select('b.app_username,b.app_user_first_name,b.app_user_last_name,gears.app_user_id ');
		$this->db->from('ks_user_gear_rent_details As rent_details');
		$this->db->join('ks_user_gear_rent_master as gear' , 'gear.user_gear_rent_id = rent_details.user_gear_rent_id' ,'INNER');
		$this->db->join('ks_rent_status_master as status_master' , 'status_master.ks_rent_status_master_id = gear.ks_rent_status_master_id' ,'INNER');
		$this->db->join('ks_user_gear_description AS gears'," gears.user_gear_desc_id = rent_details.user_gear_desc_id " ,"INNER");
		$this->db->join('ks_users AS b'," b.app_user_id = gears.create_user " ,"INNER");
		$this->db->join('ks_users AS users'," users.app_user_id = rent_details.create_user " ,"INNER");
		$this->db->join('ks_order_master_status AS order_master'," order_master.ks_status_master_id = rent_details.order_status " ,"LEFT");
		$this->db->join('ks_user_gear_payments AS order_payment'," order_payment.gear_order_id = rent_details.order_id " ,"LEFT");
		$this->db->where("rent_details.is_payment_completed","Y");
		$this->db->where("rent_details.order_id !=",''	);
		
		// $this->db->limit($limit,$offset);
		$this->db->order_by("rent_details.user_gear_rent_detail_id","desc");
		$this->db->group_by("b.app_user_id");
		return $query = $this->db->get();
	}

	public function OwnerReviewList($where)
	{
		$this->db->select('a.* ,orders.project_name, a.app_user_id AS app_user_id_given_by , users.app_user_id ,users.app_user_first_name AS app_user_first_name_given_by, users.app_user_last_name AS app_user_last_name_given_by ,users.user_profile_picture_link As user_profile_picture_link_given_by,users.bussiness_name ,users.show_business_name ');
		$this->db->from('ks_cust_gear_reviews AS a');
		$this->db->join('ks_users as users' , 'a.create_user = users.app_user_id' ,'INNER');
		$this->db->join('ks_user_gear_rent_details as orders' , 'orders.order_id = a.order_id' ,'INNER');
		$this->db->join('ks_user_gear_description as gears' , 'gears.app_user_id = a.create_user AND orders.user_gear_desc_id = gears.user_gear_desc_id' ,'INNER');
		$this->db->group_by('a.order_id');
		$this->db->where($where);
		
		$query = $this->db->get();
		return  $query ;
	}

	public function RenterReviewList($where)
	{
		$this->db->select('a.* ,orders.project_name, users.app_user_first_name AS app_user_first_name_given_by, users.app_user_last_name AS app_user_last_name_given_by ,users.user_profile_picture_link As user_profile_picture_link_given_by,,users.bussiness_name ,users.show_business_name ');
		$this->db->from('ks_cust_gear_reviews AS a');
		$this->db->join('ks_users as users' , 'a.create_user = users.app_user_id' ,'INNER');
		$this->db->join('ks_user_gear_rent_details as orders' , 'orders.order_id = a.order_id' ,'INNER');
		$this->db->join('ks_user_gear_description as gears' , 'gears.app_user_id = a.app_user_id AND orders.user_gear_desc_id = gears.user_gear_desc_id' ,'INNER');
		$this->db->where($where);
		$this->db->group_by('a.order_id');
		$query = $this->db->get();
		return  $query ;
	}
	
} /* end of class */
?>