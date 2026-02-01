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
	#active {
	  border: none;
	  border-radius: 0px;
	  padding-left: 0px;
	  border-bottom: 1px solid #d9d9d9; }
	#email {
	  border: none;
	  border-radius: 0px;
	  padding-left: 0px;
	  border-bottom: 1px solid #d9d9d9; }
	
	.display_n {
	 display: none;
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
  <div class="col-md-12 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Change Password</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <!--<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>users">App Users List</a></li>
      <li class="breadcrumb-item active">Modify App User</li>-->
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
      <h4 class="card-title">Change Password</h4>
	  <?php if($this->session->flashdata('success')!=''){ ?>
		<?php echo $this->session->flashdata('success');?>
		<?php } ?>
      <form class="form-material m-t-40" action="<?php echo base_url();?>change_password/save_changes" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="form-group col-md-4">
            <label for="exampleInputEmail1">Old Password:</label>
            <input type="password" name="oldPass" id="oldPass" placeholder="Old Password" class="form-control" value="<?php echo set_value('oldPass'); ?>"  />
            <?php echo form_error('oldPass','<span class="text-danger">','</span>'); ?>
          </div>
		</div>
		 <div class="row">
          <div class="form-group col-md-4">
            <label>New Password</label>
            <input type="password" class="form-control" name="password" id="password" value="<?php echo set_value('password'); ?>" placeholder="New Password">
            <?php echo form_error('password','<span class="text-danger">','</span>'); ?>
          </div>
		  </div>
		  <div class="row">
		  <div class="form-group col-md-4">
            <label>Confirm Password</label>
            <input type="password" class="form-control" name="cnfpwd" id="cnfpwd" value="<?php echo set_value('cnfpwd'); ?>" placeholder="Confirm Password">
            <?php echo form_error('cnfpwd','<span class="text-danger">','</span>'); ?>
          </div>
		  </div>
		  <div class="box-footer">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
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
<!--<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
	var j = jQuery.noConflict(); 

    j(function() {
      j("#myFrm").validate({
        rules: {
           
		 username: {
					required: true,
					remote: {
                    url: "<?php echo base_url()?>users/username_check_edit/<?php echo $user[0]->app_user_id; ?>",
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
  </script>-->
