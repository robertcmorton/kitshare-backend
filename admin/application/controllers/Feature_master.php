<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feature_master extends CI_Controller {
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
                'field' => 'feature_name',
                'label' => 'Feature Name',
                'rules' => 'trim|required'
            )
			
        ),
    );

	public function index()
	{

	    $data=array();
		
		$where ='';
		if (!empty($this->input->get('limit'))) {
			$data['limit'] = $this->input->get('limit');
		}else{
			$data['limit']=25;
		}
		

		$data['feature_name']		  = $this->input->get('feature_name');
				if($data['feature_name'] != ''){
				$where .= " WHERE e.feature_name  LIKE '%".trim($data['feature_name'])."%' AND ";
			}
		
		$desc=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y'));
		$data['gear_category'] = $desc->result();
		
			
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
		$data['offset'] = $offset;
		$nSer="SELECT e.feature_master_id,e.gear_category_id,e.feature_name,e.feature_abbr,e.feature_unit,e.is_active,e.create_user,e.create_date,c.app_user_name
			   FROM ks_feature_master e JOIN m_app_user c ON e.create_user = c.app_user_id 
			   ".$where." ORDER BY e.gear_category_id ASC,e.feature_name ASC";
			   
		
		$sql=$nSer." LIMIT ".$data['limit']." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$data['limit'];
		$config['total_rows'] = $total_rows;
		$config['per_page'] =$data['limit'];
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url()."feature_master?feature_name=".$data['feature_name']."&limit=".$data['limit'];
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
		$this->load->view('feature_master/list', $data);
		$this->load->view('common/footer');		

	}
	
	public function add()
	{
		$data=array();
		$cat=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y',"gear_sub_category_id"=>'0'));
		$data['gear_category'] = $cat->result();
		
		
		$data['cnt']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y')); 
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('feature_master/add', $data);
		$this->load->view('common/footer');	
	}
	
	public function save()
	{
		$data=array();
		
		$this->form_validation->set_rules($this->validation_rules['Add']);
		
		$desc=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y'));
		$data['gear_category'] = $desc->result();
		$de=$desc->result();
		
		/*$count=$this->common_model->countAll("ks_user_gear_features",array("is_active"=>'Y',"user_gear_desc_id"=>$de[0]->user_gear_desc_id));*/
		
		if($this->form_validation->run())
		{
		
			$y=$this->input->post('sub_cat');
			if($y==0)
				$row['gear_category_id']= 0;
			else
				$row['gear_category_id']= $this->input->post('gear_category_id_'.$y);
				
			$row['feature_name']= $this->input->post('feature_name');
			$row['feature_abbr']= $this->input->post('feature_abbr');
			$row['feature_unit']= $this->input->post('feature_unit');
			$row['create_user']= $this->session->userdata('ADMIN_ID');
			if($this->input->post('status')=="Y")
			{
				$row['is_active']= 'Active';
			}
			else
			{
				$row['is_active']= 'Inactive';	
			}
			$row['create_date']= date('Y-m-d') ;
			
			$id = $this->common_model->addRecord('ks_feature_master',$row);
			
			
			$this->db->where('feature_master_id', $id);
			$this->db->update('ks_feature_master', $row); 
			
			$feature_value = $this->input->post('feature_value');
			
			if(count($feature_value)>0){
						
				//All the feature values are inserted into the table
				//Checked if there is any values in the ks_gear_feature_details table
				$where_clause = "is_active='Y' AND feature_master_id='".$id."'";
				$cnt = $this->common_model->countAll("ks_gear_feature_details",$where_clause);
				
				if($cnt >0){
				
					//Records are deleted from the ks_gear_feature_details table
					$this->common_model->Delete("ks_gear_feature_details", $id, "feature_master_id");
				}
				
				//Record is inserted into the ks_gear_feature_details
				foreach($feature_value as $key=>$val){
					$row = array();
					if($val!=""){
				
						$row['feature_master_id'] = $id;
						$row['feature_values'] = $val;
						$row['create_user'] = $this->session->userdata('ADMIN_ID');
						$row['create_date'] = date("Y-m-d");
												
						$this->common_model->Add_Record($row,"ks_gear_feature_details");
					}
				}
			}
			
			
			$message = '<div class="alert alert-success">Gear feature has been successfully added.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('feature_master');
							
		}
		else
		{
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');					
			$this->load->view('feature_master/add', $data);
			$this->load->view('common/footer');	
		}
	
	}
	public function edit()
	{
		$data=array();
		
		$id = $this->uri->segment(3);
		$where_array = array('feature_master_id'=>$id);
		$device= $this->common_model->GetAllWhere("ks_feature_master",$where_array);
		$data['result'] = $device->result();
		
		$gear=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y',"gear_sub_category_id"=>'0'));
		$data['gear_category'] = $gear->result();
		$data['cnt']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y')); 
		$data['feature_master_id'] = $id;
			
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('feature_master/edit', $data);
		$this->load->view('common/footer');		
	}
	
	public function update()
	{
		$data = array();
		$id=$this->input->post('feature_master_id');
		
		$this->form_validation->set_rules($this->validation_rules['Add']);
		
		$where_array = array('feature_master_id'=>$id);
		$device= $this->common_model->GetAllWhere("ks_feature_master",$where_array);
		$data['result'] = $device->result();
		
		$gear=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y'));
		$data['gear_category'] = $gear->result();
		
		if($this->form_validation->run() == true )
		{
			//print_r($this->input->post());die;
			$y=$this->input->post('sub_cat');
			if( $this->input->post('gear_category_id_2') != '' ){
				
				$row['gear_category_id'] = $this->input->post('gear_category_id_2');
			}else{
				$row['gear_category_id'] = $this->input->post('gear_category_id');
			}
			
			//$row['gear_category_id']= $this->input->post('gear_category_id');
			$row['feature_name']= $this->input->post('feature_name');
			$row['feature_abbr']= $this->input->post('feature_abbr');
			$row['feature_unit']= $this->input->post('feature_unit');
			$row['update_user']= $this->session->userdata('ADMIN_ID');
			//print_r($row);die;
			if($this->input->post('status')=="Active")
			{
				$row['is_active']= 'Active';
			}
			else
			{
				$row['is_active']= 'Inactive';	
			}
			$row['update_date']= date('Y-m-d') ;
			
			$this->db->where('feature_master_id', $id);
			$this->db->update('ks_feature_master', $row); 
			
			$feature_value = $this->input->post('feature_value');
			
			if(count($feature_value)>0){
						
				//All the feature values are inserted into the table
				//Checked if there is any values in the ks_gear_feature_details table
				$where_clause = "is_active='Y' AND feature_master_id='".$id."'";
				$cnt = $this->common_model->countAll("ks_gear_feature_details",$where_clause);
				
				if($cnt >0){
				
					//Records are deleted from the ks_gear_feature_details table
					$this->common_model->Delete("ks_gear_feature_details", $id, "feature_master_id");
				}
				
				//Record is inserted into the ks_gear_feature_details
				foreach($feature_value as $key=>$val){
					$row = array();
					if($val!=""){
				
						$row['feature_master_id'] = $id;
						$row['feature_values'] = $val;
						$row['create_user'] = $this->session->userdata('ADMIN_ID');
						$row['create_date'] = date("Y-m-d");
												
						$this->common_model->Add_Record($row,"ks_gear_feature_details");
					}
				}
			}


			$message = '<div class="alert alert-success"><p>Category Features updated successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('feature_master');
	
		}
		else
		{	
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('feature_master/edit', $data);
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
	        
			$where_array = array('feature_master_id'=>$id);
			$image= $this->common_model->get_all_record('ks_feature_master',$where_array);
			
			/*foreach($image as $im){
				$this->load->helper("file");
				$oldfile = MODEL_IMAGE."/".$im->model_image;
				unlink($oldfile);
			}*/
     		$this->common_model->delele('ks_feature_master','feature_master_id',$id);
			
    	}
		$message = '<div class="alert alert-success"><p>Features have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('feature_master');
		}
	}
	
	
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		$where_array = array('feature_master_id'=>$id);
		$image= $this->common_model->get_all_record('ks_feature_master',$where_array);
		/*foreach($image as $im){
				$this->load->helper("file");
				$oldfile = MODEL_IMAGE."/".$im->model_image;
				unlink($oldfile);
			}*/
		$this->common_model->delele('ks_feature_master','feature_master_id',$id);
		
		$message = '<div class="alert alert-success"><p>Feature has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('feature_master');
	}
	
	public function ajax()
	{			
		
		$data=array();
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>$this->input->post('data')));
		$data['gear_categories'] = $gear_categories->result();
		$data['x'] =$this->input->post('data');
		$data['y'] = $this->input->post('count'); 
		$data['cnt']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>$this->input->post('data')));
		$data['cnt2']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y'));
		$this->load->view('gear_categories/ajax',$data);
	}
	
	public function import()
	{
		 $data = array();
		 if($this->input->post()!='')
		 {
			      
				  $filename=$_FILES["file"]["tmp_name"];
				  if($_FILES["file"]["size"] > 0)
				  {
						$file = fopen($filename, "r");
						$count = 0;
						while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE)
						{
								if($count>0){
								$cnt = count($importdata);
							
								$data = array();
							
								for($i=0;$i<$cnt;$i++){
									
									if($importdata[$i]!=""){
									
										if($i==0){
											
											
											$cat_name=$importdata[$i];
																						
											//Category Id is fetched corresponding to this category name
											$this->db->select('gear_category_id');
											$this->db->from('ks_gear_categories');
											$this->db->where(array('gear_category_name'=>trim($cat_name)));
											$query = $this->db->get();
											
											$res = $query->result_array();
											
											$cat_id = $res[0]['gear_category_id'];
											
										}else if($i==1){
										
											$feature_name = $importdata[$i];
											
											//Feature is searched in feature master table 										
											$this->db->select('feature_master_id');
											$this->db->from('ks_feature_master');
											$this->db->where(array('gear_category_id'=>$cat_id,'feature_name'=>$feature_name));
											$query = $this->db->get();
											if($query->num_rows()>0){
											
												$res = $query->result_array();
												$data['feature_master_id'] = $res[0]['feature_master_id'];	
												
											}else{
											
												$row = array();
												$row['feature_name'] = trim($importdata[$i]);
												$row['gear_category_id'] = $cat_id;
												$row['create_user'] = $this->session->userdata['ADMIN_ID'];
												$row['create_date'] = date("Y-m-d");
												
												$data['feature_master_id'] = $this->common_model->addRecord('ks_feature_master',$row);
												
											}
																				
										}else{
											if($importdata[$i]!=""){
											
											
												//Checked whether there is any record in the table against the category id and feature master id
												$this->db->select('feature_values');
												$this->db->from('ks_gear_feature_details');
												$this->db->where(array('feature_master_id'=>$data['feature_master_id'],'feature_values'=>$importdata[$i]));
												$query = $this->db->get();
												if($query->num_rows()==0){
												
													$data['feature_values'] = $importdata[$i];
													$data['create_user'] = $this->session->userdata['ADMIN_ID'];
													$data['create_date'] = date("Y-m-d");
													
													$this->common_model->addRecord('ks_gear_feature_details',$data);
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
					 	redirect('feature_master');
			   }else{
				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';
				   $this->session->set_flashdata('success', $message);
				  	redirect('feature_master');
			   }
			}
	}
	
	public function import_to_modify()
	{
		 $data = array();
		 $feature_details_id = 0;
		 if($this->input->post()!='')
		 {
			      
				  $filename=$_FILES["file"]["tmp_name"];
				  if($_FILES["file"]["size"] > 0)
				  {
				  
						$file = fopen($filename, "r");
						$count = 0;
						while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE)
						{
								$cnt = count($importdata);
							
								if($count>0){
									for($i=0;$i<$cnt;$i++){
										
										if($i==0)									
											$feature_details_id = $importdata[$i];
										else if($i==1)
											$data['feature_master_id'] = $importdata[$i];
										else if($i==2)
											$gear_category_name = $importdata[$i];
										else if($i==3)
											$feature_name = $importdata[$i];
										else if($i==4)
											$data['feature_values'] = $importdata[$i];
										else if($i==5){
											if($importdata[$i]=='Active')
												$data['is_active']='Y';
											else	
												$data['is_active']='N';
										}											
									}
								
								
								
									//Checked for the feature master id
									$feature_master_id = $this->model->get_feature_master_id($feature_name,$gear_category_name);
									if($feature_master_id>0 && $feature_details_id!=""){
									
										$data['feature_master_id'] = $feature_master_id;
										$data['update_user'] = $this->session->userdata['ADMIN_ID'];
										$data['update_date'] = date("Y-m-d");
										
									
										//Record is updated
										$this->db->where('feature_details_id',$feature_details_id);
										$this->db->update('ks_gear_feature_details',$data);
									}else{
										
										$data['create_user'] = $this->session->userdata['ADMIN_ID'];
										$data['create_date'] = date("Y-m-d");
										
										$this->common_model->addRecord('ks_gear_feature_details',$data);
									}
								}
								
								$count++;
																
				   		}    
				   
				   	    
				   	   fclose($file);
					   $message = '<div class="alert alert-success">Data updated successfully..</p></div>';
					   $this->session->set_flashdata('success', $message);
					   redirect('feature_master');
			   }else{
				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';
				   $this->session->set_flashdata('success', $message);
				  	redirect('feature_master');
			   }
			}
	}
	
	public function downloadallcsv(){
	
	$this->db->select('b.feature_details_id,e.feature_master_id,a.gear_category_name,e.feature_name,b.feature_values,e.is_active');
	$this->db->from('ks_feature_master as e');
	$this->db->join("m_app_user as c", "e.create_user = c.app_user_id","inner");
	$this->db->join("ks_gear_categories as a", "e.gear_category_id = a.gear_category_id","inner");
	$this->db->join("ks_gear_feature_details as b", "e.feature_master_id = b.feature_master_id","left");
	$query = $this->db->get();
	
	//exit ();
	
		$fp = fopen('php://output', 'w');
		if ($fp && $query) {
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="category_features.csv"');
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
	
	public function add_feature_value(){
	
		$data = array();
		$counter = $this->input->post('counter');
		
		$data['counter'] = $counter;
		
		$this->load->view('feature_master/append_input_box',$data);
	
	}
	

	
}?>