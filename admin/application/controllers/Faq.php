<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Faq extends CI_Controller {

	 public function __construct() {

		parent::__construct();

		$this->load->helper(array('url','form','html','text','common_helper'));

		$this->load->library(array('session','form_validation','pagination','email','upload'));

		$this->load->model(array('common_model','mail_model','model'));

		if($this->session->userdata('ADMIN_ID') =='') {

          redirect('login');

		  }

	}

	

	protected $validation_rules = array

        (

		'Add' => array(

            array(

                'field' => 'category_name',

                'label' => 'category  Name',

                'rules' => 'trim|required'

            ),

			array(

                'field' => 'title',

                'label' => 'title',

                'rules' => 'trim'

            ),

			

        ),

		'Add1' => array(

            array(

                'field' => 'category_id',

                'label' => 'category  ID',

                'rules' => 'trim|required'

            ),

			array(

                'field' => 'title',

                'label' => 'title',

                'rules' => 'trim'

            ),

            array(

                'field' => 'faq_question',

                'label' => 'faq Question',

                'rules' => 'trim'

            ),

			

        ),

   

    );



	public function index()

	{



	    $data=array();

		

		$where =' ';

		

		$limit=10;

		 

		if($this->input->get("per_page")!= '')

		{

		$offset = $this->input->get("per_page");

		}

		else

		{

			$offset=0;

		}

		$nSer="SELECT * FROM ks_faq_category 

		".$where." ORDER BY order_by";

		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";

		$result=$this->db->query($sql);		

		$total_rows=count($this->db->query($nSer)->result());	

		$data['result'] = $result;

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



		//echo $this->db->last_query(); exit();

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('faq/list', $data);

		$this->load->view('common/footer');		



	



	}

	public function add()

	{

		$data=array();

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('faq/add', $data);

		$this->load->view('common/footer');	

	}

	private function set_upload_options()

	{   

    //upload an image options

	    $config = array();

	    $config['upload_path'] = UPLOAD_IMAGES.'/site_upload/faq_images/';;

	    $config['allowed_types'] = 'gif|jpg|png|jpeg';

	    $config['max_size']      = '0';

	    $config['overwrite']     = FALSE;



    	return $config;

	}

	public function save()

	{

		

		$input_data =  $this->input->post();

		//print_r($_FILES);die;

		$this->form_validation->set_rules($this->validation_rules['Add']);

		if($this->form_validation->run())

		{

			if (!empty($_FILES['image']['name']) ) {

					$this->load->library('upload');

				    $dataInfo = array();

				    $files = $_FILES;

				    $cpt = count($_FILES['image']['name']);

				        $_FILES['image']['name']= $files['image']['name'];

				        $_FILES['image']['type']= $files['image']['type'];

				        $_FILES['image']['tmp_name']= $files['image']['tmp_name'];

				        $_FILES['image']['error']= $files['image']['error'];

				        $_FILES['image']['size']= $files['image']['size'];    

				        $this->upload->initialize($this->set_upload_options());

				        $this->upload->do_upload('image');

				        $dataInfo = $this->upload->data();
				        $error = array('error' => $this->upload->display_errors());

				      	 $image = $dataInfo['file_name'];
				      	$configer =  array(
			              'image_library'   => 'gd2',
			              'source_image'    =>  UPLOAD_IMAGES.'/site_upload/faq_images/'.$image,
			              'maintain_ratio'  =>  faLSE,
			              'width'           =>  IMG_WIDTH_FAQ,
			              'height'          =>  IMG_HEIGHT_FAQ,
			            );
					   $this->image_lib->clear();
			           $this->image_lib->initialize($configer);
			           $this->image_lib->resize();

				 	$image = $dataInfo['file_name'];

								

			}else{

				$image = '' ;

			}

			$insert = array('category_name'=> $input_data['category_name'],

							'title'=>$input_data['title'],
							'permalink'=>$input_data['permalink'],

							'order_by' => $input_data['order_by'],

							'status'=> $input_data['status'],

							'create_date' =>date('Y-m-d'),

							'image'=> $image,

							

							);

			$this->common_model->AddRecord('ks_faq_category',$insert);

			$message = '<div class="alert alert-success">FAQ category has been successfully added.</p></div>';

			$this->session->set_flashdata('success', $message);

			redirect('Faq');

		}else{

			redirect('Faq/add');

		}	

	}

	

	

	public function edit()

	{

		$data=array();

		

		$id = $this->uri->segment(3);

		$where_array = array('id'=>$id);

		$device= $this->common_model->GetAllWhere('ks_faq_category',$where_array);

		$data['result'] = $device->row();

		

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('faq/edit', $data);

		$this->load->view('common/footer');		

	}

	public function update()

	{

		$input_data =  $this->input->post();

		$this->form_validation->set_rules($this->validation_rules['Add']);

		if($this->form_validation->run())

		{

			if (!empty($_FILES['image']['name']) ) {

					$this->load->library('upload');

				    $dataInfo = array();

				    $files = $_FILES;

				    $cpt = count($_FILES['image']['name']);

				        $_FILES['image']['name']= $files['image']['name'];

				        $_FILES['image']['type']= $files['image']['type'];

				        $_FILES['image']['tmp_name']= $files['image']['tmp_name'];

				        $_FILES['image']['error']= $files['image']['error'];

				        $_FILES['image']['size']= $files['image']['size'];    

				        $this->upload->initialize($this->set_upload_options());

				        $this->upload->do_upload('image');

				        $dataInfo = $this->upload->data();

				        $image = $dataInfo['file_name'];
				      	$configer =  array(
			              'image_library'   => 'gd2',
			              'source_image'    =>  UPLOAD_IMAGES.'/site_upload/faq_images/'.$image,
			              'maintain_ratio'  =>  faLSE,
			              'width'           =>  IMG_WIDTH_FAQ,
			              'height'          =>  IMG_HEIGHT_FAQ,
			            );
					   $this->image_lib->clear();
			           $this->image_lib->initialize($configer);
			           $this->image_lib->resize();
				      

				 $insert['image']   = $dataInfo['file_name'];

								

			}else{

				

			}

			$insert['category_name'] =  $input_data['category_name'];

			$insert['title'] =  $input_data['title'];

			$insert['permalink'] =  $input_data['permalink'];

			$insert['status'] =  $input_data['status'];

			$insert['status'] =  $input_data['status'];

			$insert['order_by'] =  $input_data['order_by'];

			$this->db->where('id',$input_data['id']);

			$this->db->Update('ks_faq_category',$insert);

			$message = '<div class="alert alert-success">FAQ category has been successfully updated.</p></div>';

			$this->session->set_flashdata('success', $message);

			redirect('Faq');

		}else{

			redirect('Faq/edit');

		}

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

		

		

		//print_r($data['result']);die;

		

		$data['paginator'] = $paginator;

		

			

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('device/view', $data);

		$this->load->view('common/footer');		

	}

	

	public function faq_query($value='')

	{

		$data= array();

		$query = $this->common_model->GetAllWhere('ks_faq_category',array('status'=>'Y'));

		$data['faq_category'] = $query->result();

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('faq/add-faq', $data);

		$this->load->view('common/footer');	

	}

	public function saveFaq()

	{

		$input_data =  $this->input->post();

		

		$this->form_validation->set_rules($this->validation_rules['Add1']);

		if($this->form_validation->run())

		{

			

			if (!empty($_FILES['image']['name']) ) {

					$this->load->library('upload');

				    $dataInfo = array();

				    $files = $_FILES;

				    $cpt = count($_FILES['image']['name']);

				        $_FILES['image']['name']= $files['image']['name'];

				        $_FILES['image']['type']= $files['image']['type'];

				        $_FILES['image']['tmp_name']= $files['image']['tmp_name'];

				        $_FILES['image']['error']= $files['image']['error'];

				        $_FILES['image']['size']= $files['image']['size'];    

				        $this->upload->initialize($this->set_upload_options());

				        $this->upload->do_upload('image');

				        $dataInfo = $this->upload->data();

				      	$image = $dataInfo['file_name'];
				      	$configer =  array(
			              'image_library'   => 'gd2',
			              'source_image'    =>  UPLOAD_IMAGES.'/site_upload/faq_images/'.$image,
			              'maintain_ratio'  =>  faLSE,
			              'width'           =>  IMG_WIDTH_FAQ,
			              'height'          =>  IMG_HEIGHT_FAQ,
			            );
					   $this->image_lib->clear();
			           $this->image_lib->initialize($configer);
			           $this->image_lib->resize();

				 	$image = $dataInfo['file_name'];

								

			}else{

				$image = '' ;

			}

			$insert = array('faq_question'=> $input_data['faq_question'],

							'category_id'=>$input_data['category_id'],

							'title'=>$input_data['title'],														'permalink'=>$input_data['permalink'],							

							'writer_name'=>$input_data['writer_name'],

							'order_by'=>$input_data['order_by'],

							'description'=>htmlspecialchars($input_data['gear_desc_1']),

							'status'=> $input_data['status'],

							'create_date' =>date('Y-m-d'),

							'image'=> $image

							);

			$this->common_model->AddRecord('ks_faq',$insert);

			$message = '<div class="alert alert-success">FAQ category has been successfully added.</p></div>';

			$this->session->set_flashdata('success', $message);

			redirect('Faq/Faqlist');

		}else{

			//echo "byee";die;

			redirect('Faq/faq_query');

		}	

	}

	

	public function Faqlist()

	{

		$data=array();

		

		$where =' ';

		

		if($this->input->get('limit') != ''){

				$data['limit']	= $this->input->get('limit');

				$limit  =  $this->input->get('limit'); 

		}

		else{

			$data['limit']	= 25;

			$limit  = 25;

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

		

		$nSer="SELECT a.*, category.category_name FROM ks_faq As a  INNER JOIN  ks_faq_category As category ON a.category_id =category.id

		".$where." ORDER BY a.order_by";

		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";

		$result=$this->db->query($sql);		

		$total_rows=count($this->db->query($nSer)->result());	

		$data['result'] = $result;

		$data['total_rows']=$total_rows;

		$data['limit']=$limit;

		$config['total_rows'] = $total_rows;
		$config['base_url'] = base_url()."faq/faqlist/?&limit=".$limit;
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



		//echo $this->db->last_query(); exit();

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('faq/Faqlist', $data);

		$this->load->view('common/footer');		

	}



	public function editfaq($id)

	{

		$data=array();

		

		$id = $this->uri->segment(3);

		$where_array = array('faq_id'=>$id);

		$device= $this->common_model->GetAllWhere('ks_faq',$where_array);

		$data['result'] = $device->row();

		$query = $this->common_model->GetAllWhere('ks_faq_category',array('status'=>'Y'));

		$data['faq_category'] = $query->result();

		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('faq/editfaq', $data);

		$this->load->view('common/footer');	

	}

	public function updateFaq()

	{

		$input_data =  $this->input->post();

		

		$this->form_validation->set_rules($this->validation_rules['Add1']);

		if($this->form_validation->run())

		{

			

			if (!empty($_FILES['image']['name']) ) {

					$this->load->library('upload');

				    $dataInfo = array();

				    $files = $_FILES;

				    $cpt = count($_FILES['image']['name']);

				        $_FILES['image']['name']= $files['image']['name'];

				        $_FILES['image']['type']= $files['image']['type'];

				        $_FILES['image']['tmp_name']= $files['image']['tmp_name'];

				        $_FILES['image']['error']= $files['image']['error'];

				        $_FILES['image']['size']= $files['image']['size'];    

				        $this->upload->initialize($this->set_upload_options());

				        $this->upload->do_upload('image');

				        $dataInfo = $this->upload->data();

				      
				        $image = $dataInfo['file_name'];
				      	$configer =  array(
			              'image_library'   => 'gd2',
			              'source_image'    =>  UPLOAD_IMAGES.'/site_upload/faq_images/'.$image,
			              'maintain_ratio'  =>  faLSE,
			              'width'           =>  IMG_WIDTH_FAQ,
			              'height'          =>  IMG_HEIGHT_FAQ,
			            );
					   $this->image_lib->clear();
			           $this->image_lib->initialize($configer);
			           $this->image_lib->resize();
				 	$insert['image'] = $dataInfo['file_name'];

								

			}else{

				

			}

			

			$insert['category_id'] =  $input_data['category_id'];

			$insert['title'] =  $input_data['title'];						$insert['permalink'] =  $input_data['permalink'];															

			$insert['faq_question'] =  $input_data['faq_question'];

			$insert['writer_name'] =  $input_data['writer_name'];

			$insert['description'] =  htmlspecialchars($input_data['gear_desc_1']);

			$insert['faq_question'] =  $input_data['faq_question'];

			$insert['order_by'] =  $input_data['order_by'];

			$insert['updated_date'] =  date('Y-m-d');

			$insert['status'] =  $input_data['status'];

						

			$this->db->where('faq_id',$input_data['faq_id']);

			$this->db->Update('ks_faq',$insert);

			

			$message = '<div class="alert alert-success">FAQ  has been successfully added.</p></div>';

			$this->session->set_flashdata('success', $message);

			redirect('Faq/Faqlist');

		}else{

			//echo "byee";die;

			redirect('Faq/faq_query');

		}	

	}

	public function delete_record_faq()

	{

		$id=$this->uri->segment(3);

		$where_array = array('faq_id'=>$id);

		

		$this->common_model->delele('ks_faq','faq_id',$id);

		

		$message = '<div class="alert alert-success"><p>FAQ  has been deleted successfully.</p></div>';

		$this->session->set_flashdata('success', $message);

		redirect('Faq/Faqlist');

	}

	public function select_delete_faq()

	{

		if(isset($_POST['bulk_delete_submit']))

		{

			$idArr = $this->input->post('checked_id');

			foreach($idArr as $id)

			{

     			$this->common_model->delele('ks_faq','faq_id',$id);

    		}

		$message = '<div class="alert alert-success"><p>FAQ  have been deleted successfully.</p></div>';

		$this->session->set_flashdata('success', $message);

		redirect('Faq/faqlist');

		}

	}

	



	

	

	

	public function select_delete()

	{

		if(isset($_POST['bulk_delete_submit']))

		{

			$idArr = $this->input->post('checked_id');

			foreach($idArr as $id)

			{

     			$this->common_model->delele('ks_faq_category','id',$id);

    		}

		$message = '<div class="alert alert-success"><p>FAQ Category have been deleted successfully.</p></div>';

		$this->session->set_flashdata('success', $message);

		redirect('Faq');

		}

	}

	

	

	public function delete_record()

	{

		$id=$this->uri->segment(3);

		$where_array = array('id'=>$id);

		

		$this->common_model->delele('ks_faq_category','id',$id);

		

		$message = '<div class="alert alert-success"><p>FAQ Category has been deleted successfully.</p></div>';

		$this->session->set_flashdata('success', $message);

		redirect('Faq');

	}
	public function faqOrderByUpdate()
	{
		$input_data =  $this->input->post();
		$this->db->where('faq_id',$input_data['faq_id']);
		$this->db->Update('ks_faq',$input_data);
		$message = '<div class="alert alert-success"><p>FAQ Category Order has been updated successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		echo  json_encode("1");
	}
	

	public function permalinks()
	{
		$device  = $this->common_model->GetAllWhere('ks_faq',array());

		$result = $device->result();
		echo "<pre>";
		print_r($result);
		foreach ($result as $value) {
			
				print_r($value);
				echo "<br>";
				print_r($value->title);
				echo "<br>";
				$input = preg_replace("/[^a-zA-Z]+/", " ", $value->title);
				$input =trim($input) ;
				echo $permalink =  str_replace(' ', '-', $input);
				$insert = array(
								'permalink'=>$permalink .'-'.$value->faq_id
							);

				$this->db->where('faq_id',$value->faq_id);

				$this->db->Update('ks_faq',$insert);

			
		}
	}

	public function UploadImageSize()
	{
			echo $directory = UPLOAD_IMAGES."site_upload/faq_images/";
			die;
			$images = glob($directory . "/*");
			$this->load->library('upload');
			foreach($images as $image)
			{
			  // echo $image;
			  $images =  explode('faq_images//', $image);
			  print_r($images[1]);
			  $images_change = $images[1] ;
			  // echo "<br>";die;
			  $configer =  array(
			              'image_library'   => 'gd2',
			              'source_image'    =>  UPLOAD_IMAGES.'/site_upload/faq_images/'.$images_change,
			              'maintain_ratio'  =>  faLSE,
			              'width'           =>  IMG_WIDTH_FAQ,
			              'height'          =>  IMG_HEIGHT_FAQ,
			            );
					   $this->image_lib->clear();
			           $this->image_lib->initialize($configer);
			           $this->image_lib->resize();
			}
	}

}?>

