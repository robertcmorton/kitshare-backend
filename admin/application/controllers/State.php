<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class State extends CI_Controller {
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
                'field' => 'ks_state_name',
                'label' => 'state name',
                'rules' => 'trim|required'
            ),
			array(
                'field' => 'ks_country_id',
                'label' => 'country name',
                'rules' => 'trim|required'
            ),

        ),

    );

	public function index()
	{
		$data=array();
		//$where = ' ';
		$limit=10;
		$data['ks_state_name']				= $this->input->get('ks_state_name');
		if($data['ks_state_name'] != ''){
			$where .= "a.ks_state_name LIKE '%".trim($data['ks_state_name'])."%' and ";
		}
		
		$data['ks_country_name']				= $this->input->get('ks_country_name');
				if($data['ks_country_name'] != ''){
				$where .= "b.ks_country_name LIKE '%".trim($data['ks_country_name'])."%' and ";
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
		
		$nUser=$this->model->state($where);
		$sql=$this->model->state($where,$limit,$offset);
		
		$state=$sql->result();
	
		$total_rows=$nUser->num_rows();	
		$data['state'] = $state;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = base_url()."state/index/?ks_state_name=".$data['ks_state_name']."&ks_country_name=".$data['ks_country_name'];
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
		$this->load->view('state/list', $data);
		$this->load->view('common/footer');		

	}

	public function add()
	{
		$data=array();
		
		$qc = $this->common_model->GetAllWhere("ks_countries",array("is_active"=>'Y'));
		$data['countries'] =  $qc->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('state/add', $data);
		$this->load->view('common/footer');	
	}

	public function save()
	{
	$data=array();
	
	$qc = $this->common_model->GetAllWhere("ks_countries",array("is_active"=>'Y'));
	$data['countries'] =  $qc->result();
	
	
		
	$this->form_validation->set_rules($this->validation_rules['Add']);
	if($this->form_validation->run())
	{
	
	    $q = $this->common_model->GetAllWhere("ks_states",array("ks_country_id"=>$this->input->post('ks_country_id'),"ks_state_name"=>$this->input->post('ks_state_name')));
		
			if($q->num_rows()>0){
			
				$message = '<div class="alert alert-success">State is already added.</p></div>';
			
			}
			else{
			
			        $ks_country_id = $this->input->post('ks_country_id');
					
					if($this->input->post('ks_country_name')!=''){
					
							$row1['ks_country_name']= $this->input->post('ks_country_name');
							$row1['create_user'] = $this->session->userdata('ADMIN_ID');
							$row1['is_active'] = 'Y';
							$row1['create_date'] = date('Y-m-d');
							$ks_country_id = $this->common_model->addRecord('ks_countries',$row1);
					
					}
			       
					$row['ks_country_id']= $ks_country_id;
					$row['ks_state_name']= $this->input->post('ks_state_name');
					$row['ks_state_code']= $this->input->post('ks_state_code');
					$row['ks_state_esl']= $this->input->post('ks_state_esl');
					$row['ks_state_sd']= $this->input->post('ks_state_sd');
					$row['base_rate']= $this->input->post('base_rate');
					$row['is_active']= 'Y' ;
					$row['create_date']= date('Y-m-d') ;
					$row['create_user']=  $this->session->userdata('ADMIN_ID') ;
					$this->common_model->addRecord('ks_states',$row);
					
					$message = '<div class="alert alert-success">State has been successfully added.</p></div>';
			}
		
		
		
		$this->session->set_flashdata('success', $message);
		redirect('state');
	}else{
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('state/add', $data);
		$this->load->view('common/footer');	
	 }

	}
	
	
	public function edit()
	{
	    $data = array();
		
		$qc = $this->common_model->GetAllWhere("ks_countries",array("is_active"=>'Y'));
	    $data['countries'] =  $qc->result();
		
		$id = $this->uri->segment(3);
		$where_array = array('ks_state_id'=>$id);
		$state = $this->model->state($where_array);	
		$data['result'] = $state->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('state/edit', $data);
		$this->load->view('common/footer');		
	}

	public function update()
	{
	
		$data = array();
		$id = $this->input->post('ks_state_id');
		
		$qc = $this->common_model->GetAllWhere("ks_countries",array("is_active"=>'Y'));
	    $data['countries'] =  $qc->result();
		
		 $q = $this->common_model->GetAllWhere("ks_states",array("ks_state_name"=>$this->input->post('ks_state_name'),"ks_country_id"=>$this->input->post('ks_country_id'),"ks_state_id !="=>$this->input->post('ks_state_id')));
		if($q->num_rows()>0){
		
			$message = '<div class="alert alert-success">State is already added.</p></div>';
		
		}else{
		
		    $ks_country_id = $this->input->post('ks_country_id');
					
			if($this->input->post('ks_country_name')!=''){
			
					$row1['ks_country_name']= $this->input->post('ks_country_name');
					$row1['create_user'] = $this->session->userdata('ADMIN_ID');
					$row1['is_active'] = 'Y';
					$row1['create_date'] = date('Y-m-d');
					$ks_country_id = $this->common_model->addRecord('ks_countries',$row1);
			
			}
		
			$row['ks_country_id']= $ks_country_id;
			$row['ks_state_name']= $this->input->post('ks_state_name');
			$row['ks_state_code']= $this->input->post('ks_state_code');
			$row['ks_state_esl']= $this->input->post('ks_state_esl');
			$row['ks_state_sd']= $this->input->post('ks_state_sd');
			$row['base_rate']= $this->input->post('base_rate');
			$row['is_active']= $this->input->post('is_active');
			$row['update_date']= date('Y-m-d') ;
			$row['update_user']=  $this->session->userdata('ADMIN_ID') ;
			$this->db->where('ks_state_id', $id);
			$this->db->update('ks_states', $row); 
			
			$message = '<div class="alert alert-success"><p>State updated successfully.</p></div>';
		}
		
		
		$this->session->set_flashdata('success', $message);
		redirect('state');

	}

	

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
				$this->db->where('ks_state_id',$id);
				$this->db->delete('ks_states'); 
			}
			$message = '<div class="alert alert-success"><p>State have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success',$message);
			redirect('state');
		}

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('ks_state_id', $id);
        $this->db->delete('ks_states'); 
		$message = '<div class="alert alert-success"><p>State has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('state');

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
						/* print_r($importdata);echo count($importdata); exit();*/
						{
								if($count>0){
								
								        $q = $this->common_model->GetAllWhere("ks_countries",array("ks_country_name"=>trim($importdata[1])));
										if($q->num_rows()>0){
			
											$rs = $q->result();
											$ks_country_id = $rs[0]->ks_country_id;
											
											$row['ks_country_id']= $ks_country_id;
											$row['ks_state_name']= trim($importdata[2]);
											$row['ks_state_code']= trim($importdata[3]);
											$row['is_active']= 'Y' ;
											$row['create_date']= date('Y-m-d') ;
											$row['create_user']=  $this->session->userdata('ADMIN_ID') ;
											$this->common_model->addRecord('ks_states',$row);
										
										}
										else
										{
										
											$data['ks_country_name'] = trim($importdata[1]);
											$data['create_user']     = $this->session->userdata('ADMIN_ID');
											$data['is_active']       = 'Y';
											$data['create_date']     = date('Y-m-d');
											$ks_country_id = $this->common_model->addRecord('ks_countries',$data);
											
											
											$row['ks_country_id']= $ks_country_id;
											$row['ks_state_name']= trim($importdata[2]);
											$row['ks_state_code']= trim($importdata[3]);
											$row['is_active']= 'Y' ;
											$row['create_date']= date('Y-m-d') ;
											$row['create_user']=  $this->session->userdata('ADMIN_ID') ;
											$this->common_model->addRecord('ks_states',$row);
										
										
										}
								
								}
						$count++;
				   }                    
				   fclose($file);
				   $message = '<div class="alert alert-success">Data are imported successfully..</p></div>';
				   $this->session->set_flashdata('success', $message);
				   redirect('state');
				   }else{
				   $message = '<div class="alert alert-danger">Something went wrong..</p></div>';
				   $this->session->set_flashdata('success', $message);
				   redirect('state');
				   }
			}
	}

	

}?>