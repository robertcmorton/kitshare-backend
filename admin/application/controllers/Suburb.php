<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suburb extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','pagination','email'));
		$this->load->model(array('common_model','mail_model','model'));
		if($this->session->userdata('ADMIN_ID') =='') {
          redirect('login');
		  }
		 error_reporting(0);
	}

	protected $validation_rules = array
        (
		'Add' => array(
			array(
                'field' => 'suburb_name',
                'label' => 'state name',
                'rules' => 'trim|required'
            ),
			array(
                'field' => 'ks_state_id',
                'label' => 'country name',
                'rules' => 'trim|required'
            ),

        ),

    );

	public function index()
	{
		$data=array();
		//$where = ' ';
		if (!empty($this->input->get('limit'))) {
			$data['limit'] = $this->input->get('limit');
			$limit = $this->input->get('limit');
		}else{
			$data['limit']=25;
			$limit = '25';
		}
		$data['suburb_name']				= $this->input->get('suburb_name');
		if($data['suburb_name'] != ''){
			$where .= "a.suburb_name LIKE '%".trim($data['suburb_name'])."%' and ";
		}
		
		$data['ks_state_name']				= $this->input->get('ks_state_name');
				if($data['ks_state_name'] != ''){
				$where .= "b.ks_state_name LIKE '%".trim($data['ks_state_name'])."%' and ";
			}
			
		$where = substr($where, 0, -4);
		
		if($this->input->get("per_page")!= '')
		{
		$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		
		$nUser=$this->model->suburb($where);
		$sql=$this->model->suburb($where,$limit,$offset);
		
		$state=$sql->result();
	
		$total_rows=$nUser->num_rows();	
		$data['state'] = $state;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = base_url()."suburb/index/?suburb_name=".$data['suburb_name']."&ks_state_name=".$dada['ks_state_name']."&limit=".$data['limit'];
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
		
		//echo $this->db->last_query();
		//print_r($data['state']); exit();
		
		
//////////////////////////////Pagination config//////////////////////////////////				
		$data['paginator'] = $paginator;
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('suburb/list', $data);
		$this->load->view('common/footer');		

	}

	public function add()
	{
		$data=array();
		
		$qc = $this->common_model->GetAllWhere("ks_states",array("is_active"=>'Y'));
		$data['states'] =  $qc->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('suburb/add', $data);
		$this->load->view('common/footer');	
	}

	public function save()
	{
	$data=array();
	
	$qc = $this->common_model->GetAllWhere("ks_states",array("is_active"=>'Y'));
	$data['states'] =  $qc->result();
	
	
		
	$this->form_validation->set_rules($this->validation_rules['Add']);
	if($this->form_validation->run())
	{
	
	    $q = $this->common_model->GetAllWhere("ks_suburbs",array("ks_state_id"=>$this->input->post('ks_state_id'),"suburb_name"=>$this->input->post('suburb_name')));
		
			if($q->num_rows()>0){
			
				$message = '<div class="alert alert-success">Suburb is already added.</p></div>';
			
			}
			else{
			
			        $ks_state_id = $this->input->post('ks_state_id');
					
					$row['ks_state_id']= $ks_state_id;
					
					$row['suburb_postcode']= $this->input->post('suburb_postcode');
					$row['time_zone']= $this->input->post('time_zone');
					$row['latitude']= $this->input->post('latitude');
					$row['longitude']= $this->input->post('longitude');
					$row['suburb_name']= $this->input->post('suburb_name');
					$row['is_active']= 'Y' ;
					$row['create_date']= date('Y-m-d') ;
					$row['create_user']=  $this->session->userdata('ADMIN_ID') ;
					$this->common_model->addRecord('ks_suburbs',$row);
					
					$message = '<div class="alert alert-success">Suburb has been successfully added.</p></div>';
			}
		
		
		
		$this->session->set_flashdata('success', $message);
		redirect('suburb');
	}else{
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('suburb/add', $data);
		$this->load->view('common/footer');	
	 }

	}
	
	
	public function edit()
	{
	    $data = array();
		
		$qc = $this->common_model->GetAllWhere("ks_states",array("is_active"=>'Y'));
	    $data['states'] =  $qc->result();
		
		$id = $this->uri->segment(3);
		$where_array = array('ks_suburb_id'=>$id);
		$state = $this->model->suburb($where_array);	
		$data['result'] = $state->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('suburb/edit', $data);
		$this->load->view('common/footer');		
	}

	public function update()
	{
	
		$data = array();
		$id = $this->input->post('ks_suburb_id');
		
		$qc = $this->common_model->GetAllWhere("ks_states",array("is_active"=>'Y'));
	    $data['states'] =  $qc->result();
		
		 $q = $this->common_model->GetAllWhere("ks_suburbs",array("suburb_name"=>$this->input->post('suburb_name'),"ks_state_id"=>$this->input->post('ks_state_id'),"ks_state_id !="=>$this->input->post('ks_state_id')));
		if($q->num_rows()>0){
		
			$message = '<div class="alert alert-success">Suburb is already added.</p></div>';
		
		}else{
		
		    $ks_state_id = $this->input->post('ks_state_id');
					
			$row['ks_state_id']= $ks_state_id;
			$row['suburb_name']= $this->input->post('suburb_name');
			$row['suburb_postcode']= $this->input->post('suburb_postcode');
			$row['time_zone']= $this->input->post('time_zone');
			$row['latitude']= $this->input->post('latitude');
			$row['longitude']= $this->input->post('longitude');
			$row['is_active']= $this->input->post('is_active');
			$row['update_date']= date('Y-m-d') ;
			$row['update_user']=  $this->session->userdata('ADMIN_ID') ;
			$this->db->where('ks_suburb_id', $id);
			$this->db->update('ks_suburbs', $row); 
			
			$message = '<div class="alert alert-success"><p>Suburb updated successfully.</p></div>';
		}
		
		
		$this->session->set_flashdata('success', $message);
		redirect('suburb');

	}

	

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
				$this->db->where('ks_suburb_id',$id);
				$this->db->delete('ks_suburbs'); 
			}
			$message = '<div class="alert alert-success"><p>Suburbs have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success',$message);
			redirect('suburb');
		}

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('ks_suburb_id', $id);
        $this->db->delete('ks_suburbs'); 
		$message = '<div class="alert alert-success"><p>Suburb has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('suburb');

	}
	
	public function import()
	{
		 $data = array();
		 $state = array();
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
								
									for($i=1;$i<$cnt;$i++){
									
										if($i == 3 || $i == 4){
											
											if($i == 3)
												$state['ks_state_code'] = $importdata[$i];
											
											if($i == 4){
											
												//Checked whether the state exists or not
												$state['ks_state_name'] = $importdata[$i];
												$state['ks_country_id'] = 1;
												$state['create_user'] = $this->session->userdata['ADMIN_ID'];
												$state['create_date'] = date("Y-m-d");
												
												$where_clause = array('ks_state_name'=>$state['ks_state_name']);											
												$query = $this->common_model->GetAllWhere('ks_states',$where_clause);											
												$res_state = $query->result_array();
												
												
												if(count($res_state)>0){
												
													$data['ks_state_id'] = $res_state[0]['ks_state_id'];
													
												}else{ //if state doesn't exist then the record is inserted into the table
												
													$insert_id = $this->common_model->addRecord('ks_states',$state);
													$data['ks_state_id'] = $insert_id;
												
												}
											}
											
										}else{
										
											if($i == 1)
												$key = 'suburb_name';
											else if($i == 5)
												$key = 'suburb_postcode';
											else if($i == 6)
												$key = 'suburb_type';
											else
												$key = $heading[$i];
												
											$data[$key] = $importdata[$i];
										}
										
									}
									
									$where_clause = array('suburb_name'=>$data['suburb_name'],'ks_state_id'=>$data['ks_state_id'],'suburb_postcode'=>$data['suburb_postcode'],);											
									//print_r($where_clause);die;
									$query = $this->common_model->GetAllWhere('ks_suburbs',$where_clause);											
									$res_state = $query->row();
									if (!empty($res_state)) {
										$data['update_user'] = $this->session->userdata['ADMIN_ID'];
										$data['update_date'] = date("Y-m-d");
										//print_r($data);die;
										$this->db->where('ks_suburb_id', $res_state->ks_suburb_id);
										$this->db->update('ks_suburbs', $data); 
									
									}else{

									$data['create_user'] = $this->session->userdata['ADMIN_ID'];
									$data['create_date'] = date("Y-m-d");
									//print_r($data);die;
									$this->common_model->addRecord('ks_suburbs',$data);
									}
								//print_r($check_surbs);die;
								}
								$count++;
								
								
								
				   }    
				   
				   	   fclose($file);
					   $message = '<div class="alert alert-success">Data imported successfully..</p></div>';
					   $this->session->set_flashdata('success', $message);
					   redirect('suburb');
			   }else{
				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';
				   $this->session->set_flashdata('success', $message);
				   redirect('suburb');
			   }
			}
	}
	
	public function import_to_modify()
	{
		 $data = array();
		 $state = array();
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
									
									
									$ks_suburb_id = $importdata[0];
								
									for($i=1;$i<$cnt;$i++){
									
										if($i == 3 || $i == 4){
											
											if($i == 3)
												$state['ks_state_code'] = $importdata[$i];
											
											if($i == 4){
											
												//Checked whether the state exists or not
												$state['ks_state_name'] = $importdata[$i];
												$state['ks_country_id'] = 1;
												$state['create_user'] = $this->session->userdata['ADMIN_ID'];
												$state['create_date'] = date("Y-m-d");
												
												$where_clause = array('ks_state_name'=>$state['ks_state_name']);											
												$query = $this->common_model->GetAllWhere('ks_states',$where_clause);											
												$res_state = $query->result_array();
												
												
												if(count($res_state)>0){
												
													$data['ks_state_id'] = $res_state[0]['ks_state_id'];
													
												}else{ //if state doesn't exist then the record is inserted into the table
												
													$insert_id = $this->common_model->addRecord('ks_states',$state);
													$data['ks_state_id'] = $insert_id;
												
												}
											}
											
										}else{
										
											if($i == 1)
												$key = 'suburb_name';
											else if($i == 5)
												$key = 'suburb_postcode';
											else if($i == 6)
												$key = 'suburb_type';
											else
												$key = $heading[$i];
												
											$data[$key] = $importdata[$i];
										}
										
									}
								
									$data['update_user'] = $this->session->userdata['ADMIN_ID'];
									$data['update_date'] = date("Y-m-d");
									
									$this->db->where('ks_suburb_id', $ks_suburb_id);
									$this->db->update('ks_suburbs', $data);									
								
								}
								$count++;
								
								
								
				   }    
				   
				   	   fclose($file);
					   $message = '<div class="alert alert-success">Data imported successfully..</p></div>';
					   $this->session->set_flashdata('success', $message);
					   redirect('suburb');
			   }else{
				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';
				   $this->session->set_flashdata('success', $message);
				   redirect('suburb');
			   }
			}
	}
	
	public function downloadallcsv(){
	
		$query = $this->model->get_all_suburb_data();		
	
		$fp = fopen('php://output', 'w');
						
		if ($fp && $query) {
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="au_suburbs.csv"');
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