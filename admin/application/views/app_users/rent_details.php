<style>
#username {
  border: none;
  border-radius: 0px;
  padding-left: 0px;
  border-bottom: 1px solid #d9d9d9; }
#password {
  border: none;
  border-radius: 0px;
  padding-left: 0px;
  border-bottom: 1px solid #d9d9d9; }
#cnfpwd {
  border: none;
  border-radius: 0px;
  padding-left: 0px;
  border-bottom: 1px solid #d9d9d9; }
#email {
  border: none;
  border-radius: 0px;
  padding-left: 0px;
  border-bottom: 1px solid #d9d9d9; }
</style>
<?php //print_r($details);?>
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
      <h4 class="card-title">View</h4>
      <form class="form-material m-t-40" id="myFrm" action="<?php echo base_url();?>app_users/update" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="form-group col-md-6">
            <label>Model</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $details[0]->model_name  ; ?>" />
          </div>
          <div class="form-group col-md-6">
            <label>Model Description</label>
            <textarea type="text" class="form-control form-control-line" id=""  name="" />
            <?php echo $details[0]->model_description ; ?>
            </textarea>
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="form-group col-md-3">
            <label>Gear Name</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $details[0]->gear_name ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Gear Description #1</label>
            <textarea type="text" class="form-control form-control-line" id=""  name="" ><?php echo $details[0]->gear_description_1 ; ?></textarea>
          </div>
          <div class="form-group col-md-3">
            <label>Gear Description #2</label>
            <textarea type="text" class="form-control form-control-line" id=""  name="" ><?php echo $details[0]->gear_description_2 ; ?></textarea>
          </div>
          <div class="form-group col-md-3">
            <label>Model Id</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->model_id ; ?>" />
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="form-group col-md-3">
            <label>Serial No.</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $details[0]->serial_number ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Replacement Value AUD EX GST</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->replacement_value_aud_ex_gst ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Replacement Value AUD IN GST</label>
              <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->replacement_value_aud_inc_gst ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Per day Cost AUD EX GST</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->per_day_cost_aud_ex_gst ; ?>" />
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="form-group col-md-3">
            <label>Per Day Cost AUD IN GST</label>

              <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $details[0]->per_day_cost_aud_inc_gst ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Per Weekend Cost AUD EX GST</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->per_weekend_cost_aud_ex_gst ; ?>" />
          </div>

          <div class="form-group col-md-3">
            <label>Per Weekend Cost AUD IN GST</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->per_week_cost_aud_inc_gst  ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Per Week Cost AUD EX GST</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->per_week_cost_aud_ex_gst ; ?>" />
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="form-group col-md-3">
            <label>Per Week Cost AUD IN GST</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $details[0]->per_weekend_cost_aud_inc_gst ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>GST Paid  </label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->gst_amount ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Order ID</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->order_id  ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Security Deposit</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->security_deposit ; ?>" />
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="form-group col-md-3">
            <label>Total Amount EX GST </label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->total_rent_amount_ex_gst ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Total Discount</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->total_discount ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Other Charges</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->other_charges  ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Total Rent Amount</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->total_rent_amount ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Order By</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $renter_details->app_user_first_name . ' '. $renter_details->app_user_last_name ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Insurance Type</label>
            <select type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $renter_details->app_user_first_name . ' '. $renter_details->app_user_last_name ; ?>">
                <option>--Select Category--</option>
                <?php if (!empty($ks_insurance_category)) {
                      foreach ($ks_insurance_category as $value) {
                ?>        
                     <option value="<?php echo $value->ks_insurance_category_type_id;?>"  <?php if($value->ks_insurance_category_type_id == $details[0]->ks_insurance_category_type_id){echo "selected";} ?> ><?php echo $value->name;?></option>
                <?php      }
                } ?>
            </select>  
          </div>
          <?php 
          if ($details[0]->insurance_tier_id > 0 ) { ?>
          <div class="form-group col-md-3">
            <label>Insurance Tier Type</label>
            <select type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $renter_details->app_user_first_name . ' '. $renter_details->app_user_last_name ; ?>">
                <option>--Select Category--</option>
                <?php if (!empty($ks_insurance_tiers)) {
                      foreach ($ks_insurance_tiers as $value) {
                ?>        
                     <option value="<?php echo $value->tiers_id;?>"  <?php if($value->tiers_id == $details[0]->insurance_tier_id){echo "selected";} ?> ><?php echo $value->tier_name;?></option>
                <?php      }
                } ?>
            </select>  
          </div>

          <?php }
          ?>
          <div class="form-group col-md-3">
            <label>Deposite Amount</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $details[0]->insurance_amount;?>">
              
          </div>
        </div>
        <div class="clr"></div>
      </form>
      
      <?php $id = $details[0]->app_user_id;
	  		$query = $this->model->app_users_rent_delivery_details($where='',$limit=0,$offset=0,$id); 
			$address_det = $query->result();
			 if(!empty($address_det)){
			
	  ?>
	  <div align="center" style="background-color:#999999; width:100%; color:#FFFFFF">Delivery Details</div>
      <form class="form-material m-t-40" id="myFrm" action="<?php echo base_url();?>app_users/update" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="form-group col-md-6">
            <label>Token</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $address_det[0]->delivery_token  ; ?>" />
          </div>
          <div class="form-group col-md-6">
            <label>Token Generated On</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php
					$str = $address_det[0]->delivery_token_generated_on;
					$arr = explode(" ",$str);
					$date = $arr[0];
					$time = $arr[1];
					echo date('d M, Y', strtotime($date)).' at '.date("g:i A", strtotime($time));
			
			 ?>" />
          </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="form-group col-md-3">
            <label>Delivered</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php if($address_det[0]->is_delivered=='Y') echo 'Yes' ; else echo 'No' ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Delivery Time</label>
            <input type="text" class="form-control form-control-line" id=""  value="<?php
					$str = $address_det[0]->delivery_timestamp;
					$arr = explode(" ",$str);
					$date = $arr[0];
					$time = $arr[1];
					echo date('d M, Y', strtotime($date)).' at '.date("g:i A", strtotime($time));
			
			 ?>" >
          </div>
		   <?php if($address_det[0]->is_returned=='Y'){ ?>
          <div class="form-group col-md-3">
            <label>Return Token</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $address_det[0]->return_token ; ?>" />
          </div>
         
          <div class="form-group col-md-3">
            <label>Returned</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php if($address_det[0]->is_returned=='Y') echo 'Yes' ; else echo 'No' ; ?>" />
          </div>
          <?php } ?>
        </div>
        <div class="clr"></div>
        <?php if($address_det[0]->is_returned=='Y'){ ?>
        <div class="row">
          <div class="form-group col-md-3">
            <label>Return Time</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php
					$str = $address_det[0]->return_timestamp;
					$arr = explode(" ",$str);
					$date = $arr[0];
					$time = $arr[1];
					echo date('d M, Y', strtotime($date)).' at '.date("g:i A", strtotime($time));
			
			 ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Apartment Number</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $address_det[0]->apartment_number ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Street Address</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $address_det[0]->street_address ; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Equipment Address #1</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $address_det[0]->default_equipment_address_1 ; ?>" />
          </div>
        </div>
        <div class="clr"></div>
        <?php } ?>
      </form>
	 
      
      <?php $userid = $details[0]->app_user_id;
	  		$delivery_id = $address_det[0]->user_gear_rent_delivery_id ;
			$query = $this->model->app_users_payment_details($where='',$limit=0,$offset=0,$userid,$delivery_id); 
			$payment_det = $query->result();
			
			if(!empty($payment_det)){
	   ?>
	   <div align="center" style="background-color:#999999; width:100%; color:#FFFFFF">Payment Details</div>
      <form class="form-material m-t-40" id="myFrm" action="<?php echo base_url();?>app_users/update" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="form-group col-md-4">
            <label>Transaction Amount</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->transaction_amount  ; ?>" />
          </div>
          <div class="form-group col-md-4">
            <label>Transaction Time</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php
					$str = $payment_det[0]->transaction_timestamp;
					$arr = explode(" ",$str);
					$date = $arr[0];
					$time = $arr[1];
					echo date('d M, Y', strtotime($date)).' at '.date("g:i A", strtotime($time));
			
			 ?>" />
          </div>
          <div class="form-group col-md-4">
            <label>Transaction Time</label>
            <input type="text" class="form-control form-control-line" id=""  name="" value="<?php echo $payment_det[0]->payment_mode_abbr  ; ?>" />
          </div>
        </div>
        <div class="clr"></div>
        <?php if($payment_det[0]->payment_mode_abbr=='NB'){ ?>
        <div class="row">
          <div class="form-group col-md-3">
            <label>Bank</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->bank_name; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Bank Headoffice</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->bank_head_office; ?>" />
          </div>
		  <div class="form-group col-md-3">
            <label>Account Type</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->account_type; ?>" />
          </div>
		  <div class="form-group col-md-3">
            <label>Branch Code</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->branch_code; ?>" />
          </div>
        </div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>BSB No.</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->bsb_number; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>Branch Address</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->branch_address; ?>" />
          </div>
		  <div class="form-group col-md-3">
            <label>Branch Street</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->branch_street; ?>" />
          </div>
		  <div class="form-group col-md-3">
            <label>Branch Cidt</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->branch_city; ?>" />
          </div>
        </div>
		
		<div class="row">
          <div class="form-group col-md-3">
            <label>Zip Code</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->branch_zip_code; ?>" />
          </div>
          <div class="form-group col-md-3">
            <label>User Account No.</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php echo $payment_det[0]->user_account_number; ?>" />
          </div>
		  <div class="form-group col-md-3">
            <label>Branch Street</label>
            <input type="text" class="form-control form-control-line" id="" name="" value="<?php if($payment_det[0]->accept_stripe_connection=='Y') echo 'Yes'; else echo 'No'; ?>" />
          </div>
		  
        </div>
        <?php } ?>
      </form>
	  <?php } } ?>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- End Page Content -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
