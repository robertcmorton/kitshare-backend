<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pre_fill extends CI_Controller {

	 public function __construct() {
	 	header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->model(array('common_model','home_model'));
	}
	
	
	//Function to pull the Rental Types
	public function rentaltype(){
	
		$table = "ks_renter_type";
		$where_clause = "is_active='Y'";
		$query = $this->common_model->RetriveRecordByWhere($table,$where_clause);
		
		$result_arr = array();
		if($query->num_rows()>0){
			
			$i=0;
			foreach($query->result_array() as $row){
			
				$result_arr[$i]['ks_renter_type_id']=$row['ks_renter_type_id'];
				$result_arr[$i]['ks_renter_type']=$row['ks_renter_type'];				
				$i++;
			}
		
		}
		
		$response=array("status"=>200,
						"status_message"=>"success",
						"result"=>$result_arr);
		echo json_encode($response);
		exit();
		
	}
	
	//Function to retrieve the Profession Types
	public function profession_types(){
	
		$table = "ks_profession_types";
		$where_clause = "is_active='Y' AND is_approved='Y'";
		$query = $this->common_model->RetriveRecordByWhere($table,$where_clause);
		
		$result_arr = array();
		
		if($query->num_rows()>0){
			
			$i=0;
			foreach($query->result_array() as $row){
			
				$result_arr[$i]['profession_type_id']=$row['profession_type_id'];
				$result_arr[$i]['profession_name']=$row['profession_name'];				
				$i++;
			}
		
		}
		
		$response=array("status"=>200,
						"status_message"=>"success",
						"result"=>$result_arr);
		echo json_encode($response);
		exit();
	
	}
	public function StateList()
	{
		
		$table = "ks_states";
		$where_clause = "is_active='Y' ";
		$query = $this->common_model->RetriveRecordByWhere($table,$where_clause);
		$result_arr = array();
		
		if($query->num_rows()>0){
			
			$i=0;
			foreach($query->result_array() as $row){
				$result_arr[$i]['state_id']=$row['ks_state_id'];
				$result_arr[$i]['ks_state_code']=$row['ks_state_code'];

				$result_arr[$i]['ks_state_name']=$row['ks_state_name'];
				

				$i++;
			}
		
		}
		
		$response=array("status"=>200,
						"status_message"=>"success",
						"result"=>$result_arr);
		echo json_encode($response);
		exit();
	}
	public function SuburbsList()
	{
		$id =$this->input->get('state_id');
		if (trim($id) == '') {
			$response=array("status"=>204,
						"status_message"=>"state id  is required",
					);
		echo json_encode($response);
		exit();
		}else{
		$table = "ks_suburbs";
		$where_clause = "is_active='Y' AND ks_state_id ='".$id."' ";
		$query = $this->common_model->RetriveRecordByWhere($table,$where_clause);
		$result_arr = array();
		
		if($query->num_rows()>0){
			
			$i=0;
			foreach($query->result_array() as $row){
				$result_arr[$i]['ks_suburb_id']=$row['ks_suburb_id'];
				$result_arr[$i]['ks_state_id']=$row['ks_state_id'];

				$result_arr[$i]['suburb_name']=$row['suburb_name'];
				$result_arr[$i]['suburb_postcode']=$row['suburb_postcode'];
				

				$i++;
			}
		
		}
		
		$response=array("status"=>200,
						"status_message"=>"success",
						"result"=>$result_arr);
		echo json_encode($response);
		exit();
		}
	}


}?>
