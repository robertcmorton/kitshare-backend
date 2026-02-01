<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {
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
		'settingsAdd' => array(
            array(
                'field' => 'setting_phone',
                'label' => 'Phone',
                'rules' => 'trim|required'
            ),
			array(
                'field' => 'setting_email',
                'label' => 'Email',
                'rules' => 'trim|required'
            ),
			array(
                'field' => 'gst_percent',
                'label' => 'GST%',
                'rules' => 'trim|required'
            ),
			array(
                'field' => 'usd_to_aud',
                'label' => 'Conversion Rate',
                'rules' => 'trim|required'
            ),
        ),
    );
	
	public function index()
	{
		$data=array();
		$where = " ";
		$limit=3;
		//$offset=0;
	
		$data['setting_email']				= $this->input->get('setting_email');
		if($data['setting_email'] != ''){
				$where .= "setting_email LIKE '%".trim($data['setting_email'])."%' AND ";
			}
		
		if($this->input->get("per_page")!= '')
		{
		$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		 $nEmail="SELECT * FROM ks_settings  ".$where." ORDER BY settings_id DESC";
		$sql=$nEmail." LIMIT ".$limit." OFFSET  ".$offset." ";
		$settings=$this->db->query($sql);		
		$total_rows=count($this->db->query($nEmail)->result());	
		$data['settings'] = $settings->result();
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
		$config['base_url'] = base_url()."settings/index/?email=".$data['setting_email']."";
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
		$this->load->view('settings/edit', $data);
		$this->load->view('common/footer');		
	}
	
	
	public function update_settings()
	{
	$data = array();
	
	$this->form_validation->set_rules($this->validation_rules['settingsAdd']);
	if($this->form_validation->run())
	{
	
		$newname= time();
		$filePath                = SITE_IMAGE;
		$config['upload_path']   = $filePath;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['file_name']     = $newname;
		$config['max_size']      = "";
		$config['max_width']     = "";
		$config['max_height']    = "";
		
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('setting_logo'))
		{
			$error = array('error' => $this->upload->display_errors());		
		}
		else
		{		
			$imgdata = array('upload_data' => $this->upload->data());
		}	
	
		if(!empty($imgdata))
		{
			$where_array = array('settings_id'=>1);
			$img= $this->common_model->get_all_record('ks_settings',$where_array);
			foreach($img as $im){
				$this->load->helper("file");
				$oldfile = SITE_IMAGE."/".$im->setting_logo ; 
				unlink($oldfile);
			}
			$row['setting_logo'] = $imgdata['upload_data']['file_name'];
		}
	
		$row['setting_phone']= $this->input->post('setting_phone');
		$row['setting_email']= $this->input->post('setting_email');
		$row['setting_twitter']= $this->input->post('setting_twitter');
		$row['setting_facebook']= $this->input->post('setting_facebook');
		$row['setting_linked_in']= $this->input->post('setting_linked_in');
		$row['instagram_link']= $this->input->post('instagram_link');
		$row['fb_link']= $this->input->post('fb_link');
		$row['twitter_link']= $this->input->post('twitter_link');
		$row['gst_percent']= $this->input->post('gst_percent');
		$row['usd_to_aud']= $this->input->post('usd_to_aud');
		$row['digitalId_url']= $this->input->post('digitalId_url');
		$row['digitalId_url_backend']= $this->input->post('digitalId_url_backend');
		$row['digitalId_url_userinfo']= $this->input->post('digitalId_url_userinfo');
		$row['max_replacement_value']= $this->input->post('max_replacement_value');
		$row['digitalId_secretid']= $this->input->post('digitalId_secretid');
		$row['digitalId_clientid']= $this->input->post('digitalId_clientid');
		$row['api_host']= $this->input->post('api_host');
		$row['braintree_private_key']= $this->input->post('braintree_private_key');
		$row['braintree_url']= $this->input->post('braintree_url');
		$row['braintree_merchant_id']= $this->input->post('braintree_merchant_id');
		$row['braintree_public_key']= $this->input->post('braintree_public_key');
		$row['updated_date']= date('Y-m-d') ;
		$row['updated_time']=  date('H:i:s') ;
		
		
			
		$this->db->update('ks_settings', $row); 
		$message = '<div class="alert alert-success"><p>Settings updated successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('settings');
		}else{
			
			$settings= $this->db->query("SELECT * FROM `ks_settings` WHERE `settings_id`=1");
			$data['settings'] = $settings->result();
			$data['total_rows'] = count($data['settings']);
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('settings/edit', $data);
			$this->load->view('common/footer');	
		}
	
	}

	
}?>