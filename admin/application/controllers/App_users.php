<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class App_users extends CI_Controller {

	 public function __construct() {

		parent::__construct();

		$this->load->helper(array('url','form','html','text','common_helper'));

		$this->load->library(array('session','form_validation','pagination','email','upload','image_lib'));

		$this->load->model(array('common_model','mail_model','model'));

		if($this->session->userdata('ADMIN_ID') =='') {

          redirect('login');

		  }

	}



		



		protected $validation_rules = array



        (



		'Add' => array(



			array(

                'field' => 'app_username',

                'label' => 'Username',

                'rules' => 'trim|required'

            ),

			array(

                'field' => 'app_user_first_name',

                'label' => 'First Name',

                'rules' => 'trim|required'

            ),

			array(

                'field' => 'app_user_last_name',

                'label' => 'Last Name',

                'rules' => 'trim|required'

            ),

			array(

                'field'   => 'app_password',

                'label'   => 'Password',

                'rules'   => 'trim|required'

            ),

			array(

                'field'   => 'conf_password',

                'label'   => 'Confirm Password',

                'rules'   => 'trim|required|matches[app_password]'



            )



        ),
        'Edit' => array(



			array(

                'field' => 'app_username',

                'label' => 'Username',

                'rules' => 'trim|required'

            ),

			array(

                'field' => 'app_user_first_name',

                'label' => 'First Name',

                'rules' => 'trim|required'

            ),

			array(

                'field' => 'app_user_last_name',

                'label' => 'Last Name',

                'rules' => 'trim|required'

            ),




        ),



    );



	public function index()

	{

	    $data=array();

		$where ='';

		if($this->input->get('limit') != ''){

			$data['limit']	= $this->input->get('limit');

		}

		else{

			$data['limit']	= 100;

		}



		$data['username']		  = $this->input->get('username');

		if($data['username'] != ''){

				$where .= "a.app_username LIKE '%".trim($data['username'])."%' AND ";

		}

			

	    

		$data['first_name']		  = $this->input->get('first_name');

		if($data['first_name'] != ''){

				$where .= "a.app_user_first_name LIKE '%".trim($data['first_name'])."%' AND ";

		}

		

		$data['last_name']		  = $this->input->get('last_name');

		if($data['last_name'] != ''){

				$where .= "a.app_user_last_name LIKE '%".trim($data['last_name'])."%' AND ";

		}





		$where = substr($where,0,(strlen($where)-4));

		

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

		

		if($this->input->get('field')!="")

			$data['field_name'] = $this->input->get('field');

		else

			$data['field_name'] = "app_user_id";

		



		$n=$this->model->app_users($where);

		//$sql=$this->model->app_users($where,$data['limit'],$offset);

		

		$sql = $this->model->app_users_orderby($where,$data['limit'],$offset,$order_by,$data['field_name']);

		
        

		$data['offset'] = $offset;

		$result=$sql->result();	

		$total_rows=$n->num_rows();	

		$data['result'] = $result;

		$data['total_rows']=$total_rows;

		$data['limit']=$data['limit'];

		$config['base_url'] = base_url()."app_users?username=".$data['username']."&first_name=".$data['first_name']."&last_name=".$data['last_name']."&order_by=".$order_by."&limit=".$data['limit'];

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



//////////////////////////////Pagination config//////////////////////////////////				

		//echo $this->db->last_query(); exit();



		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('app_users/list', $data);

		$this->load->view('common/footer');		



	}



	



	public function add()

	{

		$data=array();

		$data['owner_type']= $this->common_model->get_all_record(OWNER_TYPE,array("is_active"=>'Y'));

		$data['rental_type']= $this->common_model->get_all_record('ks_renter_type',array("is_active"=>'Y'));

		$data['profession_types']= $this->common_model->get_all_record('ks_profession_types',array("is_active"=>'Y'));

		$data['countries']= $this->common_model->get_all_record('ks_countries',array("is_active"=>'Y'));

		$this->db->select("user_unique_id_number");
		$this->db->from("ks_users");
		$this->db->order_by('app_user_id','desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$rs = $query->row();
		//print_r($rs);
			 $unique_id =$rs->user_unique_id_number;
		
		if($unique_id!=""){
		
			
			
			 $unique_arr = explode("KS",$unique_id);
			
		$no=(int)$unique_arr[1];
			
			if($no<9999999){
			
				$str="";
				
				$len=strlen($no);
							
				if(strlen($no)<7){
					for($l=$len;$l<7;$l++){
						$str.="0";
					}
				}
				
				$no=$no+1;
				
			 $data['unique_id'] ="KS".$str.$no;
			}
		
		}else{
			
			$data['unique_id'] = 'KS000001';
		}
		
		
		

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('app_users/add', $data);

		$this->load->view('common/footer');

	}



	public function save()

	{

	

	$data=array();

	$data['owner_type']= $this->common_model->get_all_record(OWNER_TYPE,array("is_active"=>'Y'));

		

	$this->form_validation->set_rules($this->validation_rules['Add']);



	if($this->form_validation->run())

	{

	

		//print_r($this->input->post());die;

		$newname= time();

		$filePath                = PROFILE_IMAGE;

		$config['upload_path']   = $filePath;

		$config['allowed_types'] = 'gif|jpg|png|jpeg';

		$config['file_name']     = $newname;

		$config['max_size']      = "";

		$config['max_width']     = "";

		$config['max_height']    = "";

		

		$this->load->library('upload', $config);

		$this->upload->initialize($config);

		if (!$this->upload->do_upload('user_profile_picture_link'))

		{

			$error = array('error' => $this->upload->display_errors());		

		}

		else

		{		

			$imgdata = array('upload_data' => $this->upload->data());

		}

		

		//print_r($error); die;

		$row['app_username']                  = $this->input->post('app_username');

		$row['app_user_first_name']           = $this->input->post('app_user_first_name');

		$row['app_user_last_name']            = $this->input->post('app_user_last_name');

		$row['owner_type_id']                 = $this->input->post('owner_type_id');

		$row['user_birth_date']               = $this->input->post('user_birth_date');

		$row['user_unique_id_number']         = $this->input->post('user_unique_id_number');

		$row['australian_business_number']    = $this->input->post('australian_business_number');

		$row['user_description']              = $this->input->post('user_description');

		$row['about_me']                      = $this->input->post('about_me');

		$row['primary_mobile_number']         = $this->input->post('primary_mobile_number');

		$row['additional_mobile_number']      = $this->input->post('additional_mobile_number');

		$row['primary_email_address']         = $this->input->post('primary_email_address');

		$row['additional_email_address_1']    = $this->input->post('additional_email_address_1');

		$row['additional_email_address_2']    = $this->input->post('additional_email_address_2');

		$row['additional_email_address_3']    = $this->input->post('additional_email_address_3');

		$row['user_signup_type']              = $this->input->post('user_signup_type');

		//$row['user_account_type']             = $this->input->post('user_account_type');

		$row['user_social_id']                = $this->input->post('user_social_id');

		$row['user_profile_picture_link']     = ROOT_PATH.'/site_upload/profile_img/'.$imgdata['upload_data']['file_name'];

		$row['app_password']                  = $this->common_model->base64En(2,trim($this->input->post('app_password')));

		$row['user_website']                  = $this->input->post('user_website');

		$row['imdb_link']                     = $this->input->post('imdb_link');

		$row['showreel_link']                 = $this->input->post('showreel_link');

		$row['instagram_link']                = $this->input->post('instagram_link');

		$row['facebook_link']                 = $this->input->post('facebook_link');

		$row['vimeo_link']                    = $this->input->post('vimeo_link');

		$row['youtube_link']                  = $this->input->post('youtube_link');

		$row['flikr_link']                    = $this->input->post('flikr_link');

		$row['twitter_link']                  = $this->input->post('twitter_link');

		$row['linkedin_link']                 = $this->input->post('linkedin_link');

		$row['receive_sms_notification']      = $this->input->post('receive_sms_notification');

		$row['enable_weekly_rate']            = $this->input->post('enable_weekly_rate');

		$row['registered_for_gst']           = $this->input->post('registered_for_gst');

		$row['is_willing_deliver']            = $this->input->post('is_willing_deliver');

		$row['is_prep_space_available']       = $this->input->post('is_prep_space_available');

		$row['is_active']                     = $this->input->post('is_active');

		$row['ks_renter_type_id']             = $this->input->post('ks_renter_type_id');

		$row['profession_type_id']             = $this->input->post('profession_type_id');

		

		$row['is_blocked']                    = $this->input->post('is_blocked');

		if($row['is_blocked']=='Y')

		{

			$row['blocked_on']                = date('Y-m-d');

		}

		$row['block_reason']				  = $this->input->post('block_reason');

		$row['create_user']                   = $this->session->userdata('ADMIN_ID');

		$row['create_date']                   = date('Y-m-d');

		$this->common_model->addRecord(GS_USERS,$row);
		$app_iser_id = $this->db->insert_id();
		$def_addr_val = $this->input->post('default_address');
		if (count($this->input->post('street_address_line1'))>0 ) {
			$i = 0; 
			foreach ($this->input->post('street_address_line1') as  $address_array) {
			$j = $i+1;
				if ( $j ==$def_addr_val[0] ) {
					$default_address =  '1' ;
				}else{
					$default_address = '0';
				}
				$queryks_suburbs =$this->common_model->GetAllWhere('ks_suburbs',array('ks_suburb_id'=>$this->input->post('ks_suburb_id')[$i]));
					$queryks_suburbs_details = $queryks_suburbs->row();

					$queryks_states =$this->common_model->GetAllWhere('ks_states',array('ks_state_id'=>$this->input->post('ks_state_id')[$i]));
					$ks_states = $queryks_states->row();
							 	$string = '';

				 	if (!empty($this->input->post('street_address_line1')[$i])) {
				 		$string .= $this->input->post('street_address_line1')[$i].',';
				 	}
					 if (!empty($this->input->post('street_address_line2')[$i])) {
					 		$string .= $this->input->post('street_address_line2')[$i].',';
					 	}
				 	if (!empty($this->input->post('ks_suburb_id')[$i])) {
				 		$string .= $queryks_suburbs_details->suburb_name.',';
				 	}
				 	if (!empty($this->input->post('ks_state_id')[$i])) {
				 		$string .= $ks_states->ks_state_name.',';
				 	}
				 	if (!empty($this->input->post('ks_country_id')[$i])) {
				 		$string .= 'Australia ';
				 	}
				 	$lat_lng=   $this->GetLatLngFromAddress($string);
				 	
				
				
				$address['street_address_line1'] = $this->input->post('street_address_line1')[$i];
				$address['street_address_line2'] = $this->input->post('street_address_line2')[$i];
				$address['route'] = $this->input->post('route')[$i];
				$address['default_address'] = $default_address;
				$address['ks_country_id'] = $this->input->post('ks_country_id')[$i];
				$address['ks_state_id'] = $this->input->post('ks_state_id')[$i];
				$address['ks_suburb_id'] = $this->input->post('ks_suburb_id')[$i];
				$address['lat'] = $lat_lng['lat'];
				$address['lng'] = $lat_lng['lng'];
				$address['postcode'] = $this->input->post('postcode')[$i];
				$address['is_active'] = 'Y';
				$address['create_date'] = Date('Y-m-d');
				$address['create_user'] = $this->session->userdata('ADMIN_ID') ;
				$address['app_user_id'] = $app_iser_id ;
				$this->common_model->addRecord('ks_user_address',$address);
				$i++ ;
			}
		}

		$message = '<div class="alert alert-success">User has been successfully added.</p></div>';

		$this->session->set_flashdata('success', $message);

	    redirect('app_users');



	 }else{

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');					

		$this->load->view('app_users/add', $data);

		$this->load->view('common/footer');	



	  }



	}



	



	public function edit()

	{



	    $data = array();

		$id = $this->uri->segment(3);

		$where_array = array('app_user_id'=>$id);

		$data['app_users']= $this->common_model->get_all_record(GS_USERS,$where_array);
		$where_array['is_active'] = 'Y'	;
		$sql = " SELECT address.*,suburb_name,ks_state_name,ks_country_name FROM  ks_user_address As address  INNER JOIN ks_suburbs On address.ks_suburb_id = ks_suburbs.ks_suburb_id INNER JOIN ks_states ON address.ks_state_id = ks_states.ks_state_id INNER JOIN ks_countries ON address.ks_country_id = ks_countries.ks_country_id  WHERE address.is_active = 1 AND address.app_user_id   = '".$id."'";
		$data['app_users_address'] =  $this->common_model->get_records_from_sql($sql);
		// $data['app_users_address']= $this->common_model->get_all_record('ks_user_address',$where_array);

		$data['owner_type']= $this->common_model->get_all_record(OWNER_TYPE,array("is_active"=>'Y'));

		$data['rental_type']= $this->common_model->get_all_record('ks_renter_type',array("is_active"=>'Y'));

		$data['profession_types']= $this->common_model->get_all_record('ks_profession_types',array("is_active"=>'Y'));

		$data['countries']= $this->common_model->get_all_record('ks_countries',array("is_active"=>'Y'));

		$data['states']= $this->common_model->get_all_record('ks_states',array("is_active"=>'Y'));

		if (!empty($data['app_users_address'])) {
			$gear_category_ids = '';
			foreach ($data['app_users_address'] as  $value) {
				  $gear_category_ids.= "'". $value->ks_state_id."',";
			}
			$sql = "SELECT * FROM ks_suburbs WHERE  ks_state_id IN 	(".rtrim($gear_category_ids , ',').")" ;
			$data['suburbs']= $this->common_model->get_records_from_sql($sql);
		}else{
			$data['suburbs']= ''	;
		}
		


		$this->load->view('common/header');	

		$this->load->view('common/left-menu');					

		$this->load->view('app_users/edit', $data);

		$this->load->view('common/footer');		



	}



	public function update()

	{

		$data = array();

		$data['owner_type']= $this->common_model->get_all_record(OWNER_TYPE,array("is_active"=>'Y'));

		$id = $this->input->post('app_user_id');

		$where_array = array('app_user_id'=>$id);

		$data['app_users']= $this->common_model->get_all_record(GS_USERS,$where_array);

		

		

		//print_r($this->input->post()); die;

   

    	$this->form_validation->set_rules($this->validation_rules['Edit']);



		if($this->form_validation->run() == true )

		{

			

				$newname= time();

				$filePath                = PROFILE_IMAGE;

				$config['upload_path']   = $filePath;

				$config['allowed_types'] = 'gif|jpg|png|jpeg';

				$config['file_name']     = $newname;

				$config['max_size']      = "";

				$config['max_width']     = "";

				$config['max_height']    = "";

				

				$this->load->library('upload', $config);

				$this->upload->initialize($config);

				

				if (!$this->upload->do_upload('user_profile_picture_link'))

				{

				

					$error = array('error' => $this->upload->display_errors());		

				}

				else

				{	

					

					$imgdata = array('upload_data' => $this->upload->data());

					

				}

				

				//print_r($error); die;

		

		

			if(!empty($imgdata))

			{

				$where_array = array('app_user_id'=>$id);

				$img= $this->common_model->get_all_record(GS_USERS,$where_array);

				foreach($img as $im){

					$this->load->helper("file");

					$oldfile = PROFILE_IMAGE."/".$im->user_profile_picture_link ; 

					unlink($oldfile);

				}

				$row['user_profile_picture_link'] = $imgdata['upload_data']['file_name'];

			}				

			//echo $imgdata['upload_data']['file_name']; die;

			$row['app_username']                  = $this->input->post('app_username');

			$row['app_user_first_name']           = $this->input->post('app_user_first_name');

			$row['app_user_last_name']            = $this->input->post('app_user_last_name');

			$row['owner_type_id']                 = $this->input->post('owner_type_id');

			$row['user_birth_date']               = $this->input->post('user_birth_date');

			$row['user_unique_id_number']         = $this->input->post('user_unique_id_number');

			$row['australian_business_number']    = $this->input->post('australian_business_number');

			$row['user_description']              = $this->input->post('user_description');

			$row['about_me']                      = $this->input->post('about_me');

			$row['primary_mobile_number']         = $this->input->post('primary_mobile_number');

			$row['additional_mobile_number']      = $this->input->post('additional_mobile_number');

			$row['primary_email_address']         = $this->input->post('primary_email_address');

			$row['additional_email_address_1']    = $this->input->post('additional_email_address_1');

			$row['additional_email_address_2']    = $this->input->post('additional_email_address_2');

			$row['additional_email_address_3']    = $this->input->post('additional_email_address_3');

			$row['user_signup_type']              = $this->input->post('user_signup_type');

			//	$row['user_account_type']             = $this->input->post('user_account_type');

			$row['user_social_id']                = $this->input->post('user_social_id');

			//$row['app_password']                  = $this->common_model->base64En(2,trim($this->input->post('app_password')));

			$row['user_website']                  = $this->input->post('user_website');

			$row['imdb_link']                     = $this->input->post('imdb_link');

			$row['showreel_link']                 = $this->input->post('showreel_link');

			$row['instagram_link']                = $this->input->post('instagram_link');

			$row['facebook_link']                 = $this->input->post('facebook_link');

			$row['vimeo_link']                    = $this->input->post('vimeo_link');

			$row['youtube_link']                  = $this->input->post('youtube_link');

			$row['flikr_link']                    = $this->input->post('flikr_link');

			$row['twitter_link']                  = $this->input->post('twitter_link');

			$row['linkedin_link']                 = $this->input->post('linkedin_link');

			$row['receive_sms_notification']      = $this->input->post('receive_sms_notification');

			$row['enable_weekly_rate']            = $this->input->post('enable_weekly_rate');

			$row['registered_for_gst']           = $this->input->post('registered_for_gst');

			$row['is_willing_deliver']            = $this->input->post('is_willing_deliver');

			$row['is_prep_space_available']       = $this->input->post('is_prep_space_available');

			$row['is_active']                     = $this->input->post('is_active');

			$row['ks_renter_type_id']             = $this->input->post('ks_renter_type_id');

			$row['profession_type_id']             = $this->input->post('profession_type_id');

		

			$row['is_blocked']                    = $this->input->post('is_blocked');

			if($row['is_blocked']=='Y')

			{

				$row['blocked_on']                = date('Y-m-d');

			}

			$row['block_reason']				  = $this->input->post('block_reason');

			$row['update_user']                   = $this->session->userdata('ADMIN_ID');

			$row['update_date']                   = date('Y-m-d');

			

			$this->db->where('app_user_id', $id);

			$this->db->update(GS_USERS, $row); 

			

			$i=0; 
			//echo "<pre>";
			$def_addr_val = $this->input->post('default_address');
			// echo "<pre>";
			// print_r($this->input->post());die;
			foreach ($this->input->post('street_address_line1') as  $value) {
			
				 $j= $i+1 ;
				// print_r($value);die;
				if ($this->input->post('user_address_id')[$i] == $def_addr_val[0])  {
					$default_address =  '1' ;
				}else{
					$default_address = '0';
				}
				
				if($this->input->post('user_address_id')[$i]!=''){

					$queryks_suburbs =$this->common_model->GetAllWhere('ks_suburbs',array('ks_suburb_id'=>$this->input->post('ks_suburb_id')[$i]));
					$queryks_suburbs_details = $queryks_suburbs->row();

					$queryks_states =$this->common_model->GetAllWhere('ks_states',array('ks_state_id'=>$this->input->post('ks_state_id')[$i]));
					$ks_states = $queryks_states->row();
							 	$string = '';

				 	if (!empty($this->input->post('street_address_line1')[$i])) {
				 		$string .= $this->input->post('street_address_line1')[$i].',';
				 	}
					 if (!empty($this->input->post('street_address_line2')[$i])) {
					 		$string .= $this->input->post('street_address_line2')[$i].',';
					 	}
				 	if (!empty($this->input->post('ks_suburb_id')[$i])) {
				 		$string .= $queryks_suburbs_details->suburb_name.',';
				 	}
				 	if (!empty($this->input->post('ks_state_id')[$i])) {
				 		$string .= $ks_states->ks_state_name.',';
				 	}
				 	if (!empty($this->input->post('ks_country_id')[$i])) {
				 		$string .= 'Australia ';
				 	}
				 	// $lat_lng=   $this->GetLatLngFromAddress($string);
				 	
				
				
					$address['street_address_line1'] = $this->input->post('street_address_line1')[$i];
					$address['street_address_line2'] = $this->input->post('street_address_line2')[$i];
					$address['route'] = $this->input->post('route')[$i];
					$address['ks_country_id'] = $this->input->post('ks_country_id')[$i];
					$address['ks_state_id'] = $this->input->post('ks_state_id')[$i];
					$address['ks_suburb_id'] = $this->input->post('ks_suburb_id')[$i];
					// $address['lat'] = $lat_lng['lat'];
					// $address['lng'] = $lat_lng['lng'];
					$address['postcode'] = $this->input->post('postcode')[$i];
					$address['is_active'] = 'Y';
					$address['default_address ']= $default_address; 
					$address['create_date'] = Date('Y-m-d');
					$address['create_user'] = $this->session->userdata('ADMIN_ID') ;
					$address['app_user_id'] = $id;
					
					$this->db->where('user_address_id', $this->input->post('user_address_id')[$i]);
					$this->db->update('ks_user_address', $address);
					//print_r($this->db->last_query());die;
			}else{
				$queryks_suburbs =$this->common_model->GetAllWhere('ks_suburbs',array('ks_suburb_id'=>$this->input->post('ks_suburb_id')[$i]));
					$queryks_suburbs_details = $queryks_suburbs->row();

					$queryks_states =$this->common_model->GetAllWhere('ks_states',array('ks_state_id'=>$this->input->post('ks_state_id')[$i]));
					$ks_states = $queryks_states->row();
							 	$string = '';

				 	if (!empty($this->input->post('street_address_line1')[$i])) {
				 		$string .= $this->input->post('street_address_line1')[$i].',';
				 	}
					 if (!empty($this->input->post('street_address_line2')[$i])) {
					 		$string .= $this->input->post('street_address_line2')[$i].',';
					 	}
				 	if (!empty($this->input->post('ks_suburb_id')[$i])) {
				 		$string .= $queryks_suburbs_details->suburb_name.',';
				 	}
				 	if (!empty($this->input->post('ks_state_id')[$i])) {
				 		$string .= $ks_states->ks_state_name.',';
				 	}
				 	if (!empty($this->input->post('ks_country_id')[$i])) {
				 		$string .= 'Australia ';
				 	}
				 	// $lat_lng=   $this->GetLatLngFromAddress($string);
				 	
						$def_addr_val =  $def_addr_val[0] -1 ; 
						if ($i == $def_addr_val ) {
							$default_address = '1';
						}else{
							$default_address = '0';
						}
				$address['street_address_line1'] = $this->input->post('street_address_line1')[$i];
				$address['street_address_line2'] = $this->input->post('street_address_line2')[$i];
				$address['route'] = $this->input->post('route')[$i];
				$address['ks_country_id'] = $this->input->post('ks_country_id')[$i];
				$address['ks_state_id'] = $this->input->post('ks_state_id')[$i];
				$address['ks_suburb_id'] = $this->input->post('ks_suburb_id')[$i];
				// $address['lat'] = $lat_lng['lat'];
				// $address['lng'] = $lat_lng['lng'];
				$address['postcode'] = $this->input->post('postcode')[$i];
				$address['is_active'] = 'Y';
				$address['default_address ']= $default_address; 
				$address['create_date'] = Date('Y-m-d');
				$address['create_user'] = $this->session->userdata('ADMIN_ID') ;
				$address['app_user_id'] = $id;
					
				$this->common_model->addRecord('ks_user_address',$address);
			}
			$i++;
			}

			//	die;

			//echo $this->db->last_query(); die;

			

			

			$message = '<div class="alert alert-success"><p>Userdata updated successfully.</p></div>';

		

			$this->session->set_flashdata('success', $message);

			redirect('app_users');

	

		}

		else

		{	
			$data['app_users_address']= array();

			$this->load->view('common/header');	

			$this->load->view('common/left-menu');

			$this->load->view('app_users/edit', $data);

			$this->load->view('common/footer');	

		}

	

	}
		  public function  GetLatLngFromAddress($string='')
    {
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($string)."&key=AIzaSyCPU8keawNREwb4_tHM8D1mcw4bRuSEoUQ",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "",
			  CURLOPT_HTTPHEADER => array(
			    "Postman-Token: 8f73ce74-3855-44cb-8b1e-79469ad9a72f",
			    "cache-control: no-cache"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
				$response =  json_decode($response, true) ; 
			   return $response['results'][0]['geometry']['location'];
			}
    }


	public function view()

	{



	    $data = array();

		$id = $this->uri->segment(3);

		$where_array = array('app_user_id'=>$id);

		$data['user']= $this->common_model->get_all_record(M_APP_USER,$where_array);	

	

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');					

		$this->load->view('meet_team/view', $data);

		$this->load->view('common/footer');		



	}



	public function select_delete()

	{

		if(isset($_POST['bulk_delete_submit']))

		{

	

			$idArr = $this->input->post('checked_id');

			foreach($idArr as $id){

			

				$where_array = array('app_user_id'=>$id);

				$img= $this->common_model->get_all_record(GS_USERS,$where_array);

				foreach($img as $im){

					$this->load->helper("file");

					$oldfile = PROFILE_IMAGE."/".$im->user_profile_picture_link ; 

					unlink($oldfile);

				}

				$this->db->where('app_user_id', $id);

				$this->db->delete(GS_USERS);    

	

			}

	

			$message = '<div class="alert alert-success"><p>Users have been deleted successfully.</p></div>';

			$this->session->set_flashdata('success', $message);

			redirect('app_users');

	

		 }



	}



	public function delete_record()

	{



		$id=$this->uri->segment(3);

		$where_array = array('app_user_id'=>$id);

		$img= $this->common_model->get_all_record(GS_USERS,$where_array);

		foreach($img as $im){

			$this->load->helper("file");

			$oldfile = PROFILE_IMAGE."/".$im->user_profile_picture_link ; 

			unlink($oldfile);

		}

		$this->db->where('app_user_id', $id);

		$this->db->delete(GS_USERS);    

		$message = '<div class="alert alert-success"><p>User has been deleted successfully.</p></div>';

		$this->session->set_flashdata('success', $message);

		redirect('app_users');



	}



	

	public function username_check()

	{

	 

	 $username = $this->input->post('app_username'); 

	 $username_exist = $this->model->checkAppUser($username);

		if($username_exist->num_rows() > 0 )

		{

            echo 'false';

        } else {

            echo 'true';

        }

	 

	 }

	 

	 

	 public function username_check_edit(){

		$username = $this->input->post('app_username');

		$id = $this->uri->segment(3); 

		$username_exist = $this->model->checkAppUserEdit($username,$id);

		

			if($username_exist->num_rows() > 0 ){

				echo 'false';

			} else {

				echo 'true';

			}

	 }

	 

	 public function unique_id_check()

	 {

	 

	 $user_unique_id_number = $this->input->post('user_unique_id_number'); 

	 $userid_exist = $this->model->checkAppUserId($user_unique_id_number);

		if($userid_exist->num_rows() > 0 ){

            echo 'false';

        } else {

            echo 'true';

        }

	 

	 }

	 

	 

	 public function upload_image()

	 {

	 	$id = $this->input->post('app_user_id');

	 

	 	$newname= time();

		$filePath                = PROFILE_IMAGE;

		$config['upload_path']   = $filePath;

		$config['allowed_types'] = 'gif|jpg|png|jpeg';

		$config['file_name']     = $newname;

		$config['max_size']      = "";

		$config['max_width']     = "";

		$config['max_height']    = "";

		

		$this->load->library('upload', $config);

		$this->upload->initialize($config);

		

		if (!$this->upload->do_upload('user_profile_picture_link'))

		{

			$error = array('error' => $this->upload->display_errors());		

		}

		else

		{	

			$imgdata = array('upload_data' => $this->upload->data());
			
		}
		
		if(!empty($imgdata))

		{

			$where_array = array('app_user_id'=>$id);

			$img= $this->common_model->get_all_record(GS_USERS,$where_array);

			foreach($img as $im){

				$this->load->helper("file");

				 $oldfile = PROFILE_IMAGE."/".$im->user_profile_picture_link ; 

				unlink($oldfile);

			}
//print_r($imgdata['upload_data']['file_name']);die;
			$row['user_profile_picture_link'] = ROOT_PATH.'site_upload/profile_img/'.$imgdata['upload_data']['file_name'];

		}

		$row['update_user']                   = $this->session->userdata('ADMIN_ID');

		$row['update_date']                   = date('Y-m-d');

		$this->db->where('app_user_id', $id);

		$this->db->update(GS_USERS, $row); 

		$message = '<div class="alert alert-success"><p>Image uploaded successfully.</p></div>';

		$this->session->set_flashdata('success', $message);

		redirect('app_users/edit/'.$id);

	 

}



	  public function rent()

	  {

	   

	  	$data=array();

		

		 $id = $this->uri->segment(3);
		  $type = $this->uri->segment(4);
		 if($type== "owner"){
		
		$where ='';

		$limit=10;



		$data['username']		  = $this->input->get('username');

		if($data['username'] != ''){

				$where .= "a.app_username LIKE '%".trim($data['username'])."%' AND ";

		}

			

	    

		$data['first_name']		  = $this->input->get('first_name');

		if($data['first_name'] != ''){

				$where .= "a.app_user_first_name LIKE '%".trim($data['first_name'])."%' AND ";

		}

		

		$data['last_name']		  = $this->input->get('last_name');

		if($data['last_name'] != ''){

				$where .= "a.app_user_last_name LIKE '%".trim($data['last_name'])."%' AND ";

		}





		$where = substr($where,0,(strlen($where)-4));

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
		



		$n=$this->model->app_users_rent($where,$limit=0,$offset=0,$id);

		$sql=$this->model->app_users_rent($where,$limit,$offset,$id,$order_by);


		$data['id']= $id;
		$data['type']= $type;
		$result=$sql->result();	

		$total_rows=$n->num_rows();	

		$data['result'] = $result;

		$data['total_rows']=$total_rows;

		$data['limit']=$limit;

		$config['base_url'] = base_url()."app_users/rent/".$id."/owner?username=".$data['username']."&first_name=".$data['first_name']."&last_name=".$data['last_name'];

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

		

		

		//echo  $this->db->last_query(); 

		//print_r($data['result']); die;



//////////////////////////////Pagination config//////////////////////////////////				

		//echo $this->db->last_query(); exit();
	}elseif ( $type=='renter') {
		$data['ownerlist'] = $this->rentedList();
		//print_r($data['ownerlist']);
	}elseif ($type=='mangelist') {
		$data['mangelist'] = $this->manageList();
	}
		
		
		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('app_users/rent', $data);

		$this->load->view('common/footer');	

	  

	  

	  }

	  public function rentedList()
	  {
		$data=array();
		$id = $this->uri->segment(3);
		$where ='';
		$limit=10;
		$where = substr($where,0,(strlen($where)-4));
		if($this->input->get("per_page")!= ''){
			$offset = $this->input->get("per_page");
		}else{
			$offset=0;
		}
			 $data['order_by']	= $this->input->get('order_by');
			
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
		
		

		$n=$this->model->app_users_rentedList($where,$limit=0,$offset=0,$id);
		$sql=$this->model->app_users_rentedList($where,$limit,$offset,$id,$order_by);
		$result=$sql->result();	
		$total_rows=$n->num_rows();	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = base_url()."app_users/rent/".$id."/renter";
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
		$data['id'] =$id;
		$data['type'] ='renter';
		return  $data;
	  }

	public function manageList()
	{
	  	 $data=array();
		
		$where ='';
		
		$limit=10;
		
		$id = $this->uri->segment(3);
		if (!empty($id)) {
				$where .= " WHERE e.app_user_id = '".$id."' AND ";  		 
		}	 
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
		$nSer="SELECT e.* , b.model_name ,c.app_user_first_name,c.app_user_last_name ,c.app_username, gear_type.ks_gear_type_name,gear_name.gear_category_name FROM ks_user_gear_order_description e 
		INNER JOIN ks_models b ON e.model_id = b.model_id
		Left JOIN ks_users c ON e.app_user_id = c.app_user_id 
		LEFT JOIN ks_gear_type AS gear_type ON e.ks_gear_type_id = gear_type.ks_gear_type_id
		LEFT JOIN ks_gear_categories As gear_name ON  e.ks_category_id = gear_name.gear_category_id
		".$where." ORDER BY e.user_gear_desc_id";
		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;

		$config['base_url'] = base_url()."app_users/rent/".$id."/mangelist/?ks_category_id=".$data['ks_category_id']."&limit=".$limit;
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

						
		 $query = $this->common_model->GetAllWhere('ks_gear_type',''); 
		 $data['type'] = $query->result();
		 $sql1 = "SELECT DISTINCT (ks_users.app_user_id),ks_users.app_user_first_name,ks_users.app_user_last_name  FROM ks_users INNER JOIN   ks_user_gear_description ON ks_user_gear_description.app_user_id = ks_users.app_user_id " ;
		$query = $this->db->query($sql1);
		$data['app_users'] = $query->result();
		 $query = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_sub_category_id'=> '0')); 
		 $data['category'] = $query->result();

		 return $data;
	}

	  

	  public function rent_details()

	  {

	  	$data = array();

		$app_user_id              = $this->uri->segment(3);

		$user_gear_rent_detail_id = $this->uri->segment(4);

		$where = array("a.user_gear_rent_detail_id"=>$user_gear_rent_detail_id);

		$details = $this->model->app_users_rent($where,$limit='0',$offset='0',$app_user_id);

		$data['details']= $details->result();

		$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$data['details'][0]->create_user));
		$data['renter_details'] = $query->row();
		$query = $this->common_model->GetAllWhere('ks_insurance_category_type','');
		$data['ks_insurance_category'] = $query->result();

		$query = $this->common_model->GetAllWhere('ks_insurance_tiers','');
		$data['ks_insurance_tiers'] = $query->result();


		// echo "<pre>";
		// print_r($data);die;
		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('app_users/rent_details', $data);

		$this->load->view('common/footer');	

	  }

	  

	  public function import()

	  {

		 $data = array();

		 $heading = array();

		 if($this->input->post()!='')

			{

			      

				  $filename=$_FILES["file"]["tmp_name"];

				  if($_FILES["file"]["size"] > 0)

				  {

						$file = fopen($filename, "r");

						$count = 0;

						while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE)

						{

									if($count==0){

										

										$heading = $importdata;										

										

									}

									

									if($count>0){

									

										$cnt = count($importdata);

									

										for($i=1;$i<$cnt;$i++){

										

											if($i<52){

												$key = $heading[$i];

												$data[$key] = $importdata[$i];

											}

											

										}

									

										$user_data = $this->common_model->GetAllWhere('ks_users',array('primary_email_address'=>$data['primary_email_address']));

										$response = $user_data->result();

										//die;

										if (!empty($response)) {

											$this->db->where('app_user_id', $response['0']->app_user_id);

											$this->db->update('ks_users', $data); 

										}else{

											$this->common_model->addRecord('ks_users',$data);

										}

									

									}

									$count++;

					   }                    

					   fclose($file);

					   $message = '<div class="alert alert-success">Data imported successfully..</p></div>';

					   $this->session->set_flashdata('success', $message);

					   redirect('app_users');

				   }else{

				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';

				   $this->session->set_flashdata('success', $message);

				   redirect('app_users');

				   }

			}

	}

	

	public function downloadallcsv(){

	

		$query = $this->model->app_users();		
		// echo $this->db->last_query();die;
		$data = $query->result();
		// echo "<pre>";print_r($data);die;
		$fp = fopen('php://output', 'w');

						

		if ($fp && $query) {

			header('Content-Type: text/csv');

			header('Content-Disposition: attachment; filename="app_user.csv"');

			header('Pragma: no-cache');

			header('Expires: 0');

			$i=0;

			foreach($query->result('array') as $row){

				if($i==0)

					fputcsv($fp, array_keys($row));

			

				fputcsv($fp, array_values($row));

				$i++;

			}

			fclose($fp);

			

			die;



		}



 	}

	public function getStateList(){

		

		$country_id = $this->input->post('country_id');

		$user_data = $this->common_model->GetAllWhere('ks_states',array('ks_country_id'=>$country_id));

		$response = $user_data->result();

		//print_r($response);die;

		if(!empty($response)){

			$value= '<option value="" >--Select State--</option>';

			foreach ($response AS $countries){

				$value .= '<option value="'.$countries->ks_state_id.'" >'.$countries->ks_state_name.'</option>';

			}

		}else{

			$value  = '<option value="" >--No State Found --</option>';

		}

		echo $value ;

	}

	public function getCityList(){

		

		$state_id = $this->input->post('state_id');

		$user_data = $this->common_model->GetAllWhere('ks_suburbs',array('ks_state_id'=>$state_id));

		$response = $user_data->result();

		//print_r($response);die;

		if(!empty($response)){

			$value= '<option value="" >--Select Suburb--</option>';

			foreach ($response AS $countries){

				$value .= '<option value="'.$countries->ks_suburb_id.'" >'.$countries->suburb_name.'</option>';

			}

		}else{

			$value  = '<option value="" >--No State Found --</option>';

		}

		echo $value ;

	}

	public function getpincode(){

		

		$suburb_id = $this->input->post('suburb_id');

		$user_data = $this->common_model->GetAllWhere('ks_suburbs',array('ks_suburb_id'=>$suburb_id));

		$response = $user_data->row();

		//print_r($response);die;

		if(!empty($response)){

			$value  = $response->suburb_postcode;

		}else{

			$value  = '';

		}

		echo $value ;

	}
	public function addaccountaddress()
 	{

 		$data[]= array();
 		$data['counter'] = $this->input->post('counter');
 		$data['countries']= $this->common_model->get_all_record('ks_countries',array("is_active"=>'Y'));
 		$this->load->view('app_users/add-account-address', $data);
 	}
	


 	public function chat_history($owner_id,$gear_id,$renter)
 	{
 		
 		$data = array();
 		$app_user_id = $owner_id;
 		$user_gear_desc_id = $gear_id ; 
 		$renter_id = $renter ; 
 		$sql = "SELECT * FROM ks_chat_user WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$app_user_id."')  AND  user_gear_desc_id = '".$user_gear_desc_id."' " ; 
			$check_response = $this->common_model->get_records_from_sql($sql);
			if (empty($check_response)) {
				$response['status'] = 200;
				$response['status_message'] = 'Message Found Successfully';
				$response['message'] = '';
				$data['response']= $response;
				
			}else{ 
				$chat_user_id =  $check_response[0]->chat_user_id;
					
			  	$query  =  $this->common_model->GetAllWhere('ks_user_chatmessage',array('chat_user_id'=>$chat_user_id));					 
			  	$message = $query->result();
			  	if (!empty($message)) {
			  		$i = 0 ;	
			  		 foreach ($message as  $value) {
			  		 		// sender details
			  		 		$query_sender  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$value->sender_id));					 
			  				$sender_details = $query_sender->result();
			  				if (!empty($sender_details)) {
			  					$sender_detail =array(
			  											'app_username'=>$sender_details[0]->app_username,
			  											'app_user_first_name'=>$sender_details[0]->app_user_first_name,
			  											'app_user_last_name'=>$sender_details[0]->app_user_last_name,
			  											'user_profile_picture_link'=>$sender_details[0]->user_profile_picture_link,
			  											'chat_status'=>$sender_details[0]->chat_status,
			  											);
			  					$message[$i]->sender_details = $sender_detail;
			  				}else{
			  					$message[$i]->sender_details = array();
			  				}

			  				$query_receiver  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$value->receiver_id));					 
			  				$receiver_details = $query_receiver->result();
			  				if (!empty($receiver_details)) {
			  					$receiver_detail =array(
			  											'app_username'=>$receiver_details[0]->app_username,
			  											'app_user_first_name'=>$receiver_details[0]->app_user_first_name,
			  											'app_user_last_name'=>$receiver_details[0]->app_user_last_name,
			  											'user_profile_picture_link'=>$receiver_details[0]->user_profile_picture_link,
			  											'chat_status'=>$receiver_details[0]->chat_status,
			  											);
			  					$message[$i]->receiver_details = $receiver_detail;
			  				}else{
			  					$message[$i]->receiver_details = array();
			  				}
			  				//print_r($sender_details[0]);	
			  		 	$i++;
			  		 }
			  		$query =  $this->common_model->GetAllWhere('ks_user_gear_description' ,array('user_gear_desc_id'=>$user_gear_desc_id));
			  		$chatDetails['gear_detais'] =$query->row();
			  		$chatDetails['sender_details'] =  $this->common_model->get_records_from_sql("SELECT app_user_id,app_user_first_name ,app_user_last_name FROM ks_users WHERE app_user_id = '".$app_user_id."' ");
			  		$chatDetails['receiver_detail'] =   $this->common_model->get_records_from_sql("SELECT app_user_id,app_user_first_name ,app_user_last_name FROM ks_users WHERE app_user_id = '".$renter_id."' ");

			  	}
			  	$response['status'] = 200;
				$response['status_message'] = 'Message Send Successfully';
				$response['message'] = $message;
				$response['chatDetais'] = $chatDetails;
				$data['response'] =$response;
			}
				


 		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('app_users/chat_history', $data);

		$this->load->view('common/footer');
 	}
	  

}?>