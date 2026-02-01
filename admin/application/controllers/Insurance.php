<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Insurance extends CI_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text','common_helper'));
		$this->load->library(array('session','form_validation','pagination','email','upload','excel'));
		$this->load->model(array('common_model','mail_model','model'));
		if($this->session->userdata('ADMIN_ID') =='') {
          redirect('login');
		  }
	}
	
	protected $validation_rules = array
        (
		'Add' => array(
            array(
                'field' => 'gear_name',
                'label' => 'Gear Name',
                'rules' => 'trim'
            ),
			array(
                'field' => 'model_name',
                'label' => 'Model',
                'rules' => 'trim'
            ),
			
        ),
    );

	public function index()
	{

	    $data=array();
		
		$where ='';
		
		$limit=10;
		$data['limit'] = $limit ;
		$order_by ='';
	
					
	    	
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
		$nSer="SELECT * FROM ks_insurance_category_type ";
		$sql=$nSer." LIMIT ".$limit." OFFSET  ".$offset." ";
		$result=$this->db->query($sql);		
		$total_rows=count($this->db->query($nSer)->result());	
		$data['result'] = $result;
		
		$config['base_url'] = base_url()."gear_desc/?&order_by=".$order_by."&limit=".$data['limit'];
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
		 $query = $this->common_model->GetAllWhere('ks_gear_type',''); 
		 $data['type'] = $query->result();
		 $sql1 = "SELECT DISTINCT (ks_users.app_user_id),ks_users.app_user_first_name,ks_users.app_user_last_name  FROM ks_users INNER JOIN   ks_user_gear_description ON ks_user_gear_description.app_user_id = ks_users.app_user_id " ;
		$query = $this->db->query($sql1);
		$data['app_users'] = $query->result();
		 $query = $this->common_model->GetAllWhere('ks_gear_categories',array('gear_sub_category_id'=> '0')); 
		 $data['category'] = $query->result();
		//echo $this->db->last_query(); exit();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('Insurance/list', $data);
		$this->load->view('common/footer');		

	

	}
	
	
	
	
	
	public function edit()
	{
		$data=array();
		$id = $this->uri->segment(3);
		$where_array = array('ks_insurance_category_type_id'=>$id);
		$device= $this->common_model->GetAllWhere('ks_insurance_category_type', $where_array);
		$data['result'] = $device->row();
		$where_array['is_deleted'] = '0';
		$where_array['status'] = '0';
		$query= $this->common_model->GetAllWhere('ks_insurance_tiers', $where_array);
		$data['tiers'] = $query->result();
		if ($id == 1) {
		$query= $this->common_model->GetAllWhere('ks_settings','');
		$data['settings'] = $query->row();
		}else{
				$data['settings'] = array();
		}
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('Insurance/edit', $data);
		$this->load->view('common/footer');		
	}
	
	public function view()
	{
		$data=array();
		
		$id = $this->uri->segment(3);
		$where_array = array('ks_insurance_category_type_id'=>$id);
		$device= $this->common_model->GetAllWhere('ks_insurance_category_type', $where_array);
		$data['result'] = $device->row();
		$this->load->view('common/header');	
		$this->load->view('common/left-menu');	
		$this->load->view('Insurance/view', $data);
		$this->load->view('common/footer');		
	}
	
	
	public function updateInsurance()
	{
		$data =  $this->input->post();
		
		if (!empty($data['initial_value'][0])) {
			$count =  count($data['initial_value']);
			for($i = 0 ;$i < $count; $i++){
				if($data['initial_value'][$i] != ''){

					
					if (!empty($data['tiers_id'][$i])) {

							$where_clause1 = array(
												'initial_value' =>  $data['initial_value'][$i] ,
												'end_value' =>  $data['end_value'][$i] ,
												'tiers_percentage' =>  $data['tiers_percentage'][$i] ,
												'tiers_id'=>$data['tiers_id'][$i]
											);
							$query =  $this->common_model->GetAllWhere('ks_insurance_tiers',$where_clause1 );
							$data_response  = $query->result();
							 
							if (empty($data_response)) {
								$update_data=  array(
									
									'status_inactive_date'=>date('Y-m-d'),
									'status'=>'1',
									'modified_date' => date('Y-m-d'),
									'created_by'=> $this->session->userdata('ADMIN_ID')
								);
								 $this->db->where('tiers_id',$data['tiers_id'][$i]);
								 $this->db->update('ks_insurance_tiers',$update_data);
								 $query =$this->common_model->GetAllWhere('ks_insurance_tiers','');
							$last_data =  $query->result();
							if (!empty($last_data )) {
								 $last_id = count($last_data) + 1;
							}else{
								$last_id =  1;
							}
							 	$insert_data=  array(
									'ks_insurance_category_type_id' => $data['ks_insurance_category_type_id'],
									'tier_name'=>'Tier-'.$last_id,
									'initial_value' =>  $data['initial_value'][$i] ,
									'end_value' =>  $data['end_value'][$i] ,
									'tiers_percentage' =>  $data['tiers_percentage'][$i] ,
									'status' => '0',
									'difference_amount'=>$data['end_value'][$i] - $data['initial_value'][$i] ,
									'created_by'=> $this->session->userdata('ADMIN_ID'),
									'created_date'=>date('Y-m-d')
								);
							$insert_id = $this->common_model->addRecord('ks_insurance_tiers',$insert_data);

							}
							
					}else{
					
							$query =$this->common_model->GetAllWhere('ks_insurance_tiers','');
							$last_data =  $query->result();
							if (!empty($last_data )) {
								 $last_id = count($last_data) + 1;
							}else{
								$last_id =  1;
							}
							$insert_data=  array(
									'ks_insurance_category_type_id' => $data['ks_insurance_category_type_id'],
									'tier_name'=>'Tier-'.$last_id,
									'initial_value' =>  $data['initial_value'][$i] ,
									'end_value' =>  $data['end_value'][$i] ,
									'tiers_percentage' =>  $data['tiers_percentage'][$i] ,
									'status' => '0',
									'difference_amount'=>$data['end_value'][$i] - $data['initial_value'][$i] ,
									'created_by'=> $this->session->userdata('ADMIN_ID'),
									'created_date'=>date('Y-m-d')
								);
							$insert_id = $this->common_model->addRecord('ks_insurance_tiers',$insert_data);
					}
				}
				
			}
		}
		
		$data1['updated_date'] = date('Y-m-d');
		$data1['updated_time'] = date('h-i-s');
		$data1['name'] = $data['name'];
		$data1['percent'] = $data['percent'];
		$data1['description'] = $data['description'];
		$data1['status'] = $data['status'];
		
		$this->common_model->UpdateRecord_2( $data1,'ks_insurance_category_type' ,'ks_insurance_category_type_id',$data['ks_insurance_category_type_id'] );
		if ($data['ks_insurance_category_type_id'] == '1') {
				
		$this->db->update('ks_settings', array('max_replacement_value'=>$this->input->post('max_replacement_value')));

		}
		$message = '<div class="alert alert-success"><p>Insurance Type have been updated successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('Insurance');
	}
	
	public function select_delete()
	{
		if(isset($_POST['bulk_delete_submit']))
		{
			$idArr = $this->input->post('checked_id');
			foreach($idArr as $id)
			{
	        
			$where_array = array('ks_insurance_category_type_id'=>$id);
			$image= $this->common_model->get_all_record('ks_insurance_category_type',$where_array);
     		$this->common_model->delele('ks_insurance_category_type','ks_insurance_category_type_id',$id);
    	}
		$message = '<div class="alert alert-success"><p>Insurance Type have been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('Insurance');
		}
	}
	
	
	public function delete_record()
	{
		$id=$this->uri->segment(3);
		$where_array = array('ks_insurance_category_type_id'=>$id);
		$image= $this->common_model->get_all_record('ks_insurance_category_type',$where_array);
		$this->common_model->delele('ks_insurance_category_type','ks_insurance_category_type_id',$id);
		$message = '<div class="alert alert-success"><p>Insurance Type has been deleted successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('Insurance');
	}

	public function DeleteTiers($value='')
	{
		$data =  $this->input->post();
		// print_r($data);die;
		$update_data = array(
								'is_deleted' => '1',
								'modified_date' => date('Y-m-d'),
								'created_by'=> $this->session->userdata('ADMIN_ID')
							);
		$this->db->where('tiers_id', $data['tiers_id']);
		$this->db->update('ks_insurance_tiers', $update_data);
	}
	
	public function InsuranceSummmary()
	{	
		error_reporting(0);
		$where = '';
			if(!empty($this->input->get())){
				$start_date =$this->input->get('start_date')  ;
				$end_date =  $this->input->get('end_date');
				if(!empty($start_date)){
					$start_date  =explode('/',$start_date);
					 $start_date = $start_date['2'].'-' .$start_date['0'].'-'.$start_date['1'] ;
					$where .= "   rent_details.gear_rent_end_date >= '".$start_date."'  "; 						
				}
				if(!empty($end_date)){

					$end_date  =explode('/',$end_date);
					 $end_date = $end_date['2'].'-' .$end_date['0'].'-'.$end_date['1'] ;
					if(!empty($where)){
						$where .= " AND  rent_details.gear_rent_end_date <= '".$end_date."'  ";

					}else{
					$where .= "   rent_details.gear_rent_end_date <= '".$end_date."'  ";

					}
				}
				if(!empty($end_date) && !empty($start_date) ){

				 $calender_date =  $start_date.' -  '.$end_date;
				}else{
					$calender_date = date('d-m-Y');
				}	
				
			}else{
				
				$calender_date = date('d-m-Y');
			}
			
			$n=$this->model->InsuranceListSummary($where);
			$result = $n->result();
			if(!empty($result)){
				$i= 0;
				foreach ($result as  $value) {
						// echo $value->order_id;
						// echo "<br>";
						// echo "hello";
						// echo "<br>";
						if($value->order_status =='1' || $value->order_status ==' ' ){
							$status = 'Quote' ;
						}elseif($value->order_status == '2'){
							$status = 'Reservation' ;
						}elseif($value->order_status == '3'){
							$status = 'Contract' ;
						}elseif($value->order_status == '4'){
							$status = 'Completed' ;
						}elseif($value->order_status == '5'){
							$status = 'Cancelled' ;
						}elseif($value->order_status == '6'){
							$status = 'Rejected' ;
						}elseif($value->order_status == '7'){
							$status = 'Archived' ;
						}elseif($value->order_status == '8'){
							$status = 'Expired' ;
						}
						if($value->paymnet_status == ''){
							$paymnet_status = 'STORE';
						}elseif($value->paymnet_status == 'RECEIVED'){
							$paymnet_status = 'SETTLED';
						}else{
							$paymnet_status = $value->paymnet_status;
						}
						if ($value->ks_insurance_category_type_id == '5' ) {
							$value->name = 'Security Deposit';
						}	

						$date_from = strtotime(date('d-m-Y',strtotime($value->gear_rent_start_date)));
						$date_to = strtotime(date('d-m-Y',strtotime($value->gear_rent_end_date)));

						$diff = abs($date_to - $date_from);
						$date_array =  $this->getDateList($date_from,$date_to);
						array_pop($date_array); 
						$insurance_days = count($date_array);
					 	if ($insurance_days == 0) {
					 		$insurance_days = 1;
					 	}
					 	array_shift($date_array);
						$shoot_days = count($date_array);
						if(empty($shoot_days)){
							$shoot_days = 1;
						}
						if ($value->renter_rent_complete_date != '0000-00-00 00:00:00') {
							$renter_rent_complete_date = date('d-m-Y h:i:m',strtotime($value->renter_rent_complete_date)) ;
						}else{
							$renter_rent_complete_date = '';
						}
						$sub_total_ex =  ($value->total_rent_amount_ex_gst + $value->insurance_fee + $value->community_fee + $value->owner_insurance_amount -$value->beta_discount) ;					
						$gst_amount =  ($value->total_rent_amount_ex_gst + $value->insurance_fee + $value->community_fee + $value->owner_insurance_amount -$value->beta_discount)*10/100 ;					
						$end_date =  date('d-m-Y',strtotime($value->gear_rent_end_date)) ;
						$start_date =  date('d-m-Y',strtotime($value->gear_rent_start_date)) ;
						// echo "<pre>";
						// print_r($value);
						if ($value->security_deposit == '0.00') {
							$value->deposite_status = '';
						}else{
							$value->deposite_status =  $value->deposite_status;
						}

						$gear_data[] =  array(
											'Serial_no'=> $value->serial_number,	
				 							'order_id' => $value->order_id,
				 							'gear_name'=> $value->gear_name,
				 							'project_name'=> $value->project_name ,
				 							'owner_name'=>$value->app_user_first_name .' ' .$value->app_user_last_name,
				 							'buyer_name'=>$value->buyer_first_name.' '.$value->buyer_last_name,
				 							'start_date' => $start_date.' 14:00:00',
				 							'end_date' => $end_date .' 12:00:00',
				 							'requested_date'=> $value->gear_rent_requested_on,
				 							'renter_rent_complete_date' =>	$renter_rent_complete_date,
				 							'gear_total_rent_request_days'=> $value->gear_total_rent_request_days,
				 							'daily_rate' => $value->per_day_cost_aud_ex_gst,
											'total_shoot_days'=>$shoot_days,
				 							'insurance_days'=>$insurance_days,					 								
				 							'sub_total'=> number_format((float)$value->total_rent_amount_ex_gst, 2, '.', ''),
				 							'beta_discount'=>	number_format((float)$value->beta_discount, 2, '.', ''),
				 							'insurance_fee'=>	number_format((float)$value->insurance_fee, 2, '.', ''),
				 							'community_fee'=>	number_format((float)$value->community_fee, 2, '.', ''),
				 							'owner_insurance_amount'=> number_format((float)$value->owner_insurance_amount, 2, '.', ''),
				 							'sub_total_ex'=> number_format((float)($value->total_rent_amount_ex_gst + $value->insurance_fee + $value->community_fee + $value->owner_insurance_amount -$value->beta_discount), 2, '.', ''),
				 							'gst_amount'=>	number_format((float)($value->total_rent_amount_ex_gst + $value->insurance_fee + $value->community_fee + $value->owner_insurance_amount -$value->beta_discount)*10/100, 2, '.', ''),
				 							'total_rent_amount'=> number_format((float)($gst_amount+$sub_total_ex), 2, '.', ''),
				 							'deposit_amount' =>	number_format((float)$value->security_deposit, 2, '.', ''),
				 							'replacement_amount' =>	number_format((float)$value->replacement_value_aud_ex_gst, 2, '.', ''),
				 							'deposite_status'=> $value->deposite_status,
				 							'order_status'=>$status ,
				 							'payment_status'=>$paymnet_status,
				 							'Insurance_type'=>$value->name ,
				 							// 'tier_type'=>$value->tier_name ,
				 							'tiers_percentage'=>$value->tiers_percentage,
				 							'address'=>$value->street_address_line1 .' ' .$value->street_address_line2 .' ' .$value->route.' '.$value->suburb_name.' '.  $value->ks_state_name.' '.$value->ks_country_name,
				 							
				 							
				 					);	
						
				 					$i++;			
				} 
			// 	echo "<pre>";
			// print_r($gear_data);
			// die;

					 $this->excel->setActiveSheetIndex(0);
				//name the worksheet
				$this->excel->getActiveSheet()->setTitle('Insurance Summmary List');
				$this->excel->getActiveSheet()->setCellValue('A1', 'Main Item Serial Number');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Order Id');
				$this->excel->getActiveSheet()->setCellValue('C1', 'Listing Name');
				$this->excel->getActiveSheet()->setCellValue('D1', 'Project Name');
				$this->excel->getActiveSheet()->setCellValue('E1', ' Owner Name ');
				$this->excel->getActiveSheet()->setCellValue('F1', 'Renter Name ');
				$this->excel->getActiveSheet()->setCellValue('G1', 'Rent Start Date');
				$this->excel->getActiveSheet()->setCellValue('H1', 'Rent End Date');
				$this->excel->getActiveSheet()->setCellValue('I1', 'Rent Requested Date');
				$this->excel->getActiveSheet()->setCellValue('J1', 'Owner Rent End Date');
				$this->excel->getActiveSheet()->setCellValue('K1', 'Rented Days');
				$this->excel->getActiveSheet()->setCellValue('L1', 'Daily Rate ');
				$this->excel->getActiveSheet()->setCellValue('M1', 'Total Shoot Days ');
				$this->excel->getActiveSheet()->setCellValue('N1', 'Total Insurance Days ');
				$this->excel->getActiveSheet()->setCellValue('O1', 'SubTotal');
				$this->excel->getActiveSheet()->setCellValue('P1', 'Discount');
				$this->excel->getActiveSheet()->setCellValue('Q1', 'Insurance Fee');
				$this->excel->getActiveSheet()->setCellValue('R1', 'Community Fee');
				$this->excel->getActiveSheet()->setCellValue('S1', 'Owner Insurance Amount');
				$this->excel->getActiveSheet()->setCellValue('T1', ' Total Amount Ex GST');
				$this->excel->getActiveSheet()->setCellValue('U1', 'GST Fee');
				$this->excel->getActiveSheet()->setCellValue('V1', ' Total Amount');
				$this->excel->getActiveSheet()->setCellValue('W1', 'Deposit Amount');
				$this->excel->getActiveSheet()->setCellValue('X1', 'Replacement Value ex GST');
				$this->excel->getActiveSheet()->setCellValue('Y1', 'Deposit Status');
				$this->excel->getActiveSheet()->setCellValue('Z1', 'Order Status');
				$this->excel->getActiveSheet()->setCellValue('AA1', 'Payment Status');
				$this->excel->getActiveSheet()->setCellValue('AB1', 'Insurance Type');
				// $this->excel->getActiveSheet()->setCellValue('AA1', 'Tier Type');
				$this->excel->getActiveSheet()->setCellValue('AC1', 'Tier percentage(%)');
				$this->excel->getActiveSheet()->setCellValue('AD1', 'Address');
				
				for($col = ord('A'); $col <= ord('AD1'); $col++){ //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
					$this->excel->getActiveSheet()->getStyle(chr($col)."1")->getFont()->setBold(true);
					$this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
				}


				$this->excel->getActiveSheet()->fromArray($gear_data, null, 'A4');
				$filename='InsuranceSummary-'.$calender_date.'.xls'; //save our workbook as this file name
				header('Content-Type: application/vnd.ms-excel'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0');
				//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
				//if you want to save it as .XLSX Excel 2007 format
				$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
				//force user to download the Excel file without writing it to server's HD
				$objWriter->save('php://output');
			}else{

				$this->excel->setActiveSheetIndex(0);
				//name the worksheet
				$this->excel->getActiveSheet()->setTitle('Insurance Summmary List');
				$this->excel->getActiveSheet()->setCellValue('A1', 'Main Item Serial Number');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Order Id');
				$this->excel->getActiveSheet()->setCellValue('C1', 'Listing Name');
				$this->excel->getActiveSheet()->setCellValue('D1', 'Project Name');
				$this->excel->getActiveSheet()->setCellValue('E1', ' Owner Name ');
				$this->excel->getActiveSheet()->setCellValue('F1', 'Renter Name ');
				$this->excel->getActiveSheet()->setCellValue('G1', 'Rent Start Date');
				$this->excel->getActiveSheet()->setCellValue('H1', 'Rent End Date');
				$this->excel->getActiveSheet()->setCellValue('I1', 'Rent Requested Date');
				$this->excel->getActiveSheet()->setCellValue('J1', 'Owner Rent End Date');
				$this->excel->getActiveSheet()->setCellValue('K1', 'Rented Days');
				$this->excel->getActiveSheet()->setCellValue('L1', 'Total Shoot Days ');
				$this->excel->getActiveSheet()->setCellValue('M1', 'Total Insurance Days ');
				$this->excel->getActiveSheet()->setCellValue('N1', 'SubTotal');
				$this->excel->getActiveSheet()->setCellValue('O1', 'Discount');
				$this->excel->getActiveSheet()->setCellValue('P1', 'Insurance Fee');
				$this->excel->getActiveSheet()->setCellValue('Q1', 'Community Fee');
				$this->excel->getActiveSheet()->setCellValue('R1', ' Total Amount Ex GST');
				$this->excel->getActiveSheet()->setCellValue('S1', 'GST Fee');
				$this->excel->getActiveSheet()->setCellValue('T1', ' Total Amount');
				$this->excel->getActiveSheet()->setCellValue('U1', 'Deposit Amount');
				$this->excel->getActiveSheet()->setCellValue('V1', 'Replacement Value ex GST');
				$this->excel->getActiveSheet()->setCellValue('W1', 'Deposit Status');
				$this->excel->getActiveSheet()->setCellValue('X1', 'Order Status');
				$this->excel->getActiveSheet()->setCellValue('Y1', 'Payment Status');
				$this->excel->getActiveSheet()->setCellValue('Z1', 'Insurance Type');
				$this->excel->getActiveSheet()->setCellValue('AA1', 'Tier Type');
				$this->excel->getActiveSheet()->setCellValue('AB1', 'Tier percentage(%)');
				for($col = ord('A'); $col <= ord('Y'); $col++){ //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
					$this->excel->getActiveSheet()->getStyle(chr($col)."1")->getFont()->setBold(true);
					$this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
				}

				$gear_data = array(); 
				$this->excel->getActiveSheet()->fromArray($gear_data, null, 'A4');
				$filename='InsuranceSummary-'.$calender_date.'.xls'; //save our workbook as this file name
				header('Content-Type: application/vnd.ms-excel'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0');
				//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
				//if you want to save it as .XLSX Excel 2007 format
				$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
				//force user to download the Excel file without writing it to server's HD
				$objWriter->save('php://output');
				$message = '<div class="alert alert-danger"><p>No  Listing Ordered During the period .</p></div>';
				$this->session->set_flashdata('success', $message);
				redirect('Insurance');
			}
		
			// echo "<pre>";
			// print_r($gear_data);
	}

	public function GetRentDays($date_from,$date_to)
	{
		
		$total_days = '' ;
        $count = '';
        $count1 = '';
		$diff = abs($date_to -$date_from);
        $days = floor(($diff)/ (60*60*24));
        if ($days > 1) {
           $days =   $days +1-2 ; 
        }else{
             $days =   1;
        }
        $tola_days_reamining =   $days%7 ;  
        $tola_week =  floor( $days/7) ;
        if  ($tola_week > 0 ){
             $total_days = $tola_week*3 ;
        }
          if ($tola_days_reamining  > 1 && $tola_days_reamining <= 6  ) {
            for ($i=1; $i <= $tola_days_reamining ; $i++) { 
                $days1[] =   date('D', strtotime('+'.$i.' day', $date_from));
            }
            $count = 0;
            $count1 = 0;
            foreach ($days1 as $value) {
                if ($value == 'Sat'  || $value == 'Sun'  ) {
                    $count1 += 1; 
                }else{
                    $count += 1;
                    if($count >= 3)
                        break;
                }
            }
            $next_day =  date('D', strtotime('+1 day', strtotime($date_from)));
             if ( $count1 >=1 && $count < 3) {
                 $count = $count + 1 ;
             }
             return $count+ $total_days  ;    

        }elseif($tola_days_reamining == '1'  && $tola_week ==  0 || $tola_days_reamining == '0'  &&  $tola_week ==  0 ){
            $count = 1 ;
        }elseif($tola_days_reamining == '1' &&  $tola_week  > 0     ){
            $count = 1 ;
        }
        return  $count+ $total_days  ;       
	}
	public function getDateList($date_from,$date_to)
	{
		
			$dates = array();
			while($date_from <= $date_to)
			{
				$values= date( 'Y-m-d',$date_from) ;
						
					

			    array_push( $dates,$values);
			    $date_from += 86400;
			}
			return $dates ;
	}
}?>
