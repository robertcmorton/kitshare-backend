<?php
class Common_model extends CI_Model {

	public function __construct() {

		parent::__construct();
	}
	
	public function countAll($table_name,$where_clause) {
		if($where_clause != '')
			$this->db->where($where_clause);
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get();  
		$tot_rec = $query->num_rows();
		//echo $this->db->last_query();
		return $tot_rec;
	} // end of countAll
	
	
	public function GetAllWhere($table_name,$where_clause) {
		if($where_clause != '')
			$this->db->where($where_clause);
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get();  
		
		
		return $query;
	} // end of countAll
	
	public function GetPrivID($table_name,$where_clause) {
		if($where_clause != '')
			$this->db->where($where_clause);
		$this->db->select('app_priv_id');
		$this->db->from($table_name);
		$query = $this->db->get();  
		
		
		return $query;
	}
	
		
  public function checkUser($email) {
		$sql = "SELECT * FROM tbl_registered_user WHERE (email='".$email."') " ;
		$query = $this->db->query($sql);
		return $query;
	}	
		
    public function checkUserLogin($username,$password) {
		$sql = "SELECT * FROM tbl_registered_user WHERE (email='".$username."') AND reg_password='".$password."' " ;
		$query = $this->db->query($sql);
		return $query;
	}
	
		
	public function get_all_record($table_name,$where_array){
		$res=$this->db->get_where($table_name,$where_array);
		return $res->result();
	}

	public function delfn($table_name, $id){
		$this->db->where('id', $id);
		$this->db->delete($table_name);
	}
		
	
	
	public function get_records_from_sql($sql)
	{
		//$sql = "SELECT * FROM ".$table_name." WHERE id=".$id; 
		$query = $this->db->query($sql);
		return $query->result();
		
	}
	
	
	public function count_records($table_name,$where_clause,$field,$fieldVal) {
		$where_clause .= " AND ".$field." = '".$fieldVal."'";
		//echo $where_clause;
		//exit;
		//$this->db->order_by($order_by_fld,$order_by);
		//$this->db->limit($limit,$offset);
		if($where_clause!='')
		$this->db->where($where_clause);
        //$this->db->distinct();		
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		//return $query; 
        return $query->num_rows();
	} // end of get_all_records
	
	
	public function get_all_distinct_records($table_name,$field) {
        $this->db->distinct();		
		$this->db->select($field);
        $this->db->order_by($field, "asc");
		$this->db->from($table_name);
		$query = $this->db->get();
		//echo $this->db->last_query();
		//exit;
		return $query; 

	} // end of get_all_records
	##--------------------------------@1st-October-2015-SOM-Starts------------------------------##
	public function get_all_records($table_name, $where_clause='',$order_by_fld='',$order_by='',$offset='',$limit='') {
		if($order_by_fld <> '' && $order_by <> '' ){
			$this->db->order_by($order_by_fld,$order_by);
		}
		//if($offset <> '' && $limit <> '' ){
			$this->db->limit($limit,$offset);
		//}
		if($where_clause!='')
		$this->db->where($where_clause);
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get();
		return $query; 
	}	
	##--------------------------------@1st-October-2015-SOM-Ends-------------------------------##
	public function get_skills_records($table_name) {
	
		$this->db->order_by('skill_name','ASC');
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get();
		return $query; 

	} // end of get_all_records


	public function Retrive_Record_By_Where_Clause($table,$where_clause) {
		$this->db->select('*');
		$this->db->from($table);
		if(!empty($where_clause))
		$this->db->where($where_clause);
		$query = $this->db->get();
		$row = $query->row_array();
		return $row;
		//$row = $query->row_array();
		//echo $this->db->last_query();
		//print_r($query);
		return $query;
		//echo $query->num_rows;
	}
	
	public function Retrive_Record_By_Where_Clause1($table,$where_clause) {
		$this->db->select('*');
		$this->db->from($table);
		if(!empty($where_clause))
		$this->db->where($where_clause);
		$query = $this->db->get();
		return $query;
		//$row = $query->row_array();
		//echo $this->db->last_query();
		//print_r($query);
		//return $query;
		//echo $query->num_rows;
	}	
	
	
	public function get_all_records_cms($table_name, $where_clause,$order_by_fld,$order_by,$offset,$limit) {
		$this->db->order_by($order_by_fld,$order_by);
		$this->db->limit($limit,$offset);
		if($where_clause!='')
		$this->db->where($where_clause);
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get();  
		return $query; 

	} // end of get_all_records
	public function getallrecords($table_name) {
		$this->db->order_by("id","desc");
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get();  
		return $query; 

	} // end of getallrecords
	
	public function getallrecords1($table_name) {
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get();  
		return $query; 

	} // end of getallrecords

	public function getrecord($table_name,$where_clause) {
		$this->db->order_by("countryId","desc");
		$this->db->where("countryId = '$id'");
		$this->db->select('*');
		$this->db->from($table_name);
		$query = $this->db->get(); 
		$row   = $query->result();
		return $row; 

	} // end of getallrecords
	
	public function getComment($table_name,$id) {
		$this->db->order_by("id","desc");
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('id',$id);
		$query = $this->db->get();  
		return $query; 

	} // end of getNewsComment	

	
	function Add_Record($row,$table) {
		$str = $this->db->insert_string($table, $row);        
		$query = $this->db->query($str);    
		$insertid = $this->db->insert_id();
		//echo $this->db->last_query(); exit;
		return $insertid;
	
	}	// end of Add_Record
	
	
	function Update_Record($row,$table,$id)
	{
		$this->db->where('reg_user_id', $id);
		$flag = $this->db->update($table, $row);
		return $flag;
		//echo $this->db->last_query(); exit;
	}
	
	function Update_Rcrd($row,$table,$id)
	{
		$this->db->where('uid', $id);
		$flag = $this->db->update($table, $row);
		return $flag;
		//echo $this->db->last_query(); exit;
	}
	
	
	public function Update_Record1($row_data,$table)
	{
		//$this->db->where('id', $id);
		//print_r($row_data);
		$flag = $this->db->update($table, $row_data);
		return $flag;
		//echo $this->db->last_query(); exit;
	}
	
	
	function Retrive_Record($table,$id) {
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('id', addslashes($id));
		$query = $this->db->get();
		$row = $query->row_array();
		$cnt = $query->num_rows ();
		//echo $cnt; 
		if($cnt>0){
		return $row;
		}
		
	} 
	
			
	
	public function getAllNews($table_name) {
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->order_by("id","desc");
		$this->db->limit('9');


		$query = $this->db->get(); 
		return $query; 

	} // end of getallrecords	
	
	function get_all_country() {
		
		$orderby_field = "printable_name";    
		$orderby = "ASC";
		$this->db->select("*");
		$this->db->from(COUNTRY);
		$this->db->order_by($orderby_field,$orderby);		
		$query = $this->db->get(); 
		//echo $this->db->last_query(); 
		return $query;
	
	}  // end of get_all_country
	
	function insert_record($table,$row) {
		
		$this->db->insert($table, $row); 
		   
		$insertid = $this->db->insert_id();
		//echo $this->db->last_query(); exit;
		return $insertid;
	
	}	// end of Add_Record
	
	
	
	public function get_Details($table_name,$id) {
			
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('id',$id);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query; 

	} // end of get_all_records	
	
	

	function dispbanner(){
		$orderby_field = "order";    
		$orderby = "ASC";
		$this->db->select("*");
		$this->db->where("is_active = '1'");
		$this->db->from(HOMEPAGEBANNER);
		$this->db->order_by($orderby_field,$orderby);		
		$query = $this->db->get();
		//echo $this->db->last_query(); 
		$result=$query->result();
		foreach($result as $row_homepagebanner){
			
			$target = "_parent";
			if($row_homepagebanner->open_option == '2') $target = "_blank";
			echo anchor($row_homepagebanner->link_url,img(array("src"=>FLD_HOMEPAGEBANNER.$row_homepagebanner->banner,"height"=>"338px", "width"=>"947px", "alt"=>$row_homepagebanner->alt_tag, "border"=>"0")),array("target"=>$target, "title"=>$row_homepagebanner->title_tag));

		}
	}
	

	function addRecord($table,$row) {
	
		if(is_array($row)) {
			foreach($row as $key=>$val) {
				$row[$key] = $val;
			}
		}
		
		$str = $this->db->insert($table, $row);   
		//echo $this->db->last_query();
		$insertid = $this->db->insert_id();
		//exit;
		return $insertid;
	
	}	
	

	
	
	public function record_change_id($table,$where_clause,$order_by_fld,$order_by,$offset,$limit) {
		$id="";
		$this->db->order_by($order_by_fld,$order_by);
		$this->db->limit($limit,$offset);
		$this->db->where($where_clause);
		$this->db->select('*');
		$this->db->from($table);
		$query = $this->db->get();
		//echo $this->db->last_query();
		foreach ($query->result() as $row){ 
		$id = $row->id;
		}
		//print_r($row); exit;
		if($id!=""){
		return $id; }
	}
	
		public function getallsecurityquestions($table_name) {
		$this->db->order_by("order","asc");
		$this->db->select('*');
		$this->db->where("is_active = '1' ");
		$this->db->from($table_name);
		$query = $this->db->get();  
		return $query; 

	} // end of getall security question records
	public function getallsatest($table_name) {
		$this->db->order_by("test_id","desc");
		$this->db->select('*');
		$this->db->where("is_active = '1' ");
		$this->db->from($table_name);
		$query = $this->db->get();  
		return $query; 

	} // end of get all test records
	
	
	
	
	function save_feedback($name,$mail,$feed_message)
    {
     //$sql="select * from sff_feedback where feed_email ='".$mail."'"; 
     // $query = $this->db->query($sql);
      $sql_insert="INSERT INTO ".FEEDBACK." SET feed_title ='".$name."',feed_email ='".$mail."',feed_message ='".$feed_message."',feed_date= '".date('Y-m-d')."'";
       $this->db->query($sql_insert);
       return true;
      
    }

	#Decription Function
	public function base64De($num,$val) {
		for($i=0; $i<$num; $i++) {
			$val = base64_decode($val);
		}
		
		return $val;
	}

	#Encryption Function
	public function base64En($num,$val) {
		for($i=0; $i<$num; $i++) {
			$val = base64_encode($val);
		}
		
		Return $val;
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

	public function getVerifyNumber($length)
	{		
		$random= "";
		$data1 = "";
		srand((double)microtime()*1000000);
		$data1 = "0123456789";
		$data1 .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for($i = 0; $i < $length; $i++)
		{
			$random .= substr($data1, (rand()%(strlen($data1))), 1);
		}
		return $random;
	} 

	
	

	function Update_Record_ColumnName($row,$table,$id,$column) {
		$this->db->where($column, $id);
		$this->db->update($table, $row);
	}
	function UpdateRecord_2($row,$table,$idfld,$id)
	{
		$this->db->where($idfld, $id);
		$query = $this->db->update($table, $row);
		return $query;
	}
	
	
	
	
	
	public function Email_exists($email) {
		$sql = "SELECT * FROM ".USER." WHERE email='".$email."'" ;
		$query = $this->db->query($sql);
		$row = $query->num_rows(); 
		//echo $this->db->last_query();
		return $row;
	} // end of Retrive_User
		
	public function userEmailExists($email) {
		$sql = "SELECT * FROM tbl_registered_user WHERE email='".$email."'" ;
		$query = $this->db->query($sql);
		$row = $query->num_rows(); 
		//echo $this->db->last_query();
		return $row;
	} // end of Retrive_User
	
	public function catalog($url){

		$doc = new DOMDocument();
		$path = $url;
		$doc->load($path);//xml file loading here
	
		$data = $doc->getElementsByTagName('item');
	
		return $data;
	}
	public function SubWord($str_content,$num_of_words_to_show,$symbol_to_more='.',$num_of_symbols=3) {
	   $subword_output ='';
	   $str_arr_num=0;
	   $smbl_to_mr = '';
	   $str_arr = explode(' ',$str_content);
	   for($str_arr_num=0;$str_arr_num<$num_of_words_to_show;$str_arr_num++){
		$subword_output = $subword_output.$str_arr[$str_arr_num].' ';
	   }
	   for($smbl_num=0;$smbl_num<$num_of_symbols;$smbl_num++){
		$smbl_to_mr = $smbl_to_mr.$symbol_to_more;
	   }
	   $subword_output = $subword_output.$smbl_to_mr;
	   return $subword_output; 
	}
	
	
	public function delele($tbl,$feild,$field_value){
	
	
		$this->db->delete($tbl, array($feild => $field_value)); 
	
	
	
	}
	public function get_all_record_page($table_name,$table_name2,$id){
        $cms_page_id=$id;

		$this->db->select('a.cms_page_id,b.page_code,a.created_date,a.content,b.page');
		$this->db->from('ks_page_content as a');
		$this->db->join('ks_cms_pages as b', 'a.cms_page_id = b.cms_page_id');
		$this->db->where('a.cms_page_id', $cms_page_id);
		$query = $this->db->get();
		
		
		return $query->result();
	}

	
	public function Delete($table_name, $id, $idfld){
		$this->db->where($idfld, $id);
		$this->db->delete($table_name);
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
			//  echo "cURL Error #:" . $err;
			} else {
			//  echo $response;
			}

	}
	

}
//end of class
?>