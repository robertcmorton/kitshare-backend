<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'/third_party/braintree-php-3.36.0/lib/autoload.php');

// require_once(APPPATH.'/third_party/braintree-php-3.36.0/lib/Braintree.php');
class BrainTree extends CI_Controller {
	
	 public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email','upload','pagination'));
		$this->load->model(array('common_model','home_model'));
		}
	

	public function index()
	{

	}
	
	public function Token ($value='')
	{
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		$gateway = new Braintree_Gateway([
			    'environment' => 'sandbox',
			    'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);

			$config = new Braintree_Configuration([
			    'environment' => 'sandbox',
			     'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);
				 $clientToken = $gateway->clientToken()->generate();		

						$response['status_code'] = 200;
						$response['result'] = $clientToken;

						
					echo json_encode($response);			
		
	}

	public function Payment2($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		
			// Instantiate a Braintree Gateway either like this:
			$gateway = new Braintree_Gateway([
			    'environment' => 'sandbox',
			    'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);

			$config = new Braintree_Configuration([
			    'environment' => 'sandbox',
			     'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);
			
			$result = $gateway->transaction()->sale([
				    'amount' => $post_data['amount'],
				    'paymentMethodNonce' => $post_data['payment_method_nonce']
				    
				]); 
			print_r($result);
			if($result->success == '1'){

				$response['status'] = 200;
				$response['message'] = 'Transaction made sucessfully';
				$response['trancation_id'] = $result->transaction->id;
				
				echo json_encode($response,true);
			}else{

				$response['status'] = 400;
				$response['message'] = 'Something went wrong';
				
				echo json_encode($response,true);
			}
		
	}

	public function Payment($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		 $token = $post_data['token']; 
		   $app_user_id =  $this->userinfo($token);

		if ($app_user_id =='') {

			 	$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}else{
			$Cart_details = $this->home_model->getUserCart($app_user_id);
				$gateway = new Braintree_Gateway([
								    'environment' => 'sandbox',
								    'merchantId' => 'zmdpynpfyrtg74pn',
								    'publicKey' => '5mx3mprhdtxkw97z',
								    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
								]);
				$config = new Braintree_Configuration([
										    'environment' => 'sandbox',
										     'merchantId' => 'zmdpynpfyrtg74pn',
										    'publicKey' => '5mx3mprhdtxkw97z',
										    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
										]);
				$query  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
				$user_data = $query->row();
				$result = $gateway->customer()->create([
												    'firstName' => $user_data->app_user_first_name,
												    'lastName' => $user_data->app_user_last_name,
												    'company' => $user_data->bussiness_name,
												    'paymentMethodNonce' => $post_data['payment_method_nonce']
												]);
					if ($result->success) {
							foreach ($Cart_details as $key => $value) {
									$data_update = array(	
												'braintree_token'=>$result->customer->paymentMethods[0]->token
												);
									$this->common_model->UpdateRecord($data_update , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value['user_gear_rent_detail_id']);

							}	
					    
					    $token_details = array(

									    	'braintree_customer_id'=>$result->customer->id ,
									    	'braintree_token'=>$result->customer->paymentMethods[0]->token

									    	);

					    $this->common_model->UpdateRecord($token_details , 'ks_users' , 'app_user_id', $user_data->app_user_id);
					   	$response['status'] = 200;
						$response['message'] = 'Transaction made sucessfully';
						$response['trancation_id'] = '';
						
						echo json_encode($response,true);
					} else {
							$response['status'] = 400;
							$response['message'] = 'Something went wrong';
							echo json_encode($response,true);
					}

		}	 
			
		
	}
	public function paymentMethods($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		
			// Instantiate a Braintree Gateway either like this:
			$gateway = new Braintree_Gateway([
			    'environment' => 'sandbox',
			    'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);

			$config = new Braintree_Configuration([
			    'environment' => 'sandbox',
			     'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);
				$result = $gateway->customer()->create([
			    'firstName' => 'Mike',
			    'lastName' => 'Jones',
			    'company' => 'Jones Co.',
			    'paymentMethodNonce' => 'tokencc_bh_858nzd_pptjpx_2cmsqb_qbjnr8_5p3'
			]);
			if ($result->success) {
			    echo($result->customer->id);
			    echo($result->customer->paymentMethods[0]->token);
			} else {
			    foreach($result->errors->deepAll() AS $error) {
			        echo($error->code . ": " . $error->message . "\n");
			    }
			}
			print_r($result);die;
			die;
			$result = $gateway->transaction()->sale([
				    'amount' => $post_data['amount'],
				    'paymentMethodNonce' => $post_data['payment_method_nonce']
				    
				]); 
			if($result->success == '1'){

				$response['status'] = 200;
				$response['message'] = 'Transaction made sucessfully';
				$response['trancation_id'] = $result->transaction->id;
				
				echo json_encode($response,true);
			}else{

				$response['status'] = 400;
				$response['message'] = 'Something went wrong';
				
				echo json_encode($response,true);
			}
		
	}
	public function paymentMethodsNonce($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		
			// Instantiate a Braintree Gateway either like this:
			$gateway = new Braintree_Gateway([
			    'environment' => 'sandbox',
			    'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);

			$config = new Braintree_Configuration([
			    'environment' => 'sandbox',
			     'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);
				$result = $gateway->paymentMethodNonce()->create('jyk4w5');
			
			print_r($result);die;
			die;
			$result = $gateway->transaction()->sale([
				    'amount' => $post_data['amount'],
				    'paymentMethodNonce' => $post_data['payment_method_nonce']
				    
				]); 
			if($result->success == '1'){

				$response['status'] = 200;
				$response['message'] = 'Transaction made sucessfully';
				$response['trancation_id'] = $result->transaction->id;
				
				echo json_encode($response,true);
			}else{

				$response['status'] = 400;
				$response['message'] = 'Something went wrong';
				
				echo json_encode($response,true);
			}
		
	}
	public function DepositePayment($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		 $token = $post_data['token']; 
		   $app_user_id =  $this->userinfo($token);

		if ($app_user_id =='') {

			 	$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Alreday  Logged In';
				$json_response = json_encode($response);
				echo $json_response;
				header('HTTP/1.1 401 Unauthorized');
				exit();
		}else{
			$order_id = $post_data['order_id'] ;
			$Cart_details = $this->home_model->getUserCart_deposite($app_user_id ,$order_id);
			$gateway = new Braintree_Gateway([
			    'environment' => 'sandbox',
			    'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);

			$config = new Braintree_Configuration([
			    'environment' => 'sandbox',
			     'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);

					$query  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
				$user_data = $query->row();
				$result = $gateway->customer()->create([
												    'firstName' => $user_data->app_user_first_name,
												    'lastName' => $user_data->app_user_last_name,
												    'company' => $user_data->bussiness_name,
												    'paymentMethodNonce' => $post_data['payment_method_nonce']
												]);
				foreach ($Cart_details as $key => $value) {
									$data_update = array(	
												'security_deposite_token_braintree'=>$result->customer->paymentMethods[0]->token
												);
								///	print_r($data_update);
									$this->common_model->UpdateRecord($data_update , 'ks_user_gear_rent_details' , 'user_gear_rent_detail_id', $value['user_gear_rent_detail_id']);

							}	
				//print_r($result);die;
					if ($result->success) {
							
							
					    
					    	$token_details = array(

									    	//'braintree_customer_id'=>$result->customer->id ,
									    	'braintree_token'=>$result->customer->paymentMethods[0]->token

									    	);

						    $this->common_model->UpdateRecord($token_details , 'ks_users' , 'app_user_id', $user_data->app_user_id);
						   	$response['status'] = 200;
							$response['message'] = 'Transaction made sucessfully';
							$response['trancation_id'] = '';
							echo json_encode($response,true);
							exit;
					}else{
							$response['status'] = 404;
							$response['message'] = 'Something Went wrong';
							$response['trancation_id'] = '';
							echo json_encode($response,true);
							exit;

					}
		}		
			// Instantiate a Braintree Gateway either like this:
			
			
			// $result = $gateway->transaction()->sale([
			// 	    'amount' => $post_data['amount'],
			// 	    'paymentMethodNonce' => $post_data['payment_method_nonce'],
			// 	]); 
		
		
	}

	public function Settlepayment($value='')
	{
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		
			// Instantiate a Braintree Gateway either like this:
			$gateway = new Braintree_Gateway([
			    'environment' => 'sandbox',
			    'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);

			$config = new Braintree_Configuration([
			    'environment' => 'sandbox',
			     'merchantId' => 'zmdpynpfyrtg74pn',
			    'publicKey' => '5mx3mprhdtxkw97z',
			    'privateKey' => 'c446fcb32c6975c9d222fd572e9dbdff'
			]);
			
			$result = $gateway->transaction()->submitForSettlement('e24ppw7t');
		
			if ($result->success) {
			    $settledTransaction = $result->transaction;
			} else {
			    print_r($result->errors);
			} 
			if($result->success == '1'){

				$response['status'] = 200;
				$response['message'] = 'Transaction made sucessfully';
				$response['trancation_id'] = $result->transaction->id;
				
				echo json_encode($response,true);
			}else{

				$response['status'] = 400;
				$response['message'] = 'Something went wrong';
				
				echo json_encode($response,true);
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
		if($query->num_rows()>0){
					$row = $query->result_array();
					
					$app_user_id = $row[0]['app_user_id'];
					
					return $app_user_id;
		}else{
					$app_user_id = '';
					$response['status'] = 401;
					$response['status_message'] = 'User Alreday  Logged In';
					$json_response = json_encode($response);
					echo $json_response;
					header('HTTP/1.1 401 Unauthorized');
					exit();
		}

		
	}
	

}?>
