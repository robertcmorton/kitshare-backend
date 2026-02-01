<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gear_categories1 extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text','common_helper'));
		$this->load->library(array('session','form_validation','pagination','email'));
		$this->load->model(array('common_model','mail_model','model'));
		if($this->session->userdata('ADMIN_ID') =='') {
          redirect('login');
		  }
	}
	
	protected $validation_rules = array
    (
		'Add' => array(
            array(
                'field' => 'gear_category_name',
                'label' => 'Category',
                'rules' => 'trim|required'
            )
			
        ),
    );

	public function index()
	{
		 $id=$this->uri->segment(3);
		
	    $data=array();
		
		$where ='';

		$limit=10;

		$data['gear_category_name']		  = urldecode($this->input->get('gear_category_name'));
		
		if($data['gear_category_name'] != ''){
				$where .= "a.gear_category_name LIKE '%".trim($data['gear_category_name'])."%' AND";
		}
		
		if($id!="")
			$where .= array('a.gear_sub_category_id'=>$id);
		else
			$where .= "(a.`gear_sub_category_id` IS NULL OR a.`gear_sub_category_id`=0)";
		
		if($this->input->get("per_page")!= '')
		{
			$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
				 
		$n=$this->model->gear_categories($where);
		$sql=$this->model->gear_categories($where,$limit,$offset);
		
		//echo $this->db->last_query();
				
		$result=$sql->result();	
		if($id!="")
			$base_url = base_url()."gear_categories1/index/".$id."?gear_category_name=".$data['gear_category_name']."";
		else
			$base_url =  base_url()."gear_categories1?gear_category_name=".$data['gear_category_name']."";
		
		$total_rows=$n->num_rows();	
		$data['gear'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = $base_url;
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

		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('gear_categories1/list', $data);
		$this->load->view('common/footer');		

	

	}
	public function add()
	{
		$data=array();
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>'0'));
		$data['gear_categories'] = $gear_categories->result();
		
		$data['cnt']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y')); 
		
		$this->load->library('user_agent');
		$data['refer'] =  $this->agent->referrer();
		
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('gear_categories1/add', $data);
		$this->load->view('common/footer');	
	}
	public function save()
	{
		$data=array();
		$id = $this->uri->segment(3);
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_category_id'=>$id));
		$data['gear_categories'] = $gear_categories->result();
		//print_r(url_link);
		$url_link =$this->input->post('url_link');
		
		$this->form_validation->set_rules($this->validation_rules['Add']);
		
			if($this->form_validation->run())
			{
				$y=$this->input->post('sub_cat');
				$row['gear_category_name']= $this->input->post('gear_category_name');
				$row['average_value']= $this->input->post('average_value');
				$row['security_deposit']= $this->input->post('security_deposit');
				$row['is_active']= 'Y';
				$row['create_user']= $this->session->userdata('ADMIN_ID');	
				$row['create_date']= date('Y-m-d') ;
				$cnt=$y-1;
				if($y==0)
				$row['gear_sub_category_id']= '0';
				else
					$row['gear_sub_category_id']= $this->input->post('gear_category_id_'.$y);
				$this->common_model->addRecord('ks_gear_categories',$row);
				$message = '<div class="alert alert-success">Gear Category has been successfully added.</p></div>';
				$this->session->set_flashdata('success', $message);
				redirect($url_link);
							
			}
			else
			{
				$this->load->view('common/header');	
				$this->load->view('common/left-menu');					
				$this->load->view('gear_categories1/add', $data);
				$this->load->view('common/footer');	
			}
		
	
	}
	public function edit()
	{
		$data=array();
		
		$id = $this->uri->segment(3);
		$where_array = array('a.gear_category_id'=>$id);
		$categories= $this->model->gear_categories($where_array);
		$data['result'] = $categories->result();
		
		//print_r($data['result']);exit();
		
		$where_array = "is_active='Y' AND (gear_sub_category_id=0 OR gear_sub_category_id IS NULL)";
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",$where_array);
		$data['gear_categories'] = $gear_categories->result();
		
		$data['cnt']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y')); 
		
			$this->load->library('user_agent');
		$data['refer'] =  $this->agent->referrer();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('gear_categories1/edit', $data);
		$this->load->view('common/footer');		
	}
	
	public function uploadpic()
	{
		$data=array();
		
		$id = $this->uri->segment(3);
		$where_array = array('gear_category_id'=>$id);
		$categories= $this->model->gear_categories($where_array);
		$data['result'] = $categories->result();
		
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>0));
		$data['gear_categories'] = $gear_categories->result();
		$data['error']='';
		
			
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('gear_categories1/uploadpic', $data);
		$this->load->view('common/footer');		
	}
	
	public function view()
	{
		$data=array();
		
		$id = $this->uri->segment(3);
		$where_array = array('gear_category_id'=>$id);
		$gear_categories= $this->model->gear_categories($where_array);
		$data['result'] = $gear_categories->result();
		
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>0));
		$data['gear_categories'] = $gear_categories->result();
		
			
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('gear_categories1/view', $data);
		$this->load->view('common/footer');		
	}
	
	
	public function update()
	{
		$data = array();
		
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>0));
		$data['gear_categories'] = $gear_categories->result();
		
		//print_r( $this->input->post());
		$url = $this->input->post('url');
		
		//die;
    	  $id = $this->input->post('gear_category_id');
		
   
		$this->form_validation->set_rules($this->validation_rules['Add']);

		if($this->form_validation->run() == true )
		{
			if($this->input->post('gear_category_id_1') == ''){
				
				$gear_sub_category_id = '0';
			}else{
				$gear_sub_category_id = $this->input->post('gear_category_id_1');
			}
				$row['gear_category_name']= $this->input->post('gear_category_name');
				$row['gear_sub_category_id']= $gear_sub_category_id;
				$row['average_value']= $this->input->post('average_value');
				$row['security_deposit']= $this->input->post('security_deposit');
				$row['is_active']= $this->input->post('is_active');
				$row['update_user'] = $this->session->userdata('ADMIN_ID');
				$row['update_date'] = date('Y-m-d');
				
				$this->db->where('gear_category_id', $id);
				$this->db->update('ks_gear_categories', $row); 
				
				$message = '<div class="alert alert-success"><p>Gear Category Details updated successfully.</p></div>';
				$this->session->set_flashdata('success', $message);
				redirect($url);
	
		}
		else
		{	
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('gear_categories1/edit', $data);
			$this->load->view('common/footer');	
		}
	
	}
	public function do_upload() { 
         $config['upload_path']   = './admin/uploads/'; 
         $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
         $config['max_size']      = 100; 
         $config['max_width']     = 1040; 
         $config['max_height']    = 1040;  
         $this->load->library('upload', $config);
			
         if ( ! $this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors()); 
            $this->load->view('gear_categories/uploadpic', $error); 
         }
			
         else { 
            $data = array('upload_data' => $this->upload->data()); 
            $this->load->view('gear_categories/upload_success', $data); 
         } 
      } 
	
	
	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
			$this->load->library('user_agent');
		 $url =  $this->agent->referrer();
		
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id)
			{
	        
     		  	$this->common_model->delele('ks_gear_categories','gear_category_id',$id);
			
    	    }
		$message = '<div class="alert alert-success"><p>Gear categories have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect($url);
		}
	}
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		$this->load->library('user_agent');
		 $url =  $this->agent->referrer();
		$this->common_model->delele('ks_gear_categories','gear_category_id',$id);
		
		$message = '<div class="alert alert-success"><p>Gear category has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect($url);
	}

	public function ajax($id = '')
	{				
		
		$data=array();
		if($id != ''){
			$where_array = array('feature_master_id'=>$id);
			$device= $this->common_model->GetAllWhere("ks_feature_master",$where_array);
			$data['result'] = $device->row();
		}else{
				$data['result'] = '';
		}
		
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>$this->input->post('data')));
		$data['gear_categories'] = $gear_categories->result();
		$data['x'] =$this->input->post('data');
		$data['y'] = $this->input->post('count'); 
		$data['cnt']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>$this->input->post('data')));
		$data['cnt2']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y'));
		$this->load->view('gear_categories/ajax',$data);
	}
	
	public function ajax_edit_init()
	{				
		if($this->input->post('data')!=NULL)
		{
			$data=array();
			$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_category_id'=>$this->input->post('data')));
			$a = $gear_categories->result();
			$gear=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_category_id'=>$a[0]->gear_sub_category_id));
			$b = $gear->result();
			
			$data['gear_category']=$b;
			$data['x'] =$this->input->post('data');
			
			$this->load->view('gear_categories/ajax_edit_init',$data);
		}
		else
			exit();
	}
	
	public function ajax_edit()
	{				
		$data=array();
		$id=$this->uri->segment(3);
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>$this->input->post('data')));
		$data['gear_categories'] = $gear_categories->result();
		$data['x'] =$this->input->post('data');
		$data['y'] = $this->input->post('count'); 
		$data['cnt']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>$this->input->post('data')));
		$data['cnt2']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y'));
		$id = $this->uri->segment(3);
		$where_array = array('gear_category_id'=>$id);
		$categories= $this->model->gear_categories($where_array);
		$data['result'] = $categories->result();
		$this->load->view('gear_categories/ajax_edit',$data);
	}
	
	
	public function get_subcategory(){
	
		$cat_id = $this->input->post('gear_category_id');
		
		$where = "a.is_active='Y' AND a.gear_sub_category_id='".$cat_id."'";		
		$subcat_result = $this->model->gear_categories($where);
		
		$result = $subcat_result->result();
		
		$option = '<label for="exampleInputarticle">Category Name </label>';
		$option .= '<select name="gear_category_name" id="gear_category_name" class="form-control">';
		foreach($result as $row){
		
			$option .= '<option value="'.$row->gear_category_id.'">'.$row->gear_category_name.'</option>';
		
		}
		$option .= '</select>';
		
		echo $option;
		
	
	}
	
	public function import()
	{
		 $data = array();
		 $categories = array();
		 if($this->input->post()!='')
		 {
			      
				  $filename=$_FILES["file"]["tmp_name"];
				  if($_FILES["file"]["size"] > 0)
				  {
						$file = fopen($filename, "r");
						$count = 0;

						while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE)
						{
								//print_r($importdata);exit();
								
								if($count==0){
										
									$heading = $importdata;										
										
								}
									
								if($count>0){
								
									$cnt = count($importdata);
									
									for($i=0;$i<$cnt;$i++){
										
										if($i==0){
											$categories['gear_category_name'] = $importdata[$i+1];
											
											//Checked whether this category name exists or not
											$where_clause = array('gear_category_name'=>$categories['gear_category_name']);											
											$query = $this->common_model->GetAllWhere('ks_gear_categories',$where_clause);											
											$res_cat = $query->result_array();
											//print_r($res_cat);die;
											if(count($res_cat)>0){
												
												$data['gear_sub_category_id'] = $res_cat[0]['gear_category_id'];
												
											}else{ //if state doesn't exist then the record is inserted into the table
											
												$categories['create_user'] = $this->session->userdata['ADMIN_ID'];
												$categories['create_date'] = date("Y-m-d");
											
												$insert_id = $this->common_model->addRecord('ks_gear_categories',$categories);
												$data['gear_sub_category_id'] = $insert_id;
											
											}
											
										}else if($i==1){
										
											if($importdata[$i]!=""){
										
												$data['gear_category_name'] = $importdata[$i];
												
												//Checked whether this category name exists or not
												$where_clause = array('gear_category_name'=>$data['gear_category_name'],'gear_sub_category_id'=>$data['gear_sub_category_id']);											
												$query = $this->common_model->GetAllWhere('ks_gear_categories',$where_clause);											
												$res_subcat = $query->result_array();
												
												if(count($res_subcat)>0){
													
													$data['gear_sub_category_id'] = $res_subcat[0]['gear_category_id'];
													
												}else{ //if state doesn't exist then the record is inserted into the table
												
													$data['create_user'] = $this->session->userdata['ADMIN_ID'];
													$data['create_date'] = date("Y-m-d");
													echo "<pre>";
													print_r($data);	
													//$insert_id = $this->common_model->addRecord('ks_gear_categories',$data);
												
												}
											}											
											
										}else{
										
											//Feature are inserted into the feature table
											if($i==2 || $i==3 || $i==4 || $i==5 || $i==6 || $i==7 || $i==8 || $i==9 || $i==10 || $i==11 || $i==12){
											
												if($importdata[$i]!=""){
												
													$feature['feature_name'] = $importdata[$i];
													
													//Feature name is checked for existence
													$where_clause = array('feature_name'=>$feature['feature_name'],'gear_category_id'=>$data['gear_sub_category_id']);											
													$query = $this->common_model->GetAllWhere('ks_feature_master',$where_clause);											
													$res_feature = $query->result_array();
													if(count($res_feature)==0){
														
														$feature['gear_category_id'] = $data['gear_sub_category_id'];
														$feature['create_user'] = $this->session->userdata['ADMIN_ID'];
														$feature['create_date'] = date("Y-m-d");
														
														$insert_id = $this->common_model->addRecord('ks_feature_master',$feature);
													}
												}
												
											}
										}
										
									}
								
								}
								$count++;
								
								
								
				   }    
				   
				   	   fclose($file);
					   $message = '<div class="alert alert-success">Data imported successfully..</p></div>';
					   $this->session->set_flashdata('success', $message);
					   redirect('gear_categories');
			   }else{
				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';
				   $this->session->set_flashdata('success', $message);
				   redirect('gear_categories');
			   }
			}
	}
public function import1()
	{
		 $data = array();
		 $categories = array();
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
								 	$categories['gear_category_name'] = $importdata[1];
								 	if($importdata[1] !='Other'){
										$where_clause = array('gear_category_name'=>$categories['gear_category_name']);
										$query = $this->common_model->GetAllWhere('ks_gear_categories',$where_clause);											
									}else{
										$where_clause = array('a.gear_category_name'=>$categories['gear_category_name'] , 'b.gear_category_name'=> $importdata[2]);	
										$query = $this->model->CheckGearSubCategory('ks_gear_categories',$where_clause);											
									}
									$res_cat = $query->row();

									if (!empty($res_cat)) {
									 	$action =  'update' ;
									}else{
										$action =  'add' ;
									}
									if ($importdata[2] != '' ) {
										$where_clause1 = array('gear_category_name'=> $importdata[2]);
										$query = $this->common_model->GetAllWhere('ks_gear_categories',$where_clause1);											
										$res_cat_sub = $query->row();
										if(!empty($res_cat_sub)){
											$gear_sub_category_id = $res_cat_sub->gear_category_id ; 	
										}else{
											$gear_sub_category_id =  '0'; 	
										}
									}else{
										$gear_sub_category_id =  '0';
									}
									$data_update = array(
															'gear_category_name' => $importdata[1] ,
															'gear_sub_category_id' => $gear_sub_category_id , 
															'security_deposit' =>$importdata[3],
															'average_value' =>$importdata[4],
															'is_active' => $importdata[5],
															'create_user'=> $this->session->userdata('ADMIN_ID'),
															'create_date' => date('Y-m-d')
														);
									;	
									if($action == 'add'){
										$insert_id = $this->common_model->addRecord('ks_gear_categories',$data_update);
									}else{
											// $this->db->where('gear_category_id',$res_cat->gear_category_id);
											// $this->db->update('ks_gear_categories',$data_update);
									}
								}
								$count++;
				   }    
				   
				   	   fclose($file);
					   $message = '<div class="alert alert-success">Data imported successfully..</p></div>';
					   $this->session->set_flashdata('success', $message);
					   redirect('gear_categories1');
			   }else{
				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';
				   $this->session->set_flashdata('success', $message);
				   redirect('gear_categories1');
			   }
			}
	}
	public function import_to_modify()
	{
		 $data = array();
		 $gear_category_id = 0;
		 if($this->input->post()!='')
		 {
			      
				  $filename=$_FILES["file"]["tmp_name"];
				  if($_FILES["file"]["size"] > 0)
				  {
						$file = fopen($filename, "r");
						$count = 0;
						while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE)
						{
								//print_r($importdata);exit();
								
								if($count==0){
									//$heading[0]='GEAR_CATEGORY_ID';
									$heading[1]='gear_category_name';
									$heading[2]='gear_sub_category_id';
									$heading[3]='security_deposit';
									$heading[4]='average_value';
									$heading[5]='is_active';
									
										
								}
									
								if($count>0){
								
									$cnt = count($importdata);
								
									for($i=1;$i<$cnt;$i++){
										
										$key = $heading[$i];
										$data[$key] = $importdata[$i];
										
									}
									
									$data['create_user'] = $this->session->userdata['ADMIN_ID'];
									$data['create_date'] = date("Y-m-d");
									
									$insert_id = $this->common_model->addRecord('ks_gear_categories',$data);
									
									//$this->db->where('gear_category_id', $gear_category_id);
									//$this->db->update('ks_gear_categories', $data); 
								
								}
								
								$count++;
								
								
								
				  		}    
										   
				   	   fclose($file);
					   $message = '<div class="alert alert-success">Data imported successfully..</p></div>';
					   $this->session->set_flashdata('success', $message);
					   redirect('gear_categories');
			   }else{
				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';
				   $this->session->set_flashdata('success', $message);
				   redirect('gear_categories');
			   }
			}
	}
	
	public function downloadallcsv(){
	
		$this->db->select('a.gear_category_id AS CATEGORY_ID,a.gear_category_name AS CATEGORY_NAME,b.gear_category_name AS PARENT_CATEGORY_ID,a.is_active AS STATUS,a.average_value,a.security_deposit');
		$this->db->from('ks_gear_categories As a');
		$this->db->join('ks_gear_categories AS b','a.gear_sub_category_id = b.gear_category_id ','LEFT');
		  //$this->db->group_by('a.gear_category_id');
		$query = $this->db->get();
		$data = $query->result();
		// $i = 0 ;
		// foreach ($data as  $value) {
			
		// 	if ($value->PARENT_CATEGORY_ID == '') {
		// 		$data[$i]->PARENT_CATEGORY_ID  = '0';
		// 	}

		// }
		// echo "<pre>";
		// print_r($data);die;
		$fp = fopen('php://output', 'w');
		if ($fp && $query) {
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="gear_categories.csv"');
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
	
}?>
