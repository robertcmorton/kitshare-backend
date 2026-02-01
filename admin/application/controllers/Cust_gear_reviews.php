<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cust_gear_reviews extends CI_Controller {
   public function __construct() {
    parent::__construct();
    $this->load->helper(array('url','form','html','text'));
    $this->load->library(array('session','form_validation','pagination','email'));
    $this->load->model(array('common_model','mail_model','model'));
    if($this->session->userdata('ADMIN_ID') =='') {
      redirect('login');
    }
  }
  
  public function index()
  {
    $data=array();
    $where = "WHERE parent_gear_review_id = 0 ";
    if($this->input->get('limit') != ''){
        $data['limit']  = $this->input->get('limit');
    }
    else{
      $data['limit']  = 25;
    }
    
    $data['gear_name']        = $this->input->get('gear_name');
    if($data['gear_name'] != ''){
        $where .=  " AND reviews.order_id  =  '".trim($data['gear_name'])."'";
      }
    $data['cust_gear_review_desc']        = $this->input->get('cust_gear_review_desc');
    if($data['cust_gear_review_desc'] != ''){
        $where .=  " where cust_gear_review_desc LIKE '%".trim($data['cust_gear_review_desc'])."%'";
      }
    
    $data['order_by']       = $this->input->get('order_by');
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
    $nSer="SELECT r_details.project_name,reviews.*,users.app_user_first_name As review_given_to_f_name ,users.app_user_last_name As review_given_to_l_name,b.app_user_first_name As review_given_by_f_name ,b.app_user_last_name As review_given_by_l_name FROM ks_cust_gear_reviews As reviews INNER JOIN ks_users As users ON  users.app_user_id =reviews.app_user_id INNER JOIN ks_users As b ON  b.app_user_id =reviews.create_user  INNER JOIN ks_user_gear_rent_details As r_details ON r_details.order_id = reviews.order_id  ".$where." GROUP BY(order_id) ORDER BY ks_cust_gear_review_id ".$order_by;
    $sql=$nSer." LIMIT ".$data['limit']." OFFSET  ".$offset." ";
    $result=$this->db->query($sql);   
    $total_rows=count($this->db->query($nSer)->result()); 
    $data['result'] = $result;
    $data['total_rows']=$total_rows;
    $data['limit']=$data['limit'];
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $data['limit'];
    $config['page_query_string'] = TRUE;
    $config['base_url'] = base_url()."cust_gear_reviews/?gear_name=".$data['gear_name']."&order_by=".$order_by."&limit=".$data['limit'];
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
    $this->load->view('cust_gear_reviews/list', $data);
    $this->load->view('common/footer');   
  }
  
  public function view_reviews()
  {
    $data=array();
 	$order_id=$this->uri->segment(3);
 	$query =  $this->common_model->getAllwhere('ks_user_gear_rent_details',array('order_id'=>$order_id));
 	$order_details =  $query->row();
    $owner_review =  $this->GetOwnerReviews($order_details->create_user,$order_id);
    $owner_review =  json_decode($owner_review,true) ;
    $data['owner_review']=$owner_review['result'];


    $renter_review =  $this->GetRenterReview($order_details->create_user,$order_id);
    $renter_review =  json_decode($renter_review,true) ;
    $data['renter_review']=$renter_review['result'];

    $this->load->view("common/header");
    $this->load->view("common/left-menu");
    $this->load->view("cust_gear_reviews/view_reviews",$data);
    $this->load->view("common/footer");
  }

  public function GetOwnerReviews($app_user_id,$order_id)
	{
		
		
		if ($app_user_id != '') {
			$where = array(
							'a.parent_gear_review_id' =>0,
							'a.app_user_id ' =>$app_user_id,
							'a.order_id'=>$order_id
						);

			$query =  $this->model->OwnerReviewList($where);
			$user_data = array();
			$result = $query->result();
			if (!empty($result)) {
				foreach ($result as  $value) {

					$this->db->select('a.* ,orders.project_name, users.app_user_first_name AS app_user_first_name_given_by, users.app_user_last_name AS app_user_last_name_given_by ,users.user_profile_picture_link As user_profile_picture_link_given_by,,users.bussiness_name ,users.show_business_name ');
					$this->db->from('ks_cust_gear_reviews AS a');
					$this->db->join('ks_users as users' , 'a.create_user = users.app_user_id' ,'INNER');
					$this->db->join('ks_user_gear_rent_details as orders' , 'orders.order_id = a.order_id' ,'INNER');
					// $this->db->join('ks_user_gear_description as gears' , 'gears.app_user_id = a.app_user_id AND orders.user_gear_desc_id = gears.user_gear_desc_id' ,'INNER');
					$this->db->where('parent_gear_review_id' ,$value->ks_cust_gear_review_id );
					$this->db->group_by('a.order_id');
					$query = $this->db->get();

					$data = $query->row();
					if (!empty($data)) {
						// $data->project_name = '';
					 //    $data->app_user_id_given_by = '';
					 //    $data->app_user_first_name_given_by = '';
					 //    $data->user_profile_picture_link_given_by = '';
					 //    $data->app_user_last_name_given_by = '';
						$user_data[] = $data ;
					}
				}
			}
			if (!empty($user_data)) {
				$result =  array_merge($result , $user_data);
			}

			if (!empty($result)) {
				$i =0 ;
				foreach ($result as $value) {
					if ($result[$i]->user_profile_picture_link_given_by == '') {
						$result[$i]->user_profile_picture_link_given_by =  BASE_URL."server/assets/images/profile.png";
					}
					if($result[$i]->show_business_name == 'Y'){
					    $result[$i]->app_user_first_name_given_by   =   $result[$i]->bussiness_name ;
					    $result[$i]->app_user_last_name_given_by   =  '' ;

					}

					 $i++;
				}
			# code...
			}

			// print_r($result )
			if (!empty($result)) {
				$response['status'] = 200;
					$response['status_message'] = ' Review found for Ownere';
					$response['result'] = $result;
					$json_response = json_encode($response);
					return $json_response;
			}else{
					$response['status'] = 200;
					$response['status_message'] = ' No review found for owners';
					$response['result'] = array();
					$json_response = json_encode($response);
					return $json_response;
			}

		}else{
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				return $json_response;
				

		}
	}

	public function  GetRenterReview($app_user_id ,$order_id)
	{
		
		$app_user_id = $app_user_id;
		if ($app_user_id != '') {


			$where = array(
							'a.create_user'=>$app_user_id ,'a.parent_gear_review_id' =>0 ,'a.order_id'=>$order_id
						);

			$query =  $this->model->RenterReviewList($where);

			$result = $query->result();
			$user_data =array();
			if (!empty($result)) {
				foreach ($result as  $value) {

					$this->db->select('a.* ,orders.project_name, users.app_user_first_name AS app_user_first_name_given_by, users.app_user_last_name AS app_user_last_name_given_by ,users.user_profile_picture_link As user_profile_picture_link_given_by,,users.bussiness_name ,users.show_business_name ');
					$this->db->from('ks_cust_gear_reviews AS a');
					$this->db->join('ks_users as users' , 'a.create_user = users.app_user_id' ,'INNER');
					$this->db->join('ks_user_gear_rent_details as orders' , 'orders.order_id = a.order_id' ,'INNER');
					// $this->db->join('ks_user_gear_description as gears' , 'gears.app_user_id = a.app_user_id AND orders.user_gear_desc_id = gears.user_gear_desc_id' ,'INNER');
					$this->db->where('parent_gear_review_id' ,$value->ks_cust_gear_review_id );
					$this->db->group_by('a.order_id');
					$query = $this->db->get();
					$data = $query->row();
					if (!empty($data)) {
						// $data->project_name = '';
					 //    $data->app_user_id_given_by = '';
					 //    $data->app_user_first_name_given_by = '';
					 //    $data->user_profile_picture_link_given_by = '';
					 //    $data->app_user_last_name_given_by = '';
					 //    $data->show_business_name = '';
					 //    $data->bussiness_name = '';
						$user_data[] = $data ;
					}
				}
			}
			if (!empty($user_data)) {
				$result =  array_merge($result , $user_data);
			}
			if (!empty($result)) {
				$i =0 ;
				foreach ($result as $value) {
					if ($result[$i]->user_profile_picture_link_given_by == '') {
						$result[$i]->user_profile_picture_link_given_by =  BASE_URL."server/assets/images/profile.png";
					}
					if($result[$i]->show_business_name == 'Y'){
					    $result[$i]->app_user_first_name_given_by   =   $result[$i]->bussiness_name ;
					    $result[$i]->app_user_last_name_given_by   =  '' ;

					}

					 $i++;
				}
			# code...
			}
			if (!empty($result)) {
				$response['status'] = 200;
					$response['status_message'] = ' Review found for Ownere';
					$response['result'] = $result;
					$json_response = json_encode($response);
					return $json_response;
			}else{
					$response['status'] = 200;
					$response['status_message'] = ' No review found for owners';
					$response['result'] = array();
					$json_response = json_encode($response);
					return $json_response;
			}

		}else{
				$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				return $json_response;
				

		}
	}

  
  public function select_delete()
  {
  if(isset($_POST['bulk_delete_submit']))
  {
    $idArr = $this->input->post('checked_id');
    foreach($idArr as $id){
          
      $where_array = array('ks_cust_gear_review_id'=>$id);
      $lang= $this->common_model->get_all_record('ks_cust_gear_reviews',$where_array);
      }
      
        $this->common_model->delele('ks_cust_gear_reviews','ks_cust_gear_review_id',$id);
      
      }
    
    redirect('cust_gear_reviews');
   }
  
  public function delete_record()
  {
    $id=$this->uri->segment(3);
    $where_array = array('ks_cust_gear_review_id'=>$id);
    $manufacturer= $this->common_model->get_all_record('ks_cust_gear_reviews',$where_array);

    $this->common_model->delele('ks_cust_gear_reviews','ks_cust_gear_review_id',$id);
    
    redirect('cust_gear_reviews');
  }
  
  public function ajax()
  {
    if($this->input->post('data')!=NULL){
      $data['x']=$this->input->post('data');
    
      $sql="SELECT * FROM ks_cust_gear_reviews WHERE is_active='Y' AND parent_gear_review_id=".$this->input->post('data')." ORDER BY cust_gear_review_date DESC";
      $rev=$this->db->query($sql);
      $data['rev']=$rev->result();
      
      $this->load->view("cust_gear_reviews/ajax",$data);
    }
    else{
      exit();
    }
  }
  
}?>