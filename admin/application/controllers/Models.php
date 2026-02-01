<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Models extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','pagination','email','excel'));
		$this->load->model(array('common_model','mail_model','model'));
		if($this->session->userdata('ADMIN_ID') =='') {
		  redirect('login');
		}
	}
	
	protected $validation_rules = array
        (
		'Add' => array(
            array(
                'field' => 'model',
                'label' => 'Model Name',
                'rules' => 'trim|required'
            )
        ),
    );

	
	public function index()
	{
		$data=array();
		$where = " ";
		if($this->input->get('limit') != ''){
				$data['limit']	= $this->input->get('limit');
		}
		else{
			$data['limit']	= 25;
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
		
		
		$data['model']				= $this->input->get('model');
		if($data['model'] != ''){
				$where .=  "a.model_name LIKE '%".htmlspecialchars(trim($data['model']),ENT_QUOTES)."%' AND ";
		}
			
		if($this->input->get('order_by_fld')!=""){
			if ($this->input->get('order_by_fld') == 'create_date') {
					$order_by_fld = 'a.create_date';
			}else{
			  $order_by_fld = $this->input->get('order_by_fld');
			}  
		}else
			$order_by_fld = "c.manufacturer_name";
		
		
			$data['order_by_fld']=$order_by_fld;
		/*$nSer="SELECT * FROM ks_models ".$where." ORDER BY model_name ".$order_by;
		$sql=$nSer." LIMIT ".$data['limit']." OFFSET  ".$offset." ";*/
		
		
		// $where .= "a.is_active='Y' AND b.is_active='Y' AND c.is_active='Y'";
		$where .= "a.is_active='Y'";
		$query = $this->model->getModelsList($where,$data['limit'],$offset,$order_by_fld,$order_by);
		$query1 = $this->model->getModelsList($where);
		$total_rows=$query1->num_rows();
		
		$data['result'] = $query;
		$data['total_rows']=$total_rows;
		$data['limit']=$data['limit'];
		$config['base_url'] = base_url()."models/?model=".$data['model']."&order_by=".$order_by."&order_by_fld=".$order_by_fld."&limit=".$data['limit'];
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
		//print_r($this->db->last_query());die;
		//////////////////////////////Pagination config//////////////////////////////////				

		$data['paginator'] = $paginator;
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('models/list', $data);
		$this->load->view('common/footer');		
	}
	public function add()
	{
		$data=array();
		
		$gear_category = $this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y' , 'gear_sub_category_id'=>'0'));
		$data['gear_category'] =  $gear_category->result();
		// $this->common_model->GetAllWhere("",array());


		$this->db->select('*') ;
		$this->db->from('ks_manufacturers') ;
		$this->db->where("is_active",'Y') ;
		$this->db->order_by('manufacturer_name', 'ASC');
		$manufacturer = $this->db->get();
		$manufacturer=  $manufacturer->result();
	 	$data['manufacturer']  = $manufacturer ;
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('models/add', $data);
		$this->load->view('common/footer');	
	}
	public function save()
	{
		$data=array();
	
		$this->form_validation->set_rules($this->validation_rules['Add']);
	
		if($this->form_validation->run())
		{
			
				$newname= time();
				$filePath                = MODEL_IMAGE_ORIGINAL;
				$config['upload_path']   = $filePath;
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['file_name']     = $newname;
				$config['max_size']      = "";
				$config['max_width']     = "";
				$config['max_height']    = "";
				
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				
				if (!$this->upload->do_upload('model_image'))
				{
				
					$error = array('error' => $this->upload->display_errors());		
				}
				else
				{	
					
					$imgdata = array('upload_data' => $this->upload->data());
					$data['model_image']=$imgdata['upload_data']['file_name'];
					$images_change = $imgdata['upload_data']['file_name'] ;
					$imagePath =  MODEL_IMAGE_ORIGINAL.'/'.$images_change;
					$newPath = UPLOAD_IMAGES.'/site_upload/model_images/';
					$ext = '.jpg';
					$newName  = $images_change;
					$copied = copy($imagePath , $newPath.$newName);
				 	$configer =  array(
			              'image_library'   => 'gd2',
			              'source_image'    =>  UPLOAD_IMAGES.'/site_upload/model_images/'.$images_change,
			              'maintain_ratio'  =>  true,
			              'width'           =>  IMG_WIDTH,
			              'height'          =>  IMG_HEIGHT,
			            );
					   $this->image_lib->clear();
			           $this->image_lib->initialize($configer);
			           $this->image_lib->resize();
				}
				$query = $this->common_model->GetAllWhere('ks_settings', '');
				$website_settings = $query->row();

				$data['model_name']= htmlspecialchars($this->input->post('model'),ENT_QUOTES);
				$data['manufacturer_id']=$this->input->post('manufacturer_id');
				$data['gear_category_id']=$this->input->post('gear_category_id');
				$data['gear_sub_category_id']=$this->input->post('gear_sub_category_id');
				$data['model_description']=$this->input->post('model_description');
				
				
				$is_active= $this->input->post('status');
				if($is_active=='Y')
				$data['is_active']='Y';
				else
				$data['is_active']='N';
				//echo $this->session->userdata('ADMIN_ID');die;
				$data['create_date']= date('Y-m-d') ;
				$data['create_user'] = $this->session->userdata('ADMIN_ID');
				$data['add_model_requested_by'] = $this->session->userdata('ADMIN_ID');
				
				$data['per_day_cost_usd']= $this->input->post('per_day_cost_usd');
				$data['per_day_cost_aud_ex_gst']=$this->input->post('per_day_cost_aud_ex_gst');
				$data['per_day_cost_aud_inc_gst']=$this->input->post('per_day_cost_aud_ex_gst') + ($this->input->post('per_day_cost_aud_ex_gst') * $website_settings->gst_percent/100);
				$data['per_weekend_cost_usd']=$this->input->post('per_day_cost_usd')*3;
				
				$data['per_weekend_cost_aud_ex_gst']= $this->input->post('per_day_cost_aud_ex_gst')*1;
				$data['per_weekend_cost_aud_inc_gst']=$this->input->post('per_day_cost_aud_ex_gst')*1 +($this->input->post('per_day_cost_aud_ex_gst')*1*$website_settings->gst_percent/100) ;
				$data['per_week_cost_usd']=$this->input->post('per_day_cost_usd')*3;
				$data['per_week_cost_aud_ex_gst']=$this->input->post('per_week_cost_aud_ex_gst')*3;
				
				$data['per_week_cost_aud_inc_gst']= $this->input->post('per_week_cost_aud_ex_gst')*3 +($this->input->post('per_week_cost_aud_ex_gst')*3*$website_settings->gst_percent/100);
				$data['add_model_requested_by']=$this->input->post('add_model_requested_by');
				$data['replacement_value_aud_ex_gst']=$this->input->post('replacement_value_aud_ex_gst');
				$data['replacement_value_aud_inc_gst']=$this->input->post('replacement_value_aud_ex_gst') + ($this->input->post('replacement_value_aud_ex_gst')*$website_settings->gst_percent/100);
				$data['replacement_day_rate_percent']=$this->input->post('replacement_day_rate_percent') ;
				$data['is_approved']=$this->input->post('is_approved');
				$data['add_model_requested_by'] = $this->session->userdata('ADMIN_ID');
				
				if($data['is_approved']=='Y'){
					$data['approved_on']=date('Y-m-d');
				}
				
			$this->common_model->addRecord('ks_models',$data);
			$message = '<div class="alert alert-success">Model has been successfully added.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('models');
						
		}
		
		else
		{
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');					
			$this->load->view('models/add', $data);
			$this->load->view('common/footer');	
		}
	
	}
	public function edit()
	{
	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('model_id'=>$id);
		$res= $this->common_model->GetAllWhere('ks_models',$where_array);	
		$data['result'] =  $res->result();
		
		//print_r($data['result']);
		
		$gear_category = $this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y' , 'gear_sub_category_id'=>'0'));
		$data['gear_category'] =  $gear_category->result();
		$gear_category = $this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y' , 'gear_category_id'=>$data['result'][0]->gear_sub_category_id));
		$data['gear_sub_category'] =  $gear_category->result();
		$manufacturer = $this->common_model->GetAllWhere("ks_manufacturers",array("is_active"=>'Y'));
		$data['manufacturer'] =  $manufacturer->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('models/edit', $data);
		$this->load->view('common/footer');		
	}
	public function update()
	{
		$data = array();
    	$id = $this->input->post('model_id');
		
		//Transaction begins
		$this->db->trans_begin();		
		
		
		$where_array = array('model_id'=>$id);
		$res= $this->common_model->GetAllWhere('ks_models',$where_array);	
		$data['result'] =  $res->result();
		
		$gear_category = $this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y'));
		$data['gear_category'] =  $gear_category->result();
		
		$manufacturer = $this->common_model->GetAllWhere("ks_manufacturers",array("is_active"=>'Y'));
		$data['manufacturer'] =  $manufacturer->result();
   
		$this->form_validation->set_rules($this->validation_rules['Add']);

		if($this->form_validation->run() == true )
		{
		
			$newname= time();
			$filePath                = MODEL_IMAGE_ORIGINAL;
			$config['upload_path']   = $filePath;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['file_name']     = $newname;
			$config['max_size']      = "";
			$config['max_width']     = "";
			$config['max_height']    = "";
			
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			
			if (!$this->upload->do_upload('model_image'))
			{
			
				$error = array('error' => $this->upload->display_errors());		
			}
			else
			{	
				
				$imgdata = array('upload_data' => $this->upload->data());
				$images_change = $imgdata['upload_data']['file_name'] ;
				$imagePath =  MODEL_IMAGE_ORIGINAL.'/'.$images_change;
				$newPath = UPLOAD_IMAGES.'/site_upload/model_images/';
				// $ext = '.jpg';
				$newName  = $images_change;
				$copied = copy($imagePath , $newPath.$newName);
				 $configer =  array(
			              'image_library'   => 'gd2',
			              'source_image'    =>  UPLOAD_IMAGES.'/site_upload/model_images/'.$images_change,
			              'maintain_ratio'  =>  true,
			              'width'           =>  IMG_WIDTH,
			              'height'          =>  IMG_HEIGHT,
			            );
					   $this->image_lib->clear();
			           $this->image_lib->initialize($configer);
			           $this->image_lib->resize();
				
			}
		if(!empty($imgdata))
		{
			$where_array = array('model_id'=>$id);
			$image= $this->common_model->get_all_record('ks_models',$where_array);
			foreach($image as $img){
				$this->load->helper("file");
				$oldfile = MODEL_IMAGE."/".$img->model_image ; 
				//unlink($oldfile);
			}
			$row['model_image'] = $imgdata['upload_data']['file_name'];
		}
		
		$query = $this->common_model->GetAllWhere('ks_settings', '');
				$website_settings = $query->row();
		$row['model_name']= htmlspecialchars($this->input->post('model'),ENT_QUOTES);
		$row['manufacturer_id']=$this->input->post('manufacturer_id');
		$row['gear_category_id']=$this->input->post('gear_category_id');
		$row['model_description']=$this->input->post('model_description');
		$row['is_active']= $this->input->post('status');	
		$row['update_date']= date('Y-m-d') ;
		$row['update_user'] = $this->session->userdata('ADMIN_ID');
		
		
		
		
		
		$row['per_day_cost_usd']= $this->input->post('per_day_cost_usd');
		$row['per_day_cost_aud_ex_gst']=$this->input->post('per_day_cost_aud_ex_gst');
		$row['per_day_cost_aud_inc_gst']=$this->input->post('per_day_cost_aud_ex_gst') + ($this->input->post('per_day_cost_aud_ex_gst') * $website_settings->gst_percent/100);
		$row['per_weekend_cost_usd']=$this->input->post('per_day_cost_usd')*3;
		
		$row['per_weekend_cost_aud_ex_gst']= $this->input->post('per_day_cost_aud_ex_gst')*1;
		$row['per_weekend_cost_aud_inc_gst']=$this->input->post('per_day_cost_aud_ex_gst')*1 +($this->input->post('per_day_cost_aud_ex_gst')*1*$website_settings->gst_percent/100) ;
		$row['per_week_cost_usd']=$this->input->post('per_day_cost_usd')*3;
		$row['per_week_cost_aud_ex_gst']=$this->input->post('per_week_cost_aud_ex_gst')*3;
		
		$row['per_week_cost_aud_inc_gst']= $this->input->post('per_week_cost_aud_ex_gst')*3 +($this->input->post('per_week_cost_aud_ex_gst')*3*$website_settings->gst_percent/100);
		$row['add_model_requested_by']=$this->input->post('add_model_requested_by');
		$row['replacement_value_aud_ex_gst']=$this->input->post('replacement_value_aud_ex_gst');
		$row['replacement_value_aud_inc_gst']=$this->input->post('replacement_value_aud_ex_gst') + ($this->input->post('replacement_value_aud_ex_gst')*$website_settings->gst_percent/100);
		$row['replacement_day_rate_percent']=$this->input->post('replacement_day_rate_percent') ;
		$row['gear_sub_category_id']=$this->input->post('gear_sub_category_id');
		$row['add_model_requested_by']=$this->input->post('add_model_requested_by');
		$row['is_approved']=$this->input->post('is_approved');
		if($row['is_approved']=='Y'){
			$row['approved_on']=date('Y-m-d');
		}
		
		$this->db->where('model_id', $id);
		$this->db->update('ks_models', $row); 
		
		//Search table is updated with model name
		$search_array = array();
		$search_array['model_name'] = $row['model_name'];
		
		$this->db->where('model_id', $id);
		$this->db->update('ks_user_gear_search', $search_array); 
		
		//Transaction ends here
		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$message = '<div class="alert alert-danger"><p>Action failed! Please try again.</p></div>';
		}
		else
		{
				$this->db->trans_commit();
				$message = '<div class="alert alert-success"><p>Model updated successfully.</p></div>';
		}
		
		$this->session->set_flashdata('success', $message);
		redirect('models');
	
		}
		else
		{	
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('models/edit', $data);
			$this->load->view('common/footer');	
		}
	
	}
	public function select_delete()
	{
	if(isset($_POST['bulk_delete_submit']))
	{
    $idArr = $this->input->post('checked_id');
    foreach($idArr as $id){
	        
			$where_array = array('model_id'=>$id);
			$lang= $this->common_model->get_all_record('ks_models',$where_array);
			
			foreach($lang as $im){
				$this->load->helper("file");
				$oldfile = model."/".$im->model_logo;
				unlink($oldfile);
			}
			
     		$this->common_model->delele('ks_models','model_id',$id);
			
    	}
		$message = '<div class="alert alert-success"><p>Models have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('models');
	 }
	}
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		$where_array = array('model_id'=>$id);
		$model= $this->common_model->get_all_record('ks_models',$where_array);
		foreach($model as $im){
			$this->load->helper("file");
			$oldfile = model."/".$im->model_logo;
			unlink($oldfile);
		}
		
		$this->common_model->delele('ks_models','model_id',$id);
		
		$message = '<div class="alert alert-success"><p>Models have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('models');
	}
	
	public function downloadModelList(){
		$this->load->library(array('excel'));
		 $this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Model List');
		//set cell A1 content with some text
		$this->excel->getActiveSheet()->setCellValue('A1', 'Manufacturer');
		$this->excel->getActiveSheet()->setCellValue('B1', 'Model');
		$this->excel->getActiveSheet()->setCellValue('C1', 'Description');
		$this->excel->getActiveSheet()->setCellValue('D1', 'Price per Day (USD)');
		$this->excel->getActiveSheet()->setCellValue('E1', 'Price Per Day (AUD ex GST)');
		$this->excel->getActiveSheet()->setCellValue('F1', 'Price per Day (inc GST)');
		$this->excel->getActiveSheet()->setCellValue('G1', 'Replacement Value (USD)');
		$this->excel->getActiveSheet()->setCellValue('H1', 'Replacement Value (AUD ex GST)');
		$this->excel->getActiveSheet()->setCellValue('I1', 'Replacement Value (AUD inc GST)');
		$this->excel->getActiveSheet()->setCellValue('J1', 'Replacement/Day rate %');
		$this->excel->getActiveSheet()->setCellValue('K1', 'Image Name');
		$this->excel->getActiveSheet()->setCellValue('L1', 'Category');
		$this->excel->getActiveSheet()->setCellValue('M1', 'Sub Category');
		
		
		
		
		for($col = ord('A'); $col <= ord('M'); $col++){ //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
			
			$this->excel->getActiveSheet()->getStyle(chr($col)."1")->getFont()->setBold(true);
			
			 //change the font size
			$this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
			 
		}


		$where = '';
		//Model lists are fetched
		$where = "a.is_active='Y' AND b.is_active='Y' AND c.is_active='Y'";
		$order_by = "ASC";
		$order_by_fld = "c.manufacturer_name";
		$query = $this->model->getModelsList($where,0,0,$order_by_fld,$order_by);
		
		$result = "";
		
		if($query->num_rows()>0){
			
			$result = $query->result_array();
			$exceldata = array();

			foreach($result as $row){
				
				//Parent category names are fetched
				$parent_cat_query = $this->model->parent_category($row['gear_category_id']);
				$cat_result = $parent_cat_query->result();
				
				$parent_category_name = $cat_result[0]->gear_category_name;
				
			
				$exceldata[]=array(
							 stripslashes($row['manufacturer_name']),
							 htmlspecialchars_decode(stripslashes($row['model_name']),ENT_QUOTES),
							 stripslashes($row['model_description']),
							 stripslashes($row['per_day_cost_usd']),
							 stripslashes($row['per_day_cost_aud_ex_gst']),
							 stripslashes($row['per_day_cost_aud_inc_gst']),
							 stripslashes($row['replacement_value_usd']),
							 stripslashes($row['replacement_value_aud_ex_gst']),
							 stripslashes($row['replacement_value_aud_inc_gst']),
							 stripslashes($row['replacement_day_rate_percent']),
							 stripslashes($row['model_image']),
							 htmlspecialchars_decode(stripslashes($row['gear_category_name']),ENT_QUOTES), 
							 htmlspecialchars_decode(stripslashes($row['gear_sub_category_name']),ENT_QUOTES)
							 );
							 
			}
					
			$this->excel->getActiveSheet()->fromArray($exceldata, null, 'A4');
			$filename='ModelList.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			error_reporting(0);
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
			//force user to download the Excel file without writing it to server's HD
			// echo "<pre>";
			// print_r($exceldata);die;
			$objWriter->save('php://output');
		
		
		}
	
	}
	public function getsubcategory(){
		
		$gear_category_id =  $this->input->post('gear_category_id');
		$where_array = array('gear_sub_category_id'=>$gear_category_id);
			$gear_list= $this->common_model->get_all_record(' ks_gear_categories',$where_array);
			//print_r($gear_list);
			$html = '';
			if(count($gear_list)> 0 ){
				foreach($gear_list as $gear){
				
					$html .= '<option value="'.$gear->gear_category_id.'">'.$gear->gear_category_name.'</option>';
				}
			
			}else{
					$html .= '<option value="0">Not Found</option>';
			}
		
		echo $html;
	}
	
	public function updateModelName(){
	
		$query = $this->common_model->GetAllWhere('ks_models','');
		$res = $query->result_array();
		foreach($res as $row){
		
			//$model = htmlspecialchars_decode(stripslashes($row['model_name']),ENT_QUOTES);
		
			$model = htmlspecialchars($row['model_name'],ENT_QUOTES);
		
			$update_array['model_name']= $model;
			
			echo $update_array['model_name'];
			echo "<br />";
			
			$this->common_model->UpdateRecord_2($update_array,"ks_models","model_id",$row['model_id']);
			
			echo $this->db->last_query();
			
			echo "<br />";
			
		}
		
	
	}
	
	public function UpdateModelSize()
	{
		ini_set('max_execution_time', 3600);
		$directory = UPLOAD_IMAGES."site_upload/model_images/";
			$images = glob($directory . "/*");
			$this->load->library('upload');
			foreach($images as $image)
			{
			  // echo $image;
			  $images =  explode('model_images//', $image);
			  // print_r($images[1]);
			  $images_change = $images[1] ;
			  // echo "<br>";die;
			  $configer =  array(
			              'image_library'   => 'gd2',
			              'source_image'    =>  UPLOAD_IMAGES.'/site_upload/model_images/'.$images_change,
			              'maintain_ratio'  =>  true,
			              'width'           =>  IMG_WIDTH,
			              'height'          =>  IMG_HEIGHT,
			            );
					   $this->image_lib->clear();
			           $this->image_lib->initialize($configer);
			           $this->image_lib->resize();
			}
	}
	
	
	
}?>