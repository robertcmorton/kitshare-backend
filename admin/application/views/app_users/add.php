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
    <h3 class="text-themecolor m-b-0 m-t-0">Add App User</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_users">App Users List</a></li>
      <li class="breadcrumb-item active">Add App User</li>
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
      <h4 class="card-title">Add</h4>
      <form class="form-material m-t-40" id="myFrm" action="<?php echo base_url();?>app_users/save" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="form-group col-md-3">
            <label>Username</label>
            <input type="text" class="form-control form-control-line" id="app_username" name="app_username" value="<?php echo set_value('app_username'); ?>">
            <label for="app_username" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('app_username','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>First Name</label>
            <input type="text" class="form-control form-control-line" id="app_user_first_name"  name="app_user_first_name" value="<?php echo set_value('app_user_first_name'); ?>">
            <label for="app_user_first_name" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('app_user_first_name','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Lastname</label>
            <input type="text" class="form-control form-control-line" id="app_user_last_name" name="app_user_last_name" value="<?php echo set_value('app_user_last_name'); ?>">
			<label for="app_user_last_name" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('app_user_last_name','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Owner Type</label>
			<select name="owner_type_id" id="owner_type_id" class="form-control form-control-line">
			<option value="">--Select--</option>
			<?php if(count($owner_type)>0){
					foreach($owner_type as $val){ ?>
					
						<option value="<?php echo $val->owner_type_id; ?>"><?php echo $val->owner_type_name; ?></option>
					
					<?php } } ?>
			</select>
			<label for="owner_type_id" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('owner_type_id','<span class="text-danger">','</span>'); ?> </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="form-group col-md-3">
            <label>Date Of Birth</label>
            <input type="date" class="form-control form-control-line" id="user_birth_date"  name="user_birth_date" value="<?php echo set_value('user_birth_date'); ?>">
            <?php echo form_error('user_birth_date','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Unique Id</label>
            <input type="text" class="form-control form-control-line" id="user_unique_id_number" name="user_unique_id_number" value="<?php echo  $unique_id ;?>"  >
			<label for="user_unique_id_number" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('user_unique_id_number','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-6">
            <label>Australian Business Number</label>
            <input type="text" class="form-control form-control-line" id="australian_business_number"  name="australian_business_number" value="<?php echo set_value('australian_business_number'); ?>">
            <?php echo form_error('australian_business_number','<span class="text-danger">','</span>'); ?> </div>
          
        </div>
		<div class="clr"></div>
		<div class="row">
		<div class="form-group col-md-6">
            <label>Description</label>
            <textarea type="text" class="form-control form-control-line" id="user_description"  name="user_description" value="<?php echo set_value('user_description'); ?>"></textarea>
            <?php echo form_error('user_description','<span class="text-danger">','</span>'); ?> </div>
		<div class="form-group col-md-6">
            <label>About Me</label>
            <textarea type="text" class="form-control form-control-line" id="about_me"  name="about_me" value="<?php echo set_value('about_me'); ?>"> </textarea>
            <?php echo form_error('about_me','<span class="text-danger">','</span>'); ?> </div>
		</div>
		<div class="clr"></div>
		<div class="row">
          
          <div class="form-group col-md-3">
            <label>Mobile No.#1</label>
            <input type="text" class="form-control form-control-line" id="primary_mobile_number"  name="primary_mobile_number" value="<?php echo set_value('primary_mobile_number'); ?>">
            <label for="primary_mobile_number" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('primary_mobile_number','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Mobile No.#2</label>
            <input type="text" class="form-control form-control-line" id="additional_mobile_number"  name="additional_mobile_number" value="<?php echo set_value('additional_mobile_number'); ?>">
            <?php echo form_error('additional_mobile_number','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-6">
            <label>Primary Email Address</label>
            <input type="primary_email_address" class="form-control form-control-line" id="primary_email_address"  name="primary_email_address" value="<?php echo set_value('primary_email_address'); ?>">
            <label for="primary_email_address" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('primary_email_address','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Additional Email Address #1</label>
            <input type="text" class="form-control form-control-line" id="additional_email_address_1"  name="additional_email_address_1" value="<?php echo set_value('additional_email_address_1'); ?>">
			<label for="additional_email_address_1" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('additional_email_address_1','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Additional Email Address #2</label>
            <input type="text" class="form-control form-control-line" id="additional_email_address_2"  name="additional_email_address_2" value="<?php echo set_value('additional_email_address_2'); ?>">
			<label for="additional_email_address_2" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('additional_email_address_2','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Additional Email Address #3</label>
            <input type="text" class="form-control form-control-line" id="additional_email_address_3"  name="additional_email_address_3" value="<?php echo set_value('additional_email_address_3'); ?>">
			<label for="additional_email_address_3" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('additional_email_address_3','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Signup Type</label>
            <select type="text" class="form-control form-control-line" id="user_signup_type" name="user_signup_type" value="<?php echo set_value('user_signup_type'); ?>">
				<option value="">--SELECT SIGNUP--</option>
				<option value="GM">Gmail</option>
				<option value="FB">Facebook</option>
				<option value="EM">Email</option>
			</select>
            <?php echo form_error('user_signup_type','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		
		<div class="row" >
      <div class="col-md-12">
      <h2>Primary Address</h2>
      </div>
    
     
      <div class="form-group col-md-4">
        <label>Street Address 1 </label>
        <input type="text" class="form-control form-control-line" id="street_address_line1" name="street_address_line1[]" value="<?php echo set_value('street_address_line1'); ?>">
        <?php echo form_error('street_address_line1','<span class="text-danger">','</span>'); ?>
      </div>
      <div class="form-group col-md-4">
        <label>Street Address 2</label>
        <input type="text" class="form-control form-control-line" id="street_address_line2" name="street_address_line2[]" value="<?php echo set_value('street_address_line2'); ?>">
        <?php echo form_error('street_address_line2','<span class="text-danger">','</span>'); ?>
      </div>
      <div class="form-group col-md-4">
        <label>Route</label>
        <input type="text" class="form-control form-control-line" id="route" name="route[]" value="<?php echo set_value('route'); ?>">
        <?php echo form_error('route','<span class="text-danger">','</span>'); ?>
      </div>
      
      <div class="form-group col-md-3">
        <label>Country</label>
        <select type="text" class="form-control form-control-line ks_country_id_1" id="ks_country_id" name="ks_country_id[]" onchange="changeStates(1)" value="<?php echo set_value('ks_country_id'); ?>">
          <option value="" >--Select Country--</option>
          <?php if(!empty($countries)){  
                foreach($countries AS $country){  
              
          ?>
          <option value="<?php echo $country->ks_country_id; ?>"><?php echo $country->ks_country_name; ?></option>
          <?php }} ?>
        </select>
        <?php echo form_error('ks_country_id','<span class="text-danger">','</span>'); ?>
      </div>
      <div class="form-group col-md-3">
        <label>State</label>
        <select type="text" class="form-control form-control-line ks_state_id_1 " id="ks_state_id" name="ks_state_id[]" onchange="changeSuburb(1)"  value="<?php echo set_value('ks_state_id'); ?>">
          <option value="" >--Select State--</option>
        
        </select>
        <?php echo form_error('ks_state_id','<span class="text-danger">','</span>'); ?>
      </div>
      <div class="form-group col-md-3">
        <label>Town/Suburb</label>
        <select type="text" class="form-control form-control-line ks_suburb_id_1" id="ks_suburb_id" name="ks_suburb_id[]" onchange="Changepincode(1)"  value="<?php echo set_value('ks_suburb_id'); ?>">
          <option value="" >--Select Suburb--</option>
        
        </select>
        <?php echo form_error('ks_suburb_id','<span class="text-danger">','</span>'); ?>
      </div>
      <div class="form-group col-md-3">
        <label>Postcode </label>
        <input type="text" class="form-control form-control-line postcode_1" id="postcode" name="postcode[]" value="<?php echo set_value('postcode'); ?>">
        <?php echo form_error('postcode','<span class="text-danger">','</span>'); ?>
      </div>
      <div class="form-group col-md-3">
        <label>Default Address </label>
        <br>
       <!-- <input type="radio" class="default_address" id="default_address" name="default_address[1]"  value="0"> -->
        <input type="radio" name="default_address[]" value="1">
        <?php echo form_error('postcode','<span class="text-danger">','</span>'); ?>
      </div>
    <input type="hidden" value="1" id="counter" name="counter">
    <a href="javascript:void(0);" onclick="addFeatureValue('divFeatureValue','addFeatureVal1')" id="addFeatureVal1">Add More</a>
    </div>
    <div class="row" id="divFeatureValue" >
      
    </div>
		<div class="clr"></div>
		<div class="row">
		
          <!--<div class="form-group col-md-3">
            <label>Account Type</label>
            <input type="text" class="form-control form-control-line" id="user_account_type" name="user_account_type" value="<?php echo set_value('user_account_type'); ?>">
            <?php echo form_error('user_account_type','<span class="text-danger">','</span>'); ?> </div>
          --><div class="form-group col-md-3">
            <label>Social Id</label>
            <input type="user_social_id" class="form-control form-control-line" id="user_social_id" name="user_social_id" value="<?php echo set_value('user_social_id'); ?>">
            <?php echo form_error('user_social_id','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Profile Picture</label>
            <input type="file" id="user_profile_picture_link" name="user_profile_picture_link" value="<?php echo set_value('user_profile_picture_link'); ?>">
            <?php echo form_error('user_profile_picture_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Password</label>
            <input type="password" class="form-control form-control-line" id="app_password" name="app_password" value="<?php echo set_value('app_password'); ?>">
            <label for="app_password" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('app_password','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Confirm Password</label>
            <input type="password" class="form-control form-control-line" id="conf_password" name="conf_password" value="<?php echo set_value('conf_password'); ?>">
            <label for="conf_password" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('conf_password','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>User Website</label>
            <input type="url" class="form-control form-control-line" id="user_website" name="user_website" value="<?php echo set_value('user_website'); ?>">
            <label for="user_website" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('user_website','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>IMDB Link</label>
            <input type="url" class="form-control form-control-line" id="imdb_link" name="imdb_link" value="<?php echo set_value('imdb_link'); ?>">
             <label for="imdb_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('imdb_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Showreel Link</label>
			<input type="url" class="form-control form-control-line" id="showreel_link" name="showreel_link" value="<?php echo set_value('showreel_link'); ?>">
            <label for="showreel_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('showreel_link','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Instagram Link</label>
            <input type="url" class="form-control form-control-line" id="instagram_link" name="instagram_link" value="<?php echo set_value('instagram_link'); ?>">
            <label for="instagram_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('instagram_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Facebook Link</label>
            <input type="url" class="form-control form-control-line" id="facebook_link" name="facebook_link" value="<?php echo set_value('facebook_link'); ?>">
            <label for="facebook_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('facebook_link','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Vimeo Link</label>
            <input type="url" class="form-control form-control-line" id="vimeo_link" name="vimeo_link" value="<?php echo set_value('vimeo_link'); ?>">
            <label for="vimeo_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('vimeo_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Youtube Link</label>
            <input type="url" class="form-control form-control-line" id="youtube_link" name="youtube_link" value="<?php echo set_value('youtube_link'); ?>">
            <label for="youtube_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('youtube_link','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Flikr Link</label>
            <input type="url" class="form-control form-control-line" id="flikr_link"  name="flikr_link" value="<?php echo set_value('flikr_link'); ?>">
            <label for="flikr_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('flikr_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Twitter Link</label>
            <input type="url" class="form-control form-control-line" id="twitter_link" name="twitter_link" value="<?php echo set_value('twitter_link'); ?>">
            <label for="twitter_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('twitter_link','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Linkedin Llink</label>
            <input type="url" class="form-control form-control-line" id="linkedin_link" name="linkedin_link" value="<?php echo set_value('linkedin_link'); ?>">
            <label for="linkedin_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('linkedin_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Receive SMS Notification</label>
			<select class="form-control form-control-line" name="receive_sms_notification" id="receive_sms_notification">
			    <option value="">--Select--</option>
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select>
            <?php echo form_error('receive_sms_notification','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Enable Weekly/Weekend Rate</label>
			<select class="form-control form-control-line" name="enable_weekly_rate" id="enable_weekly_rate">
			    <option value="">--Select--</option>
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select>
            <?php echo form_error('enable_weekly_rate','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>GST Registered</label>
			<select class="form-control form-control-line" name="registered_for_gst" id="registered_for_gst">
			    <option value="">--Select--</option>
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select>
            <?php echo form_error('registered_for_gst','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Willing to Deliver</label>
			<select class="form-control form-control-line" name="is_willing_deliver" id="is_willing_deliver">
			    <option value="">--Select--</option>
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select>
            <?php echo form_error('is_willing_deliver','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Prep Space Available</label>
			<select class="form-control form-control-line" name="is_prep_space_available" id="is_prep_space_available">
			    <option value="">--Select--</option>
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select>
            <?php echo form_error('is_prep_space_available','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Active</label>
			<select class="form-control form-control-line" name="is_active" id="is_active">
			    <option value="">--Select--</option>
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select>
            <?php echo form_error('is_active','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Blocked</label>
			<select class="form-control form-control-line" name="is_blocked" id="is_blocked" onchange="Block()">
			    <option value="">--Select--</option>
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select>
            <?php echo form_error('is_blocked','<span class="text-danger">','</span>'); ?> </div>
			<div class="form-group col-md-3">
            <label>Renter Type</label>
      <select class="form-control form-control-line" name="ks_renter_type_id" id="ks_renter_type_id">
          <option value="">--Select--</option>
          <?php if (!empty($rental_type)) {
                  foreach ($rental_type as $value) {
            ?>
            <option value="<?php echo $value->ks_renter_type_id; ?>"><?php echo $value->ks_renter_type ;?></option>
          <?php         } } ?>
          
      </select>
            <?php echo form_error('ks_renter_type_id','<span class="text-danger">','</span>'); ?> </div>
      <div class="form-group col-md-3">
            <label>Profession</label>
      <select class="form-control form-control-line" name="profession_type_id" id="ks_renter_type_id">
          <option value="">--Select--</option>
          <?php if (!empty($profession_types)) {
                  foreach ($profession_types as $value) {
            ?>
            <option value="<?php echo $value->profession_type_id; ?>"><?php echo $value->profession_name ;?></option>
          <?php         } } ?>
          
      </select>
            <?php echo form_error('profession_type_id','<span class="text-danger">','</span>'); ?> </div>          
		 
		  <div class="form-group col-md-3" id="block_reason_div">
            <label>Block Reason</label>
            <input type="text" class="form-control form-control-line" id="block_reason" name="block_reason" value="<?php echo set_value('block_reason'); ?>">
            <?php echo form_error('block_reason','<span class="text-danger">','</span>'); ?> </div>
          
        </div>
		<div class="clr"></div>
        <div class="box-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
        <div class="clr"></div>
      </form>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- End Page Content -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->

<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>

var j = jQuery.noConflict(); 
j(function() {
j("#block_reason_div").hide();
  j("#myFrm").validate({
	rules: {
		app_username: {
			required: true,
			remote: {
			url: "<?php echo base_url()?>app_users/username_check",
			type: "post"
			}
		 },
		app_user_first_name: {
			required: true,
		},
		app_user_last_name: {
			required: true,
		},
		owner_type_id: {
			required: true,
		},
		primary_mobile_number: {
			required: true,
		},
		primary_email_address: {
			required: true,
			email: true,
		},
		ks_renter_type_id: {
			required: true,
			
		},
		additional_email_address_1:{
			email: true,
		},
		additional_email_address_2:{
			email: true,
		},
		additional_email_address_3:{
			email: true,
		},
		app_password:{
			email: true,
		},
		app_password: {
			required: true,
			minlength: 6
		},
		conf_password: {
			required: true,
			minlength: 6,
			equalTo: "#app_password",
		},
		user_unique_id_number:{
			required: true,
			remote: {
			url: "<?php echo base_url()?>app_users/unique_id_check",
			type: "post"
			}
		},
		
	},

  messages: {
		app_username: {
			required: "Please provide Username",
			remote: "Username already in use!"
			
		},
		app_user_first_name: {
			required: "Please provide first name",
		},
		app_user_last_name: {
			required: "Please provide last name",
		},
		owner_type_id: {
			required: "Please provide owner type",
		},
		primary_mobile_number: {
			required: "Please provide mobile number",
		},
		primary_email_address: {
			required: "Please provide email address",
			
		},
		ks_renter_type_id: {
			required: "Please Select type",
			
		},
		app_password: {
			required: "Please provide a password",
			minlength: "Minimum input 6 characters"
		},
		conf_password: {
			required: "Please provide confirm password",
			minlength: "Minimum input 6 characters",
			equalTo: "Should be same as password"
		},
		user_unique_id_number: {
			required: "Please provide UserId",
			remote: "UserId already in use!"
			
		},
		
		
	},
	submitHandler: function(form) {
		form.submit();
	}
});
});


function Block()
{
  
  	var block_status = j('#is_blocked').val();
	if(block_status=='Y')
	{
	    j('#block_reason').val('');
		j('#block_reason_div').show();
	}else{
		j('#block_reason_div').hide();
	}
 }

</script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
<script>
  	function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
	
}

function changeStates(counter){
  //alert(counter);
  var country_id = $('.ks_country_id_'+counter).val();
  //alert(country_id);
   $.ajax({
            type:'post',
            url:'<?php echo base_url(); ?>app_users/getStateList',
            data:{country_id:country_id},
            success:function(data){
               $('.ks_state_id_'+counter).html(data);
            }
        });
}
function changeSuburb(counter){
  var state_id = $('.ks_state_id_'+counter).val();
  //alert(country_id);
   $.ajax({
            type:'post',
            url:'<?php echo base_url(); ?>app_users/getCityList',
            data:{state_id:state_id},
            success:function(data){
               $('.ks_suburb_id_'+counter).html(data);
            }
        });
}
function  Changepincode(counter){
  var suburb_id = $('.ks_suburb_id_'+counter).val();
   $.ajax({
            type:'post',
            url:'<?php echo base_url(); ?>app_users/getpincode',
            data:{suburb_id:suburb_id},
            success:function(data){
               $('.postcode_'+counter).val(data);
            }
        });
  
}
function defaultaddress(counter){
var count= $('#counter').val();
//alert(count);
for (var i = 1; i <= count; i++) {
  $('.default_address_'+i).val('0');
 };

$('.default_address_'+counter).val('1');
}
function addFeatureValue(placeholder,addplaceholder){
  var counter = +$("#counter").val() + 1;
  $.ajax({
    type:"post",
    url:"<?php echo base_url(); ?>app_users/addaccountaddress",
    data:{'counter':counter},
    success:function(response){
      j('#'+placeholder+'').append(response);
    $('#counter').val(counter);
    }
  
  });

}

  </script>