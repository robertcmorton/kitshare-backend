<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sitemap extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','email','upload','pagination'));
		$this->load->model(array('common_model','model'));
	}

	//Listing URL for site Map
	/*public function index()
	{	
		$where ="WHERE e.is_active='Y' AND e.gear_hide_search_results ='Y' ";
		$nSer="SELECT e.* , b.model_name ,c.app_user_first_name,c.app_user_last_name ,c.app_username, gear_type.ks_gear_type_name,gear_name.gear_category_name FROM ks_user_gear_description e 
		LEFT JOIN ks_models b ON e.model_id = b.model_id
		Left JOIN ks_users c ON e.app_user_id = c.app_user_id 
		LEFT JOIN ks_gear_type AS gear_type ON e.ks_gear_type_id = gear_type.ks_gear_type_id
		LEFT JOIN ks_gear_categories As gear_name ON  e.ks_category_id = gear_name.gear_category_id
		".$where." ORDER BY e.user_gear_desc_id";
		
		$sql = $nSer;
		$query = $this->db->query($sql);	
		$result  = $query->result();
		$str ='<?xml version="1.0" encoding="UTF-8"?>
		<urlset
      			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            	http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

            	';
		foreach ($result as  $value) {
			 $siteurl = WEB_URL.'view-gear/'.$value->gear_slug_name;	
			$str .=	'<url>
						<loc>'.$siteurl.'</loc>
						<lastmod>'.gmdate("Y-m-d\TH:i:s").'</lastmod>
						<priority>1.00</priority>
				 	 </url>
					 	 ';
		}
		$str .= '</urlset>' ;
		
		$this->load->helper('download');
		$data = $str;
		$name = 'sitemap.xml';
		$content = $data;

		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/sitemap.xml","wb");
		fwrite($fp,$content);
		fclose($fp);
	}
	public function CMSsitemap()
	{
		$sql = "SELECT page,page_code,ks_cms_pages.cms_page_id FROM ks_cms_pages INNER JOIN ks_page_content ON ks_cms_pages.cms_page_id = ks_page_content.cms_page_id  ";
		$query = $this->db->query($sql);	
		
		$cms_details =  $query->result();
		$str ='<?xml version="1.0" encoding="UTF-8"?>
		<urlset
      			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            	http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

            	';
		foreach ($cms_details as $value) {
			$string =  str_replace(' ', '-', $value->page) ;
			$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
			$string =strtolower($string);
			
			$siteurl = WEB_URL.$string;	
			$str .=	'<url>
						<loc>'.$siteurl.'</loc>
						<lastmod>'.gmdate("Y-m-d\TH:i:s").'</lastmod>
						<priority>1.00</priority>
				 	 </url>
					 	 ';
		}
		$str .= '</urlset>' ;
		
		$this->load->helper('download');
		$data = $str;
		$name = 'sitemap.xml';
		force_download($name, $data);
	}

	//FAQ 

	public function SitemapFAQ()
	{
		
		$sql = "SELECT * FROM 	ks_faq_category WHERE status='y'  ";
		$query = $this->db->query($sql);	
		$faq_details =  $query->result();
		$str ='<?xml version="1.0" encoding="UTF-8"?>
		<urlset
      			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            	http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

            	';
		foreach ($faq_details as  $value) {
			$str .=	'<url>
						<loc>'.WEB_URL.'faq/list/'.$value->permalink.'</loc>
						<lastmod>'.gmdate("Y-m-d\TH:i:s").'</lastmod>
						<priority>1.00</priority>
				 	 </url>
					 	 ';
		}
		$sql = "SELECT * FROM 	ks_faq WHERE status='y'  ";
		$query = $this->db->query($sql);	
		$faq_details =  $query->result();
		foreach ($faq_details as  $value) {
			$str .=	'<url>
						<loc>'.WEB_URL.'faq/list/'.$value->permalink.'</loc>
						<lastmod>'.gmdate("Y-m-d\TH:i:s").'</lastmod>
						<priority>1.00</priority>
				 	 </url>
					 	 ';
		}
		$str .= '</urlset>' ;
		
		$this->load->helper('download');
		$data = $str;
		$name = 'sitemap.xml';
		force_download($name, $data);
	}*/

	public function index()
	{
		$where ="WHERE e.is_active='Y' AND e.gear_hide_search_results ='Y' ";
		$nSer="SELECT e.* , b.model_name ,c.app_user_first_name,c.app_user_last_name ,c.app_username, gear_type.ks_gear_type_name,gear_name.gear_category_name FROM ks_user_gear_description e 
		INNER JOIN ks_users c ON e.app_user_id = c.app_user_id 
		INNER JOIN ks_models b ON e.model_id = b.model_id		
		LEFT JOIN ks_gear_type AS gear_type ON e.ks_gear_type_id = gear_type.ks_gear_type_id
		LEFT JOIN ks_gear_categories As gear_name ON  e.ks_category_id = gear_name.gear_category_id
		".$where." ORDER BY e.user_gear_desc_id";
		
		
		$sql = $nSer;
		$query = $this->db->query($sql);	
		$result  = $query->result();
		$str ='<?xml version="1.0" encoding="UTF-8"?>
		<urlset
      			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            	http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
            	<url>
					<loc>'.WEB_URL.'</loc>
						<lastmod>'.date("Y-m-d").'</lastmod>
						<priority>1.00</priority>
				 	</url>

            	';
		foreach ($result as  $value) {
			 $siteurl = WEB_URL.'view-gear/'.$value->gear_slug_name;	
			$str .=	'<url>
						<loc>'.$siteurl.'</loc>
						<lastmod>'.date("Y-m-d").'</lastmod>
						<priority>1.00</priority>
				 	 </url>
					 	 ';
		}
		
		//Profile URL
		$sql = "SELECT app_username FROM `ks_users` WHERE  `is_active` ='Y' AND `is_blocked`='N'";
		$query = $this->db->query($sql);	
		
		$profile =  $query->result();
		
		foreach ($profile as  $value) {
			 $siteurl = WEB_URL.'profile/'.$value->app_username;	
			$str .=	'<url>
						<loc>'.$siteurl.'</loc>
						<lastmod>'.date("Y-m-d").'</lastmod>
						<priority>1.00</priority>
				 	 </url>
					 	 ';
		}
		
		
		$sql = "SELECT page,page_code,ks_cms_pages.cms_page_id FROM ks_cms_pages INNER JOIN ks_page_content ON ks_cms_pages.cms_page_id = ks_page_content.cms_page_id  ";
		$query = $this->db->query($sql);	
		
		$cms_details =  $query->result();
		foreach ($cms_details as $value) {
			$string =  str_replace(' ', '-', $value->page) ;
			$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
			$string =strtolower($string);
			
			$siteurl = WEB_URL.$string;	
			$str .=	'<url>
						<loc>'.$siteurl.'</loc>
						<lastmod>'.date("Y-m-d").'</lastmod>
						<priority>1.00</priority>
				 	 </url>
					 	 ';
		}

		$sql = "SELECT * FROM 	ks_faq_category WHERE status='y'  ";
		$query = $this->db->query($sql);	
		$faq_details =  $query->result();
		foreach ($faq_details as  $value) {
			$str .=	'<url>
						<loc>'.WEB_URL.'faq/list/'.$value->permalink.'</loc>
						<lastmod>'.date("Y-m-d").'</lastmod>
						<priority>1.00</priority>
				 	 </url>
					 	 ';
		}
		$sql = "SELECT * FROM 	ks_faq WHERE status='y'  ";
		$query = $this->db->query($sql);	
		$faq_details =  $query->result();
		foreach ($faq_details as  $value) {
			$str .=	'<url>
						<loc>'.WEB_URL.'faq/list/'.$value->permalink.'</loc>
						<lastmod>'.date("Y-m-d").'</lastmod>
						<priority>1.00</priority>
				 	 </url>
					 	 ';
		}
		$str .= '</urlset>' ;
		
		//$this->load->helper('download');
		$data = $str;
		$name = 'sitemap.xml';
		//$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/sitemap.xml","wb");
		$fp = fopen(SITEMAP_PATH."/sitemap.xml","wb");
		fwrite($fp,$data);
		fclose($fp);

		//force_download($name, $data);
	}
}

?>	