<!doctype html>
<html>
<head>
<?php  
    /*if ($order_details[0]->owner_app_show_business_name  =='Y') {
      $order_details[0]->app_user_first_name =$order_details[0]->owner_app_bussiness_name   ;
      $order_details[0]->app_user_last_name =''  ;
    }
    if ($order_details[0]->renter_app_show_business_name  =='Y') {
      
      $order_details[0]->renter_firstname =$order_details[0]->renter_app_bussiness_name  ;
      $order_details[0]->renter_lastname = '' ;
    }*/

?>
<meta charset="utf-8">
<title>kitshare</title>
</head>

<body  style="font-family: 'Roboto', sans-serif;">
<table width="550" border="0" style="margin:0 auto;" cellspacing="0" cellpadding="0">
  <tr>
    
    <td  width="300"><img src="<?php echo BASE_URL ;?>admin/assets/images/pdf_logo.png" style="width:300px; margin-top:-50px;"></td>
    <td width="250"><h2>TAX INVOICE</h2>
  
  
      <table width="200" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td class="table_head" style="font-family:'Roboto', sans-serif; font-size:13px; color:#095cab;">REFERENCE:</td>
            <td class="reff" style="font-size:11px; font-family:'Roboto', sans-serif; text-align:left;"><?php echo $six_digit_random_number; ?></td>
          </tr>
          <tr>
            <td class="table_head"  style="font-family:'Roboto', sans-serif; font-size:13px; color:#095cab;">BILLING DATE:</td>
            <td class="reff" style="font-size:11px; font-family:'Roboto', sans-serif; text-align:left;"><?php echo date('d-M-Y'); ?></td>
          </tr>
          <tr>
            <td class="table_head"  style="font-family:'Roboto', sans-serif; font-size:13px; color:#095cab;">RENTAL DATES:</td>
            <td class="reff" style="font-size:12px; font-family:'Roboto', sans-serif; text-align:left;"><?php echo date('d-M-Y',strtotime($order_details[0]->gear_rent_start_date)) .' -'. date('d-M-Y',strtotime($order_details[0]->gear_rent_end_date)) ?></td>
          </tr>
          <tr>
            <td class="table_head"  style="font-family:'Roboto', sans-serif; font-size:13px; color:#095cab;">OWNER:</td>
            <td class="reff" style="font-size:12px; font-family:'Roboto', sans-serif; text-align:left;"><?php echo $order_details[0]->app_user_first_name .' ' .$order_details[0]->app_user_last_name; ?></td>
          </tr>
        </tbody>
      </table></td>
  </tr>
</table>
<table width="700" border="0" style="margin:0 auto;" cellspacing="0" cellpadding="0">
  <tr>
    <td width="300" style="vertical-align: top;">
      <h2 style="color:#095cab; font-family:'Roboto', sans-serif; border-bottom:2px solid #095cab; padding-bottom:10px;">OUR INFORMATION</h2>
      <h3 style="margin:0; padding:0; font-family:'Roboto', sans-serif;">Kitshare Pty Ltd</h3>
      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:'Roboto', sans-serif;">PO BOX 131</p>
      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:'Roboto', sans-serif;">Seaforth</p>
      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:'Roboto', sans-serif;">NSW 2092</p>
      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:'Roboto', sans-serif;">Australia</p>
      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:'Roboto', sans-serif;">ABN 85 623 435 709</p></td>
    </td>  
    <td width="300" style="vertical-align: top;"><h2 style="width:300px;color:#095cab; font-family:'Roboto', sans-serif; border-bottom:2px solid #095cab; padding-bottom:10px;">BILLING TO</h2>
      <?php  if (!empty($addrsss)) {
              if (!empty($addrsss->street_address_line1)) {
                $addrsss->street_address_line1 = $addrsss->street_address_line1 .' / ';
              }
        ?>
      <?php if($order_details[0]->business_name!=""){?>
      <h3  style="margin:0; padding:0; font-family:'Roboto', sans-serif;"><?php echo  $order_details[0]->business_name ?></h3>
	  <?php }?>
      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:'Roboto', sans-serif;"><?php echo  $order_details[0]->renter_firstname." ".$order_details[0]->renter_lastname; ?></p>
      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:'Roboto', sans-serif;"><?php  echo $addrsss->street_address_line1.$addrsss->street_address_line2.' '.$addrsss->route;?></p>
      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:'Roboto', sans-serif;"><?php echo $addrsss->suburb_name.' '.$addrsss->postcode;  ?></p>
      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:'Roboto', sans-serif;"> <?php echo $addrsss->ks_state_name; ?></p>
      <p style="font-size:15px; color:#818181; margin:0; padding:5px 0px; font-family:'Roboto', sans-serif;"><?php echo  $addrsss->ks_country_name ;?></p>
    
    <?php }?>
    </td>
    <td width="300"></td>
  </tr>
</table>
<table class="table table-condensed" width="530" style="margin:0px auto;" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <td  style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Description </strong></td>
	  <td class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Replacement Value</strong></td>
      <td class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Rented Days </strong></td>
      <td class="text-center" style="padding:5px 0px; border-bottom:1px solid #ddd"><strong>Unit Price</strong></td>      
      <td class="text-right" style="padding:5px 0px; border-bottom:1px solid #ddd; text-align:right"><strong>Amount AUD</strong></td>
    </tr>
  </thead>
  <tbody>
    <?php 
           $total_rent_amount_ex_gst = '0';
           $gst_amount = '0';
           $other_charges = '0';
           $total_rent_amount = '0';
           $total_rent_amount2 = '0';
           $security_deposit = '0';
            $owner_insurance_amount = '0';
            $beta_discount = '0';
            $insurance_fee = '0';
            $community_fee = '0';
           $msg1  = '';
          foreach ($order_details as   $value) {    
    ?>
    <tr>
      <td style="padding:5px 0px; border-bottom:1px solid #ddd"><?php echo   $value->gear_name; ?></td>
	  <td  style="padding:10px 0px; border-bottom:1px solid #ddd">$<?php echo   number_format((float)$value->replacement_value_aud_inc_gst, 2, '.', ''); ?></td>
      <td style="padding:10px 0px; border-bottom:1px solid #ddd"><?php echo   $value->total_rent_days; ?></td>
      <td  style="padding:10px 0px; border-bottom:1px solid #ddd">$<?php echo  number_format((float)$value->per_day_cost_aud_ex_gst, 2, '.', ''); ?></td>      
      <td  style="padding:10px 0px; border-bottom:1px solid #ddd;  text-align:right">$<?php echo   number_format((float)$value->total_rent_amount_ex_gst, 2, '.', ''); ?></td>
    </tr>
    <?php

        $total_rent_amount_ex_gst  += $value->total_rent_amount_ex_gst ; 
        $gst_amount  += ($value->total_rent_amount_ex_gst -$value->beta_discount +  $value->insurance_fee + $value->community_fee + $value->owner_insurance_amount)*10/100;; 
        $other_charges  += $value->other_charges ; 
        $total_rent_amount  += $value->total_rent_amount_ex_gst -$value->beta_discount + $value->insurance_fee + $value->community_fee + $value->owner_insurance_amount ; 
       
         $owner_insurance_amount += $value->owner_insurance_amount;
        // $total_rent_amount  += $value['total_rent_amount'] ; 
        $security_deposit +=   $value->security_deposit; 
         $beta_discount +=  $value->beta_discount ; 
          $insurance_fee +=  $value->insurance_fee ; 
          $community_fee += $value->community_fee ;
     } 
     $total_rent_amount2  +=  $total_rent_amount + $gst_amount  ;          
     ?>
    
    <tr>
      <td colspan="4" style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Subtotal</strong></td>
      <td  style="padding:10px 0px; text-align:right; font-size:15px;">$<?php echo number_format((float)$total_rent_amount_ex_gst, 2, '.', '')?></td>
    </tr>
    <tr>
      <td  colspan="4"  style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Discount(15%)</strong></td>
      <td  style="padding:10px 0px; text-align:right; font-size:15px;">-($<?php echo  number_format((float)$beta_discount, 2, '.', '');?>)</td>
    </tr>
    <tr>
      <td  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Insurance Fee</strong></td>
      <td style="padding:10px 0px; text-align:right; font-size:15px;">$<?php echo number_format((float)$insurance_fee, 2, '.', ''); ?></td>
    </tr>
    <?php  if ($owner_insurance_amount > 0) { ?>
    <tr>
      <td  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Owner Insurance Fee</strong></td>
      <td  style="padding:10px 0px; text-align:right; font-size:15px;">$<?php echo  number_format((float)$owner_insurance_amount, 2, '.', ''); ?></td>
    </tr>
    <?php  } ?>
    <tr>
      <td  colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>Community Fee</strong></td>
      <td  style="padding:10px 0px; text-align:right; font-size:15px;">$<?php echo  number_format((float)$community_fee, 2, '.', ''); ?></td>
    </tr>
    
    <tr>
      <td   colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL ex GST</strong></td>
      <td  style="padding:10px 0px; text-align:right; font-size:15px;">$<?php echo number_format((float)$total_rent_amount, 2, '.', '');  ?></td>
    </tr>
    <tr>
      <td   colspan="4"   style="padding:10px 0px; text-align:right; font-size:15px;"><strong>TOTAL GST 10%</strong></td>
      <td  style="padding:10px 0px; text-align:right; font-size:15px;">$<?php echo number_format((float)$gst_amount, 2, '.', '')  ?></td>
    </tr>
    <tr>
      <td   colspan="4"  style="border-top:2px solid #000; padding-top: 10px; font-size:20px; font-weight:bolder; text-align:right"><strong>TOTAL AUD</strong></td>
      <td style="border-top:2px solid #000;  font-size:20px; font-weight:bolder; text-align:right">$<?php echo  number_format((float)$total_rent_amount2, 2, '.', '') ;?> </td>
    </tr>
  </tbody>
</table>
<table width="550" border="0" style="margin:0 auto;" cellspacing="0" cellpadding="0">
  <tr>
    <td><h2 style="color:#095cab; font-family:'Roboto', sans-serif; border-bottom:2px solid #095cab; padding-bottom:10px;">PAYMENT INFORMATION</h2>
      <p>Subject to Kitshare ‘Terms and Conditions’ available at www.kitshare.com.au If you have any questions concerning this invoice, contact accounts at support@kitshare.com.au </p>
      <p>Thank you for your buisness.</p></td>
  </tr>
</table>
</body>
</html>
