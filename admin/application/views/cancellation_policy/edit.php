<!DOCTYPE html>
<html lang="en">
<style>
span font{
	color:#990000;
}

span b {
	font-size: 10px;
}
.morecontent span {
    display: none;
}
.morelink {
    display: block;
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
      <h3 class="text-themecolor m-b-0 m-t-0">Edit</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>cancellation_policy">Cancellation Policy</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- Start Page Content -->
  <!-- ============================================================== -->
   <form name="bulk_action_form" action="<?php echo base_url();?>cancellation_policy/update" method="post" enctype="multipart/form-data"/>
   <input type="hidden" name="user_gear_cancel_policy_id" value="<?php echo $result[0]->user_gear_cancel_policy_id;?>" />
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Cancellation Policy</h4>
          <div class="box-header with-border"> <?php if($this->session->flashdata('success')!=''){ ?>
		<?php echo $this->session->flashdata('success');?>
		<?php } ?></div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-2">&nbsp;Cancel Policy</div>
                <div class="col-md-1">:</div>
                <div class="col-md-9">
                  <p><textarea name="user_gear_cancel_policy" id="user_gear_cancel_policy" placeholder="Policy"  rows="20" cols="80" class="form-control" required><?php echo $result[0]->user_gear_cancel_policy ;?></textarea></p>
                </div>
              </div>
			   <div class="clr"></div>
              <div class="row">
                <div class="col-md-2">&nbsp;Cancel Price</div>
                <div class="col-md-1">:</div>
                <div class="col-md-9">
                  <p><input type="text" name="user_gear_cancel_price" id="user_gear_cancel_price" placeholder="Cancel Price" class="form-control" value="<?php echo $result[0]->user_gear_cancel_price;?>"  required/>
                   
                  </p>
                </div>
              </div>
			   <div class="clr"></div>
			  <div class="row">
                <div class="col-md-2">&nbsp;Status</div>
                <div class="col-md-1">:</div>
                <div class="col-md-9">
                  <p>
				   <select class="form-control select2" name="is_active" id="is_active" style="width:100%;" >
                  <option value="Y" <?= ($result[0]->is_active=='Y')?'selected="selected"':''?> >Active</option>
                  <option value="N" <?= ($result[0]->is_active=='N')?'selected="selected"':''?>>Inactive</option>
                </select> 
                   
                  </p>
                </div>
              </div>
			   <div class="clr"></div>
			   </br>
			  <div class="row">
                <div class="col-md-2">&nbsp;</div>
                <div class="col-md-1"></div>
                <div class="col-md-9">
				
                  <p>
				     <button type="submit" class="btn btn-success">Edit</button>
                   
                  </p>
				  
                </div>
              </div>
			  </br>
              
              <div class="clr"></div>
            </div>
          </div>
          <!-- /.mail-box-messages -->
        </div>
        <div class="clr"></div>
		
        
        
    <!-- /. box -->
	
  </div>
  <!-- /.col -->
  <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
</div>
</form>
</div>
</div>
</div>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script>
  $(function () {
    CKEDITOR.replace('user_gear_cancel_policy');
    $(".textarea").wysihtml5();
  });
</script>