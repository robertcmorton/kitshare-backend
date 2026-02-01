<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cust_gear_star_rating extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','pagination','email'));
		$this->load->model(array('common_model','mail_model'));
		if($this->session->userdata('ADMIN_ID') =='') {
		  redirect('login');
		}
	}
	
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
		
		$data['gear_name']				= $this->input->get('gear_name');
		if($data['gear_name'] != ''){
				$where .=  " where gear_name LIKE '%".trim($data['gear_name'])."%'";
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
		$nSer="SELECT * FROM ks_cust_gear_star_rating ".$where." GROUP BY(user_gear_desc_id) ORDER BY cust_gear_rating_id ".$order_by;
		$sql=$nSer." LIMIT ".$data['limit']." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$data['limit'];
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $data['limit'];
		$config['page_query_string'] = TRUE;
		$config['base_url'] = base_url()."cust_gear_star_rating/?gear_name=".$data['gear_name']."&order_by=".$order_by."&limit=".$data['limit'];
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
		$this->load->view('cust_gear_star_rating/list', $data);
		$this->load->view('common/footer');		
	}
	
	public function view_ratings()
	{
		$data=array();
		$gear_id=$this->uri->segment(3);
		
		
		$gear=$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>"Y","user_gear_desc_id"=>$gear_id));
		$data['gears']=$gear->result();
		
		$sql="SELECT * FROM ks_cust_gear_star_rating WHERE is_active='Y' AND user_gear_desc_id=".$gear_id." ORDER BY gear_star_rating_date DESC";
		$ratings=$this->db->query($sql);
		$data['ratings']=$ratings->result();
		
		$this->load->view("common/header");
		$this->load->view("common/left-menu");
		$this->load->view("cust_gear_star_rating/view_rating",$data);
		$this->load->view("common/footer");
	}
	
	
	public function select_delete()
	{
	if(isset($_POST['bulk_delete_submit']))
	{
    $idArr = $this->input->post('checked_id');
    foreach($idArr as $id){
	        
			$where_array = array('ks_cust_gear_star_rating_id'=>$id);
			$lang= $this->common_model->get_all_record('ks_cust_gear_star_rating',$where_array);
			}
			
     		$this->common_model->delele('ks_cust_gear_star_rating','ks_cust_gear_star_rating_id',$id);
			
    	}
		
		redirect('cust_gear_star_rating');
	 }
	
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		$where_array = array('ks_cust_gear_star_rating_id'=>$id);
		$manufacturer= $this->common_model->get_all_record('ks_cust_gear_star_rating',$where_array);

		$this->common_model->delele('ks_cust_gear_star_rating','ks_cust_gear_star_rating_id',$id);
		
		redirect('ks_cust_gear_star_rating');
	}
	
}?>