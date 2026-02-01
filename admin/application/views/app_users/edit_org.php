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
    <h3 class="text-themecolor m-b-0 m-t-0">Edit App User</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_users">App Users List</a></li>
      <li class="breadcrumb-item active">Edit App User</li>
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
      <h4 class="card-title">Edit</h4>
      <form class="form-material m-t-40" id="myFrm" action="<?php echo base_url();?>app_users/update" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="form-group col-md-3">
            <label>Username</label>
			<input type="hidden" name="app_user_id" value="<?php echo $app_users[0]->app_user_id ; ?>">
            <input type="text" class="form-control form-control-line" id="app_username" name="app_username" value="<?php echo $app_users[0]->app_username ; ?>">
            <label for="app_username" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('app_username','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>First Name</label>
            <input type="text" class="form-control form-control-line" id="app_user_first_name"  name="app_user_first_name" value="<?php echo $app_users[0]->app_user_first_name ; ?>">
            <label for="app_user_first_name" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('app_user_first_name','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Lastname</label>
            <input type="text" class="form-control form-control-line" id="app_user_last_name" name="app_user_last_name" value="<?php echo $app_users[0]->app_user_last_name ; ?>">
			<label for="app_user_last_name" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('app_user_last_name','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Owner Type</label>
			<select name="owner_type_id" id="owner_type_id" class="form-control form-control-line">
			<option value="">--Select--</option>
			<?php if(count($owner_type)>0){
					foreach($owner_type as $val){ ?>
					
						<option value="<?php echo $val->owner_type_id; ?>" <?php if($val->owner_type_id==$app_users[0]->owner_type_id) echo "selected=selected"; ?>><?php echo $val->owner_type_name; ?></option>
					
					<?php } } ?>
			</select>
			<label for="owner_type_id" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('owner_type_id','<span class="text-danger">','</span>'); ?> </div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="form-group col-md-3">
            <label>Date Of Birth</label>
            <input type="date" class="form-control form-control-line" id="user_birth_date"  name="user_birth_date" value="<?php echo $app_users[0]->user_birth_date; ?>">
            <?php echo form_error('user_birth_date','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Unique Id</label>
            <input type="text" class="form-control form-control-line" id="user_unique_id_number" name="user_unique_id_number" value="<?php echo $app_users[0]->user_unique_id_number; ?>">
            <?php echo form_error('user_unique_id_number','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Australian Business Number</label>
            <input type="text" class="form-control form-control-line" id="australian_business_number"  name="australian_business_number" value="<?php echo $app_users[0]->australian_business_number; ?>">
            <?php echo form_error('australian_business_number','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Description</label>
            <input type="text" class="form-control form-control-line" id="user_description"  name="user_description" value="<?php echo $app_users[0]->user_description; ?>">
            <?php echo form_error('user_description','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>About Me</label>
            <input type="text" class="form-control form-control-line" id="about_me"  name="about_me" value="<?php echo $app_users[0]->about_me; ?>">
            <?php echo form_error('about_me','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Mobile No.#1</label>
            <input type="text" class="form-control form-control-line" id="primary_mobile_number"  name="primary_mobile_number" value="<?php echo $app_users[0]->primary_mobile_number; ?>">
            <label for="primary_mobile_number" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('primary_mobile_number','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Mobile No.#2</label>
            <input type="text" class="form-control form-control-line" id="additional_mobile_number"  name="additional_mobile_number" value="<?php echo $app_users[0]->additional_mobile_number; ?>">
            <?php echo form_error('additional_mobile_number','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Primary Email Address</label>
            <input type="primary_email_address" class="form-control form-control-line" id="primary_email_address"  name="primary_email_address" value="<?php echo $app_users[0]->primary_email_address; ?>">
            <label for="primary_email_address" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('primary_email_address','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Email Address #1</label>
            <input type="text" class="form-control form-control-line" id="additional_email_address_1"  name="additional_email_address_1" value="<?php echo $app_users[0]->additional_email_address_1; ?>">
			<label for="additional_email_address_1" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('additional_email_address_1','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Email Address #2</label>
            <input type="text" class="form-control form-control-line" id="additional_email_address_2"  name="additional_email_address_2" value="<?php echo $app_users[0]->additional_email_address_2; ?>">
			<label for="additional_email_address_2" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('additional_email_address_2','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Email Address #3</label>
            <input type="text" class="form-control form-control-line" id="additional_email_address_3"  name="additional_email_address_3" value="<?php echo $app_users[0]->additional_email_address_3; ?>">
			<label for="additional_email_address_3" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
            <?php echo form_error('additional_email_address_3','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Signup Type</label>
            <input type="text" class="form-control form-control-line" id="user_signup_type" name="user_signup_type" value="<?php echo $app_users[0]->user_signup_type; ?>">
            <?php echo form_error('user_signup_type','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
		
          <div class="form-group col-md-3">
            <label>Account Type</label>
            <input type="text" class="form-control form-control-line" id="user_account_type" name="user_account_type" value="<?php echo $app_users[0]->user_account_type; ?>">
            <?php echo form_error('user_account_type','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Social Id</label>
            <input type="user_social_id" class="form-control form-control-line" id="user_social_id" name="user_social_id" value="<?php echo $app_users[0]->user_social_id; ?>">
            <?php echo form_error('user_social_id','<span class="text-danger">','</span>'); ?> </div>
			<div class="form-group col-md-3">
				<div class="row">
					<div class="col-sm-4">
					<?php if($app_users[0]->user_profile_picture_link==''){ ?>
						<img src="<?php echo base_url(); ?>assets/images/default1.jpg" width="80px" height="80px" />
					<?php } else { ?>
					  <img src="<?php echo FRONT_URL;?>profile_img/<?php echo $app_users[0]->user_profile_picture_link;?>" width="80px" height="80px" />
					<?php } ?>
					</div>
				  </div>
			  </div>
			
			
		  <div class="form-group col-md-3">
            <label>Profile Picture</label>
            <input type="file" id="user_profile_picture_link" name="user_profile_picture_link" value="<?php echo set_value('user_profile_picture_link'); ?>">
            <?php echo form_error('user_profile_picture_link','<span class="text-danger">','</span>'); ?> </div>
          <!--<div class="form-group col-md-3">
            <label>Password</label>
            <input type="password" class="form-control form-control-line" id="app_password" name="app_password" value="<?php echo $app_users[0]->app_password; ?>">
            <label for="app_password" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('app_password','<span class="text-danger">','</span>'); ?> </div>-->
			<input type="hidden" class="form-control form-control-line" id="app_password" name="app_password" value="<?php echo $app_users[0]->app_password; ?>">
        </div>
		<div class="clr"></div>
		<div class="row">
		<input type="hidden" class="form-control form-control-line" id="conf_password" name="conf_password" value="<?php echo $app_users[0]->app_password; ?>">
          <!--<div class="form-group col-md-3">
            <label>Confirm Password</label>
            <input type="hidden" class="form-control form-control-line" id="conf_password" name="conf_password" value="<?php echo $app_users[0]->app_password; ?>">
            <label for="conf_password" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('conf_password','<span class="text-danger">','</span>'); ?> </div>-->
          <div class="form-group col-md-3">
            <label>User Website</label>
            <input type="text" class="form-control form-control-line" id="user_website" name="user_website" value="<?php echo $app_users[0]->user_website; ?>">
            <?php echo form_error('user_website','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>IMDB Link</label>
            <input type="url" class="form-control form-control-line" id="imdb_link" name="imdb_link" value="<?php echo $app_users[0]->imdb_link; ?>">
            <label for="imdb_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('imdb_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Showreel Link</label>
            <input type="url" class="form-control form-control-line" id="showreel_link" name="showreel_link" value="<?php echo $app_users[0]->showreel_link; ?>">
            <label for="showreel_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('showreel_link','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Instagram Link</label>
            <input type="url" class="form-control form-control-line" id="instagram_link" name="instagram_link" value="<?php echo $app_users[0]->instagram_link; ?>">
            <label for="instagram_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('instagram_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Facebook Link</label>
            <input type="url" class="form-control form-control-line" id="facebook_link" name="facebook_link" value="<?php echo $app_users[0]->facebook_link; ?>">
            <label for="instagram_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('facebook_link','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Vimeo Link</label>
            <input type="url" class="form-control form-control-line" id="vimeo_link" name="vimeo_link" value="<?php echo $app_users[0]->vimeo_link; ?>">
            <label for="vimeo_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('vimeo_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Youtube Link</label>
            <input type="url" class="form-control form-control-line" id="youtube_link" name="youtube_link" value="<?php echo $app_users[0]->youtube_link; ?>">
            <label for="youtube_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('youtube_link','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Flikr Link</label>
            <input type="url" class="form-control form-control-line" id="flikr_link"  name="flikr_link" value="<?php echo $app_users[0]->flikr_link; ?>">
             <label for="flikr_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('flikr_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Twitter Link</label>
            <input type="url" class="form-control form-control-line" id="twitter_link" name="twitter_link" value="<?php echo $app_users[0]->twitter_link; ?>">
            <label for="twitter_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('twitter_link','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Linkedin Llink</label>
            <input type="text" class="form-control form-control-line" id="linkedin_link" name="linkedin_link" value="<?php echo $app_users[0]->linkedin_link; ?>">
            <label for="linkedin_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
			<?php echo form_error('linkedin_link','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Receive SMS Notification</label>
			<select class="form-control form-control-line" name="receive_sms_notification" id="receive_sms_notification">
			    <option value="">--Select--</option>
				<option value="Y" <?php if($app_users[0]->receive_sms_notification=='Y') echo "selected=selected" ; ?>>Yes</option>
				<option value="N" <?php if($app_users[0]->receive_sms_notification=='N') echo "selected=selected" ; ?>>No</option>
			</select>
            <?php echo form_error('receive_sms_notification','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Enable Weekly Rate</label>
			<select class="form-control form-control-line" name="enable_weekly_rate" id="enable_weekly_rate">
			    <option value="">--Select--</option>
				<option value="Y" <?php if($app_users[0]->enable_weekly_rate=='Y') echo "selected=selected" ; ?>>Yes</option>
				<option value="N" <?php if($app_users[0]->enable_weekly_rate=='N') echo "selected=selected" ; ?>>No</option>
			</select>
            <?php echo form_error('enable_weekly_rate','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Enable Weekend Rate</label>
			<select class="form-control form-control-line" name="enable_weekend_rate" id="enable_weekend_rate">
			    <option value="">--Select--</option>
				<option value="Y" <?php if($app_users[0]->enable_weekend_rate=='Y') echo "selected=selected" ; ?>>Yes</option>
				<option value="N" <?php if($app_users[0]->enable_weekend_rate=='N') echo "selected=selected" ; ?>>No</option>
			</select>
            <?php echo form_error('enable_weekend_rate','<span class="text-danger">','</span>'); ?> </div>
		  <div class="form-group col-md-3">
            <label>Willing Deliver</label>
			<select class="form-control form-control-line" name="is_willing_deliver" id="is_willing_deliver">
			    <option value="">--Select--</option>
				<option value="Y" <?php if($app_users[0]->is_willing_deliver=='Y') echo "selected=selected" ; ?>>Yes</option>
				<option value="N" <?php if($app_users[0]->is_willing_deliver=='N') echo "selected=selected" ; ?>>No</option>
			</select>
            <?php echo form_error('is_willing_deliver','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Prep Space Available</label>
			<select class="form-control form-control-line" name="is_prep_space_available" id="is_prep_space_available">
			    <option value="">--Select--</option>
				<option value="Y" <?php if($app_users[0]->is_prep_space_available=='Y') echo "selected=selected" ; ?>>Yes</option>
				<option value="N" <?php if($app_users[0]->is_prep_space_available=='N') echo "selected=selected" ; ?>>No</option>
			</select>
            <?php echo form_error('is_prep_space_available','<span class="text-danger">','</span>'); ?> </div>
        </div>
		<div class="clr"></div>
		<div class="row">
          <div class="form-group col-md-3">
            <label>Active</label>
			<select class="form-control form-control-line" name="is_active" id="is_active">
			    <option value="">--Select--</option>
				<option value="Y" <?php if($app_users[0]->is_active=='Y') echo "selected=selected" ; ?>>Yes</option>
				<option value="N" <?php if($app_users[0]->is_active=='N') echo "selected=selected" ; ?>>No</option>
			</select>
            <?php echo form_error('is_active','<span class="text-danger">','</span>'); ?> </div>
          <div class="form-group col-md-3">
            <label>Blocked</label>
			<select class="form-control form-control-line" name="is_blocked" id="is_blocked" onchange="Block()">
			    <option value="">--Select--</option>
				<option value="Y" <?php if($app_users[0]->is_blocked=='Y') echo "selected=selected" ; ?>>Yes</option>
				<option value="N" <?php if($app_users[0]->is_blocked=='N') echo "selected=selected" ; ?>>No</option>
			</select>
            <?php echo form_error('is_blocked','<span class="text-danger">','</span>'); ?> </div>
			
		
		  <div class="form-group col-md-3" id="block_reason_div">
            <label>Block Reason</label>
            <input type="text" class="form-control form-control-line" id="block_reason" name="block_reason" value="<?php echo $app_users[0]->block_reason; ?>">
            <?php echo form_error('block_reason','<span class="text-danger">','</span>'); ?> 
			</div>
       
        </div>
		<div class="clr"></div>
        <div class="box-footer">
          <button type="submit" class="btn btn-success">Edit</button>
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
  </script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>

var j = jQuery.noConflict(); 
j(function() {

<?php if($app_users[0]->is_blocked=='Y'){ ?>
		j("#block_reason_div").show();
<?php } else { ?>
		j("#block_reason_div").hide();
<?php } ?>

  j("#myFrm").validate({
	rules: {
		/*app_username: {
			required: true,
			remote: {
			url: "<?php// echo base_url()?>app_users/username_check",
			type: "post"
			}
		 },*/
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
		}
		
	},

  messages: {
		/*app_username: {
			required: "Please provide Username",
			remote: "Username already in use!"
			
		},*/
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
		app_password: {
			required: "Please provide a password",
			minlength: "Minimum input 6 characters"
		},
		conf_password: {
			required: "Please provide confirm password",
			minlength: "Minimum input 6 characters",
			equalTo: "Should be same as password"
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
	    
		j('#block_reason_div').show();
	}else{
		j('#block_reason_div').hide();
	}
 }



</script>



 
