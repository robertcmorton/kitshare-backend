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

img {
    border-radius: 50%;
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
        <h3 class="text-themecolor m-b-0 m-t-0">Profile</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo base_url() ?>home">Dashboard</a></li>
		  <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_users">App Users List</a></li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->
    <div class="row">
      <!-- Column -->
      <div class="col-lg-4 col-xlg-3 col-md-5">
	  
        <div class="card">
          <div class="card-body">
            <center class="m-t-30">
			<?php echo $this->session->flashdata('success'); ?>
              <!--<img src="../assets/images/users/5.jpg" class="img-circle" width="150" />-->
              <?php if($app_users[0]->user_profile_picture_link==''){ ?>
              <a href="#" data-toggle="modal" data-target="#myModal"><img src="<?php echo base_url(); ?>assets/images/download.jpg" width="150"/></a>
              <?php } else { ?>
              <a href="#" data-toggle="modal" data-target="#myModal"><img src="<?php echo $app_users[0]->user_profile_picture_link;?>" width="150" /></a>
              <?php } ?>
              <h4 class="card-title m-t-10"><?php echo ucfirst(strtolower($app_users[0]->app_user_first_name)) ; ?> <?php echo ucfirst(strtolower($app_users[0]->app_user_last_name)) ; ?></h4>
              <h6 class="card-subtitle"><?php echo $app_users[0]->app_username ; ?></h6>
            </center>
          </div>
          <div>
            <hr>
          </div>
          <div class="card-body"> <small class="text-muted">Email address </small>
            <h6><?php echo $app_users[0]->primary_email_address; ?></h6>
            <small class="text-muted p-t-30 db">Phone</small>
            <h6><?php echo $app_users[0]->primary_mobile_number; ?></h6>
            <small class="text-muted p-t-30 db">Address</small>
            <h6>71 Pilgrim Avenue Chevy Chase, MD 20815</h6>
             
            <small class="text-muted p-t-30 db">Social Profile</small> <br/>
            <a href="<?php echo $app_users[0]->facebook_link; ?>" class="btn btn-circle btn-secondary"><i class="fa fa-facebook"></i></a> <a href="<?php echo $app_users[0]->twitter_link; ?>" class="btn btn-circle btn-secondary"><i class="fa fa-twitter"></i></a> <a href="<?php echo $app_users[0]->youtube_link; ?>" class="btn btn-circle btn-secondary"><i class="fa fa-youtube"></i></a> </div>
        </div>
      </div>
      <!-- Column -->
      <!-- Column -->
      <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs profile-tab" role="tablist">
            <!-- <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Timeline</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Profile</a> </li>-->
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Details</a> </li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active" id="settings" role="tabpanel">
              <div class="card-body">
                <form class="form-horizontal form-material" id="myFrm" action="<?php echo base_url();?>app_users/update" method="post" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Username</label>
						 <input type="hidden" name="app_user_id" value="<?php echo $app_users[0]->app_user_id ; ?>">
						 <input type="hidden" name="app_password" value="<?php echo $app_users[0]->app_password ; ?>">
						 <input type="hidden" name="conf_password" value="<?php echo $app_users[0]->app_password ; ?>">
                        <input type="text" class="form-control form-control-line" id="app_username" name="app_username" value="<?php echo $app_users[0]->app_username ; ?>">                   <label for="app_username" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
						<?php echo form_error('app_username','<span class="text-danger">','</span>'); ?>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control form-control-line" id="app_user_first_name"  name="app_user_first_name" value="<?php echo $app_users[0]->app_user_first_name ; ?>">
						<label for="app_user_first_name" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
						<?php echo form_error('app_user_first_name','<span class="text-danger">','</span>'); ?>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Lastname</label>
                        <input type="text" class="form-control form-control-line" id="app_user_last_name" name="app_user_last_name" value="<?php echo $app_users[0]->app_user_last_name ; ?>">
                        <label for="app_user_last_name" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('app_user_last_name','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
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
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Date Of Birth</label>
                        <input type="date" class="form-control form-control-line" id="user_birth_date"  name="user_birth_date" value="<?php echo $app_users[0]->user_birth_date; ?>">
                        <?php echo form_error('user_birth_date','<span class="text-danger">','</span>'); ?> </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Unique Id</label>
                        <input type="text" class="form-control form-control-line" id="user_unique_id_number" name="user_unique_id_number" value="<?php echo $app_users[0]->user_unique_id_number; ?>" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Australian Business Number</label>
                        <input type="text" class="form-control form-control-line" id="australian_business_number"  name="australian_business_number" value="<?php echo $app_users[0]->australian_business_number; ?>">
                        <?php echo form_error('australian_business_number','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Mobile No.#1</label>
                        <input type="text" class="form-control form-control-line" id="primary_mobile_number"  name="primary_mobile_number" value="<?php echo $app_users[0]->primary_mobile_number; ?>">
                        <label for="primary_mobile_number" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('primary_mobile_number','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Mobile No.#2</label>
                        <input type="text" class="form-control form-control-line" id="additional_mobile_number"  name="additional_mobile_number" value="<?php echo $app_users[0]->additional_mobile_number; ?>">
                        <?php echo form_error('additional_mobile_number','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Primary Email Address</label>
                        <input type="primary_email_address" class="form-control form-control-line" id="primary_email_address"  name="primary_email_address" value="<?php echo $app_users[0]->primary_email_address; ?>">
                        <label for="primary_email_address" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('primary_email_address','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Email Address #1</label>
                        <input type="text" class="form-control form-control-line" id="additional_email_address_1"  name="additional_email_address_1" value="<?php echo $app_users[0]->additional_email_address_1; ?>">
                        <label for="additional_email_address_1" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('additional_email_address_1','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Email Address #2</label>
                        <input type="text" class="form-control form-control-line" id="additional_email_address_2"  name="additional_email_address_2" value="<?php echo $app_users[0]->additional_email_address_2; ?>">
                        <label for="additional_email_address_2" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('additional_email_address_2','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Email Address #3</label>
                        <input type="text" class="form-control form-control-line" id="additional_email_address_3"  name="additional_email_address_3" value="<?php echo $app_users[0]->additional_email_address_3; ?>">
                        <label for="additional_email_address_3" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('additional_email_address_3','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Signup Type</label>
                        <select type="text" class="form-control form-control-line" id="user_signup_type" name="user_signup_type" >
							<option value="">--SELECT SIGNUP--</option>
							<option value="GM" <?php if($app_users[0]->user_signup_type == 'GM'){ echo 'selected';  } ?> >Gmail</option>
							<option value="FB" <?php if($app_users[0]->user_signup_type == 'FB'){ echo 'selected';  } ?> >Facebook</option>
							<option value="EM" <?php if($app_users[0]->user_signup_type == 'EM'){ echo 'selected';  } ?> >Email</option>
						</select>	
                        <?php echo form_error('user_signup_type','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                   <!-- <div class="col-md-4">
                      <div class="form-group">
                        <label>Account Type</label>
                        <input type="text" class="form-control form-control-line" id="user_account_type" name="user_account_type" value="<?php echo $app_users[0]->user_account_type; ?>">
                        <?php echo form_error('user_account_type','<span class="text-danger">','</span>'); ?> </div>
                    </div> -->
                  </div>
				  
		<?php if (count($app_users_address)> 0 ) { 
            $i =0; 
            foreach ($app_users_address as $value) {
            
           ?>
		 <div class="row" >
			<div class="col-md-12">
				<?php  if($i == '0'){?>
				<h2>Primary Address</h2>
				<?php }else{?>
				<h2>Business Address</h2>
				<?php }?>
			</div>
		</div >
		<div class="row" >
			
		  
       <input type="hidden" class="form-control form-control-line" id="street_address_line1" name="user_address_id[]" value="<?php  if(!empty($app_users_address)){ echo $value->user_address_id; }  ?>">
      <div class="form-group col-md-6">
        <label>Suite or Apartment Number</label>
        <input type="text" class="form-control form-control-line" id="street_address_line1" name="street_address_line1[]" value="<?php  if(!empty($app_users_address)){ echo $value->street_address_line1; }  ?>">
        <?php echo form_error('street_address_line1','<span class="text-danger">','</span>'); ?>
      </div>
      <div class="form-group col-md-6">
        <label>Street Address </label>
        <input type="text" class="form-control form-control-line" id="street_address_line2" name="street_address_line2[]" value="<?php  if(!empty($app_users_address)){  echo $value->street_address_line2; }  ?>">
        <?php echo form_error('street_address_line2','<span class="text-danger">','</span>'); ?>
      </div>
      
      
      <div class="form-group col-md-6">
        <label>Country</label>
        <select type="text" class="form-control form-control-line ks_country_id_<?php echo $j =$i+1 ; ?> " id="ks_country_id_<?php echo $j =$i+1 ; ?>" name="ks_country_id[]" onchange="changeStates(<?php echo $j =$i+1 ; ?>)" value="<?php echo set_value('ks_country_id'); ?>">
          <option value="" >--Select Country--</option>
          <?php if(!empty($countries)){  
                foreach($countries AS $country){  
              
          ?>
          <option value="<?php echo $country->ks_country_id; ?>"  <?php if(!empty($app_users_address)){ if($country->ks_country_id == $value->ks_country_id ){ echo "selected";} }?> ><?php echo $country->ks_country_name; ?></option>
          <?php }} ?>
        </select>
        <?php echo form_error('ks_country_id','<span class="text-danger">','</span>'); ?>
      </div>
      <div class="form-group col-md-6">
        <label>State</label>
        <select type="text" class="form-control form-control-line" id="ks_state_id" name="ks_state_id[]" onchange="changeSuburb()"  value="">
          <option value="" >--Select State--</option>
          <?php if(!empty($states)){  
                foreach($states AS $country){ 
              
          ?>
          <option value="<?php echo $country->ks_state_id; ?>"  <?php if(!empty($app_users_address)){ if($country->ks_state_id == $value->ks_state_id ){ echo "selected";} }?> ><?php echo $country->ks_state_name; ?></option>
          <?php  }} ?>
        </select>
        <?php echo form_error('ks_state_id','<span class="text-danger">','</span>'); ?>
      </div>
      <div class="form-group col-md-4">
        <label>Town/Suburb</label>
        <select type="text" class="form-control form-control-line ks_suburb_id_<?php echo $j =$i+1 ; ?>" id="ks_suburb_id" name="ks_suburb_id[]"  onchange="Changepincode(<?php echo $j =$i+1 ; ?>)" value="<?php echo set_value('ks_suburb_id'); ?>">
          <option value="" >--Select Suburb--</option>
          <?php if(!empty($states)){  
                foreach($suburbs AS $country){  
              
          ?>
          <option value="<?php echo $country->ks_suburb_id; ?>"  <?php if(!empty($app_users_address)){ if($country->ks_suburb_id == $value->ks_suburb_id ){ echo "selected";} }?> ><?php echo $country->suburb_name; ?></option>
          <?php }} ?>
        </select>
        <?php echo form_error('ks_suburb_id','<span class="text-danger">','</span>'); ?>
      </div>
      <div class="form-group col-md-4">
        <label>Postcode </label>
        <?php echo form_error('postcode','<span class="text-danger">','</span>'); ?>
        <input type="text" class="form-control form-control-line postcode_<?php echo $j =$i+1 ; ?>" id="postcode" name="postcode[]"  value="<?php   if(!empty($app_users_address)){ echo $value->postcode; } ?>">
      </div>
      <div class="form-group col-md-3">
        <label> Deafult Address </label>
        <br>
        <!--<input type="radio" class=" default_address_<?php echo $i;?>" id="default_address" name="default_address_<?php echo $i ;?>" <?php if($value->default_address== '1'){ echo  "checked" ;}  ?>  onclick= "defaultaddress(<?php echo $i; ?>)" value="<?php  echo $value->default_address; ?>">-->
        <input type="radio" name="default_address[]" <?php if($value->default_address== '1'){ echo  "checked" ;}  ?>  value="<?php  echo $value->user_address_id; ?>">
        <?php echo form_error('postcode','<span class="text-danger">','</span>'); ?>
      </div>

   
		
		</div>
		    <?php $i++; }}?> 
		 <input type="hidden" value="<?php  echo count($app_users_address);?>" id="counter" name="counter">
    <a href="javascript:void(0);" onclick="addFeatureValue('divFeatureValue','addFeatureVal1')" id="addFeatureVal1">Add More</a>
    
		 <div class="row" id="divFeatureValue" >
		 </div>
		<div class="clr"></div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Social Id</label>
                        <input type="user_social_id" class="form-control form-control-line" id="user_social_id" name="user_social_id" value="<?php echo $app_users[0]->user_social_id; ?>">
                        <?php echo form_error('user_social_id','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>User Website</label>
                        <input type="text" class="form-control form-control-line" id="user_website" name="user_website" value="<?php echo $app_users[0]->user_website; ?>">
                        <?php echo form_error('user_website','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>IMDB Link</label>
                        <input type="url" class="form-control form-control-line" id="imdb_link" name="imdb_link" value="<?php echo $app_users[0]->imdb_link; ?>">
                        <label for="imdb_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('imdb_link','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Showreel Link</label>
                        <input type="url" class="form-control form-control-line" id="showreel_link" name="showreel_link" value="<?php echo $app_users[0]->showreel_link; ?>">
                        <label for="showreel_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('showreel_link','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Instagram Link</label>
                        <input type="url" class="form-control form-control-line" id="instagram_link" name="instagram_link" value="<?php echo $app_users[0]->instagram_link; ?>">
                        <label for="instagram_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('instagram_link','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Facebook Link</label>
                        <input type="url" class="form-control form-control-line" id="facebook_link" name="facebook_link" value="<?php echo $app_users[0]->facebook_link; ?>">
                        <label for="instagram_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('facebook_link','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Vimeo Link</label>
                        <input type="url" class="form-control form-control-line" id="vimeo_link" name="vimeo_link" value="<?php echo $app_users[0]->vimeo_link; ?>">
                        <label for="vimeo_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('vimeo_link','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Youtube Link</label>
                        <input type="url" class="form-control form-control-line" id="youtube_link" name="youtube_link" value="<?php echo $app_users[0]->youtube_link; ?>">
                        <label for="youtube_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('youtube_link','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Flikr Link</label>
                        <input type="url" class="form-control form-control-line" id="flikr_link"  name="flikr_link" value="<?php echo $app_users[0]->flikr_link; ?>">
                        <label for="flikr_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('flikr_link','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Twitter Link</label>
                        <input type="url" class="form-control form-control-line" id="twitter_link" name="twitter_link" value="<?php echo $app_users[0]->twitter_link; ?>">
                        <label for="twitter_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('twitter_link','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Linkedin Llink</label>
                        <input type="text" class="form-control form-control-line" id="linkedin_link" name="linkedin_link" value="<?php echo $app_users[0]->linkedin_link; ?>">
                        <label for="linkedin_link" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
                        <?php echo form_error('linkedin_link','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Receive SMS Notification</label>
                        <select class="form-control form-control-line" name="receive_sms_notification" id="receive_sms_notification">
                          <option value="">--Select--</option>
                          <option value="Y" <?php if($app_users[0]->receive_sms_notification=='Y') echo "selected=selected" ; ?>>Yes</option>
                          <option value="N" <?php if($app_users[0]->receive_sms_notification=='N') echo "selected=selected" ; ?>>No</option>
                        </select>
                        <?php echo form_error('receive_sms_notification','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Enable Weekly/Weekend Rate</label>
                        <select class="form-control form-control-line" name="enable_weekly_rate" id="enable_weekly_rate">
                          <option value="">--Select--</option>
                          <option value="Y" <?php if($app_users[0]->enable_weekly_rate=='Y') echo "selected=selected" ; ?>>Yes</option>
                          <option value="N" <?php if($app_users[0]->enable_weekly_rate=='N') echo "selected=selected" ; ?>>No</option>
                        </select>
                        <?php echo form_error('enable_weekly_rate','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>GST Registered</label>
                        <select class="form-control form-control-line" name="registered_for_gst" id="registered_for_gst">
                          <option value="">--Select--</option>
                          <option value="Y" <?php if($app_users[0]->registered_for_gst=='Y') echo "selected=selected" ; ?>>Yes</option>
                          <option value="N" <?php if($app_users[0]->registered_for_gst=='N') echo "selected=selected" ; ?>>No</option>
                        </select>
                        <?php echo form_error('registered_for_gst','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <!--<div class="col-md-4">
                      <div class="form-group">
                        <label>Enable Weekend Rate</label>
                        <select class="form-control form-control-line" name="enable_weekend_rate" id="enable_weekend_rate">
                          <option value="">--Select--</option>
                          <option value="Y" <?php //if($app_users[0]->enable_weekend_rate=='Y') echo "selected=selected" ; ?>>Yes</option>
                          <option value="N" <?php //if($app_users[0]->enable_weekend_rate=='N') echo "selected=selected" ; ?>>No</option>
                        </select>
                        <?php //echo form_error('enable_weekend_rate','<span class="text-danger">','</span>'); ?> </div>
                    </div>-->
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Willing to Deliver</label>
                        <select class="form-control form-control-line" name="is_willing_deliver" id="is_willing_deliver">
                          <option value="">--Select--</option>
                          <option value="Y" <?php if($app_users[0]->is_willing_deliver=='Y') echo "selected=selected" ; ?>>Yes</option>
                          <option value="N" <?php if($app_users[0]->is_willing_deliver=='N') echo "selected=selected" ; ?>>No</option>
                        </select>
                        <?php echo form_error('is_willing_deliver','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Prep Space Available</label>
                        <select class="form-control form-control-line" name="is_prep_space_available" id="is_prep_space_available">
                          <option value="">--Select--</option>
                          <option value="Y" <?php if($app_users[0]->is_prep_space_available=='Y') echo "selected=selected" ; ?>>Yes</option>
                          <option value="N" <?php if($app_users[0]->is_prep_space_available=='N') echo "selected=selected" ; ?>>No</option>
                        </select>
                        <?php echo form_error('is_prep_space_available','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Active</label>
                        <select class="form-control form-control-line" name="is_active" id="is_active">
                          <option value="">--Select--</option>
                          <option value="Y" <?php if($app_users[0]->is_active=='Y') echo "selected=selected" ; ?>>Yes</option>
                          <option value="N" <?php if($app_users[0]->is_active=='N') echo "selected=selected" ; ?>>No</option>
                        </select>
                        <?php echo form_error('is_active','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Blocked</label>
                        <select class="form-control form-control-line" name="is_blocked" id="is_blocked" onchange="Block()">
                          <option value="">--Select--</option>
                          <option value="Y" <?php if($app_users[0]->is_blocked=='Y') echo "selected=selected" ; ?>>Yes</option>
                          <option value="N" <?php if($app_users[0]->is_blocked=='N') echo "selected=selected" ; ?>>No</option>
                        </select>
                        <?php echo form_error('is_blocked','<span class="text-danger">','</span>'); ?> </div>
                    </div>
					<div class="col-md-4">
                      <div class="form-group">
                        <label>Renter Type</label>
                        <select class="form-control form-control-line" name="ks_renter_type_id" id="ks_renter_type_id" >
                          <option value="">--Select--</option>
                           <?php if (!empty($rental_type)) {
                                  foreach ($rental_type as $value) {
                            ?>
                            <option value="<?php echo $value->ks_renter_type_id; ?>" <?php if($app_users[0]->ks_renter_type_id==$value->ks_renter_type_id) echo "selected=selected" ; ?>  ><?php echo $value->ks_renter_type ;?></option>
                          <?php         } } ?>
                          
                        </select>
                        <?php echo form_error('ks_renter_type_id','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                     <div class="col-md-4">
                      <div class="form-group">
                        <label>Profession</label>
                        <select class="form-control form-control-line" name="profession_type_id" id="profession_type_id" >
                          <option value="">--Select--</option>
                           <?php if (!empty($profession_types)) {
                                  foreach ($profession_types as $value) {
                            ?>
                            <option value="<?php echo $value->profession_type_id; ?>" <?php if($app_users[0]->profession_type_id==$value->profession_type_id) echo "selected=selected" ; ?>><?php echo $value->profession_name ;?></option>
                          <?php         } } ?>
                        </select>
                        <?php echo form_error('is_blocked','<span class="text-danger">','</span>'); ?> </div>
                    </div>

                    <div class="col-md-4" id="block_reason_div">
                      <div class="form-group">
                        <label>Block Reason</label>
                        <input type="text" class="form-control form-control-line" id="block_reason" name="block_reason" value="<?php echo $app_users[0]->block_reason; ?>">
                        <?php echo form_error('block_reason','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control form-control-line" id="user_description"  name="user_description" value="<?php echo $app_users[0]->user_description; ?>">
                        <?php echo form_error('user_description','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>About Me</label>
                        <input type="text" class="form-control form-control-line" id="about_me"  name="about_me" value="<?php echo $app_users[0]->about_me; ?>">
                        <?php echo form_error('about_me','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-5">
                      <div class="form-group">
                        <label>Australia Post Digital ID</label>
                        <select type="text" class="form-control form-control-line" id="aus_post_verified "  name="aus_post_verified  " value="<?php //echo $ ; ?>" disabled>
                          <option>--Select Option--</option>
                          <option value="Y" <?php if($app_users[0]->aus_post_verified == 'Y'){ echo "selected";} ?>  >Approved</option>
                          <option value="N" <?php if($app_users[0]->aus_post_verified == 'N'){ echo "selected";} ?>  >Not Approved Yet</option>
                        </select> 

                        <?php echo form_error('aus_post_verified','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Phone number</label>
                        <select type="text" class="form-control form-control-line" id="mobile_number_verfiy "  name="mobile_number_verfiy  " value="<?php //echo $ ; ?>" disabled>
                          <option>--Select Option--</option>
                          <option value="1" <?php if($app_users[0]->mobile_number_verfiy == '1'){ echo "selected";} ?>  >Verified</option>
                          <option value="0" <?php if($app_users[0]->mobile_number_verfiy == '0'){ echo "selected";} ?>  >Not Verified Yet</option>
                        </select> 

                        <?php echo form_error('aus_post_verified','<span class="text-danger">','</span>'); ?> </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group">
                      <div class="col-sm-12">
                        <button type="submit"  class="btn btn-success">Update Profile</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Column -->
    </div>
    <!-- Row -->
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    
    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
  </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
	  <h4 class="modal-title">Upload Image</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <form class="form-inline" data-toggle="validator" role="form" method="post" action="<?php echo base_url();?>app_users/upload_image" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
		  <input type="hidden" name="app_user_id" value="<?php echo $app_users[0]->app_user_id ; ?>" >
            <input type="file" name="user_profile_picture_link" id="csv" required>
            </br>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--end model -- >
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
		app_username: {
			required: true,
			remote: {
			url: "<?php echo base_url()?>app_users/username_check_edit/<?php echo $app_users[0]->app_user_id; ?>",
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
// alert(count);
for (var i = 0; i <= count; i++) {
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
