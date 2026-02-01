<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter PDF Library
 *
 * Generate PDF's in your CodeIgniter applications.
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Chris Harvey
 * @license			MIT License
 * @link			https://github.com/chrisnharvey/CodeIgniter-PDF-Generator-Library
 */


function gen_pdf($file,$orderid){

require_once("dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();
				// $customPaper = array(0,0,360,360);
				// $dompdf->set_paper($customPaper);	
				$dompdf->set_paper('A4', 'portrait');
				$dompdf->load_html($file);
				$dompdf->render();
				$time=time();
				file_put_contents(UPLOAD_IMAGES.'admin/uploads/invoices/'.$orderid.'.pdf', $dompdf->output());
				// echo UPLOAD_IMAGES.'admin/uploads/invoices/'.$orderid.'.pdf';die;
				return  BASE_URL.'admin/uploads/invoices/'.$orderid.'.pdf' ;

}