<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Payments extends CI_Controller {

	 public function __construct() {

		parent::__construct();

		$this->load->helper(array('url','form','html','text','common_helper'));

		$this->load->library(array('session','form_validation','pagination','email','upload','image_lib','excel'));

		$this->load->model(array('common_model','mail_model','model'));

		if($this->session->userdata('ADMIN_ID') =='') {

          redirect('login');

		  }

	}
		protected $validation_rules = array
        (

		'AddPayment' => array(
			array(

                'field' => 'order_id',

                'label' => 'Order Id',

                'rules' => 'trim|required'
            ),
			array(

                'field' => 'owner_payment_amount',

                'label' => 'Payment Name',

                'rules' => 'trim|required'
            ),
			array(
                'field' => 'owner_payment_dates',
                'label' => 'Payment Dates',
                'rules' => 'trim|required'
            )
        ),

    );



	public function index()

	{

	    $data=array();

		$where ='';

		if($this->input->get('limit') != ''){

				$data['limit']	= $this->input->get('limit');

		}

		else{

			$data['limit']	= 100;

		}
		$where = substr($where,0,(strlen($where)-4));
		$data['order_by']				= $this->input->get('order_by');
		if($data['order_by'] != ''){
				$order_by = $data['order_by'];
		}
		else{
			$order_by = 'DESC';
		}
		if($this->input->get("per_page")!= '')
		{
			$offset = $this->input->get("per_page");
		}
		else
		{
			$offset=0;
		}

		

		if($this->input->get('field')!="")

			$data['field_name'] = $this->input->get('field');

		else

			$data['field_name'] = "app_user_id";

		$n=$this->model->OrderPayments($where);

		$sql = $this->model->OrderPayments($where,$data['limit'],$offset,$order_by,$data['field_name']);
	
		$data['offset'] = $offset;
		$result=$sql->result();
		if (!empty($result)) {
			$i = 0 ;
			foreach ($result as $value) {
				$this->db->select('SUM(total_rent_amount) AS  total_rent_amount,SUM(total_rent_amount_ex_gst) AS  total_rent_amount_ex_gst , SUM(security_deposit)  AS security_deposit');
				$this->db->where('order_id',$value->order_id);
				$this->db->from('ks_user_gear_rent_details');
				$query = $this->db->get();
				$order_itme=  $query->row();
				$result[$i]->total_rent_amount = $order_itme->total_rent_amount;
				$result[$i]->total_rent_amount_ex_gst = $order_itme->total_rent_amount_ex_gst;
				$result[$i]->security_deposit = $order_itme->security_deposit;
				$i++;
			}
		}
		$total_rows=$n->num_rows();	

		$data['result'] = $result;

		$data['total_rows']=$total_rows;

		$data['limit']=$data['limit'];
	
		$config['base_url'] = base_url()."payments?order_by=".$order_by."&limit=".$data['limit'];

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



//////////////////////////////Pagination config//////////////////////////////////				

		//echo $this->db->last_query(); exit();



		$this->load->view('common/header');	

		$this->load->view('common/left-menu');	

		$this->load->view('payment/list', $data);

		$this->load->view('common/footer');		



	}

	public function Invocie($order_id='')
	{
		$sql = "SELECT o_d.* ,g_tbl.gear_name FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$order_id."'  "	;
		$order_details   = $this->common_model->get_records_from_sql($sql);
		foreach ($order_details as  $value) {
		 $users[] = 	$value->create_user;
		}
		$this->db->select('users.*,u_add.*,state.ks_state_name,suburbs.suburb_name,countries.ks_country_name ');
		$this->db->from('ks_user_gear_rent_master g_r_m');
		$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.user_gear_rent_id = g_r_m.user_gear_rent_id','inner');
		$this->db->join('ks_user_address as u_add', 'g_r_m.user_address_id = u_add.user_address_id','inner');
		$this->db->join('ks_states  As state', 'state.ks_state_id  = u_add.ks_state_id','inner');
		$this->db->join('ks_suburbs  As suburbs', 'suburbs.ks_suburb_id  = u_add.ks_suburb_id','inner');
		$this->db->join('ks_countries  As countries', 'countries.ks_country_id  = u_add.ks_country_id','inner');
		$this->db->join('ks_users  As users', 'users.app_user_id  = u_add.app_user_id','inner');
		$this->db->where('u_add.app_user_id' , $users[0]);
		$query = $this->db->get();
		$data['addrsss'] =  $query->row();
		// print_r($data['addrsss']);
		// print_r(array_unique($users));die;
		$url = '';
		$data['six_digit_random_number'] = $order_id.mt_rand(1000, 9999); 
		$this->uri->segment('3');
		 	$data['order_details'] = $order_details;
		// print_r($order_details);
		$mail_body = $this->mail_model->OrderInvocies($url , $data);
		//print_r($mail_body);die;
		$this->load->helper('pdf');
		gen_pdf($mail_body,$this->uri->segment('3'),'admin');
	}

	

	public function TestMail($order_id)
	{
		$sql = "SELECT o_d.* ,g_tbl.gear_name FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl  ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id WHERE o_d.order_id = '".$order_id."'  "	;
		$order_details   = $this->common_model->get_records_from_sql($sql);
		foreach ($order_details as  $value) {
		 $users[] = 	$value->create_user;
		}
		//$this->db->select('*');
		$this->db->from('ks_user_gear_rent_master g_r_m');
		$this->db->join('ks_user_gear_rent_details as g_r_d', 'g_r_d.user_gear_rent_id = g_r_m.user_gear_rent_id','inner');
		$this->db->join('ks_user_address as u_add', 'g_r_m.user_address_id = u_add.user_address_id','inner');
		$this->db->join('ks_states  As state', 'state.ks_state_id  = u_add.ks_state_id','inner');
		$this->db->join('ks_suburbs  As suburbs', 'suburbs.ks_suburb_id  = u_add.ks_suburb_id','inner');
		$this->db->join('ks_countries  As countries', 'countries.ks_country_id  = u_add.ks_country_id','inner');
		$this->db->join('ks_users  As users', 'users.app_user_id  = u_add.app_user_id','inner');
		$this->db->where('u_add.app_user_id' , $users[0]);
		$query = $this->db->get();
		$data['addrsss'] =  $query->row();
		// echo "<pre>";
		//  print_r($data['addrsss']);die;
		// print_r(array_unique($users));die;
		$url = '';
		//$data['six_digit_random_number'] = $order_id.mt_rand(1000, 9999); 
		$data['six_digit_random_number'] = $order_id; 
		$this->uri->segment('3');
		 	$data['order_details'] = $order_details;
		$mail_body = $this->load->view('orders/email-template1',$data ,true);
		// print_r($mail_body);die;	
		$this->load->helper('pdf');
		gen_pdf($mail_body,$this->uri->segment('3'),'admin');
	}

	public function markpaid($value='')
	{
		$data =  $this->input->post();
		$this->form_validation->set_rules($this->validation_rules['AddPayment']);
		if($this->form_validation->run()){
		
			$insert_data = array(
									'paid_amount'=>$data['owner_payment_amount'],
									'paid_date'=> $data['owner_payment_dates'],
									'paid_remark'=>$data['remark'],
									'paid_by'=>$this->session->userdata('ADMIN_ID'),
									'status'=>'PAID',
								);	


			$this->db->where('gear_order_id', $data['order_id']);
			$this->db->update('ks_user_gear_payments',$insert_data);
			$this->SendOwnerPaymentMail($data['order_id'] ,$data['owner_payment_amount'] );
			$message = '<div class="alert alert-success"><p>Paid to owner  successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('Payments');
		}
	}

	public function SendOwnerPaymentMail($order_id, $amount)
	{
		
		$cart_details = $this->model->getUserCartbyOrderId($order_id);
		
		if ($cart_details[0]['owner_show_bussiness_name'] == 'y') {
			 $cart_details[0]['app_user_first_name']  = $cart_details[0]['owner_bussiness_name'];
			 $cart_details[0]['app_user_last_name']  = '';
		}
		if ($cart_details[0]['renter_show_bussiness_name'] == 'y') {
			 $cart_details[0]['renter_firstname']  = $cart_details[0]['renter_bussiness_name'];
			 $cart_details[0]['renter_lastname']  = '';
		}

		$sql ="SELECT sum(paid_amount) AS amount_paid from ks_user_gear_description As g_d INNER JOIN ks_user_gear_rent_details AS cart ON  cart.user_gear_desc_id  =g_d.user_gear_desc_id INNER JOIN ks_user_gear_payments As g_p ON  g_p.gear_order_id = cart.order_id   WHERE g_d.app_user_id = '".$cart_details[0]['app_user_id']."' AND g_p.paid_amount !='' Group by cart.order_id ";
		$order_details   = $this->common_model->get_records_from_sql($sql);
		
		if (!empty($order_details)) {
			$sum = 0 ;
			foreach ($order_details as  $value) {
				$sum += $value->amount_paid ;
			}
			$total_rent_amount = $sum;
		}else{
			$total_rent_amount = '0';
		}
		$msg= '<!doctype html>
				<html>
				<head>
				<meta charset="utf-8">
				<title>kitshare</title>
				</head>

				<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">

				<div class="wrapper" style="border:0px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">

				</div>


				<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
				<table width="940" style="    margin: 0px auto;
				    background-color:#095cab;
				    padding: 10px 10px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
				</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
				<tr>
				<td style="font-size:20px; padding-bottom:10px;">
				Hi '.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].' , </td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">Rental order   <b>'.$order_id.'</b> has been completed, and you have been paid.</td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">Amount $'.number_format($amount,2).'.</td>

				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">Lifetime Earnings:  $'.number_format($total_rent_amount,2).'.</td>

				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">Domestic payments should appear in your account in 1-3 days.</td>

				</tr>
				
				<td style="font-size:18px; padding-bottom:10px;"> Regards,
				<br>
				The Kitshare Team

				</td>

				</tr>

				</table>

				<table width="940" style="    margin: 0px auto;
				    background-color:#ddd;
				    padding: 5px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><p style="margin:0">&#169;2018 KitShare. All rights reserved</p></td>
				</tr>
				</table>
				</div>

				</body>
				</html>
				';

		 $mail_body = $msg; 
		$to= $cart_details[0]['primary_email_address'];
		// $to= 'singhaniagourav@gmail.com';
		$subject = "You have been paid!";		
		$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia",
											),
										"To"=>array(
												array("Email"=>$to,
												"Name"=>"",
												),
											),
										"Subject"=> $subject,
				                        "TextPart"=> "",
				                        "HTMLPart"=> $mail_body
									),
								)
							);	
		$this->common_model->sendMail($mail_data);
		
	}

	public function PaymentSummmary()
	{
			error_reporting(0);
			$where = '';
			if(!empty($this->input->get())){
				$start_date =$this->input->get('start_date')  ;
				$end_date =  $this->input->get('end_date');
				if(!empty($start_date)){

					$start_date  =explode('/',$start_date);
					$start_date = $start_date['2'].'-' .$start_date['0'].'-'.$start_date['1'] ;

					$end_date  =explode('/',$end_date);
					$end_date = $end_date['2'].'-' .$end_date['0'].'-'.$end_date['1'] ;

					 $where .= "   p.create_date >= '".$start_date."' AND p.create_date <= '".$end_date."'  "; 						
				}
				if(!empty($end_date) && !empty($start_date) ){

				 $calender_date =  $start_date.' -  '.$end_date;
				}else{
					$calender_date = date('d-m-Y');
				}

					$query = $this->model->PaymentSummary($where);	
					$result = 	$query->result();
					
					if(!empty($result)){
				$i= 0;
				foreach ($result as  $value) {
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
						}elseif ($value->order_status == '8') {
							$status = 'Expired';
						}
						if($value->paymnet_status == ''){
							$paymnet_status = 'STORE';
						}else{
							$paymnet_status = $value->paymnet_status;
						}
						$date_from = strtotime(date('d-m-Y',strtotime($value->gear_rent_start_date)));
						$date_to = strtotime(date('d-m-Y',strtotime($value->gear_rent_end_date)));

						$diff = abs($date_to - $date_from);
						$date_array =  $this->getDateList($date_from,$date_to);
						$insurance_days = count($date_array);
					 	array_pop($date_array); 
					 	array_shift($date_array);
						$shoot_days = count($date_array);
						if(empty($shoot_days)){
							$shoot_days = 1;
						}
						if($value->renter_rent_complete_date =='0000-00-00'){
							$renter_rent_complete_date = '';
						}else{
							$renter_rent_complete_date = $value->renter_rent_complete_date;
						}

						if ( $value->payment_type== 'Gear Payment') {
							$payment_type  = 'Rental Order';
						}else{
							$payment_type  = 'Deposit';
						}
						if($value->renter_rent_complete_date =='0000-00-00'){
							$renter_rent_complete_date = '';
						}else{
							$renter_rent_complete_date = $value->renter_rent_complete_date;
						}

						$end_date =  date('d-m-Y',strtotime($value->gear_rent_end_date)) ;
						$start_date =  date('d-m-Y',strtotime($value->gear_rent_start_date)) ;
						
						if ($value->owner_gst == 'N') {
							 $owner_amount =number_format((float) ($value->transaction_amount*85)/100, 2, '.', '')	;
						}else{
								$owner_amount =number_format((float) ($value->transaction_amount*85)/100, 2, '.', '')	+ number_format((float) ($value->gst_amount*85)/100, 2, '.', '');
						}

						$gear_data[] =  array(
											'Serial_no'=> $i+1,	
				 							'order_id' => $value->order_id,
				 							'transaction_id'=> $value->transaction_id,
				 							'project_name'=> $value->project_name ,
				 							'owner_name'=>$value->app_user_first_name .' ' .$value->app_user_last_name,
				 							'buyer_name'=>$value->buyer_first_name.' '.$value->buyer_last_name,
				 							'start_date' => $start_date.' 14:00:00',
				 							'end_date' => $end_date .' 12:00:00',
				 							'requested_date'=> $value->gear_rent_requested_on,
				 							'renter_rent_complete_date' =>$renter_rent_complete_date,
				 							'total_shoot_days'=>$shoot_days,
				 							'gear_total_rent_request_days'=> $value->gear_total_rent_request_days,
				 							'insurance_days'=>$insurance_days,					 								
				 							'sub_total'=> number_format((float)$value->total_rent_amount_ex_gst, 2, '.', ''),
				 							'beta_discount'=>	number_format((float)$value->beta_discount, 2, '.', ''),
				 							'insurance_fee'=>	number_format((float)$value->insurance_fee, 2, '.', ''),
				 							'owner_insurance_amount'=>	number_format((float)$value->owner_insurance_amount, 2, '.', ''),
				 							'community_fee'=>	number_format((float)$value->community_fee, 2, '.', ''),
				 							'sub_total_ex'=> number_format((float)$value->total_rent_amount_ex_gst, 2, '.', ''),
				 							'gst_amount'=>	number_format((float)$value->gst_amount, 2, '.', ''),
				 							'total_rent_amount'=> number_format((float)$value->total_rent_amount, 2, '.', ''),
				 							'deposit_amount' =>	number_format((float)$value->insurance_amount, 2, '.', ''),
				 							'refund_amount'=>number_format((float)$value->refund_amount, 2, '.', ''),
				 							'refund_date'=>$value->refund_date,	
				 							'transaction_amount'=>number_format((float)$value->transaction_amount, 2, '.', ''),
				 							'owner_amount'=>$owner_amount ,
				 							'registered_for_gst' =>$value->registered_for_gst,
				 							'paymnet_type' =>$payment_type,
				 							'order_status'=>$status ,
				 							'payment_status'=>$paymnet_status,
				 							'deposite_status'=> $value->deposite_status,
				 					);	
						
				 					$i++;			
				} 
			// 		echo "<pre>";
			// print_r($gear_data);die;
					 $this->excel->setActiveSheetIndex(0);
				//name the worksheet
				$this->excel->getActiveSheet()->setTitle('Payment Summmary List');
				$this->excel->getActiveSheet()->setCellValue('A1', 'Reference Number');
				$this->excel->getActiveSheet()->setCellValue('B1', 'Order Id');
				$this->excel->getActiveSheet()->setCellValue('C1', 'Transaction Id');
				$this->excel->getActiveSheet()->setCellValue('D1', 'Project Name');
				$this->excel->getActiveSheet()->setCellValue('E1', ' Owner Name ');
				$this->excel->getActiveSheet()->setCellValue('F1', 'Renter Name ');
				$this->excel->getActiveSheet()->setCellValue('G1', 'Rent Start Date');
				$this->excel->getActiveSheet()->setCellValue('H1', 'Rent End Date');
				$this->excel->getActiveSheet()->setCellValue('I1', 'Rent Requested Date');
				$this->excel->getActiveSheet()->setCellValue('J1', 'Owner Rent End Date');
				$this->excel->getActiveSheet()->setCellValue('K1', 'Total Shoot Days');
				$this->excel->getActiveSheet()->setCellValue('L1', 'Rented Days');
				$this->excel->getActiveSheet()->setCellValue('M1', 'Total Insurance Days ');
				$this->excel->getActiveSheet()->setCellValue('N1', 'SubTotal');
				$this->excel->getActiveSheet()->setCellValue('O1', 'Discount');
				$this->excel->getActiveSheet()->setCellValue('P1', 'Insurance Fee');
				$this->excel->getActiveSheet()->setCellValue('Q1', 'Owner Insurance Fee  ex GST');
				$this->excel->getActiveSheet()->setCellValue('R1', 'Community Fee');
				$this->excel->getActiveSheet()->setCellValue('S1', 'Total Ex GST');
				$this->excel->getActiveSheet()->setCellValue('T1', 'GST Fee');
				$this->excel->getActiveSheet()->setCellValue('U1', ' Total Amount');
				$this->excel->getActiveSheet()->setCellValue('V1', 'Deposit Amount');
				$this->excel->getActiveSheet()->setCellValue('W1', 'Refund Amoun');
				$this->excel->getActiveSheet()->setCellValue('X1', 'Refund Date');
				$this->excel->getActiveSheet()->setCellValue('Y1', 'Transaction Amount');
				$this->excel->getActiveSheet()->setCellValue('Z1', 'Owner Amount');
				$this->excel->getActiveSheet()->setCellValue('AA1', 'GST  Regsitered');
				$this->excel->getActiveSheet()->setCellValue('AB1', 'Transaction Type');
				$this->excel->getActiveSheet()->setCellValue('AC1', 'Order Status');
				$this->excel->getActiveSheet()->setCellValue('AD1', 'Payment Status');
				$this->excel->getActiveSheet()->setCellValue('AE1', 'Deposit Status');
				
				
				
				
				
				
				for($col = ord('A'); $col <= ord('AE1'); $col++){ //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
					$this->excel->getActiveSheet()->getStyle(chr($col)."1")->getFont()->setBold(true);
					$this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
				}


				$this->excel->getActiveSheet()->fromArray($gear_data, null, 'A4');
				$filename='PaymentSummary -'.$calender_date.'.xls'; //save our workbook as this file name
				header('Content-Type: application/vnd.ms-excel'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0');
				//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
				//if you want to save it as .XLSX Excel 2007 format
				$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
				//force user to download the Excel file without writing it to server's HD
				$objWriter->save('php://output');
			}else{

				$message = '<div class="alert alert-danger"><p>No  Listing Ordered During the period .</p></div>';
				$this->session->set_flashdata('success', $message);
				redirect('Payments');
			}
			}
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
	public function refundPayment($value='')
	{
		$data =  $this->input->post();
		$this->form_validation->set_rules($this->validation_rules['AddPayment']);
		if($this->form_validation->run()){
			$refund_data =  array(
				'order_id'=>$data['order_id'],
				'amount'=>$data['owner_payment_amount'],
				'user_id'=>$this->session->userdata('ADMIN_ID'),
				'reason'=>$data['remark'],
				'refund_date'=>$data['owner_payment_dates'],
				'refund_type'=>'Payment',
				'status'=>'Active',
				'created_date'=>date('Y-m-d'),
				'created_time'=>date('H:i:m'),
				'created_by'=> $this->session->userdata('ADMIN_ID')
			);
			$insert_id = $this->common_model->addRecord('ks_refund_order',$refund_data);
				$message = '<div class="alert alert-success"><p>Refund to Renter  successfully.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('Payments');
		}else{
			$message = '<div class="alert alert-danger"><p>Something went wrong.</p></div>';
			$this->session->set_flashdata('success', $message);
			redirect('Payments');

		}	
		
	}

	//Get Owner Bank Details
	public function GetOwnerBankDetails()
	{
		$order_id = $this->input->post('order_id');
		$sql = "SELECT g_tbl.gear_name,g_tbl.app_user_id As owner_id ,u_banks.*,ks_banks.bank_name,ks_banks.bank_logo,ks_banks.bank_head_office FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl   ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id INNER JOIN  ks_user_bank_details  AS u_banks  ON u_banks.app_user_id  =  g_tbl.app_user_id INNER JOIN ks_banks ON u_banks.bank_id = ks_banks.bank_id	 WHERE o_d.order_id = '".$order_id."' AND   u_banks.is_active= 'Y' "	;
		$order_details   = $this->common_model->get_records_from_sql($sql);
		// print_r($order_details);
		if (!empty($order_details)) {
			$result['status']= '1';
			$result['message']= 'Owner Bank Details found';
			$result['bankdetails']= $order_details[0];
		}else{
			$result['status']= '0';
			$result['message']= 'Owner Bank Details Not  found';
			$result['bankdetails']= array();
		}
		echo json_encode($result ,true);
	}
	//Get Owner Bank Details
	public function GetRenterBankDetails()
	{
		$order_id = $this->input->post('order_id');
		$sql = "SELECT g_tbl.gear_name,g_tbl.app_user_id As owner_id ,u_banks.*,ks_banks.bank_name,ks_banks.bank_logo,ks_banks.bank_head_office FROM ks_user_gear_rent_details As o_d INNER JOIN ks_user_gear_description As g_tbl   ON g_tbl.user_gear_desc_id  = o_d.user_gear_desc_id INNER JOIN  ks_user_bank_details  AS u_banks  ON u_banks.app_user_id  =  o_d.create_user INNER JOIN ks_banks ON u_banks.bank_id = ks_banks.bank_id	 WHERE o_d.order_id = '".$order_id."' AND u_banks.is_active = 'Y'  "	;
		$order_details   = $this->common_model->get_records_from_sql($sql);
		// print_r($order_details);
		if (!empty($order_details)) {
			$result['status']= '1';
			$result['message']= 'Owner Bank Details found';
			$result['bankdetails']= $order_details[0];
		}else{
			$result['status']= '0';
			$result['message']= 'Owner Bank Details Not  found';
			$result['bankdetails']= array();
		}
		echo json_encode($result ,true);
	}
	
}?>
