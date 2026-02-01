<style>
	#manufacturer {
	  border: none;
	  border-radius: 0px;
	  padding-left: 0px;
	  border-bottom: 1px solid #d9d9d9; }
	#status {
	  border: none;
	  border-radius: 0px;
	  padding-left: 0px;
	  border-bottom: 1px solid #d9d9d9; }
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
    <h3 class="text-themecolor m-b-0 m-t-0">Modify Manufacturer</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>manufacturers">Manufacturer List</a></li>
      <li class="breadcrumb-item active">Modify Manufacturer</li>
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
      <form class="form-material m-t-40" id="myFrm" action="<?php echo base_url();?>manufacturers/update" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputarticle">Manufacturer</label>
              <input type="hidden" class="form-control" name="manufacturer_id" id="manufacturer_id" value="<?php echo $result[0]->manufacturer_id; ?>">
              <input type="text" class="form-control" name="manufacturer" id="manufacturer" value="<?php echo $result[0]->manufacturer_name; ?>" placeholder="Manufacturer">
              <?php echo form_error('manufacturer','<span class="text-danger">','</span>'); ?>
              <label for="manufacturer" class="error" style="color:#FF0000; display:none; font-size:14px"></label>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="exampleInputImage">Status</label>
              <select class="form-control select2" name="status" id="status" style="width:100%;" value="<?php echo set_value('status'); ?>">
                <option value="Y"<?php if($result[0]->is_active=='Y') echo "selected='selected'" ; ?>>Yes</option>
                <option value="N"<?php if($result[0]->is_active=='N') echo "selected='selected'" ; ?>>No</option>
              </select>
              <?php echo form_error('status','<span class="text-danger">','</span>'); ?> </div>
          </div>
        </div>
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
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>

var j = jQuery.noConflict(); 
j(function() {
  j("#myFrm").validate({
	rules: {
		manufacturer: {
			required: true,
		},
		
	},

  messages: {
		manufacturer: {
			required: "Please provide Manufacturer",
			
			
		},
	},
	submitHandler: function(form) {
		form.submit();
	}
});
});
</script>
