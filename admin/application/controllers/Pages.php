<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {
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
		'cmspagesAdd' => array(
			array(
                'field' => 'page',
                'label' => 'Page Title ',
                'rules' => 'trim|required'
            ),
			array(
                'field' => 'page_code',
                'label' => 'Page Code',
                'rules' => 'trim|required'
            ),
			array(
                'field' => 'content',
                'label' => 'Content',
                'rules' => 'trim|required'
            )
			
			
        ),
    );

	
	public function index()
	{
		$data=array();
		$where = " ";
		$limit=10;
		//$offset=0;
	
		$data['page']				= $this->input->get('page');
		if($data['page'] != ''){
				$where .=  " where page LIKE '%".trim($data['page'])."%'";
			}
		
		if($this->input->get("per_page")!= '')
		{
		$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		//$nSer="SELECT * FROM tbl_faq ".$where." ORDER BY faq_id DESC";
		$nSer="SELECT a.cms_page_id,a.created_date,a.content,b.page from ks_page_content a INNER JOIN  ks_cms_pages b ON a.cms_page_id=b.cms_page_id ".$where."";
		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";
		
		$page_data=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['pages'] = $page_data->result();
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = base_url()."pages/?title=".$data['page']."";
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
	$this->load->view('pages/list', $data);
	$this->load->view('common/footer');		
	}
	public function add()
	{
		$data=array();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('pages/add', $data);
		$this->load->view('common/footer');	
	}
	public function save_page()
	{
	$data=array();
	
	$this->form_validation->set_rules($this->validation_rules['cmspagesAdd']);
	
	if($this->form_validation->run())
	{
	    $where_array = array("page"=>$this->input->post('page'));
		$page = $this->common_model->get_all_record('ks_cms_pages',$where_array);
		
		if(count($page)>0){
				$message = '<div class="alert alert-success">Page already added.</p></div>';
		}
		else
		{
			$row['page']= $this->input->post('page');
			$row['page_code']=$this->input->post('page_code');	
			$row['created_date']= date('Y-m-d') ;
			$row['created_time']=  date('H:i:s') ;
		
		
		
			$insert_id=$this->common_model->addRecord('ks_cms_pages',$row);
		
		
			if($insert_id>0)
			{
			
			$rows['cms_page_id']= $insert_id;
			$rows['content']= htmlspecialchars($this->input->post('content'));
			
			$rows['created_date']= date('Y-m-d') ;
			$rows['created_time']=  date('H:i:s') ;
			
			
			$this->common_model->addRecord('ks_page_content',$rows);
			
			}
			$message = '<div class="alert alert-success">Page has been successfully added.</p></div>';
		}
		 $this->session->set_flashdata('success', $message);
		 redirect('pages');
					}else{
	$this->load->view('common/header');	
	$this->load->view('common/left-menu');					
	$this->load->view('pages/add', $data);
	$this->load->view('common/footer');	
					}
	}
	public function edit()
	{
		$data = array();
		$id = $this->uri->segment(3);
		$data['page']= $this->common_model->get_all_record_page('ks_cms_pages','ks_page_content',$id);
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('pages/edit', $data);
		$this->load->view('common/footer');		
	}
		public function view()
	{
		$data = array();
		$id = $this->uri->segment(3);
		$data['page']= $this->common_model->get_all_record_page('ks_cms_pages','ks_page_content',$id);
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('pages/view', $data);
		$this->load->view('common/footer');		
	}
	public function update_page()
	{
	$data = array();
    $id = $this->input->post('id');
	
	$this->form_validation->set_rules($this->validation_rules['cmspagesAdd']);
		

	if($this->form_validation->run() == true )
	{
	
	$row['page']= $this->input->post('page');
	$row['page_code']=$this->input->post('page_code');	
	$row['updated_date']= date('Y-m-d') ;
	$row['updated_time']=  date('H:i:s') ;
	$this->db->where('cms_page_id', $id);
    $this->db->update('ks_cms_pages', $row);
	if($this->db->affected_rows()>0)
	
	{
	
	
	$rows['content']= htmlspecialchars($this->input->post('content'));
	
	
	$rows['updated_date']= date('Y-m-d') ;
	$rows['updated_time']=  date('H:i:s') ;
	$this->db->where('cms_page_id', $id);
    $this->db->update('ks_page_content', $rows);
	
	} 
	$message = '<div class="alert alert-success"><p>Page updated successfully.</p></div>';
	$this->session->set_flashdata('success', $message);
	 redirect('pages');
					}else{
				//redirect($_SERVER['HTTP_REFERER']);

	$this->load->view('common/header');	
	$this->load->view('common/left-menu');
	$where_array = array('cms_page_id'=>$id);
	$data['language']= $this->common_model->get_all_record('ks_cms_pages',$where_array);
	$this->load->view('page/edit', $data);
	$this->load->view('common/footer');	
					}
	
	}
	public function select_delete()
	{
	if(isset($_POST['bulk_delete_submit']))
	{
    $idArr = $this->input->post('checked_id');
    foreach($idArr as $id){
	        
			$this->db->delete('ks_cms_pages', array('cms_page_id' => $id)); 
    	}
	$message = '<div class="alert alert-success"><p>Page have been deleted successfully.</p></div>';
	 $this->session->set_flashdata('success', $message);
	 redirect('pages');
	 }
	}
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		
		$this->db->delete('ks_cms_pages', array('cms_page_id' => $id)); 

		
		$message = '<div class="alert alert-success"><p>Page been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('pages');
	}
	
	
}?>
