<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message extends CI_Controller {

	  public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email','upload','pagination'));
		$this->load->model(array('common_model','home_model','mail_model'));
		}
	
	
	public function index(){  
		echo "hello";
		
   		echo CI_VERSION;
 
      
	}

	public function SendMessageMail($order_id,$sender_id,$receiver_id,$message)
	{	
		$cart_details = $this->home_model->getUserCartbyOrderId($order_id);
		$this->SenderMessageMail($cart_details ,$sender_id,$receiver_id,$message);
		//$this->ReciverMessageMail($cart_details ,$receiver_id,$sender_id,$message);
	}

	public function SenderMessageMail($cart_details ,$sender_id,$receiver_id,$message)
	{

		$sql =  $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$receiver_id));
		$user_Details = $sql->row();
		$sql =  $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$sender_id));
		$sender_Details = $sql->row();
		$cart_details[0]['renter_image'] = $sender_Details->user_profile_picture_link;
		$cart_details[0]['primary_email_address'] = $user_Details->primary_email_address;
		$cart_details[0]['owner_show_business_name'] = $user_Details->show_business_name;
		$cart_details[0]['app_user_first_name'] = $user_Details->app_user_first_name;
		$cart_details[0]['app_user_last_name'] = $user_Details->app_user_last_name;
		$cart_details[0]['owner_bussiness_name'] = $user_Details->bussiness_name;
		$cart_details[0]['owner_bussiness_name'] = $user_Details->bussiness_name;
		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'] 	;
			$cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'] 	;
			$cart_details[0]['renter_lastname'] ='' 	;
		}
		if (empty($cart_details[0]['renter_image'])) {
			$cart_details[0]['renter_image'] = BASE_URL."server/assets/images/profile.png";
		}

		// print_r($receiver_id) ;
		
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
		}

		

$msg= '<div class="wrapper" style="border:1px solid #ddd; width:940px; margin:0 auto; padding:0px 0px">
			<table width="940" style="border:1px solid #ddd; margin:0px auto; background-color:#095cab; padding: 10px 0px;text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
      			<td><img src="'.BASE_URL.'assets/images/logo.png"></td>
				</tr>
			</table>
			<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0px 10px" cellpadding="0" cellspacing="0">		
				<tr><td height="15"></td></tr>
				<tr>
				<td style="font-size:20px; padding-bottom:10px;"> Hi '.$cart_details[0]['app_user_first_name'].'  '.$cart_details[0]['app_user_last_name'].'</td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">Reservation request for   '.$cart_details[0]['project_name'].' for '. date('d-M-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).' - '.date('d-M-Y',strtotime($cart_details[0]['gear_rent_request_to_date'])).'.</td>
				</tr>
				<td><img src="'.$cart_details[0]['renter_image'].'" height="150px" width="150px" ></td>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">'.$cart_details[0]['renter_firstname'].'</td>
				</tr>
				<tr>
				
				<td style="font-size:18px; font-family:Gill Sans, Gill Sans MT, Myriad Pro, DejaVu Sans Condensed, Helvetica, Arial, sans-serif; padding-bottom:10px">
				<div style="background-color: lightgrey;border: 2px solid black;padding: 50px;margin: 20px;">'.$message.'</div>
				</td>
				</tr>
		
				<tr>
				<td><a href="'.$web_url.'rentals-dashboard">
				<button type="submit" style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">Reply Message </button>
				</a> </td>
				</tr>
				</tr>
				<tr><td height="15"></td></tr>
				<tr>
				<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
				</tr>
			</table>
  		</div>';

		   $mail_body = $msg; 

		 // die;
		// $to= "singhaniagourav@gmail.com";
		$to= $cart_details[0]['primary_email_address'];
		$subject = "Someone has left a message in your inbox.";		
		$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia ",
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

	public function ReciverMessageMail($cart_details ,$receiver_id,$sender_id,$message)
	{	
		if ($cart_details[0]['owner_show_business_name']  =='Y') {
			$cart_details[0]['app_user_first_name'] =$cart_details[0]['owner_bussiness_name'] 	;
			$cart_details[0]['app_user_last_name'] ='' 	;
		}
		if ($cart_details[0]['renter_show_business_name']  =='Y') {
			$cart_details[0]['renter_firstname'] =$cart_details[0]['renter_bussiness_name'] 	;
			$cart_details[0]['renter_lastname'] ='' 	;
		}
		if (empty($cart_details[0]['user_profile_picture_link'])) {
			$cart_details[0]['user_profile_picture_link'] = BASE_URL."server/assets/images/profile.png";
		}
		
		$web_url = WEB_URL;
		
		if(strstr($web_url,"backend/")){
			
			$web_url = str_replace("backend/","",$web_url);
			
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
				    padding: 10px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><img src="'.BASE_URL.'server/assets/images/logo.png"></td>
				</tr>
				</table>
				<table class="table table-condensed" width="940" style="margin:25px auto; padding: 0 10px;" cellpadding="0" cellspacing="0">
				<tr>
				<td style="font-size:20px; padding-bottom:10px;">
				Hi '.$cart_details[0]['renter_firstname'].'  '.$cart_details[0]['renter_lastname'].'</td>
				</tr>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">Reservation request for   '.$cart_details[0]['project_name'].' for '. date('d-M-Y',strtotime($cart_details[0]['gear_rent_request_from_date'])).' - '.date('d-M-Y',strtotime($cart_details[0]['gear_rent_request_to_date'])).'.</td>
				</tr>
				<td><img src="'.$cart_details[0]['user_profile_picture_link'].'" height="150px" width="150px" ></td>
				<tr>
				<td style="font-size:18px; padding-bottom:10px;">'.$cart_details[0]['app_user_first_name'].'</td>

				</tr>
				<tr>
					<td style="font-size:18px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;padding-bottom:10px;">

						<div style="background-color: lightgrey;border: 2px solid black;padding: 50px;margin: 20px;">'.$message.'</div>
					</td>
				</tr>
				<tr>
				    <td>
				     <a href="'.$web_url.'rentals-dashboard"><button type="submit" style="background-color:#428bca; color:#fff; padding:10px 12px; border:none; margin-bottom:20px">Reply Message </button></a>
				     
				      </td>
				  </tr>

				</tr>

				</table>

				<table width="940" style="    margin: 0px auto;
				    background-color:#ddd;
				    padding: 5px 0px;
				    text-align: center;" cellpadding="0" cellspacing="0">
				<tr>
				<td><p style="margin:0">&#169;'.date("Y").' KitShare. All rights reserved</p></td>
				</tr>
				</table>
				</div>

				</body>
				</html>
				';

		   $mail_body = $msg; 
		  //die;
		// $to= "singhaniagourav@gmail.com";
		$to= $cart_details[0]['renter_email'];
		$subject = "Someone has left a message in your inbox.";		
		$mail_data = array(
						'Messages'=>array(array(
										"From"=>array(
												"Email"=>"support@kitshare.com.au",
												"Name"=>"Kitshare Australia ",
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


	//SendMessage
	public function AddUserContact(){
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		
		if(is_array($post_data) && count($post_data)>0 ){
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		$message_code = "";
		
		if ($app_user_id != '') {
			
			if($post_data['message_type']=="Order"){
				
				if(!(array_key_exists("message_type",$post_data)))
					$post_data['message_type']="Order";
			
				$sql = "SELECT * FROM ks_chat_user WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$post_data['renter_id']."' OR owner_id = '".$post_data['renter_id']."' OR renter_id = '".$app_user_id ."'  )   AND  order_id = '".$post_data['order_id']."' " ;
				$check_response = $this->common_model->get_records_from_sql($sql);
				if (empty($check_response)) {
					$insert_data = 	array(
											'owner_id'=>$app_user_id , 
											'renter_id'=>$post_data['renter_id'] , 
											'user_gear_desc_id'=>$post_data['user_gear_desc_id'] , 
											'order_id'=>$post_data['order_id'] , 
											'status'=>'Active',
											'create_date'=>date('Y-m-d'),
											'created_time' => date('H:i:m')
										 );
					$chat_user_id =  $this->common_model->InsertData('ks_chat_user',$insert_data);
				}else{ 
					$chat_user_id =  $check_response[0]->chat_user_id;

					$update_Data = array(
										
										'create_date'=>date('Y-m-d'),
										
									);
					$this->db->where('chat_user_id', $chat_user_id);
					$this->db->update('ks_chat_user', $update_Data);

				}
				
				if ($app_user_id == $post_data['renter_id']) {
					if ($app_user_id == $check_response[0]->owner_id) {
						$post_data['renter_id'] =  $check_response[0]->renter_id ;
					}else{
						$post_data['renter_id'] = $check_response[0]->owner_id ;
					}
				}
				
				$receiver_id = $post_data['renter_id'];
				//$this->SendMessageMail($post_data['order_id'],$app_user_id , $receiver_id ,$post_data['message']);	
				
			}else{
				$receiver_id = $post_data['renter_id'];
				$chat_user_id = $post_data['sender_id'];
				
				//Chat message code is fetched
				$where_array = "((sender_id='".$app_user_id."' and receiver_id='".$receiver_id."') OR (sender_id='".$receiver_id."' and receiver_id='".$app_user_id."')) AND (message_code IS NOT NULL OR message_code!='') AND message_type='Contact'";
				$query = $this->common_model->GetAllWhere('ks_user_chatmessage',$where_array);				
				
				$message_details = $query->row();
				if($message_details->message_code!="")
					$message_code = $message_details->message_code;
			}	
			
			$insert_msg = array('chat_user_id'=>$chat_user_id,
			 					 'sender_id'=>$app_user_id,  
			 					 'receiver_id' =>$receiver_id,
			 					 'message' =>$post_data['message'],
								 'message_type'=>$post_data['message_type'],
								 'message_code'=>$message_code,
			 					 'create_date'=>date('Y-m-d'),
			 					 'created_time'=>date('H:i:m'),
			 					 'created_by'=>$app_user_id
			 					) ;
			  $chat_user_id =  $this->common_model->InsertData('ks_user_chatmessage',$insert_msg);					 
			  	$response['status'] = 200;
				$response['status_message'] = 'Message Sent Successfully';
				$json_response = json_encode($response);
				echo $json_response;
		}else{
				/*$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;*/
				header('HTTP/1.1 400 Session Expired');
				exit();

		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}
	// Get MessageDetails
	public function GetAllmessageList()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);

		if ($app_user_id != '') {
			
			if($post_data['message_type']=="Order"){
				
				$sql = "SELECT * FROM ks_chat_user WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$app_user_id."')  AND  order_id = '".$post_data['order_id']."' ORDER BY date(create_date) DESC " ;
				$check_response = $this->common_model->get_records_from_sql($sql);

				if (empty($check_response)) {
					$response['status'] = 200;
					$response['status_message'] = 'Message Found Successfully';
					$json_response = json_encode($response);
					echo $json_response;
					exit;
				}else{ 
					$chat_user_id =  $check_response[0]->chat_user_id;

				}
				$where = array('chat_user_id'=>$chat_user_id,'message_type'=>'Order');
				
				$query  =  $this->common_model->GetAllWhere('ks_user_chatmessage',$where);				 
			  	$message = $query->result();
				
			}else{
				$sql = "SELECT * FROM `ks_user_chatmessage` WHERE (sender_id='".$post_data['sender_id']."' OR receiver_id='".$post_data['sender_id']."' ) AND message_type='Contact' AND message_code='".$post_data['order_id']."' ORDER BY `chat_message_id` ASC";
				
				$message = $this->common_model->get_records_from_sql($sql);		
			}
			  	
			  	if (!empty($message)) {
			  		$i = 0 ;	
			  		 foreach ($message as  $value) {
							
							if($post_data['message_type']=="Order"){
								
								if($post_data['order_id']!=""){
									
									$sql ="SELECT a.project_name,a.order_status, a.gear_rent_start_date, a.gear_rent_end_date, b.transaction_amount  
										  FROM ks_user_gear_rent_details a INNER JOIN ks_user_gear_payments b ON a.order_id=b.gear_order_id 
										  WHERE a.order_id = '".$post_data['order_id']."' GROUP BY order_id";
									$order_details = $this->common_model->get_records_from_sql($sql);
									
									$message[$i]->project_name 		= $order_details[0]->project_name;
									$message[$i]->rental_start_date = $order_details[0]->gear_rent_start_date;
									$message[$i]->rental_end_date 	= $order_details[0]->gear_rent_end_date;
									$message[$i]->amount 			= $order_details[0]->transaction_amount;	
									$message[$i]->owner_link 		= REDIRECT_HOST."/order-summary/".$post_data['order_id']."/owner";
									$message[$i]->renter_link 		= REDIRECT_HOST."/order-summary/".$post_data['order_id']."/renter";
									$message[$i]->accept_link 		= BASE_URL."server/Gear_listing/AcceptByOwnerGearRequest/".$post_data['order_id'];
									$message[$i]->decline_link		= BASE_URL."server/Gear_listing/RejectByOwnerGearRequest/".$post_data['order_id'];
									
									
								}
								
								
							}
			  		 		// sender details
			  		 		$query_sender  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$value->sender_id));					 
			  				$sender_details = $query_sender->result();
			  				if (!empty($sender_details)) {
			  					$sender_detail =array(
			  											'app_username'=>$sender_details[0]->app_username,
			  											'app_user_first_name'=>$sender_details[0]->app_user_first_name,
			  											'app_user_last_name'=>$sender_details[0]->app_user_last_name,
			  											'user_profile_picture_link'=>$sender_details[0]->user_profile_picture_link,
			  											'chat_status'=>$sender_details[0]->chat_status,
			  											);
			  					$message[$i]->sender_details = $sender_detail;
			  				}else{
			  					$message[$i]->sender_details = array();
			  				}

			  				$query_receiver  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$value->receiver_id));					 
			  				$receiver_details = $query_receiver->result();
			  				if (!empty($receiver_details)) {
			  					$receiver_detail =array(
			  											'app_username'=>$receiver_details[0]->app_username,
			  											'app_user_first_name'=>$receiver_details[0]->app_user_first_name,
			  											'app_user_last_name'=>$receiver_details[0]->app_user_last_name,
			  											'user_profile_picture_link'=>$receiver_details[0]->user_profile_picture_link,
			  											'chat_status'=>$receiver_details[0]->chat_status,
			  											);
			  					$message[$i]->receiver_details = $receiver_detail;
			  				}else{
			  					$message[$i]->receiver_details = array();
			  				}
							
							
							
			  				
			  		 	$i++;
			  		 }
			  	}
			  	$response['status'] = 200;
				$response['status_message'] = 'Message Send Successfully';
				$response['message'] = $message;
				$json_response = json_encode($response);
				echo $json_response;
		}else{
				header('HTTP/1.1 400 Session Expired');
				exit();

		}
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	// Get status
	public function GetMessageStatus()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {
			$query = $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id)); 
			$result = $query->row();
			$response['status'] = 200;
			$response['status_message'] = 'User Current Status';
			$response['status_message'] = $result;
			$json_response = json_encode($response);
			echo $json_response;
		}else{
				/*$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;*/
				header('HTTP/1.1 400 Session Expired');
				exit();

		}	
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
			
		}
	}

	//  update Status
	public function updateUserStatus($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
			$token 			= $post_data['token'];
			$app_user_id = $this->userinfo($token);
			if ($app_user_id != '') {
				$chat_status = array('chat_status'=>$post_data['chat_status'])	;
				$this->common_model->UpdateRecord($chat_status, 'ks_users' ,'app_user_id' ,$app_user_id);
				$response['status'] = 200;
				$response['status_message'] = 'User Chat status is been updated Successfully';
				$json_response = json_encode($response);
				echo $json_response;

			}else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();

			}	
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}
	public function ContactDetails()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
	
		
		if ($app_user_id != '') {
			
			
			//Orders that are not expired or Completed are fetched
			/*$sql ="SELECT DISTINCT(order_id) FROM `ks_user_gear_rent_details` WHERE `order_status`='1' OR `order_status`='2' OR `order_status`='3'";
			$res = $this->common_model->get_records_from_sql($sql);
		
			
			$order_id_list = "";
			foreach($res as $row)
			{
				$order_id_list.="'".$row->order_id."',";
				
			}
			$order_id_list = substr($order_id_list,0,-1);			
			
			
			$sql = "SELECT  a.`owner_id`,a.`renter_id`,a.`order_id`,b.`chat_user_id`,b.`chat_message_id`,b.`message_type`,b.`sender_id`,b.`receiver_id` FROM ks_chat_user a INNER JOIN ks_user_chatmessage b ON a.chat_user_id = b.chat_user_id WHERE ( (a.owner_id = '".$app_user_id."' OR a.renter_id = '".$app_user_id."') AND b.message_type='Order'";
			
			$sql.=")";
		
			
			$sql1 = "SELECT 1 as `owner_id`,2 as `renter_id`, message_code as `order_id`, `chat_user_id`, `chat_message_id`,`message_type`,`sender_id`,`receiver_id` FROM `ks_user_chatmessage` WHERE (sender_id='".$app_user_id."' OR receiver_id='".$app_user_id."') AND message_type='Contact'";
			
			
			$sql3 = "SELECT * FROM (".$sql." UNION ".$sql1.") AS c GROUP BY c.order_id ORDER BY c.chat_message_id DESC";*/			
			
			
			$sql3 ="SELECT * FROM (SELECT * FROM (SELECT 1 as `owner_id`, 2 as `renter_id`, message_code as `order_id`, `chat_user_id`, `chat_message_id`, `message_type`, `sender_id`, `receiver_id`, `create_date`, `created_time`  FROM `ks_user_chatmessage` WHERE (`sender_id`='".$app_user_id."' OR `receiver_id`='".$app_user_id."') AND `message_type`='Contact' ORDER BY `chat_message_id` DESC) AS A UNION SELECT * FROM (SELECT b.`owner_id`,b.`renter_id`, b.`order_id`, a.`chat_user_id`, a.`chat_message_id`, a.`message_type`, a.`sender_id`, a.`receiver_id`, a.`create_date`, a.`created_time` FROM `ks_user_chatmessage` AS a INNER JOIN `ks_chat_user` AS b ON a.chat_user_id=b.chat_user_id WHERE (a.`sender_id`='".$app_user_id."' OR a.`receiver_id`='".$app_user_id."') AND a.`message_type`='Order' ORDER BY a.`chat_message_id` DESC) AS G ORDER BY `chat_message_id` DESC) K GROUP BY K.order_id ORDER BY K.`create_date` DESC, K.`created_time` DESC";
			
			$check_response = $this->common_model->get_records_from_sql($sql3);
			
			
			if (!empty($check_response)) {
				$i = 0 ;
				$chat_arr = array();
				foreach ($check_response as  $value) {
					$app_users_ids = '' ;
					$renter_id = '' ;
						
						if($value->message_type == "Order"){
							if ($value->owner_id == $app_user_id) {
								$app_users_ids = $value->renter_id;
                            	$renter_id = $value->renter_id;
							}elseif ($value->renter_id == $app_user_id) {
								$app_users_ids = $value->owner_id;
                            	$renter_id = $value->renter_id;
							}
						}else{
							if($value->receiver_id==$app_user_id){
								$app_users_ids = $value->sender_id;
								$renter_id = $value->sender_id;
								//$check_response[$i]->renter_id = $value->sender_id;
							}else{
								$app_users_ids = $value->receiver_id;
								$renter_id = $value->receiver_id;
								//$check_response[$i]->renter_id = $value->receiver_id;
							}
						}

						$sql ="SELECT app_user_id ,bussiness_name,show_business_name,app_username,app_user_first_name,app_user_last_name,chat_status,user_profile_picture_link  FROM ks_users WHERE app_user_id ='".$app_users_ids."'" ;
						$user_details = $this->common_model->get_records_from_sql($sql);
						
						if(count($user_details)>0) {
							$chat_arr[$i] = (array) $value;
							$chat_arr[$i]['chat_id'] = $value->order_id;
							$chat_arr[$i]['renter_id'] = $renter_id;
							$check_response[$i]->user_details = $user_details;
							$check_response[$i]->chat_id=$value->order_id;
							$check_response[$i]->renter_id = $renter_id;

							if (empty($user_details[0]->user_profile_picture_link)) {
								$user_details[0]->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
							}

							if ($user_details[0]->show_business_name == 'Y') {
								$user_details[0]->app_user_first_name = $user_details[0]->bussiness_name ;
								$user_details[0]->app_user_last_name = '' ;
							}
						
					
							$check_response[$i]->user_details = $user_details;
							$chat_arr[$i]['user_details'] = $user_details;



							if($value->message_type == "Order"){	
								$sql ="SELECT project_name ,order_status, gear_rent_start_date, gear_rent_end_date FROM ks_user_gear_rent_details WHERE order_id = '".$value->order_id."' " ;
								$order_details = $this->common_model->get_records_from_sql($sql);
							
								if (!empty($order_details)) {
										$check_response[$i]->project_name =  $order_details[0]->project_name ;
										$chat_arr[$i]['project_name'] = $order_details[0]->project_name;
								}else{
										$check_response[$i]->project_name = '';
										$chat_arr[$i]['project_name'] = '';
								}
								if(!empty($order_details)){
									if ($order_details[0]->order_status =='2') {
										$chat_arr[$i]['order_status'] = 'Reservation';
										$chat_arr[$i]['active_message'] = true;
									}elseif ($order_details[0]->order_status =='3') {
										$chat_arr[$i]['order_status'] = 'Contract';
										$chat_arr[$i]['active_message'] = true;
									}elseif ($order_details[0]->order_status =='4') {
										$chat_arr[$i]['order_status'] = 'Completed';
										$chat_arr[$i]['active_message'] = false;
									}elseif ($order_details[0]->order_status =='5') {
										$chat_arr[$i]['order_status'] = 'Cancelled';
										$chat_arr[$i]['active_message'] = false;
									}elseif ($order_details[0]->order_status =='6') {
										$chat_arr[$i]['order_status'] = 'Declined';
										$chat_arr[$i]['active_message'] = false;
									}elseif ($order_details[0]->order_status =='7') {
										$chat_arr[$i]['order_status'] = 'Archived';
										$chat_arr[$i]['active_message'] = false;
									}elseif ($order_details[0]->order_status =='8') {
										$chat_arr[$i]['order_status'] = 'Expired';
										$chat_arr[$i]['active_message'] = false;
									}
									else{
										$chat_arr[$i]['order_status'] = 'Quote';
										$chat_arr[$i]['active_message'] = true;
									}
									
									$check_response[$i]->pickup_date = date("Y-m-d",strtotime($order_details[0]->gear_rent_start_date));
									$check_response[$i]->return_date = date("Y-m-d",strtotime($order_details[0]->gear_rent_end_date));
										$chat_arr[$i]['pickup_date'] = date("Y-m-d",strtotime($order_details[0]->gear_rent_start_date));
										$chat_arr[$i]['return_date'] = date("Y-m-d",strtotime($order_details[0]->gear_rent_end_date));
									
								}else{
									$chat_arr[$i]['order_status'] = '';
									$chat_arr[$i]['active_message'] = false;
									$chat_arr[$i]['pickup_date'] = '';
									$chat_arr[$i]['return_date'] = '';
								}	
							
							
								$sql ="  SELECT count(chat_message_id) AS unseen_chat_count  FROM ks_user_chatmessage WHERE chat_user_id = '".$value->chat_user_id."' AND is_seen = 'unseen' AND receiver_id ='".$app_user_id."' " ;
								$unread_chat = $this->common_model->get_records_from_sql($sql);
								
								if (!empty($unread_chat)) {
									$check_response[$i]->unseen_message =  $unread_chat[0]->unseen_chat_count ;  
									$chat_arr[$i]['unseen_message'] = $unread_chat[0]->unseen_chat_count;
								}else{
									$check_response[$i]->unseen_message = 0;
									$chat_arr[$i]['unseen_message'] = 0;
								}
								
							}else{
								
								$check_response[$i]->project_name = '';
								$check_response[$i]->order_status = '';
								$chat_arr[$i]['project_name'] = '';
								$chat_arr[$i]['order_status'] = '';
								
								$sql ="  SELECT count(chat_message_id) AS unseen_chat_count  FROM ks_user_chatmessage WHERE is_seen = 'unseen' AND sender_id ='".$value->sender_id."' AND receiver_id='".$app_user_id."' AND message_type='Contact'" ;
								$unread_chat = $this->common_model->get_records_from_sql($sql);
								
								if (!empty($unread_chat)) {
									$check_response[$i]->unseen_message =  $unread_chat[0]->unseen_chat_count ;  
									$chat_arr[$i]['unseen_message'] = $unread_chat[0]->unseen_chat_count;
								}else{
									$check_response[$i]->unseen_message = 0;
									$chat_arr[$i]['unseen_message'] = 0;
								}
								
							}
						
							$check_response[$i]->sender_id = $value->sender_id;
							$chat_arr[$i]['sender_id'] = $value->sender_id;
							
							// $sql ="SELECT app_user_id ,bussiness_name,show_business_name,app_username,app_user_first_name,app_user_last_name,chat_status,user_profile_picture_link  FROM ks_users WHERE app_user_id ='".$app_users_ids."'" ;
							// $user_details = $this->common_model->get_records_from_sql($sql);
							$check_response[$i]->user_details = $user_details;
							
							// if(count($user_details)>0){
							// 	if (empty($user_details[0]->user_profile_picture_link)) {
							// 		$user_details[0]->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
							// 	}

							// 	if ($user_details[0]->show_business_name == 'Y') {
							// 		$user_details[0]->app_user_first_name = $user_details[0]->bussiness_name ;
							// 		$user_details[0]->app_user_last_name = '' ;
							// 	}
							// }
						
							$check_response[$i]->user_details = $user_details;


							$i++ ;
						} else {
							// unset($check_response[$i]);
						}
				}//end loop
				
				$response['status'] = 200;
				$response['status_message'] = 'Chat Users found Successfully';
				// $response['check_response'] = $check_response;
				$response['result'] = $chat_arr;
				$response['total'] = $i;
				$json_response = json_encode($response);
				echo $json_response;
			}else{

				$response['status'] = 200;
				$response['status_message'] = 'No chat found';
				$response['result'] = array();
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}
			
		}else{
				/*$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;*/
				header('HTTP/1.1 400 Session Expired');
				exit();

		}		
		}else{
			
			
			header('HTTP/1.1 200 Success');
			exit();
		}
		
	}
	public function ContactDetails_old()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
	
		
		if ($app_user_id != '') {
			
			
			//Orders that are not expired or Completed are fetched
			/*$sql ="SELECT DISTINCT(order_id) FROM `ks_user_gear_rent_details` WHERE `order_status`='1' OR `order_status`='2' OR `order_status`='3'";
			$res = $this->common_model->get_records_from_sql($sql);
		
			
			$order_id_list = "";
			foreach($res as $row)
			{
				$order_id_list.="'".$row->order_id."',";
				
			}
			$order_id_list = substr($order_id_list,0,-1);			
			
			
			$sql = "SELECT  a.`owner_id`,a.`renter_id`,a.`order_id`,b.`chat_user_id`,b.`chat_message_id`,b.`message_type`,b.`sender_id`,b.`receiver_id` FROM ks_chat_user a INNER JOIN ks_user_chatmessage b ON a.chat_user_id = b.chat_user_id WHERE ( (a.owner_id = '".$app_user_id."' OR a.renter_id = '".$app_user_id."') AND b.message_type='Order'";
			
			$sql.=")";
		
			
			$sql1 = "SELECT 1 as `owner_id`,2 as `renter_id`, message_code as `order_id`, `chat_user_id`, `chat_message_id`,`message_type`,`sender_id`,`receiver_id` FROM `ks_user_chatmessage` WHERE (sender_id='".$app_user_id."' OR receiver_id='".$app_user_id."') AND message_type='Contact'";
			
			
			$sql3 = "SELECT * FROM (".$sql." UNION ".$sql1.") AS c GROUP BY c.order_id ORDER BY c.chat_message_id DESC";*/			
			
			
			$sql3 ="SELECT * FROM (SELECT * FROM (SELECT 1 as `owner_id`, 2 as `renter_id`, message_code as `order_id`, `chat_user_id`, `chat_message_id`, `message_type`, `sender_id`, `receiver_id`, `create_date`, `created_time`  FROM `ks_user_chatmessage` WHERE (`sender_id`='".$app_user_id."' OR `receiver_id`='".$app_user_id."') AND `message_type`='Contact' ORDER BY `chat_message_id` DESC) AS A UNION SELECT * FROM (SELECT b.`owner_id`,b.`renter_id`, b.`order_id`, a.`chat_user_id`, a.`chat_message_id`, a.`message_type`, a.`sender_id`, a.`receiver_id`, a.`create_date`, a.`created_time` FROM `ks_user_chatmessage` AS a INNER JOIN `ks_chat_user` AS b ON a.chat_user_id=b.chat_user_id WHERE (a.`sender_id`='".$app_user_id."' OR a.`receiver_id`='".$app_user_id."') AND a.`message_type`='Order' ORDER BY a.`chat_message_id` DESC) AS G ORDER BY `chat_message_id` DESC) K GROUP BY K.order_id ORDER BY K.`create_date` DESC, K.`created_time` DESC";
			
			$check_response = $this->common_model->get_records_from_sql($sql3);
			
			
			if (!empty($check_response)) {
				$app_users_ids = '' ;
				$i = 0 ;
				foreach ($check_response as  $value) {
					
						$check_response[$i]->chat_id=$value->order_id;
						if($value->message_type == "Order"){
							if ($value->owner_id == $app_user_id) {
								$app_users_ids = $value->renter_id;
							}elseif ($value->renter_id == $app_user_id) {
								$app_users_ids = $value->owner_id;
							}
						}else{
							if($value->receiver_id==$app_user_id){
								$app_users_ids = $value->sender_id;
								$check_response[$i]->renter_id = $value->sender_id;
							}else{
								$app_users_ids = $value->receiver_id;
								$check_response[$i]->renter_id = $value->receiver_id;
							}
						}
						if($value->message_type == "Order"){	
							$sql ="SELECT project_name ,order_status, gear_rent_start_date, gear_rent_end_date FROM ks_user_gear_rent_details WHERE order_id = '".$value->order_id."' " ;
							$order_details = $this->common_model->get_records_from_sql($sql);
						
							if (!empty($order_details)) {
									$check_response[$i]->project_name =  $order_details[0]->project_name ;
							}else{
									$check_response[$i]->project_name = '';
							}
							if(!empty($order_details)){
								if ($order_details[0]->order_status =='2') {
									$check_response[$i]->order_status = 'Reservation';
									$check_response[$i]->active_message = true;
								}elseif ($order_details[0]->order_status =='3') {
									$check_response[$i]->order_status = 'Contract';
									$check_response[$i]->active_message = true;
								}elseif ($order_details[0]->order_status =='4') {
									$check_response[$i]->order_status = 'Completed';
									$check_response[$i]->active_message = false;
								}elseif ($order_details[0]->order_status =='5') {
									$check_response[$i]->order_status = 'Cancelled';
									$check_response[$i]->active_message = false;
								}elseif ($order_details[0]->order_status =='6') {
									$check_response[$i]->order_status = 'Declined';
									$check_response[$i]->active_message = false;
								}elseif ($order_details[0]->order_status =='7') {
									$check_response[$i]->order_status = 'Archived';
									$check_response[$i]->active_message = false;
								}elseif ($order_details[0]->order_status =='8') {
									$check_response[$i]->order_status = 'Expired';
									$check_response[$i]->active_message = false;
								}
								else{
									$check_response[$i]->order_status = 'Quote';
									$check_response[$i]->active_message = true;
								}
								
								$check_response[$i]->pickup_date = date("Y-m-d",strtotime($order_details[0]->gear_rent_start_date));
								$check_response[$i]->return_date = date("Y-m-d",strtotime($order_details[0]->gear_rent_end_date));
								
							}else{
								$check_response[$i]->order_status = '';
								$check_response[$i]->active_message = false;
								$check_response[$i]->pickup_date = '';
								$check_response[$i]->return_date = '';
							}	
						
						
							$sql ="  SELECT count(chat_message_id) AS unseen_chat_count  FROM ks_user_chatmessage WHERE chat_user_id = '".$value->chat_user_id."' AND is_seen = 'unseen' AND receiver_id ='".$app_user_id."' " ;
							$unread_chat = $this->common_model->get_records_from_sql($sql);
							
							if (!empty($unread_chat)) {
								$check_response[$i]->unseen_message =  $unread_chat[0]->unseen_chat_count ;  
							}else{
								$check_response[$i]->unseen_message = 0;
							}
							
						}else{
							
							$check_response[$i]->project_name = '';
							$check_response[$i]->order_status = '';
							
							$sql ="  SELECT count(chat_message_id) AS unseen_chat_count  FROM ks_user_chatmessage WHERE is_seen = 'unseen' AND sender_id ='".$value->sender_id."' AND receiver_id='".$app_user_id."' AND message_type='Contact'" ;
							$unread_chat = $this->common_model->get_records_from_sql($sql);
							
							if (!empty($unread_chat)) {
								$check_response[$i]->unseen_message =  $unread_chat[0]->unseen_chat_count ;  
							}else{
								$check_response[$i]->unseen_message = 0;
							}
							
						}
						
						$check_response[$i]->sender_id = $value->sender_id;
						
						$sql ="SELECT app_user_id ,bussiness_name,show_business_name,app_username,app_user_first_name,app_user_last_name,chat_status,user_profile_picture_link  FROM ks_users WHERE app_user_id ='".$app_users_ids."'" ;
						$user_details = $this->common_model->get_records_from_sql($sql);
						$check_response[$i]->user_details = $user_details;
						
						if(count($user_details)>0){
							if (empty($user_details[0]->user_profile_picture_link)) {
								$user_details[0]->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
							}

							if ($user_details[0]->show_business_name == 'Y') {
								$user_details[0]->app_user_first_name = $user_details[0]->bussiness_name ;
								$user_details[0]->app_user_last_name = '' ;
							}
						}
					
						$check_response[$i]->user_details = $user_details;


					$i++ ;
				}
				
				$response['status'] = 200;
				$response['status_message'] = 'Chat Users found Successfully';
				$response['result'] = $check_response;
				$response['total'] = $i;
				$json_response = json_encode($response);
				echo $json_response;
			}else{

				$response['status'] = 200;
				$response['status_message'] = 'No chat found';
				$response['result'] = array();
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}
			
		}else{
				/*$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;*/
				header('HTTP/1.1 400 Session Expired');
				exit();

		}		
		}else{
			
			
			header('HTTP/1.1 200 Success');
			exit();
		}
		
	}




	// Get UserInfo
	function userinfo($token){
		$app_user_id = $this->common_model->fetchTokenDetails($token);
		return $app_user_id;
	}
	
	public function SearchUsers()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);
		if ($app_user_id != '') {

			 $sql = "SELECT ks_chat_user.* FROM ks_chat_user INNER JOIN ks_users ON ks_users.app_user_id =  ks_chat_user.renter_id  WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$app_user_id."')   AND (ks_users.app_username LIKE '%".$post_data['search_key']."%' OR  ks_users.app_user_first_name LIKE '%".$post_data['search_key']."%' OR  ks_users.app_user_last_name LIKE '%".$post_data['search_key']."%' ) " ; 
			 $check_response2 = $this->common_model->get_records_from_sql($sql);

			 $sql1 = "SELECT ks_chat_user.* FROM ks_chat_user INNER JOIN ks_users ON ks_users.app_user_id =  ks_chat_user.owner_id  WHERE ( owner_id = '".$app_user_id."' OR renter_id = '".$app_user_id."')   AND ks_users.app_username LIKE '%".$post_data['search_key']."%'   " ; 
			 $check_response1 = $this->common_model->get_records_from_sql($sql1);
			 $check_response =  array_merge($check_response2, $check_response1);
			if (!empty($check_response)) {
				$app_users_ids = '' ;
				$i = 0 ;
				foreach ($check_response as  $value) {
					if ($value->owner_id == $app_user_id) {
						$app_users_ids = "'". $value->renter_id."',";
					}elseif ($value->renter_id == $app_user_id) {
						$app_users_ids = "'". $value->owner_id."',";
					}
					$sql ="  SELECT count(chat_message_id) AS unseen_chat_count  FROM ks_user_chatmessage WHERE chat_user_id = '".$value->chat_user_id."' AND is_seen = 'unseen' " ;
					$unread_chat = $this->common_model->get_records_from_sql($sql);
					// print_r($unread_chat[0]->unseen_chat_count);
					if (!empty($unread_chat)) {
						$check_response[$i]->unseen_message =  $unread_chat[0]->unseen_chat_count ;  
					}else{
						$check_response[$i]->unseen_message = 0;
					}	
					$sql ="SELECT project_name   FROM ks_user_gear_rent_details WHERE order_id = '".$value->order_id."' " ;
					$order_details = $this->common_model->get_records_from_sql($sql);
					if (!empty($order_details)) {
							$check_response[$i]->project_name =  $order_details[0]->project_name ;
					}else{
							$check_response[$i]->project_name = '';
					}
					$sql ="SELECT app_user_id , app_username,app_user_first_name,app_user_last_name,chat_status,user_profile_picture_link  FROM ks_users WHERE app_user_id IN (".rtrim($app_users_ids , ',').")  " ;
					$user_details = $this->common_model->get_records_from_sql($sql);
					if (empty($user_details[0]->user_profile_picture_link)) {
						$user_details[0]->user_profile_picture_link = BASE_URL."server/assets/images/profile.png";
					}
					$check_response[$i]->user_details = $user_details	;

					$i++ ;
				}
				$response['status'] = 200;
				$response['status_message'] = 'Chat Users found Successfully';
				$response['result'] = $check_response;
				$json_response = json_encode($response);
				echo $json_response;
			}else{

				$response['status'] = 200;
				$response['status_message'] = 'No chat found';
				$response['result'] = array();
				$json_response = json_encode($response);
				echo $json_response;
				exit;
			}
		}else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();
		}	
		
		}else{
			
			header('HTTP/1.1 200 Success');
			exit();
		}
	}

	// Change Message Status
	public function MarkChatSeen()
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		
		if(is_array($post_data) && count($post_data)>0 ){
		$token 			= $post_data['token'];
		$app_user_id = $this->userinfo($token);	

		if (!empty($app_user_id)) {
				
				if((!(array_key_exists("message_type",$post_data)) && array_key_exists("order_id",$post_data)) || (array_key_exists("message_type",$post_data) && $post_data['message_type']=="Order")){
				
					$order_id = $post_data['order_id'];
					$query = $this->common_model->GetAllWhere('ks_chat_user',array('order_id'=>$order_id)); 
					$result = $query->row();
					$where = array('chat_user_id'=>$result->chat_user_id , 'receiver_id'=>$app_user_id );
					
				}else{
					$where = array('receiver_id'=>$app_user_id,'message_type'=>'Contact','sender_id'=>$post_data['sender_id']);
				}
				
				$query = $this->common_model->GetAllWhere('ks_user_chatmessage',$where); 
				$result1 = $query->result();
				foreach ($result1 as  $value) {
							$update_Data = array(
									
									'is_seen'=> 'seen',
									
								);
						$this->db->where('chat_message_id', $value->chat_message_id);
						$this->db->update('ks_user_chatmessage', $update_Data);
					}	
			
				$response['status'] = 200;
				$response['status_message'] = 'Chat Status updated';
				$response['result'] = array();
				$json_response = json_encode($response);
				echo $json_response;
				exit;
		}else{
					/*$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Already logged in';
					$json_response = json_encode($response);
					echo $json_response;*/
					header('HTTP/1.1 400 Session Expired');
					exit();
		}	
		}else{
			header('HTTP/1.1 200 Success');
			exit();
		}	
	}	
}	
?>	