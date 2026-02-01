<!DOCTYPE html>
<html lang="en">
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

  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Update User </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url();?>home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url();?>users">Users</a></li>
      <li class="active">Update</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-8">
        <!-- /.box -->
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Update </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body pad">
            <form action="<?php echo base_url();?>users/update" method="post" id="myFrm">
              
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Username</label>
                    <input type="hidden" name="app_user_id" value="<?php echo $this->uri->segment(3);?>" />
                    <input type="text" placeholder="Username" name="username" id="username"  class="form-control" value="<?php echo $user[0]->app_user_name; ?>">
                  </div>
                </div>
                <div class="clr"></div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group mbr">
                    <label for="exampleInputEmail1">Password</label>
                    <?php $password= $this->common_model->base64De(2,$user[0]->app_user_pwd) ?>
                    <input type="password" placeholder="Password" name="password" id="password"   class="form-control" value="<?php echo $password; ?>">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Confirm Password</label>
                    <input type="password" placeholder="Confirm Password" name="cnfpwd" id="cnfpwd"  class="form-control" value="<?php echo $password; ?>">
                  </div>
                </div>
                <div class="clr"></div>
              </div>
			  <div class="row">
                <div class="col-sm-6">
                  <div class="form-group mbr">
                    <label for="exampleInputEmail1">Status</label>
                    <select name="active" id="active" class="form-control">
                <option value="Y" <?php if($user[0]->active=='Y') echo "selected='selected'"; ?>>Yes</option>
                <option value="N" <?php if($user[0]->active=='N') echo "selected='selected'"; ?>>No</option>
              </select>
                  </div>
                </div>
                
                <div class="clr"></div>
              </div>
			  
              <div class="form-group">
                <label for="exampleInputEmail1">Add Privilege:</label>
                <br />
                <div class="col-sm-3">
                  <div class="form-group">
                    <label >Add</label>
                    <br />
                    <input type="radio" name="pre_add" id="optionsRadios4" value="Y" <?php if ($user[0]->pre_add == 'Y') echo "checked='checked'"; ?>>
                    Yes
                    <input type="radio" name="pre_add" id="optionsRadios5" value="N" <?php if ($user[0]->pre_add == 'N') echo "checked='checked'"; ?>>
                    No </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Edit</label>
                    <br />
                    <input type="radio" name="pre_edit" id="optionsRadios4" value="Y" <?php if ($user[0]->pre_edit == 'Y') echo "checked='checked'"; ?>>
                    Yes
                    <input type="radio" name="pre_edit" id="optionsRadios5" value="N" <?php if ($user[0]->pre_edit == 'N') echo "checked='checked'"; ?>>
                    No </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">View</label>
                    <br />
                    <input type="radio" name="pre_view" id="optionsRadios4" value="Y" <?php if ($user[0]->pre_view == 'Y') echo "checked='checked'"; ?> >
                    Yes
                    <input type="radio" name="pre_view" id="optionsRadios5" value="N" <?php if ($user[0]->pre_view == 'N') echo "checked='checked'"; ?>>
                    No </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Delete</label>
                    <br />
                    <input type="radio" name="pre_delete" id="optionsRadios4" value="Y" <?php if ($user[0]->pre_delete == 'Y') echo "checked='checked'"; ?>>
                    Yes
                    <input type="radio" name="pre_delete" id="optionsRadios5" value="N" <?php if ($user[0]->pre_delete == 'N') echo "checked='checked'"; ?>>
                    No </div>
                </div>
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Edit</button>
              </div>
              
            </form>
          </div>
        </div>
      </div>
      <!-- /.col-->
    </div>
    <!-- ./row -->
  </section>
  <!-- /.content -->
</div>
  <!-- ============================================================== -->
  <!-- End Page wrapper  -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
