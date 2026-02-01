<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hyperwallet extends CI_Controller {
		 public function __construct() {
			header('Access-Control-Allow-Origin: *');
			header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
			parent::__construct();
			$this->load->helper(array('url','form','html','text'));
			$this->load->library(array('session','form_validation','email'));
			$this->load->model(array('common_model','home_model'));
		}

	public function AddUser()
	{
			
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);
		
		if ($app_user_id > 0 ) {
			unset($post_data['token']);

			 $six_digit_random_number = mt_rand(100000, 999999);
			$user_details = array(
									'profileType'=>'INDIVIDUAL',
									'clientUserId'=>$six_digit_random_number,
									'programToken'=>'prg-64ec65a9-6517-40bf-8cf8-ee3e85741188',
								);
			$user_details = array_merge($user_details,$post_data);

			$hyperwallet_user_data =  json_encode($user_details,true);
							
				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://api.sandbox.hyperwallet.com/rest/v3/users",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS =>$hyperwallet_user_data,
				  CURLOPT_HTTPHEADER => array(
				    "authorization: Basic cmVzdGFwaXVzZXJAMTQ1ODc3ODE2MTk6ayF0U2hhcmVhcGkwMTA4MTg=",
				    "content-type: application/json",
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				 			 $response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
				} else {
					//print_r($response);
					$response =  json_decode($response);
					if(isset($response->errors)){
						$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
					}else{
							$update_data = array('token_hyperwallet'=>$response->token);
							$this->common_model->UpdateRecord($update_data,"ks_users","app_user_id",$app_user_id);
							$response=array("status"=>200,
								"status_message"=>"success",
								"result"=>'User details Added to hyperwallet');
							echo json_encode($response);
							exit();
					}
				}
			
		}else{
			header('HTTP/1.1 401 Unauthorized');
				exit();
		}
		
	}

	public function AddUserCard()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ) {
			unset($post_data['token']);
			$query = $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$app_user_id));
			$user_details  = $query->row();
			$bank_details  =array(	"transferMethodCountry"=> "US",
								  "transferMethodCurrency"=> "USD",
								  "type"=>"BANK_CARD"
								 );
			$bank_details = array_merge($bank_details,$post_data);
			$hyperwallet_bank_data = json_encode($bank_details,true);
				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://api.sandbox.hyperwallet.com/rest/v3/users/".$user_details->token_hyperwallet."/bank-cards",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => $hyperwallet_bank_data,
				  CURLOPT_HTTPHEADER => array(
				    "authorization: Basic cmVzdGFwaXVzZXJAMTQ1ODc3ODE2MTk6ayF0U2hhcmVhcGkwMTA4MTg=",
				    "content-type: application/json",
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				 // echo "cURL Error #:" . $err;
				  $response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
				} else {
				 
					$response =  json_decode($response);
					if(isset($response->errors)){
						$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
					}else{
						$update_data = array('token_card_hyperwallet'=>$response->token);
							$this->common_model->UpdateRecord($update_data,"ks_users","app_user_id",$app_user_id);
							$response=array("status"=>200,
								"status_message"=>"success",
								"result"=>'User details Added to hyperwallet');
							echo json_encode($response);
							exit();
					}	
				}
		
		}else{
			header('HTTP/1.1 401 Unauthorized');
				exit();
		}
		//echo $app_user_id;
	}



	//	 Add User Bank Details   
	public function AddBanKDetails()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ) {
			unset($post_data['token']);
			$query = $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$app_user_id));
			$user_details  = $query->row();
			
			$bank_details  =array(	"transferMethodCountry"=> "US",
								  "transferMethodCurrency"=> "USD",
								  "type"=>"BANK_ACCOUNT",
								  "bankAccountRelationship"=>"SELF",
								  "bankAccountPurpose"=>'CHECKING'
								 );
			$bank_details = array_merge($bank_details,$post_data);
			$hyperwallet_bank_data = json_encode($bank_details,true);
			//print_r($hyperwallet_bank_data);die;
				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://api.sandbox.hyperwallet.com/rest/v3/users/".$user_details->token_hyperwallet."/bank-accounts",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => $hyperwallet_bank_data,
				  CURLOPT_HTTPHEADER => array(
				    "authorization: Basic cmVzdGFwaXVzZXJAMTQ1ODc3ODE2MTk6ayF0U2hhcmVhcGkwMTA4MTg=",
				    "content-type: application/json",
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				 // echo "cURL Error #:" . $err;
				  $response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
				} else {
				// print_r($response);
					$response =  json_decode($response);
					if(isset($response->errors)){
						$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
					}else{
						$update_data = array('token_card_hyperwallet'=>$response->token);
							$this->common_model->UpdateRecord($update_data,"ks_users","app_user_id",$app_user_id);
							$response=array("status"=>200,
								"status_message"=>"success",
								"result"=>'User details Added to hyperwallet');
							echo json_encode($response);
							exit();
					}	
				}
		
		}else{
			header('HTTP/1.1 401 Unauthorized');
				exit();
		}
		//echo $app_user_id;
	}

	// Tranfer Payment

	public function HyperwalletUserTranfer()
	{
			$post_data      = json_decode(file_get_contents("php://input"),true);
		
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ) {
			unset($post_data['token']);
			$query = $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$app_user_id));
			$user_details  = $query->row();
			$six_digit_random_number = mt_rand(100000, 999999);
			$five_digit_random_number = mt_rand(10000, 99999);
			$tranfer_array = array(
						 			'clientTransferId'=>$six_digit_random_number , 
						 			'destinationAmount' =>2 ,  
						 			'destinationCurrency'=>'USD', 
						 			'notes'=> "Partial-Balance Transfer",
						 			'memo' => 'TransferClientId56387'.$five_digit_random_number , 
						 			'sourceToken'=>$user_details->token_hyperwallet , 
						 			'destinationToken'=>$user_details->token_card_hyperwallet 
								);

				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://api.sandbox.hyperwallet.com/rest/v3/transfers",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS =>  json_encode($tranfer_array),
				  CURLOPT_HTTPHEADER => array(
				    "authorization: Basic cmVzdGFwaXVzZXJAMTQ1ODc3ODE2MTk6ayF0U2hhcmVhcGkwMTA4MTg=",
				    "content-type: application/json",
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				 // echo "cURL Error #:" . $err;

					$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
					echo json_encode($response);
					exit();
				} else {
				  //echo $response;
				  $response =  json_decode($response);
					if(isset($response->errors)){
						$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
					}else{
						$update_data = array('token_tranfer_hyperwallet'=>$response->token);
							$this->common_model->UpdateRecord($update_data,"ks_users","app_user_id",$app_user_id);
							$response=array("status"=>200,
								"status_message"=>"success",
								"result"=>'User Tranfer Made to hyperwallet');
							echo json_encode($response);
							exit();
					}
				}
			
		}else{
			header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}

	// User Payment


	public function HyperwalletUserPayment($value='')
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ) {
			unset($post_data['token']);
			$query = $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$app_user_id));
			$user_details  = $query->row();
			$six_digit_random_number = mt_rand(100000, 999999);
			$payment_array = array(
								'amount'=>$post_data['amount'],
								'clientPaymentId'=>$six_digit_random_number ,
								'currency'=> "AUD",
								'destinationToken'=>$user_details->token_hyperwallet,
								'programToken' => "prg-64ec65a9-6517-40bf-8cf8-ee3e85741188",
								'purpose'=>"OTHER"
								);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.sandbox.hyperwallet.com/rest/v3/payments",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => json_encode($payment_array),
			  CURLOPT_HTTPHEADER => array(
			    "authorization: Basic cmVzdGFwaXVzZXJAMTQ1ODc3ODE2MTk6ayF0U2hhcmVhcGkwMTA4MTg=",
			    "content-type: application/json",
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			 // echo "cURL Error #:" . $err;
			    $response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
			} else {
			  $response =  json_decode($response);
					if(isset($response->errors)){
						$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
					}else{
						$update_data = array('token_payment_hyperwallet'=>$response->token);
							$this->common_model->UpdateRecord($update_data,"ks_users","app_user_id",$app_user_id);
							$response=array("status"=>200,
								"status_message"=>"success",
								"result"=>'User Payment Made to hyperwallet');
							echo json_encode($response);
							exit();
					}	
			}
			  


		}else{
			header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}

	// User Details Get
	public function GetHyperwalletUserDetails()
	{
		$post_data      = json_decode(file_get_contents("php://input"),true);
		
		$token 			= $post_data['token'];
		
		$app_user_id = $this->userinfo($token);
		if ($app_user_id > 0 ) {
			unset($post_data['token']);
			$query = $this->common_model->GetAllWhere('ks_users' , array('app_user_id'=>$app_user_id));
			$user_details  = $query->row();
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.sandbox.hyperwallet.com/rest/v3/users/".$user_details->token_hyperwallet,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "authorization: Basic cmVzdGFwaXVzZXJAMTQ1ODc3ODE2MTk6ayF0U2hhcmVhcGkwMTA4MTg=",
			    "content-type: application/json",
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  //echo "cURL Error #:" . $err;
			  $response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
			} else {
			 // echo $response;
			  $response =  json_decode($response);
					if(isset($response->errors)){
						$response=array("status"=>400,
								"status_message"=>"failure",
								"result"=>'Somethig went wrong ');
							echo json_encode($response);
							exit();
					}else{
							$response=array("status"=>200,
								"status_message"=>"success",
								"result"=>$response);
							echo json_encode($response);
							exit();
					}
			}

		}else{
			header('HTTP/1.1 401 Unauthorized');
				exit();
		}	
	}
	function userinfo($token){
	
		if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
     		exit();
		}
		$token_decrypt = base64_decode($token);
		$token_array = explode("|",$token_decrypt);
		
		
		$email = $token_array[0];
		$secret_key = $token_array[1];
		$expire_time = $token_array[2];	
	
		$columns = "app_user_id";
		$table = "ks_users";
		$where_clause = array('primary_email_address'=>$email,'auth_secret_key'=>$secret_key,'expire_time'=>$expire_time);
		
		$query = $this->common_model->GetSpecificValues($columns,$table,$where_clause);
		$row = $query->result_array();
		if (count($row) > 0) {
			$app_user_id = $row[0]['app_user_id'];
		}else{

			$app_user_id = '';
			$response['status'] = 401;
			$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}
		
		
		return $app_user_id;

	}
	
	
	
}?>
