<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from wrappixel.com/demos/admin-templates/material-pro/material/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Dec 2017 12:49:35 GMT -->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Join the camera sharing community! Rent professional film and photogrpahic equipment in your local area">
<meta name="author" content="">
<!-- Favicon icon -->
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>assets/images/favicon.png">
<title>Kitshare | The Camera Sharing Community</title>
<!-- Bootstrap Core CSS -->
<link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/html5-editor/bootstrap-wysihtml5.css" />
<!-- chartist CSS -->
<link href="<?php echo base_url();?>assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
<!--This page css - Morris CSS -->
<link href="<?php echo base_url();?>assets/plugins/c3-master/c3.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">
<!-- You can change the theme colors from here -->
<link href="<?php echo base_url();?>assets/css/colors/blue.css" id="theme" rel="stylesheet">
<script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="fix-header fix-sidebar card-no-border">
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
<div id="main-wrapper">
<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<header class="topbar">
  <nav class="navbar top-navbar navbar-expand-md navbar-light">
    <!-- ============================================================== -->
    <!-- Logo -->
    <!-- ============================================================== -->
    <div class="navbar-header"> <a class="navbar-brand" href="index.html">
      <!-- Logo icon -->
      <b>
      <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
      <!-- Dark Logo icon -->
      <img src="<?php echo base_url();?>assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
      <!-- Light Logo icon -->
      <img src="<?php echo base_url();?>assets/images/logo-light-icon.png" alt="homepage" class="light-logo" /> </b>
      <!--End Logo icon -->
      <!-- Logo text -->
      <span>
      <!-- dark Logo text -->
      <img src="<?php echo base_url();?>assets/images/logo-text.png" alt="homepage" class="dark-logo" />
      <!-- Light Logo text -->
      <img src="<?php echo base_url();?>assets/images/logo-light-text.png" class="light-logo" alt="homepage" /></span> </a> </div>
    <!-- ============================================================== -->
    <!-- End Logo -->
    <!-- ============================================================== -->
    <div class="navbar-collapse">
      <!-- ============================================================== -->
      <!-- toggle and nav items -->
      <!-- ============================================================== -->
      <ul class="navbar-nav mr-auto mt-md-0">
        <!-- This is  -->
        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
        <!-- ============================================================== -->
        <!-- Search -->
        <!-- ============================================================== -->
        <!--<li class="nav-item hidden-sm-down search-box"> <a class="nav-link hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
          <form class="app-search">
            <input type="text" class="form-control" placeholder="Search & enter">
            <a class="srh-btn"><i class="ti-close"></i></a>
          </form>
        </li>-->
        <!-- ============================================================== -->
        <!-- Messages -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- End Messages -->
        <!-- ============================================================== -->
      </ul>
      <!-- ============================================================== -->
      <!-- User profile and search -->
      <!-- ============================================================== -->
      <ul class="navbar-nav my-lg-0">
        <!-- ============================================================== -->
        <!-- Comment -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- End Comment -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Messages -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- End Messages -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Profile -->
        <!-- ============================================================== -->
        <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo base_url();?>assets/images/users/1.jpg" alt="user" class="profile-pic" /></a>
          <div class="dropdown-menu dropdown-menu-right scale-up">
            <ul class="dropdown-user">
              <li>
                <div class="dw-user-box">
                  <!--<div class="u-img"><img src="../assets/images/users/1.jpg" alt="user"></div>-->
                  <div class="u-text">
                    <h4><?php echo $this->session->userdata('ADMIN_USERNAME'); ?></h4>
                    <p class="text-muted"><?php echo  $this->session->userdata('ADMIN_EMAIL_ID'); ?></p>
                    <a href="<?php echo base_url() ?>users/edit/<?php  echo  $this->session->userdata('ADMIN_ID') ; ?>" class="btn btn-rounded btn-danger btn-sm">View Profile</a></div>
                </div>
              </li>
              <li role="separator" class="divider"></li>
              <li><a href="<?php echo base_url() ?>change_password"><i class="fa fa-key" aria-hidden="true"></i>&nbsp;Change Password</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="<?php echo base_url();?>logout"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
          </div>
        </li>
        <!-- ============================================================== -->
        <!-- Language -->
        <!-- ============================================================== -->
      </ul>
    </div>
  </nav>
</header>
<!-- ============================================================== -->
<!-- End Topbar header -->
<!-- ============================================================== -->

