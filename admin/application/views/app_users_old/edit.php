        <style>
			input {
			  border: none;
			  border-radius: 0px;
			  padding-left: 0px;
			  border-bottom: 1px solid #d9d9d9; }
		</style>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dropify/dist/css/dropify.min.css">
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
                        <h3 class="text-themecolor m-b-0 m-t-0">Modify User</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_users">Users List</a></li>
                            <li class="breadcrumb-item active">Modify User</li>
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
                                <h4 class="card-title">Modify</h4>
                                <form name="myUserFrm" id="myUserFrm" class="form-material m-t-40" action="<?php echo base_url();?>app_users/update" method="post" enctype="multipart/form-data"><input type="hidden" value="<?php echo $user[0]->app_user_id; ?>" name="app_user_id" id="app_user_id" />
                                    <div class="row">
									<div class="form-group col-md-6">
                                        <label>Username</label>
										<span style="color:#FF0000;">*</span>
                                        <input type="text" class="form-control form-control-line" id="app_username" placeholder="Username" name="app_username" value="<?php echo $user[0]->app_username; ?>"><?php echo form_error('app_username','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Primary Email Address</label>
										<span style="color:#FF0000;">*</span>
                                        <input type="email" class="form-control form-control-line" id="primary_email_address" placeholder="Primary Email Address" name="primary_email_address" value="<?php echo $user[0]->primary_email_address; ?>"><?php echo form_error('primary_email_address','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
										<div class="form-group col-md-6">
											<label>Password</label>
											<span style="color:#FF0000;">*</span>
											<input type="password" placeholder="Password" name="password" id="password" class="form-control" value="<?php echo$user[0]->app_password; ?>">
											<?php echo form_error('password','<span class="text-danger">','</span>'); ?> </div>
										<div class="form-group col-md-6">
											<label>Confirm Password</label>
											<span style="color:#FF0000;">*</span>
											<input type="password" placeholder="Confirm Password" name="cnfpwd" id="cnfpwd" class="form-control" value="<?php echo $user[0]->app_password; ?>"> 
											<?php echo form_error('cnfpwd','<span class="text-danger">','</span>'); ?></div>
									</div>
									<input type="checkbox" id="showpass" onclick="myFunction()" class="filled-in chk-col-blue"><label for="showpass">Show Password</label><br/>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>First Name</label>
										<span style="color:#FF0000;">*</span>
                                        <input type="text" class="form-control form-control-line" id="app_user_first_name" placeholder="First Name" name="app_user_first_name" value="<?php echo $user[0]->app_user_first_name; ?>"><?php echo form_error('app_user_first_name','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control form-control-line" id="app_user_last_name" placeholder="Last Name" name="app_user_last_name" value="<?php echo $user[0]->app_user_last_name; ?>"><?php echo form_error('app_user_last_name','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>Owner Type</label>
										<span style="color:#FF0000;">*</span>
                                        <select class="form-control valid" name="owner_type_id" id="owner_type_id">
											  <option value="">--Select Owner Type--</option>
											  <?php foreach($owner_type_id as $key=>$val){ ?>
											  <option value="<?php echo $val->owner_type_id; ?>" <?php if($val->owner_type_id==$user[0]->owner_type_id) echo "selected='selected'" ; ?>><?php echo $val->owner_type_name; ?></option>
											  <?php } ?>
											  <option value="other">Other</option>
										</select>
										<?php echo form_error('owner_type_id','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Date of Birth</label>
										<span style="color:#FF0000;">*</span>
                                        <input type="date" class="form-control form-control-line" id="user_birth_date" placeholder="Date of Birth" name="user_birth_date" value="<?php echo $user[0]->user_birth_date; ?>"><?php echo form_error('user_birth_date','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>User Unique ID Number</label>
                                        <input type="text" class="form-control form-control-line" id="user_unique_id_number" placeholder="User Unique ID Number" name="user_unique_id_number" value="<?php echo $user[0]->user_unique_id_number; ?>"><?php echo form_error('user_unique_id_number','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Australian Business Number</label>
                                        <input type="text" class="form-control form-control-line" id="australian_business_number" placeholder="Australian Business Number" name="australian_business_number" value="<?php echo $user[0]->australian_business_number; ?>"><?php echo form_error('australian_business_number','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>User Description</label>
                                        <textarea rows=5 class="form-control form-control-line" id="user_description" placeholder="User Description" name="user_description" value="<?php echo $user[0]->user_description; ?>"></textarea><?php echo form_error('user_description','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>About Me</label>
                                        <textarea rows=5 class="form-control form-control-line" id="about_me" placeholder="About Me" name="about_me" value="<?php echo $user[0]->about_me; ?>"></textarea><?php echo form_error('about_me','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>Primary Mobile Number</label>
                                        <input type="text" class="form-control form-control-line" id="primary_mobile_number" placeholder="Primary Mobile Number" name="primary_mobile_number" value="<?php echo $user[0]->primary_mobile_number; ?>"><?php echo form_error('primary_mobile_number','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Additional Mobile Number</label>
                                        <input type="text" class="form-control form-control-line" id="additional_mobile_number" placeholder="Additional Mobile Number" name="additional_mobile_number" value="<?php echo $user[0]->additional_mobile_number; ?>"><?php echo form_error('additional_mobile_number','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-4">
                                        <label>Additional Email Address 1</label>
                                        <input type="text" class="form-control form-control-line" id="additional_email_address_1" placeholder="Additional Email Address 1" name="additional_email_address_1" value="<?php echo $user[0]->additional_email_address_1; ?>"><?php echo form_error('additional_email_address_1','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-4">
                                        <label>Additional Email Address 2</label>
                                        <input type="text" class="form-control form-control-line" id="additional_email_address_2" placeholder="Additional Email Address 2" name="additional_email_address_2" value="<?php echo $user[0]->additional_email_address_2; ?>"><?php echo form_error('additional_email_address_2','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-4">
                                        <label>Additional Email Address 3</label>
                                        <input type="text" class="form-control form-control-line" id="additional_email_address_3" placeholder="Additional Email Address 3" name="additional_email_address_3" value="<?php echo $user[0]->additional_email_address_3; ?>"><?php echo form_error('additional_email_address_3','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-4">
                                        <label>User Signup Type</label>
										<span style="color:#FF0000;">*</span>
                                        <select class="form-control valid" name="user_signup_type" id="user_signup_type">
											  <option value="">--Select User Signup Type--</option>
											  <option value="FB" <?php if($user[0]->user_signup_type=="FB") echo "selected='selected'" ; ?>>Facebook</option>
											  <option value="GM" <?php if($user[0]->user_signup_type=="GM") echo "selected='selected'" ; ?>>Gmail</option>
											  <option value="EM" <?php if($user[0]->user_signup_type=="EM") echo "selected='selected'" ; ?>>Email</option>
										</select><?php echo form_error('user_signup_type','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-4">
                                        <label>User Account Type</label>
										<span style="color:#FF0000;">*</span>
                                        <select class="form-control valid" name="user_account_type" id="user_account_type">
											  <option value="">--Select User Account Type--</option>
											  <option value="IN" <?php if($user[0]->user_account_type=="IN") echo "selected='selected'" ; ?>>Individual</option>
											  <option value="CO" <?php if($user[0]->user_account_type=="CO") echo "selected='selected'" ; ?>>Company</option>
											  <option value="RH" <?php if($user[0]->user_account_type=="RH") echo "selected='selected'" ; ?>>Rental House</option>
										</select><?php echo form_error('user_account_type','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-4">
                                        <label>User Social ID</label>
                                        <input type="text" class="form-control form-control-line" id="user_social_id" placeholder="User Social ID" name="user_social_id" value="<?php echo $user[0]->user_social_id; ?>"><?php echo form_error('user_social_id','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label for="input-file-now">Upload User Profile Picture</label>
                                        <input type = "file" id="input-file-now" name = "user_profile_picture_link" size = "20"  accept='image/*' /><?php echo form_error('user_profile_picture_link','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>User Website</label>
                                        <input type="text" class="form-control form-control-line" id="user_website" placeholder="User Website" name="user_website" value="<?php echo $user[0]->user_website; ?>"><?php echo form_error('user_website','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>IMDb Link</label>
                                        <input type="text" class="form-control form-control-line" id="imdb_link" placeholder="IMDb Link" name="imdb_link" value="<?php echo $user[0]->imdb_link; ?>"><?php echo form_error('imdb_link','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Showreel Link</label>
                                        <input type="text" class="form-control form-control-line" id="showreel_link" placeholder="Showreel Link" name="showreel_link" value="<?php echo $user[0]->showreel_link; ?>"><?php echo form_error('showreel_link','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>Instagram Link</label>
                                        <input type="text" class="form-control form-control-line" id="instagram_link" placeholder="Instagram Link" name="instagram_link" value="<?php echo $user[0]->instagram_link; ?>"><?php echo form_error('instagram_link','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Facebook Link</label>
                                        <input type="text" class="form-control form-control-line" id="facebook_link" placeholder="Facebook Link" name="facebook_link" value="<?php echo $user[0]->facebook_link; ?>"><?php echo form_error('facebook_link','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>Vimeo Link</label>
                                        <input type="text" class="form-control form-control-line" id="vimeo_link" placeholder="Vimeo Link" name="vimeo_link" value="<?php echo $user[0]->vimeo_link; ?>"><?php echo form_error('vimeo_link','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Youtube Link</label>
                                        <input type="text" class="form-control form-control-line" id="youtube_link" placeholder="Youtube Link" name="youtube_link" value="<?php echo $user[0]->youtube_link; ?>"><?php echo form_error('youtube_link','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>Flickr Link</label>
                                        <input type="text" class="form-control form-control-line" id="flikr_link" placeholder="Flickr Link" name="flikr_link" value="<?php echo $user[0]->flikr_link; ?>"><?php echo form_error('flikr_link','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Twitter Link</label>
                                        <input type="text" class="form-control form-control-line" id="twitter_link" placeholder="Twitter Link" name="twitter_link" value="<?php echo $user[0]->twitter_link; ?>"><?php echo form_error('twitter_link','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>Linkedin Link</label>
                                        <input type="text" class="form-control form-control-line" id="linkedin_link" placeholder="Linkedin Link" name="linkedin_link" value="<?php echo $user[0]->linkedin_link; ?>"><?php echo form_error('linkedin_link','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Receive SMS Notification</label>
                                        <select class="form-control valid" id="receive_sms_notification" name="receive_sms_notification">
										<option value="Y" <?php if($user[0]->receive_sms_notification=="Y") echo "selected='selected'" ; ?>>Yes</option>
										<option value="N" <?php if($user[0]->receive_sms_notification=="N") echo "selected='selected'" ; ?>>No</option>
										</select><?php echo form_error('receive_sms_notification','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>Enable Weekly Rate</label>
                                        <select class="form-control valid" id="enable_weekly_rate" name="enable_weekly_rate">
										<option value="Y" <?php if($user[0]->enable_weekly_rate=="Y") echo "selected='selected'" ; ?>>Yes</option>
										<option value="N" <?php if($user[0]->enable_weekly_rate=="N") echo "selected='selected'" ; ?>>No</option>
										</select><?php echo form_error('enable_weekly_rate','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Enable Weekend Rate</label>
                                        <select class="form-control valid" id="enable_weekend_rate" name="enable_weekend_rate">
										<option value="Y" <?php if($user[0]->enable_weekend_rate=="Y") echo "selected='selected'" ; ?>>Yes</option>
										<option value="N" <?php if($user[0]->enable_weekend_rate=="N") echo "selected='selected'" ; ?>>No</option>
										</select><?php echo form_error('enable_weekend_rate','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>Are they willing to deliver?</label>
                                        <select class="form-control valid" id="is_willing_deliver" name="is_willing_deliver">
										<option value="Y" <?php if($user[0]->is_willing_deliver=="Y") echo "selected='selected'" ; ?>>Yes</option>
										<option value="N" <?php if($user[0]->is_willing_deliver=="N") echo "selected='selected'" ; ?>>No</option>
										</select><?php echo form_error('is_willing_deliver','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Is Prep Space Available?</label>
                                        <select class="form-control valid" id="is_prep_space_available" name="is_prep_space_available">
										<option value="N" <?php if($user[0]->is_prep_space_available=="N") echo "selected='selected'" ; ?>>No</option>
										<option value="Y" <?php if($user[0]->is_prep_space_available=="Y") echo "selected='selected'" ; ?>>Yes</option>
										</select><?php echo form_error('is_prep_space_available','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div class="form material row">
									<div class="form-group col-md-6">
                                        <label>Status</label>
                                        <select class="form-control valid" id="is_active" name="is_active">
										<option value="Y" <?php if($user[0]->is_active=="Y") echo "selected='selected'" ; ?>>Active</option>
										<option value="N" <?php if($user[0]->is_active=="N") echo "selected='selected'" ; ?>>Inactive</option>
										</select><?php echo form_error('is_active','<span class="text-danger">','</span>'); ?> </div>
									<div class="form-group col-md-6">
                                        <label>Blocking Status</label>
                                        <select class="form-control valid" id="is_blocked" name="is_blocked" onchange="Block()">
										<option value="N" <?php if($user[0]->is_blocked=="N") echo "selected='selected'" ; ?>>Not blocked</option>
										<option value="Y" <?php if($user[0]->is_blocked=="Y") echo "selected='selected'" ; ?>>Blocked</option>
										</select><?php echo form_error('is_blocked','<span class="text-danger">','</span>'); ?> </div>
									</div>
									<div class="clr"></div>
									<div style="display:none;" id="block">
									<div class="form material row">
									<div class="form-group col-md-6">
										<label>Blocking Reason</label>
                                        <textarea rows=5 class="form-control form-control-line" name="block_reason" id="block_reason" value="<?php echo $user[0]->block_reason; ?>"></textarea><?php echo form_error('block_reason','<span class="text-danger">','</span>'); ?> </div>
									</div></div>
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
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
	var j = jQuery.noConflict(); 

    j(function() {
      j("#myUserFrm").validate({
        rules: {
           
		 username: {
					required: true,
					remote: {
                    url: "<?php echo base_url();?>app_users/username_check",
                    type: "post"
					}
                 },
				password: {
					required: true,
					minlength: 6
				},
				cnfpwd: {
					required: true,
					minlength: 6,
					equalTo: "#password",
				}
           
        },
      messages: {
              
				username: {
					required: "Please provide Username",
					remote: "Username already in use!"
					
				},
				password: {
					required: "Please provide a password",
					minlength: "Minimum input 6 characters"
				},
				cnfpwd: {
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

  </script>
  <script>
  	function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
  function Block()
  {
   
  	var block_status = $('#is_blocked').val();
	if(block_status=='Y'){
			 //alert('ddd');
		$('#block').show();
	}else{
		$('#block').hide();
	}
 }
  </script>