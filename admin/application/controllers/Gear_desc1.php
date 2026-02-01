<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gear_desc extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text','common_helper'));
		$this->load->library(array('session','form_validation','pagination','email','upload','excel'));
		$this->load->model(array('common_model','mail_model','model'));
		if($this->session->userdata('ADMIN_ID') =='') {
          redirect('login');
		  }
	}
	
	protected $validation_rules = array
        (
		'Add' => array(
            array(
                'field' => 'gear_name',
                'label' => 'Gear Name',
                'rules' => 'trim'
            ),
			array(
                'field' => 'model_name',
                'label' => 'Model',
                'rules' => 'trim'
            ),
			
        ),
    );

	public function index()
	{

	    $data=array();
		
		$where ='';
		
		$limit=10;
		
	
		$data['gear_category_name']		  = $this->input->get('gear_category_type');
				if($data['gear_category_name'] != ''){
				$where .= " WHERE e.gear_category_id = (SELECT b.gear_category_id FROM ks_gear_categories a,ks_user_gear_description b WHERE a.gear_category_name LIKE '%".trim($data['gear_category_name'])."%') AND ";
			}
			
	    $data['model']		  = $this->input->get('model');
				if($data['model'] != ''){
				$where .= " WHERE e.model_id = (SELECT a.model_id FROM ks_models a WHERE a.model_name = '".trim($data['model'])."' ) AND ";
			}
		$data['gear_name']		  = $this->input->get('gear_name');
				if($data['gear_name'] != ''){
					if(empty($where)){
						$where .= " WHERE e.gear_name  LIKE '%".trim($data['gear_name'])."%' AND ";
					}else{
						$where .= "  e.gear_name  LIKE '%".trim($data['gear_name'])."%' AND ";
					}	
			}
		
		$data['ks_gear_type_id'] = $this->input->get('ks_gear_type_id');
			if($data['ks_gear_type_id'] != ''){
				if(empty($where)){
					$where .= " WHERE e.ks_gear_type_id  LIKE '%".trim($data['ks_gear_type_id'])."%' AND ";
				}else{
					$where .= "  e.ks_gear_type_id  LIKE '%".trim($data['ks_gear_type_id'])."%' AND ";
				}	
			}
		$data['app_user_id'] = $this->input->get('app_user_id');
			if($data['app_user_id'] != ''){
				if(empty($where)){
					$where .= " WHERE e.app_user_id  = '".trim($data['app_user_id'])."' AND ";
				}else{
					$where .= "  e.app_user_id  = '".trim($data['app_user_id'])."' AND ";
				}	
			}
			
		$data['ks_category_id'] = $this->input->get('ks_category_id');
		
			if($data['ks_category_id'] != ''){
				if(empty($where)){
					$where .= " WHERE e.ks_category_id  = '".trim($data['ks_category_id'])."' AND ";
				}else{
					$where .= "   e.ks_category_id  = '".trim($data['ks_category_id'])."' AND ";
				}
			}
		if (empty($where)) {
			//$where .= "  WHERE  e.is_deleted  = 'No ' AND "  ;
		}else{

			//$where .= "    e.is_deleted  = 'No ' AND "  ;
		}
			
		$where = substr($where,0,(strlen($where)-4));

		
		if($this->input->get("per_page")!= '')
		{
			$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		 
		if($this->input->get("per_page")!= '')
		{
		$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		$nSer="SELECT e.* , b.model_name ,c.app_user_first_name,c.app_user_last_name ,c.app_username, gear_type.ks_gear_type_name,gear_name.gear_category_name FROM ks_user_gear_description e 
		INNER JOIN ks_models b ON e.model_id = b.model_id
		Left JOIN ks_users c ON e.app_user_id = c.app_user_id 
		LEFT JOIN ks_gear_type AS gear_type ON e.ks_gear_type_id = gear_type.ks_gear_type_id
		LEFT JOIN ks_gear_categories As gear_name ON  e.ks_category_id = gear_name.gear_category_id
		".$where." ORDER BY e.user_gear_desc_id";
		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;

		$config['base_url'] = base_url()."gear_desc/?app_user_id=".$data['app_user_id']."&ks_category_id=".$data['ks_category_id']."&limit=".$limit;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
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
		$data['paginator'] = $paginator; 

//////////////////////////////Pagination config//////////////////////////////////				
		 $query = $this->common_model->GetAllWhere('ks_gear_type',''); 
		 $data['type'] = $query->result();
		 $sql1 = "SELECT DISTINCT (ks_users.app_user_id),ks_users.app_user_first_name,ks_users.app_user_last_name  FROM ks_users INNER JOIN   ks_user_gear_description ON ks_user_gear_description.app_user_id = ks_users.app_user_id " ;
		$query = $this->db->query($sql1);
		$data['app_users'] = $query->result();
		 $query = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_sub_category_id'=> '0')); 
		 $data['category'] = $query->result();
		//echo $this->db->last_query(); exit();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('device/list', $data);
		$this->load->view('common/footer');		

	

	}
	public function add()
	{
		$data=array();
		$models=$this->common_model->GetAllWhere("ks_models",array("is_active"=>'Y'));
		$data['models'] = $models->result();
		foreach ($data['models'] as  $value1) {
			$value_mdals[] = $value1->model_name;
		}
		$data['models1'] =$value_mdals; 
		$models=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y' ,'gear_sub_category_id'=>'0'));
		$data['category'] = $models->result();
		$category =  $models->result();
		if (!empty($category)) {
			
			foreach ($category as  $value) {
				$value_category[]  =  $value->gear_category_name ;
			}
		}
		$data['category_data'] = $value_category ;
		$sub_cat=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y' ,'gear_sub_category_id !='=>'0'));
		$data['category_sub'] = $sub_cat->result();
		$category_sub =  $sub_cat->result();
		//print_r($category_sub);die;
		if (!empty($category_sub)) {
			
			foreach ($category_sub as  $value) {
				$value_category_sub[]  =  $value->gear_category_name ;
			}
		}
		$data['category_sub__data'] = $value_category_sub ;
		$manufacturers=$this->common_model->GetAllWhere("ks_manufacturers",array("is_active"=>'Y' ,));
		$manufacturers= $manufacturers->result(); 
		foreach ($manufacturers as  $value) {
			$values[] = $value->manufacturer_name;
		}
		
		
		//echo "<pre>";
		$data['manufacturers_array']  = $manufacturers;
		$data['manufacturers']  = $values ;
		//print_r($data['manufacturers']);die;
		$query = $this->common_model->GetAllWhere("ks_gear_type",array("is_active"=>'Y'));
		$data['gear_type'] = $query->result();
		
		$users=$this->common_model->GetAllWhere("ks_users",array("is_active"=>'Y'));
			$users  = $users->result();
		$i =0 ;
		foreach ($users  as  $value) {
							
							unset($users[$i]->user_description);
							unset($users[$i]->about_me);
							
							$i++;
		}
		 $data['users'] =$users;
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('device/add', $data);
		$this->load->view('common/footer');	
	}
	private function set_upload_options()
{   
    //upload an image options
    $config = array();
    $config['upload_path'] = ROOT_UPLOAD_IMPORT_PATH.'/site_upload/gear_images/';
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    $config['max_size']      = '0';
    $config['overwrite']     = FALSE;

    return $config;
}
public function resize($image_data) {
	$img = substr($image_data['full_path'], 63);
	$config['image_library'] = 'gd2';
	$config['source_image'] = $image_data['full_path'];
	$config['new_image'] = ROOT_UPLOAD_IMPORT_PATH.'/site_upload/gear_images/new_' . $img;
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

	public function save()
	{
		$data=array();
		$this->load->library('image_lib');
		
		$this->form_validation->set_rules($this->validation_rules['Add']);
		
		//check for manufaturer id
		$query =$this->common_model->GetAllWhere('ks_manufacturers',array('manufacturer_name' => $this->input->post('manufacturer_name')));
		$manufacturers_data = $query->row();
		if (!empty($manufacturers_data)) {
			 $manufacturer_id = $manufacturers_data->manufacturer_id ;
		}else{
			$insert_manufacturers = array(
			'manufacturer_name'=>$this->input->post('manufacturer_name'),
			'create_date'=>date('Y-m-d'),
			'create_user' =>$this->session->userdata('ADMIN_ID'),
			'is_active'=>'Y',
									);
		 	$manufacturer_id = $this->common_model->addRecord('ks_manufacturers' ,$insert_manufacturers);
		//$row['manufacturer_id'] = $manufacturer_id ;
		}
		//check category id 
		$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => $this->input->post('category_name')));
		$category_data = $query->row();
		if (!empty($category_data)) {
			
			$category_id = $category_data->gear_category_id ;
		}else{
			$category_insert = array(
			'gear_category_name' =>$this->input->post('category_name'),
			'gear_sub_category_id'=> '0',
			'security_deposit'=> 'N',
			'average_value' =>'00.00',
			'is_active'=>'Y',
			'create_user'=>$this->session->userdata('ADMIN_ID'),
			'create_date'=>date('Y-m-d')
							);
			$category_id = $this->common_model->addRecord('ks_gear_categories',$category_insert);
		}
		// check sub category
	if (empty($this->input->post('sub_category_name'))) {
			 $sub_category_id = '';
	}else{		 
		$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => $this->input->post('sub_category_name')));
		$category_data = $query->row();
		if (!empty($category_data)) {
			
			$sub_category_id = $category_data->gear_category_id ;
		}else{
			$category_insert = array(
			'gear_category_name' =>$this->input->post('sub_category_name'),
			'gear_sub_category_id'=> $category_id,
			'security_deposit'=> 'N',
			'average_value' =>'00.00',
			'is_active'=>'Y',
			'create_user'=>$this->session->userdata('ADMIN_ID'),
			'create_date'=>date('Y-m-d')
							);
			 $sub_category_id = $this->common_model->addRecord('ks_gear_categories',$category_insert);
		}
	}	
		//check for model id
		$query =$this->common_model->GetAllWhere('ks_models',array('model_name' => $this->input->post('modal_name')));
		$modal_data = $query->row();
		// $this->input->post('modal_name') ;
		//print_r($modal_data);die;
		if(!empty($modal_data)){
			 $model_id = $modal_data->model_id ;
		}else{
			
			$modal_insert = array(
				'model_name'=>$this->input->post('modal_name'),
				'model_description'=> '',
				'manufacturer_id'=> $manufacturer_id,
				'gear_category_id'=> $gear_category_id,
				'gear_sub_category_id'=> $sub_category_id,
				'is_approved'=>'Y',
				'is_active' => 'Y',
				'approved_on'=> date('Y-m-d'),
				'create_date'=> date('Y-m-d'),
				'create_user' => $this->session->userdata('ADMIN_ID'),
				'approved_by_user_id'=>$this->session->userdata('ADMIN_ID'),

						);
		$model_id =	$this->common_model->addRecord('ks_models',$modal_insert);
		}
		//die;
		$models=$this->common_model->GetAllWhere("ks_models",array("is_active"=>'Y'));
		$data['models'] = $models->result();
		
		$users=$this->common_model->GetAllWhere("ks_users",array("is_active"=>'Y'));
		$data['users'] = $users->result();
		
		if($this->form_validation->run())
		{
			//echo $sub_category_id;//die;
			//if ($sub_category_id == '') {
				$row['ks_category_id'] =  $category_id;
			//}else{
				$row['ks_sub_category_id'] =  $sub_category_id;
			//}
			//pri
			if(count($this->input->post('geears_ids')) > 0 ){
				$row['accessories'] =  implode(',',$this->input->post('geears_ids'));	
			}else{
				$row['accessories'] = '';
			}
			if(count($this->input->post('address_id')) > 0 ){
				$row['ks_gear_address_id'] =  implode(',',$this->input->post('address_id'));	
			}else{
				$row['ks_gear_address_id'] = '';
			}
			
			
			$query =$this->common_model->GetAllWhere('ks_settings','');
			$settings =$query->row();
			
				$gst_rate = $settings->gst_percent/100;
		//	print_r($this->input->post();die;
			//print_r($this->input->post());die;
		//$row['model_id']= $this->input->post('model_id');
		$row['gear_name']= $this->input->post('gear_name');
		$row['ks_user_gear_condition']= $this->input->post('condition');
		$row['serial_number']= $this->input->post('serial_number');
		$row['ks_gear_type_id']= $this->input->post('ks_gear_type_id');
		$row['gear_type']= $this->input->post('ks_gear_type_id');
		$row['ks_manufacturers_id'] = $manufacturer_id;
		$row['google_360_link'] = $this->input->post('google_360_link');
		$row['security_deposite'] = $this->input->post('security_deposite');
		$row['model_id'] = $model_id;
		if(!empty($this->input->post('feature_master_id'))){
			$feature_master_id=implode(',', $this->input->post('feature_master_id'));
		}else{
			$feature_master_id= '';	
		}
		$row['feature_master_id'] =$feature_master_id; 
		//$row['gear_description_1']= substr(trim($this->input->post('gear_desc_1')),3,-4);
		//$row['gear_description_2']= substr(trim($this->input->post('gear_desc_2')),3,-4);
		$row['owners_remark']= $this->input->post('owner_remark');
		$row['gear_description_1']= $this->input->post('gear_desc_1');
		$row['per_day_cost_aud_ex_gst']= $this->input->post('per_day_cost_aud_ex_gst');
		$row['per_day_cost_aud_inc_gst']= $this->input->post('per_day_cost_aud_ex_gst') + ( $this->input->post('per_day_cost_aud_ex_gst')*$gst_rate );
		$row['per_weekend_cost_aud_ex_gst']= $this->input->post('per_day_cost_aud_ex_gst')*1;
		$row['per_weekend_cost_aud_inc_gst']= $row['per_weekend_cost_aud_ex_gst'] +( $row['per_weekend_cost_aud_ex_gst']*$gst_rate);
		$row['per_week_cost_aud_ex_gst']= $this->input->post('per_day_cost_aud_ex_gst')*3;
		$row['per_week_cost_aud_inc_gst']= $row['per_week_cost_aud_ex_gst'] +( $row['per_week_cost_aud_ex_gst']*$gst_rate);
		$row['replacement_value_aud_ex_gst']= $this->input->post('replacement_value_aud_ex_gst');
		$row['replacement_value_aud_inc_gst']= $this->input->post('replacement_value_aud_ex_gst') + ($this->input->post('replacement_value_aud_ex_gst')*$gst_rate);
		$row['create_user']= $this->session->userdata('ADMIN_ID');
		$row['app_user_id']= $this->input->post('app_user_id');
		if($this->input->post('status')=="Active")
		{
			$row['is_active']= 'Y';
		}
		else
		{
			$row['is_active']= 'N';	
		}
		$row['create_date']= date('Y-m-d') ;
		
		$value = $this->common_model->addRecord('ks_user_gear_description',$row);
		if(count($this->input->post('unavailble_dates')) > 0 ) {
			foreach($this->input->post('unavailble_dates') AS $unavailble_dates){
				$date_array =  explode( '-' ,$unavailble_dates);
				$from_date_array =  explode('/',$date_array[0]);
				 $from_date =  trim($from_date_array[2]).'-'.trim($from_date_array[0]).'-'.trim($from_date_array[1]);
				$to_date_array =  explode('/',$date_array[1]);
				 $to_date =  trim($to_date_array[2]).'-'.trim($to_date_array[0]).'-'.trim($to_date_array[1]);
				
				$insert_data3 = array(
									'user_gear_description_id'=> $value,
									'unavailability_reason'=>'',
									'unavailable_from_date' =>$from_date ,
									'unavailable_to_date'=>$to_date ,
									'create_user'=> $this->session->userdata('ADMIN_ID'),
									'create_date'=>date('Y-m-d')
										
								);	
					$this->common_model->addRecord('ks_gear_unavailable',$insert_data3);		
					//echo $this->db->last_query();	
 				//die;
			}
			
		}
		if(count($this->input->post('address_id')) > 0 ){ 
				$address_array =  $this->input->post('address_id') ;
				foreach ($address_array as  $address) {
					$address_insert =  array(
											'user_gear_desc_id' => $value,
											'user_address_id'=> $address,
											'is_active' => 'Y',
											'create_date'=> date('Y-m-d'),
											'create_user'=> $this->session->userdata('ADMIN_ID'),

										 );
				$this->common_model->addRecord( 'ks_gear_location' ,$address_insert);
				}
				
			}else{
				$row['ks_gear_address_id'] = '';
			}
			
		//die;
		//print_r($value);die;
		if (count($_FILES['images']) > 0 ) {
					$this->load->library('upload');
				    $dataInfo = array();
				    $files = $_FILES;
				    $cpt = count($_FILES['images']['name']);
			 if ($_FILES['images']['name'][0] != '') {
				    for($i=0; $i<$cpt; $i++)
				    {           
				        $_FILES['image']['name']= time().$files['images']['name'][$i];
				        $_FILES['image']['type']= $files['images']['type'][$i];
				        $_FILES['image']['tmp_name']= $files['images']['tmp_name'][$i];
				        $_FILES['image']['error']= $files['images']['error'][$i];
				        $_FILES['image']['size']= $files['images']['size'][$i];    
				        $this->upload->initialize($this->set_upload_options());
				        $this->upload->do_upload('image');
				        $dataInfo = $this->upload->data();
				        $image_data = $this->upload->data();
				        if ($image_data['image_width'] > '300' || $image_data['image_width'] > 300 ) {
				        	$dataInfo1 = $this->resize($image_data);
				        	//  unlink($dataInfo['full_path']);
				        	  $image =  $dataInfo['file_name'] ;
				       
				        }else{

				        	$image =  $dataInfo['file_name'] ; 
				        }
				       
				         $data_images = array(
				         					'model_id' => $row['model_id'],
				         					'gear_display_image' =>$image ,
				         					'user_gear_desc_id'=> $value,
				         					'is_active'=> 'Y',
				         					'create_user'=> $this->session->userdata('ADMIN_ID'),
				         					'create_date'=>date('Y-m-d')
				         				);
				         $this->common_model->addRecord('ks_user_gear_images',$data_images);
				    }
				     
			 }	 
								
		}
		if (!empty($modal_data->model_image)) {
			// if (file_exists(ROOT_UPLOAD_IMPORT_PATH.'/site_upload/model_images/'.$modal_data->model_image )) {
			// 	$response_image =  $this->cpoyimage($modal_data->model_image);
	 	// 		if ($response_image['message'] == '1' ) {
	 	// 				# code...
	 					
	 	// 	 		 $data_images = array(

	  //        					'model_id' => $row['model_id'],
	  //        					'gear_display_image' =>$response_image['name_image'] ,
	  //        					'user_gear_desc_id'=> $value,
	  //        					'type' =>'model' ,
	  //        					'is_active'=> 'Y',
	  //        					 'create_user'=> $this->session->userdata('ADMIN_ID'),
	  //        					 'create_date'=>date('Y-m-d')
	  //        				);
			// 				         $this->common_model->addRecord('ks_user_gear_images',$data_images);
			// 	}	
			// }
		}
			$feature_details_id = 	$this->input->post('feature_details_id') ;
		if (count($feature_details_id) > 0 ) {
		 		for ($i=0; $i < count($feature_details_id ); $i++) { 
		 			
		 			$value_insert = array(
		 								'user_gear_desc_id'=>$value ,
		 								'feature_details_id	'=>$feature_details_id[$i],
		 								'is_active'=>'Y',
		 								'create_user'=> $this->session->userdata('ADMIN_ID'),
		 								'create_date'=>date('Y-m-d')
		 							);
		 								$this->common_model->addRecord('ks_user_gear_features',$value_insert);
		 		}
		}
			
		$message = '<div class="alert alert-success">Gear has been successfully added.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('gear_desc');
						
		}
		else
		{
		//	echo "bye";die;
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');					
			$this->load->view('device/add', $data);
			$this->load->view('common/footer');	
		}
	
	}
	public function upload()
	{
		$data=array();
		
		$id = $this->uri->segment(3);
		$where_array = array('user_gear_desc_id'=>$id);
		$device= $this->model->device($where_array);
		$data['result'] = $device->result();
		
		$gear_categories=$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y'));
		$data['gear_categories'] = $gear_categories->result();
		
			
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('device/uploadpic', $data);
		$this->load->view('common/footer');		
	}
	public function do_upload() 
	{ 
		 $id=$this->input->post("user_gear_desc_id");
		 $newname= time();
    echo $config['upload_path']   = $_SERVER['DOCUMENT_ROOT'].'/site_upload/gear_images/' ; 
         $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
         $config['max_size']      = ""; 
         $config['max_width']     = ""; 
         $config['max_height']    = "";  
		 $config['file_name']     = $newname;
         $this->load->library('upload', $config);
		 $imgdata = array('upload_data' => $this->upload->data());
		 $qu=$this->common_model->GetAllWhere('ks_user_gear_description',array("user_gear_desc_id"=>$id));
		 $result=$qu->result();
		 $model_id=$result[0]->model_id;
		 $count=$this->common_model->countAll("ks_user_gear_images",array("is_active"=>"Y","user_gear_desc_id"=>$id));
		
         if ( ! $this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors()); 
            print_r($error);die;
            $this->load->view('device/uploadpic', $error); 
         }
			
         else { 
            $data = array('upload_data' => $this->upload->data());
            $this->load->view('device/upload_success', $data); 
			$row['user_gear_desc_id']=$id;
			$row['model_id']=$model_id;
			if($this->input->post('gear_display_seq_id')=='')
			$row['gear_display_seq_id']=$count+1;
			$row['is_active']=$result[0]->is_active;
			$row['create_user']= $this->session->userdata('ADMIN_ID');
			$row['create_date']= date('Y-m-d');
			//$row['gear_display_image'] = $this->upload->data('file_name');
			$row['gear_display_image'] = $data['upload_data']['file_name'];
			
			$value = $this->common_model->addRecord('ks_user_gear_images',$row);
			
			$message = '<div class="alert alert-success">Image has been successfully added.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('gear_desc/upload/'.$id);
         } 
    } 
	public function edit()
	{
		$data=array();
		
		$id = $this->uri->segment(3);
		$where_array = array('user_gear_desc_id'=>$id);
		$device= $this->model->device($where_array);
		$data['result'] = $device->result();
		
		 $sql = " SELECT address.*, s.ks_state_name , subs.suburb_name FROM  ks_user_address As address LEFT JOIN ks_states As s ON address.ks_state_id = s.ks_state_id LEFT JOIN ks_suburbs As subs ON address.ks_suburb_id = subs.ks_suburb_id  WHERE  address.app_user_id = '".$data['result'][0]->app_user_id."'";
		 $address_array = $this->common_model->get_records_from_sql($sql);
		
		$data['address_array'] = $address_array;
		
	$models=$this->common_model->GetAllWhere("ks_gear_unavailable",array("user_gear_description_id"=>$id , 'unavailable_to_date > ' => date('Y-m-d')));
		$data['unavailable_data'] = $models->result();
 
 
 
		$models=$this->common_model->GetAllWhere("ks_user_gear_images",array("user_gear_desc_id"=>$id));
		$data['images'] = $models->result();

		$models=$this->common_model->GetAllWhere("ks_models",array("is_active"=>'Y'));
		$data['models'] = $models->result();
		
		

		$query = $this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y'));
		$data['gear_list'] = $query->result();

		$query = $this->common_model->GetAllWhere("ks_gear_type",array("is_active"=>'Y'));
		$data['gear_type'] = $query->result();

		$users=$this->common_model->GetAllWhere("ks_users",array("is_active"=>'Y'));
		$users = $users->result();


		foreach ($data['models'] as  $value1) {
			$value_mdals[] = $value1->model_name;
		}
		$data['models1'] =$value_mdals; 
		$models=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y' ,'gear_sub_category_id'=>'0'));
		$data['category'] = $models->result();
		$category =  $models->result();
		if (!empty($category)) {
			
			foreach ($category as  $value) {
				$value_category[]  =  $value->gear_category_name ;
			}
		}
		$data['category_data'] = $value_category ;
		$sub_cat=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y' ,'gear_sub_category_id !='=>'0'));
		$data['category_sub'] = $sub_cat->result();
		$category_sub =  $sub_cat->result();
		//print_r($category_sub);die;
		if (!empty($category_sub)) {
			
			foreach ($category_sub as  $value) {
				$value_category_sub[]  =  $value->gear_category_name ;
			}
		}
		$data['category_sub__data'] = $value_category_sub ;
		$manufacturers=$this->common_model->GetAllWhere("ks_manufacturers",array("is_active"=>'Y' ,));
		$manufacturers= $manufacturers->result(); 
		foreach ($manufacturers as  $value) {
			$values[] = $value->manufacturer_name;
		}
		$query =    $this->common_model->GetAllWhere('ks_feature_master',array('gear_category_id'=>$data['result'][0]->ks_category_id));
		$data['feature_master']  = $query->result();
		$query =    $this->common_model->GetAllWhere('ks_gear_feature_details','');
		$data['feature_details']  = $query->result();

		$query =    $this->common_model->GetAllWhere('ks_user_gear_features',array('user_gear_desc_id'=>$data['result'][0]->user_gear_desc_id));
		$user_feature_details  = $query->result();
		if (!empty($user_feature_details)) {
			foreach ($user_feature_details as $value) {

				$va[] = $value->feature_details_id;
			}
			$data['user_feature_details'] = $va;
		}else{
			$data['user_feature_details'] = array();
		}
			$data['manufacturers_array']  = $manufacturers;
		$data['manufacturers']  = $values ;
		$i =0 ;
		foreach ($users  as  $value) {
							
							unset($users[$i]->user_description);
							unset($users[$i]->about_me);
							
							$i++;
		}
		 $data['users'] =$users;
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('device/edit', $data);
		$this->load->view('common/footer');		
	}
	
	public function view()
	{
		$data=array();
		
		$id = $this->uri->segment(3);
		$where_array = array('user_gear_desc_id'=>$id);
		$device= $this->model->device($where_array);
		$data['result'] = $device->result();
		
		$models=$this->common_model->GetAllWhere("ks_models",array("is_active"=>'Y'));
		$data['models'] = $models->result();
		
		$users=$this->common_model->GetAllWhere("ks_users",array("is_active"=>'Y'));
		$data['users'] = $users->result();
		
	    $model_id = $data['result'][0]->model_id;
		$models1=$this->common_model->GetAllWhere("ks_models",array("model_id"=>$model_id));
		$data['models1'] = $models1->result();
		
		 $manufacturer_id = $data['models1'][0]->manufacturer_id;
		 $manufac=$this->common_model->GetAllWhere("ks_manufacturers",array("manufacturer_id"=>$manufacturer_id));
		 $data['manufac'] = $manufac->result();
		 // echo $data['manufac'][1]->manufacturer_name;
		 //echo $this->db->last_query();
		//exit();
		
		
		$where = " ";
		$limit=10;
		
		
		$where .=  " where a.user_gear_desc_id =".$id;
		
		
		if($this->input->get("per_page")!= '')
		{
		$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		
		$nSer="SELECT a.user_gear_desc_id,a.user_gear_cancel_policy_id,a.user_gear_desc_id,a.user_gear_cancel_policy,a.user_gear_cancel_price,a.is_active,a.create_date 
			   FROM ks_user_gear_cancel_policy a
			   INNER JOIN ks_user_gear_description as b ON a.user_gear_desc_id=b.user_gear_desc_id
			  ".$where." ORDER BY b.user_gear_desc_id DESC";
		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result1'] = $result->result();
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
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
//////////////////////////////Pagination config//////////////////////////////////				
		
		
		//print_r($data['result']);die;
		
		$data['paginator'] = $paginator;
			
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('device/view', $data);
		$this->load->view('common/footer');		
	}
	
	
	public function update()
	{
		$data = array();
		$id=$this->input->post('user_gear_desc_id');
		
		
		if(count($this->input->post('unavailble_dates')) > 0 ) {
			$i=0 ;
			
			foreach($this->input->post('unavailble_dates') AS $unavailble_dates){
				$date_array =  explode( '-' ,$unavailble_dates);
				$from_date_array =  explode('/',$date_array[0]);
				 $from_date =  trim($from_date_array[2]).'-'.trim($from_date_array[0]).'-'.trim($from_date_array[1]);
				$to_date_array =  explode('/',$date_array[1]);
				 $to_date =  trim($to_date_array[2]).'-'.trim($to_date_array[0]).'-'.trim($to_date_array[1]);
				  $ks_gear_unavailable_id = $_POST['ks_gear_unavailable_id'][$i];
				
				$insert_data3 = array(
									'user_gear_description_id'=> $id,
									'unavailability_reason'=>'',
									'unavailable_from_date' =>$from_date ,
									'unavailable_to_date'=>$to_date ,
									'create_user'=> $this->session->userdata('ADMIN_ID'),
									'create_date'=>date('Y-m-d')
										
								);
				if($_POST['ks_gear_unavailable_id'][$i] == ''){
					$this->common_model->addRecord('ks_gear_unavailable',$insert_data3);
				}else{
					$this->db->where('ks_gear_unavailable_id', $ks_gear_unavailable_id);
					$this->db->update('ks_gear_unavailable',$insert_data3);
				}								
							
					//echo $this->db->last_query();	
 				//die;
				$i++;
			}
			
		}
		
		//die;
		$this->form_validation->set_rules($this->validation_rules['Add']);
		// check  manufacturer_name 
			$query =$this->common_model->GetAllWhere('ks_manufacturers',array('manufacturer_name' => $this->input->post('manufacturer_name')));
		$manufacturers_data = $query->row();
		if (!empty($manufacturers_data)) {
			 $manufaturer_id = $manufacturers_data->manufacturer_id ;
		}else{
			$insert_manufacturers = array(
			'manufacturer_name'=>$this->input->post('manufacturer_name'),
			'create_date'=>date('Y-m-d'),
			'create_user' =>$this->session->userdata('ADMIN_ID'),
			'is_active'=>'Y',
									);
		 	$manufaturer_id = $this->common_model->addRecord('ks_manufacturers' ,$insert_manufacturers);
		//$row['manufacturer_id'] = $manufacturer_id ;
		}

		// category check
		//check category id 
		$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => $this->input->post('category_name')));
		$category_data = $query->row();
		if (!empty($category_data)) {
			
			$category_id = $category_data->gear_category_id ;
		}else{
			$category_insert = array(
			'gear_category_name' =>$this->input->post('category_name'),
			'gear_sub_category_id'=> '0',
			'security_deposit'=> 'N',
			'average_value' =>'00.00',
			'is_active'=>'Y',
			'create_user'=>$this->session->userdata('ADMIN_ID'),
			'create_date'=>date('Y-m-d')
							);
			$category_id = $this->common_model->addRecord('ks_gear_categories',$category_insert);
		}
		// check sub category
			 
		$query =$this->common_model->GetAllWhere('ks_gear_categories',array('gear_category_name' => $this->input->post('sub_category_name')));
		$category_data = $query->row();
		if (!empty($category_data)) {
			
			$sub_category_id = $category_data->gear_category_id ;
		}else{
			$category_insert = array(
			'gear_category_name' =>$this->input->post('sub_category_name'),
			'gear_sub_category_id'=> $category_id,
			'security_deposit'=> 'N',
			'average_value' =>'00.00',
			'is_active'=>'Y',
			'create_user'=>$this->session->userdata('ADMIN_ID'),
			'create_date'=>date('Y-m-d')
							);
			 $sub_category_id = $this->common_model->addRecord('ks_gear_categories',$category_insert);
		}
		// check models
		$query =$this->common_model->GetAllWhere('ks_models',array('model_name' => $this->input->post('modal_name')));
		$modal_data = $query->row();
		// $this->input->post('modal_name') ;
		//print_r($modal_data);die;
		if(!empty($modal_data)){
			 $model_id = $modal_data->model_id ;
		}else{
			if (!empty($sub_category_id)) {
				$gear_category_id = $sub_category_id;
			}else{
				$gear_category_id = $category_id;
			}
			$modal_insert = array(
				'model_name'=>$this->input->post('modal_name'),
				'model_description'=> '',
				'manufacturer_id'=> $manufacturer_id,
				'gear_category_id'=> $gear_category_id,
				'is_approved'=>'Y',
				'is_active' => 'Y',
				'approved_on'=> date('Y-m-d'),
				'create_date'=> date('Y-m-d'),
				'create_user' => $this->session->userdata('ADMIN_ID'),
				'approved_by_user_id'=>$this->session->userdata('ADMIN_ID'),

						);
		$model_id =	$this->common_model->addRecord('ks_models',$modal_insert);
		}
		
		$models=$this->common_model->GetAllWhere("ks_models",array("is_active"=>'Y'));
		$data['models'] = $models->result();
		
		$users=$this->common_model->GetAllWhere("ks_users",array("is_active"=>'Y'));
		$data['users'] = $users->result();

		if($this->form_validation->run() == true )
		{
	
	  		
				$row['ks_category_id'] =  $category_id;
			
				$row['ks_sub_category_id'] =  $sub_category_id;
			
			if(count($this->input->post('geears_ids')) > 0 ){
				$row['accessories'] =  implode(',',$this->input->post('geears_ids'));	
			}else{
				$row['accessories'] = '';
			}
			
			if(count($this->input->post('address_id')) > 0 ){
				$row['ks_gear_address_id'] =  implode(',',$this->input->post('address_id'));	
			}else{
				$row['ks_gear_address_id'] = '';
			}
			
			$query =$this->common_model->GetAllWhere('ks_settings','');
			$settings =$query->row();
			
				 $gst_rate = $settings->gst_percent/100; 
			if (!empty($this->input->post('feature_master_id'))) {
				$feature_master_id =   implode(',', $this->input->post('feature_master_id')) ;
			}else{
				$feature_master_id =  '';
			}	

		$row['model_id']= $model_id;
		$row['gear_name']= $this->input->post('gear_name');
		$row['ks_manufacturers_id'] = $manufaturer_id;
		$row['ks_user_gear_condition']= $this->input->post('condition');
		$row['serial_number']= $this->input->post('serial_number');
		$row['ks_gear_type_id']= $this->input->post('ks_gear_type_id');
		$row['google_360_link']= $this->input->post('google_360_link');
		$row['security_deposite'] = $this->input->post('security_deposite');
		$row['feature_master_id']= $feature_master_id;

		//$row['gear_description_1']= substr(trim($this->input->post('gear_desc_1')),3,-4);
		//$row['gear_description_2']= substr(trim($this->input->post('gear_desc_2')),3,-4);
		$row['owners_remark']= htmlspecialchars($this->input->post('owner_remark'));
		$row['gear_description_1']= htmlspecialchars($this->input->post('gear_desc_1'));
		$row['per_day_cost_aud_ex_gst']= $this->input->post('per_day_cost_aud_ex_gst');
		$row['per_day_cost_aud_inc_gst']= $this->input->post('per_day_cost_aud_ex_gst') + ( $this->input->post('per_day_cost_aud_ex_gst')*$gst_rate );
		$row['per_weekend_cost_aud_ex_gst']= $this->input->post('per_day_cost_aud_ex_gst')*1;
		$row['per_weekend_cost_aud_inc_gst']= $row['per_weekend_cost_aud_ex_gst'] +( $row['per_weekend_cost_aud_ex_gst']*$gst_rate);
		$row['per_week_cost_aud_ex_gst']= $this->input->post('per_day_cost_aud_ex_gst')*3;
		$row['per_week_cost_aud_inc_gst']= $row['per_week_cost_aud_ex_gst'] +( $row['per_week_cost_aud_ex_gst']*$gst_rate);
		$row['replacement_value_aud_ex_gst']= $this->input->post('replacement_value_aud_ex_gst');
		$row['replacement_value_aud_inc_gst']= $this->input->post('replacement_value_aud_ex_gst') + ($this->input->post('replacement_value_aud_ex_gst')*$gst_rate);
		$row['update_user']= $this->session->userdata('ADMIN_ID');
		$row['app_user_id']= $this->input->post('app_user_id');
		if($this->input->post('status')=="Active")
		{
			$row['is_active']= 'Y';
		}
		else
		{
			$row['is_active']= 'N';	
		}
		// echo "<pre>";
		// print_r($row);//die;
		$row['update_date']= date('Y-m-d') ;
		$this->db->where('user_gear_desc_id', $id);
		$this->db->update('ks_user_gear_description', $row); 
		// die;
		
		if ($_FILES['images']['name'][0] != '' ) {
					$this->load->library('upload');
				    $dataInfo = array();
				    $files = $_FILES;
				    $cpt = count($_FILES['images']['name']);
				    //$this->common_model->delete('ks_user_gear_images', $id  ,'user_gear_desc_id');
				  //  echo $this->db->last_query();
				    for($i=0; $i<$cpt; $i++)
			    {     
			          	$_FILES['image']['name']= time().$files['images']['name'][$i];
				        $_FILES['image']['type']= $files['images']['type'][$i];
				        $_FILES['image']['tmp_name']= $files['images']['tmp_name'][$i];
				        $_FILES['image']['error']= $files['images']['error'][$i];
				        $_FILES['image']['size']= $files['images']['size'][$i];    
				        $this->upload->initialize($this->set_upload_options());
				        $this->upload->do_upload('image');
				        $dataInfo = $this->upload->data();
				        $image_data = $this->upload->data();
				        if ($image_data['image_width'] > '300' || $image_data['image_width'] > 300 ) {
				        	//$dataInfo1 = $this->resize($image_data);
				        	//  unlink($dataInfo['full_path']);
				        	  $image = $dataInfo['file_name'] ; 
				       
				        }else{

				        	$image =  $dataInfo['file_name'] ; 
				        }
				        $errors = $this->upload->display_errors();
				        if (empty( $errors)) {
				        		
				        	 $data_images = array(

				         					'model_id' => $this->input->post('model_id'),
				         					'gear_display_image' =>$image ,
				         					'user_gear_desc_id'=> $id ,
				         					'is_active'=> 'Y',
				         					 'create_user'=> $this->session->userdata('ADMIN_ID'),
				         					 'create_date'=>date('Y-m-d')
				         				);
				         $this->common_model->addRecord('ks_user_gear_images',$data_images);
				        }
				    }
			}
		$query = $this->common_model->GetAllWhere('ks_user_gear_images',array('user_gear_desc_id'=>$id , 'model_id'=> $modal_data->model_id ,'type'=>'model' ) )	;
		$check_modal_images	   = $query->row();
	
		if (!empty($check_modal_images)) {
			# code...
		}else{
			// if (file_exists(ROOT_UPLOAD_IMPORT_PATH.'/site_upload/model_images/'.$modal_data->model_image )) {
			// 	$response_image =  $this->cpoyimage($modal_data->model_image);
	 	// 		if ($response_image['message'] == '1' ) {
	 	// 				# code...
	 					
	 	// 	 		 $data_images = array(

	  //        					'model_id' => $row['model_id'],
	  //        					'gear_display_image' =>$response_image['name_image'] ,
	  //        					'type' =>'model' ,
	  //        					'user_gear_desc_id'=> $id,
	  //        					'is_active'=> 'Y',
	  //        					 'create_user'=> $this->session->userdata('ADMIN_ID'),
	  //        					 'create_date'=>date('Y-m-d')
	  //        				);
			// 				         $this->common_model->addRecord('ks_user_gear_images',$data_images);
			// 	}	
			// }
		}	
			$feature_details_id = 	$this->input->post('feature_details_id') ;
		if (count($feature_details_id) > 0 ) {
		 		for ($i=0; $i < count($feature_details_id ); $i++) { 
		 			if ($i== 0) {
		 				$this->common_model->delele('ks_user_gear_features','user_gear_desc_id' ,$id );
		 			}
		 			$value_insert = array(
		 								'user_gear_desc_id'=>$id ,
		 								'feature_details_id	'=>$feature_details_id[$i],
		 								'is_active'=>'Y',
		 								'create_user'=> $this->session->userdata('ADMIN_ID'),
		 								'create_date'=>date('Y-m-d')
		 							);
		 								$this->common_model->addRecord('ks_user_gear_features',$value_insert);
		 		}
		}
		//	die;
		$message = '<div class="alert alert-success"><p>Gear updated successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('gear_desc');
	
		}
		else
		{	
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('device/edit', $data);
			$this->load->view('common/footer');	
		}
	
	}
	
	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id)
			{
	        
			$where_array = array('user_gear_desc_id'=>$id);
			$image= $this->common_model->get_all_record('ks_user_gear_description',$where_array);
			
			/*foreach($image as $im){
				$this->load->helper("file");
				$oldfile = MODEL_IMAGE."/".$im->model_image;
				unlink($oldfile);
			}*/
			
     		$this->common_model->delele('ks_user_gear_description','user_gear_desc_id',$id);
			
    	}
		$message = '<div class="alert alert-success"><p>Gears have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('gear_desc');
		}
	}
	
	public function select_delete_image()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
			$id1 = $this->input->post('user_gear_desc_id');
			$where_array = array('user_gear_desc_id'=>$id1);
			$device= $this->model->device($where_array);
			$data['result'] = $device->result();
		
			$gear_categories=$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y'));
			$data['gear_categories'] = $gear_categories->result();
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id)
			{
	        
			$where_array = array('user_gear_image_id'=>$id);
			$image= $this->common_model->get_all_record('ks_user_gear_images',$where_array);
			
			$sqldel1="SELECT gear_display_image FROM ks_user_gear_images WHERE user_gear_image_id=".$id;
			$resultdel1=$this->db->query($sqldel1);
			foreach($resultdel1->result() as $rowdel1)
			{
				$filename = $rowdel1->gear_display_image;
				if (file_exists($filename)) {
					$url_b=base_url().GEAR_IMAGE."/".$filename;
					unlink($filename);
				}
			}
			
     		$this->common_model->delele('ks_user_gear_images','user_gear_image_id',$id);
			
			}
		$message = '<div class="alert alert-success"><p>Images have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('device/uploadpic', $data);
		$this->load->view('common/footer');	
		}
	}
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		$where_array = array('user_gear_desc_id'=>$id);
		$image= $this->common_model->get_all_record('ks_user_gear_description',$where_array);
		/*foreach($image as $im){
				$this->load->helper("file");
				$oldfile = MODEL_IMAGE."/".$im->model_image;
				unlink($oldfile);
			}*/
		
		$this->common_model->delele('ks_user_gear_description','user_gear_desc_id',$id);
		
		$message = '<div class="alert alert-success"><p>Gear has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('gear_desc');
	}
	public function delete_record_image()
	{
		$id1 = $_GET['id1'];
		$id=$_GET['id'];
		$where_array = array('user_gear_image_id'=>$id);
		$where_array1 = array('user_gear_desc_id'=>$id1);
		$image= $this->common_model->get_all_record('ks_user_gear_images',$where_array);
		/*foreach($image as $im){
				$this->load->helper("file");
				$oldfile = MODEL_IMAGE."/".$im->model_image;
				unlink($oldfile);
			}*/
		$sqldel1="SELECT gear_display_image FROM ks_user_gear_images WHERE user_gear_image_id=".$id;
		$resultdel1=$this->db->query($sqldel1);
		foreach($resultdel1->result() as $rowdel1)
		{
			$filename = $rowdel1->gear_display_image;
			if (file_exists($filename)) {
				$url_b=base_url().GEAR_IMAGE."/".$filename;
				unlink($filename);
			}
		}
		$this->common_model->delele('ks_user_gear_images','user_gear_image_id',$id);
		$device= $this->model->device($where_array1);
		$data['result'] = $device->result();
		
		$gear_categories=$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y'));
		$data['gear_categories'] = $gear_categories->result();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('device/uploadpic', $data);
		$this->load->view('common/footer');	
	}
	// public function getsubcategory()
	// {
	// 	$category_id = $this->input->post('category_id');


	// 	$query = $this->common_model->GetAllWhere('ks_gear_categories',array('is_active', 'gear_category_name'=>$category_id));
	// 	$gear_name = $query->row();


	// 	$query = $this->common_model->GetAllWhere('ks_gear_categories',array('is_active', 'gear_sub_category_id'=>$gear_name->gear_category_id));
	// 	$result = $query->result();
	// 	if(!empty($result)){
	// 		$html = '';
	// 		$html .= '<option value="">--Select Sub Category--</option>';
	// 		foreach ($result as  $value) {
	// 			$html .= '<option value="'.$value->gear_category_name.'" >'.$value->gear_category_name.'</option>';
	// 		}
	// 	}else{
	// 		$html = '<option value="">No sub category found </option>';
	// 	}
	// 	echo $html;
	// }
		public function getsubcategory()
	{
		$category_id = stripslashes($this->input->post('category_id'));
		$data= array();
		$query = $this->common_model->GetAllWhere('ks_gear_categories',array('is_active', 'gear_category_name'=>$category_id));
		$gear_name = $query->row();
		$query = $this->common_model->GetAllWhere('ks_gear_categories',array('is_active', 'gear_sub_category_id'=>$gear_name->gear_category_id));
		$data['result'] = $query->result();
		$query1 = $this->common_model->GetAllWhere('ks_feature_master',array('is_active', 'gear_category_id'=>$gear_name->gear_category_id));
		$data['feature_result'] = $query1->result();
		// if(!empty($result)){
		// 	$html = '';
		// 	$html .= '<option value="">--Select Sub Category--</option>';
		// 	foreach ($result as  $value) {
		// 		$html .= '<option value="'.$value->gear_category_name.'" >'.$value->gear_category_name.'</option>';
		// 	}
		// 	$data['html'] = $html;
		// }else{
		// 	$html = '<option value="">No sub category found </option>';
		// 	$data['html'] = $html;
		// }
		// if(!empty($feature_result)){
		// 	$html1 = '';
		// 	$html1 .= '<option value="">--Select Sub Category--</option>';
		// 	foreach ($feature_result as  $value1) {
		// 		$html1 .= '<option value="'.$value1->feature_master_id.'" >'.$value1->feature_name.'</option>';
		// 	}
		// 	$data['html1'] = $html1;
		// }else{
		// 	$html1 = '<option value="">No sub category found </option>';
		// 	$data['html1'] = $html1;
		// }
		echo json_encode($data);

	}
	public function getgearsList($app_user_id)
	{
		$sql = "SELECT ks_user_gear_description.* FROM   ks_user_gear_description  INNER JOIN    ks_users  ON ks_user_gear_description.app_user_id = ks_users.app_user_id  WHERE ks_user_gear_description.app_user_id = '".$app_user_id."' AND gear_type != '3'  " ; 

		$gearlist = $this->common_model->get_records_from_sql($sql);
	//	$gearlist = 	$query->result();

		if (!empty($gearlist)) {
			$html = '<option>--Select Gear--</option>';
			 foreach ($gearlist as $value) {
				$html .= '<option value="'.$value->user_gear_desc_id.'" >'.$value->gear_name.'</option>  ' ;
			 }
		}else{
			$html = '<option>--Select Gear--</option>';
		}
		echo $html ;
	}

	public function getmodalsList(){

		$manufacturer_id =  $this->input->post('manufacturer_id');

		$query = $this->common_model->GetAllWhere('ks_manufacturers' , array('manufacturer_name'=>$manufacturer_id));
		$gearlist = 	$query->row();

		$query = $this->common_model->GetAllWhere('ks_models' , array('manufacturer_id'=>$gearlist->manufacturer_id));
		$gearlist = 	$query->result();
		$name = '<option value="">-Select Model--</option> ' ;
		if (!empty($gearlist)) {

			 foreach ($gearlist as $value) {
				$html[] = $value->model_name;

				$name .='<option value="'.$value->model_name.'">'.$value->model_name.'</option>';  
			 }
		}else{
			
			$html = '';
		}
		
		echo $name;
		//print_r($html);
		
	}
	public function getAppuserAddress(){
		
		 $app_user_id =  $this->input->post('app_user_id');
		 
		 $sql = " SELECT address.*, s.ks_state_name , subs.suburb_name FROM  ks_user_address As address LEFT JOIN ks_states As s ON address.ks_state_id = s.ks_state_id LEFT JOIN ks_suburbs As subs ON address.ks_suburb_id = subs.ks_suburb_id  WHERE  address.app_user_id = '".$app_user_id."'";
		 $address_array = $this->common_model->get_records_from_sql($sql);
		 
		 
		
		 
		 //print_r($address_array);
		 if(count($address_array) > 0 ){
			 $html = '<option>--Select Address--</option>';
			  foreach($address_array AS $address){
				 $html .= '<option value="'.$address->user_address_id.'" > '.$address->street_address_line1  .' ' . $address->ks_state_name. ' ' . $address->suburb_name . $address->postcode .'</option>'; 
			  }
		 }else{
			 
			 $html = '<option>--No Address Found--</option>';
		 }
		 echo $html ;
	}
	public function deleteUnacailable($unavailble_date,$desc_id){
		
		$this->common_model->delele('ks_gear_unavailable','ks_gear_unavailable_id',$unavailble_date);
		 redirect('gear_desc/edit/'.$desc_id);
	}
	public function getsubcat($sub_category_id){
		
		$query = $this->common_model->GetAllWhere('ks_gear_categories', array('gear_sub_category_id'=>$sub_category_id) );
		$values = $query->result();
		if(!empty($values)){
			$html = '' ;
			foreach($values As $value ){
				
				$html .= '<option value="'.$value->gear_category_id.'">'.$value->gear_category_name.'</option>';
			}
		}else{
				$html = '<option value="">No sub Category Found</option>';
		}
		echo $html ;
	}

	public function getmodalDetails()
	{
		$post_data      =$this->input->post();
		// print_r($post_data);
		// die;
		//check manufacturers
		$query_manufacturers = $this->common_model->GetAllWhere('ks_manufacturers',array( 'manufacturer_name'=>$post_data['manufacturer_name'] ,)) ;
		$manufacturers = $query_manufacturers->row();
		$query_category = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_sub_category_id != '=>0)) ;
		$sub_category = $query_category->result();
		if(empty($manufacturers)){
			$result['status'] = '400';
			$result['status_message'] = 'manufacturer does not exist.';
			$result['result'] = '';
			echo json_encode($result,true);
				exit ;
		}
		$query_category = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_sub_category_id'=>0)) ;
			$category = $query_category->result();
		//check Models
		$query_model = $this->common_model->GetAllWhere('ks_models',array( 'model_name'=>$post_data['model_name'] ,)) ;
		$models = $query_model->row();

		if(empty($models)){
			$result['status'] = '400';
			$result['status_message'] = 'Model does not exist.';
			$result['result'] = '';
			$result['category'] = $category ;
			$result['sub_category'] = $sub_category ;

			echo json_encode($result,true);
				exit ;
		}
		//GET models
		$query = $this->common_model->GetAllWhere('ks_models',array( 'manufacturer_id'=>$manufacturers->manufacturer_id , 'model_id'=> $models->model_id)) ;
		$result_arr = $query->row();
		// Get All gear Category 
			$query_category = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_sub_category_id'=>0)) ;
			$category = $query_category->result();
		if(count($result_arr) > 0 ){
			if($result_arr->model_image != ''){
				$result_arr->model_image = ROOT_PATH.'/site_upload/gear_images/model_images/'.$result_arr->model_image ;
			}
			$result_arr->security_deposit = '0';
			 foreach ($sub_category as  $value) {
			 	if ($value->gear_category_id == $result_arr->gear_sub_category_id ) {
			 			
			 			if($value->security_deposit == 'Y') {
			 				$result_arr->security_deposit = '1';
			 			}
			 	}else{

			 	}
			 }
			 $query1 = $this->common_model->GetAllWhere('ks_feature_master',array('gear_category_id'=>$result_arr->gear_category_id));
			 $result['feature_details'] = $query1->result();
			$result['status'] = '200';
			$result['status_message'] = 'Success';
			$result['match_found'] = 1;
			$result['result'] = $result_arr;
			$result['category'] = $category ;
			$result['sub_category'] = $sub_category ;
			// echo "<pre>";
			 // print_r($result_arr->gear_category_id);die;
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
				$result_arr->model_image = ROOT_PATH.'site_upload/model_images/'.$result_arr->model_image ;
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
	public function getfeauturedetails()
	{
		$feature_master_id =  $this->input->post('feature_master_id');
		$gear_category_ids = '';
		foreach ($feature_master_id as $gear_category) {
				$gear_category_ids.= "'". $gear_category."',";
		}
		$where =  "feature_master_id IN (".rtrim($gear_category_ids , ',').")  ";
		$data['feature_details'] = $this->common_model->get_records_from_sql(' SELECT * FROM  ks_gear_feature_details WHERE '.$where);
		echo json_encode($data);
	}
	public function checksecuritydeposite()
	{
		$category_name =  $this->input->post('category_name');
		$query = $this->common_model->GetAllWhere('ks_gear_categories',array('is_active', 'gear_category_name'=>$category_name));
		$gear_name = $query->row();
		
		 if ($gear_name->security_deposit == 'Y') {
			echo "Yes" ;
		 }else{
		 	echo "No" ;
		 }
	}
	public function cpoyimage($image='')
	{
		$imagePath =  ROOT_UPLOAD_IMPORT_PATH.'/site_upload/model_images/'.$image;
		$newPath = ROOT_UPLOAD_IMPORT_PATH.'/site_upload/gear_images/';
		$ext = '.jpg';
		$newName  = time()."-".$ext;

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
	public function AddAccessory($value='')
	{
			 parse_str($_POST['data'], $post_data);
			$inser_data = array(
								'gear_name'=>$post_data['gear_name_acess'],
								'serial_number'=>$post_data['seria_no_acess'],
								'app_user_id'=>$post_data['app_user_acess'],
								'ks_gear_type_id'=>'3',
								'gear_type'=>'3',
								'create_user'=>$this->session->userdata('ADMIN_ID'),
								'create_date'=> date('Y-m-d'),
							);

			$this->common_model->addRecord('ks_user_gear_description', $inser_data);
			$sql = "SELECT ks_user_gear_description.* FROM   ks_user_gear_description  INNER JOIN    ks_users  ON ks_user_gear_description.app_user_id = ks_users.app_user_id  WHERE ks_user_gear_description.app_user_id = '".$post_data['app_user_acess']."' " ; 

			$gearlist = $this->common_model->get_records_from_sql($sql);
			if (!empty($gearlist)) {
				$html = '<option>--Select Gear--</option>';
				 foreach ($gearlist as $value) {
					$html .= '<option value="'.$value->user_gear_desc_id.'" >'.$value->gear_name.'</option>  ' ;
				 }
			}else{
				$html = '<option>--Select Gear--</option>';
			}
		echo $html ;
		

	}

	public function downloadCsv()
	{
		
		$data=array();
		$where_array = '';
		$device= $this->model->device($where_array);
		$result = $device->result();
		foreach ($result as  $value) {
				
			unset($value->ks_manufacturers_id);
			unset($value->ks_category_id);
			unset($value->ks_sub_category_id);
			unset($value->model_id);
			unset($value->ks_gear_address_id);
			unset($value->create_user);
			unset($value->gear_star_rating_avg);
			unset($value->gear_review_count);
			unset($value->gear_delisting_date);
			unset($value->gear_listing_date);
			$gear_data[] =  array(
											'Serial_no'=> $value->user_gear_desc_id,	
				 							'gear_name' => $value->gear_name,
				 							'gear_category_name'=> $value->gear_category_name,
				 							'sub_category_name'=> $value->sub_category_name ,
				 							'model_name'=>$value->model_name ,
				 							'ks_gear_type_name'=>$value->ks_gear_type_name ,
				 							'manufacturer_name'=>$value->manufacturer_name,
				 							'create_date' => $value->create_date,
				 							'gear_description_1'=> $value->gear_description_1,
				 							'serial_number' =>$value->serial_number,
				 							
				 							'replacement_value_aud_ex_gst'=> number_format((float)$value->replacement_value_aud_ex_gst, 2, '.', ''),
				 							'replacement_value_aud_inc_gst'=>	number_format((float)$value->replacement_value_aud_inc_gst, 2, '.', ''),
				 							'per_day_cost_aud_ex_gst'=>	number_format((float)$value->per_day_cost_aud_ex_gst, 2, '.', ''),
				 							'per_day_cost_aud_inc_gst'=>	number_format((float)$value->per_day_cost_aud_inc_gst, 2, '.', ''),
				 							'per_weekend_cost_aud_ex_gst'=> number_format((float)$value->per_weekend_cost_aud_ex_gst, 2, '.', ''),
				 							'per_weekend_cost_aud_inc_gst'=>	number_format((float)$value->per_weekend_cost_aud_inc_gst, 2, '.', ''),
				 							'per_week_cost_aud_ex_gst'=> number_format((float)$value->per_week_cost_aud_ex_gst, 2, '.', ''),
				 							'per_week_cost_aud_inc_gst' =>	number_format((float)$value->per_week_cost_aud_inc_gst, 2, '.', ''),
				 							'ks_user_gear_condition' =>	$value->ks_user_gear_condition,
				 							'security_deposite'=> number_format((float)$value->security_deposite, 2, '.', ''),
				 							'security_deposite_inc_gst'=> number_format((float)$value->security_deposite_inc_gst, 2, '.', ''),
				 							'google_360_link'=>$value->google_360_link ,
				 							'Status' =>$value->is_active,
				 							
				 							
				 							
				 					);	

		}
		// $this->load->library('Csvreader');
		 $filename = "Gear-List-".date('d-m-Y').".csv";
		$fp = fopen('php://output', 'w');
		$header['1'] = 'Gear ID';
		$header['2'] = 'Gear  Number';
		$header['3'] = 'Gear Cateogry  Name';
		$header['4'] = 'Gear Sub Cateogry Name';
		$header['5'] = 'Model Name';
		$header['6'] = 'Gear Type';
		$header['7'] = 'Manufacturer Date';
		$header['8'] = 'Created Date';
		$header['9'] = ' Gear Description';
		$header['10'] = 'Serial no';
		$header['11'] = 'Replacement Value ex GST';
		$header['12'] = 'Replacement Value Inc GST';
		$header['13'] = 'Perday  Value ex GST';
		$header['14'] = 'Perday  Value Inc GST';
		$header['15'] = 'Per WeekEnd  Value ex GST';
		$header['16'] = 'Per WeekEnd  Value Inc GST';
		$header['17'] = 'Per Week Value ex GST';
		$header['18'] = 'Per Week Value Inc GST';
		
		$header['19'] = 'Gear Condition';
		$header['20'] = 'Security Deposit Exc GST';
		$header['21'] = 'Security Deposit Inc GST';
		$header['22'] = 'Google 360';
		$header['23'] = 'Status';
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		fputcsv($fp, $header);
		$i= '1';
		foreach($gear_data AS $row) {
			
			fputcsv($fp, $row);
			$i++ ;
		}
		// 		$this->excel->setActiveSheetIndex(0);
		// 		$this->excel->getActiveSheet()->setTitle('Gear List');
		// 		$this->excel->getActiveSheet()->setCellValue('A1', ' Gear ID');
		// 		$this->excel->getActiveSheet()->setCellValue('B1', 'Gear  Number');
		// 		$this->excel->getActiveSheet()->setCellValue('C1', 'Gear Cateogry  Name');
		// 		$this->excel->getActiveSheet()->setCellValue('D1', 'Gear Sub Cateogry Name');
		// 		$this->excel->getActiveSheet()->setCellValue('E1', ' Model Name ');
		// 		$this->excel->getActiveSheet()->setCellValue('F1', 'Gear Type ');
		// 		$this->excel->getActiveSheet()->setCellValue('G1', 'Manufacturer Date');
		// 		$this->excel->getActiveSheet()->setCellValue('H1', 'Created Date');
		// 		$this->excel->getActiveSheet()->setCellValue('I1', 'Gear Description');
		// 		$this->excel->getActiveSheet()->setCellValue('J1', 'Serial no');
		// 		$this->excel->getActiveSheet()->setCellValue('K1', 'Replacement Value ex GST');
		// 		$this->excel->getActiveSheet()->setCellValue('L1', 'Replacement Value Inc GST');
		// 		$this->excel->getActiveSheet()->setCellValue('M1', 'Perday  Value ex GST ');
		// 		$this->excel->getActiveSheet()->setCellValue('N1', 'Perday  Value Inc GST');
		// 		$this->excel->getActiveSheet()->setCellValue('O1', 'Per WeekEnd  Value ex GST');
		// 		$this->excel->getActiveSheet()->setCellValue('P1', 'Per WeekEnd  Value Inc GST');
		// 		$this->excel->getActiveSheet()->setCellValue('Q1', 'Per Week Value ex GST');
		// 		$this->excel->getActiveSheet()->setCellValue('R1', 'Per Week Value Inc GST');
		// 		$this->excel->getActiveSheet()->setCellValue('S1', 'Gear Condition');
		// 		$this->excel->getActiveSheet()->setCellValue('T1', 'Security Deposit Exc GST');
		// 		$this->excel->getActiveSheet()->setCellValue('U1', 'Security Deposit Inc GST');
		// 		$this->excel->getActiveSheet()->setCellValue('V1', 'Google 360');
		// 		for($col = ord('A'); $col <= ord('Y'); $col++){ //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		// 			$this->excel->getActiveSheet()->getStyle(chr($col)."1")->getFont()->setBold(true);
		// 			$this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
		// 		}
		// 		$this->excel->getActiveSheet()->fromArray($gear_data, null, 'A4');
		// 		$filename='Gear Description-'.'.xls'; //save our workbook as this file name
			
				
		// 		header('Content-Type: application/vnd.ms-excel'); //mime type
		// 		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		// 		header('Cache-Control: max-age=0'); //no cache
		// 		error_reporting(0);
		// 		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		// 		//if you want to save it as .XLSX Excel 2007 format
		// 		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		// 		//force user to download the Excel file without writing it to server's HD
		// 		// echo "<pre>";
		// 		// print_r($exceldata);die;
		// 		$objWriter->save('php://output');

		print_r($gear_data);

		die;
		//  $sql = " SELECT address.*, s.ks_state_name , subs.suburb_name FROM  ks_user_address As address LEFT JOIN ks_states As s ON address.ks_state_id = s.ks_state_id LEFT JOIN ks_suburbs As subs ON address.ks_suburb_id = subs.ks_suburb_id  WHERE  address.app_user_id = '".$data['result'][0]->app_user_id."'";
		//  $address_array = $this->common_model->get_records_from_sql($sql);
		
		// $data['address_array'] = $address_array;
		
		// $models=$this->common_model->GetAllWhere("ks_gear_unavailable",array("user_gear_description_id"=>$id , 'unavailable_to_date > ' => date('Y-m-d')));
		// $data['unavailable_data'] = $models->result();
 
 
 
		// $models=$this->common_model->GetAllWhere("ks_user_gear_images",array("user_gear_desc_id"=>$id));
		// $data['images'] = $models->result();

		// $models=$this->common_model->GetAllWhere("ks_models",array("is_active"=>'Y'));
		// $data['models'] = $models->result();
		
		

		// $query = $this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y'));
		// $data['gear_list'] = $query->result();

		// $query = $this->common_model->GetAllWhere("ks_gear_type",array("is_active"=>'Y'));
		// $data['gear_type'] = $query->result();

		// $users=$this->common_model->GetAllWhere("ks_users",array("is_active"=>'Y'));
		// $users = $users->result();

	}
}?>
