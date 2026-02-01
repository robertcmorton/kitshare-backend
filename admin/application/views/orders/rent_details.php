<style>
.add_left h2 {
    font-size: 19px;
    color: #095cab;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid #095cab;
}
.add_left p {
    margin: 0;
    font-size: 13px;
    line-height: 23px;
}
.add_left {
    margin-bottom: 20px;
}
td.table_head {
    font-size: 17px;
    color: #095cab;
    margin-bottom: 0px;
    font-weight: bolder;
    padding: 4px 0px;
}
.logo_pdf img {
    width: 293px;
    max-width: 100%;
    margin-top: 57px;
}
.info_heading h3 {
    font-size: 30px;
    text-align: right;
    margin-bottom: 20px;
    font-weight: bolder;
}
td.reff {
    font-size: 16px;
    text-align: right;
}
.info_heading {
    margin-bottom: 15px;
}
.fonts_weight{ font-weight:bolder; color:#000; font-size:17px;}
.table td, .table th {
    padding: 6px;
    vertical-align: top;
    border-top: 1px solid #e9ecef;
}
</style>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
  <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">App User Rent Details</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_users">App User Rent Details</a></li>
      <li class="breadcrumb-item active">View App User Rent Details</li>
    </ol>
  </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
<div class="col-12">
  <div class="card">
    <div class="card-body">
   <!--   <h4 class="card-title">View</h4>-->

      
    <div class="container">
  <div class="wrapper">
    <div class="row">
      <div class="col-md-7">
        <div class="logo_pdf"> 
        <img src="<?php echo base_url(); ?>/assets/images/pdf_logo.png" class=" img-responsive" style="width: 295px;">
         </div>
      </div>
      <div class="col-md-5">
        <div class="info_heading">
          <h3>TAX INVOICE</h3>
          <table width="100%" border="0">
            <tr>
              <td class="table_head">REFERENCE:</td>
              <td class="reff"><?php echo  $six_digit_random_number;?></td>
            </tr>
            <tr>
              <td class="table_head">BILLING DATE:</td>
              <td class="reff"><?php echo date('d-M-Y',strtotime($cart_details[0]->gear_rent_requested_on)); ?></td>
            </tr>
            <tr>
              <td class="table_head">RENTAL DATES:</td>
              <td class="reff"><?php echo date('d-M-Y',strtotime($cart_details[0]->gear_rent_request_from_date)) .' - ' . date('d-M-Y',strtotime($cart_details[0]->gear_rent_request_to_date)) ?></td>
            </tr>
            <tr>
              <td class="table_head">OWNER:</td>
              <td class="reff"><?php echo $addrsss1->owner_app_user_first_name . $addrsss1->owner_app_user_last_name;  ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="add_details_new">
      <div class="row">
        <div class="col-md-6">
          <div class="add_left">
            <h2>OUR INFORMATION</h2>
            <h3>Kitshare Pty Ltd</h3>
              <p>PO BOX 131</p>
              <p>Seaforth</p>
              <p>NSW 2092</p>
              <p>Australia</p>
              <p>ABN 85 623 435 709</p>
          
          </div>
        </div>
        <div class="col-md-6">
          <div class="add_left">
            <h2>BILLING TO</h2>
            <?php  if (!empty($addrsss)) {
              if (!empty($addrsss->street_address_line1)) {
                  $addrsss->street_address_line1 =  $addrsss->street_address_line1.' / ' ;
              }
                
            ?>
            <h3><?php echo  $addrsss->bussiness_name; ?></h3>
                     
              <p><?php echo  $addrsss->app_user_first_name.'  '.$addrsss->app_user_last_name; ?></p>
              <p><?php echo  $addrsss->street_address_line1.$addrsss->street_address_line2 .' '. $addrsss->route;?></p>
              <p><?php echo  $addrsss->suburb_name. ' '. $addrsss->postcode;  ?></p>
              <p><?php echo  $addrsss->ks_state_name; ?></p>
              <p><?php echo  $addrsss->ks_country_name ;?></p>
        
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <div class="invoice_table">
        <div class="table_invoice">
          <div class="table-responsive">
            <table class="table table-condensed">
              <thead>
                <tr>
                  <td><strong class="fonts_weight">Description </strong></td>
                  <td class="text-center"><strong class="fonts_weight">Replacement Value </strong></td>
                  <td class="text-center"><strong class="fonts_weight">Rented Days</strong></td>
                  <td class="text-center"><strong class="fonts_weight">Daily Rate</strong></td>
                  <td class="text-right"><strong class="fonts_weight">Amount AUD</strong></td>
                </tr>
              </thead>
              <tbody>
                <?php  
                   $total_rent_amount_ex_gst = 0;
                   $gst_amount = 0;
                   $other_charges = 0;
                   $total_rent_amount = 0;
                   $total_rent_amount2 = 0;
                   $security_deposit = 0;
                   $beta_discount = 0 ; 
                   $insurance_fee = 0 ; 
                   $community_fee = 0 ; 
                   $owner_insurance_amount = 0;

                foreach ($cart_details as $value) {
                 ?>
                <tr>
                  <td><?php echo $value->gear_name; ?></td>
                  <td class="text-center">$<?php echo number_format((float)$value->replacement_value_aud_inc_gst, 2, '.', ''); ?></td>
                  <td class="text-center"><?php  echo $value->gear_total_rent_request_days;?></td>
                  <td class="text-center">$<?php  echo  number_format((float)$value->per_day_cost_aud_ex_gst, 2, '.', '');  ?></td>
                  
                  <td class="text-right">$<?php echo number_format((float)$value->total_rent_amount_ex_gst, 2, '.', ''); ?></td>
                </tr>
                <?php

                      $total_rent_amount_ex_gst  += $value->total_rent_amount_ex_gst ; 
                      $gst_amount  += ($value->total_rent_amount_ex_gst -$value->beta_discount +  $value->insurance_fee + $value->community_fee + number_format((float)(( $value->owner_insurance_amount)) , 2, '.', ''))*10/100;; 
                      $other_charges  += $value->other_charges ; 
                      $total_rent_amount  += $value->total_rent_amount_ex_gst -$value->beta_discount + $value->insurance_fee + $value->community_fee +  number_format((float)(( $value->owner_insurance_amount)) , 2, '.', '') ; 
                      
                      $owner_insurance_amount += number_format((float)( $value->owner_insurance_amount), 2, '.', '') ;
                      // $total_rent_amount  += $value['total_rent_amount'] ; 
                      $security_deposit +=   $value->security_deposit; 
                      $beta_discount += $value->beta_discount ; 
                      $insurance_fee += $value->insurance_fee ; 
                      $community_fee += $value->community_fee ; 

                 }
                 $total_rent_amount2  = $total_rent_amount + $gst_amount  ;                    
                 ?>
               
                <tr>
                  <td class="thick-line"></td>
                  <td class="thick-line"></td>
                  <td class="thick-line"></td>
                  <td class="thick-line text-right"><strong>Subtotal</strong></td>
                  <td class="thick-line text-right">$<?php echo number_format((float)$total_rent_amount_ex_gst, 2, '.', '')?></td>
                </tr>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right"><strong>Discount(15%)</strong></td>
                  <td class="no-line text-right">$<?php echo  number_format((float)$beta_discount, 2, '.', '');?></td>
                </tr>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right "><strong>Insurance Fee</strong></td>
                  <td class="no-line text-right">$<?php echo number_format((float)$insurance_fee, 2, '.', ''); ?></td>
                </tr>
                <?php if ($owner_insurance_amount > 0 ) {?>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right"><strong>Owner Insurance Fee</strong></td>
                  <td class="no-line text-right">$<?php echo  number_format((float)$owner_insurance_amount, 2, '.', ''); ?></td>
                </tr>
                <?php } ?>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right"><strong>Community Fee</strong></td>
                  <td class="no-line text-right">$<?php echo  number_format((float)$community_fee, 2, '.', ''); ?></td>
                </tr>
                
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right"><strong>TOTAL ex GST</strong></td>
                  <td class="no-line text-right">$<?php echo number_format((float)$total_rent_amount, 2, '.', '');  ?></td>
                </tr>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right"><strong>TOTAL GST 10%</strong></td>
                  <td class="no-line text-right">$<?php echo number_format((float)$gst_amount, 2, '.', '')  ?></td>
                </tr>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right" style="border-top:2px solid #000; font-size:20px; font-weight:bolder"><strong>TOTAL AUD</strong></td>
                  <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder">$<?php echo  number_format((float)$total_rent_amount2, 2, '.', '') ;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
    </div>
    <div class="payment_infor">
      <div class="row">
        <div class="col-md-12">
         <!--  <div class="add_left">
            <h2>PAYMENT INFORMATION</h2>
            <p>Subject to Kitshare ‘Terms and Conditions’ available at www.kitshare.com.au
              If you have any  questions concerning this invoice, contact accounts at support@kitshare.com.au <br>
              <br>
              <br>
              Thank you for your buisness.</p>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- End Page Content -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<style type="text/css">

</style>