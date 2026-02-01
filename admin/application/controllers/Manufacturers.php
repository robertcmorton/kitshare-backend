<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manufacturers extends CI_Controller {
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
                'field' => 'manufacturer',
                'label' => 'manufacturer',
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
		
		$data['manufacturer']				= $this->input->get('manufacturer');
		if($data['manufacturer'] != ''){
				$where .=  " where manufacturer_name LIKE '%".trim($data['manufacturer'])."%'";
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
		$nSer="SELECT * FROM ks_manufacturers ".$where." ORDER BY manufacturer_name ".$order_by;
		$sql=$nSer." LIMIT ".$data['limit']." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$data['limit'];
		$config['base_url'] = base_url()."manufacturers/?manufacturer=".$data['manufacturer']."&order_by=".$order_by."&limit=".$data['limit'];
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
//////////////////////////////Pagination config//////////////////////////////////				
		
		$data['paginator'] = $paginator;
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('manufacturer/list', $data);
		$this->load->view('common/footer');		
	}
	public function add()
	{
		$data=array();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('manufacturer/add', $data);
		$this->load->view('common/footer');	
	}
	public function save()
	{
	$data=array();
	
	
	$this->form_validation->set_rules($this->validation_rules['Add']);
	if($this->form_validation->run())
	{

		$q = $this->common_model->GetAllWhere("ks_manufacturers",array("manufacturer_name"=>$this->input->post('manufacturer')));
		
		if($q->num_rows()>0){
			$message = '<div class="alert alert-success">Manufacturer is already added.</p></div>';
		}
		else{
			$data['manufacturer_name']= $this->input->post('manufacturer');
			$data['is_active']= $this->input->post('status');
			$data['create_date']= date('Y-m-d') ;
			$data['create_user'] = $this->session->userdata('ADMIN_ID');
			
			$this->common_model->addRecord('ks_manufacturers',$data);
			$message = '<div class="alert alert-success">Manufacturer has been successfully added.</p></div>';
		}
		$this->session->set_flashdata('success', $message);
		redirect('manufacturers');
					
	}
	else
	{
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('manufacturer/add', $data);
		$this->load->view('common/footer');	
	}
	
	}
	public function edit()
	{
	    $data = array();
		$id = $this->uri->segment(3);
		$where_array = array('manufacturer_id'=>$id);
		$data['result']= $this->common_model->get_all_record('ks_manufacturers',$where_array);	
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');					
		$this->load->view('manufacturer/edit', $data);
		$this->load->view('common/footer');		
	}
	public function update()
	{
		$data = array();
    	$id = $this->input->post('manufacturer_id');
   		
		$where_array = array('manufacturer_id'=>$id);
		$data['result']= $this->common_model->get_all_record('ks_manufacturers',$where_array);	
   
   
		$this->form_validation->set_rules($this->validation_rules['Add']);

		if($this->form_validation->run() == true )
		{
			$q = $this->common_model->GetAllWhere("ks_manufacturers",array("manufacturer_name"=>$this->input->post('manufacturer'),"manufacturer_id !="=>$id));
			if($q->num_rows()>0){
			
				$message = '<div class="alert alert-success">Manufacturer is already added.</p></div>';
			
			}else{
				
				///Transaction begins here
				$this->db->trans_begin();
				
				$row['manufacturer_name']= $this->input->post('manufacturer');
				$row['is_active']= $this->input->post('status');
				$row['update_date']= date('Y-m-d') ;
				$row['update_user'] = $this->session->userdata('ADMIN_ID');
				
				
				$this->db->where('manufacturer_id', $id);
				$this->db->update('ks_manufacturers', $row); 
				
				//Search table is updated
				$search_array = array();
				$search_array['manufacturer_name'] = $this->input->post('manufacturer');
				$this->db->where('ks_manufacturers_id', $id);
				$this->db->update('ks_user_gear_search', $search_array); 
				///
				
				//Transaction ends here
				if ($this->db->trans_status() === FALSE)
				{
						$this->db->trans_rollback();
						$message = '<div class="alert alert-danger"><p>Action failed! Please try again.</p></div>';
				}
				else
				{
						$this->db->trans_commit();
						$message = '<div class="alert alert-success"><p>Manufacturer updated successfully.</p></div>';
				}
				////////////////////////
				
			}
			$this->session->set_flashdata('success', $message);
			redirect('manufacturers');
	
		}
		else
		{	
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('manufacturer/edit', $data);
			$this->load->view('common/footer');	
		}
	
	}
	public function select_delete()
	{
	if(isset($_POST['bulk_delete_submit']))
	{
    $idArr = $this->input->post('checked_id');
    foreach($idArr as $id){
	        
			$where_array = array('manufacturer_id'=>$id);
			$lang= $this->common_model->get_all_record('ks_manufacturers',$where_array);
			
			foreach($lang as $im){
				$this->load->helper("file");
				$oldfile = manufacturer."/".$im->manufacturer_logo;
				unlink($oldfile);
			}
			
     		$this->common_model->delele('ks_manufacturers','manufacturer_id',$id);
			
    	}
		$message = '<div class="alert alert-success"><p>Manufacturer have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('manufacturers');
	 }
	}
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		$where_array = array('manufacturer_id'=>$id);
		$manufacturer= $this->common_model->get_all_record('ks_manufacturers',$where_array);
		foreach($manufacturer as $im){
			$this->load->helper("file");
			$oldfile = manufacturer."/".$im->manufacturer_logo;
			unlink($oldfile);
		}
		
		$this->common_model->delele('ks_manufacturers','manufacturer_id',$id);
		
		$message = '<div class="alert alert-success"><p>Manufacturer have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('manufacturers');
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
								   $q = $this->common_model->GetAllWhere("ks_manufacturers",array("manufacturer_name"=>trim($importdata[1])));

								   if($q->num_rows()==0){

										

										$data['manufacturer_name'] = trim($importdata[1]);

										$data['create_user']     = $this->session->userdata('ADMIN_ID');

										$data['is_active']       = 'Y';

										$data['create_date']     = date('Y-m-d');

										$this->common_model->addRecord('ks_manufacturers',$data);
									}
								}

						$count++;

				   }                    

				   fclose($file);

				   $message = '<div class="alert alert-success">Data are imported successfully..</p></div>';

				   $this->session->set_flashdata('success', $message);

				   redirect('manufacturers');

				   }else{

				   $message = '<div class="alert alert-danger">Something went wrong..</p></div>';

				   $this->session->set_flashdata('success', $message);

				   redirect('manufacturers');

				   }

			}

	}
	public function downloadallcsv(){

	$sql = "SELECT * FROM ks_manufacturers  ORDER BY manufacturer_name DESC ";
	$query = $this->db->query($sql);
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