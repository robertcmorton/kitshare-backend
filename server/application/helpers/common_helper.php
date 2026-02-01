<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


   function getRandomName($filename){
       //get main CodeIgniter object
       $ci =& get_instance();
       
		$file_array = explode(".",$filename);
		$file_ext = end($file_array);
		$new_file_name = RandomNumber(10).".".$file_ext;
		return $new_file_name;    
   }

   function RandomNumber($length='', $datetime = TRUE) 
	{
		$rand = "";
		if(empty($length)===TRUE) 
		{
			$random = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s');
		} 
		else 
		{				 
			srand((double)microtime()*1000000); 
			$data = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
			for($i = 0;$i<$length;$i++) 
			{	

				$rand.= substr($data,(rand()%(strlen($data))),1);
			}
			if($datetime === TRUE) $random = $rand."-".date("Ymd")."-".date("His");
		}		
		return $random;
	}
	
	
	function send_email($sender, $to , $subject, $mail_body)
	 {
	  $ci =& get_instance();
	  $config['protocol'] = "smtp";
	  $config['smtp_host'] = "smtp.sendgrid.net";
	  $config['smtp_port'] = "587";
	  $config['smtp_user'] = "apikey"; 
	  $config['smtp_pass'] = "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";
	  //$config['protocol'] = 'sendmail';
	  $config['charset']  = 'utf-8';
	  $config['mailtype'] = 'html';
	  $config['newline']  = "\r\n";
	  $ci->email->initialize($config); 
	  $ci->email->from($sender, 'Kitshare');
	  $ci->email->to($to);
	  $ci->email->subject($subject);
	  $ci->email->message($mail_body);
	  $ci->email->send();
	 }
	
	
	
	


