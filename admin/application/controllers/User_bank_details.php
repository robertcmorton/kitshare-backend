<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_bank_details extends CI_Controller {
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
                'field' => 'user_account_number',
                'label' => 'Branch Code',
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
		
		$data['branch_code']				= $this->input->get('branch_code');
		if($data['branch_code'] != ''){
				$where .=  " where branch_code LIKE '%".trim($data['branch_code'])."%'";
			}
		$data['bsb_number']				= $this->input->get('bsb_number');
		if($data['bsb_number'] != ''){
				$where .=  " where bsb_number LIKE '%".trim($data['bsb_number'])."%'";
			}
		$data['branch_suburb_name']				= $this->input->get('branch_suburb_name');
		if($data['branch_suburb_name'] != ''){
				$where .=  " where branch_suburb_id = ( SELECT ks_suburb_id FROM ks_suburbs WHERE suburb_name LIKE '%".trim($data['branch_suburb_name'])."%')";
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
		$nSer="SELECT * FROM ks_user_bank_details ".$where." ORDER BY user_bank_detail_id DESC";
		$sql=$nSer." LIMIT ".$data['limit']." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		//echo $this->db->last_query();
		//exit ();
		
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$data['limit'];
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $data['limit'];
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url()."user_bank_details/?branch_code=".$data['branch_code']."&order_by=".$order_by."&limit=".$data['limit'];
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
		$this->load->view('user_bank_details/list', $data);
		$this->load->view('common/footer');		
	}
	public function add()
	{
		$data=array();
		
		$bank=$this->common_model->GetAllWhere("ks_banks",array("is_active"=>'Y'));
		$data['bank'] = $bank->result();
		
		$user=$this->common_model->GetAllWhere("ks_users",array("is_active"=>'Y'));
		$data['user'] = $user->result();
		
		$country=$this->common_model->GetAllWhere("ks_countries",array("is_active"=>'Y'));
		$data['country'] = $country->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('user_bank_details/add', $data);
		$this->load->view('common/footer');	
	}
	public function save()
	{
	$data=array();
	
	
	$this->form_validation->set_rules($this->validation_rules['Add']);
	
	
	
	if($this->form_validation->run())
	{

		$data['account_type']= $this->input->post('account_type');
		$data['branch_code']= $this->input->post('branch_code');
		$data['app_user_id']= $this->input->post('app_user_id');
		$data['bank_id']= $this->input->post('bank_id');
		$data['bsb_number']= $this->input->post('bsb_number');
		$data['branch_address']= $this->input->post('branch_address');
		$data['branch_street']= $this->input->post('branch_street');	
		$data['branch_city']= $this->input->post('branch_city');	
		$data['branch_zip_code']= $this->input->post('branch_zip_code');	
		$data['branch_suburb_id']= $this->input->post('branch_suburb_id');	
		$data['branch_state_id']= $this->input->post('branch_state_id');	
		$data['branch_country_id']= $this->input->post('branch_country_id');	
		$data['user_account_number']= $this->input->post('user_account_number');	
		$data['accept_stripe_connection']= $this->input->post('accept_stripe_connection');
		
		$is_active= $this->input->post('status');
		if($is_active=='Y')
		$data['is_active']='Y';
		else
		$data['is_active']='N';
		$data['create_date']= date('Y-m-d') ;
		$data['create_user'] = $this->session->userdata('ADMIN_ID');
		
		$this->common_model->addRecord('ks_user_bank_details',$data);
		$message = '<div class="callout callout-success">User bank detail has been successfully added.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('user_bank_details');
					
	}
	else
	{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('user_bank_details/add', $data);
		$this->load->view('common/footer');	
	}
	
	}
	public function edit()
	{
	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('user_bank_detail_id'=>$id);
		$data['result']= $this->common_model->get_all_record('ks_user_bank_details',$where_array);	
		
		$bank=$this->common_model->GetAllWhere("ks_banks",array("is_active"=>'Y'));
		$data['bank'] = $bank->result();
		
		$user=$this->common_model->GetAllWhere("ks_users",array("is_active"=>'Y'));
		$data['user'] = $user->result();
		
		$country=$this->common_model->GetAllWhere("ks_countries",array("is_active"=>'Y'));
		$data['country'] = $country->result();
		
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('user_bank_details/edit', $data);
		$this->load->view('common/footer');		
	}
	public function update()
	{
		$data = array();
    	$id = $this->input->post('user_bank_detail_id');
   
		$this->form_validation->set_rules($this->validation_rules['Add']);

		if($this->form_validation->run() == true )
		{
	
		$row['account_type']= $this->input->post('account_type');
		$row['branch_code']= $this->input->post('branch_code');
		$row['app_user_id']= $this->input->post('app_user_id');
		$row['user_account_name']= $this->input->post('user_account_name');
		$row['bank_id']= $this->input->post('bank_id');
		$row['bsb_number']= $this->input->post('bsb_number');
		$row['branch_address']= $this->input->post('branch_address');
		$row['branch_street']= $this->input->post('branch_street');	
		$row['branch_city']= $this->input->post('branch_city');	
		$row['branch_zip_code']= $this->input->post('branch_zip_code');	
		$row['branch_suburb_id']= $this->input->post('branch_suburb_id');	
		$row['branch_state_id']= $this->input->post('branch_state_id');	
		$row['branch_country_id']= $this->input->post('branch_country_id');	
		$row['user_account_number']= $this->input->post('user_account_number');	
		$row['accept_stripe_connection']= $this->input->post('accept_stripe_connection');
		$row['is_active']= $this->input->post('status');	
		$row['update_date']= date('Y-m-d') ;
		$row['update_user'] = $this->session->userdata('ADMIN_ID');
		
		
		$this->db->where('user_bank_detail_id', $id);
		$this->db->update('ks_user_bank_details', $row); 
		
		
		
		$message = '<div class="callout callout-success"><p>Bank details updated successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('user_bank_details');
	
		}
		else
		{	
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('user_bank_details/edit', $data);
			$this->load->view('common/footer');	
		}
	
	}
	public function select_delete()
	{
	if(isset($_POST['bulk_delete_submit']))
	{
    $idArr = $this->input->post('checked_id');
    foreach($idArr as $id){
	        
			$where_array = array('user_bank_detail_id'=>$id);
			$lang= $this->common_model->get_all_record('ks_user_bank_details',$where_array);
			
     		$this->common_model->delele('ks_user_bank_details','user_bank_detail_id',$id);
			
    	}
		$this->session->set_flashdata('success', $message);
		redirect('user_bank_details');
	 }
	}
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		$where_array = array('user_bank_detail_id'=>$id);
		$manufacturer= $this->common_model->get_all_record('ks_user_bank_details',$where_array);
		
		$this->common_model->delele('ks_user_bank_details','user_bank_detail_id',$id);
		
		$message = '<div class="callout callout-success"><p>manufacturers have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('user_bank_details');
	}
	
	public function ajax_state()
	{				
		$data=array();
		$state=$this->common_model->GetAllWhere("ks_states",array("is_active"=>'Y','ks_country_id'=>$this->input->post('data')));
		$data['state'] = $state->result();
		$data['count']= $this->common_model->countAll("ks_states",array("is_active"=>'Y','ks_country_id'=>$this->input->post('data')));
		$this->load->view('user_bank_details/ajax_state',$data);
	}
	
	public function ajax_suburb()
	{				
		$data=array();
		$suburb=$this->common_model->GetAllWhere("ks_suburbs",array("is_active"=>'Y','ks_state_id'=>$this->input->post('data')));
		$data['suburb'] = $suburb->result();
		$data['count']= $this->common_model->countAll("ks_suburbs",array("is_active"=>'Y','ks_state_id'=>$this->input->post('data')));
		$this->load->view('user_bank_details/ajax_suburb',$data);
	}
	public function ajax_pincode()
	{				
		$data=array();
		$suburb=$this->common_model->GetAllWhere("ks_suburbs",array("is_active"=>'Y','ks_suburb_id'=>$this->input->post('data')));
		$data['pincode'] = $suburb->row();
		echo $data['pincode']->suburb_postcode;
		
	}
	 public function import()

	  {

		 $data = array();

		 $heading = array();

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
										for($i=1;$i<$cnt;$i++){
											if($i<17){
												$key = $heading[$i];
												$data[$key] = $importdata[$i];

											}

										}

									

										$this->common_model->addRecord('ks_user_bank_details',$data);
									}

									$count++;

					   }                    

					   fclose($file);

					   $message = '<div class="alert alert-success">Data imported successfully..</p></div>';

					   $this->session->set_flashdata('success', $message);

					   redirect('user_bank_details');

				   }else{

				   $message = '<div class="alert alert-danger">Data import aborted. Something went wrong.</p></div>';

				   $this->session->set_flashdata('success', $message);

				   redirect('user_bank_details');

				   }

			}

	}
	public function downloadallcsv(){
	
	$this->db->select('a.`app_user_id`,b.`bank_name`,a.`account_type`,a.`branch_code`,a.`bsb_number`,a.`branch_address`,a.`branch_street`, a.`branch_city`, a.`branch_zip_code`, c.`suburb_name`, d.`ks_state_name`, e.`ks_country_name`, a.`user_account_number`, a.`accept_stripe_connection`, a.`is_active`, f.`app_username`, a.`update_user`, a.`update_date`');
	$this->db->from("ks_user_bank_details as a");
	$this->db->join("ks_banks as b", "b.bank_id = a.bank_id","inner");
	$this->db->join("ks_suburbs as c", "c.ks_suburb_id = a.branch_suburb_id","inner");
	$this->db->join("ks_states as d", "d.ks_state_id = a.branch_state_id","inner");
	$this->db->join("ks_countries as e", "e.ks_country_id = a.branch_country_id","inner");
	$this->db->join("ks_users as f", "f.app_user_id = a.app_user_id","inner");
		
	//$sql = "SELECT * FROM ks_manufacturers  ORDER BY manufacturer_name DESC ";
	$query = $this->db->get();
	
		$fp = fopen('php://output', 'w');
		if ($fp && $query) {
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="exportall.csv"');
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