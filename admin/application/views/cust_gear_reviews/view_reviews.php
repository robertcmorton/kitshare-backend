<!DOCTYPE html>
<html lang="en">
<style>
  span font{
    color:#990000;
  }

  span b {
    font-size: 10px;
  }
</style>
<!-- Mirrored from wrappixel.com/demos/admin-templates/material-pro/material/table-data-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Dec 2017 12:51:16 GMT -->
<head>
<!-- You can change the theme colors from here -->
<link href="css/colors/blue.css" id="theme" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="fix-header card-no-border">
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
      <h3 class="text-themecolor m-b-0 m-t-0">Gear Reviews List</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>cust_gear_reviews">Customer Gear Reviews List</a></li>
        <li class="breadcrumb-item active">Gear Reviews List</li>
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
                    
        <div class="col-lg-12 col-xlg-12 col-md-12">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"> Owner Review</a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Renter Reviews</a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="home" role="tabpanel">
                        <div class="card-body">
                            <div class="profiletimeline">
                              <?php  if (!empty($owner_review)) {

                                      foreach ($owner_review As $value) {
                                        // print_r($value);
                               ?>          
                                  <div class="sl-item">
                                    <div class="sl-left"> <img src=" <?php echo $value['user_profile_picture_link_given_by']; ?>" alt="user" class="img-circle" /> </div>
                                    <div class="sl-right">
                                        <div> <a href="#" class="link"><?php echo $value['app_user_first_name_given_by'] .' '.$value['app_user_last_name_given_by']; ?></a> <span class="sl-date"><?php echo $value['cust_gear_review_date'] ;?></span>
                                         <p> 
                                          <?php
                                              if ($value['star_rating'] != 0 ) {
                                                 
                                                for ($i=0; $i < $value['star_rating']; $i++) { ?>
                                                     <i class="fa fa-star" style="color: goldenrod;"></i>                        
                                               <?php }}else{

                                          ?>      
                                          <i class="fa fa-star" ></i>                        
                                          <i class="fa fa-star" ></i>                        
                                          <i class="fa fa-star" ></i>                        
                                          <i class="fa fa-star" ></i>                        
                                          <i class="fa fa-star" ></i>                        

                                          <?php      }
                                               ?>

                                          </p>     
                                            <div class="m-t-20 row">
                                                <div class="col-md-9 col-xs-12">
                                                    <p><?php echo $value['cust_gear_review_desc'] ?> </p> </div>
                                            </div>
                                            <!-- <div class="like-comm m-t-20"> <a href="javascript:void(0)" class="link m-r-10">2 comment</a> <a href="javascript:void(0)" class="link m-r-10"><i class="fa fa-heart text-danger"></i> 5 Love</a> </div> -->
                                        </div>
                                    </div>
                                  </div>

                                   
                              <?php        }
                                # code...
                              }?>
                                
                            </div>
                        </div>
                    </div>
                    <!--second tab-->
                    <div class="tab-pane" id="profile" role="tabpanel">
                        <div class="card-body">
                           <div class="profiletimeline">
                              <?php  if (!empty($renter_review)) {

                                      foreach ($renter_review As $value) {
                                        // print_r($value);
                               ?>          
                                  <div class="sl-item">
                                    <div class="sl-left"> <img src=" <?php echo $value['user_profile_picture_link_given_by']; ?>" alt="user" class="img-circle" /> </div>
                                    <div class="sl-right">
                                        <div> <a href="#" class="link"><?php echo $value['app_user_first_name_given_by'] .' '.$value['app_user_last_name_given_by']; ?></a> <span class="sl-date"><?php echo $value['cust_gear_review_date'] ;?></span>
                                         <p> 
                                          <?php
                                              if ($value['star_rating'] != 0 ) {
                                                 
                                                for ($i=0; $i < $value['star_rating']; $i++) { ?>
                                                     <i class="fa fa-star" style="color: goldenrod;"></i>                        
                                               <?php }}else{

                                          ?>      
                                          <i class="fa fa-star" ></i>                        
                                          <i class="fa fa-star" ></i>                        
                                          <i class="fa fa-star" ></i>                        
                                          <i class="fa fa-star" ></i>                        
                                          <i class="fa fa-star" ></i>                        

                                          <?php      }
                                               ?>

                                          </p>     
                                            <div class="m-t-20 row">
                                                <div class="col-md-9 col-xs-12">
                                                    <p><?php echo $value['cust_gear_review_desc'] ?> </p> </div>
                                            </div>
                                            <!-- <div class="like-comm m-t-20"> <a href="javascript:void(0)" class="link m-r-10">2 comment</a> <a href="javascript:void(0)" class="link m-r-10"><i class="fa fa-heart text-danger"></i> 5 Love</a> </div> -->
                                        </div>
                                    </div>
                                  </div>

                                   
                              <?php        }
                                # code...
                              }?>
                                
                            </div>   
                         
                            
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
                    
    </div>
 
  <!-- /.col -->
  <!-- /.row -->
  </section>
  <!-- /.content -->
</div>