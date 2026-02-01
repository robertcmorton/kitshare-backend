<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Users extends CI_Controller {

	 public function __construct() {

		parent::__construct();

		$this->load->helper(array('url','form','html','text','common_helper'));

		$this->load->library(array('session','form_validation','pagination','email','upload','image_lib'));

		$this->load->model(array('common_model','mail_model','model'));

		if($this->session->userdata('ADMIN_ID') =='') {

          redirect('login');

		  }

	}

		

		protected $validation_rules = array

        (

		'Add' => array(

			array(

                'field' => 'username',

                'label' => 'Username',

                'rules' => 'trim|required|is_unique[m_app_user.app_user_name]'

            ),

			array(

                'field'   => 'password',

                'label'   => 'Password',

                'rules'   => 'trim|required'

            ),



			array(

                'field'   => 'cnfpwd',

                'label'   => 'Confirm Password',

                'rules'   => 'trim|required|matches[password]'

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
		
		$data['username']				= $this->input->get('username');
		if($data['username'] != ''){
				$where .=  " where app_user_name LIKE '%".trim($data['username'])."%'";
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
		$nSer="SELECT * FROM m_app_user ".$where." ORDER BY app_user_name ".$order_by;
		$sql=$nSer." LIMIT ".$data['limit']." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$data['limit'];
		
		$config['base_url'] = base_url()."users/?users=".$data['username']."&order_by=".$order_by."&limit=".$data['limit'];
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
		$data['paginator'] = $paginator;
		//echo $this->db->last_query(); die;
		
		//print_r($data['app_users']->result()); exit();

//////////////////////////////Pagination config//////////////////////////////////				


		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('users/list', $data);
		$this->load->view('common/footer');		

	

	}

	

	public function add()
	{
		$data=array();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('meet_team/add', $data);
		$this->load->view('common/footer');
	}

	public function save()

	{

	

	$data=array();

	$this->form_validation->set_rules($this->validation_rules['Add']);

	if($this->form_validation->run())
	{

		$data['app_user_name']= $this->input->post('username');
		$data['app_user_pwd']= $this->common_model->base64En(2,trim($this->input->post('password')));
		$data['creator'] = $this->session->userdata('ADMIN_ID');
		$data['app_user_email'] = $this->input->post('email');
		$data['created_date'] = date('Y-m-d');
		$data['created_time'] = date('H:i:s');
		$this->common_model->addRecord(M_APP_USER,$data);
		$message = '<div class="alert alert-success">User has been successfully added.</p></div>';
		$this->session->set_flashdata('success', $message);

	    redirect('users');

	 }else{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('users/add', $data);
		$this->load->view('common/footer');	

	  }

	}

	

	public function edit()
	{

	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('app_user_id'=>$id);
		$data['user']= $this->common_model->get_all_record(M_APP_USER,$where_array);	
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('users/edit', $data);
		$this->load->view('common/footer');		

	}

	public function update()
	{

		$data = array();
		$app_user_id= $this->input->post('app_user_id');
		$data['app_user_name']= $this->input->post('username');
		//$data['app_user_pwd']= $this->common_model->base64En(2,trim($this->input->post('password')));
		$data['app_user_email']= $this->input->post('email');
		$data['active']= $this->input->post('active');
		$data['modifier'] = $this->session->userdata('ADMIN_ID');
		$data['pre_add'] = $this->input->post('pre_add');
		$data['pre_edit'] = $this->input->post('pre_edit');
		$data['pre_view'] = $this->input->post('pre_view');
		$data['pre_delete'] = $this->input->post('pre_delete');
		$data['active'] = $this->input->post('active');
		$data['updated_date']= date('Y-m-d') ;
		$data['updated_time']=  date('H:i:s') ;
		$this->db->where('app_user_id', $app_user_id);
		$this->db->update(M_APP_USER, $data); 
		$message = '<div class="alert alert-success">User has been successfully updated.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('users');

	}

	public function view()
	{

	   $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('app_user_id'=>$id);
		$data['user']= $this->common_model->get_all_record(M_APP_USER,$where_array);	
	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('users/view', $data);
		$this->load->view('common/footer');		

	}

	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
	
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id){
				$this->db->where('app_user_id', $id);
				$this->db->delete(M_APP_USER);    
	
			}
	
			$message = '<div class="alert alert-success"><p>Users have been deleted successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('users');
	
		 }

	}

	public function delete_record()
	{

		$id=$this->uri->segment(3);
		$this->db->where('app_user_id', $id);
		$this->db->delete(M_APP_USER); 
		$message = '<div class="alert alert-success"><p>User has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('users');

	}

	
	public function username_check()
	{
	 
	 $username = $this->input->post('username'); 
	 $username_exist = $this->model->checkUserAd($username);
		
		if($username_exist->num_rows() > 0 ){
            echo 'false';
        } else {
            echo 'true';
        }
	 
	 }
	 
	 
	 public function username_check_edit(){
	 
	 
		$username = $this->input->post('username');
		$id = $this->uri->segment(3); 
		$username_exist = $this->model->checkUserEdit($username,$id);
			
			if($username_exist->num_rows() > 0 ){
				echo 'false';
			} else {
				echo 'true';
			}
	 
	 
	 
	 }

}?>