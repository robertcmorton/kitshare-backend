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
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">Modify App User</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>users">App Users List</a></li>
                            <li class="breadcrumb-item active">Modify App User</li>
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
                                <form class="form-material m-t-40" action="<?php echo base_url();?>users/update" method="post" enctype="multipart/form-data">
                                    <div class="row">
										<div class="form-group col-md-4">
										  <label for="exampleInputEmail1">Username:</label>
										   <input type="hidden" name="app_user_id" id="app_user_id" value="<?php echo $user[0]->app_user_id; ?>"  />
										  <input type="text" name="username" id="username" placeholder="Username" class="form-control" value="<?php echo $user[0]->app_user_name; ?>"  />
										  <label for="username" class="error" style="color:#FF0000; display:none;"></label>
										</div>
									  
									  <div class="form-group col-md-4">
                                        <label>Email</label>
                                        <input type="email" name="email" id="email" placeholder="Email" class="form-control" value="<?php echo $user[0]->app_user_email; ?>"  />
										 <label for="email" class="error" style="color:#FF0000; display:none;"></label></div>
										
									  
									
										<div class="form-group col-md-4">
										  <label for="exampleInputEmail1">Password:</label>
										  <?php $password= $this->common_model->base64De(2,$user[0]->app_user_pwd) ?>
										  <input type="password" name="password" id="password" placeholder="Password" class="form-control" value="<?php echo $password; ?>" />
										  <label for="password" class="error" style="color:#FF0000; display:none;"></label>
										</div>
										<div class="form-group col-md-4">
										  <label for="exampleInputEmail1">Confirm Password:</label>
										  <input type="password" name="cnfpwd" id="cnfpwd" placeholder="Confirm Password" class="form-control" value="<?php echo $password; ?>" />
										  <label for="cnfpwd" class="error" style="color:#FF0000; display:none;"></label>
										</div>
                                        
                                        <div class="col-sm-4 col-md-4">
										<div class="form-group">
										  <label for="exampleInputEmail1">Active:</label>
										  <select class="form-control select2" name="active" id="active" style="width:100%;" >
											<option value="Y"  <?php if ($user[0]->active == 'Y') echo "selected='selected'"; ?>>Active</option>
											<option value="N"  <?php if ($user[0]->active == 'N') echo "selected='selected'"; ?>>Inactive</option>
										  </select>
										</div>
									  </div>
                                      </div>
                                        
									  <div class="clr"></div>
									
									<input type="checkbox" id="showpass" onclick="myFunction()" class="filled-in chk-col-blue display_n"><label for="showpass">Show Password</label><br/>
									<div class="row">
									  
									  <div class="clr"></div>
									</div>
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
  </script>