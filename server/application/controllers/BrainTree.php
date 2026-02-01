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
		// error_reporting(0);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
				$query =  $this->common_model->GetAllWhere('ks_settings',array() );
				$settings = $query->row();		
						
				// Instantiate a Braintree Gateway either like this:
				
				$gateway = new Braintree_Gateway([
									'environment' => BRAINTREE_ENVIORMENT,
							    'merchantId' => $settings->braintree_merchant_id,
							    'publicKey' => $settings->braintree_public_key,
							    'privateKey' => $settings->braintree_private_key
				]);

				$config = new Braintree_Configuration([
      								'environment' => BRAINTREE_ENVIORMENT,
								    'merchantId' => $settings->braintree_merchant_id,
								    'publicKey' => $settings->braintree_public_key,
								    'privateKey' => $settings->braintree_private_key
				]);
				 $clientToken = $gateway->clientToken()->generate();		

						$response['status_code'] = 200;
						$response['result'] = $clientToken;

						
					echo json_encode($response);			
		
	}

	public function Payment2($value='')
	{
		//error_reporting(0);
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		
			// Instantiate a Braintree Gateway either like this:
				$query =  $this->common_model->GetAllWhere('ks_settings',array() );
				$settings = $query->row();		
				
				// Instantiate a Braintree Gateway either like this:
				
				$gateway = new Braintree_Gateway([
									'environment' => BRAINTREE_ENVIORMENT,
							    'merchantId' => $settings->braintree_merchant_id,
							    'publicKey' => $settings->braintree_public_key,
							    'privateKey' => $settings->braintree_private_key
				]);

				$config = new Braintree_Configuration([
      								'environment' => BRAINTREE_ENVIORMENT,
								    'merchantId' => $settings->braintree_merchant_id,
								    'publicKey' => $settings->braintree_public_key,
								    'privateKey' => $settings->braintree_private_key
				]);
			
			$result = $gateway->transaction()->sale([
				    'amount' => $post_data['amount'],
				    'paymentMethodNonce' => $post_data['payment_method_nonce']
				    
				]); 
			//print_r($result);
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
		//error_reporting(0);
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		 $token = $post_data['token']; 
		   $app_user_id =  $this->userinfo($token);

		if ($app_user_id =='') {

			 	$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;
				//header('HTTP/1.1 401 Unauthorized');
				exit();
		}else{
				$Cart_details = $this->home_model->getUserCart1($app_user_id);
				
				$query =  $this->common_model->GetAllWhere('ks_settings',array() );
				$settings = $query->row();		
				
				// Instantiate a Braintree Gateway either like this:
				
				$gateway = new Braintree_Gateway([
								'environment' => BRAINTREE_ENVIORMENT,
							    'merchantId' => $settings->braintree_merchant_id,
							    'publicKey' => $settings->braintree_public_key,
							    'privateKey' => $settings->braintree_private_key
				]);

				$config = new Braintree_Configuration([
      								'environment' => BRAINTREE_ENVIORMENT,
								    'merchantId' => $settings->braintree_merchant_id,
								    'publicKey' => $settings->braintree_public_key,
								    'privateKey' => $settings->braintree_private_key
				]);
				$query  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
				$user_data = $query->row();
				if ( $user_data->show_business_name =='Y') {
					$user_data->app_user_first_name = $user_data->bussiness_name;
					$user_data->app_user_last_name = '';
				}
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
		//error_reporting(0);
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		
			// Instantiate a Braintree Gateway either like this:
			$query =  $this->common_model->GetAllWhere('ks_settings',array() );
				$settings = $query->row();		
				
				// Instantiate a Braintree Gateway either like this:
				
				$gateway = new Braintree_Gateway([
									'environment' => BRAINTREE_ENVIORMENT,
							    'merchantId' => $settings->braintree_merchant_id,
							    'publicKey' => $settings->braintree_public_key,
							    'privateKey' => $settings->braintree_private_key
				]);

				$config = new Braintree_Configuration([
      								'environment' => BRAINTREE_ENVIORMENT,
								    'merchantId' => $settings->braintree_merchant_id,
								    'publicKey' => $settings->braintree_public_key,
								    'privateKey' => $settings->braintree_private_key
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
		//error_reporting(0);
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		
			// Instantiate a Braintree Gateway either like this:
			$query =  $this->common_model->GetAllWhere('ks_settings',array() );
			$settings = $query->row();		
			
			// Instantiate a Braintree Gateway either like this:
			
			$gateway = new Braintree_Gateway([
								'environment' => BRAINTREE_ENVIORMENT,
						    'merchantId' => $settings->braintree_merchant_id,
						    'publicKey' => $settings->braintree_public_key,
						    'privateKey' => $settings->braintree_private_key
			]);

			$config = new Braintree_Configuration([
  								'environment' => BRAINTREE_ENVIORMENT,
							    'merchantId' => $settings->braintree_merchant_id,
							    'publicKey' => $settings->braintree_public_key,
							    'privateKey' => $settings->braintree_private_key
			]);
				$result = $gateway->paymentMethodNonce()->create('gngvq44');
			
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
		//error_reporting(0);
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		 $token = $post_data['token']; 
		   $app_user_id =  $this->userinfo($token);

		if ($app_user_id =='') {

			 	$app_user_id = '';
				$response['status'] = 401;
				$response['status_message'] = 'User Already logged in';
				$json_response = json_encode($response);
				echo $json_response;
				//header('HTTP/1.1 401 Unauthorized');
				exit();
		}else{
			$order_id = $post_data['order_id'] ;
			$Cart_details = $this->home_model->getUserCart_deposite($app_user_id ,$order_id);
			$query =  $this->common_model->GetAllWhere('ks_settings',array() );
			$settings = $query->row();		
			// Instantiate a Braintree Gateway either like this:
			
			$gateway = new Braintree_Gateway([
								'environment' => BRAINTREE_ENVIORMENT,
						    'merchantId' => $settings->braintree_merchant_id,
						    'publicKey' => $settings->braintree_public_key,
						    'privateKey' => $settings->braintree_private_key
			]);

			$config = new Braintree_Configuration([
  								'environment' => BRAINTREE_ENVIORMENT,
							    'merchantId' => $settings->braintree_merchant_id,
							    'publicKey' => $settings->braintree_public_key,
							    'privateKey' => $settings->braintree_private_key
			]);

					$query  =  $this->common_model->GetAllWhere('ks_users',array('app_user_id'=>$app_user_id));
				$user_data = $query->row();
				if ( $user_data->show_business_name =='Y') {
					$user_data->app_user_first_name = $user_data->bussiness_name;
					$user_data->app_user_last_name = '';
				}
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
		//error_reporting(0);
		$post_data  = json_decode(file_get_contents("php://input"),true);
		if (version_compare(PHP_VERSION, '5.4.0', '<')) {
		    throw new Braintree_Exception('PHP version >= 5.4.0 required');
		}
		
			// Instantiate a Braintree Gateway either like this:
			$query =  $this->common_model->GetAllWhere('ks_settings',array() );
			$settings = $query->row();		
			
			// Instantiate a Braintree Gateway either like this:
			
			$gateway = new Braintree_Gateway([
								'environment' => BRAINTREE_ENVIORMENT,
						    'merchantId' => $settings->braintree_merchant_id,
						    'publicKey' => $settings->braintree_public_key,
						    'privateKey' => $settings->braintree_private_key
			]);

			$config = new Braintree_Configuration([
  								'environment' => BRAINTREE_ENVIORMENT,
							    'merchantId' => $settings->braintree_merchant_id,
							    'publicKey' => $settings->braintree_public_key,
							    'privateKey' => $settings->braintree_private_key
			]);
			
			$result = $gateway->transaction()->submitForSettlement('73gqdsq1' ,'100');
		
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
		
		$app_user_id = $this->common_model->fetchTokenDetails($token);
		return $app_user_id;		
	}
	

}?>
