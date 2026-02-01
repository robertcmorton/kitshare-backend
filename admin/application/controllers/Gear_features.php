<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gear_features extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text','common_helper'));
		$this->load->library(array('session','form_validation','pagination','email'));
		$this->load->model(array('common_model','mail_model','model'));
		if($this->session->userdata('ADMIN_ID') =='') {
          redirect('login');
		  }
	}
	
	protected $validation_rules = array
        (
		'Add' => array(
            array(
                'field' => 'feature_name',
                'label' => 'Feature Name',
                'rules' => 'trim|required'
            ),
			array(
				'field' => 'user_gear_desc_id',
				'label' => 'Gear',
				'rules' => 'trim|required'
			),
			
        ),
    );

	public function index()
	{

	    $data=array();
		
		$where ='';
		
		$limit=10;
		

		$data['gear_name']		  = $this->input->get('gear_name');
				if($data['gear_name'] != ''){
				$where .= " WHERE e.user_gear_desc_id = (SELECT user_gear_desc_id FROM ks_user_gear_description WHERE gear_name LIKE '%".trim($data['gear_name'])."%') AND ";
			}
		$data['feature_name']		  = $this->input->get('feature_name');
				if($data['feature_name'] != ''){
				$where .= " WHERE e.feature_name  LIKE '%".trim($data['feature_name'])."%' AND ";
			}
		
		$desc=$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y'));
		$data['gear_desc'] = $desc->result();
			
		
			
		$where = substr($where,0,(strlen($where)-4));

		
		if($this->input->get("per_page")!= '')
		{
			$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		 
		if($this->input->get("per_page")!= '')
		{
		$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}
		$nSer="SELECT * FROM ks_user_gear_features e".$where." ORDER BY e.user_gear_feature_id DESC";
		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;
		$data['total_rows']=$total_rows;
		$data['limit']=$limit;
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

		//echo $this->db->last_query(); exit();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('gear_features/list', $data);
		$this->load->view('common/footer');		

	

	}
	public function add()
	{
		$data=array();
		$desc=$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y'));
		$data['gear_desc'] = $desc->result();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('gear_features/add', $data);
		$this->load->view('common/footer');	
	}
	public function save()
	{
		$data=array();
		
		$this->form_validation->set_rules($this->validation_rules['Add']);
		
		$desc=$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y'));
		$data['gear_desc'] = $desc->result();
		$de=$desc->result();
		
		$count=$this->common_model->countAll("ks_user_gear_features",array("is_active"=>'Y',"user_gear_desc_id"=>$de[0]->user_gear_desc_id));
		
		if($this->form_validation->run())
		{

		$row['user_gear_desc_id']= $this->input->post('user_gear_desc_id');
		$row['feature_name']= $this->input->post('feature_name');
		$row['feature_description']= $this->input->post('feature_description');
		$row['feature_display_seq_id']= $count+1;
		$row['create_user']= $this->session->userdata('ADMIN_ID');
		if($this->input->post('status')=="Active")
		{
			$row['is_active']= 'Y';
		}
		else
		{
			$row['is_active']= 'N';	
		}
		$row['create_date']= date('Y-m-d') ;
		
		$this->common_model->addRecord('ks_user_gear_features',$row);
		$message = '<div class="alert alert-success">Gear feature has been successfully added.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('gear_features');
						
		}
		else
		{
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');					
			$this->load->view('gear_features/add', $data);
			$this->load->view('common/footer');	
		}
	
	}
	public function edit()
	{
		$data=array();
		
		$id = $this->uri->segment(3);
		$gid = $this->uri->segment(4);
		$where_array = array('user_gear_feature_id'=>$id);
		$device= $this->common_model->GetAllWhere("ks_user_gear_features",$where_array);
		$data['result'] = $device->result();
		
		$desc=$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y'));
		$data['gear_desc'] = $desc->result();
		
		$disp_seq=$this->common_model->GetAllWhere("ks_user_gear_features",array("is_active"=>'Y',"user_gear_desc_id"=>$gid));
		$data['feature_display_seq_id'] = $disp_seq->result();
		
			
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('gear_features/edit', $data);
		$this->load->view('common/footer');		
	}
	
	public function update()
	{
		$data = array();
		$id=$this->input->post('user_gear_desc_id');
		
		$this->form_validation->set_rules($this->validation_rules['Add']);
		
		$where_array = array('user_gear_feature_id'=>$id);
		$device= $this->common_model->GetAllWhere("ks_user_gear_features",$where_array);
		$data['result'] = $device->result();
		
		$desc=$this->common_model->GetAllWhere("ks_user_gear_description",array("is_active"=>'Y'));
		$data['gear_desc'] = $desc->result();
		
		$disp_seq=$this->common_model->GetAllWhere("ks_user_gear_features",array("is_active"=>'Y'));
		$data['feature_display_seq_id'] = $disp_seq->result();
		
		if($this->form_validation->run() == true )
		{
	
		$row['user_gear_desc_id']= $this->input->post('user_gear_desc_id');
		$row['feature_name']= $this->input->post('feature_name');
		$row['feature_description']= $this->input->post('feature_description');
		$feature_display_seq_id_new= $this->input->post('feature_display_seq_id_new');
		$feature_display_seq_id_old= $this->input->post('feature_display_seq_id_old');
		if($feature_display_seq_id_new==$feature_display_seq_id_old)
			$row['feature_display_seq_id']=$feature_display_seq_id_old;
		else
		{
			$row['feature_display_seq_id']=$feature_display_seq_id_new;
			$in['feature_display_seq_id']=$feature_display_seq_id_old;
			$this->db->where('user_gear_desc_id',$this->input->post('user_gear_desc_id'));
			$this->db->where('feature_display_seq_id',$feature_display_seq_id_new);
			$this->db->update('ks_user_gear_features', $in); 
		}
		$row['update_user']= $this->session->userdata('ADMIN_ID');
		if($this->input->post('status')=="Active")
		{
			$row['is_active']= 'Y';
		}
		else
		{
			$row['is_active']= 'N';	
		}
		$row['update_date']= date('Y-m-d') ;
		
		$this->db->where('user_gear_feature_id', $id);
		$this->db->update('ks_user_gear_features', $row); 
		
		
		$message = '<div class="alert alert-success"><p>Gear features updated successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('gear_features');
	
		}
		else
		{	
			$this->load->view('common/header');	
			$this->load->view('common/left-menu');
			$this->load->view('gear_features/edit', $data);
			$this->load->view('common/footer');	
		}
	
	}
	
	
	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id)
			{
	        
			$where_array = array('user_gear_feature_id'=>$id);
			$image= $this->common_model->get_all_record('ks_user_gear_features',$where_array);
			
			/*foreach($image as $im){
				$this->load->helper("file");
				$oldfile = MODEL_IMAGE."/".$im->model_image;
				unlink($oldfile);
			}*/
			$cnt=$image[0]->feature_display_seq_id;
			$update=" UPDATE `ks_user_gear_features` SET `feature_display_seq_id`=`feature_display_seq_id`-1 WHERE `feature_display_seq_id`>".$cnt." AND `user_gear_desc_id`=".$image[0]->user_gear_desc_id;
			$this->db->query($update);
     		$this->common_model->delele('ks_user_gear_features','user_gear_feature_id',$id);
			
    	}
		$message = '<div class="alert alert-success"><p>Gears have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('gear_features');
		}
	}
	
	
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		$where_array = array('user_gear_feature_id'=>$id);
		$image= $this->common_model->get_all_record('ks_user_gear_features',$where_array);
		/*foreach($image as $im){
				$this->load->helper("file");
				$oldfile = MODEL_IMAGE."/".$im->model_image;
				unlink($oldfile);
			}*/
		$cnt=$image[0]->feature_display_seq_id;
		$update=" UPDATE `ks_user_gear_features` SET `feature_display_seq_id`=`feature_display_seq_id`-1 WHERE `feature_display_seq_id`>".$cnt." AND `user_gear_desc_id`=".$image[0]->user_gear_desc_id;
		$this->db->query($update);
		$this->common_model->delele('ks_user_gear_features','user_gear_feature_id',$id);
		
		$message = '<div class="alert alert-success"><p>Gear has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('gear_features');
	}
	
	
}?>
