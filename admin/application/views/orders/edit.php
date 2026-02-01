<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script> 

<link href="<?php  echo base_url();?>plugins/bootstrap-material-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

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
        <h3 class="text-themecolor m-b-0 m-t-0">Orders Date Update</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo base_url() ?>home">Dashboard</a></li>
		  <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Orders">Orders List</a></li>
          <li class="breadcrumb-item active">Edit Order Date</li>
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
      
      <!-- Column -->
      <!-- Column -->
      <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs profile-tab" role="tablist">
            <!-- <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Timeline</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Profile</a> </li>-->
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Order ID  <?php echo $user_data[0]->order_id ; ?></a> </li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active" id="settings" role="tabpanel">
              <div class="card-body">
                <form class="form-horizontal form-material" id="myFrm" action="<?php echo base_url();?>Orders/updateDate" method="post" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Order Requested Date</label>
            					 <input type="hidden"  value="<?php echo $user_data[0]->order_id ; ?>" name="order_id">	
                        <input type="datetime" class="form-control form-control-line" id="app_username" name="app_username" value="<?php echo $user_data[0]->gear_rent_requested_on ; ?>">
                        <label for="app_username" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>
						            <?php echo form_error('app_username','<span class="text-danger">','</span>'); ?>
                      </div>
                    </div>
                    
                  
                  </div>
                  <div class="row">
                    <div class="form-group">
                      <div class="col-sm-12">
                        <button type="submit"  class="btn btn-success">Update Order</button>
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
  
<!--end model -- >
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>plugins/bootstrap-material-datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>plugins/bootstrap-material-datetimepicker/js/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script>

var j = jQuery.noConflict(); 
    j(function() {
 j('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    forceParse: 0,
        showMeridian: 1
    });

});
</script>
