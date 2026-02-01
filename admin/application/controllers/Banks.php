<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banks extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','pagination','email'));
		$this->load->model(array('common_model','mail_model'));
		if($this->session->userdata('ADMIN_ID') =='') {
		  redirect('login');
		}
	}
	
	protected $validation_rules = array
        (
		'Add' => array(
            array(
                'field' => 'bank_name',
                'label' => 'bank_name',
                'rules' => 'trim|required'
            )
        ),
    );

	
	public function index()
	{
		$data=array();
		$where = " ";
		$limit=10;
		
		$data['bank']				= $this->input->get('bank');
		if($data['bank'] != ''){
				$where .=  " where bank_name LIKE '%".trim($data['bank'])."%'";
			}
		
		if($this->input->get("per_page")!= '')
		{
		$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		$nSer="SELECT * FROM ks_banks ".$where." ORDER BY bank_id DESC";
		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = base_url()."banks/?bank=".$data['bank']."";
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
		
		$data['paginator'] = $paginator;
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('banks/list', $data);
		$this->load->view('common/footer');		
	}
	public function add()
	{
		$data=array();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('banks/add', $data);
		$this->load->view('common/footer');	
	}
	public function save()
	{
	$data=array();
	
	
	$this->form_validation->set_rules($this->validation_rules['Add']);
	
	
	
	if($this->form_validation->run())
	{
			$newname= time();
			$filePath                = BANK_LOGO;
			$config['upload_path']   = $filePath;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['file_name']     = $newname;
			$config['max_size']      = "";
			$config['max_width']     = "";
			$config['max_height']    = "";
			
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			
			if (!$this->upload->do_upload('bank_logo'))
			{
			
				$error = array('error' => $this->upload->display_errors());		
			}
			else
			{	
				
				$imgdata = array('upload_data' => $this->upload->data());
				
			}
			
		$data['bank_name']= $this->input->post('bank_name');
		$data['bank_head_office']=$this->input->post('bank_head_office');
		$data['bank_logo']=$imgdata['upload_data']['file_name'];
		
		$is_active= $this->input->post('status');
		if($is_active=='Y')
		$data['is_active']='Y';
		else
		$data['is_active']='N';
		$data['create_date']= date('Y-m-d') ;
		$data['create_user'] = $this->session->userdata('ADMIN_ID');
		
		$this->common_model->addRecord('ks_banks',$data);
		$message = '<div class="callout callout-success">Bank has been successfully added.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('banks');
					
	}
	else
	{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('banks/add', $data);
		$this->load->view('common/footer');	
	}
	
	}
	public function edit()
	{
	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('bank_id'=>$id);
		$res= $this->common_model->GetAllWhere('ks_banks',$where_array);	
		$data['result'] =  $res->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('banks/edit', $data);
		$this->load->view('common/footer');		
	}
	public function update()
	{
		$data = array();
    	$id = $this->input->post('bank_id');
		$where_array = array('bank_id'=>$id);
		$res= $this->common_model->GetAllWhere('ks_banks',$where_array);	
		$data['result'] =  $res->result();
   
		$this->form_validation->set_rules($this->validation_rules['Add']);

		if($this->form_validation->run() == true )
		{
		
			$newname= time();
			$filePath                = BANK_LOGO;
			$config['upload_path']   = $filePath;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['file_name']     = $newname;
			$config['max_size']      = "";
			$config['max_width']     = "";
			$config['max_height']    = "";
			
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			
			if (!$this->upload->do_upload('bank_logo'))
			{
			
				$error = array('error' => $this->upload->display_errors());		
			}
			else
			{	
				
				$imgdata = array('upload_data' => $this->upload->data());
				
			}
	
	
		if(!empty($imgdata))
		{
			$where_array = array('bank_id'=>$id);
			$image= $this->common_model->get_all_record('ks_banks',$where_array);
			foreach($image as $img){
				$this->load->helper("file");
				$oldfile = BANK_LOGO."/".$img->bank_logo ; 
				unlink($oldfile);
			}
			$row['bank_logo'] = $imgdata['upload_data']['file_name'];
		}
		
		$row['bank_name']= $this->input->post('bank_name');
		$row['bank_head_office']=$this->input->post('bank_head_office');
		$row['is_active']= $this->input->post('status');	
		$row['update_date']= date('Y-m-d') ;
		$row['update_user'] = $this->session->userdata('ADMIN_ID');
		
		
		$this->db->where('bank_id', $id);
		$this->db->update('ks_banks', $row); 
		
		
		
		$message = '<div class="callout callout-success"><p>model updated successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('banks');
	
		}
		else
		{	
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('banks/edit', $data);
			$this->load->view('common/footer');	
		}
	
	}
	public function select_delete()
	{
	if(isset($_POST['bulk_delete_submit']))
	{
    $idArr = $this->input->post('checked_id');
    foreach($idArr as $id){
	        
			$where_array = array('bank_id'=>$id);
			$lang= $this->common_model->get_all_record('ks_banks',$where_array);
			
			foreach($lang as $im){
				$this->load->helper("file");
				$oldfile = BANK_LOGO."/".$im->bank_logo;
				unlink($oldfile);
			}
			
     		$this->common_model->delele('ks_banks','bank_id',$id);
			
    	}
		$message = '<div class="callout callout-success"><p>models have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('banks');
	 }
	}
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		$where_array = array('bank_id'=>$id);
		$bank= $this->common_model->get_all_record('ks_banks',$where_array);
		foreach($bank as $im){
			$this->load->helper("file");
			$oldfile = BANK_LOGO."/".$im->bank_logo;
			unlink($oldfile);
		}
		
		$this->common_model->delele('ks_banks','bank_id',$id);
		
		$message = '<div class="callout callout-success"><p>banks have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('banks');
	}
	
	
}?>