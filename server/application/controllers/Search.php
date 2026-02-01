<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {
	
	 public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email','upload','pagination'));
		$this->load->model(array('common_model','home_model'));
	}
		
		
	public function index(){
	
		
		$data=array();
		$where = " ";
		$URL = '';
		$search_txt = "";
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
		$data['key_word']	= urldecode($this->input->get('key_word'));		

		if(!empty($data['key_word']) ){
				//$where .=  "(a.gear_name LIKE '%".trim($data['key_word'])."%'  OR e.model_name  LIKE '%".trim($data['key_word'])."%'  OR  d.manufacturer_name LIKE  '%".trim($data['key_word'])."%' OR f.gear_category_name LIKE   '%".trim($data['key_word'])."%' )   AND ";
				$search_txt = $data['key_word'];
				
		}
		
		$data['ks_gear_type_id']	= urldecode($this->input->get('ks_gear_type_id'));
		if($data['ks_gear_type_id'] != ''){
			$ks_gear_type =  explode(',', $data['ks_gear_type_id']) ;
				$ks_gear_type_ids = '' ;
				foreach ($ks_gear_type as $gear_type) {
				$ks_gear_type_ids.= "'". $gear_type."',";
				}
				$where .=  "a.ks_gear_type_id IN (".rtrim($ks_gear_type_ids , ',').") AND ";
		}

		// gear category loops start
		$data['gear_category_id']	= urldecode($this->input->get('gear_category_id'));
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
		$data['manufacturer_id']	= urldecode($this->input->get('manufacturer_id'));
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
		$data['model_id']	= urldecode($this->input->get('model_id'));
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
					// print_r($where);die;
		}else{
			// /echo "hello";
			$date['from_date'] =date('Y-m-d');
			$date['to_date']   =date('Y-m-d') ;
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
							 $radius = 5;
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
		
		$lngWest = $this->input->get('lngWest');
		$lngEast = $this->input->get('lngEast');
		$latSouth = $this->input->get('latSouth');
		$latNorth = $this->input->get('latNorth');
		 
		
		if($lngWest!="" && $lngEast!="" && $latSouth!="" && $latNorth!=""){
		
			$postal_arr_list = $this->fetchPostCodesByLatLng($lngWest,$lngEast,$latSouth,$latNorth);
			
			if($postal_arr_list!="")						
				$where .= "u_add.postcode IN (".$postal_arr_list.") AND ";
			else
				$where .= "u_add.postcode ='' AND ";
		
		}
		
		
		/*$data['suburb_name'] = urldecode($this->input->get('suburb_name'));
		if (!empty($data['suburb_name'])) {
			$where .= " u_add.street_address_line1 LIKE  '%".trim($data['suburb_name'])."%' OR u_add.street_address_line2 LIKE  '%".trim($data['suburb_name'])."%'   OR ks_suburbs.suburb_name LIKE  '%".trim($data['suburb_name'])."%' OR ks_states.ks_state_name LIKE  '%".trim($data['suburb_name'])."%'    AND  "; 
		}*/
			
		//echo $where ;
		
		$where .= "a.is_active='Y' AND a.gear_hide_search_results ='Y' AND b.is_active ='Y' ";

		$query = $this->home_model->getSearchList($search_txt,$where,$data['limit'],$offset,$order_by_fld,$order_by);
		
		$query1 = $this->home_model->getSearchList($search_txt,$where); 
		
		//$query = $this->home_model->getModelsList($where,$data['limit'],$offset,$order_by_fld,$order_by);
		//$query1 = $this->home_model->getModelsList($where); 
		
		
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

				//$models_array[]  = $values['model_id'] ;
				//$category_array[]  = $values['ks_category_id'] ;
				$brand_array[]  = $values['ks_manufacturers_id'] ;
				$user_gear_array[]  = $values['user_gear_desc_id'] ;
			}
			// Models data list 
			/*$models_array =   array_unique($models_array) ; 
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
			}*/

			// category List

				/*$query =  $this->common_model->GetAllWhere('ks_gear_categories',  array('is_active' => 'Y' , 'gear_sub_category_id'=>'0' ));
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
				$data['category_data'] = $category_data ;*/
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
				
				$sql =  "SELECT ks_user_address.app_user_id,ks_user_address.lat,ks_user_address.lng FROM ks_gear_location INNER JOIN  ks_user_address ON ks_user_address.user_address_id  =  ks_gear_location.user_address_id  INNER JOIN  ks_states ON   ks_states.ks_state_id = ks_user_address.ks_state_id  INNER JOIN ks_suburbs   ON ks_suburbs.ks_suburb_id = ks_user_address.ks_suburb_id  INNER JOIN ks_user_gear_description As a ON  ks_gear_location.user_gear_desc_id = a.user_gear_desc_id  WHERE ks_gear_location.user_gear_desc_id IN (".rtrim($address_list , ',').") " ;
				
				 if($postal_arr_list!="")
				 	$sql .=  "AND ks_user_address.postcode IN (".$postal_arr_list.")";
					
				
				$address_data = $this->common_model->get_records_from_sql($sql);	
				
					
				
				
				$data['address_data'] = $address_data;

			}else{
				$data['address_data'] = array();
			}


			// print_r(array_unique($brand_array));


			/*if (!empty($data['gear_category_id'])) {
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


					

			}	*/
			
		}
		
		//die;
		echo json_encode($data, true);
	 	
	
	
	}
	
	//To fetch all the Gear Types 
	public function fetchGearType(){
	
		$data=array();
		$where = "ks_gear_type_id !='3' AND is_active='Y'";
		$columns = "ks_gear_type_id,ks_gear_type_name,ks_gear_type_description";

		
		if($this->input->get('ks_gear_type_id')!=""){
			$ks_gear_type_id	= urldecode($this->input->get('ks_gear_type_id'));
			
			$where.=" AND ks_gear_type_id='".$ks_gear_type_id."'";
			
		}

		$query = $this->common_model->GetSpecificValues($columns,'ks_gear_type',$where,'','ks_gear_type_id','ASC') ;	
		$data['gear_type'] = $query->result_array();
		
		echo json_encode($data, true);
	
	}
	
	//To fetch all the Renter Types
	public function fetchRenterType(){
	
		$data=array();
		$where = "is_active='Y'";
		$columns = "ks_renter_type_id,ks_renter_type";

		
		if($this->input->get('ks_renter_type_id')!=""){
			$ks_renter_type_id	= urldecode($this->input->get('ks_renter_type_id'));
			
			$where.=" AND ks_renter_type_id='".$ks_renter_type_id."'";
			
		}

		$query = $this->common_model->GetSpecificValues($columns,'ks_renter_type',$where,'','ks_renter_type_id','ASC');	
		$data['owner_data'] = $query->result_array();
		
		echo json_encode($data, true);
	
	}
	
	//To fetch all the Owner Types
	public function fetchOwnerType(){
	
		$data=array();
		$where = "is_active='Y'";
		$columns = "owner_type_id,owner_type_name";

		
		if($this->input->get('owner_type_id')!=""){
			$owner_type_id	= urldecode($this->input->get('owner_type_id'));
			
			$where.=" AND owner_type_id='".$owner_type_id."'";			
		}

		$query = $this->common_model->GetSpecificValues($columns,'ks_owner_types',$where,'','owner_type_id','ASC');	
		$data['owner_data'] = $query->result_array();
		
		echo json_encode($data, true);
	
	}
	
	public function GetLatLng($address = '')
	{
		
		/*$curl = curl_init();

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
		));*/

		
		$cURLConnection = curl_init();

		curl_setopt($cURLConnection, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key=AIzaSyCPU8keawNREwb4_tHM8D1mcw4bRuSEoUQ');
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($cURLConnection);
		$err = curl_error($cURLConnection);
		curl_close($cURLConnection);

		if ($err) {
		  return "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}


	//Recursive function to find the postcodes
	function fetchPostCodes($lat,$lng,$radius,$level){
	
		$postal_arr=$this->home_model->zipcodeRadius($lat,$lng,$radius);
		$postal_arr_list="";
		
		
		if(count($postal_arr)>5 || $level==200){
		
			if(count($postal_arr)>1){
				$postal_arr_list=implode(",",$postal_arr);
			}else if(count($postal_arr)>0)
				$postal_arr_list=$postal_arr[0];
				
			return $postal_arr_list;
		}
		else{
			$level=$level+5;
			$radius = $radius+5;
			return $this->fetchPostCodes($lat,$lng,$radius,$level);
		}
		
	
	}
	
	//Recursive function to find the postcodes
	function fetchPostCodesByLatLng($lngWest,$lngEast,$latSouth,$latNorth){
	
		$postal_arr= $this->home_model->zipcodeRadiusLatLon($lngWest,$lngEast,$latSouth,$latNorth);

		$postal_arr_list="";
		
		
		if(count($postal_arr)>1){
			$postal_arr_list=implode(",",$postal_arr);
		}else if(count($postal_arr)>0)
			$postal_arr_list=$postal_arr[0];
		
		return $postal_arr_list;
	
	}
	
	//Function to add indexing in the gear description table
	public function add_metaphone(){
		
		$sql = "SELECT `user_gear_desc_id`,`gear_name` FROM `ks_user_gear_description`";
		$res = $this->common_model->get_records_from_sql($sql);
		
		foreach($res as $row){
			
			$sound = '';
			if($row->gear_name!=''){
				
				$words = explode(' ',$row->gear_name);
				foreach($words as $word){
					
					$sound.=metaphone($word)." ";
					
				}
				
			}
			
			$update_data = array("indexing" => $sound);
			
			//Table is updated
			$this->common_model->UpdateRecord($update_data,"ks_user_gear_description","user_gear_desc_id",$row->user_gear_desc_id);
			
		}
		
	}
	
	public function search_text(){
		
		//$search_txt = $_POST['search_text'];
		
		//SELECT MATCH(`gear_name`) AGAINST('GST replacement') AS `relevance`,`gear_name`,`user_gear_desc_id` FROM `ks_user_gear_description` WHERE MATCH(`gear_name`) AGAINST('GST replacement') ORDER BY `relevance` DESC
				
		
		$search_txt = "Lenses";
		$sql_match = "";
		$order_by_match = "";
		$sql_where_match = "";
		$search_text_arr = array();
		$model_search = "";
		$manufacturer_search = "";
		
		$order_by_fld="";
		$limit = "";
		
		$sql = "SELECT ";
		
		if($search_txt!=""){
		
			if(strstr($search_txt," ")){
				
				$search_text_arr = explode(" ",$search_txt);
				
			}else
				$search_text_arr[0]=$search_txt;
			
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
		
		}
		
			$sql.= "a.user_gear_desc_id,a.gear_slug_name,
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
					   d.manufacturer_name,e.model_name,f.gear_category_name 
					   FROM `ks_user_gear_description` a
					   INNER JOIN `ks_users` b ON a.`app_user_id`=b.`app_user_id`
					   INNER JOIN `ks_gear_categories` f ON a.`ks_category_id`=f.`gear_category_id`";
			if ($this->input->get('feature_master_id')) {
			
				$sql.="INNER JOIN `ks_user_gear_features` g_feat ON a.`user_gear_desc_id` = g_feat.`user_gear_desc_id`";
			}else{

				$sql.="LEFT JOIN `ks_user_gear_features` g_feat ON a.`user_gear_desc_id` = g_feat.`user_gear_desc_id`";
			}
					   
			$sql.="LEFT JOIN `ks_manufacturers` d ON ".$manufacturer_search." a.`ks_manufacturers_id`=d.`manufacturer_id`
				   LEFT JOIN `ks_user_gear_images` c ON a.`user_gear_desc_id`=c.`user_gear_desc_id` AND c.`is_deleted` = '0'
				   LEFT JOIN `ks_models` e ON ".$model_search." a.`model_id`=e.`model_id`
				   LEFT JOIN `ks_gear_unavailable` ON a.user_gear_desc_id = ks_gear_unavailable.user_gear_description_id";
				   
			$sql.=" WHERE ";
			
			if(empty($search_txt)==false)
				$sql.="(".$sql_where_match.") AND ";
			
			$sql.="a.`is_deleted`='No'";
			
			if (!empty($limit)) {
				$sql.=" LIMIT ".$offset.",".$limit;
			}
			
			$sql.=" GROUP BY a.`user_gear_desc_id`";
			
			$sql.=" ORDER BY ";
			
			if(empty($search_txt)==true && $order_by_fld =='')
				$sql.="rand()";
			else if(empty($search_txt)==false && $order_by_fld =='')
				$sql.= $order_by_match;
			else
				$sql.="a.".$order_by_fld." ".$order_by;
			
		

			$result = $this->common_model->get_records_from_sql($sql);
			
			echo $sql;
		
			$array = json_decode(json_encode($result), true);	
		
			echo "<pre>";
			print_r($array);
			echo "</pre>";
			
			exit();
			/*$search_string = "";
			$search_text_arr = array();
			
			if(strstr($search_txt," ")){
				
				$search_text_arr = explode(" ",$search_txt);
				
			}else
				$search_text_arr[0]=$search_txt;
			
			foreach($search_text_arr as $string){
				
				$search_string=metaphone($string);
				
				//Database is searched with this search string 
				$sql = "SELECT * FROM `ks_user_gear_description` WHERE `indexing` LIKE '%".$search_string."%'";
				$res = $this->common_model->get_records_from_sql($sql);
				
				echo $this->db->last_query();
				
				echo "<pre>";
				print_r($res);
				echo "</pre>";
				
				
			}*/
		
			//$search_string = substr($search_string,0,-1);
		
		
		
	}
	
	
	//Function to import all gear related data from user_gear_description table to search table
	public function replicateGearData(){
		
		$sql = "SELECT a.*,d.gear_category_name,b.`model_name`,c.`manufacturer_name`,e.gear_category_name AS gear_subcategory_name FROM `ks_user_gear_description` AS a INNER JOIN `ks_gear_categories` AS d ON a.`ks_category_id`=d.`gear_category_id` 
		INNER JOIN `ks_gear_categories` AS e ON a.`ks_sub_category_id`=e.`gear_category_id` 
		LEFT JOIN `ks_models` AS b ON a.`model_id`=b.`model_id` LEFT JOIN `ks_manufacturers` AS c ON a.`ks_manufacturers_id`=c.`manufacturer_id` ";
		$result = $this->common_model->get_records_from_sql($sql);
		
		foreach($result as $row){
			
			//Model name is fetched
			$search_arr = array();
			$search_arr = json_decode(json_encode($row),true);
			$search_arr['model_name'] = $row->model_name;
			$search_arr['manufacturer_name'] = $row->manufacturer_name;
			$search_arr['gear_category_name'] = $row->gear_category_name;
			$search_arr['gear_subcategory_name'] = $row->gear_subcategory_name;
			
			//Record is inserted into the table
			$this->common_model->AddRecord($search_arr,'ks_user_gear_search');			
			
		}		
	}
	
	
	
	
	
}