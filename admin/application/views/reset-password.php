<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from wrappixel.com/demos/admin-templates/material-pro/material/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Dec 2017 12:51:28 GMT -->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- Favicon icon -->
<title>Admin | Log in</title>
<!-- Bootstrap Core CSS -->
<link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">
<!-- You can change the theme colors from here -->
<link href="<?php echo base_url();?>assets/css/colors/blue.css" id="theme" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
  <svg class="circular" viewBox="25 25 50 50">
    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
  </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<section id="wrapper">
  <div class="login-register" style="background-image: url(<?php echo base_url();?>assets/images/black-and-white-2876387_1920.jpg);">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="headings12"> Administrator's Control Panel </div>
        </div>
      </div>
    </div>
    <div class="login-box card">
      <div class="card-body">
	  <?php echo  $this->session->flashdata('success'); ?>
        <form class="form-horizontal form-material" id="loginform" action="<?php echo base_url() ?>forgot_password/change_password" method="post">
          <h3>Change Password</h3>
          <div class="form-group ">
            <div class="col-xs-12">
               <input class="form-control" type="password" name="password" placeholder="Password" required>
			  <?php echo form_error('password','<span class="text-danger">','</span>'); ?>
            </div>
          </div>
		  <div class="form-group ">
            <div class="col-xs-12">
               <input class="form-control" type="password" name="conf_password" placeholder="Confirm Password" required>
			  <?php echo form_error('conf_password','<span class="text-danger">','</span>'); ?>
            </div>
          </div>
          <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
              <button class="btn btn-info btn-lg btn-block text-uppercase">Reset</button>
            </div>
          </div>
          
          
        </form>
        
      </div>
    </div>
  </div>
</section>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="<?php echo base_url();?>assets/js/waves.js"></script>
<!--Menu sidebar -->
<script src="<?php echo base_url();?>assets/js/sidebarmenu.js"></script>
<!--stickey kit -->
<script src="<?php echo base_url();?>assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
<!--Custom JavaScript -->
<script src="<?php echo base_url();?>assets/js/custom.min.js"></script>
<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script src="<?php echo base_url();?>assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
<!--<script type="text/javascript" src="<?php //echo base_url() ?>assets/js/jquery.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>-->
<!--<script>
   var j = jQuery.noConflict(); 
    j(function() {
      j("#myFrm").validate({
        rules: {
           
            username: {
                required: true,
              
            },
			admin_password: {
					required: true,
					
				}
           
        },
      messages: {
              
           username: {
					required: "Please provide your username",
				},
				admin_password: {
					required: "Please provide your password",
					
				},
            
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
    
  </script>-->
</body>
<!-- Mirrored from wrappixel.com/demos/admin-templates/material-pro/material/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Dec 2017 12:51:29 GMT -->
</html>
