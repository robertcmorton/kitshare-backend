<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'/third_party/jwt/vendor/autoload.php');
use \Firebase\JWT\JWT;

class Common_model extends CI_Model {

	public function __construct() {

		parent::__construct();
	}
	
	public function RetriveRecordByWhereRow($table,$where_clause) {
		$this->db->select('*');
		$this->db->from($table);
		if(!empty($where_clause))
		$this->db->where($where_clause);
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;
	}
	
	public function RetriveRecordByWhere($table,$where_clause) {
		$this->db->select('*');
		$this->db->from($table);
		if(!empty($where_clause))
		$this->db->where($where_clause);
		$query = $this->db->get();
		return $query;
	}
	
	public function RetriveRecordByWhereLimit($table,$where_clause,$limit,$orderbyfld,$orderby) {
		$this->db->select('*');
		$this->db->limit($limit);
		$this->db->from($table);
		if(!empty($where_clause))
		$this->db->where($where_clause);
		$this->db->order_by($orderbyfld, $orderby);
		$query = $this->db->get();
		return $query;
	}
	public function get_all_record($table_name,$where_array){
		$res=$this->db->get_where($table_name,$where_array);
		return $res->result();
	}
	
	function AddRecord($row,$table) {
		$str = $this->db->insert_string($table, $row);        
		$query = $this->db->query($str);    
		$insertid = $this->db->insert_id();
		return $insertid;
	}
	
	function UpdateRecord($row,$table,$idfld,$id)
	{
		$this->db->where($idfld, $id);
		$query = $this->db->update($table, $row);
		return $query;
	}
	
	public function GetAll($table_name){
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get(); 
		return $query;
	}
	
	public function Count($table_name) {
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get();  
		$tot_rec = $query->num_rows();
		return $tot_rec;
	}
	
	public function CountWhere($table_name,$where_clause) {
		$this->db->select('*');
		$this->db->where($where_clause);
		$this->db->from($table_name);
		$query = $this->db->get();  
		$tot_rec = $query->num_rows();
		return $tot_rec;
	}


	public function Delete($table_name, $id, $idfld){
		$this->db->where($idfld, $id);
		$this->db->delete($table_name);
	}
	public function get_records_from_sql($sql)
	{
		
		$query = $this->db->query($sql);
		return $query->result();
		
	}
	
	public function getRandomNumber($length)
	{
			
		$random= "";
		$data1 = "";
		srand((double)microtime()*1000000);
		$data1 = "9876549876542156012";
		$data1 .= "0123456789564542313216743132136864313";
		for($i = 0; $i < $length; $i++)
		{
			$random .= substr($data1, (rand()%(strlen($data1))), 1);
		}
		return $random;
	}
	public function GetAllWhere($table_name,$where_clause) {
		if($where_clause != '')
			$this->db->where($where_clause);
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get();  
		return $query;
	}
	
	public function RandomNumber($length='', $datetime = TRUE) 
	{
		$random = '';
		if(empty($length)===true) 
		{
			$random = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s');
		} 
		else 
		{				 
			srand((double)microtime()*1000000); 
			$data = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
			for($i = 0;$i < $length;$i++) 
			{
				$random.= substr($data,(rand()%(strlen($data))),1); 
			}
			if($datetime === TRUE) $random = $random."-".date("Ymd")."-".date("His");
		}
		return $random;
	}
	
	public function GetSpecificValues($columns,$table,$where_clause,$limit='',$orderbyfld='',$orderby=''){
		$this->db->select($columns);
		$this->db->from($table);
		
		if(!empty($where_clause))
			$this->db->where($where_clause);
		
		if(!empty($limit))
			$this->db->limit($limit);
		
		if($orderbyfld!="")
			$this->db->order_by($orderbyfld, $orderby);
		$query = $this->db->get();
		return $query;
	}
	public function InsertData($table,$data)
	{
		$this->db->insert($table,$data);
		//$query = $this->db->query($str);    
		$insertid = $this->db->insert_id();
		return $insertid;
	}

	/// Send Mail 
	public function sendMail($data)
	{
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.mailjet.com/v3.1/send",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => json_encode($data) ,
			  CURLOPT_HTTPHEADER => array(
			    //"Authorization: Basic MTE1MGEzYWZkMzg3MzM0ZjU1YmJiMWQ5MTI3YWE5Y2M6N2U3MzdkNjNiMWE1NDQ3NjgwNzNlZjVjOTlmMTQ2YmU=",
              	"Authorization: Basic YTU5YjgyZGFlZTViYjVhZjZiMDAzYTI0ZTk5NTQ4NTc6OGJkODJlODQ4MTJjMzg4MjZhNzNiZmM2OTlmZjkwNjE=",
			    "Content-Type: application/json",
			    "Postman-Token: 5dacc0e7-eff8-430b-ba53-b9a520abed72",
			    "cache-control: no-cache"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				return 1;
			}

	}
	
	/*public function sendMail($data)
	{
			$url = 'https://api.mailjet.com/v3.1/send';
			$username = '1150a3afd387334f55bbb1d9127aa9cc';
			$password = '7e737d63b1a544768073ef5c99f146be';
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
			 
			//Execute the cURL request.
			$response = curl_exec($ch);
			 
			//Check for errors.
			if(curl_errno($ch)){
				//If an error occured, throw an Exception.
				throw new Exception(curl_error($ch));
			}
			
	}*/
	
	/// function to check APP USER ID from provided token
	/*function userinfo($token){
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
		
		$query = $this->GetSpecificValues($columns,$table,$where_clause);
		$row = $query->result_array();
		if($query->num_rows()>0){
					$row = $query->result_array();
					$app_user_id = $row[0]['app_user_id'];
		}else{
					$app_user_id = '';
					
		}
		return $app_user_id;

		
	}*/
	
	//Revised function to fetch userinfo
	function userinfo($app_user_id,$token){

		$arr = array();
		
		$table = "ks_users";
		
		$where_clause = array('ks_users.app_user_id'=>$app_user_id,'ks_users.is_active'=>'Y','ks_user_activity_log.access_token'=>$token);
		
		$this->db->select('ks_users.*,ks_user_activity_log.activity_log_id');		
		$this->db->from($table);
		$this->db->join("ks_user_activity_log","ks_user_activity_log.app_user_id=ks_users.app_user_id");
		$this->db->where($where_clause);
		$query = $this->db->get();  		
		
		if($query->num_rows()>0){
			$arr = $query->result_array();				
			return $arr;
		}else{
			return $arr;
		}
		
	}
	
	
	//To fetch credentials using token
	public function fetchTokenDetails($token,$return='app_user_id',$login_type=''){
	
		if($return == "app_user_id")
			$userinfo = "";
		else	
			$userinfo = array();
		
		//Token is checked with the token stored in cookie
		$token_cookie = get_cookie("kitshare_access_token");
		
		
		//if($token == $token_cookie || strstr($_SERVER['HTTP_REFERER'],"http://localhost:4200/")==true){	
			
		$app_user_id = $this->decode_token($token,$login_type);
		
		if($return == 'app_user_id'){
			$userinfo = $app_user_id;			
		}else{			
			$userinfo = $this->userinfo($app_user_id,$token);
		}
		//}
		return $userinfo;
		
	}
	
	//Function to check device data
	public function checkDevice(){
	
		$this->load->library('user_agent');
		
		if ($this->agent->is_browser())
		{
				$data['browser'] = $this->agent->browser();
				$data['browser_version'] = $this->agent->version();
				if($this->agent->platform()=="Android" || $this->agent->platform()=="iPhone" || $this->agent->platform()=="iphone"){
					$data['device'] = "mobile";
					$data['device_type'] = $this->agent->platform();
				}else{
					$data['device'] = "web";
					$data['device_type'] = "";
				}
		}
		elseif ($this->agent->is_robot())
		{
				$data['browser'] = $this->agent->robot();
				$data['browser_version'] = "";
				$data['device'] = "";
				$data['device_type'] = "";
		}
		elseif ($this->agent->is_mobile())
		{
				$data['browser'] = $this->agent->mobile();
				$data['device'] = "mobile";
				$data['browser_version'] = "";
				
				if ($this->agent->is_mobile('iphone'))
				{
					$data['device_type'] = "iphone";
				}
				elseif ($this->agent->is_mobile())
				{
					$data['device_type'] = "other";
				}
		}
		else
		{
				$data['browser'] = 'Unidentified User Agent';
				$data['browser_version'] = "";
				$data['device'] = "";
				$data['device_type'] = "";
		}
		
		return $data;
	
	}
	
	public function fetchUserDetails($username){
	
			$where_clause = array("app_username"=>$username);
			$query = $this->common_model->RetriveRecordByWhere("ks_users",$where_clause);
			
			if($query->num_rows()>0){
				$row = $query->result_array();
				return $row;
			}else
				return 0;
	
	}
	
	public function create_token($app_user_id,$expire_time){
		
		$secretKey 	= SECRET_KEY;
		$issuedAt 	= time();
		$issued_nbf = $issuedAt + 2;		

		$payload = array(
			"iss" => WEB_URL_1,
			"aud" => WEB_URL_1,
			"iat" => $issuedAt,
			"nbf" => $issued_nbf,
			'data' => [                  // Data related to the signer user
					'userId'   => $app_user_id // userid from the users table
			]
		);
		
		if($expire_time!=""){
			$expire 	= $issued_nbf + $expire_time;
			$expire_arr = array('exp'=>$expire);
			$payload = array_merge($payload,$expire_arr);
		}

		$jwt = JWT::encode($payload, $secretKey);	
		return $jwt;
		
	}
	
	public function decode_token($token,$login_type){
		
		if($login_type=="login")
			sleep(2);
		
		$secretKey = SECRET_KEY;
		$msg = "";
		$user_id = "";
		
		try {
			
			$decoded = JWT::decode($token, $secretKey, array('HS256'));
			$user_id = $decoded->data->userId;			
			return $user_id;
			
		} catch (Exception $e) {
			
			//echo 'Caught exception: '.  $e->getMessage(). "\n";
			
			return $user_id;
			
		}

	}
	
	
	//Function to retrieve user_id 
	public function fetchUserId($app_username){
		
		$table = "ks_users";
		
		$arr = array();
		
		$where_clause = array('ks_users.app_username'=>$app_username,'ks_users.is_active'=>'Y');
		
		$this->db->select('ks_users.app_user_id');		
		$this->db->from($table);
		$this->db->where($where_clause);
		$query = $this->db->get();  		
		
		if($query->num_rows()>0){
			$arr = $query->row_array();				
			return $arr['app_user_id'];
		}else{
			return 0;
		}
		
		
	}

	
	
}
//end of class
?>