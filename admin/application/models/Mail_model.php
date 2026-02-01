<?php
class Mail_model extends CI_Model {

	public function __construct() {
	
		parent::__construct();
	}
	
	public function mail_content($mailContent,$title='') {
	
		$msg = '<html>
		<head>
		<style>
		table {
			display: table;
			border-collapse: separate;
			border-spacing: 0;
			border: 0;
			background:#f1f1f1;
		} 
		</style>
		</head>
		
		<body>
		<p>&nbsp;</p>
		<table width="700px;" align="center" border="0" cellpadding="0" cellspacing="2">
		  <tr style="background:#000000; color: #ffff;">
			<td style="padding:20px;"><a href="'.base_url().'" target="_blank"><img src="'.base_url().'assets/images/logo-light-text.png" style="border:none; height:80px; width: 150px;" /></a></td>
			<td style="padding:20px;" width="200"> <div align="right"></div></td>
		  </tr>
		  <tr>
			<td colspan="2" style="padding:20px;">
			<div style="width:100%; text-align:center; color:#333333; border-bottom:1px solid #cccccc;">
			
			</div>
			<div style="font: normal 12px Arial, Helvetica, sans-serif; color:#666;">
		
		   <p style="font-size:18px; line-height:30px; text-align:center;">'.$mailContent.'</p>
			
			 
		</div>    </td>
		  </tr>
		
		  
		</table>
		<p>&nbsp;</p>
		
		</body>
		</html>
		';
	
	return $msg;

	}

	public function OrderInvocies($url ='',$data= '')
	{	
		
		$msg= '
				<style>
					body { font-family: DejaVu Sans; }
				</style>
				<body style=" font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; width:700px">
				<table width="700px" border="0" style="margin:0 auto;">
				  <tr>
				    <td width="350px;"><img src="'.ROOT_PATH.'assets/images/pdf_logo.png" style="width:200px; margin-top:-50px;"></td>
				    <td width="350px"><h2 style="font-size:30px; text-align:left; text-transform:uppercase; font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif">TAX INVOICE</h2>
				      <table width="100%" border="0">
				        <tbody>
				          <tr>
				            <td class="table_head" style="font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; font-size:20px; color:#095cab;">REFERENCE:</td>
				            <td class="reff" style="font-size:15px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; text-align:right;">'.$data['six_digit_random_number'].'</td>
				          </tr>
				          <tr>
				            <td class="table_head"  style="font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; font-size:20px; color:#095cab;">BILLING DATE:</td>
				            <td class="reff" style="font-size:15px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; text-align:right;">'.date('d-m-Y').'</td>
				          </tr>
				          <tr>
				            <td class="table_head"  style="font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; font-size:20px; color:#095cab;">RENTAL DATES:</td>
				            <td class="reff" style="font-size:15px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; text-align:right;">09Nov-19Nov</td>
				          </tr>
				          <tr>
				            <td class="table_head"  style="font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; font-size:20px; color:#095cab;">OWNER:</td>
				            <td class="reff" style="font-size:15px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; text-align:right;">Robert Morton</td>
				          </tr>
				        </tbody>
				      </table></td>
				  </tr>
				</table>
				<table width="700px" border="0" style="margin:0 auto;">
				  <tr>
				    <td width="350px"><h2 style="color:#095cab; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", "Helvetica", Arial, sans-serif; border-bottom:2px solid #095cab; padding-bottom:10px;">OUR INFORMATION</h2>
				      <h3 style="margin:0; padding:0; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">Kitshare Pty Ltd</h3>
				      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">PO BOX 131</p>
				      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">Seaforth</p>
				      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">NSW 2092</p>
				      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">Australia</p>
				      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">ABN 85 623 435 709</p></td>';
				    if (!empty($data['addrsss'])) {
				      
				$msg .=     '<td width="350px"><h2 style="color:#095cab; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; border-bottom:2px solid #095cab; padding-bottom:10px;">BILLING TO</h2>
				      <h3  style="margin:0; padding:0; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">'.$data['addrsss']->bussiness_name.'</h3>
				      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">'.$data['addrsss']->app_user_first_name.$data['addrsss']->app_user_last_name.'</p>
				      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">'.$data['addrsss']->street_address_line1.$data['addrsss']->street_address_line2.'</p>
				      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">'.$data['addrsss']->suburb_name.$data['addrsss']->ks_state_name.'</p>
				      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">'.$data['addrsss']->postcode.'</p>
				      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;">'.$data['addrsss']->ks_country_name.'</p>
				     </td>';
				 	}

				$msg .=  '</tr>
				</table>
				<table class="table table-condensed" width="700px" style="margin:25px auto;" cellpadding="0" cellspacing="0">
				  <thead>
				    <tr>
				      <td width="300px" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Description </strong></td>
				      <td width="70px" class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>No Of Days </strong></td>
				      <td width="100px" class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Unit Price</strong></td>
				      <td width="70px" class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>GST</strong></td>
				      <td width="70px"class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd; text-align:right"><strong>Amount AUD</strong></td>
				    </tr>
				  </thead>
				  <tbody>
				  	' ;
					 $total_rent_amount_ex_gst = '0';
					 $gst_amount = '0';
					 $other_charges = '0';
					 $total_rent_amount = '0';
					 $total_rent_amount2 = '0';
					 $security_deposit = '0';
					  	$msg1  = '';
				  	foreach ($data['order_details'] as   $value) { 
				  		
				  		$msg1  .= '	<tr>
						      <td style="padding:5px 0px; border-bottom:1px solid #ddd">'.$value->gear_name.'</td>
						      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.$value->total_rent_days.'</td>
						      <td class="text-center" style="padding:10px 0px; border-bottom:1px solid #ddd">'.  number_format((float)$value->total_rent_amount_ex_gst, 2, '.', '').'</td>
						      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd">'. number_format((float)$value->gst_amount, 2, '.', '').'</td>
						      <td class="text-right" style="padding:10px 0px; border-bottom:1px solid #ddd;  text-align:right">'.number_format((float)$value->total_rent_amount, 2, '.', '').'</td>
						    </tr>' ;

						  $total_rent_amount_ex_gst  += $value->total_rent_amount_ex_gst ; 
			 			 $gst_amount  += $value->gst_amount; 
			 			 $other_charges  += $value->other_charges ; 
			 			 $total_rent_amount  += $value->total_rent_amount_ex_gst ; 
			 			 $total_rent_amount2  += $value->total_rent_amount ; 
			 			// $total_rent_amount  += $value['total_rent_amount'] ; 
			 			 $security_deposit +=   $value->security_deposit;   
				  	}	
					$beta_discount = ($total_rent_amount_ex_gst*15)/100 ; 
			 		$insurance_fee = ($total_rent_amount_ex_gst*10)/100 ; 
			 		$community_fee = ($total_rent_amount_ex_gst*5)/100 ; 
				    $msg .= $msg1;
				   $msg.=  '
				    <tr>
				      <td class="thick-line" colspan="4" style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Subtotal</strong></td>
				      <td class="thick-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right" colspan="4"  style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Discount(15%)</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'. number_format((float)$beta_discount, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right "  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Insurance Fee</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'. number_format((float)$insurance_fee, 2, '.', '') .'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Community Fee</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'. number_format((float)$community_fee, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL ex GST</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'. number_format((float)$total_rent_amount_ex_gst, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL GST 10%</strong></td>
				      <td class="no-line text-right" style="padding:10px 0px; text-align:right; font-size:15px;">'.number_format((float)$gst_amount, 2, '.', '').'</td>
				    </tr>
				    <tr>
				      <td class="no-line text-right"  colspan="4"  style="border-top:2px solid #000; padding-top: 10px; font-size:20px; font-weight:bolder; text-align:right"><strong>TOTAL AUD</strong></td>
				      <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder; text-align:right">'.number_format((float)$total_rent_amount2, 2, '.', '').'</td>
				    </tr>
				  </tbody>
				</table>
				<table width="700px" border="0" style="margin:0 auto;">
				  <tr>
				    <td><h2 style="color:#095cab; font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif; border-bottom:2px solid #095cab; padding-bottom:10px;">PAYMENT INFORMATION</h2>
				      <p>Subject to Kitshare ‘Terms and Conditions’ available at www.kitshare.com.au If you have any questions concerning this invoice, contact accounts at support@kitshare.com.au </p>
				      <p>Thank you for your buisness.</p></td>
				  </tr>
				</table>
				
				';
		
		return $msg;		
	}
	

	
	
} // end of class