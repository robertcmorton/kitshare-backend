<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gear_percentage extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text','common_helper'));
		$this->load->library(array('session','form_validation','pagination','email'));
		$this->load->model(array('common_model','mail_model','model',));
		// if($this->session->userdata('ADMIN_ID') =='') {
  //         redirect('login');
		//   }
	}
	
	protected $validation_rules = array
    (
		'Add' => array(
            array(
                'field' => 'category_id',
                'label' => 'Categor',
                'rules' => 'trim|required'
            )
			
        ),
    );

	public function index()
	{
		$id=$this->uri->segment(3);
		
	    $data=array();
		
		$where ='';

		if (!empty($this->input->get('limit'))) {
			$data['limit'] = $this->input->get('limit');
			$limit = $this->input->get('limit');
		}else{
			$limit =25;
			$data['limit']=25;
		}
		$data['search_rates']		  = $this->input->get('search_rates');
		if($data['search_rates'] != ''){
				$where .= "a.brand LIKE '%".trim($data['search_rates'])."%' ";
			}
		// if($id!="")
		// 	//$where = array('gear_sub_category_id'=>$id);
		// else
			//$where = "(`gear_sub_category_id` = 'NULL' OR `gear_sub_category_id`=0)";
		
		if($this->input->get("per_page")!= '')
		{
			$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
				 
		$n=$this->model->getGearPercentRate($where);
		$sql=$this->model->getGearPercentRate($where,$limit,$offset);
				
		$result=$sql->result();	
		
		$total_rows=$n->num_rows();	
		$data['gear'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = base_url()."gear_percentage?id=".$data['search_rates']."&limit=".$data['limit']."";
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
		$this->load->view('gear_percentage/list', $data);
		$this->load->view('common/footer');		

	

	}
	public function add()
	{
		$data=array();
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_sub_category_id'=>NULL));
		$data['gear_categories'] = $gear_categories->result();
		
		$data['cnt']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y')); 
		
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('gear_categories/add', $data);
		$this->load->view('common/footer');	
	}
	public function save()
	{
		$data=array();
		$id = $this->uri->segment(3);
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y','gear_category_id'=>$id));
		$data['gear_categories'] = $gear_categories->result();
		
		
		$this->form_validation->set_rules($this->validation_rules['Add']);
		
			if($this->form_validation->run())
			{
				$y=$this->input->post('sub_cat');
				$row['gear_category_name']= $this->input->post('gear_category_name');
				$row['is_active']= 'Y';
				$row['create_user']= $this->session->userdata('ADMIN_ID');	
				$row['create_date']= date('Y-m-d') ;
				$cnt=$y-1;
				if($y==0)
				$row['gear_sub_category_id']= NULL;
				else
					$row['gear_sub_category_id']= $this->input->post('gear_category_id_'.$y);
				$this->common_model->addRecord('ks_gear_categories',$row);
				$message = '<div class="alert alert-success">Gear Category has been successfully added.</p></div>';
				$this->session->set_flashdata('success', $message);
				redirect('gear_categories');
							
			}
			else
			{
				$this->load->view('common/header');	
				$this->load->view('common/left-menu');					
				$this->load->view('gear_categories/add', $data);
				$this->load->view('common/footer');	
			}
		
	
	}
	public function edit()
	{
		$data=array();
		
		$id = $this->uri->segment(3);
		$where_array = array('id'=>$id);
		$categories= $this->model->gear_percentage_rate($where_array);
		$data['result'] = $result=$categories->row();	
		$gear_categories=$this->common_model->GetAllWhere("ks_gear_categories",array("is_active"=>'Y',));
		$data['gear_categories'] = $gear_categories->result();
		
		$data['cnt']= $this->common_model->countAll("ks_gear_categories",array("is_active"=>'Y')); 
		
			
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('gear_percentage/edit', $data);
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
		$this->load->view('gear_categories/uploadpic', $data);
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
		$this->load->view('gear_categories/view', $data);
		$this->load->view('common/footer');		
	}
	
	
	public function update()
	{
		$data = array();
		//print_r($this->input->post());die;
		
    	
   
		$this->form_validation->set_rules($this->validation_rules['Add']);

		if($this->form_validation->run() == true )
		{
			//print_r($this->input->post());die;
				$row['category_id']= $this->input->post('category_id');
				$row['sub_category_id']= $this->input->post('gear_category_id_1');
				$row['status']= $this->input->post('status');
				$row['lowest_limit']= $this->input->post('lowest_limit');
				$row['upper_limit']= $this->input->post('upper_limit');
				$row['average']= $this->input->post('average');
				//$row['update_user'] = $this->session->userdata('ADMIN_ID');
				$row['updated_date'] = date('Y-m-d');
				$row['updated_time'] = date('H:i:s');
				
				$this->db->where('id', $this->input->post('id'));
				$this->db->update('ks_gear_percentage_rate', $row); 
				$message = '<div class="alert alert-success"><p>Gear Percentage Category Details updated successfully.</p></div>';
				$this->session->set_flashdata('success', $message);
				redirect('gear_percentage');
	
		}
		else
		{	
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('gear_percentage/edit', $data);
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
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id)
			{
	        
     		  	$this->common_model->delele('ks_gear_percentage_rate','id',$id);
			
    	    }
		$message = '<div class="alert alert-success"><p>Gear Percentage Rate have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('gear_percentage');
		}
	}
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		//print_r($id);die;
		$this->common_model->delele('ks_gear_percentage_rate','id',$id);
		
		$message = '<div class="alert alert-success"><p>Gear Percentage Rate has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('gear_percentage');
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
						echo "<pre>";print_r($importdata);//die;
						if ($importdata[2] == '0' || empty($importdata[2])) { // if sub category is 0  
								$categories['gear_category_name'] = $importdata[1];
								//Checked whether this category name exists or not
								$where_clause = array('gear_category_name'=>$categories['gear_category_name']);											
								$query = $this->common_model->GetAllWhere('ks_gear_categories',$where_clause);											
								$res_cat = $query->result_array();
								if(count($res_cat)>0){
									// if present update data
									$data['gear_category_name'] = $importdata[1];
									$data['gear_sub_category_id'] = $importdata[2];
									$data['create_user'] = $this->session->userdata['ADMIN_ID'];
									$data['create_date'] = date("Y-m-d");
									$data['is_active'] = $importdata[3];
									$data['update_user'] = $this->session->userdata['ADMIN_ID'];
									$data['update_date'] = date("Y-m-d");
									$this->db->where('gear_category_id', $res_cat[0]['gear_category_id']);
									$this->db->update('ks_gear_categories', $data); 
									 
								}else{ //else add data
									$categories['gear_category_name'] = $importdata[1];
									$categories['gear_sub_category_id'] = '0';
									$categories['create_user'] = $this->session->userdata['ADMIN_ID'];
									$categories['create_date'] = date("Y-m-d");
									$categories['is_active'] = 'Y';
									$insert_id = $this->common_model->addRecord('ks_gear_categories',$categories);
									//$data['gear_sub_category_id'] = $insert_id;		
								}
						}else{

								$categories['gear_category_name'] = $importdata[1];
								//Checked whether this category name exists or not
								$where_clause = array('gear_category_name'=>$categories['gear_category_name']);											
								$query = $this->common_model->GetAllWhere('ks_gear_categories',$where_clause);											
								$res_cat = $query->result_array();
								if(count($res_cat)>0){
									// if present update data
									$data['gear_category_name'] = $importdata[1];
									$data['gear_sub_category_id'] = $importdata[2];
									$data['create_date'] = date("Y-m-d");
									$data['is_active'] = $importdata[3];
									$data['update_user'] = $this->session->userdata['ADMIN_ID'];
									$data['update_date'] = date("Y-m-d");
									$this->db->where('gear_category_id', $res_cat[0]['gear_category_id']);
									$this->db->update('ks_gear_categories', $data); 
									 
								}else{ //else add data
									$categories['gear_category_name'] = $importdata[1];
									$categories['gear_sub_category_id'] = $importdata[2];
									$categories['create_user'] = $this->session->userdata['ADMIN_ID'];
									$categories['create_date'] = date("Y-m-d");
									$categories['is_active'] = 'Y';
									$insert_id = $this->common_model->addRecord('ks_gear_categories',$categories);
									//$data['gear_sub_category_id'] = $insert_id;		
								}


						}		
									
									
					}
					$count++;
													
				}
			}
		}			
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
					//print_r($importdata);
					if($count==0){
						$heading = $importdata;			
					}
					if ($count > 0) {
						$cnt = count($importdata);
					//	echo "<pre>";print_r($importdata);
						//--------Start category check----//
						if (!empty($importdata[0])) {
							$category_name=  $importdata[0];
							$where_clause = array('gear_category_name'=>$category_name);											
							$query = $this->common_model->GetAllWhere('ks_gear_categories',$where_clause);	
								$res_cat = $query->result_array();
							
								if (empty($res_cat)) { //add category
									$add_cat['gear_category_name']= $category_name;
									$add_cat['is_active']= 'Y';
									$add_cat['create_user']= $this->session->userdata('ADMIN_ID');
									$add_cat['gear_sub_category_id']='0';	
									$add_cat['create_date']= date('Y-m-d') ;
									$cat_resonse = $this->common_model->addRecord('ks_gear_categories',$add_cat);
									
									$category_id =$cat_resonse; 
								}else{ // get category id
									$category_id =$res_cat[0]['gear_category_id']; 
								}
								//--------end category check----//
								//--------start sub category check----//
								if (!empty($importdata[1])) {

									$sub_cat_name =$importdata[1];
									$where_clause = array('gear_category_name'=>$sub_cat_name,
										'gear_sub_category_id'=>$category_id
										);

									$query = $this->common_model->GetAllWhere('ks_gear_categories',$where_clause);	
								$res_cat_sub = $query->result_array();
								if (empty($res_cat_sub)) {
									$add_cat_sub['gear_category_name']= $sub_cat_name;
									$add_cat_sub['is_active']= 'Y';
									$add_cat_sub['create_user']= $this->session->userdata('ADMIN_ID');
									$add_cat_sub['gear_sub_category_id']=$category_id;	
									$add_cat_sub['create_date']= date('Y-m-d') ;
									$sub_cat_resonse = $this->common_model->addRecord('ks_gear_categories',$add_cat_sub);
									
									$sub_category_id =$sub_cat_resonse;
								}else{
									$sub_category_id =$res_cat_sub[0]['gear_category_id'];
								}
								}
						//----end sub cat check----//
						$data_check = array(
										'category_id'=>$category_id,
										'sub_category_id'=>$sub_category_id ,
										'brand'=>$importdata[2]
									);
						$query = $this->common_model->GetAllWhere('ks_gear_percentage_rate',$data_check);
						$res_gear_per = $query->result_array();

						if (!empty($res_gear_per)) {
							if ($importdata[6] == '') {
								$security_deposite = 'NO';
							}else{
								$security_deposite = 'YES';
							}
							$data_update = array(
										'category_id'=>$category_id,
										'sub_category_id'=>$sub_category_id ,
										'brand'=>$importdata[2],
										'lowest_limit'=>$importdata[3],
										'average'=>$importdata[4],
										'upper_limit'=>$importdata[5],
										'security_deposite'=>$security_deposite,
										'created_by'=>$this->session->userdata('ADMIN_ID'),
										'updated_date'=>date("Y-m-d"),
										'updated_time'=>date('H:i:s'),
										'status'=>'YES'
									);
							$this->db->where('id', $res_gear_per[0]['id']);
							$this->db->update('ks_gear_percentage_rate', $data_update); 

						}else{
							if ($importdata[6] == '') {
								$security_deposite = 'NO';
							}else{
								$security_deposite = 'YES';
							}
								$data_update = array(
										'category_id'=>$category_id,
										'sub_category_id'=>$sub_category_id ,
										'brand'=>$importdata[2],
										'lowest_limit'=>$importdata[3],
										'average'=>$importdata[4],
										'upper_limit'=>$importdata[5],
										'security_deposite'=>$security_deposite,
										'created_by'=>$this->session->userdata('ADMIN_ID'),
										'created_date'=>date("Y-m-d"),
										'created_date'=>date('H:i:s'),
										'status'=>'YES'
									);
								$cat_resonse = $this->common_model->addRecord('ks_gear_percentage_rate',$data_update);
						//die;
						}
						}
					}
					//print_r($importdata);die;//exit();
								
								
								
								
					$count++;			
				}
				//die;    
				   
				   	   fclose($file);
					   $message = '<div class="alert alert-success">Data imported successfully..</p></div>';
					   $this->session->set_flashdata('success', $message);
					   redirect('Gear_percentage');
			}else{
				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';
				   $this->session->set_flashdata('success', $message);
				   redirect('Gear_percentage');
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
										
									$heading[0]='gear_category_id';
									$heading[1]='gear_category_name';
									$heading[2]='gear_sub_category_id';
									$heading[3]='is_active';
										
								}
									
								if($count>0){
								
									$cnt = count($importdata);
								
									for($i=0;$i<$cnt;$i++){
										
										if($i>0){
											$key = $heading[$i];
											$data[$key] = $importdata[$i];
										}else
											$gear_category_id = $importdata[$i];
										
									}
									
									$data['update_user'] = $this->session->userdata['ADMIN_ID'];
									$data['update_date'] = date("Y-m-d");
									
									$this->db->where('gear_category_id', $gear_category_id);
									$this->db->update('ks_gear_categories', $data); 
								
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
	
		$this->db->select('b.gear_category_name AS category_name, c.gear_category_name As sub_category_name,a.brand,a.lowest_limit,a.upper_limit,a.average,a.status,a.security_deposite');
		$this->db->from("ks_gear_percentage_rate as a");
		$this->db->join("ks_gear_categories as b", "a.category_id = b.gear_category_id","left");
		$this->db->join("ks_gear_categories as c", "a.sub_category_id = c.gear_category_id","left");
		$query = $this->db->get();
		//$data =$query->result('array');
		//print_r($data);die;
		$fp = fopen('php://output', 'w');
		if ($fp && $query) {
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="gear_percentage_rate.csv"');
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
